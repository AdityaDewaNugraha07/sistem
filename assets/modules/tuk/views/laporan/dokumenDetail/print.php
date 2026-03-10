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
											<th>No.</th>
											<th style="line-height: 1"><?= Yii::t('app', 'Tanggal') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Dokumen') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Customer') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Produk') ?></th>
											<th style="line-height: 1" colspan="2"><?= Yii::t('app', 'T') ?></th>
											<th style="line-height: 1" colspan="2"><?= Yii::t('app', 'L') ?></th>
											<th style="line-height: 1" colspan="2"><?= Yii::t('app', 'P') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Total<br>Palet') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Total<br>Pcs') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Total<br>M<sup>3</sup>') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporan()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;" class="td-kecil"><?= $i+1; ?></td>
												<td class="td-kecil"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']); ?></td>
												<td class="td-kecil"><?= $data['nomor_dokumen']; ?></td>
												<td class="td-kecil"><?= $data['jenis_produk']; ?></td>
												<td class="td-kecil"><?= $data['cust_an_nama']; ?></td>
												<td class="td-kecil"><?= $data['produk_nama']; ?></td>
												<td class="td-kecil text-align-right">
													<?php
													if($data['produk_t']==0 || $data['produk_l']==0 || $data['produk_p']==0){
														echo $data['t'];
													}else{
														echo $data['produk_t'];
													}
													?>
												</td>
												<td class="td-kecil"><?= $data['produk_t_satuan']; ?></td>
												<td class="td-kecil text-align-right">
													<?php
													if($data['produk_t']==0 || $data['produk_l']==0 || $data['produk_p']==0){
														echo $data['l'];
													}else{
														echo $data['produk_l'];
													}
													?>
												</td>
												<td class="td-kecil"><?= $data['produk_l_satuan']; ?></td>
												<td class="td-kecil text-align-right">
													<?php
													if($data['produk_t']==0 || $data['produk_l']==0 || $data['produk_p']==0){
														echo $data['p'];
													}else{
														echo $data['produk_p'];
													}
													?>
												</td>
												<td class="td-kecil"><?= $data['produk_p_satuan']; ?></td>
												<td class="td-kecil text-align-center">
													<?php
													if($data['produk_t']==0 || $data['produk_l']==0 || $data['produk_p']==0){
														echo "-";
													}else{
														echo $data['qty_besar'];
													}
													?>
												</td>
												<td class="td-kecil text-align-right">
													<?php
													if($data['produk_t']==0 || $data['produk_l']==0 || $data['produk_p']==0){
														echo $data['random_qty_kecil'];
													}else{
														echo $data['qty_kecil'];
													}
													?>
												</td>
												<td class="td-kecil text-align-right">
													<?php
													if($data['produk_t']==0 || $data['produk_l']==0 || $data['produk_p']==0){
														echo $data['random_kubikasi'];
													}else{
														echo $data['kubikasi'];
													}
													?>
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