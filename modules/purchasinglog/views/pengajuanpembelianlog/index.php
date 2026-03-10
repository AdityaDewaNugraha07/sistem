<?php
/* @var $this yii\web\View */
$this->title = 'Keputusan Pembelian Log Alam';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
app\assets\MagnificPopupAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
table.table thead tr th{
	font-size: 1.3rem;
	padding: 2px;
	border: 1px solid #A0A5A9;
}
table.table#table-detail-permintaan thead tr th{
	padding: 10px;
	border: 1px solid #A0A5A9;
}
.table-striped.table-bordered.table-hover.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover2.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover3.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover4.table-bordered > thead > tr > th {
    line-height: 1;
}
.add-more:hover {
    background: #58ACFA;
}

</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<span class="pull-right">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Keputusan Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<span class="caption-subject bold"><h4> Data Keputusan </h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'pengajuan_pembelianlog_id'); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'keterangan'); ?>
										<?php if(!isset($_GET['pengajuan_pembelianlog_id'])){ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<table style="width: 100%">
														<tr>
															<td style="width: 60%"><?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%; font-weight:bold','disabled'=>'disabled']) ?></td>
															<td style="width: 20%">
																&nbsp;&nbsp; Revisi :
															</td>
															<td style="width: 20%">
																<?= \yii\bootstrap\Html::activeDropDownList($model, 'revisi', ["0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5"],['class'=>'form-control','style'=>'width:100%']) ?>
															</td>
														</tr>
													</table>
												</div>
											</div>
										<?php }else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<table style="width: 100%">
														<tr>
															<td style="width: 50%"><?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?></td>
															<td style="width: 10%"><a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
																	<i class="icon-paper-clip"></i>
																</a>
															</td>
															<td style="width: 20%">
																&nbsp;&nbsp; Revisi :
															</td>
															<td style="width: 20%">
																<?= \yii\bootstrap\Html::activeDropDownList($model, 'revisi', ["0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5"],['class'=>'form-control','style'=>'width:100%']) ?>
															</td>
														</tr>
													</table>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'tanggal',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'log_kontrak_id')->dropDownList(\app\models\TLogKontrak::getOptionListPO(),['class'=>'form-control select2','onchange'=>'setKontrak()','prompt'=>'','style'=>'width:100%;'])->label("Kode PO"); ?>
										<?= $form->field($model, 'nomor_kontrak')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'volume_kontrak')->textInput(['class'=>'form-control float'])->label("Volume Kontrak"); ?>
										<?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionList("LA"),['class'=>'form-control select2','prompt'=>''])->label("Suplier"); ?>
										<?= $form->field($model, 'asal_kayu')->textInput(); ?>
                                        <?= $form->field($model, 'status_fsc')->inline()->radioList(['FSC 100%'=>"FSC 100%",'Non FSC'=>"NON FSC"]) ?>
									</div>
									<div class="col-md-6">
										<?= $form->field($model, 'asuransi')->inline(true)->radioList([true=>"Ya",false=>"Tidak"]); ?>
										<?= $form->field($model, 'nominal_dp')->textInput(['class'=>"form-control float"])->label("Nominal Terbayar (Rp)"); ?>
										<?= $form->field($model, 'tanggal_bayar_dp',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Terbayar"); ?>
										<?= $form->field($model, 'term_of_price')->dropDownList(["CIF"=>"CIF","CNF"=>"CNF","FOB"=>"FOB","Logpond Penjual"=>"Logpond Penjual"],['prompt'=>'']); ?>
										<div class="form-group">
											<label class="col-md-4 control-label">Jangka Waktu Penyerahan</label>
											<div class="col-md-8">
												<span class="input-group-btn" style="width: 50%">
													<?= $form->field($model, 'waktu_penyerahan_awal',[
																'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
																			 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																			 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
												</span>
												<span class="input-group-addon textarea-addon" style="width: 10%; background-color: #fff; border: 0;"> sd </span>
												<span class="input-group-btn" style="width: 50%">
													<?= $form->field($model, 'waktu_penyerahan_akhir',[
																'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
																			 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																			 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
												</span>
											</div>
										</div>
										<?= $form->field($model, 'lokasi_muat')->textInput(); ?>
										<?php echo $form->field($model, 'total_volume')->textInput(['class'=>'form-control float','disabled'=>true])->label("Total Volume Pembelian m<sup>3</sup>"); ?>
										<?php echo $form->field($model, 'keterangan_pembelian')->textarea(['class'=>'form-control','style'=>'font-size:1.2rem;']); ?>
										<?php // echo $form->field($model, 'by_kanit_name')->textInput(['disabled'=>true]); ?>
										<?php // echo $form->field($model, 'by_gmpurch_name')->textInput(['disabled'=>true]); ?>
										<?php // echo $form->field($model, 'by_kadiv_name')->textInput(['disabled'=>true]); ?>
										<?php // echo $form->field($model, 'by_gmopr_name')->textInput(['disabled'=>true]); ?>
										<?php // echo $form->field($model, 'by_dirut_name')->textInput(['disabled'=>true]); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'by_kanit'); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'by_gmpurch'); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'by_kadiv'); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'by_gmopr'); ?>
										<?= yii\bootstrap\Html::activeHiddenInput($model, 'by_dirut'); ?>
									</div>
								</div>
								<hr>
								<h4><?= Yii::t('app', 'Permintaan Pembelian Log Alam'); ?></h4>
                                <div class="row" style="margin-left: -20px; margin-right: -20px;">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail-permintaan">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;"></th>
                                                        <th style="width: 150px; line-height: 1"><?= Yii::t('app', 'Kode<br>Permintaan'); ?></th>
                                                        <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Permintaan'); ?></th>
                                                        <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Dibutuhkan<br>Untuk'); ?></th>
                                                        <th ><?= Yii::t('app', 'Tanggal<br>Dibutuhkan'); ?></th>
                                                        <th style="width: 125px; line-height: 1"><?= Yii::t('app', 'Diminta Oleh'); ?></th>
                                                        <th style="width: 125px; line-height: 1"><?= Yii::t('app', 'Decision Maker'); ?></th>
                                                        <th style="width: 80px; line-height: 1"><?= Yii::t('app', 'Qty<br>Permintaan'); ?></th>
                                                        <th style="width: 120px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr id="empty-tr"><td colspan="11"><center style='font-size: 1.2rem;'>Belum ada permintaan yang dipilih</center></td></tr>
												</tbody>
                                                <tfoot>
													<tr>
														<td colspan="7" style="text-align: right;">Total Permintaan &nbsp; </td>
                                                        <td style="text-align: right; font-weight: 600;"><span id="place-total-permintaan">0</span> M<sup>3</sup></td>
													</tr>
												</tfoot>
                                            </table>
                                        </div>
                                        <a class="btn btn-xs blue" id="btn-add-permintaan" onclick="addPermintaan()"><i class="fa fa-plus"></i> Add Permintaan</a>
                                        <span class="font-red-flamingo pull-right" id="place-warning-overpembelian"></span>
                                        <?= yii\helpers\Html::activeHiddenInput($model, "total_permintaan"); ?>
                                    </div>
                                </div>
                                <hr>
								<h4><?= Yii::t('app', 'Log Industri'); ?></h4>
                                <div class="row" style="margin-left: -20px; margin-right: -20px;">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-industri">
												<thead>
													<?php $ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log'); ?>
													<tr>
														<th style="width: 30px;" rowspan="3" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
														<th style="" rowspan="3"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
														<th colspan="<?= (count($ukuranganrange)*3+3) ?>"><?= Yii::t('app', 'Diameter'); ?></th>
														<th rowspan="3" style="width: 30px;"></th>
													</tr>
													<tr>
														<?php foreach($ukuranganrange as $i => $range){ ?>
														<th colspan="3"><?= $range ?></th>
														<?php } ?>
														<th colspan="3">Total</th>
													</tr>
													<tr>
														<?php foreach($ukuranganrange as $i => $range){ ?>
														<th style="width: 40px;">Btg</th>
														<th style="width: 65px;">M<sup>3</sup></th>
														<th style="width: 75px;">Harga</th>
														<?php } ?>
														<th style="width: 45px;">Btg</th>
														<th style="width: 70px;">M<sup>3</sup></th>
														<th style="width: 80px;">Harga</th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
												<tfoot>
													<tr>
														<td colspan="2" style="text-align: right;">Jumlah &nbsp; </td>
														<?php foreach($ukuranganrange as $i => $range){ ?>
														<td><?= yii\helpers\Html::textInput("TPengajuanPembelianlogDetailIndustri[".$range."][total_btg]",0,["class"=>'form-control float col-btg-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
														<td><?= yii\helpers\Html::textInput("TPengajuanPembelianlogDetailIndustri[".$range."][total_m3]",0,["class"=>'form-control float col-m3-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
														<td><?php // echo yii\helpers\Html::textInput("TPengajuanPembelianlogDetailIndustri[".$range."][total_harga]",0,["class"=>'form-control float col-m3-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
														<?php } ?>
														<td><?= yii\helpers\Html::textInput("TPengajuanPembelianlogDetailIndustri[total][total_btg]",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
														<td><?= yii\helpers\Html::textInput("TPengajuanPembelianlogDetailIndustri[total][total_m3]",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
														<td><?php echo yii\helpers\Html::textInput("TPengajuanPembelianlogDetailIndustri[total][total_harga]",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
													</tr>
												</tfoot>
											</table>
										</div>
										<a class="btn btn-xs blue" id="btn-add-industri" onclick="addItem('industri')"><i class="fa fa-plus"></i> Add Item</a>
                                    </div>
                                </div><br>
								<hr>
								<h4><?= Yii::t('app', 'Log Trading'); ?></h4>
                                <div class="row" style="margin-left: -20px; margin-right: -20px;">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-trading">
												<thead>
													<?php $ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log'); ?>
													<tr>
														<th style="width: 30px;" rowspan="3" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
														<th style="" rowspan="3"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
														<th colspan="<?= (count($ukuranganrange)*3+3) ?>"><?= Yii::t('app', 'Diameter'); ?></th>
														<th rowspan="3" style="width: 30px;"></th>
													</tr>
													<tr>
														<?php foreach($ukuranganrange as $i => $range){ ?>
														<th colspan="3"><?= $range ?></th>
														<?php } ?>
														<th colspan="3">Total</th>
													</tr>
													<tr>
														<?php foreach($ukuranganrange as $i => $range){ ?>
														<th style="width: 40px;">Btg</th>
														<th style="width: 65px;">M<sup>3</sup></th>
														<th style="width: 75px;">Harga</th>
														<?php } ?>
														<th style="width: 45px;">Btg</th>
														<th style="width: 70px;">M<sup>3</sup></th>
														<th style="width: 80px;">Harga</th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
												<tfoot>
													<tr>
														<td colspan="2" style="text-align: right;">Jumlah &nbsp; </td>
														<?php foreach($ukuranganrange as $i => $range){ ?>
														<td><?= yii\helpers\Html::textInput("TPengajuanPembelianlogDetailTrading[".$range."][total_btg]",0,["class"=>'form-control float col-btg-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
														<td><?= yii\helpers\Html::textInput("TPengajuanPembelianlogDetailTrading[".$range."][total_m3]",0,["class"=>'form-control float col-m3-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
														<td><?php // echo yii\helpers\Html::textInput("TPengajuanPembelianlogDetailTrading[".$range."][total_harga]",0,["class"=>'form-control float col-m3-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
														<?php } ?>
														<td><?= yii\helpers\Html::textInput("TPengajuanPembelianlogDetailTrading[total][total_btg]",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
														<td><?= yii\helpers\Html::textInput("TPengajuanPembelianlogDetailTrading[total][total_m3]",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
														<td><?php echo yii\helpers\Html::textInput("TPengajuanPembelianlogDetailTrading[total][total_harga]",0,["class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1rem;","disabled"=>true]) ?></td>
													</tr>
												</tfoot>
											</table>
										</div>
										<a class="btn btn-xs blue" id="btn-add-trading" onclick="addItem('trading')"><i class="fa fa-plus"></i> Add Item</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php // echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>"printout('PRINT')"]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
										<?php 
										$modApproval = app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
										if(isset($_GET['pengajuan_pembelianlog_id'])){
											$status = true;
										}else{
											$status = false;
										}
										if(count($modApproval)>0){
											foreach($modApproval as $i => $approval){
												if($approval['status']== app\models\TApproval::STATUS_APPROVED){
													$status &= true;
												}else{
													$status &= false;
												}
											}
										}
										if($status == true){ ?>
										<h4 style="text-align: center;"><strong><?= Yii::t('app', 'Monthly Monitoring Log'); ?></strong></h4>
										<div class="row" style="margin-left: -20px; margin-right: -20px;">
											<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
												<div class="table-scrollable">
													<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-monitoring">
														<thead>
															<tr>
																<th style="width: 30px;" rowspan="2"><?= Yii::t('app', 'No.'); ?></th>
																<th style="line-height: 1; width: 150px;">Tanggal /<br>Lokasi Logpond</th>
																<th style="line-height: 1">Details</th>
																<th style="width: 50px;" rowspan="2"></th>
															</tr>
														</thead>
														<tbody>

														</tbody>
														<tfoot>

														</tfoot>
													</table>
												</div>
												<a class="btn btn-xs blue" id="btn-add-monitoring" onclick="addMonitoring()"><i class="fa fa-plus"></i> Add Item</a>
											</div>
										</div><br>
										<?php } ?>
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

<?php
$pagemode = "";
if(isset($_GET['pengajuan_pembelianlog_id']) && !isset($_GET['edit'])){
    $pagemode = "afterSave(".$_GET['pengajuan_pembelianlog_id']."); getMonitoring();";
}else if( isset($_GET['pengajuan_pembelianlog_id']) && isset($_GET['edit']) ){
	$pagemode = "afterSave(".$_GET['pengajuan_pembelianlog_id'].",".$_GET['edit'].");";
}else{
	$pagemode = "addItem('industri'); addItem('trading');";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Keputusan Pembelian Log'))."');
	$('select[name*=\"[suplier_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Suplier',
	});
	$('select[name*=\"[log_kontrak_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode PO',
	});
", yii\web\View::POS_READY); ?>
<script>
function addItem(tipe){
	var last_tr =  $("#table-detail-"+tipe+" > tbody > tr:last").find("input,select,textarea").serialize();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/addItem']); ?>',
        type   : 'POST',
        data   : {last_tr:last_tr,tipe:tipe},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail-'+tipe+' > tbody').fadeIn(100,function(){
                    reordertablethis('#table-detail-'+tipe);
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function cancelItemThis(ele,tipe){
	var no_urut = $(ele).parents("tr").find("#no_urut").val();
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
		total(tipe);
        reordertablethis();
    });
}

function reordertablethis(){
    var row = 0;
    $('#table-detail-industri > tbody > tr').each(function(iindustri){
        $(this).find("#no_urut").val(iindustri+1);
        $(this).find("span.no_urut").text(iindustri+1);
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 3){
                $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]);
                $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]");
            }
            if(old_name_arr.length == 4){
                    $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]+"_"+old_name_arr[3]);
                    $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]["+old_name_arr[3]+"]");
            }
        });
        row++;
    });
	$('#table-detail-trading > tbody > tr').each(function(itrading){
        $(this).find("#no_urut").val(itrading+1);
        $(this).find("span.no_urut").text(itrading+1);
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 3){
                $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]);
                $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]");
            }
            if(old_name_arr.length == 4){
                    $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]+"_"+old_name_arr[3]);
                    $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]["+old_name_arr[3]+"]");
            }
        });
        row++;
    });
    formconfig();
}

