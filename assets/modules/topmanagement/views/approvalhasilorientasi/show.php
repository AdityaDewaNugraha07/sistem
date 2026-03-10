<?php
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\THasilOrientasi::findOne(['kode'=>$model->reff_no]);
$sistem_pemuatan = "";
$modReff->sistem_pemuatan = yii\helpers\Json::decode($modReff->sistem_pemuatan);
if($modReff->sistem_pemuatan['sp_langsung']=='1'){
	$sistem_pemuatan .= "Langsung : <span class='font-red-flamingo'>".$modReff->sistem_pemuatan['sp_langsung_feet']." Feet</span>";
}
if($modReff->sistem_pemuatan['sp_estafet']=='1'){
	if($modReff->sistem_pemuatan['sp_langsung']=='1'){
		$sistem_pemuatan .= "<br>";
	}
	$sistem_pemuatan .= "Estafet : ".$modReff->sistem_pemuatan['sp_estafet_kendaraan']." <span class='font-red-flamingo'>".$modReff->sistem_pemuatan['sp_estafet_feet']." Feet</span>";
	$sistem_pemuatan .= "<br>Tongkang Induk : <span class='font-red-flamingo'>".$modReff->sistem_pemuatan['sp_estafet_induk_feet']." Feet</span>";
}
$lama_pemuatan = "";
$modReff->lama_pemuatan = yii\helpers\Json::decode($modReff->lama_pemuatan);
if($modReff->lama_pemuatan['lp_langsung']=='1'){
	$lama_pemuatan .= "Langsung : <span class='font-font-red-mint'>".$modReff->lama_pemuatan['lp_langsung_hari']." Hari</span>";
}
if($modReff->lama_pemuatan['lp_estafet']=='1'){
	if($modReff->lama_pemuatan['lp_langsung']=='1'){
		$lama_pemuatan .= "<br>";
	}
	$lama_pemuatan .= "Estafet : <span class='font-red-flamingo'>".$modReff->lama_pemuatan['lp_estafet_m3']." m<sup>3</sup>/".
								$modReff->lama_pemuatan['lp_estafet_hari']." Hari</span>";
}
$jenis_alat_berat = "";
$modReff->jenis_alat_berat = yii\helpers\Json::decode($modReff->jenis_alat_berat);
$jenis_alat_berat .= "Traktor : <span class='font-red-flamingo'>".$modReff->jenis_alat_berat['jab_traktor']." Unit</span><br>";
$jenis_alat_berat .= "Logging : <span class='font-red-flamingo'>".$modReff->jenis_alat_berat['jab_logging']." Unit</span><br>";
$jenis_alat_berat .= "Loader : <span class='font-red-flamingo'>".$modReff->jenis_alat_berat['jab_loader']." Unit</span><br>";
$jenis_alat_berat .= "Lainnya : <span class='font-red-flamingo'>".$modReff->jenis_alat_berat['jab_lainnya']." Unit</span><br>";
$lokasi_produksi = "";
$modReff->lokasi_produksi = yii\helpers\Json::decode($modReff->lokasi_produksi);
$lokasi_produksi .= "Blok ke TPN : <span class='font-red-flamingo'>".$modReff->lokasi_produksi['lpr_blok2tpn']." KM</span> (".$modReff->lokasi_produksi['lpr_blok2tpn_kondisi'].")";
$lokasi_produksi .= "<br>TPN ke TPK : <span class='font-red-flamingo'>".$modReff->lokasi_produksi['lpr_tpn2tpk']." KM</span> (".$modReff->lokasi_produksi['lpr_tpn2tpk_kondisi'].")";
$rendemen_produksi = "";
$modReff->rendemen_produksi = yii\helpers\Json::decode($modReff->rendemen_produksi);

