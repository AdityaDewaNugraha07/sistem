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
                                            <th><?= Yii::t('app', 'No.'); ?></th>
                                            <th><?= Yii::t('app', 'Jenis Produk'); ?></th>
											<th><?= Yii::t('app', 'Kode') ?></th>
											<th><?= Yii::t('app', 'Nama Produk') ?></th>
											<th><?= Yii::t('app', 'Dimensi') ?></th>
											<th><?= Yii::t('app', 'Total Palet') ?></th>
											<th><?= Yii::t('app', 'Total Qty') ?></th>
											<th><?= Yii::t('app', 'Total M<sup>3</sup>') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										if(!empty($model)){ 
											foreach($model as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data['produk_group'] ?></td>
												<td><?= $data['produk_kode'] ?></td>
												<td><?= \app\models\MBrgProduk::findOne($data['produk_id'])->NamaProduk ?></td>
												<td><?= $data['produk_dimensi'] ?></td>
												<td style="text-align: center;"><?= $data['palet'] ?></td>
												<td style="text-align: right;"><?= $data['qty_kecil']." (".$data['in_qty_kecil_satuan'].")" ?></td>
												<td style="text-align: right;">
													<?= (strlen(substr(strrchr($data['kubikasi'], "."), 1)) > 4)? $data['kubikasi']*10000/10000:$data['kubikasi'];  ?>
												</td>
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