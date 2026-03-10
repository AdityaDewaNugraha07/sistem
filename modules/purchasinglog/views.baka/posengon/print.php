<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
#bg{
	min-height: 100%;
	min-width: 100%;
	width:100%;
	height: auto;
	position: fixed;
	top: 0;
	left: -20px;
}
@media screen and (max-width: 1024px) {  Specific to this particular image 
	img.bg {
		left: 50%;
		margin-left: -512px;
	}
}
/*table tr td{
	font-size: 0.9rem;
}*/
</style>
<img src="<?php echo \Yii::$app->view->theme->baseUrl."/cis/img/bg-surat.png"; ?>" id="bg" alt="" style="">
<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
				<div class="row">
                    <div class="col-md-12">
						<table style="width: 100%; margin-top: 125px;" border="0">
							<tr>
								<td style="text-align: center; width: 10px" rowspan="9">
								</td>
								<td style="text-align: center; width: 120px" rowspan="6">
									&nbsp;
								</td>
								<td style="height: 30px; vertical-align: top;" colspan="3">
									Demak, <?= \app\components\DeltaFormatter::formatDateTimeId(date('Y-m-d')) ?>
								</td>
							</tr>
							<tr>
								<td style="width:80px;"><?= Yii::t('app', 'Nomor'); ?></td>
								<td style="width:10px;">:</td>
								<td><?= $model->kode; ?></td>
							</tr>
							<tr>
								<td><?= Yii::t('app', 'Kepada'); ?></td>
								<td>:</td>
								<td><?= $model->suplier->suplier_nm; ?></td>
							</tr>
							<tr>
								<td><?= Yii::t('app', 'Alamat'); ?></td>
								<td>:</td>
								<td><?= $model->suplier->suplier_almt; ?></td>
							</tr>
							<tr>
								<td><?= Yii::t('app', 'HP'); ?></td>
								<td>:</td>
								<td><?= $model->suplier->suplier_phone; ?></td>
							</tr>
							<tr>
								<td colspan="3" style="height: 50px; vertical-align: bottom;"></td>
							</tr>
							<tr>
								<td colspan="5" style="height: 50px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="" colspan="4"><?= Yii::t('app', 'Dengan Hormat,'); ?></td>
							</tr>
							<tr>
								<td style="" colspan="4">
									Berikut disampaikan pemesanan barang dengan spesifikasi tersebut dibawah ini :<br><br>
									<table border="0">
										<tr>
											<td style="width:80px;">Nama Barang</td>
											<td style="width:10px;">:</td>
											<td style="text-align: justify"><?= $model->nama_barang ?></td>
										</tr>
										<tr>
											<td style="width:80px;">Panjang</td>
											<td style="width:10px;">:</td>
											<td style="text-align: justify"><?= $model->panjang ?></td>
										</tr>
										<tr>
											<td style="width:80px;">Banyaknya</td>
											<td style="width:10px;">:</td>
											<td style="text-align: justify"><?= \app\components\DeltaFormatter::formatNumberForUser($model->kuantiti) ?></td>
										</tr>
										<tr>
											<td style="width:150px; vertical-align: top;">Diameter / Harga</td>
											<td style="width:10px; vertical-align: top;">:</td>
											<td style="text-align: justify">
												<?php
												if(count($model->diameter_harga)>0){
													foreach($model->diameter_harga as $i => $diahar){
														echo "<table><tr>
															<td style='width:100px;'>$i</td>
															<td>".\app\components\DeltaFormatter::formatUang($diahar)."</td>
														</tr></table>";
													}
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="width:80px;">Waktu Pengiriman</td>
											<td style="width:10px;">:</td>
											<td style="text-align: justify"><?= $model->periode_pengiriman ?></td>
										</tr>
										<tr>
											<td style="width:80px;">Cara Pembayaran</td>
											<td style="width:10px;">:</td>
											<td style="text-align: justify"><?= $model->cara_bayar ?></td>
										</tr>
										<tr>
											<td style="width:80px;">No. Rekening</td>
											<td style="width:10px;">:</td>
											<td style="text-align: justify"><?= $model->rekening_bank; ?></td>
										</tr>
									</table>
									<br>
									<table border="0">
										<tr>
											<td colspan="2">Spesifikasi Log : </td>
										</tr>
										<?php if(count($model->spesifikasi_log)>0){
											foreach($model->spesifikasi_log as $ii => $spek){ ?>
												<tr>
													<?php if(!is_numeric($ii)){ ?>
														<td style="width: 10px; vertical-align: top;"> - </td>
														<td style="width: 200px; vertical-align: top;"><?= $ii ?></td>
														<td style="width: 10px;  vertical-align: top;"> : </td>
														<td> <?= $spek ?></td>
													<?php }else{ ?>
														<td style="width: 10px"> - </td>
														<td style="text-align: justify" colspan="3"><?= $spek ?></td>
													<?php } ?>
												</tr>
										<?php
											}
										}
										?>
									</table>
								</td>
							</tr>
						</table>
						<br><br>
						<table border="0" style="width: 100%">
							<tr>
								<td style="width: 35%; text-align: center;">
									Disetujui
									<br><br><br><br><br>
									<u><?= $model->suplier->suplier_nm ?></u><br>
									Supplier
								</td>
								<td style="width: 30%;">&nbsp;</td>
								<td style="width: 35%; text-align: center;">
									Hormat Kami, 
									<br><br><br><br><br>
									<u><?= $model->disetujuiDirektur->pegawai_nama ?></u><br>
									Direktur
								</td>
							</tr>
						</table>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>