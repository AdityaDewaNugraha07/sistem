<?php
if(!isset($sizelabel)){
	$sizelabel = "3";
}
if(!isset($sizeelement)){
	$sizeelement = "6";
}
$today = date('Y-m-d');
$periodeSekarang = date('F Y', strtotime($today));
?>
<div class="form-group">
    <label class="col-md-<?= $sizelabel ?> control-label"><?= $label ?></label>
    <div class="col-md-<?= $sizeelement ?>">
            <span class="input-group-btn" style="width: 40%">
                <?= $form->field($model, 'tgl_awal')->dropDownList(
                    \app\models\ViewPenyerapanBudget::getOptionListPeriodeBln(), 
                    ['value' => isset($model->tgl_awal) ? $model->tgl_awal : $periodeSekarang]
                )->label(false); ?>
            </span>
            <span class="input-group-addon" style="background-color: #fff; border: 0;"> <?= Yii::t('app', 's/d') ?> </span>
            <span class="input-group-btn" style="width: 40%">
                <?= $form->field($model, 'tgl_akhir')->dropDownList(
                    \app\models\ViewPenyerapanBudget::getOptionListPeriodeBln(), 
                    ['value' => $periodeSekarang]
                )->label(false); ?>
            </span>
        <span class="help-block"></span>
    </div>
</div>

