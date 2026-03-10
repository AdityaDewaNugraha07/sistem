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
										<th><?= Yii::t('app', 'Nama Item') ?></th>
										<th><?= Yii::t('app', 'Qty') ?></th>
										<?php 
										$pegawai_id = Yii::$app->user->identity->pegawai_id;
										if(in_array($pegawai_id, app\components\Params::DEFAULT_PEGAWAI_ID_BUDGETING))
										{
										?>
										<th><?= Yii::t('app', 'Harga Peritem') ?></th>
										<th><?= Yii::t('app', 'Subtotal') ?></th>
										<?php } ?>
										<th><?= Yii::t('app', 'Target Plan') ?></th>
										<th><?= Yii::t('app', 'Target Peruntukan') ?></th>
										<th><?= Yii::t('app', 'Departement') ?></th>
										<th><?= Yii::t('app', 'Dept. Peruntukan') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->kode ?></td>
												<td><?= $data->bhp_nm ?></td>
												<td><?= $data->jumlah ?></td>
												<?php 
												$pegawai_id = Yii::$app->user->identity->pegawai_id;
												if(in_array($pegawai_id, app\components\Params::DEFAULT_PEGAWAI_ID_BUDGETING))
												{
												?>
												<td><div style="text-align: right"><?= app\components\DeltaFormatter::formatNumberForUser($data->harga_peritem) ?></div></td>
												<td>
													<div style="text-align: right">
														<?php 
														$subtotal = $data->jumlah * $data->harga_peritem;
														echo app\components\DeltaFormatter::formatNumberForUser($subtotal);
														?>
													</div>
												</td>
												<?php } ?>
												<td><center><?= $data->target_plan ?></center></td>
												<td><center><?= $data->target_peruntukan ?></center></td>
												<td><center><?= $data->departement ?></center></td>
												<td>
													<?php 
													if($data->dept_peruntukan != null){
														echo '<center>'.$data->dept_peruntukan.'</center>';
													} else {
														echo '<center>-</center>';
													}
													?>
												</td>
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