<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]spm_kod_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_hpp"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_jual"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]keterangan"); ?>
		<?php
		if(!empty($modDetail->produk_id)){
			if($is_random==true){
				echo "<b>".$modDetail->produk->produk_nama.'</b> <a onclick="listRandom(this)">(Random)</a> <span class="pull-right">Total Random &nbsp; </span>';
			}else{
				echo "<b>".$modDetail->produk->produk_nama."</b> (". str_replace(" ", "", $modDetail->produk->produk_dimensi).")";
			}
		}else{
			echo "<i><b class='font-red-flamingo'>Master Tidak Ditemukan!</b></i> (".$produkorder.")";
		}
		?>
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-center">
		<?php
		if(!empty($realisasi)){
			echo yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]qty_besar",['class'=>'form-control float','style'=>'font-size:1.1rem; width:50px; padding:3px;','disabled'=>'disabled']);
		}else{
			echo '<span id="place-qty_besar">'. app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar) .'</span>';
			echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar");
		}
		?>
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-right">
		<?php
		if(!empty($realisasi)){
			echo '<div class="input-group">';
			echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]qty_kecil",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; width:100%;","disabled"=>"disabled"]);
			echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil");
			echo "<span class='input-group-addon' style='width=10%; padding-left: 3px; padding-right: 3px; font-size:1.1rem;'>";
			echo "".$modDetail->satuan_kecil."";
			echo "</span>";
			echo '</div>';
		}else{
			echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil)." <i>(". $modDetail->satuan_kecil .")</i>";
			echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil");
			echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil");
		}
		?>
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-right">
		<?php
		$modDetail->kubikasi = number_format($modDetail->kubikasi,4);
		if(!empty($realisasi)){
			echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]kubikasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
		}else{
			echo $modDetail->kubikasi;
			echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi");
		}
		?>
    </td>
	<td class="text-align-right td-kecil" style="background-color: #B6D25D;">
		<?php
		if($model->status == app\models\TSpmKo::REALISASI){
			echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar_realisasi)."</b>";
		}else{
			if(!empty($realisasi)){
				echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]qty_besar_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
				echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar_realisasi");
			}else{
				echo "-";
			}
		}
		?>
	</td>
	<td class="text-align-right td-kecil" style="background-color: #B6D25D;">
		<?php
		if($model->status == app\models\TSpmKo::REALISASI){
			echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil_realisasi) ." <i>(".$modDetail->satuan_kecil_realisasi.")</i>"."</b>";
		}else{
			if(!empty($realisasi)){
				echo '<div class="input-group">';
				echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]qty_kecil_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; width:100%;","disabled"=>"disabled"]);
				echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil_realisasi");
				echo "<span class='input-group-addon' style='width=10%; padding-left: 3px; padding-right: 3px; font-size:1.1rem;'>";
				echo "".$modDetail->satuan_kecil_realisasi."";
				echo "</span>";
				echo '</div>';
			}else{
				echo "-";
			}
		}
		?>
	</td>
	<td class="text-align-right td-kecil" style="background-color: #B6D25D;">
		<?php
		if($model->status == app\models\TSpmKo::REALISASI){
			echo "<b>". number_format($modDetail->kubikasi_realisasi,4)."</b>";
		}else{
			if(!empty($realisasi)){
				$modDetail->kubikasi_realisasi_display = number_format($modDetail->kubikasi_realisasi,4);
				echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]kubikasi_realisasi_display",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
				echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi_realisasi");
			}else{
				echo "-";
			}
		}
		?>
	</td>
</tr>
<script>

</script>
