<?php

use yii\helpers\Url;
use app\assets\DatatableAsset;

DatatableAsset::register($this);
?>
<div class="modal fade" id="modal-daftarPenerimaanLogAlam" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Penerimaan Log Alam'); ?></h4>
                <h6 class="text-danger">** Perhatian ..!! Tombol Edit dan Hapus hanya bisa digunakan dihari yang sama (sesuai dengan tanggal transaksi)</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-aftersave">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><?= Yii::t('app', 'Kode'); ?></th>
                                    <th><?= Yii::t('app', 'Tanggal'); ?></th>
                                    <th><?= Yii::t('app', 'Area Pembelian'); ?></th>
                                    <th><?= Yii::t('app', 'No. Truk'); ?></th>
                                    <th><?= Yii::t('app', 'No. Dokumen'); ?></th>
                                    <th><?= Yii::t('app', 'Peruntukan'); ?></th>
                                    <th><?= Yii::t('app', 'Lokasi Tujuan'); ?></th>
                                    <th><?= Yii::t('app', 'PIC Ukur'); ?></th>
                                    <th><?= Yii::t('app', 'Edit / View'); ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

$this->registerJs("dtPenerimaanLogAlam();");
?>
<script>
    function dtPenerimaanLogAlam() {
        var dt_table = $('#table-aftersave').dataTable({
            ajax: {
                url: '<?= Url::toRoute('/ppic/terimalogalam/daftarPenerimaanLogAlam') ?>',
                data: {
                    dt: 'modal-daftarPenerimaanLogAlam'
                }
            },
            order: [
                [8, 'desc']
            ],
            autoWidth: false,
            columnDefs: [{
                    targets: 0,
                    visible: false
                },
                {
                    targets: 1,
                    class: "text-align-center td-kecil",
                    render: function(data, type, full, meta) {
                        return full[0];
                    }
                },
                {
                    targets: 2,
                    class: "text-align-center td-kecil",
                    render: function(data, type, full, meta) {
                        var date = new Date(full[1]);
                        date = date.toString('dd/MM/yyyy');
                        return '<center>' + date + '</center>';
                    }
                },
                {
                    targets: 3,
                    class: "text-align-center td-kecil",
                    render: function(data, type, full, meta) {
                        return full[2];
                    }
                },
                {
                    targets: 4,
                    class: "text-align-center td-kecil",
                    render: function(data, type, full, meta) {
                        return full[3];
                    }
                },
                {
                    targets: 5,
                    class: "text-align-center td-kecil",
                    render: function(data, type, full, meta) {
                        return full[4];
                    }
                },
                {
                    targets: 6,
                    class: "text-align-center td-kecil",
                    render: function(data, type, full, meta) {
                        return full[5];
                    }
                },
                {
                    targets: 7,
                    class: "text-align-center td-kecil",
                    render: function(data, type, full, meta) {
                        return full[6];
                    }
                },
                {
                    targets: 8,
                    class: "text-align-center td-kecil",
                    render: function(data, type, full, meta) {
                        return full[7];
                    }
                },
                {
                    targets: 9,
                    render: function(data, type, full, meta) {
                        var dateini = moment().format('DD/MM/YYYY'); // Format tanggal hari ini
                        var date = moment(full[1]).format('DD/MM/YYYY'); // Format tanggal dari data
                        var display = "";
                        if(dateini === date) {
                            display = '<a style="margin-right: 0px;" class="btn btn-xs btn-outline yellow tooltips" data-original-title="Edit" onclick="edit(' + full[8] + ')"><i class="fa fa-edit"></i></a>\n\
                                    <a style=""; class="btn btn-xs btn-outline red" onclick="deleteItem(' + full[8] + ')"><i class="fa fa-trash-o"></i></a>';
                        }
                        var ret = '<center>\n\
                                    <a class="btn btn-xs btn-outline blue" onclick="lihatDetail(' + full[8] + ')"><i class="fa fa-eye"></i></a>\n\
                                    '+ display +'</center>';
                        return ret;
                    }
                },
            ],
            "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
    }

    function lihatDetail(id) {
        window.location.replace(
            '<?= Url::toRoute(['/ppic/terimalogalam/index', 'terima_logalam_id' => '']); ?>' +
            id + '&view=1');
    }

    function edit(id) {
        window.location.replace(
            '<?= Url::toRoute(['/ppic/terimalogalam/index', 'terima_logalam_id' => '']); ?>' +
            id + '&edit=1');
    }

    function deleteItem(terima_logalam_id) {
        openModal('<?= Url::toRoute(['/ppic/terimalogalam/deleteItem', 'id' => '']) ?>' + terima_logalam_id,
            'modal-confirm', '250px');
        $('#modal-daftarPenerimaanLogAlam').modal('toggle');
    }
</script>