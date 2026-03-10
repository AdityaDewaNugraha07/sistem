<div class="modal-body">
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="form-group col-md-12" style="margin-bottom: 0px;">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['reff_no'] ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
			</div>
			<div class="form-group col-md-12" style="margin-bottom: 0px;">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['tanggal_berkas'] ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_berkas); ?></strong></div>
			</div>
			<div class="form-group col-md-12" style="margin-bottom: 0px;">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['assigned_to'] ?></label>
				<div class="col-md-7"><strong><?= $model->assignedTo->pegawai_nama; ?></strong></div>
			</div>
			<div class="form-group col-md-12" style="margin-bottom: 0px;">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Agreement Level'); ?></label>
				<div class="col-md-7"><strong><?= $model->level ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12" style="margin-bottom: 0px;">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['approved_by'] ?></label>
				<div class="col-md-7"><strong><?= !empty($model->approved_by) ? $model->approvedBy->pegawai_nama : "-"; ?></strong></div>
			</div>
			<div class="form-group col-md-12" style="margin-bottom: 0px;">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['tanggal_approve'] ?></label>
				<div class="col-md-7"><strong><?= !empty($model->status == \app\models\TApproval::STATUS_APPROVED) ? app\components\DeltaFormatter::formatDateTimeForUser2($model->updated_at) : "-"; ?></strong></div>
			</div>
			<div class="form-group col-md-12" style="margin-bottom: 0px;">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['status'] ?></label>
				<div class="col-md-7"><strong>
						<?php

						use app\models\TBpb;
						use app\models\TBpbDetail;
                                                        use yii\helpers\Json;

						if ($model->status == \app\models\TApproval::STATUS_APPROVED) {
							echo '<span class="label label-success">' . $model->status . '</span>';
						} else if ($model->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED) {
							echo '<span class="label label-default">' . $model->status . '</span>';
						} else if ($model->status == \app\models\TApproval::STATUS_REJECTED) {
							echo '<span class="label label-danger">' . $model->status . '</span>';
						}
						?>
					</strong></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-hoki bordered">
				<div class="portlet-title">
					<div class="tools" style="float: left;">
						<a href="javascript:;" class="collapse" data-original-title="" title=""> </a> &nbsp;
					</div>
					<div class="caption"> <?= Yii::t('app', 'Show Detail ') . \app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no); ?> </div>
				</div>
				<div class="portlet-body" style="background-color: #d9e2f0">
					<div class="row">
						<div class="col-md-6">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%"><?= Yii::t('app', 'Tanggal SPB'); ?></td>
									<td style="font-weight: 800;">: <?= !empty($modReff->spb_tanggal) ? app\components\DeltaFormatter::formatDateTimeForUser2($modReff->spb_tanggal) : "<center>-</center>"; ?></td>
								</tr>
								<tr>
									<td><?= Yii::t('app', 'Diminta Oleh'); ?></td>
									<td style="font-weight: 800;">: <?= $modReff->spbDiminta->pegawai_nama ?> - <?= $modReff->departement->departement_nama; ?></td>
								</tr>
							</table>
						</div>
						<?php
						$by_setuju = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = " . $modReff->spb_disetujui)->queryOne();
						$by_ketahui = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = " . $modReff->spb_mengetahui)->queryOne();
						?>
						<div class="col-md-6">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%"><?= Yii::t('app', 'Disetujui Oleh'); ?></td>
									<td style="font-weight: 800;">: <?= !empty($modReff->spb_disetujui) ? $modReff->spbDisetujui->pegawai_nama : " - "; ?>
										<?php
										if ($by_setuju['status'] == \app\models\TApproval::STATUS_APPROVED) {
											echo " <br>&nbsp; <span class='font-green-seagreen'> " . \app\models\TApproval::STATUS_APPROVED . " at " .
												\app\components\DeltaFormatter::formatDateTimeForUser2($by_setuju['updated_at']) . "</span>";
										} else if ($by_setuju['status'] == \app\models\TApproval::STATUS_REJECTED) {
											echo " <br>&nbsp; <span class='font-red-flamingo'> " . \app\models\TApproval::STATUS_REJECTED . " at " .
												\app\components\DeltaFormatter::formatDateTimeForUser2($by_setuju['updated_at']) . "</span>";
										} else {
											echo "<br>&nbsp; <i>(Not Confirm)</i>";
										}
										if ($modReff->reason_approval) {
											foreach (Json::decode($modReff->reason_approval) as $reason) {
												echo $modReff->spb_disetujui == $reason['assigned_to'] ? '<br/>&nbsp; <span style="font-style: italic">( ' . $reason['reason'] . ' )</span>' : '';
											}
										}
										?>
									</td>
								</tr>
								<tr>
									<td><?= Yii::t('app', 'Diketahui Oleh'); ?></td>
									<td style="font-weight: 800;">: <?= !empty($modReff->spb_mengetahui) ? $modReff->spbMengetahui->pegawai_nama : " - "; ?>
										<?php
										if ($by_ketahui['status'] == \app\models\TApproval::STATUS_APPROVED) {
											echo " <br>&nbsp; <span class='font-green-seagreen'> " . \app\models\TApproval::STATUS_APPROVED . " at " .
												\app\components\DeltaFormatter::formatDateTimeForUser2($by_ketahui['updated_at']) . "</span>";
										} else if ($by_ketahui['status'] == \app\models\TApproval::STATUS_REJECTED) {
											echo " <br>&nbsp; <span class='font-red-flamingo'> " . \app\models\TApproval::STATUS_REJECTED . " at " .
												\app\components\DeltaFormatter::formatDateTimeForUser2($by_ketahui['updated_at']) . "</span>";
										} else {
											echo "<br>&nbsp; <i>(Not Confirm)</i>";
										}

										if ($modReff->reason_approval) {
											foreach (Json::decode($modReff->reason_approval) as $reason) {
												echo $modReff->spb_mengetahui == $reason['assigned_to'] ? '<br/>&nbsp; <span style="font-style: italic">( ' . $reason['reason'] . ' )</span>' : '';
											}
										}
										?>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
									<thead>
										<tr>
											<th style="width: 30px;">No.</th>
											<th style="width: 35%;"><?= Yii::t('app', 'Nama Item'); ?></th>
											<th style="width: 80px; text-align: center"><?= Yii::t('app', 'Current Stock'); ?></th>
											<th style="width: 80px; text-align: center"><?= Yii::t('app', 'Qty Pemakaian Bulan Ini'); ?></th>
											<th style="width: 80px; text-align: center"><?= Yii::t('app', 'Qty Diminta'); ?></th>
											<th><?= Yii::t('app', 'Keterangan'); ?></th>
											<th><?= Yii::t('app', 'Tanggal Dibutuhkan'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = "SELECT * FROM t_bpb_detail ";
										$sql .= "INNER JOIN t_bpb ON t_bpb_detail.bpb_id = t_bpb.bpb_id ";
										$sql .= "WHERE t_bpb.departement_id = $modReff->departement_id ";
										$sql .= "AND ( t_bpb.bpb_tgl_diterima BETWEEN '" . date('Y-m-01') . "' AND '" . date('Y-m-t') . "' ) ";
										$bpb_1_bulan = Yii::$app->db->createCommand($sql)->queryAll();

										function pemakaian_bulan_ini($bpb_1_bulan, $bhp_id)
										{
											$total = 0;
											if (count($bpb_1_bulan) > 0) {
												foreach ($bpb_1_bulan as $bpb) {
													if ($bpb['bhp_id'] == $bhp_id) {
														$total += $bpb['bpbd_jml'];
													}
												}

											}
											return $total;
										}

										if (count($modDetail) > 0) {
											foreach ($modDetail as $i => $detail) {
										?>
												<tr>
													<td style="padding: 3px; text-align: center;"><?= $i + 1 ?></td>
													<td style="padding: 3px;"><?= $detail->bhp->bhp_nm; ?></td>
													<td style="padding: 3px; text-align: center;"><?= $detail->bhp->current_stock . " (" . $detail->bhp->bhp_satuan . ")" ?></td>
													<td style="padding: 3px; text-align: center;"><?= pemakaian_bulan_ini($bpb_1_bulan, $detail->bhp_id) . " (" . $detail->bhp->bhp_satuan . ")" ?></td>
													<td style="padding: 3px; text-align: center;"><?= $detail->spbd_jml . " (" . $detail->bhp->bhp_satuan . ")"; ?></td>
													<td style="padding: 3px; padding: 5px; font-size: 1.1rem;"><?= nl2br($detail->spbd_ket) ?></td>
													<td style="padding: 3px; text-align: center"><?= app\components\DeltaFormatter::formatDateTimeForUser2($detail->spbd_tgl_dipakai) ?></td>
												</tr>
										<?php
											}
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer" style="text-align: center;">
	<?php if ((empty($model->approved_by)) && (empty($model->tanggal_approve))) { ?>
		<?= yii\helpers\Html::button(Yii::t('app', 'Approve'), ['class' => 'btn hijau btn-outline', 'onclick' => "confirmSPB(" . $model->approval_id . ",'approve')"]); ?>
		<?= yii\helpers\Html::button(Yii::t('app', 'Reject'), ['class' => 'btn red btn-outline', 'onclick' => "confirmSPB(" . $model->approval_id . ",'reject')"]); ?>
	<?php } ?>
</div>