<?php
$model = \app\models\TApproval::findOne($approval_id);
$approval_id = $model->approval_id;
$modelTApprovals = \app\models\TApproval::findAll(['reff_no'=>trim($model->reff_no)]);
$modTTerimaBhp = \app\models\TTerimaBhp::findOne(['terimabhp_kode'=>trim($model->reff_no)]);
$terima_bhp_id = $modTTerimaBhp->terima_bhp_id;
$modTTerimaBhpDetails = \app\models\TTerimaBhpDetail::findAll(['terima_bhp_id'=>$terima_bhp_id]);

?>

<div class="modal-body">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box blue-hoki bordered">
					<div class="portlet-title">
						<div class="tools" style="float: left;">
							<a href="javascript:;" class="collapse" data-original-title="" title=""> </a> &nbsp; 
						</div>
						<div class="caption">Daftar Terima Barang</div>
					</div>
					<div class="portlet-body" style="background-color: #d9e2f0" >
						<div class="row">
							<div class="col-md-12">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
										<thead>
											<tr>
												<th style="padding: 20px;">No.</th>
												<th style="padding: 20px;">Kode Terima BHP</th>
												<th style="padding: 20px;">Nama Barang</th>
												<th style="padding: 20px;">Jumlah</th>
												<th style="padding: 20px;" class="text-right">Harga</th>
												<th style="padding: 20px;" class="text-right">PPN</th>
												<th style="padding: 20px;" class="text-right">PPh</th>
												<th style="padding: 20px;" class="text-right">Supplier</th>
											</tr>
										</thead>
										<tbody>
										<?php
										$i = 1;
										foreach ($modTTerimaBhpDetails as $value) {
											$bhp_id = $value->bhp_id;
												$bhp_nama = Yii::$app->db->createCommand("select bhp_nm from m_brg_bhp where bhp_id = ".$bhp_id." ")->queryScalar();
											$jumlah = $value->terimabhpd_qty;
											$harga = $value->terimabhpd_harga;
											$ppn_peritem = $value->ppn_peritem;
											$pph_peritem = $value->pph_peritem;
											$suplier_id = $value->suplier_id;
												if (isset($suplier_id)) {
													//$suplier_nm = Yii::$app->db->createCommand("select suplier_nm from m_suplier where suplier_id = ".$suplier_id." ")->queryScalar();
													if ($suplier_id > 0) {
														$suplier_nm = Yii::$app->db->createCommand("select suplier_nm from m_suplier where suplier_id = ".$suplier_id." ")->queryScalar();
													} else {
														$suplier_nm = "<font style='color: #f00; font-weight: bold; text-align: center;'>BELUM DIISI</font>";
													}
												} else {
													$suplier_nm = "<font style='color: #f00; font-weight: bold; text-align: center;'>BELUM DIISI</font>";
												}
										?>
										<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $model->reff_no;?></td>
											<td><?php echo $bhp_nama;?></td>
											<td class='text-center'><?php echo $jumlah;?></td>
											<td class='text-right'><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($harga);?></td>
											<td class='text-right'><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($ppn_peritem);?></td>
											<td class='text-right'><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($pph_peritem);?></td>
											<td><?php echo $suplier_nm;?></td>
										</tr>
										<?php
											$i++;
										}
										?>
										</tbody>
									</table>
								</div>
							</div>
							<div style="margin: 10px;"><b>Keterangan : <?php echo $modTTerimaBhp->terimabhp_keterangan;?></b></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">

		<div class="modal-footer" style="text-align: center;">
			<div class="container col-md-12">
				<div class="row">
				<?php
				if (!empty($modTTerimaBhp->approve_reason) || !empty($modTTerimaBhp->reject_reason)) {
					// approval 1 dan 2 satu level ya
					// approval 1 : kadiv hrd ga (andrian argasasmita 124)
					// approval 2 : kadiv akt (nowo eko yulianto 58)
					$pegawai_ids = array(124,58);
					foreach ($pegawai_ids as $pegawai_id) {
						$pegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$pegawai_id]);
						$t_approval = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>$pegawai_id]);;
					?>
					<div class="col-md-6" style="font-size: 1.2rem;">
						<?php
						$color = "";
						if ($t_approval->status == "APPROVED") {
							$color = "#38C68B";
							$reasons = json_decode($modTTerimaBhp->approve_reason);
							foreach($reasons as $reason) {
								if ($pegawai_id == $reason->by) {
									$reasonx = $reason->reason;
								}
							}
						} 

						if ($t_approval->status == "REJECTED") {
							$color = "#f00";
							$reasons = json_decode($modTTerimaBhp->reject_reason);
							foreach($reasons as $reason) {
								if ($pegawai_id == $reason->by) {
									$reasony = $reason->reason;
								}
							}				                            	
						}

						isset($reasonx) ? $reasonx = $reasonx : $reasonx = "";
						isset($reasony) ? $reasony = $reasony : $reasony = "";
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
							} 

							if ($t_approval->status == "REJECTED") {
								echo $reasony; 
							}
							?>
						</span> 
					</div>
					<?php
					}
				}
				?>
				</div>

				<?php
				if ($model->status == "Not Confirmed") {
				?>
				<div class="row" style="padding-top: 10px; padding-bottom: 10px;">
				<?php
				if ($model->level == 1) {
					echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
					//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					//echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
				}
				?>
				</div>
				<?php
				}
				?>
			</div>

		</div>

	</div>
</div>



