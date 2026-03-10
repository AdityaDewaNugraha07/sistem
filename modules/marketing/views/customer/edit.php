<?php

use app\models\MDefaultValue;
use yii\helpers\Url;

app\assets\DatepickerAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\RepeaterAsset::register($this);
?>
<div class="modal fade" id="modal-customer-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit Data Customer'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-customer-edit',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'customer_jenis',['template'=>'{label}<div class="col-md-8">
                                <span class="input-group-btn" style="width: 50%">{input}</span> 
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($model, 'cust_tipe_penjualan', MDefaultValue::getOptionList('destinasi-penjualan'),['class'=>'form-control']).'</span> {error}</div>'])
                                ->dropDownList(MDefaultValue::getOptionList('customer-jenis'),['class'=>'form-control','onchange'=>'setFormByJenisCust()'])->label("Jenis Customer"); ?>
                        <?= $form->field($model, 'cust_kode')->textInput(); ?>
                        <?= $form->field($model, 'cust_tanggal_join',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                        <?= $form->field($model, 'cust_is_pkp',[])->radioList(['Tidak','Ya'],false)->label('PKP'); ?>
                        <?= $form->field($model, 'cust_an_nama')->textInput()->label("Nama Customer"); ?>
                        <?= $form->field($model, 'cust_pr_nama')->textInput()->label("Nama Perusahaan"); ?>
                        <?= $form->field($model, 'cust_an_nik')->textInput(['class'=>'form-control numbers-only']); ?>
                        <?= $form->field($model, 'cust_an_jk')->dropDownList(MDefaultValue::getOptionList('jenis-kelamin'),['class'=>'form-control']) ?>
                        <?= $form->field($model, 'cust_an_tgllahir',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                        <?= $form->field($model, 'cust_an_nohp')->textInput(); ?>
                        <?= $form->field($model, 'cust_an_agama')->dropDownList(MDefaultValue::getOptionList('agama'),['class'=>'form-control']) ?>
                        <?= $form->field($model, 'cust_an_alamat')->textarea() ?>
                        <?= $form->field($model, 'cust_an_email')->textInput() ?>
                    </div>
                    <div class="col-md-4">
                        <div class="identitas-input" style="display:none">
                            <?= $form->field($model, 'cust_pr_direktur')->textInput(); ?>
                            <?= $form->field($model, 'cust_pr_alamat')->textarea() ?>
                            <?= $form->field($model, 'cust_pr_phone')->textInput(); ?>
                            <?= $form->field($model, 'cust_pr_fax')->textInput(['class'=>'form-control numbers-only']); ?>
                            <?= $form->field($model, 'cust_pr_email')->textInput(); ?>
                        </div>
                        <?= $form->field($model, 'cust_no_npwp')->textInput(); ?>
                        <?= $form->field($model, 'cust_max_plafond')->textInput(['class'=>'form-control float']); ?>
                        <?= $form->field($model, 'cust_max_plafond_lama')->hiddenInput(['class'=>'form-control float','value'=>$model->cust_max_plafond])->label(false); ?>
                        <?= $form->field($model, 'active',['template' => '{label}<div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>',])->checkbox([],false)->label(Yii::t('app', 'Active')); ?>
                        <?php /* <?= $form->field($model, 'status_approval')->textInput(['class'=>'form-control', 'style'=>'background-color: #f00; color: #fff; opacity: 0.5;', 'disabled' => true]); ?> */?>
                        <?php
                        if ($model->status_approval == "REJECTED") {
                            $color = "#F00";
                        } else if ($model->status_approval == "APPROVED") {
                            $color = "#38C68B";
                        } else {
                            $color = "#000";
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Status Approval'); ?></label>
                            <div class="col-md-8" styl="padding-top: 10px;">
                                <font style="color: <?php echo $color;?>; font-weight: bold;"><?php echo $model->status_approval;?></font>
                            </div>
                        </div>
                        <?php
                        ?>
                        <div class="form-group" style="margin-top: 10px;">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Term of Payment (Hari)'); ?></label>
                            <div class="col-md-8">
                                <div class="repeater">
                                    <div data-repeater-list="<?= \yii\helpers\StringHelper::basename(get_class($modCustTop));  ?>">
                                        <?php
                                        $modJenisProduk = MDefaultValue::find()->where(['active'=>TRUE,'type'=>'jenis-produk'])->all();

                                        if(count($modCustTops)>0){
                                            foreach($modCustTops as $i => $custtop) { ?>
                                            <div data-repeater-item style="display: block;">
                                                <span class="input-group-btn" style="width: 170px;">
                                                    <?php echo \yii\bootstrap\Html::activeDropDownList($modCustTop, 'custtop_jns', MDefaultValue::getOptionList('jenis-produk'),
                                                            ['class'=>'form-control','prompt'=>'','onblur'=>'setItemRequiredTOP()','value'=>$custtop->custtop_jns]) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 40px;">
                                                    <?php echo \yii\bootstrap\Html::activeTextInput($modCustTop, 'custtop_top', 
                                                            ['class'=>'form-control numbers-only', 'placeholder'=>'Hari','onblur'=>'setItemRequiredTOP()','oninput'=>'cekMaxTOP(this)','value'=>$custtop->custtop_top]) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 40px" id="remove-btn">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-danger"><i class="fa fa-close"></i></a>
                                                </span>
                                            </div>
                                            <?php } ?>
                                        <?php }else{ ?>
                                            <div data-repeater-item style="display: none;">
                                                <span class="input-group-btn" style="width: 170px;">
                                                    <?php echo \yii\bootstrap\Html::activeDropDownList($modCustTop, 'custtop_jns', MDefaultValue::getOptionList('jenis-produk'),
                                                            ['class'=>'form-control','prompt'=>'','onblur'=>'setItemRequiredTOP()']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 40px;">
                                                    <?php echo \yii\bootstrap\Html::activeTextInput($modCustTop, 'custtop_top', 
                                                            ['class'=>'form-control numbers-only','placeholder'=>'Hari','onblur'=>'setItemRequiredTOP()','oninput'=>'cekMaxTOP(this)']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 130px;">
                                                    <?php echo \yii\bootstrap\Html::activeTextInput($modCustTop, 'status_approval', 
                                                            ['class'=>'form-control text-right', 'style'=>'color: '.$color, 'placeholder'=>'','onblur'=>'setItemRequiredTOP()','oninput'=>'cekMaxTOP(this)','value'=>$custtop->status_approval, 'disabled'=>true]) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 40px;" id="remove-btn">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-danger"><i class="fa fa-close"></i></a>
                                                </span>
                                            </div>
                                        <?php } ?>

                                        <?php
                                        if(count($modCustTops)>0){
                                            foreach($modCustTops as $i => $custtop) { ?>
                                                    <input type="hidden" name="MCustTopLama[<?php echo $i;?>][custtop_jns]" value="<?php echo $custtop->custtop_jns;?>" class="form-control">
                                                    <input type="hidden" name="MCustTopLama[<?php echo $i;?>][custtop_top]" value="<?php echo $custtop->custtop_top;?>" class="form-control">
                                        <?php } ?>
                                        <?php } else { ?>
                                                    <input type="hidden" name="MCustTopLama[<?php echo $i;?>][custtop_jns]" value="<?php echo $custtop->custtop_jns;?>">
                                                    <input type="hidden" name="MCustTopLama[<?php echo $i;?>][custtop_top]" value="<?php echo $custtop->custtop_top;?>" class="form-control">
                                        <?php 
                                        }
                                        ?>

                                        <input type="hidden" id="total-jenis-produk" value="<?= count($modJenisProduk); ?>">
                                    </div>
                                    <a href="javascript:;" data-repeater-create class="btn btn-xs btn-info mt-repeater-add" style="margin-top: 5px;">
                                        <i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah TOP'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <?= $form->field($model, 'cust_pr_direktur')->textInput()->label("Nama Direktur"); ?>
                            <?= $form->field($model, 'cust_pr_alamat')->textInput()->label("Alamat Perusahaan"); ?>
                            <?= $form->field($model, 'cust_pr_phone')->textInput()->label("Telp Perusahaan"); ?>
                            <?= $form->field($model, 'cust_pr_fax')->textInput()->label("Fax Perusahaan"); ?>
                            <?= $form->field($model, 'cust_pr_email')->textInput()->label("Email Perusahaan"); ?>
                            <?= $form->field($model, 'contact_person')->textInput()->label("CP Perusahaan"); ?>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-6">
                            <?php 
                            echo $form->field($model, 'cust_file_ktp',[
                                'template'=>'{label}
                                    <div class="col-md-8">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail">
                                                <img src="'.(!empty($model->cust_file_ktp)?
                                                Url::base().'/uploads/mkt/customer/'.$model->cust_file_ktp :
                                                Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png').'" alt="" /> 
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                            <div>
                                                <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                    <span class="fileinput-new"> Select image </span>
                                                    <span class="fileinput-exists"> Change </span>
                                                    {input} 
                                                </span> 
                                                <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                {error}
                                            </div>
                                        </div>
                                    </div>'
                            ])->fileInput();
                            echo $form->field($model, 'cust_file_npwp',[
                                'template'=>'{label}
                                    <div class="col-md-8">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail">
                                                <img src="'.(!empty($model->cust_file_npwp)?
                                                Url::base().'/uploads/mkt/customer/'.$model->cust_file_npwp :
                                                Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png').'" alt="" /> 
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                            <div>
                                                <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                    <span class="fileinput-new"> Select image </span>
                                                    <span class="fileinput-exists"> Change </span>
                                                    {input} 
                                                </span> 
                                                <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                {error}
                                            </div>
                                        </div>
                                    </div>'
                            ])->fileInput();
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            echo $form->field($model, 'cust_file_photo',[
                                'template'=>'{label}
                                    <div class="col-md-8">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail">
                                                <img src="'.(!empty($model->cust_file_photo)?
                                                Url::base().'/uploads/mkt/customer/'.$model->cust_file_photo :
                                                Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png').'" alt="" /> 
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                            <div>
                                                <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                    <span class="fileinput-new"> Select image </span>
                                                    <span class="fileinput-exists"> Change </span>
                                                    {input} 
                                                </span> 
                                                <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                {error}
                                            </div>
                                        </div>
                                    </div>'
                            ])->fileInput();
                            ?>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12" style="margin-top: 20px;">
                                YANG HARUS DIUPLOAD: 
                                <ol>
                                    <li>KTP <font style="color: red; font-weight: bold; font-style: underline;">DAN ATAU</font> NPWP</li>
                                    <li>FOTO <font style="color: red; font-weight: bold; font-style: underline;">WAJIB</font> DIUPLOAD !</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#modal-customer-edit\').modal(\'hide\'); $(\'#table-customer\').dataTable().fnClearTable();")'])?>
            </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    formconfig();
    setFormByJenisCust();
//	$('#".\yii\bootstrap\Html::getInputId($model, 'cust_no_npwp')."').inputmask({'mask': '99.999.999.9-999.999'});
	$('.repeater').repeater({
        show: function () {
            if(setItemRequiredTOP()){
                setDropdownJenisProdukTOP();
                $(this).slideDown();
                setTimeout(function(){
                    setFieldTombolTOP();
                }, 500);
            }
            $('div[data-repeater-item][style=\"display: none;\"]').remove();
        },
        hide: function (e) {
            setDropdownJenisProdukTOP();
            $(this).slideUp(e);
            setTimeout(function(){
                setFieldTombolTOP();
            }, 500);
        },
    });
    setFieldTombolTOP();
", yii\web\View::POS_READY); ?>

<script type="text/javascript">
function setFormByJenisCust(){
    var jeniscustomer = $('#<?= yii\bootstrap\Html::getInputId($model, 'customer_jenis'); ?>').val();
    $('div.identitas-input').addClass('animation-loading');
    if(jeniscustomer != 'Perorangan'){
        $('.field-mcustomer-cust_pr_nama').show();
        setTimeout(function() {
            $('div.identitas-input').css('display','block');
            $('div.identitas-input').removeClass('animation-loading');
        }, 300);
    }else{
        $('.field-mcustomer-cust_pr_nama').hide();
        setTimeout(function() {
            $('div.identitas-input').css('display','none');
            $('div.identitas-input').removeClass('animation-loading');
        }, 300);
    }
    return false;
}

function setItemRequiredTOP(){
    var list_jenis_terisi = true;
    var list_top_terisi = true;
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        var list_jenis = $(this).find('select[name*="[custtop_jns]"]').val();
        if(list_jenis){
            list_jenis_terisi &= true;
            $(this).find('select[name*="[custtop_jns]"]').removeAttr('style');
        }else{
            list_jenis_terisi &= false;
            $(this).find('select[name*="[custtop_jns]"]').attr('style','border-color: #e73d4a;');
        }
        var list_top = $(this).find('input[name*="[custtop_top]"]').val();
        if(list_top){
            list_top_terisi &= true;
            $(this).find('input[name*="[custtop_top]"]').removeAttr('style');
        }else{
            list_top_terisi &= false;
            $(this).find('input[name*="[custtop_top]"]').attr('style','border-color: #e73d4a;');
        }
    });
    return (list_jenis_terisi && list_top_terisi);
}

function cekMaxTOP(ele){
    if(ele.value > 90){
        $(ele).val('90');
    }
}

function setDropdownJenisProdukTOP(){
    var arr_list_jenis = [];
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        var list_jenis = $(this).find('select[name*="[custtop_jns]"]').val();
        arr_list_jenis[index] = list_jenis;
    });
    $.ajax({
        url    : '<?= Url::toRoute(['/marketing/customer/setDropdownJenisProdukTOP']); ?>',
        type   : 'POST',
        data   : {list_jenis:arr_list_jenis},
        success: function (data) {
            if(data.html){
                $('div[data-repeater-item][style!="display: none;"]:last').find('select[name*="[custtop_jns]"]').html(data.html);
                $('div[data-repeater-item][style!="display: none;"]:last').find('input[name*="[custtop_top]"]').val('7');
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
	return false;
}

function setFieldTombolTOP(){
    if( $('#total-jenis-produk').val() == $('div[data-repeater-item][style!="display: none;"]').length ){
        $('.mt-repeater-add').attr('style','visibility:hidden; margin-top: 5px');
    }else{
        $('.mt-repeater-add').attr('style','visibility:visible; margin-top: 5px');
    }
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        $(this).find('select[name*="[custtop_jns]"]').attr('readonly','readonly');
        $(this).find('input[name*="[custtop_top]"]').prop('readonly', true);
        $(this).find('#remove-btn').css('visibility','hidden');
    });
    $('div[data-repeater-item][style!="display: none;"]:last').find('select[name*="[custtop_jns]"]').removeAttr('readonly');
    $('div[data-repeater-item][style!="display: none;"]:last').find('input[name*="[custtop_top]"]').prop('readonly', false);
    $('div[data-repeater-item][style!="display: none;"]:last').find('#remove-btn').css('visibility','visible');
}
</script>