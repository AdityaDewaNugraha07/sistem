<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]po_ko_detail_id") ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
        <span><?= $modDetail->produk_alias ?></span>
		<span style="float: right;">
            <input type="checkbox" name="alias" class="custom-checkbox" <?= $modDetail->alias?'checked':''; ?> disabled/>
        </span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
        <?php
        if($modDetail->produk_id_alias){
            $produk_ids = explode(',', $modDetail->produk_id_alias);
            $produk_id = $produk_ids[0];
        } else {
            $produk_id = $modDetail->produk_id;
        }
        $mod = app\models\MBrgLog::findOne($produk_id);
        $r_diameter = $mod->range_awal.'-'.$mod->range_akhir;
        $range_diameter = $modDetail->range_diameter?$modDetail->range_diameter:$r_diameter; 
        echo $range_diameter; 
        ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
		<?= $modDetail->diameter_alias; ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
        <input type="checkbox" class="custom-checkbox" <?= $modDetail->fsc?'checked':''; ?> disabled/>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?php
        if($modDetail->poKo->jenis_produk == "Log"){
            if(!$modDetail->produk_id){
                $produk_ids = explode(',', $modDetail->produk_id_alias);
                $log_namas = [];
                foreach($produk_ids as $p => $log_id){
                    $modLog = app\models\MBrgLog::findOne($log_id);
                    $log_namas[] = $modLog->log_nama;
                }
                echo implode('<br>', $log_namas);
            } else {
                echo $modDetail->log->log_nama;
            }
        }else{
            echo $modDetail->produk->produk_kode;
        }
        ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
        <?= $modDetail->komposisi ?>
        <input type="hidden" name="TPoKoDetail[<?= $i ?>][komposisi]" value="<?= $modDetail->komposisi ?>" onblur="hitungTotal()">
    </td>
	<td class="td-kecil text-align-right">
        <?= $modDetail->kubikasi ?>
        <input type="hidden" name="TPoKoDetail[<?= $i ?>][kubikasi]" value="<?= $modDetail->kubikasi ?>">
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga) ?>
        <input type="hidden" name="TPoKoDetail[<?= $i ?>][harga]" value="<?= $modDetail->harga ?>">
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?php 
        $subtotal = $modDetail->kubikasi * $modDetail->harga;
        echo app\components\DeltaFormatter::formatNumberForUserFloat($subtotal);
        ?>
    </td>
	<td><?php echo '<center>-</center>'; ?></td>
</tr>
<script>

</script>