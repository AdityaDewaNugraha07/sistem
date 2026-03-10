<?php
app\assets\InputMaskAsset::register($this);
?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Pembayaran Piutang Customer'); ?></h4>
            </div>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'form-transaksi',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-6">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-5 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<?= \yii\bootstrap\Html::activeHiddenInput($model, "cust_id") ?>
						<?= \yii\bootstrap\Html::activeHiddenInput($model, "tanggal") ?>
						<?= \yii\bootstrap\Html::activeHiddenInput($model, "tanggal_bill") ?>
						<?= \yii\bootstrap\Html::activeHiddenInput($model, "tanggal_bayar") ?>
						<?= \yii\bootstrap\Html::activeHiddenInput($model, "custtop_top") ?>
                        <?= $form->field($model, "tipe")->dropDownList(app\models\MDefaultValue::getOptionList("destinasi-penjualan"),['disabled'=>true]); ?>
                        <?= $form->field($model, "cust_an_nama")->textInput(['disabled'=>'disabled'])->label("Customer"); ?>
                        <?= $form->field($model, "cust_an_nama")->textInput(['disabled'=>'disabled'])->label("Customer"); ?>
                        <?php
                        if($modCust->cust_tipe_penjualan == 'lokal'){
                            echo $form->field($model, "bill_reff")->dropDownList(app\models\TNotaPenjualan::getOptionListPayment($modCust->cust_id),['prompt'=>'','onchange'=>'setNota()']);
                        }else{
                            echo $form->field($model, "bill_reff")->dropDownList(app\models\TInvoice::getOptionListPaymentPiutang($modCust->cust_id),['prompt'=>'','onchange'=>'setInvoice()']);
                        }
                        ?>
						<?= $form->field($model, "nominal_bill")->textInput(['disabled'=>'disabled','class'=>'form-control float'])->label("Nominal Bill"); ?>
						<?= $form->field($model, "nominal_terbayar")->textInput(['disabled'=>'disabled','class'=>'form-control float'])->label("Pernah Terbayar"); ?>
						<?= $form->field($model, "tagihan")->textInput(['disabled'=>'disabled','class'=>'form-control float','style'=>'font-weight:700'])->label("<b>Sisa Tagihan</b>"); ?>
