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
											<th><?= Yii::t('app', 'Kode') ?></th>
											<th><?= Yii::t('app', 'Tanggal<br>Terima') ?></th>
											<th><?= Yii::t('app', 'Nama Item') ?></th>
											<th><?= Yii::t('app', 'Plan') ?></th>
											<th><?= Yii::t('app', 'Peruntukan') ?></th>
											<th><?= Yii::t('app', 'Departement') ?></th>
											<th><?= Yii::t('app', 'IN') ?></th>
											<th><?= Yii::t('app', 'OUT') ?></th>
											<th><?= Yii::t('app', 'STOCK') ?></th>
											<th><?= Yii::t('app', 'Umur<br>Stock') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ 
												
												$tanggalInput = new DateTime($data->tanggal);
												$tanggalSekarang = new DateTime();
												// Set both dates to midnight
												$tanggalInput->setTime(0, 0);
												$tanggalSekarang->setTime(0, 0);
												$interval = $tanggalInput->diff($tanggalSekarang);
												$jumlahHari = $interval->days;
												?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->kode ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal) ?></td>
												<td><?= $data->bhp_nm ?></td>
												<td><center><?= $data->target_plan ?></center></td>
												<td><center><?= $data->target_peruntukan ?></center></td>
												<td><center><?= $data->departement ?></center></td>
												<td style="text-align: right"><right><?=  app\components\DeltaFormatter::formatNumberForUserFloat($data->qty_in); ?></right></td>
												<td style="text-align: right"><right><?=  app\components\DeltaFormatter::formatNumberForUserFloat($data->qty_out); ?></right></td>
												<td>
													<div style="text-align: right">
														<?php 
														$sisa = $data->qty_in - $data->qty_out;
														echo app\components\DeltaFormatter::formatNumberForUserFloat($sisa);
														?>
													</div>
												</td>
												<td><?= $jumlahHari ." hari"; ?></td>
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