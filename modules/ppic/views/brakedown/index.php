<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Brakedown Sawmill';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); 
?>
<style>
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<span class="pull-right">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Brakedown Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Brakedown'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
                                    <div class="col-md-6">
                                        <?php 
										if(!isset($_GET['brakedown_id'])){ ?>
											<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);?>
										<?php }else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%; font-weight:bold;']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        <?php if(!isset($_GET['brakedown_id']) || isset($_GET['edit'])){ ?>
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode SPK'); ?></label>
                                                <div class="col-md-8">
                                                    <span class="input-group-btn" style="width: 100%">
                                                        <?= \yii\bootstrap\Html::activeDropDownList($model, 'spk_sawmill_id', $model->spk_sawmill_id ? [$model->spk_sawmill_id => $model->kode_spk] : [],['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;', 'onchange'=>'setSPK();']); ?>
                                                    </span>
                                                    <span class="input-group-btn" style="width: 20%">
                                                        <a class="btn btn-icon-only btn-default tooltips" onclick="openSPK();" data-original-title="Daftar SPK" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <?= $form->field($model, 'kode_spk')->textInput(['readonly'=>true])->label('Kode SPK'); ?>
                                        <?php } ?>
                                        <?= $form->field($model, 'kayu_id')->dropDownList(\app\models\MKayu::getOptionListNamaKayu(),['class'=>'form-control select2','prompt'=>'', 'onchange'=>"$('#table-detail tbody').empty(); addItem();", 'disabled'=>'disabled'])->label('Jenis Kayu'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'tanggal', [
                                                'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(); ?>
                                        <?= $form->field($model, 'line_sawmill')->dropDownList(\app\models\MDefaultValue::getOptionList('line-sawmill'),['prompt'=>'', 'disabled'=>'disabled']); ?>
                                        <?php if(isset($_GET['brakedown_id'])){ ?>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
                                                <div class="col-md-8" style="margin-top:7px;">
                                                    <?php 
                                                    if($model->cancel_transaksi_id){?>
                                                        <span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
                                                        <?php
                                                        $modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
                                                        echo "<br><span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena ".$modCancel->cancel_reason."</span>";
                                                        ?>
                                                    <?php } else { ?>
                                                        <!-- Button cancel hanya dibuka di hari yg sama saja -->
                                                        <?php if(date('Y-m-d') === $model->tanggal){?>
                                                            <a href="javascript:void(0);" onclick="cancelBrakedown(<?= $model->brakedown_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Brakedown'); ?></a>
                                                        <?php } else { ?>
                                                            <a href="javascript:void(0);" class="btn btn-outline btn-sm grey"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Brakedown'); ?></a>
                                                    <?php }
                                                    } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
								</div>
                                <br>
                                <div class="row">
                                    <h4><?= Yii::t('app', 'Detail Brakedown'); ?></h4>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th rowspan="2"><?= Yii::t('app', 'No. Lap<br>No. Barcode'); ?></th>
                                                        <th rowspan="2"><?= Yii::t('app', 'Grade'); ?></th>
                                                        <th rowspan="2"><?= Yii::t('app', 'Panjang<br>(m)'); ?></th>
                                                        <th colspan="2"><?= Yii::t('app', 'Ukuran Diameter (cm)'); ?></th>
                                                        <th colspan="3"><?= Yii::t('app', 'Ukuran Cacat (cm)'); ?></th>
                                                        <th rowspan="2"><?= Yii::t('app', 'Vol<br>(m<sup>3</sup>)'); ?></th>
                                                        <th rowspan="2" style="width: 30px;"></th>
                                                    </tr>
                                                    <tr>
                                                        <th><?= Yii::t('app', 'U1<br>U2'); ?></th>
                                                        <th><?= Yii::t('app', 'P1<br>P2'); ?></th>
                                                        <th><?= Yii::t('app', 'P'); ?></th>
                                                        <th><?= Yii::t('app', 'Gb'); ?></th>
                                                        <th><?= Yii::t('app', 'Gr'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													
												</tbody>
                                            </table>
                                        </div>
                                        <a class="btn btn-xs blue-hoki btn-outline" id="btn-add-item" onclick="addItem()"><i class="fa fa-plus"></i> Add Item</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Print'), ['id' => 'btn-print', 'class' => 'btn blue btn-outline ciptana-spin-btn', 'onclick' => 'printBreakdown(' . (isset($_GET['brakedown_id']) ? $_GET['brakedown_id'] : '') . ');', 'disabled' => true]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['brakedown_id'])){
    $pagemode = "afterSaveThis(". $_GET['brakedown_id'] .");";
}else {
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
    $('select[name*=\"[spk_sawmill_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode SPK Sawmill',
		ajax: {
			url: '".\yii\helpers\Url::toRoute('/ppic/brakedown/findSPK')."',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data,
                    edit: '". (isset($_GET['edit'])?$_GET['edit']:'') ."',
                    id: '". (isset($_GET['brakedown_id'])?$_GET['brakedown_id']:'') ."'
				};
			},
			cache: true
		}
	});
    $(this).find('select[name*=\"[kayu_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Kayu',
		width: null
	});
