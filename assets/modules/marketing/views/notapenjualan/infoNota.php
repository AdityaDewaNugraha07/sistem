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
<div class="modal fade" id="modal-info-nota" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Nota'); ?></h4>
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
										<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
										<?= $model->jenis_produk ?>
									</td>
									<td style="width: 5.5cm; height: 1cm; vertical-align: top; padding: 10px;">
										<table>
											<tr>
												<td style="width:2cm;"><b>Kode</b></td>
												<td>: &nbsp; <?= $model->kode; ?></td>
											</tr>
											<tr>
												<td><b>Tanggal</b></td>
												<td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal ); ?> </td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding: 8px; background-color: #F1F4F7;">
							<table style="width: 100%">
								<tr>
									<td style="width: 60%; vertical-align: top; padding-left: 10px;">	
										<table>
											<tr>
												<td style="width: 3cm; vertical-align: top;"><b>Customer</b></td>
												<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
												<td style="width: 6cm; vertical-align: top;">
													<?php
														echo $model->cust->cust_an_nama." <br>";
														echo $model->cust->cust_an_alamat;
													?>
												</td>
											</tr>
											<tr>
												<td style="vertical-align: top;"><b>Alamat Bongkar</b></td>
												<td style="vertical-align: top;"><b>:</b></td>
												<td style="vertical-align: top;"><?= $model->alamat_bongkar ?></td>
											</tr>
										</table>
									</td>
									<td style="width: 40%; vertical-align: top; padding-left: 10px;">
										<table>
											<tr>
												<td style="width: 4.5cm; vertical-align: top;"><b>Nopol Kendaraaan</b></td>
												<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
												<td style="width: 6cm; vertical-align: top;"><?= $model->kendaraan_nopol ?></td>
											</tr>
											<tr>
												<td style="vertical-align: top;"><b>Nama Supir</b></td>
												<td style="vertical-align: top;"><b>:</b></td>
												<td style="vertical-align: top;"><?= $model->kendaraan_supir ?></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding: 0px;">
							<table style="width: 100%" id="table-detail">
								<tr style="height: 0.5cm; border-bottom: solid 1px #000;">
									<td rowspan="2" style="width: 7cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Produk</center></b></td>
									<td colspan="3" style="width: 5cm; border-right: solid 1px #000;"><b><center>Qty Order</center></b></td>
									<td rowspan="2" style="width: 3cm; border-right: solid 1px #000; vertical-align: middle; text-align: right;"><b><center> Harga Satuan</center></b></td>
									<td rowspan="2" style="width: 3cm; vertical-align: middle;"><b><center>Subtotal</center></b></td>
								</tr>
								<tr style="border-bottom: solid 1px #000;">
                                    <?php if( $model->opKo->jenis_produk == "Limbah" ){ ?>
                                        <td style="width: 1cm; border-right: solid 1px #000;"><b><center></center></b></td>
                                        <td style="width: 2.0cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Beli</center></b></td>
                                        <td style="width: 2.0cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Angkut</center></b></td>
                                    <?php }else{ ?>
                                        <td style="width: 1cm; border-right: solid 1px #000;"><b><center>Palet</center></b></td>
                                        <td style="width: 2.5cm; border-right: solid 1px #000;"><b><center>Satuan Kecil</center></b></td>
                                        <td style="width: 1.5cm; border-right: solid 1px #000;"><b><center>M<sup>3</sup></center></b></td>
                                    <?php } ?>
                                </tr>
								<?php
								$max = 4;
								if(count($modDetail) > $max){
									$max = count($modDetail);
								}
								$total_besar = 0;
								$total_kecil = 0;
								$total_kubik = 0;
								$subtotal=0;
								?>
								<?php for($i=0;$i<$max;$i++){
									if( count($modDetail) >= ($i+1) ){
										$total_besar += $modDetail[$i]->qty_besar;
										$total_kecil += $modDetail[$i]->qty_kecil;
										$total_kubik += $modDetail[$i]->kubikasi;
										if($modDetail[$i]->notaPenjualan->jenis_produk == "Plywood" || $modDetail[$i]->notaPenjualan->jenis_produk == "Lamineboard" || $modDetail[$i]->notaPenjualan->jenis_produk == "Platform" || $modDetail[$i]->notaPenjualan->jenis_produk == "Limbah"){
											$subtotal = $modDetail[$i]->harga_jual * $modDetail[$i]->qty_kecil;
										}else{
											$subtotal = $modDetail[$i]->harga_jual * $modDetail[$i]->kubikasi;
										}

								?>
									<tr>
										<td style="padding: 2px 5px; border-right: 1px solid black;"><?= ($model->opKo->jenis_produk=="Limbah")? $modDetail[$i]->limbah->limbah_kode." - (".$modDetail[$i]->limbah->limbah_produk_jenis.") ".$modDetail[$i]->limbah->limbah_nama : $modDetail[$i]->produk->produk_nama; ?></td>
										<td style="padding: 2px 5px; border-right: solid 1px #000;">
											<span style="float: right"><?= ($model->opKo->jenis_produk=="Limbah")?"":\app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_besar); ?></span>
										</td>
										<td style="padding: 2px 5px; border-right: solid 1px #000;">
											<span style="float: right;">
												<?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_kecil) ?> 
												<i>(<?= $modDetail[$i]->satuan_kecil ?>)</i>
											</span>
										</td>
										<td style="padding: 2px 5px; border-right: solid 1px #000;">
											<span style="float: right">
												<?= ($model->opKo->jenis_produk=="Limbah")? ( ($modDetail[$i]->satuan_kecil=="Rit")?$modDetail[$i]->satuan_besar:"" ) :number_format($modDetail[$i]->kubikasi,4); ?>
											</span> 
										</td>
										<td style="padding: 2px 5px; border-right: solid 1px #000;">
											<span style="float: left">Rp.</span> 
											<span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->harga_jual) ?></span> 
										</td>
										<td style="padding: 2px 5px; ">
											<span style="float: left">Rp.</span> 
											<span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($subtotal) ?></span> 
										</td>
									</tr>
								<?php }else{ ?>
									<tr>
										<td style="padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
										<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
										<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
										<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
										<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
										<td style="padding: 2px 5px;">&nbsp;</td>
									</tr>
								<?php } ?>
								<?php } ?>
								<?php if($model->total_ppn!=0 || $model->total_potongan!=0){ ?>
									<tr style="border-top: solid 1px #000; background-color: #F1F4F7;" >
										<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total Qty</b> &nbsp;</td>
										<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
											<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_besar) ?>
										</b></td>
										<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
											<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil) ?> <i>(<?= $modDetail[0]->satuan_kecil ?>)</i>
										</b></td>
										<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
											<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubik) ?>
										</b></td>
										<td style="width: 3cm; padding: 5px; border-right: solid 1px #000;" class="text-align-right"><b>Total Harga</b> &nbsp;</td>
										<td class="text-align-right" style="padding: 5px;"><b>
											<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_harga) ?>
										</b></td>
									</tr>
								<?php } ?>
								<?php if($model->total_ppn!=0){ ?>
									<tr style="border-top: solid 1px #000; background-color: #F1F4F7;">
										<td colspan="4" class="text-align-right" style="padding: 5px; border-right: solid 1px #000; background-color: #fff;"></td>
										<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Ppn 10%</b> &nbsp;</td>
										<td class="text-align-right" style="padding: 5px;"><b>
											<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_ppn) ?>
										</b></td>
									</tr>
								<?php } ?>
								<?php if($model->total_potongan!=0){ ?>
									<tr style="border-top: solid 1px #000; background-color: #F1F4F7;" >
										<td colspan="4" class="text-align-right" style="padding: 5px; border-right: solid 1px #000; background-color: #fff; "></td>
										<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Potongan</b> &nbsp;</td>
										<td class="text-align-right" style="padding: 5px;"><b>
											<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_potongan) ?>
										</b></td>
									</tr>
								<?php } ?>
								<tr style="border-top: solid 1px #000; border-bottom: solid 1px transparent; background-color: #F1F4F7;" >

									<td colspan="5" class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total Bayar</b> &nbsp;</td>
									<td class="text-align-right" style="padding: 5px;"><b>
										<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_bayar) ?>
									</b></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="border-bottom: solid 1px transparent;">
						<td colspan="3" style=" border-top: solid 1px transparent;">
							<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
								<tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
									<td style="width: 16cm; text-align: left; border-bottom: solid 1px transparent;">
										<b>Terbilang :</b>
									</td>
									<td style="vertical-align: middle; width: 4cm; background-color: #F1F4F7;">Dibuat Oleh</td>
								</tr>
								<tr>
									<td style="font-size:1.4rem; text-align: left; vertical-align: top;">
										<b><i><?= app\components\DeltaFormatter::formatNumberTerbilang($model->total_bayar); ?></i></b>
									</td>
									<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;"></td>
								</tr>
								<tr>
									<td style="vertical-align: bottom; font-size: 0.9rem; text-align: left; border-top: solid 1px transparent;">
										<?php
										echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
										echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
										?>
									</td>
									<td style="background-color: #F1F4F7; height: 20px; vertical-align: middle;  border-right: solid 1px transparent;  ">
										<?php
										if(!empty($model->created_by)){
                                                                                    if(($model->tanggal)<='2020-01-21'){
											echo "<span style='font-size:0.9rem'>".\app\models\MPegawai::findOne(app\components\Params::DEFAULT_PEGAWAI_ID_FITRIYANAH)->pegawai_nama."</span>";
                                                                                    }else{
                                                                                        echo "<span style='font-size:0.9rem'>".\app\models\MPegawai::findOne(app\components\Params::DEFAULT_PEGAWAI_ID_RYA)->pegawai_nama."</span>";
                                                                                    }
                                                                                    
                                                                                }
										?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
							<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-10-0</span>
						</td>
					</tr>
				</table>
            </div>
			<div class="modal-footer" style="text-align: center;">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printout('.$model->nota_penjualan_id.')']); ?>
			</div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>\

<script>

</script>