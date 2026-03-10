<?php if(Yii::$app->session->hasFlash('success')){ ?>
<div class="alert" style="border-color: #298A08; color: #298A08">
    <strong><?= Yii::t('app', 'Transaksi Berhasil!'); ?></strong>
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true"></button>
     &nbsp; <small><?= Yii::$app->session->getFlash('success') ?></small>
</div>
<?php } ?>
<?php if(Yii::$app->session->hasFlash('error')){ ?>
<div class="alert" style="border-color: #e35b5a; color: #e35b5a">
    <strong><?= Yii::t('app', 'Transaksi Gagal!'); ?></strong>
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true"></button>
     &nbsp; <small><?= Yii::$app->session->getFlash('error') ?></small>
</div>
<?php } ?>