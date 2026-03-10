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
                                            <th><?= Yii::t('app', 'Kode Asset') ?></th>
											<th><?= Yii::t('app', 'Nama Asset') ?></th>
											<th><?= Yii::t('app', 'Periode') ?></th>
											<th><?= Yii::t('app', 'Target Plan') ?></th>
											<th><?= Yii::t('app', 'Total Biaya') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporanRekapAsset()->all();
                                        $total = 0;
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ 
                                                $total += $data->total;
                                            ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
                                                <td><?= $data->kode ?></td>
												<td><?= $data->inventaris_nama ?></td>
												<td style="text-align: center;">
                                                    <?php 
                                                    if($data->bulan == 1){
                                                        $bulan = 'Jan';
                                                    } else if($data->bulan == 2){
                                                        $bulan = 'Feb';
                                                    } else if($data->bulan == 3){
                                                        $bulan = 'Mar';
                                                    } else if($data->bulan == 4){
                                                        $bulan = 'Apr';
                                                    } else if($data->bulan == 5){
                                                        $bulan = 'Mei';
                                                    } else if($data->bulan == 6){
                                                        $bulan = 'Jun';
                                                    } else if($data->bulan == 7){
                                                        $bulan = 'Jul';
                                                    } else if($data->bulan == 8){
                                                        $bulan = 'Agu';
                                                    } else if($data->bulan == 9){
                                                        $bulan = 'Sep';
                                                    } else if($data->bulan == 10){
                                                        $bulan = 'Okt';
                                                    } else if($data->bulan == 11){
                                                        $bulan = 'Nov';
                                                    } else if($data->bulan == 12){
                                                        $bulan = 'Des';
                                                    } 
                                                    echo $bulan .'-'. $data->tahun;
                                                    ?>
                                                </td>
												<td style="text-align: center;"><?= $data->target_plan ?></td>
												<td class="text-align-right"><?= app\components\DeltaFormatter::formatNumberForUser($data->total); ?></td>
											</tr>
										<?php }
										}else{
											echo "<tr><td colspan='5' class='text-align-center'>".Yii::t('app', 'Data tidak ditemukan')."</td></tr>";
										}
										?>
									</tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" style="text-align: right;"><b>Total &nbsp;</b></td>
                                            <td style="text-align: right;"><b><?= app\components\DeltaFormatter::formatNumberForUser($total); ?></b></td>
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