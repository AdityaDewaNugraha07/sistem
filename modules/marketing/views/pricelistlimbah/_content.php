<tr>
    <td style="text-align:center">
		<?= yii\helpers\Html::activeHiddenInput($model, "[ii]limbah_id"); ?>
		<?= $i+1 ?>
	</td>
    <td>
        <?= $model->limbah_kode ?>
    </td>
    <td>
		<?= $model->limbah_nama ?>
	</td>
    <td>
		<?= "/".$model->limbah_satuan_jual." (".$model->limbah_satuan_muat.")"; ?>
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