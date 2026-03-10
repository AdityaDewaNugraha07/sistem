<?php
$modApp = \app\models\TApproval::findOne($approval_id);
$model = \app\models\TRealisasimakanGrader::find()->where(['kode'=>$modApp->reff_no])->one();

app\assets\DatepickerAsset::register($this);
?>

<style>
table tr td{
	padding: 3px;
	border: solid 3px #fff;
}
</style>

<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Realisasi'); ?></label>
                <div class="col-md-7"><strong><?= $model->kode ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Dinas'); ?></label>
                <div class="col-md-7"><strong><?= $model->dkg->kode ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Periode Awal'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->periode_awal) ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Tipe Dinas'); ?></label>
                <div class="col-md-7"><strong><?= $model->dkg->tipe ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Periode Akhir'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->periode_akhir) ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Grader'); ?></label>
                <div class="col-md-7"><strong><?= $model->graderlog->graderlog_nm ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Saldo Awal'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatUang($model->saldo_awal) ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Wilayah Dinas'); ?></label>
                <div class="col-md-7"><strong><?= $model->dkg->wilayahDinas->wilayah_dinas_nama ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Total Realisasi'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatUang($model->total_realisasi) ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Tempat Tujuan'); ?></label>
                <div class="col-md-7"><strong><?= !empty($model->dkg->tujuan)?$model->dkg->tujuan:"-" ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Saldo Akhir'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatUang($model->saldo_akhir) ?></strong></div>
            </div>
            <div class="form-group col-md-6">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan'); ?></label>
                <div class="col-md-7"><strong><?= !empty($model->keterangan)?$model->keterangan:"-" ?></strong></div>
            </div>
        </div>
    </div>

    <?php
    $model = \app\models\TApproval::findOne($approval_id);
    $modRMG = \app\models\TRealisasimakanGrader::find()->where(['kode'=>$model->reff_no])->one();
    ?>
    <div class="row">
        <div class="modal-footer" style="text-align: center;">
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
            <div class="row">
                <?php if( (empty($model->approved_by)) && (empty($model->tanggal_approve)) ){ ?>
                <?php if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){ ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); ?>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
            
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
formconfig();
", yii\web\View::POS_READY); ?>
<script>

</script>