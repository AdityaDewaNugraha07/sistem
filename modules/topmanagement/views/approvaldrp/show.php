<?php

use app\models\TOpenVoucher;
use app\models\TPengajuanDrp;
use app\models\TPengajuanDrpDetail;
use app\models\TVoucherPengeluaran;
use yii\bootstrap\Html;

$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TPengajuanDrp::findOne(['kode'=>$model->reff_no]);
   
// $model_t_pengajuan_drp = \app\models\TPengajuanDrp::findOne(['kode'=>$model->reff_no]);
$reason_approval = yii\helpers\Json::decode($modReff->reason_approval);
$reason_rejected = yii\helpers\Json::decode($modReff->reason_rejected);

$dis = ($model->status == 'Not Confirmed') ? '' : 'disabled';
?>
<style>
.form-group {
    margin-bottom: 0 !important;
}
</style>
<div class="modal-body" >
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode DRP'); ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Rencana Pembayaran'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['assigned_to'] ?></label>
				<div class="col-md-7"><strong><?= $model->assignedTo->pegawai_nama; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan'); ?></label>
				<div class="col-md-7"><strong><?= empty($modReff->keterangan)?'-':$modReff->keterangan ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
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
					if ($model->level == 3 || $model->level == 2) {
						// cek dulu status approval level 1
						$sql_status_approval_level_1 = "select status from t_approval where reff_no = '".$model->reff_no."' and level = 1 ";
						$status_approval_level_1 = Yii::$app->db->createCommand($sql_status_approval_level_1)->queryScalar($sql_status_approval_level_1);

						$sql_status_approval_level_2 = "select status from t_approval where reff_no = '".$model->reff_no."' and level = 2 ";
						$status_approval_level_2 = Yii::$app->db->createCommand($sql_status_approval_level_2)->queryScalar($sql_status_approval_level_2);
						
						if ($status_approval_level_1 != "REJECTED") {
							if ($status_approval_level_2 != "REJECTED"){
								if ($model->status == \app\models\TApproval::STATUS_APPROVED) {
									echo '<span class="label label-success">'.$model->status.'</span>';
								} else {
									echo '<span class="label label-default">'.$model->status.'</span>';
								}	
							} else {
								echo '<span class="label label-danger">REJECTED already by approval level 2</span>';
							}
						} else {
							echo '<span class="label label-danger">REJECTED already by approval level 1</span>';
						}
					} else {
						if($model->status == \app\models\TApproval::STATUS_APPROVED){
							echo '<span class="label label-success">'.$model->status.'</span>';
						}else if($model->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
							echo '<span class="label label-default">'.$model->status.'</span>';
						}else if($model->status == \app\models\TApproval::STATUS_REJECTED){
							echo '<span class="label label-danger">'.$model->status.'</span>';
						}
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
					<div class="row">
						<div class="col-md-12">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
									<thead>
										<tr>
											<th style="width: 30px;">No.</th>
											<th style="text-align: center; width: 10%;"><?= Yii::t('app', 'Kode Voucher'); ?></th>
											<th style="width: 10%;"><?= Yii::t('app', 'Kategori DRP'); ?></th>
											<th style="width: 10%;"><?= Yii::t('app', 'Tipe Voucher'); ?></th>
											<th style="width: 15%;"><?= Yii::t('app', 'Penerima Pembayaran'); ?></th>
											<!-- <th style="width: 15%;"><?= Yii::t('app', 'Reff'); ?></th> -->
											<th style=""><?= Yii::t('app', 'Keterangan Voucher'); ?></th>
											<th style="width: 8%;"><?= Yii::t('app', 'Jumlah'); ?></th>
											<?php if($model->status == 'Not Confirmed'){ ?>
												<th style="width: 35px; text-align: center;"><input type="checkbox" id="check-all" onchange="checkboxAll(this); total();" <?= $dis ?>></th>
											<?php }?>
										</tr>
									</thead>
									<tbody>
										<?php
										$modDetails = TPengajuanDrpDetail::find()->join('JOIN', 't_voucher_pengeluaran', 't_voucher_pengeluaran.voucher_pengeluaran_id = t_pengajuan_drp_detail.voucher_pengeluaran_id')
																				->where("pengajuan_drp_id = $modReff->pengajuan_drp_id AND (status_pengajuan IS NULL OR status_pengajuan = 'Disetujui')")
																				->orderBy(['t_voucher_pengeluaran.total_nominal'=>SORT_DESC])->all();
										$total = 0;
										if(count($modDetails)>0){
											foreach($modDetails as $i => $detail){
												//cari supplier
												$sql = "SELECT t_voucher_pengeluaran.kode, m_suplier.suplier_nm, t_gkk.gkk_id,	t_gkk.kode AS gkk_kode,
														t_ppk.ppk_id, t_ppk.kode AS ppk_kode,t_ajuandinas_grader.ajuandinas_grader_id, t_ajuandinas_grader.kode AS pdg_kode,
														t_ajuanmakan_grader.ajuanmakan_grader_id, t_ajuanmakan_grader.kode AS pmg_kode, t_log_bayar_dp.log_bayar_dp_id, t_log_bayar_dp.kode AS kode_dp,
														t_log_bayar_muat.log_bayar_muat_id, t_log_bayar_muat.kode AS kode_pelunasan,m_penerima_voucher.nama_penerima AS nama_penerima, 
														m_penerima_voucher.nama_perusahaan AS nama_perusahaan, m_suplierOV.suplier_nm AS suplier_ov, t_open_voucher.tipe AS tipe_ov,
														t_asuransi.kepada
														FROM t_pengajuan_drp 
														JOIN t_pengajuan_drp_detail on t_pengajuan_drp_detail.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id
														JOIN t_voucher_pengeluaran on t_voucher_pengeluaran.voucher_pengeluaran_id = t_pengajuan_drp_detail.voucher_pengeluaran_id
														LEFT JOIN t_open_voucher on t_open_voucher.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
														LEFT JOIN m_suplier ON m_suplier.suplier_id = t_voucher_pengeluaran.suplier_id 
														LEFT JOIN t_gkk ON t_gkk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
														LEFT JOIN t_ppk ON t_ppk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
														LEFT JOIN t_ajuandinas_grader ON t_ajuandinas_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
														LEFT JOIN t_ajuanmakan_grader ON t_ajuanmakan_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
														LEFT JOIN t_log_bayar_dp ON t_log_bayar_dp.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
														LEFT JOIN t_log_bayar_muat ON t_log_bayar_muat.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
														LEFT JOIN m_penerima_voucher ON m_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id
														LEFT JOIN m_suplier AS m_suplierOV ON m_suplierOV.suplier_id = t_open_voucher.penerima_reff_id
														LEFT JOIN t_asuransi ON t_asuransi.kode = t_open_voucher.reff_no
														WHERE t_pengajuan_drp.pengajuan_drp_id = {$modReff->pengajuan_drp_id} AND t_voucher_pengeluaran.voucher_pengeluaran_id = {$detail['voucher_pengeluaran_id']}";
												$sups = Yii::$app->db->createCommand($sql)->queryAll();
												$supplier='<center>-</center>';
												if (count($sups) > 0) {
													foreach ($sups as $a => $sup) {
														if($sup['suplier_nm'] !== null){
															$supplier = $sup['suplier_nm'];
														}else if($sup['gkk_kode'] !== null){
															$supplier= "<a onclick='gkk(".$sup['gkk_id'].")'>".$sup['gkk_kode']."</a>";
														}else if($sup['ppk_kode'] !== null){
															$supplier= "<a onclick='ppk(".$sup['ppk_id'].")'>".$sup['ppk_kode']."</a>";
														}else if($sup['pdg_kode'] !== null){
															$supplier="<a onclick='ajuanDinas(".$sup['ajuandinas_grader_id'].")'>".$sup['pdg_kode']."</a>";
														}else if($sup['pmg_kode'] !== null){
															$supplier="<a onclick='ajuanMakan(".$sup['ajuanmakan_grader_id'].")'>".$sup['pmg_kode']."</a>";
														}else if($sup['kode_dp'] !== null){
															$supplier= "<a onclick='infoAjuanDp(".$sup['log_bayar_dp_id'].")'>".$sup['kode_dp']."</a>";
														}else if($sup['kode_pelunasan'] !== null){
															$supplier= "<a onclick='infoPelunasan(".$sup['log_bayar_muat_id'].")'>".$sup['kode_pelunasan']."</a>";
														}else if($sup['nama_penerima'] !== null){
															$supplier= $sup['nama_penerima'];
														}else if($sup['nama_perusahaan'] !== null){
															$supplier= $sup['nama_penerima']." (".$sup['nama_perusahaan'].")";
														}else if($sup['tipe_ov'] !== null){
															if($sup['tipe_ov'] == "PEMBAYARAN ASURANSI LOG SHIPPING"){
																$supplier = $sup['kepada'];
															}else{
																$supplier = $sup['suplier_ov'];
															}
														}
													}
												};
												$modVoucherPengeluaran = TVoucherPengeluaran::findOne($detail->voucher_pengeluaran_id);
												$total += $modVoucherPengeluaran->total_nominal;
											?>
												<tr>
													<td style="text-align: center;"><?= $i+1; ?></td>
													<td style="text-align: left;"><?= $modVoucherPengeluaran->kode; ?></td>
													<td style="text-align: center;"><?= $detail['kategori']; ?></td>
													<td style="text-align: center;"><?= ($modVoucherPengeluaran->tipe == "Open Voucher")?$modVoucherPengeluaran->tipe."<br><b>".$sup['tipe_ov'].'</b>':$modVoucherPengeluaran->tipe; ?></td>
													<td style="text-align: center;"><?= $supplier; ?></td>
													<!-- <td style="text-align: left;"><?php //echo $detail['reff_ket']; ?></td> -->
													<td style="text-align: left;">
													<?php 
															$sql =  "SELECT keterangan FROM t_voucher_pengeluarandetail WHERE voucher_pengeluaran_id = {$detail['voucher_pengeluaran_id']}";
															$mods = Yii::$app->db->createCommand($sql)->queryAll(); ?>
															<?php 
															foreach($mods as $m => $mod){ 
																echo "- ".$mod['keterangan'] ;
																if(count($mods) > 1){
																	echo "<br>";
																} 
															}  
															?>
													</td>
													<td style="text-align: right; padding-right: 5px;">
														<input type="hidden" class="nominal" value="<?= $modVoucherPengeluaran->total_nominal; ?>">
														<?= \app\components\DeltaFormatter::formatNumberForUserFloat($modVoucherPengeluaran->total_nominal); ?>
													</td>
													<?php if($model->status == 'Not Confirmed'){ ?>
														<td style="text-align: center;"><input type="checkbox" class='check-item' uncheck='ditunda' data-detail-id="<?= $detail['pengajuan_drp_detail_id']; ?>" <?= $dis; ?> onchange="total();"></td>
													<?php } ?>
												</tr>
										<?php
											}
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="6" style="text-align: right;">TOTAL &nbsp; </td>
											<td style="text-align: right;">
												<span id="totalDisplay"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total);?></span>
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

<div class="modal-footer" style="text-align: center;">
	<?php

	$sql = "select * from t_approval where reff_no = trim('".$modReff->kode."') AND level < ".$model->level." AND status != 'Not Confirmed' ";
	$checkApprovals = Yii::$app->db->createCommand($sql)->queryAll();

	$sql_status_level1 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '1' ";
	$status_level1 = Yii::$app->db->createCommand($sql_status_level1)->queryScalar();

	$sql_status_level2 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '2' ";
	$status_level2 = Yii::$app->db->createCommand($sql_status_level2)->queryScalar();

	$sql_status_level3 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '3' ";
	$status_level3 = Yii::$app->db->createCommand($sql_status_level3)->queryScalar();

	$sql_status_level4 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '4' ";
	$status_level4 = Yii::$app->db->createCommand($sql_status_level4)->queryScalar();
	$status_level4 != "Not Confirmed" || $status_level4 != "Rejected" ? $status_level4 = "APPROVED" : $status_level4 = "REJECTED";
	

    if ($model->status == "Not Confirmed") {
		if( (empty($modApprove->approved_by)) && (empty($modApprove->tanggal_approve)) ){
			if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )) {

				if ($model->level == 1 && count($checkApprovals) == 0) {
					echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"validateCheckbox(".$model->approval_id.");"]);
					echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
				}

				if ($model->level == 2 && count($checkApprovals) > 0) {
					if ($status_level1 == "REJECTED") {
						echo "<button class='btn btn-danger'>REJECTED already by approval level 1</button>";
					} else {
						echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"validateCheckbox(".$model->approval_id.");"]);
						echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);						
					}
                }

                if ($model->level == 3 && count($checkApprovals) > 0) {
                    if ($status_level1 == "REJECTED") {
                        echo "<button class='btn btn-danger'>REJECTED already by approval level 1</button>";
                    } else if($status_level2 == "REJECTED"){
						echo "<button class='btn btn-danger'>REJECTED already by approval level 2</button>";
					} else {
						echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"validateCheckbox(".$model->approval_id.");"]);
                        echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);						
                    }
                }

				if ($model->level == 4 && count($checkApprovals) > 0) {
                    if ($status_level1 == "REJECTED") {
                        echo "<button class='btn btn-danger'>REJECTED already by approval level 1</button>";
                    } else if($status_level2 == "REJECTED"){
						echo "<button class='btn btn-danger'>REJECTED already by approval level 2</button>";
					} else if($status_level3 == "REJECTED"){
						echo "<button class='btn btn-danger'>REJECTED already by approval level 3</button>";
					} else {
						echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"validateCheckbox(".$model->approval_id.");"]);
                        echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);						
                    }
                }
			}
		}
	} else {
        if ($status_level1 == "APPROVED" && $status_level2 == "APPROVED" && $status_level3 == "APPROVED" && $status_level4 == "APPROVED") {
			$hasil_keputusan = "Data sudah disetujui pada tanggal ".app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve);
			$btn = "label-success";
		} else if ($status_level1 == "REJECTED" || $status_level2 == "REJECTED") {
			$hasil_keputusan = "Data sudah ditolak pada tanggal ".app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve);
			$btn = "label-danger";
		} else {
			$hasil_keputusan = "";
			$btn = "";
		}
    }

	isset($hasil_keputusan) ? $hasil_keputusan = $hasil_keputusan : $hasil_keputusan = "" ;
	isset($btn) ? $btn=$btn : $btn="" ;

    ?>
    <br><br>
    <div class="col-md-12">
        <?php
		$lvl_1 = \app\models\TApproval::find()->select(['assigned_to'])->where(['reff_no'=>$model->reff_no, 'level'=>1])->one();
		$approver_1 = $lvl_1->assigned_to;
		$lvl_2 = \app\models\TApproval::find()->select(['assigned_to'])->where(['reff_no'=>$model->reff_no, 'level'=>2])->one();
		$approver_2 = $lvl_2->assigned_to;
		$lvl_3 = \app\models\TApproval::find()->select(['assigned_to'])->where(['reff_no'=>$model->reff_no, 'level'=>3])->one();
		$approver_3 = $lvl_3->assigned_to;
		$pegawai_ids = array($approver_1, $approver_2, $approver_3); //pak yos, pak nowo, pak aseng/pak as
        foreach ($pegawai_ids as $pegawai_id) {
            $pegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$pegawai_id]);
            $t_approval = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>$pegawai_id]);
            
            if ($t_approval->status == "APPROVED") {
                $color = "#38C68B";
                if($t_approval->tanggal_berkas <='2020-09-30'){
                    $reasonx = "";
                }else{
                    $reasons = json_decode($modReff->reason_approval);
                    foreach($reasons as $reason) {
                        if ($pegawai_id == $reason->by) {
                            $reasonx = $reason->reason;
                        }
                    }
                }
            } 

            if ($t_approval->status == "REJECTED") {
                $color = "#f00";
                if($t_approval->tanggal_berkas <='2020-09-30'){
                    $reasony = "";
                }else{
                    $reasons = json_decode($modReff->reason_rejected);
                    foreach($reasons as $reason) {
                        if ($pegawai_id == $reason->by) {
                            $reasony = $reason->reason;
                        }
                    }
                }
            }

            isset($reasonx) ? $reasonx = $reasonx : $reasonx = "";
            isset($reasony) ? $reasony = $reasony : $reasony = "";            

        ?>
        <div class="col-md-4" style="font-size: 1.2rem;">
            <?php
            $color = "";
            if ($t_approval->status == "APPROVED") {
                $color = "#38C68B";
            } 

            if ($t_approval->status == "REJECTED") {
                $color = "#f00";
            }
            ?>
            <span style="color: <?php echo $color;?>"><strong><?php echo $pegawai->pegawai_nama;?></strong></span>
            <br>
            <span style="color: <?php echo $color;?>"><?php echo $t_approval->status;?></span>
            <span style="color: <?php echo $color;?>">at <?php echo app\components\DeltaFormatter::formatDateTimeForUser2($t_approval->updated_at);?></span>
            <br>
            <span style="color: <?php echo $color;?>">
                <?php 
                if ($t_approval->status == "APPROVED") {
                    echo $reasonx;
                } else if($t_approval->status == "Not Confirmed") {
                    echo '';
                } else {
					echo $reasony;
				}
                ?>
            </span>            
        </div>
        <?php
        }
        ?>
    </div>

    <?php
    // BUTTON HASIL KEPUTUSAN BAWAH SENDIRI CUY
    if ($hasil_keputusan != "" && $btn != "") {
    ?>
    <br><br><br>
    <div class="col-md-12 text-center">
        <button class="btn <?php echo $btn;?>" style="color: #fff;"><?php echo $hasil_keputusan;?></button> 
    </div>
    <br>
    <?php
    }
    ?>

</div>
<script>
	function checkboxAll(box){
		var checkboxes = document.querySelectorAll('.check-item');
		if (box.checked) {
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
				checkboxes[i].checked = true;
				}
			}
		} else { 
			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
				checkboxes[i].checked = false;
				}
			}
		}
	}

	function validateCheckbox(approval_id){
		var anyChecked = false;
		var details = [];

		$('.check-item').each(function() {
			var detailId = $(this).data('detail-id');
			var isChecked = this.checked;
			if (isChecked) {
                anyChecked = true;                
            }
			var status = isChecked ? 'Disetujui':'Ditunda';
			details.push({ detailId: detailId, status: status });
		});
		if (!anyChecked) {
			cisAlert('Mohon pilih data terlebih dahulu!');
			return;
		}
		confirm(approval_id, 'approve', details);
	}

	function total(){
		totalSum = 0;
        $('.check-item:checked').each(function() {
            var nominal = unformatNumber($(this).closest('tr').find('.nominal').val());
            totalSum += nominal;
        });
        $('#totalDisplay').text(formatInteger(totalSum));
	}
</script>