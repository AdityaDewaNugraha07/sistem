<?php
$model = \app\models\TApproval::findOne($approval_id);
$approval_id = $model->approval_id;

$model_m_harga_limbah = \app\models\MHargaLimbah::findAll(['kode'=>$model->reff_no]);
$model_m_harga_limbahx = \app\models\MHargaLimbah::findOne(['kode'=>$model->reff_no]);

//$approve_reason = yii\helpers\Json::decode($model_h_harga_limbah->approve_reason);
//$reject_reason = yii\helpers\Json::decode($model_h_harga_limbah->reject_reason);

$model_t_approval = \app\models\TApproval::findAll(['reff_no'=>trim($model->reff_no)]);

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
						<div class="caption">Daftar Harga Limbah</div>
					</div>
					<div class="portlet-body" style="background-color: #d9e2f0" >
						<div class="row">
                            <div class="col-md-6 text-left small" style="font-style: italic;">
                                * Pricelist yang di tampilkan hanya Limbah yang harga sebelumnya berbeda (tidak sama) dengan harga pengajuan
                            </div>
							<div class="col-md-6 text-right small" style="font-style: italic;">
                                * Klik tanda <i class='fa fa-arrow-circle-down' aria-hidden='true' style='color: #33cc33;'></i> atau
                                <i class='fa fa-arrow-circle-right' aria-hidden='true' style='color: #bebebe;'></i> atau 
                                <i class='fa fa-arrow-circle-up' aria-hidden='true' style='color: #ff0000;'></i>
                                untuk melihat history lengkap perubahan harga
                            </div>
							<div class="col-md-12">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
										<thead>
											<tr>
												<th style="width: 30px;">No.</th>
												<th style="width: 45%;"><?= Yii::t('app', 'Limbah'); ?></th>
												<th><?= Yii::t('app', 'Harga Sebelumnya'); ?></th>
												<th><?= Yii::t('app', 'Harga Pengajuan'); ?></th>
											</tr>
										</thead>
										<tbody>
										<?php
										$i = 1;
										foreach ($model_m_harga_limbah as $list => $kolom) {
											$limbah_id = $kolom['limbah_id'];
											$harga_tanggal_penetapan = $kolom['harga_tanggal_penetapan'];

											$harga_enduser = $kolom['harga_enduser'];
											
											$limbah = \app\models\MBrgLimbah::findOne($limbah_id);
											$limbah_kode = $limbah->limbah_kode;
											$limbah_nama = $limbah->limbah_nama;
											// $limbah_dimensi = $limbah->limbah_dimensi;

											$status_approval = $kolom['status_approval'];
                                                                                        
                                            $sql_harga_lama = "select a.harga_enduser ".
                                                            "	from m_harga_limbah a ".
                                                            "	where a.limbah_id = ".$limbah_id." ".
                                                            "	and a.status_approval = 'APPROVED' ".
                                                            "	and a.harga_tanggal_penetapan < '".$harga_tanggal_penetapan."' ".
                                                            "	order by a.harga_id desc ".
                                                            "	limit 1 ".
                                                            "	";
                                            $harga_lama = Yii::$app->db->createCommand($sql_harga_lama)->queryScalar();
                                            $harga_lama > 0 || $harga_lama != NULL ? $harga_lama = $harga_lama : $harga_lama = 0;
                                            if($harga_enduser <> $harga_lama && $harga_enduser <> 0){
										?>
										<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $limbah_nama;?></td>
											<td class="text-right">
											<?php
											
											echo "Rp ".\app\components\DeltaFormatter::formatNumberForUser($harga_lama);
											?>
											</td>
											<td class="text-right">
												<?php
												if ($harga_enduser > $harga_lama) {
													$color = "#ff0000";
                                            		$sign = "<a onclick='graf(".$kolom['limbah_id'].")'><i class='fa fa-arrow-circle-up' aria-hidden='true' style='color: #ff0000;')'></i></a>";
												} else if ($harga_enduser < $harga_lama) {
													$color = "#33cc33";
													$sign = "<a onclick='graf(".$kolom['limbah_id'].")'><i class='fa fa-arrow-circle-down' aria-hidden='true' style='color: #33cc33;'></i></a>";
												} else {
													$color = "#000";
													$sign = "<a onclick='graf(".$kolom['limbah_id'].")'><i class='fa fa-arrow-circle-right' aria-hidden='true' style='color: #dedede;'></i></a>";
												}
												?>
												<font style="color: <?php echo $color;?>"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($harga_enduser)." ".$sign;?></font>
											</td>
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
					$model_m_harga_limbah = \app\models\MHargalimbah::findOne(['kode'=>$model->reff_no]);

					if (!empty($model_m_harga_limbah->approve_reason) || !empty($model_m_harga_limbah->reject_reason)) {
						// batal
						//approval 1 : gm marketing (inge tjandra 122)
						//approval 2 : kadiv akt (nowo eko yulianto 58)
						//approval 3 : dir (heryanto suwardi 22)
						//approval 4 : dirut (agus soewito 59)

						// revisi 2020-05-20
						//approval 1 : kadiv marketing (iwan s 19)
						//approval 2 : gm marketing (inge tjandra 122)
						//approval 3 : dir (heryanto suwardi 22)
						//approval 4 : dirut (agus soewito 59)
						$levels = array(
                            'level1'=>\app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO,
                            'level2'=>\app\components\Params::DEFAULT_PEGAWAI_ID_ASENG
                        );

						foreach ($levels as $level => $pegawai_id) {
							$pegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$pegawai_id]);
							$t_approval = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>$pegawai_id]);
						?>
						<div class="col-md-3" style="font-size: 1.2rem;">
							<?php
							$color = "";
							if ($t_approval->status == "APPROVED") {
								$color = "#38C68B";
								$reasons = json_decode($model_m_harga_limbah->approve_reason);
								foreach($reasons as $reason) {
									if ($pegawai_id == $reason->by) {
										$reasonx = $reason->reason;
									}
								}
							} 

							if ($t_approval->status == "REJECTED") {
								$color = "#f00";
								$reasons = json_decode($model_m_harga_limbah->reject_reason);
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
						if( (empty($model_t_approval->approved_by)) && (empty($model_t_approval->tanggal_approve)) ){
							if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )) {
								$model_m_harga_limbah = \app\models\MHargalimbah::findOne(['kode'=>$model->reff_no]);
								
								// cari level dibawahnya dulu
								$level_approver_sebelumnya = $model->level - 1;

								$sql = "select * from t_approval where reff_no = trim('".$model_m_harga_limbah->kode."') AND level = ".$level_approver_sebelumnya." AND status != 'Not Confirmed' ";
								$checkApprovals = Yii::$app->db->createCommand($sql)->queryAll();

								if ($model->level == 1 && count($checkApprovals) == 0) {
									echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
									echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
								}

								if ($model->level == 2 && count($checkApprovals) > 0) {
									echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
									echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
								}

								// if ($model->level == 3 && count($checkApprovals) > 0) {
								// 	echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
								// 	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								// 	echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
								// }

								// if ($model->level == 4 && count($checkApprovals) > 0) {
								// 	echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
								// 	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								// 	echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
								// }
							}
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
</div>

<script>
    function graf(id,kode) {
        openModal('<?= \yii\helpers\Url::toRoute(['/marketing/pricelistlimbah/graf','id'=>'']) ?>'+id+'&kode='+kode,'modal-madul','85%');
    }
</script>