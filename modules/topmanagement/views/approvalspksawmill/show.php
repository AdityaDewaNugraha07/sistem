<?php
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TSpkSawmill::findOne(['kode'=>$model->reff_no]);
$modKayu = \app\models\MKayu::findOne($modReff->kayu_id);
   
$reason_approval = yii\helpers\Json::decode($modReff->approve_reason);
$reason_rejected = yii\helpers\Json::decode($modReff->reject_reason);

$dis = ($model->status == 'Not Confirmed') ? '' : 'disabled';
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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
			</div>
            <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Revisi Ke'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->refisi_ke ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Mulai'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal_mulai); ?></strong></div>
			</div>
            <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Selesai'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal_selesai); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Kayu'); ?></label>
				<div class="col-md-7"><strong><span style="color: red;"><?= $modKayu->kayu_nama ?></span></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Produk Sawmill'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->produk_sawmill ?></strong></div>
			</div>
        </div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode PO'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->pemenuhan_po; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Peruntukan'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->peruntukan; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Line Sawmill'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->line_sawmill ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan'); ?></label>
				<div class="col-md-7"><strong>
					<?= !empty($modReff->keterangan)?$modReff->keterangan:'-'; ?><br>
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
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
									<thead>
										<tr>
											<th style="text-align: center; width: 70%;"><?= Yii::t('app', 'PANJANG'); ?></th>
											<th colspan="2"><?= Yii::t('app', 'UKURAN'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$modDetails = \app\models\TSpkSawmillDetail::find()
														->select(['produk_t', 'produk_l', 'kategori_ukuran'])
														->where(['spk_sawmill_id' => $modReff->spk_sawmill_id])
														->groupBy(['produk_t', 'produk_l', 'kategori_ukuran'])
														->orderBy("kategori_ukuran desc")
														->all();
										// untuk count rowspan di kategori
										$rowspans = [];
										foreach($modDetails as $detail){
											$key = $detail->kategori_ukuran;
											if(!isset($rowspans[$key])){
												$rowspans[$key] = 0;
											}
											$rowspans[$key]++;
										}
										$kategoris = [];
										// eo count rowspan
										if(count($modDetails)>0){
											foreach($modDetails as $i => $detail){?>
												<tr>
													<td class="text-align-center td-kecil">
														<?php 
														$modPanjang = Yii::$app->db->createCommand("SELECT produk_p FROM t_spk_sawmill_detail 
																									WHERE spk_sawmill_id = $modReff->spk_sawmill_id AND produk_t = $detail->produk_t 
																									AND produk_l = $detail->produk_l AND kategori_ukuran = '$detail->kategori_ukuran' 
																								")->queryAll();
														$listPanjang = [];
														foreach ($modPanjang as $row) {
															$listPanjang[] = $row['produk_p'];
														}
														echo implode(', ', $listPanjang);
														?>
													</td>
													<td class="text-align-center td-kecil">
														<?= $detail->produk_t . 'x' . $detail->produk_l; ?>
													</td>
													<?php 
													if(!in_array($detail->kategori_ukuran, $kategoris)){
														$kategoris[] = $detail->kategori_ukuran;
														$count = $rowspans[$detail->kategori_ukuran];?>
														<td rowspan='<?= $count; ?>' style='text-align:center; vertical-align: middle;' class="td-kecil">
															<?= $detail->kategori_ukuran?>
														</td>
													<?php } ?>
												</tr>
										<?php
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
		$pegawai_ids = array($approver_1, $approver_2);
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