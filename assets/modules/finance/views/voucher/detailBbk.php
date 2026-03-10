<?php app\assets\DatatableAsset::register($this); ?>
<?php
$kode = $model->kode;
?>
<style>
table{
	font-size: 1.4rem;
}
</style>
<div class="modal fade" id="modal-bbk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Voucher Pengeluaran / BBK'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<table style="width: 19cm; margin: 10px; height: auto;" border="1">
							<tr style="height: 2cm;">
								<td colspan="3">
									<table style="width: 100%; " border="0">
										<tr style="">
											<td style="text-align: left; vertical-align: middle; padding: 3px; width: 4cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
												<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 75px;"> 	
											</td>
											<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
												<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul']; ?></u></span><br>

												<?php
												if(!empty($model->akun_debit)){
													echo "<span>".substr( \app\models\MAcctRekening::getByPk($model->akun_debit)->acct_nm, -3,3 )."</span>";
												}

												if( substr($model->kode, 0,3) == "BBK" ){
													$kode = $model->kode;
												}else{
													$kode = \app\components\DeltaGenerator::kodeBuktiBankKeluar($model->akun_debit,$model->tanggal_bayar);
												}

												if(!empty($model->suplier_id)){
													$receiver = "<span style='font-size:1.3rem'><b>".$model->suplier->suplier_nm."</b></span>";
												}else{
													if($model->tipe == "Top-up Kas Kecil" || $model->tipe == "Ganti Kas Kecil"){
														$receiver = "<b>Kas Kecil CWM</b>";
													}else if($model->tipe == "Ganti Kas Besar"){
														$receiver = "<b>Kas Besar CWM</b>";
													}else if($model->tipe == "Uang Dinas Grader"){
														$modAjuanDinas = \app\models\TAjuandinasGrader::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
														$receiver = $modAjuanDinas->graderlog->graderlog_nm;
													}else if($model->tipe == "Uang Makan Grader"){
														$modAjuanMakan = \app\models\TAjuanmakanGrader::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
														$receiver = $modAjuanMakan->graderlog->graderlog_nm;
													}else if($model->tipe == "Open Voucher"){
														$modOpenVoucher = \app\models\TOpenVoucher::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
                                                        if($modOpenVoucher->tipe == "REGULER"){
                                                            $modPenerimaVoucher = \app\models\MPenerimaVoucher::findOne($modOpenVoucher->penerima_voucher_id);
                                                           $receiver = "<span style='font-size:1.2rem;'>".(!empty($modPenerimaVoucher->nama_perusahaan)?$modPenerimaVoucher->nama_perusahaan:"")."<br><b>".
                                                                                    $modPenerimaVoucher->nama_penerima."</span>";
                                                        }else if($modOpenVoucher->tipe == "PEMBAYARAN LOG ALAM"){
                                                            $modSuplier = \app\models\MSuplier::findOne($modOpenVoucher->penerima_reff_id);
                                                            $receiver = "<span style='font-size:1.2rem;'>".(!empty($modSuplier->suplier_nm_company)?$modSuplier->suplier_nm_company:"")."<br><b>".
                                                                                $modSuplier->suplier_nm."</span>";
                                                        }else if($modOpenVoucher->tipe == "DEPOSIT SUPPLIER LOG"){
                                                            $modSuplier = \app\models\MSuplier::findOne($modOpenVoucher->penerima_reff_id);
                                                            $receiver = "<span style='font-size:1.2rem;'><b>".$modSuplier->suplier_nm."</b>".
                                                                        (!empty($modSuplier->suplier_nm_company)?"<br>".$modSuplier->suplier_nm_company:"")."</span>";
                                                        }else if($modOpenVoucher->tipe == "DP LOG SENGON" || $modOpenVoucher->tipe == "PELUNASAN LOG SENGON"){
                                                            $modSuplier = \app\models\MSuplier::findOne($modOpenVoucher->penerima_reff_id);
                                                            $receiver = "<span style='font-size:1.2rem;'><b>".$modSuplier->suplier_nm."</b></span>";
                                                        }
													}else{
														$receiver = "";
													}
												}
												?>
											</td>
											<td style="width: 6cm; height: 1cm; vertical-align: top; padding: 10px;">
												<table style="width: 100%;">
													<tr>
														<td style="width:2.1cm;">No.</td>
														<td>:&nbsp;</td>
														<td><?= $kode; ?></td>
													</tr>
													<tr>
														<td>Tanggal</td>
														<td>:&nbsp;</td>
														<td><?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal_bayar ); ?> </td>
													</tr>
													<tr>
														<td style="vertical-align: top;">Penerima</td>
														<td style="vertical-align: top;">:&nbsp;</td>
														<td style="line-height: 1;"><?= $receiver ?></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr style="background-color: #F1F4F7; border-bottom: solid 1px transparent; height: 0.5cm;">
								<td style="width: 3.5cm; padding: 7px 5px;"><b><center>Kode Perkiraan</center></b></td>
								<td style="width: 11cm; padding: 7px 5px;"><b><center>Keterangan</center></b></td>
								<td style="padding: 7px 5px;"><b><center>Jumlah</center></b></td>
							</tr>
							<tr  style="height: auto; vertical-align: top;">
								<td colspan="3">
									<table style="width: 100%" border="1">
										<?php
										$total = 0; 
										foreach($modDetail as $i => $detail){
											$total += $detail->jumlah;
										?>
											<tr >
												<td style="width: 3.5cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;"><center><?= !empty($detail->acct)? $detail->acct->acct_no:''; ?></center></td>
												<td style="width: 11cm; padding: 2px 5px; border-right: 1px solid black;"><?= !empty($detail->keterangan)?$detail->keterangan:"<center> - </center>"; ?></td>
												<td style="padding: 2px 5px; border-right: solid 1px transparent;">
													<span style="float: left">Rp.</span>
													<?php
													if($detail->jumlah < 0){
														$detail->jumlah = \app\components\DeltaFormatter::formatNumberForUser(abs($detail->jumlah));
														$jml = "(".$detail->jumlah.")";
													}else{
														$jml = \app\components\DeltaFormatter::formatNumberForUser($detail->jumlah);
													}
													?>
													<span style="float: right"><?= $jml ?></span>
												</td>
											</tr>
										<?php } ?>
										<tr>
											<td style="width: 3.5cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
											<td style="width: 11cm; padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
											<td style="padding: 2px 5px; border-right: solid 1px transparent;">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="2" style="text-align: right; padding: 3px 5px; border-left: solid 1px transparent;"> 
												<span style="float: left; font-size: 1.2rem;">Cara Bayar : <?= $model->cara_bayar.' '.$model->cara_bayar_reff; ?></span>
												<span style="float: right"><b>Total</b> &nbsp;</span>
											</td>
											<td style="padding: 3px 5px; font-weight: 800;">
												<span style="float: left">Rp.</span>
												<span style="float: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($total) ?></span>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr style="border-bottom: solid 1px transparent; border-top: solid 1px transparent;">
								<td style="width: 3.5cm; padding: 7px 5px; text-align: right; font-size: 1.2rem;">Uang Sejumlah &nbsp; </td>
								<td colspan="2" style="padding-left: 5px; background-color: #F1F4F7; font-size: 1.3rem;"><b><i><?= \app\components\DeltaFormatter::formatNumberTerbilang($total); ?></i></b></td>
							</tr>
							<tr>
								<td colspan="3">
									<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000;" border="1">
										<tr style="height: 0.4cm;">
											<td style="width:20%; vertical-align: middle; border-left: solid 1px transparent;">Diterima Oleh</td>
											<td style="width:20%; vertical-align: middle;">Dibukukan Oleh</td>
											<td style="width:20%; vertical-align: middle;">Diperiksa Oleh</td>
											<td style="width:20%; vertical-align: middle;">Disetujui Oleh</td>
											<td style="width:20%; vertical-align: middle; border-right: solid 1px transparent;">Dibuat Oleh</td>
										</tr>
										<tr>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-left: solid 1px transparent;">Tgl :</td>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;">Tgl :</td>
										</tr>
										<tr>
											<td style="height: 20px; vertical-align: middle; border-left: solid 1px transparent;"></td>
											<td style="height: 20px; vertical-align: middle; ">Staff Acc</td>
											<td style="height: 20px; vertical-align: middle; ">Kadept Fin & Kadiv FA</td>
											<td style="height: 20px; vertical-align: middle; ">GM Operasional / Direksi</td>
											<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;">Kanit Bank</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; ">
									<?php
									echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
									echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
									?>
									<span class="pull-right">CWM-FK-FIN-09-0</span>
								</td>
							</tr>
						</table>
						<?php
						if(!empty($model->cancel_transaksi_id)){
							echo "<center><label class='label label-danger'>".\app\models\TCancelTransaksi::STATUS_ABORTED."</label></center>";
						}
						?>
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