<?php
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TPiutangAlert::findOne(['piutang_nomor_nota'=>$model->reff_no]);
$modDetail = \app\models\TPiutangAlertDetail::find()->where(['piutang_alert_id'=>$modReff->piutang_alert_id])->orderBy(['piutang_alert_detail_id'=>SORT_ASC])->all();
$modCustTop = \app\models\MCustTop::findOne(['cust_id'=>$modReff->customer_id,'active'=>true]);

$sql = "select * from t_approval where reff_no = trim('".$modReff->piutang_nomor_nota."') AND level < ".$model->level." AND status != 'Not Confirmed'  AND parameter1 = 'PAL'";
$checkApprovals = Yii::$app->db->createCommand($sql)->queryAll();

$sql_status_level1 = "select status from t_approval where reff_no = trim('".$modReff->piutang_nomor_nota."') AND level = '1' ";
$status_level1 = Yii::$app->db->createCommand($sql_status_level1)->queryScalar();

//$sqApprovalPAL_1 = "select * from view_approval where reff_no = trim('".$modReff->piutang_nomor_nota."') AND level = '1' ";
//$ApprovalPAL_1 = Yii::$app->db->createCommand($sqApprovalPAL_1)->queryAll();

$ApprovalPAL_1 = \app\models\ViewApproval::find()->where(['reff_no' => $model->reff_no,'level' => '1','parameter1' => 'PAL'])->one();
$ApprovalPAL_2 = \app\models\ViewApproval::find()->where(['reff_no' => $model->reff_no,'level' => '2','parameter1' => 'PAL'])->one();

//echo "<pre>";
//print_r($ApprovalPAL_1);

//print_r($approval_id);
//echo"</pre>";
//exit;

