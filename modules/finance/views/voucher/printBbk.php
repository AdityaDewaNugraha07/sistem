<?php
/* @var $this yii\web\View */

use app\models\MPegawai;
use app\models\MUser;
use app\models\TApproval;

$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderBbk',['model'=>$model,'paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<style>
table{
	font-size: 1.4rem;
}
</style>
<table style="width: 19cm; margin: 10px; height: auto;" border="1">
	<tr style="height: 2cm;">
		<td colspan="3">
			<table style="width: 100%; " border="0">
				<tr>
					<td style="text-align: left; vertical-align: top; padding: 3px; width: 4cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 75px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3; width: 7.5cm; ">
						<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul']; ?></u></span><br>
						<?php
						if(!empty($model->akun_debit)){
							echo "<span>".substr( \app\models\MAcctRekening::getByPk($model->akun_debit)->acct_nm, -3,3 )."</span>";
						}
						if( substr($model->kode, 0,3) == "BBK" ){
							$kode = $model->kode;
						}else{
							$kode = \app\components\DeltaGenerator::kodeBuktiBankKeluar($model->akun_debit,$model->tanggal_bayar);
						}
						if(!empty($model->suplier_id)){
							$receiver = "<span style='font-size:1.3rem'><b>".$model->suplier->suplier_nm."</b></span>";
						}else{
							if($model->tipe == "Top-up Kas Kecil" || $model->tipe == "Ganti Kas Kecil"){
								$receiver = "<b>Kas Kecil CWM</b>";
							}else if($model->tipe == "Ganti Kas Besar"){
								$receiver = "<b>Kas Besar CWM</b>";
							}else if($model->tipe == "Uang Dinas Grader"){
								$modAjuanDinas = \app\models\TAjuandinasGrader::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
								$receiver = $modAjuanDinas->graderlog->graderlog_nm;
							}else if($model->tipe == "Uang Makan Grader"){
								$modAjuanMakan = \app\models\TAjuanmakanGrader::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
								$receiver = $modAjuanMakan->graderlog->graderlog_nm;
							}else if($model->tipe == "Open Voucher"){
                                $modOpenVoucher = \app\models\TOpenVoucher::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
                                if($modOpenVoucher->tipe == "REGULER"){
                                    $modPenerimaVoucher = \app\models\MPenerimaVoucher::findOne($modOpenVoucher->penerima_voucher_id);
                                    $receiver = "<span style='font-size:1.2rem;'>".(!empty($modPenerimaVoucher->nama_perusahaan)?$modPenerimaVoucher->nama_perusahaan:"")."<br><b>".
                                                                                    $modPenerimaVoucher->nama_penerima."</span>";
                                }else if($modOpenVoucher->tipe == "PEMBAYARAN LOG ALAM"){
                                    $modSuplier = \app\models\MSuplier::findOne($modOpenVoucher->penerima_reff_id);
                                    $receiver = "<span style='font-size:1.2rem;'>".(!empty($modSuplier->suplier_nm_company)?$modSuplier->suplier_nm_company:"")."<br><b>".
                                                                                $modSuplier->suplier_nm."</span>";
                                }else if($modOpenVoucher->tipe == "DEPOSIT SUPPLIER LOG"){
                                    $modSuplier = \app\models\MSuplier::findOne($modOpenVoucher->penerima_reff_id);
                                    $receiver = "<span style='font-size:1.2rem;'><b>".$modSuplier->suplier_nm."</b>".
                                                (!empty($modSuplier->suplier_nm_company)?"<br>".$modSuplier->suplier_nm_company:"")."</span>";
                                }else if($modOpenVoucher->tipe == "DP LOG SENGON" || $modOpenVoucher->tipe == "PELUNASAN LOG SENGON"){
                                    $modSuplier = \app\models\MSuplier::findOne($modOpenVoucher->penerima_reff_id);
                                    $receiver = "<span style='font-size:1.2rem;'><b>".$modSuplier->suplier_nm."</b></span>";
                                }else if($modOpenVoucher->tipe == "PEMBAYARAN ASURANSI LOG SHIPPING"){
									$modAsuransi = \app\models\TAsuransi::findOne(['kode'=>$modOpenVoucher->reff_no]);
									$receiver = "<span style='font-size:1.2rem;'><b>".$modAsuransi->kepada."</b></span>";
								}
                                
                            }else{
                                $receiver = "";
                            }
						}
						?>
					</td>
					<td style="width: 6cm; height: 1cm; vertical-align: top; padding: 10px; width: 7.5cm; ">
						<table style="width: 100%;">
							<tr>
								<td style="width:2.1cm; font-size:1.2rem;">No.</td>
								<td style="font-size:1.2rem;">:&nbsp;</td>
								<td style="font-size:1.2rem;"><?= $kode; ?></td>
							</tr>
							<tr>
								<td style="font-size:1.2rem;">Tanggal</td>
								<td style="font-size:1.2rem;">:&nbsp;</td>
								<td style="font-size:1.2rem;"><?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal_bayar ); ?> </td>
							</tr>
							<tr>
								<td style="vertical-align: top;font-size:1.2rem;">Penerima</td>
								<td style="vertical-align: top;font-size:1.2rem;">:&nbsp;</td>
								<td style="line-height: 1; font-size:1.2rem;"><?= $receiver ?></td>
                            </tr>
                            <?php
                            if($model->tipe == "Open Voucher"){
                                $modOpenVoucher = \app\models\TOpenVoucher::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
                            ?>
                            <tr>
								<td style="vertical-align: top;font-size:1.2rem;">QQ</td>
								<td style="vertical-align: top;font-size:1.2rem;">:&nbsp;</td>
								<td style="line-height: 1;font-size:1.2rem;"><b><?= nl2br($modOpenVoucher->penerima_voucher_qq); ?></td>
                            </tr>
                            <?php
                            }
                            ?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="background-color: #F1F4F7; border-bottom: solid 1px transparent; height: 0.5cm;">
		<td style="width: 3.5cm; padding: 7px 5px;"><b><center>Kode Perkiraan</center></b></td>
		<td style="width: 11cm; padding: 7px 5px;"><b><center>Keterangan</center></b></td>
		<td style="padding: 7px 5px;"><b><center>Jumlah</center></b></td>
	</tr>
	<tr  style="height: auto; vertical-align: top;">
		<td colspan="3">
			<table style="width: 100%"  border="1">
				<?php
				$total = 0; 
				if ($model->mata_uang == "USD") {
					$matauang = "$";
				} else if ($model->mata_uang == "EUR") {
					$matauang = "&#128;";
				} else if ($model->mata_uang == "CNY") {
					$matauang = "¥";
				} else {
					$matauang = "Rp";
				}
				$modTerima = \app\models\TTerimaBhp::findOne(['voucher_pengeluaran_id'=>$model->voucher_pengeluaran_id]);
				if(!empty($modTerima)){
					if($modTerima->spo_id){
						$modSPO = app\models\TSpo::findOne($modTerima->spo_id);
						if(!empty($modSPO)){
							$matauang = $modSPO->defaultValue->name_en;
						}
					}
				}
				foreach($modDetail as $i => $detail){
					$total += $detail->jumlah;
				?>
					<tr>
						<td style="width: 3.5cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;"><center><?= !empty($detail->acct)? $detail->acct->acct_no:''; ?></center></td>
						<td style="width: 11cm; padding: 2px 5px; border-right: 1px solid black;">
							<div style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">
								<?= !empty($detail->keterangan)?$detail->keterangan:"<center> - </center>"; ?>
							</div>
						</td>
						<td style="padding: 2px 5px; border-right: solid 1px transparent;">
							<span style="float: left"><?= $detail->voucherPengeluaran->defaultValue->name_en; ?></span>
							<?php
							if($detail->jumlah < 0){
								$detail->jumlah = $model->mata_uang=="IDR"?\app\components\DeltaFormatter::formatNumberForUser(abs($detail->jumlah)):\app\components\DeltaFormatter::formatNumberForUserFloat(abs($detail->jumlah), 2);
								$jml = "(".$detail->jumlah.")";
							}else{
								$jml = $model->mata_uang=="IDR"?\app\components\DeltaFormatter::formatNumberForUser($detail->jumlah):\app\components\DeltaFormatter::formatNumberForUserFloat($detail->jumlah, 2);
							}
							?>
							<span style="float: right"><?= $jml ?></span>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<td style="width: 3.5cm; padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
					<td style="width: 11cm; padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
					<td style="padding: 2px 5px; border-right: solid 1px transparent;">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right; padding: 3px 5px; border-left: solid 1px transparent;"> 
						<span style="float: left; font-size: 1.2rem;">Cara Bayar : <?= $model->cara_bayar.' '.$model->cara_bayar_reff; ?></span>
						<span style="float: right"><b>Total</b> &nbsp;</span>
					</td>
					<td style="padding: 3px 5px; font-weight: 800; border-right: 1px solid transparent;">
						<span style="float: left"><?= $matauang ?></span>
						<span style="float: right;"><?= $model->mata_uang=="IDR"?\app\components\DeltaFormatter::formatNumberForUser($total):\app\components\DeltaFormatter::formatNumberForUserFloat($total, 2) ?></span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="border-bottom: solid 1px transparent; border-top: solid 1px transparent;">
		<td style="width: 3.5cm; padding: 7px 5px; text-align: right; font-size: 1.2rem;">Uang Sejumlah &nbsp; </td>
		<td colspan="2" style="padding-left: 5px; background-color: #F1F4F7; font-size: 1.3rem;"><b><i>
			<?php
			if ($detail->voucherPengeluaran->mata_uang == "USD") {
				$ret = \app\components\DeltaFormatter::formatNumberTerbilangDollar($total);
			} else if ($detail->voucherPengeluaran->mata_uang == "EUR" || $detail->voucherPengeluaran->mata_uang == "CNY") {
				$mata_uang = $detail->voucherPengeluaran->mata_uang;
				$ret = \app\components\DeltaFormatter::formatNumberTerbilangEurCny($total,$mata_uang);
			} else {
				$ret = \app\components\DeltaFormatter::formatNumberTerbilang($total);
			}
			echo $ret;
			?>
		</i></b></td>
	</tr>
	<tr>
		<td colspan="3">
			<?php 
			$lvl1 = ''; $lvl2 = ''; $lvl3 = '';
			$tgl_1 = ''; $tgl_2 = ''; $tgl_3 = ''; 
			$modDrp = Yii::$app->db->createCommand("select t_pengajuan_drp.kode from t_pengajuan_drp 
						join t_pengajuan_drp_detail on t_pengajuan_drp_detail.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id
						join t_voucher_pengeluaran on t_voucher_pengeluaran.voucher_pengeluaran_id = t_pengajuan_drp_detail.voucher_pengeluaran_id
						where t_voucher_pengeluaran.voucher_pengeluaran_id = {$model->voucher_pengeluaran_id} and t_pengajuan_drp.cancel_transaksi_id is null and status_approve <> 'REJECTED'")
						->queryOne();
			if(!empty($modDrp)){
				$kode = $modDrp['kode'];
				$approvals = TApproval::find()->where(['reff_no'=>$kode])->all();
				foreach($approvals as $a => $approval){
					$sql = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = {$approval->assigned_to}")->queryOne();
					if($approval['level'] == 1){
						$lvl1 = $sql['pegawai_nama'];
						$tgl_1 = $approval->tanggal_approve;
					} else if($approval['level'] == 2){
						$lvl2 = $sql['pegawai_nama'];
						$tgl_2 = $approval->tanggal_approve;
					} else if($approval['level'] == 3){
						$lvl3 = $sql['pegawai_nama'];
						$tgl_3 = $approval->tanggal_approve;
					}
				}
			}
			//staff
			$user = MUser::findOne($model->created_by);
			$pegawai = MPegawai::findOne($user->pegawai_id);
			?>
			<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000;" border="1">
				<tr style="height: 0.4cm;">
					<td style="width:20%; vertical-align: middle; border-left: solid 1px transparent;">Diterima Oleh</td>
					<td style="width:20%; vertical-align: middle;">Dibukukan Oleh</td>
					<td style="width:20%; vertical-align: middle;">Diperiksa Oleh</td>
					<td style="width:20%; vertical-align: middle;">Disetujui Oleh</td>
					<td style="width:20%; vertical-align: middle; border-right: solid 1px transparent;">Dibuat Oleh</td>
				</tr>
				<tr>
					<td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: center; border-left: solid 1px transparent; border-bottom: solid 1px transparent;"></td>
					<td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: center; border-bottom: solid 1px transparent;"></td>
					<td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: center; border-bottom: solid 1px transparent;"><?= ($model->tanggal_bayar >= '2024-11-25')?'APPROVED<br>'.$lvl1.'<br>'.$lvl2:'' ?></td>
					<td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: center; border-bottom: solid 1px transparent;"><?= ($model->tanggal_bayar >= '2024-11-25')?'APPROVED<br>'.$lvl3:'' ?></td>
					<td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: center; border-right: solid 1px transparent; border-bottom: solid 1px transparent;"><?= ($model->tanggal_bayar >= '2024-11-25')?$pegawai->pegawai_nama:'' ?></td>
				</tr>
				<tr>
					<td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: left; border-left: solid 1px transparent;">Tgl :</td>
					<td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl :</td>
					<td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl : <?= ($model->tanggal_bayar >= '2024-11-25')?app\components\DeltaFormatter::formatDateTimeForUser2($tgl_2):'' ?></td>
					<td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl : <?= ($model->tanggal_bayar >= '2024-11-25')?app\components\DeltaFormatter::formatDateTimeForUser2($tgl_3):'' ?></td>
					<td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;">Tgl : <?= ($model->tanggal_bayar >= '2024-11-25')?app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal):'' ?></td>
				</tr>
				<tr>
					<td style="height: 20px; vertical-align: middle; border-left: solid 1px transparent;"></td>
					<td style="height: 20px; vertical-align: middle; font-size: 1rem"></td>
					<td style="height: 20px; vertical-align: middle; font-size: 1rem">Kadept Fin & Kadiv FA</td>
					<td style="height: 20px; vertical-align: middle; font-size: 1rem">Direktur / Direktur Utama</td>
					<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;  font-size: 1rem">Kanit Bank</td>
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
			<span class="pull-right">CWM-FK-FIN-09-1</span>
		</td>
	</tr>
</table>