<?php
/** @var integer $i */
/** @var TPackinglist $modPackinglist */

/** @var TInvoiceDetail $modDetail */

use app\models\TInvoiceDetail;
use app\models\TPackinglist;
use yii\bootstrap\Html;

?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= Html::hiddenInput('no_urut', null, ['id' => 'no_urut', 'style' => 'width:30px;']) ?>
        <span class="no_urut"><?= $i + 1 ?></span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?= Html::activeHiddenInput($modDetail, "[" . $i . "]produk_id", ['class' => 'float']) ?>
        <?= Html::activeHiddenInput($modDetail, "[" . $i . "]qty_besar", ['class' => 'float']) ?>
        <?= Html::activeHiddenInput($modDetail, "[" . $i . "]satuan_besar") ?>
        <?= Html::activeHiddenInput($modDetail, "[" . $i . "]qty_kecil", ['class' => 'float']) ?>
        <?= Html::activeHiddenInput($modDetail, "[" . $i . "]satuan_kecil") ?>
        <?= Html::activeHiddenInput($modDetail, "[" . $i . "]kubikasi", ['class' => 'float']) ?>
        <?= Html::activeHiddenInput($modDetail, "[" . $i . "]harga_hpp", ['class' => 'float']) ?>
        <?= Html::activeHiddenInput($modDetail, "[" . $i . "]ppn", ['class' => 'float']) ?>
        <?php
        $modDetail->keterangan = !empty($modDetail->keterangan) ? $modDetail->keterangan : $modDetail->produk->produk_nama;
        echo \yii\helpers\Html::activeTextInput($modDetail, "[" . $i . "]keterangan", ['class' => 'form-control', 'style' => "padding:2px;"]);
        ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
        <?php echo $modDetail->produk->grade; ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
        <?php
        $tebal = "";
        $lebar = "";
        $tinggi = "";
        $panjang = "";
        if ($modDetail->produk->produk_t === 0) {
            try {
                $sql = "
                    SELECT bundles_no,MIN(thick), MAX(thick) 
                    FROM t_packinglist_container 
                    WHERE packinglist_id = $modPackinglist->packinglist_id 
                        AND grade = '{$modDetail->produk->grade}' 
                        AND jenis_kayu = '{$modDetail->produk->jenis_kayu}' 
                        AND profil_kayu = '{$modDetail->produk->profil_kayu}'
                    GROUP BY 1
                    HAVING COUNT(bundles_no)>1
                ";
                $minmax = Yii::$app->db->createCommand($sql)->queryOne();
                if (!empty($minmax)) {
                    if (($minmax['min'] === $minmax['max'])) {
                        $tebal = $minmax['min'] . " " . $modDetail->produk->produk_t_satuan;
                    } else {
                        $tebal = $minmax['min'] . $modDetail->produk->produk_t_satuan . "~" . $minmax['max'] . $modDetail->produk->produk_t_satuan;
                    }
                }
            } catch (\yii\db\Exception $exception) {
                echo $exception->getMessage();
            }
        } else {
            $tebal = $modDetail->produk->produk_t . " " . $modDetail->produk->produk_t_satuan;
        }
        if ($modDetail->produk->produk_l === 0) {
            try {
                $sql = "
                    SELECT bundles_no,MIN(width), MAX(width) 
                    FROM t_packinglist_container 									  
                    WHERE packinglist_id = $modPackinglist->packinglist_id 
                        AND grade = '{$modDetail->produk->grade}' 
                        AND jenis_kayu = '{$modDetail->produk->jenis_kayu}' 
                        AND profil_kayu = '{$modDetail->produk->profil_kayu}' 
                    GROUP BY 1
                    HAVING COUNT(bundles_no)>1
                ";
                $minmax = Yii::$app->db->createCommand($sql)->queryOne();
                if (!empty($minmax)) {
                    if (($minmax['min'] === $minmax['max'])) {
                        $lebar = $minmax['min'] . " " . $modDetail->produk->produk_l_satuan;
                    } else {
                        $lebar = $minmax['min'] . $modDetail->produk->produk_l_satuan . "~" . $minmax['max'] . $modDetail->produk->produk_l_satuan;
                    }
                }
            } catch (\yii\db\Exception $exception) {
                echo $exception->getMessage();
            }
        } else {
            $lebar = $modDetail->produk->produk_l . " " . $modDetail->produk->produk_l_satuan;
        }
        if ($modDetail->produk->produk_p === 0) {
            try {
                $sql = "
                    SELECT bundles_no,MIN(length), MAX(length) 
                    FROM t_packinglist_container 
					WHERE packinglist_id = $modPackinglist->packinglist_id 
						AND grade = '{$modDetail->produk->grade}' 
						AND jenis_kayu = '{$modDetail->produk->jenis_kayu}' 
						AND profil_kayu = '{$modDetail->produk->profil_kayu}'
                    GROUP BY 1
                    HAVING COUNT(bundles_no)>1
                ";
                $minmax = Yii::$app->db->createCommand($sql)->queryOne();
                if (!empty($minmax)) {
                    if (($minmax['min'] === $minmax['max'])) {
                        $panjang = $minmax['min'] . " " . $modDetail->produk->produk_p_satuan;
                    } else {
                        $panjang = $minmax['min'] . $modDetail->produk->produk_p_satuan . "~" . $minmax['max'] . $modDetail->produk->produk_p_satuan;
                    }
                }
            } catch (\yii\db\Exception $exception) {
                echo $exception->getMessage();
            }
        } else {
            $panjang = $modDetail->produk->produk_p . " " . $modDetail->produk->produk_p_satuan;
        }
        echo $tebal . " x " . $lebar . " x " . $panjang;
        ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
        <?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
        <?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil) ?>
        <i>(<?= $modDetail->satuan_kecil ?>)</i>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
        <?= Html::activeHiddenInput($modDetail, "[" . $i . "]kubikasi_display", ['class' => '']) ?>
        <?= number_format($modDetail->kubikasi_display, 4) ?>
    </td>
    <td class="text-align-right td-kecil">
        <?php
        echo \yii\helpers\Html::activeTextInput($modDetail, "[" . $i . "]harga_jual", ['class' => 'form-control float', 'style' => "padding:2px;", 'onblur' => 'subTotal();']);
        ?>
    </td>
    <td class="text-align-right td-kecil">
        <?php
        echo Html::activeHiddenInput($modDetail, "[" . $i . "]subtotal", ['class' => 'float']);
        echo \yii\helpers\Html::activeTextInput($modDetail, "[" . $i . "]subtotal_display", ['class' => 'form-control float', 'style' => "padding:2px;", 'disabled' => 'disabled']);
        ?>
    </td>
</tr>
<script>

</script>
