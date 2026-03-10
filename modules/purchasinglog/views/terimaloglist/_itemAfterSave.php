<tr style="">
	<td style="vertical-align: middle; text-align: center; border: 1px solid #bdbdbd;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<!--<span class="no_urut"></span>-->
		<?= $modDetail->nomor_grd ?>
	</td>
	<td style="text-align: center;"><?= $modDetail->nomor_produksi ?></td>
	<td style="text-align: center;"><?= $modDetail->nomor_batang ?></td>
	<td><?= $modDetail->kayu->kayu_nama ?></td>
	<td style="text-align: center;"><?= $modDetail->panjang ?></td>
	<td style="text-align: center;"><?= $modDetail->diameter_ujung ?></td>
	<td style="text-align: center;"><?= $modDetail->diameter_pangkal ?></td>
	<td style="text-align: center;"><?= $modDetail->diameter_rata ?></td>
	<td style="text-align: center;"><?= ($modDetail->cacat_panjang)?$modDetail->cacat_panjang:"<center> - </center>" ?></td>
	<td style="text-align: center;"><?= ($modDetail->cacat_gb)?$modDetail->cacat_gb:"<center> - </center>" ?></td>
	<td style="text-align: center;"><?= ($modDetail->cacat_gr)?$modDetail->cacat_gr:"<center> - </center>" ?></td>
	<td style="text-align: center;"><?= ($modDetail->volume_range)?$modDetail->volume_range:"<center> - </center>" ?></td>
	<td style="text-align: right;"><?= ($modDetail->volume_value)?$modDetail->volume_value:"<center> - </center>" ?></td>
	<td><center><?= ($modDetail->is_freshcut)?"Ya":"Tidak"; ?></center></td>
	<td style="vertical-align: middle; text-align: center; border: 1px solid #bdbdbd;">
		-
	</td>
</tr>
<?php $this->registerJs(" 
	
", yii\web\View::POS_READY); ?>