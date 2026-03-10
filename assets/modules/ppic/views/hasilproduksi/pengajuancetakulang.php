<div class="modal fade " id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Oppps...'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-transaksi-ajuan',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-5">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                    <div class="col-md-12">
                        <?php if($statuspengajuan=='info'){ ?>
                            <div class="row">
                                <div class="col-md-12 text-align-center">
                                    <b style="font-size: 1.4rem;">Pengajuan cetak ulang sudah diajukan, mohon bersabar... </b><br>
                                    <?php
                                    $modAjuanManipulasi = \app\models\TPengajuanManipulasi::find()->where(['reff_no'=>$modTerima->kode])->orderBy("pengajuan_manipulasi_id DESC")->one();
                                    $modApproval1 = \app\models\TApproval::find()->where(['reff_no'=>$modAjuanManipulasi->kode,'assigned_to'=>$modAjuanManipulasi->approver1])->orderBy("approval_id DESC")->one();
                                    $modApproval2 = \app\models\TApproval::find()->where(['reff_no'=>$modAjuanManipulasi->kode,'assigned_to'=>$modAjuanManipulasi->approver2])->orderBy("approval_id DESC")->one();
                                    $statusappr1 = $modApproval1->status;
                                    $statusappr2 = $modApproval2->status;
                                    ?>
                                    Status Approval <?= $modApproval1->assignedTo->pegawai_nama ?> : <b><u><?= $statusappr1 ?></u></b><br>
                                    Status Approval <?= $modApproval2->assignedTo->pegawai_nama ?> : <b><u><?= $statusappr2 ?></u></b>
                                </div>
                                <br><br>
                            </div>
                        <?php }else{ ?>
                            <div class="row">
                                <div class="col-md-12 text-align-center">
                                    <b style="font-size: 1.4rem;">Maaf kesempatan untuk mencetak sudah habis. :( </b><br><br>
                                    <i style="font-size: 1.1rem;">Mohon isi form Permintaan dibawah ini jika ingin mengajukan cetak label produk satu kali lagi ;)</i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?= yii\bootstrap\Html::activeHiddenInput($model, "approver1"); ?>
                                    <?= yii\bootstrap\Html::activeHiddenInput($model, "approver2"); ?>
                                    <?= yii\bootstrap\Html::activeHiddenInput($model, "reff_no"); ?>
                                    <?= $form->field($model, 'approver1_display')->textInput(['disabled'=>true])->label("Disetujui Oleh"); ?>
                                    <?= $form->field($model, 'approver2_display')->textInput(['disabled'=>true])->label("Diketahui Oleh"); ?>
                                    <?= $form->field($model, 'tipe')->textInput(['disabled'=>true])->label("Permohonan"); ?>
                                    <?= $form->field($model, 'reff_no2')->textInput(['disabled'=>true])->label("Kode Barang Jadi"); ?>
                                    <?= $form->field($model, 'priority')->dropDownList(['CRITICAL'=>'CRITICAL','MAJOR'=>'MAJOR','NORMAL'=>'NORMAL','MINOR'=>'MINOR'])->label("Prioritas"); ?>
                                    <?= $form->field($model, 'reason')->textarea()->label("Alasan"); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 text-align-center">
                                    <?php echo \yii\helpers\Html::button( Yii::t('app', 'Buat Permintaan!'),['class'=>'btn blue ciptana-spin-btn',
                                        'onclick'=>'save(this);'
                                    ]);?>
                                </div>
                            </div>
                        <?php } ?><br>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center"></div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
function save(ele){
    var $form = $('#form-transaksi-ajuan');
    if(validatingDetail()){
        submitformajax(ele,"$('#modal-transaksi').modal('hide');");
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
	var alasan = $("#<?= \yii\bootstrap\Html::getInputId($model, "reason") ?>").val();
	if(!alasan){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "reason") ?>").parents(".form-group").addClass("has-error");
		has_error = has_error + 1;
	}else{
		$("#<?= \yii\bootstrap\Html::getInputId($model, "reason") ?>").parents(".form-group").removeClass("has-error");
    }
    if(has_error === 0){
        return true;
    }
    return false;
}
</script>