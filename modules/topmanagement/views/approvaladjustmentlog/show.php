<?php
$model = \app\models\TApproval::findOne($approval_id);

$modAdjustmentLog = \app\models\TAdjustmentLog::findOne(['kode'=>$model->reff_no]);
    $adjustment_log_kode = $model->reff_no;
    $jml_batang_loglist = $modAdjustmentLog->jml_batang_loglist;
    $jml_m3_loglist = $modAdjustmentLog->jml_m3_loglist;
    $jml_batang_terima = $modAdjustmentLog->jml_batang_terima;
    $jml_m3_terima = $modAdjustmentLog->jml_m3_terima;
    $reff_no_loglist = $modAdjustmentLog->reff_no_loglist;
    $reff_no_spk = $modAdjustmentLog->reff_no_spk;

$modLoglist = \app\models\TLoglist::findOne(['loglist_kode'=>$reff_no_loglist]);
    $loglist_id = $modLoglist->loglist_id;
    $pengajuan_pembelianlog_id = $modLoglist->pengajuan_pembelianlog_id;
        $sql_jumlah_batang_loglist = "select count(*) from t_loglist_detail where loglist_id = ".$loglist_id."";
        $sql_jumlah_m3_loglist = "select sum(volume_value) from t_loglist_detail where loglist_id = ".$loglist_id."";
        $loglist_total_batang = Yii::$app->db->createCommand($sql_jumlah_batang_loglist)->queryScalar();
        $loglist_total_volume = \app\components\DeltaFormatter::formatNumberForAllUser(Yii::$app->db->createCommand($sql_jumlah_m3_loglist)->queryScalar());
    
// SPK SHIPPING
$modSpkShipping = \app\models\TSpkShipping::findOne(['kode'=>$reff_no_spk]);
    $spk_shipping_id = $modSpkShipping->spk_shipping_id;
    $spk_shipping_kode = $modSpkShipping->kode;
    $spk_shipping_total_batang = $modSpkShipping->estimasi_total_batang;
    $spk_shipping_total_volume = $modSpkShipping->estimasi_total_m3;

// PENGAJUAN PEMBELIAN LOG
$modPengajuanPembelianLog = \app\models\TPengajuanPembelianlog::findOne(['pengajuan_pembelianlog_id'=>$pengajuan_pembelianlog_id]);
    $pengajuan_pembelianlog_kode = $modPengajuanPembelianLog->kode;
    $sql_pengajuan_pembelianlog_total_batang = "select sum(qty_batang) from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."";
    $pengajuan_pembelianlog_total_batang = Yii::$app->db->createCommand($sql_pengajuan_pembelianlog_total_batang)->queryScalar();
    $pengajuan_pembelianlog_total_volume = $modPengajuanPembelianLog->total_volume;

