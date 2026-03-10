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
											<th colspan="3"><?= Yii::t('app', 'Nomor'); ?></th>
											<th rowspan="2" style="width: 100px;"><?= Yii::t('app', 'Kayu'); ?></th>
											<th rowspan="2" style="width: 70px;"><?= Yii::t('app', 'Panjang'); ?></th>
											<th colspan="3"><?= Yii::t('app', 'Diameter'); ?></th>
											<th colspan="3"><?= Yii::t('app', 'Unsur Cacat'); ?></th>
											<th colspan="2"><?= Yii::t('app', 'Volume'); ?></th>
											<th rowspan="2" style="width: 50px;"><?= Yii::t('app', 'Fresh'); ?></th>
										</tr>
										<tr>
											<th style="width: 50px;"><?= Yii::t('app', 'Grd'); ?></th>
											<th style="width: 60px;"><?= Yii::t('app', 'Prod'); ?></th>
											<th style="width: 75px;"><?= Yii::t('app', 'Pcs'); ?></th>
											<th style="width: 50px;"><?= Yii::t('app', 'P'); ?></th>
											<th style="width: 50px;"><?= Yii::t('app', 'U'); ?></th>
											<th style="width: 55px;"><?= Yii::t('app', 'Rata'); ?><sup>2</sup></th>
											<th style="width: 70px;"><?= Yii::t('app', 'Pjg'); ?></th>
											<th style="width: 70px;"><?= Yii::t('app', 'GB'); ?></th>
											<th style="width: 70px;"><?= Yii::t('app', 'GR'); ?></th>
											<th style="width: 70px;"><?= Yii::t('app', 'Range'); ?></th>
											<th style="width: 50px;"><?= Yii::t('app', 'Value'); ?> m<sup>3</sup></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$contents = $model->searchLaporan()->all(); 
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?> 
											<tr>
												<td class="text-align-center"><?= $data->nomor_grd ?></td>
												<td class="text-align-center"><?= $data->nomor_produksi ?></td>
												<td class="text-align-center"><?= $data->nomor_batang ?></td>
												<td><?= $data->kayu_nama ?></td>
												<td class="text-align-right"><?= $data->panjang ?></td>
												<td class="text-align-right"><?= $data->diameter_ujung ?></td>
												<td class="text-align-right"><?= $data->diameter_pangkal ?></td>
												<td class="text-align-right"><?= $data->diameter_rata ?></td>
												<td class="text-align-right"><?= $data->cacat_panjang ?></td>
												<td class="text-align-right"><?= $data->cacat_gb ?></td>
												<td class="text-align-right"><?= $data->cacat_gr ?></td>
												<td class="text-align-center"><?= $data->volume_range ?></td>
												<td class="text-align-right"><?= $data->volume_value ?></td>
												<td class="text-align-center"><?= ($data->is_freshcut)?"Ya":"Tidak"; ?></td>
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