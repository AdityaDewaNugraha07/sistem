<?php
if($modNota->jenis_produk == "JasaKD" || $modNota->jenis_produk == "JasaGesek" || $modNota->jenis_produk == "JasaMoulding"){
    $modPaletKD = Yii::$app->db->createCommand("
                SELECT * FROM t_terima_jasa
                WHERE op_ko_id = '{$modNota->op_ko_id}' AND produk_jasa_id = {$modDetail->produk_id} AND nomor_palet IN(".(!empty($modSPMDetail)?$modSPMDetail->keterangan:"'3'").")
            ")->queryAll();
}
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
		<?php
        if($modNota->jenis_produk == "JasaKD" || $modNota->jenis_produk == "JasaGesek" || $modNota->jenis_produk == "JasaMoulding"){
            echo $modDetail->produkJasa->nama;
        }else{
            echo $modDetail->produk->produk_nama;
        }
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil">
        <?php
        if($modNota->jenis_produk == "JasaKD" || $modNota->jenis_produk == "JasaGesek" || $modNota->jenis_produk == "JasaMoulding"){
            foreach($modPaletKD as $iii => $paletKD){
                echo " - ".$paletKD['t']." ".$paletKD['t_satuan']." x ".$paletKD['l']." ".$paletKD['l_satuan']." x ".$paletKD['p']." ".$paletKD['p_satuan'];
                echo "<br>";
            }
        }else{
            echo str_replace(" 0 ", "~", $modDetail->produk->produk_dimensi);
        }
        ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil text-align-right">
        <?php
        if($modNota->jenis_produk == "JasaKD" || $modNota->jenis_produk == "JasaGesek" || $modNota->jenis_produk == "JasaMoulding"){
            foreach($modPaletKD as $iii => $paletKD){
                echo $paletKD['qty_kecil']." (".$paletKD['satuan_kecil'].")";
                echo "<br>";
            }
        }else{
            echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil). "<i>(".$modDetail->satuan_kecil .")</i>";
        }
        ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
        <?php
        if($modNota->jenis_produk == "JasaKD" || $modNota->jenis_produk == "JasaGesek" || $modNota->jenis_produk == "JasaMoulding"){
            foreach($modPaletKD as $iii => $paletKD){
                echo number_format($paletKD['kubikasi'],4);
                echo "<br>";
            }
        }else{
            echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->kubikasi);
        }
        ?>
    </td>
	<td class="text-align-right td-kecil">
		<?= $modDetail->keterangan ?>
	</td>
</tr>
<script>

</script>
