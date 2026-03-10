<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
if(isset($_GET['caraprint'])){
    if($_GET['caraprint'] == "EXCEL"){
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
        header('Cache-Control: max-age=0');
        $header = "";
    }
}
$tablewidth = "20";
$modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
$total_all_pcs = 0;
$total_all_volume = 0;
$total_all_gross = 0;
$total_all_nett = 0;
if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
//	$satuanbesar = "Crate";
	$satuanbesar = "Bdl(s)";
}else{
	$satuanbesar = "Bdl(s)";
}
?>
<style>
table{
	font-size: 1.1rem;
}
table#table-detail{
	font-size: 1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<table style="width: <?= $tablewidth ?>cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="width: 3cm; text-align: center; vertical-align: middle; padding: 0px; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="width: 10cm; text-align: left; vertical-align: top; padding: 5px; line-height: 1.1;">
						<span style="font-size: 1.3rem; font-weight: 600"><?= $modCompany->name; ?></span><br>
						<span style="font-size: 1rem;"><?= $modCompany->alamat; ?></span><br>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="border-bottom: solid 1px transparent;">
			<table style="width: 100%;" border="0">
				<tr style="">
					<td style="width: 5cm; text-align: left; vertical-align: middle; height: 1cm; border-right: solid 1px transparent;"></td>
					<td style="text-align: center; vertical-align: top; padding: 5px; line-height: 1.1;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
					</td>
					<td style="width: 5cm; height: 1cm; vertical-align: top;">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="width: 50%; height: 8cm; vertical-align: top; padding: 5px 10px; background-color: #F1F4F7; border-right: solid 1px transparent;">
			<table style="width: 100%;">
				<tr>
					<td style="width: 2.5cm; vertical-align: top;"><b>Shipper</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo !empty($modPackinglist->shipper)?str_replace("\n", "<br>", $modPackinglist->shipper):"";?></td>
				</tr>
				<?php if(!empty($modPackinglist->notify_party)){ ?>
				<tr>
					<td style="width: 2.5cm; vertical-align: top;"><b>Applicant</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top; line-height: 1.3;"><?php echo strtoupper($modPackinglist->cust->cust_an_nama)."<br>".strtoupper($modPackinglist->cust->cust_an_alamat);?></td>
				</tr>
				<tr>
					<td style="width: 2.5cm; vertical-align: top;"><b>Notify Party</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top; line-height: 1.3;"><?php echo strtoupper($modPackinglist->notifyParty->cust_an_nama)."<br>".strtoupper($modPackinglist->notifyParty->cust_an_alamat);?></td>
				</tr>
				<?php }else{ ?>
				<tr>
					<td style="width: 2.5cm; vertical-align: top;"><b>Shipment To</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top; line-height: 1.3;"><?php echo strtoupper($modPackinglist->cust->cust_an_nama)."<br>".strtoupper($modPackinglist->cust->cust_an_alamat);?></td>
				</tr>
				<?php } ?>
				<tr><td style="vertical-align: top;">&nbsp;</td> </tr>
				<tr>
					<td style="width: 2cm; vertical-align: top;"><b>Port of Loading</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo strtoupper((!empty($modPackinglist->port_of_loading)?$modPackinglist->port_of_loading:"<i>TBA</i>"));?></td>
				</tr>
				<tr>
					<td style="width: 2cm; vertical-align: top;"><b>Final Destination</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo strtoupper((!empty($modPackinglist->final_destination)?$modPackinglist->final_destination:"<i>TBA</i>"));?></td>
				</tr>
				<tr>
					<td style="width: 2cm; vertical-align: top;"><b>Vessel</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo strtoupper((!empty($modPackinglist->vessel)?$modPackinglist->vessel:"<i>TBA</i>"));?></td>
				</tr>
				<?php if(!empty($modPackinglist->mother_vessel)){ ?>
				<tr>
					<td style="width: 2cm; vertical-align: top;"><b>Mother Vessel</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo strtoupper($modPackinglist->mother_vessel);?></td>
				</tr>
				<?php } ?>
				<tr>
					<td style="width: 2cm; vertical-align: top;"><b>ETD</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo strtoupper((!empty($modPackinglist->etd)?app\components\DeltaFormatter::formatDateTimeEn($modPackinglist->etd):"<i>TBA</i>"));?></td>
				</tr>
				<tr>
					<td style="width: 2cm; vertical-align: top;"><b>ETA</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo strtoupper((!empty($modPackinglist->eta)?app\components\DeltaFormatter::formatDateTimeEn($modPackinglist->eta):"<i>TBA</i>"));?></td>
				</tr>
				<tr>
					<td style="width: 2cm; vertical-align: top;"><b>Origin</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo strtoupper((!empty($modPackinglist->origin)?$modPackinglist->origin:"<i>TBA</i>"));?></td>
				</tr>
