<?php
$rincian = '<a class="btn btn-sm btn-default" style="font-size:1rem; padding: 3px;" onclick="lihatRincian(\''.$detail['tanggal'].'\')"><i class="fa fa-search"></i> Lihat Laporan</a>';
$ppk = '';
$desc = "";
if($detail['debit'] > 0 && $detail['kredit'] <= 0){
	$desc = "Penerimaan Kas Besar";
}
if($detail['debit'] <= 0 && $detail['kredit'] <= 0){
	$desc = "Tidak Ada Transaksi Penerimaan / Setoran";
	$rincian = '-';
}
if($detail['kredit'] > 0 && $detail['debit'] <= 0){
	$desc = "Setor Tunai";
}
if($detail['kredit'] > 0 && $detail['debit'] > 0){
	$desc = "Setor Tunai & Penerimaan Kas Besar";
}
?>
<tr style="">
	<td class=""  style="font-size:1.2rem; text-align: center; padding: 5px;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<span class="no_urut"><?= $i+1; ?></span>
	</td>
	<td class=""  style="font-size:1.2rem; padding: 5px;">
		<?= \app\components\DeltaFormatter::formatDateTimeForUser2($detail['tanggal']); ?>
	</td>
	<td class=""  style="font-size:1.2rem; padding: 5px;">
		<?= $desc ?>
	</td>
	<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
		<?= !empty($detail['debit'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['debit']):0; ?>
	</td>
	<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
		<?= !empty($detail['kredit'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['kredit']):0; ?>
	</td>
	<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat(\app\models\HSaldoKasbesar::getSaldoAkhir($detail['tanggal']) ); ?>
	</td>
	<td class="text-align-center"  style="font-size:1.2rem; padding: 5px;">
		<?= $rincian ?>
	</td>
</tr>