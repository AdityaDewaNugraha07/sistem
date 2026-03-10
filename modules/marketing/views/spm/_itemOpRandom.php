<tr>
    <td style="vertical-align: middle; text-align: center; padding: 1px;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
	<?php
	$dotted = "";
	if(($ii!=0)){
		if($modRandom[($ii-1)]['nomor_produksi']!=$random['nomor_produksi']){
			$res = $random['nomor_produksi']." : ";
			$dotted = "border-top: 2px dotted #777";
		}else{
			$res = "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
		}
	}else{
		$res = $random['nomor_produksi']." : ";
	}
	?>
    <td style="vertical-align: middle; padding: 1px; <?= $dotted ?>" id="item-detail" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($random, "[".$i."]op_ko_random_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($random, "[".$i."]op_ko_detail_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($random, "[".$i."]produk_id"); ?>
		<?php
		echo "&nbsp; <b>".$res."</b> - <span style='font-size:1.1rem;'>".$random['t']." ".$random['t_satuan']." X ".$random['l']." ".$random['l_satuan']." X ".$random['p']." ".$random['p_satuan']."<span><br>";
		$random->qty_besar = '-';
		?>
    </td>
    <td style="vertical-align: middle; background-color: #FFF7DE; padding: 1px;" class="td-kecil text-align-center">
		<?php
		if(!empty($edit)){
			echo yii\bootstrap\Html::activeTextInput($random, "[".$i."]qty_besar",['class'=>'form-control float','style'=>'font-size:1.1rem; width:50px; padding:3px; height: 22px;','disabled'=>'disabled']);
		}else{
			echo "-";
		}
		?>
    </td>
    <td style="vertical-align: middle; background-color: #FFF7DE; padding: 1px;" class="td-kecil text-align-right">
		<?php
		if(!empty($edit)){
			echo '<div class="input-group">';
			echo \yii\bootstrap\Html::activeTextInput($random, "[".$i."]qty_kecil",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; width:100%; height: 22px;","disabled"=>"disabled"]);
			echo \yii\bootstrap\Html::activeHiddenInput($random, "[".$i."]satuan_kecil");
			echo "<span class='input-group-addon' style='width=10%; padding: 1px; font-size:1.1rem;'>";
			echo "".$random->satuan_kecil."";
			echo "</span>";
			echo '</div>';
		}else{
			echo app\components\DeltaFormatter::formatNumberForUserFloat($random['qty_kecil'])." <i>(".$random['satuan_kecil'].")</i>";
		}
		?>
    </td>
    <td style="vertical-align: middle; background-color: #FFF7DE; padding: 1px;" class="td-kecil text-align-right">
		<?php
		if(!empty($edit)){
			echo \yii\bootstrap\Html::activeTextInput($random, "[".$i."]kubikasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; height: 22px;","disabled"=>"disabled"]);
		}else{
			echo app\components\DeltaFormatter::formatNumberForUserFloat($random['kubikasi']);
		}
		?>
    </td>
	<td class="text-align-right td-kecil" style="background-color: #EEF7D0; padding: 1px;">
		<?php
		if($model->status == app\models\TSpmKo::REALISASI){
			echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($random->qty_besar_realisasi)."</b>";
		}else{
			if(!empty($edit)){
				$random->qty_besar_realisasi = '-';
				echo \yii\helpers\Html::activeTextInput($random, "[".$i."]qty_besar_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; height: 22px;","disabled"=>"disabled"]);
			}else{
				echo "-";
			}
		}
		?>
	</td>
	<td class="text-align-right td-kecil" style="background-color: #EEF7D0; padding: 1px;">
		<?php
		$random->qty_kecil_realisasi = $random->qty_kecil;
		$random->satuan_kecil_realisasi = $random->satuan_kecil;
		$random->kubikasi_realisasi = $random->kubikasi;
		if($model->status == app\models\TSpmKo::REALISASI){
			echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($random->qty_kecil_realisasi) ." <i>(".$random->satuan_kecil_realisasi.")</i>"."</b>";
		}else{
			if(!empty($edit)){
				echo '<div class="input-group">';
				echo \yii\bootstrap\Html::activeTextInput($random, "[".$i."]qty_kecil_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; width:100%; height: 22px;","disabled"=>"disabled"]);
				echo \yii\bootstrap\Html::activeHiddenInput($random, "[".$i."]satuan_kecil_realisasi");
				echo "<span class='input-group-addon' style='width=10%; padding: 1px; font-size:1.1rem;'>";
				echo "".$random->satuan_kecil_realisasi."";
				echo "</span>";
				echo '</div>';
			}else{
				echo "-";
			}
		}
		?>
	</td>
	<td class="text-align-right td-kecil" style="background-color: #EEF7D0; padding: 1px;">
		<?php
		if($model->status == app\models\TSpmKo::REALISASI){
			echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($random->kubikasi_realisasi)."</b>";
		}else{
			if(!empty($edit)){
				echo \yii\bootstrap\Html::activeTextInput($random, "[".$i."]kubikasi_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; height: 22px;","disabled"=>"disabled"]);
			}else{
				echo "-";
			}
		}
		?>
	</td>
</tr>