if(!empty($modReff->rendemen_produksi) && $modReff->rendemen_produksi != "-"){
    $rendemen_produksi .= "Sawnmill : <span class='font-red-flamingo'>".$modReff->rendemen_produksi['rp_sawnmill']."%</span><br>";
    $rendemen_produksi .= "Plymill : <span class='font-red-flamingo'>".$modReff->rendemen_produksi['rp_plymill']."%</span><br>";
    $rendemen_produksi .= "&nbsp; - Face : <span class='font-red-flamingo'>".$modReff->rendemen_produksi['rp_face']."%</span><br>";
    $rendemen_produksi .= "&nbsp; - Back : <span class='font-red-flamingo'>".$modReff->rendemen_produksi['rp_back']."%</span><br>";
    $rendemen_produksi .= "&nbsp; - Core : <span class='font-red-flamingo'>".$modReff->rendemen_produksi['rp_core']."%</span>";
}

$target_rkt_sebelumnya = "";
$modReff->target_rkt_sebelumnya = yii\helpers\Json::decode($modReff->target_rkt_sebelumnya);
$target_rkt_sebelumnya .= "Tahun : <span class='font-red-flamingo'>".$modReff->target_rkt_sebelumnya['tahun_target_rkt1']."</span>";
$target_rkt_sebelumnya .= "&nbsp;&nbsp;&nbsp;Realisasi : <span class='font-red-flamingo'>".	$modReff->target_rkt_sebelumnya['target_rkt1']." m3</span><br>";
$target_rkt_sebelumnya .= "Tahun : <span class='font-red-flamingo'>".$modReff->target_rkt_sebelumnya['tahun_target_rkt2']."</span>";
$target_rkt_sebelumnya .= "&nbsp;&nbsp;&nbsp;Realisasi : <span class='font-red-flamingo'>".$modReff->target_rkt_sebelumnya['target_rkt2']." m3</span><br>";

