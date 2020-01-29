<div id="app-wrapper">
<h1>Transactions</h1>

<?
if(!isset($transactions) || !$transactions || count($transactions) === 0)
{
	echo "<h2>There are currently no transactions to report</h2>";
	return; // break out of this view
}
?>
<p style="float: right">
	<a href="https://manage.stripe.com" target="_blank" class="tooltip" title="Launch the payment gateway site">Manage Stripe Account</a>
	&nbsp;
	<a class="yb-button tooltip" title="Download this table as an Excel Spreadsheet" href="?download=xls">Download as Excel Document</a></p>

<table id="checkouts_table" class="tablesorter" width="100%">
	<thead>
		<th>Transaction</th>
		<th>Name</th>
		<th>E-Mail Address</th>
		<th>Fund</th>
		<th>Amount</th>
		<th>Date / Time</th>
		<th>&nbsp;</th>
	</thead>
	<tbody>
<?	foreach($transactions as $transaction)
	{
?>
		<tr>
			<td><?=Model_Checkout::generateTransactionID($transaction->timestamp,$transaction->id) ?></td>
			<td><?=$transaction->name ?></td>
			<td><a href="mailto:<?=$transaction->email ?>"><?=$transaction->email ?></a></td>
			<td><?=$transaction->product ?></td>
			<td>$<?=$transaction->amount ?></td>
			<td><?=date("F j, Y h:ia",strtotime($transaction->timestamp)) ?></td>
			<td><a href="https://manage.stripe.com/payments/<?=$transaction->vendor_transaction_id ?>" title="Stripe Transaction ID: <?=$transaction->vendor_transaction_id ?>" target="_blank">view</a></td>
		</tr>
<?	} ?>
	</tbody>
</table>

<em>Note: the "Transaction ID" is the ID provided to the user in their confirmation email.  It is a local reference and is not associated with the transaction at stripe.com</em>

<script>
$(document).ready(function() { 

	$("#checkouts_table").tablesorter({ 
            widgets: ['zebra'], //zebra = adds "odd" and "even" row classes
            headers: { 0: { sorter: false}, // dont sort columns #1 or #7
                       6: { sorter: false}
                     }
	});
});											 
</script>