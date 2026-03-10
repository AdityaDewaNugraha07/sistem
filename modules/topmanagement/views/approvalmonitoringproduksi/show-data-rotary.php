<?php

/** @var TApproval $model */

use app\components\DeltaFormatter;
use app\models\MPegawai;
use app\models\TApproval;
use app\models\TMtrgRotary;
use app\models\TMtrgRotaryDetail;
use yii\helpers\Html;
use yii\helpers\Json;

$modReff = TMtrgRotary::findOne(['kode' => $model->reff_no]);
$modReffDetail = TMtrgRotaryDetail::findAll(['mtrg_rotary_id' => $modReff->mtrg_rotary_id]);

?>
<div class="modal-body">
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-4">
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Nomor Referensi') ?></label>
                <div class="col-md-7"><strong><?= $modReff->kode ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal') ?></label>
                <div class="col-md-7">
                    <strong><?= DeltaFormatter::formatDateTimeForUser2($modReff->tanggal) ?></strong>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Shift') ?></label>
                <div class="col-md-7"><strong><?= $modReff->shift ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Kayu') ?></label>
                <div class="col-md-7"><strong><?= $modReff->jenis_kayu ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Jam Jalan') ?></label>
                <div class="col-md-7"><strong><?= $modReff->jam_jalan > 0 ? $modReff->jam_jalan / 60 : 0?> Jam</strong></div>
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
                    <strong><?= !empty($model->tanggal_approve) ? DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve) : "-" ?></strong>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Berkas') ?></label>
                <div class="col-md-7">
                    <strong><?= DeltaFormatter::formatDateTimeForUser2($model->tanggal_berkas) ?></strong>
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
                    <div class="caption"> <?= Yii::t('app', "Detail Input Rotary") ?> </div>
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
                                        <th><?= Yii::t('app', 'Suplier') ?></th>
                                        <th><?= Yii::t('app', 'Unit') ?></th>
                                        <th><?= Yii::t('app', 'Diameter') ?></th>
                                        <th><?= Yii::t('app', 'Panjang') ?></th>
                                        <th><?= Yii::t('app', 'PCS') ?></th>
                                        <th><?= Yii::t('app', 'Volume') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $no = 1;
                                    $volume = 0;
                                    $pcs = 0;
                                    if (count($modReffDetail) > 0):
                                        foreach ($modReffDetail as $i => $detail): ?>
                                            <tr style="text-align: center">
                                                <td style="text-align: center"><?= $no ?></td>
                                                <td style="text-align: left"><?= $detail->suplier->suplier_nm, $detail->suplier->suplier_almt ?></td>
                                                <td><?= $detail->unit ?></td>
                                                <td><?= $detail->diameter ?></td>
                                                <td><?= $detail->panjang ?></td>
                                                <td><?= $detail->pcs ?></td>
                                                <td><?= $detail->volume ?></td>
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


