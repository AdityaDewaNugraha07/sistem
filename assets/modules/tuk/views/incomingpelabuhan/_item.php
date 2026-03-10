<?php
if(!empty($modDetail->keberangkatan_tongkang_detail_id) && empty($edit)){
	$btnsave = 'none';
	$btncancel = 'none';
	$btnedit = '';
	$btndelete = '';
	$disabled = true;
}else{
	$btnsave = '';
	$btncancel = '';
	$btnedit = 'none';
	$btndelete = 'none';
	$disabled = false;
}
?>
<tr style="">
    <td style="text-align: center; padding: 4px; vertical-align: middle; font-size: 11px;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]keberangkatan_tongkang_detail_id") ?>
    </td>
	<td style="text-align: left; padding: 4px; vertical-align: middle; font-size: 11px;">
		<?= $modDetail->loglist->loglist_kode." - ".$modDetail->loglist->logKontrak->pihak1_perusahaan ?>
	</td>
	<td style="text-align: left; padding: 4px; vertical-align: middle; font-size: 11px;">
		<?= $modDetail->lokasi_muat ?>
	</td>
	<td style="text-align: center; padding: 4px; vertical-align: middle; font-size: 11px;">
		<?= app\components\DeltaFormatter::formatDateTimeForUser2($modDetail->tanggal_muat) ?>
	</td>
	<td style="text-align: center; padding: 4px; vertical-align: middle; font-size: 11px;">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_batang) ?>
	</td>
	<td style="text-align: right; padding: 4px; vertical-align: middle; font-size: 11px;">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_m3) ?>
	</td>
	<td style="text-align: center; padding: 4px; font-size: 10px;">
		<?php
		$modDkb = \app\models\TIncomingDkb::find()->where(["loglist_id"=>$modDetail->loglist_id])->all();
		$modPersediaan = \app\models\HPersediaanDkb::find()->where(["reff_no"=>$modDetail->loglist->loglist_kode])->all();
		if(count($modDkb)){
			$date = app\components\DeltaFormatter::formatDateTimeForUser2($modDkb[0]->created_at);
			$bongkar = $modPersediaan[0]->lokasi;
			echo "{$date} - {$bongkar} - <a onclick='detailDKB()'>Lihat DKB</a>";
		}else{
			echo "<i class='font-red-flamingo'>DKB Belum diinput</i>";
		}
		?>
	</td>
</tr>
<?php $this->registerJs(" 
	
	
", yii\web\View::POS_READY); ?>