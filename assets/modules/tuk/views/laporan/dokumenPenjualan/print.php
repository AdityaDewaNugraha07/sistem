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
											<th><?= Yii::t('app', 'Jenis<br>Dokumen') ?></th>
											<th><?= Yii::t('app', 'Tanggal') ?></th>
											<th><?= Yii::t('app', 'Nomor<br>Dokumen') ?></th>
											<th><?= Yii::t('app', 'Kode<br>Nota') ?></th>
											<th><?= Yii::t('app', 'Customer') ?></th>
											<th><?= Yii::t('app', 'Nopol') ?></th>
											<th><?= Yii::t('app', 'Nama<br>Supir') ?></th>
											<th><?= Yii::t('app', 'Alamat<br>Bongkar') ?></th>
											<th><?= Yii::t('app', 'Petugas<br>TUK') ?></th>
											<th><?= Yii::t('app', 'Noreg<br>Petugas') ?></th>
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
												<td><?= $data['jenis_dokumen']; ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']); ?></td>
												<td><?= $data['nomor_dokumen']; ?></td>
												<td><?= $data['kode']; ?></td>
												<td><?= $data['cust_an_nama']; ?></td>
												<td><?= $data['kendaraan_nopol']; ?></td>
												<td><?= $data['kendaraan_supir']; ?></td>
												<td><?= $data['alamat_bongkar']; ?></td>
												<td><?= $data['pegawai_nama']; ?></td>
												<td><?= $data['noreg']; ?></td>
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