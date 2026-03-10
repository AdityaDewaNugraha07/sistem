<?php
app\assets\DatepickerAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\RepeaterAsset::register($this);
?>
<div class="modal fade" id="modal-customer-create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Tambahkan Customer Baru'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-customer-create',
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
                                <span class="input-group-btn" style="width: 50%">'.\yii\bootstrap\Html::activeDropDownList($model, 'cust_tipe_penjualan', \app\models\MDefaultValue::getOptionList('destinasi-penjualan'),['class'=>'form-control']).'</span> {error}</div>'])
                                ->dropDownList(\app\models\MDefaultValue::getOptionList('customer-jenis'),['class'=>'form-control','onchange'=>'setFormByJenisCust()'])->label("Jenis Customer"); ?>
                        <?= $form->field($model, 'cust_kode')->textInput(); ?>
                        <?= $form->field($model, 'cust_tanggal_join',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                        <?= $form->field($model, 'cust_is_pkp',[])->radioList(['Tidak','Ya'],false)->label('PKP'); ?>
                        <?= $form->field($model, 'cust_an_nama')->textInput([])->label("Nama Customer"); ?>
                        <?= $form->field($model, 'cust_pr_nama')->textInput([])->label("Nama Perusahaan"); ?>
                        <?= $form->field($model, 'cust_an_nik')->textInput(['class'=>'form-control numbers-only']); ?>
                        <?= $form->field($model, 'cust_an_jk')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-kelamin'),['class'=>'form-control']) ?>
                        <?= $form->field($model, 'cust_an_tgllahir',[
                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                        <?= $form->field($model, 'cust_an_nohp')->textInput(); ?>
                        <?= $form->field($model, 'cust_an_agama')->dropDownList(\app\models\MDefaultValue::getOptionList('agama'),['class'=>'form-control']) ?>
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
                            <?= $form->field($model, 'contact_person')->textInput(['placeholder'=>'Nama']); ?>
                        </div>
                        <?= $form->field($model, 'cust_no_npwp')->textInput(); ?>
                        <?= $form->field($model, 'cust_max_plafond_lama')->hiddenInput(['class'=>'form-control float'])->label(false); ?>
                        <?= $form->field($model, 'cust_max_plafond')->textInput(['class'=>'form-control float']); ?>
                        <div class="form-group" style="margin-top: 10px;">
                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Term of Payment (Hari)'); ?></label>
                            <div class="col-md-8">
                                <div class="repeater">
                                    <div data-repeater-list="<?= \yii\helpers\StringHelper::basename(get_class($modCustTop));  ?>">
                                        <?php 
                                        $modJenisProduk = \app\models\MDefaultValue::find()->where(['active'=>TRUE,'type'=>'jenis-produk'])->all();
                                        
                                        if(count($modJenisProduk)>0){
                                            foreach($modJenisProduk as $i => $jenisproduk){ ?>
                                            <div data-repeater-item style="display: block;">
                                                <span class="input-group-btn" style="width: 60%">
                                                    <?php echo \yii\bootstrap\Html::activeDropDownList($modCustTop, 'custtop_jns', \app\models\MDefaultValue::getOptionList('jenis-produk'),
                                                            ['class'=>'form-control','prompt'=>'','onblur'=>'setItemRequiredTOP()','value'=>$jenisproduk->value]) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 25%">
                                                    <?php echo \yii\bootstrap\Html::activeTextInput($modCustTop, 'custtop_top', ['class'=>'form-control numbers-only','placeholder'=>'Hari','onblur'=>'setItemRequiredTOP()','oninput'=>'cekMaxTOP(this)','value'=>7]) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%" id="remove-btn">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-danger"><i class="fa fa-close"></i></a>
                                                </span>
                                            </div>
                                        <?php } ?>
                                        <input type="hidden" id="total-jenis-produk" value="<?= count($modJenisProduk); ?>">
                                        <?php } ?>
                                    </div>
                                    <a href="javascript:;" data-repeater-create class="btn btn-xs btn-info mt-repeater-add" style="margin-top: 5px;">
                                        <i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah TOP'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                echo $form->field($model, 'cust_file_ktp',[
                                    'template'=>'{label}
                                        <div class="col-md-8">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                    <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                <div>
                                                    <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                        <span class="fileinput-new"> Select Image </span>
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
                                                <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                    <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                <div>
                                                    <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                        <span class="fileinput-new"> Select Image </span>
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
                                        <div class="col-md-8" style="margin-left: -20px">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                    <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                <div>
                                                    <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                        <span class="fileinput-new"> Select Image </span>
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
                        </div>
                        <div class="row">
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
                    'onclick'=>'submitformajax(this,"$(\'#modal-customer-create\').modal(\'hide\'); $(\'#table-customer\').dataTable().fnClearTable();")'
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
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/customer/setDropdownJenisProdukTOP']); ?>',
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