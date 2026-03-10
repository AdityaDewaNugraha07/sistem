<?php

use app\components\DeltaFormatter;
use app\models\TVideoTraining;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/** @var TVideoTraining $modVideoTraining */
?>

<style>
    .video-container {
        overflow: hidden;
        position: relative;
        width:100%;
        display: none;
    }

    .video-container::after {
        padding-top: 56.25%;
        display: block;
        content: '';
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>

<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Training periode  ').' '. DeltaFormatter::formatDateTimeForUser($modVideoTraining->tgl_awal) . ' s/d ' . DeltaFormatter::formatDateTimeForUser($modVideoTraining->tgl_akhir); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <ul class="nav nav-tabs video-tabs">
                            <?php $no = 1; foreach (Json::decode($modVideoTraining->video, true) as $video): ?>
                                <li role="presentation" class="<?= $no === 1 ? 'active' : '' ?>">
                                    <a href="javascript:void(0)" data-id="video-<?= $no ?>">Training <?= $no ?></a>
                                </li>
                            <?php $no++; endforeach;?>
                        </ul>
                        <?php $no = 1; foreach (Json::decode($modVideoTraining->video, true) as $video): ?>
                        <div class="video-container" id="video-<?= $no ?>">
                            <iframe src="<?= $video['url'] ?>"
                                    title="<?= $modVideoTraining->judul ?>"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                            </iframe>
                        </div>
                        <?php $no++; endforeach;?>
                        <div class="caption-container">
                            <h3><?= $modVideoTraining->judul ?></h3>
                            <?= $modVideoTraining->deskripsi ?>
                        </div>
                        <div class="footer-video">
                            <h4>Link evaluasi Peserta</h4>
                            <?php $no = 1; if(!empty($modVideoTraining->evaluasi_peserta)) foreach (Json::decode($modVideoTraining->evaluasi_peserta, true) as $peserta): ?>
                                <a href="<?= $peserta['url'] ?>" class="btn btn-default btn-sm" target="_blank">link <?= $no ?></a>
                            <?php $no++; endforeach; ?>
                            <h4>Link evaluasi Penilai</h4>
                            <?php $no = 1; if(!empty($modVideoTraining->evaluasi_atasan)) foreach (Json::decode($modVideoTraining->evaluasi_atasan, true) as $atasan): ?>
                                <a href="<?= $atasan['url'] ?>" class="btn btn-default btn-sm" target="_blank">link <?= $no ?></a>
                            <?php $no++; endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <table class="table table-striped table-bordered table-hover" id="table-peserta" style="width: auto;">
                            <thead>
                            <tr>
                                <th><?= Yii::t('app', 'NAMA') ?></th>
                                <th><?= Yii::t('app', 'JABATAN') ?></th>
                                <th><?= Yii::t('app', 'DEPARTEMEN') ?></th>
                                <th><?= Yii::t('app', 'SEBAGAI') ?></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
$this->registerJs("
videoTraining();
peserta();
", View::POS_READY);
?>
<script>
    function videoTraining() {
        $('#video-1').show();
        $('.video-tabs li a').each(function() {
            $(this).on('click', function() {
                $('.video-container').hide();
                $('.video-tabs li').removeClass('active');
                let id = $(this).data('id');
                $('#' + id).show();
                $(this).parent().addClass('active')
            })
        })
    }

    function peserta() {
        $('#table-peserta').DataTable({
            ajax: {
                url: '<?= Url::toRoute('/hrd/videotraining/daftarpeserta') . '?id=' . $modVideoTraining->video_training_id ?>',
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
                }
            ],
        });
    }
</script>