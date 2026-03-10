<?php 
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-transaksi',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-6">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-5 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', "SPK Grader"); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<?= $form->field($model, 'kode')->textInput(['style'=>'width:200px;','readonly'=>true])->label(Yii::t('app', 'Kode Dinas')); ?>
						<?= $form->field($model, 'tanggal',[
                            'template'=>'{label}<div class="col-md-6"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
								<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                        <?= $form->field($model, 'jenis_log')->dropDownList(["LA"=>"LOG ALAM","LS"=>"LOG SENGON","LJ"=>"LOG JABON"],['class'=>'form-control',"prompt"=>'','onchange'=>'setTipeDinas()']); ?>
                        <div class="form-group" style="margin-top: 7px; margin-bottom: 7px;">
							<?= \yii\bootstrap\Html::activeLabel($model, 'tipe', ['class'=>'col-md-5 control-label', 'style'=>'top: -5px;']); ?>
							<div class="col-md-6">
								<?php echo yii\helpers\Html::activeRadioList($model, 'tipe', \app\models\MDefaultValue::getOptionList('tipe-dinas-grader'),['separator' => ' &nbsp; &nbsp;', 'tabindex' => 3, 'onchange'=>'setTipeDinas();']); ?>
							</div>
						</div>
						<?php /* t_pmr */?>
                        <?php /* dropdown
                        <?= $form->field($modPmr, 'hasil_orientasi_id')->dropDownList(\app\models\THasilOrientasi::getOptionListPO(),['class'=>'form-control select2','prompt'=>'All']); ?>
                        */ ?>

                        <?php /* t_pengajuan_pembelianlog */?>
                        <?php /* dropdown
                        <?= $form->field($modPengajuanPembelianlog, 'pengajuan_pembelianlog_id')->dropDownList(\app\models\TPengajuanPembelianlog::getOptionListLoglist(),['class'=>'form-control select2','prompt'=>'All']); ?>
                        */ ?>
                        <div class="form-group" style="margin-top: 7px; margin-bottom: 7px;">
                            <div class="col-md-5"></div>
                            <div class="col-md-6">
                                <a class="btn btn-xs blue" id="btn-add-open-pmr" onclick="setTipeDinas(); openPMR()"><i class="fa fa-plus"></i> Permintaan Pembelian Log</a>
                                <a class="btn btn-xs blue" id="btn-add-open-pengajuan-pembelianlog" onclick="setTipeDinas(); openPengajuanPembelianlog()"><i class="fa fa-plus"></i> Keputusan Pembelian Log</a>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 7px; margin-bottom: 7px;">
                            <label id="labelX" class="col-md-5 text-right"></label>
                            <div class="col-md-6">
                                <table id="contentX"></table>
                            </div>
                        </div>
                    </div>
					<div class="col-md-6">
                        <?= $form->field($model, 'graderlog_id')->dropDownList(\app\models\MGraderlog::getOptionList2($grader_aktif),['id'=>'GLO','class'=>'form-control select2','prompt'=>'All']); ?>
                        <?= $form->field($model, 'graderlog_id')->dropDownList(\app\models\MGraderlog::getOptionList3($grader_aktif,"GLA"),['id'=>'GLA','class'=>'form-control select2','prompt'=>'All']); ?>
                        <?= $form->field($model, 'graderlog_id')->dropDownList(\app\models\MGraderlog::getOptionList3($grader_aktif,"GLS"),['id'=>'GLS','class'=>'form-control select2','prompt'=>'All']); ?>
                        <?= $form->field($model, 'graderlog_id')->dropDownList(\app\models\MGraderlog::getOptionList3($grader_aktif,"GLJ"),['id'=>'GLJ','class'=>'form-control select2','prompt'=>'All']); ?>
						<?= $form->field($model, 'wilayah_dinas_id')->dropDownList(\app\models\MWilayahDinas::getOptionList(),['class'=>'form-control select2','prompt'=>'All']); ?>
						<?= $form->field($model, 'tujuan')->textInput()->label(Yii::t('app', 'Tujuan PT')); ?>
						<?= $form->field($model, 'keterangan')->textarea(); ?>
					</div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'save();'
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
$('.field-GLA').hide();
$('.field-GLS').hide();
$('.field-GLJ').hide();
$('#btn-add-open-pmr').hide();
$('#btn-add-open-pengajuan-pembelianlog').hide();
$('#".yii\bootstrap\Html::getInputId($model, 'graderlog_id')."').select2({
	allowClear: !0,
	placeholder: 'Pilih Nama Grader',
	width: null
});
$('#".yii\bootstrap\Html::getInputId($model, 'wilayah_dinas_id')."').select2({
	allowClear: !0,
	placeholder: 'Pilih Wilayah Dinas',
	width: null
});
$.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script>
    function save() {
        //submitformajax('#form-transaksi',"$(\'#close-btn-modal\').removeAttr(\'disabled\'); $(\'#close-btn-modal\').trigger(\'click\'); getItems();");
        var jenis_log = $('#tdkg-jenis_log').val();
        if (jenis_log == "LA") {
            $(".field-GLO").remove();
            $(".field-GLS").remove();
            $(".field-GLJ").remove();
            var tipe_dinas = $('input[name="TDkg[tipe]"]:checked').val();
            var pmr_id = $("#form-transaksi").find("input[name*='pmr_id']").val();
            var pengajuan_pembelianlog_id = $("#form-transaksi").find("input[name*='pengajuan_pembelianlog_id']").val();
            var graderlog_id = $("#GLA").val();
            var wilayah_dinas_id = $("#tdkg-wilayah_dinas_id").val();
            var tujuan = $("#tdkg-tujuan").val();
            if (tipe_dinas == "ORIENTASI") {
                if (pmr_id > 0) {
                    if (graderlog_id > 0) {
                        if (wilayah_dinas_id > 0) {
                            if (tujuan != '') {
                                var submit = 1;
                            } else {
                                cisAlert("Tujuan PT belum diinput");
                            }
                        } else {
                            cisAlert("Wilayah dinas belum diinput");
                        }
                    } else {
                        cisAlert('Grader belum diinput');
                    }
                } else {
                    cisAlert('Kode Permintaan Pembelian Log belum diinput');
                }
            }

            if (tipe_dinas == "GRADING") {
                if (pengajuan_pembelianlog_id > 0) {
                    if (graderlog_id > 0) {
                        if (wilayah_dinas_id > 0) {
                            if (tujuan != '') {
                                var submit = 1;
                            } else {
                                cisAlert("Tujuan PT belum diinput");
                            }
                        } else {
                            cisAlert("Wilayah dinas belum diinput");
                        }
                    } else {
                        cisAlert('Grader belum diinput');
                    }
                } else {
                    cisAlert('Kode pengajuan pembelian belum diinput');
                }
            }

        } else if (jenis_log == "LS") {
            $(".field-GLO").remove();
            $(".field-GLA").remove();
            $(".field-GLJ").remove();
            var graderlog_id = $("#GLS").val();
            if (graderlog_id > 0) {
                var submit = 1;
            }
        } else if (jenis_log == "LJ") {
            $(".field-GLO").remove();
            $(".field-GLA").remove();
            $(".field-GLS").remove();
            var graderlog_id = $("#GLJ").val();
            if (graderlog_id > 0) {
                var submit = 1;
            }
        } else {
            cisAlert('Jenis log belum diinput');
        }

        if (submit > 0) {
            var $form = $('#form-transaksi');
            submitformajax($form);
            $('#close-btn-modal').removeAttr('disabled');
            $('#close-btn-modal').trigger('click'); 
            getItems();
        }
    }

    function setTipeDinas() {
        var jenis_log = $('#tdkg-jenis_log').val();
        var tipe_dinas = $('input[name="TDkg[tipe]"]:checked').val();
        if (jenis_log == "LA" && tipe_dinas == "ORIENTASI") {
            $('#btn-add-open-pmr').show();
            $('#btn-add-open-pengajuan-pembelianlog').hide();
            //$('#contentX').html('');
            if ($('#labelX').text() == 'Kode Keputusan Pembelian Log') {
                $('#contentX').html('');
            }
            $('#labelX').html('Kode Permintaan Pembelian Log');
        } else if (jenis_log == "LA" && tipe_dinas == "GRADING") {
            $('#btn-add-open-pmr').hide();
            $('#btn-add-open-pengajuan-pembelianlog').show();
            /*if ($('#labelX').text() == 'Kode Pengajuan Pembelian Log') {
                $('#contentX').html('');
            }*/
            $('#contentX').html('');
            $('#labelX').html('Kode Keputusan Pembelian Log');
        } else {
            $('#btn-add-open-pmr').hide();
            $('#btn-add-open-pengajuan-pembelianlog').hide();
            $('#labelX').html('');
        }

        if (jenis_log == "LA") {
            $(".field-GLO").hide();
            $(".field-GLA").show();
            $(".field-GLS").hide();
            $(".field-GLJ").hide();
        } else if (jenis_log == "LS") {
            $(".field-GLO").hide();
            $(".field-GLA").hide();
            $(".field-GLS").show();
            $(".field-GLJ").hide();
        } else {
            $(".field-GLO").hide();
            $(".field-GLA").hide();
            $(".field-GLS").hide();
            $(".field-GLJ").show();
        }
    }

    //===

    /*function openPMR(){
        var jenis_log = $('#tdkg-jenis_log').val();
        openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/openPMR']) ?>','modal-madul','90%');
    }

    function pickPMR(id, kode) {
        $("#modal-madul").find('button.fa-close').trigger('click');
        $('#contentX').append('<tr><td><input type="hidden" name="pmr_id" value="'+id+'">'+kode+' <a class="btn btn-xs red" onclick="cancelPMR();"><i class="fa fa-remove"></i></a></td></tr>');
        $('#btn-add-open-pmr').hide();
    }

    function cancelPMR() {
        $('#contentX').html('');
        $('#btn-add-open-pmr').show();
    }*/

    function openPMR(){
        openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/openPMR']) ?>','modal-madul','90%');
    }

    function pickPMR(id,kode){
	    $("#modal-madul").find('button.fa-close').trigger('click');
        var xxx = $('#contentX').find('input[name*="pmr_id"]');
        var i = (xxx.length);
        var no = i + 1;
        if (i == 0) {
            $('#contentX').append('<tr><td><input type="hidden" name="pmr_id[]" value="'+id+'"> - '+kode+' <a class="xxx btn btn-xs red" onclick="cancelPMR('+i+');"><i class="fa fa-remove"></i></a></td></tr>');
        } else {
            var i = (xxx.length);
            var no = i + 1;
            var allowAdd = true;
            $('#contentX > tbody > tr').each(function(){
                if($(this).find("input[name*='pmr_id']").val() != id){
                    allowAdd &= true;
                }else{
                    allowAdd = false;
                }
            });
            if (allowAdd) {
                $('#contentX').append('<tr><td><input type="hidden" name="pmr_id[]" value="'+id+'"> - '+kode+' <a class="xxx btn btn-xs red" onclick="cancelPMR('+i+');"><i class="fa fa-remove"></i></a></td></tr>');
            } else {
                cisAlert('Data sudah dipilih');
            }
        }
    }

    function cancelPMR() {
        $("#contentX").on("click", ".xxx", function() {
            $(this).closest("tr").remove();
        });
    }

    //===

    /*function openPengajuanPembelianlog(){
        openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/openPengajuanPembelianlog']) ?>','modal-madul','90%');
    }

    function pickPengajuanPembelianlog(id,kode){
	    $("#modal-madul").find('button.fa-close').trigger('click');
        var xxx = $('#contentX').find('input[name*="pengajuan_pembelianlog_id"]');
        var i = (xxx.length);
        var no = i + 1;
        if (i == 0) {
            $('#contentX').append('<tr><td><input type="hidden" name="pengajuan_pembelianlog_id[]" value="'+id+'"> - '+kode+' <a class="xxx btn btn-xs red" onclick="cancelPengajuanPembelianlog('+i+');"><i class="fa fa-remove"></i></a></td></tr>');
        } else {
            var i = (xxx.length);
            var no = i + 1;
            var allowAdd = true;
            $('#contentX > tbody > tr').each(function(){
                if($(this).find("input[name*='pengajuan_pembelianlog_id']").val() != id){
                    allowAdd &= true;
                }else{
                    allowAdd = false;
                }
            });
            if (allowAdd) {
                $('#contentX').append('<tr><td><input type="hidden" name="pengajuan_pembelianlog_id[]" value="'+id+'"> - '+kode+' <a class="xxx btn btn-xs red" onclick="cancelPengajuanPembelianlog('+i+');"><i class="fa fa-remove"></i></a></td></tr>');
            } else {
                cisAlert('Data sudah dipilih');
            }
        }
    }

    function cancelPengajuanPembelianlog() {
        $("#contentX").on("click", ".xxx", function() {
            $(this).closest("tr").remove();
        });
    }*/

    function openPengajuanPembelianlog(){
        var jenis_log = $('#tdkg-jenis_log').val();
        openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/openPengajuanPembelianlog']) ?>','modal-madul','90%');
    }

    function pickPengajuanPembelianlog(id, kode) {
        $("#modal-madul").find('button.fa-close').trigger('click');
        $('#contentX').append('<tr><td><input type="hidden" name="pengajuan_pembelianlog_id" value="'+id+'">'+kode+' <a class="btn btn-xs red" onclick="cancelPengajuanPembelianlog();"><i class="fa fa-remove"></i></a></td></tr>');
        $('#btn-add-open-pengajuan-pembelianlog').hide();
    }

    function cancelPengajuanPembelianlog() {
        $('#contentX').html('');
        $('#btn-add-open-pengajuan-pembelianlog').show();
    }
</script>