<!--				<tr>
					<td style="width: 2cm; vertical-align: top;"><b>Harvesting Area</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php // echo strtoupper((!empty($modPackinglist->harvesting_area)?$modPackinglist->harvesting_area:"<i>TBA</i>"));?></td>
				</tr>-->
			</table>
		</td>
		<td style="width: 50%; vertical-align: top; padding: 10px; background-color: #F1F4F7;">
			<table style="width: 100%;">
				<tr>
					<td style="width: 2.7cm; vertical-align: top;"><b>Invoice No.</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><b><?php echo $model->nomor;?></b></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>Invoice Date</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo strtoupper(app\components\DeltaFormatter::formatDateTimeEn($model->tanggal));?></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>Contract No.</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><b><?php echo $modOpEx->nomor_kontrak;?></b></td>
				</tr>
				<tr>
					<td style="width: 2cm; vertical-align: top;"><b>Hs Code</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo strtoupper((!empty($modPackinglist->hs_code)?$modPackinglist->hs_code:"<i>TBA</i>"));?></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>SVLK No.</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo (!empty($modPackinglist->svlk_no)?$modPackinglist->svlk_no:"<i>TBA</i>");?></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>V-Legal No.</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo (!empty($modPackinglist->vlegal_no)?$modPackinglist->vlegal_no:"<i>TBA</i>");?></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>Statistic Code</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php echo strtoupper((!empty($modPackinglist->static_product_code)?$modPackinglist->static_product_code:"<i>TBA</i>"));?></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>Goods Description</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;">
						<?php echo !empty($modPackinglist->goods_description)?str_replace("\n", "<br>", $modPackinglist->goods_description):"";?>
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>Payment Method</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php 
						if(!empty($model->payment_method)){
							$pm = $model->payment_method;
							if($model->payment_method== "TT"){
								$pm = "TELEGRAPHIC TRANSFER";
							}
						}else{
							$pm = "TBA";
						}
						echo $pm;
						echo strtoupper((!empty($model->payment_method_reff)?"(".$model->payment_method_reff.")":""));
					?></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>Term of Price</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><?php 
						echo strtoupper((!empty($model->term_of_price)?$model->term_of_price:"<i>TBA</i>"))." ";
					?></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>Cont. Code / Seal</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top; font-size: 1rem;">
						<?php
						$modcs = Yii::$app->db->createCommand("SELECT container_no, container_kode, seal_no FROM t_packinglist_container 
																WHERE packinglist_id = {$model->packinglist_id}
																GROUP BY 1,2,3 ORDER BY 1
															")->queryAll();
						if(count($modcs)){
							foreach($modcs as $i => $cs){
								echo "Container No. ".$cs['container_no']." : ";
								echo (!empty($cs['container_kode'])?$cs['container_kode']:"TBA")." / ";
								echo (!empty($cs['seal_no'])?$cs['seal_no']:"TBA")."<br>";
							}
						}else{
							echo "<i>TBA</i>";
						}
						?>
					</td>
				</tr>
				<?php if(!empty($model->notes)){ ?>
				<tr>
					<td style="vertical-align: top;"><b>Notes</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
						<td style="vertical-align: top;"><?= $model->notes ?></td>
				</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
				<thead>
					<tr style="border-bottom: solid 1px #000;">
						<td style="width:  0.5cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>No</center></b></td>
						<td style="width: 4.5cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Product Name</center></b></td>
						<?php if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){ $colspan_footer="6"; ?>
						<td style="width: 1.2cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle; line-height: 1"><b><center>Glue</center></b></td>
						<?php }else{ $colspan_footer="5"; } ?>
						<td style="width: 1.5cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle; line-height: 1"><b><center>Grade</center></b></td>
						<td style="width: 4cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle; line-height: 1"><b><center>Dimension</center></b></td>
						<td style="width: 2cm; padding: 5px; border-right: solid 1px #000; line-height: 1; vertical-align: middle;"><b><center>Unit Price<br>(<?= $model->mata_uang ?>/M<sup>3</sup>)</center></b></td>
						<td style="width: 1.2cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center><?= $satuanbesar ?></center></b></td>
						<td style="width: 1.2cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Pcs</center></b></td>
						<td style="width: 1.4cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>M<sup>3</sup></center></b></td>
						<td style="padding: 5px; vertical-align: middle;"><b><center>Amount</center></b></td>
					</tr>
				</thead>
				<tbody>
					<?php
					$total = 0; $total_palet=0; $total_pcs=0; $total_m3 = 0; $total_row=0;
					if(count($modDetails)>0){
						foreach($modDetails as $ii => $detail){
							
							$tebal = ""; $lebar = ""; $tinggi = "";
							if($detail->produk->produk_t == 0){
								$minmax = Yii::$app->db->createCommand("SELECT bundles_no,MIN(thick), MAX(thick) FROM t_packinglist_container 
																		  WHERE packinglist_id = {$modPackinglist->packinglist_id} 
																			AND grade = '{$detail->produk->grade}' 
																			AND jenis_kayu = '{$detail->produk->jenis_kayu}' 
																			AND profil_kayu = '{$detail->produk->profil_kayu}'
                                                                                                                                                            GROUP BY 1
                                                                                                                HAVING COUNT(bundles_no)>1")->queryOne();
								if(!empty($minmax)){
									if(($minmax['min'] == $minmax['max'])){
										$tebal = $minmax['min']." ".$detail->produk->produk_t_satuan;
									}else{
										$tebal = $minmax['min'].$detail->produk->produk_t_satuan."~".$minmax['max'].$detail->produk->produk_t_satuan;
									}
								}
							}else{
								$tebal = $detail->produk->produk_t." ".$detail->produk->produk_t_satuan;
							}
							if($detail->produk->produk_l == 0){
								$minmax = Yii::$app->db->createCommand("SELECT bundles_no,MIN(width), MAX(width) FROM t_packinglist_container 
																		  WHERE packinglist_id = {$modPackinglist->packinglist_id} 
																			AND grade = '{$detail->produk->grade}' 
																			AND jenis_kayu = '{$detail->produk->jenis_kayu}' 
																			AND profil_kayu = '{$detail->produk->profil_kayu}'
                                                                                                                                                            GROUP BY 1
                                                                                                                HAVING COUNT(bundles_no)>1")->queryOne();
								if(!empty($minmax)){
									if(($minmax['min'] == $minmax['max'])){
										$lebar = $minmax['min']." ".$detail->produk->produk_l_satuan;
									}else{
										$lebar = $minmax['min'].$detail->produk->produk_l_satuan."~".$minmax['max'].$detail->produk->produk_l_satuan;
									}
								}
							}else{
								$lebar = $detail->produk->produk_l." ".$detail->produk->produk_l_satuan;
							}
							if($detail->produk->produk_p == 0){
								$minmax = Yii::$app->db->createCommand("SELECT bundles_no,MIN(length), MAX(length) FROM t_packinglist_container 
																		  WHERE packinglist_id = {$modPackinglist->packinglist_id} 
																			AND grade = '{$detail->produk->grade}' 
																			AND jenis_kayu = '{$detail->produk->jenis_kayu}' 
																			AND profil_kayu = '{$detail->produk->profil_kayu}'
                                                                                                                                                            GROUP BY 1
                                                                                                                HAVING COUNT(bundles_no)>1")->queryOne();
								if(!empty($minmax)){
									if(($minmax['min'] == $minmax['max'])){
										$panjang = $minmax['min']." ".$detail->produk->produk_p_satuan;
									}else{
										$panjang = $minmax['min'].$detail->produk->produk_p_satuan."~".$minmax['max'].$detail->produk->produk_p_satuan;
									}
								}
							}else{
								$panjang = $detail->produk->produk_p." ".$detail->produk->produk_p_satuan;
							}
							$dimensi = $tebal." x ".$lebar." x ".$panjang;
							
							echo "<tr>";
							echo	"<td style='text-align:center; padding: 2px; border-right: solid 1px #000; vertical-align: top;'>".($ii+1)."</td>";
							echo	"<td style='text-align:left; padding: 2px; border-right: solid 1px #000; vertical-align: top;'>".
										(!empty($detail->keterangan)? $detail->keterangan : $detail->produk->produk_nama)
									."</td>";
							if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
								echo	"<td style='text-align:center; padding: 2px; border-right: solid 1px #000; vertical-align: top;'>".
											$detail->produk->glue
										."</td>";
							}
							echo	"<td style='text-align:center; padding: 2px; border-right: solid 1px #000; vertical-align: top;'>".
										$detail->produk->grade
									."</td>";
							echo	"<td style='text-align:left; padding: 2px; border-right: solid 1px #000; vertical-align: top;'>".$dimensi."</td>";
							echo	"<td style='padding: 2px; border-right: solid 1px #000; vertical-align: top;'>".
										"<span class='pull-left' style='padding-left:2px;'>".$model->mata_uang."</span>".
										"<span class='pull-right'>".number_format($detail->harga_jual,2)."</span>"
									."</td>";
							echo	"<td style='text-align:center; padding: 2px; border-right: solid 1px #000; vertical-align: top;'>".
										number_format($detail->qty_besar)
									."</td>";
							echo	"<td style='text-align:right; padding: 2px; border-right: solid 1px #000; vertical-align: top;'>".
										number_format($detail->qty_kecil)
									."</td>";
							echo	"<td style='text-align:right; padding: 2px; border-right: solid 1px #000; vertical-align: top;'>".
										number_format($detail->kubikasi_display,4)
									."</td>";
//							$xcvxcv = floor( ($detail->harga_jual * $detail->kubikasi_display) * 1000) / 1000;
							$xcvxcv = $detail->harga_jual * $detail->kubikasi_display;
							if(strlen(substr(strrchr($xcvxcv, "."), 1)) > 2){
                                // START KEBIJAKAN Perubahan Pembulatan dari Round Up ke Round Per tgl 14 Sept 2019
                                $tgl = date('Y-m-d', strtotime(\app\components\DeltaFormatter::formatDateTimeForDb($model->tanggal)));
                                $tgl_kebijakan = date('Y-m-d', strtotime("2019-09-13"));
                                if( $tgl > $tgl_kebijakan ){
                                    $xcvxcv = round($xcvxcv,2);
                                }else{
                                    $xcvxcv = \app\components\DeltaFormatter::roundUp($xcvxcv, 2);
                                }
                                // END KEBIJAKAN
							}else{
								$xcvxcv = round($xcvxcv,2);
							}
							echo	"<td style='padding: 2px; vertical-align: top;'>".
										"<span class='pull-left' style='padding-left:2px;'>".$model->mata_uang."</span>".
										"<span class='pull-right'>".number_format($xcvxcv,2)."</span>"
									."</td>";
							echo "</tr>";
							$total_row = $total_row+1;
							$total_palet += $detail->qty_besar;
							$total_pcs += $detail->qty_kecil;
							$total_m3 += $detail->kubikasi_display;
							$total += $xcvxcv;
						}
					}
					?>
				</tbody>
				<tfoot>
					<tr style="border-top: solid 1px #000;">
						<td colspan="<?= $colspan_footer ?>" style="font-size: 1.1rem; padding: 3px; text-align: right; border-right: solid 1px #000;"><b>Total</b> &nbsp; </td>
						<td style="padding: 3px; text-align: center; border-right: solid 1px #000;"><b><?= number_format($total_palet) ?></b></td>
						<td style="padding: 3px; text-align: right; border-right: solid 1px #000;"><b><?= number_format($total_pcs) ?></b></td>
						<td style="padding: 3px; text-align: right; border-right: solid 1px #000;"><b><?= number_format($total_m3,4) ?></b></td>
						<td style="padding: 3px; text-align: right;">
							<b class="pull-left" style="padding-left:2px;"><?= $model->mata_uang ?></b>
							<b class="pull-right"><?= number_format($total,2) ?></b>
						</td>
					</tr>
					<tr style="border-top: solid 1px #000;">
						<td colspan="8" style="font-size: 1.1rem; padding: 3px; text-align: left;"><i>
							<b>Say : </b>
							<?= \app\components\DeltaFormatter::formatNumberTerbilangDollar( \app\components\DeltaFormatter::formatNumberForUserFloat($total,2) ) ?>
					</i> </td>
					</tr>
					<tr style="border-top: solid 1px #000; border-bottom: solid 1px transparent;">
						<td colspan="<?= $colspan_footer+2; ?>" style="border-bottom: solid 1px transparent; font-size: 1.1rem; padding: 3px; padding-bottom: 0px; padding-left: 10px; text-align: left;"><b>Thank you for Your business!</b></td>
						<?php if(!empty($model->fob_preview)){ ?>
						<td style="font-size: 1.1rem; padding: 3px; text-align: right; padding-bottom: 0px; "><b>FOB : </b></td>
						<td style="font-size: 1.1rem; padding: 3px; text-align: right; padding-bottom: 0px; ">
							<b class="pull-left" style="padding-left:2px;"><?= $model->mata_uang ?></b>
							<b class="pull-right"><?= (!empty($model->fob)?\app\components\DeltaFormatter::formatNumberForUserFloat($model->fob,2):0);  ?></b>
						</td>
						<?php } ?>
					</tr>
				</tfoot>
			</table>
		</td>
	</tr>
	<tr style="border: solid 1px #000; border-top: solid 1px transparent; width:<?= $tablewidth ?>cm; bottom: 10px;">
		<td colspan="3" style="">
			<table style="width: 100%; font-size: 1.1rem;" border="0">
				<tr style="height: 0.5cm;">
					<td style="vertical-align: bottom; border-bottom: solid 1px transparent; text-align: left; line-height: 1.3; padding-left: 10px;">
						<!--<span style="font-size: 1.2rem;"><b>Thank you for Your bussiness!</b></span><br>-->
						<span style="font-size: 1.1rem;">The entire amount should be paid through our bank account :</span>
					</td>
					<td style="vertical-align: bottom; width: 4cm; text-align: center;"><?= $modCompany->name ?></td>
				</tr>
				<tr>
					<td style=" padding-left: 15px; line-height: 1.3; ">
						<table style="" border="0">
							<tr>
								<td style="width:2.5cm; font-size: 1rem;">Name</td>
								<td style="width:0.25cm; font-size: 1rem;">:</td>
								<td style="width:5.75cm; font-size: 1rem;"><b>PT. CIPTA WIJAYA MANDIRI</b></td>
							</tr>
							<tr>
								<td style="font-size: 1rem;">Our Bank</td>
								<td style="font-size: 1rem;">:</td>
								<td style="font-size: 1rem;"><b>PT. Bank Central Asia, Tbk.</b></td>
							</tr>
							<tr>
								<td style="font-size: 1rem;">Bank of</td>
								<td style="font-size: 1rem;">:</td>
								<td style="font-size: 1rem;"><b>BCA Pemuda, Semarang</b></td>
							</tr>
							<tr>
								<td style="font-size: 1rem; vertical-align: top;">Bank Address</td>
								<td style="font-size: 1rem; vertical-align: top;">:</td>
								<td style="font-size: 1rem;"><b>Jl. Pemuda No. 90-92, Semarang 50133<br>Jawa Tengah, Indonesia</b></td>
							</tr>
							<tr>
								<td style="font-size: 1rem;">Account Number</td>
								<td style="font-size: 1rem;">:</td>
								<td style="font-size: 1rem;"><b>009.635.5.666</b></td>
							</tr>
							<tr>
								<td style="font-size: 1rem;">Bank Swift Code</td>
								<td style="font-size: 1rem;">:</td>
								<td style="font-size: 1rem;"><b>CENAIDJA</b></td>
							</tr>
						</table>
					</td>
					<td style="vertical-align: bottom; line-height: 1;  text-align: center;">
						<?php
						if(!empty($model->disetujui)){
							echo "<span style='font-size:0.9rem'><b><u> ". $model->disetujui0->pegawai_nama." </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>Head of Finance Accounting </span>";
						}
						?>
					</td>
				</tr>
				<?php 
				$max = 14; 
				$blankspace = $max - $total_row; 
				?>
				<tr><td style="height: <?= (0.5*$blankspace) ?>cm">&nbsp;</td></tr>
				<tr>
					<td style="vertical-align: bottom; height: 1.5cm;" colspan="2">
						<table style="width: 100%;">
							<tr>
								<td style="vertical-align: bottom; font-size: 0.9rem; padding:3px;">
									<?php
									echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
									echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
									?>
								</td>
								<td style="text-align: right; padding:3px;">
									<?php // echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/sertifikasi_mutu.jpg" alt="" class="logo-default" style="width: 4.5cm; margin-top: 10px;"> 	&nbsp;'; ?>
									<?php // echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/logo_sertifikasi_kayu.jpg" alt="" class="logo-default" style="width: 4.5cm;">'; ?>
									<?php echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/new-sertifikat.png" alt="" class="logo-default" style="width: 8cm;">'; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<span style="page-break-after: always;">&nbsp;</span>
