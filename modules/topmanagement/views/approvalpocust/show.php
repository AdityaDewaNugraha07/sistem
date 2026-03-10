<?php

use app\models\MBrgLog;
use app\models\TPoKo;
use app\models\TPoKoDetail;
use yii\bootstrap\Html;

$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TPoKo::findOne(['kode'=>$model->reff_no]);
   
$reason_approval = yii\helpers\Json::decode($modReff->approve_reason);
$reason_rejected = yii\helpers\Json::decode($modReff->reject_reason);

$dis = ($model->status == 'Not Confirmed') ? '' : 'disabled';

$modCustTop = \app\models\MCustTop::findOne(['cust_id'=>$modReff->cust_id,'custtop_jns'=>$modReff->jenis_produk,'active'=>true]);
$data_piutang = \yii\helpers\Json::decode($modReff->data_piutang);
$maks_plafon= \app\components\DeltaFormatter::formatNumberForUserFloat($data_piutang[0]['maks_plafon']);
$sisa_piutang= \app\components\DeltaFormatter::formatNumberForUserFloat($data_piutang[0]['piutang_aktif']);
$op_aktif= \app\components\DeltaFormatter::formatNumberForUserFloat($data_piutang[0]['op_aktif']);
$is_negative = strpos((string)$data_piutang[0]['sisa_plafon'], '-') === 0;
$positive_value = trim((string)$data_piutang[0]['sisa_plafon'], '-');
$sisa_plafon = \app\components\DeltaFormatter::formatNumberForUserFloat($positive_value);
$sisa_plafon = $is_negative ? '-' . $sisa_plafon : $sisa_plafon;
// $sisa_plafon = \app\components\DeltaFormatter::formatNumberForUserFloat($data_piutang[0]['sisa_plafon']);
?>

<style>
.form-group {
    margin-bottom: 0 !important;
}

.custom-checkbox {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  width: 16px;
  height: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #f5f5f5;
  cursor: not-allowed;
  position: relative;
}

.custom-checkbox:checked {
  background-color: #4CAF50;
  border-color: #4CAF50;
}

.custom-checkbox:checked::after {
  content: '✔';
  color: white;
  font-size: 10px;
  position: absolute;
  top: 0px;
  left: 3px;
}
</style>

