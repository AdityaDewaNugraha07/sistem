<div class="modal fade zzz" id="modal-review" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-left"><b>REVIEW LOG UNTUK PLAN ALOKASI STOK LOG</b></div>
            <div class="modal-body text-center">
                <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id' => 'form-review',
                    'fieldConfig' => [
                        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                        'labelOptions'=>['class'=>'col-md-4 control-label'],
                    ],
                ]); ?>
                <style>
                    td { text-align: left;}
                </style>
                
                <table style="margin-left: -5px;"  id="table-review">
                    <tbody>
                        <tr>
                            <td style="width: 140px;">QR Barcode</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><b><?php echo $modH->no_barcode;?></b></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Jenis Kayu</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php $modKayu = \app\models\MKayu::findOne($modH->kayu_id); echo $modKayu->kayu_nama;?></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">No Batang</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php echo $modH->no_btg;?></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">No Grade</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php echo $modH->no_grade;?></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">No Lapangan</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php echo $modH->no_lap;?></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Kode Potong</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?php echo (empty($modH->pot))?"-":$modH->pot;?></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Panjang</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->fisik_panjang; ?> m</td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">⌀ Ujung 1</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->diameter_ujung1; ?> cm</td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">⌀ Ujung 2</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->diameter_ujung2; ?> cm</td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">⌀ Pangkal 1</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->diameter_pangkal1; ?> cm</td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">⌀ Pangkal 2</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->diameter_pangkal2; ?> cm</td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">⌀ Rata</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->fisik_diameter; ?> cm</td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Cacat Panjang</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->cacat_panjang; ?> cm</td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Cacat Gubal</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->cacat_gb; ?> cm</td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Cacat Growong</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->cacat_gr; ?> cm</td>
                        </tr>
                        <tr>
                            <td style="width: 140px;">Volume</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->fisik_volume; ?> m<sup>3</sup> </td>
                        </tr>
                         <tr>
                            <td style="width: 140px;">Status</td>
                            <td> : </td>
                            <td style="width: 140px;padding-left: 10px;"><?= $modH->fsc=='true'?'FSC 100%':'Non FSC'; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"><hr></td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Simpan'),['id'=>'btn-save','class'=>'btn btn-primary btn-outline ciptana-spin-btn ladda-button','onclick'=>'saveItem(\''.$modH->no_barcode.'\');', 'data' => ['toggle' => 'modal']]); ?>
                                <!-- <button type="button" id="btn-save-details" class="btn btn-primary btn-outline ciptana-spin-btn ladda-button" onclick="saveItem();" data-style="zoom-in" title="Simpan Detail Penerimaan"><span class="ladda-label">Simpan</span><span class="ladda-spinner"></span></button> -->
                            </td>
                            <td>&nbsp;</td>
                            <td class="text-center"><button type="button" id="btn-close" class="btn btn-danger btn-outline ciptana-spin-btn ladda-button" onclick="cancelItem();" data-style="zoom-in" title="Batalkan Detail Penerimaan"><span class="ladda-label">Batal</span><span class="ladda-spinner"></span></button></td>
                        </tr>
                    </tbody>
                </table>
                <?php \yii\bootstrap\ActiveForm::end(); ?>
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
    function saveItem(no_barcode){
        var jenis_alokasi = '<?= $jenis_alokasi; ?>';
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/planalokasi/saveNomorBarcode']); ?>',
            type   : 'POST',
            data   : {no_barcode:no_barcode, jenis_alokasi:jenis_alokasi},
            success: function (data) {
                if(data.msg){
                    cisAlert(data.msg);
                }
                $('#modal-review').modal('hide');
                $('#table-master').dataTable().fnClearTable();
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function cancelItem() {
        $('#modal-review').modal('toggle');
    }
</script>