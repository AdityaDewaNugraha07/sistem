<div class="modal fade zzz" id="modal-review" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="margin: 120px auto !important;">
        <div class="modal-content">
            <div class="modal-header text-left"><b>DATA LOG</b></div>
            <div class="modal-body text-center">
                <style>
                    td {
                        text-align: left;
                    }
                </style>
                <table style="margin-left: -5px;">
                    <tr>
                        <td style="width: 140px;">Jenis Kayu</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modKayu->kayu_nama; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">No. QRcode</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->no_barcode; ?></td>
                    </tr>
                    <tr>
                        <td>No. Batang</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->no_btg; ?></td>
                    </tr>
                    <tr>
                        <td>No. Lap.</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->no_lap; ?></td>
                    </tr>
                    <tr>
                        <td>No. Grade.</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->no_grade; ?></td>
                    </tr>
                    <tr>
                        <td>Panjang</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->fisik_panjang; ?> m</td>
                    </tr>
                    <tr>
                        <td>Kode Potong</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->pot; ?></td>
                    </tr>
                    <tr>
                        <td>Diameter Ujung 1</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->diameter_ujung1; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Ujung 2</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->diameter_ujung2; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Pangkal 1</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->diameter_pangkal1; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Pangkal 2</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->diameter_pangkal2; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Diameter Rata Rata</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->fisik_diameter; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Cacat Panjang</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->cacat_panjang; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Cacat Gubal</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->cacat_gb; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Cacat Growong</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->cacat_gr; ?> cm</td>
                    </tr>
                    <tr>
                        <td>Volume</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $model->fisik_volume; ?> m<sup>3</sup></td>
                    </tr>
                    <!-- TAMBAH FSC -->
                    <tr>
                        <td>Status FSC</td>
                        <td> : </td>
                        <td style="padding-left: 10px;">
                            <?php 
                            if($model->fsc == true){
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
                        <td colspan="3">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><button type="button" id="btn-save-details" class="btn btn-primary btn-outline ciptana-spin-btn ladda-button" onclick="saveItem();" data-style="zoom-in" title="Tambah Data Log"><span class="ladda-label">Tambah</span><span class="ladda-spinner"></span></button></td>
                        <td>&nbsp;</td>
                        <td class="text-center"><button type="button" id="btn-close" class="btn btn-danger btn-outline ciptana-spin-btn ladda-button" onclick="batalkan();" data-style="zoom-in" title="Batalkan Data Log"><span class="ladda-label">Batal</span><span class="ladda-spinner"></span></button></td>
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
        var no_barcode = '<?php echo $model->no_barcode; ?>';
        var kayu_id = '<?php echo $modKayu->kayu_id; ?>';

        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/perubahanjenis/saveItem']); ?>',
            type   : 'POST',
            data   : {no_barcode: no_barcode, kayu_id: kayu_id},
            success: function (data) {
                if(data.item){
                    var already = [];
                    $('#table-detail > tbody > tr').each(function(){
						var no_barcode = $(this).find('input[name*="[no_barcode]"]');
						if( no_barcode.val() ){
							already.push(no_barcode.val());
						}
					});

                    if( $.inArray(  data.no_barcode.toString(), already ) != -1 ){ // Jika ada yang sama
						cisAlert("Log ini sudah dipilih di list");
						return false;
					}else{
                        $(data.item).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                            $(this).find('select[name*="[kayu_id_new]"]').select2({
                                allowClear: !0,
                                placeholder: 'Ketik Nama Kayu',
                                width: null
                            });
                            $(this).find('.select2-selection').css('font-size','1.2rem');
                            $(this).find('.select2-selection').css('padding-left','5px');
                            $(this).find(".tooltips").tooltip({ delay: 50 });
                            reordertable('#table-detail');
                            $('#modal-addItem').modal('hide');
                            $('#modal-review').modal('hide');
                        });
                    }
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function batalkan() {
        $('#modal-review').modal('toggle');
    }
</script>