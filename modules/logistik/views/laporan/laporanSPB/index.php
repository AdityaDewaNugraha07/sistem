<?php
/* @var $this yii\web\View */

use app\models\TmpAnalisaSpb;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\web\View;

$this->title = 'Analisa Pembelian SPB';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <?= /** @var TmpAnalisaSpb $model */
            $this->render('_search', ['model' => $model]) ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-list"></i>
                                <span class="caption-subject hijau bold"><?= Yii::t('app', 'Analisa Pembelian Bahan Pembantu') ?></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover" id="table-list">
                                        <thead style="background-color: #B2C4D3">
                                            <tr>
                                                <!--  0  -->
                                                <th style="text-align: center;"><?= Yii::t('app', 'No.') ?></th>
                                                <!--  1  -->
                                                <th style="text-align: center; line-height: 1;"><?= Yii::t('app', 'SPB') ?></th>
                                                <!--  2  -->
                                                <th style="text-align: center; line-height: 1;"><?= Yii::t('app', 'Tanggal SPB') ?></th>
                                                <!--  3  -->
                                                <th style="text-align: center; line-height: 1;"><?= Yii::t('app', 'Pegawai') ?></th>
                                                <!--  4  -->
                                                <th style="text-align: center; line-height: 1;"><?= Yii::t('app', 'Approval') ?></th>
                                                <!--  5  -->
                                                <th style="text-align: center; line-height: 1;"><?= Yii::t('app', 'SPP') ?></th>
                                                <!--  6  -->
                                                <th style="text-align: center; line-height: 1;"><?= Yii::t('app', 'SPO/SPL') ?></th>
                                                <!--  7  -->
                                                <th style="text-align: center; line-height: 1;"><?= Yii::t('app', 'TPB') ?></th>
                                                <!--  8  -->
                                                <th style="text-align: center; line-height: 1;"><?= Yii::t('app', 'BPB') ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>


<?php
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js', [
    'depends' => [
        JqueryAsset::className()
    ]
]);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js', [
    'depends' => [
        JqueryAsset::className()
    ]
]);

$this->registerJsFile('@web/themes/metronic/global/plugins/loader/loader.js', [
    'depends' => [
        JqueryAsset::className()
    ]
]);

$this->registerCssFile('@web/themes/metronic/global/plugins/loader/loader.css');

