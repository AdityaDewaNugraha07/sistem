<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan Jasa';
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
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSaveX()"><i class="fa fa-list"></i> <?= Yii::t('app', 'OP Yang Telah Dibuat'); ?></a>
                    </span>
					<?php /*<span class="pull-right" style="margin-left: 10px; margin-right: 10px;">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Ketidaksesuaian OP'); ?></a> */?>
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered" style="border: solid 1px;">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>
										<?php
										if(!isset($_GET['op_ko_id'])){
											echo "Order Penjualan Baru";
										}else{
											echo "Data Order Penjualan";
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
                                <div class="row" style="margin-bottom: -30px;">
                                    <div class="col-md-5">
										<?php
										if(!isset($_GET['op_ko_id'])){
											echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%','readonly'=> true]) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
                                        <?php } ?>
                                        <?= $form->field($model, 'tanggal')->textInput(['readonly'=>true]); ?>
										<?= $form->field($model, 'jenis_produk')->dropDownList(\app\models\MDefaultValue::getOptionList('jenis-produk'),['onchange'=>'openPoFamily();', 'readonly'=> true]); ?>
										<?= $form->field($model, 'pp_no')->textInput(['readonly'=> true]); ?>
                                        <?php /*<?= $form->field($model, 'sales_id')->dropDownList(\app\models\MSales::getOptionList(),['class'=>'form-control select2','prompt'=>'']); ?>
										<?= $form->field($model, 'disetujui')->dropDownList(\app\models\MPegawai::getOptionListByDept(\app\components\Params::DEPARTEMENT_ID_MARKETING),['class'=>'form-control select2','prompt'=>''])->label('Disetujui Oleh'); ?>*/?>
                                        
                                        <div class="form-group field-topko-sales_id required">
                                            <label class="col-md-5 control-label" for="topko-sales_id">Sales</label>
                                            <div class="col-md-7">
                                                <input type="hidden" id="topko-sales_id" class="form-control" name="TOpKo[sales_id]" value="<?php echo $model->sales_id;?>" readonly="" aria-required="true">
                                                <input type="text" id="topko-sales_id" class="form-control" value="<?php echo $sales_nm;?>" readonly="" aria-required="true">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group field-topko-sales_id required">
                                            <label class="col-md-5 control-label" for="topko-sales_id">Disetujui</label>
                                            <div class="col-md-7">
                                                <input type="hidden" id="topko-sales_id" class="form-control" name="TOpKo[disetujui]" value="<?php echo $model->disetujui;?>" readonly="" aria-required="true">
                                                <input type="text" id="topko-sales_id" class="form-control" value="<?php echo $disetujui;?>" readonly="" aria-required="true">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <?= $form->field($model, 'syarat_jual')->textInput(['readonly'=>true]); ?>

										<?php /*<div id="po_family" style="display: none;">
                                            <?= $form->field($model, 'po')->textInput(['readonly'=>true]); ?>
                                            <?= $form->field($model, 'tanggal_po')->textInput(['readonly'=>true]); ?>
										</div>*/?>

										<?php /*<div class="form-group" id="files">
											<div class="col-md-12 text-right">
												<label class="col-md-5 control-label text-right" id="label_file">Foto/Image PO<br><br></label>
												<div class="row">
													<div class="col-md-12">
														<div id="place-attch">
															<?php
															// EDIT
															if(isset($_GET['edit'])){
																//for ($i = 1; $i <= 5; $i++) {
																	$sql_kode = "select kode from t_op_ko where op_ko_id = ".$model->op_ko_id."";
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
																			
																			$full_path_file_name = Yii::$app->homeUrl.'/uploads/mkt/po/'.$file_name;
																			
																			$sql_ext = "select file_ext from t_attachment where file_name = '".$file_name."' and active = 'true' ";
																			$file_ext = Yii::$app->db->createCommand($sql_ext)->queryScalar();
																		} else {
																			$full_path_file_name = Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png';
																			$attachment_id = 0;
																		}

																		if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "bmp" || $file_ext == "png" || $file_ext == "gif" || $file_ext == "tiff") {

																			echo '<div class="col-md-2">';
																			echo $form->field($modAttachment, 'file'.$i,[
																				'template'=>'
																					<div class="col-md-12">
																						<div class="fileinput fileinput-new" data-provides="fileinput">
																						<div class="fileinput-new thumbnail" style="width: 55px;"/>
																							<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('.$attachment_id.')">
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

																	$sisa = 6 - $numrows_attachment;
																	$nomer_terakhir_tambah_satu = $numrows_attachment + 1;
																	for ($i = $nomer_terakhir_tambah_satu; $i <= 6; $i++) {
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
															else if ((isset($_GET['success']) && $_GET['success'] == 1) || isset($model->op_ko_id)) {
																if (isset($jumlah_attachment)) {
																	$jumlah_attachment = $jumlah_attachment;
																} else {
																	$jumlah_attachmetn = 0;
																}

																$sql_kode = "select kode from t_op_ko where op_ko_id = ".$model->op_ko_id."";
																$kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();

																$sql_attachment = "select * from t_attachment where reff_no = '".$kode."' and active = 'true' order by seq asc";
																$query_attachment = Yii::$app->db->createCommand($sql_attachment)->queryAll();
																$numrows_attachment = count($query_attachment);

																$i = 1;
																foreach ($query_attachment as $attachment) {
																	$attachment_id = $attachment['attachment_id'];
																	$file_name = $attachment['file_name'];

																	$full_path_file_name = Yii::$app->homeUrl.'/uploads/mkt/po/'.$file_name;
																	
																	$sql_ext = "select file_ext from t_attachment where file_name = '".$file_name."' and active = 'true' ";
																	$file_ext = Yii::$app->db->createCommand($sql_ext)->queryScalar();
																	
																	$sql_attachment_id = "select attachment_id from t_attachment where file_name = '".$file_name."' and active = 'true' order by seq asc";
																	$attachment_id = Yii::$app->db->createCommand($sql_attachment_id)->queryScalar();
																																		
																	$sql_ext = "select file_ext from t_attachment where file_name = '".$file_name."' and active = 'true' ";
																	$file_ext = Yii::$app->db->createCommand($sql_ext)->queryScalar();
																	
																	if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "bmp" || $file_ext == "png" || $file_ext == "giff" || $file_ext == "tiff") {
																		echo '<div class="col-md-2">';
																		echo $form->field($modAttachment, 'file',[
																			'template'=>'
																				<div class="col-md-12">
																					<div class="fileinput fileinput-new" data-provides="fileinput">
																						<div class="fileinput-new thumbnail" style="width: 55px;"/>
																							<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('.$attachment_id.')">
																								<img src="'.$full_path_file_name.'" alt="'.$full_path_file_name.'"/>
																							</a>
																						</div>
																						<div class="fileinput-preview fileinput-exists thumbnail"> </div>
																					</div>
																				</div>'
																		])->fileInput();
																		echo '</div>';
																	} else {
																		echo '<div class="col-md-2">';
																		echo $form->field($modAttachment, 'file',[
																			'template'=>'
																				<div class="col-md-12">
																					<div class="fileinput fileinput-new" data-provides="fileinput">
																						<div class="fileinput-new thumbnail" style="width: 55px;"/>
																							<a class="btn btn-xs blue-hoki btn-outline tooltips" href="'.$full_path_file_name.'"><i class="fa fa-arrow-circle-down fa-2x" aria-hidden="true" style="padding: 5px;"></i></a>
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
																for ($i = 1; $i <=6; $i++) {																	
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
										</div> */?>

										<?php if(isset($_GET['op_ko_id'])){ ?>
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
													<?php
													$sql_kode = "select kode from t_op_ko where op_ko_id = ".$_GET['op_ko_id']." ";
													$kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();

													$sql_status_approval = "select status from t_approval where reff_no = '".$kode."' ";
													$status_approval = Yii::$app->db->createCommand($sql_status_approval)->queryScalar();
													
													if ($status_approval == 'Not Confirmed') {
													?>

													<?php /*if(empty(app\models\TSpmKo::findOne(['op_ko_id'=>$model->op_ko_id]))){ ?>
														<a href="javascript:void(0);" onclick="cancelTransaksi(<?= $model->op_ko_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan OP'); ?></a>
													<?php }else{ ?>
														<a href="javascript:void(0);" class="btn default btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan OP'); ?></a>
													<?php } */?>

													<?php
													}
													?>

												</div>
											</div>
										<?php } ?>
										<?php } ?>
									</div>
									<div class="col-md-6">
										<?php if(!isset($_GET['op_ko_id'])){ ?>
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Customer'); ?></label>
											<div class="col-md-7">
												<span class="input-group-btn" style="width: 75%">
													<?= \yii\bootstrap\Html::activeDropDownList($model, 'cust_id', \app\models\MCustomer::getOptionList(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setCustomer()']); ?>
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
										<?php /*<?= $form->field($model, 'tanggal_kirim',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['readonly'=>true]); ?> */?>
                                        <?= $form->field($model, 'tanggal_kirim')->textInput(['readonly'=>true]); ?>
										<?= $form->field($model, 'cust_alamat')->textarea(['style'=>'width:90%', 'readonly'=>true]); ?>
										<?= $form->field($model, 'alamat_bongkar')->textarea(['style'=>'width:90%', 'readonly'=>true]); ?>
										<?= $form->field($model, 'provinsi_bongkar')->dropDownList(["BANTEN"=>"BANTEN","JAWA BARAT"=>"JAWA BARAT","JAWA TENGAH"=>"JAWA TENGAH","YOGYAKARTA"=>"YOGYAKARTA","JAWA TIMUR"=>"JAWA TIMUR","BALI"=>"BALI"],['class'=>'form-control','prompt'=>'','style'=>'width:200px;', 'readonly'=>true]); ?>
										
										<?php /*<div class="form-group">
											<label class="col-md-5 control-label">Status Approval</label>
											<div class="col-md-7">
												<?php
												if (isset($_GET['op_ko_id'])) {
													$approves = \yii\helpers\Json::decode($model->approve_reason);
													$rejects = \yii\helpers\Json::decode($model->reject_reason);

													if(count($approves)>0){
														foreach($approves as $i => $approve){
															$sql_approval = "select m_pegawai.pegawai_nama from t_approval left join m_pegawai on m_pegawai.pegawai_id = t_approval.assigned_to where reff_no = '".$model->kode."' AND assigned_to = ".$approve['by']."";
															//echo "<br>".count($approves)."<br>".$sql_approval;
															$approval = Yii::$app->db->createCommand($sql_approval)->queryScalar();
															echo \yii\helpers\Html::button( Yii::t('app', 'by : '.$approval.'<br>reason : '.$approve['reason'].'<br>tanggal : '.\app\components\DeltaFormatter::formatDateTimeForUser2($approve['at'])),['class'=>'btn green btn-outline ciptana-spin-btn pull-left text-left', 'style' => 'text-align: left; font-size: 10px; margin-right: 10px; margin-top: 10px;']);
														}
													}

													if(count($rejects)>0){
														foreach($rejects as $i => $reject){
															$sql_approval = "select m_pegawai.pegawai_nama from t_approval left join m_pegawai on m_pegawai.pegawai_id = t_approval.assigned_to where reff_no = '".$model->kode."' AND assigned_to = ".$reject['by']."";
															//echo "<br>".count($rejects)."<br>".$sql_approval;
															$approval = Yii::$app->db->createCommand($sql_approval)->queryScalar();
															echo \yii\helpers\Html::button( Yii::t('app', 'by : '.$approval.'<br>reason : '.$reject['reason'].'<br>tanggal : '.\app\components\DeltaFormatter::formatDateTimeForUser2($reject['at'])),['class'=>'btn red btn-outline ciptana-spin-btn pull-left text-left', 'style' => 'text-align: left; font-size: 10px; margin-left: 10px;']);
														}
													}
												}
												?>
												<span class="help-block"></span>
											</div>
										</div>*/?>
                                        
                                        <div style="display: none;">
                                            <?= $form->field($model, 'sistem_bayar')->dropDownList(app\models\MDefaultValue::getOptionList('sistem-bayar'),['class'=>'form-control','onchange'=>'setSistembayarDisplay(); setVal();','style'=>'width:200px;']); ?>
                                            <div class="form-group" id="place-top" style="display: <?= (!isset($_GET['op_ko_id'])?"none": (($model->sistem_bayar!="Tempo")?"none":"") ) ?>;">
                                                <label class="col-md-5 control-label"><?= Yii::t('app', 'Term of Payment'); ?></label>
                                                <div class="col-md-4">
                                                    <span class="input-group-btn" style="width: 100px;">
                                                        <?= \yii\bootstrap\Html::activeTextInput($modTempo, 'top_hari', ['class'=>'form-control float','onblur'=>'setVerify()']) ?>
                                                        <?= \yii\bootstrap\Html::activeHiddenInput($modTempo, "maks_top_hari", ['class'=>'form-control float']) ?>
                                                    </span>
                                                    <span class="input-group-addon" style="padding-left: 5px; padding-right: 5px;">Hari </span>
                                                </div>
                                            </div>
                                            <?= $form->field($model, 'cara_bayar')->dropDownList(app\models\MDefaultValue::getOptionListCustom('cara-bayar',"'Klik-BCA'",'ASC'),['class'=>'form-control','style'=>'width:200px; margin-top:5px;']); ?>
                                            <?= \yii\bootstrap\Html::activeHiddenInput($model, "status") ?>
                                            <div id="place-plafond" style="display: <?= (!isset($_GET['op_ko_id'])?"none": (($model->sistem_bayar!="Tempo")?"none":"") ) ?>;">
                                                <div class="form-group" style="margin-bottom: 5px;">
                                                    <label class="col-md-5 control-label"><?= Yii::t('app', 'Max Plafon'); ?></label>
                                                    <div class="col-md-5">
                                                        <?= \yii\bootstrap\Html::activeTextInput($modTempo, "maks_plafon",['class'=>'form-control float','disabled'=>true]) ?>
                                                    </div>
                                                </div>
                                                <div class="form-group" style="margin-bottom: 5px;">
                                                    <label class="col-md-5 control-label"><?= Yii::t('app', 'Piutang Aktif'); ?></label>
                                                    <div class="col-md-5">
                                                        <?= \yii\bootstrap\Html::activeTextInput($modTempo, "sisa_piutang",['class'=>'form-control float','disabled'=>true]) ?>
                                                    </div>
                                                </div>
                                                <div class="form-group" style="margin-bottom: 5px;">
                                                    <label class="col-md-5 control-label"><?= Yii::t('app', 'OP Aktif'); ?></label>
                                                    <div class="col-md-5">
                                                        <?= \yii\bootstrap\Html::activeTextInput($modTempo, "op_aktif",['class'=>'form-control float','disabled'=>true]) ?>
                                                    </div>
                                                </div>
                                                <div class="form-group" style="margin-bottom: 5px;">
                                                    <label class="col-md-5 control-label"><?= Yii::t('app', 'Sisa Plafon'); ?></label>
                                                    <div class="col-md-5">
                                                        <?= \yii\bootstrap\Html::activeTextInput($modTempo, "sisa_plafon",['class'=>'form-control float','disabled'=>true,'style'=>'font-weight:800']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>
								</div><br>
                                <?php
                                if ($beda > 0) {
                                ?>
                                <hr>
                                <div class="row" id="detail-order-awal" style="margin-top: -10px; margin-bottom: -10px;">
                                    <div class="col-md-12" style="margin-top: -10px; margin-bottom: -10px;">
                                        <h5 style="font-weight: bold;"><?= Yii::t('app', 'Detail Order Awal'); ?></h5>
                                    </div>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%;" id="table-detail-awal">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.3rem;">No.</th>
														<th rowspan="2" style="line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Produk'); ?></th>
														<!--<th rowspan="2" style="width: 160px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?php // echo Yii::t('app', 'Nama Produk'); ?></th>-->
														<th colspan="3" style="line-height: 0.9;  padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Qty'); ?></th>
														<?php // if(!isset($_GET['op_ko_id'])){ ?>
														<th rowspan="2" style="width: 80px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Available<br>Stock'); ?></th>
														<?php // } ?>
													</tr>
													<tr>
                                                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 50px;"><?= Yii::t('app', 'Palet'); ?></th>
                                                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 130px"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
                                                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 70px"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>

                                                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 50px; display: none;"><?= Yii::t('app', '-'); ?></th>
                                                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 130px; display: none;"><?= Yii::t('app', 'Satuan<br>Beli'); ?></th>
                                                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 70px; display: none;"><?= Yii::t('app', 'Satuan<br>Angkut'); ?></th>
                                                        
                                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 50px; display: none;"><?= Yii::t('app', 'Batang'); ?></th>
                                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 130px; display: none;"><?= Yii::t('app', '-'); ?></th>
                                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 70px; display: none;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
													</tr>
												</thead>
												<tbody>
                                                <?php
                                                    if ($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding") {
                                                        $sql_modDetailJasa = "select * from t_op_ko_detail_jasa a ". 
                                                                                "   join m_produk_jasa b on b.produk_jasa_id = a.produk_id ".
                                                                                " where op_ko_id = ".$_GET['op_ko_id']."";
                                                    } else {
                                                        $sql_modDetailJasa = "select * from t_op_ko_detail_jasa a ". 
                                                                                "   join m_brg_produk b on b.produk_id = a.produk_id ".
                                                                                " where op_ko_id = ".$_GET['op_ko_id']."";
                                                    }
                                                    $modDetailJasa = Yii::$app->db->createCommand($sql_modDetailJasa)->queryAll();
                                                    $i = 1;
                                                    foreach($modDetailJasa as $kolom) {
                                                        if ($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding") {
                                                            $produk_nama = $kolom['kode']." - ".$kolom['nama'];
                                                        } else {
                                                            $produk_nama = $kolom['produk_nama'];
                                                        }
                                                        $qty_besar = $kolom['qty_besar'];
                                                        $qty_kecil = $kolom['qty_kecil'];
                                                        $kubikasi = $kolom['kubikasi'];
                                                        $harga_jual = $kolom['harga_jual'];
                                                        $subtotal = \app\components\DeltaFormatter::formatNumberForAllUser($kubikasi * $harga_jual);
                                                    ?>
                                                    <tr>
                                                        <td class="td-kecil text-center" style="height: 30px;"><?php echo $i;?></td>
                                                        <td class="td-kecil text-left" style="height: 30px;"><?php echo $produk_nama;?></td>
                                                        <td class="td-kecil text-center" style="height: 30px;"><?php echo $qty_besar;?></td>
                                                        <td class="td-kecil text-right" style="height: 30px;"><?php echo $qty_kecil;?> (pcs)</td>
                                                        <td class="td-kecil text-right" style="height: 30px;"><?php echo $kubikasi;?></td>
                                                        <td class="td-kecil"></td>
                                                    </tr>
                                                    <?php
                                                        $i++;
                                                    }
                                                }
                                                ?>
												</tbody>
											</table>
										</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row" style="margin-top: -20px; margin-bottom: -20px;">
                                    <div class="col-md-12" style="margin-bottom: -10px;">
                                        <h5 style="font-weight: bold;"><?= Yii::t('app', 'Detail Order'); ?></h5>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -10px; margin-bottom: -10px;">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.3rem;">No.</th>
														<th rowspan="2" style="line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Produk'); ?></th>
														<!--<th rowspan="2" style="width: 160px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?php // echo Yii::t('app', 'Nama Produk'); ?></th>-->
														<th colspan="3" style="line-height: 0.9;  padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Qty'); ?></th>
														<?php // if(!isset($_GET['op_ko_id'])){ ?>
														<th rowspan="2" style="width: 80px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Available<br>Stock'); ?></th>
														<?php // } ?>
														<?php /*<th rowspan="2" style="width: 120px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Harga<br>Satuan'); ?></th>*/?>
														<?php /*<th rowspan="2" style="width: 120px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Subtotal'); ?></th>*/?>
														<?php // if(!isset($_GET['op_ko_id'])){ ?>
														<?php /*<th rowspan="2" style="width: 50px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Cancel'); ?></th>*/?>
														<?php // } ?>
													</tr>
													<tr>

                                                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 50px"><?= Yii::t('app', 'Palet'); ?></th>
                                                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 130px"><?= Yii::t('app', 'Satuan<br>Kecil'); ?></th>
                                                        <th class="place-satuan-produk" style="font-size: 1.2rem; line-height: 0.9; width: 70px"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>

                                                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 50px; display: none;"><?= Yii::t('app', '-'); ?></th>
                                                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 130px; display: none;"><?= Yii::t('app', 'Satuan<br>Beli'); ?></th>
                                                        <th class="place-satuan-limbah" style="font-size: 1.2rem; line-height: 0.9; width: 70px; display: none;"><?= Yii::t('app', 'Satuan<br>Angkut'); ?></th>
                                                        
                                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 50px; display: none;"><?= Yii::t('app', 'Batang'); ?></th>
                                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 130px; display: none;"><?= Yii::t('app', '-'); ?></th>
                                                        <th class="place-satuan-gesek" style="font-size: 1.2rem; line-height: 0.9; width: 70px; display: none;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php //isi Detail Order ?>
												</tbody>
												<tfoot>
													<?php /*<tr>
														<td colspan="6">
															<a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
														</td>
														<td style="vertical-align: middle; text-align: right;">
															Total Harga &nbsp;
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_harga',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr> */?>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row" id="place-table-terima-jasa" >
                                    <div class="col-md-12" style="margin-top: -20px; margin-bottom: -10px;">
                                        <h5 id="place-terima-jasa-judul" style="font-weight: bold;"></h5>
                                    </div>
                                    <div class="col-md-12" style="margin-bottom: -10px;">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-terima-jasa">
												<thead>
													<tr>
														<th rowspan="2" style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">No.</th>
														<th rowspan="2" style="width: 125px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Tanggal<br>Terima / Hasil'); ?></th>
														<th rowspan="2" style="width: 100px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Nopol'); ?></th>
														<th rowspan="2" style="width: 120px; line-height: 0.9; padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Produk'); ?></th>
														<th rowspan="2" style="width: 30px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'No.<br>Palet'); ?></th>
														<th colspan="3" style="line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Dimensi'); ?></th>
														<th colspan="2" style="line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Dokumen'); ?></th>
														<th colspan="2" style="line-height: 0.9;  padding: 5px; font-size: 1.2rem;"><?= Yii::t('app', 'Aktual'); ?></th>
														<th rowspan="2" style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Ket'); ?></th>
														<th rowspan="2" style="width: 35px; line-height: 0.9; font-size: 1.2rem;"></th>
													</tr>
                                                    <tr>
                                                        <th style="width: 100px; line-height: 0.9; font-size: 1.2rem;"><?= "T" ?></th>
                                                        <th style="width: 100px; line-height: 0.9; font-size: 1.2rem;"><?= "L" ?></th>
                                                        <th style="width: 100px; line-height: 0.9; font-size: 1.2rem;"><?= "P" ?></th>
                                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Qty'); ?></th>
														<th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Vol'); ?></th>
                                                        <th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Qty'); ?></th>
														<th style="width: 60px; line-height: 0.9; font-size: 1.2rem;"><?= Yii::t('app', 'Vol'); ?></th>
                                                    </tr>
												</thead>
												<tbody>
													<?php //isi Detail Penerimaan Jasa KD/GESEK/MOULDING?>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="7">
                                                            <?php if(isset($_GET['edit'])): ?>
                                                                <a class="btn btn-xs blue-hoki" id="btn-add-item-terima" onclick="addItemTerima();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Detail'); ?></a>
                                                            <?php endif; ?>
                                                            <?php if (empty(isset($_GET['edit']))) :?>
                                                            <?= \yii\helpers\Html::button( Yii::t('app', 'Download Excel Penerimaan'),['id'=>'btn-print-detail','class'=>'btn green btn-outline ciptana-spin-btn pull-right btn-xs','style'=>'margin-left: 10px; margin-top: 5px;','onclick'=>'printDetail('.(isset($_GET['op_ko_id'])?$_GET['op_ko_id']:'').');','disabled'=>true]); ?>
                                                            <?php endif; ?>
														</td>
														<td style="vertical-align: middle; text-align: right;">
															Total &nbsp;
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_terima_qty',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'width:100%; font-size:1.1rem; padding:2px; height:30px; text-align:right']); ?>
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_terima_m3',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'width:100%; font-size:1.1rem; padding:2px; height:30px; text-align:right']); ?>
                                                        </td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_terima_qty_actual',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'width:100%; font-size:1.1rem; padding:2px; height:30px; text-align:right']); ?>
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_terima_m3_actual',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'width:100%; font-size:1.1rem; padding:2px; height:30px; text-align:right']); ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
													</tr>
												</tfoot>
											</table>
										</div>
                                    </div>
                                </div>
                                <br>
		                        <div class="form-actions pull-right col-md-12 row" style="margin-top: -10px; margin-bottom: -10px;">
		                            <div class="col-md-12 right">
										<div class="col-md-6">
											<?php
											/*(!isset($_GET['op_ko_id'])) ? $label_status = "Verify Status" : $label_status = "Status : ".$model->status; 
											?>
											<style>
											#place-verifystatus:link {
												color: #f00;
												background-color: #fff;
											}
											#place-verifystatus:hover {
												color: #f00;
												background-color: #fff;
											}
											#place-verifystatus:active {
												color: #f00;
												background-color: #fff;
											}
											#place-verifystatus:focus {
												color: #f00;
												background-color: #fff;
											}
											#place-verifystatus:visited {
												color: #f00;
												background-color: #fff;
											}
											.xxx:visited {
												color: #f00;
												background-color: #fff;
											}
											</style>
											<?php echo \yii\helpers\Html::button( Yii::t('app', $label_status),['id'=>'place-verifystatus','class'=>'btn red btn-outline ciptana-spin-btn pull-right']); ?> */?>
										</div>
										<div class="col-md-6 pull-right pull-right" id="action-panel">
		                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'save();']); ?>
                                        <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn pull-right','style'=>'margin-left: 10px;','onclick'=>'printOP('.(isset($_GET['op_ko_id'])?$_GET['op_ko_id']:'').');','disabled'=>true]); ?>
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
if(isset($_GET['op_ko_id'])){
    $pagemode = "afterSave(".$_GET['op_ko_id'].");";
}else{
	$pagemode = "resetTableDetail(); setVerify();";
}
?>
<?php $this->registerJs(" 
    $pagemode
    formconfig();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Penerimaan Jasa'))."');
	$('select[name*=\"[sales_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Sales',
	});
	
	$('select[name*=\"[disetujui]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Pegawai',
	});
	
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
			url    : '".\yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/hapusfile'])."',
			type   : 'POST',
			data   : formData,
			success: function (data) {
				if(data){
					$(data).each(function(){
						$('#loading').show();
						$('#xxx').load('".\yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/xxx'])." div#yyy', {}, function () {
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
	})
	openPoFamily();
", yii\web\View::POS_READY); ?>
<script>

function openPoFamily() {
	var jp = document.getElementById("topko-jenis_produk").value;
	if (jp == "JasaKD" || jp == "JasaGesek" || jp == "JasaMoulding") {
		$("#po_family").css("display", "block");
	} else {
		$("#po_family").css("display", "none");
	}
}

function setCustomer(){
	var cust_id = $('#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/setCustomer']); ?>',
        type   : 'POST',
        data   : {cust_id:cust_id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val('');
			resetTableDetail();
			if(data.cust_id){
				$("#modal-master").find('button.fa-close').trigger('click');
				$("#<?= yii\bootstrap\Html::getInputId($model, "alamat_bongkar") ?>").val(data.cust_an_alamat);
			}
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
	$('#<?= \yii\bootstrap\Html::getInputId($model, 'sistem_bayar') ?>').val('Bayar Lunas');
	setVerify();
	setSistembayarDisplay();
}
function resetTableDetail(){
	$('#table-detail tbody').html('');
	addItem();
}

/*function checkLowPrice() {
	var total_harga_jual = 0;
	var total_harga_jual_lama = 0;
	$('#table-detail > tbody > tr').each(function(){
		var harga_jual = unformatNumber( $(this).find('input[name*="[harga_jual]"]').val() );
		var harga_jual_lama = unformatNumber( $(this).find('input[name*="[harga_jual_lama]"]').val() );
		subtotal_harga_jual = harga_jual;
		subtotal_harga_jual_lama = harga_jual_lama;
		total_harga_jual += subtotal_harga_jual;
		total_harga_jual_lama += subtotal_harga_jual_lama;
	});
	
	if( (parseInt(total_harga_jual) *1) < (parseInt(total_harga_jual_lama) *1) ){
		$("#place-verifystatus").html("<span class='font-yellow-gold'>Low Price</span>");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val("Low Price");
	} else {
		$("#place-verifystatus").html("<span class='font-yellow-gold'>Low Price</span>");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val("Low Price");
	}
}*/

function setVerify(){
	var sistem_bayar = $("#<?= \yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>").val();
	var max_plafon = unformatNumber( $("#<?= \yii\bootstrap\Html::getInputId($modTempo, "maks_plafon") ?>").val() );
	var sisa_piutang = unformatNumber( $("#<?= \yii\bootstrap\Html::getInputId($modTempo, "sisa_piutang") ?>").val() );
	var sisa_plafon = unformatNumber( $("#<?= \yii\bootstrap\Html::getInputId($modTempo, "sisa_plafon") ?>").val() );
	var total_harga = unformatNumber( $('input[name="total_harga"]').val() );
	var top_hari = unformatNumber( $("#<?= \yii\bootstrap\Html::getInputId($modTempo, "top_hari") ?>").val() );
	var maks_top_hari = unformatNumber( $("#<?= \yii\bootstrap\Html::getInputId($modTempo, "maks_top_hari") ?>").val() );
	
	//$("#place-verifystatus").html("<span class='font-green-seagreen'>Allowed</span>");
	//$("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val("");

	var status = $("#place-verifystatus").text();
	
	if(sistem_bayar == "Tempo"){
		/*if(total_harga > sisa_plafon){
			$("#place-verifystatus").html("<span class='font-yellow-gold'>"+status+"Over Plafond</span>");
			$("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val(status+"Over Plafond");
		}

		if(top_hari > maks_top_hari){
			$("#place-verifystatus").html("<span class='font-yellow-gold'>"+status+"Over TOP</span>");
			$("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val(status+"Over TOP");
		}

		if( (total_harga > sisa_plafon) && (top_hari > maks_top_hari) ){
			$("#place-verifystatus").html("<span class='font-yellow-gold'>"+status+"Over TOP & Over Plafond</span>");
			$("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val(status + "Over TOP & Over Plafond");
		}*/

		if(parseInt(total_harga *1) > parseInt(sisa_plafon *1)) {
			var over_plafond = "Over Plafond";
		} else {
			var over_plafond = "";
		}

		if(parseInt(top_hari *1) > parseInt(maks_top_hari *1)) {
			if (over_plafond == 'Over Plafond' ) {
				var over_top = " - Over Top";
			} else {
				var over_top = " Over Top";
			}
		} else {
			var over_top = "";
		}
	} else {
		var over_plafond = "";
		var over_top = "";
	}

	var total_harga_jual = 0;
	var total_harga_jual_lama = 0;
	var total_i = 0;
	var list_harga_status = [];

	$('#table-detail > tbody > tr').each(function(){
		var harga_jual = unformatNumber( $(this).find('input[name*="[harga_jual]"]').val() );
		var harga_jual_lama = unformatNumber( $(this).find('input[name*="[harga_jual_lama]"]').val() );
		var produk_id = $(this).find('select[name*="[produk_id]"]');

		subtotal_harga_jual = harga_jual;
		subtotal_harga_jual_lama = harga_jual_lama;

		total_harga_jual += subtotal_harga_jual;
		total_harga_jual_lama += subtotal_harga_jual_lama;

		//TOpKoDetail_1_status_harga
		var id = 'TOpKoDetail_'+total_i+'_status_harga';
		// LOW PRICE CUY
		if (harga_jual < harga_jual_lama) {
			// jika selisih harga dari low price > 100rb maka approval 3 biji
			if (harga_jual_lama - harga_jual >= 100000) {
				$('#'+id).val('low3');
			} 
			// jika selisih harga dari low price < 100rb maka approval cukup 2 biji
			else {
				$('#'+id).val('low2');
			}

		} else {
			$('#'+id).val('ok');
		}

		total_i++;
		if($('#'+id).val()) {
            list_harga_status.push($('#'+id).val().split(' ')[0]);
        }

	});

	//var low_price = $("input[name='TOpKoDetail_[]_status_harga']").map(function(){return $(this).val();}).get();

	/*if( (parseInt(total_harga_jual) *1) < (parseInt(total_harga_jual_lama) *1) ){
		if (over_plafond!= '' || over_top != '') {
			var low_price = " - Low Price";
		} else {
			var low_price = " Low Price";
		}
	} else {
		var low_price = '';
	}
	*/

	var set_low_price = list_harga_status;
	var search_low_price2 = "low2";
	var search_low_price3 = "low3";
	if(set_low_price.indexOf(search_low_price2) != -1 || set_low_price.indexOf(search_low_price3) != -1){
		if (set_low_price.indexOf(search_low_price2) != -1) {
			if (over_plafond != '' || over_top != '') {
				var low_price = " - Low Price (2)";
			} else {
				var low_price = " Low Price (2)";
			}
		}
		
		if (set_low_price.indexOf(search_low_price3) != -1) {
			if (over_plafond != '' || over_top != '') {
				var low_price = " - Low Price (3)";
			} else {
				var low_price = " Low Price (3)";
			}
		}
	} else {
		var low_price = '';
	}

	if (over_plafond != '' || over_top != '' || low_price != '') {
		$("#place-verifystatus").html("<span class='font-yellow-gold'>Status : "+over_plafond+''+over_top+''+low_price+"</span>");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val("Status : "+over_plafond+''+over_top+''+low_price);
	} else {
		$("#place-verifystatus").html("<span class='font-yellow-gold'>Status</span>");
		$("#<?= \yii\bootstrap\Html::getInputId($model, "status") ?>").val('');
	}

}

function setSistembayarDisplay(){
	if( $("#<?= \yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>").val() == "Tempo" ){
		$('#place-top').slideDown();
		$('#place-plafond').slideDown();
	}else{
		$('#place-top').slideUp();
		$('#place-plafond').slideUp();
	}
}

function masterCustomer(){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/customer/masterOnModal']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master .modal-dialog").css('width','90%');
		$("#modal-master").modal('show');
		$("#modal-master").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
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

function masterProduk(ele){
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'jenis_produk') ?>').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/produkInStock','disableAction'=>'']); ?>1&tr_seq='+tr_seq+"&jenis_produk="+jenis_produk;
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master-produk .modal-dialog").css('width','75%');
		$("#modal-master-produk").modal('show');
		$("#modal-master-produk").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function masterLimbah(ele){
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'jenis_produk') ?>').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/openlimbah','disableAction'=>'']); ?>1&tr_seq='+tr_seq+"&jenis_produk="+jenis_produk;
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master-limbah .modal-dialog").css('width','75%');
		$("#modal-master-limbah").modal('show');
		$("#modal-master-limbah").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function masterJasa(ele){
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'jenis_produk') ?>').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/openjasa','disableAction'=>'']); ?>1&tr_seq='+tr_seq+"&jenis_produk="+jenis_produk;
	$(".modals-place-3-min").load(url, function() {
		$("#modal-master-jasa .modal-dialog").css('width','75%');
		$("#modal-master-jasa").modal('show');
		$("#modal-master-jasa").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function listRandom(ele){
	var tr_seq = $(ele).parents('tr').find('#no_urut').val();
	var produk_id = $(ele).parents('tr').find('select[name*="[produk_id]"]').val();
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, 'jenis_produk') ?>').val();
	var nomor_produksi_random = $(ele).parents('tr').find('input[name*="[nomor_produksi_random]"]').val();
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/listRandom']); ?>?tr_seq='+tr_seq+"&produk_id="+produk_id+"&nomor_produksi_random="+nomor_produksi_random;
	$(".modals-place-2-min").load(url, function() {
		$("#modal-random .modal-dialog").css('width','75%');
		$("#modal-random").modal('show');
		$("#modal-random").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
		
		$('#modal-random').on('hidden.bs.modal', function () {
			var row = $(this).find("input[name*='reff_ele']").val();
			var nomor_produksi_all =  $(this).find("input[name*='nomor_produksi_all']").val();
			var palet = (nomor_produksi_all)?nomor_produksi_all.split(',').length:"0";
			var pcs =  $(this).find("input[name*='tot_qty']").val();
			var kubikasi =  $(this).find("input[name*='tot_kubikasi']").val();
			$("#TOpKoDetail_"+(row-1)+"_nomor_produksi_random").val(nomor_produksi_all);
			$("#TOpKoDetail_"+(row-1)+"_qty_besar").val(palet);
			$("#TOpKoDetail_"+(row-1)+"_qty_kecil").val(pcs);
			$("#TOpKoDetail_"+(row-1)+"_kubikasi").val(kubikasi);
			total();
		})
	});
}

function pick(cust_id,par){
	$("#modal-master").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").empty().append('<option value="'+cust_id+'">'+par+'</option>').val(cust_id).trigger('change');
}

function pickProduk(produk_id, tr_seq, data, stock_pcs, stock_kubik, stock_palet, harga_enduser, kode){
    var jns_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
    $("#table-detail").addClass("animation-loading");
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/pickProduk']); ?>',
        type   : 'POST',
        data   : {produk_id:produk_id,jns_produk:jns_produk},
        success: function (data) {
			if(data){
				if(jns_produk == "Limbah" || jns_produk == "JasaKD"  || jns_produk == "JasaGesek" || jns_produk == "JasaMoulding" || jns_produk == data.produk_group){
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
						//pickProduk("2575",1,"9","65","2.8525657875","1","9")
						//alert('produk_id : '+produk_id+'\nbaris : '+tr_seq+'\nstock_pcs : '+stock_pcs+'\nstock_kubik : '+stock_kubik+'\nstock_palet : '+stock_palet+'\nharga_enduser : '+harga_enduser+'\nkode : '+kode);
                        if (jns_produk == "Limbah") {
                            $("#modal-master-limbah").find('button.fa-close').trigger('click');
                            $("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[produk_id]']").empty().append('<option value="'+data.limbah_id+'">'+data.limbah_kode+' - ('+data.limbah_produk_jenis+') '+data.limbah_nama+'</option>').val(data.limbah_id).trigger('change');
                        } else if(jns_produk == "JasaKD" || jns_produk == "JasaGesek" || jns_produk == "JasaMoulding"){
                            $("#modal-master-jasa").find('button.fa-close').trigger('click');
                            $("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[produk_id]']").empty().append('<option value="'+data.produk_jasa_id+'">'+data.kode+' - '+data.nama+'</option>').val(data.produk_jasa_id).trigger('change');
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
            $("#table-detail").removeClass("animation-loading");
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setVal(){
	var cust_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val();
	var jns_produk = $("#<?= \yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
	var sistem_bayar = $("#<?= \yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>").val();
	$("#<?= yii\bootstrap\Html::getInputId($modTempo, "maks_plafon") ?>").val('0');
	$("#<?= yii\bootstrap\Html::getInputId($modTempo, "op_aktif") ?>").val('0');
	$("#<?= yii\bootstrap\Html::getInputId($modTempo, "sisa_piutang") ?>").val('0');
	$("#<?= yii\bootstrap\Html::getInputId($modTempo, "sisa_plafon") ?>").val('0');
	var top = 0;
	if(cust_id){
		$.ajax({
			url    : '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/setVal']); ?>',
			type   : 'POST',
			data   : {cust_id:cust_id,jns_produk:jns_produk,op_ko_id:"<?= isset($_GET['op_ko_id'])?$_GET['op_ko_id']:"" ?>"},
			success: function (data) {
				if(data.cust){
					if(data.top_hari){
						$('#<?= \yii\bootstrap\Html::getInputId($modTempo, "top_hari") ?>').val(data.top_hari);
						$('#<?= \yii\bootstrap\Html::getInputId($modTempo, "maks_top_hari") ?>').val(data.maks_top_hari);
					}else{
						if(sistem_bayar == 'Tempo'){
							cisAlert("TOP untuk customer "+data.cust.cust_an_nama+" belum diset");
							$('#<?= \yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>').val("Bayar Lunas");
							if(data.maks_plafon <= 0){
								cisAlert("Maksimal Plafon untuk customer "+data.cust.cust_an_nama+" belum diset");
								$('#<?= \yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>').val("Bayar Lunas");
							}
						}
					}
					$("#<?= yii\bootstrap\Html::getInputId($modTempo, "maks_plafon") ?>").val( formatNumberForUser(data.maks_plafon) );
					$("#<?= yii\bootstrap\Html::getInputId($modTempo, "sisa_piutang") ?>").val( formatNumberForUser(data.sisa_piutang) );
					$("#<?= yii\bootstrap\Html::getInputId($modTempo, "op_aktif") ?>").val( formatNumberForUser(data.op_aktif) );
					$("#<?= yii\bootstrap\Html::getInputId($modTempo, "sisa_plafon") ?>").val( formatNumberForUser(data.sisa_plafon) );
				}
				setSistembayarDisplay();
				setVerify();
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
	}
}

function addItem(){
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var notin = [];

    if(jns_produk == "Limbah"){
        $(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","");
        $(".place-satuan-gesek").css("display","none");
        // $("#btn-add-item").hide();
    }else if(jns_produk == "JasaGesek"){
        $(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","none");
        $(".place-satuan-gesek").css("display","");
    }else{
        $(".place-satuan-produk").css("display","");
        $(".place-satuan-limbah").css("display","none");
        $(".place-satuan-gesek").css("display","none");
        // $("#btn-add-item").show();
    }
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
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/addItem']); ?>',
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
								url: '<?= \yii\helpers\Url::toRoute('/marketing/penerimaanjasa/findProdukActive') ?>',
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

function setItem(ele,produk_id=null){
	var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var cust_id = $('#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>').val();
	if(!produk_id){
		produk_id = $(ele).val();
	}
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/setItem']); ?>',
        type   : 'POST',
        data   : {produk_id:produk_id,jns_produk:jns_produk},
        success: function (data) {
			$(ele).parents('tr').find('input[name*="[produk_nama]"]').val('');
			$(ele).parents('tr').find('input[name*="[qty_besar]"]').val("");
			$(ele).parents('tr').find('input[name*="[satuan_besar]"]').val("");
			$(ele).parents('tr').find('input[name*="[qty_kecil]"]').val('');
			$(ele).parents('tr').find('input[name*="[qty_kecil_perpalet]"]').val('');
			$(ele).parents('tr').find('input[name*="[satuan_kecil]"]').val('');
			$(ele).parents('tr').find('input[name*="[kubikasi]"]').val('');
			$(ele).parents('tr').find('input[name*="[kubikasi_perpalet]"]').val('');
			$(ele).parents('tr').find('input[name*="[harga_hpp]"]').val('0');
			$(ele).parents('tr').find('input[name*="[harga_jual]"]').val('0');
			$(ele).parents('tr').find('input[name*="[harga_jual]"]').attr('disabled','disabled');
			$(ele).parents('tr').find('#place-availablestock').html('');
			$(ele).parents('tr').find('input[name*="[produk_p]"]').val('0');
			$(ele).parents('tr').find('input[name*="[produk_l]"]').val('0');
			$(ele).parents('tr').find('input[name*="[produk_t]"]').val('0');
			$(ele).parents('tr').find('input[name*="[produk_p_satuan]"]').val('0');
			$(ele).parents('tr').find('input[name*="[produk_l_satuan]"]').val('0');
			$(ele).parents('tr').find('input[name*="[produk_t_satuan]"]').val('0');
			$(ele).parents('tr').find(".btn-random").css("display","none");
			$(ele).parents('tr').find("input[name*='[qty_besar]']").prop("disabled",false);
			$(ele).parents('tr').find("input[name*='[qty_kecil]']").prop("disabled",false);
			$(ele).parents('tr').find("input[name*='[kubikasi]']").prop("disabled",false);
			$(ele).parents('tr').find("input[name*='[is_random]']").val("0");
			$(ele).parents('tr').find("input[name*='[nomor_produksi_random]']").val("");

			if (data.sql) {
				//alert(data.sql.produk_id);
			}

            if(data.produk){
                $(ele).parents('tr').find('input[name*="[produk_nama]"]').val(data.produk.produk_nama);
                $(ele).parents('tr').find('input[name*="[qty_besar]"]').val("1");
                $(ele).parents('tr').find('input[name*="[satuan_besar]"]').val("Palet");
                $(ele).parents('tr').find('input[name*="[qty_kecil]"]').val("0");
				$(ele).parents('tr').find('input[name*="[qty_kecil_perpalet]"]').val(data.produk.produk_qty_satuan_kecil);
                $(ele).parents('tr').find('input[name*="[satuan_kecil]"]').val(data.produk.produk_satuan_kecil);
                $(ele).parents('tr').find('input[name*="[kubikasi]"]').val("0");
                $(ele).parents('tr').find('input[name*="[kubikasi_perpalet]"]').val(data.produk.kapasitas_kubikasi);

                if( data.harga_enduser ){
                    $(ele).parents('tr').find('input[name*="[harga_jual]"]').val( formatNumberForUser(data.harga_enduser) );
                    $(ele).parents('tr').find('input[name*="[harga_jual_lama]"]').val( formatNumberForUser(data.harga_enduser) );
                }
                
				$(ele).parents('tr').find('input[name*="[produk_p]"]').val(data.produk.produk_p);
				$(ele).parents('tr').find('input[name*="[produk_l]"]').val(data.produk.produk_l);
				$(ele).parents('tr').find('input[name*="[produk_t]"]').val(data.produk.produk_t);
				$(ele).parents('tr').find('input[name*="[produk_p_satuan]"]').val(data.produk.produk_p_satuan);
				$(ele).parents('tr').find('input[name*="[produk_l_satuan]"]').val(data.produk.produk_l_satuan);
				$(ele).parents('tr').find('input[name*="[produk_t_satuan]"]').val(data.produk.produk_t_satuan);
				
				if(cust_id){
					//alert('ada cust_id');
					$(ele).parents('tr').find('input[name*="[harga_jual]"]').removeAttr('disabled');
					$(ele).parents('tr').find('input[name*="[harga_jual_lama]"]').removeAttr('disabled');
				}
				
				if(data.availablestock){
					var a = data.availablestock.kubikasi * 1;
					var b = a.toFixed(4);
					$(ele).parents('tr').find('#place-availablestock').html(data.availablestock.qty_kecil+"("+data.availablestock.in_qty_kecil_satuan+")<br>"+b+"M<sup>3</sup>");
				}

				if(data.availablestock){
					//alert('ada data.availablestock');
					$(ele).parents('tr').find('#place-availablestock').html(data.availablestock.qty_kecil+"("+data.availablestock.in_qty_kecil_satuan+")<br>"+data.availablestock.kubikasi+"M<sup>3</sup>");
				}
                
                if(data.random){
                	//alert('ada data.random');
                    if(data.random.total_palet > 0){
                        $(ele).parents('tr').find(".btn-random").css("display","");
                        $(ele).parents('tr').find('input[name*="[qty_besar]"]').val("0");
                        $(ele).parents('tr').find("input[name*='[qty_besar]']").prop("disabled",true);
                        $(ele).parents('tr').find("input[name*='[qty_kecil]']").prop("disabled",true);
                        $(ele).parents('tr').find("input[name*='[kubikasi]']").prop("disabled",true);
                        $(ele).parents('tr').find("input[name*='[is_random]']").val("1");
                    }
                }

                if(jns_produk == "Limbah"){
                    if( data.produk.limbah_satuan_jual == "Rit" ){
                        $(ele).parents('tr').find("input[name*='[qty_kecil]']").val( "1" );
                        $(ele).parents('tr').find("input[name*='[qty_kecil]']").prop("disabled",true);
                        $(ele).parents('tr').find('input[name*="[satuan_besar]"]').show();
                    }else{
                        $(ele).parents('tr').find('input[name*="[satuan_besar]"]').hide();
                    }
                    
                    $(ele).parents('tr').find('input[name*="[satuan_kecil]"]').val( data.produk.limbah_satuan_jual );
                    $(ele).parents('tr').find('input[name*="[satuan_besar]"]').val( data.produk.limbah_satuan_muat );
                } else if (jns_produk == "JasaKD"){
                    $(ele).parents('tr').find('input[name*="[qty_besar]"]').val( "0" );
                    $(ele).parents('tr').find('input[name*="[satuan_kecil]"]').val( "Pcs" );
                } else if (jns_produk == "JasaGesek"){
                    $(ele).parents('tr').find('input[name*="[qty_kecil]"]').val( "1" );
                    $(ele).parents('tr').find('input[name*="[qty_besar]"]').val( "0" );
                    $(ele).parents('tr').find('input[name*="[satuan_kecil]"]').val( "Pcs" );
                } else if (jns_produk == "JasaMoulding"){
                    $(ele).parents('tr').find('input[name*="[qty_besar]"]').val( "0" );
                    $(ele).parents('tr').find('input[name*="[satuan_kecil]"]').val( "Pcs" );
                }
            } 
        // setMeterKubik(ele);
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setQty(ele){
	var qty_besar = unformatNumber( $(ele).parents('tr').find('input[name*="[qty_besar]"]').val() );
	var qty_kecil_perpalet = unformatNumber( $(ele).parents('tr').find('input[name*="[qty_kecil_perpalet]"]').val() );
	var kubikasi_perpalet = unformatNumber( $(ele).parents('tr').find('input[name*="[kubikasi_perpalet]"]').val() );
	$(ele).parents('tr').find('input[name*="[qty_kecil]"]').val( formatNumberForUser(qty_besar * qty_kecil_perpalet) );
	$(ele).parents('tr').find('input[name*="[kubikasi]"]').val( formatNumberForUser(qty_besar * kubikasi_perpalet) );
}

function setMeterKubik(ele){
    var p = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_p]"]').val() );
    var l = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_l]"]').val() );
    var t = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_t]"]').val() );
    var sat_p = $(ele).parents('tr').find('input[name*="[produk_p_satuan]"]').val();
    var sat_l = $(ele).parents('tr').find('input[name*="[produk_l_satuan]"]').val();
    var sat_t = $(ele).parents('tr').find('input[name*="[produk_t_satuan]"]').val();
    var qty = unformatNumber( $(ele).parents('tr').find('input[name*="[qty_kecil]"]').val() );
    var sat_p_m = 0;
    var sat_l_m = 0;
    var sat_t_m = 0;
    var result = 0;
    if(sat_p == 'mm'){
        sat_p_m = p * 0.001;
    }else if(sat_p == 'cm'){
        sat_p_m = p * 0.01;
    }else if(sat_p == 'inch'){
        sat_p_m = p * 0.0254;
    }else if(sat_p == 'm'){
        sat_p_m = p;
    }else if(sat_p == 'feet'){
        sat_p_m = p * 0.3048;
    }
    if(sat_l == 'mm'){
        sat_l_m = l * 0.001;
    }else if(sat_l == 'cm'){
        sat_l_m = l * 0.01;
    }else if(sat_l == 'inch'){
        sat_l_m = l * 0.0254;
    }else if(sat_l == 'm'){
        sat_l_m = l;
    }else if(sat_l == 'feet'){
        sat_l_m = l * 0.3048;
    }
    if(sat_t == 'mm'){
        sat_t_m = t * 0.001;
    }else if(sat_t == 'cm'){
        sat_t_m = t * 0.01;
    }else if(sat_t == 'inch'){
        sat_t_m = t * 0.0254;
    }else if(sat_t == 'm'){
        sat_t_m = t;
    }else if(sat_t == 'feet'){
        sat_t_m = t * 0.3048;
    }
    result = sat_p_m * sat_l_m * sat_t_m * qty;
    result = (Math.round( result * 10000 ) / 10000 ).toString();
    setTimeout(function() {
		if( $(ele).parents('tr').find("input[name*='[is_random]']").val() != 1 ){
			$(ele).parents('tr').find('input[name*="[kubikasi]"]').val( formatNumberForUser(result) );
			total();
		}
    }, 300);
}

function setQtyByKubikasi(ele){
    var p = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_p]"]').val() );
    var l = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_l]"]').val() );
    var t = unformatNumber( $(ele).parents('tr').find('input[name*="[produk_t]"]').val() );
    var sat_p = $(ele).parents('tr').find('input[name*="[produk_p_satuan]"]').val();
    var sat_l = $(ele).parents('tr').find('input[name*="[produk_l_satuan]"]').val();
    var sat_t = $(ele).parents('tr').find('input[name*="[produk_t_satuan]"]').val();
    var kubikasi = unformatNumber( $(ele).parents('tr').find('input[name*="[kubikasi]"]').val() );
    var sat_p_m = 0;
    var sat_l_m = 0;
    var sat_t_m = 0;
    var result = 0;
    if(sat_p == 'mm'){
        sat_p_m = p * 0.001;
    }else if(sat_p == 'cm'){
        sat_p_m = p * 0.01;
    }else if(sat_p == 'inch'){
        sat_p_m = p * 0.0254;
    }else if(sat_p == 'm'){
        sat_p_m = p;
    }else if(sat_p == 'feet'){
        sat_p_m = p * 0.3048;
    }
    if(sat_l == 'mm'){
        sat_l_m = l * 0.001;
    }else if(sat_l == 'cm'){
        sat_l_m = l * 0.01;
    }else if(sat_l == 'inch'){
        sat_l_m = l * 0.0254;
    }else if(sat_l == 'm'){
        sat_l_m = l;
    }else if(sat_l == 'feet'){
        sat_l_m = l * 0.3048;
    }
    if(sat_t == 'mm'){
        sat_t_m = t * 0.001;
    }else if(sat_t == 'cm'){
        sat_t_m = t * 0.01;
    }else if(sat_t == 'inch'){
        sat_t_m = t * 0.0254;
    }else if(sat_t == 'm'){
        sat_t_m = t;
    }else if(sat_t == 'feet'){
        sat_t_m = t * 0.3048;
    }
    result = kubikasi / (sat_p_m * sat_l_m * sat_t_m) ;
    result = (Math.round( result * 10000 ) / 10000 ).toFixed();
    setTimeout(function() {
		$(ele).parents('tr').find('input[name*="[qty_kecil]"]').val( formatNumberForUser(result) );
		total();
    }, 300);
}

function total(){
	var jnsproduk = $("#<?= \yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>").val();
	var subtotal = 0;
	var total = 0;
	$('#table-detail > tbody > tr').each(function(){
		var qty_kecil = unformatNumber( $(this).find('input[name*="[qty_kecil]"]').val() );
		var kubikasi = unformatNumber( $(this).find('input[name*="[kubikasi]"]').val() );
		var harga_jual = unformatNumber( $(this).find('input[name*="[harga_jual]"]').val() );
		var harga_jual_lama = unformatNumber( $(this).find('input[name*="[harga_jual_lama]"]').val() );
		if(jnsproduk == "Plywood" || jnsproduk == "Lamineboard" || jnsproduk == "Platform" || jnsproduk == "Limbah"){
			subtotal = qty_kecil * harga_jual;
		}else{
			subtotal = kubikasi * harga_jual;
		}
		total += subtotal;
		$(this).find('input[name*="[subtotal]"]').val( formatNumberForUser(subtotal) );
	});
	$('input[name="total_harga"]').val( formatNumberForUser(total) );
	setVerify();
}

function save() {
    //var d = $('#btn-save');
    //d.text(d.text().trim().replace(/Save/i, "Sabaaar"));
    $(this).prop('disabled', true);

    var $form = $('#form-transaksi');
	var has_error = 0;

	<?php
	if (isset($_GET['edit'])) {
		$halaman = 'edit';
	} else if (isset($_GET['afterSave'])) {
		$halaman = 'aftersave';
	} else {
		$halaman = 'index';
	}
	?>

	var halaman = '<?php echo $halaman;?>';

    if(formrequiredvalidate($form)){
		var jp = $('#topko-jenis_produk').val();
		var po = $('#topko-po').val();
		var tanggal_po = $('#topko-tanggal_po').val();
		var file1 = document.getElementById("file1");
		var file2 = document.getElementById("file2");
		var file3 = document.getElementById("file3");
		var file4 = document.getElementById("file4");
		var file5 = document.getElementById("file5");
		var file6 = document.getElementById("file6");

		//if (file1.files.length == 0 || file2.files.length == 0 || file3.files.length == 0 || file4.files.length == 0 || file5.files.length == 0 || file6.files.length == 0) { 
		if (halaman == 'index' && (file1.files.length == 0 && file2.files.length == 0 && file3.files.length == 0 && file4.files.length == 0 && file5.files.length == 0 && file6.files.length == 0)) { 
			var error_file = 1;
		} else {
			var error_file = 0;
		}
		
		if (jp == "JasaKD" || jp == "JasaGesek" || jp == "JasaMoulding") {			
			var fieldPo = $("#<?= yii\bootstrap\Html::getInputId($model, "po") ?>");
			var fieldTanggalPo = $("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_po") ?>");

			/*if (po == '') {
				if(!fieldPo.val()){
					$(fieldPo).parents('.form-group').addClass('error-tb-detail');
					cisAlert('Isi PO terlebih dahulu');
					var error_jasa_cuy = 1;
				}else{
					$(fieldPo).parents('.form-group').removeClass('error-tb-detail');
					var error_jasa_cuy = 0;
				}
			}*/

			/*if (tanggal_po == '') {
				if(!fieldTanggalPo.val()){
					$(fieldTanggalPo).parents('.form-group').addClass('error-tb-detail');
					cisAlert('Isi Tanggal PO terlebih dahulu');
					var error_jasa_cuy = 1;
				}else{
					$(fieldTanggalPo).parents('.form-group').removeClass('error-tb-detail');
					var error_jasa_cuy = 0;
				}
			}*/

			if (halaman == 'index' && error_file == 1){
				$('#label_file').parents('.form-group').addClass('error-tb-detail');
				var error_jasa_cuy = 1;
				cisAlert('Upload foto/image/gambar PO terlebih dahulu');
			} else {
				$('#label_file').parents('.form-group').removeClass('error-tb-detail');
				$(".fileinput-new").css('border: solid 1px #ccc');
				var error_jasa_cuy = 0;
			}
		} else {
			var error_jasa_cuy = 0;
		}

        var jumlah_item = $('#table-detail tbody tr').length;
        if (jumlah_item < 0) {
			cisAlert('Isi detail terlebih dahulu');
        }

        if (validatingDetail()) {
            $("#btn-save").html("Sabaaar");
            setTimeout(function(){
                if (error_jasa_cuy < 1 && has_error < 1) {
				    submitform($form);
                }
            }, 1500);
        }
    }
	//alert(error_jasa_cuy+' - '+has_error);
    return false;
}

function validatingDetail(){
    var has_error = 0;
	var fielda = $("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>");
	var prov_bongkar = $("#<?= yii\bootstrap\Html::getInputId($model, "provinsi_bongkar") ?>");
	if(!fielda.val()){
		$(fielda).parents('.form-group').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(fielda).parents('.form-group').removeClass('error-tb-detail');
	}
	if(!prov_bongkar.val()){
		$(prov_bongkar).parents('.form-group').addClass('error-tb-detail');
		has_error = has_error + 1;
	}else{
		$(prov_bongkar).parents('.form-group').removeClass('error-tb-detail');
	}
    $('#table-detail tbody > tr').each(function(){
        var field1 = $(this).find('select[name*="[produk_id]"]');
        var field2 = $(this).find('input[name*="[qty_besar]"]');
        var field3 = $(this).find('input[name*="[harga_jual]"]');
        var field4 = $(this).find('input[name*="[harga_jual_lama]"]');
        var field5 = $(this).find('input[name*="[qty_kecil]"]');
        if(!field1.val()){
            $(this).find('select[name*="[produk_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('select[name*="[produk_id]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[qty_besar]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( $(this).find('input[name*="[qty_besar]"]').val() == 0 ){
                has_error = has_error + 1;
                $(this).find('input[name*="[qty_besar]"]').parents('td').addClass('error-tb-detail');
            }else{
                $(this).find('input[name*="[qty_besar]"]').parents('td').removeClass('error-tb-detail');
            }
        }
        if(!field3.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[harga_jual]"]').parents('td').addClass('error-tb-detail');
            $(this).find('input[name*="[harga_jual_lama]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( $(this).find('input[name*="[harga_jual]"]').val() == 0 ){
                has_error = has_error + 1;
                $(this).find('input[name*="[harga_jual]"]').parents('td').addClass('error-tb-detail');
                $(this).find('input[name*="[harga_jual_lama]"]').parents('td').addClass('error-tb-detail');
            }else{
                $(this).find('input[name*="[harga_jual]"]').parents('td').removeClass('error-tb-detail');
                $(this).find('input[name*="[harga_jual_lama]"]').parents('td').removeClass('error-tb-detail');
            }
        }

		/*if(!field5.val()){
            has_error = has_error + 1;
            $(this).find('input[name*="[qty_kecil]"]').parents('td').addClass('error-tb-detail');
        }else{
            if( (!Number.isInteger( unformatNumber($(this).find('input[name*="[qty_kecil]"]').val()) * 1 ) ) || ($(this).find('input[name*="[qty_kecil]"]').val() == 0 ) ){
                has_error = has_error + 1;
                $(this).find('input[name*="[qty_kecil]"]').parents('td').addClass('error-tb-detail');
            }else{
                $(this).find('input[name*="[qty_kecil]"]').parents('td').removeClass('error-tb-detail');
            }
        }*/
    });
	<?php if(isset($_GET['edit'])){ ?>
		has_error = 0;
	<?php } ?>
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
	<?php if(!isset($_GET['edit'])) { ?>
		getItems(id);
		$('#btn-add-item').hide();
		$('form').find('input').each(function(){ $(this).prop("disabled", true); });
	<?php } else { ?>
		getItems(id,1);
		setVal();
	<?php } ?>
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('#<?= yii\bootstrap\Html::getInputId($model, 'pegawai_mutasi') ?>').attr('disabled','');
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal_kirim') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    $('#btn-print-detail').removeAttr('disabled');

	<?php if(isset($_GET['edit'])){ ?>
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "pp_no") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "sales_id") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "disetujui") ?>').prop("disabled", false);
		$('input[name*="[syarat_jual]"]').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_kirim") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'tanggal_kirim') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, 'alamat_bongkar') ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "sistem_bayar") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modTempo, "top_hari") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "cara_bayar_reff") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "po") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_po") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modAttachment, "file") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modAttachment, "file1") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modAttachment, "file2") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modAttachment, "file3") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modAttachment, "file4") ?>').prop("disabled", false);
		$('#<?= \yii\bootstrap\Html::getInputId($modAttachment, "file5") ?>').prop("disabled", false);
		$('#btn-save').prop('disabled',false);
        $('#btn-print').prop('disabled',true);
        $('#btn-print-detail').prop('disabled',true);
	<?php } ?>
    setHtmlTerimaJasa();
}

