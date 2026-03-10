<?php app\assets\DatatableAsset::register($this); ?>
<?php
$modelTPengajuanPembelianlog = \app\models\TPengajuanPembelianlog::find()->select('kode, nomor_kontrak')->where(['pengajuan_pembelianlog_id' => $id])->one();
?>
<div class="modal fade" id="modal-madul" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body">
            <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title" style="padding-bottom: 10px;"><b><?= Yii::t('app', 'Detail Keputusan Pembelian Log Alam'); ?> :: <?php echo  $modelTPengajuanPembelianlog->kode;?> :: <?php echo  $modelTPengajuanPembelianlog->nomor_kontrak;?></b></h5>
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
                            <thead>
                                <tr>
                                    <th class="td-kecil" style="width: 50px;">No.</th>
                                    <th class="td-kecil" style="width: 100px;">Tipe</th>
                                    <th class="td-kecil" style="width: 150px;">Kayu</th>
                                    <th class="td-kecil" style="width: 100px;">Diameter</th>
                                    <th class="td-kecil" style="width: 100px;">Qty Pcs</th>
                                    <th class="td-kecil" style="width: 100px;">Qty m<sup>3</sup></th>
                                    <th class="td-kecil" style="width: 150px;">Harga</th>
                                    <th class="td-kecil">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            $sql_detail = "select * from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$id."".
                                            "   and qty_batang > 0 ".
                                            "   and qty_m3 > 0 ".
                                            "   and harga > 0 ".
                                            "   ";
                            $query_detail = Yii::$app->db->createCommand($sql_detail)->queryAll();
                            $total_batang = 0;
                            $total_qty_m3 = 0;
                            foreach ($query_detail as $kolom) {
                                $kayu_id = $kolom['kayu_id'];
                                $modKayu = \app\models\MKayu::findOne($kayu_id);
                                $kayu_nama = $modKayu->kayu_nama;
                            ?>
                                <tr>
                                    <td class="td-kecil text-center"><?php echo $i;?></td>
                                    <td class="td-kecil text-center"><?php echo $kolom['tipe'];?></td>
                                    <td class="td-kecil"><?php echo $kayu_nama;?></td>
                                    <td class="td-kecil text-center"><?php echo $kolom['diameter_cm'];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom['qty_batang'];?></td>
                                    <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($kolom['qty_m3']);?></td>
                                    <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($kolom['harga']);?></td>
                                    <td class="td-kecil"><?php echo $kolom['keterangan'];?></td>
                                </tr>
                            <?php
                                    $total_batang += $kolom['qty_batang'];
                                    $total_qty_m3 += $kolom['qty_m3'];
                                $i++;
                            }
                            ?>
                                <tr>
                                    <td class="td-kecil text-center" colspan="4"><b>TOTAL</b></td>
                                    <td class="td-kecil text-right"><b><?php echo \app\components\DeltaFormatter::formatNumberForUser($total_batang);?></b></td>
                                    <td class="td-kecil text-right"><b><?php echo \app\components\DeltaFormatter::formatNumberForUser($total_qty_m3);?></b></td>
                                    <td colspan="4"></td>
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
