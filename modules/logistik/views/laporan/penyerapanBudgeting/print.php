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
											<th><?= Yii::t('app', 'Departement') ?></th>							
											<th><?= Yii::t('app', 'Peruntukan') ?></th>
											<th><?= Yii::t('app', 'Target') ?></th>
											<th><?= Yii::t('app', 'Periode') ?></th>
											<th><?= Yii::t('app', 'Penerimaan') ?></th>
											<th><?= Yii::t('app', 'Pemakaian') ?></th>
											<th><?= Yii::t('app', 'Prosentase<br>Penyerapan') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 											
											foreach($contents as $i => $data){ 
										?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->departement_nama ?></td>												
												<td><center><?= $data->target_peruntukan ?></center></td>
												<td><center><?= $data->target_plan ?></center></td>
												<td><center><?= $data->periode_bulan ?></center></td>
												<td style="text-align: right"><right><?=  app\components\DeltaFormatter::formatNumberForUserFloat($data->total_penerimaan); ?></right></td>
												<td style="text-align: right"><right><?=  app\components\DeltaFormatter::formatNumberForUserFloat($data->total_pemakaian); ?></right></td>
												<td><center><?= $data->penyerapan ?> %</center></td>
											</tr>
										<?php }
										}else{
											"<tr colspan='8'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
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