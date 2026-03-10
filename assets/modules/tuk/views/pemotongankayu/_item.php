<?php
if(!empty($modDetail->hasil_pemotongan)){
	$hasil_pemotongan = \yii\helpers\Json::decode($modDetail->hasil_pemotongan);
	$no_barcode_baru = ""; $panjang_baru=""; $volume_baru='';
	foreach($hasil_pemotongan as $i => $pemotongan){
		$no_barcode_baru .= yii\helpers\Html::activeTextInput($modDetail, '[ii]['.($i).']no_barcode_baru',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>true,'value'=>$pemotongan['no_barcode_baru']]);
		$panjang_baru .= yii\helpers\Html::activeTextInput($modDetail, '[ii]['.($i).']panjang_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)','disabled'=>true,'value'=>$pemotongan['panjang_baru']]);
		$volume_baru .= yii\helpers\Html::activeTextInput($modDetail, '[ii]['.($i).']volume_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','disabled'=>true,'value'=>$pemotongan['volume_baru']]);
	}
	$modDetail->no_barcode_baru = $pemotongan['no_barcode_baru'];
	$modDetail->panjang_baru = $pemotongan['panjang_baru'];
	$modDetail->volume_baru = $pemotongan['volume_baru'];
}
?>
<tr style="">
    <td style="text-align: center; padding: 2px; vertical-align: middle;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]pemotongan_kayu_id") ?>
		<?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]pemotongan_kayu_detail_id") ?>
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
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]reduksi',['class'=>'form-control','style'=>'padding: 2px; font-size:13px; height:25px; text-align:center;','disabled'=>true]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]volume',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?></center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<center><?= yii\helpers\Html::activeTextInput($modDetail, '[ii]jumlah_potong',['class'=>'form-control float','style'=>'padding: 2px; font-size:13px; height:25px;','disabled'=>true]); ?>
			<?php if(!empty($modDetail->pemotongan_kayu_detail_id)){ ?>
				<a style="font-size: 1.3rem; cursor: not-allowed;" title="tambah potongan"><i class="fa fa-plus-circle"></i></a>
				<a style="font-size: 1.3rem; cursor: not-allowed;" title="kurangi potongan"><i class="fa fa-minus-circle"></i></a>
			<?php }else{ ?>
				<a onclick="addPotongan(this);" style="font-size: 1.3rem;" title="tambah potongan"><i class="fa fa-plus-circle"></i></a>
				<a onclick="removePotongan(this);" style="font-size: 1.3rem;" title="kurangi potongan"><i class="fa fa-minus-circle"></i></a>
			<?php } ?>
			
		</center>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
		if(!empty($modDetail->hasil_pemotongan)){
			echo $no_barcode_baru;
		}else{
			for($i=0;$i<($modDetail->jumlah_potong);$i++){
				$modDetail->no_barcode_baru = $modDetail->no_barcode.".".app\components\DeltaFormatter::getTwoDigit($i+1);
				echo yii\helpers\Html::activeTextInput($modDetail, '[ii]['.($i).']no_barcode_baru',['class'=>'form-control','style'=>'padding: 2px; text-align:center; font-size:13px; height:25px;','disabled'=>true]);
			}
		}
		?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
		
		if(!empty($modDetail->hasil_pemotongan)){
			echo $panjang_baru;
		}else{
			for($i=0;$i<($modDetail->jumlah_potong);$i++){
				$modDetail->panjang_baru = 0;		
				echo yii\helpers\Html::activeTextInput($modDetail, '[ii]['.($i).']panjang_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;','onblur'=>'setVolBaru(this)']);
			}
		}
		?>
	</td>
	<td style="text-align: center; padding: 2px; font-size: 10px;">
		<?php
		if(!empty($modDetail->hasil_pemotongan)){
			echo $volume_baru;
		}else{
			for($i=0;$i<($modDetail->jumlah_potong);$i++){
				$modDetail->volume_baru = 0;
				echo yii\helpers\Html::activeTextInput($modDetail, '[ii]['.($i).']volume_baru',['class'=>'form-control float','style'=>'padding: 2px; text-align:right; font-size:13px; height:25px;']);
			}
		}
		?>
	</td>
    <td style="vertical-align: middle; text-align: center;">
		<?php if(!empty($modDetail->pemotongan_kayu_detail_id)){ ?>
			<span id="place-deletebtn" >
				<a class="btn btn-xs grey" id="close-btn-this"><i class="fa fa-remove"></i></a>
			</span>
		<?php }else{ ?>
			<span id="place-cancelbtn" >
				<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
			</span>
		<?php } ?>
    </td>
</tr>
<?php $this->registerJs(" 
	
	
", yii\web\View::POS_READY); ?>