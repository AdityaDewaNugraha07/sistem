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
											<th style="text-align: center; width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
											<th style="text-align: center; width: 75px;"><?= Yii::t('app', 'Kode'); ?></th>
											<th style="text-align: center; width: 75px;"><?= Yii::t('app', 'Tanggal'); ?></th>
											<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Penerima'); ?></th>
											<th style="text-align: center; "><?= Yii::t('app', 'Deskripsi'); ?></th>
											<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Nominal'); ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php 
										$total = 0;
										foreach($models as $i => $model){
											?>
											<tr style="">
												<td class="td-kecil text-align-center"><?= $i+1; ?></td>
												<td class="td-kecil" style="font-weight: bold; text-align: center;"><?= $model->kode; ?></td>
												<td class="td-kecil text-align-center"><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kasbon) ?></td>
												<td class="td-kecil"><?= $model->penerima ?></td>
												<td class="td-kecil" style="font-size: 1.2rem;"><?= $model->deskripsi ?></td>
												<td class="td-kecil text-align-right"><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal):$model->nominal; ?></td>
											</tr>
										<?php $total += $model->nominal; } ?>
											<tr style="background-color: #e2e3e5;">
												<td class="td-kecil" colspan="5" style="font-size: 1.2rem; font-weight: bold; text-align: right;">TOTAL</td>
												<td class="td-kecil text-align-right" style="font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( $total ):$total; ?></td>
											</tr>
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