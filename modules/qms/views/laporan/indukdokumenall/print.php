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
											<th><?= Yii::t('app', 'Tanggal Terbit'); ?></th>
											<th><?= Yii::t('app', 'Revisi'); ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporanIndukAll()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td class="text-align-center td-kecil"><?= $i+1; ?></td>
												<td class="td-kecil"><?= $data->nomor_dokumen ?></td>
												<td class="td-kecil"><?= $data->nama_dokumen ?></td>
												<td class="text-align-center td-kecil"><?= app\components\DeltaFormatter::formatDateTimeForUser($data->tanggal_berlaku) ?></td>
												<td class="text-align-center td-kecil"><?= $data->revisi_ke ?></td>
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