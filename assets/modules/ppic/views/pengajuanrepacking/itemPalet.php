<?php
if(!empty($model->terimamutasi_hasilrepacking_id)){
    if(!empty($edit)){
        $disabled = false;
    }else{
        $disabled = true;
    }
}else{
    $disabled = false;
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
        <span class="no_urut"></span>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]pengajuan_repacking_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]mutasi_keluar_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]terima_mutasi_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]hasil_repacking_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]nomor_produksi_lama") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]nomor_produksi_baru") ?>
    </td>
    <td style="text-align: left;" class="td-kecil">
        <b><?= $modPengajuan->kode ?></b><br>
        <?= app\components\DeltaFormatter::formatDateTimeForUser2($modPengajuan->tanggal) ?>
    </td>
    <td style="text-align: left;" class="td-kecil">
        <b><?= $model->nomor_produksi_lama ?></b><br>
        <?= $modProduk->produk_nama ?>
    </td>
    <td style="text-align: center;" class="td-kecil">
        <?= app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_kecil) ?>
    </td>
    <td style="text-align: right;" class="td-kecil">
        <?= number_format($model->qty_m3,4) ?>
    </td>
    <td style="text-align: center;" class="td-kecil">
        <?= app\components\DeltaFormatter::formatDateTimeForUser2($modMutasiKeluar->tanggal) ?>
    </td>
    <td style="text-align: center;" class="td-kecil">
        <?= app\components\DeltaFormatter::formatDateTimeForUser2($modTerimaMutasi->tanggal) ?>
    </td>
    <td style="text-align: center;" class="td-kecil">
        <?php
        if($disabled==true){
            echo '<center><a class="btn btn-xs grey"><i class="fa fa-remove"></i></a></center>';
        }else{
            echo '<center><a class="btn btn-xs red" onclick="cancelItemThis2(this)"><i class="fa fa-remove"></i></a></center>';
        }
        ?>
    </td>
</tr>
<script>

</script>