<div class="modal-body" >
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode PO'); ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal PO'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal_po); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->jenis_produk; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Syarat Jual'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->syarat_jual; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Kirim'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal_kirim); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan'); ?></label>
				<div class="col-md-7"><strong>
					<?= !empty($modReff->keterangan)?$modReff->keterangan:'-'; ?><br>
				</strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Customer'); ?></label>
				<div class="col-md-7"><strong>
					<?= !empty($modReff->cust->cust_pr_nama)?$modReff->cust->cust_pr_nama:$modReff->cust->cust_an_nama; ?><br>
					<?= !empty($modReff->cust->cust_pr_alamat)?$modReff->cust->cust_pr_alamat:$modReff->cust->cust_an_alamat; ?>
				</strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label">Gambar/Image PO</label>
				<div class="col-md-7">
					<div class="row">
					<?php 
					$modTAttachments = \app\models\TAttachment::findAll(['reff_no'=>$modReff->kode,'active'=>true]);
					foreach ($modTAttachments as $modTAttachment) {
						$attachment_id = $modTAttachment->attachment_id;
						$file_name = $modTAttachment->file_name;
						$file_ext = $modTAttachment->file_ext;
						$seq = $modTAttachment->seq;
						
						$full_path_file_name = Yii::$app->homeUrl.'/uploads/mkt/purchaseorder/'.$file_name;			
						if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "bmp" || $file_ext == "png" || $file_ext == "giff" || $file_ext == "tiff") {
							echo '<div class="col-md-2" style="width: 50px;">
									<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="showFile('.$attachment_id.')">
										<img src="'.$full_path_file_name.'" alt="'.$full_path_file_name.'" style="width: 20px;" />
									</a>
								</div>';
						} else {
							echo '<div class="col-md-2" style="width: 50px;">
									<a class="btn btn-xs blue-hoki btn-outline tooltips" href="javascript:void(0)" onclick="showFile('.$attachment_id.')">
										<i class="fa fa-arrow-circle-down fa-2x" aria-hidden="true" style="padding: 5px;"></i>
									</a>
								</div>';
							// echo '<div class="col-md-2" style="width: 50px;">
							// 		<a class="btn btn-xs blue-hoki btn-outline tooltips" href="'.$full_path_file_name.'"><i class="fa fa-arrow-circle-down fa-2x" aria-hidden="true" style="padding: 5px;"></i></a>
							// 	</div>';																		
						}
					}
					?>
					</div>	
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Alamat Bongkar'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->alamat_bongkar; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Sistem - Cara Bayar'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->sistem_bayar; ?><?= $modReff->sistem_bayar == 'Tempo'?' ('. $modReff->top_hari .' Hari)':''; ?>  - <?= $modReff->cara_bayar; ?></strong></div>
			</div>
			<?php if($modReff->sistem_bayar == 'Tempo'){ 
				if($modReff->data_piutang){?>
				<div class="form-group col-md-12">
					<label class="col-md-5 control-label"><?= Yii::t('app', 'Maks Plafon'); ?></label>
					<div class="col-md-7"><strong><?= $maks_plafon ?></strong></div>
				</div>
				<div class="form-group col-md-12">
					<label class="col-md-5 control-label"><?= Yii::t('app', 'Piutang Aktif'); ?></label>
					<div class="col-md-7"><strong><?= $sisa_piutang ?></strong></div>
				</div>
				<div class="form-group col-md-12">
					<label class="col-md-5 control-label"><?= Yii::t('app', 'OP Aktif'); ?></label>
					<div class="col-md-7"><strong><?= $op_aktif ?></strong></div>
				</div>
				<div class="form-group col-md-12">
					<label class="col-md-5 control-label"><?= Yii::t('app', 'Sisa Plafon'); ?></label>
					<div class="col-md-7"><strong><?= $sisa_plafon ?></strong></div>
				</div>
				<?php } ?>
				<div class="form-group col-md-12">
					<label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan Bayar'); ?></label>
					<div class="col-md-7"><strong><?= !empty($modReff->keterangan_bayar)?$modReff->keterangan_bayar:'-'; ?></strong></div>
				</div>
				<div class="form-group col-md-12">
					<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Maks Bayar'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal_bayarmax); ?></strong></div>
			</div>
			<?php } ?>
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
					<div class="row">
						<div class="col-md-12">
							<span style="color: red;">
								**Jika produk alias <b>tercentang</b> maka nama produk alias akan diteruskan dari <b>PO sampai INVOICE</b>.
								Jika produk alias <b>tidak tercentang</b> maka nama produk alias hanya akan tampil di <b>PO saja</b>.
							</span>
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
									<thead>
										<tr>
											<th style="width: 30px;">No.</th>
											<th style="text-align: center; "><?= Yii::t('app', 'Produk Alias'); ?></th>
											<th style=""><?= Yii::t('app', 'Diameter Alias'); ?></th>
											<th style=""><?= Yii::t('app', 'Produk'); ?></th>
											<th style=""><?= Yii::t('app', 'Komposisi'); ?></th>
											<th style=""><?= Yii::t('app', 'FSC<br>100%'); ?></th>
											<th style=""><?= Yii::t('app', 'Volume'); ?></th>
											<th style=""><?= Yii::t('app', 'Harga'); ?></th>
											<th style=""><?= Yii::t('app', 'Subtotal'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$modDetails = TPoKoDetail::find()->join('JOIN', 't_po_ko', 't_po_ko.po_ko_id = t_po_ko_detail.po_ko_id')
																				->where("t_po_ko.po_ko_id = $modReff->po_ko_id")
																				->orderBy('po_ko_detail_id')->all();
										$jml_komposisi = 0; $jml_kubikasi = 0; $total_harga = 0;
										if(count($modDetails)>0){
											foreach($modDetails as $i => $detail){
												$jml_komposisi += $detail['komposisi'];
												$jml_kubikasi += $detail['kubikasi'];
												$subtotal = $detail['kubikasi'] * $detail['harga'];
												$total_harga += $subtotal;
											?>
												<tr>
													<td style="text-align: center;"><?= $i+1; ?></td>
													<td style="text-align: left;">
														<span><?= $detail['produk_alias']; ?></span>
														<span style="float: right;">
															<input type="checkbox" name="alias" class="custom-checkbox" <?= $detail['alias']?'checked':''; ?> disabled/>
														</span>
													</td>
													<td style="text-align: center;"><?= $detail['diameter_alias']; ?></td>
													<td>
														<?php 
														if(!$detail['produk_id']){
															$produk_ids = explode(',', $detail['produk_id_alias']);
															$log_namas = [];
															foreach($produk_ids as $p => $log_id){
																$modLog = app\models\MBrgLog::findOne($log_id);
																$log_namas[] = $modLog->log_nama;
															}
															echo implode('<br>', $log_namas);
														} else {
															$modLog = app\models\MBrgLog::findOne($detail['produk_id']);
															echo $modLog->log_nama;
														}
														?>
													</td>
													<td style="text-align: center;"><?= $detail['komposisi'] .' %'; ?></td>
													<td style="text-align: center;">
														<input type="checkbox" class="custom-checkbox" <?= strpos($modLog->log_nama, 'FSC100%') !== false ? 'checked' : ''; ?> disabled />
													</td>
													<td style="text-align: right;"><?= $detail['kubikasi'] . ' m<sup>3</sup>'; ?></td>
													<td style="text-align: right; padding-right: 5px;">
														<?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail['harga']); ?>
													</td>
													<td style="text-align: right; padding-right: 5px;">
														<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($subtotal); ?>
													</td>
												</tr>
										<?php
											}
										}
										?>
										<tr>
											<td colspan="4" style="text-align: right;"><b>TOTAL &nbsp;</b></td>
											<td style="text-align: center;"><b><?= $jml_komposisi; ?>%</b></td>
											<td></td>
											<td style="text-align: right;"><b><?= $jml_kubikasi; ?> m<sup>3</sup></b></td>
											<td></td>
											<td style="text-align: right;"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_harga); ?></b></td>
										</tr>
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
	<?php

	$sql = "select * from t_approval where reff_no = trim('".$modReff->kode."') AND level < ".$model->level." AND status != 'Not Confirmed' ";
	$checkApprovals = Yii::$app->db->createCommand($sql)->queryAll();

	$sql_status_level1 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '1' ";
	$status_level1 = Yii::$app->db->createCommand($sql_status_level1)->queryScalar();

	$sql_status_level2 = "select status from t_approval where reff_no = trim('".$modReff->kode."') AND level = '2' ";
	$status_level2 = Yii::$app->db->createCommand($sql_status_level2)->queryScalar();
	$status_level2 != "Not Confirmed" || $status_level2 != "Rejected" ? $status_level2 = "APPROVED" : $status_level2 = "REJECTED";

    if ($model->status == "Not Confirmed") {
		if( (empty($modApprove->approved_by)) && (empty($modApprove->tanggal_approve)) ){
			if(( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_OWNER )) {

				if ($model->level == 1 && count($checkApprovals) == 0) {
					echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
					echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
				}

				if ($model->level == 2 && count($checkApprovals) > 0) {
					if ($status_level1 == "REJECTED") {
						echo "<button class='btn btn-danger'>REJECTED already by approval level 1</button>";
					} else {
						echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
						echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);						
					}
                }
			}
		}
	} else {
        if ($status_level1 == "APPROVED" && $status_level2 == "APPROVED") {
			$hasil_keputusan = "Data sudah disetujui pada tanggal ".app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve);
			$btn = "label-success";
		} else if ($status_level1 == "REJECTED" || $status_level2 == "REJECTED") {
			$hasil_keputusan = "Data sudah ditolak pada tanggal ".app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve);
			$btn = "label-danger";
		} else {
			$hasil_keputusan = "";
			$btn = "";
		}
    }

	isset($hasil_keputusan) ? $hasil_keputusan = $hasil_keputusan : $hasil_keputusan = "" ;
	isset($btn) ? $btn=$btn : $btn="" ;

    ?>
    <br><br>
    <div class="col-md-12">
        <?php
		$lvl_1 = \app\models\TApproval::find()->select(['assigned_to'])->where(['reff_no'=>$model->reff_no, 'level'=>1])->one();
		$approver_1 = $lvl_1->assigned_to;
		$lvl_2 = \app\models\TApproval::find()->select(['assigned_to'])->where(['reff_no'=>$model->reff_no, 'level'=>2])->one();
		$approver_2 = $lvl_2->assigned_to;
		$pegawai_ids = array($approver_1, $approver_2); //kadiv mkt, direktur
        foreach ($pegawai_ids as $pegawai_id) {
            $pegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$pegawai_id]);
            $t_approval = \app\models\TApproval::findOne(['reff_no'=>$model->reff_no, 'assigned_to'=>$pegawai_id]);
            
            if ($t_approval->status == "APPROVED") {
                $color = "#38C68B";
                $reasons = json_decode($modReff->approve_reason);
                foreach($reasons as $reason) {
                    if ($pegawai_id == $reason->by) {
                        $reasonx = $reason->reason;
                    }
                }
            } 

            if ($t_approval->status == "REJECTED") {
                $color = "#f00";
                $reasons = json_decode($modReff->reject_reason);
                foreach($reasons as $reason) {
                    if ($pegawai_id == $reason->by) {
                        $reasony = $reason->reason;
                    }
                }
            }

            isset($reasonx) ? $reasonx = $reasonx : $reasonx = "";
            isset($reasony) ? $reasony = $reasony : $reasony = "";            

        ?>
        <div class="col-md-6" style="font-size: 1.2rem;">
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
                } else if($t_approval->status == "Not Confirmed") {
                    echo '';
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
<script>
</script>