<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporanSpb',['paramprint'=>$paramprint,'model'=>$model]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<table style="width: 20cm; margin: 5px;" border="1" >
	<tr style="">
		<td colspan="2" style="vertical-align: top;">
			<?php echo $header; ?>
		</td>
	</tr>
	<tr style="border-top: solid 1px transparent;">
		<td style="width: 50%; padding-left: 10px;">
			<table style="font-size: 1.3rem;" >
				<tr>
					<td style="width: 4cm"><strong><?= Yii::t('app', 'Kode SPB'); ?></strong></td>
					<td style="width: 6cm"><?= $model->spb_kode ?></td>
				</tr>
				<tr>
					<td style=""><strong><?= Yii::t('app', 'Nomor Berkas SPB'); ?></strong></td>
					<td style=""><?= (!empty($model->spb_nomor)?$model->spb_nomor:" - ") ?></td>
				</tr>
				<tr>
					<td style=""><strong><?= Yii::t('app', 'Dept. Pemesan'); ?></strong></td>
					<td style=""><?= $model->departement->departement_nama; ?></td>
				</tr>
				<tr>
					<td style=""><strong><?= Yii::t('app', 'Tanggal'); ?></strong></td>
					<td style=""><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->spb_tanggal); ?></td>
				</tr>
				<tr>
					<td style=""><strong><?= Yii::t('app', 'Status'); ?></strong></td>
					<td style=""><?= $model->spb_status ?></td>
				</tr>
			</table>
		</td>
		<td style="width: 50%; padding-left: 10px; border-left: solid 1px transparent;">
			<table style="font-size: 1.3rem;">
				<tr>
					<td style="width: 4cm"><strong><?= Yii::t('app', 'Catatan Khusus'); ?></strong></td>
					<td style="width: 6cm"><?= (!empty($model->spb_keterangan)?$model->spb_keterangan:" - "); ?></td>
				</tr>
				<tr>
					<td style=""><strong><?= Yii::t('app', 'Diminta Oleh'); ?></strong></td>
					<td style=""><?= (!empty($model->spb_diminta)?$model->spbDiminta->pegawai_nama:' - ') ?></td>
				</tr>
				<tr>
					<td style=""><strong><?= Yii::t('app', 'Disetujui Oleh'); ?></strong></td>
					<td style=""><?= (!empty($model->spb_disetujui)?$model->spbDisetujui->pegawai_nama:' - ') ?></td>
				</tr>
				<tr>
					<td style=""><strong><?= Yii::t('app', 'Diketahui Oleh'); ?></strong></td>
					<td style=""><?= (!empty($model->spb_mengetahui)?$model->spbMengetahui->pegawai_nama:' - ') ?></td>
				</tr>
				<tr>
					<td style=""><strong><?= Yii::t('app', 'Status Approval'); ?></strong></td>
					<td style="">
						<div class="col-md-7" style="margin-top:7px;">
							<?php
							if(count($model)>0){
								if($model->approve_status == \app\models\TApproval::STATUS_APPROVED){
									echo '<span class="label label-sm label-success"> '.$model->approve_status .' </span>';
								}else if($model->approve_status == \app\models\TApproval::STATUS_REJECTED){
									echo '<span class="label label-sm label-danger"> '.$model->approve_status  .' </span>';
								}else{
									echo '<span class="label label-sm label-default"> '.\app\models\TApproval::STATUS_NOT_CONFIRMATED.' </span>';
								}
							}else{
								echo '<span class="label label-sm label-default"> '.\app\models\TApproval::STATUS_NOT_CONFIRMATED.' </span>';
							}
							?>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="border-top: solid 1px transparent;"><td colspan="2">&nbsp;</td></tr>
	<tr style="border-top: solid 1px transparent;">
		<td colspan="2" style="width: 100%; padding-left: 10px;">
			<table id="table-detail" class="table table-striped table-bordered table-advance table-hover">
				<thead>
					<tr>
						<th style="width:35px;">No.</th>
						<th><?= Yii::t('app', 'Item'); ?></th>
						<th style="width:50px;"><?= Yii::t('app', 'Qty'); ?></th>
						<th><?= Yii::t('app', 'Keterangan'); ?></th>
						<th style="width:100px;"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($modelModDetail as $i => $detail){ ?>
					<tr>
						<td class="td-kecil text-align-center"><?= $i+1; ?></td>
						<td class="td-kecil"><?= $detail->bhp->bhp_nm ?></td>
						<td class="td-kecil text-align-center"><?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->spbd_jml)." ".$detail->bhp->bhp_satuan ?> </td>
						<td class="td-kecil"><?= $detail->spbd_ket ?></td>
						<td></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</td>
	</tr>
</table>
<div style="font-size: 0.9rem;">
<?php
echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
?>
</div>