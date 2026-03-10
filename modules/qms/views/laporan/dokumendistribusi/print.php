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
											<th><?= Yii::t('app', 'No. Dokumen') ?></th>
											<th><?= Yii::t('app', 'Nama Dokumen'); ?></th>
											<th><?= Yii::t('app', 'Revisi'); ?></th>
											<th><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
											<th><?= Yii::t('app', 'Pengirim'); ?></th>
											<th><?= Yii::t('app', 'Penerima Dokumen'); ?></th>
											<th><?= Yii::t('app', 'Status Pengiriman'); ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td class="text-align-center td-kecil"><?= $i+1; ?></td>
												<td class="td-kecil"><?= $data->nomor_dokumen ?></td>
												<td class="td-kecil"><?= $data->nama_dokumen ?></td>
												<td class="text-align-center td-kecil"><?= $data->revisi_ke ?></td>
												<td class="text-align-center td-kecil">
													<?php
													$tanggal = date('Y-m-d', strtotime($data->tanggal_dikirim));
													echo \app\components\DeltaFormatter::formatDateTimeForUser($tanggal);
													?>
												</td>
												<td class="text-align-center td-kecil"><?= $data->pengirim ?></td>
												<td class="text-align-center td-kecil"><?= $data->pic ?></td>
												<td class="text-align-center td-kecil">
													<?php
													$status = '';
													if($data->status_penerimaan == true){
														if($data->tanggal_penerimaan){
															$tgl = '<br><span class="td-kecil3">at :' . \app\components\DeltaFormatter::formatDateTimeForUser($data->tanggal_penerimaan) . '</span>';
														} else {
															$tgl = '';
														}
														$status = '<span style="color:green;">Sudah diterima</span>' . $tgl;
													} else if ($data->status_penerimaan == false){
														$status = '<span style="color:red;">Belum diterima</span>';
													}
													echo $status;  ?>
												</td>
											</tr>
										<?php }
										}else{ ?>
											<tr>
												<td colspan="8" class="text-align-center">Data tidak ditemukan</td>
											</tr>
										<?php }?>
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