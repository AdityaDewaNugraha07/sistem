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
											<th><?= Yii::t('app', 'Supplier'); ?></th>
											<th style="width:180px;"><?= Yii::t('app', 'Total Tagihan') ?></th>
											<th style="width:180px;"><?= Yii::t('app', 'Lunas') ?></th>
											<th style="width:180px;"><?= Yii::t('app', 'Hutang') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$totaltagihan = 0; $lunas = 0; $hutang = 0;
										$sql = $model->searchLaporanTagihanSuplier()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data['suplier_nm']; ?></td>
												<td class="text-align-right"><?= \app\components\DeltaFormatter::formatNumberForUser($data['totaltagihan']) ?></td>
												<td class="text-align-right"><?= \app\components\DeltaFormatter::formatNumberForUser($data['paid']) ?></td>
												<td class="text-align-right"><?= \app\components\DeltaFormatter::formatNumberForUser($data['hutang']) ?></td>
											</tr>
										<?php 
											$totaltagihan += $data['totaltagihan'];
											$lunas += $data['paid'];
											$hutang += $data['hutang'];
											}
										}else{
											"<tr colspan='5'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2" class="text-align-right"><b>Total &nbsp; </b></td>
											<td class="text-align-right"><b><?= \app\components\DeltaFormatter::formatNumberForUser($totaltagihan) ?></b></td>
											<td class="text-align-right"><b><?= \app\components\DeltaFormatter::formatNumberForUser($lunas) ?></b></td>
											<td class="text-align-right"><b><?= \app\components\DeltaFormatter::formatNumberForUser($hutang) ?></b></td>
										</tr>
									</tfoot>
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