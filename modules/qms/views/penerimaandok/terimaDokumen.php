<div class="modal fade" id="modal-penerimaan" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi2',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-6">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-5 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal2" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title text-align-center"><?= Yii::t('app', "Terima Dokumen " . $modDokRev->nama_dokumen); ?> </h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-10">
						<div class="form-group col-md-12">
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-md-5 control-label"><label>Nama Dokumen</label></div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="<?= $modDokRev->nama_dokumen; ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-md-5 control-label"><label>Revisi Ke</label></div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="<?= $modDokRev->revisi_ke; ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-md-5 control-label"><label>Jenis Dokumen</label></div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="<?= $modDokumen->jenis_dokumen; ?>" disabled>
                                </div>
                            </div>
                        </div>
						<div class="form-group col-md-12">
                            <?php echo $form->field($model, 'catatan_penerimaan')->textarea()->label("Catatan Penerimaan"); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Saya menyatakan sudah menerima dan membacanya'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'save(this)'
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
function save(ele){
    var $form = $('#form-transaksi2');
    
    if(formrequiredvalidate($form)){
		if(validatingDetail()){
            submitformajax(ele,"$(\'#close-btn-modal2\').removeAttr(\'disabled\'); $(\'#close-btn-modal2\').trigger(\'click\'); refreshTable();");
        }
    }
    return false;
}

function validatingDetail(){
	var has_error = 0;
    var field1 = $("#<?= \yii\bootstrap\Html::getInputId($model, "catatan_penerimaan") ?>").val();

    if(!field1){
        $("#modal-penerimaan").find('textarea[name*="[catatan_penerimaan]"]').parents('.form-group').addClass('error-tb-detail');
		has_error = has_error + 1;
    } else {
        $("#modal-penerimaan").find('textarea[name*="[catatan_penerimaan]"]').parents('.form-group').removeClass('error-tb-detail');
    }

    if(has_error === 0){
		return true;
	}
	return false;
}

function refreshTable(){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/qms/penerimaandok/refreshTable']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data) {
            $('#table-list tbody').html(data);
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
</script>