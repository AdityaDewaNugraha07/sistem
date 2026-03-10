<div class="modal fade" id="modal-transaksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <?php use app\models\TApproval;
            use yii\bootstrap\ActiveForm;
            use yii\helpers\Html;

            $form = ActiveForm::begin([
                'id' => 'form-approve',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions' => ['class' => 'col-md-3 control-label'],
                ],
            ]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                        aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Persetujuan Monitoring I/O Produksi') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php /** @var TApproval $model */
                        echo $form->field($model, 'keterangan')->textarea(['placeholder' => 'Ketik Keterangan', 'value' => ''])->label(Yii::t('app', 'Keterangan ')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo Html::button(Yii::t('app', 'Ok'), ['class' => 'btn hijau btn-outline ciptana-spin-btn',
                    'onclick' => 'submitformajax(this)'
                ]);
                ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php $this->registerJs("formconfig();", yii\web\View::POS_READY); ?>