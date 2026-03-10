<?php
if($model->cara_bayar == 'Potongan' && $model->keterangan == 'Pengajuan Koreksi Data'){
    $modPengajuan = \app\models\TPengajuanManipulasi::findOne(['reff_no'=>$model->bill_reff, 'tipe'=>'POTONGAN PIUTANG']);
    $dataArray = json_decode($modPengajuan->datadetail1,true) ;
	$customer_id =  $dataArray['new']['t_piutang_penjualan']['cust_id'];
    $customer = \app\models\MCustomer::findOne(['cust_id' => $customer_id]);
	$pemohon = \app\models\ViewUser::findOne(['user_id' => $modPengajuan->created_by]);	
	$approval = \app\models\ViewApproval::find()->where(['reff_no' => $modPengajuan->kode, 'parameter1' => 'Data Correction'])->orderBy(['level' => SORT_DESC])->all();	
	$piutang = \app\models\TPiutangPenjualan::find()->where(['bill_reff' => $model->bill_reff])->andWhere(['cancel_transaksi_id' => null])->andWhere(['<>', 'cara_bayar', 'Potongan'])->all();
	$terbayar = 0;
	foreach ($piutang as $p) {
		$terbayar += $p->bayar;
	}
	$sisa_tagihan = $dataArray['new']['t_nota_penjualan']['total_bayar'] - $terbayar !== null ? $dataArray['new']['t_nota_penjualan']['total_bayar'] - $terbayar : 0;
	$potongan = !empty($datadetail) ? $datadetail->new->t_piutang_penjualan->bayar : 0;
	$sisa = $sisa_tagihan - $potongan;
}   
?>

