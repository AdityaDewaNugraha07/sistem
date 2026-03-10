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
                        <i><h5 class="pull-right font-red-flamingo">TRIAL VERSION</h5></i>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover table-detail-mepet" id="table-laporan">
                                    <thead>
                                        <tr>
											<th><?= Yii::t('app', 'No.'); ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Kode<br>Barang Jadi') ?></th>
											<th><?= Yii::t('app', 'Produk') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Tanggal Keluar') ?></th>
											<th><?= Yii::t('app', 'Reff No.') ?></th>
											<th><?= Yii::t('app', 'Pcs') ?></th>
											<th><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
											<th><?= Yii::t('app', 'Keterangan') ?></th>
											<th></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporanProdukKeluar()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td ><?= $i+1; ?></td>
												<td ><?= $data['nomor_produksi']; ?></td>
												<td style="text-align: left;"><?= $data['produk_nama'] ?></td>
												<td ><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tgl_transaksi']) ?></td>
												<td ><?= $data['reff_no'] ?></td>
												<td style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data['pcs']) ?></td>
												<td style="text-align: right"><?= (strlen(substr(strrchr($data['m3'], "."), 1)) > 4)? $data['m3']*10000/10000: $data['m3'];  ?></td>
												<td style="text-align: left;">
													<?php
													$ret = $data['keterangan'];
													if($data['keterangan']=="PENJUALAN"){
														$ret = $data['keterangan']." <b>".$data['penerima']."</b>";
													}
													echo $ret;
													?>
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