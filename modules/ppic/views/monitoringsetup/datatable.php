<?php

use app\models\MMtrgSetup;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered form-search">

            <div class="portlet-body">
                <div class="modal-body">
                    <table class="table table-bordered table-hover" id="table-monitoring-setup" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="text-center">Tanggal</th>
<!--                            <th class="text-center">No</th>-->
                            <th>Jenis Proses</th>
                            <th>Grade</th>
                            <th>Kategori Proses</th>
                            <th>Jenis Kayu</th>
                            <th>Plan Harian</th>
                            <th>Jumlah Aktual</th>
                            <th>Satuan Harian</th>
                            <th>Sequence</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function main() {
        formconfig();
        const table = $('#table-monitoring-setup').DataTable({
            ajax: {
                url: '<?= Url::toRoute('/ppic/monitoringsetup/datatable')?>',
                data: function (d) {
                    d.tanggal = $('#tanggal').val();
                    d.kategori_proses = $('#kategori_proses').val();
                    d.jenis_proses = $('#jenis_proses').val();
                    d.jenis_kayu = $('#jenis_kayu').val();
                    d.grade = $('#grade').val();
                },
            },
            responsive: true,
            order: [[0, 'desc'], [3, 'asc'], [4, 'desc'], [8, 'asc']],
            pageLength: 15,
            columnDefs: [
                {
                    targets: 9,
                    class: 'text-center',
                    render: function (data, type, full) {
                        return `
                            <button class="btn btn-xs yellow-crusta btn-edit" type="button" onclick="edit(${full[9]})">
                                <i class="fa fa-pencil"></i>
                            </button>
                        `;
                    }
                },
                {
                    targets: '_all',
                    class: 'text-center'
                }
            ],
            drawCallback: function () {
                $('.dataTables_wrapper').children('.row').first().hide();
            }
        });

        const ids = ['#kategori_proses', '#jenis_proses', '#jenis_kayu', '#grade', '#tanggal'];
        ids.forEach(function (id) {
            $(id).change(function () {
                table.ajax.reload();
                return false;
            })
        })
    }
</script>