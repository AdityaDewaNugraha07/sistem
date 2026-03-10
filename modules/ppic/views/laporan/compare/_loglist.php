<?php if(isset($loglist)) : ?>
<tr>
    <td class='td-kecil text-center no' style='height: 30px;'><?= $i ?></td>
    <td class='td-kecil text-center loglist'><?= $loglist['nomor_grd'] ?></td>
    <td class='td-kecil text-center loglist'><?= $loglist['nomor_batang'] ?></td>
    <td class='td-kecil text-center loglist'><?= $loglist['nomor_produksi'] ?></td>
    <td class='td-kecil text-center loglist'><?= $loglist['kayu_nama'] ?></td>
    <td class='td-kecil text-right loglist'><?= $loglist['panjang'] ?></td>
    <td class='td-kecil text-right loglist'><?= $loglist['cacat_panjang'] ?></td>
    <td class='td-kecil text-right loglist'><?= $loglist['cacat_gb'] ?></td>
    <td class='td-kecil text-right loglist'><?= $loglist['cacat_gr'] ?></td>
    <td class='td-kecil text-right loglist'><?= $loglist['diameter_rata'] ?></td>
    <td class='td-kecil text-right loglist'><?= $loglist['volume_value'] ?></td>
</tr>
<?php else: ?>
<tr>
    <td class='td-kecil text-center no' style='height: 30px;'><?= $i ?></td>
    <?php for($i = 0; $i < 10; $i++): ?>
        <td class='td-kecil text-center loglist'></td>
    <?php endfor; ?>
</tr>
<?php endif; ?>