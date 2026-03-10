<?php app\assets\DatepickerAsset::register($this); ?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
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
					<div class="col-md-2 pull-right">
						<h4 class="modal-title"><strong style="background-color:#FBE88C">Kas Kecil</strong></h4>
					</div>
				</div>
            </div>
			<?php
			$sql0 = "SELECT SUM(nominal) AS nominal FROM t_kas_kecil WHERE tanggal='".$id."' AND tipe='IN'";
			$penambahankas = Yii::$app->db->createCommand($sql0)->queryOne()['nominal'];
			$sql = "SELECT SUM(nominal) AS nominal FROM t_kas_kecil WHERE tanggal='".$id."' AND tipe='OUT'";
			$operasional = Yii::$app->db->createCommand($sql)->queryOne()['nominal'];
			$sql2 = "SELECT SUM(subtotal) AS nominal FROM t_uangtunai WHERE tanggal='".$id."' AND tipe = 'KK'";
			$uangtunai = Yii::$app->db->createCommand($sql2)->queryOne()['nominal'];
			$sql3 = "SELECT SUM(nominal) AS nominal FROM t_kas_bon WHERE tipe = 'KK' AND kas_kecil_id IS NULL AND (status!='PAID' OR status_bon IS NULL)";
			$kasbon = Yii::$app->db->createCommand($sql3)->queryOne()['nominal'];
			$sql4 = "SELECT SUM(nominal) AS bonkasbesar FROM t_kas_bon WHERE kas_kecil_id IS NULL AND tipe = 'KB' AND (status_bon != 'PAID' OR status_bon IS NULL) AND status='KASBON KASBESAR KE KASKECIL' AND tanggal <= '".$id."'";
			$kasbonkasbesar = Yii::$app->db->createCommand($sql4)->queryOne()['bonkasbesar'];
			
			$penambahankas = (!empty($penambahankas)?$penambahankas:0);
			$operasional = (!empty($operasional)?$operasional:0);
			$uangtunai = (!empty($uangtunai)?$uangtunai:0);
			$kasbon = (!empty($kasbon)?$kasbon:0);
			$kasbonkasbesar = (!empty($kasbonkasbesar)?$kasbonkasbesar:0);
			
			$saldoawal = \app\models\HSaldoKaskecil::getSaldoAwal(app\components\DeltaFormatter::formatDateTimeForDb($id));
			$total_keluar = $operasional + $kasbon;
			$saldoakhir = $saldoawal + $penambahankas - $total_keluar + $kasbon;
			
			$cek_bal = ($saldoawal + $penambahankas + $kasbonkasbesar - $total_keluar) - $uangtunai;
			if($cek_bal==0){
				$balance = "<span class='font-green-seagreen'>OK!</span>";
			}else if($cek_bal > 0){
				$balance = "<span class='font-red-flamingo'>".app\components\DeltaFormatter::formatNumberForUserFloat($cek_bal)."</span>";
			}else if($cek_bal < 0){
				$balance = "<span class='font-red-flamingo'>(".app\components\DeltaFormatter::formatNumberForUserFloat($cek_bal).")</span>";
			}
			
			?>
			<div class="modal-body">
				<div class="col-md-12" style="text-align: center; margin-top: -15px;"><h4>Closing Summary</h4></div><br><br>
				<div class="row">
					<div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
								<table style="width: 100%;" class="table-striped" id="table-summary">
									<tr>
										<td style="width: 35%;"><h4><?= Yii::t('app', 'SALDO AWAL : '); ?></h4></td>
										<td style="text-align: right; width: 65%;">
											<h4><span style="font-weight: bold;" id="place-saldoawal"><?= app\components\DeltaFormatter::formatNumberForUserFloat($saldoawal); ?></span></h4>
										</td>
									</tr>
									<tr>
										<td>
											<h4><?= Yii::t('app', 'Total Masuk : '); ?></h4>
											<h5> &nbsp; &nbsp; Penambahan Kas</h5>
											<h5> &nbsp; &nbsp; Lain-Lain</h5>
										</td>
										<td style="text-align: right;">
											<h4><span style="font-weight: bold;" id="place-totalmasuk"><?= app\components\DeltaFormatter::formatNumberForUserFloat($penambahankas) ?></span></h4>
											<h5> &nbsp; &nbsp; <span id="place-penambahankas"><?= app\components\DeltaFormatter::formatNumberForUserFloat($penambahankas) ?></span></h5>
											<h5> &nbsp; &nbsp; <span id="place-masuklain">0</span></h5>
										</td>
									</tr>
									<tr>
										<td>
											<h4><?= Yii::t('app', 'Total Keluar : '); ?></h4>
											<h5>
												&nbsp; &nbsp; Operasional
												<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoLaporan('<?= $id ?>')"><i class="fa fa-info-circle"></i></a>
											</h5> 
											<h5> 
												&nbsp; &nbsp; Bon Sementara
												<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoKasbon('<?= $id ?>')"><i class="fa fa-info-circle"></i></a>
											</h5>
											<h5> &nbsp; &nbsp; Lain-Lain</h5>
										</td>
										<td style="text-align: right;">
											<h4>
												<span style="font-weight: bold;" id="place-totalkeluar"><?= app\components\DeltaFormatter::formatNumberForUserFloat($total_keluar) ?></span>
												<h5> &nbsp; &nbsp; <span id="place-operasional"><?= app\components\DeltaFormatter::formatNumberForUserFloat($operasional) ?></span></h5>
												<h5> &nbsp; &nbsp; <span id="place-bonsementara"><?= app\components\DeltaFormatter::formatNumberForUserFloat($kasbon) ?></span></h5>
												<h5> &nbsp; &nbsp; <span id="place-keluarlain">0</span></h5>
											</h4>
										</td>
									</tr>
									<tr>
										<td><h4><?= Yii::t('app', 'Bon Kas Besar : '); ?>
												<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoKasbonKasbesar('<?= $id ?>')"><i class="fa fa-info-circle"></i></a>
											</h4></td>
										<td style="text-align: right;">
											<h4><span style="font-weight: bold;" id="place-bonkasbesar"><?= app\components\DeltaFormatter::formatNumberForUserFloat($kasbonkasbesar) ?></span></h4>
										</td>
									</tr>
									<tr>
										<td><h4><?= Yii::t('app', 'Jumlah Uang Tunai : '); ?>
												<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoUangtunai('<?= $id ?>')"><i class="fa fa-info-circle"></i></a>
											</h4></td>
										<td style="text-align: right;">
											<h4><span style="font-weight: bold;" id="place-uangtunai"><?= app\components\DeltaFormatter::formatNumberForUserFloat($uangtunai) ?></span></h4>
										</td>
									</tr>
									<tr>
										<td><h4><?= Yii::t('app', 'SALDO AKHIR : '); ?></h4></td>
										<td style="text-align: right;">
											<h4><span style="font-weight: bold;" id="place-saldoakhir"><?= app\components\DeltaFormatter::formatNumberForUserFloat($saldoakhir) ?></span></h4>
										</td>
									</tr>
									<tr><td>&nbsp;</td></tr>
									<tr>
										<td><h3><b><?= Yii::t('app', 'BALANCE : '); ?></b></h3></td>
										<td style="text-align: right;">
											<h3><span style="font-weight: bold;" id=""><?= $balance ?></span></h3>
										</td>
									</tr>
								</table>
                            </div>
                        </div>
                    </div>
					<div class="col-md-1"></div>
                </div>
				<div class="row">
					<div class="col-md-12">
						<div style="font-size: 1.1rem" class="text-align-center"><b>Note:</b> <i>Mohon periksa kembali summary diatas dengan teliti sebelum anda menekan tombol Closing!</i></div>
					</div>
				</div>
			</div>
            <div class="modal-footer" style="text-align: center;">
				<?php 
					echo \yii\helpers\Html::button( Yii::t('app', 'CLOSING!'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'check()']);
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
function check(){
	var tgl = '<?= app\components\DeltaFormatter::formatDateTimeForDb($id) ?>';
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/CheckClosingUangTunai']); ?>',
		type   : 'POST',
		data   : { tgl: tgl },
		success: function (data) {
			if(data.exist == 1){
				yes();
			}else{
//				cisAlert("Tidak bisa closing, Rincian Uang Tunai belum di input."); return false;
				cisAlert(data.msg); return false;
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function yes(){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/closingConfirm','id'=>$id]); ?>',
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
	
function infoLaporan(tgl){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/rekapPraClosing','tgl'=>'']); ?>'+tgl+'&info=laporan';
	$(".modals-place-2").load(url, function() {
		$("#modal-rekap").modal('show');
		$("#modal-rekap").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
function infoUangtunai(tgl){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/rekapPraClosing','tgl'=>'']); ?>'+tgl+'&info=uangtunai';
	$(".modals-place-2").load(url, function() {
		$("#modal-uangtunai").modal('show');
		$("#modal-uangtunai .modal-dialog").css('width','400px');
		$("#modal-uangtunai").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
function infoKasbon(tgl){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/rekapPraClosing','tgl'=>'']); ?>'+tgl+'&info=kasbon';
	$(".modals-place-2").load(url, function() {
		$("#modal-kasbon").modal('show');
		$("#modal-kasbon").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
function infoKasbonKasbesar(tgl){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/rekapPraClosing','tgl'=>'']); ?>'+tgl+'&info=kasbonkasbesar';
	$(".modals-place-2").load(url, function() {
		$("#modal-kasbonkasbesar").modal('show');
		$("#modal-kasbonkasbesar").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
</script>