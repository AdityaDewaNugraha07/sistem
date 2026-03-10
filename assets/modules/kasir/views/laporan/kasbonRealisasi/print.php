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
											<th>No</th>
											<th><?= Yii::t('app', 'Kode Bon'); ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Kasbon'); ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Realisasi'); ?></th>
											<th><?= Yii::t('app', 'Penerima'); ?></th>
											<th><?= Yii::t('app', 'Deskripsi') ?></th>
											<th><?= Yii::t('app', 'Nominal') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporan("realisasi")->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td class="text-align-center"><?= $data['kode'] ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']); ?></td>
												<td class="text-align-center"><?= !empty($data['tanggal_real'])?app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal_real']):"-" ?></td>
												<td class="text-align-left"><?= $data['penerima'] ?></td>
												<td class="text-align-left"><?= $data['deskripsi'] ?></td>
												<td class="text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data['nominal']) ?></td>
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