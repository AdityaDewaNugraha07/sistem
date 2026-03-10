<?php

use yii\helpers\Url;

?>
<div class="modal fade" id="modal-master-produk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                        aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Master Produk') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
                            <thead>
                            <tr>
                                <th></th>
                                <th style="width: 10%;"><?= Yii::t('app', 'Jenis Produk') ?></th>
                                <th><?= Yii::t('app', 'Kode Produk') ?></th>
                                <th><?= Yii::t('app', 'Nama Produk') ?></th>
                                <th><?= Yii::t('app', 'Dimensi') ?></th>
                                <th><?= Yii::t('app', 'Status') ?></th>
                                <th style="width: 50px;"></th>
                            </tr>
                            </thead>
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
<script>
    $('#table-produk').dataTable({
        ajax: {url: '<?= Url::toRoute('/ppic/hasilproduksi/modalproduk') ?>', data: {dt: 'table-produk'}},
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {targets: 0, visible: false},
            {
                targets: 2,
                width: "250px",
                render: function (data, type, full) {
                    return `<a onclick='pickProduk(${full[0]}, "${data}")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'>
                                <i class='fa fa-plus-circle'></i>
                            </a>${data}`;
                }
            },
            {
                targets: 5,
                orderable: false,
                class: "text-align-center",
                width: '10%',
                render: function (data) {
                    return data ? 'Active' : '<span style="color:#B40404">Non-Active</span>';
                }
            },
            {
                targets: 6,
                orderable: false,
                class: "text-align-center",
                width: '5%',
                render: function (data, type, full) {
                    return `<div style="text-align: center;">
                                <a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="info(${full[0]})">
                                    <i class="fa fa-info-circle"></i>
                                </a>
                            </div>`;
                }
            },
        ],
        "autoWidth": false,
        "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });


    function info(id) {
        $('.modals-place-2').load('<?= Url::toRoute('/ppic/produk/info') ?>?id=' + id, function () {
            $("#modal-produk-info").modal('show');
            spinbtn();
            draggableModal();
        });
    }
</script>