function total(tipe){
	var jml_subtotal_btg = 0;
	var jml_subtotal_m3 = 0;
	var jml_subtotal_harga = 0;
	// subtotal Horizontal
	$("#table-detail-"+tipe+" > tbody > tr").each(function(){
		var tr = $(this); var subtotal_btg = 0; var subtotal_m3 = 0; var subtotal_harga = 0;
		$(tr).find(".col-btg").each(function(){
			subtotal_btg += unformatNumber( $(this).val() );
		}).promise().done( function(){ 
			$(tr).find("input[name*='[total][qty_batang]']").val(subtotal_btg);
		});
		$(tr).find(".col-m3").each(function(){
			subtotal_m3 += unformatNumber( $(this).val() );
		}).promise().done( function(){ 
			// $(tr).find("input[name*='[total][qty_m3]']").val(formatNumberForUser(subtotal_m3));
			$(tr).find("input[name*='[total][qty_m3]']").val(subtotal_m3.toFixed(2));
		});
		$(tr).find(".col-harga").each(function(){
			subtotal_harga += unformatNumber( $(this).val() ) * unformatNumber( $(this).parents('td').prev().find('.col-m3').val() );
		}).promise().done( function(){ 
			$(tr).find("input[name*='[total][harga]']").val( formatNumberForUser(subtotal_harga) );
		});
	});
	
	// subtotal Vertical
	var sub_ver = []; 
	$("#table-detail-"+tipe+" > tfoot > tr:first").each(function(){
		$(this).find(".col-btg-foot").each(function(){
			var key = $(this).attr("name").replace(/]/g,"");
			key = key.split("["); key = key[1];
			sub_ver.push(key);
		});
	});
	$(sub_ver).each(function(key,val){
		var sub_btg = 0; var sub_m3 = 0; var sub_harga = 0;
		$("#table-detail-"+tipe+" > tbody > tr").each(function(){
			sub_btg += unformatNumber( $(this).find("input[name*='["+val+"][qty_batang]']").val() );
			sub_m3 += unformatNumber( $(this).find("input[name*='["+val+"][qty_m3]']").val() );
			sub_harga += unformatNumber( $(this).find("input[name*='["+val+"][harga]']").val() );
		});
		$("#table-detail-"+tipe+" > tfoot").find("input[name*='["+val+"][total_btg]']").val( sub_btg );
		// $("#table-detail-"+tipe+" > tfoot").find("input[name*='["+val+"][total_m3]']").val( sub_m3 );
		$("#table-detail-"+tipe+" > tfoot").find("input[name*='["+val+"][total_m3]']").val( sub_m3.toFixed(2) );
		$("#table-detail-"+tipe+" > tfoot").find("input[name*='["+val+"][total_harga]']").val( formatNumberForUser(sub_harga) );
	});
	
	// total
	setTimeout(function(){ 
		$("#table-detail-"+tipe+" > tbody > tr").each(function(){ 
			jml_subtotal_btg += unformatNumber( $(this).find("input[name*='[total][qty_batang]']").val() ); 
			jml_subtotal_m3 += unformatNumber( $(this).find("input[name*='[total][qty_m3]']").val() );
			jml_subtotal_harga += unformatNumber( $(this).find("input[name*='[total][harga]']").val() );
		}).promise().done( function(){ 
			$("#table-detail-"+tipe).find("input[name*='[total][total_btg]']").val(jml_subtotal_btg);
			// $("#table-detail-"+tipe).find("input[name*='[total][total_m3]']").val(formatNumberForUser(jml_subtotal_m3));
			$("#table-detail-"+tipe).find("input[name*='[total][total_m3]']").val(jml_subtotal_m3.toFixed(2));
			$("#table-detail-"+tipe).find("input[name*='[total][total_harga]']").val( formatNumberForUser(jml_subtotal_harga) );
			
			var total_m3_industri = unformatNumber( $("#table-detail-industri").find("input[name*='[total][total_m3]']").val() );
			var total_m3_trading = unformatNumber( $("#table-detail-trading").find("input[name*='[total][total_m3]']").val() );
			var total_m3_pembelian = total_m3_industri + total_m3_trading;
			<?php if( isset($_GET['edit']) || !isset($_GET['pengajuan_pembelianlog_id']) ){ ?>
				// $("#<?// yii\bootstrap\Html::getInputId($model, "total_volume") ?>").val( formatNumberForUser(total_m3_pembelian) );
				$("#<?= yii\bootstrap\Html::getInputId($model, "total_volume") ?>").val( total_m3_pembelian.toFixed(2) );
			<?php } ?>
		}); 
        setNote();
	},400);
}


