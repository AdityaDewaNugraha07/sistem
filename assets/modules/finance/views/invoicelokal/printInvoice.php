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
$tablewidth = "19";
$modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
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
		<td colspan="3" style="border-bottom: solid 1px #000;">
			<table style="width: 100%;" border="0">
				<tr style="">
					<td style="width: 5cm; text-align: left; vertical-align: middle; height: 1cm; border-right: solid 1px transparent;"></td>
					<td style="text-align: center; vertical-align: middle; padding: 5px; line-height: 1.1;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
					</td>
					<td style="width: 5cm; height: 1cm; vertical-align: top;">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="width: 50%; height: 3cm; vertical-align: middle; padding: 5px 10px; background-color: #F1F4F7; border-right: solid 1px transparent;">
			<table style="width: 100%;">
				<tr>
					<td style="width: 2.5cm; vertical-align: top;"><b>Customer</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top; line-height: 1.3;"><?php echo strtoupper($model->cust->cust_an_nama);?></td>
				</tr>
				<tr>
					<td style="width: 2.5cm; vertical-align: top;"><b>Alamat</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top; line-height: 1.3;"><?php echo strtoupper($model->cust->cust_an_alamat);?></td>
				</tr>
				<tr>
					<td style="width: 2.5cm; vertical-align: top;"><b>No. NPWP</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
                    <td style="vertical-align: top; line-height: 1.3;">
                        <?php
                            echo substr($model->cust_no_npwp, 0,2).".".
                                 substr($model->cust_no_npwp, 2,3).".".
                                 substr($model->cust_no_npwp, 5,3).".".
                                 substr($model->cust_no_npwp, 8,1)."-".
                                 substr($model->cust_no_npwp, 9,3).".".
                                 substr($model->cust_no_npwp, 12,3); 
                        ?>
                    </td>
				</tr>
				<tr>
					<td style="width: 2.5cm; vertical-align: top;"><b>No. Faktur Pajak</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
                    <td style="vertical-align: top; line-height: 1.3;">
                        <?php
                            echo substr($model->no_faktur_pajak, 0,3).".".
                                 substr($model->no_faktur_pajak, 3,3)."-".
                                 substr($model->no_faktur_pajak, 6,2).".".
                                 substr($model->no_faktur_pajak, 8,8); 
                        ?>
                    </td>
				</tr>
			</table>
		</td>
		<td style="width: 50%; vertical-align: middle; padding: 5px 10px; background-color: #F1F4F7;">
			<table style="width: 100%;">
				<tr>
					<td style="width: 2.5cm; vertical-align: top;"><b>No. Invoice</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
					<td style="vertical-align: top;"><b><?php echo $model->kode;?></b></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>Tanggal Invoice</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
                    <td style="vertical-align: top;"><?php echo strtoupper(app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal));?></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>Cara Bayar</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
                    <td style="vertical-align: top;"><?php echo $model->cara_bayar;?></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><b>Mata Uang</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
                    <td style="vertical-align: top;"><?php echo $model->mata_uang;?></td>
				</tr>
