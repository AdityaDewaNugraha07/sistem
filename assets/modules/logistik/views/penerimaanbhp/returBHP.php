<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-retur',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-7">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Retur Pembelian Bahan Pembantu dari Penerimaan '); ?><b><?= $model->terimabhp_kode; ?></b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<?php echo yii\bootstrap\Html::activeHiddenInput($model, 'terima_bhpd_id',['readonly'=>'readonly']); ?>
						<?php 
						if(!isset($_GET['retur_bhp_id'])){
							echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
						}else{ ?>
							<div class="form-group">
								<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
								<div class="col-md-7" style="padding-bottom: 5px;">
									<span class="input-group-btn" style="width: 90%">
										<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
									</span>
									<span class="input-group-btn" style="width: 10%">
										<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
											<i class="icon-paper-clip"></i>
										</a>
									</span>
								</div>
							</div>
						<?php } ?>
						<?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
						<?php echo $form->field($model, 'bhp_nm')->textInput(['readonly'=>'readonly','style'=>'font-size:1.1rem'])->label("Nama Item"); ?>
						<?php echo $form->field($model, 'deskripsi')->textarea(['style'=>'font-size:1.2rem'])->label("Keterangan Retur"); ?>
                    </div>
					<div class="col-md-6">
						<?php echo $form->field($modTerimaDetail, 'terimabhpd_harga')->textInput(['class'=>'form-control float','readonly'=>'readonly'])->label("Harga Terima"); ?>
						<?php echo $form->field($model, 'potongan')->textInput(['class'=>'form-control float','onblur'=>'setTotalKembali()'])->label("Potongan"); ?>
						<?php echo $form->field($model, 'harga')->textInput(['class'=>'form-control float','readonly'=>'readonly'])->label("Harga Retur"); ?>
						<?php echo $form->field($model, 'qty')->textInput(['class'=>'form-control float','style'=>'width:80px;','onblur'=>'checkqtyretur(); setTotalKembali();'])->label("Qty Retur"); ?>
						<?php echo yii\bootstrap\Html::activeHiddenInput($modTerimaDetail, 'terimabhpd_qty',['readonly'=>'readonly']); ?>
						<?php echo $form->field($model, 'total_kembali')->textInput(['class'=>'form-control float','readonly'=>'readonly'])->label("Total Kembali"); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <?php // echo \yii\helpers\Html::button(Yii::t('app', 'Batalkan'),['class'=>'btn red btn-outline ciptana-spin-btn','onclick'=>"submitformajax(this)"]); ?>
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Simpan'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
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
setTotalKembali();
", yii\web\View::POS_READY); ?>
<script>
function checkqtyretur(){
	var qty_terima = unformatNumber( $('#modal-transaksi').find('input[name*="[terimabhpd_qty]"]').val() );
	var qty_retur = unformatNumber( $('#modal-transaksi').find('input[name*="[qty]"]').val() );
	if(qty_retur > qty_terima){
		$('#modal-transaksi').find('input[name*="[qty]"]').val(qty_terima);
		return false;
	}
	if(qty_retur < 0){
		$('#modal-transaksi').find('input[name*="[qty]"]').val(0);
		return false;
	}
}

function setTotalKembali(){
	var total_terima = 0;
	var harga_terima = $('#modal-transaksi').find('input[name*="[terimabhpd_harga]"]').val();
	var potongan = $('#modal-transaksi').find('input[name*="[potongan]"]').val();
	var harga_retur = $('#modal-transaksi').find('input[name*="[harga]"]').val();
	var qty_retur = $('#modal-transaksi').find('input[name*="[qty]"]').val();
	harga_retur = (unformatNumber(harga_terima)-unformatNumber(potongan));
	total_terima = harga_retur * qty_retur;
	
	$('#modal-transaksi').find('input[name*="[harga]"]').val( formatNumberForUser(harga_retur) );
	$('#modal-transaksi').find('input[name*="[total_kembali]"]').val( formatNumberForUser(total_terima) );
}
</script>