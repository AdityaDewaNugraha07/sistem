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
											<th><?= Yii::t('app', 'Kode Penerimaan') ?></th>
											<th><?= Yii::t('app', 'Tanggal Terima') ?></th>
											<th><?= Yii::t('app', 'Supplier') ?></th>
											<th><?= Yii::t('app', 'Kode Item') ?></th>
											<th><?= Yii::t('app', 'Nama Item') ?></th>
											<th><?php echo Yii::t('app', 'Satuan') ?></th>
											<th><?= Yii::t('app', 'Qty') ?></th>
											<th><?php echo Yii::t('app', 'Harga Satuan') ?></th>
											<th><?php echo Yii::t('app', 'Ppn') ?></th>
											<th><?php echo Yii::t('app', 'Pph') ?></th>
											<th><?php echo Yii::t('app', 'PBBKB') ?></th>
											<th><?php echo Yii::t('app', 'Total') ?></th>
											<th><?php echo Yii::t('app', 'Keterangan') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;" class="td-kecil"><?= $i+1; ?></td>
												<td class="td-kecil"><?= $data->terimabhp_kode ?></td>
												<td class="td-kecil"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tglterima) ?></td>
												<td class="td-kecil"><?= $data->suplier_nm ?></td>
												<td class="td-kecil"><?= $data->bhp_kode ?></td>
												<td class="td-kecil"><?php
													$parse1 = explode("/", $data->bhp_nm)[1];
													$parse2 = isset(explode("/", $data->bhp_nm)[2])?'/'.explode("/", $data->bhp_nm)[2]:'';
													$parse3 = isset(explode("/", $data->bhp_nm)[3])?'/'.explode("/", $data->bhp_nm)[3]:'';
													$parse4 = isset(explode("/", $data->bhp_nm)[4])?'/'.explode("/", $data->bhp_nm)[4]:'';
													echo $parse1.$parse2.$parse3.$parse4;
												?></td>
												<td class="td-kecil" style="text-align: left;"><?php echo $data->bhp_satuan ?></td>
												<td class="td-kecil" style="text-align: right;"><?= $data->terimabhpd_qty ?></td>
												<td class="td-kecil" style="text-align: right;"><?php echo round($data->terimabhpd_harga); ?></td>

												<td class="td-kecil" style="text-align: right;">
													<?php
//														if( (!empty($data->spo_id)) && ($data->ppn_nominal != 0) ){
//															echo round(($data->terimabhpd_qty*$data->terimabhpd_harga)*0.1);
//														}
														if(($data->ppn_nominal != 0)){
															echo round(($data->terimabhpd_qty*$data->terimabhpd_harga)*0.1);
														}
													?>
												</td>

												<td class="td-kecil" style="text-align: right;">
													<?= round($data->pph_peritem); ?>
												</td>

												<td class="td-kecil" style="text-align: right;">
													<?= round($data->total_pbbkb); ?>
												</td>

												<td class="td-kecil" style="text-align: right;">
													<?php
													$total_pbbkb = $data->total_pbbkb;
													$ret = $data->terimabhpd_qty*$data->terimabhpd_harga;
//													if( (!empty($data->spo_id)) && ($data->ppn_nominal != 0) ){
//														$ret = $ret + ($ret*0.1);
//													}
													if(($data->ppn_nominal != 0)){
														$ret = $ret + ($ret*0.1);
													}
//													echo \app\components\DeltaFormatter::formatNumberForUser($ret);
													echo round($ret + $total_pbbkb, 0);
													?>
												</td>

												<td style="padding: 3px; font-size:1.1rem;"><?php echo $data->terimabhpd_keterangan; ?></td>
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