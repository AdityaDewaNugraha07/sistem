<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
				<div class="row">
                    <div class="col-md-12">
						<?php echo $header; ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
                                        <tr>
										<th><?= Yii::t('app', 'No.'); ?></th>
										<th><?= Yii::t('app', 'Kode') ?></th>
										<th><?= Yii::t('app', 'Tanggal') ?></th>
										<th><?= Yii::t('app', 'Tipe') ?></th>
										<th><?= Yii::t('app', 'Dept') ?></th>
										<th><?= Yii::t('app', 'Reff No') ?></th>
										<th><?= Yii::t('app', 'Penerima') ?></th>
										<th><?= Yii::t('app', 'Penerima QQ') ?></th>
										<th><?= Yii::t('app', 'Cara Bayar') ?></th>
										<th><?= Yii::t('app', 'Total Tagihan') ?></th>
										<th><?= Yii::t('app', 'Total Kubikasi') ?></th>
										<th><?= Yii::t('app', 'Status Pembayaran') ?></th>
										<th><?= Yii::t('app', 'Prepared By') ?></th>
										<th><?= Yii::t('app', 'Keterangan') ?></th>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											$jml = 0;
											foreach($contents as $i => $data){ 
												$jml += $data->total_pembayaran;
											?>
											<tr class="td-kecil">
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->kode ?></td>
												<td style="text-align: center;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal) ?></td>
												<td><?= $data->tipe ?></td>
												<td style="text-align: center;"><?= $data->departement_nama ?></td>
												<td><?= ($data->reff_no == null)?'-':$data->reff_no ?></td>
												<td>
													<?php 
													$penerima = '-';
													if($data->tipe =="REGULER"){
														if($data->penerima_voucher_id !== null){
															$penerima = '<b>'.$data->nama_penerima.'</b><br>'.$data->nama_perusahaan;
														} else{
															$penerima = '<b>'.$data->pegawai_nama.'</b><br>'.$data->departement_nama;
														}
													} else if($data->tipe =="PEMBAYARAN LOG ALAM"){
														$penerima = '<b>'.$data->suplier_nm.'</b><br>'.$data->suplier_nm_company;
													} else if($data->tipe =="DEPOSIT SUPPLIER LOG"){
														$penerima = '<b>'.$data->suplier_nm.'</b><br>'.$data->suplier_nm_company;
													} else if($data->tipe  == "DP LOG SENGON"){
														$penerima = '<b>'.$data->suplier_nm.'</b><br>'.$data->suplier_almt;
													} else if($data->tipe  == "PELUNASAN LOG SENGON"){
														$penerima = '<b>'.$data->suplier_nm.'</b><br>'.$data->suplier_almt;
													} else if($data->tipe == "PEMBAYARAN ASURANSI LOG SHIPPING"){
														$penerima = $data->kepada;
													}
													echo $penerima;
													?>
												</td>
												<td><?= $data->penerima_voucher_qq; ?></td>
												<td style="text-align: center;"><?= $data->cara_bayar; ?></td>
												<td style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForPrint($data->total_pembayaran); ?></td>
												<td style="text-align: right;">
												<?php 
													$kubikasi = "";
													if( $data->tipe=="PELUNASAN LOG SENGON" ){
														$asd = json_decode($data->keterangan_sengon, true);
														$totalm3 = 0;
														foreach ($asd as $i => $v) {
															$m3 = 0;
															foreach($v['diameter_harga'] as $ii => $vv){
																$m3 += $vv['m3'];
															}
															$totalm3 += $m3;
														}
														$kubikasi .= "<br>".app\components\DeltaFormatter::formatNumberForUserFloat( $totalm3 )." m<sup>3</sup>";
													}
													echo $kubikasi;
													?>
												</td>
												<td style="text-align: center;">
													<?php
													if($data->status_bayar == "WAITING"){
														$tanggal = '';
													} else {
														if($data->status_bayar == "PAID"){
															$a = 'at ';
														} else {
															$a = 'plan ';
														}
														$tanggal = '<br>'. $a . app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal_bayar);
													}
													echo $data->status_bayar. '<span class="td-kecil2">'. $tanggal .'</span>'; 
													?>
												</td>
												<td style="text-align: center;"><?= $data->pegawai_nama; ?></td>
												<td style="font-size:0.9rem">
													<?php 
													$ket = "";
													if( $data->tipe=="PELUNASAN LOG SENGON" ){
														$asd = json_decode($data->keterangan_sengon, true);
														foreach ($asd as $i => $v) {
															$m3 = 0;
															$ket .= "<b>" . htmlspecialchars($v['reff_no']) . "</b> - " . app\components\DeltaFormatter::formatNumberForUserFloat($v['total_m3']) . " m<sup>3</sup>";
															if (($i + 1) < count($asd)) {
																$ket .= "<br>";
															}
														}
													} else {
														$ket = $data->keterangan;
													}
													echo $ket;
													?>
												</td>
											</tr>
										<?php }
										}else{
											"<tr colspan='5'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
										}
										?>
									</tbody>
									<!-- <tfoot>
										<tr>
											<td colspan="9" style="text-align: right;"><b>Total All</b></td>
											<td style="text-align: right;"><b><?php //echo app\components\DeltaFormatter::formatNumberForPrint($jml) ?></b></td>
										</tr>
									</tfoot> -->
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>