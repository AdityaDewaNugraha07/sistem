<tr>
    <td style="text-align:center">
		<?= yii\helpers\Html::activeHiddenInput($model, "[ii]log_id"); ?>
		<?= $i+1 ?>
	</td>
    <td>
        <?= $model->log_kode ?>
    </td>
    <td>
		<?= $model->log_nama ?>
	</td>
	<td>
		<center><?= $m->range_awal ?> - <?= $m->range_akhir; ?></center>
	</td>
    <td>
		<center><?= $model->log_satuan_jual; ?></center>
	</td>
    <td style="text-align: right; padding-right: 15px;">
		<?php
		if($tipe=="view"){
			echo app\components\DeltaFormatter::formatNumberForUserFloat($model->harga_enduser);
		}else if($tipe=="input" || $tipe=="edit"){
			echo yii\helpers\Html::activeTextInput($model, "[ii]harga_enduser",['class'=>'form-control float']);
		}
		?>
    </td>
    <!-- <td> -->
		<?php
		// if($tipe=="view"){
		// 	echo app\components\DeltaFormatter::formatNumberForUserFloat($model->harga_keterangan);
		// }else if($tipe=="input" || $tipe=="edit"){
		// 	echo yii\helpers\Html::activeTextInput($model, "[ii]harga_keterangan",['class'=>'form-control']);
		// }
		?>
    <!-- </td> -->
</tr>