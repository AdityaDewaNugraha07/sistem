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
	font-size: 1.1rem;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
table#table-detail th{
	text-align: center;
}
</style>
<table style="width: 20cm; margin: 10px; height: 10cm;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px; border-bottom: solid 1px transparent;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 4cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
						<?= $paramprint['judul2'] ?>
					</td>
					<td style="width: 3cm; height: 1cm; vertical-align: top; padding: 10px;">
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
				<thead>
					<tr>
						<th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;">No.</th>
						<th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Kode'); ?></th>
						<th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Tanggal'); ?></th>
						<th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Sales'); ?></th>										
						<th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
						<th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Customer'); ?></th>
                        <th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Tanggal<br>Terima/Hasil'); ?></th>
                        <th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Nopol'); ?></th>
                        <th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'No. Palet'); ?></th>
                        <th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Produk'); ?></th>
                        <th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Dimensi<br>(t x l x p)'); ?></th>
                        <th colspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Dokumen'); ?></th>
                        <th colspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Penerimaan Aktual'); ?></th>
                        <th rowspan="2" style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Ket'); ?></th>
                        <th colspan="3" style="border-bottom: solid 1px #000; border-top: solid 1px #000;"><?= Yii::t('app', 'Dikirim'); ?></th>
					</tr>
                    <tr>
                        <th style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;">Qty</th>
                        <th style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;">Vol</th>
                        <th style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;">Qty</th>
                        <th style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;">Vol</th>
                        <th style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;">Kode</th>
                        <th style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;">Tanggal</th>
                        <th style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;">Vol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = $model->searchLaporanPenerimaanJasaKD()->createCommand()->rawSql;
                    $modDetail = Yii::$app->db->createCommand($sql)->queryAll();
					$total_qty = 0; $total_vol = 0;
					$total_qty_act = 0; $total_vol_act = 0;
					$totals = 0;
                    if(count($modDetail) > 0){
                        foreach($modDetail as $i => $detail){
							$total_qty += $detail['qty_kecil'];
							$total_vol += $detail['kubikasi'];
							// $total_vol = 0.123+0.123;
							$total_qty_act += $detail['qty_kecil_actual'];
							$total_vol_act += $detail['kubikasi_actual'];
							if($detail['status'] == 'REALISASI'){
								$totals += $detail['kubikasi_actual'];
							}
							?>
                        <tr>
                            <td><?= $i+1; ?></td>
                            <td><?= $detail['kode']; ?></td>
							<td><?= \app\components\DeltaFormatter::formatDateTimeForUser($detail['tanggal']); ?></td>
							<td><?= $detail['sales_nm']; ?></td>
							<td><?= \app\components\DeltaFormatter::formatDateTimeForUser($detail['tanggal_kirim']); ?></td>
							<td><?= $detail['cust_pr_nama']?$detail['cust_pr_nama']:$detail['cust_an_nama']; ?></td>
							<td><?= \app\components\DeltaFormatter::formatDateTimeForUser($detail['tgl_terima']); ?></td>
							<td><?= $detail['nopol']; ?></td>
							<td><?= $detail['nomor_palet']; ?></td>
							<td><?= $detail['nama']; ?></td>
							<td><?= $detail['dimensi']; ?></td>
							<td style="text-align: right;"><?= $detail['qty_kecil']; ?></td>
							<td style="text-align: right;"><?= number_format($detail['kubikasi'], 4); ?></td>
							<td style="text-align: right;"><?= $detail['qty_kecil_actual']; ?></td>
							<td style="text-align: right;"><?= number_format($detail['kubikasi_actual'], 4); ?></td>
							<td><?= $detail['keterangan']; ?></td>
							<td><?= $detail['status']=='REALISASI'?$detail['kode_spm']:'-'; ?></td>
							<td><?= $detail['status']=='REALISASI'?\app\components\DeltaFormatter::formatDateTimeForUser($detail['tgl_spm']):'-'; ?></td>
							<td style="text-align: right;"><?= $detail['status']=='REALISASI'?number_format($detail['kubikasi_actual'], 4):'-'; ?></td>
                        </tr>
                        <?php }
                    } else { ?>
						<tr>
							<td colspan="19" class="text-align-center">Data tidak ditemukan</td>
						</tr>
					<?php }
                    ?>
                </tbody>
				<tfoot>
					<tr>
                        <th colspan="11" style="text-align:right; font-weight: bold;">TOTAL</th>
                        <th style="text-align:right; font-weight: bold;"><?= $total_qty; ?></th>
                        <th style="text-align:right; font-weight: bold;"><?= number_format($total_vol, 4) ?></th>
                        <th style="text-align:right; font-weight: bold;"><?= $total_qty_act; ?></th>
                        <th style="text-align:right; font-weight: bold;"><?= number_format($total_vol_act, 4); ?></th>
                        <th colspan="3"></th>
                        <th style="text-align:right; font-weight: bold;"><?= number_format($totals, 4); ?></th>
                    </tr>
				</tfoot>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; border-top: solid 1px #000; height: 20px; vertical-align: top;">
			<?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-12-0</span>
		</td>
	</tr>
</table>