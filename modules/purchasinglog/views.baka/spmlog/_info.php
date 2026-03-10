<?php 
use app\models\MKayu;
app\assets\DatatableAsset::register($this); 
?>
<?php
$sql_suplier_nm = "select suplier_nm from m_suplier where suplier_id = ".$model->suplier_id."";
$suplier_nm = Yii::$app->db->createCommand($sql_suplier_nm)->queryScalar();

$model->asuransi == true ? $asuransi = "Ya" : $asuransi = "Tidak";
?>
<div class="modal fade" id="modal-detailKeputusanPembelianlog" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
            <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title" style="padding-bottom: 10px;"><b><?= Yii::t('app', 'Keputusan Pembelian Log Alam'); ?></b></h5>
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th class="td-kecil" style="width: 90px; border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Kode'); ?></th>
									<th class="td-kecil" style="width: 70px; border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Tanggal'); ?></th>
									<th class="td-kecil" style="width: 170px; border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Nomor<br>Kontrak'); ?></th>
									<th class="td-kecil" style="width: 100px; border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Volume<br>Kontrak m<sup>3</sup>'); ?></th>
									<th class="td-kecil" style="width: 100px; border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Suplier'); ?></th>
									<th class="td-kecil" style="width: 200px; border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Asal Kayu'); ?></th>
									<th class="td-kecil" style="width: 200px; border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Lokasi Muat'); ?></th>
									<th class="td-kecil" style="width: 70px; border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Asuransi'); ?></th>
								</tr>
							</thead>
                            <tbody>
                                <tr>
                                    <td class="td-kecil text-center"><?php echo $model->kode;?></td>
                                    <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);?></td>
                                    <td class="td-kecil text-center"><?php echo $model->nomor_kontrak;?></td>
                                    <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($model->total_volume);?></td>
                                    <td class="td-kecil"><?php echo $suplier_nm;?></td>
                                    <td class="td-kecil"><?php echo $model->asal_kayu;?></td>
                                    <td class="td-kecil"><?php echo $model->lokasi_muat;?></td>
                                    <td class="td-kecil text-center"><?php echo $asuransi;?></td>
                                </tr>
                            </tbody>
						</table>
					</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title" style="padding-bottom: 10px;"><b><?= Yii::t('app', 'Detail Keputusan Pembelian Log Alam'); ?></b></h5>
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
                            <thead>
                                <tr>
                                    <th class="td-kecil" style="width: 50px;">No.</th>
                                    <th class="td-kecil" style="width: 130px;">Tipe</th>
                                    <th class="td-kecil" style="width: 185px;">Kayu</th>
                                    <th class="td-kecil" style="width: 110px;">Diameter</th>
                                    <th class="td-kecil" style="width: 110px;">Qty Pcs</th>
                                    <th class="td-kecil" style="width: 110px;">Qty m<sup>3</sup></th>
                                    <?php /* <th class="td-kecil" style="width: 150px;">Harga</th> */?>
                                    <th class="td-kecil">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            $sql_detail = "select * from t_pengajuan_pembelianlog_detail ". 
                                                "   where pengajuan_pembelianlog_id = ".$model->pengajuan_pembelianlog_id." ".
                                                "   and qty_m3 > 0 ".
                                                "   and harga > 0 ".
                                                "   ";
                            $query_detail = Yii::$app->db->createCommand($sql_detail)->queryAll();
                            $total_batang = 0;
                            $total_qty_m3 = 0;
                            foreach ($query_detail as $kolom) {
                                $kayu_id = $kolom['kayu_id'];
                                $modKayu = MKayu::findOne(['kayu_id' => $kayu_id]);
                                $kayu_nama = $modKayu->kayu_nama;
                            ?>
                                <tr>
                                    <td class="td-kecil text-center"><?php echo $i;?></td>
                                    <td class="td-kecil text-center"><?php echo $kolom['tipe'];?></td>
                                    <td class="td-kecil"><?php echo $kayu_nama;?></td>
                                    <td class="td-kecil text-center"><?php echo $kolom['diameter_cm'];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom['qty_batang'];?></td>
                                    <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($kolom['qty_m3']);?></td>
                                    <?php /* <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($kolom['harga']);?></td> */?>
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
