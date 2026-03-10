<?php
/* @var $this yii\web\View */
$this->title = 'Loglist';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\RepeaterAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Loglist'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Loglist'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Loglist'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= yii\helpers\Html::activeHiddenInput($model, "loglist_id"); ?>
                                        <?= $form->field($model, 'loglist_kode')->textInput(['style'=>'font-weight:bold']); ?>
										<?= $form->field($model, 'tanggal',[
                                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'tongkang')->textInput(); ?>
										
                                        <?php if(isset($_GET['edit'])){ ?>
                                            <div class="form-group" style="margin-top: 10px;">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Grader'); ?></label>
                                                <div class="col-md-8">
                                                    <div class="repeater">
                                                        <div data-repeater-list="TLoglist">
                                                            <?php
                                                            if(count($modDkg)>0){
                                                                foreach($modDkg as $i => $dkg){ 
                                                                    $model->grader_id = $dkg->dkg_id;
                                                                ?>
                                                                    <div data-repeater-item style="display: block;">
                                                                        <span class="input-group-btn" style="width: 60%">
                                                                            <?php echo \yii\bootstrap\Html::activeDropDownList($model, 'grader_id', \app\models\TDkg::getOptionListGraderEdit($dkg->dkg_id),['class'=>'form-control','prompt'=>'']) ?>
                                                                        </span>
                                                                        <span class="input-group-btn" style="width: 20%" id="remove-btn">
                                                                            <a href="javascript:;" data-repeater-delete class="btn btn-danger"><i class="fa fa-close"></i></a>
                                                                        </span>
                                                                    </div>
                                                                <?php } ?>
                                                                <input type="hidden" id="total-item-repeater" value="<?= count(\app\models\TDkg::getOptionListGrader()); ?>">
                                                            <?php } ?>
                                                        </div>
                                                        <a href="javascript:;" data-repeater-create class="btn btn-xs btn-info mt-repeater-add" style="margin-top: 5px;">
															<i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Grader'); ?>
														</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }else{ ?>
                                            <div class="form-group" style="">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Grader Terlibat'); ?></label>
                                                <?php if(isset($_GET['loglist_id'])){ ?>
                                                    <div class="col-md-8" style="margin-top: 10px;">
                                                        <?php if(count($modDkg)>0){ ?>
                                                        <?php foreach($modDkg as $i => $dkg){ ?>
                                                        <strong><?= ($i+1).". ".$dkg->graderlog->graderlog_nm." (".$dkg->kode.")"; ?></strong><br>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                <?php }else{ ?>
                                                    <div class="col-md-8">
                                                        <div class="repeater">
                                                            <div data-repeater-list="<?= \yii\helpers\StringHelper::basename(get_class($model));  ?>">
                                                                <div data-repeater-item style="display: block;">
                                                                    <span class="input-group-btn" style="width: 60%">
                                                                        <?php echo \yii\bootstrap\Html::activeDropDownList($model, 'grader_id', \app\models\TDkg::getOptionListGrader(),['class'=>'form-control','prompt'=>'']) ?>
                                                                    </span>
                                                                    <span class="input-group-btn" style="width: 20%" id="remove-btn">
                                                                        <a href="javascript:;" data-repeater-delete class="btn btn-danger"><i class="fa fa-close"></i></a>
                                                                    </span>
                                                                </div>
                                                                <input type="hidden" id="total-item-repeater" value="<?= count(\app\models\TDkg::getOptionListGrader()); ?>">
                                                            </div>
                                                            <a href="javascript:;" data-repeater-create class="btn btn-xs btn-info mt-repeater-add" style="margin-top: 5px;">
                                                                <i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Grader'); ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6">
										<?= yii\helpers\Html::activeHiddenInput($model, "log_kontrak_id"); ?>
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Keputusan'); ?></label>
											<div class="col-md-8">
												<span class="input-group-btn" style="width: 100%">
													<?= \yii\bootstrap\Html::activeDropDownList($model, 'pengajuan_pembelianlog_id', \app\models\TPengajuanPembelianlog::getOptionListLoglist(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setKeputusan()','style'=>'width:100%;']); ?>
												</span>
												<span class="input-group-btn" style="width: 20%">
													<a class="btn btn-sm grey btn-outline" id="btn-detailkeputusan" onclick="detailKeputusan();" data-original-title="Daftar OP" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-info-circle"></i></a>
												</span>
											</div>
										</div>
										<?= $form->field($model, 'kode_po')->textInput(['disabled'=>true])->label("Kode PO"); ?>
										<?= $form->field($model, 'nomor_kontrak')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'kode_bajg')->textInput(); ?>
										<?= $form->field($model, 'lokasi_muat')->textInput(); ?>
                                        <?= $form->field($model, 'model_ukuran_loglist',[])->radioList(['2 Diameter','4 Diameter'],['onchange'=>'setDiameter()'])->label('Model Ukuran Loglist'); ?>
                                    </div>
                                </div>
                                <br><br><hr>
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Terima Loglist'); ?></h4>
                                    </div>
                                    <div class="col-md-7">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th colspan="3"><?= Yii::t('app', 'Nomor'); ?></th>
                                                        <th rowspan="2"><?= Yii::t('app', 'Kayu'); ?></th>
                                                        <th rowspan="2" style="width: 50px;"><?= Yii::t('app', 'Pjg<sup>m</sup>'); ?></th>
                                                        <th colspan="3" id="diameter-th"><?= Yii::t('app', 'Diameter'); ?></th>
                                                        <th colspan="3"><?= Yii::t('app', 'Unsur Cacat'); ?></th>
                                                        <th colspan="2"><?= Yii::t('app', 'Volume'); ?></th>
                                                        <th rowspan="2" style="width: 30px; font-size: 0.9rem;"><?= Yii::t('app', 'Fresh<br>Cut'); ?></th>
                                                        <th rowspan="2" style="width: 60px;"><?= Yii::t('app', ''); ?></th>
                                                    </tr>
													<tr>
														<th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Grade'); ?></th>
														<th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Produksi'); ?></th>
                                                        <th style="width: 80px; font-size: 1.1rem;"><?= Yii::t('app', 'Batang'); ?></th>
                                                        <th class="diameter2" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D1<sup>cm</sup>'); ?></th>
                                                        <th class="diameter2" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D2<sup>cm</sup>'); ?></th>
                                                        <th class="diameter4 hidden" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D1<sup>cm</sup>'); ?></th>
                                                        <th class="diameter4 hidden" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D2<sup>cm</sup>'); ?></th>
                                                        <th class="diameter4 hidden" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D3<sup>cm</sup>'); ?></th>
                                                        <th class="diameter4 hidden" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D4<sup>cm</sup>'); ?></th>
                                                        <th style="width: 55px; font-size: 1.1rem;"><?= Yii::t('app', 'Rata2'); ?><sup>cm</sup></th>
														<th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Panjang'); ?><sup>cm</sup></th>
														<th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'GB'); ?></th>
														<th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'GR'); ?></th>
														<th style="width: 70px; font-size: 1.1rem;"><?= Yii::t('app', 'Range'); ?></th>
														<th style="width: 70px; font-size: 1.1rem;">m<sup>3</sup></th>
													</tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
												<tfoot>
													<tr>
														<td colspan="6">
															<?php if((isset($_GET['loglist_id']))&&(!isset($_GET['edit']))){ ?>
																<a class="btn btn-xs btn-outline blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Batang'); ?></a>
															<?php }else{ ?>
																<a class="btn btn-xs btn-outline grey" id="btn-add-item" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Batang'); ?></a>
															<?php } ?>
														</td>
													</tr>
												</tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['loglist_id'])){
    $pagemode = "afterSave(); setFieldTombolTOP()";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode;
	$(this).find('select[name*=\"[pengajuan_pembelianlog_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Keputusan',
		width: null
	});
	$('.repeater').repeater({
        show: function () {
            if(RepeaterSetItemRequired()){
                RepeaterSetDropdown();
                $(this).slideDown();
                setTimeout(function(){
                    RepeaterSetFieldTombol();
                }, 500);
            }
            $('div[data-repeater-item][style=\"display: none;\"]').remove();
        },
        hide: function (e) {
            RepeaterSetDropdown();
            $(this).slideUp(e);
            setTimeout(function(){
                RepeaterSetFieldTombol();
            }, 500);
        },
    });
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Log List'))."');
", yii\web\View::POS_READY); ?>
<script>
function addItem(){
	var disabled =  $("#table-detail > tbody > tr:last").find('input:disabled, select:disabled').removeAttr('disabled');
	var last_tr =  $("#table-detail > tbody > tr:last").find("input,select").serialize();
    if( $('input[name*="[model_ukuran_loglist]"]:checked').val() == "0") { // kondisi 2 diameter
        var ukuran = "2 Diameter";
    }else{
        var ukuran = "4 Diameter";
    }
	disabled.attr('disabled','disabled');
	var loglist_id = $('#<?= yii\helpers\Html::getInputId($model, "loglist_id") ?>').val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/addItem']); ?>',
        type   : 'POST',
        data   : {last_tr:last_tr,loglist_id:loglist_id,ukuran:ukuran},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                    reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function hitungRata(obj){
    if( $(obj).parents('tr').find('input[name*="[diameter_ujung]"]').length!=0 ){ // jika 2 diameter
        var ujung = parseInt($(obj).parents('tr').find('input[name*="[diameter_ujung]"]').val());
        var pangkal = parseInt($(obj).parents('tr').find('input[name*="[diameter_pangkal]"]').val());
        if((ujung) && (pangkal)){
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( ( (ujung) + (pangkal) )/2 );
        }else{
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( 0 );
        }
    }else{
        var ujung1 = parseInt($(obj).parents('tr').find('input[name*="[diameter_ujung1]"]').val());
        var ujung2 = parseInt($(obj).parents('tr').find('input[name*="[diameter_ujung2]"]').val());
        var pangkal1 = parseInt($(obj).parents('tr').find('input[name*="[diameter_pangkal1]"]').val());
        var pangkal2 = parseInt($(obj).parents('tr').find('input[name*="[diameter_pangkal2]"]').val());
        if((ujung1) && (ujung2) && (pangkal1) && (pangkal2)){
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( ( (ujung1) + (ujung2) + (pangkal1) + (pangkal2) )/4 );
        }else{
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( 0 );
        }
    }
}

function setDropdownGrader(obj){
    var selected_items = [];
    $('#table-detail tbody tr').each(function(){
        var graderlog_id = $(this).find('select[name*="[graderlog_id]"]').val();
        if(graderlog_id){
            selected_items.push(graderlog_id);
        }
    });
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/setDropdownGrader']); ?>',
		type   : 'POST',
		data   : {selected_items:selected_items},
		success: function (data) {
			$(obj).find('select[name*="[graderlog_id]"]').html(data.html);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function RepeaterSetItemRequired(){
    var list_terisi = true;
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        var list = $(this).find('select[name*="[grader_id]"]').val();
        if(list){
            list_terisi &= true;
            $(this).find('select[name*="[grader_id]"]').removeAttr('style');
        }else{
            list_terisi &= false;
            $(this).find('select[name*="[grader_id]"]').attr('style','border-color: #e73d4a;');
        }
    });
    return (list_terisi);
}
function RepeaterSetDropdown(){
    var arr_list = [];
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        var list = $(this).find('select[name*="[grader_id]"]').val();
        arr_list[index] = list;
    });
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/repeaterSetDropdown']); ?>',
        type   : 'POST',
        data   : {list:arr_list},
        success: function (data) {
            if(data.html){
                $('div[data-repeater-item][style!="display: none;"]:last').find('select[name*="[grader_id]"]').html(data.html);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
	return false;
}
function RepeaterSetFieldTombol(){
	if( $('#total-item-repeater').val() == $('div[data-repeater-item][style!="display: none;"]').length ){
        $('.mt-repeater-add').attr('style','visibility:hidden; margin-top: 5px');
    }else{
        $('.mt-repeater-add').attr('style','visibility:visible; margin-top: 5px');
    }
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        $(this).find('select[name*="[grader_id]"]').prop('disabled', true);
        $(this).find('#remove-btn').css('visibility','hidden');
    });
    $('div[data-repeater-item][style!="display: none;"]:last').find('select[name*="[grader_id]"]').prop('disabled', false);
    $('div[data-repeater-item][style!="display: none;"]:last').find('#remove-btn').css('visibility','visible');
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
//        var jumlah_item = $('#table-detail tbody tr').length;
//        if(jumlah_item <= 0){
//                cisAlert('Isi detail terlebih dahulu');
//            return false;
//        }
//        if(validatingDetail()){
            submitform($form);
//        }
    }
    return false;
}

