<?php
/* @var $this yii\web\View */

use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Compare Hasil Loglist vs Penerimaan';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<style>
    .Jloglist {
        background-color: #E9EE94 !important;
    }

    .Jpenerimaan {
        background-color: #94EE9F !important;
    }

    .Jno {
        background-color: #A2B0FB;
    }

    .loglist {
        background-color: #e8ead5;
    }

    .penerimaan {
        background-color: #d2ead1;
    }

    .no {
        background-color: #CDD4F9;
    }

    #table-loglist,
    #table-loglist th,
    #table-loglist td,
    #table-terima,
    #table-terima th,
    #table-terima td
    {
        border: 1px solid #333 !important;
    }

    th i {
        margin-left: 2px;
    }
</style>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <?= $this->render('_search', ['model' => $modelTerimaLogalam]) ?>
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>
                    <span class="caption-subject hijau bold"><?= $this->title; ?><span id="periode-label" class="font-blue-soft"></span></span>
                </div>
                <div class="tools">
                    <!-- <a href="javascript:;" class="reload"> </a>
                    <a href="javascript:;" class="fullscreen"> </a> -->
                    <button class="btn btn-sm green-jungle" onclick="excel()"><i class="fa fa-table"></i> Excel</button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-5">
                                <table class="table table-striped table-bordered table-hover table-laporan" id="table-loglist" style="border: solid 1px #ececec;">
                                    <thead>
                                        <tr>
                                            <th rowspan="3" class="Jno">No.</th>
                                            <th colspan="10" class="Jloglist">LOGLIST</th>
                                        </tr>
                                        <tr>
                                            <th class="td-kecil loglist" colspan="3">Nomor</th>
                                            <th class="td-kecil loglist" rowspan="2">Jenis Kayu</th>
                                            <th class="td-kecil loglist" rowspan="2">Panjang</th>
                                            <th class="td-kecil loglist" colspan="3">Cacat</th>
                                            <th class="td-kecil loglist" rowspan="2">Diameter</th>
                                            <th class="td-kecil loglist" rowspan="2">Volume</th>
                                        </tr>
                                        <tr>
                                            <th class="td-kecil loglist sortable" data-column="nomor_grd" data-type="INTEGER" data-group="loglist">Grade</th>
                                            <th class="td-kecil loglist sortable" data-column="nomor_batang" data-type="VARCHAR" data-group="loglist">Batang</th>
                                            <th class="td-kecil loglist sortable" data-column="nomor_produksi" data-type="VARCHAR" data-group="loglist">Produksi</th>
                                            <th class="td-kecil loglist">Panjang</th>
                                            <th class="td-kecil loglist">GB</th>
                                            <th class="td-kecil loglist">GR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7">
                                <table class="table table-striped table-bordered table-hover table-laporan" id="table-terima" style="border: solid 1px #ececec;">
                                    <thead>
                                        <tr>
                                            <th rowspan="3" class="Jno">No.</th>
                                            <th colspan="15" class='Jpenerimaan'>PENERIMAAN</th>
                                        </tr>
                                        <tr>
                                            <th class="td-kecil penerimaan" colspan="5">Nomor</th>
                                            <th class="td-kecil penerimaan" rowspan="2">Jenis Kayu</th>
                                            <th class="td-kecil penerimaan" rowspan="2">Panjang</th>
                                            <th class="td-kecil penerimaan" colspan="4">Diameter</th>
                                            <th class="td-kecil penerimaan" colspan="3">Cacat</th>
                                            <th class="td-kecil penerimaan" rowspan="2">Volume</th>
                                        </tr>
                                        <tr>
                                            <th class="td-kecil penerimaan sortable" data-column="no_grade" data-type="INTEGER" data-group="terima">Grade</th>
                                            <th class="td-kecil penerimaan sortable" data-column="no_btg" data-type="VARCHAR" data-group="terima">Batang</th>
                                            <th class="td-kecil penerimaan sortable" data-column="no_produksi" data-type="VARCHAR" data-group="terima">Produksi</th>
                                            <th class="td-kecil penerimaan sortable" data-column="no_lap" data-type="VARCHAR" data-group="terima">Lapangan</th>
                                            <th class="td-kecil penerimaan sortable" data-column="no_barcode" data-type="VARCHAR" data-group="terima">QRCode</th>
                                            <th class="td-kecil penerimaan">U1</th>
                                            <th class="td-kecil penerimaan">U2</th>
                                            <th class="td-kecil penerimaan">P1</th>
                                            <th class="td-kecil penerimaan">P2</th>
                                            <th class="td-kecil penerimaan">Panjang</th>
                                            <th class="td-kecil penerimaan">GB</th>
                                            <th class="td-kecil penerimaan">GR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<?php $this->registerJs("mergeSameValue(); init();"); ?>
<script>
    function mergeSameValue() {
        var arr = [];
        var coll = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $("#table-informasi").find('tr').each(function(r, tr) {
            $(this).find('td').each(function(d, td) {
                if (coll.indexOf(d) !== -1) {
                    var $td = $(td);
                    var v_dato = $td.html();
                    if (typeof arr[d] != 'undefined' && 'dato' in arr[d] && arr[d].dato == v_dato) {
                        var rs = arr[d].elem.data('rowspan');
                        if (rs == 'undefined' || isNaN(rs)) rs = 1;
                        arr[d].elem.data('rowspan', parseInt(rs) + 1).addClass('rowspan-combine');
                        $td.addClass('rowspan-remove');
                    } else {
                        arr[d] = {
                            dato: v_dato,
                            elem: $td
                        };
                    };
                }
            });
        });
        $('.rowspan-combine').each(function(r, tr) {
            var $this = $(this);
            $this.attr('rowspan', $this.data('rowspan')).css({
                'vertical-align': 'middle'
            });
        });
        $('.rowspan-remove').remove();
    }

    function init() {
        let orders = [];
        $('.sortable').each(function(i, sortable) {
            let icons = [
                '<i class="fa fa-sort" aria-hidden="true"></i>',
                '<i class="fa fa-sort-asc" aria-hidden="true"></i>',
                '<i class="fa fa-sort-desc" aria-hidden="true"></i>'
            ];
            let column = $(sortable).data('column');
            let type = $(sortable).data('type');
            let group = $(sortable).data('group');
            let direction = '';
            orders.push({
                column,
                direction,
                type,
                group
            })
            let directions = ['', 'ASC', 'DESC'];
            let state = 0;
            let sortableElement = $(sortable);
            sortableElement.append(icons[state])
                .css('cursor', 'pointer')
                .css('white-space', 'nowrap')
                .on('click', function(e) {
                    $('i', this).remove();
                    state = (state + 1) % icons.length;
                    $(this).append(icons[state])
                    orders = orders.map(r => {
                        if (r.column === $(this).data('column')) {
                            return {
                                ...r,
                                direction: directions[state]
                            }
                        } else {
                            return r
                        }
                    })

                    cupet(orders);
                })
        })
    }

    function excel() {
        const area_pembelian = $('#tterimalogalam-area_pembelian input[type=radio]:checked').val();
        const pengajuan_pembelianlog_id = $('#tterimalogalam-pengajuan_pembelianlog_id').val();
        const spk_shipping_id = $('#tterimalogalam-spk_shipping_id').val();
        window.open('<?= Url::toRoute('/ppic/laporan/comparePrint')?>?caraprint=EXCEL&area_pembelian=' + area_pembelian + '&pengajuan_pembelianlog_id=' + pengajuan_pembelianlog_id + '&spk_shipping_id=' + spk_shipping_id)
    }
</script>