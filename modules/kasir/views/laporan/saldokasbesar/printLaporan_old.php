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
.table td, .table th {
    font-size: 12px !important;
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
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
                                        <tr>
											<th style="text-align: center; width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
											<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Kode'); ?></th>
											<th style="text-align: center; "><?= Yii::t('app', 'Deskripsi'); ?></th>
											<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Debit'); ?></th>
											<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Kredit'); ?></th>
											<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Saldo'); ?></th>
										</tr>
                                    </thead>
									<tbody>
										<tr>
											<td class="" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right; background-color: #daffaa">SALDO AWAL</td>
											<td class="" style="background-color: #daffaa"> &nbsp; </td>
											<td class="" style="background-color: #daffaa"> &nbsp; </td>
											<td class="text-align-right" style="background-color: #daffaa; font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat(\app\models\HSaldoKasbesar::getSaldoAwal($model[0]->tanggal) ):\app\models\HSaldoKasbesar::getSaldoAwal($model[0]->tanggal); ?></td>
										</tr>
										<?php
										$totalkredit = 0;
										$totaldebit = 0;
										if(count($model)>0){ 
											foreach($model as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td class="text-align-center"><?= $data['kode'] ?></td>
												<td><?= $data['deskripsi']; ?></td>
												<td class="text-align-right"><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat($data['debit']):$data['debit'] ?></td>
												<td class="text-align-right"><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat($data['kredit']):$data['kredit'] ?></td>
												<!--<td class="text-align-right"><?php // echo app\components\DeltaFormatter::formatNumberForUserFloat($data['saldo']) ?></td>-->
												<td class="td-kecil text-align-right">-</td>
											</tr>
										<?php 
										$totaldebit += $data['debit'];
										$totalkredit += $data['kredit'];
										}
										}else{
											"<tr colspan='5'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
										}
										?>
										<tr>
											<td class="" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right;">TOTAL</td>
											<td class=" text-align-right" style="font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( $totaldebit ):$totaldebit; ?></td>
											<td class=" text-align-right" style="font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( $totalkredit ):$totalkredit; ?></td>
											<td class=""> &nbsp; </td>
										</tr>
										<tr>
											<td class="" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right;">SALDO AKHIR</td>
											<td class= > &nbsp; </td>
											<td class= > &nbsp; </td>
											<td class=" text-align-right" style="font-weight: bold; "><?= ($_GET['caraprint'] != "EXCEL")?app\components\DeltaFormatter::formatNumberForUserFloat( \app\models\HSaldoKasbesar::getSaldoAkhir($model[0]->tanggal) ):\app\models\HSaldoKasbesar::getSaldoAkhir($model[0]->tanggal); ?></td>
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