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
                                            <th><?= Yii::t('app', 'Nomor Dokumen') ?></th>
											<th><?= Yii::t('app', 'Jenis Dokumen') ?></th>
											<th><?= Yii::t('app', 'Kategori Dokumen'); ?></th>
											<th><?= Yii::t('app', 'Nama Dokumen'); ?></th>
											<th><?= Yii::t('app', 'Status'); ?></th>
                                        </tr>
                                    </thead>
									<tbody>
									<?php
                                    if(count($model) > 0){
									    foreach ($model as $key) {
                                            $nomor_dokumen = $key['nomor_dokumen'];
                                            $jenis_dokumen = $key['jenis_dokumen'];
                                            $kategori_dokumen = $key['kategori_dokumen'];
                                            $nama_dokumen = $key['nama_dokumen'];
                                            $active = $key['active'];
                                            $active == 1 ? $status='Active' : $status='Non-Active'; ?>
											<tr>
												<td><?= $nomor_dokumen; ?></td>
												<td class="text-align-center"><?= $jenis_dokumen ?></td>
												<td class="text-align-center"><?= $kategori_dokumen ?></td>
												<td><?= $nama_dokumen ?></td>
												<td class="text-align-center"><?= $status ?></td>
											</tr>
										<?php }
										}else{ ?>
											<tr>
												<td colspan="5" class="text-align-center">Data tidak ditemukan</td>
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