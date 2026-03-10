<?php

use app\models\MSuplier;
use yii\bootstrap\ActiveForm;

?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered form-search">
            <div class="portlet-title">
                <div class="tools panel-cari">
                    <button href="javascript:;"
                            class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
                    <span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian') ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'form-search-laporan',
                    'fieldConfig' => [
                        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                        'labelOptions' => ['class' => 'col-md-4 control-label'],
                    ],
                    'enableClientValidation' => false
                ]); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= /** @var MSuplier $model */
                            $form->field($model, 'suplier_nm')->textInput()->label(Yii::t('app', 'Nama Supplier')) ?>
                            <?= $form->field($model, 'suplier_nm_company')->textInput()->label(Yii::t('app', 'Nama Perusahaan')) ?>
                           <?php 
                            if(yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER) { ?>
                                <?= $form->field($model, 'type')->dropDownList(MSuplier::getOptionListTypeSuplier(),['prompt'=>'ALL','class'=>'form-control select2'])->label(Yii::t('app', 'Jenis Suplier')); ?>
                            <?php }else{ ?>
                                <?= $form->field($model, 'type')->dropDownList(MSuplier::getOptionListTypeSuplier(),['class'=>'form-control select2'])->label(Yii::t('app', 'Jenis Suplier')); ?>
                            <?php }?>    
                        </div>
                        <div class="col-md-5">
                            <?= $form->field($model, 'suplier_almt')->textInput()->label(Yii::t('app', 'Alamat')) ?>
                            <?= $form->field($model, 'suplier_ket')->textarea()->label(Yii::t('app', 'Keterangan')) ?>
                        </div>
                    </div>
                    <?php echo $this->render('@views/apps/form/tombolSearch') ?>
                </div>
                <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
                <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>