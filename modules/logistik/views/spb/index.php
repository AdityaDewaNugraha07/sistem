<?php
/* @var $this yii\web\View */

use app\components\DeltaFormatter;
use app\models\MPegawai;
use app\models\TApproval;
use yii\helpers\Json;

$this->title = 'Transaksi SPB';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Surat Permintaan Barang (SPB)'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
	'id' => 'form-spb',
	'fieldConfig' => [
		'template' => '{label}<div class="col-md-7">{input} {error}</div>',
		'labelOptions' => ['class' => 'col-md-4 control-label'],
	],
]);
echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
	.modal-body {
		max-height: 400px;
		overflow-y: auto;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<?php
				if (app\components\DeltaGlobalClass::authByUserDeptId(app\components\Params::DEPARTEMENT_ID_LOGISTIC) || (Yii::$app->user->identity->pegawai_id == \app\components\Params::DEFAULT_PEGAWAI_ID_ANNEKE)) {
				?>
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="<?= yii\helpers\Url::toRoute("/logistik/spb/index"); ?>"> <?= Yii::t('app', 'SPB Baru'); ?> </a>
						</li>
						<li class="">
							<a href="<?= yii\helpers\Url::toRoute("/logistik/penerimaanspb/index"); ?>"> <?= Yii::t('app', 'SPB Masuk'); ?> </a>
						</li>
					</ul>
				<?php } ?>
				<div class="row" style="margin-bottom: 10px; display: <?= (count($spb_exist) > 0) ? 'block' : 'none'; ?>">
					<div class="col-md-12">
						<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarBpbblmterima(<?= Yii::$app->user->identity->pegawai->departement_id ?>)"><i class="fa fa-list"></i> <?= Yii::t('app', 'BPB Yang Belum Diterima'); ?></a>
						<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarSpb(<?= Yii::$app->user->identity->pegawai->departement_id ?>)" style="margin-right: 5px;"><i class="fa fa-list"></i> <?= Yii::t('app', 'SPB Yang Telah Dibuat'); ?></a>
						<a class="btn grey-gallery btn-sm btn-outline pull-right" target="_BLANK" href="<?= yii\helpers\Url::toRoute("/logistik/spb/itemdipesan") ?>" style="margin-right: 5px;"><i class="fa fa-list"></i> <?= Yii::t('app', 'Item Yang Pernah Dipesan'); ?></a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="portlet light bordered">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject bold">
										<h4><?= Yii::t('app', 'Data Permintaan'); ?></h4>
									</span>
								</div>
								<div class="tools">
									<a href="javascript:;" class="reload"> </a>
									<a href="javascript:;" class="fullscreen"> </a>
								</div>
							</div>
							<div class="portlet-body">
								<div class="row">
									<div class="col-md-5">
										<?= $form->field($model, 'departement_id')->dropDownList(\app\models\MDepartement::getOptionList(), ['prompt' => '', 'disabled' => 'disabled']); ?>
										<?= $form->field($model, 'spb_jenis')->dropDownList(\app\models\MDefaultValue::getOptionList('spb-jenis'), ['prompt' => '']); ?>
										<?= $form->field($model, 'spb_tipe')->inline(true)->radioList(app\models\MDefaultValue::getOptionList('spb-tipe'), ['style' => 'margin-left:20px']); ?>
										<?php
										if (!isset($_GET['spb_id'])) {
											echo $form->field($model, 'spb_kode')->textInput(['disabled' => 'disabled', 'style' => 'font-weight:bold']);
										} else { ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode SPB'); ?></label>
												<div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'spb_kode', ['class' => 'form-control', 'style' => 'width:100%']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->spb_kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
										<?= $form->field($model, 'spb_nomor')->textInput(['placeholder' => 'No. pada faktur']); ?>
										<?= $form->field($model, 'spb_tanggal', [
											'template' => '{label}<div class="col-md-8"><div class="input-group input-medium date date-picker" data-date-start-date="-0d">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'
										])->textInput(['readonly' => 'readonly']); ?>
										<?php if (isset($_GET['spb_id'])) { ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Status SPB') ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<?php
													$tampilkanAlasan = false;
													if ($model->spb_status === 'BELUM DIPROSES') {
														echo '<span class="label label-sm label-info">' . $model->spb_status . '</span>';
													} else if ($model->spb_status === 'SEDANG DIPROSES') {
														echo '<span class="label label-sm label-warning">' . $model->spb_status . '</span>';
													} else if ($model->spb_status === 'DITOLAK') {
														echo '<span class="label label-sm label-danger">' . $model->spb_status . '</span>';
														$tampilkanAlasan = true;
													} else if ($model->spb_status === 'TERPENUHI') {
														echo '<span class="label label-sm label-success">' . $model->spb_status . '</span>';
													}
													?>
												</div>
											</div>
											<?php if ($tampilkanAlasan && !empty($model->reason_ditolak)) : ?>
												<div class="form-group">
													<label class="col-md-4 control-label"><?= Yii::t('app', 'Alasan Ditolak') ?></label>
													<div class="col-md-7" style="margin-top:7px;">
														<?php $ditolak = Json::decode($model->reason_ditolak);
														echo $ditolak['alasan_ditolak'] ?>
														<p class="help-block" style="margin-top: -3px;font-size: 10px;color: #737373b3;">
															Oleh <?= MPegawai::findOne(['pegawai_id' => $ditolak['pegawai_id']])->pegawai_nama ?>
															pada <?= DeltaFormatter::formatDateTimeForUser($ditolak['tanggal_ditolak']) ?>
														</p>
													</div>
												</div>
											<?php endif ?>
										<?php } ?>
									</div>
									<div class="col-md-6">
										<?= $form->field($model, 'spb_keterangan')->textarea(); ?>
										<?= $form->field($model, 'spb_diminta')->dropDownList(MPegawai::getOptionListByDept(!empty($model->spb_diminta) ? $model->spbDiminta->departement_id : null), ['class' => 'form-control select2', 'prompt' => '']); ?>
										<?php // echo $form->field($model, 'spb_disetujui')->dropDownList(\app\models\MPegawai::getOptionMenyetujuiSPB( !empty($model->spb_disetujui)?$model->spbDisetujui->departement_id:null ),['class'=>'form-control select2','prompt'=>'']); 
										?>
										<?php
										// if (isset($_GET['spb_id'])) {
										// 	echo $form->field($model, 'spb_disetujui')->dropDownList(MPegawai::getOptionList(), ['class' => 'form-control select2', 'prompt' => '', 'disabled' => 'disabled']);
										// } else {
											echo $form->field($model, 'spb_disetujui')->dropDownList(MPegawai::getOptionMenyetujuiSPB(null), ['class' => 'form-control select2', 'prompt' => '']);
										// }
										// if (isset($_GET['spb_id'])) {
										// 	echo $form->field($model, 'spb_mengetahui')->dropDownList(MPegawai::getOptionList(), ['class' => 'form-control select2', 'prompt' => '', 'disabled' => 'disabled']);
										// } else {
											echo $form->field($model, 'spb_mengetahui')->dropDownList(MPegawai::getOptionListAtasan(), ['class' => 'form-control select2', 'prompt' => '']);
										// }
										?>
										<?php if (isset($_GET['spb_id'])) { ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Status Approval'); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<?php
													if (count($model) > 0) {
														if ($model->approve_status == \app\models\TApproval::STATUS_APPROVED) {
															echo '<span class="label label-sm label-success"> ' . $model->approve_status . ' </span>';
														} else if ($model->approve_status == \app\models\TApproval::STATUS_REJECTED) {
															echo '<span class="label label-sm label-danger"> ' . $model->approve_status  . ' </span>';
														} else {
															echo '<span class="label label-sm label-default"> ' . \app\models\TApproval::STATUS_NOT_CONFIRMATED . ' </span>';
														}
													} else {
														echo '<span class="label label-sm label-default"> ' . \app\models\TApproval::STATUS_NOT_CONFIRMATED . ' </span>';
													}
													?>
												</div>
											</div>
											<?php if ($model->approve_status === TApproval::STATUS_REJECTED) : ?>
												<div class="form-group">
													<label class="col-md-4 control-label">
														Alasan
													</label>
													<div class="col-md-7" style="margin-top:7px;">
														<textarea disabled class="form-control"><?php 
														if($model->reason_approval) {
															foreach(Json::decode($model->reason_approval) as $reason) {
																$pegawai = MPegawai::findOne($reason['assigned_to']);
																echo $pegawai->pegawai_nama . ' : ' . $reason['reason'] . "\n";
															}
														}
														?></textarea>
													</div>
												</div>
											<?php endif; ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Status Penerimaan'); ?></label>
												<div class="col-md-7" style="margin-top:7px;">
													<?php
													$modBpb = \app\models\TBpb::find()->where(['spb_id' => $_GET['spb_id']])->orderBy(['created_at' => SORT_DESC])->all();
													if (count($modBpb) > 0) {
														foreach ($modBpb as $i => $bpb) {
															if ($i != 0) {
																echo "<br>";
															}
															if ($bpb->bpb_status == "BELUM DITERIMA") {
																echo "<a style='font-size:0.8em;' class='font-red-intense' data-bpb='$bpb->bpb_id' onclick='infoBpb(" . $bpb->bpb_id . ")'>" . $bpb->bpb_kode . " - " . $bpb->bpb_status . "</a>";
															} else if ($bpb->bpb_status == "SUDAH DITERIMA") {
																echo "<a style='font-size:0.8em;' class='font-green-meadow' data-bpb='$bpb->bpb_id' onclick='infoBpb(" . $bpb->bpb_id . ")'>" . $bpb->bpb_kode . " - " . $bpb->bpb_status . "</a>";
															}
														}
													} else {
														echo "<i style='font-size:0.8em;'>-- --</i>";
													}
													?>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
								<br><br>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<h4><?= Yii::t('app', 'Detail Permintaan'); ?> <a class="pull-right btn btn-outline btn-sm blue hidden-xs" target="_BLANK" href="<?= yii\helpers\Url::toRoute("/logistik/bahanpembantu/list") ?>">List Master Item BHP</a></h4>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12"><br>
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
														<th style="width: 30px;">No.</th>
														<th style="width: 330px;"><?= Yii::t('app', 'Nama Item'); ?></th>
														<th style="width: 75px;"><?= Yii::t('app', 'Qty'); ?></th>
														<th style="width: 80px;"><?= Yii::t('app', 'Satuan'); ?></th>
														<th style="line-height: 1; width: 140px;"><?= Yii::t('app', 'Tanggal<br>Dibutuhkan'); ?></th>
														<th><?= Yii::t('app', 'Keterangan'); ?></th>
														<th style="width: 50px;"><?= Yii::t('app', 'Cancel'); ?></th>
													</tr>
												</thead>
												<tbody>

												</tbody>
												<tfoot>
													<tr>
														<td colspan="6">
															<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a>
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
								<?php echo \yii\helpers\Html::button(Yii::t('app', 'Save'), ['id' => 'btn-save', 'class' => 'btn hijau btn-outline ciptana-spin-btn', 'onclick' => 'save();']); ?>
								<?php // echo \yii\helpers\Html::button( Yii::t('app', 'Print SPB'),['id'=>'btn-print','class'=>'btn blue-hoki btn-outline ciptana-spin-btn','disabled'=>true,'onclick'=>'print()']); 
								?>
								<?php echo \yii\helpers\Html::button(Yii::t('app', 'Reset'), ['id' => 'btn-reset', 'class' => 'btn grey-gallery btn-outline ciptana-spin-btn', 'onclick' => 'resetForm();']); ?>
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
if (isset($_GET['spb_id']) && isset($_GET['edit'])) {
	$pagemode = "editMode(" . $_GET['spb_id'] . ")";
} else if (isset($_GET['spb_id'])) {
	$pagemode = "afterSave(" . $_GET['spb_id'] . ")";
} else {
	//    $pagemode = "addItem(); checkBPB();";
	$pagemode = "addItem();";
}
?>
<?php $this->registerJs(" 
    $pagemode
", yii\web\View::POS_READY); ?>
<script>
	function addItem() {
		$.ajax({
			url: '<?= \yii\helpers\Url::toRoute(['/logistik/spb/addItem']); ?>',
			type: 'POST',
			data: {},
			success: function(data) {
				if (data.item) {
					$(data.item).hide().appendTo('#table-detail tbody').fadeIn(500, function() {
						//                    setDropdownBhp($(this));
						$(this).find('select[name*="[bhp_id]"]').select2({
							allowClear: !0,
							placeholder: 'Ketik nama item',
							width: null,
							ajax: {
								url: '<?= \yii\helpers\Url::toRoute('/logistik/spb/findBhpActive') ?>',
								dataType: 'json',
								delay: 250,
								processResults: function(data) {
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
			error: function(jqXHR) {
				getdefaultajaxerrorresponse(jqXHR);
			},
		});
	}

	function setItem(obj, bhp_id = null) {
		if (!bhp_id) {
			bhp_id = $(obj).val();
		}
		$.ajax({
			url: '<?= \yii\helpers\Url::toRoute(['/logistik/spb/setItem']); ?>',
			type: 'POST',
			data: {
				bhp_id: bhp_id
			},
			success: function(data) {
				if (data.bhp_satuan) {
					//                $(obj).parents('tr').find('select[name*="[spbd_satuan]"]').val(data.bhp_satuan);
					$(obj).parents('tr').find('input[name*="[spbd_satuan]"]').val(data.bhp_satuan);
					$(obj).parents('tr').find('#place-satuan').html(data.bhp_satuan);
				}
			},
			error: function(jqXHR) {
				getdefaultajaxerrorresponse(jqXHR);
			},
		});
	}

	function setDropdownBhp(obj) {
		var selected_items = [];
		$('#table-detail tbody tr').each(function() {
			var bhp_id = $(this).find('select[name*="[bhp_id]"]').val();
			if (bhp_id) {
				selected_items.push(bhp_id);
			}
		});
		$.ajax({
			url: '<?= \yii\helpers\Url::toRoute(['/logistik/spb/setDropdownBhp']); ?>',
			type: 'POST',
			data: {
				selected_items: selected_items
			},
			success: function(data) {
				$(obj).find('select[name*="[bhp_id]"]').html(data.html);
			},
			error: function(jqXHR) {
				getdefaultajaxerrorresponse(jqXHR);
			},
		});
	}

	function reordertable(obj_table) {
		var row = 0;
		$(obj_table).find("tbody > tr").each(function() {
			$(this).find("#no_urut").val(row + 1);
			$(this).find("span.no_urut").text(row + 1);
			$(this).find('input,select,textarea').each(function() { //element <input>
				var old_name = $(this).attr("name").replace(/]/g, "");
				var old_name_arr = old_name.split("[");
				if (old_name_arr.length == 3) {
					$(this).attr("id", old_name_arr[0] + "_" + row + "_" + old_name_arr[2]);
					$(this).attr("name", old_name_arr[0] + "[" + row + "][" + old_name_arr[2] + "]");
				}
			});
			row++;
		});
		formconfig();
	}

	function cancelItem(ele) {
		$(ele).parents('tr').fadeOut(500, function() {
			$(this).remove();
			reordertable('#table-detail');
		});
	}

	function save() {
		var $form = $('#form-spb');
		if (formrequiredvalidate($form)) {
			var jumlah_item = $('#table-detail tbody tr').length;
			if (jumlah_item <= 0) {
				cisAlert('Isi detail permintaan terlebih dahulu');
				return false;
			}
			if (validatingDetail()) {
				submitform($form);
			}
		}
		return false;
	}

	function validatingDetail() {
		var has_error = 0;
		$('#table-detail tbody > tr').each(function() {
			if ($(this).find('select[name*="[bhp_id]"]').length > 0) {
				var field1content = 'select[name*="[bhp_id]"]';
			} else {
				var field1content = 'input[name*="[bhp_id]"]';
			}
			var field1 = $(this).find(field1content);
			var field2 = $(this).find('input[name*="[spbd_jml]"]');
			var field3 = $(this).find('textarea[name*="[spbd_ket]"]');
			if (!field1.val()) {
				$(this).find(field1content).parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			} else {
				$(this).find(field1content).parents('td').removeClass('error-tb-detail');
			}
			if (!field2.val()) {
				has_error = has_error + 1;
				$(this).find('input[name*="[spbd_jml]"]').parents('td').addClass('error-tb-detail');
			} else {
				if (unformatNumber(field2.val()) <= 0) {
					has_error = has_error + 1;
					$(this).find('input[name*="[spbd_jml]"]').parents('td').addClass('error-tb-detail');
				} else {
					$(this).find('input[name*="[spbd_jml]"]').parents('td').removeClass('error-tb-detail');
				}
			}
			if (!field3.val()) {
				has_error = has_error + 1;
				$(this).find('textarea[name*="[spbd_ket]"]').parents('td').addClass('error-tb-detail');
			} else {
				$(this).find('textarea[name*="[spbd_ket]"]').parents('td').removeClass('error-tb-detail');
			}
		});
		if (has_error === 0) {
			return true;
		}
		return false;
	}

	function afterSave(id) {
		getAllItem(id);
		$('form').find('input').each(function() {
			$(this).prop("disabled", true);
		});
		$('form').find('select').each(function() {
			$(this).prop("disabled", true);
		});
		$('form').find('textarea').each(function() {
			$(this).attr("readonly", "readonly");
		});
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spb_disetujui') ?>').attr('disabled', '');
		$('#<?= yii\bootstrap\Html::getInputId($model, 'spb_diminta') ?>').attr('disabled', '');
		$('#btn-add-item').hide();
		$('#btn-save').attr('disabled', '');
		$('#btn-print').removeAttr('disabled');
	}

	function editMode(id) {
		getAllItem(id, true);
	}

	function getAllItem(spb_id, editable = false) {
		$.ajax({
			url: '<?= \yii\helpers\Url::toRoute(['/logistik/spb/getAllItem']); ?>',
			type: 'POST',
			data: {
				spb_id: spb_id,
				editable: editable
			},
			success: function(data) {
				if (editable) {
					$('#table-detail tbody').html(data.html);
					reordertable('#table-detail');
					$('#table-detail tbody tr').each(function(i) {
						var initialValue = {
							id: data.value[i].bhp_id,
							text: data.value[i].bhp_nama
						};
						$(this).find('select[name*="[bhp_id]"]').select2({
							allowClear: !0,
							placeholder: 'Ketik nama item',
							width: null,
							ajax: {
								url: '<?= \yii\helpers\Url::toRoute('/logistik/spb/findBhpActive') ?>',
								dataType: 'json',
								delay: 250,
								processResults: function(data) {
									return {
										results: data
									};
								},
								cache: true
							},
							initSelection: function(element, callback) { // set Default Value
								callback(initialValue);
							}
						});
					});
				} else {
					if (data.html) {
						$('#table-detail tbody').html(data.html);
					}
				}
			},
			error: function(jqXHR) {
				getdefaultajaxerrorresponse(jqXHR);
			},
		});
	}

	function daftarSpb(dept_id) {
		openModal('<?= \yii\helpers\Url::toRoute(['/logistik/spb/daftarSpb', 'dept_id' => '']) ?>' + dept_id, 'modal-aftersave', '95%');
	}

	function infoBpb(bpb_id) {
		var url = '<?= \yii\helpers\Url::toRoute(['/logistik/spb/infoBpb', 'bpb_id' => '']) ?>' + bpb_id;
		var modal_id = 'modal-terima-bpb';
		$(".modals-place-2").load(url, function() {
			$("#" + modal_id).modal('show');
			$("#" + modal_id).on('hidden.bs.modal', function(e) {
				dtAftersave();
			});
			spinbtn();
			draggableModal();
		});
		return false;
	}

	function checkBPB() { // No open ticket : 201902228
		var pegawai_id = "<?= Yii::$app->user->identity->pegawai_id ?>";
		$.ajax({
			url: '<?= \yii\helpers\Url::toRoute(['/logistik/spb/checkBPB']); ?>',
			type: 'POST',
			data: {
				pegawai_id: pegawai_id
			},
			success: function(data) {
				if (data.msg) {
					$(".form-actions").html("<h3 class='font-red-flamingo'>" + data.msg + "</h3>");
				}
			},
			error: function(jqXHR) {
				getdefaultajaxerrorresponse(jqXHR);
			},
		});
	}

	function infoApproval(spb_id) {
		var url = '<?= \yii\helpers\Url::toRoute(['/logistik/spb/infoApproval', 'spb_id' => '']) ?>' + spb_id;
		var modal_id = 'modal-approval-spb';
		$(".modals-place-2").load(url, function() {
			$("#" + modal_id).modal('show');
			$("#" + modal_id).on('hidden.bs.modal', function(e) {
				dtAftersave();
			});
			spinbtn();
			draggableModal();
		});
		return false;
	}
	function daftarBpbblmterima(dept_id) {
		openModal('<?= \yii\helpers\Url::toRoute(['/logistik/spb/daftarBpbblmterima', 'dept_id' => '']) ?>' + dept_id, 'modal-aftersave', '40%');
	}
</script>