function getItems(op_ko_id,edit=null){
    var jns_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
    if(jns_produk == "Limbah"){
        $(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","");
        $(".place-satuan-gesek").css("display","none");
    }else if(jns_produk == "JasaGesek"){
        $(".place-satuan-produk").css("display","none");
        $(".place-satuan-limbah").css("display","none");
        $(".place-satuan-gesek").css("display","");
    }else{
        $(".place-satuan-produk").css("display","");
        $(".place-satuan-limbah").css("display","none");
        $(".place-satuan-gesek").css("display","none");
    }
    $("#table-detail").addClass("animation-loading");
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/getItems']); ?>',
		type   : 'POST',
		data   : {op_ko_id:op_ko_id,edit:edit},
		success: function (data) {
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
					$('#table-detail tbody tr').each(function(){
						$(this).find('select[name*="[produk_id]"]').select2({
							allowClear: !0,
							placeholder: 'Ketik kode produk',
							width: '100%',
							ajax: {
								url: '<?= \yii\helpers\Url::toRoute('/marketing/penerimaanjasa/findProdukActive') ?>',
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
						$(this).find("input[name*='[harga_jual]']").removeAttr("disabled");
						//$(this).find("input[name*='[harga_jual_lama]']").removeAttr("disabled");
						if(($(this).find("input[name*='[is_random]']").val() == "1") && (data.random)){
							$(this).find(".btn-random").css("display","");
							$(this).find("input[name*='[qty_besar]']").prop("disabled",true);
							$(this).find("input[name*='[qty_kecil]']").prop("disabled",true);
							$(this).find("input[name*='[kubikasi]']").prop("disabled",true);
						}
					});
					reordertable('#table-detail');
                }
                $("#table-detail").removeClass("animation-loading");
			}
			setTimeout(function(){
				total();
			},500);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function daftarAfterSaveX(){
    openModal('<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/daftarAfterSaveX']) ?>','modal-aftersavex','90%');
}

function printOP(id){
	window.open("<?= yii\helpers\Url::toRoute('/marketing/penerimaanjasa/printOP') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=800px, height=600px, scrollbars=yes');
}

function printDetail(id){
	window.open("<?= yii\helpers\Url::toRoute('/marketing/penerimaanjasa/printDetailJasa') ?>?id="+id+"&caraprint=EXCEL","",'location=_new, width=800px, height=600px; scrollbars=yes');
}

function cancelTransaksi(op_ko_id){
	openModal('<?php echo \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/cancelTransaksi']) ?>?id='+op_ko_id,'modal-transaksi');
}

function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/index','op_ko_id'=>'']); ?>'+id+'&edit=1');
}

function setHtmlTerimaJasa(){
    <?php if( isset($_GET['op_ko_id']) ){ ?>
        $("#table-terima-jasa > tbody").html("");
        var jns_produk = $("#<?= \yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
        var op_ko_id = "<?= (isset($_GET['op_ko_id'])?$_GET['op_ko_id']:null); ?>";
        $("#btn-add-item-terima").hide();
        if(jns_produk == "JasaKD" || jns_produk == "JasaMoulding" || jns_produk == "JasaGesek"){
            $("#place-table-terima-jasa").removeAttr("style");
            
            if(jns_produk == "JasaKD"){
                $("#place-terima-jasa-judul").html("Detail Penerimaan Jasa KD");
            }else if(jns_produk == "JasaMoulding"){
                $("#place-terima-jasa-judul").html("Detail Penerimaan Jasa Moulding");
            }else if(jns_produk == "JasaGesek"){
                $("#place-terima-jasa-judul").html("Detail Data Tally Hasil Gesek");
            }
            
            <?php if(isset($_GET['edit'])){ ?>
                $("#btn-add-item-terima").show();
                getItemTerima(op_ko_id,"1");
            <?php }else{ ?>
                getItemTerima(op_ko_id);
            <?php } ?>
        }else{
            $("#place-table-terima-jasa").css("display","none");
        }
    <?php } ?>
}

function addItemTerima(ele){
    var jns_produk = $("#<?= \yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
    var op_ko_id = "<?= isset($_GET['op_ko_id'])?$_GET['op_ko_id']:"" ?>";
    var last_tr_val = [];
    if( $("#table-terima-jasa > tbody > tr:last").length > 0 ){
        last_tr_val['op_ko_detail_id'] = $("#table-terima-jasa > tbody > tr:last").find("input[name*='[op_ko_detail_id]']").val();
        last_tr_val['tanggal'] = $("#table-terima-jasa > tbody > tr:last").find("input[name*='[tanggal]']").val();
        last_tr_val['nopol'] = $("#table-terima-jasa > tbody > tr:last").find("input[name*='[nopol]']").val();
        last_tr_val['produk_jasa_id'] = $("#table-terima-jasa > tbody > tr:last").find("select[name*='[produk_jasa_id]']").val();
        last_tr_val['nomor_palet'] = $("#table-terima-jasa > tbody > tr:last").find("input[name*='[nomor_palet]']").val();
        last_tr_val['t'] = $("#table-terima-jasa > tbody > tr:last").find("input[name*='[t]']").val();
        last_tr_val['l'] = $("#table-terima-jasa > tbody > tr:last").find("input[name*='[l]']").val();
        last_tr_val['p'] = $("#table-terima-jasa > tbody > tr:last").find("input[name*='[p]']").val();
        
        last_tr_val['t_satuan'] = $("#table-terima-jasa > tbody > tr:last").find("select[name*='[t_satuan]']").val();
        last_tr_val['l_satuan'] = $("#table-terima-jasa > tbody > tr:last").find("select[name*='[l_satuan]']").val();
        last_tr_val['p_satuan'] = $("#table-terima-jasa > tbody > tr:last").find("select[name*='[p_satuan]']").val();
    }else{
        last_tr_val['op_ko_detail_id'] = ( $('#table-detail > tbody > tr:last').find("input[name*='[op_ko_detail_id]']").val() )? $('#table-detail > tbody > tr:last').find("input[name*='[op_ko_detail_id]']").val() : $('#table-detail > tbody > tr').eq(-2).find("input[name*='[op_ko_detail_id]']").val();
    }

    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/addItemTerima']); ?>',
        type   : 'POST',
        data   : {op_ko_id:op_ko_id, edit:1},
        success: function (data) {
            if(data.item){
                $(data.item).hide().appendTo('#table-terima-jasa tbody').fadeIn(200,function(){
                    $(this).find(".tooltips").tooltip({ delay: 50 });
                    reordertable('#table-terima-jasa');
                    setDropdownDetailJasa();
                    $("#table-terima-jasa > tbody > tr:last").find("input[name*='[op_ko_detail_id]']").val( (last_tr_val['op_ko_detail_id'])?last_tr_val['op_ko_detail_id']:'' );
                    $("#table-terima-jasa > tbody > tr:last").find("select[name*='[produk_jasa_id]']").val( (last_tr_val['produk_jasa_id'])?last_tr_val['produk_jasa_id']:'' );
                    $("#table-terima-jasa > tbody > tr:last").find("input[name*='[tanggal]']").val( (last_tr_val['tanggal'])?last_tr_val['tanggal']:'' );
                    $("#table-terima-jasa > tbody > tr:last").find("input[name*='[nopol]']").val( (last_tr_val['nopol'])?last_tr_val['nopol']:'' );
                    $("#table-terima-jasa > tbody > tr:last").find("input[name*='[nomor_palet]']").val( (last_tr_val['nomor_palet'])?last_tr_val['nomor_palet']:'' );
                    $("#table-terima-jasa > tbody > tr:last").find("input[name*='[t]']").val( (last_tr_val['t'])?last_tr_val['t']:'' );
                    $("#table-terima-jasa > tbody > tr:last").find("select[name*='[t_satuan]']").val( (last_tr_val['t_satuan'])?last_tr_val['t_satuan']:'' );
                    $("#table-terima-jasa > tbody > tr:last").find("input[name*='[l]']").val( (last_tr_val['l'])?last_tr_val['l']:'' );
                    $("#table-terima-jasa > tbody > tr:last").find("select[name*='[l_satuan]']").val( (last_tr_val['l_satuan'])?last_tr_val['l_satuan']:'' );
                    $("#table-terima-jasa > tbody > tr:last").find("input[name*='[p]']").val( (last_tr_val['p'])?last_tr_val['p']:'' );
                    $("#table-terima-jasa > tbody > tr:last").find("select[name*='[p_satuan]']").val( (last_tr_val['p_satuan'])?last_tr_val['p_satuan']:'' );
                    totalTerima();
                    if(jns_produk == "JasaGesek"){
                        $("#table-terima-jasa > tbody > tr:last").find('input[name*="[nopol]"]').val("-");
                        $("#table-terima-jasa > tbody > tr:last").find('input[name*="[nopol]"]').prop("disabled",true);
                    }
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

//aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
function setMeterKubikTerima(ele){
    var p = unformatNumber( $(ele).parents('tr').find('input[name*="[p]"]').val() );
    var l = unformatNumber( $(ele).parents('tr').find('input[name*="[l]"]').val() );
    var t = unformatNumber( $(ele).parents('tr').find('input[name*="[t]"]').val() );
    var sat_p = $(ele).parents('tr').find('select[name*="[p_satuan]"]').val();
    var sat_l = $(ele).parents('tr').find('select[name*="[l_satuan]"]').val();
    var sat_t = $(ele).parents('tr').find('select[name*="[t_satuan]"]').val();
    var qty = unformatNumber( $(ele).parents('tr').find('input[name*="[qty_kecil]"]').val() );
    var qty_actual = $(ele).parents('tr').find('input[name*="[qty_kecil_actual]"]').val();
    var sat_p_m = 0;
    var sat_l_m = 0;
    var sat_t_m = 0;
    var result = 0;
    var result_actual = 0;
    if(sat_p == 'mm'){
        sat_p_m = p * 0.001;
    }else if(sat_p == 'cm'){
        sat_p_m = p * 0.01;
    }else if(sat_p == 'inch'){
        sat_p_m = p * 0.0254;
    }else if(sat_p == 'm'){
        sat_p_m = p;
    }else if(sat_p == 'feet'){
        sat_p_m = p * 0.3048;
    }
    if(sat_l == 'mm'){
        sat_l_m = l * 0.001;
    }else if(sat_l == 'cm'){
        sat_l_m = l * 0.01;
    }else if(sat_l == 'inch'){
        sat_l_m = l * 0.0254;
    }else if(sat_l == 'm'){
        sat_l_m = l;
    }else if(sat_l == 'feet'){
        sat_l_m = l * 0.3048;
    }
    if(sat_t == 'mm'){
        sat_t_m = t * 0.001;
    }else if(sat_t == 'cm'){
        sat_t_m = t * 0.01;
    }else if(sat_t == 'inch'){
        sat_t_m = t * 0.0254;
    }else if(sat_t == 'm'){
        sat_t_m = t;
    }else if(sat_t == 'feet'){
        sat_t_m = t * 0.3048;
    }
    result = sat_p_m * sat_l_m * sat_t_m * qty;
    result = (Math.round( result * 10000 ) / 10000 ).toString();
    result_actual = sat_p_m * sat_l_m * sat_t_m * qty_actual;
    result_actual = (Math.round( result_actual * 10000 ) / 10000 ).toString();
    setTimeout(function() {
        $(ele).parents('tr').find('input[name*="[kubikasi]"]').val( formatNumberForUser(result) );
        $(ele).parents('tr').find('input[name*="[kubikasi_actual]"]').val( formatNumberForUser(result_actual) );
        var produk_jasa = $(ele).parents('tr').find(':selected').text();
        var nama_produk = produk_jasa.replace(/Mm/gm,'');
        var nama_produk = nama_produk.replace(/Cm/gm,'');
        var nama_produk = nama_produk.replace(/Feet/gm,'');
        var nama_produk = nama_produk.replace(/Meter/gm,'');
        /*var dt = new Date();
        var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
        if ($(ele).parents('tr').find("[title='detail_qty_besar']").prop('')) {
            $(ele).parents('tr').find("[title*='detail_qty_besar']").prop('title', '');
        } else {
            $(ele).parents('tr').find("[title*='detail_qty_besar']").prop('title', 'detail_qty_besar '+nama_produk);
        }*/
        totalTerima(nama_produk);
    }, 300);
}

function cancelItemTerima(ele,callback){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-terima-jasa');
        if(callback != null){
            eval(callback);
        }
    });
}

function totalTerima(nama_produk){
	var total_qty = 0;
	var total_m3 = 0;
	var total_qty_actual = 0;
	var total_m3_actual = 0;
    var palet = [];
    var jns_produk = $("#<?= \yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
	$('#table-terima-jasa > tbody > tr').each(function(){
		total_qty += unformatNumber( $(this).find('input[title="detail_qty_kecil"]').val() );
		total_m3 += unformatNumber( $(this).find('input[name*="[kubikasi]"]').val() );
		total_qty_actual += unformatNumber( $(this).find('input[title="detail_qty_kecil_actual"]').val() );
		total_m3_actual += unformatNumber( $(this).find('input[name*="[kubikasi_actual]"]').val() );
        var pal = $(this).find("input[title='detail_qty_besar "+nama_produk+"']").val();

        $(this).find().val
        if( !( palet.indexOf( pal )>-1 && (palet.length > 0)) ){
            if(pal){
                palet.push( pal );
            }
        }
	});
	$('input[name="total_terima_qty"]').val( formatNumberForUser(total_qty) );
	$('input[name="total_terima_m3"]').val( formatNumberForUser(total_m3) );
	$('input[name="total_terima_qty_actual"]').val( formatNumberForUser(total_qty_actual) );
	$('input[name="total_terima_m3_actual"]').val( formatNumberForUser(total_m3_actual) );

    // update ke row detail order disable saja dulu
    if(jns_produk == "JasaKD"){
        if (nama_produk != null) {
            //$('#table-detail > tbody').find("[title='qty_besar "+nama_produk+"']").val( palet.length );
            //$('#table-detail > tbody').find("input[title='qty_kecil "+nama_produk+"']").val( formatNumberForUser(total_qty) );
            //$('#table-detail > tbody').find("input[title='kubikasi "+nama_produk+"']").val( formatNumberForUser(total_m3) );

        } else {
            var nama_produk = '';
        }
    }
    total();
    
}
//zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz

function getItemTerima(op_ko_id,edit){
    var jns_produk = $("#<?= \yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val();
    $("#table-terima-jasa").addClass("animation-loading");
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/getItemTerima']); ?>',
		type   : 'POST',
		data   : {op_ko_id:op_ko_id,edit:edit},
		success: function (data) {
			if(data.html){
				$('#table-terima-jasa > tbody').html(data.html);
                reordertable('#table-terima-jasa');
            }
            $("#table-terima-jasa").removeClass("animation-loading");
			setTimeout(function(){
				totalTerima();
                
                $('#table-terima-jasa > tbody > tr').each(function(){
                    if(!edit){
                        $(this).find('input[name*="[tanggal]"]').prop("disabled",true);
                        $(this).find('input[name*="[tanggal]"]').siblings('.input-group-btn').find('button').prop('disabled', true);
                        $(this).find('input[name*="[nopol]"]').prop("disabled",true);
                        $(this).find('input[name*="[nomor_palet]"]').prop("disabled",true);
                        $(this).find('select[name*="[produk_jasa_id]"]').prop("disabled",true);
                        $(this).find('input[name*="[t]"]').prop("disabled",true);
                        $(this).find('select[name*="[t_satuan]"]').prop("disabled",true);
                        $(this).find('input[name*="[l]"]').prop("disabled",true);
                        $(this).find('select[name*="[l_satuan]"]').prop("disabled",true);
                        $(this).find('input[name*="[p]"]').prop("disabled",true);
                        $(this).find('select[name*="[p_satuan]"]').prop("disabled",true);
                        $(this).find('input[name*="[qty_kecil]"]').prop("disabled",true);
                        $(this).find('input[name*="[kubikasi]"]').prop("disabled",true);
                        $(this).find('input[name*="[qty_kecil_actual]"]').prop("disabled",true);
                        $(this).find('input[name*="[kubikasi_actual]"]').prop("disabled",true);
                        $(this).find('input[name*="[keterangan]"]').prop("disabled",true);
                        $(this).find('.btn.btn-xs.red').remove();
                    }
                    if(jns_produk == "JasaGesek"){
                        $(this).find('input[name*="[nopol]"]').val("-");
                        $(this).find('input[name*="[nopol]"]').prop("disabled",true);
                    }
                });
			},500);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setDropdownDetailJasa(){
    var prod_jasa_arr = [];
    $('#table-detail > tbody > tr').each(function(i,val){
        var prod_id = $(this).find('select[name*="[produk_id]"]').val();
        var kode = $(this).find('select[name*="[produk_id]"] option:selected').text();
        prod_jasa_arr[i] = {"produk_id":prod_id,"kode":kode};

        // exec even end off loop -- 7 des 2019
        if( (i+1) == $('#table-detail > tbody > tr').length ){
            var elFirst = document.createElement("option");
            elFirst.textContent = ""; elFirst.value = "";
            var htmlsel = elFirst.outerHTML;
            for(var iarr = 0; iarr < prod_jasa_arr.length; iarr++) {
                var content = prod_jasa_arr[iarr].kode;
                var value = prod_jasa_arr[iarr].produk_id;
                var el = document.createElement("option");
                el.textContent = content;
                el.value = value;
                htmlsel += el.outerHTML;

                if( (iarr+1) == prod_jasa_arr.length ){
                    $('#table-terima-jasa > tbody > tr:last').each(function(){
                        $(this).find("select[name*='[produk_jasa_id]']").html(htmlsel);
                    });
                };

            }
        }
    });
}

function addAttch(){
    $("#place-attch .col-md-2.hidden:first").removeClass('hidden');
}

function image(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalhasilorientasi/image','id'=>'']) ?>'+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-image").modal('show');
		$("#modal-image").on('hidden.bs.modal', function () { });
		$("#modal-image .modal-dialog").css('width',"1024px");
		spinbtn();
		draggableModal();
	});
}

function hapus_file(attachment_id) {
	var x = confirm("Yakin mau dihapus ?");
	if (x) {
		$( "#files" ).load("<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/hapusfile?attachment_id=']);?>attachment_id", {attachment_id:attachment_id});
    	return true;
	} else {
    	return false;
	}
}
</script>