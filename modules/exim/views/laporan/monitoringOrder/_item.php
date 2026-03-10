<?php
$sqlinv = "SELECT * FROM t_invoice 
			JOIN t_packinglist ON t_packinglist.packinglist_id = t_invoice.packinglist_id
			JOIN t_packinglist_container ON t_packinglist_container.packinglist_id = t_invoice.packinglist_id
			WHERE t_invoice.op_export_id = ".$op['op_export_id']." AND lot_code = '".$detail['detail_lot_code']."'
			ORDER BY invoice_id ASC";
$modInvoice = \Yii::$app->db->createCommand($sqlinv)->queryOne();
?>
<tr>
	<td class="td-kecil text-align-center" style="vertical-align: middle !important; font-size: 1.1rem !important;">
		<?= ($i+1); ?>
	</td>
	<td class="td-kecil text-align-left" style="font-size: 1.1rem !important; line-break: 1;">
		<?= "<b>".$op['nomor_kontrak']."</b><br>".$op['cust_an_nama']; ?>
	</td>
	<td class="td-kecil text-align-left" style="font-size: 1.1rem !important;">
		<?= $detail['detail_description']; ?>
	</td>
	<td class="td-kecil text-align-left" style="font-size: 1.1rem !important;">
		<?= $detail['detail_size']; ?> 
	</td>
        <td class="td-kecil text-align-right" style="font-size: 1.1rem !important;">
		<?= (!empty($detail['detail_volume'])? $detail['detail_volume']:""); ?>
	</td>
        <td class="td-kecil text-align-right" style="font-size: 1.1rem !important;">
		<?= (!empty($detail['detail_price'])? app\components\DeltaFormatter::formatNumberForUserFloat($detail['detail_price']):""); ?>
	</td>
	<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
		<?= (!empty($modInvoice['term_of_price'])?$modInvoice['term_of_price']:""); ?>
	</td>
	<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
		<?= (!empty($modInvoice['payment_method'])?$modInvoice['payment_method']:""); ?>
	</td>
	<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
		<?= $detail['detail_lot_code']; ?>
	</td>
	<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
		<?= $detail['shipment_time']; ?>
	</td>
	<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
		<?= (!empty($modInvoice['nomor'])? substr($modInvoice['nomor'], 0, 5):""); ?>
	</td>
	<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
		<?= (!empty($modInvoice['tanggal'])?app\components\DeltaFormatter::formatDateTimeForUser2($modInvoice['tanggal']):""); ?>
	</td>
	<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
		<?= (!empty($modInvoice['etd'])?app\components\DeltaFormatter::formatDateTimeForUser2($modInvoice['etd']):""); ?>
	</td>
	<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
		<?= (!empty($modInvoice['eta'])?app\components\DeltaFormatter::formatDateTimeForUser2($modInvoice['eta']):""); ?>
	</td>
	<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
		
	</td>
</tr>