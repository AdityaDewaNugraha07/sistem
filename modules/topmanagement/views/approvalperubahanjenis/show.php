<?php
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TLogRubahjenis::findOne(['kode'=>$model->reff_no]);
?>
<style>
.form-group {
    margin-bottom: 0 !important;
}
table.table-striped thead tr th{
	padding : 3px !important;
}
.table-striped, 
.table-striped > tbody > tr > td, 
.table-striped > tbody > tr > th, 
.table-striped > tfoot > tr > td, 
.table-striped > tfoot > tr > th, 
.table-striped > thead > tr > td, 
.table-striped > thead > tr > th {
    border: 1px solid #A0A5A9;
	line-height: 0.9 !important;
	font-size: 1.2rem;
}
</style>
<div class="modal-body" >
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Permintaan'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kode; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Permintaan'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Peruntukan'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->peruntukan; ?></strong></div>
			</div>
            <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->keterangan; ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['assigned_to'] ?></label>
				<div class="col-md-7"><strong><?= $model->assignedTo->pegawai_nama; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['tanggal_approve'] ?></label>
				<div class="col-md-7"><strong><?= !empty($model->tanggal_approve)?app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve):"-"; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['status'] ?></label>
				<div class="col-md-7"><strong>
					<?php
					if($model->status == \app\models\TApproval::STATUS_APPROVED){
						echo '<span class="label label-success">'.$model->status.'</span>';
					}else if($model->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
						echo '<span class="label label-default">'.$model->status.'</span>';
					}else if($model->status == \app\models\TApproval::STATUS_REJECTED){
						echo '<span class="label label-danger">'.$model->status.'</span>';
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
					<div class="caption"> <?= Yii::t('app', 'Show Detail'); ?> </div>
				</div>
				<div class="portlet-body" style="background-color: #d9e2f0" >
					<div class="row"  style="margin-top: -15px;">
						<br>
                        <?php
                        $models = \app\models\TApproval::find()->where(['reff_no'=>$model->reff_no])->all();
                        for ($i=1; $i<=count($models); $i++) {
                            $sql_pegawai_id = "select assigned_to from t_approval where reff_no = '".$model->reff_no."' and level = ".$i."";
                            $pegawai_id = Yii::$app->db->createCommand($sql_pegawai_id)->queryScalar();
                            $json = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND level = ".$i."")->queryOne();
                            if ($json['status'] == "APPROVED") {
                                $addClass = "green";
                            } else if ($json['status'] == "REJECTED") {
                                $addClass = "red";
                            } else {
                                $addClass = "grey";
                            }
                        ?>
                            <div class="col-md-4">
                                <table style="width: 100%; font-size: 1.1rem;">
                                    <tr>
                                        <td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Approved By'); ?></td>
                                        <td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
                                            <?= \app\models\MPegawai::findOne($pegawai_id)->pegawai_nama; ?>
                                            <span style="font-weight: 500; font-size: 1rem;">
                                                <?php
                                                if($json['status']==\app\models\TApproval::STATUS_APPROVED){
                                                    echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
                                                            \app\components\DeltaFormatter::formatDateTimeForUser2($json['updated_at'])."</span>";
                                                    $modApproveReason = \yii\helpers\Json::decode($modReff->reason_approval);
                                                    if ($modApproveReason != "") {
                                                        foreach($modApproveReason as $iap => $aprreas){
                                                            if($aprreas['by'] == $json['assigned_to']){
                                                                echo '<span style="font-weight: 500;">';
                                                                echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                                echo '</span>';
                                                            }
                                                        }
                                                    }
                                                }else if($json['status']==\app\models\TApproval::STATUS_REJECTED){
                                                    echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
                                                            \app\components\DeltaFormatter::formatDateTimeForUser2($json['updated_at'])."</span>";
                                                    $modRejectReason = \yii\helpers\Json::decode($modReff->reason_rejected);
                                                    if ($modRejectReason != "") {
                                                        foreach($modRejectReason as $iap => $rejreas){
                                                            if($rejreas['by'] == $json['assigned_to']){
                                                                $reason_rejected = $rejreas['reason'];
                                                            } else {
                                                                $reason_rejected = "Auto Reject";
                                                            }
                                                            echo '<span style="font-weight: 500;">';
                                                            echo "<br>&nbsp; <span class='text-danger'>( ".$reason_rejected." )</span>";
                                                            echo '</span>';
                                                        }
                                                    }
                                                }else{
                                                    echo "<br>&nbsp; <i>(Not Confirm)</i>";
                                                }
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php
                        }
                        ?>

						<div class="col-md-12">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-industri">
									<thead>
										<tr>
											<th class="td-kecil" style="width: 20px;"><?= Yii::t('app', 'No.'); ?></th>
                                            <th class="td-kecil" style=""><?= Yii::t('app', 'No Barcode / No Lap') ?></th>
                                            <th class="td-kecil" style=""><?= Yii::t('app', 'Jenis Kayu Lama') ?></th>
                                            <th class="td-kecil" style=""><?= Yii::t('app', 'Jenis Kayu Baru') ?></th>
                                            <th style="width: 30px;"><?= Yii::t('app', 'History<br>Perubahan') ?></th>
										</tr>
									</thead>
									<tbody>
                                        <?php 
                                        $datadetails = \yii\helpers\Json::decode($modReff->datadetail, true);
                                        foreach($datadetails as $i => $detail){ 
                                            $modKayuOld = \app\models\MKayu::findOne($detail['kayu_id_old']);
                                            $modKayuNew = \app\models\MKayu::findOne($detail['kayu_id_new']);
                                            ?>
                                            <tr>
                                                <td style="vertical-align: middle;"><?= $i + 1; ?></td>
                                                <td style="vertical-align: middle;"><?= $detail['no_barcode'] . ' / ' . $detail['no_lap']; ?></td>
                                                <td style="vertical-align: middle;"><?= $modKayuOld->kayu_nama; ?></td>
                                                <td style="vertical-align: middle;"><?= $modKayuNew->kayu_nama; ?></td>
                                                <td><center><a class="btn btn-md" href="javascript:void(0)" onclick="lihatHistory('<?= $detail['no_barcode'] ?>')" title="Click untuk lihat history perubahan jenis"><i class="fa fa-clock-o"></i></a></center></td>
                                            </tr>
                                        <?php } ?>
									</tbody>
									<tfoot>
										
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
<div class="modal-footer" style="text-align: center;">
	<?php if( (empty($model->approved_by)) && (empty($model->tanggal_approve)) ){ ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); ?>
	<?php } ?>
</div>
<script>
function lihatHistory(no_barcode){
    var url = '<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalperubahanjenis/lihatHistory','no_barcode'=>'']) ?>'+no_barcode;
	var modal_id = 'modal-history';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) { });
		$("#"+modal_id+" .modal-dialog").css('width',"40%");
	});
	return false;
}
</script>