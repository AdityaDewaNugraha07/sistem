<?php app\assets\DatatableAsset::register($this); ?>
<style>
table{
	font-size: 1.4rem;
}
</style>
<div class="modal fade" id="modal-bkk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Laporan Giro / Cek'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<table style="width: 20cm; margin: 10px;" border="1">
							<tr style="height: 2cm;">
								<td colspan="3">
									<table style="width: 100%; height: 1cm;" border="0">
										<tr style="">
											<td style="text-align: center; vertical-align: top; padding: 10px;">
												<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul']; ?></u></span><br>
											</td>
											<td style="width: 6cm; height: 1.4cm; vertical-align: top; padding: 10px;">
												<table>
													<tr>
														<td style="width:2cm;">No.</td>
														<td>: &nbsp; <?= $modDetail[0]->kode ?></td>
													</tr>
													<tr>
														<td style="width:2cm;">Tanggal</td>
														<td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($modDetail[0]->tanggal) ?> </td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<br>
								</td>
							</tr>
							<tr style="border-bottom: solid 1px transparent; ">
								<td colspan="3">
									<table style="width: 100%;" border="1">
										<tr style="background-color: #F1F4F7; border-top: 1px solid transparent; border-right: 1px solid transparent; border-left: 1px solid transparent;">
											<td rowspan="2" style="width: 0.5cm; padding: 3px; font-size: 1.3rem"><center>No.</center></td>
											<td rowspan="2" style="width: 3cm; padding: 3px; font-size: 1.3rem"><center>Nama Customer</center></td>
											<td rowspan="2" style="width: 1.5cm; padding: 3px; font-size: 1.3rem; line-height:13px"><center>No. Bukti</center></td>
											<td colspan="2" style="padding: 3px; font-size: 1.3rem"><center>Bank</center></td>
											<td rowspan="2" style="width: 2cm; padding: 3px; font-size: 1.3rem"><center>No. BG/Cek</center></td>
											<td rowspan="2" style="width: 2.5cm; padding: 3px; font-size: 1.3rem; line-height:13px"><center>Tgl Jatuh<br>Tempo</center></td>
											<td rowspan="2" style="width: 2.5cm; padding: 3px; font-size: 1.3rem"><center>Nominal</center></td>
											<td rowspan="2" style="max-width: 5cm; padding: 3px; font-size: 1.3rem"><center>Keterangan</center></td>
										</tr>
										<tr style="background-color: #F1F4F7; border-right: 1px solid transparent; border-left: 1px solid transparent;">
											<td style="width: 1.5cm; padding: 3px; font-size: 1.3rem"><center>Nama</center></td>
											<td style="width: 2.5cm; padding: 3px; font-size: 1.3rem"><center>No. Acct</center></td>
										</tr>
										<?php foreach($modDetail as $i => $detail){ ?>
											<tr style="border-left: 1px solid transparent; border-right: 1px solid transparent; font-size: 1.1rem;">
												<td style="vertical-align: top;">
													<center><?= $i+1; ?></center>
												</td>
												<td style="vertical-align: top;">
													<?= $detail->nama_customer ?>
												</td>
												<td style="vertical-align: top;">
													<center><?= $detail->no_bukti; ?></center>
												</td>
												<td style="vertical-align: top;">
													<center><?= $detail->cust_bank; ?></center>
												</td>
												<td style="vertical-align: top;">
													<center><?= $detail->cust_acct; ?></center>
												</td>
												<td style="vertical-align: top;">
													<center><?= $detail->reff_number; ?></center>
												</td>
												<td style="vertical-align: top;">
													<center><?= app\components\DeltaFormatter::formatDateTimeForUser2($detail->tanggal_jatuhtempo); ?></center>
												</td>
												<td style="vertical-align: top; text-align: right;">
													<?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->nominal); ?> &nbsp; 
												</td>
												<td style="vertical-align: top; max-width: 5cm;">
													<center><?= $detail->keterangan; ?></center>
												</td>
											</tr>
										<?php } ?>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="3" style="border-bottom: 1px solid transparent;">&nbsp;
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000;" border="1">
										<tr style="height: 0.4cm;">
											<td style="vertical-align: middle; width: 7.6cm; border-left: solid 1px transparent;" rowspan="3"></td>
											<td style="vertical-align: middle; width: 3.8cm;">Diperiksa Oleh</td>
											<td style="vertical-align: middle; width: 3.8cm;">Disetujui Oleh</td>
											<td style="vertical-align: middle; width: 3.8cm; border-right: solid 1px transparent;">Dibuat Oleh</td>
										</tr>
										<tr>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;">Tgl : <?= date('d/m/Y'); ?> </td>
										</tr>
										<tr>
											<td style="height: 20px; vertical-align: middle; text-align: left;">B. Keu :</td>
											<td style="height: 20px; vertical-align: middle; text-align: left">B. Keu :</td>
											<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;">
												<?php
												if(!empty($model->dibuat_oleh)){
													echo "<span style='font-size:0.9rem'>asdasd</span>";
												}
												?>
												(Kasir)
											</td>
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
								</td>
							</tr>
						</table>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
                <?= yii\helpers\Html::button(Yii::t('app', 'Print'),['class'=>'btn blue-steel ciptana-spin-btn btn-outline','onclick'=>"printNontunai('".$modDetail[0]->tanggal."')"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>