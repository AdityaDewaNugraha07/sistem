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
											<th><?= Yii::t('app', 'Kode'); ?></th>
											<th><?= Yii::t('app', 'Tanggal<br>Penerimaan'); ?></th>
											<th><?= Yii::t('app', 'Customer'); ?></th>
											<th><?= Yii::t('app', 'Bank') ?></th>
											<th><?= Yii::t('app', 'No.rek') ?></th>
											<th><?= Yii::t('app', 'Reff Number') ?></th>
											<th><?= Yii::t('app', 'Tanggal Jatuh<br>Tempo') ?></th>
											<th><?= Yii::t('app', 'Nominal') ?></th>
											<th><?= Yii::t('app', 'Keterangan') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporan()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td class="text-align-center"><?= $data['kode'] ?></td>
												<td class="text-align-center"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']); ?></td>
												<td><?= $data['nama_customer'] ?></td>
												<td><?= $data['cust_bank'] ?></td>
												<td><?= $data['cust_acct'] ?></td>
												<td><?= $data['reff_number'] ?></td>
												<td class="text-align-center"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal_jatuhtempo']); ?></td>
												<td><?= app\components\DeltaFormatter::formatNumberForUserFloat($data['nominal']) ?></td>
												<td><?= $data['keterangan'] ?></td>
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