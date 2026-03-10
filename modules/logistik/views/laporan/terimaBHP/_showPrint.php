<div id="yyy" class="portlet-body" id="ajax" style="padding-left: -15px; padding-right: -15px;">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover" id="table-list" style="width: 100%;">
										<thead style="background-color: #B2C4D3">
											<tr>
												<th style="text-align: center; width: 40px;"><?= Yii::t('app', 'No.'); ?></th>
												<th style="text-align: center; width: 100px; line-height: 1;"><?= Yii::t('app', 'TBP'); ?></th>
												<th style="text-align: center; width: 100px; line-height: 1;"><?= Yii::t('app', 'SPO / SPL'); ?></th>
												<th style="text-align: center; width: 100px; line-height: 1;"><?= Yii::t('app', 'SPP'); ?></th>
												<th style="text-align: center; width: 100px; line-height: 1;"><?= Yii::t('app', 'SPB'); ?></th>
												<th style="text-align: center; width: 50px; line-height: 1;"><?= Yii::t('app', 'Approval'); ?></th>
												<th style="text-align: center; width: 100px; line-height: 1;"><?= Yii::t('app', 'Keterangan'); ?></th>
											</tr>
										</thead>
										<tbody>

											<?php /* <tr>
												<td colspan="9" style="text-align: center;"><?= Yii::t('app', 'No Data Available'); ?></td>
											</tr> */?>
											<div id="yyy">
											<?php
											//t_terima_bhp
											$sql_t_terima_bhp = "select tglterima, terima_bhp_id, terimabhp_kode, spo_id, spl_id, terimabhp_keterangan, created_at ". 
																	"	from t_terima_bhp ".
																	"	where status_approval != 'ALLOWED' ".
																	"	and tglterima between '".$tgl_awal."' and '".$tgl_akhir."' ".
																	"	and cancel_transaksi_id is null ".
																	"	order by tglterima desc, terimabhp_kode desc ".
																	" ";
											$query_t_terima_bhp = Yii::$app->db->createCommand($sql_t_terima_bhp)->queryAll();
											$i = 1;

											foreach ($query_t_terima_bhp as $key_t_terima_bhp) {
												$spo_id = $key_t_terima_bhp['spo_id'];
												$spl_id = $key_t_terima_bhp['spl_id'];
												
												$terima_bhp_id = $key_t_terima_bhp['terima_bhp_id'];

												//terima_bhp_detail_id
												$sql_t_terima_bhp_detail = "select * from t_terima_bhp_detail ". 
																			"	where terima_bhp_id = '".$terima_bhp_id."' ".
																			"	";
												$query_t_terima_bhp_detail = Yii::$app->db->createCommand($sql_t_terima_bhp_detail)->queryAll();
												
												foreach ($query_t_terima_bhp_detail as $key_t_terima_bhp_detail) {
													$terima_bhpd_id = $key_t_terima_bhp_detail['terima_bhpd_id'];
													$bhp_id = $key_t_terima_bhp_detail['bhp_id'];
													$terimabhp_kode = $key_t_terima_bhp['terimabhp_kode'];
											
													//spo
													if (isset($spo_id)) {
														$sql_spo = "select spo_kode from t_spo where spo_id = '".$spo_id."' ";
														$spo_kode = Yii::$app->db->createCommand($sql_spo)->queryScalar();

														$sql_spod = "select spod_id from t_spo_detail where spo_id = ".$spo_id." and bhp_id = ".$bhp_id." ";
														$spod_id =  Yii::$app->db->createCommand($sql_spod)->queryScalar();

														$sql_spo_tanggal = "select spo_tanggal from t_spo where spo_id = '".$spo_id."' ";
														$spo_tanggal = Yii::$app->db->createCommand($sql_spo_tanggal)->queryScalar();
														
														$sql_msdr = "select t_spp.spp_kode, t_spp.spp_tanggal, t_spp.spp_id, t_spp_detail.sppd_id ". 
																		"	from map_spp_detail_reff ".
																		"	join t_spp_detail on t_spp_detail.sppd_id = map_spp_detail_reff.sppd_id ".
																		"	join t_spp on t_spp.spp_id = t_spp_detail.spp_id ".
																		"	where reff_no = '".$spo_kode."' ". 
																		"	and t_spp.cancel_transaksi_id is null ".
																		"	and t_spp_detail.bhp_id = ".$bhp_id.
																		"	and map_spp_detail_reff.reff_detail_id = ".$spod_id." ".
																		//"	and terima_bhpd_id = '".$terima_bhpd_id."' ".
																		//"	order by t_spp.spp_tanggal desc, t_spp.spp_kode desc ".
																		"	";
													} else {
														$spo_kode = '';
														$spo_tanggal = '';
													}

													//spl
													if (isset($spl_id)) {
														$sql_spl = "select spl_kode from t_spl where spl_id = '".$spl_id."' ";
														$spl_kode = Yii::$app->db->createCommand($sql_spl)->queryScalar();

														$sql_spld = "select spld_id from t_spl_detail where spl_id = ".$spl_id." and bhp_id = ".$bhp_id." ";
														$spld_id =  Yii::$app->db->createCommand($sql_spld)->queryScalar();

														$sql_spl_tanggal = "select spl_tanggal from t_spl where spl_id = '".$spl_id."' ";
														$spl_tanggal = Yii::$app->db->createCommand($sql_spl_tanggal)->queryScalar();
														
														$sql_msdr = "select t_spp.spp_kode, t_spp.spp_tanggal, t_spp.spp_id, t_spp_detail.sppd_id ". 
																		"	from map_spp_detail_reff ".
																		"	join t_spp_detail on t_spp_detail.sppd_id = map_spp_detail_reff.sppd_id ".
																		"	join t_spp on t_spp.spp_id = t_spp_detail.spp_id ".
																		"	where reff_no = '".$spl_kode."' ". 
																		"	and t_spp.cancel_transaksi_id is null ".
																		"	and t_spp_detail.bhp_id = ".$bhp_id.
																		"	and map_spp_detail_reff.reff_detail_id = ".$spld_id." ".
																		//"	and terima_bhpd_id = '".$terima_bhpd_id."' ".
																		//"	order by t_spp.spp_tanggal desc, t_spp.spp_kode desc ".
																		"	";
													} else {
														$spl_kode = '';
														$spl_tanggal = '';
													}

													//map_spp_detail_reff
													$query_msdr = Yii::$app->db->createCommand($sql_msdr)->queryAll();
													$sppd_id = '';
													$spp_kode = '';
													foreach ($query_msdr as $key_msdr) {
														$sppd_id .= $key_msdr['sppd_id'].",";
														$spp_id = $key_msdr['spp_id'];

														$spp_kode .= "<p style='padding-bottom: -10px; margin-bottom: -5px;'><a onclick='infoSPP(".$spp_id.", ".$spp_id.", ".$spl_id.")'>".$key_msdr['spp_kode']."</a></p><p style='line-height: 1em; margin-top: 0px; margin-bottom: 10px;'><font style='font-size: 10px; color: #999;'>".\app\components\DeltaFormatter::formatDateTimeForUser2($key_msdr['spp_tanggal'])."</font></p>";
													}

													//map_spb_detail_spp_detail 
													$sql_msdsd = "select t_spb_detail.spbd_id, t_spb.spb_id, t_spb.spb_kode, t_spb.spb_tanggal, t_spb_detail.spbd_id  ".
																	"	from map_spb_detail_spp_detail ". 
																	"	join t_spb_detail on t_spb_detail.spbd_id = map_spb_detail_spp_detail.spbd_id".
																	"	join t_spb on t_spb.spb_id = t_spb_detail.spb_id ".
																	"	where map_spb_detail_spp_detail.sppd_id in (".$sppd_id."0 ) ". 
																	//"	and t_spb_detail.bhp_id = ".$bhp_id.
																	//"	order by t_spb.spb_tanggal desc, t_spb.spb_kode desc ".
																	"	";
													$msdsd = Yii::$app->db->createCommand($sql_msdsd)->queryAll();
													$sql_spb_kode = '';
													$spb_kode = '';
													foreach ($msdsd as $key_msdsd) {
														$sql_spb_kode .= "select t_spb.spb_kode ".
																		"	from t_spb ".
																		"	join t_spb_detail on t_spb_detail.spb_id = t_spb.spb_id ".
																		"	join map_spb_detail_spp_detail on map_spb_detail_spp_detail.spbd_id = t_spb_detail.spbd_id ".
																		"	where map_spb_detail_spp_detail.sppd_id in (".$sppd_id."0 )".
																		"	";
														$spb_id = $key_msdsd['spb_id'];
														$spb_tanggal = $key_msdsd['spb_tanggal'];
														$spbd_id = $key_msdsd['spbd_id'];
														$spb_kode = Yii::$app->db->createCommand($sql_spb_kode)->queryScalar();
														isset($spo_id) ? $spo_id = $spo_id : $spo_id = 0;
														isset($spl_id) ? $spl_id = $spl_id : $spl_id = 0;
														//$spb_kodex = "<p style='padding-bottom: -10px; margin-bottom: -5px;'><a onclick='infoSPB(".$spb_id.", ".$spo_id.", ".$spl_id.", ".$bhp_id.")'>".$spb_kode."</a></p><p style='line-height:1em; margin-top: 0px; margin-bottom: 10px;'><font style='font-size: 10px; color: #999;'>".\app\components\DeltaFormatter::formatDateTimeForUser2($spb_tanggal)."</font></p>";
														$spb_kodex = "<p style='padding-bottom: -10px; margin-bottom: -5px;'><a onclick='infoSPB(".$spb_id.", ".$spo_id.", ".$spl_id.", ".$bhp_id.")'>".$spb_kode."</a></p>
																		<p style='line-height:1em; margin-top: 0px; margin-bottom: 10px;'>
																			<font style='font-size: 10px; color: #999;'>".\app\components\DeltaFormatter::formatDateTimeForUser2($spb_tanggal)."</font>
																		</p>";
																		//<p style='line-height:1em; margin-top: -15px;'>
																		//	<font style='font-size: 8px; color: #ccc;'>".$spb_id.", ".$spo_id.", ".$spl_id.", ".$bhp_id."</font>
																		//</p>
																		//";
													}

												}

											?>
											<tr>
												<td class="text-center"><?php echo $i;?></td>
												<td>
													<p style='padding-bottom: -10px; margin-bottom: -5px;'><a onclick="infoTBP(<?php echo $key_t_terima_bhp['terima_bhp_id'];?>, <?php echo $bhp_id;?>)"><?php echo $key_t_terima_bhp['terimabhp_kode'];?></a></p>
													<p style='line-height:1em; margin-top: 0px; margin-bottom: 10px;'><font style="font-size: 10px; color: #999;"><?php echo \app\components\DeltaFormatter::formatDateTimeId($key_t_terima_bhp['created_at']);?></font></p>
												</td>
												<td>
													<?php
													if (($spo_kode != '' && $spl_kode == '' ) || ($spl_kode != '' && $spo_kode == '') ) {
														if ($spo_kode != '') {
														?>
														<p style='padding-bottom: -10px; margin-bottom: -5px;'><a onclick="infoSPO(<?php echo $spo_id;?>, <?php echo $bhp_id;?>)"><?php echo $spo_kode."</a></p><p style='line-height:1em; margin-top: 0px; margin-bottom: 10px;'><font style='font-size: 10px; color: #999;'>".\app\components\DeltaFormatter::formatDateTimeForUser2($spo_tanggal);?></font></p>
														<?php
														}
														?>

														<?php
														if ($spl_kode != '') {
														?>
														<p style='padding-bottom: -10px; margin-bottom: -5px;'><a onclick="infoSPL(<?php echo $spl_id;?>, <?php echo $bhp_id;?>)"><?php echo $spl_kode."</a></p><p style='line-height:1em; margin-top: 0px; margin-bottom: 10px;'><font style='font-size: 10px; color: #999;'>".\app\components\DeltaFormatter::formatDateTimeForUser2($spl_tanggal);?></font></p>
														<?php
														}
													} else {
														echo "--;";
													}
													?>
												</td>
												<?php // infoSPP(spp_id,spo_id,spl_id) ;?>
												<td><?php echo $spp_kode;?></td>
												<td><?php echo $spb_kodex;?></td>
												<td class="text-center">
													<?php
													$approval_1 = \app\models\TApproval::find()->where(['reff_no'=>$terimabhp_kode, 'assigned_to'=>58])->one();
													$pegawai_nama = \app\models\MPegawai::findOne(58)->pegawai_nama;
													if ($approval_1->status == "APPROVED") {
														echo "<span style='width: 150px; font-size: 8px;' class='text-success'>".$pegawai_nama."</span>";
													} else if ($approval_1->status == "REJECTED") {
														echo "<span style='width: 150px; font-size: 8px;' class='text-danger'>".$pegawai_nama."</span>";
													} else {
														echo "<span style='width: 150px; font-size: 8px;' class='text-default'>".$pegawai_nama."</span>";
													}

													$approval_2 = \app\models\TApproval::find()->where(['reff_no'=>$terimabhp_kode, 'assigned_to'=>124])->one();
													$pegawai_nama = \app\models\MPegawai::findOne(124)->pegawai_nama;
													if ($approval_2->status == "APPROVED") {
														echo "<br><span style='width: 150px; font-size: 8px;' class='text-success'>".$pegawai_nama."</span>";
													} else if ($approval_2->status == "REJECTED") {
														echo "<br><span style='width: 150px; font-size: 8px;' class='text-danger'>".$pegawai_nama."</span>";
													} else {
														echo "<br><span style='width: 150px; font-size: 8px;' class='text-default'>".$pegawai_nama."</span>";
													}												
													?>
												</td>
												<td style="font-size: 12px;"><?php echo $key_t_terima_bhp['terimabhp_keterangan'];?></td>
											</tr>
											<?php
												$i++;
											}
											?>
											</div>
										</tbody>
									</table>
								</div>
								<div id="beres"></div>
							</div>

							