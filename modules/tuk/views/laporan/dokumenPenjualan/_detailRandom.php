<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-detailRandom" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Random : '.$nomor_dokumen); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersavex">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">No.</th>
                                    <th class="td-kecil" style="line-height: 1; width: 40px;"><?= Yii::t('app', 'Tanggal') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 130px;"><?= Yii::t('app', 'Nomor<br>Dokumen') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 120px;"><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 200px;"><?= Yii::t('app', 'Customer') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 200px;"><?= Yii::t('app', 'Produk') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 45px;"><?= Yii::t('app', 'T') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 45px;"><?= Yii::t('app', 'Sat(T)') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 45px;"><?= Yii::t('app', 'L') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 45px;"><?= Yii::t('app', 'Sat(L)') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 45px;"><?= Yii::t('app', 'P') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 45px;"><?= Yii::t('app', 'Sat(P)') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 45px;"><?= Yii::t('app', 'Nomor Produksi') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Total<br>Pcs') ?></th>
                                    <th class="td-kecil" style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Total<br>M<sup>3</sup>') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /*echo "<br>".$sql_list;
                                echo "<br>".$sql_kod_id;
                                echo "<br>".$sql_spm_ko_id;
                                echo "<br>".$sql_op_ko_id;
                                echo "<br>".$sql_op_ko_detail_id;*/
                                $i = 1;
                                $total_pcs = 0;
                                $total_kubikasi = 0;
                                foreach ($list as $kolom) {
                                    $produk_nama = Yii::$app->db->createCommand("select produk_nama from m_brg_produk where produk_id = ".$produk_id."")->queryScalar();
                                    $customer = str_replace("_"," ",$customer)
                                ?>
                                <tr>
                                    <td class="td-kecil text-center"><?php echo $i;?></td>
                                    <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($tanggal);?></td>
                                    <td class="td-kecil text-center"><?php echo $nomor_dokumen;?></td>
                                    <td class="td-kecil"><?php echo $jenis_produk;?></td>
                                    <td class="td-kecil"><?php echo $customer;?></td>
                                    <td class="td-kecil"><?php echo $produk_nama; ?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["t"];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["t_satuan"];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["l"];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["l_satuan"];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["p"];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["p_satuan"];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["nomor_produksi"];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["qty_kecil"];?></td>
                                    <td class="td-kecil text-right"><?php echo $kolom["kubikasi"];?></td>
                                </tr>
                                <?php
                                    $i++;
                                    $total_pcs = $total_pcs + $kolom["qty_kecil"];
                                    $total_kubikasi = $total_kubikasi + $kolom["kubikasi"];
                                }
                                ?>
                                <tr>
                                    <td class="text-right" colspan="13"><b>Total</b></td>
                                    <td class="text-right"><b><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_pcs);?></b></td>
                                    <td class="text-right"><b><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_kubikasi,4);?></b></td>
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
