<div class="modal fade" id="modal-closespp" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi2',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-6">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal2" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title text-align-center"><?= Yii::t('app', "Ganti Status SPP"); ?> </h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-1">
					</div>
					<div class="col-md-10">
						<div class="form-group col-md-12">
                            <?php echo $form->field($model, 'status')->dropDownList(['0'=>$currentstatus,'1'=>'CLOSED'],['class'=>'form-control','onchange'=>'setKeterangan(this.value)']); ?>
                        </div>
						<div class="form-group col-md-12">
                            <?php echo $form->field($model, 'status_closed')->textarea()->label("Alasan"); ?>
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
$('#".\yii\bootstrap\Html::getInputId($model, "status")."').val('".( !empty($model->status_closed)?"1":"0" )."');
setKeterangan(".( !empty($model->status_closed)?"1":"0" ).");
", yii\web\View::POS_READY); ?>
<script>
function setKeterangan(status){
	if(status=='0'){
		$("#<?= \yii\bootstrap\Html::getInputId($model, "status_closed") ?>").val('');
		$("#<?= \yii\bootstrap\Html::getInputId($model, "status_closed") ?>").attr('disabled','disabled');
	}else{
		$("#<?= \yii\bootstrap\Html::getInputId($model, "status_closed") ?>").removeAttr('disabled','disabled');
	}
}

function save(ele){
    var $form = $('#form-transaksi2');
    if(formrequiredvalidate($form)){
		if(validatingDetail()){
            submitformajax(ele,"$(\'#close-btn-modal2\').removeAttr(\'disabled\'); $(\'#close-btn-modal2\').trigger(\'click\'); getItems();");
        }
    }
    return false;
}
function validatingDetail(){
	var status = $("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val();
	if(status=='1'){
		var has_error = 0;
		var field1 = $("#modal-closespp").find('textarea[name*="[status_closed]"]');
		if(!field1.val()){
			$("#modal-closespp").find('textarea[name*="[status_closed]"]').parents('.form-group').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$("#modal-closespp").find('textarea[name*="[status_closed]"]').parents('.form-group').removeClass('error-tb-detail');
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