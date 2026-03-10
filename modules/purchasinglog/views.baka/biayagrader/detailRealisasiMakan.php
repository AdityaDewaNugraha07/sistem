<style>
#detail-ajuandinas tr td{
	font-size: 1.3rem !important;
	vertical-align: top;
}
</style>
<div class="modal fade" id="modal-realisasimakan" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title text-align-center"><?= Yii::t('app', 'Relisasi Makan Grader'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Realisasi'); ?></label>
                            <div class="col-md-7"><strong><?= $model->kode ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Uang Makan /hari'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatUang($model->wilayah_dinas_makan) ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Periode Awal'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->periode_awal) ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Uang Pulsa /bln'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatUang($model->wilayah_dinas_pulsa) ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Periode Akhir'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->periode_akhir) ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Qty Hari'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_hari) ?> Hari</strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Dinas'); ?></label>
                            <div class="col-md-7"><strong><?= $model->dkg->kode ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Saldo Awal'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatUang($model->saldo_awal) ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tipe Dinas'); ?></label>
                            <div class="col-md-7"><strong><?= $model->dkg->tipe ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Total Realisasi'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatUang($model->total_realisasi) ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Grader'); ?></label>
                            <div class="col-md-7"><strong><?= $model->graderlog->graderlog_nm ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Saldo Akhir'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatUang($model->saldo_akhir) ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Wilayah Dinas'); ?></label>
                            <div class="col-md-7"><strong><?= $model->dkg->wilayahDinas->wilayah_dinas_nama ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan'); ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->keterangan)?$model->keterangan:"-" ?></strong></div>
                        </div>
						<div class="form-group col-md-6">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tempat Tujuan'); ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->dkg->tujuan)?$model->dkg->tujuan:"-" ?></strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
            <?php
                $model = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->one();
                if (count($model) > 0) {
                    $modRMG = \app\models\TRealisasimakanGrader::find()->where(['kode'=>$model->reff_no])->one();
                ?>
                <div class="row">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            // approve level 1 iswari
                            if(!empty($modRMG->approve_reason)){
                                $modApproveReason = \yii\helpers\Json::decode($modRMG->approve_reason);
                                foreach($modApproveReason as $kolom => $value){
                                    $approver1 = $model->assigned_to;
                                    if($value['by'] == $approver1){
                                        $approver = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approver1." ")->queryScalar();
                                        $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($value['at']);
                                    ?>
                                        <span class='font-green-seagreen text-bold'><?php echo $approver;?></span>
                                        <br><span class='font-green-seagreen'>Approved at : <?php echo $tanggal;?></span>
                                        <br><span class='font-green-seagreen'>Reason : <?php echo $value['reason'];?></span>
                                    <?php
                                    }
                                }
                            }
                            // reject level 1 iswari
                            if(!empty($modRMG->reject_reason)){
                                $modApproveReason = \yii\helpers\Json::decode($modRMG->reject_reason);
                                foreach($modApproveReason as $kolom => $value){
                                    $approver1 = $model->assigned_to;
                                    if($value['by'] == $approver1){
                                        $approver = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approver1." ")->queryScalar();
                                        $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($value['at']);
                                    ?>
                                        <span class='text-danger text-bold'><?php echo $approver;?></span>
                                        <br><span class='text-danger'>Rejected at : <?php echo $tanggal;?></span>
                                        <br><span class='text-danger'>Reason : <?php echo $value['reason'];?></span>
                                    <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>