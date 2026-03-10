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

?>
<style>
table{
	font-size: 1.2rem;
	vertical-align: top;
	border-bottom: solid 1px transparent;
}
#table-detail{
	border-left: solid 1px transparent; 
	border-right: solid 1px transparent;
}
#table-detail thead tr th{
	padding: 5px;
	text-align: center;
	font-size: 1.3rem;
}
#table-detail tbody tr td{
	vertical-align: top;
	padding: 2px;
	font-size: 1.1rem;
}
</style>
<table style="width: 20cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 10px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 70px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 2rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
						<?= (isset($model[0]['tanggal'])?"<i>Tanggal ". app\components\DeltaFormatter::formatDateTimeForUser(substr($model[0]['tanggal']."</i>", 0,10)):"") ?>
					</td>
					<td style="width: 5.5cm; height: 1cm; vertical-align: top; padding: 10px;"></td>
				</tr>
			</table>
			<table style="width: 100%;" border="1" id="table-detail">
				<thead>
					<tr style=" background-color: #F1F4F7;">
						<th style="width: 50px;">No.</th>
						<th style="width: 65px;">Kode</th>
						<th>Deskripsi</th>
						<th style="width: 80px;">Debit</th>
						<th style="width: 80px;">Kredit</th>
						<th style="width: 80px;">Saldo</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="" colspan="5" style="font-weight: bold; text-align: right; background-color: #daffaa">SALDO AWAL &nbsp;  &nbsp;  &nbsp; </td>
						<td class="text-align-right" style="background-color: #daffaa; font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( \app\models\HSaldoKaskecil::getSaldoAwal($model[0]['tanggal']) ):\app\models\HSaldoKaskecil::getSaldoAwal($model[0]['tanggal']); ?></td>
					</tr>
					<?php
					$totalkredit = 0;
					$totaldebit = 0;
                                        
                                        $dateini = date('Y-m-d');
                                        $TanggalPengeluaran = $model[0]['tanggal'];
                                        $dateTambah40juta = '2021-07-07 00:00:00';
                                        $dateMinus40juta = '2021-07-19 00:00:00';
                                        
                                        if($TanggalPengeluaran <= $dateTambah40juta){
                                            $DANA_TETAP_KAS_KECIL_TAMBAH_40JUTA = 0;
                                        }elseif(($TanggalPengeluaran < $dateMinus40juta) && ($TanggalPengeluaran < $dateMinus40juta)){
                                            $DANA_TETAP_KAS_KECIL_TAMBAH_40JUTA = \app\components\Params::DANA_TETAP_KAS_KECIL_TAMBAH_40JUTA ;
                                        }elseif($TanggalPengeluaran >= $dateMinus40juta){
                                            $DANA_TETAP_KAS_KECIL_TAMBAH_40JUTA = 0; //kembalikan lagi menjadi 60juta perhari
                                        }

					if(count($model)>0){ 
						foreach($model as $i => $data){ ?>
						<tr>
							<td style="text-align: center;"><?= $i+1; ?></td>
							<td class="text-align-center"><b><?= $data['reff_no'] ?></b></td>
							<td><?= $data['deskripsi']; ?></td>
							<td class="text-align-right"><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat($data['debit']):$data['debit']; ?></td>
							<td class="text-align-right"><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat($data['kredit']):$data['kredit'] ?></td>
							<td class="td-kecil text-align-right">-</td>
						</tr>
					<?php 
					$totaldebit += $data['debit'];
					$totalkredit += $data['kredit'];
					}
					}else{
						"<tr colspan='5'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
					}
					?>
					<tr>
						<td class="" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right;">TOTAL</td>
						<td class=" text-align-right" style="font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( $totaldebit ):$totaldebit; ?></td>
						<td class=" text-align-right" style="font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( $totalkredit ):$totalkredit; ?></td>
						<td class=""> &nbsp; </td>
					</tr>
					<tr>
						<td class="" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right;">SALDO AKHIR</td>
						<td class= > &nbsp; </td>
						<td class= > &nbsp; </td>
						<td class=" text-align-right" style="font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( \app\models\HSaldoKaskecil::getSaldoAkhir($model[0]['tanggal']) ) : \app\models\HSaldoKaskecil::getSaldoAkhir($model[0]['tanggal']); ?></td>
					</tr>
					<tr>
						<td class="" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right;">DANA TETAP</td>
						<td class="" > &nbsp; </td>
						<td class="" > &nbsp; </td>
						<td class=" text-align-right" style="font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( (\app\components\Params::DANA_TETAP_KAS_KECIL) + ($DANA_TETAP_KAS_KECIL_TAMBAH_40JUTA)) : \app\components\Params::DANA_TETAP_KAS_KECIL + $DANA_TETAP_KAS_KECIL_TAMBAH_40JUTA; ?></td>
					</tr>
					<tr style="border-bottom: solid 1px transparent;">
						<td class="" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right;">TOTAL TOP-UP</td>
						<td class="" > &nbsp; </td>
						<td class="" > &nbsp; </td>
						<td class=" text-align-right" style="font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( ((\app\components\Params::DANA_TETAP_KAS_KECIL) + $DANA_TETAP_KAS_KECIL_TAMBAH_40JUTA ) - (\app\models\HSaldoKaskecil::getSaldoAkhir($model[0]['tanggal'])) ): ((\app\components\Params::DANA_TETAP_KAS_KECIL) + $DANA_TETAP_KAS_KECIL_TAMBAH_40JUTA)-(\app\models\HSaldoKaskecil::getSaldoAkhir($model[0]['tanggal'])); ?></td>
					</tr>
				</tbody>
			</table>
			<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
				<tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
					<td rowspan="3" style="width: 14cm; vertical-align: bottom; text-align: left; font-size: 0.9rem;">
						<?php
						echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
						echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
						?>
					</td>
					<td style="vertical-align: middle; width: 122px; background-color: #F1F4F7;">Disetujui Oleh</td>
					<td style="vertical-align: middle; width: 123px; background-color: #F1F4F7;">Diperiksa Oleh</td>
				</tr>
				<tr>
					<td style="height: 55px; vertical-align: bottom; font-size: 0.8rem; text-align: center;"><i>Kadep Finance</i></td>
					<td style="height: 55px; vertical-align: bottom; font-size: 0.8rem; text-align: center; border-right: solid 1px transparent;"><i>Staff Finance</i></td>
				</tr>
				<tr style="background-color: #F1F4F7;">
					<td style="height: 20px; vertical-align: middle;">
						
					</td>
					<td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;  ">
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-FIN-07-1</span>
		</td>
	</tr>
</table>