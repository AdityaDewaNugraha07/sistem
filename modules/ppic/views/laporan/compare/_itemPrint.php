<?php $max = max(count($loglists), count($terima)); ?>
<tr>
    <td colspan="11" class="td-kecil" style="background-color: #eaeaea;"><b><?= $pengajuan_pembelianlog_kode ?></b></td>
    <td rowspan="<?= $max + 1 ?>" style="background-color: #eaeaea;"></td>
    <td colspan="15" class="td-kecil" style="background-color: #eaeaea;"><b><?= $pengajuan_pembelianlog_kode ?></b></td>
</tr>
<?php for ($i = 0; $i < $max; $i++) : ?>
    <tr>
        <td class="td-kecil text-center"><?= $i + 1 ?></td>
        <td class="td-kecil text-center"><?= isset($loglists[$i]['nomor_grd']) ? $loglists[$i]['nomor_grd'] : '' ?></td>
        <td class="td-kecil text-center"><?= isset($loglists[$i]['nomor_batang']) ? $loglists[$i]['nomor_batang'] : '' ?></td>
        <td class="td-kecil text-center"><?= isset($loglists[$i]['nomor_produksi']) ? $loglists[$i]['nomor_produksi'] : '' ?></td>
        <td class="td-kecil text-center"><?= isset($loglists[$i]['kayu_nama']) ? $loglists[$i]['kayu_nama'] : '' ?></td>
        <td class="td-kecil text-right"><?= isset($loglists[$i]['panjang']) ? $loglists[$i]['panjang'] : '' ?></td>
        <td class="td-kecil text-right"><?= isset($loglists[$i]['cacat_panjang']) ? $loglists[$i]['cacat_panjang'] : '' ?></td>
        <td class="td-kecil text-right"><?= isset($loglists[$i]['cacat_gb']) ? $loglists[$i]['cacat_gb'] : '' ?></td>
        <td class="td-kecil text-right"><?= isset($loglists[$i]['cacat_gr']) ? $loglists[$i]['cacat_gr'] : '' ?></td>
        <td class="td-kecil text-right"><?= isset($loglists[$i]['diameter_rata']) ? $loglists[$i]['diameter_rata'] : '' ?></td>
        <td class="td-kecil text-right"><?= isset($loglists[$i]['volume_value']) ? $loglists[$i]['volume_value'] : '' ?></td>
        <td class="td-kecil text-center"><?= isset($terima[$i]['no_grade']) ? $terima[$i]['no_grade'] : '' ?></td>
        <td class='td-kecil text-center'><?= isset($terima[$i]['no_btg']) ? $terima[$i]['no_btg'] : '' ?></td>
        <td class='td-kecil text-center'><?= isset($terima[$i]['no_produksi']) ? $terima[$i]['no_produksi'] : '' ?></td>
        <td class='td-kecil text-center'><?= isset($terima[$i]['no_lap']) ? $terima[$i]['no_lap'] : '' ?></td>
        <td class='td-kecil text-center'><?= isset($terima[$i]['no_barcode']) ? $terima[$i]['no_barcode'] : '' ?></td>
        <td class='td-kecil text-center'><?= isset($terima[$i]['kayu_nama']) ? $terima[$i]['kayu_nama'] : '' ?></td>
        <td class='td-kecil text-center'><?= isset($terima[$i]['panjang']) ? $terima[$i]['panjang'] : '' ?></td>
        <td class='td-kecil text-right'><?= isset($terima[$i]['diameter_ujung1']) ? $terima[$i]['diameter_ujung1'] : '' ?></td>
        <td class='td-kecil text-right'><?= isset($terima[$i]['diameter_ujung2']) ? $terima[$i]['diameter_ujung2'] : '' ?></td>
        <td class='td-kecil text-right'><?= isset($terima[$i]['diameter_pangkal1']) ? $terima[$i]['diameter_pangkal1'] : '' ?></td>
        <td class='td-kecil text-right'><?= isset($terima[$i]['diameter_pangkal2']) ? $terima[$i]['diameter_pangkal2'] : '' ?></td>
        <td class='td-kecil text-right'><?= isset($terima[$i]['cacat_panjang']) ? $terima[$i]['cacat_panjang'] : '' ?></td>
        <td class='td-kecil text-right'><?= isset($terima[$i]['cacat_gb']) ? $terima[$i]['cacat_gb'] : '' ?></td>
        <td class='td-kecil text-right'><?= isset($terima[$i]['cacat_gr']) ? $terima[$i]['cacat_gr'] : '' ?></td>
        <td class='td-kecil text-right'><?= isset($terima[$i]['volume']) ? $terima[$i]['volume'] : '' ?></td>
    </tr>
<?php endfor; ?>