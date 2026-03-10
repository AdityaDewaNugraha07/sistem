<?php
/* @var $this yii\web\View */

use app\models\MBrgBhp;
use app\models\MBrgLog;
use app\models\MKayu;

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
.t {border-top: solid 1px;}
.b {border-bottom: solid 1px;}
.l {border-left: solid 1px;}
.r {border-right: solid 1px;}
</style>
<table style="width: 20cm; margin: 10px;">
    <tr>
        <td rowspan="4" style="width: 90px;" class="text-center t b l">
            <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default text-center" style="width: 80px; margin: 20px;">
        </td>
        <td rowspan="4" colspan="10" class="text-center t b ">
            <span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
			<?php 
			if($model->jenis_produk == 'Log'){
				echo "Kayu Bulat";
			} else {
				echo $model->jenis_produk;
			}
			?>
		</td>
        <td style="width: 110px; padding-left: 30px; font-weight: bold;" class="t"><b>Kode</b></td>
        <td style="width: 110px;" class="t r">: <?= $kode; ?></td>
    </tr>
    <tr>
        <td style="padding-left: 30px; font-weight: bold;"><b>Tanggal</b></td>
        <td class="r">: <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim) ?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td class="r">&nbsp;</td>
    </tr>
    <tr>
        <td class="b">&nbsp;</td>
        <td class="b r">&nbsp;</td>
    </tr>
    <tr style="background-color: #e9e9e9;">
        <td style="vertical-align: top; padding-top: 10px; padding-left: 10px;" class="l"><b>Customer</b></td>
        <td colspan="5" style="width: 240px; vertical-align: top; padding-top: 10px;">
        <?php
        echo $model->cust->cust_an_nama." <br>";
        if(!empty($model->cust->cust_pr_nama)){
            echo $model->cust->cust_pr_nama." <br>";
        }
        echo $model->cust_alamat ?: $model->cust->cust_pr_alamat ?: $model->cust->cust_an_alamat;
        ?>
        </td>
        <td colspan="5" style="vertical-align: top; padding-top: 10px; padding-left: 10px;"><b>Alamat Bongkar</b></b></td>
        <td colspan="2" style="vertical-align: top; padding-top: 10px;" class="r"><?= $model->alamat_bongkar ?></td>
    </tr>
    <tr style="background-color: #e9e9e9;">
        <td style="vertical-align: top; padding-left: 10px;" class="l"><b>Syarat Bayar</b></td>
        <td colspan="5" style="width: 240px; vertical-align: top;">
        <?php
        if($model->sistem_bayar == "Tempo"){
            $modTempo = app\models\TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);
            echo "TEMPO <i>(".$modTempo->top_hari." Hari)</i>";
        }else{
            echo $model->sistem_bayar;
        }
        ?>
        </td>
        <td colspan="5" style="vertical-align: top; padding-left: 10px;"><b>Syarat Jual</b></td>
        <td colspan="2" style="vertical-align: top;" class="r"><?= $model->syarat_jual;?></td>
    </tr>
    <tr style="background-color: #e9e9e9;">
        <td style="vertical-align: top; padding-left: 10px; padding-bottom: 10px;" class="l b"><b>Cara Bayar</b></td>
        <td colspan="5" style="width: 240px; vertical-align: top;" class="b">
        <?php
            if($model->cara_bayar == "Cek" || $model->cara_bayar == "Bilyet Giro"){
                echo $model->cara_bayar." <i>Reff : ".$model->cara_bayar_reff."</i>";
            }else{
                echo $model->cara_bayar;
            }
        ?>
        </td>
        <td colspan="5" style="vertical-align: top; padding-left: 10px;" class="b"><b>Tanggal Kirim</b></td>
        <td colspan="2" style="vertical-align: top;" class="b r"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim);?></td>
    </tr>
    <?php /* kolom kontrol /*<tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr><?php */?>
    <tr style="height: 30px; line-height: 30px;">
        <td colspan="4" class="text-center t b l r" style="font-weight: bold; width: 320px;">Produk</td>
        <td colspan="2" class="text-center t b l r" style="font-weight: bold; width: 40px;">Palet</td>
        <td colspan="2" class="text-center t b l r" style="font-weight: bold; width: 40px;">Satuan</td>
        <td colspan="3" class="text-center t b l r" style="font-weight: bold; width: 40px;">M<sup>3</sup</td>
        <td class="text-center t b l r" style="font-weight: bold;">Harga</td>
        <td class="text-center t b l r" style="font-weight: bold;">Keterangan</td>
    </tr>

    <?php
    $total_besar = 0;
    $total_kecil = 0;
    $total_kubik = 0;
    $total_harga = 0;
    $row = 0;
        foreach($modDetail as $i => $detail){
            $total_besar += $detail->qty_besar;
            $total_kecil += $detail->qty_kecil;
            $total_kubik += $detail->kubikasi;
            $total_harga += $detail->harga_jual;
            $modRandom = \app\models\TOpKoRandom::find()->where("op_ko_detail_id = ".$detail->op_ko_detail_id)->all();
            if(count($modRandom)>0){
                foreach($modRandom as $ii => $random){
                    $row = $row+1;
                    ?>
                    <tr>
                        <td colspan="4" style="padding: 2px 5px; border-right: 1px solid black;" class="l">
                            <?= $detail->produk->NamaProduk." (".$random['t'].$random['t_satuan']."x".$random['l'].$random['l_satuan']."x".$random['p'].$random['p_satuan'].")<br>" ?>
                        </td>
                        <td colspan="2" style="padding: 2px 5px; border-right: solid 1px #000; text-align: center;">-</td>
                        <td colspan="2" style="padding: 2px 5px; border-right: solid 1px #000;">
                            <span style="float: right;">
                                <?= \app\components\DeltaFormatter::formatNumberForUserFloat($random['qty_kecil']) ?> 
                                <i>(<?= $random['satuan_kecil'] ?>)</i>
                            </span>
                        </td>
                        <td colspan="3" style="padding: 2px 5px; border-right: solid 1px #000;">
                            <span style="float: right">
                                <?= \app\components\DeltaFormatter::formatNumberForUserFloat($random['kubikasi']) ?>
                            </span>
                        </td>
                        <td style="padding: 2px 5px; border-right: solid 1px #000;">
                            <span style="float: left">Rp. </span>
                            <span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga_jual) ?></span>
                        </td>
                        <td style="padding: 2px 5px;" class="r">
                            <span style="float: left"><?= $detail->keterangan ?></span>
                        </td>
                    </tr>
                    <?php
                }
            }else{
                $row = $row+1;
                ?>
                    <tr>
						<td colspan="4" style="padding: 2px 5px; vertical-align: top;" class="l"><?php
                            if($model->jenis_produk == "Limbah"){
                                echo $detail->limbah->limbah_kode." - (".$detail->limbah->limbah_produk_jenis.") ".$detail->limbah->limbah_nama;
                            }else if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                                echo $detail->produkJasa->kode." - ".$detail->produkJasa->nama;
                            }else if($model->jenis_produk == "Log"){
								$modLog = MBrgLog::findOne($detail->produk_id);
								$modKayu = MKayu::findOne($modLog->kayu_id);
								// jika log fsc100 tampilkan nama ilmiah
								if($modLog && stripos($modLog->log_nama, 'FSC100') !== false){
									// Menebalkan teks FSC100 di nama produk
									$produkNama = str_ireplace('FSC100', '<strong>FSC100</strong>', htmlspecialchars($modLog->log_nama));
									echo $produkNama;
									echo "<br><em>" . htmlspecialchars($modKayu->nama_ilmiah) . "</em>";
								} else {
									echo $detail->log->log_nama;
								}
							}else{
								if($model->jenis_produk == 'FingerJointLamineBoard'){
									echo $detail->produk->produk_nama." (". str_replace(" ", "", $detail->produk->produk_dimensi).")";
								}else if($model->jenis_produk == 'Veneer'){
									// jika vener dengan grade fsc100 maka tampilkan nama ilimiah sesuai dengan jenis log
									$modProduk = \app\models\MBrgProduk::findOne(['produk_id' => $detail->produk_id]);
									if ($modProduk && stripos($modProduk->grade, 'FSC100') !== false) {
										$modJeniskayu = \app\models\MJenisKayu::findOne(['jenis_produk' => $model->jenis_produk, 'nama' => $modProduk->jenis_kayu]);
										// Menebalkan teks FSC100 di nama produk
										$produkNama = str_ireplace('FSC100', '<strong>FSC100</strong>', htmlspecialchars($detail->produk->produk_nama));
										echo $produkNama . " (" . str_replace(" ", "", $detail->produk->produk_dimensi) . ")";
										echo "<br><em>" . htmlspecialchars($modJeniskayu->othername) . "</em>";
									} else {
										echo htmlspecialchars($detail->produk->produk_nama) . " (" . str_replace(" ", "", $detail->produk->produk_dimensi) . ")";
									}
								}else{
                                	echo $detail->produk->NamaProduk." (". str_replace(" ", "", $detail->produk->produk_dimensi).")";
								}
                            }
                        ?></td>
                        <td colspan="2" style="padding: 2px 5px; text-align: center; vertical-align: top;" class="l">
                            <?= ($model->jenis_produk == "Limbah" || $model->jenis_produk == "Log")?"":\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_besar); ?>
                        </td>
                        <td colspan="2" style="padding: 2px 5px;  vertical-align: top;" class="l">
                            <span style="float: right;">
                                <?php
                                if($model->jenis_produk == "JasaGesek"){
                                    echo "-";
                                }else if($model->jenis_produk == "Log"){
                                    // echo app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_besar);
									echo $detail->satuan_besar ;
                                }else{
                                    echo app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil). "<i>(". $detail->satuan_kecil .")</i>";
                                }
                                ?>
                            </span>
                        </td>
                        <td colspan="3" style="padding: 2px 5px;  vertical-align: top;" class="l">
                            <span style="float: right">
                                <?= ($model->jenis_produk == "Limbah")?$detail->satuan_besar:\app\components\DeltaFormatter::formatNumberForUserFloat($detail->kubikasi); ?>
                            </span>
                        </td>
                        <td style="padding: 2px 5px;  vertical-align: top;" class="l r">
                            <span style="float: left">Rp. </span>
                            <span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga_jual) ?></span>
                        </td>
                        <td style="padding: 2px 5px;  vertical-align: top;" class="r">
                            <span style="float: left"><?= $detail->keterangan ?></span>
                        </td>
                    </tr>
                <?php
            }
    ?>
    <?php } ?>
    <?php
    $max = 8;
    if($row < $max){ ?>
    <tr style="background-color: #e9e9e9;">
        <td colspan="4" style="padding: 2px 5px;" class="t b l r">&nbsp;</td>
        <td colspan="2" style="padding: 2px 5px;" class="t b l r">&nbsp;</td>
        <td colspan="2" style="padding: 2px 5px;" class="t b l r">&nbsp;</td>
        <td colspan="3" style="padding: 2px 5px;" class="t b l r">&nbsp;</td>
        <td style="padding: 2px 5px;" class="t b l r">&nbsp;</td>
        <td style="padding: 2px 5px;" class="t b l r">&nbsp;</td>
    </tr>
    <?php } ?>
    <tr style="background-color: #e9e9e9;">
        <td colspan="4" class="text-align-right t b l r" style="padding: 5px;"><b>Total</b> &nbsp;</td>
        <td colspan="2" class="text-align-center t b l r" style="padding: 5px;"><b><?= ($model->jenis_produk == "Limbah" || $model->jenis_produk == "Log")?"": \app\components\DeltaFormatter::formatNumberForUserFloat($total_besar) ?></b></td>
        <td colspan="2" class="text-align-right t b l r" style="padding: 5px;">
			<b>
				<?php 
				if($model->jenis_produk == "JasaGesek"){
					echo "";
				} else if($model->jenis_produk == "Log"){
					echo "";
					// echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_besar)."<i>(".$modDetail[0]->satuan_besar." )</i>";
				} else {
					echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil)."<i>(".$modDetail[0]->satuan_kecil." )</i>";
				}
				?>
			</b>
		</td>
        <td colspan="3" class="text-align-right t b l r" style="padding: 5px;"><b><?= ($model->jenis_produk == "Limbah")?"": \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubik) ?></b></td>
        <td class="text-align-right t b l r" style="padding: 5px;"><b>
            <!--<span style="float: left">Rp. </span>
            <span style="float: right"><?php // echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_harga) ?></span> -->
        </b></td>
        <td style="padding: 2px 5px;" class="t b l r">&nbsp;</td>
    </tr>

    <tr>
		<td colspan="13"style="vertical-align: bottom; font-size: 0.9rem;">
            <?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-07-0</span>
		</td>
    </tr>

    <tr>
        <td colspan="8"></td>
        <td colspan="5" class="text-center">
            Disetujui Oleh,

            <br><br><br><br><br>
            <?php
            if(!empty($model->disetujui)){
                echo "<span style='font-size:0.8rem'>Kanit Adm Marketing</span><br>";
                echo "<span style='font-size:0.9rem'>( ".$model->disetujui0->pegawai_nama." )</span>";
            }
            ?>
        </td>
    </tr>
