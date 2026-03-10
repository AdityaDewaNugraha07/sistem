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
											<th style="font-size: 1.1rem; line-height: 1; width: 30px;">No.</th>
											<th style="font-size: 1.1rem; line-height: 1; width: 100px;"><?= Yii::t('app', 'Kode SPM') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'No Inv / PL') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Kirim') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'Customer') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'Final Destination') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'Nama Produk') ?></th>
											<th colspan="2" style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'T') ?></th>
											<th colspan="2" style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'L') ?></th>
											<th colspan="2" style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'P') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'Jml<br>Palet') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'Qty<br>Pcs') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'Total<br>Volume') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$contents = $model->searchLaporanStuffingDetail()->all();
										if(!empty($contents)){ 
                                                                                    $total_palet = 0;
                                                                                    $total_pcs = 0;
                                                                                    $total_kubikasi = 0;
											foreach($contents as $i => $data){
                                                                                            $total_palet += $data['qty_besar_realisasi'];
                                                                                            $total_pcs += $data['qty_kecil_realisasi'];
                                                                                            $total_kubikasi += number_format($data['kubikasi'],4);
											?>
											<tr>
												<td style="text-align: center; font-size: 1rem; "><?= $i+1; ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->kode ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->no_inv ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal_kirim); ?></td>
												<td style="font-size: 1rem;"><?= $data->cust_an_nama; ?></td>
												<td style="font-size: 1rem;"><?= $data->alamat_bongkar; ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->produk_group; ?></td>
												<td style="font-size: 1rem;"><?= $data->produk_nama; ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->produk_t ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->produk_t_satuan ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->produk_l ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->produk_l_satuan ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->produk_p ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->produk_p_satuan ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->qty_besar_realisasi; ?></td>
												<td style="font-size: 1rem; text-align: right;"><?= $data->qty_kecil_realisasi; ?></td>
												<td style="font-size: 1rem; text-align: right;"><?= number_format($data->kubikasi,4); ?></td>
											</tr>
										<?php }
										}else{
											"<tr colspan='5'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
										}
										?>
                                                                                        <tr>
                                                                                            <td colspan='14'></td>
                                                                                            <td style="font-size: 1rem; text-align: center;"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_palet);?></td>
                                                                                            <td style="font-size: 1rem; text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_pcs);;?></td>
                                                                                            <td style="font-size: 1rem; text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_kubikasi,4);?></td>
                                                                                        </tr>
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