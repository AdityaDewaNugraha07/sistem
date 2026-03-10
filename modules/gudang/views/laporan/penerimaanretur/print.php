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
<style>
.td-kecils{
    line-height: 1 !important;
    padding: 3px !important;
    vertical-align: top !important;
    font-size:1.2rem !important;
}
</style>
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
											<th><?= Yii::t('app', 'Kode<br>Tanggal'); ?></th>
											<th><?= Yii::t('app', 'Customer'); ?></th>
											<th><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
											<th><?= Yii::t('app', 'Nama<br>Produk') ?></th>
											<th><?= Yii::t('app', 'Dimensi') ?></th>
											<th><?= Yii::t('app', 'Qty<br>Kecil') ?></th>
											<th><?= Yii::t('app', 'Kubikasi') ?></th>
											<th><?= Yii::t('app', 'Alasan<br>Retur') ?></th>
											<th><?= Yii::t('app', 'Petugas<br>Penerima') ?></th>
											<th><?= Yii::t('app', 'Waktu<br>Terima') ?></th>
											<th><?= Yii::t('app', 'Diperiksa<br>Security') ?></th>
											<th><?= Yii::t('app', 'Status') ?></th>
											<th><?= Yii::t('app', 'Nomor<br>Produksi') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporanPenerimaanRetur()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td class="td-kecils text-align-center"><?= $i+1; ?></td>
												<td class="td-kecils"><?= $data['kode'] . '<br>' . \app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']); ?></td>
												<td class="td-kecils text-align-center"><?= $data['cust_an_nama'] ?></td>
												<td class="td-kecils text-align-center"><?= $data['produk_group'] ?></td>
												<td class="td-kecils"><?= $data['produk_nama'] ?></td>
												<td class="td-kecils"><?= $data['produk_dimensi'] ?></td>
												<td class="td-kecils text-align-right"><?= $data['qty_kecil'] ?></td>
												<td class="td-kecils text-align-right"><?php echo number_format($data['kubikasi'],4); ?></td>
												<td class="td-kecils"><?= $data['alasan_retur'] ?></td>
												<td class="td-kecils text-align-center"><?= $data['petugas_penerima']?$data['petugas_penerima']:'-' ?></td>
												<td class="td-kecils text-align-center"><?= $data['waktu_terima']?\app\components\DeltaFormatter::formatDateTimeForUser2($data['waktu_terima']):'-' ?></td>
												<td class="td-kecils text-align-center"><?= $data['diperiksa_security']?$data['diperiksa_security']:'-' ?></td>
												<td class="td-kecils text-align-center"><?= $data['status']?$data['status']:'BELUM DITERIMA' ?></td>
												<td class="td-kecils text-align-center"><?= $data['nomor_produksi'] ?></td>
											</tr>
										<?php }
										}else{?>
											<tr>
												<td colspan="14" class="text-align-center"><?= Yii::t('app', 'Data tidak ditemukan'); ?></td>
											</tr>
										<?php } ?>
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