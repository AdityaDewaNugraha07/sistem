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
<style>
	@media print {
		tfoot {
			display: table-header-group;
		}
	}
</style>
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
										<th><?= Yii::t('app', 'Tanggal Rencana Pembayaran') ?></th>
										<!-- <th><?= Yii::t('app', 'Kategori') ?></th> -->
										<th><?= Yii::t('app', 'Keterangan') ?></th>
										<th><?= Yii::t('app', 'Total') ?></th>
										<th><?= Yii::t('app', 'Status') ?></th>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											$jml = 0;
											foreach($contents as $i => $data){ 
												$jml += $data->total_jml;
											?>
											<tr>
												<td style="text-align: center;  font-size: 1.2rem;"><?= $i+1; ?></td>
												<td style=" font-size: 1.2rem;"><?= $data->kode ?></td>
												<td style="text-align: center;  font-size: 1.2rem;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal) ?></td>
												<td style=" font-size: 1.2rem;"><?= $data->keterangan ?></td>
												<td style="text-align: right; font-size: 1.2rem;"><?= app\components\DeltaFormatter::formatNumberForPrint($data->total_jml) ?></td>
												<td style="text-align: center; font-size: 1.2rem;"><?= $data->status_approve ?></td>
											</tr>
										<?php }
										}else{ ?>
											<tr>
												<td colspan="6" class="text-align-center">Data Tidak Ditemukan</td>
											</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="4" style="text-align: right; font-size: 1.2rem;"><b>Total &nbsp;</b></td>
											<td style="text-align: right; font-size: 1.2rem;"><b><?= !empty($contents)?app\components\DeltaFormatter::formatNumberForPrint($jml):0 ?></b></td>
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