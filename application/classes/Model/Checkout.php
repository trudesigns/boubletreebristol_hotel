<?php defined('SYSPATH') or die('No direct script access.');

	/*
	Note, the default "checkouts" table included in yellow brick includes
	columns for "Name," "email," and "fund."
	Depending on your implementation "Name" and "email" should probably be replaced by an ID to a
	table of users, customers or donors.
	Likewise, "product" should probably be the ID or SKU to a table of products or donations.
	*/

class Model_Checkout extends ORM {
	

	// generate a user friendly local transaction id
	public static function generateTransactionID($datestamp,$id)
	{
		return date("ymd",strtotime($datestamp)).$id;
	}
	
	// return the actual database row ID for a given "transaction id"
	public function getTransaction($transaction_id, $id_only = false)
	{
		$db_row_id = substr($transaction_id, 6);
		return ($id_only) ? $db_row_id : ORM::factory('Checkout',$db_row_id);
	}
	
	
}