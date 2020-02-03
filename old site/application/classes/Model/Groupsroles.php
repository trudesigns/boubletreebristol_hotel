<?php defined('SYSPATH') or die('No direct script access.');


class Model_Groupsroles extends ORM
{
    
    protected $_table_name = 'groups_roles';    
    protected $_belongs_to = array(
            'groups' => array(
                    'model'=>'group'
                    //, 'foreign_key'=>'group_id'
                    //, 'far_key'=>'group_id'
                )
            , 'roles' => array(
                    'model'=>'role'
                   // , 'foreign_key'=>'user_id'
                    //, 'far_key' =>'user_id'
            )
    );
}


