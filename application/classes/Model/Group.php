<?php defined('SYSPATH') or die('No direct script access.');

class Model_Group extends ORM
{
    protected $_table_name = 'groups';
    protected $_has_many = array(
		'users'=> array(
                        'model'        => 'user'
                        ,'through'     => 'users_groups'

                ),
                'roles'       => array('model' => 'Role', 'through' => 'groups_roles'),
	);
 
}

