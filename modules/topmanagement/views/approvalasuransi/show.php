<?php
$model = \app\models\TApproval::findOne($approval_id);
$modAsuransi = \app\models\TAsuransi::findOne(['kode'=>$model->reff_no]);
?>
<style>

td {
    font-size: 13px;
    line-height: 20px;
}
th {
    font-size: 13px;
    line-height: 20px;
}
#detail td, th {
    border: solid 1px #ccc;
    padding: 3px;
}
</style>

<div class="modal-body" >
    <div class="row">
        <div class="col-md-12">            
            <table style="width: 100%;">
                <tr>
                    <td style="width: 15%; vertical-align: top;">Kode </td>
                    <td style="width: 1%; vertical-align: top;" class="text-center"> : </td>
                    <td style="width: 40%;vertical-align: top;"> <?= nl2br($modAsuransi->kode);?></td>
                    <td style="width: 15%; vertical-align: top;">Tanggal Pengajuan </td>
                    <td style="width: 1%; vertical-align: top;" class="text-center"> : </td>
                    <td style="vertical-align: top;"><?= \app\components\DeltaFormatter::formatDateTimeForUser($modAsuransi->tanggal);?></td>
                </tr>
                <tr>
                    <td style="width: 15%; vertical-align: top;">Kepada Yth </td>
                    <td style="width: 1%; vertical-align: top;" class="text-center"> : </td>
                    <td style="width: 40%;"> <?= nl2br($modAsuransi->kepada);?></td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Lampiran </td>
                    <td style="vertical-align: top;" class="text-center"> : </td>
                    <td style="vertical-align: top;"> <?= $modAsuransi->lampiran;?></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td style="width: 15%; vertical-align: top;">Tanggal Muat</td>
                    <td class="text-center" style="width: 1%;vertical-align: top;"> : </td>
                    <td style="width: 40%;vertical-align: top;"> <?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modAsuransi->tanggal_muat);?></td>
                    <td style="width: 1%;vertical-align: top;">Rute</td>
                    <td class="text-center" style="width: 1%;vertical-align: top;"> : </td>
                    <td style="vertical-align: top;"> <?= $modAsuransi->rute;?></td>
                </tr>                    
                <tr>
                    <td style="vertical-align: top;">Tanggal Berangkat</td>
                    <td style="vertical-align: top;" class="text-center"> : </td>
                    <td style="vertical-align: top;"> <?= \app\components\DeltaFormatter::formatDateTimeForUser($modAsuransi->tanggal_berangkat);?></td>
                    <td style="vertical-align: top;">Nama Kapal</td>
                    <td style="vertical-align: top;" class="text-center"> : </td>
                    <td style="vertical-align: top;"> <?= $modAsuransi->nama_kapal;?></td>
                </tr>                    
                <tr>
                    <td style="vertical-align: top;">Deskripsi Obyek Pertanggungan</td>
                    <td style="vertical-align: top;" class="text-center"> : </td>
                    <td style="vertical-align: top;"> <?php echo $modAsuransi->dop;?></td>
                    <td style="vertical-align: top;">Rate</td>
                    <td style="vertical-align: top;" class="text-center"> : </td>
                    <td style="vertical-align: top;"> <?= $modAsuransi->rate;?> %</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Total Sum Insured</td>
                    <td style="vertical-align: top;" class="text-center"> : </td>
                    <td style="vertical-align: top;"> Rp. <?= \app\components\DeltaFormatter::formatNumberForAllUser($modAsuransi->pembulatan);?>,-</td>
                    <td style="vertical-align: top;">Lumpsump</td>
                    <td style="vertical-align: top;" class="text-center">:</td>
                    <td style="vertical-align: top;">
                        <input type="checkbox" name="lumpsump" id="lumpsump" style="margin-top: 10px;" <?= $modAsuransi->lumpsump ? 'checked' : '' ?> disabled>
                        <span class="help-block"></span>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Terbilang</td>
                    <td style="vertical-align: top;" class="text-center"> : </td>
                    <td style="vertical-align: top;"> #<?= strtoupper(\app\components\DeltaFormatter::formatNumberTerbilang($modAsuransi->pembulatan))?>#</td>
                    <td style="vertical-align: top;">Freight</td>
                    <td style="vertical-align: top;" class="text-center">:</td>
                    <td style="vertical-align: top;"><?= \app\components\DeltaFormatter::formatNumberForAllUser($modAsuransi->freight);?></td>
                </tr>
            </table>
            
            <br>
            <table id="detail" style="width: 100%;">
                <tr>
                    <th style="text-align: center;">No.</th>
                    <th style="text-align: left;">Kelompok</th>
                    <th style="text-align: center;">Harga</th>
                    <th style="text-align: center;">m<sup>3</sup></th>
                    <th style="text-align: center;">Sub Total</th>
                </tr>
                <tbody>
                <?php
                // $modAsuransiDetail = \app\models\TAsuransiDetail::findAll(['asuransi_id' => $modAsuransi->asuransi_id]);
                $modAsuransiDetail = \app\models\TAsuransiDetail::find()->where(['asuransi_id'=>$modAsuransi->asuransi_id])->orderBy(['asuransi_detail_id'=>SORT_ASC])->all();
                $x = '';
                $i = 0;
                $kubikasis = 0 ;
                foreach ($modAsuransiDetail as $f => $v) {
                ?>
                <tr>
                    <td class="text-center">
                        <?php
                        if ($x != $v->jenis) {
                            $i = $i + 1;
                            echo $i;
                            
                            $kubikasis += $v->kubikasi;
                            $PaddingRight =""; 
                        }else{
                            $PaddingRight ="style='color:red;padding-right:40px;'"; 
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $v->tipe;?>
                    </td>
                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($v->harga);?></td>
                    <td class="text-right"<?= $PaddingRight ?>><?php echo \app\components\DeltaFormatter::formatNumberForUser($v->kubikasi);?></td>
                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($v->total);?></td>
                </tr>
                <?php
                    $x = $v->jenis;
                }
                ?>
                </tbody>
                <tr>
                    <th colspan="3" class="text-right">Total</th>
                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($kubikasis);?></th>
                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->total);?></th>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td class="text-center"></td>
                    <td>Freight x Kubikasi</td>
                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->freight * 1);?></td>
                    <td class="text-right"><?php echo $modAsuransi->lumpsump ? 0 : \app\components\DeltaFormatter::formatNumberForUser($kubikasis);?></td>
                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser(($modAsuransi->freight * ($modAsuransi->lumpsump ? 1 : $kubikasis)));?></td>
                </tr>
                <tr>
                    <td class="text-center"></td>
                    <td colspan="3" class="text-right">Jumlah</td>
                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->jumlah);?></td>
                </tr>
                <tr>
                    <td class="text-center"></td>
                    <td colspan="3" class="text-right">Ppn </td>
                    <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->ppn);?></td>
                </tr>
                <tr>
                    <td class="text-center"></td>
                    <th colspan="3" class="text-right">Grand Total</th>
                    <th class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->grandtotal);?></th>
                </tr>
                <tr>
                    <td class="text-center"></td>
                    <th colspan="3" class="text-right">Dibulatkan </th>
                    <th class="text-right" style="color:darkgreen;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modAsuransi->pembulatan);?></th>
                </tr>
            </table>
            
            <br>
            
            <table style="width: 95%;">
                <tr>
                <?php
                $modApprover = \app\models\TApproval::findAll(['reff_no'=>$model->reff_no]);
                for ($i=1; $i<=count($modApprover); $i++) {
                    $jumlah_kolom = 12/count($modApprover);
                    ?>
                    <td style="width: 50%; text-align: center; vertical-align: top;">
                        <?php
                        $modApproval = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'level'=>$i]);
                        $modPegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$modApproval->assigned_to]);
                        if ($modApproval->status == "APPROVED") {
                            $color = "darkgreen";
                        } else if ($modApproval->status == "REJECTED") {
                            $color = "red";
                        } else {
                            $color = "grey";
                        }
                        echo "<p>".$modPegawai->pegawai_nama;
