<?php if($jenis_log=="LA"){ 
    $ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log');
    if((!empty($model->pmr_id)) && ($edit=="false")){
        $disabled = true;
        $kayu_id = $model->kayu_id;
        $keterangan = $model->keterangan;
    }else{
        $disabled = false;
        $kayu_id = "";
        $keterangan = "";
    }
?>
<tr style="">
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_detail_id',[]); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_id',[]); ?>
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
		<?php echo yii\helpers\Html::activeDropDownList($model, '[ii]kayu_id',app\models\MKayu::getOptionListPlusGroup(),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%; padding: 1px; height:25px;','val'=>$kayu_id,"disabled"=>$disabled]); ?>
	</td>
	<?php 
	foreach($ukuranganrange as $i => $range){
        if((!empty($model->pmr_id))){
			$sql = "SELECT SUM(qty_m3) AS qty_m3 FROM t_pmr_detail 
					WHERE pmr_id = {$model->pmr_id} AND kayu_id = {$model->kayu_id} AND diameter_range = '{$range}'";
			$modQty = Yii::$app->db->createCommand($sql)->queryOne();
	?>
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>\app\components\DeltaFormatter::formatNumberForUserFloat($modQty['qty_m3']),'onblur'=>"total();","disabled"=>$disabled]); ?>
		</td>
    <?php }else{ ?>
		<td class="td-kecil" style="vertical-align: middle; text-align: center;">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>0,'onblur'=>"total();","disabled"=>$disabled]); ?>
		</td>
	<?php } ?>
    <?php } ?>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii][total]qty_m3',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]keterangan',['class'=>'form-control','style'=>'width:100%; padding: 1px; height:25px; font-size:1.1rem;',"disabled"=>$disabled]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<a class="btn btn-xs red" onclick="cancelItem(this,'total()');"><i class="fa fa-remove"></i></a>
	</td>
</tr>
<?php  }else if($jenis_log=="LS"){ 
    $model->kayu_id = 29; // RC - Sengon
    $ukuranganrange = \app\models\MDefaultValue::getOptionList('log-sengon-panjang');
    if((!empty($model->pmr_id)) && ($edit=="false")){
        $disabled = true;
    }else{
        $disabled = false;
    }
?>
<tr style="">
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_detail_id',[]); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pmr_id',[]); ?>
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
		<?php echo yii\helpers\Html::activeDropDownList($model, '[ii]kayu_id',app\models\MKayu::getOptionListPlusGroup(),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%; padding: 1px; height:25px;',"disabled"=>true]); ?>
	</td>
	<?php 
	foreach($ukuranganrange as $i => $range){
        if((!empty($model->pmr_id))){
			$sql = "SELECT SUM(qty_m3) AS qty_m3 FROM t_pmr_detail 
					WHERE pmr_id = {$model->pmr_id} AND kayu_id = {$model->kayu_id} AND panjang = '{$range}'";
			$modQty = Yii::$app->db->createCommand($sql)->queryOne();
	?>
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>$modQty['qty_m3'],'onblur'=>"total();","disabled"=>$disabled]); ?>
		</td>
    <?php }else{ ?>
		<td class="td-kecil" style="vertical-align: middle; text-align: center;">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>0,'onblur'=>"total();","disabled"=>$disabled]); ?>
		</td>
	<?php } ?>
    <?php } ?>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii][total]qty_m3',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]keterangan',['class'=>'form-control','style'=>'width:100%; padding: 1px; height:25px; font-size:1.1rem;',"disabled"=>$disabled]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<a class="btn btn-xs red" onclick="cancelItem(this,'total()');"><i class="fa fa-remove"></i></a>
	</td>
</tr>
<?php } ?>
