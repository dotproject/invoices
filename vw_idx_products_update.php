<?php /* PRODUCTS $Id: vw_idx_products_update.php,v 1.0 2004/03/24 00:59:49 mfinger Exp $ */
GLOBAL $AppUI, $invoice_id, $obj;

// check permissions
$canEdit = !getDenyEdit( 'invoices', $invoice_id );
if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$product_id = intval( dPgetParam( $_GET, 'product_id', 0 ) );
$log = new CProduct();
if ($product_id) {
	$log->load( $product_id );
} else {
	$log->product_invoice = $invoice_id;
}

// select costcodes for pull down
$sql = "select costcode_id, costcode_name
        from costcodes
        order by costcode_id";
$product_costcodes = arrayMerge( array( 0=>'Select'), db_loadHashList( $sql ) );


if ($canEdit) {
// Product Update Form
	if ($product_id) {
		echo $AppUI->_( "Edit Log" );
	} else {
		echo $AppUI->_( "Add Log" );
	}
?>
<table cellspacing="1" cellpadding="2" border="0" width="100%">
<form name="editFrm" action="?m=invoices&a=view&invoice_id=<?php echo $invoice_id;?>" method="post">
	<input type="hidden" name="uniqueid" value="<?php echo uniqid("");?>" />
	<input type="hidden" name="dosql" value="do_updateproduct" />
	<input type="hidden" name="product_id" value="<?php echo $log->product_id;?>" />
	<input type="hidden" name="product_invoice" value="<?php echo $log->product_invoice;?>" />
<tr>
	<td align="right">
		<?php echo $AppUI->_('Cost Code');?>
	</td>
	<td>
<?php
		echo arraySelect( $product_costcodes, 'product_costcodes', 'size="1" class="text" onchange="javascript:product_costcode.value = this.options[this.selectedIndex].value; product_name.value = this.options[this.selectedIndex].text;"', '' );
?>
		&nbsp;->&nbsp; <input type="text" class="text" name="product_costcode" value="<?php echo $log->product_costcode;?>" maxlength="8" size="8" />
	</td>
</tr>
<tr>
	<td align="right"><?php echo $AppUI->_('Description');?></td>
	<td>
		<input type="text" class="text" name="product_name" value="<?php echo $log->product_name;?>" maxlength="255" size="30" />
	</td>
</tr>
<tr>
	<td align="right">
		<?php echo $AppUI->_('Qty');?>
	</td>
	<td>
		<input type="text" class="text" name="product_qty" value="<?php echo $log->product_qty;?>" maxlength="8" size="6" />
	</td>
</tr>
<tr>
	<td align="right">
		<?php echo $AppUI->_('Rate');?>
	</td>
	<td>
		<input type="text" class="text" name="product_price" value="<?php echo $log->product_price;?>" maxlength="8" size="6" />
	</td>
</tr>
<tr>
	<td colspan="2" valign="bottom" align="right">
		<input type="button" class="button" value="<?php echo $AppUI->_('update product');?>" onclick="updateProduct()" />
	</td>
</tr>

</form>
</table>
<?php } ?>
