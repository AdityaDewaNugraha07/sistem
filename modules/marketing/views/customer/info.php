<?php

use app\models\HCustomer;
use app\models\HCustTop;
use app\models\MCustTop;
use app\models\TApproval;
use yii\helpers\Html;
use yii\helpers\Url;

$modCustTop     = MCustTop::findAll(['cust_id' => $model->cust_id]);
$modCustTopBaru = HCustTop::findAll(['cust_id'=>$model->cust_id]);
$approve_reason = yii\helpers\Json::decode($model->approve_reason);
$reject_reason  = yii\helpers\Json::decode($model->reject_reason);
$modApprove     = TApproval::findAll(['reff_no'=>trim($model->kode_customer)]);
$modApprove1    = TApproval::findOne(['reff_no'=>trim($model->kode_customer), 'level'=>1]);
$modApprove2    = TApproval::findOne(['reff_no'=>trim($model->kode_customer), 'level'=>2]);
?>
<div class="modal fade" id="modal-customer-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Customer');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_kode'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_tipe_penjualan'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_tipe_penjualan ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_tanggal_join'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->cust_tanggal_join); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Termasuk PKP'); ?> </label>
                            <div class="col-md-7"><strong><?= ($model->cust_is_pkp)?'Ya':'Tidak' ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_nama'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_nama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_nik'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_nik ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_jk'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_jk ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_tgllahir'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->cust_an_tgllahir); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_nohp'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_nohp; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_agama'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_agama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_alamat'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_alamat ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_email'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_an_email)?$model->cust_an_email:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_no_npwp'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_no_npwp ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Kode Referensi</label>
                            <div class="col-md-7"><strong><?= $model->kode_customer ?></strong></div>
                        </div>
                        <?php
                        if(!empty($model->cust_pr_nama)){ ?>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_nama'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_pr_nama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_direktur'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_pr_direktur)?$model->cust_pr_direktur:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_alamat'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_pr_alamat)?$model->cust_pr_alamat:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_phone'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_pr_phone)?$model->cust_pr_phone:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_fax'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_pr_fax)?$model->cust_pr_fax:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_email'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_pr_email)?$model->cust_pr_email:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['contact_person'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->contact_person)?$model->contact_person:" - " ?></strong></div>
                        </div>
                        <?php } ?>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['active'] ?></label>
                            <div class="col-md-7"><strong><?= ($model->active)?'<span class="font-green-jungle">Active</span>':'<span class="font-red">Non-Active</span>' ?></strong></div>
                        </div>
                        <?php /*<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['created_at'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser($model->created_at); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['created_by'] ?></label>
                            <div class="col-md-7"><strong><?php echo ( \app\models\MUser::findIdentity($model->created_by)) ? \app\models\MUser::findIdentity($model->created_by)->userProfile->fullname : "Unknown"; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['updated_at'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser($model->updated_at); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['updated_by'] ?></label>
                            <div class="col-md-7"><strong><?php echo ( \app\models\MUser::findIdentity($model->updated_by)) ? \app\models\MUser::findIdentity($model->updated_by)->userProfile->fullname : "Unknown"; ?></strong></div>
                        </div>*/?>
                    </div>
                    <?php $hcustomer = HCustomer::findOne(['cust_id' => $model->cust_id, 'kode_customer' => $model->kode_customer]);?>
                    <div class="col-md-4">
                        <?php
                        count($hcustomer) && $model->status_approval == 'Not Confirmed' ? $lama = "Lama" : $lama = "";
                        ?>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Plafond <?php echo $lama;?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatNumberForUser($model->cust_max_plafond) ?></strong></div>
                        </div>
                        <?php
                        if (count($hcustomer) && $model->status_approval == 'Not Confirmed') {
                        ?>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Plafond Baru</label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatNumberForUser($hcustomer->cust_max_plafond) ?></strong></div>
                        </div>
                        <?php
                        }
                        ?>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label">Status Approval Customer</label>
                            <?php
                            if ($model->status_approval == "REJECTED") {
                                $color = "#f00";
                            } else if ($model->status_approval == "APPROVED") {
                                $color = "#38C68B";
                            } else {
                                $color = "#000";
                            }
                            ?>
                            <div class="col-md-7" style="color: <?php echo $color;?>"><strong><?= !empty($model->status_approval)?$model->status_approval:" - " ?></strong></div>
                        </div>

                    <?php
                    if (!empty($model->approve_reason) || !empty($model->reject_reason)) {
                        $reasons_approve = json_decode($model->approve_reason);
                        $reasons_reject = json_decode($model->reject_reason);
                    
                        // STATUS DAN REASON APPROVER
                        foreach($modApprove as $key => $row) {
                            $level = $row['level'];

                            // LEVEL <=> BY
                            $row['level'] == 2 ? $levels = 22 : $levels = 19;
                        ?>
                        <div class="form-group col-md-12">
                            <?php
                            if ($row['status'] == "REJECTED" && !empty($reasons_reject)) {
                                $color = "#f00";
                                $reasons = json_decode($model->reject_reason);

                                foreach($reasons as $reason) {
                                    if ($levels == $reason->by) {
                                        $reasonx = $reason->reason;
                                        $status = $reason->status;
                                        $byx = $reason->by;
                                        $at = $reason->at;                                        
                                    }
                                }
                            } else if ($row['status'] == "APPROVED" && !empty($reasons_approve)) {
                                $color = "#38C68B";
                                $reasons = json_decode($model->approve_reason);

                                foreach($reasons as $reason) {
                                    if ($levels == $reason->by) {
                                        $reasonx = $reason->reason;
                                        $status = $reason->status;
                                        $byx = $reason->by;
                                        $at = $reason->at;                                        
                                    }
                                }                                    
                            } else {
                                $color = "#000";
                            }
                            isset($reasonx) ? $reasonx = "(".$reasonx.")" : $reasonx = '';
                            isset($at) ? $at = " at ".app\components\DeltaFormatter::formatDateTimeForUser2($at) : $at = '';

                            if ($level == 1) {
                                $pegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$model->by_kadiv]);
                            } else {
                                $pegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$model->by_dirut]);
                            }
                            $pegawai_nama = $pegawai->pegawai_nama;
                            ?>
                            <div class="col-md-12" style="color: <?php echo $color;?>; margin-top: 20px; font-size: 1.2rem;">
                                <span style="color: #000;"><?= $pegawai_nama;?></span>
                                <br>
                                <span><?php echo $row['status'];?></span> 
                                <span><?php echo $at;?></span>
                                <br>
                                <span><?= $reasonx;?></span>
                            </div>
                        </div>
                        <?php
                        }
                    }
                    ?>


                    </div>
                </div>
                <?php $hcusttop = HCustTop::findAll(['cust_id' => $model->cust_id, 'kode_customer' => $model->kode_customer]); ?>
                <div class="row" style="margin-top: 30px;">
                    <div class="form-group col-md-4">
                        <div class="form-group col-md-6">
                            <?php !empty($hcusttop) == "Not Confirmed" ? $lama = "Lama" : $lama = "";?>
                            <h4 class="" style="font-size: 15px;"><?= Yii::t('app', 'Term of Payment '.$lama.' :'); ?></h4>
                            <?php if(!empty($modCustTop)){ ?>
                                <table class="table table-bordered table-hover col-md-5">
                                    <tr>
                                        <th>JENIS JASA</th>
                                        <th style="width: 80px;">PERIODE</th>
                                    </tr>
                            <?php foreach($modCustTop as $i => $top){ ?>
                                    <tr>
                                        <th style="padding: 5px"><?= $top->custtop_jns ?> </th>
                                        <td style="padding: 5px" class="text-center"><?= $top->custtop_top." ". Yii::t('app', 'Hari'); ?></td>
                                    </tr>
                            <?php } ?>
                                </table>
                            <?php }else{ ?>
                                <table class="table table-bordered table-hover col-md-5">
                                    <tr>
                                        <td style="padding: 5px"><center><?= Yii::t('app', 'Belum di Set'); ?> </center></td>
                                    </tr>
                                </table>
                            <?php } ?>
                        </div>
                        <?php if(!empty($hcusttop)): ?>
                        <div class="form-group col-md-6">
                            <?php if ($model->status_approval == 'Not Confirmed') { ?>
                            <h4 class="" style="font-size: 15px;"><?= Yii::t('app', 'Term of Payment Baru :'); ?></h4>
                            <?php if(!empty($modCustTopBaru)){ ?>
                                <table class="table table-bordered table-hover col-md-5">
                                    <tr>
                                        <th>JENIS JASA</th>
                                        <th style="width: 80px;">PERIODE</th>
                                    </tr>
                            <?php foreach($modCustTopBaru as $i => $topBaru){ ?>
                                    
                                    <?php 
                                    if (trim($topBaru->kode_customer) == trim($model->kode_customer)) {
                                    ?>
                                    <tr>
                                        <th style="padding: 5px"><?= $topBaru->custtop_jns ?> </th>
                                        <td style="padding: 5px" class="text-center"><?= $topBaru->custtop_top." ". Yii::t('app', 'Hari'); ?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>

                            <?php } ?>
                                </table>
                            <?php }else{ ?>
                                <table class="table table-bordered table-hover col-md-5">
                                    <tr>
                                        <td style="padding: 5px"><center><?= Yii::t('app', 'Belum ada perubahan'); ?> </center></td>
                                    </tr>
                                </table>
                            <?php } ?>
                            <?php } ?>
                        </div>
                        <?php endif ?>
                    </div>
                    <div class="form-group col-md-8">
                        <div class="col-md-4">
                            <h4 class=""><?= $model->attributeLabels()['cust_file_ktp']; ?> : </h4>
                            <?php
                            if(!empty($model->cust_file_ktp)){
                                echo '<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('."'ktp', ".$model->cust_id.')">';
                                echo '<img src="'. Url::base().'/uploads/mkt/customer/'.$model->cust_file_ktp .'" alt="ktp-scan" style="height: 150px; width: 100%; display: block;"> ';
                                echo '</a>';
                            }else{
                                echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="ktp-scan" style="width: 100%; display: block;"> ';
                            }
                            ?>
                        </div>
                        <div class="col-md-4">
                            <h4 class=""><?= $model->attributeLabels()['cust_file_npwp']; ?> : </h4>
                            <?php
                            if(!empty($model->cust_file_npwp)){
                                echo '<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('."'npwp', ".$model->cust_id.')">';
                                echo '<img src="'. Url::base().'/uploads/mkt/customer/'.$model->cust_file_npwp .'" alt="npwp-scan" style="height: 150px; width: 100%; display: block;"> ';
                                echo '</a>';
                            }else{
                                echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="ktp-scan" style="width: 100%; display: block;"> ';
                            }
                            ?>
                        </div>
                        <div class="col-md-4">
                            <h4 class><?= $model->attributeLabels()['cust_file_photo']; ?> : </h4>
                            <?php
                            if(!empty($model->cust_file_photo)){
                                echo '<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('."'photo', ".$model->cust_id.')">';
                                echo '<img src="'. Url::base().'/uploads/mkt/customer/'.$model->cust_file_photo .'" alt="npwp-scan" style="height: 150px; width: 100%; display: block;"> ';
                                echo '</a>';
                            }else{
                                echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="ktp-scan" style="width: 100%; display: block;"> ';
                            }
                            ?>
                        </div>
                        <div class="col-md-12 text-center" style="margin-top: 20px; font-weight: bold; color: #f00;">*Klik pada gambar untuk memperbesar.</div>
                    </div>
                </div>
            </div>
            
            <?php
            // cek customer ini sedang dalam pengajuan approval atau nggak
            if ($model->status_approval == 'Not Confirmed') {
            ?>
            <div class="modal-footer text-info">
                <strong>Data customer ini sedang dalam proses pengajuan approval. Follow up pihak terkait untuk proses lebih lanjut</strong>
            </div>
            <?php
            } else {
            ?>
            <div class="modal-footer">
                <?= Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"openModal('". Url::toRoute(['/marketing/customer/edit','id'=>$model->cust_id])."','modal-customer-edit')"]); ?>
<!--                --><?php //echo Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('". Url::toRoute(['/marketing/customer/delete','id'=>$model->cust_id,'tableid'=>'table-customer'])."','modal-delete-record')"]); ?>
                <?= Html::button(Yii::t('app', 'Tidak Aktif'), ['class' => 'btn red btn-outline', 'onclick' => "openModal('" . Url::toRoute(['/marketing/customer/inactivated', 'id' => $model->cust_id, 'tableid' => 'table-customer']) . "', 'modal-global-confirm')"]) ?>
            </div>
            <?php
            }
            ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
function image(tipe, id) {
	var url = '<?= Url::toRoute(['/marketing/customer/image','id'=>'']) ?>'+id+'&tipe='+tipe;
	$(".modals-place-2").load(url, function() {
		$("#modal-image").modal('show');
		$("#modal-image").on('hidden.bs.modal', function () { });
        $("#modal-image .modal-dialog");
		spinbtn();
		draggableModal();
	});
}
</script>