function validatingDetail(ele){
    var has_error = 0;
    var check = "";
    $('#table-detail tbody > tr').each(function(){
        var field1 = $(this).find('input[name*="[nomor_grd]"]');
        var field2 = $(this).find('input[name*="[nomor_produksi]"]');
        var field3 = $(this).find('input[name*="[nomor_batang]"]');
        var field4 = $(this).find('select[name*="[kayu_id]"]');
        var field5 = $(this).find('input[name*="[panjang]"]');
        if($(this).find('input[name*="[diameter_ujung]"]').length!=0){
            var field6 = $(this).find('input[name*="[diameter_ujung]"]');
            var field7 = $(this).find('input[name*="[diameter_pangkal]"]');
        }else{
            var field6 = $(this).find('input[name*="[diameter_ujung1]"]');
            var field10 = $(this).find('input[name*="[diameter_ujung2]"]');
            var field7 = $(this).find('input[name*="[diameter_pangkal1]"]');
            var field11 = $(this).find('input[name*="[diameter_pangkal2]"]');
        }
        var field8 = $(this).find('input[name*="[diameter_rata]"]');
        var field9 = $(this).find('input[name*="[volume_value]"]');
        
        if(!field1.val()){
            $(this).find('input[name*="[nomor_grd]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "1";
        }else{
            $(this).find('input[name*="[nomor_grd]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            $(this).find('input[name*="[nomor_produksi]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "2";
        }else{
            $(this).find('input[name*="[nomor_produksi]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field3.val()){
            $(this).find('input[name*="[nomor_batang]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "3";
        }else{
            $(this).find('input[name*="[nomor_batang]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field4.val()){
            $(this).find('select[name*="[kayu_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "4";
        }else{
            $(this).find('select[name*="[kayu_id]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field5.val()){
            $(this).find('input[name*="[panjang]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "5";
        }else{
            $(this).find('input[name*="[panjang]"]').parents('td').removeClass('error-tb-detail');
        }
        if($(this).find('input[name*="[diameter_ujung]"]').length!=0){
            if(!field6.val()){
                $(this).find('input[name*="[diameter_ujung]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "62";
            }else{
                $(this).find('input[name*="[diameter_ujung]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field7.val()){
                $(this).find('input[name*="[diameter_pangkal]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "72";
            }else{
                $(this).find('input[name*="[diameter_pangkal]"]').parents('td').removeClass('error-tb-detail');
            }
        }else{
            if(!field6.val()){
                $(this).find('input[name*="[diameter_ujung1]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "64";
            }else{
                $(this).find('input[name*="[diameter_ujung1]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field7.val()){
                $(this).find('input[name*="[diameter_pangkal1]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "74";
            }else{
                $(this).find('input[name*="[diameter_pangkal1]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field10.val()){
                $(this).find('input[name*="[diameter_ujung2]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "64";
            }else{
                $(this).find('input[name*="[diameter_ujung2]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field11.val()){
                $(this).find('input[name*="[diameter_pangkal2]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "74";
            }else{
                $(this).find('input[name*="[diameter_pangkal2]"]').parents('td').removeClass('error-tb-detail');
            }
        }
        
        if(!field8.val()){
            $(this).find('input[name*="[diameter_rata]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "8";
        }else{
            $(this).find('input[name*="[diameter_rata]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field9.val()){
            $(this).find('input[name*="[volume_value]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "9";
        }else{
            $(this).find('input[name*="[volume_value]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    setDiameter();
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#tloglist-tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    $("#<?= \yii\helpers\Html::getInputId($model, "pengajuan_pembelianlog_id") ?>").removeAttr('onchange');
    $("#<?= \yii\helpers\Html::getInputId($model, "pengajuan_pembelianlog_id") ?>").empty().append('<option value="<?= !empty($model->pengajuan_pembelianlog_id)?$model->pengajuan_pembelianlog_id:"" ?>"><?= !empty($model->pengajuanPembelianlog)?$model->pengajuanPembelianlog->kode:""; ?></option>').val('<?= !empty($model->pengajuan_pembelianlog_id)?$model->pengajuan_pembelianlog_id:"" ?>').trigger('change');
    $('input[name*="[model_ukuran_loglist]"]').prop("disabled", false);
    <?php if(isset($_GET['edit'])){ ?>
        $("#<?= \yii\helpers\Html::getInputId($model, "tanggal") ?>").prop("disabled", false);
        $('#tloglist-tanggal').siblings('.input-group-btn').find('button').prop('disabled', false);
        $("#<?= \yii\helpers\Html::getInputId($model, "tongkang") ?>").prop("disabled", false);
        $("#<?= \yii\helpers\Html::getInputId($model, "kode_bajg") ?>").prop("disabled", false);
        $("#<?= \yii\helpers\Html::getInputId($model, "lokasi_muat") ?>").prop("disabled", false);
        $('input[name*="[model_ukuran_loglist]"]').prop("disabled", true);
        $('#btn-save').removeAttr('disabled');
    <?php } ?>
}

function saveItem(ele){
    if(validatingDetail(ele)){
		$(ele).parents('tr').find('input[name*="[volume_value]"]').val( unformatNumber($(ele).parents('tr').find('input[name*="[volume_value]"]').val()) );
		$(ele).parents('tr').addClass('animation-loading');
		$.ajax({
			url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/saveitem']); ?>',
			type   : 'POST',
			data   : { formData: $(ele).parents('tr').find('input, textarea, select').serialize() },
			success: function (data) {
				$(ele).parents('tr').find('input[name*="[volume_value]"]').val( formatNumberForUser($(ele).parents('tr').find('input[name*="[volume_value]"]').val()) );
				if(data.status){
					$(ele).parents('tr').find('input[name*="[loglist_detail_id]"]').val( data.loglist_detail_id );
//					$(ele).parents('tr').find('input[name*="[no_barcode]"]').val( data.no_barcode );
					$(ele).parents('tr').find('input, textarea, select').attr('disabled','disabled');
					$(ele).parents('tr').find('#place-editbtn').attr('style','display:');
					$(ele).parents('tr').find('#place-cancelbtn').attr('style','display:none');
					$(ele).parents('tr').find('#place-savebtn').attr('style','display:none');
					$(ele).parents('tr').find('#place-deletebtn').attr('style','display:');
					$(ele).parents('tr').removeClass('animation-loading');
				}else{
					cisAlert(data.message);
				}
				reordertable('#table-detail');
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
	}
    return false;
}

function edit(ele){
	$(ele).parents('tr').find('input, select').removeAttr('disabled');
	$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
	$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
}
function deleteItem(ele){
	var loglist_detail_id = $(ele).parents("tr").find("input[name*='[loglist_detail_id]']").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/deleteItem','id'=>''])?>'+loglist_detail_id,'modal-delete-record');
}

function getItems(uk=null){
	var loglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'loglist_id') ?>').val();
	if(uk){
        var ukuran = "4 Diameter";
    }else{
        var ukuran = "2 Diameter";
    }
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/getItems']); ?>',
		type   : 'POST',
		data   : {loglist_id:loglist_id,ukuran:ukuran,edit:'<?= isset($_GET['edit'])?"1":"0" ?>'},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/daftarAfterSave']) ?>','modal-aftersave','75%');
}

function setKeputusan(){
	var pengajuan_pembelianlog_id = $("#<?= yii\helpers\Html::getInputId($model, "pengajuan_pembelianlog_id") ?>").val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/setKeputusan']); ?>?pengajuan_pembelianlog_id='+pengajuan_pembelianlog_id,
		type   : 'POST',
		data   : {},
		success: function (data) {
			if(data){
				if(data.modKontrak && data.model){
					$('#<?= yii\helpers\Html::getInputId($model, "kode_po") ?>').val(data.modKontrak.kode+' - '+data.modKontrak.tanggal_po);
					$('#<?= yii\helpers\Html::getInputId($model, "nomor_kontrak") ?>').val(data.modKontrak.nomor);
					$('#<?= yii\helpers\Html::getInputId($model, "lokasi_muat") ?>').val(data.model.lokasi_muat);
					$('#<?= yii\helpers\Html::getInputId($model, "log_kontrak_id") ?>').val(data.modKontrak.log_kontrak_id);
					$("#btn-detailkeputusan").addClass("blue-hoki");
					$("#btn-detailkeputusan").removeClass("grey");
				}else{
					$('#<?= yii\helpers\Html::getInputId($model, "kode_po") ?>').val("");
					$('#<?= yii\helpers\Html::getInputId($model, "nomor_kontrak") ?>').val("");
					$('#<?= yii\helpers\Html::getInputId($model, "lokasi_muat") ?>').val("");
					$('#<?= yii\helpers\Html::getInputId($model, "log_kontrak_id") ?>').val("");
					$("#btn-detailkeputusan").removeClass("blue-hoki");
					$("#btn-detailkeputusan").addClass("grey");
				}
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function detailKeputusan(){
	var id = $("#<?= yii\helpers\Html::getInputId($model, 'pengajuan_pembelianlog_id') ?>").val();
	openModal("<?= yii\helpers\Url::toRoute('/purchasinglog/laporan/MonitoringPembelianLogDetail') ?>?id="+id,"modal-detail");
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
    });
}

function setFieldTombolTOP(){
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        $(this).find('select[name*="[grader_id]"]').attr('readonly','readonly');
        $(this).find('input[name*="[grader_id]"]').prop('readonly', true);
        $(this).find('#remove-btn').css('visibility','hidden');
    });
    $('div[data-repeater-item][style!="display: none;"]:last').find('select[name*="[grader_id]"]').removeAttr('readonly');
    $('div[data-repeater-item][style!="display: none;"]:last').find('select[name*="[grader_id]"]').removeAttr('disabled');
    $('div[data-repeater-item][style!="display: none;"]:last').find('input[name*="[grader_id]"]').prop('readonly', false);
    $('div[data-repeater-item][style!="display: none;"]:last').find('#remove-btn').css('visibility','visible');
}
function setDiameter(){
    $('input[name*="[model_ukuran_loglist]"]').prop("disabled", true);
    $("#table-detail").addClass("animation-loading");
    var loglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'loglist_id') ?>').val();
    if( $('input[name*="[model_ukuran_loglist]"]:checked').val() == "0") { // kondisi 2 diameter
        $(".diameter2").removeClass("hidden");
        $(".diameter4").addClass("hidden");
        $("#diameter-th").attr("colspan","3");
        var modeluk = "2 Diameter";
        getItems();
    }else{
        $(".diameter2").addClass("hidden");
        $(".diameter4").removeClass("hidden");
        $("#diameter-th").attr("colspan","5");
        var modeluk = "4 Diameter";
        getItems(1);
    }
    $.ajax({
        url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/updateModelLoglist']); ?>',
        type   : 'POST',
        data   : {loglist_id:loglist_id,modeluk:modeluk,edit:'<?= isset($_GET['edit'])?"1":"0" ?>'},
        success: function (data) {
            $('input[name*="[model_ukuran_loglist]"]').prop("disabled", false);
            $("#table-detail").removeClass("animation-loading");
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
</script>