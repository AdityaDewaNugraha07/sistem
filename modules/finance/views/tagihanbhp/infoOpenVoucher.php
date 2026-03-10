<div class="modal fade" id="modal-info-ov" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Informasi Open Voucher <b>' . $model->kode . '</b>');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
							<div class="col-md-7"><strong><?= $model->kode ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
							<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Prepared By'); ?></label>
							<div class="col-md-7"><strong><?php $modPeg = \app\models\MPegawai::findOne($model->prepared_by); echo $modPeg->pegawai_nama; ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Pembayaran'); ?></label>
							<div class="col-md-7">
                                <strong>
                                    <?php
                                    $modVoc = \app\models\TVoucherPengeluaran::findOne($model->voucher_pengeluaran_id);
                                    echo app\components\DeltaFormatter::formatDateTimeForUser2($modVoc->tanggal_bayar);
                                    ?>
                                </strong>
                            </div>
						</div>
					</div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Penerima Pembarayan'); ?></label>
							<div class="col-md-7">
                                <strong>
                                    <?php
                                    if($model->penerima_reff_table == 'm_penerima_voucher'){
                                        $modPenerima = \app\models\MPenerimaVoucher::findOne($model->penerima_voucher_id);
                                        echo $modPenerima->nama_penerima . " (" . $modPenerima->nama_perusahaan . ")";
                                    } else {
                                        $modPenerima = \app\models\MSuplier::findOne($model->penerima_reff_id);
                                        echo $modPenerima->suplier_nm . " (" . $modPenerima->suplier_nm_company . ")";
                                    }
                                    ?>
                                </strong>
                            </div>
						</div>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Rekening Bank'); ?></label>
							<div class="col-md-7">
                                <strong>
                                    <?php
                                    $modOv = \app\models\TOpenVoucher::findOne($model->open_voucher_id);
                                    $modVoc = \app\models\TVoucherPengeluaran::findOne($modOv->voucher_pengeluaran_id);
                                    if($modVoc->penerima_pembayaran !== null){
                                        $penerima = json_decode($modVoc->penerima_pembayaran);
                                        $bank = $penerima[0]->nama_bank;
                                        $rek = $penerima[0]->rekening;
                                        $rek_an = $penerima[0]->an_bank;
                                        echo $bank .' - '. $rek  .'<br>a.n. '. $rek_an;
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </strong>
                            </div>
						</div>
					</div>
                </div>
				<br>
                <div class="row">
                    <div class="col-md-12" style="padding-left: 50px; padding-right: 50px;">
                        <h4>Detail Uraian Voucher</h4>
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 3%;">No</th>
                                        <th>Deskripsi</th>
                                        <th style="width: 10%;">Nominal</th>
                                        <th style="width: 10%;">PPN</th>
                                        <th style="width: 10%;">Pph</th>
                                        <th style="width: 10%;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $modDetail = \app\models\TOpenVoucherDetail::findAll(['open_voucher_id'=>$model->open_voucher_id]);
                                    if(count($modDetail) > 0){
                                        $total_dpp = 0;
                                        $total_ppn = 0;
                                        $total_pph = 0;
                                        foreach($modDetail as $i => $det){
                                            $total_dpp += $det['nominal'];
                                            $total_ppn += $det['ppn'];
                                            $total_pph += $det['pph'];
                                            ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $i+1; ?></td>
                                                <td><?= $det['deskripsi']; ?></td>
                                                <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($det['nominal']); ?></td>
                                                <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($det['ppn']); ?></td>
                                                <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($det['pph']); ?></td>
                                                <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($det['subtotal']); ?></td>
                                            </tr>
                                    <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="6">Data tidak ditemukan</td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" style="text-align: right; font-weight: bold;">Total DPP</td>
                                        <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($total_dpp); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align: right; font-weight: bold;">Total PPN</td>
                                        <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($total_ppn); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align: right; font-weight: bold;">Total Pph</td>
                                        <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($total_pph); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align: right; font-weight: bold;">Potongan</td>
                                        <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($model->total_potongan); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL PEMBAYARAN</td>
                                        <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($model->total_pembayaran); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <br>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>