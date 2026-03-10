<?php
/* @var $this yii\web\View */
$this->title = 'Laporan OP Export belum Proforma';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
    #table-laporan thead th {
        font-size: 1.2rem;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <?= $this->render('_search', ['model' => $model]) ?>
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>
                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Tabel Rekap OP Export belum Proforma Packing List '); ?><span id="periode-label" class="font-blue-soft"></span></span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="reload"> </a>
                    <a href="javascript:;" class="fullscreen"> </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="width: 150px;"><?= Yii::t('app', 'Nomor Order'); ?></th>
                            <th style="width: 200px;"><?= Yii::t('app', 'Nomor Kontrak'); ?></th>
                            <th><?= Yii::t('app', 'Jenis<br>Produk'); ?></th>
                            <th><?= Yii::t('app', 'Tanggal'); ?></th>
                            <th style="width: 250px;"><?= Yii::t('app', 'Applicant'); ?></th>
                            <th></th>
                            <th style="width: 250px;"><?= Yii::t('app', 'Notify Party'); ?></th>
                            <th></th>
                            <th><?= Yii::t('app', 'Payment<br>Method'); ?></th>
                            <th><?= Yii::t('app', 'Term of Price'); ?></th>
                            <th><?= Yii::t('app', 'HS Code'); ?></th>
                            <th><?= Yii::t('app', 'Nomor SVLK'); ?></th>
                            <th style="width: 35px;"></th>
                            <th><?= Yii::t('app', 'Keterangan'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
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
", yii\web\View::POS_READY); ?>
<script>
    function dtLaporan() {
        var dt_table = $('#table-laporan').dataTable({
            pageLength: 30,
            ajax: {
                url: '<?= \yii\helpers\Url::toRoute('/exim/laporan/opexportblmproforma') ?>',
                data: {
                    dt: 'table-laporan',
                    laporan_params: $("#form-search-laporan").serialize(),
                }
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    targets: 0,
                    visible: false
                },
                {
                    targets: 1,
                    class: "text-align-center",
                },
                {
                    targets: 2,
                    class: "text-align-center",
                },
                {
                    targets: 3,
                    class: "td-kecil text-align-center",
                },
                {
                    targets: 4,
                    class: "td-kecil text-align-center",
                    render: function(data, type, full, meta) {
                        var date = new Date(data);
                        date = date.toString('dd/MM/yyyy');
                        return '<center>' + date + '</center>';
                    }
                },
                {
                    targets: 5,
                    class: "td-kecil",
                    render: function(data, type, full, meta) {
                        return "<b>" + ((data) ? data : "") + "</b><br>" + ((full[5]) ? full[5] : "");
                    }
                },
                {
                    targets: 6,
                    visible: false,
                },
                {
                    targets: 7,
                    class: "td-kecil",
                    render: function(data, type, full, meta) {
                        return "<b>" + ((data) ? data : "") + "</b><br>" + ((full[7]) ? full[7] : "");
                    }
                },
                {
                    targets: 8,
                    visible: false,
                },
                {
                    targets: 9,
                    class: "td-kecil text-align-center",
                },
                {
                    targets: 10,
                    class: "td-kecil text-align-center",
                },
                {
                    targets: 11,
                    class: "td-kecil text-align-center",
                },
                {
                    targets: 12,
                    class: "td-kecil text-align-center",
                },
                {
                    targets: 13,
                    visible: false
                },
                {
                    targets: 14,
                    class: "td-kecil text-align-center",
                },
            ],
            autoWidth: false,
            "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "createdRow": function(row, data, index) {
                if (data[13]) {
                    $(row).addClass("cancelBackground");
                }
            },
            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
            "bDestroy": true,
            drawCallback: function(oSettings) {
                $('#' + oSettings.sTableId + '_wrapper').find('.dataTables_moreaction').html("\
                    <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
                ");
                $('#' + oSettings.sTableId + '_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
                $('#' + oSettings.sTableId + '_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
                $(".tooltips").tooltip({
                    delay: 50
                });
                changePertanggalLabel();
            }
        });
    }

    function printout(caraPrint) {
        window.open("<?= yii\helpers\Url::toRoute('/exim/laporan/opexportblmproformaprint') ?>?caraprint=" + caraPrint + "&" + $('#form-search-laporan').serialize(), "", 'location=_new, width=1200px, scrollbars=yes');
    }

    function changePertanggalLabel() {
        if ($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal'); ?>').val()) {
            $('#periode-label').html("Periode " + $('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal'); ?>').val() + " sd " + $('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir'); ?>').val());
        } else {
            $('#periode-label').html("");
        }
    }
</script>