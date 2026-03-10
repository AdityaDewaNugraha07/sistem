<?php
$modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
?>
<style>
table{
	font-size: 1.3rem;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<div class="modal fade" id="modal-info-kuitansi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Kuitansi'); ?></h4>
            </div>
            <div class="modal-body">
                <table style="width: 20cm; margin: 10px; height: 10cm;" border="1">
					<tr>
						<td colspan="3" style="padding: 5px; height: 2.3cm;">
							<table style="width: 100%; " border="0">
								<tr style="">
									<td style="width: 3cm; text-align: center; vertical-align: middle; padding: 0px; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
										<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
									</td>
									<td style="width: 10cm; text-align: left; vertical-align: top; padding: 5px; line-height: 1.1;">
										<span style="font-size: 1.4rem; font-weight: 600"><?= $modCompany->name; ?></span><br>
										<span style="font-size: 1rem;"><?= $modCompany->alamat; ?></span><br>
									</td>
									<td style="text-align: center; vertical-align: middle; padding: 10px; line-height: 1.3;">
										<span style="font-size: 2.5rem; font-weight: 600"><u>K U I T A N S I</u></span><br>
										<span style="font-size: 1.4rem;"><i>No. <?= $model->nomor ?></i></span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="height: 2.5cm; padding: 8px; background-color: #F1F4F7; vertical-align: middle;">
							<table style="width: 100%">
								<tr>
									<td style="width: 60%; vertical-align: top; padding-left: 10px;">	
										<table>
											<tr>
												<td style="vertical-align: top; width: 140px; padding-top: 10px;">Sudah terima dari</td>
												<td style="vertical-align: top; width: 15px; padding-top: 10px;">:</td>
												<td style="vertical-align: top; padding-top: 10px;"><b><?= $model->terima_dari ?></b></td>
											</tr>
											<tr>
												<td style="vertical-align: top; padding-top: 10px;">Untuk pembayaran</td>
												<td style="vertical-align: top; padding-top: 10px;">:</td>
												<td style="vertical-align: top; padding-top: 10px;"><b><?php echo nl2br($model->untuk_pembayaran); ?></b></td>
											</tr>
											<tr>
												<td style="vertical-align: top; padding-top: 10px;">Banyaknya uang</td>
												<td style="vertical-align: top; padding-top: 10px;">:</td>
												<td style="vertical-align: top; padding: 10px 0 10px 0"><b>Rp. <?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal) ?></b></td>
											</tr>
										</table>
									</td>
									<td style="width: 40%; vertical-align: top; padding-left: 10px;">
										<table>
											<tr>
												<td style="width: 4.5cm; vertical-align: top; padding-top: 10px;">Tanggal</td>
												<td style="width: 0.3cm; vertical-align: top; padding-top: 10px;">:</td>
												<td style="width: 6cm; vertical-align: top; padding-top: 10px;"><b><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></b></td>
											</tr>
											<tr>
												<td style="vertical-align: top; padding-top: 10px;">Cara Bayar</td>
												<td style="vertical-align: top; padding-top: 10px;">:</td>
												<td style="vertical-align: top; padding-top: 10px;"><b><?= $model->cara_bayar ?></b></td>
											</tr>
											<?php if(!empty($model->keterangan)){ ?>
											<tr>
												<td style="vertical-align: top; padding-top: 10px;">Keterangan</td>
												<td style="vertical-align: top; padding-top: 10px;">:</td>
												<td style="vertical-align: top; padding: 10px 0 10px 0"><b><?= $model->keterangan ?></b></td>
											</tr>
											<?php } ?>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: 1.5cm; border-bottom: solid 1px #000;">
						<td colspan="2" style="text-align: left; font-size:1.4rem; padding-left: 10px;">
							<b><i>"<?= app\components\DeltaFormatter::formatNumberTerbilang($model->nominal); ?>"</i></b>
						</td>
					</tr>
					<tr style="height: 1.5cm; border-bottom: solid 1px transparent;">
						<td style="width: 14cm; text-align: center; border-right: solid 1px transparent;">
						</td>
						<td style="vertical-align: top; text-align: center; padding-top: 5px;">
							Mranggen, <?= \app\components\DeltaFormatter::formatDateTimeForUser( $model->tanggal ) ?>
						</td>
					</tr>
					<tr>
						<td style="height: 2cm; text-align: left; vertical-align: bottom; line-height: 1.3; font-size: 1.1rem; padding-left: 15px;" colspan="2">
							<?php if($model->cara_bayar != "Tunai"){ ?>
							<i>Pembayaran menggunakan Cek/BG/Transfer, dianggap<br>Lunas setelah dana cair / masuk ke rekening yang<br>
								benar / yang sudah ditentukan</i>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td style="vertical-align: bottom; font-size: 0.9rem; text-align: left; border-top: solid 1px transparent; border-right: solid 1px transparent; padding-left: 5px;">
							<?php
							echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
							echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
							?>
						</td>
						<td style="vertical-align: bottom; line-height: 1;  text-align: center; border-top: solid 1px transparent;">
							<?php
							echo "<span style='font-size:0.9rem'><b><u> ". \app\models\MPegawai::findOne($model->petugas)->pegawai_nama." </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>Penerima </span>";
							?>
						</td>
					</tr>
					<tr style="border-right: solid 1px transparent;">
						<td colspan="2" style="font-size: 0.9rem; border-bottom: solid 1px transparent; border-left: solid 1px transparent; border-right: solid 1px transparent; vertical-align: top; padding-left: 5px;">
							<span class="pull-left lampiran-rangkap" style="font-size: 0.8rem;">
								Lembar Putih : Untuk Customer &nbsp;&nbsp; - &nbsp;&nbsp;
								Lembar Merah : Untuk Finance (Arsip)
							</span>
							<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;"></span>
						</td>
					</tr>
				</table>
            </div>
			<div class="modal-footer" style="text-align: center;">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printout('.$model->kuitansi_id.')']); ?>
			</div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>\

<script>
function printout(id){
	var caraPrint = "PRINT";
	window.open("<?= yii\helpers\Url::toRoute(['/kasir/kasbesar/printKuitansi','id'=>'']) ?>"+id+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>