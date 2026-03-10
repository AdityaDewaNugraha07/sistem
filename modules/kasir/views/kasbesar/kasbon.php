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
    'id' => 'form-bon-kasbesar',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-3 control-label'],
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
					<li class="active">
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
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/terimanontunai/index"); ?>"> <?= Yii::t('app', 'Penerimaan Non-Tunai'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Kas Bon Kas Besar (Bon Sementara)'); ?></h4></span>
                                </div>
                                <div class="tools">
									<a class="btn btn-sm btn-outline blue" id="btn-closing" onclick="historyBon();" style="margin-top: 10px; height: 28px;"><i class="icon-speedometer"></i> <?= Yii::t('app', 'Bon Terbayar'); ?></a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
														<th style="width: 110px; text-align: center;"><?= Yii::t('app', 'Kode'); ?></th>
														<th style="width: 120px; text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
														<th style="width: 130px; text-align: center;"><?= Yii::t('app', 'Penerima'); ?></th>
														<th><?= Yii::t('app', 'Deskripsi'); ?></th>
														<th style="width: 110px; "><?= Yii::t('app', 'Kredit'); ?></th>
														<th style="width: 50px; text-align: center; font-size: 1.0rem;"><?= Yii::t('app', 'Kasbon<br>Kas Kecil'); ?></th>
														<th style="width: 70px; text-align: center;"><?= Yii::t('app', ''); ?></th>
														<th style="width: 60px; text-align: center;"><?= Yii::t('app', ''); ?></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="5" class="text-align-right">Total &nbsp;</td>
														<td class="td-kecil text-align-right"><?php echo yii\bootstrap\Html::textInput('total',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem;']) ?></td>
													</tr>
													<tr>
														<td colspan="5">
															<div class="col-md-2" id="btn-additem-place">
																<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Bon Baru'); ?></a>
															</div>
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
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['kas_besar_id'])){
    $pagemode = "";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	$(\"#".yii\bootstrap\Html::getInputId($model, 'tanggal')."\").datepicker({
        rtl: App.isRTL(),
        orientation: \"left\",
        autoclose: !0,
        format: \"dd/mm/yyyy\",
        clearBtn:false,
        todayHighlight:true
    });
	getItems();
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Kas Besar'))."');
    $pagemode;
", yii\web\View::POS_READY); ?>
<script>
function getItems(){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/getKasbon']); ?>',
		type   : 'POST',
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			setTotal();
			setDetailLayout();
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function addItem(){
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/addKasbon']); ?>',
		type   : 'POST',
		data   : {},
		success: function (data) {
			if(data.html){
				$('#table-detail > tbody').append(data.html);
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function save(ele){
    var $form = $('#form-bon-kasbesar');
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
				url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/kasbesar/kasbon']); ?>',
				type   : 'POST',
				data   : { formData: $(ele).parents('tr').find('input, textarea').serialize() },
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
//    $('#table-detail tbody > tr').each(function(){
        var field1 = $(ele).parents('tr').find('textarea[name*="[deskripsi]"]');
        var field2 = $(ele).parents('tr').find('input[name*="[nominal]"]');
        var field3 = $(ele).parents('tr').find('input[name*="[penerima]"]');
        var field4 = $(ele).parents('tr').find('input[name*="[tanggal]"]');
        if(!field4.val()){
            $(ele).parents('tr').find('input[name*="[tanggal]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(ele).parents('tr').find('input[name*="[tanggal]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field3.val()){
            $(ele).parents('tr').find('input[name*="[penerima]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(ele).parents('tr').find('input[name*="[penerima]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field1.val()){
            $(ele).parents('tr').find('textarea[name*="[deskripsi]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(ele).parents('tr').find('textarea[name*="[deskripsi]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            has_error = has_error + 1;
            $(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( $(ele).parents('tr').find('input[name*="[nominal]"]').val() == 0 ){
                has_error = has_error + 1;
                $(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').addClass('error-tb-detail');
            }else{
                $(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').removeClass('error-tb-detail');
            }
        }
//    });
    if(has_error === 0){
        return true;
    }
    return false;
}

function edit(ele){
	$(ele).parents('tr').find('input, textarea').removeAttr('disabled');
	$(ele).parents('tr').find('input[name*="[kode]"]').attr('disabled','disabled');
	$('.date-picker').find('.input-group-addon').find('button').prop('disabled', false);
	$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
	$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
		setTotal();
    });
}

function deleteItem(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/deleteKasbon','id'=>''])?>'+id,'modal-delete-record')
}

function setTotal(){
	var total = 0;
	$('#table-detail > tbody > tr').each(function (){
		total += unformatNumber( $(this).find('input[name*="[nominal]"]').val() );
	});
	$('input[name="total"]').val( formatNumberForUser( total ) );
}

function setDetailLayout(){
	$('#table-detail > tbody > tr').each(function (){
		if( $(this).find('input[name*="[kas_bon_id]"]') ){
			$(this).find('input, textarea').attr('disabled','disabled');
			afterSave();
		}else{
			$(this).find('input, textarea').removeAttr('disabled');
		}
	});
}

function afterSave(){
	$('input[name*="total"]').attr('disabled','disabled');
	$('.date-picker').find('.input-group-addon').find('button').prop('disabled', true);
}

function historyBon(){
    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/bonTerbayar']) ?>','modal-history','85%');
}

function terimauangkaskecil(id){ 
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/terimauangkaskecil','id'=>''])?>'+id,'modal-global-confirm')
}
function terimauangganti(id){ 
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/terimauangganti','id'=>''])?>'+id,'modal-global-confirm')
}

function detailBkk(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/kasir/bkk/detailBkk']) ?>?id='+id,'modal-bkk','21cm');
}
function detailBbk(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/finance/voucher/detailBbk']) ?>?id='+id,'modal-bbk','21cm');
}
</script>