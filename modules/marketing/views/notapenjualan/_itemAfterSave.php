<?php if($model->jenis_produk == "Log"){ ?>
    <tr>
        <td style="vertical-align: middle; text-align: center;" class="">
            <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
            <span class="no_urut"><?= $i+1; ?></span>
        </td>
        <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]ppn",['class'=>'float']); ?>
            <?php echo $modKayu->group_kayu .'-'. $modKayu->kayu_nama; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil">
            <?php echo '<b>'.$modSpmLog->no_barcode.'</b><br>'.$modSpmLog->no_lap.'<br>'.$modSpmLog->no_grade.'<br>'.$modSpmLog->no_btg; ?>
        </td>
        <td style="vertical-align: middle; text-align: right;" class="td-kecil">
            <?php echo $modDetail->qty_kecil .'<i>('. $modDetail->satuan_kecil .')</i>'; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo $modDetail->log->range_awal .' - '. $modDetail->log->range_akhir; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo $modSpmLog->panjang; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo $modSpmLog->diameter_ujung1; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo $modSpmLog->diameter_ujung2; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo $modSpmLog->diameter_pangkal1; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo $modSpmLog->diameter_pangkal2; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo $modSpmLog->diameter_rata; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo $modSpmLog->cacat_panjang; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo $modSpmLog->cacat_gb; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo $modSpmLog->cacat_gr; ?>
        </td>
        <td style="vertical-align: middle; text-align: center;" class="td-kecil">
            <?php echo number_format($modDetail->kubikasi, 2); ?>
        </td>
        <td style="vertical-align: middle; text-align: right;" class="td-kecil">
            <?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual) ?>
        </td>
        <td style="vertical-align: middle; text-align: right;" class="td-kecil">
            <?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->subtotal) ?>
        </td>
    </tr>
<?php } else { ?>
    <tr>
        <td style="vertical-align: middle; text-align: center;" class="">
            <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
            <span class="no_urut"><?= $i+1; ?></span>
        </td>
        <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]ppn",['class'=>'float']); ?>
            <?php
            if($model->jenis_produk == "Limbah"){
                echo $modDetail->limbah->limbah_kode;
            }else if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                echo "<b>".$modDetail->produkJasa->kode;
            }else{
                echo $modDetail->produk->produk_kode;
            }
            ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil">
            <?php
            if($model->jenis_produk == "Limbah"){
                echo $modDetail->limbah->limbah_nama." (".$modDetail->limbah->limbah_produk_jenis.") ";
            }else if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                echo $modDetail->produkJasa->nama;
            }else{
                echo $modDetail->produk->produk_nama;
            }
            ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-right">
            <?php echo ($model->jenis_produk == "Limbah")?"":app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar); ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-right">
            <?= ($model->jenis_produk == "JasaGesek")? "-" : app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil)."<i> (". (!empty($modDetail->satuan_kecil)?$modDetail->satuan_kecil:"Pcs") .")</i>"; ?> 
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-right">
            <?php echo ($model->jenis_produk == "Limbah")? ( ($modDetail->satuan_kecil=="Rit")?$modDetail->satuan_besar:"" ) :number_format($modDetail->kubikasi,4); ?>
        </td>
        <td class="text-align-right td-kecil">
            <?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual) ?>
        </td>
        <td class="text-align-right td-kecil">
            <?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->subtotal) ?>
        </td>
    </tr>
<?php } ?>
<script>

</script>
