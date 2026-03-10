<style>
#table-piutang thead tr th{
	font-size: 1rem;
	line-height: 0.9;
}
#table-piutang tbody tr td{
	font-size: 1rem;
}
#table-piutang tfoot tr td{
	font-size: 1rem;
}
</style>
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover" id="table-piutang">
		<thead>
			<tr style="background-color: #F1F4F7; ">
				<th rowspan="2" style="width: 35px; text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
				<th rowspan="2" style="text-align: center;"><?= Yii::t('app', 'Customer / Buyer'); ?></th>
				<th rowspan="2" style="width: 80px; text-align: center;"><?= Yii::t('app', 'Tanggal<br>Nota/Invoice'); ?></th>
				<th rowspan="2" style="width: 100px; text-align: center;"><?= Yii::t('app', 'Kode<br>Nota/Invoice'); ?></th>
				<th rowspan="2" style="width: 120px; text-align: center;"><?= Yii::t('app', 'Sisa<br>Piutang'); ?></th>
				<th rowspan="2" style="width: 40px; text-align: center;"><?= Yii::t('app', 'TOP<br>Hari'); ?></th>
				<th colspan="4" style="text-align: center;"><?= Yii::t('app', 'Over Due'); ?></th>
			</tr>
			<tr style="background-color: #F1F4F7; ">
				<th style="width: 100px; text-align: center;"><?= Yii::t('app', '0 - 30'); ?></th>
				<th style="width: 100px; text-align: center;"><?= Yii::t('app', '31 - 60'); ?></th>
				<th style="width: 100px; text-align: center;"><?= Yii::t('app', '61 - 90'); ?></th>
				<th style="width: 100px; text-align: center;"><?= Yii::t('app', '90 +'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$seq=0; $totalpiutang_rp = 0; $total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $matauang = '';
			if(count($mods)>0){
				foreach($mods as $i => $mod){
                    $tglnotainvoice = new DateTime(app\components\DeltaFormatter::formatDateTimeForDb($mod['tanggal']));
                    $tglcetak = new DateTime(app\components\DeltaFormatter::formatDateTimeForDb($tgl));
                    if($mod['tipe']=="lokal"){
                        $modNota = Yii::$app->db->createCommand("SELECT * FROM t_nota_penjualan WHERE kode = '".$mod['kode']."'")->queryOne();
                        $modTempo = Yii::$app->db->createCommand("SELECT * FROM t_tempobayar_ko WHERE op_ko_id = '".$modNota['op_ko_id']."'")->queryOne();
                        $sisapiutang = $modNota['total_bayar']-$mod['terbayar'];
                        $interval = $tglnotainvoice->diff($tglcetak)->days;
                        $due_date = $interval-$modTempo['top_hari'];
                        $kode = '<a onclick="infoNota(\''.$modNota['kode'].'\')">'.$modNota['kode'].'</a>';
                        $sisapiutang_show = app\components\DeltaFormatter::formatNumberForUserFloat($sisapiutang);
                        $totalpiutang_rp += $sisapiutang; 
                        $matauang = \app\models\MDefaultValue::getOneByValue('mata-uang', $mod['mata_uang'], 'name_en');
                    }else{
                        $modInvoice = Yii::$app->db->createCommand("SELECT * FROM t_invoice WHERE nomor = '".$mod['kode']."'")->queryOne();
                        $due_date = -1;
                        $kode = '<a onclick="infoInvoice(\''.$modInvoice['invoice_id'].'\')" style="font-size:0.9rem">'.$modInvoice['nomor'].'</a>';
                        $modTempo['top_hari'] = "-";
                        $sisapiutang_usd = $modInvoice['total_bayar']-$mod['terbayar'];
                        $kurs = \app\models\HKursRupiah::find()->where("keperluan = 'KURS PERIODIK' AND (tanggal <= '{$mod['tanggal']}' AND tanggal_akhir >= '{$mod['tanggal']}')")->one();
                        $matauang = \app\models\MDefaultValue::getOneByValue('mata-uang', $mod['mata_uang'], 'name_en');
                        if(!empty($kurs)){
                            $sisapiutang = $kurs->usd * ($sisapiutang_usd);
                            $totalpiutang_rp += $sisapiutang;
                            $sisapiutang_show = "<span data-original-title='Hasil kurs : ".\app\components\DeltaFormatter::formatNumberForUserFloat($sisapiutang_usd)." x ".\app\components\DeltaFormatter::formatNumberForUserFloat($kurs->usd)."' class='tooltips' style='border-bottom: 1px dotted #000; text-decoration: none;'>"
                                                .number_format($sisapiutang)."<span>";
                            $matauang = \app\models\MDefaultValue::getOneByValue('mata-uang', "IDR", 'name_en');
                        }else{
                            $sisapiutang = $sisapiutang_usd;
                            $totalpiutang_rp += 0;
                            $sisapiutang_show = "<span class='font-red-flamingo' style='font-size:0.8rem;'>Kurs tidak ditemukan</span><br><strike>"
                                                .app\components\DeltaFormatter::formatNumberForUserFloat($sisapiutang)."</strike>";
                        }
                        
                    }
                    if($sisapiutang > 0){
                        $seq=$seq+1;
			?>
                    <tr>
                        <td style="text-align: center;"><?= $seq ?></td>
                        <td style=""><?= $mod['cust_an_nama'] ?></td>
                        <td style="text-align: center;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($mod['tanggal']) ?></td>
                        <td style="text-align: center;"><?= $kode ?></td>
                        <td style="text-align: right; font-weight: 600; line-height: 1">
                            <span class="pull-left"><?= $matauang ?> </span>
                            <span class="pull-right"><?= $sisapiutang_show ?>
                            </span>
                        </td>
                        <td style="text-align: center"><?= !empty($modTempo['top_hari'])?$modTempo['top_hari']:"<center>-</center>" ?></td>
                        <td style="text-align: right;">
                        <!--<td style="text-align: right; background-color: #F5F6CE;">-->
                            <?php if($due_date >= 0 && $due_date <= 30){ $total1 = $total1+$sisapiutang ?>
                                <span class="pull-left"><?= $matauang ?></span>
                                <span class="pull-right"><?= number_format($sisapiutang) ?></span>
                                </span>
                            <?php }else{ echo "<center>-</center>"; } ?>
                        </td>
                        <td style="text-align: right;">
                        <!--<td style="text-align: right; background-color: #F2F5A9;">-->
                            <?php if($due_date >= 31 && $due_date <= 60){ $total2 = $total2+$sisapiutang ?>
                                <span class="pull-left"><?= $matauang ?></span>
                                <span class="pull-right"><?= number_format($sisapiutang) ?></span>
                            <?php }else{ echo "<center>-</center>"; } ?>
                        </td>
                        <td style="text-align: right;">
                        <!--<td style="text-align: right; background-color: #F1F588;">-->
                            <?php if($due_date >= 61 && $due_date <= 90){ $total3 = $total3+$sisapiutang ?>
                                <span class="pull-left"><?= $matauang ?></span>
                                <span class="pull-right"><?= number_format($sisapiutang) ?></span>
                            <?php }else{ echo "<center>-</center>"; } ?>
                        </td>
                        <td style="text-align: right;">
                        <!--<td style="text-align: right; background-color: #F1F573;">-->
                            <?php if($due_date >= 91){ $total4 = $total4+$sisapiutang ?>
                                <span class="pull-left"><?= $matauang ?></span>
                                <span class="pull-right"><?= number_format($sisapiutang) ?></span>
                            <?php }else{ echo "<center>-</center>"; } ?>
                        </td>
                    </tr>
			<?php } } }else{ ?>
				<tr><td colspan="10" style="text-align: center;"><i>Data tidak ditemukan</i></td></tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr style="background-color: #F1F4F7; ">
				<td colspan="4" style="text-align: right; font-size: 1.3rem"><b>Total &nbsp; </b></td>
				<td style="text-align: center;" class="font-blue-steel">
					<span class="pull-left">Rp</span>
                    <span class="pull-right" style="font-size: 1.1rem"><b><?= number_format($totalpiutang_rp) ?></b></span>
				</td>
				<td style=""></td>
				<td style="text-align: right;"  class="font-blue-steel">
					<?php if($total1 > 0){ ?>
						<span class="pull-left">Rp</span>
						<span class="pull-right"><?= number_format($total1) ?></span>
					<?php }else{ echo "<center>-</center>"; } ?>
				</td>
				<td style="text-align: right;" class="font-blue-steel">
					<?php if($total2 > 0){ ?>
						<span class="pull-left">Rp</span>
						<span class="pull-right"><?= number_format($total2) ?></span>
					<?php }else{ echo "<center>-</center>"; } ?>
				</td>
				<td style="text-align: right;" class="font-blue-steel">
					<?php if($total3 > 0){ ?>
						<span class="pull-left">Rp</span>
						<span class="pull-right"><?= number_format($total3) ?></span>
					<?php }else{ echo "<center>-</center>"; } ?>
				</td>
				<td style="text-align: right;" class="font-blue-steel">
					<?php if($total4 > 0){ ?>
						<span class="pull-left">Rp</span>
						<span class="pull-right"><?= number_format($total4) ?></span>
					<?php }else{ echo "<center>-</center>"; } ?>
				</td>
			</tr>
		</tfoot>
	</table>
    <br><span class="" style="font-size: 1rem; font-style: italic;"><b>Note </b>: Laporan Piutang ini menggunakan matauang rupiah, untuk nominal invoice export (USD) akan di Rupiahkan berdasarkan data kurs pada master</span>
</div>