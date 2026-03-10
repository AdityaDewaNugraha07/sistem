<?php app\assets\DatepickerAsset::register($this); ?>
<div class="modal fade" id="modal-chstatus" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-closing',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-7">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<div class="row">
					<div class="col-md-9">
						<h4 class="modal-title"><?= Yii::t('app', $pesan); ?></h4>
					</div>
				</div>
            </div>
			<div class="modal-body">
				<div class="col-md-12" style="text-align: center; margin-top: -15px;"><h4>Summary Biaya-Biaya <b><?= $modDkg->graderlog->graderlog_nm ?></b></h4></div>
				<h5 class="modal-title text-align-center"><b><?= Yii::t('app', "Kode Dinas : "); ?> <u><?= $modDkg->kode ?></u></b></h5><br><br>
				<div class="row">
					<div class="col-md-6">
						<h5 class="text-align-center"><b>Akomodasi Dinas</b></h5>
						<div class="table-scrollable">
							<div class="pull-left font-grey-gallery" style="font-size: 1.2rem"><b>Pengajuan Uang Dinas Grader</b></div>
							<table id="table-ajuan" class="table table-striped table-bordered table-advance table-hover">
								<thead>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 110px;">Kode</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 120px;">Tanggal<br>Ajuan</th>
									<th class="td-kecil font-grey-gallery text-align-center">Total<br>Ajuan</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 80px;">Approve<br>Status</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 70px;">Payment<br>Status</th>
								</thead>
								<tbody>
									<?php
									$modAjuanDinas = \app\models\TAjuandinasGrader::find()->where(['dkg_id'=>$modDkg->dkg_id])->orderBy(['created_at'=>SORT_DESC])->all();
									if(count($modAjuanDinas)>0){
										foreach($modAjuanDinas as $i => $ajuan){
											echo "<tr>
													<td class='td-kecil'>".$ajuan->kode."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatDateTimeForUser2($ajuan->tanggal)."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatNumberForUserFloat($ajuan->total_ajuan)."</td>
													<td class='td-kecil'>".\app\models\TApproval::findOne(['reff_no'=>$ajuan->kode])->StatusLite."</td>
													<td class='td-kecil text-align-center'>".((!empty($ajuan->voucher_pengeluaran_id))?$ajuan->voucherPengeluaran->Status_bayarLite:"-")."</td>													
												 </tr>";
										}
									}else{
										echo "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Pengajuan</i></td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="table-scrollable">
							<div class="pull-left font-blue-dark" style="font-size: 1.2rem;"><b>Realisasi Uang Dinas Greder</b></div>
							<table id="table-realisasi" class="table table-striped table-bordered table-advance table-hover">
								<thead style="background-color: ">
									<th class="td-kecil font-blue-dark text-align-center" style="width: 110px;">Kode</th>
									<th class="td-kecil font-blue-dark text-align-center" >Periode</th>
									<th class="td-kecil font-blue-dark text-align-center" style="width: 110px;">Total Realisasi</th>
								</thead>
								<tbody>
									<?php
									$modRealisasiDinas = \app\models\TRealisasidinasGrader::find()->where(['dkg_id'=>$modDkg->dkg_id])->orderBy(['created_at'=>SORT_DESC])->all();
									if(count($modRealisasiDinas)>0){
										foreach($modRealisasiDinas as $i => $realisasi){
											echo "<tr>
													<td class='td-kecil'>".$realisasi->kode."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatDateTimeForUser2($realisasi->periode_awal).' - '.\app\components\DeltaFormatter::formatDateTimeForUser2($realisasi->periode_akhir)."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatNumberForUserFloat($realisasi->total_realisasi)."</td>												
												 </tr>";
										}
									}else{
										echo "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Realisasi</i></td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
						<br>
						<h4 style="" class="text-align-center font-grey-mint">Sisa Saldo Kas Dinas : <b id="place-saldodinas"><?= \app\components\DeltaFormatter::formatNumberForUserFloat(app\models\HKasDinasgrader::getSaldoKas($modDkg->graderlog_id)) ?></b></h4>
					</div>
					<div class="col-md-6">
						<h5 class="text-align-center"><b>Uang Makan</b></h5>
						<div class="table-scrollable">
							<div class="pull-left font-grey-gallery" style="font-size: 1.2rem"><b>Pengajuan Uang Makan Grader</b></div>
							<table id="table-ajuanmakan" class="table table-striped table-bordered table-advance table-hover">
								<thead>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 100px;">Kode</th>
									<th class="td-kecil font-grey-gallery text-align-center">Periode</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 80px;">Total<br>Ajuan</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 80px;">Approve<br>Status</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 70px;">Payment<br>Status</th>
								</thead>
								<tbody>
									<?php
									$modAjuanMakan = \app\models\TAjuanmakanGrader::find()->where(['dkg_id'=>$modDkg->dkg_id])->orderBy(['created_at'=>SORT_DESC])->all();
									if(count($modAjuanMakan)>0){
										foreach($modAjuanMakan as $i => $ajuan){
											echo "<tr>
													<td class='td-kecil'>".$ajuan->kode."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatDateTimeForUser2($ajuan->periode_awal)." sd ".\app\components\DeltaFormatter::formatDateTimeForUser2($ajuan->periode_akhir)."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatNumberForUserFloat($ajuan->total_ajuan)."</td>
													<td class='td-kecil'>".\app\models\TApproval::findOne(['reff_no'=>$ajuan->kode])->StatusLite."</td>
													<td class='td-kecil text-align-center'>".((!empty($ajuan->voucher_pengeluaran_id))?$ajuan->voucherPengeluaran->Status_bayarLite:"-")."</td>													
												 </tr>";
										}
									}else{
										echo "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Pengajuan</i></td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="table-scrollable">
							<div class="pull-left font-blue-dark" style="font-size: 1.2rem;"><b>Realisasi Uang Makan Grader</b></div>
							<table id="table-realisasimakan" class="table table-striped table-bordered table-advance table-hover">
								<thead style="background-color: ">
									<th class="td-kecil font-blue-dark text-align-center" style="width: 110px;">Kode</th>
									<th class="td-kecil font-blue-dark text-align-center" >Periode</th>
									<th class="td-kecil font-blue-dark text-align-center" style="width: 110px;">Total Realisasi</th>
								</thead>
								<tbody>
									<?php
									$modRealisasiMakan = \app\models\TRealisasimakanGrader::find()->where(['dkg_id'=>$modDkg->dkg_id])->orderBy(['created_at'=>SORT_DESC])->all();
									if(count($modRealisasiMakan)>0){
										foreach($modRealisasiMakan as $i => $realisasi){
											echo "<tr>
													<td class='td-kecil'>".$realisasi->kode."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatDateTimeForUser2($realisasi->periode_awal).' - '.\app\components\DeltaFormatter::formatDateTimeForUser2($realisasi->periode_akhir)."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatNumberForUserFloat($realisasi->total_realisasi)."</td>												
												 </tr>";
										}
									}else{
										echo "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Realisasi</i></td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
						<br>
						<h4 style="" class="text-align-center font-grey-mint">Sisa Saldo Kas Makan : <b id="place-saldodinas"><?= \app\components\DeltaFormatter::formatNumberForUserFloat(app\models\HKasMakangrader::getSaldoKas($modDkg->graderlog_id)) ?></b></h4>
					</div>
				</div>
				<br><br><br>
				<div class="row">
					<div class="col-md-12">
						<div style="font-size: 1.1rem"><b>Note:</b> <i>Mohon periksa kembali summary diatas dengan teliti sebelum anda menekan tombol Selesai Dinas!</i></div>
					</div>
				</div>
			</div>
            <div class="modal-footer" style="text-align: center;">
				<?php 
					echo \yii\helpers\Html::button( Yii::t('app', 'Selesai Dinas!'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'validation()']);
				?>
            </div>
			<?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
formconfig();
", yii\web\View::POS_READY); ?>
<script>
	
function validation(){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/validationSelesaiDinas','dkg_id'=>$modDkg->dkg_id]); ?>',
		type   : 'POST',
		data   : {},
		success: function (data) {
			if(data.status){
				yes();
			}else{
				cisAlert(data.msg);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function yes(){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/changeStatus','dkg_id'=>$modDkg->dkg_id]); ?>',
		type   : 'POST',
		data   : {updaterecord:true},
		success: function (data) {
			$('#modal-delete-record').modal('hide');
			if(data.status){
				if(data.message){
                    cisAlert(data.message);
				}
				<?php if(isset($tableid)){ ?> 
					$('#<?= $tableid ?>').dataTable().fnClearTable(); 
				<?php } ?>
				if(data.callback){
					eval(data.callback);
				}else{
					
				}
			}else{
				if(data.message){
                    if(data.message.errorInfo){
                        cisAlert(data.message.errorInfo[2]);
                    }else{
                        cisAlert(data.message);
                    }
				}
			}
			$('#modal-delete-record').find('.progress-success .bar').animate({'width':'0%'});
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		progress: function(e) {
			if(e.lengthComputable) {
				var pct = (e.loaded / e.total) * 100;
				$('#modal-delete-record').find('.progress-success .bar').animate({'width':pct.toPrecision(3)+'%'});
			}else{
				console.warn('Content Length not reported!');
			}
		}
	});
}
</script>