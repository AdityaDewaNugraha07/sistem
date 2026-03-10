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
                                            <th style="font-size:1.2rem;"><?= Yii::t('app', 'No.'); ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Kode') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Tanggal') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Kelompok Item') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Nama Item') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Qty') ?></th>
											<?php 
												$pegawai_id = Yii::$app->user->identity->pegawai_id;
												if(in_array($pegawai_id, app\components\Params::DEFAULT_PEGAWAI_ID_BUDGETING))
												{
												?>
											<th style="font-size:1.2rem;"><?= Yii::t('app', '@Harga') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Total') ?></th>
											<?php } ?>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Target Plan') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Target Peruntukan') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Departement') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Dept. Peruntukan') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Kode Asset') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Nama Asset') ?></th>
											<th style="font-size:1.2rem;"><?= Yii::t('app', 'Keterangan') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;font-size:1.2rem;"><?= $i+1; ?></td>
												<td style="font-size:1.2rem;"><?= $data->kode ?></td>
												<td style="font-size:1.2rem;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal) ?></td>
												<td style="font-size:1.2rem;"><?= $data->bhp_group ?></td>
												<td style="font-size:1.2rem;"><?= $data->bhp_nm ?></td>
												<td style="font-size:1.2rem;"><center><?= $data->qty ?></center></td>
												<?php 
												$pegawai_id = Yii::$app->user->identity->pegawai_id;
												if(in_array($pegawai_id, app\components\Params::DEFAULT_PEGAWAI_ID_BUDGETING))
												{
												?>
												<td><div style="text-align: right; font-size:1.2rem;"><?= app\components\DeltaFormatter::formatNumberForUser($data->harga_peritem) ?></div></td>
												<td><div style="text-align: right; font-size:1.2rem;"><?php echo app\components\DeltaFormatter::formatNumberForUser($data->total);?></div></td>
												<?php } ?>
												<td style="font-size:1.2rem;"><?= $data->target_plan ?></td>
												<td style="font-size:1.2rem;"><?= $data->target_peruntukan ?></td>
												<td style="font-size:1.2rem;"><center><?= $data->departement ?></center></td>
												<td style="font-size:1.2rem;"><?= $data->dept_peruntukan ?></td>
												<td style="font-size:1.1rem;">
													<?php
													 if($data->asset_peruntukan != null){
														echo $data->kode_asset; //. '<br>' . $data->asset_peruntukan;
													 } else {
														echo '<center>-</center>';
													 }
													?>
												</td>
												<td style="font-size:1.1rem;">
													<?php
													 if($data->asset_peruntukan != null){
														echo $data->asset_peruntukan;
													 } else {
														echo '<center>-</center>';
													 }
													?>
												</td>
												<td style="font-size:1.1rem;"><?= $data->keterangan ?></td>
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