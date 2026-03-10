<?php
app\assets\Select2Asset::register($this);
?>
<div class="modal fade" id="modal-useraccess-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Add New User Access For : <b>').$modUserGroup->name.'</b>'; ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-useraccess-create',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo yii\helpers\Html::activeHiddenInput($model, 'user_group_id'); ?>
                        <?php echo $form->field($model, 'module_id')->dropDownList(\app\models\MModule::getOptionList(),['class'=>'form-control','prompt'=>'','onchange'=>'setDropdownMenu()']) ?>
                        <?php echo $form->field($model, 'menu_group_id')->dropDownList(\app\models\MMenuGroup::getOptionList(),['class'=>'form-control','prompt'=>'','onchange'=>'setDropdownMenu()']) ?>
                        <?php echo $form->field($model, 'menu_id',['template'=>'{label}<div class="col-md-7">
                            <span class="input-group-btn" style="width: 50%">{input}</span> 
                            <span class="input-group-btn" style="width: 50%">
                            <label class="mt-checkbox mt-checkbox-outline" style="margin-left: 10px; display:none;"> Pilih Semua
                                <input type="checkbox" value="1" name="selectAll" id="selectAll" /><span></span>
                            </label>
                            </span> {error}</div>'])
                            ->dropDownList([],['class'=>'form-control select2-multiple','multiple'=>'','name'=>'MUserAccess[menu_id][]']); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Add'),['class'=>'btn hijau btn-outline',
                    'onclick'=>'submitformajax(this,"$(\'#modal-useraccess-create\').modal(\'hide\'); setUserGroup('.$modUserGroup->user_group_id.')")']) ?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    $('#".\yii\bootstrap\Html::getInputId($model, 'menu_id')."').select2({
        placeholder: 'Select Menu',
        width: null
    });
//    .on('select2:select', function(event) {
//        var value = $(event.currentTarget).find('option:selected').val();
//        console.log(value);
//        if(value == 'pilihsemua'){
//            selectAllOption()
//        }
//    });
    
    $('#selectAll').click(function(){
        var check = $(this).prop('checked');
        if(check == true) {
            selectAllOption()
        } else {
            deselectAllOption()
        }
    });

", yii\web\View::POS_READY); ?>
<script type="text/javascript">
function setDropdownMenu(){
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'menu_id') ?>').parent('span').addClass('animation-loading');
    var user_group_id = <?= $modUserGroup->user_group_id; ?>;
    var menu_group_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'menu_group_id') ?>').val();
    var module_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'module_id') ?>').val();
    $('#selectAll').prop('checked', false);
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/sysadmin/useraccess/setDropdownMenu']); ?>',
		type   : 'POST',
		data   : {menu_group_id:menu_group_id,user_group_id:user_group_id,module_id:module_id},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'menu_id') ?>").html(data.html);
            $('#<?= \yii\bootstrap\Html::getInputId($model, 'menu_id') ?>').parent('span').removeClass('animation-loading');
            if(data.result_total > 0){
                $("#selectAll").parent('label').css("display","block");
            }else{
                $("#selectAll").parent('label').css("display","none");
            }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function selectAllOption(){
    deselectAllOption();
    // Select All
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'menu_id')?>').find('option[id]').prop('selected',true);
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'menu_id')?>').trigger('change');
}
function deselectAllOption(){
    // Deselect All
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'menu_id')?>').find('option').prop('selected',false);
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'menu_id')?>').trigger('change');
}
</script>