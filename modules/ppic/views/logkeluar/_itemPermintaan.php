<tr>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
        <input type="hidden" name="TPengajuanPembelianlog[i][pengajuan_pembelianlog_id]" value="<?php echo $model->pengajuan_pembelianlog_id;?>">
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;"><?php echo $model->kode;?></td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);?></td>
	<td class="td-kecil" style="vertical-align: middle; text-align: left;"><?php echo $model->nomor_kontrak;?></td>
	<td class="td-kecil" style="vertical-align: middle; text-align: left;"><?php echo $modSuplier->suplier_nm;?></td>
    <td class="td-kecil" style="vertical-align: middle; text-align: left;"><?php echo $model->asal_kayu;?></td>
    <td class="td-kecil total_batang" style="vertical-align: middle; text-align: right;"><?php echo $jumlah_batang;?> <input type="hidden" name="TPengajuanPembelianlog[i][qty_batang]" value="<?php echo $jumlah_batang;?>"></td>
    <td class="td-kecil total_volume" style="vertical-align: middle; text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($jumlah_volume,2);?> <input type="hidden" name="TPengajuanPembelianlog[i][qty_m3]" value="<?php echo $jumlah_volume;?>"></td>
    <td class="td-kecil" style="vertical-align: middle; text-align: center;">
    <a class="btn btn-xs white-gallery btn-outline" onclick="detailPengajuanPembelianlog(<?= $model->pengajuan_pembelianlog_id ?>)"><i class="fa fa-eye"></i></a>
        <a class="btn btn-xs red" onclick="hapusPengajuanPembelianLog(this)"><i class="fa fa-remove"></i></a>
    </td>
</tr>