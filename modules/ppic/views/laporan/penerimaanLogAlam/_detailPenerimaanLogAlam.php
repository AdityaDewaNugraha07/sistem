<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-daftarPenerimaanLogAlam" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Penerimaan Log Alam'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                            <thead>
                                <tr>
                                    <?php
                                    if ($model->area_pembelian == "Luar Jawa") {
                                    ?>
                                    <th rowspan="2" style="font-size: 1.1rem;"><?= Yii::t('app', 'Pengajuan Pembelian'); ?></th>
                                    <?php
                                    }
                                    ?>
                                    <th rowspan="2" style="font-size: 1.1rem; 50px;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                    <th colspan="3"><?= Yii::t('app', 'Nomor'); ?></th>
                                    <th colspan="2"><?= Yii::t('app', 'Panjang<sup>(m3)</sup></sup>'); ?></th>
                                    <th colspan="5"><?= Yii::t('app', 'Diameter'); ?></th>
                                    <th colspan="3"><?= Yii::t('app', 'Unsur Cacat'); ?></th>
                                    <th rowspan="2" style="width: 50px;"><?= Yii::t('app', 'Vol'); ?></th>
                                </tr>
                                <tr>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Lap'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Grades'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Batang'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Kode Potong'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Panjang'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Ujung1'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Ujung2'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Pangkal1'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Pangkal2'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Rata<sup>2</sup>'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Panjang'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'GB'); ?></th>
                                    <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'GR'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            </tfoot>
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

<?php $this->registerJs("
lihatLampiran($terima_logalam_id);
", yii\web\View::POS_READY); ?>

<script>
    function lihatLampiran(terima_logalam_id, lampiran){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/terimalogalam/lihatLampiran']); ?>',
            type   : 'POST',
            data   : {terima_logalam_id, lampiran},
            success: function (data){
                $('#table-detail > tbody').html("");
                if(data.html){
                    $('#table-detail > tbody').html(data.html);
                    $('#lampiran').html('<font style="font-weight: bold; color: #fff; font-size: 20px;">L '+lampiran+'</font>');
                }
                reordertable('#table-detail');
                $("#form-transaksi :input").prop('disabled', true);
                $("#table-detail :input").prop('disabled', true);
                $("#btn-save, #btn-reset, #btn-add-details, #btn-save-details, #place-editbtn, #place-cancelbtn").hide();
                $("#place-cancelbtn").css({'display': 'none'});
                $(".place-printbtn").css({'display': 'none'});
                $("#span_button_kode_spm_log").css({'display': 'none'});
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
</script>