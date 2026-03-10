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
											<th><?= Yii::t('app', 'Status<br>Pengajuan') ?></th>
											<th><?= Yii::t('app', 'Status<br>Pembayaran') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporan()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											$num = 1;
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;">
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
												<td class="text-align-center">
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
												<td class="text-align-center">
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
												<td class="text-align-center">
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
                                                <td class="td-kecil">
													<?php
													if(!empty($data['suplier_nm'])){
														$ret = $data['suplier_nm'];
													}else{
														if($data['tipe']=='Top-up Kas Kecil'){
															$ret = "Kas Kecil CWM";
														}else if($data['tipe']=='Uang Dinas Grader'){
															$ret = $data['graderdinas_nm'];
														}else if($data['tipe']=='Uang Makan Grader'){
															$ret = $data['gradermakan_nm'];
														}else if($data['tipe']=='Ganti Kas Kecil'){
															$ret = $data['penerimagkk'];
														}else if($data['tipe']=='Open Voucher'){
                                                            if($data['tipe_openvoucher']=="DP LOG SENGON"||$data['tipe_openvoucher']=="PELUNASAN LOG SENGON"){
                                                                $ret = "<b>".$data['tipe_openvoucher']."</b><br>".$data['penerima_openvoucher'];
                                                            } else if($data['tipe_openvoucher'] == "PEMBAYARAN ASURANSI LOG SHIPPING"){
																$ret = "<b>".$data['tipe_openvoucher']."</b><br>".$data['kepada'];
															} else if($data['tipe_openvoucher'] == "REGULER"){
																$ret = "<b>".$data['tipe_openvoucher']."</b><br>".$data['nama_penerima'];
															} else{
                                                                $ret = "<b>".$data['tipe_openvoucher']."</b><br>".$data['company_openvoucher'];
                                                            }
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
												<td class="text-align-center">
													<?php 
													$ret = $data['nama_bank']?$data['nama_bank']:'';
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															echo $ret;
														}
													}else{
														echo $ret;
													} ?>
												</td>
												<td class="text-align-center">
													<?php 
													$ret = $data['rekening']?$data['rekening']:'';
													if($i!=0){
														if($data['kode'] != $contents[($i-1)]['kode']){
															echo $ret;
														}
													}else{
														echo $ret;
													} ?>
												</td>
												<td class="text-align-center"></td>
												<td class=""><?= $data['keterangan'] ?></td>
												<td class="text-align-right">
													<?php
													if($_GET['caraprint'] == "EXCEL"){
														echo $data['jumlah'];
													}else{
														echo $data['mata_uang']=="IDR"?app\components\DeltaFormatter::formatNumberForUser($data['jumlah']):app\components\DeltaFormatter::formatNumberForUserFloat($data['jumlah'], 2);
													}
													?>
												</td>
												<td class="text-align-center">
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
												<td class="text-align-center">
													<?php 
													if(!empty($data['status_drp'])){
														if($data['status_approve'] == 'APPROVED'){
															$ret =  $data['status_pengajuan'];
														} else {
															$ret = 'Diajukan DRP';
														}
													} else {
														$ret = '';
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
												<td class="text-align-center">
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
												$total_nominal = $data['mata_uang']=="IDR"?app\components\DeltaFormatter::formatNumberForUser($data['total_nominal']):app\components\DeltaFormatter::formatNumberForUserFloat($data['total_nominal'], 2);
											}
											$ret = "<tr style='background-color:#C3CBD9;'>
														<td colspan='10' class='text-align-right'><b>TOTAL</b> &nbsp; </td>
														<td class='text-align-right'><b>".$total_nominal."</b></td>
														<td></td>
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
											echo "<tr><td colspan='13' class='text-align-center'>".Yii::t('app', 'Data tidak ditemukan')."</td></tr>";
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