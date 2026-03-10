<?php app\assets\DatatableAsset::register($this); ?>
<?php
$kode = $model->kode;
?>
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
                <h4 class="modal-title"><?= Yii::t('app', 'Detail BKK'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<table style="width: 19cm; margin: 10px; height: 10cm;" border="1">
							<tr style="height: 2cm;">
								<td colspan="3">
									<table style="width: 100%; " border="0">
										<tr style="border-bottom: 1px solid #000;">
											<td style="text-align: left; vertical-align: middle; padding: 3px; width: 4cm; height: 1cm; border-bottom: solid 1px #000; border-right: solid 1px transparent;">
												<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 75px;"> 	
											</td>
											<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
												<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul']; ?></u></span><br>
											</td>
											<td style="width: 6cm; height: 1cm; vertical-align: top; padding: 10px;">
												<table style="width: 100%;">
													<tr>
														<td style="width:2.1cm;">No. BKK</td>
														<td>:&nbsp;</td>
														<td><?= $kode; ?></td>
													</tr>
													<tr>
														<td>Tanggal</td>
														<td>:&nbsp;</td>
														<td><?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal ); ?> </td>
													</tr>
													<tr>
														<td style="vertical-align: top;">Penerima</td>
														<td style="vertical-align: top;">:&nbsp;</td>
														<td style="line-height: 1;">
															<?php
															if(!empty($model->diterima_oleh)){
																echo "<span style='font-size:1.1rem'><b>".$model->diterima_oleh."</b></span>";
															}else{
																echo "-";
															}
															?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr style="border-bottom: solid 1px transparent; border-top: solid 1px transparent; height: 25px;">
								<td style="font-size: 1.2rem;"> &nbsp; Uang Sejumlah &nbsp; </td>
								<td colspan="2" style="padding-left: 5px; font-size: 1.4rem; border-left: solid 1px transparent; height: 20px;"> : <b><?= app\components\DeltaFormatter::formatUang($total) ?></b></td>
							</tr>
							<tr style="border-bottom: solid 1px #000; border-top: solid 1px transparent; height: 25px;">
								<td style="font-size: 1.2rem;"> &nbsp; Terbilang &nbsp; </td>
								<td colspan="2" style="padding-left: 5px; font-size: 1.3rem; border-left: solid 1px transparent;"> : <b><i><?= \app\components\DeltaFormatter::formatNumberTerbilang($total); ?></i></b></td>
							</tr>
							<tr style="background-color: #F1F4F7; border-bottom: solid 1px transparent; height: 0.5cm;">
								<td style="width: 3.5cm; padding: 7px 5px;"><b><center>Kode Perkiraan</center></b></td>
								<td style="width: 11cm; padding: 7px 5px;"><b><center>Keterangan</center></b></td>
								<td style="padding: 7px 5px;"><b><center>Jumlah</center></b></td>
							</tr>
							<tr  style="height: 4cm; vertical-align: top; border-bottom: solid 1px transparent;">
								<td colspan="3">
									<table style="width: 100%" border="1">
										<?php
										$max = 4;
										if(count($modDetail) > $max){
											$max = count($modDetail);
										}
										?>
										<?php for($i=0;$i<$max;$i++){
											if( count($modDetail) >= ($i+1) ){
										?>
											<tr >
												<td style="width: 3.5cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;"><center><?= !empty($detail->acct)? $detail->acct->acct_no:''; ?></center></td>
												<td style="width: 11cm; padding: 2px 5px; border-right: 1px solid black; font-size:1.2rem"><?= !empty($modDetail[$i]['detail_deskripsi'])?$modDetail[$i]['detail_deskripsi']:"<center> - </center>"; ?></td>
												<td style="padding: 2px 5px; border-right: solid 1px transparent;">
													<span style="float: left">Rp.</span>
													<?php
													if($modDetail[$i]['detail_nominal'] < 0){
														$modDetail[$i]['detail_nominal'] = \app\components\DeltaFormatter::formatNumberForUser(abs($modDetail[$i]['detail_nominal']));
														$jml = "(".$modDetail[$i]['detail_nominal'].")";
													}else{
														$jml = \app\components\DeltaFormatter::formatNumberForUser($modDetail[$i]['detail_nominal']);
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
							<tr>
								<td colspan="3">
									<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000;" border="1">
										<tr style="height: 0.4cm;">
											<td style="vertical-align: middle; width: 3.8cm; border-left: solid 1px transparent;">Diterima Oleh</td>
											<td style="vertical-align: middle; width: 3.8cm;">Dibukukan Oleh</td>
											<td style="vertical-align: middle; width: 3.8cm;">Diperiksa Oleh</td>
											<td style="vertical-align: middle; width: 3.8cm;">Disetujui Oleh</td>
											<td style="vertical-align: middle; width: 3.8cm; border-right: solid 1px transparent;">Dibuat Oleh</td>
										</tr>
										<tr>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-left: solid 1px transparent;">Tgl : <?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal ); ?></td>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
											<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;">Tgl : <?= date('d/m/Y'); ?> </td>
										</tr>
										<tr>
											<td style="height: 20px; vertical-align: middle; border-left: solid 1px transparent;">
												<?php
												if(!empty($model->diterima_oleh)){
													echo "<span style='font-size:0.9rem'>".$model->diterima_oleh."</span>";
												}
												?>
											</td>
											<td style="height: 20px; vertical-align: middle; ">Staff Acc</td>
											<td style="height: 20px; vertical-align: middle; ">Staff Finance</td>
											<td style="height: 20px; vertical-align: middle; ">Kadep Finance</td>
											<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;">
												<?php
												if(!empty($model->dibuat_oleh)){
													echo "<span style='font-size:0.9rem'>".$model->dibuatOleh->pegawai_nama."</span>";
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
									<span class="pull-right">CWM-FK-FIN-08-0</span>
								</td>
							</tr>
						</table>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
                <?= yii\helpers\Html::button(Yii::t('app', 'Print'),['class'=>'btn blue-steel ciptana-spin-btn btn-outline','onclick'=>'printBKK('.$model->bkk_id.')']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>