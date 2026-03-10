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
$matauang="Rp.";
?>
<style>
table{
	font-size: 1.4rem;
}
</style>
<?php
/*if(($model[0]['mata_uang']=="USD")&&($model[0]->sender!="Bunga Bank")&&($model[0]->tipe!="Lainnya")){
    $modPiutang = \app\models\TPiutangPenjualan::findOne(['payment_reff'=>$model[0]->kode]);
    if(empty($modPiutang)){
        echo "<center>BBM ini akan terbit setelah dilakukan pemotongan tagihan oleh penerimaan ini (<b>".$model[0]->kode."</b>)</center>"; exit;
    }
}*/

// 2020-08-13 update karena pengambilan string kode pada skrip lama hanya satu berdasarkan urutan saja
if(($model[0]['mata_uang']=="USD")&&($model[0]->sender!="Bunga Bank")&&($model[0]->tipe!="Lainnya")){
	$ada = 0;
	foreach($model as $i => $detail){
		$modPiutang = \app\models\TPiutangPenjualan::findOne(['payment_reff'=>$detail['kode']]);
		if (!empty($modPiutang)) {
			$ada = $ada + 1;
		}
	}

	if ($ada < 1) {
		echo "<center>BBM ini akan terbit setelah dilakukan pemotongan tagihan oleh penerimaan ini (<b>".$model[0]->kode."</b>)</center>"; exit;
	}
}
?>
<table style="width: 19cm; margin: 10px; height: auto;" border="1">
	<tr style="height: 2cm;">
		<td colspan="4">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 3px; width: 4cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 75px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul']; ?></u></span><br>
						<?php
						if(!empty($model[0]->akun_kredit)){
							echo "<span>".substr( \app\models\MAcctRekening::getByPk($model[0]->akun_kredit)->acct_nm, -3,3 )."</span>";
						}
						?>
					</td>
					<td style="width: 6cm; height: 1cm; vertical-align: top; padding: 10px;">
						<table style="width: 100%;">
							<tr>
								<td style="width:2.1cm;">No.</td>
								<td>:&nbsp;</td>
								<td><?= $model[0]->kode_bbm; ?></td>
							</tr>
							<tr>
								<td>Tanggal</td>
								<td>:&nbsp;</td>
								<td><?= app\components\DeltaFormatter::formatDateTimeForUser2( $model[0]->tanggal ); ?> </td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="background-color: #F1F4F7; border-bottom: solid 1px transparent; height: 0.5cm;">
		<td style="width: 2cm; padding: 7px 5px; line-height: 1; font-size: 1.1rem;"><b><center>Kode Perkiraan</center></b></td>
		<td style="width: 5.5cm; padding: 7px 5px;"><b><center>Sender</center></b></td>
		<td style="width: 8cm; padding: 7px 5px;"><b><center>Deskripsi</center></b></td>
		<td style="padding: 7px 5px;"><b><center>Jumlah</center></b></td>
	</tr>
	<tr  style="height: auto; vertical-align: top;">
		<td colspan="4">
			<table style="width: 100%" border="1">
				<?php
				$total = 0;
				$contain_usd = false;
				foreach($model as $i => $detail){
					$contain_usd += ($detail['mata_uang']=="USD")?TRUE:FALSE;
					if($detail['mata_uang']=="USD"){
						$contain_usd += TRUE;
						$nominal = $detail['total_nominal']*$usd;
					}else{
						$contain_usd += FALSE;
						$nominal = $detail['total_nominal'];
					}
					$total += $nominal;
				?>
					<tr>
						<td style="width: 2cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;"><center><?= !empty($detail->acct)? $detail->acct->acct_no:''; ?></center></td>
						<td style="width: 5.5cm; padding: 2px 5px; border-right: 1px solid black; font-size: 1.2rem; vertical-align: top;"><?= !empty($detail->sender)?$detail->sender:"<center> - </center>"; ?></td>
						<td style="width: 8cm; padding: 2px 5px; border-right: 1px solid black; font-size: 1.2rem; vertical-align: top;"><?= !empty($detail->deskripsi)?$detail->deskripsi.(($detail['mata_uang']=="USD")?" - ($ ". number_format($detail['total_nominal'],2).")":""):"<center> - </center>"; ?></td>
						<td style="padding: 2px 5px; border-right: solid 1px transparent; vertical-align: top; font-size: 1.2rem;">
							<span style="float: left"><?= $matauang ?></span>
							<?php
							if($nominal < 0){
								$nominal = \app\components\DeltaFormatter::formatNumberForUser(abs($nominal));
								$jml = "(".$nominal.")";
							}else{
								$jml = \app\components\DeltaFormatter::formatNumberForUser($nominal);
							}
							?>
							<span style="float: right"><b><?= $jml ?></b></span>
						</td>
					</tr>
                    <?php 
                        if($detail['mata_uang']=="USD"){
                            $modPiutang = \app\models\TPiutangPenjualan::find()->where(['payment_reff'=>$detail->kode])->all();
                            if(!empty($modPiutang)){
                                $kodeinv = ""; $total_piutang = 0; $jmlinv=0; $jmlinv_rp=0;
                                foreach($modPiutang as $i => $piutang){
                                    $kodeinv .= number_format(explode("/", $piutang->bill_reff)[0]);
                                    if(count($modPiutang)!=($i+1)){
                                        $kodeinv .= ", ";
                                    }
                                    $modInv = \app\models\TInvoice::findOne(['nomor'=>$piutang->bill_reff]);
                                    $modKurs = \app\models\HKursRupiah::find()->where("keperluan = 'KURS PERIODIK' AND (tanggal <= '{$modInv->tanggal}' AND tanggal_akhir >= '{$modInv->tanggal}')")->one();
                                    if(!empty($modKurs)){
                                        $kurs = $modKurs->usd;
                                        $jmlinv_rp += $piutang->tagihan * $modKurs->usd;
                                    }else{
                                        $kurs = "<b class='font-red-flamingo'>Not Set</b>";
                                        $jmlinv_rp += 0; $total = 0; $display_print = "none;";
                                    }
                                    $jmlinv += $piutang->tagihan;

                                }
                                $adm = ($jmlinv-$detail['total_nominal'])*$usd;
                                $selisih = ($nominal + $adm) - app\components\DeltaFormatter::formatNumberForDb2($jmlinv_rp);
                                if($selisih > 0){
                                    $labelselisih = "Laba Selisih Kurs";
                                    $selisih =  number_format( abs($selisih) );
                                }else{
                                    $labelselisih = "Rugi Selisih Kurs";
                                    $selisih = number_format( abs($selisih) );
                                }
                            ?>
                        <tr>
                            <td style="width: 2cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;"><center><b><?= !empty($detail->acct)? $detail->acct->acct_no:''; ?> </b></center></td>
                            <td style="width: 5.5cm; padding: 2px 5px; border-right: 1px solid black; font-size: 1.1rem; vertical-align: top;"></td>
                            <td style="width: 8cm; padding: 2px 5px; border-right: 1px solid black; font-size: 1.1rem; vertical-align: top;"> 
                                <?= "Piutang Inv (".$kodeinv.")" ?> - 
                                (Kurs : <?= app\components\DeltaFormatter::formatNumberForUserFloat($kurs) ?>) - 
                                ($ <?= number_format($jmlinv,2) ?>)
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px transparent; vertical-align: top; font-size: 1.1rem;">
                                <span style="float: left">Rp.</span>
                                <span style="float: right"><?= number_format($jmlinv_rp) ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 2cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;"><center><b><?= !empty($detail->acct)? $detail->acct->acct_no:''; ?> </b></center></td>
                            <td style="width: 5.5cm; padding: 2px 5px; border-right: 1px solid black; font-size: 1.1rem; vertical-align: top;"></td>
                            <td style="width: 8cm; padding: 2px 5px; border-right: 1px solid black; font-size: 1.1rem; vertical-align: top;"> 
                                Biaya Admin (Kurs : <?= app\components\DeltaFormatter::formatNumberForUserFloat($usd) ?>)
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px transparent; vertical-align: top; font-size: 1.1rem;">
                                <span style="float: left">Rp.</span>
                                <span style="float: right"><?= number_format($adm) ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 2cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;"><center><b><?= !empty($detail->acct)? $detail->acct->acct_no:''; ?> </b></center></td>
                            <td style="width: 5.5cm; padding: 2px 5px; border-right: 1px solid black; font-size: 1.1rem; vertical-align: top;"></td>
                            <td style="width: 8cm; padding: 2px 5px; border-right: 1px solid black; font-size: 1.1rem; vertical-align: top;"> 
                                <?= $labelselisih ?>
                            </td>
                            <td style="padding: 2px 5px; border-right: solid 1px transparent; vertical-align: top; font-size: 1.1rem;">
                                <span style="float: left">Rp.</span>
                                <span style="float: right"><?= $selisih ?></span>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php
                        } 
                    ?>
                <?php } ?>
				<tr>
					<td style="width: 2cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
					<td style="width: 5.5cm; padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
					<td style="width: 8cm; padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
					<td style="padding: 2px 5px; border-right: solid 1px transparent;">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" style="text-align: right; padding: 3px 5px; border-left: solid 1px transparent;"> 
						<span style="float: left; font-size: 1.2rem;">Cara Bayar : <?= ($model[0]->cara_bayar!="-")?$model[0]->cara_bayar.' '.$model[0]->cara_bayar_reff :"<i>Transfer</i>"; ?></span>
						<span style="float: right"><b>Total</b> &nbsp;</span>
					</td>
					<td style="padding: 3px 5px; font-weight: 800; font-size: 1.2rem; border-right: solid 1px transparent;">
						<span style="float: left"><?= $matauang ?></span>
						<span style="float: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($total) ?></span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="border-bottom: solid 1px transparent; border-top: solid 1px transparent;">
		<td style="width: 2cm; padding: 5px 5px; text-align: right; font-size: 1.2rem;">Uang<br>Sejumlah &nbsp; </td>
		<td colspan="3" style="padding-left: 5px; background-color: #F1F4F7; font-size: 1.3rem;"><b><i><?= \app\components\DeltaFormatter::formatNumberTerbilang($total); ?></i></b></td>
	</tr>
	<tr>
		<td colspan="4">
			<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000;" border="1">
				<tr style="height: 0.4cm;">
					<td style="vertical-align: middle; border-left: solid 1px transparent;">Dibukukan Oleh</td>
					<td style="vertical-align: middle;">Diperiksa Oleh</td>
					<td style="vertical-align: middle; border-right: solid 1px transparent;">Dibuat Oleh</td>
				</tr>
				<tr>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-left: solid 1px transparent;">Tgl :</td>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;">Tgl :</td>
				</tr>
				<tr>
					<td style="height: 20px; vertical-align: middle; border-left: solid 1px transparent;">Staff Acc</td>
					<td style="height: 20px; vertical-align: middle; ">Kadep Finance</td>
					<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;">Kanit Bank</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="4" style="font-size: 0.9rem; border: solid 1px transparent; ">
			<?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
			<?php
			if($contain_usd == TRUE){
				echo '&nbsp; &nbsp; - &nbsp; &nbsp; <i><b>Kurs Tengah :</b> Rp. '.\app\components\DeltaFormatter::formatNumberForUserFloat($usd).'</i>';
			}
			?>
			<span class="pull-right">CWM-FK-FIN-04-0</span>
		</td>
	</tr>
</table>