<div class="modal fade" id="modal-madul" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Penerimaan Jasa KD Detail');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" style="display: flex; justify-content: space-between; align-items: center;">
                        <h5><?= Yii::t('app', 'Detail Order'); ?></h5>
                        <a class="btn btn-icon-only btn-default tooltips pull-right" onclick="printout(<?= $op_ko_id; ?>)" data-original-title="Export to Excel"><i class="fa fa-table"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.3rem;">No.</th>
                                        <th rowspan="2" style="line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Produk'); ?></th>
                                        <th colspan="3" style="line-height: 0.9;  padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Qty'); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 50px"><?= Yii::t('app', 'Palet'); ?></th>
                                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 130px"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
                                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 70px"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>

                                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 50px; display: none;"><?= Yii::t('app', '-'); ?></th>
                                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 130px; display: none;"><?= Yii::t('app', 'Satuan<br>Beli'); ?></th>
                                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 70px; display: none;"><?= Yii::t('app', 'Satuan<br>Angkut'); ?></th>
                                        
                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 50px; display: none;"><?= Yii::t('app', 'Batang'); ?></th>
                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 130px; display: none;"><?= Yii::t('app', '-'); ?></th>
                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 70px; display: none;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php //isi Detail Order ?>
                                </tbody>
                                <?php
                                $sql = "select m_produk_jasa.nama, m_produk_jasa.kode ".
                                            "   , t_op_ko_detail.qty_besar, t_op_ko_detail.qty_kecil, t_op_ko_detail.kubikasi, t_op_ko_detail.harga_jual ".
                                            "   from t_op_ko_detail ".
                                            "   left join m_produk_jasa on m_produk_jasa.produk_jasa_id = t_op_ko_detail.produk_id ".
                                            "   where op_ko_id = ".$op_ko_id." ".
                                            " ";
                                $query = Yii::$app->db->createCommand($sql)->queryAll();
                                $total_harga = 0;
                                foreach ($query as $kolom) {
                                ?>
                                    <tr>
                                        <td>1</td>
                                        <td><?php echo $kolom['kode']." - ".$kolom['nama'];?></td>
                                        <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($kolom['qty_besar']);?></td>
                                        <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($kolom['qty_kecil']);?></td>
                                        <td class="text-right"><?php echo $kolom['kubikasi'];?></td>
                                    </tr>
                                <?php
                                $total_harga += $kolom['harga_jual'] * $kolom['kubikasi'];
                                }
                                ?>
                                <tfoot>
                                    <tr>
                                        <td colspan="5"></td>
                                        <?php /*<td style="vertical-align: middle; text-align: right;">
                                            Total Harga &nbsp;
                                        </td>
                                        <td style="vertical-align: middle; text-align: right;">
                                            <?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_harga);?>
                                        </td>*/?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row" id="place-table-terima-jasa">
                    <div class="col-md-12">
                        <h5 id="place-terima-jasa-judul"></h5>
                    </div>
                    <div class="col-md-12">
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-terima-jasa">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">No.</th>
                                        <th rowspan="2" style="width: 100px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Tanggal<br>Terima / Hasil'); ?></th>
                                        <th rowspan="2" style="width: 100px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Nopol'); ?></th>
                                        <th rowspan="2" style="width: 70px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'No. Palet'); ?></th>
                                        <th rowspan="2" style="width: 120px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Produk'); ?></th>
                                        <th colspan="3" style="width: 180px; line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Dimensi'); ?></th>
                                        <th colspan="2" style="line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Dokumen'); ?></th>
										<th colspan="2" style="line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Aktual'); ?></th>
                                        <th rowspan="2" style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Ket'); ?></th>
                                        <th colspan="3" style="width: 180px; line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Diterima'); ?></th>

                                    </tr>
                                    <tr>
                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "T" ?></th>
                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "L" ?></th>
                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "P" ?></th>
                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Qty'); ?></th>
                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Vol'); ?></th>
                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Qty'); ?></th>
                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Vol'); ?></th>
                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "Kode" ?></th>
                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "Tanggal" ?></th>
                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= "Vol" ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                // $list_details = "";
                                // $sql_x = "select t_spm_ko_detail.keterangan ".
                                //             " from t_spm_ko  ".
                                //             " join t_spm_ko_detail on t_spm_ko_detail.spm_ko_id = t_spm_ko.spm_ko_id ".
                                //             " where t_spm_ko.op_ko_id = ".$op_ko_id." ".
                                //             " ";
                                // $query_x = Yii::$app->db->createCommand($sql_x)->queryAll();
                                // foreach ($query_x as $kolom_x) {
                                //     $list_details .= $kolom_x['keterangan'];
                                // }
                                
                                $sql_y = "select t_terima_jasa.tanggal, t_terima_jasa.nopol, t_terima_jasa.nomor_palet ".
                                            "   , t_terima_jasa.t, t_terima_jasa.l, t_terima_jasa.p".
                                            "   , t_terima_jasa.t_satuan, t_terima_jasa.l_satuan, t_terima_jasa.p_satuan".
                                            "   , t_terima_jasa.qty_kecil, t_terima_jasa.kubikasi, t_terima_jasa.keterangan   ".
                                            "   , t_terima_jasa.qty_kecil_actual, t_terima_jasa.kubikasi_actual, m_produk_jasa.nama".
                                            "   from t_terima_jasa INNER JOIN m_produk_jasa ON t_terima_jasa.produk_jasa_id = m_produk_jasa.produk_jasa_id". 
                                            "   where t_terima_jasa.op_ko_id = ".$op_ko_id."". 
                                            "   ";
                                $query_y = Yii::$app->db->createCommand($sql_y)->queryAll();
                                $i                      = 1;
                                $total_qty_kecil        = 0;
                                $total_kubikasi         = 0;
                                $total_qty_kecil_actual = 0;
                                $total_kubikasi_actual  = 0;
                                foreach ($query_y as $kolom_y) {
                                    // $sql_spm_ko = "select spm_ko_id from t_spm_ko where op_ko_id = ".$op_ko_id."";
                                    // $spm_ko_id = Yii::$app->db->createCommand($sql_spm_ko)->queryScalar();
                                    
                                    // $sql_spm_ko_detail = "select produk_id from t_spm_ko_detail where spm_ko_id = ".$spm_ko_id." ";
                                    // $produk_id = Yii::$app->db->createCommand($sql_spm_ko_detail)->queryScalar();

                                    // $sql_m_produk_jasa = "select nama from m_produk_jasa where produk_jasa_id = ".$produk_id." ";
                                    // $produk_nama = Yii::$app->db->createCommand($sql_m_produk_jasa)->queryScalar();
                                    // $produk_nama = Yii::$app->db->createCommand("SELECT ")
                                    
                                    $query_spm = "  SELECT * FROM t_spm_ko_detail
                                                    JOIN t_spm_ko on t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id
                                                    WHERE op_ko_id = $op_ko_id AND status = 'REALISASI'
                                                    AND '{$kolom_y['nomor_palet']}' = ANY(string_to_array(REPLACE(keterangan, '''', ''), ','))";
                                    $mod_spm = Yii::$app->db->createCommand($query_spm)->queryOne();
                                    $kode_spm = '-'; $tgl_spm = '-';
                                    if($mod_spm){
                                        $spm = \app\models\TSpmKo::findOne($mod_spm['spm_ko_id']);
                                        $kode_spm = $spm->kode;
                                        $tgl_spm = \app\components\DeltaFormatter::formatDateTimeForUser2($spm->tanggal);
                                    }
                                ?>
                                    <tr>
                                        <td class='text-center'><?php echo $i;?></td>
                                        <td class='text-center'><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($kolom_y['tanggal']);?></td>
                                        <td class='text-center'><?php echo $kolom_y['nopol'];?></td>
                                        <td class='text-center'><?php echo $kolom_y['nomor_palet'];?></td>
                                        <td class='text-center'><?php echo $kolom_y['nama'];?></td>
                                        <td class='text-right'><?php echo $kolom_y['t'].' '.$kolom_y['t_satuan'];?></td>
                                        <td class='text-right'><?php echo $kolom_y['l'].' '.$kolom_y['l_satuan'];?></td>
                                        <td class='text-right'><?php echo $kolom_y['p'].' '.$kolom_y['p_satuan'];?></td>
                                        <td class='text-right'><?php echo $kolom_y['qty_kecil'];?></td>
                                        <td class='text-right'><?php echo $kolom_y['kubikasi'];?></td>
                                        <td class='text-right'><?php echo $kolom_y['qty_kecil_actual'];?></td>
                                        <td class='text-right'><?php echo $kolom_y['kubikasi_actual'];?></td>
                                        <td class='text-right'><?php echo $kolom_y['keterangan'];?></td>
                                        <td class='text-center'><?= $kode_spm; ?></td>
                                        <td class='text-center'><?= $tgl_spm; ?></td>
                                        <td class='text-center'><?= $kode_spm == '-' ? '-' : $kolom_y['kubikasi']; ?></td>
                                    </tr>
                                <?php
                                    $i++;
                                    $total_qty_kecil        += $kolom_y['qty_kecil'];
                                    $total_kubikasi         += $kolom_y['kubikasi'];
                                    $total_qty_kecil_actual += $kolom_y['qty_kecil_actual'];
                                    $total_kubikasi_actual  += $kolom_y['kubikasi_actual'];
                                }

                                ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <?php /* <a class="btn btn-xs blue-hoki" id="btn-add-item-terima" onclick="addItemTerima();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Detail'); ?></a> */?>
                                        </td>
                                        <td style="vertical-align: middle; text-align: right;">
                                            Total &nbsp; 
                                        </td>
                                        <td style="vertical-align: middle; text-align: right;">
                                            <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_qty_kecil) ?>
                                        </td>
                                        <td style="vertical-align: middle; text-align: right;">
                                            <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubikasi) ?>
                                        </td>
                                        <td style="vertical-align: middle; text-align: right;">
                                            <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_qty_kecil_actual) ?>
                                        </td>
                                        <td style="vertical-align: middle; text-align: right;">
                                            <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubikasi_actual) ?>
                                        </td>
                                    </tr>
                                </tfoot>
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
<script>
    // function printout(id){
    //     window.open("<?= yii\helpers\Url::toRoute('/marketing/laporan/penerimaanJasakdPrint') ?>?id="+id,'location=_new, width=1200px, scrollbars=yes');
    // }
</script>