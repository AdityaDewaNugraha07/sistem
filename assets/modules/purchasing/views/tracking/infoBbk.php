<?php
/* @var $this yii\web\View */
$this->title = 'Detail BBK';
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
table{
	font-size: 1.4rem;
}
</style>
<div class="modal fade draggable-modal" id="modal-info-voucher" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Voucher Pengeluaran');?></h4>
            </div>
            <div class="modal-body">
                <table style="width: 19cm; margin: 10px; height: 15cm;" border="1">
					<tr style="height: 2cm;">
						<td colspan="3">
							<table style="width: 100%; height: 1cm;" border="0">
								<tr style="">
									<td style="text-align: left; vertical-align: top; padding: 10px; width: 5.5cm; height: 1.5cm; border-bottom: 1px solid black; border-right: 1px solid black;">
										Dibayarkan Kepada :<br>
											<?php
											if(!empty($model->suplier_id)){
												echo "<span style='font-size:1.3rem'><b>".$model->suplier->suplier_nm."</b></span>";
											}else{
												if($model->tipe == "Top-up Kas Kecil"){
													echo "Kas Kecil CWM";
												}else if($model->tipe == "PPK Kas Besar"){
													echo "Kas Besar CWM";
												}
											}
											?>

									</td>
									<td style="text-align: center; vertical-align: top; padding: 10px;">
										<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul']; ?></u></span><br>
										<?php
										if(!empty($model->akun_debit)){
											echo "<span>".substr( \app\models\MAcctRekening::getByPk($model->akun_debit)->acct_nm, -3,3 )."</span>";
											$kode = $model->kode;
										}
										?>
									</td>
									<td style="width: 5.5cm; height: 1.7cm; vertical-align: top; padding: 10px;">
										<table>
											<tr>
												<td style="width:1.5cm;">No.</td>
												<td>: &nbsp; <?= $kode; ?></td>
											</tr>
											<tr>
												<td style="width:1.5cm;">Tanggal</td>
												<!--<td>: &nbsp; <?php // echo \app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal_bayar); ?></td>-->
												<td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal_bayar ); ?> </td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<br>
						</td>
					</tr>
					<tr style="background-color: #F1F4F7; border-bottom: solid 1px transparent; height: 0.5cm;">
						<td style="width: 3.5cm; padding: 7px 5px;"><b><center>Kode Perkiraan</center></b></td>
						<td style="width: 11cm; padding: 7px 5px;"><b><center>Keterangan</center></b></td>
						<td style="padding: 7px 5px;"><b><center>Jumlah</center></b></td>
					</tr>
					<tr  style="height: 8cm; vertical-align: top;">
						<td colspan="3">
							<table style="width: 100%" border="1">
								<?php $total = 0; for($i=0;$i<12;$i++){
									if( count($modDetail) >= ($i+1) ){
								?>
									<?php $total += $modDetail[$i]->jumlah; ?>
									<tr >
										<td style="width: 3.5cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;"><center><?= !empty($detail->acct)? $detail->acct->acct_no:''; ?></center></td>
										<td style="width: 11cm; padding: 2px 5px; border-right: 1px solid black;"><?= !empty($modDetail[$i]->keterangan)?$modDetail[$i]->keterangan:"<center> - </center>"; ?></td>
										<td style="padding: 2px 5px; border-right: solid 1px transparent;">
											<span style="float: left">Rp.</span>
											<?php
											if($modDetail[$i]->jumlah < 0){
												$modDetail[$i]->jumlah = \app\components\DeltaFormatter::formatNumberForUser(abs($modDetail[$i]->jumlah));
												$jml = "(".$modDetail[$i]->jumlah.")";
											}else{
												$jml = \app\components\DeltaFormatter::formatNumberForUser($modDetail[$i]->jumlah);
											}
											?>
											<span style="float: right"><?= $jml ?></span>
										</td>
									</tr>
								<?php }else{ ?>
									<tr>
										<td style="width: 3.5cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
										<td style="width: 11cm; padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
										<td style="padding: 2px 5px; border-right: solid 1px transparent;">&nbsp;</td>
									</tr>
								<?php } ?>
								<?php } ?>
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
									<td style="vertical-align: middle; border-left: solid 1px transparent;">Diterima Oleh</td>
									<td style="vertical-align: middle;">Dibukukan Oleh</td>
									<td style="vertical-align: middle;">Diperiksa Oleh</td>
									<td style="vertical-align: middle;">Disetujui Oleh</td>
									<td style="vertical-align: middle; border-right: solid 1px transparent;">Dibuat Oleh</td>
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
									<td style="height: 20px; vertical-align: middle; ">Akuntansi</td>
									<td style="height: 20px; vertical-align: middle; ">Keuangan</td>
									<td style="height: 20px; vertical-align: middle; ">Pimpinan</td>
									<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;">Kasir</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; ">
							<?php
							echo Yii::t('app', 'Access By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
							echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
							?>
						</td>
					</tr>
				</table>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>