", yii\web\View::POS_READY); ?>
<script>
    function openSPK(){
        var edit = '<?= isset($_GET['edit'])?$_GET['edit']:'' ?>';
        var id = '<?= isset($_GET['brakedown_id'])?$_GET['brakedown_id']:''; ?>';
        $(".modals-place-3-min").load(
            '<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/openSPK']) ?>', 
            { id: id, edit: edit },
            function(response) {
                $("#modal-master .modal-dialog").css('width','90%');
                $("#modal-master").modal('show');
                $("#modal-master").on('hidden.bs.modal', function () {});
                spinbtn();
                draggableModal();
            }
        );

        // var url = '<?php //echo \yii\helpers\Url::toRoute(['/ppic/brakedown/openSPK', 'id'=>'']); ?>'+id+'&edit='+edit;
        // $(".modals-place-3-min").load(url, function() {
        //     $("#modal-master .modal-dialog").css('width','90%');
        //     $("#modal-master").modal('show');
        //     $("#modal-master").on('hidden.bs.modal', function () {});
        //     spinbtn();
        //     draggableModal();
        // });
    }

    function pick(spk_sawmill_id,kode){
        $("#modal-master").find('button.fa-close').trigger('click');
        $("#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>").empty().append('<option value="'+spk_sawmill_id+'">'+kode+'</option>').val(spk_sawmill_id).trigger('change');
    }

    function addItem(){
        var kayu_id = $('#<?= yii\bootstrap\Html::getInputId($model, "kayu_id") ?>').val();
        var notin = [];
        $('#table-detail > tbody > tr').each(function(){
            var no_lap_baru = $(this).find('select[name*="[no_lap_baru]"]');
            if( no_lap_baru.val() ){
                notin.push(no_lap_baru.val());
            }
        });
        if(notin){
            notin = JSON.stringify(notin);
        }
        if(kayu_id){
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/addItem']); ?>',
                type   : 'POST',
                data   : {},
                success: function (data) {
                    if(data.item){
                        $(data.item).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                            $(this).find('select[name*="[no_lap_baru]"]').select2({
                                allowClear: !0,
                                placeholder: 'Masukkan no. lap',
                                width: '150px',
                                ajax: {
                                    url: '<?= \yii\helpers\Url::toRoute('/ppic/brakedown/findNoLap') ?>',
                                    type: 'POST',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                        return {
                                            term: params.term,
                                            kayu_id: kayu_id,
                                            notin: notin
                                        };
                                    },
                                    processResults: function (data) {
                                        return {
                                            results: data
                                        };
                                    },
                                    cache: true
                                }
                            });
                            $(this).find('.select2-selection').css('font-size','1.2rem');
                            $(this).find('.select2-selection').css('padding-left','5px');
                            $(this).find(".tooltips").tooltip({ delay: 50 });
                            reordertable('#table-detail');
                        });
                    }
                },
                error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
            });
        } else {
            cisAlert('Pilih jenis kayu terlebih dahulu!');
        }
    }

    function setItem(ele){
        var no_lap = $(ele).val();
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/setItem']); ?>',
            type   : 'POST',
            data   : {no_lap:no_lap},
            success: function (data) {
                if(data){
                    $(ele).parents('tr').find('input[name*="[no_barcode_baru]"]').val(data.no_barcode_baru);
                    $(ele).parents('tr').find('input[name*="[grading_rule]"]').val(data.grading_rule?data.grading_rule:'-');
                    $(ele).parents('tr').find('input[name*="[panjang_baru]"]').val(data.panjang_baru);
                    $(ele).parents('tr').find('input[name*="[diameter_ujung1_baru]"]').val(data.diameter_ujung1_baru);
                    $(ele).parents('tr').find('input[name*="[diameter_ujung2_baru]"]').val(data.diameter_ujung2_baru);
                    $(ele).parents('tr').find('input[name*="[diameter_pangkal1_baru]"]').val(data.diameter_pangkal1_baru);
                    $(ele).parents('tr').find('input[name*="[diameter_pangkal2_baru]"]').val(data.diameter_pangkal2_baru);
                    $(ele).parents('tr').find('input[name*="[cacat_pjg_baru]"]').val(data.cacat_pjg_baru);
                    $(ele).parents('tr').find('input[name*="[cacat_gb_baru]"]').val(data.cacat_gb_baru);
                    $(ele).parents('tr').find('input[name*="[cacat_gr_baru]"]').val(data.cacat_gr_baru);
                    $(ele).parents('tr').find('input[name*="[volume_baru]"]').val(data.volume_baru);
                    hitungvol(ele);
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function hitungvol(ele){
        // hitung rata2 dulu
        var ujung1 = unformatNumber($(ele).parents('tr').find('input[name*="[diameter_ujung1_baru]"]').val());
        var ujung2 = unformatNumber($(ele).parents('tr').find('input[name*="[diameter_ujung2_baru]"]').val());
        var pangkal1 = unformatNumber($(ele).parents('tr').find('input[name*="[diameter_pangkal1_baru]"]').val());
        var pangkal2 = unformatNumber($(ele).parents('tr').find('input[name*="[diameter_pangkal1_baru]"]').val());
        var ratarata = Math.round((ujung1+ujung2+pangkal1+pangkal2)/4);

        //hitung volume
        var panjang = $(ele).parents('tr').find('input[name*="[panjang_baru]"]').val();
        var cacat_panjang = $(ele).parents('tr').find('input[name*="[cacat_pjg_baru]"]').val();
        var cacat_gb = $(ele).parents('tr').find('input[name*="[cacat_gb_baru]"]').val();
        var cacat_gr = $(ele).parents('tr').find('input[name*="[cacat_gr_baru]"]').val();

        panjang == '' ? panjang = 0 : panjang = parseFloat(panjang);
        ratarata == '' ? ratarata = 0 : ratarata = parseFloat(ratarata);
        cacat_panjang == '' ? cacat_panjang = 0 : cacat_panjang = parseFloat(cacat_panjang);
        cacat_gb == '' ? cacat_gb = 0 : cacat_gb = parseFloat(cacat_gb);
        cacat_gr == '' ? cacat_gr = 0 : cacat_gr = parseFloat(cacat_gr);

        var pGrowong = (0.7854 * cacat_gr * cacat_gr * (panjang - (cacat_panjang / 100)) / 10000).toFixed(2);
        pGrowong == '' ? pGrowong = 0 : pGrowong = pGrowong;
        var zzz = (0.7854 * (panjang - (cacat_panjang / 100)) * ((ratarata - cacat_gb) * (ratarata - (cacat_gb)) * 1) / 10000) - (pGrowong);
        // var Vol = ((zzz * 100) / 100).toFixed(2);
        var Vol = zzz.toFixed(2);
        $(ele).parents('tr').find('input[name*="[volume_baru]"]').val(Vol);
    }

    function openModalNoLap(ele){
        var tr_seq = $(ele).parents('tr').find('#no_urut').val();
        var kayu_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'kayu_id') ?>').val();
        var edit = '<?= isset($_GET['edit'])?$_GET['edit']:'' ?>';
        var id = '<?= isset($_GET['brakedown_id'])?$_GET['brakedown_id']:''; ?>';
        $(".modals-place-3-min").load(
            '<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/modalNoLap']) ?>', 
            { tr_seq:tr_seq, kayu_id:kayu_id, id: id, edit: edit },
            function(response) {
                $("#modal-master .modal-dialog").css('width','90%');
                $("#modal-master").modal('show');
                $("#modal-master").on('hidden.bs.modal', function () {});
                spinbtn();
                draggableModal();
            }
        );
        // var url = '<?php //echo \yii\helpers\Url::toRoute(['/ppic/brakedown/modalNoLap']); ?>?tr_seq='+tr_seq+'&kayu_id='+kayu_id;
        // $(".modals-place-3-min").load(url, function() {
        //     $("#modal-master .modal-dialog").css('width','75%');
        //     $("#modal-master").modal('show');
        //     $("#modal-master").on('hidden.bs.modal', function () {});
        //     spinbtn();
        //     draggableModal();
        // });
    }

    function pickNoLap(no_lap,tr_seq){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/setItem']); ?>',
            type   : 'POST',
            data   : {no_lap:no_lap},
            success: function (data) {
                if(data){
                    var already = [];
                    $('#table-detail > tbody > tr').each(function(){
                        var no_lap = $(this).find('select[name*="[no_lap_baru]"]');
                        if( no_lap.val() ){
                            already.push(no_lap.val());
                        }
                    });
                    
                    if( $.inArray(  data.no_lap_baru.toString(), already ) != -1 ){ // Jika ada yang sama
                        cisAlert("Produk ini sudah dipilih di list");
                        return false;
                    }else{
                        $("#modal-master").find('button.fa-close').trigger('click');
                        $("#table-detail > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[no_lap_baru]']").empty().append('<option value="'+data.no_lap_baru+'">'+data.no_lap_baru+'</option>').val(data.no_lap_baru).trigger('change');
                    }
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function save(){
        var form = $('#form-transaksi');

        var jumlah_item = $('#table-detail tbody tr').length;
        if (jumlah_item <= 0) {
            cisAlert('Isi detail terlebih dahulu');
        }

        if (validatingDetail()){
            submitform(form);
        }

        return false;
    }

    function validatingDetail(){
        var has_error = 0;

        var spk_sawmill_id = $("#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>");
        var kayu_id = $("#<?= yii\bootstrap\Html::getInputId($model, "kayu_id") ?>");
        var line_sawmill = $("#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>");

        if(!spk_sawmill_id.val()){
            $(spk_sawmill_id).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(spk_sawmill_id).parents('.form-group').removeClass('error-tb-detail');
        }
        if(!kayu_id.val()){
            $(kayu_id).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(kayu_id).parents('.form-group').removeClass('error-tb-detail');
        }
        if(!line_sawmill.val()){
            $(line_sawmill).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(line_sawmill).parents('.form-group').removeClass('error-tb-detail');
        }

        $('#table-detail tbody > tr').each(function(){
            var no_lap_baru = $(this).find('select[name*="[no_lap_baru]"]');
            var cacat_pjg_baru = $(this).find('input[name*="[cacat_pjg_baru]"]');
            var cacat_gb_baru = $(this).find('input[name*="[cacat_gb_baru]"]');
            var cacat_gr_baru = $(this).find('input[name*="[cacat_gr_baru]"]');

            if(!no_lap_baru.val()){
				$(this).find('select[name*="[no_lap_baru]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('select[name*="[no_lap_baru]"]').parents('td').removeClass('error-tb-detail');
			}
            if(!cacat_pjg_baru || cacat_pjg_baru.val() < 0){
				$(this).find('input[name*="[cacat_pjg_baru]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[cacat_pjg_baru]"]').parents('td').removeClass('error-tb-detail');
			}
            if(!cacat_gb_baru || cacat_gb_baru.val() < 0){
				$(this).find('input[name*="[cacat_gb_baru]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[cacat_gb_baru]"]').parents('td').removeClass('error-tb-detail');
			}
            if(!cacat_gr_baru || cacat_gr_baru.val() < 0){
				$(this).find('input[name*="[cacat_gr_baru]"]').parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			}else{
				$(this).find('input[name*="[cacat_gr_baru]"]').parents('td').removeClass('error-tb-detail');
			}
        });

        if(has_error === 0){
            return true;
        }
        return false;
    }

    function afterSaveThis(id){
        <?php if(!isset($_GET['edit'])){ ?>
            getItems(id);
            $('#btn-add-item').hide();
        <?php }else{ ?>
            getItems(id,1);
        <?php } ?>
        $('form').find('input').each(function(){ $(this).prop("disabled", true); });
        $('form').find('select').each(function(){ $(this).prop("disabled", true); });
        $('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $('#btn-save').attr('disabled','');
        $('#btn-print').removeAttr('disabled');
        <?php if(isset($_GET['edit'])){ ?>
            $('#btn-save').prop('disabled',false);
            $('#btn-print').prop('disabled',true);
            $('form').find('input').each(function(){ $(this).prop("disabled", false); });
            $('form').find('select').each(function(){ $(this).prop("disabled", false); });
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'kode') ?>").prop("disabled", true);
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'kayu_id') ?>").prop("disabled", true);
            $("#<?= \yii\bootstrap\Html::getInputId($model, 'line_sawmill') ?>").prop("disabled", true);
            $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
        <?php } ?>
    }

    function getItems(brakedown_id,edit=null){
        var kayu_id = $('#<?= yii\bootstrap\Html::getInputId($model, "kayu_id") ?>').val();
        var notin = [];
        $('#table-detail > tbody > tr').each(function(){
            var no_lap_baru = $(this).find('select[name*="[no_lap_baru]"]');
            if( no_lap_baru.val() ){
                notin.push(no_lap_baru.val());
            }
        });
        if(notin){
            notin = JSON.stringify(notin);
        }
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/getItems']); ?>',
            type   : 'POST',
            data   : {brakedown_id:brakedown_id,edit:edit},
            success: function (data) {
                if(data.html){
                    $('#table-detail > tbody').html(data.html);
                    $('#table-detail tbody > tr').each(function(){
                        $(this).find('select[name*="[no_lap_baru]"]').select2({
                            allowClear: !0,
							placeholder: 'Masukkan no. lap',
                            width: '150px',
                            ajax: {
                                url: '<?= \yii\helpers\Url::toRoute('/ppic/brakedown/findNoLap') ?>',
                                type: 'POST',
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    return {
                                        term: params.term,
                                        kayu_id: kayu_id,
                                        notin: notin
                                    };
                                },
                                processResults: function (data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: true
                            }
                        });
                        $(this).find('.select2-selection').css('font-size','1.2rem');
					    $(this).find('.select2-selection').css('padding-left','5px');
                    });
                }
                setTimeout(function(){
                    reordertable('#table-detail');
                },500);
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function daftarAfterSave(){
        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/daftarAfterSave']) ?>','modal-aftersave','90%');
    }

    function cancelBrakedown(brakedown_id){
        openModal('<?php echo yii\helpers\Url::toRoute(['/ppic/brakedown/cancelBrakedown']) ?>?id='+brakedown_id,'modal-transaksi');
    }

    function printBreakdown(id){
        var caraPrint = "PRINT";
        window.open("<?= yii\helpers\Url::toRoute(['/ppic/brakedown/printBrakedown', 'id' => '']) ?>" + id + "&caraprint=" + caraPrint, "", 'location=_new, width=1200px, scrollbars=yes');
    }

    function setSPK(){
        var spk_sawmill_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>').val();
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/setSPK']); ?>',
            type   : 'POST',
            data   : {spk_sawmill_id:spk_sawmill_id},
            success: function (data) {
                if(data){
                    $('#<?= yii\bootstrap\Html::getInputId($model, "kayu_id") ?>').val(data.kayu_id).trigger('change');
                    $('#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>').val(data.line_sawmill);
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
</script>