//$sql_status_level2 = "select status from t_approval where reff_no = trim('".$modReff->piutang_nomor_nota."') AND level = '2' ";
//$status_level2 = Yii::$app->db->createCommand($sql_status_level2)->queryScalar();
//
//$status_level2 != "Not Confirmed" || $status_level2 != "Rejected" ? $status_level2 = "APPROVED" : $status_level2 = "REJECTED";


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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Nota Penjualan'); ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Nota'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tgl_nota); ?></strong></div>
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
						$sql_status_approval_level_1 = "select status from t_approval where reff_no = '".$model->reff_no."' and level = 1 and parameter1 = 'PAL'";
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
		</div>
		<div class="col-md-6">
                    <?php
                    if($modReff->piutang_jenis == 1){
                        $jenisPiutang = "Kayu Olahan";
                    }elseif($modReff->piutang_jenis == 2){
                        $jenisPiutang = "LOG";
                    }if($modReff->piutang_jenis == 3){
                        $jenisPiutang = "JASA";
                    }
                    ?>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk'); ?></label>
				<div class="col-md-7"><strong><?= $jenisPiutang ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Customer'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->cust->cust_an_nama ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Input Alert'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->created_at); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Total Piutang'); ?></label>
				<div class="col-md-7"><strong><?= "Rp. ". app\components\DeltaFormatter::formatNumberForUserFloat($modReff->tagihan_jml - $modReff->dp_terbayar ); ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tempo'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->tempo_bayar." Hari" ?></strong></div>
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
                                                    <div class="col-md-4">
                                                        <span style="font-weight: bold;  font-size: 1rem;">
                                                            Prepare by :<br>   
                                                        <?= \app\models\MPegawai::findOne($modReff->created_by)->pegawai_nama; ?>
                                                        <?= "<br>at ".\app\components\DeltaFormatter::formatDateTimeForUser2($modReff->created_at)." WIB";?>
                                                        </span>
                                                    </div>    
                                                    <div class="col-md-4">
                                                        <span style="font-weight: bold; font-size: 1rem;">
                                                        <?php
                                                        echo"Kadiv Marketing :";                                                        
                                                        echo"<br>$ApprovalPAL_1->approved_by_nama"; 
                                                        if($ApprovalPAL_1['status']==\app\models\TApproval::STATUS_APPROVED){
                                                                echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
                                                                                \app\components\DeltaFormatter::formatDateTimeForUser2($ApprovalPAL_1['updated_at'])." WIB</span>";
                                                        }else if($ApprovalPAL_1['status']==\app\models\TApproval::STATUS_REJECTED){
                                                                echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
                                                                                \app\components\DeltaFormatter::formatDateTimeForUser2($ApprovalPAL_1['updated_at'])." WIB</span>";
                                                        }else {
                                                                echo "<br>&nbsp; <i>(Not Confirm)</i>";
                                                        }
                                                        ?>
                                                        </span>
                                                        <?php
                                                        
                                                        if(!empty($modReff->approve_reason)){
                                                            
                                                            $modApproveReason = \yii\helpers\Json::decode($modReff->approve_reason);
                                                            foreach($modApproveReason as $iap => $aprreas){
                                                                if($aprreas['by'] == $modReff->disetujui){
                                                                    echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                                    echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                                    echo '</span>';
                                                                    
                                                                    
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span style="font-weight: bold; font-size: 1rem;">
                                                        <?php
                                                        echo"Kadiv HRD-GA";
                                                        echo"<br>$ApprovalPAL_2->approved_by_nama";                                                      
                                                        
                                                        if($ApprovalPAL_2['status']==\app\models\TApproval::STATUS_APPROVED){
                                                                echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
                                                                                \app\components\DeltaFormatter::formatDateTimeForUser2($ApprovalPAL_2['updated_at'])." WIB</span>";
                                                        }else if($ApprovalPAL_2['status']==\app\models\TApproval::STATUS_REJECTED){
                                                                echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
                                                                                \app\components\DeltaFormatter::formatDateTimeForUser2($ApprovalPAL_2['updated_at'])." WIB</span>";
                                                        }else {
                                                                echo "<br>&nbsp; <i>(Not Confirm)</i>";
                                                        }
                                                        ?>
                                                        </span>
                                                        <?php
                                                        if(!empty($modReff->approve_reason)){
                                                            $modApproveReason = \yii\helpers\Json::decode($modReff->approve_reason);
                                                            foreach($modApproveReason as $iap => $aprreas){
                                                                if($aprreas['by'] == $modReff->mengetahui){
                                                                    echo '<span style="font-weight: 500; font-size: 0.9rem;">';
                                                                    echo "<br>&nbsp; <span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                                    echo '</span>';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    
                                                    
							<div class="table-scrollable">                                                                
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
									<thead>
										<tr>
											<th style="width: 30px;">No.</th>
											<th style="text-align: center;"><?= Yii::t('app', 'Termin'); ?></th>
											<th style="width: 50px;"><?= Yii::t('app', 'Tagihan'); ?></th>
											<th style=""><?= Yii::t('app', 'Terbayar'); ?></th>
											<th style=""><?= Yii::t('app', 'Tempo'); ?></th>
                                                                                        <th style=""><?= Yii::t('app', 'Duedate'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
                                                                                $total_tagihan = 0;
                                                                                $total_terbayar = 0;
										$total = 0;
										$grandtotal = 0;
										if(count($modDetail)>0){
											foreach($modDetail as $i => $detail){
                                                                                                $subtotal_tagihan = $detail->termin_tagihan;
                                                                                                $subtotal_terbayar = $detail->termin_terbayar;
                                                                                                $subtotal = $detail->termin_tagihan - $detail->termin_terbayar;
                                                                                                $total_tagihan += $subtotal_tagihan;
                                                                                                $total_terbayar += $subtotal_terbayar;
                                                                                                
												$total += $subtotal;
												?>
												<tr>
													<td style="text-align: center;"><?= $i+1 ?></td>
													<td style=""><?= $detail->termin; ?></td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->termin_tagihan); ?></td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->termin_terbayar); ?></td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->termin_batas)." Hari"; ?></td>
                                                                                                        <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($detail->termin_duedate); ?></td>
												</tr>
										<?php
											}
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2" style="text-align: right;">TOTAL &nbsp; </td>
											<td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_tagihan);?></td>
                                                                                        <td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_terbayar);?></td>
                                                                                        
                                                                                        <td colspan="2"></td>
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
	
if ($model->status == "Not Confirmed") {
        if( (empty($modApprove->approved_by)) && (empty($modApprove->tanggal_approve)) ){
                if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )) {

                        if ($model->level == 1 && count($checkApprovals) == 0) {
                                echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
//					echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
                        }

                        if ($model->level == 2 && count($checkApprovals) > 0) {
                                if ($status_level1 == "REJECTED") {
                                        echo "<button class='btn btn-danger'>REJECTED already by approval level 1</button>";
                                } else {
                                        echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
//						echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);						
                                }
                        }
                        if ($model->level == 2 && count($checkApprovals) == 0) {
                                if ($status_level1 == "REJECTED") {
                                        echo "<button class='btn btn-danger'>REJECTED already by approval level 1</button>";
                                } else {
                                        echo '<span class="label label-danger">'
                                            . 'Kadiv Marketing belum melakukan <br>Persetujuan atas keterlambatan input Alert Piutang,'."<br>".''
                                                . 'mohon partisipasi bapak untuk membantu mengingatkan</span>';                                    
                                }

                        }

                }
        }
} else {

//		if ($status_level1 == "APPROVED" && $status_level2 == "APPROVED") {
//			$hasil_keputusan = "Data telah di setujui per tanggal ".app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve);
//			$btn = "label-success";		
//		} else {
//			$hasil_keputusan = "";
//			$btn = "";
//		}
}
?>
        
</div>