<?php
/* @var $this yii\web\View */
$this->title = 'Kas Kecil';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-pengeluaran-kas',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions' => ['class' => 'col-md-3 control-label'],
    ],
]);
echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
    .tDnD_whileDrag td {
        background-color: #93979d;
        -webkit-box-shadow: 11px 5px 12px 2px #333, 0 1px 0 #ccc inset, 0 -1px 0 #ccc inset;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/sementara"); ?>"> <?= Yii::t('app', 'Bon Kas Kecil'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/index"); ?>"> <?= Yii::t('app', 'Pengeluaran Kas Kecil'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/saldokaskecil/index"); ?>"> <?= Yii::t('app', 'Laporan Kas Kecil'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/rekapkaskecil/index"); ?>"> <?= Yii::t('app', 'Rekap Kas Kecil'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/kasir/pengeluarankaskecil/terimaretur"); ?>"> <?= Yii::t('app', 'Terima Uang Retur'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold">
                                        <h4><?= Yii::t('app', 'Transaksi Rekap Realisasi Pengeluaran Kas Kecil'); ?></h4>
                                    </span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'tanggal', ['template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly' => 'readonly', 'onchange' => 'getItems()'])->label('Tanggal'); ?>
                                    </div>
                                    <div class="col-md-6" style="margin-top: -8px; text-align: right;">
                                        <span id="btn-closing-place"></span><br>
                                        <a class="btn btn-sm blue" id="btn-closing" onclick="uangtunai();" style="margin-top: 10px;"><i class="fa fa-money"></i> &nbsp;<?= Yii::t('app', 'Total Uang Tunai : Rp. '); ?><span id="place-totaluangtunai"></span></a>
                                    </div>
                                </div>
                                <br><br>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Realisasi Pengeluaran Kas Kecil'); ?></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr class="nodrag">
                                                        <th style="width: 30px; vertical-align: middle; text-align: center;">No.</th>
                                                        <th><?= Yii::t('app', 'Deskripsi'); ?></th>
                                                        <th style="width: 100px; "><?= Yii::t('app', 'Penerima'); ?></th>
                                                        <th style="width: 80px; "><?= Yii::t('app', 'Debit'); ?></th>
                                                        <th style="width: 80px; "><?= Yii::t('app', 'Kredit'); ?></th>
                                                        <th style="width: 80px; "><?= Yii::t('app', 'BKK'); ?></th>
                                                        <th style="width: 95px; "><?= Yii::t('app', ''); ?></th>
                                                        <th style="width: 60px; text-align: center;"><?= Yii::t('app', ''); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="nodrag">
                                                        <td colspan="3" class="text-align-right">Total &nbsp;</td>
                                                        <td class="td-kecil text-align-right td-kecil"><?php echo yii\bootstrap\Html::textInput('totaldebit', 0, ['class' => 'form-control float', 'disabled' => 'disabled', 'style' => 'width: 80px; padding:3px; font-size:1.1rem']) ?></td>
                                                        <td class="td-kecil text-align-right td-kecil"><?php echo yii\bootstrap\Html::textInput('total', 0, ['class' => 'form-control float', 'disabled' => 'disabled', 'style' => 'width: 80px; padding:3px; font-size:1.1rem']) ?></td>
                                                    </tr>
                                                    <tr class="nodrag">
                                                        <td colspan="3">
                                                            <div class="col-md-2" id="btn-additem-place"></div>
                                                        </td>
                                                        <td colspan="5" style="text-align: left;">
                                                            <div class="row">
                                                                <div class="col-md-12 " id="btn-urutan-place"> </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if (isset($_GET['kas_kecil_id'])) {
    $pagemode = "";
} else {
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	$(\"#" . yii\bootstrap\Html::getInputId($model, 'tanggal') . "\").datepicker({
        rtl: App.isRTL(),
        orientation: \"left\",
        autoclose: !0,
        format: \"dd/mm/yyyy\",
        clearBtn:false,
        todayHighlight:true
    });
