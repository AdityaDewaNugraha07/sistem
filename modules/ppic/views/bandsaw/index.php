<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Bandsaw Sawmill';
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
.highlightable:focus {
    background-color: #cce5ff !important; 
    border-color: #66b0ff !important;
}

</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<span class="pull-right">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Bandsaw Yang Telah Dibuat'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Bandsaw'); ?></h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
                                    <div class="col-md-6">
                                        <?php 
										if(!isset($_GET['bandsaw_id'])){ ?>
											<?= $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);?>
										<?php }else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%; font-weight:bold;', 'disabled'=>'']) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        <?php if(!isset($_GET['bandsaw_id']) || isset($_GET['edit'])){ ?>
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode SPK'); ?></label>
                                                <div class="col-md-8">
                                                    <span class="input-group-btn" style="width: 100%">
                                                        <?php
                                                        echo \yii\bootstrap\Html::activeDropDownList($model, 'spk_sawmill_id', $model->spk_sawmill_id ? [$model->spk_sawmill_id => $model->kode_spk] : [],['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;', 'onchange'=>'setDetail('. (isset($_GET['edit'])?$_GET['edit']:null) .');']); 
                                                        ?>
                                                    </span>
                                                    <span class="input-group-btn" style="width: 20%">
                                                        <a class="btn btn-icon-only btn-default tooltips" id="btn-spk" onclick="openSPK();" data-original-title="Daftar SPK" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <?= $form->field($model, 'kode_spk')->textInput(['readonly'=>true])->label('Kode SPK'); ?>
                                            <?= $form->field($model, 'spk_sawmill_id')->hiddenInput()->label(false); ?>
                                        <?php } ?>
                                        <?php // $form->field($model, 'nomor_bandsaw[]')->checkboxList(\app\models\MDefaultValue::getOptionList('nomor-bandsaw'),
                                        //     ['item' => function($index, $label, $name, $checked, $value) {
                                        //             return '<label style="display:inline-block; margin-right:15px;">'
                                        //                 . yii\helpers\Html::checkbox($name, $checked, ['value' => $value,'onchange' => "setDetail()"])
                                        //                 . ' ' . $label . '</label>';},
                                        //     ]
                                        // ); ?>
                                        <?php echo $form->field($model, 'nomor_bandsaw[]')->checkboxList(\app\models\MDefaultValue::getOptionList('nomor-bandsaw'),
                                            [
                                                'value' => $checkedBandsaw,
                                                'item' => function($index, $label, $name, $checked, $value) {
                                                    return '<label style="display:inline-block; margin-right:15px;">'
                                                        . yii\helpers\Html::checkbox($name, $checked, [
                                                            'value' => $value,
                                                            'onchange' => "setDetail(". (isset($_GET['edit'])?$_GET['edit']:null) .")"
                                                        ])
                                                        . ' ' . $label . '</label>';
                                                },
                                            ]
                                        ); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'line_sawmill')->dropDownList(\app\models\MDefaultValue::getOptionList('line-sawmill'),['prompt'=>'', 'disabled'=>'']); ?>
                                        <?= $form->field($model, 'tanggal', [
                                                'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;" disabled><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['disabled'=>'']); ?>
                                        
                                        <?php if(isset($_GET['bandsaw_id'])){ ?>
                                            <!-- <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
                                                <div class="col-md-8" style="margin-top:7px;">
                                                    <?php 
                                                    if($model->cancel_transaksi_id){?>
                                                        <span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
                                                        <?php
                                                        $modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
                                                        echo "<br><span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena ".$modCancel->cancel_reason."</span>";
                                                        ?>
                                                    <?php } else {
                                                        // if($model->approval_status == 'Not Confirmed'){ ?>
                                                        <a href="javascript:void(0);" onclick="cancelBandsaw(<?= $model->bandsaw_id ?>);" class="btn red btn-sm btn-outline"><i class="fa fa-close"></i> <?= Yii::t('app', 'Batalkan Bandsaw'); ?></a>
                                                    <?php //}
                                                    }?>
                                                </div>
                                            </div> -->
                                        <?php } ?>
                                    </div>
								</div>
                                <br>
                                <div class="row">
                                    <h4><?= Yii::t('app', 'Detail Bandsaw'); ?></h4>
                                    <?php 
                                    if(isset($_GET['bandsaw_id'])){
                                        $details = \app\models\TBandsawDetail::find()
                                                    ->select('nomor_bandsaw')->where(['bandsaw_id' => $model->bandsaw_id])
                                                    ->groupBy('nomor_bandsaw')->orderBy(['nomor_bandsaw' => SORT_ASC])
                                                    ->asArray()
                                                    ->all();?>
                                            <ul class="nav nav-tabs">
                                                <?php 
                                                foreach($details as $d => $detail){ 
                                                    $nomor = $detail['nomor_bandsaw'];

                                                    if($detail['nomor_bandsaw'] == '1'){ $color = 'red'; } 
                                                    else if($detail['nomor_bandsaw'] == '2'){ $color = 'green'; } 
                                                    else if($detail['nomor_bandsaw'] == '3'){ $color = '#778899'; } 
                                                    else if($detail['nomor_bandsaw'] == '4'){ $color = 'blue'; } 
                                                    else if($detail['nomor_bandsaw'] == '5'){ $color = 'orange'; } 
                                                    else if($detail['nomor_bandsaw'] == '6'){ $color = 'purple'; } 
                                                    else if($detail['nomor_bandsaw'] == '7'){ $color = '#A52A2A'; } 
                                                    else if($detail['nomor_bandsaw'] == '8'){ $color = '#FF1493'; } 
                                                    else if($detail['nomor_bandsaw'] == '9'){ $color = '#DAA520'; }

                                                    $isActive = ($d == 0)?'active':'';
                                                ?>
                                                    <li class="tab-bandsaw <?= $isActive; ?>">
                                                        <a href="javascript:void(0);" class="nav-link" onclick="loadByNomor(this, <?= $model->spk_sawmill_id ?>, <?= $model->bandsaw_id ?>, <?= $nomor ?>)" style="color: <?= $color ?>; font-weight: bold;"> <?= Yii::t('app', $nomor); ?> </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                    <?php } ?>
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-detail" style="display: none;"><!-- style="display: none;"  -->
                                                <thead>
                                                    <tr>
                                                        <!-- <th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th> -->
                                                        <th><?= Yii::t('app', 'No.<br>Bandsaw'); ?></th>
                                                        <th><?= Yii::t('app', 'Size'); ?></th>
                                                        <th><?= Yii::t('app', 'Panjang'); ?></th>
                                                        <th><?= Yii::t('app', 'Qty'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													
												</tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Print'), ['id' => 'btn-print', 'class' => 'btn blue btn-outline ciptana-spin-btn', 'onclick' => 'printBandsaw(' . (isset($_GET['bandsaw_id']) ? $_GET['bandsaw_id'] : '') . ');', 'disabled' => true]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Mulai Input'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Close'),['id'=>'btn-close','class'=>'btn hijau btn-outline ciptana-spin-btn', 'style'=>'display: none;', 'onclick' => "resetForm();"]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-back','class'=>'btn hijau btn-outline ciptana-spin-btn', 'style'=>'display: none;', 'onclick' => "if (confirm('Apakah anda yakin akan menginput data? Pastikan data yang anda input sudah benar.')) { resetForm(); }"]); ?>
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
if(isset($_GET['bandsaw_id'])){
    $pagemode = "afterSaveThis(". $_GET['bandsaw_id'] .");";
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
			url: '".\yii\helpers\Url::toRoute('/ppic/bandsaw/findSPK')."',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data,
                    edit: '". (isset($_GET['edit'])?$_GET['edit']:'') ."',
                    id: '". (isset($_GET['bandsaw_id'])?$_GET['bandsaw_id']:'') ."'
				};
			},
			cache: true
		}
	});
