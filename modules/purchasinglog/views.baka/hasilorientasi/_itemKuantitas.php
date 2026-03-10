<?php
$ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log');
if(!empty($model->hasil_orientasi_id)){
	$disabled = true;
	$kayu_id = $model->kayu_id;
	$keterangan = $model->keterangan;
}else{
	$disabled = false;
	$kayu_id = !empty($last_tr['kayu_id'])?$last_tr['kayu_id']:"";
	$keterangan = !empty($last_tr['keterangan'])?$last_tr['keterangan']:"";
}
?>
<tr style="">
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]hasil_orientasi_id',[]); ?>
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
		<?php echo yii\helpers\Html::activeDropDownList($model, '[ii]kayu_id',app\models\MKayu::getOptionList(),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%; padding: 2px; height:25px;','onchange'=>'setKualitas()','value'=>$kayu_id,"disabled"=>$disabled]); ?>
	</td>
	<?php 
	foreach($ukuranganrange as $i => $range){ 
		if(!empty($model->hasil_orientasi_id)){
			$sql = "SELECT SUM(qty_batang) AS qty_btg, SUM(qty_m3) AS qty_m3 FROM t_hasil_orientasi_kuantitas 
					WHERE hasil_orientasi_id = {$model->hasil_orientasi_id} AND kayu_id = {$model->kayu_id} AND diameter_cm = '{$range}' AND keterangan='{$model->keterangan}'";
			$modQty = Yii::$app->db->createCommand($sql)->queryOne();
	?>
		<td class="td-kecil" style="vertical-align: middle; text-align: center;">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_batang',['class'=>'form-control float col-btg','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=> number_format($modQty['qty_btg']),'onblur'=>'totalKuantitas();',"disabled"=>$disabled]); ?>
		</td>
		<td class="td-kecil" style="vertical-align: middle; text-align: center;">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=> \app\components\DeltaFormatter::formatNumberForUserFloat($modQty['qty_m3']),'onblur'=>'totalKuantitas();',"disabled"=>$disabled]); ?>
		</td>
	<?php }else{ ?>
		<td class="td-kecil" style="vertical-align: middle; text-align: center;">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_batang',['class'=>'form-control float col-btg','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>0,'onblur'=>'totalKuantitas();',"disabled"=>$disabled]); ?>
		</td>
		<td class="td-kecil" style="vertical-align: middle; text-align: center;">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>0,'onblur'=>'totalKuantitas();',"disabled"=>$disabled]); ?>
		</td>
	<?php
		}
	} ?>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii][total]qty_batang',['class'=>'form-control float','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii][total]qty_m3',['class'=>'form-control float','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]keterangan',['class'=>'form-control','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$keterangan,"disabled"=>$disabled]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<a class="btn btn-xs red" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
	</td>
</tr>
<?php $this->registerJs(" 
	
", yii\web\View::POS_READY); ?>