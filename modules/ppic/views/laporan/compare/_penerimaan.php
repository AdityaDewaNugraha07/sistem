<?php if(isset($terima)) : ?>
<tr>
    <td class='td-kecil text-center no' style='height: 30px;'><?= $i ?></td>
    <td class='td-kecil text-center penerimaan'><?= $terima['no_grade'] ?></td>
    <td class='td-kecil text-center penerimaan'><?= $terima['no_btg'] ?></td>
    <td class='td-kecil text-center penerimaan'><?= $terima['no_produksi'] ?></td>
    <td class='td-kecil text-center penerimaan'><?= $terima['no_lap'] ?></td>
    <td class='td-kecil text-left penerimaan'><?= $terima['no_barcode'] ?></td>
    <td class='td-kecil text-center penerimaan'><?= $terima['kayu_nama'] ?></td>
    <td class='td-kecil text-center penerimaan'><?= $terima['panjang'] ?></td>
    <td class='td-kecil text-right penerimaan'><?= $terima['diameter_ujung1'] ?></td>
    <td class='td-kecil text-right penerimaan'><?= $terima['diameter_ujung2'] ?></td>
    <td class='td-kecil text-right penerimaan'><?= $terima['diameter_pangkal1'] ?></td>
    <td class='td-kecil text-right penerimaan'><?= $terima['diameter_pangkal2'] ?></td>
    <td class='td-kecil text-right penerimaan'><?= $terima['cacat_panjang'] ?></td>
    <td class='td-kecil text-right penerimaan'><?= $terima['cacat_gb'] ?></td>
    <td class='td-kecil text-right penerimaan'><?= $terima['cacat_gr'] ?></td>
    <td class='td-kecil text-right penerimaan'><?= $terima['volume'] ?></td>
</tr>
<?php else: ?>
<tr>
    <td class='td-kecil text-center no' style='height: 30px;'><?= $i ?></td>
    <?php for($i = 0; $i < 15; $i++): ?>
        <td class='td-kecil text-center penerimaan'></td>
    <?php endfor; ?>
</tr>
<?php endif; ?>