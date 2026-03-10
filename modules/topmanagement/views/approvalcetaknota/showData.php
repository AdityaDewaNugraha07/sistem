<?php
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TNotaPenjualan::findOne(['kode'=>$model->reff_no]);
	$tanggal_nota = $modReff->tanggal;
	$kode = $modReff->kode;
   
$model_t_op_ko = \app\models\TOpKo::findOne(['op_ko_id'=>$modReff->op_ko_id]);
    $approve_reason = yii\helpers\Json::decode($modReff->approve_reason);
    $reject_reason = yii\helpers\Json::decode($modReff->reject_reason);

$modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id'=>$modReff->nota_penjualan_id])->orderBy(['nota_penjualan_detail_id'=>SORT_DESC])->all();
$modTempo = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$modReff->op_ko_id]);
$modCustTop = \app\models\MCustTop::findOne(['cust_id'=>$modReff->cust_id,'custtop_jns'=>$modReff->jenis_produk,'active'=>true]);
$modTAttachments = \app\models\TAttachment::findAll(['reff_no'=>$model_t_op_ko->kode]);

$sql = "select * from t_approval where reff_no = trim('".$model->reff_no."') AND level < ".$model->level." AND status != 'Not Confirmed' ";
$checkApprovals = Yii::$app->db->createCommand($sql)->queryAll();

$sql_status_level1 = "select status from t_approval where reff_no = trim('".$model->reff_no."') AND level = '1' ";
$status_level1 = Yii::$app->db->createCommand($sql_status_level1)->queryScalar();

$ApprovalPAL_1 = \app\models\ViewApproval::find()->where(['reff_no' => $model->reff_no,'level' => '1'])->one();

//echo "<pre>";
//print_r($modTAttachments);
//echo"</pre>";
//exit;

?>
<style>
.form-group {
    margin-bottom: 0 !important;
}
</style>
<div class="modal-body" >
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-4">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Nota Penjualan'); ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Nota'); ?></label>
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
					if ($model->level == 1) {
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
		<div class="col-md-4">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->jenis_produk ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Customer'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->cust->cust_an_nama ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Nota Terbit'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->created_at); ?> WIB</strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Total Nota'); ?></label>
				<div class="col-md-7"><strong><?= "Rp. ". app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_bayar ); ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Sistem Bayar'); ?></label>
				<div class="col-md-7" style="line-height: 0.8; margin-bottom: 10px;"><strong>
					<?php
					if($modReff->sistem_bayar == "Tempo"){
						echo $modReff->sistem_bayar." - ".$modTempo->top_hari." Hari<br>";
						if(!empty($modCustTop)){
							if($modTempo->top_hari > $modCustTop->custtop_top){
								echo " &nbsp;&nbsp; <span style='font-size:1rem;' class='font-red-flamingo'><i>- Max Tempo : ".$modCustTop->custtop_top." Hari</i></span>";
							}
						}
					}else{
						echo "$modReff->sistem_bayar";
					}
					?>
					</strong>
                                </div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Cara Bayar'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->cara_bayar ?></strong></div>
			</div>
		</div>
                <div class="col-md-4">
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Syarat Jual'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->syarat_jual ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'No POL Armada'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kendaraan_nopol ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Sopir'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kendaraan_supir ?></strong></div>
			</div>
                        <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Alamat Bongkar'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->alamat_bongkar ?></strong></div>
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
                                                                    <?php if($modReff->jenis_produk == "Log"){ ?>
                                                                        <th style="width: 50px;"><?= Yii::t('app', '-'); ?></th>
                                                                    <?php } else { ?>
                                                                        <th style="width: 50px;"><?= Yii::t('app', 'Palet'); ?></th>
                                                                    <?php } ?>
                                                                    <th style=""><?= Yii::t('app', 'Qty'); ?></th>
                                                                    <th style=""><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
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

                                                                    if($modReff->jenis_produk == "Plywood" || $modReff->jenis_produk == "Lamineboard" || $modReff->jenis_produk == "Platform" || $modReff->jenis_produk == "FingerJointLamineBoard" || $modReff->jenis_produk == "FingerJointStick" || $modReff->jenis_produk == "Flooring"){
                                                                            $subtotal = $detail->harga_jual * $detail->qty_kecil;
                                                                    }elseif($modReff->jenis_produk == "Limbah"){
                                                                            $subtotal = $detail->harga_jual * $detail->qty_kecil;
                                                                    }else{
                                                                            $subtotal = $detail->harga_jual * $detail->kubikasi;                                                                            
                                                                    }

