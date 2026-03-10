<?php

$model = \app\models\TOpKo::findOne($op_ko_id);
//$modelApprove = \app\models\TApproval::findOne(['reff_no'=>$model->kode]);

//echo"<pre>";
//print_r($modelApprove->reff_no);
//echo"</pre>";
//exit;

    $tanggal_batas = $model->tanggal;
    $kode = $model->kode;
   
    $approve_reason = yii\helpers\Json::decode($model->approve_reason);
    $reject_reason = yii\helpers\Json::decode($model->reject_reason);

$modDetail = \app\models\TOpKoDetail::find()->where(['op_ko_id'=>$model->op_ko_id])->orderBy(['op_ko_detail_id'=>SORT_DESC])->all();
$modTempo = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);
$modCustTop = \app\models\MCustTop::findOne(['cust_id'=>$model->cust_id,'custtop_jns'=>$model->jenis_produk,'active'=>true]);
$modTAttachments = \app\models\TAttachment::findAll(['reff_no'=>$kode]);
?>
<style>
.form-group {
    margin-bottom: 0 !important;
}
</style>
<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Order Penjualan').'<b>'.\app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->kode).'</b>'; ?></h4>
            </div>
            
<div class="modal-body" >
	<div class="row" style="margin-bottom: 10px;">		
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Nomor'); ?></label>
				<div class="col-md-7"><strong><?= $model->kode ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk'); ?></label>
				<div class="col-md-7"><strong><?= $model->jenis_produk ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Sales'); ?></label>
				<div class="col-md-7"><strong><?= $model->sales->sales_nm ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Status OP'); ?></label>
				<div class="col-md-7">
                                    <strong class="font-yellow-gold">
                                        <?php 
                                            if($model->status ==''){
                                                $statusop = "Allowed";
                                            }else{
                                                $statusop = $model->status;
                                            }
                                            echo"$statusop";
                                        ?>                                    
                                    </strong>
                                </div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Sistem Bayar'); ?></label>
				<div class="col-md-7" style="line-height: 0.8; margin-bottom: 10px;"><strong>
					<?php
					if($model->sistem_bayar == "Tempo"){
						echo $model->sistem_bayar." - ".$modTempo->top_hari." Hari<br>";
						if(!empty($modCustTop)){
							if($modTempo->top_hari > $modCustTop->custtop_top){
								echo " &nbsp;&nbsp; <span style='font-size:1rem;' class='font-red-flamingo'><i>- Max Tempo : ".$modCustTop->custtop_top." Hari</i></span>";
							}
						}
					}else{
						echo "$model->sistem_bayar";
					}
					?>
					</strong>
                                </div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Cara Bayar'); ?></label>
				<div class="col-md-7"><strong class="font-yellow-gold"><?= $model->cara_bayar ?></strong></div>
			</div>
