<div class="modal fade _lihatDetail" id="modal-madul" tabindex="-1" role="basic" style="margin-top: 50px;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Scanned Log Kembali'); ?></h4>
            </div>
            <div class="modal-body">
                <!-- DATA PENGEMBALIAN -->
                <table>
                    <tr>
                        <th colspan="3">Penerimaan Log Kembali</th>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Kode Pengembalian</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $model->kode;?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Tanggal Pengembalian</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal);?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Alasan Pengembalian</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modDetail->alasan_pengembalian;?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Penerima</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td>
                            <?php 
                            $modPegawai = app\models\MPegawai::findOne($modDetail->penerima);
                            echo $modPegawai->pegawai_nama;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <?php 
                        $tanggal_jam = explode(" ", $modDetail->tanggal_penerimaan);
                        $tgl_penerimaan = $tanggal_jam[0]; 
                        ?>
                        <td style="width: 170px;">Tanggal Penerimaan</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo app\components\DeltaFormatter::formatDateTimeForUser2($tgl_penerimaan);?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Catatan Penerimaan</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo ($modDetail->catatan_penerimaan)?$modDetail->catatan_penerimaan:'-';?></td>
                    </tr>
                </table>
                <hr>
                <!-- DATA LOG KEMBALI -->
                <?php
                    $modKayu = app\models\MKayu::findOne($modH->kayu_id);
                    $kayu = $modKayu->group_kayu . " " .$modKayu->kayu_nama;
                    $tanggal = $modH->tgl_transaksi;
                    $sql_created_at = "select created_at from h_persediaan_log where persediaan_log_id = ".$modH->persediaan_log_id."";
                    $created_at = Yii::$app->db->createCommand($sql_created_at)->queryScalar();
                    $tanggal_jam = explode(" ", $created_at);
                    $jam = $tanggal_jam[1];
                ?>
                <table>
                    <tr>
                        <th colspan="3">Data Log dari Pengembalian Log</th>
                    </tr>
                    <tr>
                        <td style="width: 170px;">No. QRcode</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->no_barcode;?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Tanggal</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($tanggal);?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Jam</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $jam;?> WIB</td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Kayu</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $kayu;?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Kode Potong</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->pot?$modH->pot:'-';?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">No. Lap</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->no_lap;?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">No. Grade</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->no_grade;?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">No. Batang</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->no_btg;?></td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Panjang</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->fisik_panjang;?> m</td>
                    </tr>

                    <tr>
                        <td style="width: 170px;">Ujung 1</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->diameter_ujung1;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Ujung 2</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->diameter_ujung2;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Pangkal 1</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->diameter_pangkal1;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Pangkal 2</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->diameter_pangkal2;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Cacat Panjang</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->cacat_panjang;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Gubal</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->cacat_gb;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Growong</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->cacat_gr;?> cm</td>
                    </tr>
                    <tr>
                        <td style="width: 170px;">Volume</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->fisik_volume;?> m<sup>3</sup></td>
                    </tr>
                    <!-- TAMBAH FSC -->
                    <tr>
                        <td style="width: 170px;">Status FSC</td>
                        <td style="width: 10px; text-align: left;">:</td>
                        <td><?php echo $modH->fsc?'FSC 100%':'Non FSC';?></td>
                    </tr>
                    <!-- eo FSC -->
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