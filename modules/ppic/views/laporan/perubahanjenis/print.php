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
                                            <th><?= Yii::t('app', 'No'); ?></th>
                                            <th><?= Yii::t('app', 'Kode<br>Pengajuan') ?></th>
                                            <th><?= Yii::t('app', 'Tanggal'); ?></th>
                                            <th><?= Yii::t('app', 'Peruntukan') ?></th>
                                            <th><?= Yii::t('app', 'No Barcode') ?></th>
                                            <th><?= Yii::t('app', 'No Lap') ?></th>
                                            <th><?= Yii::t('app', 'Jenis Kayu Lama'); ?></th>
                                            <th><?= Yii::t('app', 'Jenis Kayu Baru'); ?></th>
                                            <th><?= Yii::t('app', 'Keterangan'); ?></th>
                                            <th><?= Yii::t('app', 'Status Approval'); ?></th>
                                        </tr>
									</thead>
									<tbody>
										<?php
										// $sql = $model->searchLaporan()->createCommand()->rawSql;
										// $contents = Yii::$app->db->createCommand($sql)->queryAll();
                                        $sql = "SELECT log_rubahjenis_id, kode, tanggal, peruntukan, d->>'no_barcode' AS no_barcode, d->>'no_lap' AS no_lap, 
                                                a.kayu_nama as kayu_old, b.kayu_nama as kayu_new, keterangan, status_approve
                                                FROM t_log_rubahjenis
                                                JOIN LATERAL jsonb_array_elements(t_log_rubahjenis.datadetail::jsonb) d ON true
                                                LEFT JOIN m_kayu a ON a.kayu_id = (d->>'kayu_id_old')::int
                                                LEFT JOIN m_kayu b ON b.kayu_id = (d->>'kayu_id_new')::int
                                                WHERE cancel_transaksi_id is NULL AND tanggal BETWEEN '$model->tgl_awal' AND '$model->tgl_akhir'";
                                        if(!empty($model->peruntukan)){
                                            $sql .= " AND peruntukan = '".$model->peruntukan."'";
                                        }
                                        // if(!empty($model->no_barcode)){
                                        //     $sql .= " AND d->>'no_barcode' ILIKE '%".$model->no_barcode."%'";
                                        // }
                                        if(!empty($model->status_approve)){
                                            $sql .= " AND status_approve = '".$model->status_approve."'";
                                        }
                                        if(!empty($model->keyword)){
                                            if($model->label_no){
                                                $sql .= " AND d->>'".$model->label_no."' ILIKE '%".$model->keyword."%'";
                                            }
                                        }
                                        $contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){?>
											<tr>
												<td style="text-align: center;"><?php echo $i+1; ?></td>
												<td><?php echo $data['kode']; ?></td>
												<td style='text-align: center;'><?php echo app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']); ?></td>
												<td style='text-align: center;'><?php echo $data['peruntukan']; ?></td>
                                                <td style='text-align: center;'><?php echo $data['no_barcode']; ?></td>
                                                <td style='text-align: center;'><?php echo $data['no_lap']; ?></td>
                                                <td style='text-align: center;'><?php echo $data['kayu_old']; ?></td>
                                                <td style='text-align: center;'><?php echo $data['kayu_new']; ?></td>
                                                <td><?php echo $data['keterangan']?$data['keterangan']:'<center>-</center>'; ?></td>
                                                <td style='text-align: center;'><?php echo $data['status_approve']; ?></td>
											</tr>
										<?php }
										}else{
											echo"<tr><td colspan='9' style='text-align: center;'>".Yii::t('app', 'Data tidak ditemukan')."<td></tr>";
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