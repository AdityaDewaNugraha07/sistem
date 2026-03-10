<?php app\assets\InputMaskAsset::register($this); ?>
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
                <h4 class="modal-title"><?= Yii::t('app', 'Update Kelengkapan Berkas Pengajuan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<?php
						if(isset($model->is_notaasli)){
							echo $form->field($model, 'is_notaasli',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
								->checkbox([],false)->label(Yii::t('app', 'Nota Asli'));
						}
						if(isset($model->is_kuitansi)){
							echo $form->field($model, 'is_kuitansi',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
								->checkbox([],false)->label(Yii::t('app', 'Kuitansi'));
						}
						if(isset($model->is_fakturpajak)){
							echo $form->field($model, 'is_fakturpajak',['template' => '{label}<div class="mt-checkbox-list col-md-1"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>
                                                                                                <div class="mt-checkbox-list col-md-6" id="place-no_fakturpajak" style="display:none;">'.
                                                                                                    yii\helpers\Html::activeTextInput($model, 'no_fakturpajak',['class'=>'form-control','placeholder'=>'Input No. Faktur Pajak']).
                                                                                                '</div>',])
								->checkbox([],false)->label(Yii::t('app', 'Faktur Pajak'));
						}
						if(isset($model->is_suratjalan)){
							echo $form->field($model, 'is_suratjalan',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])
								->checkbox([],false)->label(Yii::t('app', 'Surat Jalan'));
						}
						?>
						<?= yii\helpers\Html::activeHiddenInput($model, "keterangan_berkas"); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Ok'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'update()'
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
    console.log($('#". yii\helpers\Html::getInputId($model, 'is_fakturpajak')."'));
    if ('#". yii\helpers\Html::getInputId($model, 'is_fakturpajak')."') {
        $('#place-no_fakturpajak').show();
    }else{
        $('#place-no_fakturpajak').hide();
    }
	$('#".\yii\bootstrap\Html::getInputId($model, 'no_fakturpajak')."').inputmask({'mask': '999.999-99.999999999'});
", yii\web\View::POS_READY); ?>
<script>
$('#<?= yii\helpers\Html::getInputId($model, 'is_fakturpajak') ?>').change(function() {
    if(this.checked) {
        $("#place-no_fakturpajak").show();
    }else{
        $("#place-no_fakturpajak").hide();
    }
});
function update(){
	var pengajuan_tagihan_id = "<?= $model->pengajuan_tagihan_id ?>";
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/finance/tagihanbhp/updateBerkas','pengajuan_tagihan_id'=>'']); ?>'+pengajuan_tagihan_id+'&cash=<?= $cash ?>',
		type   : 'POST',
		data   : { formData: $("#form-transaksi").find('input').serialize()},
		success: function (data) {
			$("#modal-transaksi").modal('hide');
			if(data.html_berkas){
				$("#table-detail input[name*='[pengajuan_tagihan_id]'][value*='"+pengajuan_tagihan_id+"']").parents('tr').find('#place-berkas').html(data.html_berkas);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>