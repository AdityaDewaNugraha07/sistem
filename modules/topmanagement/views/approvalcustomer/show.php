<?php

use app\components\DeltaFormatter;
use app\components\Params;
use app\models\HCustData;
use app\models\HCustomer;
use app\models\HCustTop;
use app\models\MCustomer;
use app\models\MCustTop;
use app\models\MPegawai;
use app\models\MUser;
use app\models\TApproval;
use yii\helpers\Json;
use yii\helpers\Url;


if(isset($approval_id)) {
    $model              = TApproval::findOne($approval_id);
    $model_m_customer   = MCustomer::findOne(['kode_customer' => $model->reff_no]);
    $model_h_cust_data  = HCustData::findOne(['kode_customer' => $model->reff_no]);
//    echo "<pre>";print_r($model_m_customer);die;
    if(isset($model_m_customer)) {
        $cust_id        = $model_m_customer->cust_id;
        $label          = $model_m_customer->attributeLabels();
        $kode_customer  = $model_m_customer->kode_customer;
        $customer       = $model_m_customer;
    }else {
        $cust_id        = $model_h_cust_data->cust_id;
        $label          = $model_h_cust_data->attributeLabels();
        $kode_customer  = $model_h_cust_data->kode_customer;
        $customer       = $model_h_cust_data;
    }
    $model_m_cust_top   = MCustTop::findAll(['cust_id'=>$cust_id]);
    $model_h_cust_top   = HCustTop::findAll(['cust_id'=>$cust_id, 'kode_customer'=>$model->reff_no]);
    $modApprove         = TApproval::findAll(['reff_no'=>trim($kode_customer)]);
}

?>

<style>
.form-group {
    margin-bottom: 0 !important;
}
table.table-striped thead tr th{
    padding : 3px !important;
}

.table-striped > tbody > tr > td,
.table-striped > tbody > tr > th,
.table-striped > tfoot > tr > td,
.table-striped > tfoot > tr > th,
.table-striped > thead > tr > td,
.table-striped > thead > tr > th {
    border: 1px solid #A0A5A9;
    line-height: 0.9 !important;
    font-size: 1.2rem;
}
</style>

