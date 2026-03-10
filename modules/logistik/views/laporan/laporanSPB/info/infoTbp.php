<?php
/** @var array $mark_spb */

use app\components\DeltaFormatter;
use app\models\TTerimaBhp;

?>
    <div class="modal fade" id="modal-info-tbp" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                            aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                    <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi TBP') ?></h4>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <td><?= Yii::t('app', 'Kode') ?></td>
                            <th>: <?= /** @var TTerimaBhp $model */
                                $model->terimabhp_kode . (!empty($model->terimabhp_nomor) ? " / " . $model->terimabhp_nomor : "") ?></th>
                            <td><?= Yii::t('app', 'Penerima') ?></td>
                            <th>
                                : <?= !empty($model->pegawaipenerima0) ? $model->pegawaipenerima0->pegawai_nama : '' ?></th>
                            <td><?= Yii::t('app', 'Suplier') ?></td>
                            <th>: <?= !empty($model->suplier) ? $model->suplier->suplier_nm : '' ?></th>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'Tanggal') ?></td>
                            <th>
                                : <?= !empty($model->tglterima) ? DeltaFormatter::formatDateTimeForUser2($model->tglterima) : '' ?></th>
                            <td><?= Yii::t('app', 'Checker') ?></td>
                            <th>
                                : <?= !empty($model->pegawai_checker) ? $model->pegawaichecker0->pegawai_nama : '' ?> </th>
                            <td><?= Yii::t('app', 'Status') ?></td>
                            <th>: <?= !empty($model->status_approval) ? $model->status_approval : '' ?></th>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'No. Faktur') ?></td>
                            <th>: <?= $model->nofaktur ?></th>
                            <td><?= Yii::t('app', 'Tanggal Pengecekan') ?></td>
                            <th>
                                : <?= !empty($model->tanggal_jam_checker) ? DeltaFormatter::formatDateTimeForUser($model->tanggal_jam_checker) : '' ?></th>
                            <td><?= Yii::t('app', 'Keterangan') ?></td>
                            <th>: <?= !empty($model->terimabhp_keterangan) ? $model->terimabhp_keterangan : '' ?></th>
                        </tr>
                        <tr>
                            <td><?= Yii::t('app', 'No. Faktur Pajak') ?></td>
                            <th>: <?= $model->no_fakturpajak ?></th>
                        </tr>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr style="background-color: #F1F4F7; ">
                                        <th style="text-align: center;"><?= Yii::t('app', 'No.') ?></th>
                                        <th style="text-align: center;"><?= Yii::t('app', 'Items') ?></th>
                                        <th style="text-align: center;"><?= Yii::t('app', 'Keterangan') ?></th>
                                        <th style="text-align: center;"><?= Yii::t('app', 'Qty') ?></th>
                                        <th style="text-align: center;"><?= Yii::t('app', 'Harga (Rp)') ?></th>
                                        <th style="text-align: center;"><?= Yii::t('app', 'Sub Total (Rp)') ?></th>
                                        <!-- <th style="text-align: center; width: 60px;"><?= Yii::t('app', 'Checked') ?></th> -->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $pph = 0;
                                    $total_harga = 0;
                                    foreach ($model->tTerimaBhpDetails as $key => $detail): ?>
                                        <tr style="<?= in_array($detail->terima_bhpd_id, $mark_spb, true) ? 'background: #ffb62b4f;' : '' ?>">
                                            <td style="text-align: center;"><?= $key + 1 ?></td>
                                            <td><?= $detail->bhp->bhp_nm ?></td>
                                            <td style="font-size: 1.1rem"><?= $detail->terimabhpd_keterangan ?></td>
                                            <td style="text-align: center;"><?= !empty($detail->terimabhpd_qty) ? $detail->terimabhpd_qty : 0 ?></td>
                                            <td style="text-align: right;"><?= !empty($detail->terimabhpd_harga) ? DeltaFormatter::formatUang($detail->terimabhpd_harga) : '' ?></td>
                                            <td style="text-align: right;"><?= DeltaFormatter::formatUang($detail->terimabhpd_qty * $detail->terimabhpd_harga) ?></td>
                                        </tr>
                                        <?php $pph += $detail->pph_peritem;
                                        $total_harga += $detail->terimabhpd_qty * $detail->terimabhpd_harga; endforeach; ?>
                                    <?php if ($model->ppn_nominal > 0): ?>
                                        <tr>
                                            <th colspan="5" class="text-right">PPN</th>
                                            <td class="text-right"><?= DeltaFormatter::formatUang($model->ppn_nominal) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($pph > 0): ?>
                                        <tr>
                                            <th colspan="5" class="text-right">PPH</th>
                                            <td class="text-right"><?= DeltaFormatter::formatUang($pph) ?></td>
                                        </tr>
                                    <?php endif ?>
                                    <?php if ($model->total_pbbkb > 0): ?>
                                        <tr>
                                            <th colspan="5" class="text-right">PBBKB</th>
                                            <td class="text-right"><?= DeltaFormatter::formatUang($model->total_pbbkb) ?></td>
                                        </tr>
                                    <?php endif ?>
                                    <?php if ($model->total_biayatambahan > 0): ?>
                                        <tr>
                                            <th colspan="5" class="text-right">
                                                Biaya Tambahan
                                                <?= $model->label_biayatambahan ? '<br/><span style="font-size: 9px;color: #999;">' . $model->label_biayatambahan . '</span>' : '' ?>
                                            </th>
                                            <td class="text-right"><?= DeltaFormatter::formatUang($model->total_biayatambahan) ?></td>
                                        </tr>
                                    <?php endif ?>
                                    <tr>
                                        <th colspan="5" class="text-right">Total</th>
                                        <td class="text-right"><?= DeltaFormatter::formatUang(
                                                $model->ppn_nominal +
                                                $model->total_pbbkb +
                                                $model->total_biayatambahan +
                                                $pph +
                                                $total_harga
                                            ) ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>