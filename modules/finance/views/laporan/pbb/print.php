<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
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
											<th><?= Yii::t('app', 'BBK') ?></th>
											<th><?= Yii::t('app', 'Tanggal') ?></th>
											<th><?= Yii::t('app', 'No. Cek/BG'); ?></th>
											<th><?= Yii::t('app', 'Penerima') ?></th>
											<th><?= Yii::t('app', 'Bank<br>Penerima') ?></th>
											<th><?= Yii::t('app', 'No. Rek<br>Penerima') ?></th>
											<th><?= Yii::t('app', 'Kode<br>Perkiraan') ?></th>
											<th><?= Yii::t('app', 'Keterangan') ?></th>
											<th><?= Yii::t('app', 'Nominal') ?></th>
											<th><?= Yii::t('app', 'Bank') ?></th>
											<th><?= Yii::t('app', 'Status<br>Pembayaran') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										if ($tipe == "Pembelian BHP" || $tipe == "Pembayaran DP BHP") {
											$sql = $model->searchLaporan()->createCommand()->rawSql;
										} else {
											$sql = $model->searchLaporanPbb()->createCommand()->rawSql;
										}
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											$num = 1;
											foreach($contents as $i => $data){ 
												if ($tipe == "Pembelian BHP" || $tipe == "Pembayaran DP BHP") {
													$bank = $data['nama_bank']?$data['nama_bank']:'';
													$rek = $data['rekening']?$data['rekening']:'';;
												} else {
													if($data['tipe'] == "Open Voucher" || $data['tipe'] == "Uang Dinas Grader" || $data['tipe'] == "Uang Makan Grader"){
														if($data['penerima_pembayaran'] !== null) {
															$penerima = json_decode($data['penerima_pembayaran']);
															$bank = $penerima[0]->nama_bank;
															$rek = $penerima[0]->rekening;
														} else {
															$bank = '';
															$rek = '';
														}
													} else {
														$bank = $data['suplier_bank'];
														$rek = $data['suplier_norekening'];
													}
												}
											?>
											
											<tr>
												<td style="text-align: center;" class="td-kecil">
													<?php
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															$num = $num+1;
															echo $num;
														}
													}else{
														echo $num;
													}
													?>
												</td>
												<td class="td-kecil text-align-center">
													<?php
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															echo $data['kode'];
														}
													}else{
														echo $data['kode'];
													}
													?>
												</td>
												<td class="td-kecil text-align-center">
													<?php
													$ret = app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal_bayar']);
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															echo $ret;
														}
													}else{
														echo $ret;
													}
													?>
												</td>
												<td class="td-kecil text-align-center">
													<?php
													if($data['cara_bayar'] == 'Cek' || $data['cara_bayar'] == 'Bilyet Giro'){
														$ret = $data['cara_bayar_reff'];
													}else{
														$ret = $data['cara_bayar'];
													}
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															echo $ret;
														}
													}else{
														echo $ret;
													}
													?>
												</td>
                                                <td class="td-kecil text-align-center">
													<?php
													if(!empty($data['suplier_nm'])){
														$ret = $data['suplier_nm'];
													}else{
														if($data['tipe_openvoucher']=="DP LOG SENGON"||$data['tipe_openvoucher']=="PELUNASAN LOG SENGON"){
                                                             $ret = "<b>".$data['tipe_openvoucher']."</b><br>".$data['penerima_openvoucher'];
                                                        }else{
                                                            $ret = "<b>".$data['tipe_openvoucher']."</b><br>".$data['company_openvoucher'];
                                                        }
													}
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															echo $ret;
														}
													}else{
														echo $ret;
													}
													?>
												</td>
												<td class="td-kecil text-align-center">
													<?php 
													$ret = $bank;
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															echo $ret;
														}
													}else{
														echo $ret;
													}
													?>
												</td>
												<td class="td-kecil text-align-center">
													<?php 
													$ret = $rek;
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															echo $ret;
														}
													}else{
														echo $ret;
													}
													?>
												</td>
												<td class="td-kecil text-align-center"></td>
												<td class="td-kecil"><?= $data['keterangan'] ?></td>
												<td class="td-kecil text-align-right">
													<?php
													if($_GET['caraprint'] == "EXCEL"){
														echo $data['jumlah'];
													}else{
														echo app\components\DeltaFormatter::formatNumberForUserFloat($data['jumlah']);
													}
													?>
												</td>
												<td class="td-kecil text-align-center">
													<?php
													$ret = substr($data['acct_nm'], -3,3);
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															echo $ret;
														}
													}else{
														echo $ret;
													}
													?>
												</td>
												<td class="td-kecil text-align-center">
													<?php 
													$tgl_bayar = app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal_bayar']);
													if($data['status_bayar'] == 'PAID'){
														$ret = $data['status_bayar'] . '<br><span class="td-kecil2">at ' . $tgl_bayar .'<span class="td-kecil2">';
													} else {
														$ret = $data['status_bayar'] . '<br><span class="td-kecil2">plan ' . $tgl_bayar .'<span class="td-kecil2">';
													}
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															echo $ret;
														}
													}else{
														echo $ret;
													}
													?>
												</td>
											</tr>
											<?php
											if($_GET['caraprint'] == "EXCEL"){
												$total_nominal = $data['total_nominal'];
											}else{
												$total_nominal = app\components\DeltaFormatter::formatNumberForUserFloat($data['total_nominal']);
											}
											$ret = "<tr style='background-color:#C3CBD9;'>
														<td colspan='10' class='td-kecil text-align-right'><b>TOTAL</b> &nbsp; </td>
														<td class='td-kecil text-align-right'><b>".$total_nominal."</b></td>
														<td></td>
													</tr>";
											if(($i+1)!=(count($contents))){
												if($data['kode'] != $contents[($i+1)]['kode']){
													echo $ret;
												}
											}else{
												echo $ret;
											}
											?>
										<?php }
										}else{
											echo "<tr><td colspan='11' class='text-align-center'>".Yii::t('app', 'Data tidak ditemukan')."</td></tr>";
										}
										?>
									</tbody>
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