<?php defined('SYSPATH') or die('No direct script access.');

class Model_Role extends ORM
{
    protected $_has_many = array(
                'groups' => array('model' => 'Group', 'through' => 'groups_roles'),
                'users'  => array('model' => 'User', 'through' => 'roles_users'),
	);
}
