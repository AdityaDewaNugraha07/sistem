<?php
\app\assets\Select2Asset::register($this);
\app\assets\InputMaskAsset::register($this);

if(!empty($modDetail->asuransi_detail_id)){
	$disabled = true;
	$jenis = $modDetail->jenis;
}else{
	$disabled = false;
	$jenis = !empty($last_tr['jenis'])?$last_tr['jenis']:"";
}
?>
<tr>
    <td class="td-kecil" style="vertical-align: middle; text-align: center;"><pre><?php print_r($modDetail);?></pre>
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]asuransi_detail_id',[]); ?>
        <?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]jenis',app\models\MKayu::getOptionListN(),['class'=>'form-control select2','multiple'=>'multiple']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
        <?= yii\bootstrap\Html::activeTextInput($modDetail, '[ii]harga',['class'=>'form-control float', 'style'=>'width:100%; padding: 2px; height:25px;', 'onblur'=>'itungTotalDul();']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::activeTextInput($modDetail, '[ii]kubikasi',['class'=>'form-control float', 'style'=>'width:100%; padding: 2px; height:25px;', 'onblur'=>'itungTotalDul();']); ?>
    </td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::activeTextInput($modDetail, '[ii]total',['class'=>'form-control float', 'style'=>'width:100%; padding: 2px; height:25px;', 'onblur'=>'itungTotalDul();', 'readonly'=>'readonly']); ?>
	</td>
    <td><a class="btn btn-xs red" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a></td>
</tr>
<?php $this->registerJs(" ", yii\web\View::POS_READY);?>
