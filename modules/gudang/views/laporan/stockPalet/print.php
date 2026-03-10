<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - Per_'.(!empty($model->per_tanggal)?$model->per_tanggal:date('d/m/Y')).'.xls"');
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
                        <i><h5 class="pull-right font-red-flamingo"></h5></i>
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
											<th><?= Yii::t('app', 'Jenis Produk') ?></th>
											<th><?= Yii::t('app', 'Nama Produk') ?></th>
											<th style="width: 250px;"><?= Yii::t('app', 'Dimensi') ?></th>
											<th><?= Yii::t('app', 'KBJ') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Lokasi<br>Gudang') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Total Qty<br>(Pcs)') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Total<br>Kubikasi<br>(M<sup>3</sup>)') ?></th>
                                            <th style="line-height: 1">USG<br>(Hari)</th>
                                            <th style="line-height: 1">USP<br>(Hari)</th>
										</tr>
                                    </thead>
									<tbody>
									<?php
									$sql = $model->searchLaporanPalets()->createCommand()->rawSql;
									$contents = Yii::$app->db->createCommand($sql)->queryAll();	
									if(count($contents)>0){ 
										foreach($contents as $i => $data) {
											?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data['produk_group']; ?></td>
												<td><?= $data['produk_nama'] ?></td>
												<td style="text-align: center;"><?= $data['produk_dimensi'] ?></td>
												<td style="text-align: center;"><?= $data['nomor_produksi'] ?></td>
												<td style="text-align: center;"><?= $data['gudang_nm'] ?></td>
												<td style="text-align: right;"><?= $data['qty_kecil'] ?></td>
												<td style="text-align: right">
													<?php echo number_format($data['kubikasi'],4);  ?>
												</td>
                                                <td style="text-align: right"><?php echo $data['hari'];?></td>
                                                <td style="text-align: right"><?php echo $data['harii'];?></td>
											</tr>
											<?php 
											$tot = $data['tot'];
											if ($tot > 1) {
												$tbko_id = $data['tbko_id'];
												$sql_t_terima_ko_kd = "select * from t_terima_ko_kd where t_terima_ko_kd.tbko_id = ".$tbko_id."";
												$t_terima_ko_kd = Yii::$app->db->createCommand($sql_t_terima_ko_kd)->queryAll();
												foreach ($t_terima_ko_kd as $kolom) {
													$tbko_kd_id = $kolom['tbko_kd_id'];
													$qty = $kolom['qty'];
													$kapasitas_kubikasi = $kolom['kapasitas_kubikasi'];
													$t = $kolom['t']." ".$kolom['t_satuan'];
													$l = $kolom['l']." ".$kolom['l_satuan'];
													$p = $kolom['p']." ".$kolom['p_satuan'];

												?>
											<tr>
												<td class='text-center text-default'></td>
												<td class='text-center text-default'><font style="color: #ccc;"><?= $data['produk_group'] ?></font></td>
												<td class='text-center text-default'><font style="color: #ccc;"><?= $data['produk_nama'] ?></font></td>
												<td class='text-center text-default' style="text-align: center;"><font style="color: #ccc;"><?php echo $t." x ".$l." x ".$p."";?></font></td>
												<td class='text-center text-default'><font style="color: #ccc;"><?= $data['nomor_produksi'] ?></font></td>
												<td class='text-center text-default' style="text-align: center;"><font style="color: #ccc;"><?php echo $data['gudang_nm'];?></font></td>
												<td class='text-right text-default'><font style="color: #ccc;"><?php echo $qty;?></font></td>
												<td class='text-right text-default'><font style="color: #ccc;"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($kapasitas_kubikasi,4);?></font></td>
                                                <td class='text-right text-default'><font style="color: #ccc;"><?php echo $data['hari'];?></font></td>
                                                <td class='text-right text-default'><font style="color: #ccc;"><?php echo $data['harii'];?></font></td>
											</tr>
												<?php
												}
											?>
											<?php
											}

										}									
									} else {
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
