<?php
$model = new \app\models\TAdjustmentLog();
?>
<label id="label_reff_no_loglist" class="col-md-4 control-label"><?= Yii::t('app', 'Kode Loglist'); ?></label>
<div class="col-md-8">
    <?= \yii\bootstrap\Html::activeDropDownList($model, 'reff_no_loglist', \app\models\TLoglist::getOptionListAdjustment($pengajuan_pembelianlog_id),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;']); ?>
</div>
