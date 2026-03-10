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
											<th ><?= Yii::t('app', 'Tgl SPB') ?></th>
											<th ><?= Yii::t('app', 'Kode SPB') ?></th>
											<th ><?= Yii::t('app', 'Item') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Qty<br>Pesan') ?></th>
											<th ><?= Yii::t('app', 'Diminta') ?></th>
											<th ><?= Yii::t('app', 'Menyetujui') ?></th>
											<th ><?= Yii::t('app', 'Mengetahui') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Status<br>SPB') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Status<br>Approve') ?></th>
											<th ><?= Yii::t('app', 'Keterangan') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										if(count($models)>0){ 
											foreach($models as $i => $model){
											?>
											<tr>
												<td style="text-align: center; font-size: 1.2rem; vertical-align: top;"><?= $i+1; ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model['spb_tanggal']) ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $model['spb_kode'] ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $model['bhp_nm'] ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $model['spbd_jml'] ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $model['diminta'] ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $model['disetujui'] ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $model['diketahui'] ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $model['spb_status'] ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $model['approve_status'] ?></td>
												<td style="font-size: 1.2rem; vertical-align: top;"><?= $model['spbd_ket'] ?></td>
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