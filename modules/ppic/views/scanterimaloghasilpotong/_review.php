<?php 
$modH = Yii::$app->db->createCommand("
            SELECT * FROM t_pemotongan_log_detail_potong
            JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama
            JOIN t_log_keluar ON t_log_keluar.no_barcode = h_persediaan_log.no_barcode  AND t_log_keluar.reff_no = h_persediaan_log.reff_no
            WHERE pemotongan_log_detail_potong_id = {$pemotongan_log_detail_potong_id}")->queryOne();
$diameter_rata = number_format(($modLog->diameter_ujung1_baru + $modLog->diameter_ujung2_baru 
                    + $modLog->diameter_pangkal1_baru + $modLog->diameter_pangkal2_baru) / 4);
?>
<div class="modal fade zzz" id="modal-review" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="margin: 120px auto !important;">
        <div class="modal-content">
            <div class="modal-header text-left"><b>INPUT PENERIMAAN LOG DARI HASIL PEMOTONGAN LOG</b></div>
            <div class="modal-body text-center">
                <style>
                    td {
                        text-align: left;
                    }
                </style>
                <table style="margin-left: 25px;">
                    <tr>
                        <td>Jenis Kayu</td>
                        <td> : </td>
                        <td style="padding-left: 10px;">
                            <select id="kayu_id" name="kayu_id" class="form-control" disabled>
                                <?php
                                $sql = "select kayu_id, group_kayu, kayu_nama from m_kayu where active = 'true' order by group_kayu, kayu_nama asc ";
                                $query = Yii::$app->db->createCommand($sql)->queryAll();
                                foreach ($query as $kolom) {
                                    $kayu_id = $kolom['kayu_id'];
                                    $group_kayu = $kolom['group_kayu'];
                                    $kayu_nama = $kolom['kayu_nama'];
                                    $kayu_id == $modH['kayu_id'] ? $selected = "selected" : $selected = "";
                                ?>
                                    <option value="<?php echo $kayu_id; ?>" <?php echo $selected; ?>><?php echo $group_kayu . " - " . $kayu_nama; ?></option>
                                <?php
                                }
                                ?>
                                <option></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">No. QRcode</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->no_barcode_baru; ?></td>
                    </tr>
                    <tr>
                        <td>No. Batang</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modH['no_btg']; ?></td>
                    </tr>
                    <tr>
                        <td>No. Lap.</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->no_lap_baru; ?></td>
                    </tr>
                    <tr>
                        <td>No. Grade.</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modH['no_grade']; ?></td>
                    </tr>
                    <tr>
                        <td>Panjang</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->panjang_baru; ?> m</td>
                    </tr>
                    <tr>
                        <td>Kode Potong</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->kode_pemotongan; ?></td>
                    </tr>
                    <tr>
                        <td>Diameter Ujung 1</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->diameter_ujung1_baru; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Ujung 2</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->diameter_ujung2_baru; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Pangkal 1</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->diameter_pangkal1_baru; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Pangkal 2</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->diameter_pangkal2_baru; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Rata Rata</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $diameter_rata; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Cacat Panjang</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->cacat_pjg_baru; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Cacat Gubal</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->cacat_gb_baru; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Cacat Growong</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->cacat_gr_baru; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Volume</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modLog->volume_baru; ?> m<sup>3</sup></td>
                    </tr>
                    <!-- TAMBAH FSC -->
                    <tr>
                        <td>Status FSC</td>
                        <td> : </td>
                        <td style="padding-left: 10px;">
                            <?php 
                            if($modH['fsc'] == true){
                                $fsc = "<span style='color:red; font-size: 15px;'><b> FSC 100% </b></span>";
                            } else {
                                $fsc = 'Non FSC';
                            }
                            echo $fsc; 
                            ?>
                        </sup></td>
                    </tr>
                    <!-- eo FSC -->
                    <tr>
                        <td style="vertical-align: top;">Catatan Penerimaan</td>
                        <td style="vertical-align: top;"> : </td>
                        <td style="padding-left: 10px;"><textarea id="catatan" name="catatan" class="form-control" style="width: 100%;"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><button type="button" id="btn-save-details" class="btn btn-primary btn-outline ciptana-spin-btn ladda-button" onclick="saveItem();" data-style="zoom-in" title="Simpan Detail Pengembalian"><span class="ladda-label">Tambah</span><span class="ladda-spinner"></span></button></td>
                        <td>&nbsp;</td>
                        <td class="text-center"><button type="button" id="btn-close" class="btn btn-danger btn-outline ciptana-spin-btn ladda-button" onclick="cancelItem();" data-style="zoom-in" title="Batalkan Detail Pengembalian"><span class="ladda-label">Batal</span><span class="ladda-spinner"></span></button></td>
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
<?php $this->registerCssFile($this->theme->baseUrl . "/pages/css/profile.min.css"); ?>

<script>
    function saveItem(){
        if(validatingDetail()){
            addLog();
        }
    }

    function addLog() {
        $('#modal-review').modal('toggle');
        var catatan = $('#catatan').val();
        var pemotongan_log_detail_potong_id = '<?= $pemotongan_log_detail_potong_id; ?>';
        $.ajax({
            url: '<?= \yii\helpers\Url::toRoute(['/ppic/scanterimaloghasilpotong/index']); ?>',
            type: 'POST',
            data: { catatan:catatan, pemotongan_log_detail_potong_id: pemotongan_log_detail_potong_id },
            success: function(data) {
                window.location.reload();
            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function validatingDetail(){
        has_error = 0;
        var catatan = $('#catatan').val();
        
        if(catatan == '' || !catatan){
            $('#catatan').addClass('error-tb-detail');
		    has_error = has_error + 1;
        } else {
            $('#catatan').removeClass('error-tb-detail');
        }

        if(has_error === 0){
            return true;
        }
        return false;
    }

    function cancelItem() {
        $('#modal-review').modal('toggle');
    }

</script>