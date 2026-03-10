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
                                        <tr style="background-color: #F1F4F7;">
											<th style="font-size: 1.2rem; width: 35px; position:absolute; left:0px; background-color: #F1F4F7;"><?= Yii::t('app', 'No.'); ?></th>
											<th style="font-size: 1.2rem; width: 300px; position:absolute; left:35px; background-color: #F1F4F7;"><?= Yii::t('app', 'Nama Produk') ?></th>
											<th style="font-size: 1.2rem; width: 50px; position:absolute; left:335px; line-height: 1; background-color: #F1F4F7;"><?= Yii::t('app', 'Lokasi<br>Gudang') ?></th>
											<th style="font-size: 1.2rem; width: 60px; position:absolute; left:385px; line-height: 1; background-color: #F1F4F7;"><?= Yii::t('app', 'Total<br>Palet') ?></th>
											<th style="font-size: 1.2rem; width: 85px; position:absolute; left:445px; line-height: 1; background-color: #F1F4F7;"><?= Yii::t('app', 'Total<br>Qty') ?></th>
											<th style="font-size: 1.2rem; width: 85px; position:absolute; left:530px; line-height: 1; background-color: #F1F4F7; border-right: 1px dotted #000"><?= Yii::t('app', 'Total<br>Kubikasi M<sup>3</sup>') ?></th>
											<?php if(isset($modHead)){ ?>
											<?php foreach($modHead as $i => $head){ ?>
											<th style="font-size: 1.1rem; width: 110px;">
												<?php
												$per = explode("-", $head['periode']);
												echo \app\components\DeltaFormatter::getMonthUser($per[1])." ";
												echo $per[0];
												?>
											</th>
											<?php } ?>
											<?php } ?>
										</tr>
										
                                    </thead>
									<tbody>
										<?php
										$contents = $mods;
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td class="td-detail" style="text-align:center; font-size: 1.2rem; position:absolute; left:0px; width: 35px;"><?= $i+1 ?></td>
												<td class="td-detail" style="text-align:left; font-size: 1.2rem; position:absolute; left:35px; width: 300px;">
													<?= $data['produk_nama'] ?>
												</td>
												<td class="td-detail" style="text-align:center; font-size: 1.2rem; width: 50px; position:absolute; left:335px;">
													<?= $data['gudang_nm'] ?>
												</td>
												<td class="td-detail" style="text-align:center; font-size: 1.2rem; width: 60px; position:absolute; left:385px;">
													<?= $data['palet'] ?>
													<input type="hidden" class="total_palet" value="<?= $data['palet'] ?>">
												</td>
												<td class="td-detail" style="text-align:right; font-size: 1.2rem; width: 85px; position:absolute; left:445px;">
													<?= app\components\DeltaFormatter::formatNumberForUserFloat($data['qty_kecil'])." <i>(".$data['in_qty_kecil_satuan'].")</i>"; ?>
													<input type="hidden" class="total_qty" value="<?= $data['qty_kecil'] ?>">
												</td>
												<td class="td-detail" style="text-align:right; font-size: 1.2rem; width: 85px; position:absolute; left:530px; border-right: 1px dotted #000">
													<?= (strlen(substr(strrchr($data['kubikasi'], "."), 1)) > 4)? $data['kubikasi']*10000/10000: $data['kubikasi'] ?>
													<input type="hidden" class="total_kubikasi" value="<?= $data['kubikasi'] ?>">
												</td>
												<?php if(isset($modHead)){ ?>
												<?php foreach($modHead as $i => $head){
													$sql = "SELECT h_persediaan_produk.gudang_id, sum(in_qty_palet-out_qty_palet) AS palet, sum(in_qty_kecil-out_qty_kecil) AS qty_kecil FROM h_persediaan_produk 
															JOIN m_brg_produk ON m_brg_produk.produk_id = h_persediaan_produk.produk_id
															JOIN t_terima_ko on t_terima_ko.nomor_produksi = h_persediaan_produk.nomor_produksi
															WHERE h_persediaan_produk.produk_id = {$data['produk_id']} AND h_persediaan_produk.keterangan NOT ILIKE '%MUTASI DARI GUDANG%' AND h_persediaan_produk.gudang_id = {$data['gudang_id']}
																AND to_char(t_terima_ko.tanggal, 'YYYY-MM') = '{$head['periode']}'
															GROUP BY h_persediaan_produk.gudang_id
															HAVING SUM(in_qty_palet-out_qty_palet) > 0";
													$mod = Yii::$app->db->createCommand($sql)->queryOne();
													?>
													<td class="td-detail" style="text-align:right; font-size: 0.9rem;">
														<?php 
														if(!empty($mod)){
															echo "<b>".$mod['palet']." Palet </b> - ".\app\components\DeltaFormatter::formatNumberForUserFloat($mod['qty_kecil'])." Pcs";
														}else{
															echo "-";
														}
														?>
													</td>
												<?php } ?>
												<?php } ?>
											</tr>
										<?php }
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