<?php
if(!empty($modDetail->terima_logalam_detail_id)){
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
$kayu_id = $modDetail->kayu_id;
$sql_kayu_nama = "select concat(group_kayu,' - ',kayu_nama) as kayu_nama from m_kayu where kayu_id = ".$kayu_id."";
$kayu_nama = Yii::$app->db->createCommand($sql_kayu_nama)->queryScalar();
?>
<tr>
    <td style="text-align: center; padding: 2px;" id="nomor_urut"></td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]terima_logalam_detail_id") ?>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]terima_logalam_id") ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]kayu_id") ?>
        <?= yii\helpers\Html::activeTextInput($modDetail, '[ii]no_barcode',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]no_lap',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;', 'readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]no_grade',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]no_btg',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]nama_kayu',['value'=>$kayu_nama,'class'=>'form-control','style'=>'padding: 2px; text-align:left; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]panjang',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]kode_potong',['class'=>'form-control float','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter_ujung1',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter_ujung2',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter_pangkal1',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter_pangkal2',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]cacat_panjang',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]cacat_gb',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]cacat_gr',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= yii\helpers\Html::activeTextInput($modDetail, '[ii]volume',['class'=>'form-control','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;"> <!-- TAMBAH FSC -->
		<?php 
		if($modTerimaLog->fsc){
			$modTerimaLog->fsc = 'FSC 100%';
		} else {
			$modTerimaLog->fsc = 'Non FSC';
		}
		?>
		<?= yii\helpers\Html::activeTextInput($modTerimaLog, '[ii]fsc',['class'=>'form-control','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','readonly'=>'readonly']); ?>
	</td>
    <td style="vertical-align: middle; text-align: center;">
        <?php if(isset($edit)&&($edit=="0")){ ?>
		<span id="place-cancelbtn" style="display: <?= $btncancel ?>">
			<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);" title="Hapus Detail"><i class="fa fa-remove"></i></a>
		</span>     
        <?php } ?>
		<span class="place-printbtn" style="display: none;">
			<a class="btn btn-xs primary" id="print-btn-this" onclick="print(<?php echo $modDetail->terima_logalam_detail_id;?>);"><i class="fa fa-print"></i></a>
		</span>
    </td>
</tr>
<?php $this->registerJs(" 
", yii\web\View::POS_READY); ?>

<script>
</script>