<style>
table{
	font-size: 1.2rem;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<div class="modal fade" id="modal-catatan-potongan" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Info Potongan'); ?></h4>
            </div>
            <div class="modal-body">
                <table style="width: 20cm; margin: 10px; height: 10cm;" border="1">
					<tr>
						<td colspan="3" style="padding: 5px;">
							<table style="width: 100%; " border="0">
								<tr style="">
									<td style="text-align: left; vertical-align: top; padding: 0px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
										<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
									</td>
									<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
										<span style="font-size: 1.9rem; font-weight: 600">Potongan Piutang</span><br>
										<?= $model->keterangan ?>
									</td>
									<td style="width: 5.5cm; height: 1cm; vertical-align: top; padding: 10px;">
										<table>
											<tr>
												<td style="width:2cm;"><b>Kode</b></td>
												<td>: &nbsp; <?= $modPengajuan->kode; ?></td>
											</tr>
											<tr>
												<td><b>Tanggal</b></td>
												<td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2( $modPengajuan->tanggal ); ?> </td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding: 5px; background-color: #F1F4F7;">
							<table style="width: 100%">
								<tr>
									<td style="width: 50%; vertical-align: top; padding-left: 10px;">	
										<table>
											<tr>
												<td style="width: 4cm; vertical-align: top;"><b>Bill Reff</b></td>
												<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
												<td style="width: 6cm; vertical-align: top;"><?= $modPengajuan->reff_no; ?></td>
											</tr>
											<tr>
												<td style="vertical-align: top;"><b>Nama Pemohon</b></td>
												<td style="vertical-align: top;"><b>:</b></td>
												<td style="vertical-align: top;"><?= $pemohon->pegawai->pegawai_nama ?></td>
											</tr>
                                            <tr>
												<td style="vertical-align: top;"><b>Deprtement Pemohon</b></td>
												<td style="vertical-align: top;"><b>:</b></td>
												<td style="vertical-align: top;"><?= $pemohon->departement->departement_nama ?></td>
											</tr>                                            
										</table>
									</td>
									<td style="width: 50%; vertical-align: top; padding-left: 10px;">
                                        <table>
                                            <tr>
												<td style="width: 4cm; vertical-align: top;"><b>Jenis Pengajuan</b></td>
												<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
												<td style="vertical-align: top;"><?= $modPengajuan->tipe ?></td>
											</tr>
                                            <tr>
												<td style="vertical-align: top;"><b>Priority</b></td>
												<td style="vertical-align: top;"><b>:</b></td>
												<td style="vertical-align: top;"><?= $modPengajuan->priority ?></td>
											</tr>
                                            <tr>
												<td style="vertical-align: top;"><b>Alasan Pengajuan</b></td>
												<td style="vertical-align: top;"><b>:</b></td>
												<td style="vertical-align: top;"><?= $modPengajuan->reason ?></td>
											</tr>                                            
                                        </table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding: 0px;vertical-align:top;">
							<table style="width: 100%;" id="table-detail">
								<tr style="height: 0.5cm; border-bottom: solid 1px #000;">
									<td style="width: 7cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><left>Customer</left></b></td>
									<td style="width: 3cm; vertical-align: middle; border-right: solid 1px #000;""><b><center>Nominal Bill</center></b></td>
									<td style="width: 3cm; vertical-align: middle; text-align: right; border-right: solid 1px #000;"><b><center> Pernah Terbayar</center></b></td>
									<td style="width: 3cm; vertical-align: middle; border-right: solid 1px #000;"><b><center>Sisa Tagihan</center></b></td>
                                    <td style="width: 3cm; vertical-align: middle; border-right: solid 1px #000;"><b><center>Potongan</center></b></td>
                                    <td style="width: 3cm; vertical-align: middle;"><b><center>Sisa Piutang</center></b></td>                                    
								</tr>
								<tr style="border-bottom: solid 1px;">
                                    <td style="padding:7px;border-right: solid 1px #000;"><?= $customer->cust->cust_an_nama;?></td>
                                    <td style="padding:7px; text-align: right;border-right: solid 1px #000;"><?= number_format($dataArray['new']['t_nota_penjualan']['total_bayar']); ?></td>
                                    <td style="padding:7px; text-align: right;border-right: solid 1px #000;"><?= number_format($terbayar); ?></td>
                                    <td style="padding:7px; text-align: right;border-right: solid 1px #000;"><?= number_format($sisa_tagihan); ?></td>
                                    <td style="padding:7px; text-align: right;border-right: solid 1px #000;"><?= number_format($sisa_tagihan); ?></td>
                                    <td style="padding:7px; text-align: right;"><?= number_format($potongan); ?></td>
                                </tr>
							</table>
						</td>
					</tr>
					<tr style="border-bottom: solid 1px transparent;">
						<td colspan="3" style=" height:1cm; border-top: solid 1px transparent;">
							<table style="padding:5px; width: width: 100%; font-size: 1rem; text-align: center; border-bottom: solid 1px #000; border-left: solid 1px transparent;">
								<tr style="height: 0.4cm;  border-right: solid 1px transparent=;">
								<?php
									foreach($approval as $i => $approve) {
									?>
										<td style="vertical-align: middle; width: 20%; ">
											<h style="vertical-align: middle; width: 20%; ">APPROVAL <?= $approve->level ?></h><br>
											<h style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; "><?= $approve->status."<br>".!empty($approve->approved_by_nama) ? $approve->approved_by_nama : $approve->assigned_nama."<br>"; ?></h>
											<h style=" height: 20px; vertical-align: middle;  "><?= "at: ".$approve->updated_at." WIB"; ?></h>
										</td>
									<?php
									}
									?>
									<td style="vertical-align: middle; width: 20%; ">
										<h style="vertical-align: middle; width: 4cm; ">Dibuat Oleh</h><br>
										<h style="vertical-align: bottom; padding-left: 5px; text-align: left;"></h><br>
										<h style=" height: 20px; vertical-align: middle;"><?= $pemohon->pegawai->pegawai_nama ?><br><?= app\components\DeltaFormatter::formatDateTimeForUser($modPengajuan->created_at); ?> WIB</h>
									</td>									
								</tr>
							</table>
						</td>
					</tr>
					<tr>
					    <td colspan="2" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
							<span class="pull-left nomor-dokumen-qms" style="font-size: 0.8rem;">
							<?php
							echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
							echo Yii::t('app', 'at : '). date('d/m/Y H:i:s'). " WIB";
							?>
						    </span>
						</td>
						<td style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
							<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-</span>
						</td>
					</tr>
				</table>
            </div>
			<div class="modal-footer" style="text-align: center;">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'#('.$modPengajuan->pengajuan_manipulasi_id.')']); ?>
			</div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>\
