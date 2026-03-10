<?php
$ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log');
if(!empty($model->hasil_orientasi_id)){ // create
	$disabled = true;
	$kayu_id = $model->kayu_id;
	$qty_batang = $model->qty_batang;
	$qty_m3 = $model->qty_m3;
	$usia_tebang = \yii\helpers\Json::decode($model->usia_tebang);
	$ut_13 = $usia_tebang['ut_13'];
	$ut_45 = $usia_tebang['ut_45'];
	$ut_68 = $usia_tebang['ut_68'];
	$ut_99 = $usia_tebang['ut_99'];
	$kondisi_global = \yii\helpers\Json::decode($model->kondisi_global);
	//$kg_sehat = $kondisi_global['kg_sehat'];
	//$kg_rusak = $kondisi_global['kg_rusak'];
	$kg_gubal = $kondisi_global['kg_gubal'];
	$kondisi_total = \yii\helpers\Json::decode($model->kondisi_total);
	$kt_gr = $kondisi_total['kt_gr'];
	$kt_pecah = $kondisi_total['kt_pecah'];
	$keterangan = $model->keterangan;
}else{ // edit
	$disabled = false;
	$kayu_id = !empty($last_tr['kayu_id'])?$last_tr['kayu_id']:"";
	$qty_batang = 0;
	$qty_m3 = 0;
	$ut_13 = !empty($last_tr['ut_13'])?$last_tr['ut_13']:0;
	$ut_45 = !empty($last_tr['ut_45'])?$last_tr['ut_45']:0;
	$ut_68 = !empty($last_tr['ut_68'])?$last_tr['ut_68']:0;
	$ut_99 = !empty($last_tr['ut_99'])?$last_tr['ut_99']:0;
	//$kg_sehat = "";
	//$kg_rusak = "";
	$kg_gubal = "";
	$kt_gr = "";
	$kt_pecah = "";
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
		<?php echo yii\helpers\Html::activeDropDownList($model, '[ii]kayu_id',app\models\MKayu::getOptionList(),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%; padding: 2px; height:25px;','value'=>$kayu_id,'disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]qty_batang',['class'=>'form-control float','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$qty_batang,'disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]qty_m3',['class'=>'form-control float','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$qty_m3,'disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<div class="mt-checkbox-list" style="height: 30px; padding: 2px 10px;">
			<label class="mt-checkbox mt-checkbox-outline" style="margin-left: -15px;">
				<input name="THasilOrientasiKualitas[ii][bekas_pilih]" value="0" type="hidden">
				<input id="thasilorientasikualitas-bekas_pilih" name="THasilOrientasiKualitas[ii][bekas_pilih]" <?= ($model->bekas_pilih)?"checked":""; ?> type="checkbox" <?= ($disabled)?"disabled":"" ?>> 
				<span class="help-block" style="border: 1px solid #888;"></span>
			</label> 
		</div>
	</td>
	<?php /* <td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<table style="width: 100%;">
			<tr>
				<td style="width: 40%;">
					<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]ut_qty',['class'=>'form-control float','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>number_format($ut_qty),'disabled'=>$disabled]); ?>
				</td>
				<td style="width: 60%;">
					<?php echo yii\helpers\Html::activeDropDownList($model, '[ii]ut_satuan',['Hari'=>'Hari','Bulan'=>'Bulan','Tahun'=>'Tahun'],['class'=>'form-control select2','style'=>'width:100%; padding: 2px; height:25px;','value'=>$ut_satuan,'disabled'=>$disabled]); ?>
				</td>
			</tr>
		</table>
	</td> */?>
	<td class="td-kecil" style="vertical-align: middle; text-align: center; width: 70px;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]ut_13',['class'=>'form-control float col-md-2','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$ut_13,'disabled'=>$disabled, 'onKeyup'=>'cekTotal()']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center; width: 70px;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]ut_45',['class'=>'form-control float col-md-2','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$ut_45,'disabled'=>$disabled, 'onKeyup'=>'cekTotal()']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center; width: 70px;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]ut_68',['class'=>'form-control float col-md-2','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$ut_68,'disabled'=>$disabled, 'onKeyup'=>'cekTotal()']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center; width: 70px;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]ut_99',['class'=>'form-control float col-md-2','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$ut_99,'disabled'=>$disabled, 'onKeyup'=>'cekTotal()']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center; width: 70px;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]usia_tebang_persen',['class'=>'form-control float col-md-2','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>'','disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]kg_gubal',['class'=>'form-control','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$kg_gubal,'disabled'=>$disabled]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]kt_gr',['class'=>'form-control float','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$kt_gr,'disabled'=>$disabled]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]kt_pecah',['class'=>'form-control float','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$kt_pecah,'disabled'=>$disabled]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]keterangan',['class'=>'form-control','style'=>'width:100%; padding: 2px; height:25px; font-size:1.2rem;','value'=>$keterangan,'disabled'=>$disabled]); ?>
	</td>
	<!--<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<a class="btn btn-xs red" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
	</td>-->
</tr>
<?php $this->registerJs(" 
	
", yii\web\View::POS_READY); ?>