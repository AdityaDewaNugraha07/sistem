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
                    <div class="col-md-6">
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
										<tr>
											<th><?= Yii::t('app', 'No.'); ?></th>
											<th><?= Yii::t('app', 'Nama BHP') ?></th>
											<th><?= Yii::t('app', 'Total SPO') ?></th>
											<th><?= Yii::t('app', 'Total Item') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$contents = $model->searchTotalBeli()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){
											?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->bhp_nm ?></td>
												<td class="text-align-center"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($data->count); ?></td>
												<td class="text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data->qty) ?></td>
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
                    </div>
                    <div class="col-md-6">
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
										<tr>
											<th><?= Yii::t('app', 'No.'); ?></th>
											<th><?= Yii::t('app', 'Nama BHP') ?></th>
											<th><?= Yii::t('app', 'Total SPL') ?></th>
											<th><?= Yii::t('app', 'Total Item') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$modSplDetail = new app\models\TSplDetail();
										$modSplDetail->bhp_nm = $model->bhp_nm;
										$contents = $modSplDetail->searchTotalBeli()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){
											?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->bhp_nm ?></td>
												<td class="text-align-center"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($data->count); ?></td>
												<td class="text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data->qty) ?></td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>