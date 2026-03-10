<?php

use yii\helpers\Url;
use app\assets\DatatableAsset;
use app\assets\DatepickerAsset;
use app\models\MKayu;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\JqueryAsset;

$this->title = 'Laporan Persediaan Log';
DatatableAsset::register($this);
DatepickerAsset::register($this);

?>

<h1 class="page-title"> <?php echo $this->title; ?></h1>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered form-search">
                            <div class="portlet-title">
                                <div class="tools panel-cari">
                                    <button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
                                    <span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-search-laporan',
                                    'options' => ['class' => 'form-inline margin-top-20'],
                                    'fieldConfig' => [
                                        'options' => ['class' => 'form-group margin-right-10'],
                                        'template' => ' {label} {input} {error} ',
                                    ],
                                    'enableClientValidation' => false
                                ]);
                                echo $form->field($model, 'tgl_transaksi', [
                                    'template' => '{label}
                                                    <div class="input-group input-medium date date-picker bs-datetime">
                                                            {input} 
                                                            <span class="input-group-addon">
                                                                <button class="btn default" type="button" style="margin-left: 0px;">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    {error}
                                                '
                                ])->textInput(['readonly' => 'readonly']);
                                echo $form->field($model, 'kayu_id')->dropDownList(MKayu::getOptionList(), ['prompt' => 'All']);
                                echo $form->field($model, 'fsc')->dropDownList(['true'=>'FSC 100%', 'false'=>'Non FSC'], ['prompt' => 'All'])->label('Status FSC'); // tambah fsc
                                echo Html::button('Cari', ['class' => 'btn btn-default btn-cari', 'style' => 'margin-top: -5px']);
                                ActiveForm::end();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-rekap">
                                    <thead>
                                        <tr>
                                            <th rowspan="2"><?= Yii::t('app', 'No.'); ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Kayu') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'No. QRcode') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'No. Grade') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'No. Lap') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'No. Batang') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Pcs') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Panjang<br>(m)') ?></th>
                                            <th rowspan="2" style="width: 50px;"><?= Yii::t('app', 'Kode </br>Potong') ?></th>
                                            <th colspan="5"><?= Yii::t('app', 'Diameter (cm)') ?></th>
                                            <th colspan="3"><?= Yii::t('app', 'Cacat (cm)') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Volume<br>(m<sup>3</sup>)') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Status FSC') ?></th> <!-- TAMBAH FSC -->
                                        </tr>
                                        <tr>
                                            <th>Ujung 1</th>
                                            <th>Ujung 2</th>
                                            <th>Pangkal 1</th>
                                            <th>Pangkal 2</th>
                                            <th>Rata-rata</th>
                                            <th>Panjang</th>
                                            <th>Gubal</th>
                                            <th>Growong</th>
                                        </tr>
                                    </thead>

                                    <tfoot>
                                        <tr>
                                            <th colspan="6" style="text-align: right;">Total Per Page</th>
                                            <th id="pcs_page"></th>
                                            <th colspan="10"></th>
                                            <th id="m3_page" class="text-right"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="6" class="text-right">Total All Page</th>
                                            <th id="total_pcs" class="text-center"></th>
                                            <th colspan="10"></th>
                                            <th id="total_m3" class="text-right"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    $this->registerJsFile("https://cdn.datatables.net/plug-ins/1.13.4/api/sum().js", ['depends' => [JqueryAsset::class]]);
    $this->registerJs("init()");
    ?>
    <script>
        function init() {
            $('.date-picker').datepicker({format: 'dd/mm/yyyy'});
            const dt = $('#table-rekap').DataTable({
                ajax: {
                    url: '<?= Url::toRoute('/ppic/laporan/laporanStokLog') ?>',
                    data: function(d) {
                        d.tgl_transaksi = $('#<?= Html::getInputId($model, 'tgl_transaksi') ?>').val();
                        d.kayu_id = $('#<?= Html::getInputId($model, 'kayu_id') ?>').val();
                        d.fsc = $('#<?= Html::getInputId($model, 'fsc') ?>').val();
                    }
                },
                processing: true,
                columnDefs: [
                    {
                        targets: 7,
                        className: 'text-right',
                        render: function(data) {
                            return parseFloat(data).toFixed(1);
                        }
                    },
                    {
                        targets: 13,
                        render: function(data, type, row, meta) {
                            return Math.round((parseFloat(row[9]) + parseFloat(row[10]) + parseFloat(row[11]) + parseFloat(row[12])) / 4);
                        }
                    },
                    {
                        targets: 17,
                        className: 'text-right',
                        render: function(data) {
                            return parseFloat(data).toFixed(2);
                        }
                    },
                    // TAMBAH FSC
                    {
                        targets: 18,
                        className: 'text-center',
                        render: function(data) {
                            return data;
                        }
                    },
                    // eo FSC
                    {
                        targets: '_all',
                        className: 'text-center'
                    },
                ],
                rowCallback: function(row, data, index) {
                    var api = this.api();
                    var startIndex = api.context[0]._iDisplayStart;
                    $('td:eq(0)', row).html(index + 1 + startIndex);
                },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    $('#pcs_page').html( api.column(6, {page: 'current'}).data().sum())
                    $('#m3_page').html( api.column(17, {page: 'current'}).data().sum().toFixed(2))
                },
            })

            dt.on('xhr.dt', function(e, settings, json, xhr) {
                $('#total_pcs').text(json.total_pcs ?? 0);
                $('#total_m3').text((parseFloat(json.total_m3) ?? 0).toFixed(2));
            })

            $('.btn-cari').on('click', function() {
                dt.ajax.reload();
            })
        }

        // function printout(cara) {
        //     window.open(window.location.href + '?caraprint=' + cara);
        // }
        function printout(cara){
            window.open("<?= yii\helpers\Url::toRoute('/ppic/laporan/LaporanStokLogPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+cara,"",'location=_new, width=1200px, scrollbars=yes');
        }
    </script>