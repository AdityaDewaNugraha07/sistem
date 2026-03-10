<?php 
/*
pastikan div id modal adalah id yang dipanggil di index.php
*/

use app\models\TVideoTraining;
use yii\helpers\Url; ?>

<div class="modal fade" id="modal-tambah-peserta" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= /** @var TVideoTraining $model */
                    Yii::t('app', 'Peserta Training: ').' '.$model->judul; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <table class="table table-striped table-bordered table-hover" id="table-pegawai" style="width: auto;">
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'Nama') ?></th>
                                    <th><?= Yii::t('app', 'Jabatan') ?></th>
                                    <th><?= Yii::t('app', 'Departemen') ?></th>
                                    <th>Tambah</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <table class="table table-striped table-bordered table-hover" id="table-peserta" style="width: auto;">
                            <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Nama') ?></th>
                                <th><?= Yii::t('app', 'Jabatan') ?></th>
                                <th><?= Yii::t('app', 'Departemen') ?></th>
                                <th><?= Yii::t('app', 'Sebagai') ?></th>
                                <th>Hapus</th>
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
$this->registerJs("
dtPegawai();
",yii\web\View::POS_READY);
?>

<script>
function dtPegawai() {
    $('#table-peserta').DataTable({
        ajax: {
            url: '<?= Url::toRoute('/hrd/videotraining/daftarpeserta') . '?id=' . $model->video_training_id ?>',
            data:{
                dt: 'table-peserta'
            }
        },
        order: [
            [1, 'asc']
        ],
        pageLength: 15,
        oLanguage: {
            sSearch: 'Cari Peserta'
        },
        dom: "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        columnDefs: [
            {   targets: 0,
                render: function ( data, type, full, meta ) {
                    return full[1]
                }
            },
            {   targets: 1,
                render: function ( data, type, full, meta ) {
                    return full[2];
                }
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    return full[3]
                }
            },
            {
                targets: 3,
                render: function (data, type, full, meta) {
                    return full[5] === 'atasan' ? 'PENILAI' : 'PESERTA'
                }
            },
            {   targets: 4,
                orderable: false,
                class: 'text-center',
                render: function ( data, type, full, meta ) {
                    return `<a class="btn btn-xs red-sunglo btn-outline tooltips"
                                href="javascript:void(0)"
                                onclick="hapusPeserta(<?= $model->video_training_id ?> , ${full[4]})">
                                <i class="fa fa-minus-circle"></i> Hapus
                            </a>`;
                }
            },
        ],
    });
    $('#table-pegawai').DataTable({
        ajax: {
            url: '<?= Url::toRoute('/hrd/videotraining/tambahpeserta') . '?id=' . $model->video_training_id ?>',
            data:{
                dt: 'table-pegawai'
            }
        },
        order: [
            [1, 'asc']
        ],
        pageLength: 15,
        oLanguage: {
            sSearch: 'Cari Pegawai'
        },
        dom: "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        columnDefs: [
            {   targets: 0,
                render: function ( data, type, full, meta ) {
                    return full[1]
                }
            },
            {   targets: 1,
                render: function ( data, type, full, meta ) {
                    return full[2];
                }
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    return full[3]
                }
            },
            {   targets: 3,
                orderable: false,
                class: 'text-center',
                render: function ( data, type, full, meta ) {
                    return `<a class="btn btn-xs blue-hoki btn-outline tooltips"
                                href="javascript:void(0)"
                                onclick="buatPeserta(<?= $model->video_training_id ?> , ${full[0]}, 'peserta')">
                                <i class="fa fa-plus-circle"></i> Peserta
                            </a>
                            <a class="btn btn-xs green-haze btn-outline tooltips"
                                href="javascript:void(0)"
                                onclick="buatPeserta(<?= $model->video_training_id ?> , ${full[0]}, 'atasan')">
                                <i class="fa fa-plus-circle"></i> Penilai
                            </a>`;
                }
            },
        ],
    });
}

function buatPeserta(id_training, id_pegawai, tipe) {
    $.ajax({
        url: `<?= Url::toRoute('/hrd/videotraining/buatpeserta') ?>?id_training=${id_training}&id_pegawai=${id_pegawai}&tipe=${tipe}`,
        success: function(res) {
            const result = JSON.parse(res);
            if(result.status) {
                $('#table-peserta').DataTable().ajax.reload();
                $('#table-pegawai').DataTable().ajax.reload();
            }
        }
    })
}

function hapusPeserta(id_training, id_pegawai) {
    $.ajax({
        url: `<?= Url::toRoute('/hrd/videotraining/hapuspeserta') ?>?id_training=${id_training}&id_pegawai=${id_pegawai}`,
        success: function(res) {
            const result = JSON.parse(res);
            if(result.status) {
                $('#table-peserta').DataTable().ajax.reload();
                $('#table-pegawai').DataTable().ajax.reload();
            }
        }
    })
}
</script>