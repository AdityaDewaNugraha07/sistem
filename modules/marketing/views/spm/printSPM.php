<?php
/* @var $this yii\web\View */

use app\models\MBrgLog;

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
				<?php if( $model->opKo->jenis_produk == "Log" ){ ?>
                    <td colspan="9" style="padding: 8px; background-color: #F1F4F7;  border-bottom: solid 1px #000;">
                <?php } else { ?>
                    <td colspan="8" style="padding: 8px; background-color: #F1F4F7;  border-bottom: solid 1px #000;">
                <?php } ?>
						<table style="width: 100%; " border="0">
							<tr style="">
								<td style="text-align: left; vertical-align: middle; padding: 0px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
									<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
								</td>
								<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
									<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
									<?php 
										if($model->opKo->jenis_produk == 'Log'){
											echo 'Kayu Bulat';
										} else {
											echo $model->opKo->jenis_produk;
										}
									?>
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
				<?php if( $model->opKo->jenis_produk == "Log" ){ ?>
					<td colspan="9" style="padding: 8px; background-color: #F1F4F7;  border-bottom: solid 1px #000;">
				<?php } else { ?>
					<td colspan="8" style="padding: 8px; background-color: #F1F4F7;  border-bottom: solid 1px #000;">
				<?php } ?>
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
													echo $model->cust_alamat ?: $model->cust->cust_pr_alamat ?: $model->cust->cust_an_alamat;
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
				<?php if( $model->opKo->jenis_produk == "Log" ){ ?>
					<td rowspan="2" style="width: 6cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Produk</center></b></td>
					<td rowspan="2" style="width: 2cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Range Diameter</center></b></td>
				<?php } else { ?>
					<td rowspan="2" style="width: 8cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Produk</center></b></td>
				<?php } ?>
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
                    <?php } else if( $model->opKo->jenis_produk == "Log" ){ ?>
                        <td style="width: 1cm; border-right: solid 1px #000;"><b><center>-</center></b></td>
                        <td style="width: 2.0cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Beli</center></b></td>
                        <td style="width: 2.2cm; border-right: solid 1px #000; line-height: 1"><b><center>M<sup>3</sup></center></b></td>
                        <td style="width: 1.5cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Kecil</center></b></td>
                        <td style="width: 2.0cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Beli</center></b></td>
                        <td style="width: 1.5cm; border-right: solid 1px #000; line-height: 1"><b><center>M<sup>3</sup></center></b></td>
					<?php } else{ ?>
                        <td style="width: 1cm; border-right: solid 1px #000;"><b><center>Palet</center></b></td>
                        <td style="width: 2.2cm; border-right: solid 1px #000;"><b><center>Satuan Kecil</center></b></td>
                        <td style="width: 1.5cm; border-right: solid 1px #000;"><b><center>M<sup>3</sup></center></b></td>
                        <td style="width: 1cm; border-right: solid 1px #000;"><b><center>Palet</center></b></td>
                        <td style="width: 2.2cm; border-right: solid 1px #000;"><b><center>Satuan Kecil</center></b></td>
                        <td style="width: 1.5cm; border-right: solid 1px #000;"><b><center>M<sup>3</sup></center></b></td>
                    <?php } ?>
				</tr>
			<!-- START ISI TABEL -->
            <?php if($model->opKo->jenis_produk!=="Log"){ ?>
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
				<?php 
				for($i=0;$i<$max;$i++){
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
									")->queryAll();?>
						<?php if(count($modRandom)>0){ ?>
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
												")->queryAll();?>
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
									<td style="padding: 2px 5px; border-right: 1px solid black;">
										<?php 
											if($model->opKo->jenis_produk=="Limbah"){
												echo $modDetail[$i]->limbah->limbah_kode." - (".$modDetail[$i]->limbah->limbah_produk_jenis.") ".$modDetail[$i]->limbah->limbah_nama;
											}else{
												if($model->opKo->jenis_produk=="Veneer"){													
													// jika vener dengan grade fsc100 maka tampilkan nama ilimiah sesuai dengan jenis log
													$modProduk = \app\models\MBrgProduk::findOne(['produk_id' =>$modDetail[$i]->produk_id]);
													if ($modProduk && stripos($modProduk->grade, 'FSC 100') !== false){
														$modJeniskayu = \app\models\MJenisKayu::findOne(['jenis_produk' => $model->opKo->jenis_produk, 'nama' => $modProduk->jenis_kayu]);
														// Menebalkan teks FSC100 di nama produk
														$produkNama = str_ireplace('FSC100', '<strong>FSC100</strong>', htmlspecialchars($modDetail[$i]->produk->produk_nama));
														echo $produkNama ;
														echo "<br><em>" . htmlspecialchars($modJeniskayu['othername']) . "</em>";
													}else{
														echo $modDetail[$i]->produk->produk_nama;
													}
												}else{
													echo $modDetail[$i]->produk->produk_nama;
												}
											}
										?>
									</td>
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
									<td style="padding: 2px 5px; text-align: center; border-right: solid 1px #000;">
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
				<!-- END OF ISI TABEL -->
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
				<?php } 
                } ?>
				<tr style="border-top: solid 1px #000;" >
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total</b> &nbsp;</td>
					<td class="text-align-center" style="padding: 5px; border-right: solid 1px #000;"><b><?= ($model->opKo->jenis_produk=="Limbah")? "" : \app\components\DeltaFormatter::formatNumberForUserFloat($total_besar) ?></b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;">
						<b>
							<?php 
								if ($model->opKo->jenis_produk=="Limbah"){
									echo "";
								} else {
									echo number_format($total_kecil);
								}
							?>
						</b>
					</td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;">
						<b>
						<?php 
								if ($model->opKo->jenis_produk=="Limbah"){
									echo "";
								} else {
									echo number_format($total_kubik,4);
								}
							?>
						</b>
					</td>
					<td class="text-align-center" style="padding: 5px; border-right: solid 1px #000;">
						<b>
							<?php 
								if ($model->opKo->jenis_produk=="Limbah"){
									echo "";
								} else {
									echo number_format($total_besar_realisasi); //."<i>(".$modDetail[0]->satuan_besar .")</i>";
								}
							?>
						</b>
					</td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;">
						<b>
							<?php 
								if ($model->opKo->jenis_produk=="Limbah"){
									echo "";
								} else {
									echo number_format($total_kecil_realisasi)."<i>(".$modDetail[0]->satuan_kecil .")</i>";
								}
							?>
							<?php //echo ($model->opKo->jenis_produk=="Limbah")? "" : !empty($total_kecil_realisasi)?\app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil_realisasi)." (".(!empty($modDetail[0]->satuan_kecil_realisasi)?$modDetail[0]->satuan_kecil_realisasi:"Pcs").")":"" ?>
						</b>
					</td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;">
						<b>
							<?php 
								if ($model->opKo->jenis_produk=="Limbah"){
									echo "";
								} else {
									echo number_format($total_kubik_realisasi,4);
								}
							?>
						</b>
					</td>
				</tr>
                <!-- BAGIAN LOG -->
                <?php } else { 
                    $total_kubik = 0; $total_kecil_realisasi = 0; $total_kubik_realisasi = 0;
					$produkData = []; $produkDataAlias = []; $produkDataNoAlias = [];
                    foreach($modDetail as $i => $detail){
                        $total_kubik += $detail['kubikasi'];
                        $total_kecil_realisasi += $detail['qty_kecil_realisasi'];
                        $total_kubik_realisasi += $detail['kubikasi_realisasi'];

						// cek aliasnya dulu 
						$modAlias = yii::$app->db->createCommand("SELECT t_po_ko_detail.po_ko_id, t_po_ko_detail.alias
												FROM t_spm_ko_detail 
												JOIN t_spm_ko ON t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id
												JOIN t_op_ko ON t_op_ko.op_ko_id = t_spm_ko.op_ko_id
												JOIN t_po_ko_detail ON t_po_ko_detail.po_ko_id = t_op_ko.po_ko_id 
												LEFT JOIN m_brg_log ON m_brg_log.log_id = t_spm_ko_detail.produk_id  
												WHERE t_spm_ko_detail.spm_kod_id={$detail['spm_kod_id']} AND
												{$detail['produk_id']} = ANY (string_to_array(t_po_ko_detail.produk_id_alias, ',')::int[])
												GROUP BY t_po_ko_detail.po_ko_id, t_po_ko_detail.alias")->queryOne();
						if(empty($modAlias)){
							$modLog = MBrgLog::findOne($detail['produk_id']);
							$modKayu = app\models\MKayu::findOne($modLog->kayu_id);
							$produkDataNoAlias['manual_' . $detail['produk_id']] = [
									'produk' => $modLog->log_nama,
									'diameter' => $modLog->range_awal . '-' . $modLog->range_akhir,
									'kubikasi' => $detail['kubikasi'],
									'qty_kecil_realisasi' => $detail['qty_kecil_realisasi'],
									'kubikasi_realisasi' => $detail['kubikasi_realisasi'],
									'keterangan' => $detail['keterangan']
								];
							if(stripos($produkDataNoAlias['manual_' . $detail['produk_id']]['produk'], 'FSC100') !== false){
								// Menebalkan teks FSC100 di nama produk
								$produkNama = str_ireplace('FSC100', '<strong>FSC100</strong>', htmlspecialchars($produkDataNoAlias['manual_' . $detail['produk_id']]['produk']));
								$produkDataNoAlias['manual_' . $detail['produk_id']]['produk'] =  $produkNama;
								$produkDataNoAlias['manual_' . $detail['produk_id']]['produk'] .= "<br><em>" . htmlspecialchars($modKayu->nama_ilmiah) . "</em>";
							}
						} else {
							if ($modAlias['alias'] == true) {
								$produk_ids[] = $detail['produk_id'];
								$modPo = Yii::$app->db->createCommand("
													SELECT alias, produk_alias, diameter_alias, SUM(t_spm_ko_detail.kubikasi) AS kubikasi,
														SUM(t_spm_ko_detail.qty_kecil_realisasi) AS qty_kecil_realisasi,
														SUM(t_spm_ko_detail.kubikasi_realisasi) AS kubikasi_realisasi, t_spm_ko_detail.keterangan
													FROM t_spm_ko_detail 
													JOIN t_spm_ko ON t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id
													JOIN t_op_ko ON t_op_ko.op_ko_id = t_spm_ko.op_ko_id
													JOIN t_po_ko_detail ON t_po_ko_detail.po_ko_id = t_op_ko.po_ko_id 
														AND t_po_ko_detail.alias = true 	
														AND t_spm_ko_detail.produk_id = ANY(string_to_array(t_po_ko_detail.produk_id_alias, ',')::int[])
													WHERE t_spm_ko.spm_ko_id = {$model->spm_ko_id}
													--AND string_to_array(t_po_ko_detail.produk_id_alias, ',')::int[] && ARRAY[" . implode(',', $produk_ids) . "]
													GROUP BY alias, produk_alias, diameter_alias,t_spm_ko_detail.keterangan
											")->queryAll();

								foreach ($modPo as $row) {
									$key = $row['produk_alias'] . '|' . $row['diameter_alias'];
									$entry = [
											'produk' => $row['produk_alias'],
											'diameter' => $row['diameter_alias'],
											'kubikasi' => $row['kubikasi'],
											'qty_kecil_realisasi' => $row['qty_kecil_realisasi'],
											'kubikasi_realisasi' => $row['kubikasi_realisasi'],
											'keterangan' => $row['keterangan']
									];

									$produkDataAlias[$key] = $entry;
								}
							} else {
								$modLog = MBrgLog::findOne($detail['produk_id']);
								$modKayu = app\models\MKayu::findOne($modLog->kayu_id);
								$produkDataNoAlias['manual_' . $detail['produk_id']] = [
										'produk' => $modLog->log_nama,
										'diameter' => $modLog->range_awal . '-' . $modLog->range_akhir,
										'kubikasi' => $detail['kubikasi'],
										'qty_kecil_realisasi' => $detail['qty_kecil_realisasi'],
										'kubikasi_realisasi' => $detail['kubikasi_realisasi'],
										'keterangan' => $detail['keterangan']
									];
								if(stripos($produkDataNoAlias['manual_' . $detail['produk_id']]['produk'], 'FSC100') !== false){
									// Menebalkan teks FSC100 di nama produk
									$produkNama = str_ireplace('FSC100', '<strong>FSC100</strong>', htmlspecialchars($produkDataNoAlias['manual_' . $detail['produk_id']]['produk']));
									$produkDataNoAlias['manual_' . $detail['produk_id']]['produk'] =  $produkNama;
									$produkDataNoAlias['manual_' . $detail['produk_id']]['produk'] .= "<br><em>" . htmlspecialchars($modKayu->nama_ilmiah) . "</em>";
								}
							}
						}
					}
                        
					$produkData = array_merge($produkDataAlias, $produkDataNoAlias);
					$max = max(6, count($produkData));
					$i = 0;
					foreach ($produkData as $data) {?>
						<tr>
							<td style="padding: 2px 5px; border-right: 1px solid black;">
								<?php echo $data['produk']; ?>
							</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">
								<center>
									<?php 
									if (strpos($data['diameter'], '-') !== false) {
										$diameter = explode('-',$data['diameter']); 
										$range_awal = $diameter[0];
										$range_akhir = $diameter[1] >= 200?' UP':'-'.$diameter[1];
										echo $range_awal . $range_akhir;
									} else {
										echo $data['diameter'];
									}
									?>
								</center>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;"></td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
								<span><center><i>M3</i></center></span>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000; line-height: 1">
								<span style="float: right">
									<?php echo (!empty($data['kubikasi']))?number_format($data['kubikasi'],2):"";?>
								</span>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
								<span style="float: right">
									<?php 
									if($data['qty_kecil_realisasi'] > 0){
										echo $data['qty_kecil_realisasi'] . '<i> (pcs)</i>';
									} else {
										echo '';
									}
									?>
								</span>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
								<span><center><i><?php echo 'M3' ?></i></center></span>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
								<span style="float: right">
									<?php echo (!empty($data['kubikasi_realisasi']))?number_format($data['kubikasi_realisasi'],2):"";?>
								</span> 
							</td>
							<td style="padding: 2px 5px; ">
								<span style="float: left"><?= $data['keterangan'] ?></span> 
							</td>
						</tr>
					<?php } ?>
					<?php for($j = $i; $j < $max; $j++) { ?>
						<tr>
							<td style="padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px;">&nbsp;</td>
						</tr>
					<?php } ?>
					<tr style="border-top: solid 1px #000;" >
						<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total</b> &nbsp;</td>
						<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
						<td class="text-align-center" style="padding: 5px; border-right: solid 1px #000;"></td>
						<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
						<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><?= number_format($total_kubik,2); ?></b></td>
						<td class="text-align-center" style="padding: 5px; border-right: solid 1px #000;">
							<b><?php echo number_format($total_kecil_realisasi)."<i> (pcs)</i>"; ?></b>
						</td>
						<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"></td>
						<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;">
							<b><?php echo number_format($total_kubik_realisasi,2);?></b>
						</td>
					</tr>
					<!-- Tabel Rincian Realisasi Log -->
					<?php if($model->status == 'REALISASI'){ ?>
						<?php if($model->opKo->jenis_produk=="Log"){ ?>
							<tr>
								<td colspan="13" style=" border-top: solid 1px ;">
									<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
										<tr style="height: 0.8cm;  border-right: solid 1px transparent; ">
											<td colspan="13" style="font-size: 13px; text-align: left; vertical-align: bottom; padding-bottom:3px">Rincian Realisasi Log :</td>
										</tr>
										<tr style="height: 0.5cm; border-bottom: solid 1px #000;">
											<td rowspan = "2" style="vertical-align: middle; width: 2.5cm;"><b>QR Code</b><br>Lapangan<br>Grade<br>Batang</td>
											<td rowspan = "2" style="vertical-align: middle; width: 3cm;"><b>Jenis Kayu</b></td>
											<td rowspan = "2" style="vertical-align: middle; width: 2cm;"><b>Range Diameter</b></td>
											<td colspan = "2" style="vertical-align: middle; width: 1.2cm;"><b>Ukuran</b></td>
											<td colspan = "4" style="vertical-align: middle; width: 1.2cm;"><b>Diameter (cm)</b></td>
											<td colspan = "3" style="vertical-align: middle; width: 1.2cm;"><b>Unsur Cacat (cm)</b></td>
											<td rowspan = "2" style="vertical-align: middle; width: 1.2cm;  border-right: solid 1px transparent; "><b>Volume<br>(M<sup>3</sup>)</b></td>
										</tr>
										<tr>
											<td style="vertical-align: middle; width: 1.2cm;"><b>P<br>(m)</b></td>
											<td style="vertical-align: middle; width: 1.2cm;"><b>⌀<br>Rata</b></td>
											<td style="vertical-align: middle; width: 1.2cm;"><b>Ujung<br>1</b></td>
											<td style="vertical-align: middle; width: 1.2cm;"><b>Ujung<br>2</b></td>
											<td style="vertical-align: middle; width: 1.2cm;"><b>Pangkal<br>1</b></td>
											<td style="vertical-align: middle; width: 1.2cm;"><b>Pangkal<br>2</b></td>
											<td style="vertical-align: middle; width: 1.2cm;"><b>P</b></td>
											<td style="vertical-align: middle; width: 1.2cm;"><b>Gb</b></td>
											<td style="vertical-align: middle; width: 1.2cm;"><b>Gr</b></td>
										</tr>
										<?php 
										$sql = Yii::$app->db->createCommand("
													SELECT * FROM t_spm_log JOIN t_spm_ko ON t_spm_ko.kode = t_spm_log.reff_no 
													JOIN m_kayu ON m_kayu.kayu_id = t_spm_log.kayu_id
													WHERE reff_no = '{$model->kode}'
													ORDER BY kayu_nama 
												")->queryAll();
										foreach($sql as $i => $data){
											$log = Yii::$app->db->createCommand(
												"SELECT * FROM m_brg_log WHERE kayu_id = {$data['kayu_id']} AND {$data['diameter_rata']} 
												BETWEEN range_awal AND range_akhir"
											)->queryOne();
											$kayu = \app\models\MKayu::findOne(['kayu_id'=>$data['kayu_id']]);
										?>
											<tr style="height: 0.4cm;  border-right: solid 1px transparent; border-bottom: solid 1px transparent;">
												<td style="text-align: left; width: 1.2cm; padding-left:2px;">
													<b><?= $data['no_barcode']; ?></b><br>
													<?= $data['no_lap']; ?><br>
													<?= $data['no_grade']; ?><br>
													<?= $data['no_btg']; ?>
												</td>
												<td style="text-align: left; width: 4cm;padding-left:2px;"><?= $kayu['group_kayu'] .' - '. $kayu['kayu_nama']; ?></td>
												<td style="width: 1.2cm;">
													<?php
													$range_akhir = $log['range_akhir'] >= 200?' UP':' - '.$log['range_akhir'].'cm';
													echo $log['range_awal'] .'cm'. $range_akhir; 
													?>
												</td>
												<td style="width: 1.2cm;"><?= $data['panjang']; ?></td>
												<td style="width: 1.2cm;"><?= $data['diameter_rata']; ?></td>
												<td style="width: 1.2cm;"><?= $data['diameter_ujung1']; ?></td>
												<td style="width: 1.2cm;"><?= $data['diameter_ujung2']; ?></td>
												<td style="width: 1.2cm;"><?= $data['diameter_pangkal1']; ?></td>
												<td style="width: 1.2cm;"><?= $data['diameter_pangkal2']; ?></td>
												<td style="width: 1.2cm;"><?= $data['cacat_panjang']; ?></td>
												<td style="width: 1.2cm;"><?= $data['cacat_gb']; ?></td>
												<td style="width: 1.2cm;"><?= $data['cacat_gr']; ?></td>
												<td style="width: 1.2cm;"><?= $data['volume']; ?></td>
											</tr>
										<?php } ?>
											<tr style="border-right: solid 1px transparent; border-bottom: solid 1px transparent;">
												<td style="text-align: left; width: 1.2cm; padding-left:2px;">&nbsp;</td>
												<td style="text-align: left; width: 4cm;padding-left:2px;">&nbsp;</td>
												<td style="width: 1.2cm;">&nbsp;</td>
												<td style="width: 1.2cm;">&nbsp;</td>
												<td style="width: 1.2cm;">&nbsp;</td>
												<td style="width: 1.2cm;">&nbsp;</td>
												<td style="width: 1.2cm;">&nbsp;</td>
												<td style="width: 1.2cm;">&nbsp;</td>
												<td style="width: 1.2cm;">&nbsp;</td>
												<td style="width: 1.2cm;">&nbsp;</td>
												<td style="width: 1.2cm;">&nbsp;</td>
												<td style="width: 1.2cm;">&nbsp;</td>
											</tr>
									</table>
								</td>
							</tr>
						<?php } ?>
					<?php } 
				}?>

				<tr style="border-bottom: solid 1px transparent;">
				<?php if( $model->opKo->jenis_produk == "Log" ){ ?>
					<td colspan="9" style=" border-top: solid 1px transparent;">
				<?php } else { ?>
					<td colspan="8" style=" border-top: solid 1px transparent;">
				<?php } ?>
						<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
							<tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
								<td rowspan="3" style="width: 7cm; vertical-align: bottom; text-align: left; font-size: 0.9rem;">
									<?php
									echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
									echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
									?>
								</td>
								<td style="vertical-align: middle; width: 2.8cm; background-color: #F1F4F7; border-left: solid 1px">Customer</td>
								<td colspan="2" style="vertical-align: middle; width: 4.6cm; background-color: #F1F4F7;">Diperiksa Oleh</td>
								<td style="vertical-align: middle; width: 2.8cm; background-color: #F1F4F7;">Dikeluarkan Oleh</td>
								<td style="vertical-align: middle; width: 2.8cm; background-color: #F1F4F7;">Disetujui Oleh</td>
							</tr>
							<tr>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-left: solid 1px;"></td>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; width: 2.3cm; font-size: 0.8rem; text-align: center;"><i>Checker</i></td>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; width: 2.3cm; font-size: 0.8rem; text-align: center;"><i>Security</i></td>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; font-size: 0.8rem; text-align: center;"><i><?= ($model->opKo->jenis_produk == "Log")? 'Kanit Bongkar':'Kanit Gudang' ?></i></td>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent; font-size: 0.8rem; text-align: center;"><i>Kanit Adm Marketing</i></td>
							</tr>
							<tr style="background-color: #F1F4F7;">
								<td style="height: 20px; vertical-align: middle; border-left: solid 1px;">
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
									<?= 
										$kanit = ($model->opKo->jenis_produk == "Log")?\app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_UMAMI)->pegawai_nama:\app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_ROCHANDRA)->pegawai_nama;
										(!empty($model->dikeluarkan)?$model->dikeluarkan0->pegawai_nama: $kanit) 
									?>
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
    window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }    
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