<!--			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Total Harga'); ?></label>
				<div class="col-md-7" style="line-height: 0.8; margin-bottom: 10px;"><strong>
					<?php
					if($model->sistem_bayar == "Tempo"){
						$total = 0;
						$grandtotal = 0;
						if(count($modDetail)>0){
							foreach($modDetail as $i => $detail){								
								if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
									$subtotal = $detail->harga_jual * $detail->qty_kecil;
								}else{
									$subtotal = $detail->harga_jual * $detail->kubikasi;
								}
								$total += $subtotal;
							}
						}
						echo \app\components\DeltaFormatter::formatNumberForUserFloat($total);
						$grandtotal = $total + $modTempo->op_aktif + $modTempo->sisa_piutang;
						if($grandtotal > $modTempo->maks_plafon){
							echo "<br> &nbsp;&nbsp; <span style='font-size:1rem;' class='font-red-flamingo'><i>- Max Plafon : ".\app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->maks_plafon)."</i></span>";
							if($modTempo->sisa_piutang > 0){
								echo "<br> &nbsp;&nbsp; <span style='font-size:1rem;' class='font-red-flamingo'><i>- Piutang Aktif: ".\app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_piutang)."</i></span>";
							}
							if($modTempo->op_aktif > 0){
								echo "<br> &nbsp;&nbsp; <span style='font-size:1rem;' class='font-red-flamingo'><i>- OP Aktif: ".\app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->op_aktif)."</i></span>";
							}
						}
					}else{
						echo "-";
					}
					?>
					</strong></div>
			</div>-->
			
		</div>
                <div class="col-md-6">
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Customer'); ?></label>
				<div class="col-md-7"><strong><?= $model->cust->cust_an_nama ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Alamat Bongkar'); ?></label>
				<div class="col-md-7"><strong><?= $model->alamat_bongkar ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Syarat Jual'); ?></label>
				<div class="col-md-7"><strong class="font-yellow-gold"><?= $model->syarat_jual ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Kirim'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim); ?></strong></div>
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
											<th style="text-align: center;"><?= Yii::t('app', 'Nama Produk'); ?></th>
											<th style="width: 50px;"><?= Yii::t('app', 'Palet'); ?></th>
											<th style=""><?= Yii::t('app', 'Qty'); ?></th>
											<th style=""><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
											<th style=""><?= Yii::t('app', 'Harga Jual Terendah'); ?></th>
											<th style=""><?= Yii::t('app', 'Harga Jual'); ?></th>
											<th style=""><?= Yii::t('app', 'Subtotal'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$total = 0;
										$grandtotal = 0;
										if(count($modDetail)>0){
											foreach($modDetail as $i => $detail){
												
                                                $produk_id = $detail->produk_id;

												if($model->jenis_produk == "Log"){
													$sql_m_harga_produk = "select harga_enduser from m_harga_log ".
																		"	where log_id = '".$produk_id."' ".
																		"	and harga_tanggal_penetapan <= '".$tanggal_batas."' ".
																		"	and status_approval = 'APPROVED' ".
																		"	order by harga_tanggal_penetapan desc ".
																		"	limit 1 ".
																		"	";
												} else {
													$sql_m_harga_produk = "select harga_enduser from m_harga_produk ".
																		"	where produk_id = '".$produk_id."' ".
																		"	and harga_tanggal_penetapan <= '".$tanggal_batas."' ".
																		"	and status_approval = 'APPROVED' ".
																		"	order by harga_tanggal_penetapan desc ".
																		"	limit 1 ".
																		"	";
												}
												$harga_enduser = Yii::$app->db->createCommand($sql_m_harga_produk)->queryScalar();
												
												if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
													$subtotal = $detail->harga_jual * $detail->qty_kecil;
												}elseif($model->jenis_produk == "Limbah"){
                                                    $subtotal = $detail->harga_jual * $detail->qty_kecil;
                                                }else{
													$subtotal = $detail->harga_jual * $detail->kubikasi;
												}

												$harga_enduser > $detail->harga_jual ? $low_price = 'font-red-flamingo font-weight-bold' : $low_price = '';

												$total += $subtotal;                                                

												if ($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding" ) {
													$sql_produk_nama = "select nama from m_produk_jasa where produk_jasa_id = '".$produk_id."' ";
                                                    $produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
                                                    $harga_enduser = 0;
												} else if ($model->jenis_produk == "Limbah") {
                                                    //PPC - (Limbah) Limbah
													$sql_produk_nama = "select concat(limbah_kode,' - (',limbah_produk_jenis,') ',limbah_nama) from m_brg_limbah where limbah_id = '".$produk_id."' ";
                                                    $produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
                                                    $harga_enduser = 0;
                                                } else if($model->jenis_produk == "Log"){
													$sql_produk_nama = "select log_nama from m_brg_log where log_id = '".$produk_id."'";
													$produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
												} else {
                                                    $produk_nama = $detail->produk->produk_nama;
                                                }
                                                
                                                ?>
                                                
												<tr>
													<td style="text-align: center;"><?= $i+1; ?></td>
													<td style=""><?= $produk_nama; ?></td>
													<td style="text-align: right;"><?= ($model->jenis_produk == "Log")?'':\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_besar); ?></td>
													<td style="text-align: right;"><?= ($model->jenis_produk == "Log")?'1<i>(Pcs)</i>':\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil)." (".$detail->satuan_kecil.")"; ?></td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->kubikasi); ?></td>
													<td style="text-align: right;" class="<?php echo $low_price;?>"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($harga_enduser); ?></td>
													<td style="text-align: right;" class="<?php echo $low_price;?>"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga_jual); ?></td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($subtotal); ?></td>
												</tr>
										<?php
											}
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="7" style="text-align: right;">TOTAL &nbsp; </td>
											<td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total);?></td>
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


        </div>
    </div>
</div>
<script>
function image(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalop/image','id'=>'']) ?>'+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-image").modal('show');
		$("#modal-image").on('hidden.bs.modal', function () { });
		$("#modal-image .modal-dialog").css('width',"1000px");
		spinbtn();
		draggableModal();
	});
}
</script>