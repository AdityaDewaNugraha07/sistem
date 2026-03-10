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
											<th><?= Yii::t('app', 'No.'); ?></th>
											<th><?= Yii::t('app', 'Nama') ?></th>
											<th><?= Yii::t('app', 'Perusahaan') ?></th>
											<th><?= Yii::t('app', 'Alamat') ?></th>
											<th><?= Yii::t('app', 'Type') ?></th>
											<th><?= Yii::t('app', 'Status') ?></th>
											<th><?= Yii::t('app', 'Keterangan') ?></th>
											<th><?= Yii::t('app', 'Created_at') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){                                                                                                
                                        ?>
											<tr>
												<td style="text-align: center;" class="td-kecil"><?= $i+1; ?></td>
												<td class="td-kecil"><?= $data->suplier_nm ?></td>
												<td class="td-kecil"><?= $data->suplier_nm_company ?></td>
												<td class="td-kecil"><?= $data->suplier_almt ?></td>
												<td class="td-kecil"><?= (!empty($data->type)) ? 'Suplier<br>'.$data->type : ''; ?></td>
												<td class="td-kecil">
												<?php 
													if($data->active == true){
														$statuse = "Active";
													}else{
														$statuse = "<span style='color:#B40404'>Non-Active</span>"; 
													}
												?>	
												<?= $statuse; ?>
												</td>
												<td class="td-kecil"><?= $data->suplier_ket ?></td>
												<td class="td-kecil"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->created_at) ?></td>												
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