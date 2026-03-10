<?php

use app\models\TSpb;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

?>

<div class="modal fade" id="modal-transaksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
			<?php $form = ActiveForm::begin([
				'id' => 'form-approve',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-8">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-3 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Alasan Penolakan') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= /** @var TSpb $model */
                        $form->field($model, 'reason_ditolak')->textarea(['placeholder'=>'Ketik alasan','value'=>''])->label(Yii::t('app', 'Alasan')) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php // echo \yii\helpers\Html::button(Yii::t('app', 'Batalkan'),['class'=>'btn red btn-outline ciptana-spin-btn','onclick'=>"submitformajax(this)"]); ?>
				<?= Html::button( Yii::t('app', 'Ok'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"location.reload();")'
                    ])
                ?>
            </div>
			<?php ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    formconfig();
", View::POS_READY); ?>