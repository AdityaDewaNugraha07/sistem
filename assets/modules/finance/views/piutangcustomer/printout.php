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
<style>
#table-piutang thead tr th{
	font-size: 1.2rem;
	line-height: 0.9;
}
#table-piutang tbody tr td{
	font-size: 1.2rem;
}
#table-piutang tfoot tr td{
	font-size: 1.2rem;
}
</style>
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
                        <table class="table table-striped table-bordered table-hover" id="table-piutang">
							<thead>
								<tr style="background-color: #F1F4F7; ">
									<th rowspan="2" style="width: 40px; text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
									<th rowspan="2" style="text-align: center;"><?= Yii::t('app', 'Customer'); ?></th>
									<th rowspan="2" style="width: 110px; text-align: center;"><?= Yii::t('app', 'Kode<br>Nota/Invoice'); ?></th>
									<th rowspan="2" style="width: 100px; text-align: center;"><?= Yii::t('app', 'Tanggal<br>Nota/Invoice'); ?></th>
									<th rowspan="2" style="width: 120px; text-align: center;"><?= Yii::t('app', 'Sisa<br>Piutang'); ?></th>
									<th rowspan="2" style="width: 55px; text-align: center;"><?= Yii::t('app', 'TOP'); ?></th>
									<th colspan="4" style="text-align: center;"><?= Yii::t('app', 'Over Due'); ?></th>
								</tr>
								<tr style="background-color: #F1F4F7; ">
									<th style="width: 120px; text-align: center;"><?= Yii::t('app', '0 - 30'); ?></th>
									<th style="width: 110px; text-align: center;"><?= Yii::t('app', '31 - 60'); ?></th>
									<th style="width: 110px; text-align: center;"><?= Yii::t('app', '61 - 90'); ?></th>
									<th style="width: 110px; text-align: center;"><?= Yii::t('app', '90 +'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$seq=0; $totalpiutang = 0; $total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $matauang = '';
								if(count($model)>0){
									foreach($model as $i => $mod){
									$modNota = Yii::$app->db->createCommand("SELECT * FROM t_nota_penjualan WHERE kode = '".$mod['kode']."'")->queryOne();
									$modTempo = Yii::$app->db->createCommand("SELECT * FROM t_tempobayar_ko WHERE op_ko_id = '".$modNota['op_ko_id']."'")->queryOne();
									$sisapiutang = $modNota['total_bayar']-$mod['terbayar'];
									$totalpiutang += $sisapiutang; 
									if($sisapiutang > 0){
										$seq=$seq+1;
										$matauang = !(empty($mod['mata_uang']))?\app\models\MDefaultValue::getOneByValue('mata-uang', $mod['mata_uang'], 'name_en'):"";
										$tglnotainvoice = new DateTime(app\components\DeltaFormatter::formatDateTimeForDb($mod['tanggal']));
										$tglcetak = new DateTime(app\components\DeltaFormatter::formatDateTimeForDb($tgl));
										$interval = $tglnotainvoice->diff($tglcetak)->days;
										$due_date = $interval-$modTempo['top_hari'];

								?>
									<tr>
										<td style="text-align: center;"><?= $seq ?></td>
										<td style=""><?= $mod['cust_an_nama'] ?></td>
										<td style="text-align: center;"><?= $mod['kode'] ?></td>
										<td style="text-align: center;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($mod['tanggal']) ?></td>
										<td style="text-align: right; font-weight: 600">
											<span class="pull-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($sisapiutang) ?></span>
										</td>
										<td style="text-align: right"><i><?= $modTempo['top_hari'] ?> Hari</i></td>
										<td style="text-align: right; ">
											<?php if($due_date <= 30){ $total1 = $total1+$sisapiutang ?>
												<span class="pull-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($sisapiutang) ?></span>
											<?php }else{ echo "<center>-</center>"; } ?>
										</td>
										<td style="text-align: right; ">
											<?php if($due_date >= 31 && $due_date <= 60){ $total2 = $total2+$sisapiutang ?>
												<span class="pull-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($sisapiutang) ?></span>
											<?php }else{ echo "<center>-</center>"; } ?>
										</td>
										<td style="text-align: right; ">
											<?php if($due_date >= 61 && $due_date <= 90){ $total3 = $total3+$sisapiutang ?>
												<span class="pull-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($sisapiutang) ?></span>
											<?php }else{ echo "<center>-</center>"; } ?>
										</td>
										<td style="text-align: right; ">
											<?php if($due_date >= 91){ $total4 = $total4+$sisapiutang ?>
												<span class="pull-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($sisapiutang) ?></span>
											<?php }else{ echo "<center>-</center>"; } ?>
										</td>
									</tr>
								<?php } } }else{ ?>
									<tr><td colspan="10" style="text-align: center;"><i>Data tidak ditemukan</i></td></tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr style="background-color: #F1F4F7; ">
									<td colspan="4" style="text-align: right; font-size: 1.3rem"><b>Total &nbsp; </b></td>
									<td style="text-align: center; font-weight: 600; font-size: 1.3rem" class="font-red-soft">
										<span class="pull-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($totalpiutang) ?></span>
									</td>
									<td style=""></td>
									<td style="text-align: right;"  class="font-blue-steel">
										<?php if($total1 > 0){ ?>
											<span class="pull-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($total1) ?></span>
										<?php }else{ echo "<center>-</center>"; } ?>
									</td>
									<td style="text-align: right;" class="font-blue-steel">
										<?php if($total2 > 0){ ?>
											<span class="pull-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($total2) ?></span>
										<?php }else{ echo "<center>-</center>"; } ?>
									</td>
									<td style="text-align: right;" class="font-blue-steel">
										<?php if($total3 > 0){ ?>
											<span class="pull-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($total3) ?></span>
										<?php }else{ echo "<center>-</center>"; } ?>
									</td>
									<td style="text-align: right;" class="font-blue-steel">
										<?php if($total4 > 0){ ?>
											<span class="pull-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($total4) ?></span>
										<?php }else{ echo "<center>-</center>"; } ?>
									</td>
								</tr>
							</tfoot>
						</table>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>