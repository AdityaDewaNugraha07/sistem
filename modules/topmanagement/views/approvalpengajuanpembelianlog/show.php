<?php
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TPengajuanPembelianlog::findOne(['kode'=>$model->reff_no]);
$modDetailIndustri = \app\models\TPengajuanPembelianlogDetail::find()
                                ->select("pengajuan_pembelianlog_id, kayu_id, tipe")
                                ->groupBy("pengajuan_pembelianlog_id, kayu_id, tipe")
                                ->where(['pengajuan_pembelianlog_id'=>$modReff->pengajuan_pembelianlog_id,"tipe"=>"INDUSTRI"])->all();
$modDetailTrading = \app\models\TPengajuanPembelianlogDetail::find()
                                ->select("pengajuan_pembelianlog_id, kayu_id, tipe")
                                ->groupBy("pengajuan_pembelianlog_id, kayu_id, tipe")
                                ->where(['pengajuan_pembelianlog_id'=>$modReff->pengajuan_pembelianlog_id,"tipe"=>"TRADING"])->all();
//$modPmr = \app\models\TPmr::find()->where(['pengajuan_pembelianlog_id'=>$modReff->pengajuan_pembelianlog_id])->all();
$modMap = \app\models\MapPermintaanKeputusanLogalam::find()->where(['pengajuan_pembelianlog_id'=>$modReff->pengajuan_pembelianlog_id])->all();

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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Keputusan'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kode."-".$modReff->revisi; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Berkas'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Nomor Kontrak'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->nomor_kontrak ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Volume Kontrak'); ?></label>
				<div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->volume_kontrak)." m<sup>3</sup>" ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Supplier'); ?></label>
				<div class="col-md-7"><strong>
					<?php
						$modSupplier = \app\models\MSuplier::findOne($modReff->suplier_id);
						echo $modSupplier->suplier_nm."<br>".(!empty($modSupplier->suplier_nm_company)?$modSupplier->suplier_nm_company:$modSupplier->suplier_almt);
					?>
				</strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Asal Kayu'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->asal_kayu ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Volume Pembelian'); ?></label>
				<div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_volume)." m<sup>3</sup>"; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Term of Price'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->term_of_price ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Waktu Penyerahan'); ?></label>
				<div class="col-md-7"><strong>
				<?php 
					echo \app\components\DeltaFormatter::formatDateTimeForUser2($modReff->waktu_penyerahan_awal)." sd ";
					echo \app\components\DeltaFormatter::formatDateTimeForUser2($modReff->waktu_penyerahan_akhir);
				?>
				</strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Lokasi Muat'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->lokasi_muat ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Asuransi'); ?></label>
				<div class="col-md-7"><strong><?= ($modReff->asuransi==true)?"Ya":"Tidak" ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Nominal DP'); ?></label>
				<div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->nominal_dp) ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Pembayaran'); ?></label>
				<div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal_bayar_dp) ?></strong></div>
			</div>
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
            <?php if(!empty($modReff->keterangan)){ ?>
            <div class="form-group col-md-12">
                <h4 class="font-red-flamingo"><?= $modReff->keterangan ?></h4>
			</div>
            <?php } ?>
		</div>
	</div>
    <div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-hoki bordered" style="background-color:#a6c054">
				<div class="portlet-title" style="background-color:#a6c054">
                    <div class="tools" style="float: left;">
						<a href="javascript:;" class="collapse" data-original-title="" title=""> </a> &nbsp; 
					</div>
					<div class="caption"> <?= Yii::t('app', 'Keterangan Pembelian'); ?> </div>
				</div>
                <div class="portlet-body" style="background-color: #f5fcc9" >
                    <div class="row"  style="margin-top: -15px; padding: 10px;">
                        <?php
                        $keterangan_pembelian = str_replace( "\n", "<br>", $modReff->keterangan_pembelian ); 
                        $keterangan_pembelian = str_replace( " ", "&nbsp;", $keterangan_pembelian ); 
                        echo $keterangan_pembelian;
                        ?>
                    </div>
                </div>
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
						$by_kanit = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->by_kanit)->queryOne();
						$by_gmpurch = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->by_gmpurch)->queryOne();
						$by_kadiv = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->by_kadiv)->queryOne();
						$by_gmopr = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->by_gmopr)->queryOne();
						$by_dirut = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->by_dirut)->queryOne();
                        $by_owner = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->by_owner)->queryOne();
						$reasons = \yii\helpers\Json::decode($modReff->approve_reason);
						?>
						<br>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Kadiv Purchasing Log'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->by_gmpurch)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php
											if($by_gmpurch['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_gmpurch['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$by_gmpurch['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($by_gmpurch['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_gmpurch['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$by_gmpurch['assigned_to']){
															echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											}
											?>
										</span>
									</td>
								</tr>
							</table>
						</div>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<?php if(count($modDetailTrading)>0){ ?>
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Kadiv Marketing'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->by_kadiv)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($by_kadiv['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_kadiv['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$by_kadiv['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($by_kadiv['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_kadiv['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$by_kadiv['assigned_to']){
															echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											}?>
										</span>
									</td>
								</tr>
								<?php } ?>
								<?php if(count($modDetailIndustri)>0){ ?>
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'GM Operasional'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->by_gmopr)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($by_gmopr['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_gmopr['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$by_gmopr['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($by_gmopr['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_gmopr['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$by_gmopr['assigned_to']){
															echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											}?>
										</span>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Direktur Utama'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->by_dirut)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($by_dirut['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_dirut['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$by_dirut['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($by_dirut['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_dirut['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$by_dirut['assigned_to']){
															echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											} ?>
										</span>
									</td>
								</tr>
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Owner'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->by_owner)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($by_owner['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_owner['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$by_owner['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($by_owner['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_owner['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$by_owner['assigned_to']){
															echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											} ?>
										</span>
									</td>
								</tr>
							</table>
						</div>
						<?php $ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log'); ?>
						<?php if(count($modDetailIndustri)>0){ ?>
						<div class="col-md-12">
							<div class="table-scrollable">
								<h4>Log Industri</h4>
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-industri">
									<thead>
										<tr>
											<th style="width: 30px;" rowspan="3" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
											<th style="" rowspan="3"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
											<th colspan="<?= (count($ukuranganrange)*2+2) ?>"><?= Yii::t('app', 'Diameter'); ?></th>
										</tr>
										<tr>
											<?php foreach($ukuranganrange as $i => $range){ ?>
											<th colspan="2"><?= $range ?></th>
											<?php } ?>
											<th colspan="2">Total</th>
										</tr>
										<tr>
											<?php foreach($ukuranganrange as $i => $range){ ?>
											<!--<th style="width: 40px;">Btg</th>-->
											<th style="width: 65px;">M<sup>3</sup></th>
											<th style="width: 75px;">Harga</th>
											<?php } ?>
											<!--<th style="width: 45px;">Btg</th>-->
											<th style="width: 70px;">M<sup>3</sup></th>
											<th style="width: 80px;">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$total_btg = 0; $total_m3 = 0; $total_harga=0;
										foreach($ukuranganrange as $i => $range){
											$total_ver_btg[$range] = 0; $total_ver_m3[$range] = 0;
										}
										foreach($modDetailIndustri as $i => $industri){
											echo "<tr>";
											echo	"<td>".($i+1)."</td>";
											echo	"<td>".$industri->kayu->kayu_nama."</td>";
												$subtotal_btg = 0; $subtotal_m3 = 0; $subtotal_harga=0;
												foreach($ukuranganrange as $i => $range){
													$sql = "SELECT SUM(qty_batang) AS qty_btg, SUM(qty_m3) AS qty_m3, SUM(harga) AS harga FROM t_pengajuan_pembelianlog_detail 
															WHERE pengajuan_pembelianlog_id = {$modReff->pengajuan_pembelianlog_id} AND kayu_id = {$industri->kayu_id} AND diameter_cm = '{$range}' AND tipe='".$industri->tipe."'";
													$modQty = Yii::$app->db->createCommand($sql)->queryOne();
//													echo "<td class='text-align-center'>".$modQty['qty_btg']."</td>";
													echo "<td class='text-align-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($modQty['qty_m3'],2)."</td>";
													echo "<td class='text-align-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($modQty['harga'])."</td>";
													$subtotal_btg += $modQty['qty_btg'];
													$subtotal_m3 += $modQty['qty_m3'];
													$subtotal_harga += $modQty['harga']*$modQty['qty_m3'];
													$total_ver_btg[$range] += $modQty['qty_btg'];
													$total_ver_m3[$range] += $modQty['qty_m3'];
													
												}
												$total_btg += $subtotal_btg;
												$total_m3 += $subtotal_m3;
												$total_harga += $subtotal_harga;
//												echo "<td class='text-align-center'>".$subtotal_btg."</td>";
												echo "<td class='text-align-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($subtotal_m3,2)."</td>";
												echo "<td class='text-align-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($subtotal_harga)."</td>";
											echo "</tr>";
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2" style="text-align: right;">Jumlah &nbsp; </td>
											<?php foreach($ukuranganrange as $i => $range){ ?>
											<!--<td  class="text-align-center"><?php // echo $total_ver_btg[$range]; ?></td>-->
											<td  class="text-align-right">
												<?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_ver_m3[$range],2); ?>
											</td>
											<td  class="text-align-right">
												
											</td>
											<?php } ?>
											<!--<td class="text-align-center"> <?php // echo $total_btg ?> </td>-->
											<td class="text-align-right"> <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_m3,2) ?> </td>
											<td class="text-align-right"> <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_harga) ?> </td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<?php } ?>
						<?php if(count($modDetailTrading)>0){ ?>
						<div class="col-md-12">
							<div class="table-scrollable">
								<h4>Log Trading</h4>
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-trading">
									<thead>
										<tr>
											<th style="width: 30px;" rowspan="3" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
											<th style="" rowspan="3"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
											<th colspan="<?= (count($ukuranganrange)*2+2) ?>"><?= Yii::t('app', 'Diameter'); ?></th>
										</tr>
										<tr>
											<?php foreach($ukuranganrange as $i => $range){ ?>
											<th colspan="2"><?= $range ?></th>
											<?php } ?>
											<th colspan="2">Total</th>
										</tr>
										<tr>
											<?php foreach($ukuranganrange as $i => $range){ ?>
											
											<th style="width: 65px;">M<sup>3</sup></th>
											<th style="width: 75px;">Harga</th>
											<?php } ?>
											
											<th style="width: 70px;">M<sup>3</sup></th>
											<th style="width: 80px;">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$total_btg = 0; $total_m3 = 0; $total_harga=0;
										foreach($ukuranganrange as $i => $range){
											$total_ver_btg[$range] = 0; $total_ver_m3[$range] = 0; $total_ver_harga[$range] = 0;
										}
										foreach($modDetailTrading as $i => $trading){
											echo "<tr>";
											echo	"<td>".($i+1)."</td>";
											echo	"<td>".$trading->kayu->kayu_nama."</td>";
												$subtotal_btg = 0; $subtotal_m3 = 0; $subtotal_harga=0;
												foreach($ukuranganrange as $i => $range){
													$sql = "SELECT SUM(qty_batang) AS qty_btg, SUM(qty_m3) AS qty_m3, SUM(harga) AS harga FROM t_pengajuan_pembelianlog_detail 
															WHERE pengajuan_pembelianlog_id = {$modReff->pengajuan_pembelianlog_id} AND kayu_id = {$trading->kayu_id} AND diameter_cm = '{$range}' AND tipe='".$trading->tipe."'";
													$modQty = Yii::$app->db->createCommand($sql)->queryOne();
//													echo "<td class='text-align-center'>".$modQty['qty_btg']."</td>";
													echo "<td class='text-align-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($modQty['qty_m3'],2)."</td>";
													echo "<td class='text-align-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($modQty['harga'])."</td>";
													$subtotal_btg += $modQty['qty_btg'];
													$subtotal_m3 += $modQty['qty_m3'];
													$subtotal_harga += $modQty['harga']*$modQty['qty_m3'];
													$total_ver_btg[$range] += $modQty['qty_btg'];
													$total_ver_m3[$range] += $modQty['qty_m3'];
													$total_ver_harga[$range] += $modQty['harga'];
												}
												$total_btg += $subtotal_btg;
												$total_m3 += $subtotal_m3;
												$total_harga += $subtotal_harga;
//												echo "<td class='text-align-center'>".$subtotal_btg."</td>";
												echo "<td class='text-align-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($subtotal_m3,2)."</td>";
												echo "<td class='text-align-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($subtotal_harga)."</td>";
											echo "</tr>";
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2" style="text-align: right;">Jumlah &nbsp; </td>
											<?php foreach($ukuranganrange as $i => $range){ ?>
											<!--<td  class="text-align-center"><?php // echo $total_ver_btg[$range]; ?></td>-->
											<td  class="text-align-right">
												<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_ver_m3[$range],2); ?>
											</td>
											<td  class="text-align-right">
												<?php // echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_ver_harga[$range]); ?>
											</td>
											<?php } ?>
											<!--<td class="text-align-center"> <?php // echo $total_btg ?> </td>-->
											<td class="text-align-right"> <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_m3,2) ?> </td>
											<td class="text-align-right"> <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_harga) ?> </td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<?php } ?>
                        <?php if(count($modMap)>0){ ?>
						<div class="col-md-12">
							<div class="table-scrollable">
								<h4>Permintaan Pembelian Log Alam</h4>
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-permintaan">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;"></th>
                                            <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Kode<br>Permintaan'); ?></th>
                                            <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Permintaan'); ?></th>
                                            <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Dibutuhkan<br>Untuk'); ?></th>
                                            <th style="width: 175px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Dibutuhkan'); ?></th>
                                            <th><?= Yii::t('app', 'Diminta Oleh'); ?></th>
                                            <th><?= Yii::t('app', 'Decision Maker'); ?></th>
                                            <th style="width: 80px; line-height: 1"><?= Yii::t('app', 'Qty<br>Permintaan'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $totalpermintaan = 0;
                                        foreach ($modMap as $x => $map) {
                                        	$pmr_id = $map->pmr_id;
                                        	$modPmr = \app\models\TPmr::find()->where(['pmr_id'=>$pmr_id])->all();
	                                        foreach($modPmr as $i => $pmr){
	                                            $approver3 = app\models\TApproval::findOne(['reff_no'=>$pmr->kode,'assigned_to'=>$pmr->approver_3]); 
	                                            $total_m3 = \app\models\TPmrDetail::find()->select("SUM(qty_m3) AS total_m3")->where("pmr_id = ".$pmr->pmr_id)->one();
	                                            $pmr->total_m3 = $total_m3->total_m3;
	                                            $totalpermintaan += $pmr->total_m3;
	                                            ?>
	                                            <tr style="">
	                                                <td class="" style="vertical-align: middle; text-align: center;"> <?= ($i+1) ?> </td>
	                                                <td class="" style="vertical-align: middle; text-align: center;"> <?= $pmr->kode ?> </td>
	                                                <td class="" style="vertical-align: middle; text-align: center;">
	                                                    <?= \app\components\DeltaFormatter::formatDateTimeForUser2($pmr->tanggal) ?>
	                                                </td>
	                                                <td class="" style="vertical-align: middle; text-align: center;">
	                                                    <?= $pmr->tujuan ?>
	                                                </td>
	                                                <td class="" style="vertical-align: middle; text-align: center;">
	                                                    <?= \app\components\DeltaFormatter::formatDateTimeForUser($pmr->tanggal_dibutuhkan_awal)." - ".\app\components\DeltaFormatter::formatDateTimeForUser($pmr->tanggal_dibutuhkan_akhir); ?>
	                                                </td>
	                                                <td class="td-kecil2" style="vertical-align: middle; text-align: center;">
	                                                    <?= "<b>".$pmr->dibuatOleh->pegawai_nama."</b><br>".$pmr->dibuatOleh->departement->departement_nama; ?>
	                                                </td>
	                                                <td class="td-kecil2" style="vertical-align: middle; text-align: center;">
	                                                    <?= "<b>".$approver3->approvedBy->pegawai_nama."</b><br>".$approver3->status." at ".\app\components\DeltaFormatter::formatDateTimeForUser2($approver3->updated_at); ?>
	                                                </td>
	                                                <td class="" style="vertical-align: middle; text-align: right;">
	                                                    <?= number_format($pmr->total_m3)." M<sup>3</sup>"; ?>
	                                                </td>
	                                            </tr>
	                                        <?php
	                                    	}
	                                    }
	                                    ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7" style="text-align: right;">Total Permintaan &nbsp; </td>
                                            <td style="text-align: right; font-weight: 600;"><span id="place-total-permintaan"><?= number_format($totalpermintaan) ?></span> M<sup>3</sup></td>
                                        </tr>
                                    </tfoot>
                                </table>
							</div>
						</div>
						<?php } ?>
						<?php if($modReff->revisi > 0){ ?>
						<div class="form-group col-md-12">
							<div class="col-md-12">
								<span class="font-red-flamingo"><i><br>AWD (Accepted With Deviation)</i></span>
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
