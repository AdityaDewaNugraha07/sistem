<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - Per_'.(!empty($model->per_tanggal)?$model->per_tanggal:date('d/m/Y')).'.xls"');
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
                        <i><h5 class="pull-right font-red-flamingo"></h5></i>
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
											<th><?= Yii::t('app', 'Kode<br>Permintaan'); ?></th>
											<th><?= Yii::t('app', 'Tanggal'); ?></th>
											<th><?= Yii::t('app', 'Jenis Produk') ?></th>
											<th><?= Yii::t('app', 'Nama Produk') ?></th>
											<th><?= Yii::t('app', 'Dimensi') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Total<br>Palet') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Total Qty<br>(Pcs)') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Total<br>Kubikasi M<sup>3</sup>') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporanReturblmdikirimgudang()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td class="td-kecil text-align-center"><?= $i+1; ?></td>
												<td class="td-kecil"><?= $data['kode']; ?></td>
												<td class="td-kecil text-align-center"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']); ?></td>
												<td class="td-kecil"><?= $data['produk_group']; ?></td>
												<td class="td-kecil"><?= $data['produk_nama'] ?></td>
												<td class="td-kecil text-align-center"><?= $data['produk_dimensi'] ?></td>
												<td class="td-kecil text-align-center"><?= $data['palet'] ?></td>
												<td class="td-kecil text-align-right"><?= $data['qty_kecil'] ?></td>
												<td class="td-kecil text-align-right"><?php echo number_format($data['kubikasi'],4); ?></td>
											</tr>
										<?php }
										}else{?>
											<tr>
												<td colspan="9" class="text-align-center"><?= Yii::t('app', 'Data tidak ditemukan'); ?></td>
											</tr>
										<?php } ?>
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