function save(){
    var $form = $('#form-transaksi');
    $('#table-detail-permintaan > tbody > tr#empty-tr').remove();
    if(formrequiredvalidate($form)){
        var item_permintaan = $('#table-detail-permintaan tbody tr').length;
        var item_industri = $('#table-detail-industri tbody tr').length;
        var item_trading = $('#table-detail-trading tbody tr').length;
		$("#table-detail-industri tbody tr").each(function(){
			$(this).find('.error-tb-detail').removeClass('error-tb-detail');
		});
		$("#table-detail-trading tbody tr").each(function(){
			$(this).find('.error-tb-detail').removeClass('error-tb-detail');
		});
        if((item_permintaan <= 0)){
            cisAlert('Isi detail permintaan terlebih dahulu');
            return false;
        }
        if((item_industri <= 0) && (item_trading <= 0)){
			cisAlert('Isi detail terlebih dahulu');
            return false;
        }
		if(validatingDetail()){
			submitform($form);
        }
    }
    return false;
}

function validatingDetail($form){
	var has_error = 0;
    
    if(!$('textarea[name*="[keterangan_pembelian]"]').val()){
        $('textarea[name*="[keterangan_pembelian]"]').addClass('error-tb-detail');
        has_error = has_error + 1;
    }else{
        $('textarea[name*="[keterangan_pembelian]"]').removeClass('error-tb-detail');
    }
        
	$("#table-detail-industri tbody tr").each(function(){
		var field1 = $(this).find('select[name*="[kayu_id]"]');
		if(!field1.val()){
			$(this).find('select[name*="[kayu_id]"]').parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).find('select[name*="[kayu_id]"]').parents('td').removeClass('error-tb-detail');
		}
	});
	$("#table-detail-trading tbody tr").each(function(){
		var field1 = $(this).find('select[name*="[kayu_id]"]');
		if(!field1.val()){
			$(this).find('select[name*="[kayu_id]"]').parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(this).find('select[name*="[kayu_id]"]').parents('td').removeClass('error-tb-detail');
		}
	});
    
	if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
	getItemsById(id,"<?= isset($_GET['edit'])?$_GET['edit']:""; ?>");
	<?php if( (isset($_GET['pengajuan_pembelianlog_id'])) && !isset($_GET['edit'])){ ?>
		$('form').find('input').each(function(){ $(this).prop("disabled", true); });
		$('form').find('select').each(function(){ $(this).prop("disabled", true); });
		$('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
		$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
		$("#btn-add-permintaan").removeClass("blue").addClass("grey");
		$("#btn-add-industri").removeClass("blue").addClass("grey");
		$("#btn-add-trading").removeClass("blue").addClass("grey");
        $("#btn-add-permintaan").removeAttr("onclick");
		$("#btn-add-industri").removeAttr("onclick");
		$("#btn-add-trading").removeAttr("onclick");
		$("#btn-add-dinas").removeClass("blue").addClass("grey");
		$("#btn-add-dinas").removeAttr("onclick");
		$('.add-more').remove();
		$('#btn-save').attr('disabled','');
		$('#btn-print').removeAttr('disabled');
		$('#btn-print2').removeAttr('disabled');
		setTimeout(function(){
			$("#table-detail-permintaan").find(".btn.btn-xs.red").removeAttr("onclick");
			$("#table-detail-industri").find(".btn.btn-xs.red").removeAttr("onclick");
			$("#table-detail-trading").find(".btn.btn-xs.red").removeAttr("onclick");
			$("#table-detail-permintaan").find(".btn.btn-xs.red").removeClass("red").addClass("grey");
			$("#table-detail-industri").find(".btn.btn-xs.red").removeClass("red").addClass("grey");
			$("#table-detail-trading").find(".btn.btn-xs.red").removeClass("red").addClass("grey");
			$(".field-tattachment-file input").prop("disabled", false);
		},800);
	<?php }else{ ?>
		$('#<?= yii\bootstrap\Html::getInputId($model, 'kode') ?>').prop("disabled", true);
		setTimeout(function(){
			$("#table-detail-industri > tbody > tr").each(function(){
				$(this).find('select').prop("disabled", false);
				$(this).find('input').prop("disabled", false);
				$(this).find('input[name*="[total]"]').prop("disabled", true);
			});
		},800);
		setTimeout(function(){
			$("#table-detail-trading > tbody > tr").each(function(){
				$(this).find('select').prop("disabled", false);
				$(this).find('input').prop("disabled", false);
				$(this).find('input[name*="[total]"]').prop("disabled", true);
			});
		},800);
	<?php } ?>
}

