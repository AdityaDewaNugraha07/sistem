<div class="modal fade _lihatDetail" id="modal-madul" tabindex="-1" role="basic" style="margin-top: 50px;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Scanned Log Alam'); ?></h4>
            </div>

            <div class="modal-body">
                <?php
                // PENERIMAAN LOG ALAM

                                        use app\components\DeltaFormatter;

                ?>
                <table>
                    <tr>
                        <th colspan="3">Penerimaan Log Alam</th>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Kode</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam->kode;?></td>
                    </tr>
                    <?php
                    $created_at = $terima_logalam->created_at;
                    $tanggal_jam = explode(" ", $created_at);
                    $tanggal = $tanggal_jam[0];
                    $jam = $tanggal_jam[1];
                    ?>
                    <tr>
                        <td style="width: 140px;">Tanggal</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo DeltaFormatter::formatDateTimeForUser2($tanggal);?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Jam</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $jam;?> WIB</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">No. Truk</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam->no_truk;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">No. Dok</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam->no_dokumen;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Peruntukan</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam->peruntukan;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Lokasi</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam->lokasi_tujuan;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">PIC Ukur</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo Yii::$app->db->createCommand("select m_pegawai.pegawai_nama from m_user join m_pegawai on m_pegawai.pegawai_id = m_user.pegawai_id where m_user.pegawai_id = ".$terima_logalam->pic_ukur."")->queryScalar();?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Keterangan</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam->keterangan;?></td>
                    </tr>
                </table>
                <hr>
                <?php
                // PENERIMAAN LOG ALAM DETAIL
                $sql_tanggal = "select tanggal from t_terima_logalam where terima_logalam_id = ".$terima_logalam_detail->terima_logalam_id."";
                $tanggal = Yii::$app->db->createCommand($sql_tanggal)->queryScalar();
                $sql_kayu = "select concat(group_kayu,' ',kayu_nama) from m_kayu where kayu_id = ".$terima_logalam_detail->kayu_id."";
                $kayu = Yii::$app->db->createCommand($sql_kayu)->queryScalar();
                ?>
                <table>
                    <tr>
                        <th colspan="3">Penerimaan Log Alam Detail</th>
                    </tr>
                    <tr>
                        <td style="width: 140px;">No. QRcode</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->no_barcode;?></td>
                    </tr>
                    <?php
                    $sql_created_at = "select created_at from t_terima_logalam where terima_logalam_id = ".$terima_logalam_detail->terima_logalam_id."";
                    $created_at = Yii::$app->db->createCommand($sql_created_at)->queryScalar();
                    $tanggal_jam = explode(" ", $created_at);
                    $jam = $tanggal_jam[1];
                    ?>
                    <tr>
                        <td style="width: 140px;">Tanggal</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($tanggal);?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Jam</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $jam;?> WIB</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Kayu</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $kayu;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Kode Potong</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->kode_potong;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">No. Lap</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->no_lap;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">No. Grade</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->no_grade;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">No. Batang</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->no_btg;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Panjang</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->panjang;?> m</td>
                    </tr>

                    <tr>
                        <td style="width: 140px;">Ujung 1</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->diameter_ujung1;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Ujung 2</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->diameter_ujung2;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Pangkal 1</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->diameter_pangkal1;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Pangkal 2</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->diameter_pangkal2;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Cacat Panjang</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->cacat_panjang;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Gubal</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->cacat_gb;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Growong</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->cacat_gr;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Volume</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->volume;?> m<sup>3</sup></td>
                    </tr>
                    <!-- TAMBAH FSC -->
                    <tr>
                        <td style="width: 140px;">Status FSC</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_detail->fsc?'FSC 100%':'Non FSC';?></td>
                    </tr>
                    <!-- eo FSC -->
                </table>
                <hr>
                <?php
                // PENERIMAAN LOG ALAM PABRIK
                $sql_kayu = "select concat(group_kayu,' ',kayu_nama) from m_kayu where kayu_id = ".$terima_logalam_pabrik->kayu_id."";
                $kayu = Yii::$app->db->createCommand($sql_kayu)->queryScalar();
                $sql_pegawai = "select b.pegawai_nama from m_user a join m_pegawai b on b.pegawai_id = a.pegawai_id where a.user_id = ".$terima_logalam_pabrik->pic_terima."";
                $pegawai = Yii::$app->db->createCommand($sql_pegawai)->queryScalar();
                $created_at = $terima_logalam_pabrik->created_at;
                $tanggal_jam = explode(" ", $created_at);
                $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($tanggal_jam[0]);
                $jam = $tanggal_jam[1];
                ?>
                <table>
                    <tr>
                        <th colspan="3">Penerimaan Log Alam Pabrik</th>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Kode</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $terima_logalam_pabrik->kode;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Tanggal</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $tanggal;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Jam</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $jam;?> WIB</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Kayu</th>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $kayu;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">PIC Terima</th>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $pegawai;?></td>
                    </tr>
                </table>
            </div>
            
            <div class="modal-footer">
                <button type="button" id="btn-close" class="btn btn-danger btn-outline ciptana-spin-btn ladda-button text-center" style="float: right;" onclick="tutup();" data-style="zoom-in" title="Close">
                    <span class="ladda-label">Close</span>
                    <span class="ladda-spinner"></span>
                </button>
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
<script>
function tutup() {
    $('._lihatDetail').modal('toggle');
}
</script>