<?php
/** @var TProdukKembali $model */

use app\components\DeltaFormatter;
use app\models\TProdukKembali;
use app\models\TSpmKo;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= Html::hiddenInput('no_urut', null, ['id' => 'no_urut', 'style' => 'width:30px;']) ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil text-align-center">
        <?= Html::activeHiddenInput($model, "[ii]produk_kembali_id") ?>
        <b class="" style="font-size: 1.5rem;"><a onclick="infoPalet('<?= $model->nomor_produksi ?>')"><?= $model->nomor_produksi ?></a></b>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
        <?= Html::activeHiddenInput($model, "[ii]qty_besar") ?>
        <?= $model->qty_besar ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
        <?= Html::activeHiddenInput($model, "[ii]qty_kecil") ?>
        <?= Html::activeHiddenInput($model, "[ii]satuan_kecil") ?>
        <?= DeltaFormatter::formatNumberForUserFloat($model->qty_kecil) ?>
        <i>(<?= $model->satuan_kecil ?>)</i>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
        <?= Html::activeHiddenInput($model, "[ii]kubikasi") ?>
        <?= DeltaFormatter::formatNumberForUserFloat($model->kubikasi, 4) ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php
        $modSpm = TSpmKo::findOne(['kode' => $model->reff_no]);
        if ($modSpm !== null && $modSpm->status === TSpmKo::REALISASI) {
            echo '<a class="btn btn-xs grey"><i class="fa fa-trash-o"></i></a>';
        } else {
            echo '<a class="btn btn-xs red" onclick="hapusItem(this);"><i class="fa fa-trash-o"></i></a>';
        }
        ?>
    </td>
</tr>
<script>
    function infoPalet(nomor_produksi) {
        openModal('<?= Url::toRoute(['/marketing/spm/infoPalet', 'nomor_produksi' => '']) ?>' + nomor_produksi, 'modal-info-palet', '90%');
    }
</script>
