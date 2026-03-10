<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-approve',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-8">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-3 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Persetujuan Pengajuan Asuransi'); ?></h4>
            </div>
            <div class="modal-body">
                <span><?= Yii::t('app', 'Inputkan alasan anda <font style="font-weight: bold; color: darkgreen;">menyetujui</font>'); ?></span>
                <br><br>
                <?php echo $form->field($modelReff, 'approve_reason')->textarea(['placeholder'=>'Ketik alasan','value'=>''])->label(Yii::t('app', 'Alasan')); ?>
            </div>
            <div class="modal-footer">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Ok'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"location.reload();")'
                    ]);
				?>
            </div>
			<?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
formconfig();
", yii\web\View::POS_READY); ?>