<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
$company = app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
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
<table style="width: 20cm; margin: 10px; height: 10cm;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0" id="table-detail">
				<tr style="">
					<td colspan="5" style="padding: 5px; border-bottom: solid 1px #000;">
						<table style="width: 100%; " border="0">
							<tr style="">
								<td style="width: 4cm; text-align: left; vertical-align: top; padding: 0px; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
									<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 75px;"> 	
								</td>
								<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
									<span style="font-size: 2rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
								</td>
								<td style="width: 6.5cm; height: 1cm; vertical-align: top; padding: 10px 10px 10px 3px;">
									<table>
										<tr>
											<td style="width:2cm;"><b>Nomor</b></td>
											<td>: &nbsp; <?= $model->nomor_dokumen; ?></td>
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
				<?php if($model->jenis_dokumen == "Nota Perusahaan"){ ?>
				<tr>
					<td colspan="5" style="padding: 8px; background-color: #F1F4F7;">
						<table style="width: 100%">
							<tr>
								<td style="vertical-align: top; padding-left: 10px;">	
									<table>
										<tr>
											<td style="width: 3.2cm; vertical-align: top;"><b>Customer</b></td>
											<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
											<td style="width: 6.5cm; vertical-align: top;">
												<?php echo $model->cust->cust_an_nama ?>
											</td>
											<td style="width: 1cm;"></td>
										</tr>
										<tr>
											<td style="vertical-align: top;"><b>Alamat</b></td>
											<td style="vertical-align: top;"><b>:</b></td>
											<td style="vertical-align: top;"><?= $model->cust->cust_an_alamat ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;"><b>Nopol Kendaraaan</b></td>
											<td style="vertical-align: top;"><b>:</b></td>
											<td style="vertical-align: top;"><?= $model->kendaraan_nopol ?></td>
										</tr>
									</table>
								</td>
								<td style="vertical-align: top; padding-left: 10px;">
									<table>
										<tr>
											<td style="width: 3.2cm; vertical-align: top;"><b>Jenis Produk</b></td>
											<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
											<td style="width: 5.5cm; vertical-align: top;"><?= $model->jenis_produk ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;"><b>Alamat Bongkar</b></td>
											<td style="vertical-align: top;"><b>:</b></td>
											<td style="vertical-align: top;"><?= $model->alamat_bongkar ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;"><b>Masa Berlaku</b></td>
											<td style="vertical-align: top;"><b>:</b></td>
											<td style="vertical-align: top;">
												<?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->masaberlaku_awal) ?> -
												<?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->masaberlaku_akhir) ?>&nbsp; 
												<i>(<?= (abs(strtotime($model->masaberlaku_akhir) - strtotime($model->masaberlaku_awal))/86400)+1 ." Hari)</i>"; ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php } ?>
				<tr style="height: 0.5cm; border-bottom: solid 1px #000; border-top: solid 1px #000;">
					<td rowspan="2" style="width: 7cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Nama Produk</center></b></td>
					<td rowspan="2" style="width: 5.5cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Dimensi</center></b></td>
					<td colspan="2" style="width: 5cm; border-right: solid 1px #000;"><b><center>Jumlah</center></b></td>
					<td rowspan="2" style="width: 2.5cm; vertical-align: middle;"><b><center>Keterangan</center></b></td>
				</tr>
				<tr style="border-bottom: solid 1px #000;">
					<td style="width: 2.5cm; border-right: solid 1px #000;"><b><center>Qty</center></b></td>
					<td style="width: 2.5cm; border-right: solid 1px #000;"><b><center>Volume (M<sup>3</sup>)</center></b></td>
				</tr>
				<?php
				$max = 8;
				if($model->jenis_dokumen == "Nota Perusahaan"){
					if(count($modDetail) > $max){
						$max = 35;
					}
				}else{
					$max = 35;
				}
				$total_besar = 0;
				$total_kecil = 0;
				$total_kubik = 0;
				$trtotal = 0;
				?>
				<?php foreach($modDetail as $i => $detail){
						$total_besar += $detail->qty_besar;
						$total_kecil += $detail->qty_kecil;
						$total_kubik += $detail->kubikasi;
                        $modSPMDetail = \app\models\TSpmKoDetail::findOne(['spm_ko_id'=>$model->spm_ko_id,'produk_id'=>$detail->produk_id]);
                        if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                            $modPaletKD = Yii::$app->db->createCommand("
                                        SELECT * FROM t_terima_jasa
                                        WHERE op_ko_id = '{$model->spmKo->op_ko_id}' AND produk_jasa_id = {$detail->produk_id} AND nomor_palet IN(".(!empty($modSPMDetail)?$modSPMDetail->keterangan:"'3'").")
                                    ")->queryAll();
                        }
                        
						$modRandom = Yii::$app->db->createCommand("
							SELECT t_op_ko_random.* FROM t_op_ko_random
							JOIN t_op_ko_detail ON t_op_ko_detail.op_ko_detail_id = t_op_ko_random.op_ko_detail_id
							WHERE op_ko_id = '{$model->spmKo->op_ko_id}' AND t_op_ko_detail.produk_id = {$detail->produk_id}
							")->queryAll();
						if(count($modRandom)>0){ 
							foreach($modRandom as $ii => $random){
							$detail->attributes = $random; $trtotal = $trtotal+1 ?>
							<?php
							$dotted = "";
							if(($ii!=0)){
								if($modRandom[($ii-1)]['nomor_produksi']!=$random['nomor_produksi']){
									$res = $detail->produk->NamaProduk." <b>".$random['nomor_produksi']." :</b> ";
									$dotted = "border-top: 2px dotted #999";
								}else{
									$res = "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
								}
							}else{
								$res = $detail->produk->NamaProduk." <b>".$random['nomor_produksi']." :</b> ";
							}
							?>
							<tr>
								<td style="padding: 2px 5px; border-right: 1px solid black; <?= $dotted ?>">
									<?= $res; ?>
								</td>
								<td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>">
									<?= " - ".$random['t']." ".$random['t_satuan']." X ".$random['l']." ".$random['l_satuan']." X ".$random['p']." ".$random['p_satuan']; ?>
								</td>
								<td style="padding: 2px 5px; border-right: solid 1px #000; text-align: right; <?= $dotted ?>">
									<?= app\components\DeltaFormatter::formatNumberForUserFloat($random['qty_kecil'])." (".$random['satuan_kecil'].")"; ?>
								</td>
								<td style="padding: 2px 5px; border-right: solid 1px #000; text-align: right; <?= $dotted ?>">
									<?= app\components\DeltaFormatter::formatNumberForUserFloat($random['kubikasi']); ?>
								</td>
								<td style="padding: 2px 5px;">
									<?= $detail->keterangan ?>
								</td>
							</tr>
						<?php } ?>
					<?php }else{ $trtotal = $trtotal+1; ?>
						<tr>
							<td style="padding: 2px 5px; border-right: 1px solid black;">
                                <?php 
                                if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                                    echo $detail->produkJasa->nama;
                                }else{
                                    echo $detail->produk->produk_nama;
                                }
                                ?>
                            </td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php 
                                if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                                    foreach($modPaletKD as $iii => $paletKD){
                                        echo " - ".$paletKD['t']." ".$paletKD['t_satuan']." x ".$paletKD['l']." ".$paletKD['l_satuan']." x ".$paletKD['p']." ".$paletKD['p_satuan'];
                                        echo "<br>";
                                    }
                                }else{
                                    echo "<span>".$detail->produk->produk_dimensi."</span>";
                                }
                                ?>
								
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
								<span style="float: right;">
                                    <?php
                                    if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                                        foreach($modPaletKD as $iii => $paletKD){
                                            echo $paletKD['qty_kecil']." (".$paletKD['satuan_kecil'].")";
                                            echo "<br>";
                                        }
                                    }else{
                                        echo app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil). "<i>(".$detail->satuan_kecil .")</i>";
                                    }
                                    ?>
								</span>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
								<span style="float: right">
                                    <?php
                                    if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                                        foreach($modPaletKD as $iii => $paletKD){
                                            echo number_format($paletKD['kubikasi'],4);
                                            echo "<br>";
                                        }
                                    }else{
                                        echo \app\components\DeltaFormatter::formatNumberForUserFloat($detail->kubikasi);
                                    }
                                    ?>
								</span> 
							</td>
							<td style="padding: 2px 5px;">
								<?= $detail->keterangan ?>
							</td>
						</tr>
					<?php } ?>
				<?php } ?>
				<?php
				$max = $max-$trtotal;
				$trkosong = '<tr><td style="padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px;">&nbsp;</td></tr>';
				for($i=0;$i<$max;$i++){ echo $trkosong; }
				?>
				<tr style="border-top: solid 1px #000; background-color: #F1F4F7;" >
					<td colspan="2" class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>TOTAL</b> &nbsp;</td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
						<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil) ?> <i>(<?= $modDetail[0]->satuan_kecil ?>)</i>
					</b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
						<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubik) ?>
					</b></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr style="border-bottom: solid 1px transparent;">
		<td colspan="3" style=" border-top: solid 1px transparent;">
			<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
				<tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
					<td rowspan="2" style="text-align: justify;  line-height: 1; vertical-align: top; padding: 8px; border-right: solid 1px transparent; border-bottom: solid 1px transparent;">
						<?php if($model->jenis_dokumen == "Nota Perusahaan"){ ?>
							<span style="font-size: 1.6rem; font-weight: 600; margin-bottom: 10px;"><?= strtoupper($company->name); ?></span><br style="line-height: 1.5">
							<span style="font-size: 0.9rem;"><?= $company->alamat; ?></span>
						<?php } ?>
					</td>
					<td rowspan="3" style="width: 8cm; text-align: center; vertical-align: bottom;">
						<?php
						if($model->jenis_dokumen == "Nota Perusahaan"){
							echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/new-sertifikat.png" alt="" class="logo-default" style="width: 100%; margin-bottom: -15px;"> 	&nbsp;'; 
						}
						?>
					</td>
					<td style="width: 4.5cm; vertical-align: middle; background-color: #F1F4F7; padding: 3px;">Diterbitkan Oleh</td>
				</tr>
				<tr>
					<td style="height: 1.75cm; font-size:1rem; text-align: left; vertical-align: bottom; padding: 3px; border-right: solid 1px transparent;">
						&nbsp; Tgl : <?= date("d/m/Y") ?>
					</td>
				</tr>
				<tr>
					<td style="text-align: left; vertical-align: bottom; padding-bottom: 2px; padding-left: 3px; border-right: solid 1px transparent; font-size: 1rem;">
						<?php
						echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
						echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
						?>
					</td>
					<td style="background-color: #F1F4F7; height: 20px; vertical-align: middle;  border-right: solid 1px transparent; font-weight: bold; line-height: 0.9; padding: 3px;">
						<?php
						if(!empty($model->petugas_legalkayu_id)){
							echo "<span style='font-size:0.9rem'>".$model->petugasLegalkayu->pegawai->pegawai_nama."</span><br>";
							echo "<span style='font-size:0.9rem'>Reg No. (".$model->petugasLegalkayu->noreg.")</span>";
						}
						?><br>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">
				<?php
				if($model->jenis_dokumen == "Nota Perusahaan"){
					echo "CWM-FK-TUK-01-0";
				}else if($model->jenis_dokumen == "DKO"){
					echo "CWM-FK-TUK-02-0";
				}else if($model->jenis_dokumen == "DKB"){
					echo "CWM-FK-TUK-03-0";
				}
				?>
			</span>
		</td>
	</tr>
</table>
<?php $this->registerJs("
    setpagebreak();
", yii\web\View::POS_READY); ?>
<script>
function setpagebreak(){
	var pagerow = [50,100,150];
	$.each(pagerow,function(index,value){
		$("#table-detail > tbody > tr:eq( "+value+" )").attr('style','page-break-after:always;');
		$("<tr><td colspan='8' style='height:50px;'>&nbsp;</td></tr>").insertAfter("#table-detail > tbody > tr:eq( "+(value)+" )");
		$("<tr>"+( $("#table-detail > tbody > tr:eq( 2 )").html() )+"</tr>"+"<tr>"+( $("#table-detail > tbody > tr:eq( 3 )").html() )+"</tr>").insertAfter("#table-detail > tbody > tr:eq( "+(value+1)+" )");
	});
}
</script>