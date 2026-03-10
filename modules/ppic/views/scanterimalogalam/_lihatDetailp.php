<div class="modal fade yyy" id="modal-madul" tabindex="-1" role="basic" style="margin-top: 50px;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Scanned Log Alam'); ?></h4>
            </div>
            <div class="modal-body">
                <style>
                    h5 {font-weight: bold;}
                    .table th {font-weight: normal; font-size: 13px;}
                    .table td {font-size: 12px;}
                </style>
                <h5 class="text-bold">Penerimaan Log Alam Pabrik</h5>
                <table class="table table-striped table-bordered table-hover" id="table-aftersave">
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal Terima</th>
                        <th>Kayu</th>
                        <th>PIC Terima</th>
                    </tr>
                    <?php
                    $sql_kayu = "select concat(group_kayu,' ',kayu_nama) from m_kayu where kayu_id = ".$terima_logalam_pabrik->kayu_id."";
                    $kayu = Yii::$app->db->createCommand($sql_kayu)->queryScalar();
                    $sql_pegawai = "select b.pegawai_nama from m_user a join m_pegawai b on b.pegawai_id = a.pegawai_id where a.user_id = ".$terima_logalam_pabrik->pic_terima."";
                    $pegawai = Yii::$app->db->createCommand($sql_pegawai)->queryScalar();
                    ?>
                    <tr>
                        <td><?php echo $terima_logalam_pabrik->kode;?></td>
                        <td><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($terima_logalam_pabrik->tanggal);?></td>
                        <td><?php echo $kayu;?></td>
                        <td><?php echo $pegawai;?></td>
                    </tr>
                </table>

                <hr>

                <h5 class="text-bold">Penerimaan Alam Detail</h5>
                <table class="table table-striped table-bordered table-hover" id="table-aftersave">
                    <tr>
                        <th>No. QRcode</th>
                        <th>Tanggal</th>
                        <th>Kayu</th>
                        <th>No. Lap</th>
                        <th>No. Grade</th>
                        <th>No. Btg</th>
                        <th>Panjang</th>
                        <th>Kode<br>Potong</th>
                    </tr>
                    <?php
                    $sql_tanggal = "select tanggal from t_terima_logalam where terima_logalam_id = ".$terima_logalam_detail->terima_logalam_id."";
                    $tanggal = Yii::$app->db->createCommand($sql_tanggal)->queryScalar();
                    $sql_kayu = "select concat(group_kayu,' ',kayu_nama) from m_kayu where kayu_id = ".$terima_logalam_detail->kayu_id."";
                    $kayu = Yii::$app->db->createCommand($sql_kayu)->queryScalar();
                    ?>
                    <tr>
                        <td><?php echo $terima_logalam_detail->no_barcode;?></td>
                        <td><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($tanggal);?></td>
                        <td><?php echo $kayu;?></td>
                        <td><?php echo $terima_logalam_detail->no_lap;?></td>
                        <td><?php echo $terima_logalam_detail->no_grade;?></td>
                        <td><?php echo $terima_logalam_detail->no_btg;?></td>
                        <td><?php echo $terima_logalam_detail->panjang;?></td>
                        <td><?php echo $terima_logalam_detail->kode_potong;?></td>
                    </tr>
                </table>
                <table class="table table-striped table-bordered table-hover" id="table-aftersave">
                    <tr>
                        <th>Diameter<br>Ujung1</th>
                        <th>Diameter<br>Ujung2</th>
                        <th>Diameter<br>Pangkal1</th>
                        <th>Diameter<br>Pangkal2</th>
                        <th>Cacat<br>Panjang</th>
                        <th>Cacat<br>Gubal</th>
                        <th>Cacat<br>Growong</th>
                        <th>Volume</th>
                    </tr>
                    <tr>
                        <td><?php echo $terima_logalam_detail->diameter_ujung1;?></td>
                        <td><?php echo $terima_logalam_detail->diameter_ujung2;?></td>
                        <td><?php echo $terima_logalam_detail->diameter_pangkal1;?></td>
                        <td><?php echo $terima_logalam_detail->diameter_pangkal2;?></td>
                        <td><?php echo $terima_logalam_detail->cacat_panjang;?></td>
                        <td><?php echo $terima_logalam_detail->cacat_gb;?></td>
                        <td><?php echo $terima_logalam_detail->cacat_gr;?></td>
                        <td><?php echo $terima_logalam_detail->volume;?></td>
                    </tr>
                </table>

                <hr>

                <h5 class="text-bold">Penerimaan Log Alam</h5>
                <table class="table table-striped table-bordered table-hover" id="table-aftersave">
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>No. Truk</th>
                        <th>No. Dok</th>
                        <th>Peruntukan</th>
                        <th>Lokasi</th>
                        <th>PIC Ukur</th>
                        <th>Keterangan</th>
                    </tr>
                    <tr>
                        <td><?php echo $terima_logalam->kode;?></td>
                        <td><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($terima_logalam->tanggal);?></td>
                        <td><?php echo $terima_logalam->no_truk;?></td>
                        <td><?php echo $terima_logalam->no_dokumen;?></td>
                        <td><?php echo $terima_logalam->peruntukan;?></td>
                        <td><?php echo $terima_logalam->lokasi_tujuan;?></td>
                        <td><?php echo $terima_logalam->pic_ukur;?></td>
                        <td><?php echo $terima_logalam->keterangan;?></td>
                    </tr>
                </table>
            </div>
            
            <div class="modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->registerJs(" 
formconfig(); 
", yii\web\View::POS_READY); ?>

<?php $this->registerCssFile($this->theme->baseUrl."/pages/css/profile.min.css"); ?>


