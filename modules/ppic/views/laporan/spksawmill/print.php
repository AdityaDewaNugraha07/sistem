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
											<th rowspan="2">No.</th>
											<th><?= Yii::t('app', 'Kode SPK') ?></th>
                                            <th><?= Yii::t('app', 'Revisi') ?></th>
                                            <th><?= Yii::t('app', 'Tgl Mulai'); ?></th>
                                            <th><?= Yii::t('app', 'Tgl Selesai'); ?></th>
                                            <th><?= Yii::t('app', 'Kode PO') ?></th>
                                            <th><?= Yii::t('app', 'Peruntukan') ?></th>
                                            <th><?= Yii::t('app', 'Line<br>Sawmill') ?></th>
                                            <th><?= Yii::t('app', 'Status<br>SPK') ?></th>
                                            <th><?= Yii::t('app', 'Jenis Kayu') ?></th>
                                            <th><?= Yii::t('app', 'Produk<br>Sawmill') ?></th>
                                            <th><?= Yii::t('app', 'Size') ?></th>
                                            <th><?= Yii::t('app', 'Panjang') ?></th>
                                            <th><?= Yii::t('app', 'Kategori<br>Ukuran') ?></th>
                                            <th><?= Yii::t('app', 'Status<br>Approval') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = $model->searchLaporan()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data['kode']; ?></td>
                                                <td><?= $data['refisi_ke']; ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal_mulai']); ?></td>
                                                <td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal_selesai']); ?></td>
												<td><?= $data['pemenuhan_po']; ?></td>
												<td><?= $data['peruntukan']; ?></td>
												<td><?= $data['line_sawmill']; ?></td>
												<td><?= $data['status_spk']?'Open':'Close'; ?></td>
												<td><?= $data['kayu_nama']; ?></td>
												<td><?= $data['produk_sawmill']; ?></td>
												<td><?= $data['produk_t'] . 'x' . $data['produk_l']; ?></td>
                                                <td><?= $data['produk_p']; ?></td>
                                                <td><?= $data['kategori_ukuran']; ?></td>
                                                <td><?= $data['approval_status']; ?></td>
											</tr>
										<?php }
										}else{
											echo"<tr><td colspan='14' style='text-align: center;'>".Yii::t('app', 'Data tidak ditemukan')."<td></tr>";
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