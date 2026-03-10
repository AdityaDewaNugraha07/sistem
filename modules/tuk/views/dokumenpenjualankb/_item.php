<?php
// $modJualKb = Yii::$app->db->createCommand("
//                 SELECT * FROM t_spm_log
//                 WHERE reff_no = '{$modSpm->kode}'
//             ")->queryAll();
// $modBrgLog = Yii::$app->db->createCommand(
//                     "SELECT * FROM m_brg_log 
//                     JOIN t_nota_penjualan_detail ON t_nota_penjualan_detail.produk_id = m_brg_log.log_id
//                     WHERE nota_penjualan_id = {$nota_penjualan_id}"
//                 )->queryAll();
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]spm_kod_id",[]); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]keterangan"); ?>
        <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]spm_log_id"); ?>
        <?php 
            echo $modKayu->group_kayu .' - '. $modKayu->kayu_nama;
            // echo $modDetail->log->log_nama; 
        ?>
    </td>
    <!-- <td style="vertical-align: top;" class="td-kecil">
        <?php
            //echo '<center>'. $modDetail->log->range_awal .'cm - ' . $modDetail->log->range_akhir . 'cm <center><br>';
		?>
    </td> -->
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            // echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil). "<i>(".$modDetail->satuan_kecil .")</i>"
            echo $modSpmLog->no_barcode;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->no_lap;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->no_grade;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->no_btg;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->diameter_ujung1;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->diameter_ujung2;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->diameter_pangkal1;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->diameter_pangkal2;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->cacat_panjang;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->cacat_gb;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->cacat_gr;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->panjang;
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-center">
        <?php 
            echo $modSpmLog->diameter_rata;
        ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
        <?php 
            echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->kubikasi);
            // foreach($modJualKb as $iii => $jualKb){
            //     echo number_format($jualKb['volume'],2);
            //     echo "<br>";
            // }
        ?>
    </td>
	<td class="text-align-right td-kecil">
		<?= $modDetail->keterangan ?>
	</td>
</tr>
<script>

</script>
