<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?php echo yii\bootstrap\Html::activeHiddenInput($modSpoDetail, '[ii]bhp_id',['disabled'=>'disabled']); ?>
        <?php echo yii\bootstrap\Html::activeHiddenInput($modSpoDetail, '[ii]sppd_id',['disabled'=>'disabled']); ?>
        <?php echo yii\bootstrap\Html::activeHiddenInput($modSpoDetail, '[ii]sppd_qty',['disabled'=>'disabled']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle; line-height:13px;">
        <?php echo $modBhp->Bhp_nm; ?>
        <?php
		if(!empty($modSpp)){
			echo "<br>";
            echo "<span style='font-size:1.2rem; margin-top:-5px; cursor: pointer;' class='font-blue-steel' onclick='sppDetail(".$modSpp['spp_id'].",".$modBhp->bhp_id.")'>".$modSpp['spp_kode']."</span>";
		}
        $Jmlpenawaran = 0;
        $Jmlfilepenawaran = 0;
		if(!empty($modSppDetail)){
			$mapPenawaran = app\models\MapPenawaranBhp::find()->where("sppd_id = ".$modSppDetail['sppd_id'])->all();
			$modSpoDetail->sppd_id = $modSppDetail['sppd_id'];
            if(empty($mapPenawaran)){
                $Jmlpenawaran = 1;
            }
			echo yii\bootstrap\Html::activeHiddenInput($modSpoDetail, '[ii]sppd_id',['disabled'=>'disabled']);
			if(count($mapPenawaran)>0){ 
				echo	'<a onclick="penawaranTerpilih('.$modSppDetail['sppd_id'].')" id="tbl-penawaran" class="btn btn-default" 
							style="font-size:0.9rem; padding: 2px 2px 2px 10px; line-height:1; text-align:left; height: 35px; width: 100%;">';
								foreach($mapPenawaran as $i => $tawar){
									$modPenawaran = app\models\TPenawaranBhp::findOne($tawar->penawaran_bhp_id);
									if($mapPenawaran){
                                        $Jmlpenawaran = $i+1;
                                        if(!empty($modPenawaran->attachment)){
                                            $statusBerkas = "<i class='fa fa-check font-green-haze'></i>";
                                            $Jmlfilepenawaran = $i+1;
                                        } else{
                                            $statusBerkas = "";
                                        }                                            
										echo "<b>".($i+1).". </b><span class='font-red-flamingo'>".$modPenawaran->suplier->suplier_nm."</span> $statusBerkas<br>";                                                                                                                      
									}                                                               
								}                                
				echo	'</a>';	                			
			}
            $cekfilepenawaran = $Jmlpenawaran - $Jmlfilepenawaran;
            echo yii\bootstrap\Html::activeHiddenInput($modSpoDetail,'[ii]cekfilepenawaran',['id'=>'[ii]cekfilepenawaran','disabled'=>'disabled','value'=>$cekfilepenawaran]); 
        }  
        ?>
    </td>
    <td style="vertical-align: middle;">
        <center><?php 
			$stock = \app\models\HPersediaanBhp::getCurrentStock($modSpoDetail->bhp_id);
			$stock = (!empty($stock)?$stock:0);
			echo app\components\DeltaFormatter::formatNumberForUserFloat($stock);
		?></center>
    </td>
    <td style="vertical-align: middle;">
        <center><?= $modSpoDetail->spod_qty; ?></center>
    </td>
    <td style="vertical-align: middle; padding: 5px;">
        <?= \yii\helpers\Html::activeTextInput($modSpoDetail, '[ii]spod_qty', ['class'=>'form-control cus float','style'=>'width:100%; text-align:center; padding:3px;','onblur'=>'setSubtotal(this); hitungppn();']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?= $modSpoDetail->satuan; ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">        
        <?= \yii\helpers\Html::activeCheckbox($modSpoDetail, '[ii]spod_garansi', ['class'=>'form-control','style'=>'width:100%; text-align:center; padding:3px;','label'=>'']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center; padding: 5px;">
        <?= \yii\helpers\Html::activeTextInput($modSpoDetail, '[ii]harga_display', ['class'=>'form-control float','onblur'=>'hitungppn()','onkeyup'=>'duplicateHarga(this)','style'=>'padding:3px;']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modSpoDetail, '[ii]spod_harga',['disabled'=>'disabled']); ?>
		<?php echo yii\bootstrap\Html::activeHiddenInput($modSpoDetail, '[ii]spod_harga_bantu',['disabled'=>'disabled','class'=>'money-format']); ?>
    </td>
    <td style="vertical-align: middle; padding: 5px;">
        <?= \yii\helpers\Html::activeTextInput($modSpoDetail, '[ii]subtotal_display', ['class'=>'form-control money-format','disabled'=>'disabled','style'=>'padding:3px;']); ?>
        <?= \yii\helpers\Html::activeHiddenInput($modSpoDetail, '[ii]subtotal', ['class'=>'','disabled'=>'disabled','class'=>'money-format']); ?>
    </td>
    <td style="vertical-align: middle; padding: 5px;">
        <?= \yii\helpers\Html::activeTextarea($modSpoDetail, '[ii]spod_keterangan', ['class'=>'form-control','style'=>'height:55px; font-size:1.1rem; padding: 5px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <a class="btn btn-xs red" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>