", yii\web\View::POS_READY); ?>
<script>
    function openSPK(){
        var edit = '<?= isset($_GET['edit'])?$_GET['edit']:'' ?>';
        var id = '<?= isset($_GET['bandsaw_id'])?$_GET['bandsaw_id']:''; ?>';
        $(".modals-place-3-min").load(
            '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/openSPK']) ?>', 
            { id: id, edit: edit },
            function(response) {
                $("#modal-master .modal-dialog").css('width','90%');
                $("#modal-master").modal('show');
                $("#modal-master").on('hidden.bs.modal', function () {});
                spinbtn();
                draggableModal();
            }
        );
    }

    // function setDetail(edit){
    //     var spk_sawmill_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>').val();
    //     var nobandsawChecked = $('input[name="TBandsaw[nomor_bandsaw][]"]:checked').map(function() {
    //             return $(this).val();
    //         }).get();

    //     if(spk_sawmill_id){
    //         $.ajax({
    //             url    : '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/setDetail']); ?>',
    //             type   : 'POST',
    //             data   : {spk_sawmill_id:spk_sawmill_id, nobandsawChecked:nobandsawChecked},
    //             success: function (data) {
    //                 if(data){
    //                     $('#<?= yii\bootstrap\Html::getInputId($model, "kayu_id") ?>').val(data.spk.kayu_id).trigger('change');
    //                     $('#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>').val(data.spk.line_sawmill);

    //                     // if(Array.isArray(data.nobandsaw) && data.nobandsaw.length > 0){
    //                     //     getItems(spk_sawmill_id, bandsaw_id=null, edit, data.nobandsaw);
    //                     // } else {
    //                     //     $('#table-detail tbody').html('');
    //                     // }
    //                     var tbody = $('#table-detail tbody');
    //                     // hapus baris yg no bandsawnya ga dicentang
    //                     tbody.find('tr').each(function(){
    //                         var rowValue = $(this).find('input[name*="nomor_bandsaw"]').val();
    //                         if(nobandsawChecked.indexOf(rowValue) === -1){
    //                             $(this).remove();
    //                         }
    //                     });
    //                     // ambil baris yg udah ada
    //                     var existingRows = tbody.find('tr').map(function(){
    //                         return $(this).find('input[name*="nomor_bandsaw"]').val();
    //                     }).get();
    //                     // filter cuma buat yg dicenyang aja
    //                     var newNobandsaw = nobandsawChecked.filter(function(n){
    //                         return existingRows.indexOf(n) === -1;
    //                     });

    //                     if(!edit){
    //                         if(Array.isArray(data.nobandsaw) && data.nobandsaw.length > 0){
    //                             getItems(spk_sawmill_id, bandsaw_id=null, edit, data.nobandsaw);
    //                         } else {
    //                             $('#table-detail tbody').html('');
    //                         }
    //                     } else {
    //                         if(newNobandsaw.length > 0){
    //                             getItems(spk_sawmill_id, bandsaw_id=null, edit, newNobandsaw);
    //                         }
    //                     }
    //                 }
    //             },
    //             error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
    //         });
    //     }
    // }

    function setDetail(edit){
        var spk_sawmill_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>').val();
        var nobandsawChecked = $('input[name="TBandsaw[nomor_bandsaw][]"]:checked').map(function() {
            return $(this).val();
        }).get();

        if(spk_sawmill_id){
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/setDetail']); ?>',
                type   : 'POST',
                data   : {spk_sawmill_id:spk_sawmill_id, nobandsawChecked:nobandsawChecked},
                success: function (data) {
                    var tbody = $('#table-detail tbody');

                    // 1️⃣ hapus baris yang sudah tidak dicentang
                    tbody.find('tr').each(function(){
                        var rowValue = $(this).find('input[name*="nomor_bandsaw"]').val();
                        if(nobandsawChecked.indexOf(rowValue) === -1){
                            $(this).remove();
                        }
                    });

                    // 2️⃣ ambil baris yang sudah ada
                    var existingRows = tbody.find('tr').map(function(){
                        return $(this).find('input[name*="nomor_bandsaw"]').val();
                    }).get();

                    // 3️⃣ ambil nomor baru yang dicentang tapi belum ada di table
                    var newNobandsaw = nobandsawChecked.filter(function(n){
                        return existingRows.indexOf(n) === -1;
                    });

                    // 4️⃣ panggil getItems() cuma untuk nomor baru
                    if(newNobandsaw.length > 0){
                        getItems(spk_sawmill_id, bandsaw_id=null, edit, newNobandsaw);
                    }

                    // update field lain jika perlu
                    if(data.spk){
                        $('#<?= yii\bootstrap\Html::getInputId($model, "kayu_id") ?>').val(data.spk.kayu_id).trigger('change');
                        $('#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>').val(data.spk.line_sawmill);
                    }
                },
                error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
            });
        }
    }


    function pick(spk_sawmill_id,kode){
        $("#modal-master").find('button.fa-close').trigger('click');
        $("#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>").empty().append('<option value="'+spk_sawmill_id+'">'+kode+'</option>').val(spk_sawmill_id).trigger('change');
    }

    function getItems(spk_sawmill_id, bandsaw_id=null, edit=null, nobandsaw){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/getItems']); ?>',
            type   : 'POST',
            data   : {spk_sawmill_id:spk_sawmill_id,bandsaw_id:bandsaw_id,edit:edit,nobandsaw:nobandsaw.sort()},
            success: function (data) {
                if(data.html){
                    if(edit){
                        $('#table-detail > tbody').html(data.html);
                    } else {
                        $('#table-detail > tbody').append(data.html);
                        $('.btn-add-size').hide();
                        $('.btn-remove-size').hide();
                        $('.btn-add-pjg').hide();
                        $('.btn-remove-pjg').hide();
                        $('.btn-remove').each(function() {
                            $(this).removeClass('red').addClass('grey').css({'pointer-events':'none'}).off('click');
                        });

                        $('.input-pjg').each(function() {
                            $(this).removeAttr('onclick');
                        });
                    }
                }
                setTimeout(function(){
                    reordertables('#table-detail');
                },500);
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }

    function hitung(ele, n, i, p){
        // var now = Date.now(); // ambil waktu now (milidetik)
        // var lastClick = $(ele).data('lastClick') || 0;

        // // jika klik terlalu cepat (< 1000 ms/1 s), abaikan
        // if (now - lastClick < 1000) {
        //     return;
        // }
        // // untuk simpan waktu klik terakhir
        // $(ele).data('lastClick', now);
        
        // tambah qty
        var jml_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][jml]"]');
        var qty_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][qty]"]');
        var qty2_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][qty2]"]');
        var bandsaw_detail_id = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][bandsaw_detail_id]"]').val();

        var jml = jml_field.val() || '';
        var qty = parseInt(qty_field.val()) || 0;

        if (jml.length < 5) {
            jml += '1';
        } else {
            qty += 5; 
            jml = '1';
        }
        var qty2 = jml.length + qty;

        jml_field.val(jml);
        qty_field.val(qty);
        qty2_field.val(qty2);
        autoSave(bandsaw_detail_id, qty2);
    }

    function remove(ele, n, i, p){
        // var now = Date.now(); // ambil waktu now (milidetik)
        // var lastClick = $(ele).data('lastClick') || 0;

        // // jika klik terlalu cepat (< 1000 ms/1 s), abaikan
        // if (now - lastClick < 1000) {
        //     return;
        // }
        // // untuk simpan waktu klik terakhir
        // $(ele).data('lastClick', now);

        // kurangi qty
        var jml_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][jml]"]');
        var qty_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][qty]"]');
        var qty2_field = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][qty2]"]');
        var bandsaw_detail_id = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][bandsaw_detail_id]"]').val();

        var jml = jml_field.val() || '';
        var qty = parseInt(qty_field.val()) || 0;
        
        if (jml.length > 1) {
            jml = jml.slice(0, -1);
        } else if (jml.length === 1) {
            if (qty <= 0 && jml.length === 1) {
                jml = '';
            } else {
                jml = '11111';
                qty = Math.max(0, qty - 5);
            }
        }
        var qty2 = jml.length + qty;

        jml_field.val(jml);
        qty_field.val(qty);
        qty2_field.val(qty2);
        autoSave(bandsaw_detail_id, qty2);
    }

    function addPjg (ele, n, i, p) {
        var panjangContainer = $(ele).closest('tr').find('.place-panjang-' + n + '-' + i+ '-' + p).last();
        var jmlContainer = $(ele).closest('tr').find('.place-jml-' + n + '-' + i+ '-' + p).last();
        // var p = $(ele).closest('tr').find('.place-panjang-' + n + '-' + i).length;
        // var p = $(ele).closest('tr').find(`[class^='place-panjang-${n}-${i}']`).length - 1;
        // var lastInput = $(ele).closest('tr').find('.place-panjang-' + n + '-' + i + '-' + p +' input[name*="[panjang]"]');

        var allPjg = $(ele).closest('tr').find(`[class^="place-panjang-${n}-${i}"]`);
        var p = allPjg.length - 1;
        var lastInput = allPjg.last().find('input[name*="[panjang]"]');

        if (!lastInput.val() || lastInput.val() == 0) {
            cisAlert('Isi dan simpan panjang terlebih dahulu!');
            return;
        }

        p++;
        var newPanjang = `
            <div class="place-panjang-${n}-${i}-${p}" style="display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
                <input type="text" name="TBandsawDetail[${n}][${i}][${p}][panjang]" class="form-control float" style="width:55px; font-size:1.2rem;">
                <a class="btn btn-xs hijau" style="margin-top: 5px;" id="button-save-pjg" onclick="savePjg(this, ${n}, ${i}, ${p})">
                    <i class="fa fa-check"></i>
                </a>
                <a class="btn btn-xs red btn-remove btn-hapus-add-pjg" onclick="removePjg(this, ${n}, ${i}, ${p});"><i class="fa fa-times"></i></a>
            </div>
        `;
        var newJml = `
            <div class="place-jml-${n}-${i}-${p}" style="display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
                <input type="text" name="TBandsawDetail[${n}][${i}][${p}][jml]" class="form-control" style="width:60px; font-size:1.2rem;" disabled>
                <input type="text" name="TBandsawDetail[${n}][${i}][${p}][qty]" class="form-control float" style="width:60px; font-size:1.2rem; text-align: right;" disabled>
                <center><a class="btn btn-xs red" onclick="remove(this, ${n}, ${i}, ${p});"><i class="fa fa-minus"></i></a></center>
                <input type="text" name="TBandsawDetail[${n}][${i}][${p}][qty2]" class="form-control float" style="width:60px; font-size:1.2rem; text-align: right;" disabled>
            </div>
        `;

        $(allPjg.last()).after(newPanjang);
        var allJml = $(ele).closest('tr').find(`[class^="place-jml-${n}-${i}"]`);
        $(allJml.last()).after(newJml);
        $(ele).closest('tr').find(`input[name="TBandsawDetail[${n}][${i}][${p}][panjang]"]`).focus();
        // jika field pjg terakhir masik 0 maka gabisa addpjg
        // if(lastInput.val() > 0){
        //     $(panjangContainer).after(newPanjang);
        //     $(jmlContainer).after(newJml);
        //     $('.place-panjang-' + n + '-' + i+ '-' + p).find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][panjang]"]').last().focus();
        //     reordertables('#table-detail');
        // } else {
        //     cisAlert('Isi panjang terlebih dahulu!');
        // }
    }

    function removePjg(ele, n, i, p) {
        var row = $(ele).closest('tr');

        // hapus data di database
        // var p = $(ele).closest('tr').find('.place-panjang-' + n + '-' + i).length - 1;
        var bandsaw_detail_id = $(row).find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][bandsaw_detail_id]"]').val();
        var pjg = $(row).find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][panjang]"]').val();
        var no_bandsaw = $(row).find('input[name="TBandsawDetail[' + n + '][nomor_bandsaw]"]').val();
        var size = $(row).find('select[name="TBandsawDetail[' + n + '][' + i + '][size]"]').val();
        if(bandsaw_detail_id){
            if (!confirm('Yakin mau hapus data panjang '+ pjg +' di nomor bandsaw ' + no_bandsaw + ' size '+ size +' ini?')) {
                return;
            }
            
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/removePjg']); ?>',
                type   : 'POST',
                data   : {bandsaw_detail_id:bandsaw_detail_id},
                success: function (data) {
                    if(data.status){
                        console.log('Berhasil dihapus');
                    }
                },
                error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
            });
        }

        var panjangDivs = row.find('.place-panjang-' + n + '-' + i+ '-' + p);
        var jmlDivs = row.find('.place-jml-' + n + '-' + i+ '-' + p);

        panjangDivs.remove();
        jmlDivs.remove();
        // if (panjangDivs.length > 1) {
        //     panjangDivs.last().remove();
        //     jmlDivs.last().remove();
        // }

        // location.reload();
        reordertables('#table-detail');
    }

    function save(){
        var form = $('#form-transaksi');

        <?php if(isset($_GET['bandsaw_id'])){ ?>
            var jumlah_item = $('#table-detail tbody tr').length;
            if (jumlah_item <= 0) {
                cisAlert('Isi detail terlebih dahulu');
            }
        <?php } ?>

        if (validatingDetail()){
            submitform(form);
        }

        return false;
    }

    function validatingDetail(){
        var has_error = 0;

        var spk_sawmill_id = $("#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>");
        var line_sawmill = $("#<?= yii\bootstrap\Html::getInputId($model, "line_sawmill") ?>");

        if(!spk_sawmill_id.val()){
            $(spk_sawmill_id).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(spk_sawmill_id).parents('.form-group').removeClass('error-tb-detail');
        }
        if(!line_sawmill.val()){
            $(line_sawmill).parents('.form-group').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(line_sawmill).parents('.form-group').removeClass('error-tb-detail');
        }

        // validasi checklist nomor bandsaw
        var checkedBandsaw = $('input[name="TBandsaw[nomor_bandsaw][]"]:checked');
        if(checkedBandsaw.length === 0){
            cisAlert("Pilih minimal 1 Nomor Bandsaw!");
            has_error = has_error + 1;
        }

        <?php //if(isset($_GET['bandsaw_id'])){ ?>
            // $('#table-detail tbody > tr').each(function(){
            //     var nomor_bandsaw = $(this).find('select[name*="[nomor_bandsaw]"]');

            //     if(!nomor_bandsaw.val()){
            //         $(this).find('select[name*="[nomor_bandsaw]"]').parents('td').addClass('error-tb-detail');
            //         has_error = has_error + 1;
            //     }else{
            //         $(this).find('select[name*="[nomor_bandsaw]"]').parents('td').removeClass('error-tb-detail');
            //     }

            //     $(this).find('input[name*="[panjang]"]').each(function(){
            //         if(!$(this).val() || $(this).val() <= 0){
            //             $(this).addClass('error-tb-detail');
            //             has_error++;
            //         } else {
            //             $(this).removeClass('error-tb-detail');
            //         }
            //     });

            //     $(this).find('input[name*="[jml]"]').each(function(){
            //         if(!$(this).val()){
            //             $(this).addClass('error-tb-detail');
            //             has_error++;
            //         } else {
            //             $(this).removeClass('error-tb-detail');
            //         }
            //     });
            //     $(this).find('input[name*="[qty]"]').each(function(){
            //         if(!$(this).val()){
            //             $(this).addClass('error-tb-detail');
            //             has_error++;
            //         } else {
            //             $(this).removeClass('error-tb-detail');
            //         }
            //     });
            // });
        <?php //} ?>

        if(has_error === 0){
            return true;
        }
        return false;
    }

    function daftarAfterSave(){
        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/daftarAfterSave']) ?>','modal-aftersave','90%');
    }

    function afterSaveThis(bandsaw_id){
        var spk_sawmill_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>').val();
        $('#table-detail').show();

        setTimeout(function(){
            var tabs = $('.tab-bandsaw').first().find('a');;
            if(tabs.length){
                tabs.trigger('click');
            }
        }, 100);

        // var nobandsaw = <?php //echo json_encode($checkedBandsaw); ?>;
        <?php //if(!isset($_GET['edit'])){ ?>
        //     getItems(spk_sawmill_id, bandsaw_id, null, nobandsaw);
        <?php //}else{ ?>
        //     getItems(spk_sawmill_id, bandsaw_id, 1, nobandsaw);
        <?php //} ?>

        // $('#btn-save').prop('disabled',false);
        $('#btn-save').hide();
        $('#btn-print').prop('disabled',false);
        $('input[name="TBandsaw[nomor_bandsaw][]"]').prop('disabled', true);
        $('#btn-close').show();
        <?php if(isset($_GET['edit'])){ ?>
            $('#btn-back').show();
            $('#btn-close').hide();
            $('#btn-save').attr('disabled','');
            // $('#btn-print').removeAttr('disabled');
            $('#btn-print').prop('disabled',true);
            // $('input[name="TBandsaw[nomor_bandsaw][]"]').prop('disabled', false);
            $('select[name="TBandsaw[spk_sawmill_id]"]').prop('disabled', true);
            $('#btn-spk').addClass('disabled');
        <?php } ?>
        
        // $('#btn-save').attr('disabled','');
        // $('#btn-print').removeAttr('disabled');
        // $('input[name="TBandsaw[nomor_bandsaw][]"]').prop('disabled', true);
        // $('select[name="TBandsaw[spk_sawmill_id]"]').prop('disabled', true);
        // $('#btn-spk').addClass('disabled');
        <?php //if(isset($_GET['edit'])){ ?>
        //     $('#btn-save').prop('disabled',false);
        //     $('#btn-print').prop('disabled',true);
        //     $('input[name="TBandsaw[nomor_bandsaw][]"]').prop('disabled', false);
        <?php //} ?>
    }

    function reordertables(obj_table) {
        var row = -1; // index bandsaw (mulai -1 biar row pertama jadi 0)
        var last_noban = null; // simpan nomor bandsaw sebelumnya

        $(obj_table + ' > tbody > tr').each(function() {
            var $tr = $(this);
            var noban = $tr.find('input[name*="[nomor_bandsaw]"]').val(); // ambil nomor bandsaw

            // Kalau bandsaw berubah, baru naikkan index row
            if (noban !== last_noban) {
                row++;
                last_noban = noban;
            }

            // Update semua input di baris ini dengan index [row]
            $tr.find('input, select, textarea').each(function() {
                var $el = $(this);
                var old_name = $el.attr("name");
                if (!old_name) return; // skip kalau ga ada name

                var old_name_clean = old_name.replace(/]/g, "");
                var old_name_arr = old_name_clean.split("[");

                // 2 level (misal: Model[0][field])
                if (old_name_arr.length == 3) {
                    $el.attr("id", old_name_arr[0] + "_" + row + "_" + old_name_arr[2]);
                    $el.attr("name", old_name_arr[0] + "[" + row + "][" + old_name_arr[2] + "]");
                }
                // 3 level (misal: Model[0][0][field])
                else if (old_name_arr.length == 4) {
                    $el.attr("id", old_name_arr[0] + "_" + row + "_" + old_name_arr[2] + "_" + old_name_arr[3]);
                    $el.attr("name", old_name_arr[0] + "[" + row + "][" + old_name_arr[2] + "][" + old_name_arr[3] + "]");
                }
                // 4 level (misal: Model[0][0][0][field])
                else if (old_name_arr.length == 5) {
                    $el.attr("id", old_name_arr[0] + "_" + row + "_" + old_name_arr[2] + "_" + old_name_arr[3] + "_" + old_name_arr[4]);
                    $el.attr("name", old_name_arr[0] + "[" + row + "][" + old_name_arr[2] + "][" + old_name_arr[3] + "][" + old_name_arr[4] + "]");
                }
            });

            // Update kolom nomor urut visual (yang pakai rowspan)
            var isFirstRow = $tr.find("#no_urut").length > 0;
            if (isFirstRow) {
                $tr.find("#no_urut").val(row + 1);
                $tr.find("span.no_urut").text(row + 1);
            }
        });

        formconfig();
    }

    function autoSave(bandsaw_detail_id, qty){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/autoSave']); ?>',
            type   : 'POST',
            data   : {bandsaw_detail_id:bandsaw_detail_id, qty:qty},
            success: function (data) {
                if(data.status){
                    console.log("Data detail ke : "+bandsaw_detail_id+" berhasil disimpan.");
                }
            },
            error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
        });
    }

    function savePjg(ele, n, i, p){
        var bandsaw_id = '<?= isset($_GET['bandsaw_id'])?$_GET['bandsaw_id']:'' ?>';
        var spk_sawmill_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>').val();
        var pjg = unformatNumber($(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][panjang]"]').val());
        var no_bandsaw = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][nomor_bandsaw]"]').val();
        var size = $(ele).closest('tr').find('select[name="TBandsawDetail[' + n + '][' + i + '][size]"]').val();
        var has_error = 0;

        if(pjg <= 0){
            $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][panjang]"]').addClass('error-tb-detail');
            has_error = has_error + 1;
        } else{
            $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][panjang]"]').removeClass('error-tb-detail');
        }

        if(has_error === 0){
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/savePjg']); ?>',
                type   : 'POST',
                data   : {bandsaw_id:bandsaw_id, spk_sawmill_id:spk_sawmill_id, pjg:pjg, no_bandsaw:no_bandsaw, size:size},
                success: function (data) {
                    if(data.status){
                        $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][' + p + '][panjang]"]').prop('disabled', true);
                        $(ele).closest('tr').find('#button-save-pjg').hide();
                        location.reload();
                    }
                },
                error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
            });

            reordertables('#table-detail');
        }
    }

    function addSize(ele, n, i) {
        var bandsaw_id = '<?= isset($_GET['bandsaw_id']) ? $_GET['bandsaw_id'] : '' ?>';
        var noban = $(ele).closest('tr').find('select[name="TBandsawDetail[' + n + '][nomor_bandsaw]"]').val();
        var p = 0;

        var lastTr = $('tr[id^="tr-' + n + '-"]').last();
        var lastId = lastTr.attr('id'); // contoh: "tr-0-1"
        var parts = lastId.split('-');
        var nextI = parseInt(parts[2]) + 1; 

        $.ajax({
            url: '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/addSize']); ?>',
            type: 'POST',
            data: { bandsaw_id: bandsaw_id, n: n, i: nextI, noban: noban, p: p },
            success: function (data) {
                if (data) {
                    var allRows = $('tr[id^="tr-' + n + '-"]');
                    var newRowspan = allRows.length + 1;
                    allRows.first().find('td[rowspan]').attr('rowspan', newRowspan);
                    $('#tr-' + n + '-' + (nextI - 1)).after(data.html);
                    $('#tr-' + n + '-' + nextI).find('select[name*="[size]"]').select2({
                        allowClear: !0,
						placeholder: 't x l',
                        width: 'resolve'
                    });
                    $('#tr-' + n + '-' + nextI).find('.select2-selection').css('font-size','1.2rem');
                    $('#tr-' + n + '-' + nextI).find('.select2-selection').css('padding-left','5px');
                    $('#tr-' + n + '-' + nextI).find(".tooltips").tooltip({ delay: 50 });
                    reordertables('#table-detail');
                }
            },
            error: function (jqXHR) {
                gerefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function addSize2(ele, n, i){
        var bandsaw_id = '<?= isset($_GET['bandsaw_id'])?$_GET['bandsaw_id']:''; ?>';
        var noban = $(ele).closest('tr').find('select[name="TBandsawDetail[' + n + '][nomor_bandsaw]"]').val();
        var p = 0;
        var lastTr = $('tr[id^="tr-' + n + '-"]').last();
        var lastId = lastTr.attr('id');        // misal "tr-0-1"
        var parts = lastId.split('-');
        var nextI = parseInt(parts[2]) + 1;
        
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/addSize']); ?>',
            type   : 'POST',
            data   : {bandsaw_id:bandsaw_id, n:n, i:nextI, noban:noban, p:p},
            success: function (data) {
                if(data){
                    var countrowspan = $(ele).closest('tr').find('td[rowspan]').length;
                    var rowspan = countrowspan+1;
                    $(ele).closest('tr').find('td[rowspan]').attr('rowspan', rowspan);

                    i = countrowspan-1;
                    $('#tr-'+n+'-'+i).after(data.html);
                    reordertables('#table-detail');
                }
            },
            error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
        });
    }

    function saveSize(ele, n, i){
        var bandsaw_id = '<?= isset($_GET['bandsaw_id'])?$_GET['bandsaw_id']:'' ?>';
        var spk_sawmill_id = $('#<?= yii\bootstrap\Html::getInputId($model, "spk_sawmill_id") ?>').val();
        var size = $(ele).closest('tr').find('select[name="TBandsawDetail[' + n + '][' + i + '][size]"]').val();
        var panjang = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][0][panjang]"]').val();
        var no_bandsaw = $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][nomor_bandsaw]"]').val();
        var has_error = 0;
        console.log(panjang);

        if(!size){
            $(ele).closest('tr').find('select[name="TBandsawDetail[' + n + '][' + i + '][size]"]').addClass('error-tb-detail');
            has_error = has_error + 1;
        } else {
            $(ele).closest('tr').find('select[name="TBandsawDetail[' + n + '][' + i + '][size]"]').removeAttr('error-tb-detail');
        }

        // cek apakah ada yg sama
        var isDuplicate = false;
        var sizes = $(ele).closest('tr').find('select[name="TBandsawDetail[' + n + '][' + i + '][size]"]');
        $('select[id^="TBandsawDetail_' + n + '_"][id$="_size"]').each(function() {
            var val = $(this).val();
            if (val && val === size && this !== sizes[0]) {
                isDuplicate = true;
                return false; // stop loop
            }
        });

        if (isDuplicate) {
            cisAlert('Size ini sudah ada pada nomor bandsaw yang sama!');
            $(ele).closest('tr').find('select[name="TBandsawDetail[' + n + '][' + i + '][size]"]').addClass('error-tb-detail');
            has_error = has_error + 1;
        } else {
            $(ele).closest('tr').find('select[name="TBandsawDetail[' + n + '][' + i + '][size]"]').removeAttr('error-tb-detail');
        }
        if(!panjang || panjang <= 0){
            cisAlert('Cek kembali pada inputan panjang!');
            $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][0][panjang]"]').addClass('error-tb-detail');
            has_error = has_error + 1;
        } else {
            $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][0][panjang]"]').removeAttr('error-tb-detail');
        }

        if(has_error === 0){
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/saveSize']); ?>',
                type   : 'POST',
                data   : {bandsaw_id:bandsaw_id, spk_sawmill_id:spk_sawmill_id, size:size, no_bandsaw:no_bandsaw,panjang:panjang},
                success: function (data) {
                    if(data){
                        $(ele).closest('tr').find('select[name="TBandsawDetail[' + n + '][' + i + '][size]"]').prop('disabled', true);
                        $(ele).closest('tr').find('#place-btn-pjg').show();
                        $(ele).closest('tr').find('input[name="TBandsawDetail[' + n + '][' + i + '][0][panjang]"]').prop('readonly', true);
                        $(ele).closest('tr').find('#button-save-size').hide();
                        // $(ele).closest('tr').find('#button-save-pjg').css({'pointer-events': 'auto', 'opacity': '1'}).prop('disabled', false).removeAttr('disabled');;
                        location.reload();
                    }
                },
                error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
            });

            reordertables('#table-detail');
        }
    }

    function removeSize(ele, n, i) {
        var row = $(ele).closest('tr');

        var tr = $('#tr-'+n+'-'+i);
        // hapus data di database
        var nobandsaw = $(row).find('input[name="TBandsawDetail[' + n + '][nomor_bandsaw]"]').val();
        var size = $(row).find('select[name="TBandsawDetail[' + n + '][' + i + '][size]"]').val();
        // console.log(size);

        if(size){
            if (!confirm('Yakin mau hapus data size '+size+' di nomor bandsaw '+ nobandsaw +' ini?')) {
                return;
            }
            
            $.ajax({
                url    : '<?= \yii\helpers\Url::toRoute(['/ppic/bandsaw/removeSize']); ?>',
                type   : 'POST',
                data   : {size:size,nobandsaw:nobandsaw},
                success: function (data) {
                    if(data.status){
                        console.log('Berhasil dihapus');
                    }
                },
                error: function (jqXHR) { gerefaultajaxerrorresponse(jqXHR); },
            });
        }

        var sizeDivs = row.find('.place-size-' + n + '-' + i);
        var panjangDivs = row.find('.place-panjang-' + n + '-' + i);
        var jmlDivs = row.find('.place-jml-' + n + '-' + i);

        sizeDivs.remove();
        panjangDivs.remove();
        jmlDivs.remove();

        location.reload();
        reordertables('#table-detail');
    }

    function cancelBandsaw(bandsaw_id){
        openModal('<?php echo yii\helpers\Url::toRoute(['/ppic/bandsaw/cancelBandsaw']) ?>?id='+bandsaw_id,'modal-transaksi');
    }

    function printBandsaw(id){
        var caraPrint = "PRINT";
        window.open("<?= yii\helpers\Url::toRoute(['/ppic/bandsaw/printBandsaw', 'id' => '']) ?>" + id + "&caraprint=" + caraPrint, "", 'location=_new, width=1200px, scrollbars=yes');
    }

    function removeSizes(ele, n, i){
        var tr = $('#tr-'+n+'-'+i);
        var countrowspan = $(ele).closest('tr').find('td[rowspan]').length;
        tr.remove();
        var allRows = $('tr[id^="tr-' + n + '-"]');
        var newRowspan = allRows.length;

        if (allRows.length > 0) {
            allRows.first().find('td[rowspan]').attr('rowspan', newRowspan);
        }

        reordertables('#table-detail');
    }

    let currentDropdown = null;
    function addListSize(ele){
        currentDropdown = $(ele).closest('td').find('select[name*="[size]"]');
        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/spksawmill/addListSize']) ?>','modal-add','80%');
    }

    function loadByNomor(ele, spk_sawmill_id, bandsaw_id, nomor){
        $('.tab-bandsaw').removeClass('active');
        $(ele).closest('li.tab-bandsaw').addClass('active');

        $('#table-detail tbody').html('');
        <?php if(!isset($_GET['edit'])){ ?>
            getItems(spk_sawmill_id, bandsaw_id, null, [nomor]);
        <?php }else{ ?>
            getItems(spk_sawmill_id, bandsaw_id, 1, [nomor]);
        <?php } ?>
    }
</script>