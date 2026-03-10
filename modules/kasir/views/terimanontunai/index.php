<?php
/* @var $this yii\web\View */
$this->title = 'Kas Besar';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-nontunai',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/kasbesar/index"); ?>"> <?= Yii::t('app', 'Penerimaan Kas Besar'); ?> </a>
                    </li>
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/kasir/kasbesar/kasbon"); ?>"> <?= Yii::t('app', 'Bon Kas Besar'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/saldokasbesar/index"); ?>"> <?= Yii::t('app', 'Laporan Kas Besar'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/setorbank/index"); ?>"> <?= Yii::t('app', 'Setor Bank'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/rekapkasbesar/index"); ?>"> <?= Yii::t('app', 'Rekap Kas Besar'); ?> </a>
                    </li>
					<li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/terimanontunai/index"); ?>"> <?= Yii::t('app', 'Penerimaan Non-Tunai'); ?> </a>
                    </li>
                </ul>
                <div class="row">
					<div class="col-md-12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet light bordered">
							<div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Penerimaan Kasir Non-Tunai (Cek / Bilyet Giro)'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
							<div class="portlet-body">
								<div class="row">
                                    <div class="col-md-5">
										<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled'])->label('Kode'); ?>
										<?= $form->field($model, 'tanggal',['template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly','onchange'=>'getItems()'])->label('Tanggal'); ?>
                                    </div>
									<div class="col-md-5"></div>
									<div class="col-md-2">
										<h4 class="modal-title" id="place-statusclosing"></h4>
                                    </div>
                                </div>
							</div>
						</div>
						<!-- END EXAMPLE TABLE PORTLET-->
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div style="font-size: 1.5rem"><?= Yii::t('app', 'Listing Harian Penerimaan Kasir Non-Tunai Tanggal '); ?><span id="place-labeltanggal"></span></div>
					</div>
				</div>
				<style>
				.table thead th{
					font-size: 1.2rem !important; line-height: 13px !important; padding: 4px !important; vertical-align: middle; text-align: center;
				}
				</style>
				<div class="row">
					<div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
								<thead>
									<tr>
										<th style="width: 30px;" rowspan="2">No.</th>
										<th style="width: 180px;" rowspan="2"><?= Yii::t('app', 'Nama Customer'); ?></th>
										<th style="width: 80px;" rowspan="2"><?= Yii::t('app', 'No.<br>Bukti'); ?></th>
										<th style="width: 180px;" colspan="2"><?= Yii::t('app', 'Bank'); ?></th>
										<th style="width: 80px;" rowspan="2"><?= Yii::t('app', 'No. BG/Cek'); ?></th>
										<th style="width: 120px;" rowspan="2"><?= Yii::t('app', 'Tanggal Jatuh<br>Tempo'); ?></th>
										<th style="width: 120px;" rowspan="2"><?= Yii::t('app', 'Nominal'); ?></th>
										<th style="" rowspan="2"><?= Yii::t('app', 'Keterangan'); ?></th>
										<th style="width: 60px;" rowspan="2"><?= Yii::t('app', ''); ?></th>
									</tr>
									<tr>
										<th style="width: 80px;"><?= Yii::t('app', 'Nama'); ?></th>
										<th style="width: 100px;"><?= Yii::t('app', 'No. Acct'); ?></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="5">
											<div class="col-md-2" id="btn-additem-place"></div>
											<div class="col-md-2" id="btn-print-place" style="margin-left: 40px;"></div>
										</td>
									</tr>
								</tfoot>
							</table>
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
if(isset($_GET['kas_besar_nontunai_id'])){
    $pagemode = "";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Kas Besar'))."');
	formconfig();
	$(\"#".yii\bootstrap\Html::getInputId($model, 'tanggal')."\").datepicker({
        rtl: App.isRTL(),
        orientation: \"left\",
        autoclose: !0,
        format: \"dd/mm/yyyy\",
        clearBtn:false,
        todayHighlight:true
    });
    $pagemode;
", yii\web\View::POS_READY); ?>
<script>
function getItems(){
	$('#table-detail > tbody').addClass('animation-loading');
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	var kode = $('#<?= yii\bootstrap\Html::getInputId($model, 'kode') ?>').val();
	$('#place-labeltanggal').html(tgl);
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/kasir/terimanontunai/getItems']); ?>',
        type   : 'POST',
        data   : {tgl:tgl},
        success: function (data){
			$('#table-detail > tbody').html("");
			$('#<?= \yii\bootstrap\Html::getInputId($model, 'kode') ?>').val("Auto Generate");
			$('#<?= \yii\bootstrap\Html::getInputId($model, 'kode') ?>').attr('style','');
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			if(data.kode){
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'kode') ?>').attr('style','font-weight:bold;');
				$('#<?= \yii\bootstrap\Html::getInputId($model, 'kode') ?>').val(data.kode);
			}
			if(typeof(data.statusclosing) != "undefined" && data.statusclosing !== null) {
				if(data.statusclosing == 1){
					$('#place-statusclosing').html( '<strong style="background-color:#c8da8e">Sudah Closing</strong>' );
				}else{
					$('#place-statusclosing').html( '<strong style="background-color:#FBE88C">Belum Closing</strong>' );
				}
			}else{ 
				$('#place-statusclosing').html( "" ); 
			}
			setClosingBtn();
			setDetailLayout();
			reordertable('#table-detail');
			$('#table-detail > tbody').removeClass('animation-loading');
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setClosingBtn(){ 
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	var jmltr = $('#table-detail > tbody > tr').length;
	var html = '';
	var html2 = '';
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/setClosingBtn']); ?>',
		type   : 'POST',
		data   : {tgl:tgl},
		success: function (data) {
			if(data.status == 1){
				html = '<a id="btn-add-item" class="btn btn-sm grey" disabled="disabled" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Penerimaan'); ?></a>';
				html2 = '<a id="btn-print" class="btn btn-sm btn-outline blue" style="margin-top: 10px;" onclick="detailBkk(\''+tgl+'\')"><i class="fa fa-print"></i> <?= Yii::t('app', 'Print'); ?></a>';
				$('#form-nontunai').find('input').each(function(){ $(this).attr("readonly","readonly"); });
				$('#form-nontunai').find('textarea').each(function(){ $(this).attr("readonly","readonly"); });
				$('#btn-save').attr("disabled","disabled");
				$('#table-detail > tbody > tr').each(function(){
					$(this).find('#td-action').html(' ');
				});
			}else{
				html = '<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Penerimaan'); ?></a>';
				html2 = '<a id="btn-print" class="btn btn-sm grey" disabled="disabled" style="margin-top: 10px;"><i class="fa fa-print"></i> <?= Yii::t('app', 'Print'); ?></a>';
			}
			$('#btn-additem-place').html(html);
			$('#btn-print-place').html(html2);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function addItem(){
	var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
	var kode = $('#<?= yii\bootstrap\Html::getInputId($model, 'kode') ?>').val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/checkClosing']); ?>',
		type   : 'POST',
		data   : {tgl:tgl},
		success: function (data) {
			if(data == 1){
				cisAlert('Tidak bisa Tambah Item karena ada penerimaan kas yang belum di Closing di tanggal sebelumnya ;)')
			}else{
				$.ajax({
					url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/terimanontunai/addItem']); ?>',
					type   : 'POST',
					data   : {tgl:tgl},
					success: function (data) {
						if(data.html){
							$('#table-detail > tbody').append(data.html);
							
						}
						setClosingBtn();
						reordertable('#table-detail');
					},
					error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
				});
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function save(ele){
    var $form = $('#form-nontunai');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail(ele)){
			$(ele).parents('tr').find('input[name*="[nominal]"]').val( unformatNumber($(ele).parents('tr').find('input[name*="[nominal]"]').val()) );
			$(ele).parents('tr').addClass('animation-loading');
			$.ajax({
				url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/terimanontunai/index']); ?>',
				type   : 'POST',
				data   : { formData: $(ele).parents('tr').find('input, textarea, select').serialize() },
				success: function (data) {
					$(ele).parents('tr').find('input[name*="[nominal]"]').val( formatNumberForUser($(ele).parents('tr').find('input[name*="[nominal]"]').val()) );
					if(data.status){
						getItems();
						$(ele).parents('tr').removeClass('animation-loading');
					}
					reordertable('#table-detail');
				},
				error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
			});
        }
    }
    return false;
}

function validatingDetail(ele){
    var has_error = 0;
	var field1 = $(ele).parents('tr').find('input[name*="[nama_customer]"]');
	var field2 = $(ele).parents('tr').find('input[name*="[reff_number]"]');
	var field3 = $(ele).parents('tr').find('input[name*="[tanggal_jatuhtempo]"]');
	var field4 = $(ele).parents('tr').find('input[name*="[nominal]"]');
	if(!field1.val()){
		$(ele).parents('tr').find('input[name*="[nama_customer]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[nama_customer]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field2.val()){
		$(ele).parents('tr').find('input[name*="[reff_number]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[reff_number]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field3.val()){
		$(ele).parents('tr').find('input[name*="[tanggal_jatuhtempo]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[tanggal_jatuhtempo]"]').parents('td').removeClass('error-tb-detail');
	}
	if(!field4.val()){
		$(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').removeClass('error-tb-detail');
	}
    if(has_error === 0){
        return true;
    }
    return false;
}
function setDetailLayout(){
	$('#table-detail > tbody > tr').each(function (){
		if( $(this).find('input[name*="[kas_besar_nontunai_id]"]') ){
			$(this).find('input, textarea, select').attr('disabled','disabled');
			$(this).find('.input-group-btn button').prop('disabled', true);
		}else{
			$(this).find('input, textarea, select').removeAttr('disabled');
			$(this).find('.input-group-btn button').prop('disabled', false);
		}
	});
}
function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
		setClosingBtn();
    });
}
function deleteItem(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/terimanontunai/deleteItem','id'=>''])?>'+id,'modal-delete-record');
}
function edit(ele){
	$(ele).parents('tr').find('input, textarea, select').removeAttr('disabled');
	$('.date-picker').find('.input-group-addon').find('button').prop('disabled', false);
	$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
	$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
}

function detailBkk(tgl){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/terimanontunai/detailnontunai']) ?>?tgl='+tgl,'modal-bkk','21.5cm');
}

function printNontunai(tgl){
	window.open("<?= yii\helpers\Url::toRoute('/kasir/terimanontunai/printnontunai') ?>?tgl="+tgl+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

</script>