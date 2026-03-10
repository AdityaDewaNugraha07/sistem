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
        <?php if($model->jenis_produk == "Log"){ ?>
        <?php echo yii\bootstrap\Html::activeHiddenInput($modLog, "[".$i."]log_nama"); ?>
        <?php echo yii\bootstrap\Html::activeHiddenInput($modLog, "[".$i."]range_awal"); ?>
        <?php echo yii\bootstrap\Html::activeHiddenInput($modLog, "[".$i."]range_akhir"); ?>
        <?php 
        $modPo = Yii::$app->db->createCommand("SELECT t_po_ko_detail.alias as alias FROM t_op_ko_detail 
											JOIN t_op_ko ON t_op_ko.op_ko_id = t_op_ko_detail.op_ko_id
											JOIN t_po_ko_detail ON t_po_ko_detail.po_ko_id = t_op_ko.po_ko_id 
												AND (
													(t_po_ko_detail.produk_id IS NULL AND t_op_ko_detail.produk_id = ANY(string_to_array(t_po_ko_detail.produk_id_alias, ',')::int[])) OR
													(t_po_ko_detail.produk_id IS NOT NULL AND t_op_ko_detail.produk_id = t_po_ko_detail.produk_id)
												)
											WHERE t_op_ko.op_ko_id = $modOpDetail->op_ko_id AND t_op_ko_detail.produk_id= $modOpDetail->produk_id")->queryOne();
        echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]alias", ['value'=>$modPo['alias']]); 
        ?>
        <?php } ?>
		<?php
		if($modOpDetail->is_random=='1'){
			echo "<b>".$modDetail->produk->produk_nama.'</b> <a onclick="listRandom(this)">(Random)</a> <span class="pull-right">Total Random &nbsp; </span>';
		}else{
            if($model->jenis_produk == "Limbah"){
                echo "<b>".$modDetail->limbah->limbah_kode."</b> (".$modDetail->limbah->limbah_produk_jenis.") ".$modDetail->limbah->limbah_nama;
            } else if($model->jenis_produk == "Log"){
                echo "<b>".$modDetail->log->log_kode."</b> - ".$modDetail->log->log_nama;
            } else{
                echo "<b>".$modDetail->produk->produk_nama."</b> (". str_replace(" ", "", $modDetail->produk->produk_dimensi).")";
            }
		}
		?>
    </td>
    <?php if($model->jenis_produk == "Log"){ ?>
        <td style="vertical-align: middle; " class="td-kecil text-align-center">
            <?php echo str_replace(" ", "", $modDetail->log->range_awal)."cm - ". str_replace(" ", "", $modDetail->log->range_akhir)."cm"; ?>
        </td>
    <?php } ?>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-center">
		<?php
        if($model->jenis_produk == "Limbah"){
            echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar");
        } else if($model->jenis_produk == "Log"){
            echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil");
            echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil");
        } else{
            if(!empty($realisasi)){
                echo yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]qty_besar",['class'=>'form-control float','style'=>'font-size:1.1rem; width:50px; padding:3px;','disabled'=>'disabled']);
            }else{
                echo '<span id="place-qty_besar">'. app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar) .'</span>';
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar");
            }
        }
		?>
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-right">
		<?php
        if(!empty($realisasi)){
            if($model->jenis_produk == "Log"){
                echo '<div class="input-group">';
                echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; width:100%;","disabled"=>"disabled"]);
                echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar");
                echo "<span class='input-group-addon' style='width=10%; padding-left: 3px; padding-right: 3px; font-size:1.1rem;'>";
                echo "".$modDetail->satuan_besar."";
                echo "</span>";
                echo '</div>';
            } else {
                echo '<div class="input-group">';
                echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]qty_kecil",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; width:100%;","disabled"=>"disabled"]);
                echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil");
                echo "<span class='input-group-addon' style='width=10%; padding-left: 3px; padding-right: 3px; font-size:1.1rem;'>";
                echo "".$modDetail->satuan_kecil."";
                echo "</span>";
                echo '</div>';
            }
        }else{
            if($model->jenis_produk == "Log"){
                // echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar);
                echo "<center><i>". $modDetail->satuan_besar ."</i></center>";
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar");
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar");
            } else {
                echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil)." <i>(". $modDetail->satuan_kecil .")</i>";
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil");
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil");
            }
        }
		?>
    </td>
    <td style="vertical-align: middle; background-color: #FFE495;" class="td-kecil text-align-right">
		<?php
        if($model->jenis_produk == "Limbah"){
            echo ($modDetail->satuan_kecil=="Rit")?$modDetail->satuan_besar:"";
            echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi");
        } else if($model->jenis_produk == "Log"){
            if($model->status == app\models\TSpmKo::REALISASI){
                echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->kubikasi); //$modDetail->kubikasi_realisasi;
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi");
            } else {
                if(!empty($realisasi)){
                    echo yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]kubikasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
                }else{
                    echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->kubikasi);
                    echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi");
                }
            }
        } else{
            if(!empty($realisasi)){
                echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]kubikasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
            }else{
                echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->kubikasi,4);
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi");
            }
        }
		?>
    </td>
	<td class="text-align-right td-kecil" style="background-color: #B6D25D;">
		<?php
        if($model->jenis_produk == "Limbah"){
            if(!empty($realisasi)){
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar_realisasi");
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar_realisasi");
            }else{
                echo "-";
            }
        }else if($model->jenis_produk == "Log"){
            if($model->status == app\models\TSpmKo::REALISASI){
                echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil_realisasi) ." <i>(".$modDetail->satuan_kecil_realisasi.")</i>"."</b>";
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil_realisasi", ['value'=>$modDetail->qty_kecil_realisasi]);
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil_realisasi");
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
            // if($model->status == app\models\TSpmKo::REALISASI){
            //     echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil_realisasi)."</b>";
            //     echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->satuan_kecil_realisasi)."</b>";
            // }else{
            //     if(!empty($realisasi)){
            //         echo '<div class="input-group">';
            //         echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]qty_kecil_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; width:100%;","disabled"=>"disabled"]);
            //         echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil_realisasi");
            //         echo "<span class='input-group-addon' style='width=10%; padding-left: 3px; padding-right: 3px; font-size:1.1rem;'>";
            //         echo "".$modDetail->satuan_kecil_realisasi."";
            //         echo "</span>";
            //         echo '</div>';
            //     }else{
            //         echo "-";
            //     }
            // }
        }else{
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
        }
		?>
	</td>
	<td class="text-align-right td-kecil" style="background-color: #B6D25D;">
		<?php
        if($model->jenis_produk == "Limbah"){
            if($model->status == app\models\TSpmKo::REALISASI){
                echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil_realisasi) ." <i>(".$modDetail->satuan_kecil_realisasi.")</i>"."</b>";
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
        } else if($model->jenis_produk == "Log"){
            if($model->status == app\models\TSpmKo::REALISASI){
                // echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar_realisasi, 2);
                echo "<center><i>".$modDetail->satuan_besar_realisasi."</i><center>"."</b>";
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar_realisasi");
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar_realisasi");
            }else{
                if(!empty($realisasi)){
                    echo '<div class="input-group">';
                    echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem; width:100%;","disabled"=>"disabled"]);
                    echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar_realisasi");
                    echo "<span class='input-group-addon' style='width=10%; padding-left: 3px; padding-right: 3px; font-size:1.1rem;'>";
                    echo "".$modDetail->satuan_besar_realisasi."";
                    echo "</span>";
                    echo '</div>';
                }else{
                    echo "-";
                }
            }
        }else{
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
        }
		?>
	</td>
    <!-- -->
	<td class="text-align-right td-kecil" style="background-color: #B6D25D;">
		<?php
        if($model->jenis_produk == "Limbah"){
            if($model->status == app\models\TSpmKo::REALISASI){
                echo ($modDetail->satuan_kecil=="Rit")?"<b>".$modDetail->satuan_besar_realisasi."</b>":"";
            }else{
                if(!empty($realisasi)){
                    echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]satuan_besar_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
                    echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi_realisasi");
                }else{
                    echo "-";
                }
            }
        } else if($model->jenis_produk == "Log"){
            if($model->status == app\models\TSpmKo::REALISASI){
                // echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar_realisasi");
                echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->kubikasi_realisasi, 2); //$modDetail->kubikasi_realisasi;
                echo \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi_realisasi");
            } else {
                if(!empty($realisasi)){
                    echo yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]kubikasi_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
                }else{
                    echo "-";
                }
            }
        } else{
            if($model->status == app\models\TSpmKo::REALISASI){
                echo "<b>".app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->kubikasi_realisasi)."</b>";
            }else{
                if(!empty($realisasi)){
                    echo \yii\bootstrap\Html::activeTextInput($modDetail, "[".$i."]kubikasi_realisasi",['class'=>'form-control float','style'=>"padding:2px; font-size:1.1rem;","disabled"=>"disabled"]);
                }else{
                    echo "-";
                }
            }
        }
		?>
	</td>
</tr>
<script>

</script>