<!--						<div class="form-group" style="margin-bottom: 5px;">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Term of Payment'); ?></label>
							<div class="col-md-7">
								<div class="input-group" style="width: 85%;">
									<?php // echo \yii\bootstrap\Html::activeTextInput($model, 'custtop_top',['class'=>'form-control float','readonly'=>'readonly']) ?>
									<span class="input-group-addon" style="padding-left: 5px; padding-right: 5px;">Hari</span>
								</div>
							</div>
						</div>-->
                    </div>
					<div class="col-md-6">
						<?= $form->field($model, "cara_bayar")->dropDownList(['Transfer'=>'Transfer Bank','Cash'=>'Cash','BgCek'=>'BG/Cek','Retur'=>'Retur','CN'=>'Credit Note','Potongan'=>'Potongan','BiayaBank'=>'BiayaBank'],['onchange'=>'setCaraBayar()']); ?>
						<div class="form-group">
							<label class="col-md-5 control-label"><?php echo Yii::t('app', 'Kode Pembayaran'); ?></label>
							<div class="col-md-6" style="margin-bottom: 5px;">
								<span class="input-group-btn" style="width: 100%">
									<?= $form->field($model, 'payment_reff',['template'=>'{input}','options' => ['style' => 'margin-left: -3px; margin-right: -3px;']])
													->dropDownList([],['prompt'=>'','onchange'=>'setPaymentReff()','style'=>'width:100%;']); ?>
								</span>
								<span class="input-group-btn">
									<a class="btn btn-icon-only btn-default tooltips" onclick="openListPenerimaan();" data-original-title="List Penerimaan" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
								</span>
							</div>
						</div>
						<div class="form-group" style="display: none;">
							<label class="col-md-5 control-label"><?php echo Yii::t('app', 'Catatan'); ?></label>
							<div class="col-md-6" style="margin-bottom: 5px;">
								<?php echo yii\bootstrap\Html::activeTextInput($model, 'keterangan', ['class'=>'form-control']) ?>
							</div>
						</div>
						<?php echo $form->field($model, 'mata_uang')->dropDownList(\app\models\MDefaultValue::getOptionList('mata-uang'),['disabled'=>'disabled']) ?>
						<?= $form->field($model, "nominal_terima")->textInput(['disabled'=>'disabled','class'=>'form-control float'])->label("Nominal Terima"); ?>
						<?= $form->field($model, "nominal_terpakai")->textInput(['disabled'=>'disabled','class'=>'form-control float'])->label("Pernah Terpakai"); ?>
						<?= $form->field($model, "bayar")->textInput(['disabled'=>'disabled','class'=>'form-control float','style'=>'font-weight:700'])->label("<b>Sisa Bayar</b>"); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'save(this)'
                    ]);
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
setCaraBayar();
$.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script>
function setNota(){
	var bill_reff = $("#<?= yii\bootstrap\Html::getInputId($model, "bill_reff") ?>").val();
	$("#<?= yii\bootstrap\Html::getInputId($model, "nominal_bill") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "nominal_terbayar") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "tagihan") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "custtop_top") ?>").val("0");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/setNota']); ?>',
		type   : 'POST',
		data   : {bill_reff:bill_reff},
		success: function (data) {
			if(data.nota){
				$("#<?= yii\bootstrap\Html::getInputId($model, "nominal_bill") ?>").val(data.nota.nominal_bill);
				$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_bill") ?>").val(data.nota.tanggal);
				$("#<?= yii\bootstrap\Html::getInputId($model, "nominal_terbayar") ?>").val(data.nota.pernah_terbayar);
				$("#<?= yii\bootstrap\Html::getInputId($model, "tagihan") ?>").val(data.nota.tagihan);
				$("#<?= yii\bootstrap\Html::getInputId($model, "custtop_top") ?>").val(data.nota.custtop_top);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
	$("#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>").empty().trigger('change');
}

function setInvoice(){
	var bill_reff = $("#<?= yii\bootstrap\Html::getInputId($model, "bill_reff") ?>").val();
	$("#<?= yii\bootstrap\Html::getInputId($model, "nominal_bill") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "nominal_terbayar") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "tagihan") ?>").val("0");
	$("#<?= yii\bootstrap\Html::getInputId($model, "custtop_top") ?>").val("0");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/setInvoice']); ?>',
		type   : 'POST',
		data   : {bill_reff:bill_reff},
		success: function (data) {
			if(data.invoice){
				$("#<?= yii\bootstrap\Html::getInputId($model, "nominal_bill") ?>").val(data.invoice.nominal_bill);
				$("#<?= yii\bootstrap\Html::getInputId($model, "tanggal_bill") ?>").val(data.invoice.tanggal);
				$("#<?= yii\bootstrap\Html::getInputId($model, "nominal_terbayar") ?>").val(data.invoice.pernah_terbayar);
				$("#<?= yii\bootstrap\Html::getInputId($model, "tagihan") ?>").val(data.invoice.tagihan);
				$("#<?= yii\bootstrap\Html::getInputId($model, "custtop_top") ?>").val(data.invoice.custtop_top);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
	$("#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>").empty().trigger('change');
}

function setCaraBayar(){
	$('#<?= \yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').addClass("animation-loading");
	$("#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>").empty().trigger('change');
	var cara_bayar = $('#<?= \yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>').val();
    var tipe = $('#<?= \yii\bootstrap\Html::getInputId($model, "tipe") ?>').val();
	if(cara_bayar == "Transfer"){
        if(tipe == "lokal"){
            var par = 'IDR';
        }else if(tipe == "export"){
            var par = 'USD';
        }
		$('#<?= \yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').select2({
			allowClear: !0,
			placeholder: 'Ketik Kode Voucher',
			ajax: {
				url: '<?= \yii\helpers\Url::toRoute('/finance/piutangpenjualan/findVoucherPenerimaan'); ?>?tipe='+par,
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});
	}else if(cara_bayar == "Cash"){
		$('#<?= \yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').select2({
			allowClear: !0,
			placeholder: 'Ketik Kode Kas Besar',
			ajax: {
				url: '<?= \yii\helpers\Url::toRoute('/finance/piutangpenjualan/findKasBesar'); ?>',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});
	}else if(cara_bayar == "BgCek"){
		$('#<?= \yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').select2({
			allowClear: !0,
			placeholder: 'Ketik Kode LPG',
			ajax: {
				url: '<?= \yii\helpers\Url::toRoute('/finance/piutangpenjualan/findLPG'); ?>',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});
	}else if(cara_bayar == "Retur"){
		$('#<?= \yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').select2({
			allowClear: !0,
			placeholder: 'Ketik Kode Retur',
			ajax: {
				url: '<?= \yii\helpers\Url::toRoute('/finance/piutangpenjualan/FindRetur'); ?>',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});
	}
	setTimeout(function(){
		$('#<?= \yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').removeClass("animation-loading");
	},500)
}

function openListPenerimaan(){
	var cara_bayar = $('#<?= \yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>').val();
	var tipe = $('#<?= \yii\bootstrap\Html::getInputId($model, "tipe") ?>').val();
	$(".modals-place-2").html("");
	if(cara_bayar == "Transfer"){
        if(tipe == "lokal"){
            var url = '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/openVoucher']); ?>';
        }else if(tipe == "export"){
            var url = '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/openVoucherDollar']); ?>';
        }
	}else if(cara_bayar == "Cash"){
		var url = '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/openKasbesar']); ?>';
	}else if(cara_bayar == "BgCek"){
		var url = '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/openBgcek']); ?>';
	}else if(cara_bayar == "Retur"){
		var url = '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/openRetur']); ?>';
	}
	$(".modals-place-2").load(url, function() {
		$("#modal-info .modal-dialog").css('width','90%');
		$("#modal-info").modal('show');
		$("#modal-info").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function pickVoucher(kode){
	$("#modal-info").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>").empty().append('<option value="'+kode+'">'+kode+'</option>').val(kode).trigger('change');
}
function pickKasbesar(kode){
	$("#modal-info").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>").empty().append('<option value="'+kode+'">'+kode+'</option>').val(kode).trigger('change');
}
function pickBgcek(kode){
	$("#modal-info").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>").empty().append('<option value="'+kode+'">'+kode+'</option>').val(kode).trigger('change');
}

function setPaymentReff(){
	var payment_reff = $('#<?= \yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').val();
	var cara_bayar = $('#<?= \yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>').val();
	var tagihan = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($model, "jumlah_tagihan") ?>').val() );
	var sisa_tagihan = unformatNumber( $('#<?= \yii\bootstrap\Html::getInputId($model, "tagihan") ?>').val() );
	var nominal_terima = 0, nominal_terpakai=0, terbayar = 0;
	var tanggal = $('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal") ?>').val();
	if(cara_bayar == "CN"){
		$('#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').parents(".form-group").attr('style','display:none;');
		$('#<?= yii\bootstrap\Html::getInputId($model, "keterangan") ?>').parents(".form-group").attr('style','display:;');
		$('#<?= yii\bootstrap\Html::getInputId($model, "keterangan") ?>').attr("placeholder","Catatan Credit Note");
		$('#<?= \yii\bootstrap\Html::getInputId($model, "bayar") ?>').removeAttr("disabled");
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_bayar") ?>').val( tanggal );
	}else if(cara_bayar == "Potongan"){
		$('#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').parents(".form-group").attr('style','display:none;');
		$('#<?= yii\bootstrap\Html::getInputId($model, "keterangan") ?>').parents(".form-group").attr('style','display:;');
		$('#<?= yii\bootstrap\Html::getInputId($model, "keterangan") ?>').attr("placeholder","Catatan Potongan");
		$('#<?= \yii\bootstrap\Html::getInputId($model, "bayar") ?>').removeAttr("disabled");
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_bayar") ?>').val( tanggal );
	}else if(cara_bayar == "BiayaBank"){
		$('#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').parents(".form-group").attr('style','display:none;');
		$('#<?= yii\bootstrap\Html::getInputId($model, "keterangan") ?>').parents(".form-group").attr('style','display:;');
		$('#<?= yii\bootstrap\Html::getInputId($model, "keterangan") ?>').attr("placeholder","Catatan Biaya Bank");
		$('#<?= \yii\bootstrap\Html::getInputId($model, "bayar") ?>').removeAttr("disabled");
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_bayar") ?>').val( tanggal );
	}else{
		$('#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').parents(".form-group").attr('style','display:;');
		$('#<?= yii\bootstrap\Html::getInputId($model, "keterangan") ?>').parents(".form-group").attr('style','display:none;');
		$('#<?= \yii\bootstrap\Html::getInputId($model, "bayar") ?>').attr("disabled","disabled");
		$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_bayar") ?>').val( "" );
		if(cara_bayar == "Retur"){
			$('#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').closest(".form-group").find("label").html("Kode Retur");
		}else{
			$('#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>').closest(".form-group").find("label").html("Kode Pembayaran");
		}
	}
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/setPaymentReff']); ?>',
		type   : 'POST',
		data   : {payment_reff:payment_reff,cara_bayar:cara_bayar},
		success: function (data) {
			if(data){
				nominal_terima = data.nominal_terima;
				nominal_terpakai = data.nominal_terpakai;
				$('#<?= \yii\bootstrap\Html::getInputId($model, "mata_uang") ?>').val( data.mata_uang );
				if(data.tanggal_bayar){
					$('#<?= \yii\bootstrap\Html::getInputId($model, "tanggal_bayar") ?>').val( data.tanggal_bayar );
				}
			}
			var terbayar = nominal_terima - nominal_terpakai;
			if(terbayar > sisa_tagihan){
				terbayar = sisa_tagihan;
			}
			$('#<?= \yii\bootstrap\Html::getInputId($model, "nominal_terima") ?>').val( formatNumberForUser(nominal_terima) );
			$('#<?= \yii\bootstrap\Html::getInputId($model, "nominal_terpakai") ?>').val( formatNumberForUser(nominal_terpakai) );
			$('#<?= \yii\bootstrap\Html::getInputId($model, "bayar") ?>').val( formatNumberForUser(terbayar) );
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function save(ele){
	var cara_bayar = $('#<?= \yii\bootstrap\Html::getInputId($model, "cara_bayar") ?>').val();
	$('#<?= yii\bootstrap\Html::getInputId($model, "keterangan") ?>').removeClass("error-tb-detail");
	if(cara_bayar == "CN" || cara_bayar == "Potongan" || cara_bayar == "BiayaBank"){
		if(!$('#<?= yii\bootstrap\Html::getInputId($model, "keterangan") ?>').val()){
			$('#<?= yii\bootstrap\Html::getInputId($model, "keterangan") ?>').addClass("error-tb-detail");
		}else{
			$("#<?= yii\bootstrap\Html::getInputId($model, "payment_reff") ?>").empty().append('<option value="'+cara_bayar+'">'+cara_bayar+'</option>').val(cara_bayar).trigger('change');
		}
	}
	submitformajax(ele,"$(\'#modal-transaksi\').modal(\'hide\'); ");
}
</script>