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
											<th><?= Yii::t('app', 'Kode BPB') ?></th>
											<th><?= Yii::t('app', 'Tanggal Keluar') ?></th>
											<th><?= Yii::t('app', 'Kode Item') ?></th>
											<th><?= Yii::t('app', 'Nama Item') ?></th>
											<th><?= Yii::t('app', 'Qty') ?></th>
											<th><?php echo Yii::t('app', 'Satuan') ?></th>
											<th><?= Yii::t('app', 'Dept Tujuan') ?></th>
											<th><?= Yii::t('app', 'Keterangan') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){
											$modBhp = app\models\MBrgBhp::findOne($data->bhp_id);
//											echo "<pre>";
//											print_r($data->bhp_kode);
//											echo "<pre>";
//											print_r($data->bpb_tgl_keluar);
//											exit;
											?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->bpb_kode ?></td>
												<td><?= \app\components\DeltaFormatter::formatDateTimeForUser2($data->bpb_tgl_keluar); ?></td>
												<td><?= $data->bhp_kode ?></td>
												<td><?php
													$parse1 = explode("/", $data->bhp_nm)[1];
													$parse2 = isset(explode("/", $data->bhp_nm)[2])?'/'.explode("/", $data->bhp_nm)[2]:'';
													$parse3 = isset(explode("/", $data->bhp_nm)[3])?'/'.explode("/", $data->bhp_nm)[3]:'';
													echo $parse1.$parse2.$parse3;
												?></td>
												<td><?= $data->bpbd_jml ?></td>
												<td><?php echo $data->bhp_satuan ?></td>
												<td><?= $data->departement_nama ?></td>
												<td style="font-size: 1.1rem;"><?= $data->bpbd_ket ?></td>
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