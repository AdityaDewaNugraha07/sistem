<?php
$nominal = $model->nominal;
$model->nominal = !empty($model->status=="DITOLAK")?0:$model->nominal;
?>
<tr>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]pengajuan_tagihan_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]suplier_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]terima_bhp_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]spo_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]spl_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]nomor_nota',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]nominal',['style'=>'width:50px;']); ?>
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?>
	</td>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<a onclick="infoTBP(<?= $modTerima->terima_bhp_id ?>);"><?= $modTerima->terimabhp_kode ?></a>
	</td>
	<td class="td-kecil text-align-left" style="vertical-align: top ! important;">
		<?= $modTerima->suplier->suplier_nm ?> 
	</td>
	<td class="td-kecil text-align-center" style="vertical-align: top ! important;">
		<?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_nota); ?>
	</td>
	<td class="td-kecil text-align-left" style="vertical-align: top ! important;" id="place-berkas">
		<?= Yii::$app->runAction("purchasing/pengajuantagihan/setKelengkapanBerkas",['model'=>$model,'tipe'=>'view']); ?>
	</td>
	<td class="td-kecil text-align-left" style="vertical-align: top ! important;">
		<?= $model->nomor_nota ?><br>
		<?= $model->no_fakturpajak ?><br>
	</td>
	<td class="td-kecil text-align-left" style="vertical-align: top ! important;">
		<?= $model->nomor_kuitansi ?>
	</td>
	<td class="td-kecil text-align-right" id="place-nominal" style="vertical-align: top ! important;">
		<?php
		if($model->status=="DITOLAK"){
			echo "<strike>".\app\components\DeltaFormatter::formatNumberForUserFloat($nominal)."</strike>";
		}else{
			echo \app\components\DeltaFormatter::formatNumberForUserFloat($nominal);
		}
		?>
	</td>
	<td class="td-kecil text-align-center" id="place-status" style="vertical-align: top ! important;">
		<?php
		if(!empty($model->status)){
			if($model->status == "DIAJUKAN"){
				$label = "BELUM DITERIMA";
				echo '<a class="btn btn-xs btn-outline dark" title="Apakah akan menerima pengajuan ini?" data-toggle="konfirmasi" style="font-size:1.1rem;">BELUM DITERIMA</a>';
			}else if($model->status == "DITERIMA"){
				echo '<a class="btn btn-xs green-seagreen" title="'.$model->keterangan.'<br><b>Batalkan Penerimaan?</b>" data-toggle="konfirmasibatal" style="font-size:1.1rem;">SUDAH DITERIMA</a>';
			}else if($model->status == "DITOLAK"){
				echo '<a class="btn btn-xs btn-outline red-flamingo" title="'.$model->keterangan.'<br><b>Batalkan Penolakan?</b>" data-toggle="konfirmasibatal" style="font-size:1.1rem;">DITOLAK</a>';
			}
		}else{
			echo "-";
		}
		
		?>
	</td>
</tr>