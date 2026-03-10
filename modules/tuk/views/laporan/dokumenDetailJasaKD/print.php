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
										<th style="line-height: 1"><?= Yii::t('app', 'No') ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'Tanggal') ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Dokumen') ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'Customer') ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'Produk') ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'T') ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'L') ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'P') ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'Total<br>Pcs') ?></th>
										<th style="line-height: 1"><?= Yii::t('app', 'Total<br>M<sup>3</sup>') ?></th>
										</tr>
                                    </thead>
									<tbody>

									<?php
									$tgl_awal = $model->tgl_awal;
									$tgl_akhir = $model->tgl_akhir;
									$jenis_produk = $model->jenis_produk;
									$cust_id = $model->cust_id;
									$dokumen_penjualan_id = $model->dokumen_penjualan_id;

									$tgl_awal == "" ? $tgl_awal = date('d-m-Y') : $tgl_awal = $tgl_awal;
									$tgl_akhir == "" ? $tgl_awal = date('d-m-Y') : $tgl_akhir = $tgl_akhir;

									if ($cust_id != "") {
										$and_cust_id = "and t_dokumen_penjualan.cust_id = ".$cust_id."";
									} else {
										$and_cust_id = "";
									}

									if ($dokumen_penjualan_id != "") {
										$and_dokumen_penjualan_id = "and t_dokumen_penjualan.dokumen_penjualan_id = ".$dokumen_penjualan_id."";
									} else {
										$and_dokumen_penjualan_id = "";
									}

									$sql_x = "select t_dokumen_penjualan.nomor_dokumen, t_spm_ko.op_ko_id, t_spm_ko_detail.keterangan
												from t_dokumen_penjualan_detail 
												join t_dokumen_penjualan on t_dokumen_penjualan.dokumen_penjualan_id = t_dokumen_penjualan_detail.dokumen_penjualan_id
												join t_spm_ko_detail on t_spm_ko_detail.spm_kod_id = t_dokumen_penjualan_detail.spm_kod_id
												join t_spm_ko on t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id
												where t_dokumen_penjualan.tanggal between '".$tgl_awal."' and '".$tgl_akhir."'
												and t_dokumen_penjualan.jenis_produk = 'JasaKD'
												".$and_cust_id."
												".$and_dokumen_penjualan_id."
												group by t_dokumen_penjualan.nomor_dokumen, t_spm_ko.op_ko_id, t_spm_ko_detail.keterangan
												order by t_dokumen_penjualan.nomor_dokumen desc
												";
									
									$query_x = \Yii::$app->db->createCommand($sql_x)->queryAll();
									$numrows_x = count($query_x);
									$total_baris = 0;
									$i = 0;

									if (count($query_x) > 0){
										foreach($query_x as $kolom){
											$i % 2 == 0 ? $color = '#edfff2' :  $color = '#fffcf5'; 
											$nomor_dokumen = $kolom['nomor_dokumen'];
											$op_ko_id = $kolom['op_ko_id'];
											$keterangan = $kolom['keterangan'];
											$sql_y = "select t_dokumen_penjualan.tanggal, 
														t_dokumen_penjualan.nomor_dokumen, 
														t_dokumen_penjualan.jenis_produk, 
														m_customer.cust_an_nama, 
														m_produk_jasa.nama as produk_nama, 
														t_terima_jasa.t, 
														t_terima_jasa.l, 
														t_terima_jasa.p, 
														t_terima_jasa.qty_kecil, 
														t_terima_jasa.kubikasi, 
														t_terima_jasa.terima_jasa_id,
														count(*) OVER() AS total_count 
														from t_dokumen_penjualan_detail 
														join t_dokumen_penjualan on t_dokumen_penjualan.dokumen_penjualan_id = t_dokumen_penjualan_detail.dokumen_penjualan_id
														join t_spm_ko on t_spm_ko.spm_ko_id = t_dokumen_penjualan.spm_ko_id
														join t_spm_ko_detail on t_spm_ko_detail.spm_ko_id = t_spm_ko.spm_ko_id
														join m_customer on m_customer.cust_id = t_spm_ko.cust_id
														join t_op_ko on t_op_ko.op_ko_id = t_spm_ko.op_ko_id
														join t_op_ko_detail on t_op_ko_detail.op_ko_id = t_op_ko.op_ko_id
														join t_terima_jasa on t_terima_jasa.op_ko_id = t_op_ko.op_ko_id
														join m_produk_jasa on m_produk_jasa.produk_jasa_id = t_terima_jasa.produk_jasa_id
														where t_dokumen_penjualan.tanggal between '".$tgl_awal."' and '".$tgl_akhir."'
														and t_dokumen_penjualan.nomor_dokumen = '".$nomor_dokumen."'
														and t_terima_jasa.nomor_palet in (".$keterangan.")
														and t_terima_jasa.op_ko_id in ($op_ko_id)
														".$and_cust_id."
														".$and_dokumen_penjualan_id."
														group by t_dokumen_penjualan.nomor_dokumen,
														t_dokumen_penjualan.tanggal,
														t_dokumen_penjualan.jenis_produk,
														m_customer.cust_an_nama,
														m_produk_jasa.nama,
														t_terima_jasa.t, 
														t_terima_jasa.l, 
														t_terima_jasa.p, 
														t_terima_jasa.qty_kecil, 
														t_terima_jasa.kubikasi,
														t_terima_jasa.terima_jasa_id					
														order by t_terima_jasa.terima_jasa_id asc
														";
											$query_y = \Yii::$app->db->createCommand($sql_y)->queryAll();
											$numrows_y = count($query_y);
											$j = 0;
											foreach ($query_y as $detail) {
												$tanggal = $detail['tanggal'];
												$nomor_dokumen = $detail['nomor_dokumen'];
												$jenis_produk = $detail['jenis_produk'];
												$nama = $detail['cust_an_nama'];
												$customer = $detail['cust_an_nama'];
												$produk_nama = $detail['produk_nama'];
												$t = $detail['t'];
												$l = $detail['l'];
												$p = $detail['p'];
												$qty_kecil = $detail['qty_kecil'];
												$kubikasi = $detail['kubikasi'];
												$terima_jasa_id = $detail['terima_jasa_id'];
												$j++;
											?>
											<tr>
												<td class="td-kecil text-align-center" style="vertical-align: middle; font-size: 1.1rem;">
													<?php echo $j;?>
												</td>
												<td class="td-kecil text-align-left" style="font-size: 1.1rem; line-break: 1;"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($tanggal);?></td>
												<td class="td-kecil text-align-left" style="font-size: 1.1rem;"><?php echo $nomor_dokumen;?></td>
												<td class="td-kecil text-align-left" style="font-size: 1.1rem;"><?php echo $jenis_produk;?></td>
												<td class="td-kecil text-align-left" style="font-size: 1.1rem;"><?php echo $customer;?></td>
												<td class="td-kecil text-align-center" style="font-size: 1.1rem;"><?php echo $produk_nama;?></td>
												<td class="td-kecil text-align-right" style="font-size: 1.1rem;"><?php echo $t;?></td>
												<td class="td-kecil text-align-right" style="font-size: 1.1rem;"><?php echo $l;?></td>
												<td class="td-kecil text-align-right" style="font-size: 1.1rem;"><?php echo $p;?></td>
												<td class="td-kecil text-align-right" style="font-size: 1.1rem;"><?php echo $qty_kecil;?>
												<td class="td-kecil text-align-right" style="font-size: 1.1rem;"><?php echo $kubikasi;?></td>
											</tr>

											<?php
											}
											$i++;
										}
									?>
									<tr>
										<td class="td-kecil text-align-right" colspan="9"></td>
										<td class="td-kecil text-align-right"></td>
										<td class="td-kecil text-align-right"></td>
									</tr>
									<?php
									}
									?>
									</tbody>
									<tfoot>
										<br><br><br>
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