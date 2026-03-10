<?php
/* @var $this yii\web\View */

use app\models\MDefaultValue;
use app\models\TApproval;
use app\models\TAttachment;
use app\models\TPoKo;

$this->title = 'Purchase Order (PO)';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
app\assets\FileUploadAsset::register($this);
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
        'labelOptions'=>['class'=>'col-md-5 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
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
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <span class="pull-right" style="margin-left: 10px; margin-right: 10px;">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'PO Yang Telah Dibuat'); ?></a>
                    </span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered" style="border: solid 1px;">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>
										<?php
										if(!isset($_GET['po_ko_id'])){
											echo "Purchase Order Baru";
										}else{
											echo "Data Purchase Order";
										}
										?>
									</h4></span>
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
										if(!isset($_GET['po_ko_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%', 'readonly'=>true]) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'tanggal_po',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'jenis_produk')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-produk'), [ 'disabled'=>'disabled']); ?>
										<?= $form->field($model, 'nomor_po')->textInput(); ?>
                                        <?= $form->field($model, 'sales_id')->dropDownList(\app\models\MSales::getOptionList(),['class'=>'form-control select2','prompt'=>'']); ?>
										<?= $form->field($model, 'syarat_jual')->inline(true)->radioList(app\models\MDefaultValue::getOptionList('syarat-jual')); ?>
                                        <?= $form->field($model, 'tanggal_kirim',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>

										<div class="form-group" id="files">
											<div class="col-md-12 text-right">
												<label class="col-md-5 control-label text-right" id="label_file">Foto/Image PO<br><br></label>
												<div class="row">
													<div class="col-md-12">
														<div id="place-attch">
															<?php
															// EDIT
															if(isset($_GET['edit'])){
																//for ($i = 1; $i <= 5; $i++) {
																	$sql_kode = "select kode from t_po_ko where po_ko_id = ".$model->po_ko_id."";
																	$kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();

																	$sql_attachment = "select * from t_attachment where reff_no = '".$kode."' and active = 'true' order by seq asc";
																	$query_attachment = Yii::$app->db->createCommand($sql_attachment)->queryAll();
																	$numrows_attachment = count($query_attachment);

																	$i = 1;
																	foreach ($query_attachment as $attachment) {
																		if (!empty($attachment['file_name'])) {
																			$file_name = $attachment['file_name'];

																			$sql_attachment_id = "select attachment_id from t_attachment where file_name = '".$file_name."' and active = 'true' order by seq asc ";
																			$attachment_id = Yii::$app->db->createCommand($sql_attachment_id)->queryScalar();
																			
																			$full_path_file_name = Yii::$app->homeUrl.'/uploads/mkt/purchaseorder/'.$file_name;
																			
																			$sql_ext = "select file_ext from t_attachment where file_name = '".$file_name."' and active = 'true' ";
																			$file_ext = Yii::$app->db->createCommand($sql_ext)->queryScalar();
																		} else {
																			$full_path_file_name = Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png';
																			$attachment_id = 0;
																		}

																		if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "bmp" || $file_ext == "png" || $file_ext == "gif" || $file_ext == "tiff") {

																			echo '<div class="col-md-2"></div>';
																			echo '<div class="col-md-2"></div>';
																			echo '<div class="col-md-1"></div>';
																			echo '<div class="col-md-2">';
																			echo $form->field($modAttachment, 'file'.$i,[
																				'template'=>'
																					<div class="col-md-12">
																						<div class="fileinput fileinput-new" data-provides="fileinput">
																						<div class="fileinput-new thumbnail" style="width: 55px;"/>
																							<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="showFile('.$attachment_id.')">
																								<img src="'.$full_path_file_name.'" alt=""/>
																							</a>
																							<div style="position: relative; bot: -25px; right: -25px; z-index: 1; width: 20px; height: 20px;">
																								<a class="btn btn-xs red" onclick="hapus_file('.$attachment_id.');"><i class="fa fa-remove btn-danger" aria-hidden="true"></i></a>
																							</div>
																						</div>
																						<div class="fileinput-preview fileinput-exists thumbnail"></div>
																							<div>
																								<span class="btn btn-xs blue-hoki btn-outline btn-file">
																									<span class="fileinput-new">Upload</span>
																									<span class="fileinput-exists"> Change </span>
																									<input type="file" class="tattachment-file" id="file'.$i.'" name="TAttachment[file'.$i.']">
																								</span> 
																								<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																								{error}
																							</div>
																						</div>
																					</div>'
																			])->fileInput();
																			echo '</div>';

																		} else {

																			echo '<div class="col-md-2"></div>';
																			echo '<div class="col-md-2"></div>';
																			echo '<div class="col-md-1"></div>';
																				echo '<div class="col-md-2">';
																				echo $form->field($modAttachment, 'file'.$i,[
																					'template'=>'
																						<div class="col-md-12">
																							<div class="fileinput fileinput-new" data-provides="fileinput">
																							<div class="fileinput-new thumbnail" style="width: 55px;"/>
																								<a class="btn btn-xs blue-hoki btn-outline tooltips" href="'.$full_path_file_name.'"><i class="fa fa-arrow-circle-down fa-2x" aria-hidden="true" style="padding: 5px;"></i></a>
																								<div style="position: relative; bot: -25px; right: -25px; z-index: 1; width: 20px; height: 20px;">
																									<a class="btn btn-xs red" onclick="hapus_file('.$attachment_id.');"><i class="fa fa-remove btn-danger" aria-hidden="true"></i></a>
																								</div>
																							</div>
																							<div class="fileinput-preview fileinput-exists thumbnail"></div>
																								<div>
																									<span class="btn btn-xs blue-hoki btn-outline btn-file">
																										<span class="fileinput-new">Upload</span>
																										<span class="fileinput-exists"> Change </span>
																										<input type="file" class="tattachment-file" id="file'.$i.'" name="TAttachment[file'.$i.']">
																									</span> 
																									<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																									{error}
																								</div>
																							</div>
																						</div>'
																				])->fileInput();
																				echo '</div>';																				

																			}
																		$i++;
																	}

																	$sisa = 3 - $numrows_attachment;
																	$nomer_terakhir_tambah_satu = $numrows_attachment + 1;
																	echo '<div class="col-md-2"></div>';
																	echo '<div class="col-md-2"></div>';
																	echo '<div class="col-md-1"></div>';
																	for ($i = $nomer_terakhir_tambah_satu; $i <= 3; $i++) {
																		echo '<div class="col-md-2">';
																		echo $form->field($modAttachment, 'file',[
																			'template'=>'
																				<div class="col-md-12">
																					<div class="fileinput fileinput-new" data-provides="fileinput">
																						<div class="fileinput-new thumbnail">
																							<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
																						<div class="fileinput-preview fileinput-exists thumbnail"></div>
																						<div>
																							<span class="btn btn-xs blue-hoki btn-outline btn-file">
																								<span class="fileinput-new">Upload</span>
																								<span class="fileinput-exists"> Change </span>
																								<input type="file" class="tattachment-file" id="file'.$i.'" name="TAttachment[file'.$i.']">
																							</span> 
																							<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																							{error}
																						</div>
																					</div>
																				</div>'
																		])->fileInput();
																		echo '</div>';
																	}

																//}
															} 
															// AFTERSAVE
															else if ((isset($_GET['success']) && $_GET['success'] == 1) || isset($model->po_ko_id)) {
																if (isset($jumlah_attachment)) {
																	$jumlah_attachment = $jumlah_attachment;
																} else {
																	$jumlah_attachmetn = 0;
																}

																$sql_kode = "select kode from t_po_ko where po_ko_id = ".$model->po_ko_id."";
																$kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();

																$sql_attachment = "select * from t_attachment where reff_no = '".$kode."' and active = 'true' order by seq asc";
																$query_attachment = Yii::$app->db->createCommand($sql_attachment)->queryAll();
																$numrows_attachment = count($query_attachment);

																$i = 1;
																foreach ($query_attachment as $attachment) {
																	$attachment_id = $attachment['attachment_id'];
																	$file_name = $attachment['file_name'];

																	$full_path_file_name = Yii::$app->homeUrl.'/uploads/mkt/purchaseorder/'.$file_name;
																	
																	$sql_ext = "select file_ext from t_attachment where file_name = '".$file_name."' and active = 'true' ";
																	$file_ext = Yii::$app->db->createCommand($sql_ext)->queryScalar();
																	
																	$sql_attachment_id = "select attachment_id from t_attachment where file_name = '".$file_name."' and active = 'true' order by seq asc";
																	$attachment_id = Yii::$app->db->createCommand($sql_attachment_id)->queryScalar();
																																		
																	$sql_ext = "select file_ext from t_attachment where file_name = '".$file_name."' and active = 'true' ";
																	$file_ext = Yii::$app->db->createCommand($sql_ext)->queryScalar();
																	
																	if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "bmp" || $file_ext == "png" || $file_ext == "giff" || $file_ext == "tiff") {
																		echo '<div class="col-md-2"></div>';
																		echo '<div class="col-md-2"></div>';
																		echo '<div class="col-md-1"></div>';
																		echo '<div class="col-md-2">';
																		echo $form->field($modAttachment, 'file',[
																			'template'=>'
																				<div class="col-md-12">
																					<div class="fileinput fileinput-new" data-provides="fileinput">
																						<div class="fileinput-new thumbnail" style="width: 55px;"/>
																							<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="showFile('.$attachment_id.')">
																								<img src="'.$full_path_file_name.'" alt="'.$full_path_file_name.'"/>
																							</a>
																						</div>
																						<div class="fileinput-preview fileinput-exists thumbnail"> </div>
																					</div>
																				</div>'
																		])->fileInput();
																		echo '</div>';
																	} else {
																		echo '<div class="col-md-2"></div>';
																		echo '<div class="col-md-2"></div>';
																		echo '<div class="col-md-1"></div>';
																		echo '<div class="col-md-2">';
																		echo $form->field($modAttachment, 'file',[
																			'template'=>'
																				<div class="col-md-12">
																					<div class="fileinput fileinput-new" data-provides="fileinput">
																						<div class="fileinput-new thumbnail" style="width: 55px;"/>
																							<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="showFile('.$attachment_id.')"><i class="fa fa-arrow-circle-down fa-2x" aria-hidden="true" style="padding: 5px;"></i></a>
																						</div>
																						<div class="fileinput-preview fileinput-exists thumbnail"> </div>
																					</div>
																				</div>'
																		])->fileInput();
																		echo '</div>';																		
																	}
																}
															}
															// INDEX
															else {
																echo '<div class="col-md-2"></div>';
																echo '<div class="col-md-2"></div>';
																echo '<div class="col-md-1"></div>';
																for ($i = 1; $i <=3; $i++) {	
																	echo '<div class="col-md-2">';
																	echo $form->field($modAttachment, 'file',[
																		'template'=>'
																			<div class="col-md-12">
																				<div class="fileinput fileinput-new" data-provides="fileinput">
																					<div class="fileinput-new thumbnail">
																						<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
																					<div class="fileinput-preview fileinput-exists thumbnail"> </div>
																					<div>
																						<span class="btn btn-xs blue-hoki btn-outline btn-file">
																							<span class="fileinput-new">Upload</span>
																							<span class="fileinput-exists"> Change </span>
																							<input type="file" class="tattachment-file" id="file'.$i.'" name="TAttachment[file'.$i.']">
																						</span> 
																						<a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
																						{error}
																					</div>
																				</div>
																			</div>'
																	])->fileInput();
																	echo '</div>';
																}
															}
															?>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-md-5 control-label">Status Approval</label>
											<div class="col-md-7">
												<?php
												if (isset($_GET['po_ko_id'])) {
													$modelApproval = \app\models\TApproval::find()->where(['reff_no'=>$model->kode])->all();
													foreach ($modelApproval as $modApproval) {
														if ($modApproval['status'] == "Not Confirmed") {
															$line_color = "blue-soft";
														} else if ($modApproval['status'] == "APPROVED") {
															$line_color = "green-seagreen";
														} else {
															$line_color = "red";
														}
																
														$sql_approver = "select pegawai_nama from m_pegawai where pegawai_id = ".$modApproval['assigned_to']."";
														$approver = Yii::$app->db->createCommand($sql_approver)->queryScalar();
														$jam = \app\components\DeltaFormatter::formatDateTimeForUser2($modApproval['updated_at']);
														$approves = \yii\helpers\Json::decode($model->approve_reason);
														$rejects = \yii\helpers\Json::decode($model->reject_reason);
														if ($modApproval['status'] == "APPROVED") {
															if(count($approves) > 0){
																foreach($approves as $i => $approve){
																	$by = $approve['by'];
																	if($by == $modApproval['assigned_to']){
																		$reasons = $approve['reason'];
																	}
																} 
															}
															$reason = "reason : $reasons";
														} else if($modApproval['status'] == "REJECTED") {
															if(count($rejects) > 0){
																foreach($rejects as $i => $reject){
																	$by = $reject['by'];
																	if($by == $modApproval['assigned_to']){
																		$reasons = $reject['reason'];
																		$reason = "reason : $reasons";
																	} else {
																		$reason = "";
																	}
																} 
															}
														} else {
															$reason = "";
														}
														echo "<a style='margin-top: 5px;' class='btn btn-outline btn-xs $line_color'><i class=''></i> <b>".$modApproval['status']."</b> <font style='color: #000;'>by <b>$approver</b> <br> at : $jam <br> $reason</font></a>&nbsp";
													}
												}
												?>
												<span class="help-block"></span>
											</div>
										</div>

										<?php if(isset($_GET['po_ko_id'])){ ?>
											<?php if($model->cancel_transaksi_id != null){ ?>
												<div class="form-group">
													<label class="col-md-5 control-label"><?= Yii::t('app', ''); ?></label>
													<div class="col-md-7" style="margin-top:7px;">
														<span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
														<?php
														$modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
														echo "<br><span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena ".$modCancel->cancel_reason."</span>";
														?>
													</div>
												</div>
											<?php }else if(!isset($_GET['edit'])){ ?>
												<div class="form-group">
													<label class="col-md-5 control-label"><?= Yii::t('app', ''); ?></label>
													<div class="col-md-7" style="margin-top:7px;">
														<?php if($model->status_approval !== 'APPROVED'){ ?>
															<a href="javascript:void(0);" onclick="cancelTransaksi(<?= $model->po_ko_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan PO'); ?></a>
														<?php }else{ ?>
															<a href="javascript:void(0);" class="btn default btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan PO'); ?></a>
														<?php } ?>
													</div>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
									<div class="col-md-6">
										<?php if(!isset($_GET['po_ko_id'])){ ?>
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Customer'); ?></label>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 75%">
													<?= \yii\bootstrap\Html::activeDropDownList($model, 'cust_id', \app\models\MCustomer::getOptionList(),['class'=>'form-control select2', 'prompt'=>'','onchange'=>'setCustomer()']); ?>
												</span>
												<span class="input-group-btn" style="width: 25%">
													<a class="btn btn-icon-only btn-default tooltips" onclick="pickCustomer();" data-original-title="Lihat Master Customer" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
												</span>
											</div>
										</div>
										<?php }else{ ?>
											<?= \yii\bootstrap\Html::activeHiddenInput($model, "cust_id") ?>
											<?= $form->field($model, 'customer')->textInput(['readonly'=>true]); ?>
										<?php } ?>
                                        <?= $form->field($model, 'cust_alamat')->textarea(['style'=>'width:90%', 'readonly' => 'readonly'])->label('Customer Alamat'); ?>
										<?php //$model->kota_cust = $model->kota_cust?$model->kota_cust:'Demak'; ?>
										<?= $form->field($model, 'kota_cust')->textInput(['style'=>'width:90%']); ?>
										<?= $form->field($model, 'alamat_bongkar')->textarea(['style'=>'width:90%']); ?>
										<?= $form->field($model, 'provinsi_bongkar')->dropDownList(MDefaultValue::getOptionList('provinsi_bongkar'),['class'=>'form-control','prompt'=>'','style'=>'width:200px;']); ?>
										<?= $form->field($model, 'sistem_bayar')->dropDownList(app\models\MDefaultValue::getOptionList('sistem-bayar'),['class'=>'form-control','style'=>'width:200px;', 'onchange'=>'setTopHari(); setSistemBayar()']); ?>
                                        <div class="form-group" id="place-top" style="display: <?= (!isset($_GET['po_ko_id'])?"none": (($model->sistem_bayar!="Tempo")?"none":"") ) ?>;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Term of Payment'); ?></label>
											<div class="col-md-4">
												<span class="input-group-btn" style="width: 100px;">
													<?php echo \yii\bootstrap\Html::activeTextInput($model, 'top_hari', ['class'=>'form-control float']) ?>
												</span>
												<span class="input-group-addon" style="padding-left: 5px; padding-right: 5px;">Hari </span>
											</div>
										</div>
                                        <?= $form->field($model, 'cara_bayar')->dropDownList(app\models\MDefaultValue::getOptionListCustom('cara-bayar',"'Klik-BCA'",'ASC'),['class'=>'form-control','style'=>'width:200px; margin-top:5px;', 'onchange'=>'setSistemBayar();']); ?>
                                        <div id='place-keterangan-bayar' style="display: <?= (!isset($_GET['po_ko_id'])?"none": (($model->keterangan_bayar=="")?"none":"") ) ?>;">
                                            <?= $form->field($model, 'keterangan_bayar')->textarea(['style'=>'width:90%']); ?>
                                        </div>
                                        <?= $form->field($model, 'tanggal_bayarmax',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'keterangan')->textarea(['style'=>'width:90%']); ?>
                                    </div>
                                </div>
                                <hr>
                                <?php // DETAIL ORDER ;?>
                                <div class="row" id="detail-order" style="margin-top: -20px; margin-bottom: -20px;">
                                    <div class="col-md-12">
                                        <h5 style="font-weight: bold;"><?= Yii::t('app', 'Detail Order'); ?></h5>
                                    </div>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.3rem;">No.</th>
                                                        <th style="width: 250px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Produk<br>Alias'); ?></th>
                                                        <th style="width: 150px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Diameter<br>Alias'); ?></th>
														<th style="line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Produk'); ?></th>
														<th style="width: 90px; line-height: 0.9;  padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Komposisi<br>(%)'); ?></th>
														<th style="width: 120px; line-height: 0.9;  padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Volume<br>(M<sup>3</sup>)'); ?></th>
														<th style="width: 150px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Harga'); ?></th>
														<th style="width: 50px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Cancel'); ?></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
												<tfoot>
													<tr>
														<td colspan="3">
															<a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
														</td>
														<td style="vertical-align: middle; text-align: right;">
															TOTAL &nbsp;
														</td>
														<?php if(!isset($_GET['po_ko_id']) || isset($_GET['edit'])){ ?>
															<td style="vertical-align: middle; text-align: right;">
																<?= yii\bootstrap\Html::textInput('total_komposisi',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
															</td>
															<td style="vertical-align: middle; text-align: right;">
																<?= yii\bootstrap\Html::textInput('total_kubikasi',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
															</td>
														<?php } else { ?>
															<td style="vertical-align: middle; text-align: center;">
																<span id="place-total_komposisi">0</span>
															</td>
															<td style="vertical-align: middle; text-align: right;">
																<span id="place-total_kubikasi">0</span>
															</td>
														<?php } ?>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
                                <hr>
		                        <div class="form-actions pull-right col-md-12 row">
		                            <div class="col-md-12 right">
										<div class="col-md-6 pull-right pull-right">
											<?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'save();']); ?>
											<?php 
											if(isset($_GET['po_ko_id'])){
												echo \yii\helpers\Html::button( Yii::t('app', 'Upload File/Gambar'),['id'=>'btn-approval','class'=>'btn hijau btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px; display: none;', 'onclick'=>'edit('. $_GET['po_ko_id'] .');']); 
											}
											?>
											<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'printPO('.(isset($_GET['po_ko_id'])?$_GET['po_ko_id']:'').');','disabled'=>true]); ?>
											<?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'resetForm();']); ?>
		                            	</div>
		                            </div>
		                        </div>
		                        <br>
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
$pagemode = "";
if(isset($_GET['po_ko_id'])){
    $pagemode = "afterSave(".$_GET['po_ko_id']."); ";
}else{
	$pagemode = "resetTableDetail();";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
	$('select[name*=\"[cust_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nomor Customer',
		ajax: {
			url: '".\yii\helpers\Url::toRoute('/marketing/customer/findCustomer')."',
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
	
	$('select[name*=\"[jenis_produk]\"]').change(function(){
		$('select[name*=\"[cust_id]\"]').empty().trigger('change');
		$('#table-detail tbody').html('');
        setHtmlTerimaJasa();
	});

	$('#form-hapus').submit( function(e){
		e.preventDefault();
		var form = $(this);
		var formData = form.serialize();
		$('.loading').addClass('animation-loading');
		$.ajax({
			url    : '".\yii\helpers\Url::toRoute(['/marketing/purchaseorder/hapusfile'])."',
			type   : 'POST',
			data   : formData,
			success: function (data) {
				if(data){
					$(data).each(function(){
						$('#loading').show();
						$('#xxx').load('".\yii\helpers\Url::toRoute(['/marketing/purchaseorder/xxx'])." div#yyy', {}, function () {
							if ($('#beres').length) {
								$('.loading').removeClass('animation-loading');
							} else {
								$('.loading').addClass('animation-loading');
							}
						});
					});
				}
			},
			complete: function(){
				//$('.loading').removeClass('animation-loading');
			},	
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
		return false;        
	});

	$('select[name*=\"[kota_cust]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kota Customer',
		ajax: {
			url: '".\yii\helpers\Url::toRoute('/marketing/purchaseorder/findKotaCust')."',
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
", yii\web\View::POS_READY); ?>

<script>
function setCustomer(){
	var cust_id = $('#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/setCustomer']); ?>',
        type   : 'POST',
        data   : {cust_id:cust_id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_alamat") ?>").val('');
			if(data.cust_id){
				$("#modal-master").find('button.fa-close').trigger('click');
				$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val(data.cust_an_alamat);
                if(data.cust_pr_alamat) {
                    $("#<?= yii\bootstrap\Html::getInputId($model, "cust_alamat") ?>").val(data.cust_pr_alamat);
                }else {
                    $("#<?= yii\bootstrap\Html::getInputId($model, "cust_alamat") ?>").val(data.cust_an_alamat);
                }
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'sistem_bayar') ?>').val('Bayar Lunas');
}

function pickCustomer(){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/customer/pick']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function pick(cust_id,par){
    par = par.replace("xyz","'");
	$("#modal-master").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").empty().append('<option value="'+cust_id+'">'+par+'</option>').val(cust_id).trigger('change');
}

function setTopHari(){
	var cust_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val();
	var jns_produk = $("#<?= \yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
	var sistem_bayar = $("#<?= \yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>").val();
	if(cust_id){
		$.ajax({
			url    : '<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/setTopHari']); ?>',
			type   : 'POST',
			data   : {cust_id:cust_id, jns_produk:jns_produk},
			success: function (data) {
				if(data.cust){
					if(data.top_hari){
						if(sistem_bayar == 'Tempo'){
							$('#<?= \yii\bootstrap\Html::getInputId($model, "top_hari") ?>').val(data.top_hari);
						}
					}else{
						if(sistem_bayar == 'Tempo'){
							cisAlert("TOP untuk customer "+data.cust.cust_an_nama+" belum diset");
							$('#<?= \yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>').val("Bayar Lunas");
							setSistemBayar();
						}
					}
				}
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
	}
}

function setSistemBayar(){
    var sistem_bayar = $("#<?= \yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>").val();
    var carabayar = $("#<?= \yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>").val();
    if( sistem_bayar == "Tempo"){
        $('#place-top').slideDown();
		$('#place-keterangan-bayar').slideDown();
    } else {
        $('#place-top').slideUp();
		$('#<?= \yii\bootstrap\Html::getInputId($model, "top_hari") ?>').val(0);
		if( carabayar == "Bilyet Giro" || carabayar == "Cek"){
            $('#place-keterangan-bayar').slideDown();
        } else {
            $('#place-keterangan-bayar').slideUp();
			$("#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_bayar") ?>").val('');
        }
    }
}

function addItem(){
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var notin = [];
	$('#table-detail > tbody > tr').each(function(){
		var produk_id = $(this).find('select[name*="[produk_id]"]');
		if( produk_id.val() ){
			notin.push(produk_id.val());
		}
	});
	if(notin){
		notin = JSON.stringify(notin);
	}    
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/addItem']); ?>',
        type   : 'POST',
        data   : {jns_produk:jns_produk},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                        $(this).find('select[name*="[produk_id]"]').select2({
                            allowClear: !0,
							placeholder: 'Ketik kode produk',
                            width: '100%',
                            ajax: {
								url: '<?= \yii\helpers\Url::toRoute('/marketing/purchaseorder/findProdukActive') ?>',
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    var query = {
                                      term: params.term,
                                      type: jns_produk,
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
						$(this).find('.select2-selection').css('font-size','1.2rem');
						$(this).find('.select2-selection').css('padding-left','5px');
						$(this).find(".tooltips").tooltip({ delay: 50 });
						reordertable('#table-detail');
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function masterLog(ele){
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'jenis_produk') ?>').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/openlog','disableAction'=>'']); ?>1&tr_seq='+tr_seq+"&jenis_produk="+jenis_produk;
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master-log .modal-dialog").css('width','75%');
		$("#modal-master-log").modal('show');
		$("#modal-master-log").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function pickProduk(produk_id, tr_seq, data, stock_pcs, stock_kubik, stock_palet, harga_enduser, kode){
	var jns_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/pickProduk']); ?>',
        type   : 'POST',
        data   : {produk_id:produk_id,jns_produk:jns_produk},
        success: function (data) {
			if(data){
				if(jns_produk == "Log"){
					var already = [];
					$('#table-detail > tbody > tr').each(function(){
						var produk_id = $(this).find('select[name*="[produk_id]"]');
						if( produk_id.val() ){
							already.push(produk_id.val());
						}
					});
					if( $.inArray(  data.produk_id.toString(), already ) != -1 ){ // Jika ada yang sama
						cisAlert("Produk ini sudah dipilih di list");
						return false;
					}else{
                        if(jns_produk == "Log"){
							$("#modal-master-log").find('button.fa-close').trigger('click');
                            $("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[produk_id]']").empty().append('<option value="'+data.log_id+'">'+data.log_nama+'</option>').val(data.log_id).trigger('change');
							$("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("input[name*='[harga_jual_lama]']").empty().val(harga_enduser).trigger('change');
						} else {
                            $("#modal-master-produk").find('button.fa-close').trigger('click');
                            $("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[produk_id]']").empty().append('<option value="'+data.produk_id+'">'+data.produk_kode+'</option>').val(data.produk_id).trigger('change');
                            $("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("input[name*='[harga_jual_lama]']").empty().val(harga_enduser).trigger('change');
                        }
					}
				}else{
					cisAlert("Jenis produk ini tidak sama dengan jenis produk yang terpilih");
					return false;
				}
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function save() {
    var form = $('#form-transaksi');

    var jumlah_item = $('#table-detail tbody tr').length;
    if (jumlah_item == 0) {
		cisAlert('Isi detail terlebih dahulu');
    }
	
    if (validatingDetail()) {
		submitform(form);
    } else {
		return false;
	}
	
}

function validatingDetail(){
    var has_error = 0;
	var cust_id = $("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>");
	var prov_bongkar = $("#<?= yii\bootstrap\Html::getInputId($model, "provinsi_bongkar") ?>");
	var cara_bayar = $("#<?= yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>");
	var ket_bayar = $("#<?= yii\bootstrap\Html::getInputId($model, "keterangan_bayar") ?>");
	var sistem_bayar = $("#<?= yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>");
	var tot_komposisi = $('input[name="total_komposisi"]');
	var kota_cust = $("#<?= yii\bootstrap\Html::getInputId($model, "kota_cust") ?>");
	if(!cust_id.val()){
		$(cust_id).parents('.form-group').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(cust_id).parents('.form-group').removeClass('error-tb-detail');
	}
	if(!prov_bongkar.val()){
		$(prov_bongkar).parents('.form-group').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(prov_bongkar).parents('.form-group').removeClass('error-tb-detail');
	}
	if(cara_bayar.val() == 'Bilyet Giro' || cara_bayar.val() == 'Cek' || sistem_bayar.val() == 'Tempo'){
		if(!ket_bayar.val()){
			$("#<?= yii\bootstrap\Html::getInputId($model, "keterangan_bayar") ?>").parents('.form-group').addClass('error-tb-detail');
			has_error = has_error + 1;
		} else {
			$("#<?= yii\bootstrap\Html::getInputId($model, "keterangan_bayar") ?>").parents('.form-group').removeClass('error-tb-detail');
		}
	} else {
		$("#<?= yii\bootstrap\Html::getInputId($model, "keterangan_bayar") ?>").parents('.form-group').removeClass('error-tb-detail');
	}
	if(tot_komposisi.val() < 100 || tot_komposisi.val() > 100){
		$('input[name="total_komposisi"]').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$('input[name="total_komposisi"]').removeClass('error-tb-detail');
	}
	if(!kota_cust.val()){
		$(kota_cust).parents('.form-group').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(kota_cust).parents('.form-group').removeClass('error-tb-detail');
	}
    $('#table-detail tbody > tr').each(function(){
		var field1 = $(this).find('input[name*="[produk_alias]"]');
		var field2 = $(this).find('input[name*="[diameter_alias]"]');
		var field3 = $(this).find('select[name*="[produk_id]"]');
		var field4 = $(this).find('input[name*="[komposisi]"]');
		var field5 = $(this).find('input[name*="[kubikasi]"]');
		var field6 = $(this).find('input[name*="[harga]"]');
		var field7 = $(this).find('select[name*="[produk_id_alias]"]');

		if(!field1.val()){
			$(field1).parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(field1).parents('td').removeClass('error-tb-detail');
		}
		if(!field2.val()){
			$(field2).parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(field2).parents('td').removeClass('error-tb-detail');
		}

		if($(this).find('input[name*="[alias]"]').is(":checked")){
			if(!field7.val()){
				$(this).find('select[name*="[produk_id_alias]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('select[name*="[produk_id_alias]"]').parents('td').removeClass('error-tb-detail');
			}
		} else {
			if(!field3.val()){
				$(this).find('select[name*="[produk_id]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('select[name*="[produk_id]"]').parents('td').removeClass('error-tb-detail');
			}
		}
		// if(!field3.val()){
		// 	$(this).find('select[name*="[produk_id]"]').parents('td').addClass('error-tb-detail');
		// 	has_error = has_error + 1;
		// }else{
		// 	$(this).find('select[name*="[produk_id]"]').parents('td').removeClass('error-tb-detail');
		// }
		if(!field4.val()){
			$(field4).parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(field4).parents('td').removeClass('error-tb-detail');
		}
		if(!field5.val()){
			$(field5).parents('td').addClass('error-tb-detail');
			has_error = has_error + 1;
		}else{
			$(field5).parents('td').removeClass('error-tb-detail');
		}
		if(!field6.val()){
			has_error = has_error + 1;
			$(this).find('input[name*="[harga]"]').parents('td').addClass('error-tb-detail');
		}else{
			if( $(this).find('input[name*="[harga]"]').val() == 0 ){
				has_error = has_error + 1;
				$(this).find('input[name*="[harga]"]').parents('td').addClass('error-tb-detail');
			}else{
				$(this).find('input[name*="[harga]"]').parents('td').removeClass('error-tb-detail');
			}
		}
    });

    if(has_error === 0){
        return true;
    } else {
		return false;
	}
}

function resetTableDetail(){
	$('#table-detail tbody').html('');
	addItem();
}

function hitungTotal(){
	var komposisi = 0; var kubikasi = 0;
	$('#table-detail tbody > tr').each(function(){
		var jml_komposisi = unformatNumber($(this).find('input[name*="[komposisi]"]').val());
		var jml_kubikasi = unformatNumber($(this).find('input[name*="[kubikasi]"]').val());
		komposisi += jml_komposisi;
		kubikasi += jml_kubikasi;
	});
	<?php if(!isset($_GET['po_ko_id']) || isset($_GET['edit'])){ ?>
		$('input[name="total_komposisi"]').val( formatNumberForUser(komposisi) );
		$('input[name="total_kubikasi"]').val( formatNumberForUser(kubikasi) );
	<?php } else{ ?>
		$('#place-total_komposisi').text(formatNumberForUser(komposisi));
		$('#place-total_kubikasi').text(formatNumberForUser(kubikasi));
	<?php } ?>
	// console.log($('#place-total_kubikasi'));
}

function afterSave(id){
	<?php if(!isset($_GET['edit'])) { ?>
		getItems(id);
		$('#btn-add-item').hide();
		$('form').find('input').each(function(){ $(this).prop("disabled", true); });
	<?php } else { ?>
		getItems(id,1);
	<?php } ?>
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_po') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_kirim') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_bayarmax') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');

	<?php if(isset($_GET['edit'])){ ?>
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_po") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal_po') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "nomor_po") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "sales_id") ?>').prop("disabled", false);
		$('input[name*="[syarat_jual]"]').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_kirim") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal_kirim') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal_bayarmax') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'alamat_bongkar') ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "top_hari") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "keterangan_bayar") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "keterangan") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "provinsi_bongkar") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modAttachment, "file") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modAttachment, "file1") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modAttachment, "file2") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modAttachment, "file3") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "kota_cust") ?>').prop("disabled", false);
		$('#btn-save').prop('disabled',false);
		$('#btn-print').prop('disabled',true);
	<?php } else if(!isset($_GET['edit']) && isset($_GET['po_ko_id'])) { ?>
		<?php 
		$model = TPoKo::findOne($_GET['po_ko_id']); 
		$modAttachment = TAttachment::find()->where(['reff_no'=>$model->kode])->all();
		if(count($modAttachment) > 0){
			$gambar = 'ada';
		} else {
			$gambar = 'kosong';
		}
		?>
		var gambar = '<?php echo $gambar; ?>';
		var approval = '<?= $model->status_approval; ?>';
		var cancel = '<?= !empty($model->cancel_transaksi_id)?'ABORTED':'no' ?>';
		if(gambar == 'kosong' && approval == 'Not Confirmed' && cancel !== 'ABORTED'){
			$('#btn-approval').css('display', '');
			$('#btn-save').css('display', 'none');
			$('#btn-print').removeAttr('disabled');
		} else {
			$('#btn-save').attr('disabled','');
			$('#btn-print').prop('disabled',true);
		}
	<?php } ?>
	setSistemBayar();
}

function getItems(po_ko_id,edit=null){
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/getItems']); ?>',
		type   : 'POST',
		data   : {po_ko_id:po_ko_id,edit:edit},
		success: function (data) {
			$('#<?= \yii\bootstrap\Html::getInputId($model, "cust_alamat") ?>').val(data.alamat);
			if(data.html){
				$('#table-detail tbody').html(data.html);
				if(edit){ // edit load item process
					var notin = [];
					$('#table-detail > tbody > tr').each(function(){
						var produk_id = $(this).find('select[name*="[produk_id]"]');
						if( produk_id.val() ){
							notin.push(produk_id.val());
						}
					});
					if(notin){
						notin = JSON.stringify(notin);
					}
					reordertable('#table-detail');

					$('#table-detail tbody tr').each(function(){ 
						if($(this).find('input[name*="[alias]"]').is(":checked")){
							$(this).find('#block-produk_id_alias').css('display', '');
							$(this).find('#block-produk_id').css('display', 'none');
							var id = $(this).find('input[name*="[po_ko_detail_id]"]').val();
							$(this).find('select[name*="[produk_id_alias]"]').select2({
								placeholder: 'Ketik Nama Produk',
								ajax: {
									url: '<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/setDDProdukIdAlias']); ?>',
									type   : 'POST',
									data: function (params) {
										return {
											id: id,
										};
									},
									processResults: function (data) {
										// return {
										// 	results: data
										// };
										console.log(data);
									},
								}
							});
						} else {
							$(this).find('select[name*="[produk_id]"]').select2({
								allowClear: !0,
								placeholder: 'Ketik kode produk',
								width: '100%',
								ajax: {
									url: '<?= \yii\helpers\Url::toRoute('/marketing/purchaseorder/findProdukActive') ?>',
									dataType: 'json',
									delay: 250,
									data: function (params) {
										var query = {
										term: params.term,
										type: jns_produk,
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
						}
						$(this).find('.select2-selection').css('font-size','1.2rem');
						$(this).find('.select2-selection').css('padding-left','5px');
						$(this).find(".tooltips").tooltip({ delay: 50 });
						$(this).find("input[name*='[harga]']").removeAttr("disabled");
						// reordertable('#table-detail');
					});
					
				}
			}
			setTimeout(function(){
				hitungTotal();
			},500);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/index','po_ko_id'=>'']); ?>'+id+'&edit=1');
}

function hapus_file(attachment_id) {
	var x = confirm("Yakin mau dihapus ?");
	if (x) {
		$( "#files" ).load("<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/hapusfile?attachment_id=']);?>attachment_id", {attachment_id:attachment_id});
    	return true;
	} else {
    	return false;
	}
}

function printPO(id){
	window.open("<?= yii\helpers\Url::toRoute('/marketing/purchaseorder/printPO') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function cancelTransaksi(id){
	openModal('<?php echo \yii\helpers\Url::toRoute(['/marketing/purchaseorder/cancelTransaksi']) ?>?id='+id,'modal-transaksi');
}

function showFile(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/showFile','id'=>'']) ?>'+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-file").modal('show');
		$("#modal-file").on('hidden.bs.modal', function () { });
		$("#modal-file .modal-dialog").css('width',"1000px");
		spinbtn();
		draggableModal();
	});
}

function setFieldProduk(ele){
	if($(ele).is(":checked")){
		$(ele).closest('tr').find('#block-produk_id_alias').css('display', '');
		$(ele).closest('tr').find('#block-produk_id').css('display', 'none');
		setDDProdukIdAlias(ele);
	} else {
		$(ele).closest('tr').find('#block-produk_id_alias').css('display', 'none');
		$(ele).closest('tr').find('#block-produk_id').css('display', '');
	}
	
}

function setDDProdukIdAlias(ele){
	var id = $(ele).closest('tr').find('input[name*="[po_ko_detail_id]"]').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/purchaseorder/setDDProdukIdAlias']); ?>',
        type   : 'POST',
        data   : {id: id},
        success: function (data){
			if(data.html){
				$(ele).closest('tr').find('select[name*="[produk_id_alias]"]').html(data.html);
				$(ele).closest('tr').find('select[name*="[produk_id_alias]"]').select2({
                    placeholder: 'Ketik Nama Produk',
                });
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

</script>