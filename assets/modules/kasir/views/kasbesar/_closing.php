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
						<h4 class="modal-title"><strong style="background-color:#FBE88C">Kas Besar</strong></h4>
					</div>
				</div>
            </div>
			<?php
			$sql0 = "SELECT SUM(nominal) AS nominal FROM t_kas_besar WHERE tanggal='".$id."' AND tipe='IN'";
			$penjualan = Yii::$app->db->createCommand($sql0)->queryOne()['nominal'];
			$sql = "SELECT SUM(nominal) AS nominal FROM t_kas_besar WHERE tanggal='".$id."' AND tipe='OUT'";
			$setorbank = Yii::$app->db->createCommand($sql)->queryOne()['nominal'];
			$sql2 = "SELECT SUM(subtotal) AS nominal FROM t_uangtunai WHERE tanggal='".$id."' AND tipe = 'KB'";
			$uangtunai = Yii::$app->db->createCommand($sql2)->queryOne()['nominal'];
			$sql3 = "SELECT SUM(nominal) AS nominal FROM t_kas_bon WHERE tipe = 'KB' AND (status_bon!='PAID' OR status_bon IS NULL)";
			$kasbon = Yii::$app->db->createCommand($sql3)->queryOne()['nominal'];
			
			$penjualan = (!empty($penjualan)?$penjualan:0);
			$setorbank = (!empty($setorbank)?$setorbank:0);
			$uangtunai = (!empty($uangtunai)?$uangtunai:0);
			$kasbon = (!empty($kasbon)?$kasbon:0);
			?>
			<div class="modal-body">
				<div class="col-md-12" style="text-align: center; margin-top: -15px;"><h4>Closing Summary</h4></div><br><br>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-5 control-label">Total Penjualan :</label>
							<?php if($penjualan!=0){ ?>
							<div class="col-md-7" style="margin-top: -5px; margin-bottom: -10px;">
								<h4><a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoPenjualan('<?= $id ?>')" style="font-size: 1.4rem;">
								<?php echo app\components\DeltaFormatter::formatNumberForUserFloat($penjualan); ?>&nbsp;</a></h4>
							</div>
							<?php }else{ ?>
							<div class="col-md-7" style="margin-top: 5px; margin-bottom: -10px;">
								<span style='margin-top: 20px;'>0</span>
							</div>
							<?php } ?>
						</div>
						<div class="form-group">
							<label class="col-md-5 control-label">Setor Bank :</label>
							<div class="col-md-7" style="margin-top: -5px; margin-bottom: -10px;">
								<h4><a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoSetorBank('<?= $id ?>')" style="font-size: 1.4rem;">
								<?php echo app\components\DeltaFormatter::formatNumberForUserFloat($setorbank); ?>&nbsp;</a></h4>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-5 control-label">Uang Tunai :</label>
							<div class="col-md-7" style="margin-top: -5px; margin-bottom: -10px;">
								<h4><a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoUangtunai('<?= $id ?>')" style="font-size: 1.4rem;">
								<?php echo app\components\DeltaFormatter::formatNumberForUserFloat($uangtunai); ?>&nbsp;</a></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-5 control-label">Kasbon Sementara:</label>
							<div class="col-md-7" style="margin-top: -5px; margin-bottom: -10px;">
								<h4><a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoBonsementara('<?= $id ?>')" style="font-size: 1.4rem;">
								<?php echo app\components\DeltaFormatter::formatNumberForUserFloat($kasbon); ?>&nbsp;</a></h4>
							</div>
						</div>
					</div>
				</div>
				<br><br><br>
				<div class="row">
					<div class="col-md-12">
						<div style="font-size: 1.1rem"><b>Note:</b> <i>Mohon periksa kembali summary diatas dengan teliti sebelum anda menekan tombol Closing!</i></div>
					</div>
				</div>
			</div>
            <div class="modal-footer" style="text-align: center;">
				<?php 
					echo \yii\helpers\Html::button( Yii::t('app', 'CLOSING!'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'yes()']);
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

function yes(){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/closingConfirm','id'=>$id]); ?>',
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
	
function infoPenjualan(tgl){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/rekappraclosing','tgl'=>'']); ?>'+tgl+'&info=penjualan';
	$(".modals-place-2").load(url, function() {
		$("#modal-rekap").modal('show');
		$("#modal-rekap").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
function infoSetorBank(tgl){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/setorbank/Detailsetor','tgl'=>'']); ?>'+tgl;
	$(".modals-place-2").load(url, function() {
		$("#modal-setor").modal('show');
		$("#modal-setor").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
function infoUangtunai(tgl){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/rekappraclosing','tgl'=>'']); ?>'+tgl+'&info=uangtunai';
	$(".modals-place-2").load(url, function() {
		$("#modal-uangtunai").modal('show');
		$("#modal-uangtunai").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
function infoBonsementara(tgl){
	var url = '<?= \yii\helpers\Url::toRoute(['/kasir/kasbesar/rekappraclosing','tgl'=>'']); ?>'+tgl+'&info=kasbon';
	$(".modals-place-2").load(url, function() {
		$("#modal-kasbon").modal('show');
		$("#modal-kasbon").on('hidden.bs.modal', function () {});
		spinbtn();
	});
}
</script>