//	getItems();
    $pagemode;
	checkKasbonKasbesar();
	setMenuActive('" . json_encode(app\models\MMenu::getMenuByCurrentURL('Kas Kecil')) . "');
", yii\web\View::POS_READY); ?>
<?php $this->registerJsFile($this->theme->baseUrl . "/global/plugins/dnd/jquery.tablednd.js", ['depends' => [yii\web\YiiAsset::className()]]) ?>
<script>
    function getItems() {
        var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
        $.ajax({
            url: '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/getItems']); ?>',
            type: 'POST',
            data: {
                tgl: tgl
            },
            success: function(data) {
                $('#table-detail > tbody').html("");
                if (data.html) {
                    $('#table-detail > tbody').html(data.html);
                }
                setClosingBtn();
                setTotal();
                setDetailLayout();
                totalUangTunai();
                reordertable('#table-detail');

                setTimeout(function() {
                    if (($('#btn-closing').hasClass('red-flamingo')) == true) {
                        $('#table-detail').tableDnD({
                            onDrop: function(table, row) {
                                var param = decodeURI($.tableDnD.serialize());
                                openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/reorderkaskecil', 'ordering' => 'true']); ?>&' + param, 'modal-global-confirm');
                            }
                        });
                    }
                }, 800);
            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function addItem() {
        // Check Closing
        var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
        $.ajax({
            url: '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/checkClosing']); ?>',
            type: 'POST',
            data: {
                tgl: tgl
            },
            success: function(data) {
                if (data == 1) {
                    cisAlert('Tidak bisa Tambah Item karena ada pengeluran kas yang belum di Closing di tanggal sebelumnya ;)')
                } else {
                    $.ajax({
                        url: '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/addItem']); ?>',
                        type: 'POST',
                        data: {
                            tgl: tgl
                        },
                        success: function(data) {
                            if (data.html) {
                                $('#table-detail > tbody').append(data.html);

                            }
                            setClosingBtn();
                            reordertable('#table-detail');
                        },
                        error: function(jqXHR) {
                            getdefaultajaxerrorresponse(jqXHR);
                        },
                    });
                }
            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
        // End
    }

    function setClosingBtn() {
        var jmltr = $('#table-detail > tbody > tr').length;
        var html = '';
        var html2 = '';
        var html3 = '';
        var html4 = '';
        var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
        $.ajax({
            url: '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/setClosingBtn']); ?>',
            type: 'POST',
            data: {
                tgl: tgl
            },
            success: function(data) {
                if (data.status == 1) {
                    html = '<a class="btn btn-sm grey" id="btn-closing" disabled="disabled" style="margin-top: 10px;"><i class="fa fa-book"></i> <?= Yii::t('app', 'Closed'); ?></a>';
                    html2 = '<a id="btn-add-item" class="btn btn-sm grey" disabled="disabled" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Pengeluaran Baru'); ?></a>';
                    html3 = '<a class="btn btn-sm grey" disabled="disabled" style="margin-top: 10px; margin-right: 0px;"><i class="fa fa-refresh"></i> <?= Yii::t('app', 'Refresh'); ?></a>\n\
						 <a class="btn btn-sm grey" disabled="disabled" style="margin-top: 10px; margin-right: 0px;"><i class="fa fa-refresh"></i> <?= Yii::t('app', 'Multiple BKK'); ?></a>';
                    $('#form-pengeluaran-kas').find('input').each(function() {
                        $(this).attr("readonly", "readonly");
                    });
                    $('#form-pengeluaran-kas').find('textarea').each(function() {
                        $(this).attr("readonly", "readonly");
                    });
                    $('#btn-save').attr("disabled", "disabled");
                    //				$('#table-detail > tbody > tr').each(function(){
                    //					$(this).find('#td-action').html(' ');
                    //				});
                } else {
                    html2 = '<a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Pengeluaran Baru'); ?></a>';
                    html3 = '<a class="btn btn-sm green-haze btn-outline" id="btn-refresh" style="margin-top: 10px; margin-right: 0px;" onclick="refresh();"><i class="fa fa-refresh"></i> <?= Yii::t('app', 'Refresh'); ?></a>&nbsp;&nbsp;\n\
						 <a class="btn btn-sm purple-soft btn-outline" id="btn-bkkmultiple" style="margin-top: 10px; margin-right: 0px;" onclick="multipleBkk();"><?= Yii::t('app', 'Multiple BKK'); ?></a>';
                    //				if(jmltr > 0){
                    html = '<a class="btn btn-sm red-flamingo" id="btn-closing" onclick="closing();" style="margin-top: 10px;"><i class="fa fa-book"></i> <?= Yii::t('app', 'Closing Kas Kecil'); ?></a>';
                    //				}else{
                    //					html = '<a class="btn btn-sm red-flamingo" id="btn-closing" disabled="disabled" style="margin-top: 10px;"><i class="fa fa-book"></i> <?= Yii::t('app', 'Closing Kas Kecil'); ?></a>';
                    //				}
                }
                $('#btn-closing-place').html(html);
                $('#btn-additem-place').html(html2);
                $('#btn-urutan-place').html(html3);
            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function closing() {
        var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
        openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/closingConfirm', 'id' => '']); ?>' + tgl, 'modal-transaksi');
    }

    function save(ele) {
        var $form = $('#form-pengeluaran-kas');
        if (formrequiredvalidate($form)) {
            var jumlah_item = $('#table-detail tbody tr').length;
            if (jumlah_item <= 0) {
                cisAlert('Isi detail terlebih dahulu');
                return false;
            }
            if (validatingDetail(ele)) {
                $(ele).parents('tr').find('input[name*="[nominal]"]').val(unformatNumber($(ele).parents('tr').find('input[name*="[nominal]"]').val()));
                $(ele).parents('tr').find('input[name*="[debit]"]').val(unformatNumber($(ele).parents('tr').find('input[name*="[debit]"]').val()));
                $(ele).parents('tr').addClass('animation-loading');
                $.ajax({
                    url: '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/index']); ?>',
                    type: 'POST',
                    data: {
                        formData: $(ele).parents('tr').find('input, textarea').serialize()
                    },
                    success: function(data) {
                        $(ele).parents('tr').find('input[name*="[nominal]"]').val(formatNumberForUser($(ele).parents('tr').find('input[name*="[nominal]"]').val()));
                        $(ele).parents('tr').find('input[name*="[debit]"]').val(formatNumberForUser($(ele).parents('tr').find('input[name*="[debit]"]').val()));
                        if (data.status) {
                            $(ele).parents('tr').find('input[name*="[kode]"]').addClass('font-blue');
                            $(ele).parents('tr').find('input[name*="[kode]"]').val(data.kode);
                            $(ele).parents('tr').find('input, textarea').attr('disabled', 'disabled');
                            $(ele).parents('tr').find('#place-editbtn').attr('style', 'display:');
                            $(ele).parents('tr').find('#place-cancelbtn').attr('style', 'display:none');
                            $(ele).parents('tr').find('#place-savebtn').attr('style', 'display:none');
                            $(ele).parents('tr').find('#place-deletebtn').attr('style', 'display:');
                            $(ele).parents('tr').find('#place-tbpbtn').html(btntbp('deactive'));
                            $(ele).parents('tr').removeClass('animation-loading');
                        }
                        reordertable('#table-detail');
                        setTimeout(function() {
                            getItems();
                        }, 800);
                    },
                    error: function(jqXHR) {
                        getdefaultajaxerrorresponse(jqXHR);
                    },
                });
            }
        }

        return false;
    }

    function validatingDetail(ele) {
        var has_error = 0;
        var field1 = $(ele).parents('tr').find('textarea[name*="[deskripsi]"]');
        var field2 = $(ele).parents('tr').find('input[name*="[nominal]"]');
        var field3 = $(ele).parents('tr').find('input[name*="[penerima]"]');
        var field4 = $(ele).parents('tr').find('input[name*="[debit]"]');
        if (!field1.val()) {
            $(ele).parents('tr').find('textarea[name*="[deskripsi]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        } else {
            $(ele).parents('tr').find('textarea[name*="[deskripsi]"]').parents('td').removeClass('error-tb-detail');
        }
        if (!field2.val()) {
            $(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        } else {
            $(ele).parents('tr').find('input[name*="[nominal]"]').parents('td').removeClass('error-tb-detail');
        }
        if (!field3.val()) {
            $(ele).parents('tr').find('input[name*="[penerima]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        } else {
            $(ele).parents('tr').find('input[name*="[penerima]"]').parents('td').removeClass('error-tb-detail');
        }
        if (!field4.val()) {
            $(ele).parents('tr').find('input[name*="[debit]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        } else {
            $(ele).parents('tr').find('input[name*="[debit]"]').parents('td').removeClass('error-tb-detail');
        }
        if (has_error === 0) {
            return true;
        }
        return false;
    }

    function cancelItemThis(ele) {
        $(ele).parents('tr').fadeOut(200, function() {
            $(this).remove();
            reordertable('#table-detail');
            setTotal();
            setClosingBtn();
        });
    }

    function setTotal() {
        var total = 0;
        var totaldebit = 0;
        $('#table-detail > tbody > tr').each(function() {
            total += unformatNumber($(this).find('input[name*="[nominal]"]').val());
            totaldebit += unformatNumber($(this).find('input[name*="[debit]"]').val());
        });
        $('input[name="totaldebit"]').val(formatNumberForUser(totaldebit));
        $('input[name="total"]').val(formatNumberForUser(total));
    }

    function afterSave() {
        $('input[name*="total"]').attr('disabled', 'disabled');
    }

    function pickPanelPengeluaranSementara(ele) {
        // Check Closing
        var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
        var rowid = $(ele).parents('tr').find('input[name*="[kas_kecil_id]"]').attr('id');
        $.ajax({
            url: '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/checkClosing']); ?>',
            type: 'POST',
            data: {
                tgl: tgl
            },
            success: function(data) {
                if (data == 1) {
                    cisAlert('Tidak bisa Tambah Item karena ada pengeluran kas yang belum di Closing di tanggal sebelumnya ;)')
                } else {
                    openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/pickPanelPengeluaranSementara', 'rowid' => '']) ?>' + rowid, 'modal-history', '75%');
                }
            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
        // End
    }

    function picking() {
        var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
        var picked = $('#select_data').val();
        var rowid = $('#rowid').val();
        var kodeterima_exist = $('#' + rowid).parents('tr').find('input[name*="[tbp_reff]"]').val();
        var kodeterimalabel_exist = $('#' + rowid).parents('tr').find('#place-tbp').html();
        var kas_kecil_id_exist = $('#' + rowid).parents('tr').find('input[name*="[kas_kecil_id]"]').val();
        $.ajax({
            url: '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/pickPengeluaranSementara']); ?>',
            type: 'POST',
            data: {
                picked: picked,
                tgl: tgl
            },
            success: function(data) {
                if (data.html) {
                    var tr_selected = $('#' + rowid).parents('tr');
                    $(tr_selected).replaceWith(data.html);
                    $('#modal-history').modal('hide');
                }
                setClosingBtn();
                reordertable('#table-detail');
                if (kodeterima_exist) {
                    $('#' + rowid).parents('tr').find('input[name*="[tbp_reff]"]').val(kodeterima_exist);
                    $('#' + rowid).parents('tr').find('#place-tbp').html(kodeterimalabel_exist);
                }
                if (kas_kecil_id_exist) {
                    $('#' + rowid).parents('tr').find('input[name*="[kas_kecil_id]"]').val(kas_kecil_id_exist);
                }
                setTotal();

            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function pickPanelTBP(ele) {
        var eleid = $(ele).parents("td").find('input').attr('id');
        openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/pickPanelTBP']) ?>?eleid=' + eleid, 'modal-tbp', '75%');
    }

    function pickingTBP() {
        var picked = $('#select_data').val();
        var eleid = $('#eleid').val();
        $.ajax({
            url: '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/pickTBP']); ?>',
            type: 'POST',
            data: {
                picked: picked
            },
            success: function(data) {
                if (data.kodeterima) {
                    $('#' + eleid).val(data.kodeterima);
                }
                if (data.kodelabelterima) {
                    $('#' + eleid).parents('tr').find('#place-tbp').html(data.kodelabelterima);
                }
                if (data.total) {
                    $('#' + eleid).parents('tr').find('input[name*="[nominal]"]').val(formatNumberForUser(data.total));
                }
                if (data.deskripsi) {
                    $('#' + eleid).parents('tr').find('textarea[name*="[deskripsi]"]').val(data.deskripsi);
                }
                setTotal();
                $('#modal-tbp').modal('hide');

            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function resetTBP(ele) {
        $(ele).parents('tr').find('input[name*="[tbp_reff]"]').val("");
        $(ele).parents('tr').find('input[name*="[nominal]"]').val("");
        $(ele).parents('tr').find('textarea[name*="[deskripsi]"]').val("");
        $(ele).parents('tr').find('#place-tbp').html("");
        setTotal();
    }

    function setDetailLayout() {
        $('#table-detail > tbody > tr').each(function() {
            if ($(this).find('input[name*="[kas_kecil_id]"]')) {
                $(this).find('input:text, textarea').attr('disabled', 'disabled');
                afterSave();
            } else {
                $(this).find('input:text, textarea').removeAttr('disabled');
            }
        });
    }

    function deleteItem(id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/deleteItem', 'id' => '']) ?>' + id, 'modal-delete-record');
    }

    function edit(ele) {
        $(ele).parents('tr').find('input, textarea').removeAttr('disabled');
        $(ele).parents('tr').find('input[name*="[kode]"]').attr('disabled', 'disabled');
        $('.date-picker').find('.input-group-addon').find('button').prop('disabled', false);
        $(ele).parents('tr').find('#place-editbtn').attr('style', 'display:none');
        $(ele).parents('tr').find('#place-savebtn').attr('style', 'display:');
        $(ele).parents('tr').find('#place-tbpbtn').html(btntbp('active'));
    }

    function btntbp(mode) {
        if (mode == 'active') {
            var res = '<a class="btn btn-xs blue-steel" id="btn-addtbp" onclick="pickPanelTBP(this)" style="font-size: 1rem;"><i class="fa fa-plus"></i> TBP</a>\n\
				   <a class="btn btn-xs blue" id="btn-addbon" onclick="pickPanelPengeluaranSementara(this)" style="margin-left: -7px; font-size: 1rem;"><i class="fa fa-plus"></i> Bon</a>';
        } else {
            var res = '<a class="btn btn-xs grey" id="btn-addtbp" style="font-size: 1rem;"><i class="fa fa-plus"></i> TBP</a>\n\
				   <a class="btn btn-xs grey" id="btn-addbon" style=" margin-left: -7px; font-size: 1rem;"><i class="fa fa-plus"></i> Bon</a>';
        }
        return res;
    }

    function refresh() {
        getItems();
    }

    function urutkankode() {
        $('#table-detail').addClass('animation-loading');
        var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
        $('#table-detail').find('input, textarea').removeAttr('disabled');
        $('#table-detail > tbody > tr').each(function() {
            $(this).find('input[name*="[nominal]"]').val(unformatNumber($(this).find('input[name*="[nominal]"]').val()));
        });
        var formData = $('#table-detail').find('input, textarea').serialize();
        $('#table-detail > tbody > tr').each(function() {
            $(this).find('input[name*="[nominal]"]').val(formatNumberForUser($(this).find('input[name*="[nominal]"]').val()));
        });
        $('#table-detail').find('input, textarea').attr('disabled', 'disabled');
        $.ajax({
            url: '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/urutkanKode']); ?>',
            type: 'POST',
            data: {
                tgl: tgl,
                formData: formData
            },
            success: function(data) {
                if (data) {
                    getItems();
                }
                $('#table-detail').removeClass('animation-loading');
            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function uangtunai() {
        var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
        openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/uangtunai', 'id' => '']) ?>' + tgl, 'modal-uangtunai', '400px', 'totalUangTunai();');
    }

    function totalUangTunai() {
        $('#place-totaluangtunai').addClass('animation-loading');
        var tgl = $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').val();
        $.ajax({
            url: '<?php echo \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/getUangTunai']); ?>',
            type: 'POST',
            data: {
                tgl: tgl
            },
            success: function(data) {
                $('#place-totaluangtunai').html(formatNumberForUser(data.total));
                $('#place-totaluangtunai').removeClass('animation-loading');
            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function checkKasbonKasbesar() {
        var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/checkKasbonKasbesar']); ?>';
        $(".modals-place-confirm").load(url, function() {
            $("#modal-global-confirm").modal('show');
            $("#modal-global-confirm").on('hidden.bs.modal', function() {
                location.reload();
            });
            spinbtn();
            draggableModal();
        });
    }

    function infoTBP(terima_bhp_id) {
        var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp', 'id' => '']); ?>' + terima_bhp_id;
        $(".modals-place-2").load(url, function() {
            $("#modal-info-tbp").modal('show');
            $("#modal-info-tbp").on('hidden.bs.modal', function() {

            });
            spinbtn();
            draggableModal();
        });
    }

    function infoKasbon(kas_bon_id) {
        var url = '<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/InfoKasbonkk', 'id' => '']); ?>' + kas_bon_id;
        $(".modals-place-2").load(url, function() {
            $("#modal-info-kasbonkk").modal('show');
            $("#modal-info-kasbonkk").on('hidden.bs.modal', function() {

            });
            spinbtn();
            draggableModal();
        });
    }

    function infoBKK(id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/kasir/bkk/detailBkk']) ?>?id=' + id, 'modal-bkk', '21cm');
    }

    function printBKK(id) {
        window.open("<?= yii\helpers\Url::toRoute('/kasir/bkk/printout') ?>?id=" + id + "&caraprint=PRINT", "", 'location=_new, width=1200px, scrollbars=yes');
    }

    function createBkk(id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/createBkk', 'id' => '']); ?>' + id, 'modal-global-confirm');
    }

    function multipleBkk() {
        $('#table-detail > tbody > tr').each(function() {
            $(this).find('#place-checkboxbkkmultiple').removeAttr('style');
        });
        $('#btn-bkkmultiple').removeClass('btn-outline');
        $('#btn-bkkmultiple').html('<span class="badge badge-danger" style="font-weight: 800; !important">0</span> Apply Multiple BKK');
        $('#btn-bkkmultiple').attr('onclick', 'createMultipleBkk()');
        $('#btn-urutan-place').append(" <a id='cancelmultiplebkk' style='margin-top: 10px;' onclick='cancelMultipleBkk()' class='btn btn-outline btn-sm red-flamingo'>Cancel Multiple</a>");
    }

    function checkThisMultiple() {
        var terchecklist = $('#table-detail > tbody > tr').find('input[name="checkboxbkkmultiple"]:checked').length;
        $('#btn-bkkmultiple .badge-danger').html(terchecklist);
    }

    function createMultipleBkk() {
        var par = [];
        $('#table-detail > tbody > tr').find('input[name="checkboxbkkmultiple"]:checked').each(function() {
            par.push($(this).parents('tr').find('input[name*="[kas_kecil_id]"]').val());
        });
        openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/createMultipleBkk', 'id' => '']); ?>' + par, 'modal-global-confirm');
    }

    function cancelMultipleBkk() {
        $('#btn-bkkmultiple').addClass('btn-outline');
        $('#btn-bkkmultiple').html('Multiple BKK');
        $('#btn-bkkmultiple').attr('onclick', 'multipleBkk();');
        $('#cancelmultiplebkk').remove();
        $('#table-detail > tbody > tr').each(function() {
            $(this).find('#place-checkboxbkkmultiple').attr('style', 'display:none;');
            $(this).find('#place-checkboxbkkmultiple input:checkbox').prop('checked', false);
        });
    }

    function editDeskripsi(kas_kecil_id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/kasir/pengeluarankaskecil/editDeskripsi', 'id' => '']); ?>' + kas_kecil_id, 'modal-edit-deskripsi', '60%');
    }
</script>