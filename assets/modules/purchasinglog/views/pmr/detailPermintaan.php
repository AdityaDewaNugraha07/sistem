<style>
table{
	font-size: 1.4rem;
}
.form-group {
    margin-bottom: 0 !important;
}
/*table.table-striped thead tr th{
	padding : 3px !important;
}
.table-striped, 
.table-striped > tbody > tr > td, 
.table-striped > tbody > tr > th, 
.table-striped > tfoot > tr > td, 
.table-striped > tfoot > tr > th, 
.table-striped > thead > tr > td, 
.table-striped > thead > tr > th {
    border: 1px solid #A0A5A9;
	line-height: 0.9 !important;
	font-size: 1.2rem;
}*/
</style>
<div class="modal fade" id="modal-bbk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Permintaan Pembelian Log'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                            <div class="col-md-7"><strong><?= $model->kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Log'); ?></label>
                            <div class="col-md-7"><strong><?= ($model->jenis_log == "LA")?"Log Alam":"Log Sengon" ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kebutuhan Untuk'); ?></label>
                            <div class="col-md-7"><strong><?= $model->tujuan ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Dibutuhkan'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_dibutuhkan_awal)." sd ".\app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_dibutuhkan_akhir) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Dibuat Oleh'); ?></label>
                            <div class="col-md-7"><strong><?= $model->dibuatOleh->pegawai_nama ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php
						$approver_1 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->approver_1)->queryOne();
						$approver_2 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->approver_2)->queryOne();
						$approver_3 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->approver_3)->queryOne();
						?>
                        <div class="form-group col-md-12" style="line-height:1">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['approver_1'] ?><br><span style="font-size: 1.1rem; font-weight: 600"><?= ($model->tujuan == "INDUSTRI")?"Kadiv Opr":"Kadiv Mkt" ?></span></label>
                            <div class="col-md-7">
                                <strong><?= \app\models\MPegawai::findOne($model->approver_1)->pegawai_nama; ?></strong>
                                <span style="font-weight: 500; font-size: 1rem;">
                                    <?php if($approver_1['status']==\app\models\TApproval::STATUS_APPROVED){
                                        echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
                                                \app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
                                    }else if($approver_1['status']==\app\models\TApproval::STATUS_REJECTED){
                                        echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
                                                \app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
                                        $modApproval = app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->approver_1]);
                                        $reasons = \yii\helpers\Json::decode($modApproval->keterangan);
                                        if(count($reasons)>0){
                                            foreach($reasons as $i => $reason){
                                                if($reason['by']==$approver_1['assigned_to']){
                                                    echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
                                                }
                                            }
                                        }
                                    }else {
                                        echo "<br>&nbsp; <i>(Not Confirm)</i>";
                                    }?>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-12" style="line-height:1">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['approver_2'] ?><br><span style="font-size: 1.1rem; font-weight: 600"><?= ($model->tujuan == "INDUSTRI")?"GM Opr":"Dirut" ?></span></label>
                            <div class="col-md-7">
                                <strong><?= \app\models\MPegawai::findOne($model->approver_2)->pegawai_nama; ?></strong>
                                <span style="font-weight: 500; font-size: 1rem;">
                                    <?php if($approver_2['status']==\app\models\TApproval::STATUS_APPROVED){
                                        echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
                                                \app\components\DeltaFormatter::formatDateTimeForUser2($approver_2['updated_at'])."</span>";
                                    }else if($approver_2['status']==\app\models\TApproval::STATUS_REJECTED){
                                        echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
                                                \app\components\DeltaFormatter::formatDateTimeForUser2($approver_2['updated_at'])."</span>";
                                        $modApproval = app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->approver_2]);
                                        $reasons = \yii\helpers\Json::decode($modApproval->keterangan);
                                        if(count($reasons)>0){
                                            foreach($reasons as $i => $reason){
                                                if($reason['by']==$approver_2['assigned_to']){
                                                    echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
                                                }
                                            }
                                        }
                                    }else {
                                        echo "<br>&nbsp; <i>(Not Confirm)</i>";
                                    }?>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-12" style="line-height:1">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['approver_3'] ?><br><span style="font-size: 1.1rem; font-weight: 600"><?= Yii::t('app', 'Owner'); ?></span></label>
                            <div class="col-md-7">
                                <strong><?= \app\models\MPegawai::findOne($model->approver_3)->pegawai_nama; ?></strong>
                                <span style="font-weight: 500; font-size: 1rem;">
                                    <?php if($approver_3['status']==\app\models\TApproval::STATUS_APPROVED){
                                        echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
                                                \app\components\DeltaFormatter::formatDateTimeForUser2($approver_3['updated_at'])."</span>";
                                    }else if($approver_3['status']==\app\models\TApproval::STATUS_REJECTED){
                                        echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
                                                \app\components\DeltaFormatter::formatDateTimeForUser2($approver_3['updated_at'])."</span>";
                                        $modApproval = app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->approver_3]);
                                        $reasons = \yii\helpers\Json::decode($modApproval->keterangan);
                                        if(count($reasons)>0){
                                            foreach($reasons as $i => $reason){
                                                if($reason['by']==$approver_3['assigned_to']){
                                                    echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
                                                }
                                            }
                                        }
                                    }else {
                                        echo "<br>&nbsp; <i>(Not Confirm)</i>";
                                    }?>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['keterangan'] ?></label>
                            <div class="col-md-7"><strong><?= $model->keterangan; ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail-industri">
                                <thead>
                                    <?php $ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log'); ?>
                                    <tr>
                                        <th style="width: 30px;" rowspan="2" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                        <th style="width: 240px;" rowspan="2"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                        <th colspan="<?= count($ukuranganrange)+1 ?>"><?= Yii::t('app', 'Qty M<sup>3</sup> By Diameter Range'); ?></th>
                                        <th style="" rowspan="2"><?= Yii::t('app', 'Keterangan'); ?></th>
                                    </tr>
                                    <tr>
                                        <?php foreach($ukuranganrange as $i => $range){ ?>
                                        <th style="width: 110px;"><?= $range ?> cm</th>
                                        <?php } ?>
                                        <th style="width: 120px;">Total M<sup>3</sup></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_m3 = 0;
                                    foreach($ukuranganrange as $i => $range){
                                        $total_ver_m3[$range] = 0;
                                    }
                                    $modDetail = \app\models\TPmrDetail::find()
                                                        ->select("pmr_id, kayu_id, keterangan")
                                                        ->groupBy("pmr_id, kayu_id, keterangan")
                                                        ->where(['pmr_id'=>$model->pmr_id])->all();
                                    foreach($modDetail as $i => $detail){
                                        echo "<tr>";
                                        echo	"<td>".($i+1)."</td>";
                                        echo	"<td>".$detail->kayu->group_kayu." - ".$detail->kayu->kayu_nama."</td>";
                                            $subtotal_btg = 0; $subtotal_m3 = 0; $subtotal_harga=0;
                                            foreach($ukuranganrange as $i => $range){
                                                $sql = "SELECT SUM(qty_m3) AS qty_m3 FROM t_pmr_detail 
                                                        WHERE pmr_id = {$model->pmr_id} AND kayu_id = {$detail->kayu_id} AND diameter_range = '{$range}'";
                                                $modQty = Yii::$app->db->createCommand($sql)->queryOne();
                                                echo "<td class='text-align-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($modQty['qty_m3'],4)." M<sup>3</sup></td>";
                                                $subtotal_m3 += $modQty['qty_m3'];
                                                $total_ver_m3[$range] += $modQty['qty_m3'];

                                            }
                                            $total_m3 += $subtotal_m3;
                                        echo    "<td class='text-align-right'><b>".\app\components\DeltaFormatter::formatNumberForUserFloat($subtotal_m3,4)." M<sup>3</sup></b></td>";
                                        echo    "<td class='text-align-center'>".$detail['keterangan']."</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" style="text-align: right;">&nbsp; </td>
                                        <?php 
                                        foreach($ukuranganrange as $i => $range ){
                                            echo "<td class='text-align-right'>". number_format($total_ver_m3[$range])." M<sup>3</sup></td>";
                                        }
                                        echo "<td class='text-align-right'><b>". number_format($total_m3)." M<sup>3</sup></b></td>";
                                        ?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>