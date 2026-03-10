<?php
use app\models\TVideoTraining;
use yii\helpers\Json;
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
    <?php if($modVideoTraining->relatedRecords['peserta']->tipe === 'peserta'):?>
        <h4>Link evaluasi Peserta</h4>
        <?php $no = 1; if(!empty($modVideoTraining->evaluasi_peserta)) foreach (Json::decode($modVideoTraining->evaluasi_peserta, true) as $peserta): ?>
            <a href="<?= $peserta['url'] ?>" class="btn btn-default btn-sm" target="_blank">link <?= $no ?></a>
        <?php $no++; endforeach; ?>
    <?php else: ?>
        <h4>Link evaluasi Penilai</h4>
        <?php $no = 1; if(!empty($modVideoTraining->evaluasi_atasan)) foreach (Json::decode($modVideoTraining->evaluasi_atasan, true) as $atasan): ?>
            <a href="<?= $atasan['url'] ?>" class="btn btn-default btn-sm" target="_blank">link <?= $no ?></a>
        <?php $no++; endforeach; ?>
    <?php endif; ?>
</div>

<?php

$this->registerJs("
    videoTraining();
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
</script>