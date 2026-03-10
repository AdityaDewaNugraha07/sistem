<?php

use yii\helpers\Url;
use app\assets\DatatableAsset;
use app\assets\DatepickerAsset;
use app\models\MKayu;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\JqueryAsset;

$this->title = 'Laporan Penerimaan Log Alam Pabrik';
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
                                echo $form->field($model, 'tgl_awal', [
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
                                ])->textInput(['readonly' => 'readonly'])->label('Periode');
                                echo $form->field($model, 'tgl_akhir', [
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
                                ])->textInput(['readonly' => 'readonly'])->label('s/d');
                                echo $form->field($model, 'kayu_id')->dropDownList(MKayu::getOptionList(), ['prompt' => 'All']);
                                echo $form->field($model, 'fsc')->dropDownList(['true'=>'FSC 100%', 'false'=>'Non FSC'], ['prompt' => 'All'])->label('Status FSC');
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
                                            <th rowspan="2"><?= Yii::t('app', 'Tanggal'); ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Kayu') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'PIC') ?></th>
                                            <th colspan="5"><?= Yii::t('app', 'Nomor') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Panjang<br>(m)') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Kode<br>Potong') ?></th>
                                            <th colspan="5"><?= Yii::t('app', 'Diameter (cm)') ?></th>
                                            <th colspan="3"><?= Yii::t('app', 'Cacat (cm)') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Volume<br>(m<sup>3</sup>)') ?></th>
                                            <th rowspan="2"><?= Yii::t('app', 'Status FSC') ?></th>
                                        </tr>
                                        <tr>
                                            <th>QRCode</th>
                                            <th>Grade</th>
                                            <th>Lapangan</th>
                                            <th>Batang</th>
                                            <th>Produksi</th>
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
                                            <th colspan="19" style="text-align: right;">Total Per Page</th>
                                            <th id="m3_page" class="text-right"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="19" class="text-right td-kecil">Total All Page</th>
                                            <th id="total_m3" class="text-right td-kecil"></th>
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
</div>

<?php
$this->registerJsFile("https://cdn.datatables.net/plug-ins/1.13.4/api/sum().js", ['depends' => [JqueryAsset::class]]);
$this->registerJs("init()");
?>
<script>
    function init() {
        $('.date-picker').datepicker({format: 'dd/mm/yyyy'});
        window.dt = $('#table-rekap').DataTable({
            ajax: {
                url: '<?= Url::toRoute('/ppic/laporan/terimaLogPabrik') ?>',
                data: function(d) {
                    d.tgl_awal  = $('#<?= Html::getInputId($model, 'tgl_awal') ?>').val();
                    d.tgl_akhir = $('#<?= Html::getInputId($model, 'tgl_akhir') ?>').val();
                    d.kayu_id   = $('#<?= Html::getInputId($model, 'kayu_id') ?>').val();
                    d.fsc   = $('#<?= Html::getInputId($model, 'fsc') ?>').val();
                }
            },
            columnDefs: [{
                    targets: 19,
                    className: 'text-right',
                    render: function(data) {
                        return parseFloat(data).toFixed(2);
                    }
                },
                {
                    targets: 20,
                    className: 'text-center',
                    render: function(data) {
                        if(data){
                            ret = 'FSC 100%';
                        } else {
                            ret = 'Non FSC';
                        }
                        return ret;
                    }
                },
                {
                    targets: '_all',
                    className: 'text-center td-kecil'
                },
            ],
            rowCallback: function(row, data, index) {
                var api = this.api();
                var startIndex = api.context[0]._iDisplayStart;
                $('td:eq(0)', row).html(index + 1 + startIndex);
            },
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                $('#m3_page').html(api.column(19, {
                    page: 'current'
                }).data().sum().toFixed(2))
            },
            drawCallback: function({sTableId}) {
                formattingDatatableReport(sTableId);
            }
        })

        window.dt.on('xhr.dt', function(e, settings, json, xhr) {
            $('#total_m3').text((parseFloat(json.total_m3) ?? 0).toFixed(2));
        })

        $('.btn-cari').on('click', function() {
            window.dt.ajax.reload();
        })
        
    }

    function printout(caraprint) {
        const tgl_awal  = $('#<?= Html::getInputId($model, 'tgl_awal')?>').val();
        const tgl_akhir = $('#<?= Html::getInputId($model, 'tgl_akhir')?>').val();
        const kayu_id   = $('#<?= Html::getInputId($model, 'kayu_id')?>').val();
        const fsc       = $('#<?= Html::getInputId($model, 'fsc')?>').val();
        const dtParam   = window.dt.ajax.params();
        window.open(`<?= Url::toRoute('/ppic/laporan/terimaLogPabrikPrint')?>?caraprint=${caraprint}&tgl_awal=${tgl_awal}&tgl_akhir=${tgl_akhir}&kayu_id=${kayu_id}&fsc=${fsc}&start=${dtParam.start}&length=${dtParam.length}`, '_blank');
    }

</script>