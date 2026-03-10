<?php
/* @var $this yii\web\View */
$this->title = 'Release '.$paramprint['judul'];
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
<style>
	@media print {
		tfoot {
			display: table-header-group;
		}

        table{
            font-family: 'Arial Narrow';
        }
	}
</style>
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
											<th style="font-size: 11pt;"><?= Yii::t('app', 'No.'); ?></th>
											<th style="font-size: 11pt;"><?= Yii::t('app', 'Penerima<br>Pembayaran') ?></th>
											<th style="font-size: 11pt;"><?= Yii::t('app', 'Jumlah Bayar') ?></th>
											<th style="font-size: 11pt;"><?= Yii::t('app', 'Bank Tujuan'); ?></th>
											<th style="font-size: 11pt;"><?= Yii::t('app', 'No. Rek'); ?></th>
											<th style="font-size: 11pt;"><?= Yii::t('app', 'Rek. a/n'); ?></th>
											<th style="font-size: 11pt;"><?= Yii::t('app', 'No. Cek'); ?></th>
											<th style="font-size: 11pt;"><?= Yii::t('app', 'Keterangan'); ?></th>
											<th style="font-size: 11pt;"><?= Yii::t('app', 'Status') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php 
										$contents = $model->searchRelease()->all();
										if(!empty($contents)){ 
											$all_total = 0;
											foreach($contents as $i => $data){ 
												$all_total += $data['total_nominal'];
												$kategori = substr($data->kategori,4);
												
												if($data->tipe == "Open Voucher"){
													// $tipe = $data->tipe . '<br>' . $data->tipe_ov .'';
													$tipe = $data->tipe_ov;
												} else {
													$tipe = $data->tipe;
												}
													
										?>
											<tr>
												<td style="text-align: center; font-size: 11pt;"><?= $i+1; ?></td>
												<td style="text-align: left; font-size: 11pt;">
													<?php 
													if($data->suplier_nm_company !== null){
														$supplier = $data->suplier_nm_company;
													}else if($data->suplier_nm !== null){
														$supplier = $data->suplier_nm;
													}else if($data->gkk_kode !== null){
														$supplier= $data->gkk_kode;
													}else if($data->ppk_kode !== null){
														$supplier= $data->ppk_kode;
													}else if($data->pdg_kode !== null){
														$supplier= $data->grader_dinas;
													}else if($data->pmg_kode !== null){
														$supplier= $data->grader_makan;
													}else if($data->kode_dp !== null){
														$supplier= $data->kode_dp;
													}else if($data->kode_pelunasan !== null){
														$supplier= $data->kode_pelunasan;
													}else{
														$supplier = $data->suplierov;
													}
													echo $supplier;
													?>
												</td>
												<td style="text-align: right; font-size: 11pt;"><?= app\components\DeltaFormatter::formatNumberForPrint($data->total_nominal) ?></td>
												<?php 
													if($data->tipe == "Open Voucher" || $data->tipe == "Uang Dinas Grader" || $data->tipe == "Uang Makan Grader"){
														if($data->penerima_pembayaran !== null) {
															$penerima = json_decode($data->penerima_pembayaran);
															$bank = $penerima[0]->nama_bank;
															$rek = $penerima[0]->rekening;
															$an_rek = $penerima[0]->an_bank;
														} else {
															$bank = '';
															$rek = '';
															$an_rek = '';
														}
													} else {
														$bank = $data->suplier_bank;
														$rek = $data->suplier_norekening;
														$an_rek = $data->suplier_an_rekening;
													}
												?>
												<td style="text-align: left; font-size: 11pt;"><?= $bank; ?></td>
												<td style="text-align: left; font-size: 11pt;"><?= $rek ?></td>
												<td style="text-align: left; font-size: 11pt;"><?= $an_rek ?></td>
												<td style="text-align: left; font-size: 11pt;">
													<?php 
														if($data->cara_bayar == "Cek"){
															$nocek = $data->cara_bayar_reff;
														} else {
															$nocek = '';
														}
														echo $nocek;
													?>
												</td>
												<td style="text-align: left; font-size: 11pt;"><?= substr($data->keterangan, 0, 200); ?></td>
												<td style="text-align: center; font-size: 11pt;">
                                                    <?php 
														$status = $data->status;
														if($data->status == 'APPROVED'){
															if($data->status_pengajuan == 'Ditunda'){
																$status = "<span style='font-size: 1rem; color: red;'>*ajukan ulang ditanggal berikutnya</span>";
															}
														}
														echo $status;
													?>
                                                </td>
											</tr>
										<?php }
										}else{ ?>
											<tr>
												<td colspan="14" class="text-align-center">Data Tidak Ditemukan</td>
											</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr class="total-row">
											<td colspan="2" style="text-align: right; font-size: 11pt;"><b>Total</b>&nbsp;</td>
											<td style="text-align: right; font-size: 11pt;"><b><?= !empty($contents)? app\components\DeltaFormatter::formatNumberForPrint($all_total):0 ?></b></td>
										</tr>
									</tfoot>
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