<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-detailRandom" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Detail Random '.$nomor_produksi); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersavex">
                            <thead>
                                <th style="width: 40px;">No.</th>
                                <th class="td-kecil" style="line-height: 1;"><?= Yii::t('app', 'Kode Barang Jadi') ?></th>
                                <th class="td-kecil" style="line-height: 1;"><?= Yii::t('app', 'Produk') ?></th>
                                <th class="td-kecil" style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Keluar') ?></th>
                                <th class="td-kecil" style="line-height: 1;"><?= Yii::t('app', 'Reff No') ?></th>
                                <th class="td-kecil" style="line-height: 1;"><?= Yii::t('app', 'Pcs') ?></th>
                                <th class="td-kecil" style="line-height: 1; width: 70px;"><?= Yii::t('app', 'T') ?></th>
                                <th class="td-kecil" style="line-height: 1; width: 70px;"><?= Yii::t('app', 'L') ?></th>
                                <th class="td-kecil" style="line-height: 1; width: 70px;"><?= Yii::t('app', 'P') ?></th>
                                <th class="td-kecil" style="line-height: 1;"><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                <th class="td-kecil" style="line-height: 1;"><?= Yii::t('app', 'Keterangan') ?></th>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $total_pcs = 0;
                                $total_kubikasi = 0;
                                foreach ($list as $kolom) {
                                    if ($kolom['cara_keluar'] == "PENJUALAN") {
                                        $keterangan = $kolom["cara_keluar"]." ke ".$kolom['cust_an_nama'];
                                    } else {
                                        $keterangan = $kolom["cara_keluar"];
                                    }
                                ?>
                                <tr>
                                    <td class="td-kecil text-center"><?php echo $i;?></td>
                                    <td class="td-kecil text-center"><?php echo $nomor_produksi;?></td>
                                    <td class="td-kecil text-left"><?php echo $kolom['produk_nama']; ?></td>
                                    <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($kolom['tanggal']); ?></td>
                                    <td class="td-kecil text-center"><?php echo $kolom['reff_no']; ?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom['qty_kecil']; ?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["t"]." ".$kolom["t_satuan"];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["l"]." ".$kolom["l_satuan"];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["p"]." ".$kolom["p_satuan"];?></td>
                                    <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($kolom["kubikasi"],4);?></td>
                                    <td class="td-kecil text-left"><?php echo $keterangan;?></td>
                                </tr>
                                <?php
                                    $i++;
                                    $total_pcs = $total_pcs + $kolom["qty_kecil"];
                                    $total_kubikasi = $total_kubikasi + $kolom["kubikasi"];
                                }
                                ?>
                                <tr>
                                    <td class="text-right" colspan="5"><b>Total</b></td>
                                    <td class="text-right"><b><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_pcs);?></b></td>
                                    <td colspan="3">&nbsp;</td>
                                    <td class="text-right"><b><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_kubikasi,4);?></b></td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
						</table>
						</div>
                    </div>
                </div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
