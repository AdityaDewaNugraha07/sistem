<?php
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TPengajuanRepacking::findOne(['kode'=>$model->reff_no]);
$modDetail = \app\models\TPengajuanRepackingDetail::find()->where(['pengajuan_repacking_id'=>$modReff->pengajuan_repacking_id])->all();
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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Keperluan'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->keperluan; ?></strong></div>
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
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['approved_by'] ?></label>
				<div class="col-md-7"><strong><?= !empty($model->approved_by)?$model->approvedBy->pegawai_nama:"-"; ?></strong></div>
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
						<?php
						/*$by_prepared = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->prepared_by)->queryOne();
						$by_approved = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->approved_by)->queryOne();
                        $by_approved2 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND level = 3")->queryOne();*/
                        ?>
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
                                        <td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Prepared By'); ?></td>
                                        <td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
                                            <?= \app\models\MPegawai::findOne($pegawai_id)->pegawai_nama; ?>
                                            <span style="font-weight: 500; font-size: 1rem;">
                                                <?php
                                                if($json['status']==\app\models\TApproval::STATUS_APPROVED){
                                                    echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
                                                            \app\components\DeltaFormatter::formatDateTimeForUser2($json['updated_at'])."</span>";
                                                    $modApproveReason = \yii\helpers\Json::decode($modReff->approve_reason);
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
                                                    $modRejectReason = \yii\helpers\Json::decode($modReff->reject_reason);
                                                    if ($modRejectReason != "") {
                                                        foreach($modRejectReason as $iap => $rejreas){
                                                            if($rejreas['by'] == $json['assigned_to']){
                                                                $reject_reason = $rejreas['reason'];
                                                            } else {
                                                                $reject_reason = "Auto Reject";
                                                            }
                                                            echo '<span style="font-weight: 500;">';
                                                            echo "<br>&nbsp; <span class='text-danger'>( ".$reject_reason." )</span>";
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

                        <?php /* APPROVER LVL 1 ;?>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Prepared By'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->prepared_by)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php
											if($by_prepared['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_prepared['updated_at'])."</span>";
                                                $modApproveReason = \yii\helpers\Json::decode($modReff->approve_reason);
                                                if ($modApproveReason != "") {
                                                    foreach($modApproveReason as $iap => $aprreas){
                                                        if($aprreas['by'] == $by_prepared['assigned_to']){
                                                            echo '<span style="font-weight: 500;">';
                                                            echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
                                                    }
                                                }
											}else if($by_prepared['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_prepared['updated_at'])."</span>";
                                                $modRejectReason = \yii\helpers\Json::decode($modReff->reject_reason);
                                                if ($modRejectReason != "") {
                                                    foreach($modRejectReason as $iap => $rejreas){
                                                        if($rejreas['by'] == $by_prepared['assigned_to']){
                                                            echo '<span style="font-weight: 500;">';
                                                            echo "<br>&nbsp; <span class='text-danger'>( ".$rejreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
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
                        <?php // APPROVER LVL 2 ;?>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Approved By'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->approved_by)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php
											if($by_approved['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_approved['updated_at'])."</span>";
                                                $modApproveReason = \yii\helpers\Json::decode($modReff->approve_reason);
                                                if ($modApproveReason != "") {
                                                    foreach($modApproveReason as $iap => $aprreas){
                                                        if($aprreas['by'] == $by_approved['assigned_to']){
                                                            echo '<span style="font-weight: 500;">';
                                                            echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
                                                    }
                                                }
											}else if($by_approved['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_approved['updated_at'])."</span>";
                                                $modRejectReason = \yii\helpers\Json::decode($modReff->reject_reason);
                                                if ($modRejectReason != "") {
                                                    foreach($modRejectReason as $iap => $rejreas){
                                                        if($rejreas['by'] == $by_approved['assigned_to']){
                                                            echo '<span style="font-weight: 500;">';
                                                            echo "<br>&nbsp; <span class='text-danger'>( ".$rejreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
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
                        <?php // APPROVER LVL 3 ;?>
						<?php
                        if (count(\app\models\TApproval::find()->where(['reff_no'=>$model->reff_no])->all()) == 3) {
                        ?>
                        <div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Approved By'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($by_approved2['assigned_to'])->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
                                        <?php
											if($by_approved2['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_approved2['updated_at'])."</span>";
                                                $modApproveReason = \yii\helpers\Json::decode($modReff->approve_reason);
                                                if ($modApproveReason != "") {
                                                    foreach($modApproveReason as $iap => $aprreas){
                                                        if($aprreas['by'] == $by_approved2['assigned_to']){
                                                            echo '<span style="font-weight: 500;">';
                                                            echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
                                                    }
                                                }
											}else if($by_approved2['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_approved2['updated_at'])."</span>";
                                                $modRejectReason = \yii\helpers\Json::decode($modReff->reject_reason);
                                                if ($modRejectReason != "") {
                                                    foreach($modRejectReason as $iap => $rejreas){
                                                        
                                                        if($rejreas['by'] == $by_approved2['assigned_to']){
                                                            echo '<span style="font-weight: 500;">';
                                                            echo "<br>&nbsp; <span class='text-danger'>( ".$rejreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
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
                        }*/
                        ?>
						<?php if(count($modDetail)>0){ ?>
						<div class="col-md-12">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-industri">
									<thead>
										<tr>
											<th class="td-kecil" style="width: 20px;"><?= Yii::t('app', 'No.'); ?></th>
                                            <th class="td-kecil" style="width: 80px; line-height: 1;"><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
                                            <th class="td-kecil" style="width: 220px; padding: 20px;"><?= Yii::t('app', 'Kode Produk') ?></th>
                                            <th class="td-kecil" ><?= Yii::t('app', 'Nama Produk') ?></th>
                                            <th class="td-kecil" style="width: 220px;"><?= Yii::t('app', 'Dimensi') ?></th>
                                            <th class="td-kecil" style="width: 50px;"><?= Yii::t('app', 'Qty Palet') ?></th>
											<?php if($modReff->keperluan == 'Penanganan Barang Retur'){ ?>
												<th class="td-kecil" style="width: 50px;"><?= Yii::t('app', 'Pcs') ?></th>
												<th class="td-kecil" style="width: 50px;"><?= Yii::t('app', 'Kubikasi') ?></th>
											<?php } ?>
                                            <th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'Keterangan') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$total_palet = 0;
										foreach($modDetail as $i => $detail){
											$modRetur = \app\models\TReturProdukDetail::findOne($detail->retur_produk_detail_id);
											if($modReff->keperluan == 'Penanganan Barang Retur'){
												$nama_produk = $modRetur->nomor_produksi . ' - ' . $detail->produk->produk_nama;
											} else {
												$nama_produk = $detail->produk->produk_nama;
											}
											echo "<tr>";
											echo	"<td>".($i+1)."</td>";
											echo	"<td>".$detail->produk->produk_group."</td>";
											echo	"<td>".$detail->produk->produk_kode."</td>";
											echo	"<td>".$nama_produk."</td>";
                                            echo	"<td style='text-align:center'>".$detail->produk->produk_dimensi."</td>";
                                            echo	"<td style='text-align:center'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_besar)."</td>";
											if($modReff->keperluan == 'Penanganan Barang Retur'){
												echo	"<td style='text-align:center'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil)."</td>";
												echo	"<td style='text-align:center'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail->kubikasi)."</td>";
											}
                                            echo	"<td>".$detail->keterangan."</td>";
											echo "</tr>";
                                            $total_palet += $detail->qty_besar;
										}
										?>
									</tbody>
									<tfoot>
										<?php if($modReff->keperluan !== 'Penanganan Barang Retur'){ ?>
										<tr>
											<td colspan="5" style="text-align: right;">Total &nbsp; </td>
											<td class="text-align-center"> <?= $total_palet ?> </td>
                                            <td></td>
										</tr>
										<?php } ?>
									</tfoot>
								</table>
							</div>
						</div>
						<?php } ?>
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

</script>