//                                                                    $harga_enduser > $detail->harga_jual ? $low_price = 'font-red-flamingo font-weight-bold' : $low_price = '';

                                                                    $total += $subtotal;                                                

                                                                    if ($modReff->jenis_produk == "JasaKD" || $modReff->jenis_produk == "JasaGesek" || $modReff->jenis_produk == "JasaMoulding" ) {
                                                                            $sql_produk_nama = "select nama from m_produk_jasa where produk_jasa_id = '".$produk_id."' ";
                                                                            $produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
//                                                                            $harga_enduser = 0;
                                                                    } else if ($modReff->jenis_produk == "Limbah") {
                                                                            //PPC - (Limbah) Limbah
                                                                            $sql_produk_nama = "select concat(limbah_kode,' - (',limbah_produk_jenis,') ',limbah_nama) from m_brg_limbah where limbah_id = '".$produk_id."' ";
                                                                        $produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
//                                                                        $harga_enduser = 0;
                                                                    } else if ($modReff->jenis_produk == "Log") {
                                                                        $sql_produk_nama = "select concat(log_kode,' - ',log_nama) from m_brg_log where log_id = '".$produk_id."' ";
                                                                        $produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
                                                                    } else {
                                                                        $produk_nama = $detail->produk->produk_nama;
                                                                    }

                                                            ?>

                                                                    <tr>
                                                                        <td style="text-align: center;"><?= $i+1; ?></td>
                                                                        <td style=""><?= $produk_nama; ?></td>
                                                                        <td style="text-align: right;">
                                                                            <?php if($modReff->jenis_produk == "Log"){ } else { ?>
                                                                            <?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_besar); ?>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil)." (".$detail->satuan_kecil.")"; ?></td>
                                                                        <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->kubikasi); ?></td>
                                                                        <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga_jual); ?></td>
                                                                        <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($subtotal); ?></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                    </tbody>
                                                    <tfoot>
                                                            <tr>
                                                                    <td colspan="6" style="text-align: right;">Total Harga &nbsp; </td>
                                                                    <td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total);?></td>
                                                            </tr>
                                                            <tr>
                                                                    <td colspan="6" style="text-align: right;">Total Potongan &nbsp; </td>
                                                                    <td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_potongan);?></td>
                                                            </tr>
                                                            <tr>
                                                                    <td colspan="6" style="text-align: right;">Total Bayar &nbsp; </td>
                                                                    <td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_bayar);?></td>
                                                            </tr>
                                                    </tfoot>
                                            </table>
                                    </div>
                            </div>
                        </div>  
                    </div>
                </div>
                    
            </div>
            <?php
            if(!empty($modTAttachments)){
//                echo"<pre>";
//                print_r('ada');
//                echo"</pre>";                
            ?>
                <div class="form-group col-md-6">
                    <label class="col-md-5 control-label">PO Customer</label>
                    <div class="col-md-7">
                        <div class="row">
                        <?php
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
                                                    <a class="btn btn-xs blue-hoki btn-outline tooltips" href="'.$full_path_file_name.'"><i class="fa fa-arrow-circle-down fa-2x" aria-hidden="true" style="padding: 5px;"></i></a>
                                            </div>';																		
                                }
                        }
                        ?>
                        </div>	
                    </div>
                </div>
            <?php    
            }else{
//                echo"<pre>";
//                print_r('kosong');
//                echo"</pre>";
            }
            ?>
                <div class="form-group col-md-6">
                    <div class="col-md-12">
                        <?php
                            if($modReff->status_approval != 'Not Confirmed'){
                                $approves = \yii\helpers\Json::decode($modReff->approve_reason);
                                $rejects = \yii\helpers\Json::decode($modReff->reject_reason);

                                if(count($approves)>0){
                                    foreach($approves as $i => $approve){
                                        $sql_approval = "select m_pegawai.pegawai_nama from m_pegawai join t_nota_penjualan on m_pegawai.pegawai_id = t_nota_penjualan.control_by where kode = '".$modReff->kode."'";

                                        $approval = Yii::$app->db->createCommand($sql_approval)->queryScalar();
                                        echo \yii\helpers\Html::button( Yii::t('app', $model->status.'<br>by : '.$approval.'<br>reason : '.$approve['reason'].'<br>tanggal : '.\app\components\DeltaFormatter::formatDateTimeForUser2($approve['at']).' WIB'),['class'=>'btn green btn-outline ciptana-spin-btn pull-left text-left', 'style' => 'text-align: left; font-size: 1.1rem; margin-right: 10px;']);
                                    }
                                }

                                if(count($rejects)>0){
                                    foreach($rejects as $i => $reject){
                                        $sql_approval = "select m_pegawai.pegawai_nama from m_pegawai join t_nota_penjualan on m_pegawai.pegawai_id = t_nota_penjualan.control_by where kode = '".$modReff->kode."'";

                                        $approval = Yii::$app->db->createCommand($sql_approval)->queryScalar();
                                        echo \yii\helpers\Html::button( Yii::t('app', $model->status.'<br>by : '.$approval.'<br>reason : '.$reject['reason'].'<br>tanggal : '.\app\components\DeltaFormatter::formatDateTimeForUser2($reject['at']).' WIB'),['class'=>'btn red btn-outline ciptana-spin-btn pull-left text-left', 'style' => 'text-align: left; font-size: 1.1rem; margin-left: 10px;']);
                                    }
                                }
                            }

                        ?>
                        
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
                                if($modReff->total_potongan > 0){
                                //tampilkan approval diskon penjualan
                                echo '<span class="label label-danger">'
                                            . 'Maaf anda belum bisa melakukan Persetujuan Cetak Nota '."<br>".''
                                            . 'Dikarenakan Persetujuan Diskon Penjualan Belum Disetujui (APPROVED) '."<br>".''
                                            . 'mohon partisipasi bapak untuk membantu mengingatkan admin marketing untuk melakukan followup ke pihak terkait persetujuan Diskon</span>'; 
                            }else{
                                echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
                                echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]);
                            }
                        }

//                        if ($model->level == 2 && count($checkApprovals) > 0) {
//                                if ($status_level1 == "REJECTED") {
//                                        echo "<button class='btn btn-danger'>REJECTED already by approval level 1</button>";
//                                } else {
//                                        echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]);
//										
//                                }
//                        }
//                        if ($model->level == 2 && count($checkApprovals) == 0) {
//                                if ($status_level1 == "REJECTED") {
//                                        echo "<button class='btn btn-danger'>REJECTED already by approval level 1</button>";
//                                } else {
//                                        echo '<span class="label label-danger">'
//                                            . 'Kadiv Marketing belum melakukan <br>Persetujuan atas keterlambatan input Alert Piutang,'."<br>".''
//                                                . 'mohon partisipasi bapak untuk membantu mengingatkan</span>';                                    
//                                }
//
//                        }

                }
        }
} else {


}
?>
        
</div>

<script>
function image(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalcetaknota/image','id'=>'']) ?>'+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-image").modal('show');
		$("#modal-image").on('hidden.bs.modal', function () { });
		$("#modal-image .modal-dialog").css('width',"1000px");
		spinbtn();
		draggableModal();
	});
}
</script>