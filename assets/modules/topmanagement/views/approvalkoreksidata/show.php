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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kode ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tipe'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->tipe ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal) ?></strong></div>
			</div>
            <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Reff No '); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kode ?></strong></div>
			</div>
			
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label">Alasan</label>
				<div class="col-md-7"><strong><?= $modReff->reason; ?></strong></div>
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
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['level'] ?></label>
				<div class="col-md-7"><strong><?= $model->level; ?></strong></div>
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
                        $reasons = \yii\helpers\Json::decode($model->keterangan);
						$approver1 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->approver1)->queryOne();
						?>
						<br>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;">Approver 1</td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->approver1)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($approver1['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver1['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver1['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($approver1['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver1['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver1['assigned_to']){
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
							</table>
						</div>
                        <?php
                        if(!empty($modReff->approver2)){
                        $approver2 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->approver2)->queryOne();
                        ?>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;">Approver 2</td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->approver2)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($approver2['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver2['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver2['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($approver2['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver2['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver2['assigned_to']){
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
							</table>
						</div>
                        <?php } ?>
                        <?php
                        if(!empty($modReff->approver3)){
                        $approver3 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->approver3)->queryOne();
                        ?>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;">Approver 3</td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->approver3)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($approver3['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver3['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver3['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($approver3['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver3['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver3['assigned_to']){
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
							</table>
						</div>
                        <?php } ?>
                    </div>
                    <br>
                    
                    <?php 
                        if( $modReff->tipe == "KOREKSI HARGA JUAL" || $modReff->tipe == "KOREKSI NOPOL MOBIL" || $modReff->tipe == "POTONGAN PIUTANG" ){ 
                        $modNota = app\models\TNotaPenjualan::findOne(["kode"=>$modReff->reff_no]);
                        $modCust = \app\models\MCustomer::findOne($modNota['cust_id']);
                    ?>
                    <div class="row" > 
                        <div class="col-md-6" style="font-size: 1.1rem;">
                            <table style="width: 100%; font-size: 1.1rem;">
                                <tr>
                                    <td style="width: 30%; vertical-align: top; padding: 3px;">Kode / Tanggal Nota</td>
                                    <td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
                                        <?= $modNota['kode']." / ".\app\components\DeltaFormatter::formatDateTimeForUser2($modNota['tanggal']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; vertical-align: top; padding: 3px;">Nama Customer</td>
                                    <td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
                                        <?= $modCust->cust_an_nama ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; vertical-align: top; padding: 3px;">Alamat Bongkar</td>
                                    <td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
                                        <?= $modNota['alamat_bongkar'] ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6" style="font-size: 1.1rem;">
                            <table style="width: 100%; font-size: 1.1rem;">
                                <tr>
                                    <td style="width: 30%; vertical-align: top; padding: 3px;">Nopol Kendaraan</td>
                                    <td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
                                        <?= $modNota['kendaraan_nopol'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; vertical-align: top; padding: 3px;">Nama Supir</td>
                                    <td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
                                        <?= $modNota['kendaraan_supir'] ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if( $modReff->tipe == "KOREKSI HARGA JUAL" ){ ?>
                    <div class="row"  style="margin-top: -15px;">
                        <div class="col-md-12">
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                            <th style="" ><?= Yii::t('app', 'Produk'); ?></th>
                                            <th style="width: 80px;" ><?= Yii::t('app', 'Pcs'); ?></th>
                                            <th style="width: 100px;" ><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                                            <th style="width: 120px;" ><?= Yii::t('app', 'Harga Lama'); ?></th>
                                            <th style="width: 120px;" ><?= Yii::t('app', 'Harga Baru'); ?></th>
                                            <th style="width: 120px;" ><?= Yii::t('app', 'Subtotal'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $new_data = $modDetail['new']['t_nota_penjualan'];
                                        $new_data_detail = $modDetail['new']['t_nota_penjualan_detail'];
                                        $old_data_detail = $modDetail['old']['t_nota_penjualan_detail'];
                                        $totalharga = $new_data['total_harga']; $potongan = $new_data['total_potongan']; $total = $new_data['total_bayar'];
                                        foreach($new_data_detail as $i => $detail){
                                            $old_key = array_search($detail['nota_penjualan_detail_id'], array_column($old_data_detail, 'nota_penjualan_detail_id'));
                                            $modProduk = app\models\MBrgProduk::findOne($detail['produk_id']);
                                            if( $new_data['jenis_produk'] == "Plywood" || $new_data['jenis_produk'] == "Lamineboard" || $new_data['jenis_produk'] == "Platform" || $new_data['jenis_produk'] == "Limbah" ){
                                                $subtotal = $detail['qty_kecil'] * $detail['harga_jual'];
                                            }else{
                                                $subtotal = $detail['kubikasi'] * $detail['harga_jual'];
                                            }
                                            echo "<tr>";
                                            echo	"<td class='text-align-center'>".($i+1)."</td>";
                                            echo    "<td class='text-align-left'>". $modProduk->produk_nama." M<sup>3</sup></td>";
                                            echo    "<td class='text-align-right'>". number_format( $detail['qty_kecil'] )."</td>";
                                            echo    "<td class='text-align-right'>". number_format( $detail['kubikasi'],4 )."</td>";
                                            echo    "<td class='text-align-right font-red-flamingo'>". number_format( $old_data_detail[$old_key]['harga_jual'] )."</td>";
                                            echo    "<td class='text-align-right font-green-seagreen'>". number_format( $detail['harga_jual'] )."</td>";
                                            echo    "<td class='text-align-right'>". number_format( $subtotal )."</td>";
                                            echo "</tr>";
                                            $totalharga += $subtotal;
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" style="text-align: right;">Total Harga </td>
                                            <?php 
                                            echo "<td class='text-align-right'>".number_format( $new_data['total_harga'] )."</td>";
                                            ?>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="text-align: right;">Potongan </td>
                                            <?php 
                                            echo "<td class='text-align-right'>".number_format( $new_data['total_potongan'] )."</td>";
                                            ?>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="text-align: right;">Total Bayar </td>
                                            <?php 
                                            echo "<td class='text-align-right'>".number_format( $new_data['total_bayar'] )."</td>";
                                            ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php }else if( $modReff->tipe == "KOREKSI NOPOL MOBIL" ){
                    ?>
                        <br><br>
                        <div class="row"  style="margin-top: -15px;">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <table style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Nopol Lama</th>
                                            <td class="font-red-flamingo"><u><?= $modDetail['old']; ?></u></td>
                                            <td>==></td>
                                            <th>Nopol Baru</th>
                                            <td class="font-green-seagreen"><u><?= $modDetail['new']; ?></u></td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    <?php }else if( $modReff->tipe == "POTONGAN PIUTANG" ){ ?>
                        <br><br>
                        <table class="table table-striped table-bordered table-advance table-hover" id="table-koreksi">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Data Piutang Customer : <u id=""><?= $modNota->cust->cust_an_nama ?></u></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $datadetail1 = yii\helpers\Json::decode($modReff->datadetail1)['new']['t_piutang_penjualan'];
                                $sql = "SELECT * FROM t_piutang_penjualan WHERE bill_reff = '".$modNota->kode."' AND cancel_transaksi_id IS NULL";
                                $modPiutangs = Yii::$app->db->createCommand($sql)->queryAll();
                                $terbayar = 0;
                                if(count($modPiutangs)>0){
                                    foreach($modPiutangs as $ii => $piutang){
                                        $terbayar += $piutang['bayar'];
                                    }
                                }
                                echo "<tr>";
                                echo "  <td style='text-align:right;'>Bill Reff</td>";
                                echo "  <td style='text-align:right;'>".$datadetail1['bill_reff']."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "  <td style='text-align:right;'>Nominal Bill</td>";
                                echo "  <td style='text-align:right;'>". number_format($modNota->total_bayar)."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "  <td style='text-align:right;'>Pernah Terbayar</td>";
                                echo "  <td style='text-align:right;'>".number_format($terbayar)."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "  <td style='text-align:right;'>Sisa Tagihan</td>";
                                echo "  <td style='text-align:right;'>".number_format($datadetail1['tagihan'])."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "  <td style='text-align:right;'>Potongan</td>";
                                echo "  <td style='text-align:right;' class='font-red-flamingo'>".number_format($datadetail1['bayar'])."</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "  <td style='text-align:right;'>Sisa Piutang</td>";
                                echo "  <td style='text-align:right;' class='font-green-seagreen'>".number_format($datadetail1['sisa'])."</td>";
                                echo "</tr>";
                                ?>
                            </tbody>
                        </table>
                    <?php }else if( $modReff->tipe == "KOREKSI PIUTANG LOG & JASA" ){ ?>
                    <?php
                    $modAlert = app\models\TPiutangAlert::findOne(['piutang_nomor_nota'=>$modReff->reff_no]);
                    $modCustomer = app\models\MCustomer::findOne($modAlert->customer_id);
                    $sql = "SELECT *, (termin_tagihan - termin_terbayar) AS sisa_bayar 
                            FROM t_piutang_alert_detail 
                            WHERE piutang_alert_id = {$modAlert->piutang_alert_id} ";
                    $modDetail = Yii::$app->db->createCommand($sql)->queryAll();
                    ?>
                    <br><br>
                    <div class="row"  style="margin-top: -15px;">
                        <div class="col-md-12">
                            <div class="col-md-6" style="padding-bottom: 5px;">
                                <table style="width: 100%">
                                    <tr>
                                        <td style="width: 100px; vertical-align: top;"><b>Customer</b></td>
                                        <td style="width: 30px; vertical-align: top;"><b>:</b></td>
                                        <td><?= $modCustomer->cust_an_nama ?></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><b>Alamat</b></td>
                                        <td style="vertical-align: top;"><b>:</b></td>
                                        <td><?= $modCustomer->cust_an_alamat ?></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><b>No. NPWP</b></td>
                                        <td style="vertical-align: top;"><b>:</b></td>
                                        <td><?= !empty($modCustomer->cust_no_npwp)? 
                                                substr($modCustomer->cust_no_npwp,0,2).".".
                                                substr($modCustomer->cust_no_npwp,3,3).".".
                                                substr($modCustomer->cust_no_npwp,6,3).".".
                                                substr($modCustomer->cust_no_npwp,9,1)."-". 
                                                substr($modCustomer->cust_no_npwp,10,3).".". 
                                                substr($modCustomer->cust_no_npwp,13,3)
                                                :"-"
                                            ?></td>
                                        <!--99.999.999.9-999.999-->
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6" style="padding-bottom: 5px;">
                                <table style="width: 100%">
                                    <tr>
                                        <td style="width: 100px; vertical-align: top;"><b>Tanggal Nota</b></td>
                                        <td style="width: 30px; vertical-align: top;"><b>:</b></td>
                                        <td><?= app\components\DeltaFormatter::formatDateTimeForUser2($modAlert->tgl_nota) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><b>Tempo Bayar</b></td>
                                        <td style="vertical-align: top;"><b>:</b></td>
                                        <td><?= $modAlert->tempo_bayar." Hari" ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-12" style="padding-bottom: 5px;">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-bordered table-advance table-hover" id="table-koreksi">
                                        <thead>
                                            <tr>
                                                <th>Termin</th>
                                                <th>Tagihan</th>
                                                <th>Terbayar</th>
                                                <th>Sisa Piutang Lama</th>
                                                <th>Potongan</th>
                                                <th>Sisa Piutang Baru</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(count($modDetail)>0){
                                                if(!empty($modReff->pengajuan_manipulasi_id)){
                                                    $datadetail = yii\helpers\Json::decode($modReff->datadetail1); $hargabaru = 0;
                                                    $modAlert->potongan = $datadetail['new']['t_piutang_alert']['potongan'];
                                                    $modAlert->sisa_bayar_baru = $datadetail['new']['t_piutang_alert']['sisa_bayar_baru'];
                                                }
                                                $termin_terbayar = 0; $sisa_bayar=0;
                                                foreach($modDetail as $i => $detail){
                                                    $modPiutangDetail = new app\models\TPiutangAlertDetail();
                                                    $modPiutangDetail->piutang_alert_detail_id = $detail['piutang_alert_detail_id'];
                                                    $modPiutangDetail->sisa_bayar = $detail['sisa_bayar'];
                                                    $modPiutangDetail->potongan = 0;
                                                    $modPiutangDetail->sisa_bayar_baru = $modPiutangDetail->sisa_bayar - $modPiutangDetail->potongan;
                                                    $termin_terbayar = $detail['termin_terbayar'];
                                                    $sisa_bayar = $detail['sisa_bayar'];
                                                    
                                                    
                                                    if(!empty($modReff->pengajuan_manipulasi_id)){
                                                        foreach($datadetail['new']['t_piutang_alert_detail'] as $ii => $detttt){
                                                            if($detttt['piutang_alert_detail_id'] == $modPiutangDetail->piutang_alert_detail_id){
                                                                $modPiutangDetail->potongan = $detttt['potongan'];
                                                                $modPiutangDetail->sisa_bayar_baru = $detttt['sisa_bayar_baru'];
                                                            }
                                                        }
                                                    }
                                                    
                                                    echo "<tr class='tr-isi'>";
                                                    echo yii\helpers\Html::activeHiddenInput($modPiutangDetail, "[ii]piutang_alert_detail_id");
                                                    echo yii\helpers\Html::activeHiddenInput($modPiutangDetail, "[ii]sisa_bayar");
                                                    echo "  <td style='text-align:center; vertical-align: middle;'>".$detail['termin']."</td>";
                                                    echo "  <td style='text-align:right; vertical-align: middle;'>". number_format($detail['termin_tagihan'])."</td>";
                                                    echo "  <td style='text-align:right; vertical-align: middle;'>".number_format($detail['termin_terbayar'])."</td>";
                                                    echo "  <td style='text-align:right; vertical-align: middle;'>".number_format($detail['sisa_bayar'])."</td>";
                                                    echo "  <td style='text-align:right; vertical-align: middle;' class='font-red-flamingo'>".number_format($modPiutangDetail->potongan)."</td>";
                                                    echo "  <td style='text-align:right; vertical-align: middle;' class='font-green-seagreen'>".number_format($modPiutangDetail->sisa_bayar_baru)."</td>";
                                                    echo "</tr>";
                                                }
                                                echo "<tr style='background-color:#f1f4f7'>";
                                                echo "  <td style='text-align:right; vertical-align: middle;'><b>TOTAL</b></td>";
                                                echo "  <td style='text-align:right; vertical-align: middle;'><b>". number_format($modAlert->tagihan_jml)."</b></td>";
                                                echo "  <td style='text-align:right; vertical-align: middle;'><b>".number_format($termin_terbayar)."</b></td>";
                                                echo "  <td style='text-align:right; vertical-align: middle;'><b>".number_format($sisa_bayar)."</b></td>";
                                                echo "  <td style='text-align:right; vertical-align: middle;' class='font-red-flamingo'><b>".number_format($modAlert->potongan)."</b></td>";
                                                echo "  <td style='text-align:right; vertical-align: middle;' class='font-green-seagreen'><b>".number_format($modAlert->sisa_bayar_baru)."</b></td>";
                                                echo "</tr>";
                                            }

                                            ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
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
<script>

</script>