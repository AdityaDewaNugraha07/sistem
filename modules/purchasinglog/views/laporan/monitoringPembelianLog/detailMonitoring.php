<?php app\assets\DatatableAsset::register($this); ?>
<?php 
$by_kanit = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->by_kanit)->queryOne();
$by_gmpurch = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->by_gmpurch)->queryOne();
$by_kadiv = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->by_kadiv)->queryOne();
$by_gmopr = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->by_gmopr)->queryOne();
$by_dirut = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->by_dirut)->queryOne();
$by_owner = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->by_owner)->queryOne();
$reasons = \yii\helpers\Json::decode($model->approve_reason);
?>
<div class="modal fade" id="modal-detail" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Monitoring'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Kode Keputusan</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= $model->kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Tanggal</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Kode PO</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= $model->logKontrak->kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Nomor Kontrak</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= $model->nomor_kontrak ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Tanggal Kontrak</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->logKontrak->tanggal) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Volume Kontrak</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= $model->volume_kontrak ?> m<sup>3</sup></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Supplier</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= $model->suplier->suplier_nm.", ".$model->suplier->suplier_nm_company ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Asal Kayu</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= $model->asal_kayu ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Term of price</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= $model->term_of_price ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Waktu Penyerahan</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->waktu_penyerahan_awal)." - ".app\components\DeltaFormatter::formatDateTimeForUser2($model->waktu_penyerahan_akhir) ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Lokasi Muat</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= $model->lokasi_muat ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Asuransi</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= ($model->asuransi==true)?"Ya":"Tidak" ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Nominal Terbayar</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong>Rp. <?= app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal_dp) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Tanggal Bayar</label>
                            <div class="col-md-7"><span> &nbsp; </span><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_bayar_dp) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Total Pembelian m<sup>3</sup></label>
                            <div class="col-md-7"><span> &nbsp; </span><strong></strong></div>
                        </div>
						<?php $approval = \app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->by_gmpurch]);
						if(!empty($approval)){ ?>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Reviewed By Kadiv Purchasing Log</label>
                            <div class="col-md-7" style="line-height: 0.9"><span> &nbsp; </span><strong>
								<?php echo \app\models\MPegawai::findOne($model->by_gmpurch)->pegawai_nama; ?></strong><br>
								<?php
									if($approval->status== \app\models\TApproval::STATUS_APPROVED){
										echo "<span> &nbsp; </span><span style='font-size:1rem;' class='font-green-seagreen'>".$approval->status;
										echo " at ".app\components\DeltaFormatter::formatDateTimeForUser2($approval->updated_at)."</span>";
										if(count($reasons)>0){
											foreach($reasons as $i => $reason){
												if($reason['by']==$by_gmpurch['assigned_to']){
													echo " <br>&nbsp; <span class='font-green-seagreen' style='font-size:1rem;'> Reason : <i>".$reason['reason']."</i></span>";
												}
											}
										}
									}else if($approval->status== \app\models\TApproval::STATUS_REJECTED){
										echo "<span> &nbsp; </span><span style='font-size:1rem;' class='font-red-flamingo'>".$approval->status;
										echo " at ".app\components\DeltaFormatter::formatDateTimeForUser2($approval->updated_at)."</span>";
										if(count($reasons)>0){
											foreach($reasons as $i => $reason){
												if($reason['by']==$by_gmpurch['assigned_to']){
													echo " <br>&nbsp; <span class='font-red-flamingo' style='font-size:1rem;'> Reason : <i>".$reason['reason']."</i></span>";
												}
											}
										}
									}else{
										echo "<span> &nbsp; </span><span style='font-size:1rem;'>(".$approval->status.")</span>";
									}
								?>
							</div>
                        </div>
						<?php } ?>
						<?php $approval = \app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->by_kadiv]);
						if(!empty($approval)){ ?>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Reviewed By Kadiv Mkt</label>
							<div class="col-md-7" style="line-height: 0.9"><span> &nbsp; </span><strong>
								<?php echo \app\models\MPegawai::findOne($model->by_kadiv)->pegawai_nama; ?></strong><br>
								<?php
									if($approval->status== \app\models\TApproval::STATUS_APPROVED){
										echo "<span> &nbsp; </span><span style='font-size:1rem;' class='font-green-seagreen'>".$approval->status;
										echo " at ".app\components\DeltaFormatter::formatDateTimeForUser2($approval->updated_at)."</span>";
										if(count($reasons)>0){
											foreach($reasons as $i => $reason){
												if($reason['by']==$by_kadiv['assigned_to']){
													echo " <br>&nbsp; <span class='font-green-seagreen' style='font-size:1rem;'> Reason : <i>".$reason['reason']."</i></span>";
												}
											}
										}
									}else if($approval->status== \app\models\TApproval::STATUS_REJECTED){
										echo "<span> &nbsp; </span><span style='font-size:1rem;' class='font-red-flamingo'>".$approval->status;
										echo " at ".app\components\DeltaFormatter::formatDateTimeForUser2($approval->updated_at)."</span>";
										if(count($reasons)>0){
											foreach($reasons as $i => $reason){
												if($reason['by']==$by_kadiv['assigned_to']){
													echo " <br>&nbsp; <span class='font-red-flamingo' style='font-size:1rem;'> Reason : <i>".$reason['reason']."</i></span>";
												}
											}
										}
									}else{
										echo "<span> &nbsp; </span><span style='font-size:1rem;'>(".$approval->status.")</span>";
									}
								?>
							</div>
                        </div>
						<?php } ?>
						<?php $approval = \app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->by_gmopr]);
						if(!empty($approval)){ ?>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Reviewed By GM Opr</label>
                            <div class="col-md-7" style="line-height: 0.9"><span> &nbsp; </span><strong>
								<?php echo \app\models\MPegawai::findOne($model->by_gmopr)->pegawai_nama; ?></strong><br>
								<?php
									if($approval->status== \app\models\TApproval::STATUS_APPROVED){
										echo "<span> &nbsp; </span><span style='font-size:1rem;' class='font-green-seagreen'>".$approval->status;
										echo " at ".app\components\DeltaFormatter::formatDateTimeForUser2($approval->updated_at)."</span>";
										if(count($reasons)>0){
											foreach($reasons as $i => $reason){
												if($reason['by']==$by_gmopr['assigned_to']){
													echo " <br>&nbsp; <span class='font-green-seagreen' style='font-size:1rem;'> Reason : <i>".$reason['reason']."</i></span>";
												}
											}
										}
									}else if($approval->status== \app\models\TApproval::STATUS_REJECTED){
										echo "<span> &nbsp; </span><span style='font-size:1rem;' class='font-red-flamingo'>".$approval->status;
										echo " at ".app\components\DeltaFormatter::formatDateTimeForUser2($approval->updated_at)."</span>";
										if(count($reasons)>0){
											foreach($reasons as $i => $reason){
												if($reason['by']==$by_gmopr['assigned_to']){
													echo " <br>&nbsp; <span class='font-red-flamingo' style='font-size:1rem;'> Reason : <i>".$reason['reason']."</i></span>";
												}
											}
										}
									}else{
										echo "<span> &nbsp; </span><span style='font-size:1rem;'>(".$approval->status.")</span>";
									}
								?>
							</div>
                        </div>
						<?php } ?>
						<?php $approval = \app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->by_dirut]);
						if(!empty($approval)){ ?>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Approved By Direktur Utama</label>
                            <div class="col-md-7" style="line-height: 0.9"><span> &nbsp; </span><strong>
								<?php echo \app\models\MPegawai::findOne($model->by_dirut)->pegawai_nama; ?></strong><br>
								<?php
									if($approval->status== \app\models\TApproval::STATUS_APPROVED){
										echo "<span> &nbsp; </span><span style='font-size:1rem;' class='font-green-seagreen'>".$approval->status;
										echo " at ".app\components\DeltaFormatter::formatDateTimeForUser2($approval->updated_at)."</span>";
										if(count($reasons)>0){
											foreach($reasons as $i => $reason){
												if($reason['by']==$by_dirut['assigned_to']){
													echo " <br>&nbsp; <span class='font-green-seagreen' style='font-size:1rem;'> Reason : <i>".$reason['reason']."</i></span>";
												}
											}
										}
									}else if($approval->status== \app\models\TApproval::STATUS_REJECTED){
										echo "<span> &nbsp; </span><span style='font-size:1rem;' class='font-red-flamingo'>".$approval->status;
										echo " at ".app\components\DeltaFormatter::formatDateTimeForUser2($approval->updated_at)."</span>";
										if(count($reasons)>0){
											foreach($reasons as $i => $reason){
												if($reason['by']==$by_dirut['assigned_to']){
													echo " <br>&nbsp; <span class='font-red-flamingo' style='font-size:1rem;'> Reason : <i>".$reason['reason']."</i></span>";
												}
											}
										}
									}else{
										echo "<span> &nbsp; </span><span style='font-size:1rem;'>(".$approval->status.")</span>";
									}
								?>
							</div>
                        </div>
						<?php } ?>
                        <?php $approval = \app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->by_owner]);
						if(!empty($approval)){ ?>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label" style="line-height: 1">Approved By Owner</label>
                            <div class="col-md-7" style="line-height: 0.9"><span> &nbsp; </span><strong>
								<?php echo \app\models\MPegawai::findOne($model->by_owner)->pegawai_nama; ?></strong><br>
								<?php
									if($approval->status== \app\models\TApproval::STATUS_APPROVED){
										echo "<span> &nbsp; </span><span style='font-size:1rem;' class='font-green-seagreen'>".$approval->status;
										echo " at ".app\components\DeltaFormatter::formatDateTimeForUser2($approval->updated_at)."</span>";
										if(count($reasons)>0){
											foreach($reasons as $i => $reason){
												if($reason['by']==$by_owner['assigned_to']){
													echo " <br>&nbsp; <span class='font-green-seagreen' style='font-size:1rem;'> Reason : <i>".$reason['reason']."</i></span>";
												}
											}
										}
									}else if($approval->status== \app\models\TApproval::STATUS_REJECTED){
										echo "<span> &nbsp; </span><span style='font-size:1rem;' class='font-red-flamingo'>".$approval->status;
										echo " at ".app\components\DeltaFormatter::formatDateTimeForUser2($approval->updated_at)."</span>";
										if(count($reasons)>0){
											foreach($reasons as $i => $reason){
												if($reason['by']==$by_owner['assigned_to']){
													echo " <br>&nbsp; <span class='font-red-flamingo' style='font-size:1rem;'> Reason : <i>".$reason['reason']."</i></span>";
												}
											}
										}
									}else{
										echo "<span> &nbsp; </span><span style='font-size:1rem;'>(".$approval->status.")</span>";
									}
								?>
							</div>
                        </div>
						<?php } ?>
                    </div>
                </div>
				<br>
				<?php $ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log'); ?>
				<?php
				$modDetailIndustri = \app\models\TPengajuanPembelianlogDetail::find()
									->select("pengajuan_pembelianlog_id, kayu_id, tipe")
									->groupBy("pengajuan_pembelianlog_id, kayu_id, tipe")
									->where(['pengajuan_pembelianlog_id'=>$model->pengajuan_pembelianlog_id,"tipe"=>"INDUSTRI"])->all();
				if(count($modDetailIndustri)>0){ ?>
				<span style="font-size: 1.5rem;"><b>Log Industri</b></span>
				<div class="row">
					<div class="col-md-12">
						<div class="table-scrollable">
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
										<th style="width: 75px;">Harga<sup>Rp</sup></th>
										<?php } ?>
										<th style="width: 70px;">M<sup>3</sup></th>
										<th style="width: 80px;">Harga<sup>Rp</sup></th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach($ukuranganrange as $i => $range){
										$total_ver_m3[$range] = 0;
									}
									$total_m3 = 0; $total_harga=0;
									if(count($modDetailIndustri)>0){
										foreach($modDetailIndustri as $i => $industri){
											echo "<tr>";
											echo	"<td style='text-align:center'>".($i+1)."</td>";
											echo	"<td>".$industri->kayu->kayu_nama."</td>";
											$tot_m3 = 0; $tot_harga=0;
											foreach($ukuranganrange as $i => $range){
												$mod = Yii::$app->db->createCommand("SELECT * FROM t_pengajuan_pembelianlog_detail WHERE tipe='INDUSTRI' AND pengajuan_pembelianlog_id = {$model->pengajuan_pembelianlog_id} AND diameter_cm='{$range}' ")->queryOne();
												echo"<td style='text-align:right'>".app\components\DeltaFormatter::formatNumberForUserFloat($mod['qty_m3'])."</td>";
												echo"<td style='text-align:right'>".app\components\DeltaFormatter::formatNumberForUserFloat($mod['harga'])."</td>";
												$tot_m3 += $mod['qty_m3'];
												$tot_harga += $mod['harga']*$mod['qty_m3'];
												$total_ver_m3[$range] += $mod['qty_m3'];
											}
											echo	"<td style='text-align:right'>".app\components\DeltaFormatter::formatNumberForUserFloat($tot_m3)."</td>";
											echo	"<td style='text-align:right'>".app\components\DeltaFormatter::formatNumberForUserFloat($tot_harga)."</td>";
											echo "</tr>";
											$total_m3 += $tot_m3;
											$total_harga += $tot_harga;
										}
									}
									?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2" style="text-align: right;">Jumlah &nbsp; </td>
										<?php foreach($ukuranganrange as $i => $range){ ?>
										<td style='text-align:right'><?= $total_ver_m3[$range] ?></td>
										<td style='text-align:right'></td>
										<?php } ?>
										<td style='text-align:right'><?= app\components\DeltaFormatter::formatNumberForUserFloat($total_m3); ?></td>
										<td style='text-align:right'><?= app\components\DeltaFormatter::formatNumberForUserFloat($total_harga) ?></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<?php } ?>
				<br>
				<?php
				$modDetailTrading = \app\models\TPengajuanPembelianlogDetail::find()
									->select("pengajuan_pembelianlog_id, kayu_id, tipe")
									->groupBy("pengajuan_pembelianlog_id, kayu_id, tipe")
									->where(['pengajuan_pembelianlog_id'=>$model->pengajuan_pembelianlog_id,"tipe"=>"TRADING"])->all();
				if(count($modDetailTrading)>0){ ?>
				<span style="font-size: 1.5rem;"><b>Log Trading</b></span>
				<div class="row">
					<div class="col-md-12">
						<div class="table-scrollable">
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
										<th style="width: 75px;">Harga<sup>Rp</sup></th>
										<?php } ?>
										<th style="width: 70px;">M<sup>3</sup></th>
										<th style="width: 80px;">Harga<sup>Rp</sup></th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach($ukuranganrange as $i => $range){
										$total_ver_m3[$range] = 0;
									}
									$total_m3 = 0; $total_harga=0;
									if(count($modDetailTrading)>0){
										foreach($modDetailTrading as $i => $trading){
											echo "<tr>";
											echo	"<td style='text-align:center'>".($i+1)."</td>";
											echo	"<td>".$trading->kayu->kayu_nama."</td>";
											$tot_m3 = 0; $tot_harga=0;
											foreach($ukuranganrange as $i => $range){
												$mod = Yii::$app->db->createCommand("SELECT * FROM t_pengajuan_pembelianlog_detail WHERE tipe='TRADING' AND pengajuan_pembelianlog_id = {$model->pengajuan_pembelianlog_id} AND diameter_cm='{$range}' ")->queryOne();
												echo"<td style='text-align:right'>".app\components\DeltaFormatter::formatNumberForUserFloat($mod['qty_m3'])."</td>";
												echo"<td style='text-align:right'>".app\components\DeltaFormatter::formatNumberForUserFloat($mod['harga'])."</td>";
												$tot_m3 += $mod['qty_m3'];
												$tot_harga += $mod['harga']*$mod['qty_m3'];
												$total_ver_m3[$range] += $mod['qty_m3'];
											}
											echo	"<td style='text-align:right'>".app\components\DeltaFormatter::formatNumberForUserFloat($tot_m3)."</td>";
											echo	"<td style='text-align:right'>".app\components\DeltaFormatter::formatNumberForUserFloat($tot_harga)."</td>";
											echo "</tr>";
											$total_m3 += $tot_m3;
											$total_harga += $tot_harga;
										}
									}
									?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2" style="text-align: right;">Jumlah &nbsp; </td>
										<?php foreach($ukuranganrange as $i => $range){ ?>
										<td style='text-align:right'><?= $total_ver_m3[$range] ?></td>
										<td style='text-align:right'></td>
										<?php } ?>
										<td style='text-align:right'><?= app\components\DeltaFormatter::formatNumberForUserFloat($total_m3); ?></td>
										<td style='text-align:right'><?= app\components\DeltaFormatter::formatNumberForUserFloat($total_harga) ?></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<?php } ?>
				<br>
				<span style="font-size: 1.5rem;"><b>Weekly Grader Report Monitoring</b></span>
				<div class="row">
					<div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-monitoring">
								<thead>
									<tr>
										<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
										<th style="line-height: 1; width: 120px;">Tanggal /<br>Lokasi Logpond</th>
										<th style="line-height: 1">Details</th>
										<th style="width: 150px;">Attachment</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach($modMonitoring as $i => $monitoring){ ?>
										<tr>
											<td style="text-align: center;"><?= ($i+1) ?></td>
											<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($monitoring['tanggal']) ?><br>
												<?= $monitoring['lokasi_logpond']; ?>
											</td>
											<td>
												<table style="width:100%; border: #D6D6D9 solid 1px;" id="monitoring-detail">
													<thead>
														<tr style="background-color: #D8D8D9">
															<td style="width:150px; padding: 2px;">Kayu</td>
															<td style="width:90px; padding: 2px;">Kondisi</td>
															<td style="width:70px; padding: 2px;">Btg</td>
															<td style="width:70px; padding: 2px;">m<sup>3</sup></td>
															<td style="width:70px; padding: 2px;">GR<sup>%</sup></td>
															<td style="width:70px; padding: 2px;">Pecah<sup>%</sup></td>
															<td style="width:70px; padding: 2px;">Cm</td>
														</tr>
													</thead>
													<tbody>
														<?php $modMonitoringDetail = \app\models\TMonitoringPembelianlogDetail::find()->where("monitoring_pembelianlog_id = ".$monitoring->monitoring_pembelianlog_id)->all();
														if(count($modMonitoringDetail)>0){
														$totalbtg = 0; $totalm3 = 0; $totalgr = 0; $totalpecah = 0; $totalcm = 0;
														foreach($modMonitoringDetail as $i => $detail){ 
															$totalbtg += $detail->btg; $totalm3 += $detail->m3; $totalgr += $detail->gr; $totalpecah += $detail->pecah; $totalcm += $detail->cm;
														?>
															<tr>
																<td style="border: #D6D6D9 solid 1px;">
																	<?= $detail->kayu->kayu_nama; ?>
																</td>
																<td style="border: #D6D6D9 solid 1px; text-align: center;">
																	<?= $detail->kondisi_global; ?>
																</td>
																<td style="border: #D6D6D9 solid 1px; text-align: right;">
																	<?= $detail->btg; ?>
																</td>
																<td style="border: #D6D6D9 solid 1px; text-align: right;">
																	<?= $detail->m3; ?>
																</td>
																<td style="border: #D6D6D9 solid 1px; text-align: right;">
																	<?= $detail->gr; ?>
																</td>
																<td style="border: #D6D6D9 solid 1px; text-align: right;">
																	<?= $detail->pecah; ?>
																</td>
																<td style="border: #D6D6D9 solid 1px; text-align: right;">
																	<?= $detail->cm; ?>
																</td>
															</tr>
														<?php } ?>
														<?php } ?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="2"></td>
															<td style="border: #D6D6D9 solid 1px; text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($totalbtg) ?></td>
															<td style="border: #D6D6D9 solid 1px; text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($totalm3) ?></td>
															<td style="border: #D6D6D9 solid 1px; text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($totalgr) ?></td>
															<td style="border: #D6D6D9 solid 1px; text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($totalpecah) ?></td>
															<td style="border: #D6D6D9 solid 1px; text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($totalcm) ?></td>
														</tr>
													</tfoot>
												</table>
											</td>
											<td>
												<?php
												$img = "";
												$modAttch = Yii::$app->db->createCommand("SELECT * FROM t_attachment WHERE reff_no = '{$monitoring->kode}'")->queryAll();
												if(count($modAttch)){
													foreach($modAttch as $i => $attch){
														$img .= "<img src='".yii\helpers\Url::base()."/uploads/pur/monitoringlog/".$attch['file_name']."' style='width:120px;'>";
													}
													if(count($modAttch) != ($i+1)){
														$img .= "<br>";
													}
													echo $img;
												}
												?>
											</td>
										</tr>
									<?php } ?>
								</tbody>
								<tfoot>

								</tfoot>
							</table>
						</div>
					</div>
				</div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
	formconfig();
", yii\web\View::POS_READY); ?>
<script>

</script>