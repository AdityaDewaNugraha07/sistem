<?php
/* @var $this yii\web\View */
$this->title = 'Verifikasi Data Gudang Barang Jadi';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); 
?>
<style>
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/stockopname/index") ?>"> <?= Yii::t('app', 'Agenda Verifikasi Data'); ?> </a>
					</li>
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/stockopname/scan") ?>"> <?= Yii::t('app', 'Scan Verifikasi Data'); ?> </a>
					</li>
                    <li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/stockopname/hasil") ?>"> <?= Yii::t('app', 'Hasil Verifikasi Data'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <?php if(isset($_GET['stockopname_agenda_id'])){ ?>
                                        <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Agenda Verifikasi Data'); ?></h4></span>
                                    <?php }else{ ?>
                                        <span class="caption-subject bold"><h4><?= Yii::t('app', 'Buat Agenda Verifikasi Data Baru'); ?></h4></span>
                                    <?php } ?>
                                </div>
                                <span class="pull-right">
									<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Agenda Yang Sudah Dibuat'); ?></a> 
								</span>
                            </div>
                            <div class="portlet-body">
								<div class="row">
                                    <div class="col-md-6">
										<?= $form->field($model, 'kode')->textInput(['disabled'=>true,'style'=>'font-weight:600'])->label("Kode Agenda"); ?>
										<?= $form->field($model, 'tanggal',[
                                                                        'template'=>'{label}<div class="col-md-7"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                        <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                        {error}</div>'])->textInput()->label("Tanggal Pelaksanaan"); ?>
                                        <?= $form->field($model, 'penanggungjawab_display')->textInput(['disabled'=>true,])->label("Penanggung Jawab Kadep Acct"); ?>
                                        <?= $form->field($model, 'by_kadivacc_display')->textInput(['disabled'=>true,])->label("Menyetujui Kadiv FIN"); ?>
									</div>
									<div class="col-md-6">
                                        <?= $form->field($model, 'by_kanitgud_display')->textInput(['disabled'=>true,])->label("Mengetahui Kanit Gudang"); ?>
                                        <?= $form->field($model, 'by_kadivmkt_display')->textInput(['disabled'=>true,])->label("Mengetahui Kadiv Mkt"); ?>
                                        <?= yii\helpers\Html::activeHiddenInput($model, 'penanggungjawab') ?>
                                        <?= yii\helpers\Html::activeHiddenInput($model, 'by_kadivacc') ?>
                                        <?= yii\helpers\Html::activeHiddenInput($model, 'by_kanitgud') ?>
                                        <?= yii\helpers\Html::activeHiddenInput($model, 'by_kadivmkt') ?>
                                        <?= $form->field($model, 'keterangan')->textarea()->label("Keterangan Agenda"); ?>
                                        <?php if((isset($_GET['stockopname_agenda_id']))&&(!isset($_GET['edit']))){ ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" >Status</label>
                                            <div class="col-md-7" style="margin-top: 10px;">
                                                <?php
                                                if($model->status=="NOT ACTIVE"){
                                                    echo '<span class="label label-sm label-default"> '.$model->status.' </span><br><span style="font-size:0.9rem"><i>Akan Active jika sudah di approve</i></span>';
                                                }elseif($model->status=="ACTIVE"){
                                                    echo '<span class="label label-sm label-warning"> '.$model->status.' </span>';
                                                }elseif($model->status=="DONE"){
                                                    echo '<span class="label label-sm label-success"> '.$model->status.' </span>';
                                                }elseif($model->status=="REJECTED"){
                                                    echo '<span class="label label-sm label-danger"> '.$model->status.' </span>';
                                                    $approval = \app\models\TApproval::find()->where("reff_no = '{$model->kode}' AND status='REJECTED'")->all();
                                                    if(count($approval)>0){
                                                        foreach($approval as $i => $app){
                                                            echo "<br><span class='font-red-flamingo' style='font-size:1rem;'>".
                                                                    "<b>".\app\models\MPegawai::findOne($app->approved_by)->pegawai_nama."</b> : ".
                                                                    yii\helpers\Json::decode($app->keterangan)[0]['reason']
                                                                ."</span>";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php } ?>
									</div>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-12" style="margin-bottom: -10px; ">
                                        <h4><?= Yii::t('app', 'Peserta Verifikasi Data'); ?></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail">
												<thead>
                                                    <tr>
                                                        <th class="" style="width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th class="" style=""><?= Yii::t('app', 'Nama') ?></th>
                                                        <th class="" style="width: 150px;"><?= Yii::t('app', 'Jabatan') ?></th>
                                                        <th class="" style="width: 150px;"><?= Yii::t('app', 'Departement') ?></th>
                                                        <th class="" style="width: 200px;"><?= Yii::t('app', 'Keterangan') ?></th>
                                                        <th style="width: 35px;"></th>
                                                    </tr>
												</thead>
												<tbody>
                                                    
												</tbody>
												<tfoot>
                                                    <tr>
														<td colspan="3">
															<a class="btn btn-xs blue" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Peserta'); ?></a>
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
                                <?php 
                                $tbl = \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();'])."&nbsp;".
                                        \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']);
                                if(empty($_GET['stockopname_agenda_id']) && empty($_GET['edit'])){ // new transaksi
                                    $checkagenda = \app\models\TStockopnameAgenda::find()->where("status IN ('ACTIVE','NOT ACTIVE')")->all();
                                    if(!empty($checkagenda)){
                                        $tbl = "<h5><i><b>NOTE : Belum bisa membuat agenda baru, jika ada agenda yang belum Close</b></i></h5>";
                                    }
                                }
                                echo $tbl;
                                ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if( isset($_GET['stockopname_agenda_id']) && isset($_GET['edit'])){
    $pagemode = "getItemByPk(".$_GET['stockopname_agenda_id'].")";
}else if(isset($_GET['stockopname_agenda_id'])){
    $pagemode = "afterSave(".$_GET['stockopname_agenda_id'].")";
}else {
    $pagemode = "addItem();";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode
", yii\web\View::POS_READY); ?>
<script>
function addItem(){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/addItem']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail tbody').fadeIn(500,function(){
                    $(this).find('select[name*="[pegawai_id]"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik nama pegawai',
                        width: null,
						ajax: {
							url: '<?= \yii\helpers\Url::toRoute('/gudang/stockopname/FindPegawai') ?>',
							dataType: 'json',
							delay: 250,
							processResults: function (data) {
								return {
									results: data
								};
							},
							cache: true
						}
					});
                    reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setItem(ele){
	var pegawai_id = $(ele).parents("tr").find("select[name*='[pegawai_id]']").val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/setItem']); ?>',
        type   : 'POST',
        data   : {pegawai_id:pegawai_id},
        success: function (data) {
            if(data){
                $(ele).parents("tr").find("input[name*='[jabatan_nama]']").val(data.jabatan_nama);
                $(ele).parents("tr").find("input[name*='[departement_nama]']").val(data.departement_nama);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function save(){
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail()){
            submitform($form);
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
    $('#table-detail tbody > tr').each(function(){
		if($(this).find('select[name*="[pegawai_id]"]').length > 0){
			var field1content = 'select[name*="[pegawai_id]"]';
		}else{
			var field1content = 'input[name*="[pegawai_id]"]';
		}
        var field1 = $(this).find(field1content);
        if(!field1.val()){
            $(this).find(field1content).parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find(field1content).parents('td').removeClass('error-tb-detail');
        }
    });
    if(has_error === 0){
        return true;
    }
    return false;
}
function afterSave(id){
    getItemByPk(id);
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("readonly","readonly"); });
    $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-add-item').hide();
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
}
function getItemByPk(stockopname_agenda_id){
    var edit = "<?= (isset($_GET['edit']))?$_GET['edit']:"" ?>";
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/getItemByPk']); ?>',
		type   : 'POST',
		data   : {stockopname_agenda_id:stockopname_agenda_id,edit:edit},
		success: function (data) {
			if(edit){
                $('#table-detail tbody').html(data.html);
                var notin = [];
                $('#table-detail > tbody > tr').each(function(){
                    var pegawai_id = $(this).find('select[name*="[pegawai_id]"]');
                    if( pegawai_id.val() ){
                        notin.push(pegawai_id.val());
                    }
                });
                if(notin){
                    notin = JSON.stringify(notin);
                }
                $('#table-detail > tbody > tr').each(function(){
                    $(this).find('select[name*="[pegawai_id]"]').select2({
                        allowClear: !0,
                        placeholder: 'Ketik nama pegawai',
                        width: null,
                        ajax: {
                            url: '<?= \yii\helpers\Url::toRoute('/gudang/stockopname/FindPegawai') ?>',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                var query = {
                                  term: params.term,
                                  notin: notin
                                }
                                return query;
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    });
                });
                reordertable('#table-detail');
            }else{
                if(data.html){
                    $('#table-detail tbody').html(data.html);
                    reordertable('#table-detail');
                }
            }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/daftarAfterSave']) ?>','modal-aftersave','95%');
}
</script>