<?php app\assets\RepeaterAsset::register($this); ?>
<style>
.note-editable p{
	margin: 0px;
}
</style>
<div class="modal fade" id="modal-input" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				
                <h4 class="modal-title"><?= Yii::t('app', 'Update Data BL dari Invoice : <b>'.$model->nomor.'</b>'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-input',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <?= $form->field($model, 'bl_no')->textInput(); ?>
                        <?= $form->field($model, 'bl_tanggal',[
                                                    'template'=>'{label}<div class="col-md-4"><div class="input-group date date-picker">{input} <span class="input-group-btn">
                                                                 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                        <?= $form->field($model, 'penerbit_bl_id')->dropDownList(\app\models\MPenerbitBl::getOptionList(),['prompt'=>'']); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Update'),['class'=>'btn blue btn-outline ciptana-spin-btn',
                    'onclick'=>'saveBL(this);'
                    ]);
				?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    formconfig();
	$('.repeater').repeater({
        show: function () {
            $(this).slideDown();
            $('div[data-repeater-item][style=\"display: none;\"]').remove();
        },
        hide: function (e) {
            $(this).slideUp(e);
        },
    });
    
", yii\web\View::POS_READY); ?>
<script type="text/javascript">
function saveBL(ele){
    var $form = $('#form-input');
    if(validatingDetail(ele)){
        submitformajax(ele);
    }
    return false;
}

function validatingDetail(ele){
    var has_error = 0;
    var field1 = $(ele).parents('.modal-content').find('input[name*="[bl_no]"]');
    var field2 = $(ele).parents('.modal-content').find('input[name*="[bl_tanggal]"]');
    var field3 = $(ele).parents('.modal-content').find('select[name*="[penerbit_bl_id]"]');
    if(!field1.val()){
        $(ele).parents('.modal-content').find('input[name*="[bl_no]"]').addClass('error-tb-detail');
        has_error = has_error + 1;
    }else{
        $(ele).parents('.modal-content').find('input[name*="[bl_no]"]').removeClass('error-tb-detail');
    }
    if(!field2.val()){
        $(ele).parents('.modal-content').find('input[name*="[bl_tanggal]"]').addClass('error-tb-detail');
        has_error = has_error + 1;
    }else{
        $(ele).parents('.modal-content').find('input[name*="[bl_tanggal]"]').removeClass('error-tb-detail');
    }
    if(!field3.val()){
        $(ele).parents('.modal-content').find('select[name*="[penerbit_bl_id]"]').addClass('error-tb-detail');
        has_error = has_error + 1;
    }else{
        $(ele).parents('.modal-content').find('select[name*="[penerbit_bl_id]"]').removeClass('error-tb-detail');
    }
    if(has_error === 0){
        return true;
    }
    return false;
}
</script>