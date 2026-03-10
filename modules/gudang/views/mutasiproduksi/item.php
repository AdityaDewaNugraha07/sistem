<tr>
    <td style="vertical-align: middle; text-align: center; background-color: #E4EFFF;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
        <span class="no_urut"></span>
        <?= \yii\helpers\Html::activeHiddenInput($detail, "[ii]pengajuan_repacking_detail_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($detail, "[ii]produk_id") ?>
    </td>
    <td style="text-align: left; line-height: 1; background-color: #E4EFFF;" class="td-kecil">
        <b><?= $detail->produk->produk_kode ?></b><br>
        <?= $detail->produk->produk_nama ?>
    </td>
    <td style="text-align: left; line-height: 1; background-color: #E4EFFF;" class="td-kecil">
        <?= $detail->produk->produk_dimensi ?>
    </td>
    <td style="text-align: center; line-height: 1; background-color: #E4EFFF;" class="td-kecil">
        <?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_besar); ?>
    </td>
    <td style="text-align: center; line-height: 1; background-color: #E4EFFF;" class="td-kecil">
        <?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->keterangan); ?>
    </td>
</tr>
<script>

</script>
