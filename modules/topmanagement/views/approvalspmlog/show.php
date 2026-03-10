<?php
$model = \app\models\TApproval::findOne($approval_id);
$assigned_to = $model->assigned_to;
$approved_by = $model->approved_by;
$modTSpkShipping = \app\models\TSpkShipping::findOne(['kode'=>$model->reff_no]);
    $modTSpkShipping->asuransi == 1 ? $asuransi = "Ya" : $asuransi = "Tidak";
    $by_kanit = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modTSpkShipping->by_kanit)->queryOne();
    $by_kadiv = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modTSpkShipping->by_kadiv)->queryOne();
$modTPengajuanPembelianlog = \app\models\TPengajuanPembelianlog::findAll(['spk_shipping_id'=>$modTSpkShipping->spk_shipping_id]);
$assigned_to = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$assigned_to."")->queryScalar();
if (!empty($approved_by)) {
    $approved_by = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approved_by."")->queryScalar();
} else {
    $approved_by = '';
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
		</div>
		<div class="col-md-6">
            <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Assigned to'); ?></label>
				<div class="col-md-7"><?=$assigned_to;?></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Approved by'); ?></label>
				<div class="col-md-7"><?=$approved_by;?></div>
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
						<?php /* <div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Kanit'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modTSpkShipping->by_kanit)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php
											if($by_kanit['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_kanit['updated_at'])."</span>";
											}else if($by_kanit['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_kanit['updated_at'])."</span>";
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											}
											?>
										</span>
                                        <?php
                                        if(!empty($modTSpkShipping->approve_reason)){
                                            $modApproveReason = \yii\helpers\Json::decode($modTSpkShipping->approve_reason);
                                            foreach($modApproveReason as $iap => $aprreas){
                                                if($aprreas['by'] == $modTSpkShipping->by_kanit){
                                                    echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                    echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                    echo '</span>';
                                                }
                                            }
                                        }
                                        if(!empty($modTSpkShipping->reject_reason)){
                                            $modRejectReason = \yii\helpers\Json::decode($modTSpkShipping->reject_reason);
                                            foreach($modRejectReason as $irj => $rjcreas){
                                                if($rjcreas['by'] == $modTSpkShipping->by_kanit){
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
						</div> */?>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Kadiv'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modTSpkShipping->by_kadiv)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($by_kadiv['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_kadiv['updated_at'])."</span>";
											}else if($by_kadiv['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($by_kadiv['updated_at'])."</span>";
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											} ?>
										</span>
                                        <?php
                                        if(!empty($modTSpkShipping->approve_reason)){
                                            $modApproveReason = \yii\helpers\Json::decode($modTSpkShipping->approve_reason);
                                            foreach($modApproveReason as $iap => $aprreas){
                                                if($aprreas['by'] == $modTSpkShipping->by_kadiv){
                                                    echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                    echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                    echo '</span>';
                                                }
                                            }
                                        }
                                        if(!empty($modTSpkShipping->reject_reason)){
                                            $modRejectReason = \yii\helpers\Json::decode($modTSpkShipping->reject_reason);
                                            foreach($modRejectReason as $irj => $rjcreas){
                                                if($rjcreas['by'] == $modTSpkShipping->by_kadiv){
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
                        </div>
                    </div>
                    <div class="row">
						<div class="col-md-12">
							<div class="table-scrollable">
								<h5 class="text-bold">SPM Log</h5>
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-kuantitas">
									<thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>Nama Tongkang</th>
                                            <th>ETD to Logpond</th>
                                            <th>ETA Logpond</th>
                                            <th>ETA Tanjung Mas</th>
                                            <th>Lokasi Muat</th>
                                            <th>Total Pcs</th>
                                            <th>Total Volume</th>
                                            <th>Asuransi</th>
                                            <th>Keterangan</th>
                                        </tr>
									</thead>
									<tbody>
                                        <tr>
                                            <td class="td-kecil text-center"><?php echo $modTSpkShipping->kode;?></td>
                                            <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modTSpkShipping->tanggal);?></td>
                                            <td class="td-kecil text-center"><?php echo $modTSpkShipping->nama_tongkang;?></td>
                                            <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modTSpkShipping->etd);?></td>
                                            <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modTSpkShipping->eta_logpond);?></td>
                                            <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modTSpkShipping->eta);?></td>
                                            <td class="td-kecil"><?php echo $modTSpkShipping->lokasi_muat;?></td>
                                            <td class='td-kecil text-right'><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($modTSpkShipping->estimasi_total_batang);?></td>
                                            <td class='td-kecil text-right'><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($modTSpkShipping->estimasi_total_m3);?></td>
                                            <td class="td-kecil"><?php echo $asuransi;?></td>
                                            <td class="td-kecil"><?php echo $modTSpkShipping->keterangan;?></td>
                                        </tr>
									</tbody>
									<tfoot>
									</tfoot>
								</table>
							</div>
							<div class="table-scrollable">
								<h5 class="text-bold">Pengajuan Pembelian Log</h5>
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-kualitas">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>No. Kontrak</th>
                                            <th>Suplier</th>
                                            <th>Asal Kayu</th>
                                            <th>Total Volume</th>
                                            <th>Lokasi Muat</th>
                                            <th>Asuransi</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
									<tbody>
                                        <?php
                                        foreach ($modTPengajuanPembelianlog as $kolom) {
                                            $kolom['asuransi'] == 1 ? $asuransi = "Ya" : $asuransi = "Tidak";
                                            $suplier_id = $kolom['suplier_id'];
                                            $sql_suplier = "select suplier_nm_company from m_suplier where suplier_id = ".$suplier_id."";
                                            $suplier_nm_company = Yii::$app->db->createCommand($sql_suplier)->queryScalar();
                                        ?>
                                        <tr>
                                            <td class="td-kecil text-center"><?php echo $kolom['kode'];?></td>
                                            <td class="td-kecil text-center"><?php echo $kolom['tanggal'];?></td>
                                            <td class="td-kecil text-left"><?php echo $kolom['nomor_kontrak'];?></td>
                                            <td class="td-kecil text-left"><?php echo $suplier_nm_company;?></td>
                                            <td class="td-kecil text-leftr"><?php echo $kolom['asal_kayu'];?></td>
                                            <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($kolom['total_volume']);?></td>
                                            <td class="td-kecil text-left"><?php echo $kolom['lokasi_muat'];?></td>
                                            <td class="td-kecil text-center"><?php echo $asuransi;?></td>
                                            <td class="td-kecil text-left"><?php echo $kolom['keterangan'];?></td>
                                        </tr>
                                        <?php
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
<div class="modal-footer" style="text-align: center;">
	<?php if( (empty($model->approved_by)) && (empty($model->tanggal_approve)) ){ ?>
    <?php if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )){ ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); ?>
    <?php } ?>
	<?php } ?>
</div>