$this->registerJs("
    formconfig();
    setMenuActive('" . json_encode(app\models\MMenu::getMenuByCurrentURL('Analisa Pembelian Bahan Pembantu')) . "');
    getData();
    
", View::POS_READY) ?>
<?php

$this->registerCss("
    .va-middle {
        vertical-align: middle !important;
    }

    .ladda-button .ladda-label {
        z-index: 1;
    }
    
    .cis-tooltip {
      position: relative;
      display: inline-block;
      border-bottom: 1px dotted black;
    }
    
    .cis-tooltip .cis-tooltiptext {
      visibility: hidden;
      width: max-content;
      background-color: #A6C054;
      color: #fff;
      text-align: left;
      border-radius: 6px;
      padding: 10px;
    
      /* Position the cis-tooltip */
      position: absolute;
      z-index: 1;
    }
    
    .cis-tooltip-left {
      top: -40px;
      right: 105%; 
    }
    
    .cis-tooltip-left::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 100%;
      margin-top: -5px;
      border-width: 5px;
      border-style: solid;
      border-color: transparent transparent transparent #A6C054;
    }
    
    .cis-tooltip:hover .cis-tooltiptext {
      visibility: visible;
    }

");

?>
<script>
    function getData() {
        let datatable = $("#table-list").DataTable({
            ajax: {
                url: '<?= Url::toRoute('/logistik/laporan/laporanSPB') ?>',
                data: function(data) {
                    data.dt = 'table-list';
                    data.lap_params = $("#form-search-laporan").serialize();
                },
            },
            pagingType: "full_numbers",
            pageLength: 10,
            columnDefs: [
                {
                    targets: 0,
                    class: 'va-middle text-center',
                    width: '2%',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    targets: 1,
                    class: 'va-middle',
                    render: function(data, type, full) {
                        return `<span style='padding-bottom: -10px; margin-bottom: -5px;'>
                                    <a onclick="infoSPB('${data}')">
                                        ${data || ''}
                                    </a>
                                </span>
                                <br>
                                <span style='line-height:1em; margin-top: 0; margin-bottom: 10px;'>
                                    <span style="font-size: 10px; color: #999;">
                                        ${full[2] ? moment(full[2]).locale('id').format('LL') : ''}
                                    </span>
                                    <br/>
                                    <span class="label label-${label_color(full[4])}" style="font-size: 9px;padding: 2px;">${full[4]}</span>

                                </span>
                                `
                    }
                },
                {
                    targets: 2,
                    class: 'va-middle text-center',
                    render: function(data) {
                        return moment(data).locale('id').format('LL')
                    }
                },
                {
                    targets: 3,
                    class: 'va-middle text-center',
                    render: function(data, type, full) {
                        return `<span style='padding-bottom: -10px; margin-bottom: -5px;'>
                                    ${data || ''}
                                </span>
                                <br>
                                <span style='line-height:1em; margin-top: 0; margin-bottom: 10px;'>
                                    <span style="font-size: 10px; color: #999;">
                                        ( Departemen ${full[9]} )
                                    </span>
                                </span>
                                `
                    }
                },
                {
                    targets: 4,
                    class: 'va-middle',
                    render: function(data, type, full) {
                        let approvals = JSON.parse(full[11]);
                        let html = '';
                        if(approvals.length) {
                            approvals.forEach(row => {
                                html += '<div style="margin-bottom: 5px">'
                                html += `<span style="font-size: 12px" class="text-${label_color(row.status)}">${row.assigned_nama}</span>`
                                if(row.tanggal_approve) {
                                    html += '<br/>'
                                    html += `<span style='font-size: 10px; color: #999;'>${moment(row.tanggal_approve).locale('id').format('LL')}</span>`
                                }
                                html += '<br/>'
                                html += `<span class="label label-${label_color(row.status)}" style="font-size: 9px;padding: 2px;">${row.status}</span></div>`
                            })
                        }
                        return html;
                    }
                },
                {
                    targets: 5,
                    class: 'va-middle',
                    // orderData: 7,
                    render: function(data, type, full, meta) {
                        let html = '';
                        if(full[12]) {
                            html += '<ul style="margin-left: -25px; list-style: circle; font-size: 12px">';
                            JSON.parse(full[12]).forEach(row => {
                                html += `<li><a onclick="infoSPP('${row}')"> ${row} </a></li>`;
                            })
                            html += "</ul>";
                        }
                        return html;
                    }
                },
                {
                    targets: 6,
                    class: 'va-middle',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        let html = '';
                        if(data) {
                            html += '<ul style="margin-left: -25px; list-style: circle; font-size: 12px">';
                            JSON.parse(data).forEach(row => {
                                html += `<li><a onclick="infoSPLSPO('${row}', '${full[1]}')"> ${row} </a></li>`;
                            })
                            html += "</ul>";
                        }
                        return html;
                    }
                },
                {
                    targets: 7,
                    class: 'va-middle',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        let html = '';
                        if(data) {
                            html += '<ul style="margin-left: -25px; list-style: circle; font-size: 12px">';
                            JSON.parse(data).forEach(row => {
                                html += `<li><a onclick="infoTBP('${row}', '${full[1]}')"> ${row} </a></li>`;
                            })
                            html += "</ul>";
                        }
                        return html;
                    }
                },
                {
                    targets: 8,
                    class: 'va-middle',
                    // orderData: 16,
                    render: function(data, type, full, meta) {
                        let html = '';
                        if(full[10]) {
                            html += '<ul style="margin-left: -25px; list-style: circle; font-size: 12px">';
                            JSON.parse(full[10]).forEach(row => {
                                html += `<li><a onclick="infoBPB('${row.bpb_kode}')" class="cis-tooltip">
                                            ${row.bpb_kode}
                                            <span class="cis-tooltiptext cis-tooltip-left">
                                                <ul style="margin-left: -30px">
                                                    <li>Dikeluarkan: ${row.dikeluarkan_pegawai || "-"}</li>
                                                    <li>Tanggal: ${row.bpb_tgl_keluar || "-"}</li>
                                                    <li>Diterima: ${ row.diterima_pegawai || "-"}</li>
                                                    <li>Tanggal: ${row.bpb_tgl_terima || "-"}</li>
                                                    <li>Status: ${row.bpb_status || "-"}</li>
                                                </ul>
                                            </span>
                                        </a></li>`;
                            })
                            html += "</ul>";
                        }
                        return html;
                    }
                }
            ],
            drawCallback: function(oSettings) {
                $('#' + oSettings.sTableId + '_wrapper').find('.dataTables_moreaction').html("\
                    <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
                ");
                $('#' + oSettings.sTableId + '_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
                $('#' + oSettings.sTableId + '_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
                $(".tooltips").tooltip({
                    delay: 50
                });
                $('.pagination').find('li').removeClass('paginate_button');
                // input tipe hidden untuk pencarian realtime
                $('#start_date').val(oSettings.json.start_date);
                $('#end_date').val(oSettings.json.end_date);
                $('#departement').val(oSettings.json.departement);
                $('body').loading('stop');
            }
        });

        $("#form-search-laporan").submit(function() {
            let detik = 1;
            let intervalId = setInterval(() => {
                $('.loading-overlay-content').css('text-transform', 'none').html('LOADING...<span style="letter-spacing: normal; font-weight: normal; font-size: 10px">( ' + jam_menit_detik(detik) + ')</span>');
                detik++;
            }, 1000);
            $('body').loading({
                theme: 'dark',
                message: 'Loading...',
                onStart: function(loading) {
                    loading.overlay.slideDown(1000);
                },
                onStop: function(loading) {
                    loading.overlay.fadeOut(1000);
                    clearInterval(intervalId);
                }
            });
            datatable.ajax.reload();
            return false;
        })
    }

    function infoSPB(kode) {
        openModal('<?= Url::toRoute(['/logistik/laporan/infoSpb']) ?>?kode=' + kode, 'modal-info-spb', '75%');
    }

    function infoSPP(kode) {
        openModal('<?= Url::toRoute(['/logistik/laporan/infoSpp']) ?>?kode=' + kode, 'modal-info-spp', '75%');
    }

    function infoBPB(kode) {
        openModal('<?= Url::toRoute(['/logistik/laporan/infoBpb']) ?>?kode=' + kode, 'modal-info-bpb', '75%');
    }

    function infoTBP(kode, spb_kode) {
        openModal('<?= Url::toRoute(['/logistik/laporan/infoTbp']) ?>?kode=' + kode + '&spb_kode=' + spb_kode, 'modal-info-tbp', '75%');
    }

    function infoSPLSPO(kode, spb_kode) {
        openModal('<?= Url::toRoute(['/logistik/laporan/infoSplSpo']) ?>?kode=' + kode + '&spb_kode=' + spb_kode, 'modal-info-splspo', '75%');
    }

    function label_color(value) {
        switch (value) {
            case 'TERPENUHI':
                return 'success';
            case 'BELUM DIPROSES':
                return 'default';
            case 'BELUM DITERIMA':
                return 'default';
            case 'DITOLAK':
                return 'danger';
            case 'SEDANG DIPROSES':
                return 'warning';
            case 'REJECTED':
                return 'danger';
            case 'ABORTED':
                return 'warning';
            case 'Not Confirmed':
                return 'default';
            case 'APPROVED':
                return 'primary';
            case 'ALLOWED':
                return 'success';
            case 'SUDAH DITERIMA':
                return 'success';
            default:
                return 'default';
        }
    }

    function jam_menit_detik(seconds) {
        let x     = seconds;
        let y     = x % 3600;
        let jam   = x / 3600;
        let menit = y / 60;
        let detik = y % 60;
        let text = '';
        if(Math.floor(jam) !== 0) {
            text = Math.floor(jam) + ' Jam ' + Math.floor(menit) + ' Menit ' + Math.floor(detik) + ' Detik ';
        }else {
            if(Math.floor(menit) !== 0) {
                text = Math.floor(menit) + ' Menit ' + Math.floor(detik) + ' Detik ';
            }else {
                text = Math.floor(detik) + ' Detik ';
            }
        }
        return text;
    }

    function printout(caraPrint) {
        window.open("<?= yii\helpers\Url::toRoute('/logistik/laporan/laporanSPBPrint') ?>?caraprint=" + caraPrint, "", 'location=_new, width=1200px, scrollbars=yes');
    }
</script>