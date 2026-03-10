<?php

/** @var TApproval $model */

use app\components\DeltaFormatter;
use app\models\MBrgProduk;
use app\models\MPegawai;
use app\models\TApproval;
use app\models\TPackinglist;
use app\models\TPengajuanManipulasi;
use app\models\TProdukKeluar;
use app\models\TProdukKembali;
use app\models\TSpmKo;
use yii\helpers\Json;

$conditions = ['reff_no' => $model->reff_no, 'kode' => $model->parameter1];
if(isset($_GET['status']) && $_GET['status'] === TApproval::STATUS_NOT_CONFIRMATED) {
    $conditions['status'] = 'PROCESS';
}
$modReff = TPengajuanManipulasi::findOne($conditions);
//echo '<pre>'; print_r($modReff);die;
$spm = Json::decode($modReff->datadetail1, false);
$spmd = Json::decode($modReff->datadetail2, false);
$packinglist = TPackinglist::findOne(['packinglist_id' => $spm->packinglist_id]);

?>
<style>
    .form-group {
        margin-bottom: 0 !important;
    }
</style>
<div class="modal-body">
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-4">
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode SPM') ?></label>
                <div class="col-md-7"><strong><?= $spm->kode ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal SPM') ?></label>
                <div class="col-md-7">
                    <strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($spm->tanggal) ?></strong>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Kirim') ?></label>
                <div class="col-md-7">
                    <strong><?= DeltaFormatter::formatDateTimeForUser2($spm->tanggal_kirim) ?></strong></div>
            </div>
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
                        if ($model->level === 1) {
                            if ($model->status === TApproval::STATUS_APPROVED) {
                                echo '<span class="label label-success">' . $model->status . '</span>';
                            } else if ($model->status === TApproval::STATUS_NOT_CONFIRMATED) {
                                echo '<span class="label label-default">' . $model->status . '</span>';
                            } else if ($model->status === TApproval::STATUS_REJECTED) {
                                echo '<span class="label label-danger">' . $model->status . '</span>';
                            }
                        }
                        ?>
                    </strong></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Packinglist') ?></label>
                <div class="col-md-7"><strong><?= $packinglist->nomor ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk') ?></label>
                <div class="col-md-7"><strong><?= $packinglist->jenis_produk ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Shipment To') ?></label>
                <div class="col-md-7">
                    <strong><?= $packinglist->cust->cust_an_nama . ', ' . $packinglist->cust->cust_an_alamat ?></strong>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Port of Loading') ?></label>
                <div class="col-md-7">
                    <strong><?= $packinglist->port_of_loading ?></strong>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Final Destination') ?></label>
                <div class="col-md-7">
                    <strong><?= $packinglist->final_destination ?></strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Nopol Kendaraan') ?></label>
                <div class="col-md-7"><strong><?= $spm->kendaraan_nopol ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Supir') ?></label>
                <div class="col-md-7"><strong><?= $spm->kendaraan_supir ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Rencana Muat') ?></label>
                <div class="col-md-7"><strong><?= DeltaFormatter::formatDateTimeForUser2($spm->tanggal_rencanamuat) ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Status') ?></label>
                <div class="col-md-7"><strong style="background-color: <?= $spm->status === TSpmKo::REALISASI ? '#95EBA3' : '#FBE88C' ?>"><?= $spm->status === TSpmKo::REALISASI ? 'SUDAH REALISASI' : 'BELUM REALISASI' ?></strong></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-5 control-label"><?= Yii::t('app', 'Alasan Hapus') ?></label>
                <div class="col-md-7">
                    <strong><?= $modReff->reason ?></strong>
                </div>
            </div>
        </div>
    </div>
    <?php if($spm->status === TSpmKo::REALISASI): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue-hoki bordered">
                <div class="portlet-title">
                    <div class="tools" style="float: left;">
                        <a href="javascript:void(0)" class="collapse" data-original-title="" title=""> </a> &nbsp;
                    </div>
                    <div class="caption"> <?= Yii::t('app', 'Realisasi Produk List (Hasil Scan)') ?> </div>
                </div>
                <div class="portlet-body" style="background-color: #d9e2f0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-advance table-hover"
                                       id="table-detail">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
                                        <th rowspan="2" style="width: 90px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode Barang Jadi') ?></th>
                                        <th rowspan="2" style="width: 150px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode Produk') ?></th>
                                        <th rowspan="2" style="width: 150px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Nama Produk') ?></th>
                                        <th colspan="3" style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty') ?></th>
                                        <th rowspan="2" style="width: 100px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Scanned By') ?></th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Palet') ?></th>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 120px; padding: 5px;"><?= Yii::t('app', 'Satuan Kecil') ?></th>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $no = 1;
                                    $products = TProdukKeluar::findAll(['reff_no' => $spm->kode]);
                                    if (count($products) > 0) {
                                        foreach ($products as $i => $product): ?>
                                            <tr>
                                                <td style="text-align: center"><?= $no ?></td>
                                                <td><?= $product->nomor_produksi ?></td>
                                                <td><?= $product->produk->produk_kode ?></td>
                                                <td><?= $product->produk->produk_nama ?></td>
                                                <td><?= $product->qty_besar ?></td>
                                                <td><?= $product->qty_kecil . ' (' . $product->satuan_kecil . ')' ?></td>
                                                <td><?= number_format($product->kubikasi, 4) ?></td>
                                                <td><?= $product->petugasMengeluarkan->pegawai_nama ?></td>
                                            </tr>
                                            <?php $no++;endforeach;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php endif ?>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue-hoki bordered">
                <div class="portlet-title">
                    <div class="tools" style="float: left;">
                        <a href="javascript:void(0)" class="collapse" data-original-title="" title=""> </a> &nbsp;
                    </div>
                    <div class="caption"> <?= Yii::t('app', 'Show Detail') ?> </div>
                </div>
                <div class="portlet-body" style="background-color: #d9e2f0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-advance table-hover"
                                       id="table-detail">
                                    <thead>
                                    <tr>
                                        <th rowspan="2"
                                            style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.
                                        </th>
                                        <th rowspan="2"
                                            style="width: 400px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Produk') ?></th>
                                        <th colspan="3"
                                            style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty Pesan') ?></th>
                                        <th colspan="3"
                                            style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty Realisasi') ?></th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px;"><?= Yii::t('app', 'Palet') ?></th>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 100px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil') ?></th>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 40px; padding: 5px;"><?= Yii::t('app', 'Palet') ?></th>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 100px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil') ?></th>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 70px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $no = 1;
                                    if (count($spmd) > 0) {
                                        foreach ($spmd as $i => $detail): ?>
                                            <tr>
                                                <td style="text-align: center"><?= $no ?></td>
                                                <td>
                                                    <?php
                                                    $produk = MBrgProduk::findOne(['produk_id' => $detail->produk_id]);
                                                    echo $produk->produk_nama . ' (' . $produk->produk_dimensi . ' )';
                                                    ?>
                                                </td>
                                                <td><?= $detail->qty_besar ?></td>
                                                <td><?= $detail->satuan_kecil ?></td>
                                                <td><?= number_format($detail->kubikasi, 4) ?></td>
                                                <td><?= $detail->qty_besar_realisasi ?></td>
                                                <td><?= $detail->satuan_kecil_realisasi ?></td>
                                                <td><?= number_format($detail->kubikasi_realisasi, 4) ?></td>
                                            </tr>
                                            <?php $no++;endforeach;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <?php
                        $tdPlus = false;
                        if($spm->status === TSpmKo::REALISASI) {
                            $caption = 'Barang yang sudah di scan kembali gudang';
                            $tdPlus  = true;
                            $items   = TProdukKembali::findAll(['reff_no' => $modReff->kode]);
                            $message = 'Tidak ada barang yang di scan';
                        }else {
                            $caption = 'Barang yang harus di hapus dari scan muat';
                            $items   = TProdukKeluar::findAll(['reff_no' => $spm->kode]);
                            $message = 'Semua barang scan muat dengan nomor referensi ' . $spm->kode . ' Sudah di hapus';
                        }
                    ?>
                    <div class="caption"> <?= Yii::t('app', $caption) ?> </div>
                </div>
                <div class="portlet-body" style="background-color: #d9e2f0;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-advance table-hover"
                                       id="table-detail">
                                    <thead>
                                    <tr>
                                        <th rowspan="2"
                                            style="width: 30px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">
                                            No.
                                        </th>
                                        <th rowspan="2"
                                            style="width: 90px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode Barang Jadi') ?></th>
                                        <?php if($tdPlus) : ?>
                                        <th rowspan="2"
                                            style="width: 90px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Lokasi Gudang') ?></th>
                                        <?php endif ?>
                                        <th colspan="3"
                                            style="line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty') ?></th>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 50px; padding: 5px;"><?= Yii::t('app', 'Palet') ?></th>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 120px; padding: 5px;"><?= Yii::t('app', 'Satuan<br>Kecil') ?></th>
                                        <th style="font-size: 1.2rem; line-height: 0.9; width: 80px; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; if(count($items) > 0):
                                            foreach ($items as $item): ?>
                                            <tr>
                                                <td style="text-align: center"><?= $no ?></td>
                                                <td><?= $item->nomor_produksi ?></td>
                                                <?php if($tdPlus): ?>
                                                <td class="text-center"><?= $item->gudang->gudang_nm ?></td>
                                                <?php endif ?>
                                                <td class="text-center"><?= $item->qty_besar ?></td>
                                                <td class="text-right"><?= $item->qty_kecil . ' (' . $item->satuan_kecil . ')' ?></td>
                                                <td class="text-right"><?= $item->kubikasi ?></td>
                                            </tr>
                                        <?php $no++;endforeach;else: ?>
                                            <td colspan="<?= $tdPlus ? 7 : 6?>" style="text-align: center; padding: 10px;font-weight: bold; font-style: italic"><?= $message ?></td>
                                        <?php endif ?>
                                    </tbody>
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
                <?= MPegawai::findOne(['pegawai_id' => $approval->pegawai_id])->pegawai_nama ?>
                <br>
                <?= $approval->tanggal !== null ? DeltaFormatter::formatDateTimeForUser2($approval->tanggal) . '<br>' : '' ?>
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
            $prev_approve = TApproval::findOne(['reff_no' => $model->reff_no, 'level' => $model->level - 1, 'parameter1' => $modReff->kode]);
            if($prev_approve->status === 'Not Confirmed') {
                $tampil = false;
            }
        }

        ?>
        <?php if ((empty($model->approved_by)) && (empty($model->tanggal_approve)) && $tampil): ?>
            <?= yii\helpers\Html::button(Yii::t('app', 'Approve'), ['class' => 'btn hijau btn-outline', 'onclick' => "approve(" . $model->approval_id . ")"]) ?>
            <?= yii\helpers\Html::button(Yii::t('app', 'Reject'), ['class' => 'btn red btn-outline', 'onclick' => "reject(" . $model->approval_id . ")"]) ?>
        <?php else: ?>
            <?php if($model->status === 'Not Confirmed'): ?>
                <p style="color: red; font-style: italic">*Approval ini belum dapat di konfirmasi, karena <?= $prev_approve->assignedTo->pegawai_jk === 'Perempuan' ? 'Ibu ' : 'Bapak ' ?> <strong><?= ucwords(strtolower($prev_approve->assignedTo->pegawai_nama)) ?></strong> belum melakukan konfirmasi.</p>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>