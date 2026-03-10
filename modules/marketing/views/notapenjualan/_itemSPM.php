<?php

use app\models\HPersediaanLog;

 if($modOpKo->jenis_produk == "Log"){ ?>
    <tr>
        <td style="vertical-align: middle; text-align: center;" class="">
            <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
            <span class="no_urut"><?= $i+1; ?></span>
        </td>
        <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id",['class'=>'float']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar",['class'=>'float']); ?> 
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar"); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil",['class'=>'float total-log']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil"); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi",['class'=>'float total-log']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_hpp",['class'=>'float']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]ppn",['class'=>'float']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]spm_log_id"); ?>
            <?php echo $modKayu['group_kayu'] .'-'. $modKayu['kayu_nama']; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil">
            <?php echo '<b>'.$spmlog['no_barcode']. '</b><br>' . $spmlog['no_lap']. '<br>' .$spmlog['no_grade']. '<br>' .$spmlog['no_btg']?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?=  "1<i>(Pcs)</i>"; ?> 
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?=  $produk['range_awal'].' - '.$produk['range_akhir']; ?> 
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php echo $spmlog['panjang']; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php echo $spmlog['diameter_ujung1']; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php echo $spmlog['diameter_ujung2']; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php echo $spmlog['diameter_pangkal1']; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php echo $spmlog['diameter_pangkal2']; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php echo $spmlog['diameter_rata']; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php echo $spmlog['cacat_panjang']; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php echo $spmlog['cacat_gb']; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php echo $spmlog['cacat_gr']; ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-center">
            <?php echo number_format($spmlog['volume'], 2); ?>
        </td>
        <td class="text-align-right td-kecil">
            <?php
            echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]harga_jual",['class'=>'form-control float total-log','style'=>"padding:2px; font-size:1.1rem;",'onblur'=>'subtotal();','disabled'=>'disabled']);
            ?>
        </td>
        <td class="text-align-right td-kecil">
            <?php
            echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]subtotal",['class'=>'form-control float total-log','style'=>"padding:2px; font-size:1.1rem;",'disabled'=>'disabled']);
            ?>
        </td>
    </tr>
<?php } else { ?>
    <tr>
        <td style="vertical-align: middle; text-align: center;" class="">
            <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
            <span class="no_urut"><?= $i+1; ?></span>
        </td>
        <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id",['class'=>'float']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar",['class'=>'float']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar"); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil",['class'=>'float total']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil"); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi",['class'=>'float total']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_hpp",['class'=>'float']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]ppn",['class'=>'float']); ?>
            <?php
            if($modOpKo->jenis_produk == "Limbah"){
                echo $modDetail->limbah->limbah_kode;
            }else if($modOpKo->jenis_produk == "JasaKD" || $modOpKo->jenis_produk == "JasaGesek" || $modOpKo->jenis_produk == "JasaMoulding"){
                echo "<b>".$modDetail->produkJasa->kode;
            }else{
                echo $modDetail->produk->produk_kode;
            }
            ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil">
            <?php
            if($modOpKo->jenis_produk == "Limbah"){
                echo $modDetail->limbah->limbah_nama;
            }else if($modOpKo->jenis_produk == "JasaKD" || $modOpKo->jenis_produk == "JasaGesek" || $modOpKo->jenis_produk == "JasaMoulding"){
                echo $modDetail->produkJasa->nama;
            }else{
                echo $modDetail->produk->produk_nama;
            }
            ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-right">
            <?php echo ($modOpKo->jenis_produk == "Limbah")?"":app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar); ?>
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-right">
            <?= ($modOpKo->jenis_produk == "JasaGesek")? "-" : app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil)."<i> (". (!empty($modDetail->satuan_kecil)?$modDetail->satuan_kecil:"Pcs") .")</i>"; ?> 
        </td>
        <td style="vertical-align: middle;" class="td-kecil text-align-right">
            <?php echo ($modOpKo->jenis_produk == "Limbah")? ( ($modDetail->satuan_kecil=="Rit")?$modDetail->satuan_besar:"" ) :number_format($modDetail->kubikasi,4); ?>
        </td>
        <td class="text-align-right td-kecil">
            <?php
            echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]harga_jual",['class'=>'form-control float total','style'=>"padding:2px; font-size:1.1rem;",'onblur'=>'subtotal();','disabled'=>'disabled']);
            ?>
        </td>
        <td class="text-align-right td-kecil">
            <?php
            echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]subtotal",['class'=>'form-control float total','style'=>"padding:2px; font-size:1.1rem;",'disabled'=>'disabled']);
            ?>
        </td>
    </tr>
<?php } ?>
<script>

</script>
