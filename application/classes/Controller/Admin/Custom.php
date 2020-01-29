<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Custom pages unique to this site
 * 
 */

class Controller_Admin_Custom extends Controller_Admin_Default {
	
	
	public function action_blog()
	{	
		$id = ( $this->request->param('id') && is_numeric($this->request->param('id')) ) ? $this->request->param('id'): false;
		$view = new View('yellowbrick/custom-pages/feed_blog');	
		$crud_selected = ORM::factory('Feedarticle',$id);
		
		if (isset($_POST['delete']) && $_POST['delete'] == 'delete') 
		{
			// removes the article and its assocition to the feed, not the page itself
			$crud_selected->remove('categories');
			$crud_selected->delete();  
		}			
		
		// if this is a new feed article, check if we're creating a new page or just linking to an existing page
		if(isset($_POST['linkType']) && $_POST['linkType'] == "page" && isset($_POST['selected_page_new']) && $_POST['selected_page_new'] == 1)
		{
			// create new page
			$pages = new Model_Page;
			$newpage['parent_id'] = 287; // page id of page this will become a subpage of.
			$newpage['template_id'] = 1; // template id for the new page
			$newpage['active'] = 0; 	// default active state of new page. 0 = page is not live until activated
			$newpage['content'] = array( array('block_id'=>3, 'content'=>'oh snap, this page already has content!'));
			
			$_POST['page_id'] = $pages->add_or_update("add",$newpage);	
		}
		
		if(isset($_POST['submit-form']))
		{
			//save feed article data
			$crud_selected->page_id = ($_POST['linkType'] == "page") ? $_POST['page_id'] : 0;
			
			$other['url'] = ($_POST['linkType'] == "url") ? $_POST['url'] : '';
			$other['url_label'] = ($_POST['linkType'] == "url") ? $_POST['url_label'] : '';
			$other['url_description'] = ($_POST['linkType'] == "url") ? $_POST['url_description'] : '';
			
			$crud_selected->other = json_encode($other);
			
			$crud_selected->display_date = $_POST['display_date'];
			$crud_selected->active = (isset($_POST['active'])) ? $_POST['active'] : 0;
			$crud_selected->save();
			
			//remove previous category/article associates from the lookup table
			$crud_selected->remove('categories');
			
			//link article to selected categories
			$crud_selected->add('categories',$_POST['categories']);

			// reload this page
			$fwd = ($this->request->param('id') && is_numeric($this->request->param('id')) ) ? '' : '/'.$crud_selected->id;
			$this->redirect( rtrim($_SERVER['REQUEST_URI'],"/") . $fwd);
		}
		
		
		$view->crud_all = ORM::factory('Feedarticle')->order_by('display_date','asc')->find_all();
		$view->crud_selected = (isset($crud_selected->id)) ? $crud_selected : false;
		$view->page = (isset($crud_selected->page_id) && $crud_selected->page_id != 0) ? ORM::factory('Page',$crud_selected->page_id) : false;
		$this->template->content = $view;
		
	}
	
	public function action_checkouts()
	{
		$transactions = ORM::factory('Checkout')->order_by('timestamp','DESC')->find_all();
		
		if(isset($_GET['download']) && $_GET['download'] == "xls")
		{
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=transactions.xls");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			echo '<table>
					<tr>
					<td>Transaction</td>
					<td>Name</td>
					<td>E-Mail Address</td>
					<td>Fund</td>
					<td>Amount</td>
					<td>Date / Time</td>
					<td>Stripe Charge ID</td>
					<td>IP Address</td>
					</tr>';
			foreach($transactions as $transaction)
			{
				echo '<tr>
					<td>'.Model_Checkout::generateTransactionID($transaction->timestamp,$transaction->id).'</td>
					<td>'.$transaction->name.'</td>
					<td>'.$transaction->email.'</td>
					<td>'.$transaction->product.'</td>
					<td>$'.$transaction->amount.'</td>
					<td>'. date("F j, Y h:ia",strtotime($transaction->timestamp)).'</td>
					<td>'.$transaction->vendor_transaction_id .'</td>
					<td>'.$transaction->ip_address.'</td>
				</tr>';
			} 
			echo '</table>';			
			exit();
		}
		
		// otherwise, out put the view
		
		$view = new View('yellowbrick/custom-pages/checkouts');	
		$view->transactions = $transactions;		
		$this->template->mstyles['yb-assets/plugins/tablesorter/style.css'] = 'screen, projection';
		$this->template->mscripts[] = 'yb-assets/plugins/tablesorter/jquery.tablesorter.min.js';
		$this->template->content = $view;
	}
	
}