</table>

<?php /*<table style="width: 20cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
						<?= $model->jenis_produk ?>
					</td>
					<td style="width: 5cm; height: 1cm; vertical-align: top; padding: 10px;">
						<table>
							<tr>
								<td style="width:1.5cm;"><b>Kode OP</b></td>
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
		<td colspan="3" style="padding: 8px; background-color: #F1F4F7;">
			<table>
				<tr>
					<td style="width: 50%; vertical-align: top; padding-left: 10px;">
						<table>
							<tr>
								<td style="width: 2.5cm; vertical-align: top;"><b>Customer</b></td>
								<td style="width: 0.5cm; vertical-align: top;"><b>:</b></td>
								<td style="width: 7cm; vertical-align: top;">
									<?php
										echo $model->cust->cust_an_nama." <br>";
										if(!empty($model->cust->cust_pr_nama)){
											echo $model->cust->cust_pr_nama." <br>";
										}
										echo $model->cust->cust_an_alamat;
									?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Syarat Bayar</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;">
									<?php
										if($model->sistem_bayar == "Tempo"){
											$modTempo = app\models\TTempobayarKo::findOne(['op_ko_id'=>$model->op_ko_id]);
											echo "TEMPO <i>(".$modTempo->top_hari." Hari)</i>";
										}else{
											echo $model->sistem_bayar;
										}
									?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Cara Bayar</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;">
									<?php
										if($model->cara_bayar == "Cek" || $model->cara_bayar == "Bilyet Giro"){
											echo $model->cara_bayar." <i>Reff : ".$model->cara_bayar_reff."</i>";
										}else{
											echo $model->cara_bayar;
										}
									?>
								</td>
							</tr>
						</table>
					</td>
					<td style="width: 50%; vertical-align: top; padding-left: 10px;">
						<table>
							<tr>
								<td style="width: 4.5cm; vertical-align: top;"><b>Alamat Bongkar</b></td>
								<td style="width: 0.5cm; vertical-align: top;"><b>:</b></td>
								<td style="width: 5cm; vertical-align: top;"><?= $model->alamat_bongkar ?></td>
							</tr>
								<td style="vertical-align: top;"><b>Syarat Jual</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><?= $model->syarat_jual ?></td>
							<tr>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Tanggal Kirim</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kirim) ?></td>
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
					<td rowspan="2" style="padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Produk</center></b></td>
					<td colspan="3" style="width: 5.5cm; border-right: solid 1px #000;"><b><center>Qty</center></b></td>
					<td rowspan="2" style="width: 3cm; border-right: solid 1px #000; vertical-align: middle;"><b><center>Harga</center></b></td>
					<td rowspan="2" style="width: 3cm; vertical-align: middle;"><b><center>Keterangan</center></b></td>
				</tr>
				<tr style="border-bottom: solid 1px #000;">
                    <?php if($model->jenis_produk == "Limbah"){ ?>
                        <td style="width: 1cm; border-right: solid 1px #000; line-height: 1"><b><center>-</center></b></td>
                        <td style="width: 2.5cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Beli</center></b></td>
                        <td style="width: 2cm; border-right: solid 1px #000; line-height: 1"><b><center>Satuan<br>Angkut</center></b></td>
                    <?php }else{ ?>
                        <td style="width: 1cm; border-right: solid 1px #000;"><b><center><?= ($model->jenis_produk == "JasaGesek")?"Batang":"Palet" ?></center></b></td>
                        <td style="width: 2.5cm; border-right: solid 1px #000;"><b><center>Satuan Kecil</center></b></td>
                        <td style="width: 2cm; border-right: solid 1px #000;"><b><center>M<sup>3</sup></center></b></td>
                    <?php } ?>
				</tr>
				<?php
				$total_besar = 0;
				$total_kecil = 0;
				$total_kubik = 0;
				$total_harga = 0;
				$row = 0;
					foreach($modDetail as $i => $detail){
						$total_besar += $detail->qty_besar;
						$total_kecil += $detail->qty_kecil;
						$total_kubik += $detail->kubikasi;
						$total_harga += $detail->harga_jual;
						$modRandom = \app\models\TOpKoRandom::find()->where("op_ko_detail_id = ".$detail->op_ko_detail_id)->all();
						if(count($modRandom)>0){
							foreach($modRandom as $ii => $random){
								$row = $row+1;
								?>
								<tr>
									<td style="padding: 2px 5px; border-right: 1px solid black;">
										<?= $detail->produk->NamaProduk." (".$random['t'].$random['t_satuan']."x".$random['l'].$random['l_satuan']."x".$random['p'].$random['p_satuan'].")<br>" ?>
									</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000; text-align: center;">-</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000;">
										<span style="float: right;">
											<?= \app\components\DeltaFormatter::formatNumberForUserFloat($random['qty_kecil']) ?> 
											<i>(<?= $random['satuan_kecil'] ?>)</i>
										</span>
									</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000;">
										<span style="float: right">
											<?= \app\components\DeltaFormatter::formatNumberForUserFloat($random['kubikasi']) ?>
										</span> 
									</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000;">
										<span style="float: left">Rp. </span> 
										<span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga_jual) ?></span> 
									</td>
									<td style="padding: 2px 5px; ">
										<span style="float: left"><?= $detail->keterangan ?></span> 
									</td>
								</tr>
								<?php
							}
						}else{
							$row = $row+1;
							?>
								<tr>
									<td style="padding: 2px 5px; border-right: 1px solid black;"><?php
                                        if($model->jenis_produk == "Limbah"){
                                            echo $detail->limbah->limbah_kode." - (".$detail->limbah->limbah_produk_jenis.") ".$detail->limbah->limbah_nama;
                                        }else if($model->jenis_produk == "JasaKD" || $model->jenis_produk == "JasaGesek" || $model->jenis_produk == "JasaMoulding"){
                                            echo $detail->produkJasa->kode." - ".$detail->produkJasa->nama;
                                        }else{
                                            echo $detail->produk->NamaProduk." (". str_replace(" ", "", $detail->produk->produk_dimensi).")"; 
                                        }
                                    ?></td>
									<td style="padding: 2px 5px; border-right: solid 1px #000; text-align: center;">
										<?= ($model->jenis_produk == "Limbah")?"":\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_besar); ?>
									</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000;">
										<span style="float: right;">
                                            <?php
                                            if($model->jenis_produk == "JasaGesek"){
                                                echo "-";
                                            }else{
                                                echo app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil). "<i>(". $detail->satuan_kecil .")</i>";
                                            }
                                            ?>
										</span>
									</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000;">
										<span style="float: right">
                                            <?= ($model->jenis_produk == "Limbah")?$detail->satuan_besar:\app\components\DeltaFormatter::formatNumberForUserFloat($detail->kubikasi); ?>
										</span> 
									</td>
									<td style="padding: 2px 5px; border-right: solid 1px #000;">
										<span style="float: left">Rp. </span> 
										<span style="float: right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga_jual) ?></span> 
									</td>
									<td style="padding: 2px 5px; ">
										<span style="float: left"><?= $detail->keterangan ?></span> 
									</td>
								</tr>
							<?php
						}
				?>
				<?php } ?>
				<?php 
				$max = 8;
				if($row < $max){ ?>
				<tr>
					<td style="padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
					<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
					<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
					<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
					<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
					<td style="padding: 2px 5px;">&nbsp;</td>
				</tr>
				<?php } ?>
				<tr style="border-top: solid 1px #000; background-color: #F1F4F7;" >
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total</b> &nbsp;</td>
					<td class="text-align-center" style="padding: 5px; border-right: solid 1px #000;"><b><?= ($model->jenis_produk == "Limbah")?"": \app\components\DeltaFormatter::formatNumberForUserFloat($total_besar) ?></b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><?= ($model->jenis_produk == "JasaGesek")?"": \app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil)."<i>(".$modDetail[0]->satuan_kecil." )</i>"; ?> </b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b><?= ($model->jenis_produk == "Limbah")?"": \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubik) ?></b></td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
                        <!--<span style="float: left">Rp. </span> 
						<span style="float: right"><?php // echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_harga) ?></span> -->
					</b></td>
					<td style="padding: 2px 5px;">&nbsp;</td>
				</tr>
				
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; border-top: solid 1px #000; height: 20px; vertical-align: top;">
			<?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-07-0</span>
		</td>
	</tr>
	<tr style="border: solid 1px transparent; border-top: solid 1px #000;">
		<td colspan="3" style="border-right: solid 1px transparent;">
			<table style="width: 100%; font-size: 1.1rem; text-align: center;">
				<tr style="height: 0.4cm;  ">
					<td rowspan="3" style="vertical-align: middle; width: 16cm;">&nbsp;</td>
					<td style="vertical-align: middle; width: 4cm; ">Disetujui Oleh,</td>
				</tr>
				<tr>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;"></td>
				</tr>
				<tr>
					<td style="height: 20px; vertical-align: middle;">
						<?php
						if(!empty($model->disetujui)){
							echo "<span style='font-size:0.8rem'>Kanit Adm Marketing</span><br>";
							echo "<span style='font-size:0.9rem'>( ".$model->disetujui0->pegawai_nama." )</span>";
						}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>*/?>
