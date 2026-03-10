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
											<th><?= Yii::t('app', 'Nomor Kontrak') ?></th>
											<th><?= Yii::t('app', 'Tanggal') ?></th>
											<th><?= Yii::t('app', 'Nama Perwakilan') ?></th>
											<th><?= Yii::t('app', 'Nama Perusahaan') ?></th>
											<th><?= Yii::t('app', 'Jenis') ?></th>
											<th><?= Yii::t('app', 'Kualitas') ?></th>
											<th><?= Yii::t('app', 'Harga FOB') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$contents = $model->searchLaporan()->all(); 
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?> 
											<tr>
												<td class="text-align-center"><?= $i+1; ?></td>
												<td ><?= $data->nomor ?></td>
												<td class="text-align-center"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal) ?></td>
												<td ><?= $data->pihak1_nama ?></td>
												<td ><?= $data->pihak1_perusahaan ?></td>
												<td ><?= $data->jenis_log ?></td>
												<td ><?= $data->kualitas ?></td>
												<td class="text-align-right"><?= round($data->hargafob); ?></td>
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