//                        echo "<br><span style='color: ".$color."'>".$modApproval->status."";
                                                
                        if($modApproval->status ==\app\models\TApproval::STATUS_APPROVED){
                                echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
                                                \app\components\DeltaFormatter::formatDateTimeForUser2($modApproval['updated_at'])."</span>";
                        }else if($modApproval->status ==\app\models\TApproval::STATUS_REJECTED){
                                echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
                                                \app\components\DeltaFormatter::formatDateTimeForUser2($modApproval['updated_at'])."</span>";
                        }

                        if(!empty($modAsuransi->approve_reason)){
                            $modApproveReason = \yii\helpers\Json::decode($modAsuransi->approve_reason);
                            foreach($modApproveReason as $iap => $aprreas){
                                if($aprreas['by'] == $modPegawai->pegawai_id){
                                    echo "<br><span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                }
                            }
                        }

                        if(!empty($modAsuransi->reject_reason)){
                            $modRejectReason = \yii\helpers\Json::decode($modAsuransi->reject_reason);
                            foreach($modRejectReason as $irj => $rjcreas){
                                if($rjcreas['by'] == $modPegawai->pegawai_id){
                                    echo "<br><span class='font-red-flamingo'>( ".$rjcreas['reason']." )</span>";
                                }
                            }
                        }
                        ?>
                        </p>
                    </td>
                    <?php
                }
                ?>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer" style="text-align: center;">
	<?php if( (empty($model->approved_by)) && (empty($model->tanggal_approve)) ){ ?>
    <?php if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){ ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); ?>
    <?php } ?>
	<?php } ?>
</div>