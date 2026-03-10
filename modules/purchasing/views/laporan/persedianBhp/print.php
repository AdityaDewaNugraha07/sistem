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
                                            <th style="width: 50px;"><?= Yii::t('app', 'No.'); ?></th>
                                            <th><?= Yii::t('app', 'Nama Barang') ?></th>
                                            <th style="width: 100px;"><?= Yii::t('app', 'Qty In') ?></th>
                                            <th style="width: 100px;"><?= Yii::t('app', 'Qty Out') ?></th>
                                            <th style="width: 100px;"><?= Yii::t('app', 'Current Stock') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php 
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->bhp_nm ?></td>
												<td style="text-align: center;"><?= $data->qty_in ?></td>
												<td style="text-align: center;"><?= $data->qty_out ?></td>
												<td style="text-align: center;"><?php echo $data->current ?></td>
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