function getItemsById(id,edit=null){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/getItemsById']); ?>',
		type   : 'POST',
		data   : {id:id,edit:edit},
		success: function (data) {
			if(data.html_pmr){
				$("#table-detail-permintaan > tbody").html(data.html_pmr);
                totalPermintaan();
			}
			if(data.html_industri){
				$("#table-detail-industri > tbody").html(data.html_industri);
				total("industri");
			}
			if(data.html_trading){
				$("#table-detail-trading > tbody").html(data.html_trading);
				total("trading");
			}
			reordertablethis();
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
	setTimeout(function(){
		formconfig();
	},500);
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function addPermintaan(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/openpermintaanlog']) ?>','modal-permintaanlog','90%');
}
function pick(kode){
	$("#modal-permintaanlog").find('button.fa-close').trigger('click');
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/pick']); ?>',
        type   : 'POST',
        data   : {kode:kode},
        success: function (data){
            if(data){
                var allowadd = true;
                $('#table-detail-permintaan > tbody > tr#empty-tr').remove();
                $('#table-detail-permintaan > tbody > tr').each(function(){
                    if($(this).find("input[name*='[pmr_id]']").val() != data.pmr_id){
                        allowadd &= true;
                    }else{
                        allowadd = false;
                    }
                });
                if(allowadd){
                    $(data.html).hide().appendTo('#table-detail-permintaan > tbody').fadeIn(100,function(){
                        reordertable("#table-detail-permintaan");
                        totalPermintaan();
                    });
                }else{
                    cisAlert("Permintaan ini sudah dipilih di list");
                }
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function totalPermintaan(){
    var total_permintaan = 0;
    $('#table-detail-permintaan > tbody > tr').each(function(){
        total_permintaan += unformatNumber( $(this).find("input[name*='[total_m3]']").val() );
    });
    $("#place-total-permintaan").html( formatNumberForUser(total_permintaan) );
    $("#<?= yii\helpers\Html::getInputId($model, "total_permintaan") ?>").val(total_permintaan)
    setNote();
}
function setNote(){
    var total_pembelian = unformatNumber( $("#<?= yii\helpers\Html::getInputId($model, "total_volume") ?>").val() );
    var total_permintaan = unformatNumber( $("#<?= yii\helpers\Html::getInputId($model, "total_permintaan") ?>").val() );
    if( total_pembelian > total_permintaan ){
        var warning = "<i>Note : Total Pembelian lebih besar dari total permintaan.</i>";
    }else{
        var warning = "";
    }
    $("#place-warning-overpembelian").html( warning );
    $("#<?= yii\helpers\Html::getInputId($model, 'keterangan') ?>").val( warning );
}
function detailPermintaan(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pmr/detailPermintaan']) ?>?id='+id,'modal-bbk','80%');
}

function getMonitoring(){
	var pengajuan_pembelianlog_id = "<?= (isset($_GET['pengajuan_pembelianlog_id'])?$_GET['pengajuan_pembelianlog_id']:"") ?>";
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/getMonitoring']); ?>?pengajuan_pembelianlog_id='+pengajuan_pembelianlog_id,
        type   : 'POST',
        data   : {},
        success: function (data){
            if(data.html){
                $('#table-detail-monitoring > tbody').html(data.html);
				$('#table-detail-monitoring > tbody').find("input,select,textarea").prop("disabled",true);
				$('#table-detail-monitoring > tbody').find('.input-group-btn > button').prop('disabled', true);
				formconfig();
				reordertable("#table-detail-monitoring");
				reordertable("#monitoring-detail");
                $('#table-detail-monitoring > tbody > tr').find('img').each(function(){ 
                    $(this).parents("a").magnificPopup({
                        type: 'image'
                    });
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function addMonitoring(){
	var last_tr =  $("#table-detail-monitoring > tbody > tr:last").find("input,select,textarea").serialize();
	var pengajuan_pembelianlog_id = "<?= (isset($_GET['pengajuan_pembelianlog_id'])?$_GET['pengajuan_pembelianlog_id']:"") ?>";
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/addMonitoring']); ?>?pengajuan_pembelianlog_id='+pengajuan_pembelianlog_id,
        type   : 'POST',
        data   : {},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail-monitoring > tbody').fadeIn(100,function(){
                    formconfig();
					reordertable("#monitoring-detail");
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function saveMonitoring(ele,pengajuan_pembelianlog_id){
	$(ele).parents("tr").find("input[name*='[tanggal]']").removeClass("error-tb-detail");
	$(ele).parents("tr").find("input[name*='[lokasi_logpond]']").removeClass("error-tb-detail");
	$(ele).parents("tr").find('#monitoring-detail > tbody > tr').each(function(){ 
		$(this).find("select[name*='[kayu_id]']").removeClass("error-tb-detail");
	});
	$(ele).parents("tr").find('#monitoring-detail > tbody > tr').each(function(){ 
		$(this).find("select[name*='[kondisi_global]']").removeClass("error-tb-detail");
	});
	if(!$(ele).parents("tr").find("input[name*='[tanggal]']").val()){
		$(ele).parents("tr").find("input[name*='[tanggal]']").addClass("error-tb-detail");
		return false;
	}
	if(!$(ele).parents("tr").find("input[name*='[lokasi_logpond]']").val()){
		$(ele).parents("tr").find("input[name*='[lokasi_logpond]']").addClass("error-tb-detail");
		return false;
	}
	$(ele).parents("tr").find('#monitoring-detail > tbody > tr').each(function(){ 
		if(!$(this).find("select[name*='[kayu_id]']").val()){
			$(this).find("select[name*='[kayu_id]']").addClass("error-tb-detail");
			return false;
		}
	});
	$(ele).parents("tr").find('#monitoring-detail > tbody > tr').each(function(){ 
		if(!$(this).find("select[name*='[kondisi_global]']").val()){
			$(this).find("select[name*='[kondisi_global]']").addClass("error-tb-detail");
			return false;
		}
	});
	var data = $(ele).parents("tr").find("input,select,textarea");
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/addMonitoring']); ?>?pengajuan_pembelianlog_id='+pengajuan_pembelianlog_id,
		type   : 'POST',
		data   : {data:data.serialize()},
		success: function (data) {
			if(data.status){
				cisAlert(data.message);
				getMonitoring();
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function deleteMonitoring(monitoring_pembelianlog_id){
	$(".modals-place-confirm").load('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/deleteMonitoring']) ?>?id='+monitoring_pembelianlog_id, function() {
		$("#modal-delete-record").modal('show');
		$("#modal-delete-record").on('hidden.bs.modal', function () {
			
		});
		spinbtn();
		draggableModal();
	});
}
function editMonitoring(ele){
	$(ele).parents("tr").find("input:not([name*='total']),select,textarea").prop("disabled",false);
	$(ele).parents("tr").find('.input-group-btn > button').prop('disabled', false);
	$(ele).parents("tr").find(".show-mode").attr("style","display:none;");
	$(ele).parents("tr").find(".input-mode").attr("style","display:");
	$(ele).parents("tr").find(".hidden").removeClass("hidden");
}

function addAttch(ele){
	var monitoring_pembelianlog_id = $(ele).parents("tr").find("input[name*='[monitoring_pembelianlog_id]']").val();
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/addAttch']) ?>?monitoring_pembelianlog_id='+monitoring_pembelianlog_id,'modal-addattch','90%');
}
function addMoreAttch(){
	$("#modal-addattch .col-md-2.hidden:first").removeClass('hidden');
}
function hapusAttch(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/deleteAttch','tableid'=>'table-master','id'=>'']); ?>'+id,'modal-delete-record');
}
function addMonitoringDetail(ele){
	var tr = $(ele).closest('tr');
	var clone = $(tr).clone();
	$(clone).find('select').val('');
	$(clone).find('.mondet-btg').val('0');
	$(clone).find('.mondet-m3').val('0');
	$(clone).find('.mondet-gr').val('0');
	$(clone).find('.mondet-pecah').val('0');
	$(clone).find('.mondet-cm').val('0');
	$(tr).after(clone);
	reordertable("#monitoring-detail");
}
function removeMonitoringDetail(ele){
	$(ele).closest('tr').remove();
	reordertable("#monitoring-detail");
}
function totalMonitoringDetail(ele){
	var btg = 0; var m3 = 0; var gr = 0; var pecah = 0; var cm = 0;
	$(ele).parents("#monitoring-detail").find("tbody > tr").each(function(){
		btg += unformatNumber( $(this).find(".mondet-btg").val() );
		m3 += unformatNumber( $(this).find(".mondet-m3").val());
		gr += unformatNumber( $(this).find(".mondet-gr").val());
		pecah += unformatNumber( $(this).find(".mondet-pecah").val());
		cm += unformatNumber( $(this).find(".mondet-cm").val());
	});
	$(ele).parents("#monitoring-detail").find("tfoot").find("input[name*='totalbtg']").val( formatNumberForUser(btg) );
	$(ele).parents("#monitoring-detail").find("tfoot").find("input[name*='totalm3']").val( formatNumberForUser(m3) );
	$(ele).parents("#monitoring-detail").find("tfoot").find("input[name*='totalgr']").val( formatNumberForUser(gr) );
	$(ele).parents("#monitoring-detail").find("tfoot").find("input[name*='totalpecah']").val( formatNumberForUser(pecah) );
	$(ele).parents("#monitoring-detail").find("tfoot").find("input[name*='totalcm']").val( formatNumberForUser(cm) );
}

function setKontrak(){
	var log_kontrak_id = $("#<?= yii\helpers\Html::getInputId($model, "log_kontrak_id") ?>").val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/setKontrak']); ?>?log_kontrak_id='+log_kontrak_id,
		type   : 'POST',
		data   : {},
		success: function (data) {
			if(data){
				$('#<?= yii\helpers\Html::getInputId($model, "nomor_kontrak") ?>').val(data.nomor);
				$('#<?= yii\helpers\Html::getInputId($model, "suplier_id") ?>').val(data.suplier_id).trigger('change');
				$('#<?= yii\helpers\Html::getInputId($model, "asal_kayu") ?>').val(data.asal_log);
				$('#<?= yii\helpers\Html::getInputId($model, "lokasi_muat") ?>').val(data.lokasi_muat);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function printoutPO(id){
	window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/kontraklog/print') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
//    window.open("<?= yii\helpers\Url::toRoute('/gudang/penerimaanko/printKartuBarang') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
</script>