<!--				<tr>
					<td style="vertical-align: top;"><b>Include PPn</b></td>
					<td style="vertical-align: top; text-align: center;"><b>:</b></td>
                    <td style="vertical-align: top;"><?php // echo ($model->include_ppn)?"YA":"TIDAK";?></td>
				</tr>-->
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 2px; border-bottom: solid 1px transparent;">
			<table style="width: 100%" id="table-detail" border="1">
				<thead>
					<tr style="border-bottom: solid 1px #000;">
                        <td rowspan="2" style="width: 1cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>No</center></b></td>
						<td rowspan="2" style="padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Deskripsi </center></b></td>
                        <td colspan="2" style="padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Pengiriman </center></b></td>
						<td colspan="2" style="padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Qty </center></b></td>
						<td rowspan="2" style="padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Harga </center></b></td>
						<td rowspan="2" style="width: 3cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Total </center></b></td>
					</tr>
					<tr style="border-bottom: solid 1px #000;">
						<td style="width: 2.5cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Tanggal</center></b></td>
						<td style="width: 4cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Nopol / Supir</center></b></td>
						<td style="width: 1.5cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Pcs</center></b></td>
                        <td style="width: 1.5cm; padding: 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>M<sup>3</sup></center></b></td>
					</tr>
				</thead>
				<tbody>
					<?php
					$total = 0; $total_pcs=0; $total_m3 = 0; $total_row=0;
					if(count($modDetails)>0){
						foreach($modDetails as $ii => $detail){
                            if($model->jenis_produk=="JasaKD"){
                                $produk = "Jasa Kiln Dry";
                            }else if($model->jenis_produk=="JasaGesek"){
                                $produk = "Jasa Gesek";
                            }else if($model->jenis_produk=="JasaMoulding"){
                                $produk = "Jasa Moulding";
                            }
                            $qty = Yii::$app->db->createCommand("SELECT SUM(qty_kecil_realisasi) AS pcs, SUM(kubikasi_realisasi) AS m3 FROM t_spm_ko_detail WHERE spm_ko_id = ".$detail->spm_ko_id)->queryOne();
                            
							echo "<tr>";
							echo	"<td style='text-align:center; padding: 5px; border-right: solid 1px #000; vertical-align: top;'>".($ii+1)."</td>";
							echo	"<td style='text-align:left; padding: 5px; border-right: solid 1px #000; vertical-align: top;'>".
										$produk
									."</td>";
							echo	"<td style='text-align:left; padding: 5px; border-right: solid 1px #000; vertical-align: top;'>".
                                        app\components\DeltaFormatter::formatDateTimeForUser($detail->spmKo->tanggal)
									."</td>";
							echo	"<td style='text-align:left; padding: 5px; border-right: solid 1px #000; vertical-align: top;'>".
										$detail->spmKo->kendaraan_nopol." / ".$detail->spmKo->kendaraan_supir
									."</td>";
							echo	"<td style='text-align:right; padding: 5px; border-right: solid 1px #000; vertical-align: top;'>".
                                        number_format($qty['pcs'])
									."</td>";
							echo	"<td style='text-align:right; padding: 5px; border-right: solid 1px #000; vertical-align: top;'>".
                                        number_format($qty['m3'],4)
									."</td>";
							echo	"<td style='text-align:right; padding: 5px; border-right: solid 1px #000; vertical-align: top;'>".
                                        number_format($detail->harga_invoice)
									."</td>";
							echo	"<td style='text-align:right; padding: 5px; border-right: solid 1px #000; vertical-align: top;'>
                                        <span class='pull-left' style='padding-left:2px;'>". (($model->mata_uang=="IDR")?"Rp.":"") ."</span>
                                        <span class='pull-right'>". number_format($detail->notaPenjualan->total_bayar) ."</span>"
									."</td>";
							echo "</tr>";
							$total_row = $total_row+1;
							
						}
					}
					?>
				</tbody>
				<tfoot>
					<tr style="border-top: solid 1px #000;">
						<td colspan="7" style="font-size: 1.1rem; padding: 3px; text-align: right; border-right: solid 1px #000;"><b>DPP</b> &nbsp; &nbsp; </td>
						<td style="padding: 3px; text-align: right;">
							<b class="pull-left" style="padding-left:2px;"><?= ($model->mata_uang=="IDR")?"Rp.":"" ?></b>
							<b class="pull-right"><?= number_format($model->total_harga) ?></b>
						</td>
					</tr>
					<tr style="border-top: solid 1px #000;">
						<td colspan="7" style="font-size: 1.1rem; padding: 3px; text-align: right; border-right: solid 1px #000;"><b>PPN</b> &nbsp; &nbsp; </td>
						<td style="padding: 3px; text-align: right;">
							<b class="pull-left" style="padding-left:2px;"><?= ($model->mata_uang=="IDR")?"Rp.":"" ?></b>
							<b class="pull-right"><?= number_format($model->total_ppn) ?></b>
						</td>
					</tr>
					<tr style="border-top: solid 1px #000;">
						<td colspan="7" style="font-size: 1.1rem; padding: 3px; text-align: right; border-right: solid 1px #000;"><b>PPH</b> &nbsp; &nbsp; </td>
						<td style="padding: 3px; text-align: right;">
							<b class="pull-left" style="padding-left:2px;"><?= ($model->mata_uang=="IDR")?"Rp.":"" ?></b>
							<b class="pull-right"><?= number_format($model->total_pph) ?></b>
						</td>
					</tr>
					<tr style="border-top: solid 1px #000;">
						<td colspan="7" style="font-size: 1.1rem; padding: 3px; text-align: right; border-right: solid 1px #000;"><b>TOTAL</b> &nbsp; &nbsp; </td>
						<td style="padding: 3px; text-align: right;">
							<b class="pull-left" style="padding-left:2px;"><?= ($model->mata_uang=="IDR")?"Rp.":"" ?></b>
							<b class="pull-right"><?= number_format($model->total_bayar) ?></b>
						</td>
					</tr>
					<tr style="border-top: solid 1px #000;">
						<td colspan="9" style="font-size: 1.1rem; padding: 3px; text-align: left;"><i>
							<b>Terbilang : </b>
							<?= \app\components\DeltaFormatter::formatNumberTerbilang( $model->total_bayar ) ?>
					</i> </td>
					</tr>
				</tfoot>
			</table>
		</td>
	</tr>
    
    <?php 
    $max = 14; 
    $blankspace = $max - $total_row; 
    ?>
    <tr><td colspan="2" style="height: <?= (0.5*$blankspace) ?>cm">&nbsp;</td></tr>
    
	<tr style="border: solid 1px #000; border-top: solid 1px transparent; width:<?= $tablewidth ?>cm; bottom: 10px;">
		<td colspan="3" style="">
			<table style="width: 100%; font-size: 1.1rem;" border="0">
				<tr style="height: 0.5cm;">
					<td style="vertical-align: bottom; border-bottom: solid 1px transparent; text-align: left; line-height: 1.3; padding-left: 10px;">
						<span style="font-size: 1.2rem;"><b>Thank you for Your bussiness!</b></span><br>
						<span style="font-size: 1.2rem;">Seluruh pembayaran harus dibayarkan melalui rekening bank kami dibawah ini :</span>
					</td>
					<td style="vertical-align: top; width: 5cm; text-align: center;"><?= $modCompany->name ?></td>
				</tr>
				<tr>
					<td style=" padding-left: 15px; line-height: 1.3; ">
						<table style="" border="0">
                            <tr>
								<td style="font-size: 1.1rem;">Nama Bank</td>
								<td style="font-size: 1.1rem;">:</td>
								<td style="font-size: 1.1rem;"><b>PT. Bank Central Asia, Tbk.</b></td>
							</tr>
							<tr>
								<td style="font-size: 1.1rem;">Cabang</td>
								<td style="font-size: 1.1rem;">:</td>
								<td style="font-size: 1.1rem;"><b>BCA Pemuda, Semarang</b></td>
							</tr>
                            <tr>
								<td style="font-size: 1.1rem;">No. Rekening</td>
								<td style="font-size: 1.1rem;">:</td>
								<td style="font-size: 1.1rem;"><b>009.635.5.666</b></td>
							</tr>
							<tr>
								<td style="width:2.5cm; font-size: 1.1rem;">Atas Nama</td>
								<td style="width:0.25cm; font-size: 1.1rem;">:</td>
								<td style="width:5.75cm; font-size: 1.1rem;"><b>PT. CIPTA WIJAYA MANDIRI</b></td>
							</tr>
                            <tr><td>&nbsp;</td></tr>
						</table>
					</td>
					<td style="vertical-align: bottom; line-height: 1;  text-align: center;">
						<?php
						if(!empty($model->penerbit)){
							echo "<span style='font-size:0.9rem'><b><u> ". $model->penerbit0->pegawai_nama." </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>Kepala Divisi Marketing </span>";
						}
						?>
					</td>
				</tr>
                <tr><td style="height: 0.5cm">&nbsp;</td></tr>
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
