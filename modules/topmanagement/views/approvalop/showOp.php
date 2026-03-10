<?php
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TOpKo::findOne(['kode'=>$model->reff_no]);
$tanggal_batas = $modReff->tanggal;
$kode = $modReff->kode;
   
$model_t_op_ko = \app\models\TOpKo::findOne(['kode'=>$model->reff_no]);
    $approve_reason = yii\helpers\Json::decode($modReff->approve_reason);
    $reject_reason = yii\helpers\Json::decode($modReff->reject_reason);

$modDetail = \app\models\TOpKoDetail::find()->where(['op_ko_id'=>$modReff->op_ko_id])->orderBy(['op_ko_detail_id'=>SORT_DESC])->all();
$modTempo = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$modReff->op_ko_id]);
$modCustTop = \app\models\MCustTop::findOne(['cust_id'=>$modReff->cust_id,'custtop_jns'=>$modReff->jenis_produk,'active'=>true]);
$modTAttachments = \app\models\TAttachment::findAll(['reff_no'=>$kode,'active'=>true]);
$approveLevel_1 = app\components\params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO ;
$approveLevel_2 = app\components\params::DEFAULT_PEGAWAI_ID_ASENG ;
$approveLevel_3 = app\components\params::DEFAULT_PEGAWAI_ID_SUPRIYADI_INTERNALCONTROL ;
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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode OP'); ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal OP'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal); ?></strong></div>
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
					if ($model->level == 2) {
						// cek dulu status approval level 1
						$sql_status_approval_level_1 = "select status from t_approval where reff_no = '".$model->reff_no."' and level = 1 ";
						$status_approval_level_1 = Yii::$app->db->createCommand($sql_status_approval_level_1)->queryScalar($sql_status_approval_level_1);
						
						if ($status_approval_level_1 != "REJECTED") {
							if ($model->status == \app\models\TApproval::STATUS_APPROVED) {
								echo '<span class="label label-success">'.$model->status.'</span>';
							//} else if ($model->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED) {							
							} else {
								echo '<span class="label label-default">'.$model->status.'</span>';
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
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label">PO</label>
				<div class="col-md-7"><?php echo $modReff->po;?></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label">Tanggal PO</label>
				<div class="col-md-7"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal_po);?></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label">Gambar/Image PO</label>
				<div class="col-md-7">
					<div class="row">
					<?php
					/*if ($modReff->gambar_po != "") { 
						$src = Yii::getAlias('@web')."/uploads/mkt/po/".$modReff->gambar_po;
					} else {
						$src = Yii::$app->view->theme->baseUrl."/cis/img/no-image.png";
					}*/
					foreach ($modTAttachments as $modTAttachment) {
						$attachment_id = $modTAttachment->attachment_id;
						$file_name = $modTAttachment->file_name;
						$file_ext = $modTAttachment->file_ext;
						$seq = $modTAttachment->seq;
						
						$full_path_file_name = Yii::$app->homeUrl.'/uploads/mkt/po/'.$file_name;			
						if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "bmp" || $file_ext == "png" || $file_ext == "giff" || $file_ext == "tiff") {
							echo '<div class="col-md-2" style="width: 50px;">
									<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('.$attachment_id.')">
										<img src="'.$full_path_file_name.'" alt="'.$full_path_file_name.'" style="width: 20px;" />
									</a>
								</div>';
						} else {
							echo '<div class="col-md-2" style="width: 50px;">
									<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="image('.$attachment_id.')"><i class="fa fa-arrow-circle-down fa-2x" aria-hidden="true" style="padding: 5px;"></i></a>
								</div>';																		
						}
					}
					?>
					</div>	
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->jenis_produk ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Customer'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->cust->cust_an_nama ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Status OP'); ?></label>
				<div class="col-md-7"><strong class="font-yellow-gold"><?= $modReff->status ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Sistem Bayar'); ?></label>
				<div class="col-md-7" style="line-height: 0.8; margin-bottom: 10px;"><strong>
					<?php
					if($modReff->sistem_bayar == "Tempo"){
						echo $modReff->sistem_bayar." - ".$modTempo->top_hari." Hari<br>";
						if(!empty($modCustTop)){
							if($modTempo->top_hari > $modCustTop->custtop_top){
								echo " &nbsp; <span style='font-size:1rem;' class='font-red-flamingo'><i>- Max Tempo : ".$modCustTop->custtop_top." Hari</i></span>";
							}
						}
					}else{
						echo "-";
					}
					?>
					</strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Total Harga'); ?></label>
				<div class="col-md-7" style="line-height: 0.8; margin-bottom: 10px;"><strong>
					<?php
					if($modReff->sistem_bayar == "Tempo"){
						$total = 0;
						$grandtotal = 0;
						if(count($modDetail)>0){
							foreach($modDetail as $i => $detail){								
								if($modReff->jenis_produk == "Plywood" || $modReff->jenis_produk == "Lamineboard" || $modReff->jenis_produk == "Platform"){
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
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Sales'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->sales->sales_nm ?></strong></div>
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

												$sql_m_harga_produk = "select harga_enduser from m_harga_produk ".
																		"	where produk_id = '".$produk_id."' ".
																		"	and harga_tanggal_penetapan <= '".$tanggal_batas."' ".
																		"	and status_approval = 'APPROVED' ".
																		"	order by harga_tanggal_penetapan desc ".
																		"	limit 1 ".
																		"	";
												$harga_enduser = Yii::$app->db->createCommand($sql_m_harga_produk)->queryScalar();
												
												if($modReff->jenis_produk == "Plywood" || $modReff->jenis_produk == "Lamineboard" || $modReff->jenis_produk == "Platform"){
													$subtotal = $detail->harga_jual * $detail->qty_kecil;
												}else{
													$subtotal = $detail->harga_jual * $detail->kubikasi;
												}

												$harga_enduser > $detail->harga_jual ? $low_price = 'font-red-flamingo font-weight-bold' : $low_price = '';

												$total += $subtotal;                                                

												if ($modReff->jenis_produk == "JasaKD" || $modReff->jenis_produk == "JasaGesek" || $modReff->jenis_produk == "JasaMoulding" ) {
													$sql_produk_nama = "select nama from m_produk_jasa where produk_jasa_id = '".$produk_id."' ";
                                                    $produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
                                                    $harga_enduser = 0;
												} 
                                                else if ($modReff->jenis_produk == "Limbah") {
                                                    //PPC - (Limbah) Limbah
													$sql_produk_nama = "select concat(limbah_kode,' - (',limbah_produk_jenis,') ',limbah_nama) from m_brg_limbah where limbah_id = '".$produk_id."' ";
                                                    $produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
                                                    $harga_enduser = \app\models\MHargaLimbah::getHargaCurrentEndUser($produk_id);
                                                } else if($modReff->jenis_produk == "Log"){
													$sql_produk_nama = "select log_nama from m_brg_log where log_id = '".$produk_id."'";
													$produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
													$harga_enduser = \app\models\MHargaLog::getHargaCurrentEndUser($produk_id);
												}
                                                else {
                                                    $produk_nama = $detail->produk->produk_nama;
                                                }
                                                
                                                ?>
                                                
												<tr>
													<td style="text-align: center;"><?= $i+1; ?></td>
													<td style=""><?= $produk_nama; ?></td>
													<td style="text-align: right;"><?= ($modReff->jenis_produk == "Log")?'':\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_besar); ?></td>
													<td style="text-align: right;"><?= ($modReff->jenis_produk == "Log")?'1<i>(Pcs)</i>':\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil)." (".$detail->satuan_kecil.")"; ?></td>
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

<div class="modal-footer" style="text-align: center;">
	<?php

	$sql = "select * from t_approval where reff_no = trim('".$modReff->kode."') AND level < ".$model->level." AND status != 'Not Confirmed' ";
	$checkApprovals = Yii::$app->db->createCommand($sql)->queryAll();

	$sql_status_level1 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '1' ";
	$status_level1 = Yii::$app->db->createCommand($sql_status_level1)->queryScalar();

	$sql_status_level2 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '2' ";
	$status_level2 = Yii::$app->db->createCommand($sql_status_level2)->queryScalar();
	$status_level2 != "Not Confirmed" || $status_level2 != "Rejected" ? $status_level2 = "APPROVED" : $status_level2 = "REJECTED";
	
	if (trim($model_t_op_ko->status) != "Low Price (2)") {
        $sql_status_level3 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '3' ";
	    $status_level3 = Yii::$app->db->createCommand($sql_status_level3)->queryScalar();
        $status_level3 != "Not Confirmed" || $status_level3 != "Rejected" ? $status_level3 = "APPROVED" : $status_level3 = "REJECTED";
    }

    if ($model->status == "Not Confirmed") {
		if( (empty($modApprove->approved_by)) && (empty($modApprove->tanggal_approve)) ){
			if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )) {

				if ($model->level == 1 && count($checkApprovals) == 0) {
					//echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"approve(".$model->approval_id.",'approve')"]);
					//echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"reject(".$model->approval_id.",'reject')"]);
					echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
					echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
				}

				if ($model->level == 2 && count($checkApprovals) > 0) {
					if ($status_level1 == "REJECTED") {
						echo "<button class='btn btn-danger'>REJECTED already by approval level 1</button>";
					} else {
						//echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"approve(".$model->approval_id.",'approve')"]);
						//echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"reject(".$model->approval_id.",'reject')"]);
						echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
						echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);						
					}
                }

                //if (trim($model_t_op_ko->status) != "Low Price (2)") {
                    if ($model->level == 3 && count($checkApprovals) > 0) {
                        if ($status_level1 == "REJECTED") {
                            echo "<button class='btn btn-danger'>REJECTED already by approval level 1</button>";
                        } else {
                            //echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"approve(".$model->approval_id.",'approve')"]);
                            //echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"reject(".$model->approval_id.",'reject')"]);
                            echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
                            echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);						
                        }
                    }
                //} else {
                    //echo "xxx";
                //}
			}
		}
	} else {
        
        /*$sql_status_level1 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '1' ";
        $status_level1 = Yii::$app->db->createCommand($sql_status_level1)->queryScalar();
    
        $sql_status_level2 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '2' ";
        $status_level2 = Yii::$app->db->createCommand($sql_status_level2)->queryScalar();*/

        if (trim($model_t_op_ko->status) != "Low Price (2)") {
            $sql_status_level3 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '3' ";
            $status_level3 = Yii::$app->db->createCommand($sql_status_level3)->queryScalar();
        } else {
            $status_level3 = "APPROVED";
        }
    
        if ($status_level1 == "APPROVED" && $status_level2 == "APPROVED" && $status_level3 == "APPROVED") {
			$hasil_keputusan = "Data sudah disetujui pada tanggal ".app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve);
			$btn = "label-success";
		} else if ($status_level1 == "REJECTED" ) {
			$hasil_keputusan = "Data sudah ditolak pada tanggal ".app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve);
			$btn = "label-danger";
		} else {
			$hasil_keputusan = "";
			$btn = "";
		}
    }

	isset($hasil_keputusan) ? $hasil_keputusan = $hasil_keputusan : $hasil_keputusan = "" ;
	isset($btn) ? $btn=$btn : $btn="" ;
	
    if (trim($model_t_op_ko->status) == "Low Price (2)") {
        $pegawai_ids = array($approveLevel_1,$approveLevel_3);
        $col = 6;
    } else {      
        
        if($model_t_op_ko->tanggal <='2020-04-28'){
            $pegawai_ids = array($approveLevel_1);
            $col = 12;
        }elseif($model_t_op_ko->tanggal <='2020-09-30'){ // kondisikan mulai approval 3 level
            $pegawai_ids = array($approveLevel_1,$approveLevel_2);
            $col = 6;
        }else{            
            $pegawai_ids = array($approveLevel_1,$approveLevel_2,$approveLevel_3);
            $col = 4;
        }
    }

    ?>
    <br><br>
    <div class="col-md-12">
        <?php
        foreach ($pegawai_ids as $pegawai_id) {
            $pegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$pegawai_id]);
            $t_approval = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>$pegawai_id]);
            
            
            if ($t_approval->status == "APPROVED") {
                $color = "#38C68B";
                if($t_approval->tanggal_berkas <='2020-09-30'){
                    $reasonx = "";
                }else{
                    $reasons = json_decode($model_t_op_ko->approve_reason);
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
                    $reasons = json_decode($model_t_op_ko->reject_reason);
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
        <div class="col-md-<?php echo $col;?>" style="font-size: 1.2rem;">
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

<!--<script>
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
</script>-->