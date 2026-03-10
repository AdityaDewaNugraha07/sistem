<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Laporan Pengeluaran Log Belum Potong';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\RepeaterAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>

<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
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
                                <?php $form = ActiveForm::begin([
                                    'id' => 'form-search-laporan',
                                    'fieldConfig' => [
                                        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                                    ],
                                    'enableClientValidation' => false
                                ]); ?>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <?php echo $this->render('@views/apps/form/periodeTanggal', ['label' => 'Periode', 'model' => $model, 'form' => $form]) ?>
                                        </div>
                                        <div class="col-md-5">
                                            <?php 
                                            // echo $form->field($model, 'cara_keluar')->dropDownList([
                                            //                                                         '' => 'All',
                                            //                                                         'Industri' => 'Industri',
                                            //                                                         'Trading' => 'Trading'
                                            //                                                     ], ['class' => 'form-control', ''])?>
                                            <?= $form->field($model, 'fsc')->dropDownList([
                                                                                                    '' => 'All',
                                                                                                    'true' => 'FSC 100%',
                                                                                                    'false' => 'Non FSC'
                                                                                                ], ['class' => 'form-control'])->label('Status FSC')?>
                                        </div>
                                    </div>
                                    <?php echo $this->render('@views/apps/form/tombolSearch') ?>
                                </div>
                                <?php echo Html::hiddenInput('sort[col]'); ?>
                                <?php echo Html::hiddenInput('sort[dir]'); ?>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-laporan">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Tanggal</th>
                                    <!-- <th rowspan="2">Kode</th> -->
                                    <th rowspan="2">Reff No</th>
                                    <th rowspan="2">Peruntukan</th>
                                    <th rowspan="2">PIC</th>
                                    <th rowspan="2">Jenis Kayu</th>
                                    <th colspan="4">Nomor</th>
                                    <th rowspan="2" style="width: 40px;">Kode<br>Potong</th>
                                    <th rowspan="2">Panjang<br>(m)</th>
                                    <th colspan="5">Diameter (cm)</th>
                                    <th colspan="3">Cacat (cm)</th>
                                    <th rowspan="2">Volume<br>(m<sup>3</sup>)</th>
                                    <th rowspan="2">Status FSC</th>
                                </tr>
                                <tr>
                                    <th>QRcode</th>
                                    <th>Grade</th>
                                    <th>Lapangan</th>
                                    <th>Batang</th>
                                    <th>Ujung 1</th>
                                    <th>Ujung 2</th>
                                    <th>Pangkal 1</th>
                                    <th>Pangkal 2</th>
                                    <th>Rata Rata</th>
                                    <th>Panjang</th>
                                    <th>Gubal</th>
                                    <th>Growong</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
    $('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
	formconfig();
    dtLaporan();
	changePertanggalLabel();
"); ?>
<script>
    function dtLaporan() {
        var dt_table = $('#table-laporan').dataTable({
            pageLength: 100,
            ajax: {
                url: '<?= Url::toRoute('/ppic/laporan/logkeluarbelumpotong') ?>',
                data: {
                    dt: 'table-laporan',
                    laporan_params: $("#form-search-laporan").serialize(),
                }
            },
            columnDefs: [
                {
                    targets: 1,
                    class: "td-kecil text-center",
                    render: function(data, type, full, meta) {
                        var date = new Date(data);
                        date = date.toString('dd/MM/yyyy');
                        return date;
                    }
                },
                {
                    targets: 8,
                    class: "td-kecil text-left"
                },
                {
                    targets: 20,
                    class: "td-kecil text-right",
                    render: function(data) {
                        return parseFloat(data).toFixed(2);
                    }
                },
                {
                    targets: 21,
                    class: "td-kecil text-center",
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
                    className: 'td-kecil text-center'
                },
            ],
            rowCallback: function(row, data, index) {
                var api = this.api();
                var startIndex = api.context[0]._iDisplayStart;
                $('td:eq(0)', row).html(index + 1 + startIndex);
            },
            "fnDrawCallback": function(oSettings) {
                changePertanggalLabel();
                formattingDatatableReport(oSettings.sTableId);
                if (oSettings.aLastSort[0]) {
                    $('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
                    $('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
                }
            },
            order: [
                [1, 'desc'],
            ],
            "bDestroy": true,
        });
    }

    function printout(caraPrint) {
        window.open("<?= Url::toRoute('/ppic/laporan/logkeluarbelumpotongPrint') ?>?" + $('#form-search-laporan').serialize() + "&caraprint=" + caraPrint, "", 'location=_new, width=1200px, scrollbars=yes');
    }

    function changePertanggalLabel() {
        if ($('#<?= Html::getInputId($model, 'tgl_awal'); ?>').val()) {
            $('#periode-label').html("Periode " + $('#<?= Html::getInputId($model, 'tgl_awal'); ?>').val() + " sd " + $('#<?= Html::getInputId($model, 'tgl_akhir'); ?>').val());
        } else {
            $('#periode-label').html("");
        }
    }
</script>