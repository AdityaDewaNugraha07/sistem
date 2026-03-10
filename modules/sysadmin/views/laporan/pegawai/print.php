<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];

$cari = Yii::$app->request->get('cari');
$departement_id = Yii::$app->request->get('departement_id');
$status = Yii::$app->request->get('status');

isset($cari) ? $cari = $cari : $cari = '';
isset($departement_id) ? $departement_id = $departement_id : $departement_id = 0;
isset($status) ? $status = $status : $status = 'all';

$where = 'Filter : ';
                
if ($cari != '') {
	$where0 = " nama pegawai : '<b>".$cari."</b>'";
} else {
	$where0 = ' nama pegawai : All';
} 

if ($departement_id > 0) {
	$sql = "select departement_nama from m_departement where departement_id = ".$departement_id;
	$departement_nama = Yii::$app->db->createCommand($sql)->queryScalar();
	$where1 = ' dan departement : <b>'.$departement_nama.'</b>';
} else {
	$where1 = ' dan departement : semua';
}

if ($status != 'all') {
	$status == 'true' ? $status = 'Active' : $status = 'Non Aktif';
	$where2 = ' dan status : <b>'.$status.'</b>';
} else {
	$where2 = ' dan status : semua';
}
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
								<?php
								if ($cari != '' || $departement_id != '' || $status != '') {
									echo $where." ".$where0." ".$where1." ".$where2;
								}
								?>
								<br><br>
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
                                        <tr>
                                            <th><?= Yii::t('app', 'No.'); ?></th>
											<th><?= Yii::t('app', 'Nama Pegawai') ?></th>
											<th><?= Yii::t('app', 'Jenis Kelamin') ?></th>
											<th><?= Yii::t('app', 'Departement') ?></th>
											<th><?= Yii::t('app', 'Jabatan') ?></th>
											<th><?= Yii::t('app', 'Status') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										if(!empty($model)){ 
											foreach($model as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->pegawai_nama ?></td>
												<td><?= $data->pegawai_jk ?></td>
												<td><?= $data->departement_nama ?></td>
												<td><?= $data->jabatan_nama ?></td>
												<td><?= ($data->active == true) ? "Active" : "Nont-Active" ?></td>
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