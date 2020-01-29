<?php defined('SYSPATH') or die('No direct script access.');


class Model_Usersgroups extends ORM
{
    
    protected $_table_name = 'users_groups';    
    protected $_belongs_to = array(
            'groups' => array(
                    'model'=>'group'
                    //, 'foreign_key'=>'group_id'
                    //, 'far_key'=>'group_id'
                )
            , 'users' => array(
                    'model'=>'user'
                   // , 'foreign_key'=>'user_id'
                    //, 'far_key' =>'user_id'
            )
    );
}
