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
											<th ><?= Yii::t('app', 'Kode Nota') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Nota') ?></th>
											<th ><?= Yii::t('app', 'Sales'); ?></th>
											<th ><?= Yii::t('app', 'Customer'); ?></th>
											<th ><?= Yii::t('app', 'Alamat Customer'); ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Jenis<br>Produk'); ?></th>
											<th ><?= Yii::t('app', 'Produk'); ?></th>
											<th ><?= Yii::t('app', 'Dimensi'); ?></th>
											<th ><?= Yii::t('app', 'Pcs'); ?></th>
											<th ><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
											<th  style="line-height: 1;"><?= Yii::t('app', 'Harga<br>Satuan'); ?></th>
											<th  style="line-height: 1;"><?= Yii::t('app', 'Total<br>Harga'); ?></th>
											<th  style="line-height: 1; width: 175px;"><?= Yii::t('app', 'Reff No.'); ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){
											
											?>
											<tr>
												<td style="text-align: center; font-size: 1.2rem; vertical-align: top;"><?= $i+1; ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $data->kode ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal); ?></td>
												<td style="font-size: 1.2rem; text-align: center; vertical-align: top;"><?= $data->sales_kode ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $data->cust_an_nama ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $data->cust_an_alamat ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $data->jenis_produk ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;">
												<?php
													if ($data->jenis_produk == "Limbah") {
														$data->limbah_kode != '' ? $kode = $data->limbah_kode : $kode = ''; 
														echo $kode." - (".$data->limbah_produk_jenis.") ".$data->limbah_nama;
													} else if ($data->jenis_produk == "JasaKD") {
														$kode_nota = $data->kode;
														$t_nota_penjualan = \app\models\TNotaPenjualan::find()->where(['kode'=>$kode_nota])->one();
															$nota_penjualan_id = $t_nota_penjualan->nota_penjualan_id;
														$t_nota_penjualan_detail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id'=>$nota_penjualan_id])->one();
															$produk_jasa_id = $t_nota_penjualan_detail->produk_id;
														$m_produk_jasa = \app\models\MProdukJasa::find()->where(['produk_jasa_id'=>$produk_jasa_id])->one();
															$kode = $m_produk_jasa->kode;
															$nama = $m_produk_jasa->nama;
														echo $kode." - ".$nama;
													} else if($data->jenis_produk == "Log"){
														$log = $data->log_nama;
														if($data->alias){
															$log .= " - " . $data->produk_alias;
														}
														echo $log;
													}else {
														echo $data->produk_nama;
													}
													?>												
												</td>
												<td style="font-size: 1.2rem; vertical-align: top;">
												<?php
													if ($data->jenis_produk == "Limbah") {
														echo '<center>-</center>';
													} else if ($data->jenis_produk == "JasaKD") {
														echo '<center>-</center>';
													} else if($data->jenis_produk == "Log"){
														echo 'Range diameter :<br>'.$data->range_awal . 'cm - '. $data->range_akhir .'cm';
													}else {
														echo $data->produk_dimensi;
													}
													?>
												</td>
												<td style="text-align: right; font-size: 1.2rem; vertical-align: top;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($data->qty_kecil) ?></td>
												<td style="text-align: right; font-size: 1.2rem; vertical-align: top;">
													<?php
                                                    if($data->jenis_produk == "Limbah"){
                                                        if($data->limbah_satuan_jual == "Rit"){
                                                            echo $data->limbah_satuan_muat;
                                                        }else{
                                                            echo $data->limbah_satuan_jual;
                                                        }
                                                    }else{
                                                        if(app\components\Params::USER_GROUP_ID_STAFF_FINNACC == Yii::$app->user->identity->user_group_id){
                                                            echo \app\components\DeltaFormatter::formatNumberForUserFloat($data->kubikasi);
                                                        }else{
                                                            echo \app\components\DeltaFormatter::formatNumberForUserFloat($data->kubikasi);
                                                        }
                                                    }
													?>
												</td>
												<td style="text-align: right; font-size: 1.2rem; vertical-align: top;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($data->harga_jual) ?></td>
												<td style="text-align: right; font-size: 1.2rem; vertical-align: top;">
													<?php
													$subtotal = 0;
													if($data->jenis_produk == "Plywood" || $data->jenis_produk == "Lamineboard" || $data->jenis_produk == "Platform" || $data->jenis_produk == "Limbah" || $data->jenis_produk == "FingerJointLamineBoard" || $data->jenis_produk == "FingerJointStick" || $data->jenis_produk == "Flooring"){
														$subtotal = $data->qty_kecil * $data->harga_jual;
													}else{
														$subtotal = $data->kubikasi * $data->harga_jual;
													}
													echo \app\components\DeltaFormatter::formatNumberForUserFloat(round($subtotal));
													?>
												</td>
												<td style="text-align: right;  vertical-align: top; font-size: 1rem;">
													<?php
													if($_GET['caraprint'] == "EXCEL"){
														echo "Kode Nota : ".$data->kode."; ";
														echo "Kode OP : ".$data->kode_op."; ";
														echo "Kode SPM : ".$data->kode_spm."; ";
														echo "Kode SP : ".$data->kode_sp."; ";
														echo "Dok. Kayu : ".$data->nomor_dokumen."; ";
													}else{ ?>
														<table style="width: 100%;">
															<tr>
																<td style="font-size: 1rem;">Kode Nota</td>
																<td style="font-size: 1rem;">:</td>
																<td style="font-size: 1rem;"><?= $data->kode; ?></td>
															</tr>
															<tr>
																<td style="font-size: 1rem; width: 30%;">Kode OP</td>
																<td style="font-size: 1rem; width: 5%;">:</td>
																<td style="font-size: 1rem; width: 65%;"><?= $data->kode_op; ?></td>
															</tr>
															<tr>
																<td style="font-size: 1rem;">Kode SPM</td>
																<td style="font-size: 1rem;">:</td>
																<td style="font-size: 1rem;"><?= $data->kode_spm; ?></td>
															</tr>
															<tr>
																<td style="font-size: 1rem;">Kode SP</td>
																<td style="font-size: 1rem;">:</td>
																<td style="font-size: 1rem;"><?= $data->kode_sp; ?></td>
															</tr>
															<tr>
																<td style="font-size: 1rem;">Dok. Kayu</td>
																<td style="font-size: 1rem;">:</td>
																<td style="font-size: 0.9rem;"><?= $data->nomor_dokumen; ?></td>
															</tr>
														</table>
													<?php } ?>
												</td>
											</tr>
										<?php }
										}else{
											"<tr colspan='5'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
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