if ($modReff->perlakuan_log_tidak_standard_lain == "") { 
	$perlakuan_log_tidak_standard = $modReff->perlakuan_log_tidak_standard;
} else {
	$perlakuan_log_tidak_standard = $modReff->perlakuan_log_tidak_standard_lain;
}
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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Orientasi'); ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Berkas'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_berkas); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'IUPHHK'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->nama_iuphhk ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'IPK'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->nama_ipk ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Lokasi'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->lokasi_muat ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'RKT/Th'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatNumberForUserFloat($modReff->rkt_pertahun) ?></strong> m<sup>3</sup></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Target RKT'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatNumberForUserFloat($modReff->target_rkt) ?></strong> m<sup>3</sup></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Target RKT Sebelumnya'); ?></label>
				<div class="col-md-7">
					<strong>
						<?= app\components\DeltaFormatter::formatNumberForUserFloat($target_rkt_sebelumnya) ?>
					</strong>
				</div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kondisi Logpond'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kondisi_logpond ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Sistem Pemuatan'); ?></label>
				<div class="col-md-7"><strong><?= $sistem_pemuatan ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Lama Pemuatan'); ?></label>
				<div class="col-md-7"><strong><?= $lama_pemuatan ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Alat Berat'); ?></label>
				<div class="col-md-7"><strong><?= $jenis_alat_berat ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kondisi Alat Berat'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kondisi_alat_berat ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Lokasi Produksi'); ?></label>
				<div class="col-md-7"><strong><?= $lokasi_produksi ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Scaling'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->perjanjian_scaling; ?> <?= ($modReff->perjanjian_scaling=="Trimming")?" : <span class='font-red-flamingo'>".$modReff->perjanjian_scaling_trimming."%":"" ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kualitas Kayu'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kualitas_kayu; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Jumlah Sampling Log'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->jumlah_sampling_log; ?></strong> pcs</div>
			</div>			
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Perlakuan Log Tidak Standard'); ?></label>
				<div class="col-md-7"><strong><?= $perlakuan_log_tidak_standard; ?></strong></div>
			</div>			
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kondisi Supplier'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kondisi_perusahaan; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Rekomendasi Grader'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->rekomendasi_grader; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Alasan Pertimbangan'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->alasan_pertimbangan; ?></strong></div>
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
						?>
						<br>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'GM Log Purch'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->by_gmpurch)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php
											if($by_gmpurch['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_gmpurch['updated_at'])."</span>";
											}else if($by_gmpurch['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_gmpurch['updated_at'])."</span>";
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											}
											?>
										</span>
                                        <?php
                                        if(!empty($modReff->approve_reason)){
                                            $modApproveReason = \yii\helpers\Json::decode($modReff->approve_reason);
                                            foreach($modApproveReason as $iap => $aprreas){
                                                if($aprreas['by'] == $modReff->by_gmpurch){
                                                    echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                    echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                    echo '</span>';
                                                }
                                            }
                                        }
                                        if(!empty($modReff->reject_reason)){
                                            $modRejectReason = \yii\helpers\Json::decode($modReff->reject_reason);
                                            foreach($modRejectReason as $irj => $rjcreas){
                                                if($rjcreas['by'] == $modReff->by_gmpurch){
                                                    echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                    echo "<br>&nbsp; <span class='font-red-flamingo'>( ".$rjcreas['reason']." )</span>";
                                                    echo '</span>';
                                                }
                                            }
                                        }
                                        ?>
									</td>
								</tr>
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
											}else if($by_dirut['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_dirut['updated_at'])."</span>";
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											} ?>
										</span>
                                        <?php
                                        if(!empty($modReff->approve_reason)){
                                            $modApproveReason = \yii\helpers\Json::decode($modReff->approve_reason);
                                            foreach($modApproveReason as $iap => $aprreas){
                                                if($aprreas['by'] == $modReff->by_dirut){
                                                    echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                    echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                    echo '</span>';
                                                }
                                            }
                                        }
                                        if(!empty($modReff->reject_reason)){
                                            $modRejectReason = \yii\helpers\Json::decode($modReff->reject_reason);
                                            foreach($modRejectReason as $irj => $rjcreas){
                                                if($rjcreas['by'] == $modReff->by_dirut){
                                                    echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                    echo "<br>&nbsp; <span class='font-red-flamingo'>( ".$rjcreas['reason']." )</span>";
                                                    echo '</span>';
                                                }
                                            }
                                        }
                                        ?>
									</td>
								</tr>
							</table>
						</div>
						<div class="col-md-12">
							<div class="table-scrollable">
								<h4>Kuantitas</h4>
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-kuantitas">
									<thead>
										<?php
										$ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log');
										?>
										<tr>
											<th style="width: 30px;" rowspan="3" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
											<th style="width: 120px;" rowspan="3"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
											<th colspan="<?= (count($ukuranganrange)*2+2) ?>"><?= Yii::t('app', 'Diameter'); ?></th>
											<th rowspan="3"><?= Yii::t('app', 'Keterangan'); ?></th>
										</tr>
										<tr>
											<?php foreach($ukuranganrange as $i => $range){ ?>
											<th colspan="2"><?= $range ?></th>
											<?php } ?>
											<th colspan="2">Total</th>
										</tr>
										<tr>
											<?php foreach($ukuranganrange as $i => $range){ ?>
											<th style="width: 50px;">Btg</th>
											<th style="width: 70px;">M<sup>3</sup></th>
											<?php } ?>
											<th style="width: 60px;">Btg</th>
											<th style="width: 85px;">M<sup>3</sup></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$modHOKuantitas = \app\models\THasilOrientasiKuantitas::find()
															->select("hasil_orientasi_id, kayu_id, keterangan")
															->groupBy("hasil_orientasi_id, kayu_id, keterangan")
															->where(['hasil_orientasi_id'=>$modReff->hasil_orientasi_id])->all();
										$total_btg = 0; $total_m3 = 0; 
										foreach($ukuranganrange as $i => $range){
											$total_ver_btg[$range] = 0; $total_ver_m3[$range] = 0;
										}
										foreach($modHOKuantitas as $i => $kuantitas){
											echo "<tr>";
											echo	"<td>".($i+1)."</td>";
											echo	"<td>".$kuantitas->kayu->kayu_nama."</td>";
												$subtotal_btg = 0; $subtotal_m3 = 0;
												foreach($ukuranganrange as $i => $range){
													$sql = "SELECT SUM(qty_batang) AS qty_btg, SUM(qty_m3) AS qty_m3 FROM t_hasil_orientasi_kuantitas 
															WHERE hasil_orientasi_id = {$modReff->hasil_orientasi_id} AND kayu_id = {$kuantitas->kayu_id} AND diameter_cm = '{$range}' AND keterangan='{$kuantitas->keterangan}'";
													$modQty = Yii::$app->db->createCommand($sql)->queryOne();
													echo "<td class='text-align-center'>".$modQty['qty_btg']."</td>";
													echo "<td class='text-align-right'>".app\components\DeltaFormatter::formatNumberForUserFloat($modQty['qty_m3'])."</td>";
													$subtotal_btg += $modQty['qty_btg'];
													$subtotal_m3 += $modQty['qty_m3'];
													$total_ver_btg[$range] += $modQty['qty_btg'];
													$total_ver_m3[$range] += $modQty['qty_m3'];
												}
												$total_btg += $subtotal_btg;
												$total_m3 += $subtotal_m3;
												echo "<td class='text-align-right'>".$subtotal_btg."</td>";
												echo "<td class='text-align-right'>".app\components\DeltaFormatter::formatNumberForUserFloat($subtotal_m3)."</td>";
												echo "<td class='text-align-left'>".$kuantitas['keterangan']."</td>";
											echo "</tr>";
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2" style="text-align: right;">Jumlah &nbsp; </td>
											<?php foreach($ukuranganrange as $i => $range){ ?>
											<td  class="text-align-center">
												<?= $total_ver_btg[$range]; ?>
											</td>
											<td  class="text-align-right">
												<?= app\components\DeltaFormatter::formatNumberForUserFloat($total_ver_m3[$range]); ?>
											</td>
											<?php } ?>
											<td class="text-align-right"> <?= $total_btg ?> </td>
											<td class="text-align-right" style="border: solid 1px #bebebe;"> <?= app\components\DeltaFormatter::formatNumberForUserFloat($total_m3) ?> </td>
										</tr>
										<tr>
											<td colspan="2" style="text-align: right;">% &nbsp; </td>
											<?php foreach($ukuranganrange as $i => $range) { ?>
											<td  class="text-align-center"></td>
											<td  class="text-align-right">
											<?php

											$sub_total = $total_ver_m3[$range];
											$total = $total_m3;
											$persen = round(($sub_total / $total) * 100);
											echo $persen." %";

											?>
											</td>
											<?php } ?>
											<td class="text-align-right">&nbsp;</td>
											<td class="text-align-right" style="border: solid 1px #cecece;">&nbsp;</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="table-scrollable">
								<h4 style="padding-left: 10px;">Kualitas</h4>
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-kualitas">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;" rowspan="3"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th style="width: 120px;" rowspan="3"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                                        <th colspan="8"><?= Yii::t('app', 'Kuantitas'); ?></th>
                                                        <th rowspan="2" colspan="2"><?= Yii::t('app', 'Gubal'); ?></th>
                                                        <th rowspan="3"><?= Yii::t('app', 'Keterangan'); ?></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 70px;" rowspan="2">Btg</th>
                                                        <th style="width: 90px;" rowspan="2">M<sup>3</sup></th>
                                                        <th style="width: 50px;" rowspan="2">Bekas<br>Pilih</th>
                                                        <th style="width: 280px;" colspan="5" id="usia_tebang_persen">Usia Tebang (%)</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 70px;">1 - 3 Bln</th>
                                                        <th style="width: 70px;">4 - 5 Bln</th>
                                                        <th style="width: 70px;">6 - 8 Bln</th>
                                                        <th style="width: 70px;">9 > 12 Bln</th>
                                                        <th style="width: 70px;">Total</th>
                                                        <th style="width: 70px;">GR (%)</th>
                                                        <th style="width: 70px;">Pecah (%)</th>
                                                    </tr>
                                                </thead>
									<tbody>
										<?php
										if(count($modHOKuantitas)>0){
											foreach($modHOKuantitas as $i => $kuantitas){
                                                $kualitas = \app\models\THasilOrientasiKualitas::find()->where(['hasil_orientasi_id'=>$modReff->hasil_orientasi_id,'kayu_id'=>$kuantitas->kayu_id])->one();
												$usia_tebang = yii\helpers\Json::decode($kualitas->usia_tebang);
												$kondisi_global = yii\helpers\Json::decode($kualitas->kondisi_global);
												$kondisi_total = yii\helpers\Json::decode($kualitas->kondisi_total);
												echo "<tr>";
												echo	"<td class='text-align-center'>".($i+1)."</td>";
												echo	"<td>".$kualitas->kayu->kayu_nama."</td>";
												echo	"<td class='text-align-center'>".$kualitas->qty_batang."</td>";
												echo	"<td class='text-align-right'>". app\components\DeltaFormatter::formatNumberForUserFloat($kualitas->qty_m3)."</td>";
												echo	"<td class='text-align-center'>".(($kualitas->bekas_pilih==true)?"Ya":"Tidak")."</td>";
												//echo	"<td class='text-align-center'>".$usia_tebang['ut_qty']." ".$usia_tebang['ut_satuan']."</td>";
												//echo	"<td class='text-align-center'>".$kondisi_global['kg_sehat']." Cm</td>";
												//echo	"<td class='text-align-center'>".$kondisi_global['kg_rusak']." Cm</td>";
												echo	"<td class='text-align-center'>".$usia_tebang['ut_13']."</td>";
												echo	"<td class='text-align-center'>".$usia_tebang['ut_45']."</td>";
												echo	"<td class='text-align-center'>".$usia_tebang['ut_68']."</td>";
												echo	"<td class='text-align-center'>".$usia_tebang['ut_99']."</td>";
												echo	"<td class='text-align-center'>".$kondisi_global['kg_gubal']." ".$kondisi_global['kg_gubal']."</td>";
												echo	"<td class='text-align-center'>".$kondisi_total['kt_gr']." %</td>";
												echo	"<td class='text-align-center'>".$kondisi_total['kt_pecah']." %</td>";
												echo	"<td class='text-align-left'>".$kualitas->keterangan."</td>";
												echo "</tr>";
											}
										}
										?>
									</tbody>
								</table>
							</div>
							<div class="table-scrollable">
								<h4 style="padding-left: 10px;">Grader Terlibat</h4>
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-kualitas">
									<thead>
										<tr>
											<th style="width: 30px;" class='text-align-center'><?= Yii::t('app', 'No'); ?></th>
											<th style="width: 150px;" ><?= Yii::t('app', 'Kode Dinas'); ?></th>
											<th ><?= Yii::t('app', 'Nama Grader'); ?></th>
											<th style="" ><?= Yii::t('app', 'No. HP Grader'); ?></th>
											<th style="" ><?= Yii::t('app', 'Email Grader'); ?></th>
											<th style="" ><?= Yii::t('app', 'Wilayah Dinas'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$graders = \yii\helpers\Json::decode($modReff->grader_terlibat);
										if(count($graders)>0){
											foreach($graders as $i => $grader){
												$dkg = \app\models\TDkg::findOne($grader['gt_dkg_id']);
												echo "<tr>";
												echo	"<td class='text-align-center'>".($i+1)."</td>";
												echo	"<td class='text-align-center'>".$dkg->kode."</td>";
												echo	"<td>".$grader['gt_nama_grader']."</td>";
												echo	"<td>".$dkg->graderlog->graderlog_phone."</td>";
												echo	"<td>".$dkg->graderlog->graderlog_email."</td>";
												echo	"<td>".$grader['gt_wilayah_dinas']."</td>";
												echo "</tr>";
											}
										}
										?>
									</tbody>
								</table>
							</div>
							<br>
							
							<div class="table-scrollable">
								<h4 style="padding-left: 10px;">Attachment <span><font style="size: 10px;">(Klik gambar untuk memperbesar)</font></span></h4> 
								<br>
								<?php
								$attachments = \app\models\TAttachment::find()->where(['reff_no'=>$modReff->kode])->orderBy("seq ASC")->all();
								if(count($attachments)>0){
									foreach($attachments as $i => $attch) { ?>

									<div class="col-md-2">
										<div class="thumbnail">
											<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('<?php echo $attch->attachment_id;?>')">
												<img src="<?= Yii::$app->urlManager->baseUrl ?>/uploads/pur/hasilorientasi/<?= $attch->file_name; ?>" style="" alt="<?=  $attch->file_name;?>"/> 
											</a>
										</div>
									</div>
								<?php
									}
								}
								?>
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
    <?php if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){ ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); ?>
    <?php } ?>
	<?php } ?>
</div>