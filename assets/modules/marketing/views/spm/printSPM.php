<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$kode = $model->kode;
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
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
<table style="width: 20cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
				<tr>
					<td colspan="8" style="padding: 5px; border-bottom: solid 1px #000;">
						<table style="width: 100%; " border="0">
							<tr style="">
								<td style="text-align: left; vertical-align: middle; padding: 0px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
									<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
								</td>
								<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
									<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
									<?= $model->opKo->jenis_produk ?>
								</td>
								<td style="width: 5.5cm; height: 1cm; vertical-align: top; padding: 10px;">
									<table>
										<tr>
											<td style="width:2cm;"><b>Kode OP</b></td>
											<td>: &nbsp; <?= $model->opKo->kode; ?></td>
										</tr>
										<tr>
											<td style="width:2cm;"><b>Kode SPM</b></td>
											<td>: &nbsp; <?= $kode; ?></td>
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
					<td colspan="8" style="padding: 8px; background-color: #F1F4F7;  border-bottom: solid 1px #000;">
						<table>
							<tr>
								<td style="width: 60%; vertical-align: top; padding-left: 10px;">
									<table style="width: 100%">
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
											<td style="width: 4.5cm; vertical-align: top;"><b>Tanggal Kirim</b></td>
											<td style="width: 0.5cm; vertical-align: top;"><b>:</b></td>
											<td style="width: 5cm; vertical-align: top;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim) ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;"><b>Rencana Muat</b></td>
											<td style="vertical-align: top;"><b>:</b></td>
											<td style="vertical-align: top;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_rencanamuat) ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;"><b>Nopol Kendaraan</b></td>
											<td style="vertical-align: top;"><b>:</b></td>
											<td style="vertical-align: top;"><?= $model->kendaraan_nopol ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;"><b>Nama Supir</b></td>
											<td style="vertical-align: top;"><b>:</b></td>
											<td style="vertical-align: top;"><?= $model->kendaraan_supir ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;"><b>Waktu Muat Start</b></td>
											<td style="vertical-align: top;"><b>:</b></td>
											<td style="vertical-align: top;"><?= !empty($model->waktu_mulaimuat)?$model->waktu_mulaimuat:"" ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;"><b>Waktu Muat End</b></td>
											<td style="vertical-align: top;"><b>:</b></td>
											<td style="vertical-align: top;"><?= !empty($model->waktu_selesaimuat)?$model->waktu_selesaimuat:"" ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="height: 0.5cm; border-bottom: solid 1px #000;">
					<td rowspan="2" style="width: 8.5cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Produk</center></b></td>
					<td colspan="3" style="border-right: solid 1px #000;"><b><center>Qty Order</center></b></td>
					<td colspan="3" style="border-right: solid 1px #000;"><b><center>Qty Realisasi</center></b></td>
					<td rowspan="2" style="vertical-align: middle;"><b><center>Ket</center></b></td>
				</tr>
				<tr style="border-bottom: solid 1px #000;">
                    <?php if( $model->opKo->jenis_produk == "Limbah" ){ ?>
                        <td style="width: 1cm; border-right: solid 1px #000;"><b><center>-</center></b></td>
                        <td style="width: 2.0cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Beli</center></b></td>
                        <td style="width: 2.2cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Angkut</center></b></td>
                        <td style="width: 1cm; border-right: solid 1px #000;"><b><center>-</center></b></td>
                        <td style="width: 2.2cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Beli</center></b></td>
                        <td style="width: 2.0cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Angkut</center></b></td>
                    <?php }else{ ?>
                        <td style="width: 1cm; border-right: solid 1px #000;"><b><center>Palet</center></b></td>
                        <td style="width: 2.2cm; border-right: solid 1px #000;"><b><center>Satuan Kecil</center></b></td>
                        <td style="width: 1.5cm; border-right: solid 1px #000;"><b><center>M<sup>3</sup></center></b></td>
                        <td style="width: 1cm; border-right: solid 1px #000;"><b><center>Palet</center></b></td>
                        <td style="width: 2.2cm; border-right: solid 1px #000;"><b><center>Satuan Kecil</center></b></td>
                        <td style="width: 1.5cm; border-right: solid 1px #000;"><b><center>M<sup>3</sup></center></b></td>
                    <?php } ?>
				</tr>
				<?php
				$max = 6;
				if(count($modDetail) > $max){
					$max = count($modDetail);
				}
				$total_besar = 0;
				$total_kecil = 0;
				$total_kubik = 0;
				$total_besar_realisasi = 0;
				$total_kecil_realisasi = 0;
				$total_kubik_realisasi = 0;
				?>
				<?php for($i=0;$i<$max;$i++){
					if( count($modDetail) >= ($i+1) ){
						$total_besar += $modDetail[$i]->qty_besar;
						$total_kecil += $modDetail[$i]->qty_kecil;
						$total_kubik += $modDetail[$i]->kubikasi;
						$total_besar_realisasi += $modDetail[$i]->qty_besar_realisasi;
                        $total_kecil_realisasi += $modDetail[$i]->qty_kecil_realisasi;
						$total_kubik_realisasi += $modDetail[$i]->kubikasi_realisasi;
						
						
						$modRandom = Yii::$app->db->createCommand("
									SELECT t_op_ko_random.* FROM t_op_ko_random
									JOIN t_op_ko_detail ON t_op_ko_detail.op_ko_detail_id = t_op_ko_random.op_ko_detail_id
									WHERE op_ko_id = '{$model->op_ko_id}' AND t_op_ko_detail.produk_id = {$modDetail[$i]->produk_id}
									")->queryAll();
						if(count($modRandom)>0){ ?>
							<tr style="line-height: 1;">
								<td style="padding: 2px 5px; border-right: 1px solid black;">
									<?= $modDetail[$i]->produk->NamaProduk." <i><b>Random Size: </b></i>"; ?>
								</td>
								<td style="padding: 2px 5px; border-right: solid 1px #000;">
									<span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_besar) ?></span>
								</td>
								<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp; </td>
								<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp; </td>
								<td style="padding: 2px 5px; border-right: solid 1px #000;">
									<span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_besar) ?></span>
								</td>
								<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp; </td>
								<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp; </td>
								<td style="padding: 2px 5px;">&nbsp; </td>
							</tr>
							<?php foreach($modRandom as $ii => $random){
								$modDetail[$i]->attributes = $random; ?>

								<?php
								$dotted = "";
								if(($ii!=0)){
									if($modRandom[($ii-1)]['nomor_produksi']!=$random['nomor_produksi']){
										$res = $random['nomor_produksi']." : ";
										$dotted = "border-top: 2px dotted #999";
									}else{
										$res = "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
									}
								}else{
									$res = $random['nomor_produksi']." : ";
								}
								?>
								<tr style="line-height: 1;">
									<td style="padding: 2px 5px; border-right: 1px solid black; <?= $dotted ?>">
										<?php echo "&nbsp; <b>".$res."</b> - <span style='font-size:1.1rem;'>".$random['t']." ".$random['t_satuan']." X ".$random['l']." ".$random['l_satuan']." X ".$random['p']." ".$random['p_satuan']."<span><br>"; ?>
									</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>"></td>
									<td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>">
										<span style="float: right;">
											<?= \app\components\DeltaFormatter::formatNumberForUserFloat($random['qty_kecil']) ?> 
											<i>(<?= $random['satuan_kecil'] ?>)</i>
										</span>
									</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>">
										<span style="float: right">
											<?= number_format($modDetail[$i]->kubikasi,4) ?>
										</span> 
									</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>"></td>
									<td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>">
										<span style="float: right">
											<?php echo (!empty($modDetail[$i]->qty_kecil_realisasi))?\app\components\DeltaFormatter::formatNumberForUserFloat($random['qty_kecil']):"" ?> 
											<i><?php echo (!empty($modDetail[$i]->qty_kecil_realisasi))?"(".$random['satuan_kecil'].")":"" ?></i>
										</span>
									</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>">
										<span style="float: right">
											<?php echo (!empty($modDetail[$i]->kubikasi_realisasi))?number_format($random['kubikasi'],4):"" ?>
										</span> 
									</td>
									<td style="padding: 2px 5px; ">
										<span style="float: left"><?= $modDetail[$i]->keterangan ?></span> 
									</td>
								</tr>
						<?php } ?>
						<?php }else{
                                if($model->opKo->jenis_produk=="JasaKD" || $model->opKo->jenis_produk=="JasaGesek" || $model->opKo->jenis_produk=="JasaMoulding"){ 
                                $modJasa = \app\models\MProdukJasa::findOne($modDetail[$i]->produk_id);
                                $modPaletKD = Yii::$app->db->createCommand("
                                                SELECT * FROM t_terima_jasa
                                                WHERE op_ko_id = '{$model->op_ko_id}' AND produk_jasa_id = {$modDetail[$i]->produk_id} AND nomor_palet IN(".$modDetail[$i]->keterangan.")
                                            ")->queryAll();
                        ?>
                                <tr style="line-height: 1;">
                                    <td style="padding: 2px 5px; border-right: 1px solid black;">
                                        <?php echo "<b>".$modJasa->kode.'</b> - '.$modJasa->nama; ?>
                                    </td>
                                    <td style="padding: 2px 5px; border-right: solid 1px #000;">
                                        <span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_besar) ?></span>
                                    </td>
                                    <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp; </td>
                                    <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp; </td>
                                    <td style="padding: 2px 5px; border-right: solid 1px #000;">
                                        <span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_besar) ?></span>
                                    </td>
                                    <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp; </td>
                                    <td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp; </td>
                                    <td style="padding: 2px 5px;">&nbsp; </td>
                                </tr>
                                    <?php 
                                    foreach($modPaletKD as $iii => $paletKD){
                                        $modDetail[$i]->attributes = $paletKD; 
                                        $dotted = "";
                                        if(($iii!=0)){
                                            if($modPaletKD[($iii-1)]['nomor_palet']!=$paletKD['nomor_palet']){
                                                $res = $paletKD['nomor_palet']." : <br> &nbsp; &nbsp;";
                                                $dotted = "border-top: 2px dotted #999";
                                            }else{
                                                $res = "&nbsp;";
                                            }
                                        }else{
                                            $res = $paletKD['nomor_palet']." : <br> &nbsp; &nbsp;";
                                        }
                                    ?>
                                    <tr style="line-height: 1;">
                                        <td style="padding: 2px 5px; border-right: 1px solid black; <?= $dotted ?>">
                                            <?php echo "&nbsp; <b>".$res."</b> - <span style='font-size:1.1rem;'>".$paletKD['t']." ".$paletKD['t_satuan']." X ".$paletKD['l']." ".$paletKD['l_satuan']." X ".$paletKD['p']." ".$paletKD['p_satuan']."<span><br>"; ?>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; <?= $dotted ?>"></td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; vertical-align: bottom; <?= $dotted ?>">
                                            <span style="float: right;">
                                                <?= \app\components\DeltaFormatter::formatNumberForUserFloat($paletKD['qty_kecil']) ?> 
                                                <i>(<?= $paletKD['satuan_kecil'] ?>)</i>
                                            </span>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; vertical-align: bottom; <?= $dotted ?>">
                                            <span style="float: right">
                                                <?= number_format($modDetail[$i]->kubikasi,4) ?>
                                            </span> 
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; vertical-align: bottom;  <?= $dotted ?>"></td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; vertical-align: bottom;  <?= $dotted ?>">
                                            <span style="float: right">
                                                <?php echo (!empty($modDetail[$i]->qty_kecil_realisasi))?\app\components\DeltaFormatter::formatNumberForUserFloat($paletKD['qty_kecil']):"" ?> 
                                                <i><?php echo (!empty($modDetail[$i]->qty_kecil_realisasi))?"(".$paletKD['satuan_kecil'].")":"" ?></i>
                                            </span>
                                        </td>
                                        <td style="padding: 2px 5px; border-right: solid 1px #000; vertical-align: bottom; <?= $dotted ?>">
                                            <span style="float: right">
                                                <?php echo (!empty($modDetail[$i]->kubikasi_realisasi))?number_format($paletKD['kubikasi'],4):"" ?>
                                            </span> 
                                        </td>
                                        <td style="padding: 2px 5px; ">
                                            <span style="float: left"><?= $modDetail[$i]->keterangan ?></span> 
                                        </td>
                                    </tr>
                                    <?php } ?>
                                
                                    
                                <?php }else{ ?>
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
								<td style="padding: 2px 5px; border-right: solid 1px #000; line-height: 1">
									<span style="float: right">
                                        <?= ($model->opKo->jenis_produk=="Limbah")? ( ($modDetail[$i]->satuan_kecil=="Rit")?$modDetail[$i]->satuan_besar:"" ) :number_format($modDetail[$i]->kubikasi,4); ?>
									</span> 
								</td>
								<td style="padding: 2px 5px; border-right: solid 1px #000;">
									<span style="float: right">
										<?php echo ($model->opKo->jenis_produk=="Limbah")? "" :  (!empty($modDetail[$i]->qty_besar_realisasi))?\app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_besar_realisasi):"" ?>
									</span>
								</td>
								<td style="padding: 2px 5px; border-right: solid 1px #000;">
									<span style="float: right">
										<?php echo (!empty($modDetail[$i]->qty_kecil_realisasi))?\app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]->qty_kecil_realisasi):"" ?> 
										<i><?php echo (!empty($modDetail[$i]->qty_kecil_realisasi))?"(".$modDetail[$i]->satuan_kecil.")":"" ?></i>
									</span>
								</td>
								<td style="padding: 2px 5px; border-right: solid 1px #000;">
									<span style="float: right">
										<?php 
                                        if($model->opKo->jenis_produk=="Limbah"){
                                            echo ( ($modDetail[$i]->satuan_kecil=="Rit")?$modDetail[$i]->satuan_besar_realisasi:"" );
                                        }else{
                                            echo (!empty($modDetail[$i]->kubikasi_realisasi))?number_format($modDetail[$i]->kubikasi_realisasi,4):"";
                                        }
                                        ?>
									</span> 
								</td>
								<td style="padding: 2px 5px; ">
									<span style="float: left"><?= $modDetail[$i]->keterangan ?></span> 
								</td>
							</tr>
						<?php } ?>
						<?php } ?>
				<?php }else{ ?>
					<tr>
						<td style="padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
						<td style="padding: 2px 5px;">&nbsp;</td>
					</tr>
				<?php } ?>
				<?php } ?>
				<tr style="border-top: solid 1px #000;" >
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total</b> &nbsp;</td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><?= ($model->opKo->jenis_produk=="Limbah")? "" : \app\components\DeltaFormatter::formatNumberForUserFloat($total_besar) ?></b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><?= ($model->opKo->jenis_produk=="Limbah")? "" : \app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil)." <i>(".$modDetail[0]->satuan_kecil .")</i>" ?> </b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><?= ($model->opKo->jenis_produk=="Limbah")? "" : number_format($total_kubik,4) ?></b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><?= ($model->opKo->jenis_produk=="Limbah")? "" : !empty($total_besar_realisasi)?\app\components\DeltaFormatter::formatNumberForUserFloat($total_besar_realisasi):"" ?></b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><?= ($model->opKo->jenis_produk=="Limbah")? "" : !empty($total_kecil_realisasi)?\app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil_realisasi)." (".(!empty($modDetail[0]->satuan_kecil_realisasi)?$modDetail[0]->satuan_kecil_realisasi:"Pcs").")":"" ?></b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><?= !empty($total_kubik_realisasi)?number_format($total_kubik_realisasi,4):"" ?></b></td>
				</tr>
				<tr style="border-bottom: solid 1px transparent;">
					<td colspan="8" style=" border-top: solid 1px transparent;">
						<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
							<tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
								<td rowspan="3" style="width: 7cm; vertical-align: bottom; text-align: left; font-size: 0.9rem;">
									<?php
									echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
									echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
									?>
								</td>
								<td style="vertical-align: middle; width: 2.8cm; background-color: #F1F4F7;">Customer</td>
								<td colspan="2" style="vertical-align: middle; width: 4.6cm; background-color: #F1F4F7;">Diperiksa Oleh</td>
								<td style="vertical-align: middle; width: 2.8cm; background-color: #F1F4F7;">Dikeluarkan Oleh</td>
								<td style="vertical-align: middle; width: 2.8cm; background-color: #F1F4F7;">Disetujui Oleh</td>
							</tr>
							<tr>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;"></td>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; width: 2.3cm; font-size: 0.8rem; text-align: center;"><i>Checker</i></td>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; width: 2.3cm; font-size: 0.8rem; text-align: center;"><i>Security</i></td>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; font-size: 0.8rem; text-align: center;"><i>Kanit Gudang</i></td>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent; font-size: 0.8rem; text-align: center;"><i>Kanit Adm Marketing</i></td>
							</tr>
							<tr style="background-color: #F1F4F7;">
								<td style="height: 20px; vertical-align: middle;">
									<?php
									if(!empty($model->cust->cust_an_nama)){
										echo "<span style='font-size:0.9rem'></span>";
									}
									?>
								</td>
								<td style="height: 20px; vertical-align: middle; font-size: 0.9rem;">
									<?= ($model->status == \app\models\TSpmKo::REALISASI)?$model->diperiksa0->pegawai_nama:"" ?>
								</td>
								<td style="height: 20px; vertical-align: middle; font-size: 0.9rem;">
									<?= ($model->status == \app\models\TSpmKo::REALISASI)?$model->diperiksaSecurity0->pegawai_nama:"" ?>
								</td>
								<td style="height: 20px; vertical-align: middle; font-size: 0.9rem;">
									<?= (!empty($model->dikeluarkan)?$model->dikeluarkan0->pegawai_nama:\app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_ROCHANDRA)->pegawai_nama) ?>
								</td>
								<td style="height: 20px; vertical-align: middle;  border-right: solid 1px transparent;  ">
									<?php
									if(!empty($model->disetujui)){
										echo "<span style='font-size:0.9rem'>".$model->disetujui0->pegawai_nama."</span>";
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
	
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-08-0</span>
		</td>
	</tr>
</table>
<?php $this->registerJs("
    setpagebreak();
", yii\web\View::POS_READY); ?>
<script>
function setpagebreak(){
	var pagerow = [42,92,142];
	$.each(pagerow,function(index,value){
		$("#table-detail > tbody > tr:eq( "+value+" )").attr('style','page-break-after:always;');
		$("<tr><td colspan='8' style='height:50px;'>&nbsp;</td></tr>").insertAfter("#table-detail > tbody > tr:eq( "+(value)+" )");
		$("<tr>"+( $("#table-detail > tbody > tr:eq( 2 )").html() )+"</tr>"+"<tr>"+( $("#table-detail > tbody > tr:eq( 3 )").html() )+"</tr>").insertAfter("#table-detail > tbody > tr:eq( "+(value+1)+" )");
	});
}
</script>