<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]spm_kod_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_hpp"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_jual"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]keterangan"); ?>
        <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]nomor_palet_exist") ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?php
		$modJasa = \app\models\MProdukJasa::findOne($modDetail->produk_id);
        $itemproduk = [$modJasa->produk_jasa_id=>$modJasa->kode." - ".$modJasa->nama];
		?>
        
        <?php if(!empty($model->spm_ko_id)){
            echo "<b>".$modJasa->kode.'</b> - '.$modJasa->nama.' <a onclick="listPaletTerima(this)">(Lihat Palet)</a>';
        }else{ ?>
        <span class="input-group-btn" style="width: 100%">
            <?= yii\helpers\Html::activeDropDownList($modDetail, "[".$i."]produk_id",$itemproduk,['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;','disabled'=>true]) ?>
        </span>
        <span class="input-group-btn btn-random" style="width: 10%;">
                <a class="btn btn-default tooltips" onclick="listPaletTerima(this);" data-original-title="Lihat Palet Dari Penerimaan Customer" style="margin-left: 0px; border-radius: 4px; font-size: 1rem; line-height: 1">Pilih<br>Palet</a>
		</span>
        <?php } ?>
        
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-center">
		<?php
        echo yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]qty_besar",['class'=>'form-control float','style'=>'font-size:1.1rem; width:100%; padding:3px;','disabled'=>'disabled']);
        ?>
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-right">
		<?php
        echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]qty_kecil",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; width:100%;","disabled"=>"disabled"]);
        echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil");
        ?>
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-right">
		<?php
        echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]kubikasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
        ?>
    </td>
    <td class="text-align-right td-kecil" style="background-color: #B6D25D;">
		<?php
        if($model->status == app\models\TSpmKo::REALISASI){
            echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar_realisasi)."</b>";
        }else{
            if(!empty($realisasi)){
                echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]qty_besar_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;"]);
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
            echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil_realisasi) ." <i>(".(!empty($modDetail->satuan_kecil_realisasi)?$modDetail->satuan_kecil_realisasi:"Pcs").")</i>"."</b>";
        }else{
            if(!empty($realisasi)){
                echo '<div class="input-group">';
                echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]qty_kecil_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; width:100%;"]);
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
            echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->kubikasi_realisasi)."</b>";
        }else{
            if(!empty($realisasi)){
                echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]kubikasi_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;"]);
            }else{
                echo "-";
            }
        }
		?>
	</td>
    <td style="vertical-align: middle; text-align: center; width: 40px;" class="td-kecil">
        <?php
        if($model->status == app\models\TSpmKo::REALISASI){
            "-";
        }else{
            echo '<center><a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a></center>';
        }
		?>
		<?php // echo '<center><a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a></center>'; ?>
    </td>
</tr>
<script>

</script>
