<?php /* PRODUCT $Id: vw_products.php,v 1.0 2004/03/24 00:05:55 mfinger Exp $ */
global $AppUI, $invoice_id, $df, $canEdit;
?>
<script language="JavaScript">
function delIt2(id) {
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Products').'?';?>" )) {
		document.frmDelete2.product_id.value = id;
		document.frmDelete2.submit();
	}
}
</script>

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="tbl">
<form name="frmDelete2" action="./index.php?m=invoices" method="post">
	<input type="hidden" name="dosql" value="do_updateproduct">
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="product_id" value="0" />
</form>

<tr>
	<th></th>
	<th><?php echo $AppUI->_('ID');?></th>
	<th><?php echo $AppUI->_('Code');?></th>
	<th width="100%"><?php echo $AppUI->_('Description');?></th>
	<th><?php echo $AppUI->_('Qty');?></th>
	<th><?php echo $AppUI->_('Rate');?></th>
	<th><?php echo $AppUI->_('Amount');?></th>
	<th></th>
</tr>
<?php
// Pull the invoice data
$sql = "
SELECT invoice_product.*,
p1.invoice_status invoice_status
FROM invoice_product
LEFT JOIN invoices p1 ON product_invoice = p1.invoice_id
WHERE product_invoice = $invoice_id 
ORDER BY product_costcode
";
$logs = db_loadList( $sql );

$s = '';
$hrs = 0;
foreach ($logs as $row) {
	$s .= '<tr bgcolor="white" valign="top">';
	$s .= "\n\t<td>";
	if ($canEdit && (@$row['invoice_status'] == 0)) {
		$s .= "\n\t\t<a href=\"?m=invoices&a=view&invoice_id=$invoice_id&tab=1&product_id=".@$row['product_id']."\">"
			. "\n\t\t\t". dPshowImage( './images/icons/stock_edit-16.png', 16, 16, '' )
			. "\n\t\t</a>";
	}
	$s .= "\n\t</td>";
	$s .= '<td nowrap="nowrap">'.@$row['product_id'].'</td>';
	$s .= '<td nowrap="nowrap">'.@$row["product_costcode"].'</td>';
	$s .= '<td nowrap="nowrap">'.$row["product_name"].'</td>';
	$s .= '<td nowrap="nowrap">'.$row["product_qty"].'</td>';
	$s .= '<td nowrap="nowrap" align=right>'.$row["product_price"].'</td>';
	$s .= '<td nowrap="nowrap" align=right>'.sprintf("%01.2f",($row["product_price"] * $row["product_qty"])).'</td>';
	$s .= "\n\t<td>";
	if ($canEdit && (@$row['invoice_status'] == 0)) {
		$s .= "\n\t\t<a href=\"javascript:delIt2({$row['product_id']});\" title=\"".$AppUI->_('delete product')."\">"
			. "\n\t\t\t". dPshowImage( './images/icons/stock_delete-16.png', 16, 16, '' )
			. "\n\t\t</a>";
	}
	$s .= "\n\t</td>";
	$s .= '</tr>';
	$ttl += ($row["product_price"] * $row["product_qty"]);
}
$s .= '<tr bgcolor="white" valign="top">';
$s .= '<td colspan="6" align="right">' . $AppUI->_('Total') . ' =</td>';
$s .= '<td align="right">' . sprintf("%01.2f", $ttl) . '</td>';
$s .= '</tr>';
echo $s;
?>
</table>