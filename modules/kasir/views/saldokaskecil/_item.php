<?php
$rincian = '<a class="btn btn-sm btn-default" style="font-size:1rem; padding: 3px;" onclick="lihatRincian(\''.$detail['tanggal'].'\')"><i class="fa fa-search"></i> Lihat Laporan</a>';
$ppk = '';
$desc = "";
if($detail['debit'] > 0 && $detail['kredit'] <= 0){
	$desc = "Penambahan Kas Kecil";
	$rincian = '-';
}
if($detail['debit'] <= 0 && $detail['kredit'] <= 0){
	$desc = "Tidak Ada Transaksi Pengeluaran / Penambahan";
	$rincian = '-';
}
if($detail['kredit'] > 0 && $detail['debit'] <= 0){
	$desc = "Pengeluaran Kas Kecil";
}
if($detail['kredit'] > 0 && $detail['debit'] > 0){
	$desc = "Pengeluaran & Penambahan Kas Kecil";
}
?>
<tr style="">
	<td class=""  style="font-size:1.2rem; text-align: center; padding: 5px;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<span class="no_urut"><?= $i+1; ?></span>
	</td>
	<td class=""  style="font-size:1.2rem; padding: 5px;">
		<?= \app\components\DeltaFormatter::formatDateTimeForUser2($detail['tanggal']); ?>
	</td>
	<td class=""  style="font-size:1.2rem; padding: 5px;">
		<?= $desc ?>
	</td>
	<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
		<?= !empty($detail['debit'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['debit']):0; ?>
	</td>
	<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
		<?= !empty($detail['kredit'])?app\components\DeltaFormatter::formatNumberForUserFloat($detail['kredit']):0; ?>
	</td>
	<td class="text-align-right" style="font-size:1.2rem; padding: 5px;">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat( \app\models\HSaldoKaskecil::getSaldoAkhir($detail['tanggal']) ); ?>
	</td>
	<td class="text-align-center"  style="font-size:1.2rem; padding: 5px;">
		<?= $rincian ?>
	</td>
	<?php
	if( ($totalrows == ($i+1))){
                $dateini = date('Y-m-d');
                $TanggalPengeluaran = $detail['tanggal'];
                $dateTambah40juta = '2021-07-07 00:00:00';
                $dateMinus40juta = '2021-07-19 00:00:00';
                
                if($TanggalPengeluaran <= $dateTambah40juta){
                    $totaltopup = (\app\components\Params::DANA_TETAP_KAS_KECIL) - (\app\models\HSaldoKaskecil::getSaldoAkhir($detail['tanggal']));
                    $ppk = '<a class="btn btn-sm blue btn-outline" target="BLANK" style="font-size:1rem; padding: 3px;" href="'. \yii\helpers\Url::toRoute(['/kasir/ppk/index','tgl'=>$detail['tanggal'],'nominal'=>$totaltopup]) .'"><i class="fa fa-share"></i> Ajuan PPK</a>';
	
                }elseif(($TanggalPengeluaran > $dateTambah40juta)&& ($TanggalPengeluaran < $dateMinus40juta)){
                    $totaltopup = ((\app\components\Params::DANA_TETAP_KAS_KECIL)  + (\app\components\Params::DANA_TETAP_KAS_KECIL_TAMBAH_40JUTA)) -(\app\models\HSaldoKaskecil::getSaldoAkhir($detail['tanggal']));
                    $ppk = '<a class="btn btn-sm blue btn-outline" target="BLANK" style="font-size:1rem; padding: 3px;" href="'. \yii\helpers\Url::toRoute(['/kasir/ppk/index','tgl'=>$detail['tanggal'],'nominal'=>$totaltopup]) .'"><i class="fa fa-share"></i> Ajuan PPK</a>';
	
                }elseif($TanggalPengeluaran >= $dateMinus40juta){
                    $totaltopup = (\app\components\Params::DANA_TETAP_KAS_KECIL) - (\app\models\HSaldoKaskecil::getSaldoAkhir($detail['tanggal']));
                    $ppk = '<a class="btn btn-sm blue btn-outline" target="BLANK" style="font-size:1rem; padding: 3px;" href="'. \yii\helpers\Url::toRoute(['/kasir/ppk/index','tgl'=>$detail['tanggal'],'nominal'=>$totaltopup]) .'"><i class="fa fa-share"></i> Ajuan PPK</a>';
	
                }
		                
        }
	?>
	<td class="text-align-center"  style="font-size:1.2rem; padding: 5px;">
		<?= $ppk ?>
	</td>
</tr>