/* TERIMA LOG ALAM
$modTerimaLogalams = \app\models\TTerimaLogalam::findAll(['spk_shipping_id' => $spk_shipping_id]);
    foreach ($modTerimaLogalams as $key => $modTerimaLogalam) {
        $terima_logalam_id = $modTerimaLogalam->id;
        $terima_logalam_kode = $modTerimaLogalam->kode." ";
        $sql_terima_logalam_total_batang = "select count(*) as total_batang from t_terima_logalam_detail where terima_logalam_id = ".$terima_logalam_id." ";
        $terima_logalam_total_batang = Yii::$app->db->createCommand($sql_terima_logalam_total_batang)->queryScalar();
        $sql_terima_logalam_total_volume = "select sum(volume) as total_volume from t_terima_logalam_detail where terima_logalam_id = ".$terima_logalam_id."";
        $terima_logalam_total_volume = Yii::$app->db->createCommand($sql_terima_logalam_total_volume)->queryScalar();
    }
*/
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
	<?php // BARIS KETERANGAN ;?>
    <div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Approval Adjustment'); ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Berkas'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_berkas); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Loglist'); ?></label>
				<div class="col-md-7"><strong><?= $modAdjustmentLog->reff_no_loglist ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode SPK Shipping'); ?></label>
				<div class="col-md-7"><strong><?= $modAdjustmentLog->reff_no_spk ?></strong></div>
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
					}else if($model->status == \app\models\TApproval::STATUS_REJECTED || $model->status == "ABORTED"){
						echo '<span class="label label-danger">'.$model->status.'</span>';
					}
					?>
				</strong></div>
			</div>
		</div>
	</div>
    <?php // EO BARIS KETERANGAN ;?>

    <?php // BARIS SHOW DETAIL ;?>
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
						$by_approver1 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modAdjustmentLog->by_approver1)->queryOne();
						$by_approver2 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modAdjustmentLog->by_approver2)->queryOne();
						?>
						<br>
                        
                        <?php // KOLOM APPROVER 1 ;?>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'General Manager'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modAdjustmentLog->by_approver1)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php
											if($by_approver1['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_approver1['updated_at'])."</span>";

                                                if(!empty($modAdjustmentLog->approve_reason)){
                                                    $modApproveReason = \yii\helpers\Json::decode($modAdjustmentLog->approve_reason);
                                                    foreach($modApproveReason as $iap => $aprreas){
                                                        if($aprreas['by'] == $modAdjustmentLog->by_approver1){
                                                            echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                            echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
                                                    }
                                                }

											}else if($by_approver1['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_approver1['updated_at'])."</span>";

                                                if(!empty($modAdjustmentLog->reject_reason)){
                                                    $modRejectReason = \yii\helpers\Json::decode($modAdjustmentLog->reject_reason);
                                                    foreach($modRejectReason as $irj => $rjcreas){
                                                        if($rjcreas['by'] == $modAdjustmentLog->by_approver1){
                                                            echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                            echo "<br>&nbsp; <span class='font-red-flamingo'>( ".$rjcreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
                                                    }
                                                }

                                            }else if($by_approver1['status']=="ABORTED"){
												echo " <br>&nbsp; <span class='font-red-flamingo'>ABORTED<span>";
                                            } else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											}
											?>
										</span>
									</td>
								</tr>
							</table>
						</div>
                        <?php // EO KOLOM APPROVER 1;?>

                        <?php // KOLOM APPROVER ;?>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Direktur Utama'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modAdjustmentLog->by_approver1)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($by_approver2['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_approver1['updated_at'])."</span>";

                                                if(!empty($modAdjustmentLog->approve_reason)){
                                                    $modApproveReason = \yii\helpers\Json::decode($modAdjustmentLog->approve_reason);
                                                    foreach($modApproveReason as $iap => $aprreas){
                                                        if($aprreas['by'] == $modAdjustmentLog->by_approver1){
                                                            echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                            echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
                                                    }
                                                }

											}else if($by_approver2['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_approver1['updated_at'])."</span>";

                                                if(!empty($modAdjustmentLog->reject_reason)){
                                                    $modRejectReason = \yii\helpers\Json::decode($modAdjustmentLog->reject_reason);
                                                    foreach($modRejectReason as $irj => $rjcreas){
                                                        if($rjcreas['by'] == $modAdjustmentLog->by_approver1){
                                                            echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                            echo "<br>&nbsp; <span class='font-red-flamingo'>( ".$rjcreas['reason']." )</span>";
                                                            echo '</span>';
                                                        }
                                                    }
                                                }

                                            }else if($by_approver1['status']=="ABORTED"){
												echo " <br>&nbsp; <span class='font-red-flamingo'>ABORTED</span>";
                                            } else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											}
                                            ?>
										</span>
									</td>
								</tr>
							</table>
						</div>
                        <?php // EO KOLOM APPROVER 1;?>

						<?php
                        // KOLOM ABORTED
                        if ($by_approver1['status'] == "ABORTED") {
                        ?>
                        <div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Pembatalan oleh'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">:
                                        <?php
                                        //$kualitas = \app\models\THasilOrientasiKualitas::find()->where(['hasil_orientasi_id'=>$modReff->hasil_orientasi_id,'kayu_id'=>$kuantitas->kayu_id])->one();
                                        $cancel_transaksi = \app\models\TCancelTransaksi::find()->where(['reff_no' => $model->reff_no])->one();
                                        $cancel_by = $cancel_transaksi->cancel_by;
                                        $cancel_reason = $cancel_transaksi->cancel_reason;
                                        $updated_at = $cancel_transaksi->updated_at;
                                        echo \app\models\MPegawai::findOne($cancel_by)->pegawai_nama;
                                        ?>
										<span style="font-weight: 500; font-size: 1rem; line-height: 15px;">
											<span style="font-weight: 500; font-size: 0.9rem;"><br>&nbsp; <span class='font-red-flamingo'><?php echo $cancel_reason;?></span>
                                            <span class='font-red-flamingo'><br>&nbsp; <?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($updated_at);?></span>
                                        </span>
									</td>
								</tr>
							</table>
						</div>
                        <?php
                        }
                        // EO KOLOM ABORTED
                        ?>
                        
                        <div class="col-md-12">
                            <div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-kuantitas">
									<thead>
										<tr style="height: 50px;">
											<th class="col-md-2"><?= Yii::t('app', 'Keterangan'); ?></th>
											<th class="col-md-2"><?= Yii::t('app', 'Pengajuan Pembelian Log'); ?></th>
											<?php /* <th class="col-md-2"><?= Yii::t('app', 'Loglist'); ?></th> */?>
											<th class="col-md-2"><?= Yii::t('app', 'SPK Shipping'); ?></th>
											<th class="col-md-2"><?= Yii::t('app', 'Jumlah Loglist'); ?></th>
                                            <th class="col-md-2"><?= Yii::t('app', 'Jumlah Penerimaan'); ?></th>
										</tr>
									</thead>
									<tbody>
                                        <tr style="height: 20px;">
                                            <td class="text-center">Kode</td>
                                            <td class="text-center"><?php echo $pengajuan_pembelianlog_kode;?></td>
                                            <?php /* <td class="text-center"><?php echo $modLoglist->loglist_kode;?></td> */?>
                                            <td class="text-center"><?php echo $spk_shipping_kode;?></td>
                                            <td class="text-center"><?php echo $modLoglist->loglist_kode;?></td>
                                            <td class="text-center"><?php //echo $adjustment_log_kode;?></td>
                                        </tr>
                                        <tr style="height: 20px;">
                                            <td class="text-center">Batang</td>
                                            <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($pengajuan_pembelianlog_total_batang,0);?></td>
                                            <?php /* <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($loglist_total_batang,0);?></td> */?>
                                            <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($spk_shipping_total_batang,0);?></td>
                                            <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($jml_batang_loglist,0);?></td>
                                            <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($jml_batang_terima,0);?></td>
                                        </tr>
                                        <tr style="height: 20px;">
                                            <td class="text-center">Kubikasi (M<sup>3</sup>)</td>
                                            <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($pengajuan_pembelianlog_total_volume,2);?></td>
                                            <?php /* <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($loglist_total_volume,2);?></td> */?>
                                            <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($spk_shipping_total_volume,2);?></td>
                                            <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($jml_m3_loglist,2);?></td>
                                            <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($jml_m3_terima,2);?></td>
                                        </tr>
									</tbody>
									<tfoot>									
									</tfoot>
								</table>
							</div>
                            
                            <?php // ATTACHMENT ;?>
							<div class="table-scrollable">
								<h4 style="padding-left: 10px;">Attachment <span style="font-size: 10px;">(Klik gambar untuk memperbesar)</span></h4> 
								<br>
								<?php
								$attachments = \app\models\TAttachment::find()->where(['reff_no'=>$modAdjustmentLog->kode])->orderBy("seq ASC")->all();
								if(count($attachments)>0){
									foreach($attachments as $i => $attch) { ?>

									<div class="col-md-2">
										<div class="thumbnail">
											<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('<?php echo $attch->attachment_id;?>')">
												<img src="<?= Yii::$app->urlManager->baseUrl ?>/uploads/ppic/adjustmentlog/<?= $attch->file_name; ?>" style="width: 150px;" alt="<?=  $attch->file_name;?>"/> 
											</a>
										</div>
									</div>
								<?php
									}
								}
								?>
							</div>
                            <?php // EO ATTACHMENT ;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <?php // EO BARIS SHOW DETAIL ;?>
</div>
<div class="modal-footer" style="text-align: center;">
	<?php if( (empty($model->approved_by)) && (empty($model->tanggal_approve)) ){ ?>
    <?php if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){ ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); ?>
    <?php } ?>
	<?php } ?>
</div>