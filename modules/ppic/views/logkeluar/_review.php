<div class="modal fade zzz" id="modal-review" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-left"><b>INPUT DETAIL LOG KELUAR</b></div>
            <div class="modal-body text-center">
                <style>
                    td { text-align: left;}
                </style>
                <table style="margin-left: -5px;">
                    <tr>
                        <td style="width: 140px;">Nama Kayu</td>
                        <td> : </td>
                        <td style="padding-left: 10px;">
                        <?php
                        $kayu_id = $modPabrik->kayu_id;
                        $sql_kayu_nama = "select kayu_id, group_kayu, kayu_nama from m_kayu where kayu_id = ".$kayu_id." and active = 'true' ";
                        $query_kayu_nama = Yii::$app->db->createCommand($sql_kayu_nama)->queryAll();
                        foreach ($query_kayu_nama as $kolom) {
                            $kayu_id = $kolom['kayu_id'];
                            $group_kayu = $kolom['group_kayu'];
                            $kayu_nama = $kolom['kayu_nama'];
                            echo $group_kayu." ".$kayu_nama;
                        }
                        ?>
                        </td>
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
                        <td>Status FSC</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"> <!-- TAMBAH FSC -->
                            <?php 
                            if($modTerimaLog->fsc){
                                $fsc = "<span style='color:red; font-size: 15px;'><b> FSC 100% </b></span>";
                            } else {
                                $fsc = 'Non FSC';
                            }
                            echo $fsc;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><hr></td>
                    </tr>
                    <tr>
                        <td class="text-center"><button type="button" id="btn-save-details" class="btn btn-primary btn-outline ciptana-spin-btn ladda-button" onclick="addItem();" data-style="zoom-in" title="Tambah Detail Log Keluar"><span class="ladda-label">Tambah</span><span class="ladda-spinner"></span></button></td>
                        <td>&nbsp;</td>
                        <td class="text-center"><button type="button" id="btn-close" class="btn btn-danger btn-outline ciptana-spin-btn ladda-button" onclick="cancelItem();" data-style="zoom-in" title="Batalkan Detail Log Keluar"><span class="ladda-label">Batal</span><span class="ladda-spinner"></span></button></td>
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
function addItem() {
    $('#modal-review').modal('toggle');
    var terima_logalam_detail_id = <?php echo $modDetail->terima_logalam_detail_id;?>;
    var last_barcode = $('#table-detail-logkeluar tr:last').find("input[name*='[no_barcode]']").val();
    var no_barcode = '<?php echo $modDetail->no_barcode;?>';
    var kayu_id = '<?php echo $modDetail->kayu_id;?>';
    if (last_barcode == no_barcode) {
        cisAlert('Data sudah ada');
    } else {
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/logkeluar/addItem']); ?>',
            type   : 'POST',
            data   : {terima_logalam_detail_id:terima_logalam_detail_id, no_barcode:no_barcode, kayu_id:kayu_id},
            success: function (data) {
                if (data['msg'] == "Log sudah pernah dikeluarkan") {
                    cisAlert(data['msg']);
                } else {
                    $(data.html).hide().appendTo('#table-detail-logkeluar tbody').fadeIn(200,function(){
                        reordertable('#table-detail-logkeluar');
                    });
                    var nomor_urut = $('#table-detail-logkeluar tr').length - 1;
                    $('#table-detail-logkeluar tr:last td:first').text(nomor_urut);
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}

function cancelItem () {
    $('#modal-review').modal('toggle');
}
</script>


