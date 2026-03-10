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
											<th rowspan="2">No.</th>
											<th rowspan="2" style="line-height: 1; width: 120px;"><?= Yii::t('app', 'No. Seri') ?></th>
											<th rowspan="2" style="line-height: 1; width: 80px;"><?= Yii::t('app', 'Tanggal') ?></th>
											<th rowspan="2" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Jenis HH') ?></th>
											<th colspan="2" style="line-height: 1"><?= Yii::t('app', 'Satuan') ?></th>
											<th colspan="2" style="line-height: 1"><?= Yii::t('app', 'Tujuan Pengangkutan') ?></th>
											<th rowspan="2" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Provinsi') ?></th>
											<th rowspan="2" style="line-height: 1; width: 80px;"><?= Yii::t('app', 'Identitas<br>Pengangkut') ?></th>
										</tr>
										<tr>
											<th style="width: 50px;"><?= Yii::t('app', 'Kpg'); ?></th>
											<th style="width: 80px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
											<th style="width: 100px;"><?= Yii::t('app', 'Customer'); ?></th>
											<th><?= Yii::t('app', 'Alamat Bongkar'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = $model->searchLaporanPenerbitanKo()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data['no_seri']; ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal_nota']); ?></td>
												<td><?= $data['jenis_hh']; ?></td>
												<td><?= $data['kpg']; ?></td>
												<td><?= $data['m3']; ?></td>
												<td><?= $data['cust_an_nama']; ?></td>
												<td><?= $data['alamat_bongkar']; ?></td>
												<td><?= $data['provinsi_bongkar']; ?></td>
												<td><?= $data['kendaraan_nopol']; ?></td>
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