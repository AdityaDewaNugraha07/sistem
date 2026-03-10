<tr style="">
    <td style="text-align: center; padding: 2px; vertical-align: middle;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]keluar_pelabuhan_id") ?>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]keluar_pelabuhan_detail_id") ?>
    </td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]no_barcode',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>true]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]kayu_id',\app\models\MKayu::getOptionListPlusGroup(),['class'=>'form-control','prompt'=>'','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]panjang',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]volume',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?></center>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]kondisi',['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px; text-align:center;','disabled'=>true]); ?></center>
	</td>
    <td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]keterangan',['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px; text-align:center;','disabled'=>false]); ?></center>
	</td>
    <td style="vertical-align: middle; text-align: center;">
		<?php if(!empty($modDetail->keluar_pelabuhan_detail_id)){ ?>
			<span id="place-deletebtn" >
				<a class="btn btn-xs grey" id="close-btn-this"><i class="fa fa-remove"></i></a>
			</span>
		<?php }else{ ?>
			<span id="place-cancelbtn" >
				<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItem(this,'total();');"><i class="fa fa-remove"></i></a>
			</span>
		<?php } ?>
    </td>
</tr>
<?php $this->registerJs(" 
	
	
", yii\web\View::POS_READY); ?>