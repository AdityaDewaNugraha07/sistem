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
											<th style="width:30px;" rowspan="2">No.</th>
											<th style="line-height: 1; width:110px;" rowspan="2"><?= Yii::t('app', 'Contract No.') ?></th>
											<th style="line-height: 1;" rowspan="2"><?= Yii::t('app', 'Commodity') ?></th>
											<th style="line-height: 1; width:100px;" rowspan="2"><?= Yii::t('app', 'Size/Profile'); ?></th>
											<th style="line-height: 1; " colspan="2"><?= Yii::t('app', 'Price'); ?></th>
											<th style="line-height: 1; width:40px;" rowspan="2"><?= Yii::t('app', 'Term Of<br>Payment'); ?></th>
											<th style="line-height: 1; width:50px;" rowspan="2"><?= Yii::t('app', 'Code'); ?></th>
											<th style="line-height: 1; width:80px;" rowspan="2"><?= Yii::t('app', 'Planning'); ?></th>
											<th style="line-height: 1; " colspan="5"><?= Yii::t('app', 'Actual'); ?></th>
										</tr>
										<tr>
											<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'USD') ?></th>
											<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'Terms') ?></th>
											<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'Inv. No') ?></th>
											<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'Inv. Date') ?></th>
											<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'ETD') ?></th>
											<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'ETA') ?></th>
											<th style="line-height: 1; width:50px;"><?= Yii::t('app', 'Payment<br>Date') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if(count($model)>0){
											foreach($model as $i => $op){
												$sqldetail = "SELECT detail_order FROM t_op_export where op_export_id = ".$op['op_export_id'];
												$modDetails = \Yii::$app->db->createCommand($sqldetail)->queryOne();
												if(count($modDetails)>0){
													$modDetails = \yii\helpers\Json::decode($modDetails['detail_order']);
													foreach($modDetails as $ii => $detail){
														$sqlinv = "SELECT * FROM t_invoice 
																	JOIN t_packinglist ON t_packinglist.packinglist_id = t_invoice.packinglist_id
																	JOIN t_packinglist_container ON t_packinglist_container.packinglist_id = t_invoice.packinglist_id
																	WHERE t_invoice.op_export_id = ".$op['op_export_id']." AND lot_code = '".$detail['detail_lot_code']."'
																	ORDER BY invoice_id ASC";
														$modInvoice = \Yii::$app->db->createCommand($sqlinv)->queryOne();
														?>
														<tr>
															<td class="td-kecil text-align-center" style="vertical-align: middle !important; font-size: 1.1rem !important;">
																<?= ($i+1); ?>
															</td>
															<td class="td-kecil text-align-left" style="font-size: 1.1rem !important; line-break: 1;">
																<?= "<b>".$op['nomor_kontrak']."</b><br>".$op['cust_an_nama']; ?>
															</td>
															<td class="td-kecil text-align-left" style="font-size: 1.1rem !important;">
																<?= $detail['detail_description']; ?>
															</td>
															<td class="td-kecil text-align-left" style="font-size: 1.1rem !important;">
																<?= $detail['detail_size']; ?> 
															</td>
															<td class="td-kecil text-align-right" style="font-size: 1.1rem !important;">
																<?= (!empty($detail['detail_price'])? app\components\DeltaFormatter::formatNumberForUserFloat($detail['detail_price']):""); ?>
															</td>
															<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
																<?= (!empty($modInvoice['term_of_price'])?$modInvoice['term_of_price']:""); ?>
															</td>
															<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
																<?= (!empty($modInvoice['payment_method'])?$modInvoice['payment_method']:""); ?>
															</td>
															<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
																<?= $detail['detail_lot_code']; ?>
															</td>
															<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
																<?= $detail['shipment_time']; ?>
															</td>
															<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
																<?= (!empty($modInvoice['nomor'])? substr($modInvoice['nomor'], 0, 5):""); ?>
															</td>
															<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
																<?= (!empty($modInvoice['tanggal'])?app\components\DeltaFormatter::formatDateTimeForUser2($modInvoice['tanggal']):""); ?>
															</td>
															<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
																<?= (!empty($modInvoice['etd'])?app\components\DeltaFormatter::formatDateTimeForUser2($modInvoice['etd']):""); ?>
															</td>
															<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">
																<?= (!empty($modInvoice['eta'])?app\components\DeltaFormatter::formatDateTimeForUser2($modInvoice['eta']):""); ?>
															</td>
															<td class="td-kecil text-align-center" style="font-size: 1.1rem !important;">

															</td>
														</tr>
										<?php
													}
												}
											}
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
<?php $this->registerJs("
	mergeSameValue();
", yii\web\View::POS_READY); ?>
<script>
function mergeSameValue(){
	var arr = [];
	var coll = [0,1,2,9,10,11,12];
	$("#table-laporan").find('tr').each(function (r, tr) {
		$(this).find('td').each(function (d, td) {
			if ( coll.indexOf(d) !== -1) {
				var $td = $(td);
				var v_dato = $td.html();
				if(typeof arr[d] != 'undefined' && 'dato' in arr[d] && arr[d].dato == v_dato) {
					var rs = arr[d].elem.data('rowspan');
					if(rs == 'undefined' || isNaN(rs)) rs = 1;
					arr[d].elem.data('rowspan', parseInt(rs) + 1).addClass('rowspan-combine');
					$td.addClass('rowspan-remove');
				} else {
					arr[d] = {dato: v_dato, elem: $td};
				};
			}
		});
	});
	$('.rowspan-combine').each(function (r, tr) {
	  var $this = $(this);
	  $this.attr('rowspan', $this.data('rowspan')).css({'vertical-align': 'middle'});
	});
	$('.rowspan-remove').remove();
}
</script>