<?php if(isset($label) && isset($customer)): ?>
<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_kode'] ?></label>
                <div class="col-md-7"><strong><?= $customer->cust_kode ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_tipe_penjualan'] ?>
                </label><div class="col-md-7"><strong><?= $customer->cust_tipe_penjualan ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_tanggal_join'] ?></label>
                <div class="col-md-7"><strong><?= DeltaFormatter::formatDateTimeForUser2($customer->cust_tanggal_join)?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Termasuk PKP'); ?></label>
                <div class="col-md-7"><strong><?= $customer->cust_is_pkp === true ? 'Ya':'Tidak' ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_an_nama'] ?></label>
                <div class="col-md-7"><strong><?= $customer->cust_an_nama ?></strong>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_an_nik']?></label>
                <div class="col-md-7"><strong><?= $customer->cust_an_nik ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_an_jk'] ?>
                </label>
                <div class="col-md-7">
                    <strong><?= $customer->cust_an_jk ?></strong>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_an_tgllahir']?></label>
                <div class="col-md-7"><strong><?= DeltaFormatter::formatDateTimeForUser2($customer->cust_an_tgllahir) ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_an_nohp'] ?></label>
                <div class="col-md-7"><strong><?= $customer->cust_an_nohp ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_an_agama'] ?></label>
                <div class="col-md-7"><strong><?= $customer->cust_an_agama ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_an_alamat'] ?></label>
                <div class="col-md-7"><strong><?= $customer->cust_an_alamat ?></strong></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_an_email'] ?></label>
                <div class="col-md-7"><strong><?= !empty($customer->cust_an_email) ? $customer->cust_an_email : " - " ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_no_npwp'] ?></label>
                <div class="col-md-7"><strong><?= $customer->cust_no_npwp ?></strong></div>
            </div>
            <?php if(!empty($customer->cust_pr_nama)): ?>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label">
                    <?= isset($label['cust_pr_nama']) ? $label['cust_pr_nama'] : '' ?>
                </label>
                <div class="col-md-7">
                    <strong>
                        <?= $customer->cust_pr_nama ?>
                    </strong>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_pr_direktur'] ?></label>
                <div class="col-md-7"><strong><?= !empty($customer->cust_pr_direktur)?$customer->cust_pr_direktur:" - " ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_pr_alamat'] ?></label>
                <div class="col-md-7"><strong><?= !empty($customer->cust_pr_alamat)?$customer->cust_pr_alamat:" - " ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_pr_phone'] ?></label>
                <div class="col-md-7"><strong><?= !empty($customer->cust_pr_phone)?$customer->cust_pr_phone:" - " ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_pr_fax'] ?></label>
                <div class="col-md-7"><strong><?= !empty($customer->cust_pr_fax)?$customer->cust_pr_fax:" - " ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['cust_pr_email'] ?></label>
                <div class="col-md-7"><strong><?= !empty($customer->cust_pr_email)?$customer->cust_pr_email:" - " ?></strong></div>
            </div>
                        <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['contact_person'] ?></label>
                <div class="col-md-7"><strong><?= !empty($customer->contact_person)?$customer->contact_person:" - " ?></strong></div>
            </div>
            <?php endif ?>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['active'] ?></label>
                <div class="col-md-7"><strong><?= ($customer->active)?'<span class="font-green-jungle">Active</span>':'<span class="font-red">Non-Active</span>' ?></strong></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['created_at'] ?></label>
                <div class="col-md-7"><strong><?= DeltaFormatter::formatDateTimeForUser($customer->created_at); ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['created_by'] ?></label>
                <div class="col-md-7"><strong><?php echo ( MUser::findIdentity($customer->created_by)) ? MUser::findIdentity($customer->created_by)->userProfile->fullname : "Unknown"; ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['updated_at'] ?></label>
                <div class="col-md-7"><strong><?= DeltaFormatter::formatDateTimeForUser($customer->updated_at); ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['updated_by'] ?></label>
                <div class="col-md-7"><strong><?php echo ( MUser::findIdentity($customer->updated_by)) ? MUser::findIdentity($customer->updated_by)->userProfile->fullname : "Unknown"; ?></strong></div>
            </div>
            <?php
            if(isset($cust_id)) {
                $hcustomer = HCustomer::findOne(['cust_id' => $cust_id, 'kode_customer' => $customer->kode_customer]);
            }
            if(isset($model)) : ?>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label">Kode Customer</label>
                <div class="col-md-7 text-primary" style="font-weight: bold;"><strong><?= $model->reff_no;?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <?php
                    $lama = isset($hcustomer) ? "Lama" : "";
                    $cust_max_plafond = isset($hcustomer) ? $hcustomer->cust_max_plafond_lama : $customer->cust_max_plafond;
                ?>
                <label class="col-md-5 control-label">Plafond <?= $lama;?></label>
                <div class="col-md-7 text-primary" style="font-weight: bold;"><strong><?= DeltaFormatter::formatNumberForUser($cust_max_plafond) ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <?php if (isset($hcustomer)) :?>
                <label class="col-md-5 control-label">Plafond Baru</label>
                <div class="col-md-7 text-danger" style="font-weight: bold;"><strong><?= DeltaFormatter::formatNumberForUser($hcustomer->cust_max_plafond) ?></strong></div>
                <?php endif ?>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $label['status_approval'] ?></label>
                <?php switch ($customer->status_approval) {
                    case 'REJECTED' :
                        $color = "#f00";
                        break;
                    case 'APPROVED':
                        $color = '#38C68B';
                        break;
                    default:
                        $color = '#000';
                } ?>
                <div class="col-md-7" style="color: <?= $color;?>">
                    <strong><?= !empty($customer->status_approval) ? $customer->status_approval : " - " ?></strong>
                </div>
            </div>
            <?php endif ?>

            <?php if ((!empty($customer->approve_reason) || !empty($customer->reject_reason)) && !empty($model->reff_no) ) : ?>
                <?php foreach (['level1' => 22,'level2' => 19] as $level => $pegawai_id) : ?>
                    <?php
                    $pegawai    = MPegawai::findOne(['pegawai_id' => $pegawai_id]);
                    $t_approval = TApproval::findOne(['reff_no' => $model->reff_no, 'assigned_to' => $pegawai_id]);
                    ?>
                    <div class="col-md-12" style="color: #000; margin-top: 10px; margin-left: 15px; margin-bottom: 10px; font-size: 1.2rem;">
                    <?php
                    $reason_approve = "";
                    $reason_reject  = "";
                    $color          = "";
                    if ($t_approval->status == "APPROVED") {
                        $color          = "#38C68B";
                        $reasons        = Json::decode($customer->approve_reason);
                        foreach($reasons as $reason) {
                            if ($pegawai_id == $reason['by']) {
                                $reason_approve = $reason['reason'];
                            }
                        }
                    }

                    if ($t_approval->status == "REJECTED") {
                        $color          = "#f00";
                        $reasons        = Json::decode($customer->reject_reason);
                        foreach($reasons as $reason) {
                            if ($pegawai_id == $reason['by']) {
                                $reason_reject = $reason['reason'];
                            }
                        }
                    }
                    ?>
                    <span style="color: <?= $color;?>"><strong><?= $pegawai->pegawai_nama;?></strong></span>
                    <br>
                    <span style="color: <?php echo $color;?>"><?= $t_approval->status;?></span>
                    <span style="color: <?= $color;?>">at <?= DeltaFormatter::formatDateTimeForUser2($t_approval->updated_at);?></span>
                    <br>
                    <span style="color: <?php echo $color;?>"><?= $t_approval->status == "APPROVED" ? $reason_approve : $reason_reject ?></span>
                </div>
                <?php endforeach; ?>
            <?php endif ?>

        </div>
    </div>
    <div class="row" style="margin-top: 30px;">
        <div class="form-group col-md-4">
            <?php
                $check = TApproval::findOne(['reff_no' => $model->reff_no, 'level' => 2]);
                $lama = !empty($model_h_cust_top) && $check->status <> 'APPROVED' ?  "Lama" : "";
            ?>
            <div class="form-group col-md-6">
                <h4 class="" style="font-size: 15px;">
                    <?= Yii::t('app', 'Term of Payment '. $lama .' :'); ?>
                </h4>
                <?php if(!empty($model_m_cust_top)): ?>
                    <table class="table table-bordered table-hover col-md-5">
                        <tr>
                            <th>JENIS JASA</th>
                            <th style="width: 80px;">PERIODE</th>
                        </tr>
                        <?php foreach($model_m_cust_top as $i => $topLama): ?>
                        <tr>
                            <th style="padding: 5px"><?= $topLama->custtop_jns ?> </th>
                            <td style="padding: 5px" class="text-center"><?= $topLama->custtop_top." ". Yii::t('app', 'Hari'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else : ?>
                    <table class="table table-bordered table-hover col-md-5">
                        <tr>
                            <td style="padding: 5px; text-align: center">
                                <?= Yii::t('app', 'Belum di Set'); ?>
                            </td>
                        </tr>
                    </table>
                <?php endif ?>
            </div>
            <?php if(!empty($model_h_cust_top) && $check->status <> 'APPROVED'): ?>
            <div class="form-group col-md-6">
                <h4 class="" style="font-size: 15px;"><?= Yii::t('app', 'Term of Payment Baru '); ?>
                    <span style="font-size: 10px; padding-bottom: 8px; "></span>
                </h4>
                <table class="table table-bordered table-hover col-md-5">
                    <tr>
                        <th>JENIS JASA</th>
                        <th style="width: 80px;">PERIODE</th>
                    </tr>
                    <?php foreach($model_h_cust_top as $i => $topBaru): ?>
                    <tr>
                        <th style="padding: 5px"><?= $topBaru->custtop_jns ?> </th>
                        <td style="padding: 5px" class="text-center"><?= $topBaru->custtop_top." ". Yii::t('app', 'Hari'); ?></td>
                    </tr>
                    <?php endforeach ?>
                </table>
            </div>
            <?php endif ?>
        </div>
        <div class="form-group col-md-8 row">
            <div class="col-md-12 row">
                <div class="col-md-4">
                    <h4 class=""><?= $label['cust_file_ktp']; ?> : </h4>
                    <?php
//                    echo "<pre>";print_r($customer->cust_file_ktp);die;
                    if(!empty($customer->cust_file_ktp)){
                        echo '<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('."'ktp', "."'".$customer->kode_customer."'".')">';
                        echo '<img src="'. Url::base().'/uploads/mkt/customer/'.$customer->cust_file_ktp .'" alt="ktp-scan" style="height: 150px; width: 100%; display: block;" class="img-responsive imgsp"> ';
                        echo '</a>';
                    }else{
                        echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="ktp-scan" style="width: 100%; display: block;"> ';
                    }
                    ?>
                </div>
                <div class="col-md-4">
                    <h4 class=""><?= $label['cust_file_npwp']; ?> : </h4>
                    <?php
                    if(!empty($customer->cust_file_npwp)){
                        echo '<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('."'npwp', "."'".$customer->kode_customer."'".')">';
                        echo '<img src="'. Url::base().'/uploads/mkt/customer/'.$customer->cust_file_npwp .'" alt="npwp-scan" style="height: 150px; width: 100%; display: block;" class="img-responsive imgsp"> ';
                        echo '</a>';
                    }else{
                        echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="ktp-scan" style="width: 100%; display: block;"> ';
                    }
                    ?>
                </div>
                <div class="col-md-4">
                    <h4 class><?= $label['cust_file_photo']; ?> : </h4>
                    <?php
                    if(!empty($customer->cust_file_photo)){
                        echo '<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('."'photo', "."'".$customer->kode_customer."'".')">';
                        echo '<img src="'. Url::base().'/uploads/mkt/customer/'.$customer->cust_file_photo .'" alt="npwp-scan" style="height: 150px; width: 100%; display: block;" class="img-responsive imgsp"> ';
                        echo '</a>';
                    }else{
                        echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="ktp-scan" style="width: 100%; display: block;"> ';
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-12 row text-center text-bold text-danger" style="margin-top: 50px;"><span style="color: #f00;">* Klik gambar untuk memperbesar</span></div>
        </div>
    </div>
</div>
<div class="modal-footer" style="text-align: center;">
    <?php if (isset($model->status) && $model->status == "Not Confirmed") {
        if( (empty($modApprove->approved_by)) && (empty($modApprove->tanggal_approve)) ){
            if(( Yii::$app->user->identity->user_group_id != Params::USER_GROUP_ID_OWNER )) {
                $modCUS = MCustomer::findOne(['kode_customer'=>$model->reff_no]);
                $sql = "select * from t_approval where reff_no = trim('".$modCUS->kode_customer."') AND level < ".$model->level." AND status != 'Not Confirmed' ";
                try {
                    $checkApprovals = Yii::$app->db->createCommand($sql)->queryAll();
                } catch (\yii\db\Exception $e) {
                    echo "<script>alert('{$e->getMessage()}')</script>";
                }

                if ($model->level == 1 && isset($checkApprovals) && count($checkApprovals) == 0) {
                    echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
                    echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
                }

                if ($model->level == 2 && isset($checkApprovals) && count($checkApprovals) > 0) {
                    echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
                    echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
                }
            }
        }
    } else {
        $customer->status_approval == "APPROVED" ? $hasil_keputusan = "disetujui" : $hasil_keputusan = "ditolak";
    ?>
    Data customer sudah <?= $hasil_keputusan;?>
    <?php
    }
    ?>
</div>
<?php endif ?>