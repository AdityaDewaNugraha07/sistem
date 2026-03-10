<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?php echo yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-left" style="font-size: 1.5rem;">
		<?php echo $modKayu->group_kayu .' - '. $modKayu->kayu_nama ?>
    </td>
	<td style="vertical-align: middle;" id="item-detail" class="td-kecil text-align-left">
		<?php echo yii\bootstrap\Html::activeHiddenInput($model, "[ii]log_keluar_id"); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modPersediaan, "[ii]fisik_pcs"); ?>
		<b class="" style="font-size: 1.5rem;"><a onclick="infoLog('<?php echo $model->no_barcode ?>')"><?php echo $model->no_barcode ?></a></b>
		<br><?php echo $modPersediaan->no_lap ?>
		<br><?php echo $modPersediaan->no_grade ?>
		<br><?php echo $modPersediaan->no_btg ?>
	</td>
	<!-- <td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
		<?php //echo $modPersediaan->no_lap ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
		<?php //echo $modPersediaan->no_grade ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
		<?php //echo $modPersediaan->no_btg ?>
    </td> -->
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
		<?php echo $modPersediaan->fisik_panjang ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modPersediaan->diameter_ujung1 ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modPersediaan->diameter_ujung2 ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modPersediaan->diameter_pangkal1 ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modPersediaan->diameter_pangkal2 ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
		<?php echo $modPersediaan->fisik_diameter ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modPersediaan->cacat_panjang ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modPersediaan->cacat_gb ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modPersediaan->cacat_gr ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-right" style="font-size: 1rem;">
		<?php echo app\components\DeltaFormatter::formatNumberForUserFloat($modPersediaan->fisik_volume, 2) ?>
    </td>
	<?php //if(!empty($modSpmLog)){ ?>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
		<?php echo $modSpmLog->panjang ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modSpmLog->diameter_ujung1 ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modSpmLog->diameter_ujung2 ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modSpmLog->diameter_pangkal1 ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modSpmLog->diameter_pangkal2 ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
		<?php echo $modSpmLog->diameter_rata ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modSpmLog->cacat_panjang ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modSpmLog->cacat_gb ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		<?php echo $modSpmLog->cacat_gr ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-right" style="font-size: 1rem;">
		<?php echo yii\bootstrap\Html::activeHiddenInput($modSpmLog, "[ii]volume"); ?>
		<?php echo app\components\DeltaFormatter::formatNumberForUserFloat($modSpmLog->volume, 2) ?>
    </td>
	<?php //} else { ?>
	<!-- <td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
		-
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		-
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		-
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		-
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		-
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
		-
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		-
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		-
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1rem;">
		-
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-right" style="font-size: 1rem;">
		-
    </td> -->
	<?php //} ?>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php
		$modSpm = \app\models\TSpmKo::findOne(['kode'=>$model->reff_no]);
		if(!empty($modSpm) && $modSpm->status == \app\models\TSpmKo::REALISASI){
			echo '<a class="btn btn-xs grey"><i class="fa fa-trash-o"></i></a>';
		}else{
			echo '<a class="btn btn-xs red" onclick="hapusItem(this);"><i class="fa fa-trash-o"></i></a>';
		}
		?>
    </td>
</tr>
<script>
function infoLog(no_barcode){
	openModal('<?php echo \yii\helpers\Url::toRoute(['/ppic/scanpemuatanlog/infoLog','no_barcode'=>'']) ?>'+no_barcode,'modal-info-palet','90%');
}
</script>
