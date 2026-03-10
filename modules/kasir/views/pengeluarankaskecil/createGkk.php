<?php app\assets\DatepickerAsset::register($this); ?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-8">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', $pesan); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<?= $form->field($model, 'kode')->textInput(['style'=>'width:200px;','readonly'=>true])->label(Yii::t('app', 'Kode GKK')); ?>
						<?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
								<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                    </div>
					<div class="col-md-6">
						<?= $form->field($model, 'penerima')->textInput(['style'=>'width:200px;','readonly'=>true])->label(Yii::t('app', 'Penerima')); ?>
						<div class="form-group" id="tbp" style="">
							<label class="col-md-4 control-label"><?= Yii::t('app', 'TBP Terkait'); ?></label>
							<div class="col-md-8" style="padding-bottom: 5px; margin-top: 5px;" id="place-tbp">
								<?php
								$tbplabel = "";
								if(!empty($model->tbp_reff)){
									foreach(explode(",", $model->tbp_reff) as $i => $tbp){
										$modTBP = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>$tbp]);
										$tbplabel .= "<a onclick='infoTBP(".$modTBP->terima_bhp_id.")'>".$tbp."</a><br>";
									}
									echo $tbplabel;
								}else{
									echo "<a onclick='pickPanelTBP()' class='btn btn-xs btn-outline blue-steel'><i class='fa fa-plus'></i> Add TBP</a>";
								}
								?>
							</div>
							<?= yii\bootstrap\Html::activeHiddenInput($model, 'tbp_reff'); ?>
						</div>
					</div>
                </div>
				<br>
				<div class="row">
					<div class="col-md-5" style="margin-left: 20px;">
						<h5><?= Yii::t('app', 'Rincian GKK'); ?></h5>
					</div>
				</div>
				<div class="row">
					<div class="col-md-11" style="margin-left: 20px;">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
								<thead>
									<tr>
										<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
										<th><?= Yii::t('app', 'Deskripsi'); ?></th>
										<th style="width: 100px; "><?= Yii::t('app', 'Nominal'); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1.</td>
										<td>
											<?= \yii\helpers\Html::activeTextarea($model, 'deskripsi',['class'=>'form-control','style'=>'height:40px;']) ?>
										</td>
										<td class="text-align-right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modKasBon->nominal) ?></td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2" class="text-align-right">Total &nbsp;</td>
										<td class="td-kecil text-align-right td-kecil"><?php echo \yii\bootstrap\Html::activeTextInput($model, 'totalnominal', ['class'=>'form-control float','readonly'=>'readonly','style'=>'width: 100px; padding:3px;']) ?></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
            </div>
            <div class="modal-footer text-align-center">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#close-btn-modal\').removeAttr(\'disabled\'); $(\'#close-btn-modal\').trigger(\'click\'); $(\'#table-laporan\').dataTable().fnClearTable();")'
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
<script>
function pickPanelTBP(){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/pickPanelTBP']); ?>';
	$(".modals-place-2").load(url, function() {
		$("#modal-tbp").modal('show');
		$("#modal-tbp").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
function pickingTBP(){
	var picked = $('#select_data').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/pickTBP']); ?>',
		type   : 'POST',
		data   : {picked:picked},
		success: function (data) {
			$('#<?= yii\bootstrap\Html::getInputId($model, 'tbp_reff') ?>').val(data.kodeterima);
			$('#place-tbp').html(data.kodelabelterima);
			$("#modal-tbp").find('.fa-close').trigger('click');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function infoTBP(terima_bhp_id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp','id'=>'']); ?>'+terima_bhp_id;
	$(".modals-place-2").load(url, function() {
		$("#modal-info-tbp").modal('show');
		$("#modal-info-tbp").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
</script>