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
											<th><?= Yii::t('app', 'Kode SPP') ?></th>
											<th><?= Yii::t('app', 'Tanggal Permintaan') ?></th>
											<th><?= Yii::t('app', 'Nama Item') ?></th>
											<th><?= Yii::t('app', 'Qty') ?></th>
											<th><?= Yii::t('app', 'Qty<br>Terpenuhi') ?></th>
											<th><?= Yii::t('app', 'Satuan') ?></th>
											<th><?= Yii::t('app', 'Dept Pemesan') ?></th>
											<th><?= Yii::t('app', 'SPB') ?></th>
											<th><?= Yii::t('app', 'Penawaran<br>Terpilih') ?></th>
											<th><?= Yii::t('app', 'Reff<br> Pembelian') ?></th>
											<th><?= Yii::t('app', 'Reff<br> Terima') ?></th>
											<th><?= Yii::t('app', 'Status') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?>
											<?php //echo"<pre>";print_r($data->reff_no);echo"</pre>"; 
											if(!empty($data->status_closed)){
												$stClosed = "CLOSED<br>(".$data->status_closed.")";												
											}else{
												if($data->sppd_qty > $data->terimabhpd_qty){
													$stClosed = "PARTIAL";
												}else if($data->sppd_qty == $data->terimabhpd_qty || $data->sppd_qty < $data->terimabhpd_qty){
													$stClosed = "COMPLETE";
												}
											}
											
											?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->spp_kode ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->spp_tanggal) ?></td>
												<td><?= $data->bhp_nm ?></td>
												<td style="text-align: center;"><?= $data->sppd_qty ?></td>
												<td style="text-align: center;"><?= $data->terimabhpd_qty ?></td>												
												<td style="text-align: center;"><?= $data->bhp_satuan ?></td>
												<td><?= $data->departement_nama ?></td>
												<td>
												<?php
													if(!empty($data->spbd_id)){
														$textobj = $data->spbd_id;	
														$obj = json_decode($textobj);	
														foreach($obj as $item) {
															echo $item->spbkode . "<br>";
														}
													}
												?>
												</td>
												<td><?= $data->suplier_nm ?></td>
												<td>
												<?php 
													if(!empty($data->reff_no)){
														$textreff = $data->reff_no;	
														$objreff = json_decode($textreff);	
														foreach($objreff as $itemreff) {
															echo $itemreff->reffno . "<br>";
														}
													}
												?>
												</td>
												<td>
												<?php 
													if(!empty($data->terima_bhpd_id)){
														$textobj = $data->terima_bhpd_id;	
														$obj = json_decode($textobj);	
														foreach($obj as $item) {
															echo $item->terimabhp_kode . "<br>";
														}
													} 
												?>
												</td>
												<td><?= $stClosed ?></td>												
											</tr>
										<?php }
										}else{
											"<tr colspan='13'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
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