<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-8">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-3 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Masukkan Alasan Ditolak'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $form->field($model, 'alasan_tolak')->textarea(['placeholder'=>'Ketik alasan penolakan'])->label(Yii::t('app', 'Alasan')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Ok'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'tolak()'
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
<script>
function tolak(){
	var pengajuan_tagihan_id = "<?= $model->pengajuan_tagihan_id ?>";
	var alasan = $("#<?= \yii\helpers\Html::getInputId($model, "alasan_tolak") ?>").val();
	if(alasan){
		$("#<?= \yii\helpers\Html::getInputId($model, "alasan_tolak") ?>").removeClass("error-tb-detail");
		updateStatus(null,"DITOLAK",pengajuan_tagihan_id,alasan);
	}else{
		$("#<?= \yii\helpers\Html::getInputId($model, "alasan_tolak") ?>").addClass("error-tb-detail");
	}
}
</script>