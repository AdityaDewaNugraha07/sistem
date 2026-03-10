<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi",['class'=>'float']); ?>
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
		echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]harga_jual",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;",'onblur'=>'subtotal();','disabled'=>'disabled']);
		?>
	</td>
	<td class="text-align-right td-kecil">
		<?php
		echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]subtotal",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;",'disabled'=>'disabled']);
		?>
	</td>
</tr>
<script>

</script>
