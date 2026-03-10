<?php

/**
 * @var array $models
 * @var MHargaProduk $modHarga
 */
use app\components\DeltaFormatter;
use app\models\MHargaProduk;
use yii\bootstrap\Html;

$xxx = 1;
$total = 0;
?>

<?php if (isset($models) && count($models) > 0): ?>
    <?php foreach ($models as $i => $produk) : ?>
        <tr>
            <td style="text-align:center">
                <?= $i + 1 ?>
                <?= Html::activeHiddenInput($modHarga, '[' . $i . ']produk_id', ['value' => $produk['produk_id']]) ?>
            </td>
            <td>
                <?= $produk['produk_nama'] ?>
            </td>
            <td>
                <?= $produk['produk_kode'] ?>
            </td>
            <td style="text-align: right; padding-right: 15px;">
                <?php
                $sql = "select harga_enduser from m_harga_produk where produk_id = " . $produk['produk_id'] . " and kode = '" . $kode . "' ";
                try {
                    $harga_produk = Yii::$app->db->createCommand($sql)->queryScalar();
                } catch (\yii\db\Exception $e) {
                }
                echo DeltaFormatter::formatNumberForAllUser($harga_produk);
                ?>
                <label id="label-<?= $i ?>-harga_enduser"></label>
                <?= yii\bootstrap\Html::activeTextInput($modHarga, '[' . $i . ']harga_enduser', ['class' => 'form-control money-format', 'value' => '', 'style' => 'display:none;']); ?>
            </td>
        </tr>
    <?php endforeach ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center" style="font-style: italic">Tidak ada data</td>
    </tr>
<?php endif ?>
<tr>
    <td colspan="3" class="text-center"><b>Total</b></td>
    <td class="text-right form-control money-format" style="padding-right: 15px;"><b><?php echo DeltaFormatter::formatNumberForAllUser($total);?></b></td>
</tr>