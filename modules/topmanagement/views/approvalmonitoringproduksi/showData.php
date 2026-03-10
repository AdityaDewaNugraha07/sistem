<?php

/** @var TApproval $model */

use app\components\DeltaFormatter;
use app\models\MDefaultValue;
use app\models\MMtrgSetup;
use app\models\MPegawai;
use app\models\TApproval;
use app\models\TMtrgInOut;
use app\models\TMtrgInOutDetail;
use yii\helpers\Html;
use yii\helpers\Json;

$modReff = TMtrgInOut::findOne(['kode' => $model->reff_no]);
$modReffDetail = TMtrgInOutDetail::findAll(['mtrg_in_out_id' => $modReff->mtrg_in_out_id]);
// echo "<pre>";print_r($modReffDetail);die;

?>
<div class="modal-body">
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-4">
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Nomor Referensi') ?></label>
                <div class="col-md-7"><strong><?= $modReff->kode ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Kupas') ?></label>
                <div class="col-md-7">
                    <strong><?= DeltaFormatter::formatDateTimeForUser2($modReff->tanggal_kupas) ?></strong>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Produksi') ?></label>
                <div class="col-md-7">
                    <strong><?= DeltaFormatter::formatDateTimeForUser2($modReff->tanggal_produksi) ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Tahap Proses') ?></label>
                <div class="col-md-7"><strong><?= $modReff->kategori_proses ?></strong></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Shift') ?></label>
                <div class="col-md-7"><strong><?= $modReff->shift ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Kayu') ?></label>
                <div class="col-md-7"><strong><?= $modReff->jenis_kayu ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'I/O') ?></label>
                <div class="col-md-7"><strong><?= $modReff->status_in_out ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Berkas') ?></label>
                <div class="col-md-7">
                    <strong><?= DeltaFormatter::formatDateTimeForUser2($model->tanggal_berkas) ?></strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $model->attributeLabels()['assigned_to'] ?></label>
                <div class="col-md-7"><strong><?= $model->assignedTo->pegawai_nama ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $model->attributeLabels()['approved_by'] ?></label>
                <div class="col-md-7">
                    <strong><?= !empty($model->approved_by) ? $model->approvedBy->pegawai_nama : "-" ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $model->attributeLabels()['tanggal_approve'] ?></label>
                <div class="col-md-7">
                    <strong><?= !empty($model->tanggal_approve) ? app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve) : "-" ?></strong>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= $model->attributeLabels()['status'] ?></label>
                <div class="col-md-7"><strong>
                        <?php
                        if ($model->status === TApproval::STATUS_APPROVED) {
                            echo '<span class="label label-success">' . $model->status . '</span>';
                        } else if ($model->status === TApproval::STATUS_NOT_CONFIRMATED) {
                            echo '<span class="label label-default">' . $model->status . '</span>';
                        } else if ($model->status === TApproval::STATUS_REJECTED) {
                            echo '<span class="label label-danger">' . $model->status . '</span>';
                        }
                        ?>
                    </strong></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue-hoki bordered">
                <div class="portlet-title">
                    <div class="tools" style="float: left;">
                        <a href="javascript:void(0)" class="collapse" data-original-title="" title=""> </a> &nbsp;
                    </div>
                    <div class="caption"> <?= Yii::t('app', "DETAIL $modReff->status_in_out") ?> </div>
                </div>
                <div class="portlet-body" style="background-color: #d9e2f0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-advance table-hover"
                                       id="table-detail">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th><?= Yii::t('app', 'Unit') ?></th>
                                        <th><?= Yii::t('app', 'Tebal') ?></th>
                                        <th><?= Yii::t('app', 'Size') ?></th>
                                        <th><?= Yii::t('app', $modReff->kategori_proses === MMtrgSetup::KATEGORI_PLYTECH ? 'Patch' : 'Grade') ?></th>
                                        <th><?= Yii::t('app', 'PCS') ?></th>
                                        <th><?= Yii::t('app', 'Volume') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $no = 1;
                                    $pcs = 0;
                                    $volume = 0;
                                    if (count($modReffDetail) > 0):
                                        foreach ($modReffDetail as $i => $detail): ?>
                                            <tr>
                                                <td class="text-center" style="width: 30px"><?= $no ?></td>
                                                <td class="text-center"><?= $detail->unit ?></td>
                                                <td class="text-center"><?= $detail->tebal ?></td>
                                                <td class="text-center"><?= MDefaultValue::findOne(['value' => $detail->size, 'type' => 'size'])->name ?></td>
                                                <?php if($modReff->kategori_proses === MMtrgSetup::KATEGORI_PLYTECH): ?>
                                                <td class="text-center"><?= $detail->patching?></td>
                                                <?php else: ?>
                                                <td class="text-center"><?= $detail->grade ?></td>
                                                <?php endif ?>
                                                <td class="text-center"><?= $detail->pcs ?></td>
                                                <td class="text-center"><?= $detail->volume?></td>
                                            </tr>
                                    <?php
                                    $no++;
                                    $volume += $detail->volume;
                                    $pcs += $detail->pcs;
                                    endforeach;
                                    endif;
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right">Total:</th>
                                        <th class="text-center"><?= $pcs ?></th>
                                        <th class="text-center"><?= $volume ?></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal-footer" style="text-align: center;">
    <div id="place-approval" style="display: flex; justify-content: space-evenly;">
        <?php foreach (Json::decode($modReff->reason_approval, false) as $approval):
            $class = '';
            if($approval->status === TApproval::STATUS_APPROVED) {
                $class = 'green-meadow';
            }else if(in_array($approval->status, ['ABORTED', 'REJECTED'])) {
                $class = 'red-flamingo';
            }else {
                $class = 'dark';
            } ?>
            <div class="btn btn-outline <?= $class ?>"
                 style="font-size: 10px;">
                <?= MPegawai::findOne(['pegawai_id' => $approval->assigned_to])->pegawai_nama ?>
                <br>
                <?= $approval->tanggal_approve !== null ? DeltaFormatter::formatDateTimeForUser2($approval->tanggal_approve) . '<br>' : '' ?>
                <strong><?= $approval->status?></strong>
                <?= $approval->reason !== '' ? '<br>' . $approval->reason : '' ?>
            </div>
        <?php endforeach; ?>
    </div>
    <hr>
    <div id="place-button">
        <?php
        // cek approval sebelumnya
        $tampil = true;
        $prev_approve = null;
        if($model->level !== 1) {
            $prev_approve = TApproval::findOne(['reff_no' => $model->reff_no, 'level' => $model->level - 1]);
            if($prev_approve->status === 'Not Confirmed') {
                $tampil = false;
            }
        }

        ?>
        <?php if ((empty($model->approved_by)) && (empty($model->tanggal_approve)) && $tampil): ?>
            <?= Html::button(Yii::t('app', 'Approve'), ['class' => 'btn hijau btn-outline', 'onclick' => "approve(" . $model->approval_id . ")"]) ?>
            <?= Html::button(Yii::t('app', 'Reject'), ['class' => 'btn red btn-outline', 'onclick' => "reject(" . $model->approval_id . ")"]) ?>
        <?php else: ?>
            <?php if($model->status === 'Not Confirmed'): ?>
                <p style="color: red; font-style: italic">*Approval ini belum dapat di konfirmasi, karena <?= $prev_approve->assignedTo->pegawai_jk === 'Perempuan' ? 'Ibu ' : 'Bapak ' ?> <strong><?= ucwords(strtolower($prev_approve->assignedTo->pegawai_nama)) ?></strong> belum melakukan konfirmasi.</p>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>


