<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends ORM {  //  model_auth_user = modules:orm:classes:model:auth:user.php

    protected $_table_name = 'users';
    protected $_has_many = array(
        'user_tokens' => array('model' => 'User_Token'),
        'groups' => array(
            'model' => 'group'
            , 'through' => 'users_groups'
            , 'far_key' => 'group_id'
        ),
        'roles' => array('model' => 'Role', 'through' => 'roles_users'),
    );

    /**
     * Reset a users password using native Kohana password functions
     *
     */
    public function reset_password($user_id, $length = 6) {

        //generate a new password
        $chars = explode(",", "b,c,d,f,g,h,j,k,m,n,p,q,r,s,t,v,w,x,y,z,2,3,4,5,6,7,8,9");
        $newpass = "";
        for ($i = 0; $i < $length; $i++) {
            $ran = rand(0, count($chars) - 1);
            $newpass.= $chars[$ran];
        }

        $newpass_array = array('password' => $newpass, 'password_confirm' => $newpass); // "save_password()" requires this value in an array

        $user = ORM::factory('User', $user_id);
        $user->password = $newpass;
        $user->save();

        // this should probably be its own function
        // generate e-mail to user
        $from = "no-reply@" . $_SERVER['HTTP_HOST'];
        $subject = "Your " . $_SERVER['HTTP_HOST'] . " Username and Password";
        $body = $user->first . " " . $user->last . ", <br /><br />";
        $body.= "The username for your account is: " . $user->username . "<br />";
        $body.= "Your password has been reset to: " . $newpass . "<br /><br />";
        $body.= "You can change your username and password by logging into your account profile at http://" . $_SERVER['HTTP_HOST'] . "/user/ <br /><br />";
        $body.= "Thank you!<br /><br />note: this is an automated message from an unmonitored email address. Send any inquires to your website administrator.";

         $tools = new Model_Tools();
        if (!$tools->sendEmail($user->email,$from,$subject,$body)) {
            return false;
        }

        return true;
    }

    public function unique_key($value){
       return Valid::email($value) ? 'email' : 'username';
    }
    
    /**
     * Complete the login for a user by incrementing the logins and saving login timestamp
     *
     * @return void
     */
    public function complete_login()
    {
            if ($this->_loaded)
            {
                    // Update the number of logins
                    $this->logins = new Database_Expression('logins + 1');

                    // Set the last login date
                    $this->last_login = time();

                    // Save the user
                    $this->update();
            }
    }
    
    
           
        

	/**
	 * Rules for the user model. Because the password is _always_ a hash
	 * when it's set,you need to run an additional not_empty rule in your controller
	 * to make sure you didn't hash an empty string. The password rules
	 * should be enforced outside the model or with a model helper method.
	 *
	 * @return array Rules
	 */
	public function rules()
	{
		return array(
			'username' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
				array(array($this, 'unique'), array('username', ':value')),
			),
			'password' => array(
				array('not_empty'),
			),
			'email' => array(
				array('not_empty'),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			),
		);
	}

	/**
	 * Filters to run when data is set in this model. The password filter
	 * automatically hashes the password when it's set in the model.
	 *
	 * @return array Filters
	 */
	public function filters()
	{
		return array(
			'password' => array(
				array(array(Auth::instance(), 'hash'))
			)
		);
	}

	/**
	 * Labels for fields in this model
	 *
	 * @return array Labels
	 */
	public function labels()
	{
		return array(
			'username'         => 'username',
			'email'            => 'email address',
			'password'         => 'password',
		);
	}


	

	/**
	 * Tests if a unique key value exists in the database.
	 *
	 * @param   mixed    the value to test
	 * @param   string   field name
	 * @return  boolean
	 */
	public function unique_key_exists($value, $field = NULL)
	{
		if ($field === NULL)
		{
			// Automatically determine field by looking at the value
			$field = $this->unique_key($value);
		}

		return (bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))
			->from($this->_table_name)
			->where($field, '=', $value)
			->where($this->_primary_key, '!=', $this->pk())
			->execute($this->_db)
			->get('total_count');
	}

	

	/**
	 * Password validation for plain passwords.
	 *
	 * @param array $values
	 * @return Validation
	 */
	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->rule('password', 'min_length', array(':value', 8))
			->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
	}

	/**
	 * Create a new user
	 *
	 * Example usage:
	 * ~~~
	 * $user = ORM::factory('User')->create_user($_POST, array(
	 *	'username',
	 *	'password',
	 *	'email',
	 * );
	 * ~~~
	 *
	 * @param array $values
	 * @param array $expected
	 * @throws ORM_Validation_Exception
	 */
	public function create_user($values, $expected)
	{
		// Validation for passwords
		$extra_validation = Model_User::get_password_validation($values)
			->rule('password', 'not_empty');

		return $this->values($values, $expected)->create($extra_validation);
	}

	/**
	 * Update an existing user
	 *
	 * [!!] We make the assumption that if a user does not supply a password, that they do not wish to update their password.
	 *
	 * Example usage:
	 * ~~~
	 * $user = ORM::factory('User')
	 *	->where('username', '=', 'kiall')
	 *	->find()
	 *	->update_user($_POST, array(
	 *		'username',
	 *		'password',
	 *		'email',
	 *	);
	 * ~~~
	 *
	 * @param array $values
	 * @param array $expected
	 * @throws ORM_Validation_Exception
	 */
	public function update_user($values, $expected = NULL)
	{
		if (empty($values['password']))
		{
			unset($values['password'], $values['password_confirm']);
		}

		// Validation for passwords
		$extra_validation = Model_User::get_password_validation($values);

		return $this->values($values, $expected)->update($extra_validation);
	}
}
