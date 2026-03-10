<?php
/* @var $this yii\web\View */
$this->title = 'Transaksi BPB';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Bukti Pengeluaran Barang (BPB)'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-bpb',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
		<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarBpb()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar BPB'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Pengeluaran'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-5">
										<?php 
										if(!isset($_GET['bpb_id'])){
											echo $form->field($model, 'bpb_kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode BPB'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'bpb_kode', ['class'=>'form-control','style'=>'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->bpb_kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        <?= $form->field($model, 'bpb_nomor')->textInput(['placeholder'=>'No. pada faktur']); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'bpb_dikeluarkan')->dropDownList(\app\models\MPegawai::getOptionList(),['class'=>'form-control select2','prompt'=>'','disabled'=>'disabled'])->label('Dikeluarkan Oleh'); ?>
                                        <?= $form->field($model, 'bpb_tgl_keluar',[
										 'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                    </div>
                                </div>
                                <br><br><hr>
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Pengeluaran'); ?></h4>
                                    </div>
                                    <div class="col-md-7 pull-right" style="margin-top:25px;">
                                            <label class="col-md-4" style="margin-top:5px; text-align: right;"><?= Yii::t('app', (isset($_GET['spb_id'])?"Load SPB :":"Dept / Kode SPB :")); ?></label>
                                            <div class="col-md-8 pull-right" >
                                                <span class="input-group-btn" style="width: 40%">
													<?php if(isset($_GET['bpb_id'])){ ?>
														<input type="text" class="form-control" value="<?= $model->departement->departement_nama; ?>">
													<?php }else{ 
														echo yii\bootstrap\Html::activeDropDownList($model, 'departement_id', \app\models\TSpb::getOptionListDepartement(),['prompt'=>'-- Pilih Departement --','class'=>'form-control']);
													} ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 60%">
													<?php if(isset($_GET['bpb_id'])){ ?>
														<input type="text" class="form-control" value="<?= $model->spb->spb_kode; ?>">
													<?php }else{ 
														$spbDropdown = (isset($_GET['bpb_id'])?\app\models\TSpb::getOptionList():[]);
														echo yii\bootstrap\Html::activeDropDownList($model, 'spb_id', $spbDropdown,['prompt'=>'','class'=>'form-control']);
													} ?>
                                                    
                                                </span>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
                                                        <th rowspan="2" style="vertical-align: middle; text-align: center; width: 300px;" ><?= Yii::t('app', 'Nama Item'); ?></th>
                                                        <th colspan="4" style="text-align: center;  vertical-align: middle;"><?= Yii::t('app', 'Kuantiti'); ?></th>
                                                        <th rowspan="2" style="width: 60px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Satuan'); ?></th>
                                                        <th rowspan="2" style="vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
                                                        <th rowspan="2" style="width: 50px; vertical-align: middle; text-align: center;" ><?= Yii::t('app', 'Cancel'); ?></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 80px;"><?= Yii::t('app', 'Kebutuhan'); ?></th>
                                                        <th style="width: 80px;"><?= Yii::t('app', 'Terpenuhi'); ?></th>
                                                        <th style="width: 80px;"><?= Yii::t('app', 'Keluar'); ?></th>
                                                        <th style="width: 80px;"><?= Yii::t('app', 'Stock <br>Sekarang'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printBPB('.(isset($_GET['bpb_id'])?$_GET['bpb_id']:'').');','disabled'=>true]); ?>
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
<style>
.popover {
    width: 2000px;
    max-width:60%
}
.popover-table th, td {
    padding: 0px 15px;
    white-space:nowrap;
}
</style>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['bpb_id'])){
    $pagemode = "afterSave()";
}else{
    $pagemode = "getItems();";
}
if(isset($_GET['loadjs'])){
	$autoloadcontent = 'autoloadcontent('.$_GET['loadjs']['dept_id'].','.$_GET['loadjs']['spb_id'].')';
}else{
	$autoloadcontent = '';
}
?>
<?php $this->registerJs(" 
    $('#".yii\bootstrap\Html::getInputId($model, 'departement_id')."').change(function(){
        setDropdownSpb();
    });
    $('#".yii\bootstrap\Html::getInputId($model, 'spb_id')."').change(function(){
        getItems();
    });
	formconfig();
    $pagemode;
    $autoloadcontent;
", yii\web\View::POS_READY); ?>

<script>
function printBPB(id){
//        alert(id);
	window.open("<?= yii\helpers\Url::toRoute('/logistik/bpb/printBPB') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
function autoloadcontent(dept_id,spb_id){
	$('#<?= yii\bootstrap\Html::getInputId($model, 'departement_id') ?>').val(dept_id);
	setDropdownSpb(dept_id, function() {
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spb_id') ?>').val(spb_id);
		getItems(spb_id);
	});
}

function setDropdownSpb(dept_id, callback){
	if(!dept_id){
		dept_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'departement_id') ?>').val();
	}
    $('#<?= yii\bootstrap\Html::getInputId($model, 'spb_id') ?>').addClass('animation-loading');
    $.ajax({
	url    : '<?= \yii\helpers\Url::toRoute(['/logistik/bpb/setDropdownSpb']); ?>',
	type   : 'POST',
	data   : {dept_id:dept_id},
	success: function (data) {
	    $('#<?= yii\bootstrap\Html::getInputId($model, 'spb_id') ?>').html(data.html);
	    $('#<?= yii\bootstrap\Html::getInputId($model, 'spb_id') ?>').removeClass('animation-loading');
	    getItems();
		if(callback){
			callback();
		}
	},
	error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function getItems(spb_id=null){
    $('#table-detail').addClass('animation-loading');
    if(!spb_id){
	spb_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'spb_id') ?>').val();
    }
    var html = "";
    if(spb_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/logistik/bpb/getItems']); ?>',
            type   : 'POST',
            data   : {spb_id:spb_id},
            success: function (data) {
                if(data){
                    html = data.html;
                    $('#table-detail tbody').html(html);
                    $('#table-detail').removeClass('animation-loading');
                    reordertable('#table-detail');
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }else{
        html = "<tr><td colspan='9'><center><i>Data tidak ditemukan</i></center></td></tr>"
        $('#table-detail tbody').html(html);
        $('#table-detail').removeClass('animation-loading');
    }
    setPopoverInfoSpb(spb_id);
}

function setPopoverInfoSpb(spb_id){
    if(spb_id){
	$('.spb-info-place').html('<i class="fa fa-info-circle popover-spb" data-ajaxload="<?= \yii\helpers\Url::toRoute(['/logistik/bpb/infoSpb','id'=>'']); ?>'+spb_id+'" style="cursor: default;"> Lihat SPB</i> ');
    }else{
	$('.spb-info-place').html('');
    }
    $('.popover-spb').hover(function(){
	var e= $(this);
	e.off('hover');
	$.get(e.data('ajaxload'),function(d){
	    e.popover({html : true,placement: 'left',content: d, title:'Detail SPB'}).popover('show');
	});
    }, function(){
	$('.popover-spb').popover('hide');
    });
}

function reordertable(obj_table){
    var row = 0;
    $(obj_table).find("tbody > tr").each(function(){
        $(this).find("#no_urut").val(row+1);
        $(this).find("span.no_urut").text(row+1);
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 3){
                $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]);
                $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]");
            }
        });
        row++;
    });
    formconfig();
}

function cancelItem(ele){
    $(ele).parents('tr').fadeOut(500,function(){
        $(this).remove();
        reordertable('#table-detail');
    });
}

function validateJmlKeluar(ele){
    var jml_kebutuhan = parseInt($(ele).parents('tr').find('input[name*="[qty_kebutuhan]"]').val());
    var jml_terpenuhi = parseInt($(ele).parents('tr').find('input[name*="[spbd_jml_terpenuhi]"]').val());
    var jml_keluar = parseInt($(ele).val());
    var stock_sekarang = parseInt($(ele).parents('tr').find('input[name*="[current_stock]"]').val());
	
	if( (jml_kebutuhan < 0) || (jml_terpenuhi < 0) || (jml_keluar < 0) || (stock_sekarang < 0) ){
		cisAlert('Transaksi tidak dapat diproses, mohon hubungi IT');
		return false;
	}else{
		if( jml_keluar > jml_kebutuhan-jml_terpenuhi ){
			$(ele).val( jml_kebutuhan-jml_terpenuhi );
			return false;
		}
		if( jml_keluar > stock_sekarang ){
			$(ele).val( stock_sekarang );
			return false;
		}
	}
}

function checkNegativeValue(){
	var check = true;
	$('#table-detail > tbody > tr').each(function (){
		var jml_kebutuhan = parseInt($(this).find('input[name*="[qty_kebutuhan]"]').val());
		var jml_terpenuhi = parseInt($(this).find('input[name*="[spbd_jml_terpenuhi]"]').val());
		var jml_keluar = parseInt($(this).find('input[name*="[bpbd_jml]"]').val());
		var stock_sekarang = parseInt($(this).find('input[name*="[current_stock]"]').val());
		if( (jml_kebutuhan < 0) || (jml_terpenuhi < 0) || (jml_keluar < 0) || (stock_sekarang < 0) ){
			cisAlert('Transaksi tidak dapat diproses, mohon hubungi IT');
			check &= false;
		}else{
			check &= true;
		}
	});
	return check;
}

function save(){
    var $form = $('#form-bpb');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
			cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail() && checkNegativeValue()){
            $form.submit();
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
    $('#table-detail tbody > tr').each(function(){
        var field1 = $(this).find('input[name*="[bhp_id]"]');
        var field2 = $(this).find('input[name*="[bpbd_jml]"]');
        if(!field1.val()){
            $(this).find('input[name*="[bhp_nama]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[bhp_nama]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[bpbd_jml]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( $(this).find('input[name*="[bpbd_jml]"]').val() == 0 ){
                has_error = has_error + 1;
                $(this).find('input[name*="[bpbd_jml]"]').parents('td').addClass('error-tb-detail');
            }else{
                $(this).find('input[name*="[bpbd_jml]"]').parents('td').removeClass('error-tb-detail');
            }
        }
    });
    
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    getItemsByBpb(id);
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
}

function getItemsByBpb(){
    $('#table-detail').addClass('animation-loading');
    var bpb_id = '<?= (isset($_GET['bpb_id'])?$_GET['bpb_id']:'') ?>';
    var html = "";
    if(bpb_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/logistik/bpb/GetItemsByBpb']); ?>',
            type   : 'POST',
            data   : {bpb_id:bpb_id},
            success: function (data) {
                if(data){
                    html = data.html;
                    $('#table-detail tbody').html(html);
                    $('#table-detail').removeClass('animation-loading');
                    reordertable('#table-detail');
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }else{
        html = "<tr><td colspan='7'><center><i>Data tidak ditemukan</i></center></td></tr>"
        $('#table-detail tbody').html(html);
        $('#table-detail').removeClass('animation-loading');
    }
}

function daftarBpb(){
    openModal('<?= \yii\helpers\Url::toRoute(['/logistik/bpb/daftarBpb']) ?>','modal-daftar-bpb','75%');
}

function abortItem(id,bpb_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/logistik/bpb/abortItem','id'=>'']); ?>'+id+"&bpb_id="+bpb_id,'modal-transaksi');
} 
</script>