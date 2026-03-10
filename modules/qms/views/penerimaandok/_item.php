<?php
if(count($model) > 0){
    foreach($model as $i => $data){ ?>
        <tr>
            <td class="text-align-center td-kecil"><?= $i+1; ?></td>
            <td class="td-kecil"><?= $data['nama_dokumen']; ?></td>
            <td class="text-align-center td-kecil"><?= $data['revisi_ke']; ?></td>
            <td class="text-align-center td-kecil"><?= $data['nomor_dokumen']; ?></td>
            <td class="text-align-center td-kecil"><?= $data['jenis_dokumen']; ?></td>
            <td class="text-align-center td-kecil"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal_dikirim']); ?></td>
            <td class="text-align-center td-kecil"><?= $data['dikirim_oleh']; ?></td>
            <td class="text-align-center td-kecil"><?= $data['pic_iso']; ?></td>
            <td class="text-align-center td-kecil">
                <a href="javascript:void(0);" onclick="terimaDokumen(<?= $data['dokumen_distribusi_id']; ?>);" class="label label-info" style="font-size: 1.0rem;">TERIMA</a>
            </td>
        </tr>
<?php }
} else { ?>
    <tr>
        <td colspan="9" class="text-align-center">Data tidak ditemukan</td>
    </tr>
<?php } ?>