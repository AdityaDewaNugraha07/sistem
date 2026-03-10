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
											<th rowspan="2" style="width: 100px;"><?= Yii::t('app', 'Nama Tongkang'); ?></th>
											<th rowspan="2" style="width: 100px;"><?= Yii::t('app', 'No. Kontrak'); ?></th>
											<th rowspan="2" style="width: 100px;"><?= Yii::t('app', 'Nama Perusahaan'); ?></th>
											<th rowspan="2" style="width: 100px;"><?= Yii::t('app', 'Kayu'); ?></th>
											<th rowspan="2" style="width: 70px;"><?= Yii::t('app', 'Pcs'); ?></th>
											<th colspan="2"><?= Yii::t('app', 'Volume'); ?></th>
										</tr>
										<tr>
											<th style="width: 70px;"><?= Yii::t('app', 'Range'); ?></th>
											<th style="width: 50px;"><?= Yii::t('app', 'Value'); ?> m<sup>3</sup></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$contents = $model->searchLaporanRekap()->all(); 
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?> 
											<tr>
												<td class="text-align-center td-kecil"><?= $data->tongkang ?></td>
												<td class="text-align-center td-kecil"><?= $data->nomor ?></td>
												<td class="text-align-center td-kecil"><?= $data->pihak1_perusahaan ?></td>
												<td class="text-align-center td-kecil"><?= $data->kayu_nama ?></td>
												<td class="text-align-center td-kecil"><?= $data->pcs ?></td>
												<td class="text-align-center td-kecil"><?= $data->volume_range ?></td>
												<td class="text-align-right td-kecil"><?= \app\components\DeltaFormatter::formatNumberForAllUser($data->volume_value, 2) ?></td>
											</tr>
										<?php }
										}else{ ?>
											<tr>
												<td colspan='10' class="text-align-center td-kecil">Data tidak ditemukan</td>
											</tr>
										<?php }
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