<div class="modal fade zzz" id="modal-review" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-left"><b><?php echo $title;?></b></div>
            <div class="modal-body">
                <style>
                    td { text-align: left;}
                </style>
                <table style="margin-left: -5px;">

                    <tr>
                        <td>PIC Terima</td>
                        <td> : </td>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <?php
                            if ($peruntukan == "Industri") {
                                $pic_terima = $modPabrik->pic_terima;
                            } else {
                                $pic_terima = $modDetail->created_by;
                            }
                            $pegawai_id = Yii::$app->db->createCommand("select pegawai_id from m_user where user_id = ".$pic_terima."")->queryScalar();
                            $pegawai_nama = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$pegawai_id."")->queryScalar();
                            echo $pegawai_nama;
                            ?>
                        </td>
                    </tr>

                    <?php
                    $created_at = explode(" ", $modDetail->created_at);
                    $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($created_at[0]);
                    $jam = $created_at[1];
                    ?>
                    <tr>
                        <td style="width: 140px;">Tanggal</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $tanggal;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Jam</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $jam;?> WIB</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Jenis Kayu</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modKayu->group_kayu." ".$modKayu->kayu_nama;?></td>
                    </tr>
                    </tr>
                    <tr>
                        <td style="width: 140px;">No. QRcode</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->no_barcode;?></td>
                    </tr>
                    <tr>
                        <td>No. Batang</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->no_btg;?></td>
                    </tr>
                    <tr>
                        <td>No. Lap.</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->no_lap;?></td>
                    </tr>
                    <tr>
                        <td>No. Grade.</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->no_grade;?></td>
                    </tr>
                    <tr>
                        <td>Panjang</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->panjang;?> m</td>
                    </tr>
                    <tr>
                        <td>Kode Potong</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->kode_potong;?></td>
                    </tr>
                    <tr>
                        <td>Diameter Ujung 1</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->diameter_ujung1;?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Ujung 2</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->diameter_ujung2;?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Pangkal 1</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->diameter_pangkal1;?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Pangkal 2</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->diameter_pangkal2;?> cm</td>
                    </tr>
                    <tr>
                        <td>Cacat Panjang</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->cacat_panjang;?> cm</td>
                    </tr>
                    <tr>
                        <td>Cacat Gubal</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->cacat_gb;?> cm</td>
                    </tr>
                    <tr>
                        <td>Cacat Growong</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->cacat_gr;?> cm</td>
                    </tr>
                    <tr>
                        <td>Volume</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modDetail->volume;?> m<sup>3</sup></td>
                    </tr>
                    <tr>
                        <td colspan="3"><hr></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-center"><button type="button" id="btn-close" class="btn btn-danger btn-outline ciptana-spin-btn ladda-button" onclick="tutup();" data-style="zoom-in" title="Batalkan Detail Penerimaan"><span class="ladda-label">Tutup</span><span class="ladda-spinner"></span></button></td>
                    </tr>
                </table>
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
function tutup () {
    $('#modal-review').modal('toggle');
}
</script>


