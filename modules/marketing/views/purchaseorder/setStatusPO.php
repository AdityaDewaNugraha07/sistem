<div class="modal fade" id="modal-status-po" tabindex="-1" role="basic" aria-hidden="true">
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
                <h4 class="modal-title text-align-center"><?= Yii::t('app', "Ganti Status PO"); ?> </h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-10">
						<div class="form-group col-md-12">
                            <?php echo $form->field($model, 'status_po')->dropDownList(['0'=>'CLOSED', '1'=>'OPEN'],['class'=>'form-control', 'onchange'=>'setKeterangan()', 'options' => [$model->status_po => ['Selected' => true]] ]); ?>
                        </div>
						<div class="form-group col-md-12">
                            <?php echo $form->field($model, 'close_po')->textarea()->label("Alasan"); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
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
setKeterangan();
", yii\web\View::POS_READY); ?>

<script>
function setKeterangan(){
    var status =  $("#<?= \yii\bootstrap\Html::getInputId($model, "status_po") ?>").val();
    if(status == 1){
        $("#<?= \yii\bootstrap\Html::getInputId($model, "close_po") ?>").attr("disabled", "disabled");
        $("#<?= \yii\bootstrap\Html::getInputId($model, "close_po") ?>").val(null);
    } else {
        var alasan = '<?= $model->close_po; ?>';
        $("#<?= \yii\bootstrap\Html::getInputId($model, "close_po") ?>").removeAttr("disabled");
        $("#<?= \yii\bootstrap\Html::getInputId($model, "close_po") ?>").val(alasan);
    }
}

function save(ele){
    var $form = $('#form-transaksi2');
    
    if(formrequiredvalidate($form)){
		if(validatingDetail()){
            submitformajax(ele,"$(\'#close-btn-modal2\').removeAttr(\'disabled\'); $(\'#close-btn-modal2\').trigger(\'click\'); daftarAfterSave();");
        }
    }
    return false;
}

function validatingDetail(){
	var status = $("#<?= \yii\bootstrap\Html::getInputId($model, "status_po") ?>").val();
	if(status=='0'){
		var has_error = 0;
		var field1 = $("#modal-status-po").find('textarea[name*="[close_po]"]');
		if(!field1.val()){
			$("#modal-status-po").find('textarea[name*="[close_po]"]').parents('.form-group').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$("#modal-status-po").find('textarea[name*="[close_po]"]').parents('.form-group').removeClass('error-tb-detail');
		}
		if(has_error === 0){
			return true;
		}
		return false;
	}else{
		return true;
	}
    
}

</script>