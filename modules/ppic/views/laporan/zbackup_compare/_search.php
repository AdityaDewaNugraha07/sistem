<?php
$modelTerimaLogalam = new \app\models\TTerimaLogalam();
isset($_POST['TTerimaLogalam']['area_pembelian']) ? $modelTerimaLogalam->area_pembelian = $_POST['TTerimaLogalam']['area_pembelian'] : $modelTerimaLogalam->area_pembelian = 'Luar Jawa';
isset($_POST['TTerimaLogalam']['pengajuan_pembelianlog_id']) ? $pengajuan_pembelianlog_id = $_POST['TTerimaLogalam']['pengajuan_pembelianlog_id'] : $pengajuan_pembelianlog_id = '';
//isset($_POST['TTerimaLogalam']['pengajuan_pembelianlog_id']) && $_POST['TTerimaLogalam']['pengajuan_pembelianlog_id'] > 0 ? $display1 =  "block" : $display1 = "none";
isset($_POST['TTerimaLogalam']['spk_shipping_id']) ? $spk_shipping_id = $_POST['TTerimaLogalam']['spk_shipping_id'] : $spk_shipping_id = '';
//isset($_POST['TTerimaLogalam']['spk_shipping_id']) && $_POST['TTerimaLogalam']['spk_shipping_id'] > 0 ? $display2 =  "block" : $display2 = "none";
?>
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered form-search">
			<div class="portlet-title">
				<div class="tools panel-cari">
					<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
					<span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
				</div>
			</div>
			<div class="portlet-body">
				<?php $form = \yii\bootstrap\ActiveForm::begin([
					'id' => 'form-search-laporan',
					'fieldConfig' => [
						'template' => '{label}<div class="col-md-8">{input} {error}</div>',
						'labelOptions'=>['class'=>'col-md-3 control-label'],
					],
					'enableClientValidation'=>false
				]); ?>
				<div class="modal-body">
					<div class="row">
                        
                        <div class="col-md-4">
                            <div id="area_pembelian">
                                <?= $form->field($modelTerimaLogalam, 'area_pembelian',['wrapperOptions' => ['style' => 'display:inline-block']])->inline(true)->radioList(['Jawa'=>'Jawa','Luar Jawa'=>'Luar Jawa'],['onchange'=>'showKodeKeputusan();'])->label('Area'); ?>
                            </div>
                        </div>

                        <div class="col-md-8">
                            
                            <?php // t_pengajuan_pembelian_log ;?>
                            <div id="kode_keputusan" class="form-group" style="margin-bottom: 5px; display: none;">
                                <label id="label_kode_keputusan" class="col-md-4 control-label"><?= Yii::t('app', 'Kode Keputusan'); ?></label>
                                <div class="col-md-6">
                                    <span class="input-group-btn" style="width: 100%">
                                        <?= \yii\bootstrap\Html::activeDropDownList($modelTerimaLogalam, 'pengajuan_pembelianlog_id', \app\models\TPengajuanPembelianlog::getOptionListPenerimaanLogAlam(),['class'=>'form-control select2','prompt'=>$modelTerimaLogalam->pengajuan_pembelianlog_id]); ?>
                                    </span>
                                    <span class="input-group-btn" style="width: 30%">
                                        <a id="span_button_kode_keputusan" class="btn btn-icon-only btn-default tooltips" onclick="openDaftarKeputusanPembelianLog();" data-original-title="Daftar OP" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
                                    </span>
                                </div>
                            </div>
                            <?php /* eo t_pengajuan_pembelian_log_id; */?>

                            <?php // t_spk_shipping ;?>
                            <?php 
                            //if (empty($_POST['TTerimaLogalam']['area_pembelian']) || $spk_shipping_id > 0) {
                            ?>
                            <div id="kode_spmlog" class="form-group" style="margin-bottom: 5px; display: block;">
                                <label id="label_kode_spm_log" class="col-md-4 control-label text-left"><?= Yii::t('app', 'Kode SPM Log'); ?></label>
                                <div class="col-md-8">
                                    <span class="input-group-btn" style="width: 270px;">
                                        <?= \yii\bootstrap\Html::activeDropDownList($modelTerimaLogalam, 'spk_shipping_id', \app\models\TSpkShipping::getOptionList(),['class'=>'form-control select2','prompt'=>$modelTerimaLogalam->spk_shipping_id]); ?>
                                    </span>
                                    <span class="input-group-btn" style="width: 30px;">
                                        <a id="span_button_kode_spm_log" class="btn btn-icon-only btn-default tooltips" onclick="openDaftarSpmLog();" data-original-title="Daftar SPM Log" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
                                    </span>
                                </div>
                            </div>
                            <?php
                            //}
                            ?>
                            <?php // eo t_spk_shipping ;?>
                        </div>
                        <?php /*<?php echo $this->render('@views/apps/form/tombolSearch') ?> */?>
                        <div class="col-md-1 pull-right" style="position: relative;">
                            <button type="button" class="btn hijau btn-outline ciptana-spin-btn pull-right ladda-button" name="search-laporan" data-style="zoom-in" onclick="cupet();"><span class="ladda-label">Search</span><span class="ladda-spinner"></span><span class="ladda-spinner"></span></button>
                        </div>
				    </div>
                </div>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
				<?php \yii\bootstrap\ActiveForm::end(); ?>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>

<script>
function showKodeKeputusan() {
    var area_pembelian = ($('input[name*="[area_pembelian]"]:checked').val());
    if (area_pembelian == 'Jawa') {
        $('#kode_keputusan').show();
        $('#kode_spmlog').hide();
        $('select[name*="[spk_shipping_id]"]').val('');
    } else {
        $('#kode_keputusan').hide();
        $('#kode_spmlog').show();
        $('select[name*="[pengajuan_pembelianlog_id]"]').val('');
    }
}

function openDaftarKeputusanPembelianLog(){
    var url = '<?= \yii\helpers\Url::toRoute(['/ppic/terimalogalam/daftarKeputusanPembelianLog']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-daftarKeputusanPembelianLog .modal-dialog").css('width','90%');
		$("#modal-daftarKeputusanPembelianLog").modal('show');
		$("#modal-daftarKeputusanPembelianLog").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal(1);
	});
}

function pickDaftarKeputusanPembelianLog(pengajuan_pembelianlog_id, kode){
	$("#modal-daftarKeputusanPembelianLog").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($modelTerimaLogalam, "pengajuan_pembelianlog_id") ?>").empty().append('<option value="'+pengajuan_pembelianlog_id+'">'+kode+'</option>').val(pengajuan_pembelianlog_id).trigger('change');
}

function openDaftarSpmLog(){
    var url = '<?= \yii\helpers\Url::toRoute(['/ppic/terimalogalam/daftarSpmLog']); ?>';
	$(".modals-place-3-min").load(url, function() {
		$("#modal-daftarSpmLog .modal-dialog").css('width','90%');
		$("#modal-daftarSpmLog").modal('show');
		$("#modal-daftarSpmLog").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}

function pickDaftarSpmLog(spk_shipping_id, kode){
	$("#modal-daftarSpmLog").find('button.fa-close').trigger('click');
	$("#<?= yii\bootstrap\Html::getInputId($modelTerimaLogalam, "spk_shipping_id") ?>").empty().append('<option value="'+spk_shipping_id+'">'+kode+'</option>').val(spk_shipping_id).trigger('change');
}

function cupet(){
    var area_pembelian = ($('input[name*="[area_pembelian]"]:checked').val());
    var pengajuan_pembelianlog_id = ($('select[name*="[pengajuan_pembelianlog_id]"]').val());
    var spk_shipping_id = ($('select[name*="[spk_shipping_id]"]').val());

    if ((area_pembelian == "Jawa" && pengajuan_pembelianlog_id > 0) || (area_pembelian == "Luar Jawa" && spk_shipping_id > 0)) {
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/ppic/laporan/compares']); ?>',
            type   : 'POST',
            data   : {area_pembelian:area_pembelian, pengajuan_pembelianlog_id:pengajuan_pembelianlog_id, spk_shipping_id:spk_shipping_id},
            success: function (data) {
                if(data.html_loglist || data.html_terima){
                    $('#table-loglist tbody').html(data.html_loglist);
                    $('#table-terima tbody').html(data.html_terima);
                    //mergeSameValue();
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    } else {
        cisAlert('Data pencarian tidak valid');
    }
}

function mergeSameValue(){
	var arr = [];
	var coll = [0,1,2,3,4,5,6,7,8,9,10];
	$("#table-informasi").find('tr').each(function (r, tr) {
		$(this).find('td').each(function (d, td) {
			if ( coll.indexOf(d) !== -1) {
				var $td = $(td);
				var v_dato = $td.html();
				if(typeof arr[d] != 'undefined' && 'dato' in arr[d] && arr[d].dato == v_dato) {
					var rs = arr[d].elem.data('rowspan');
					if(rs == 'undefined' || isNaN(rs)) rs = 1;
					arr[d].elem.data('rowspan', parseInt(rs) + 1).addClass('rowspan-combine');
					$td.addClass('rowspan-remove');
				} else {
					arr[d] = {dato: v_dato, elem: $td};
				};
			} else {
            }
		});
	});
	$('.rowspan-combine').each(function (r, tr) {
	  var $this = $(this);
	  $this.attr('rowspan', $this.data('rowspan')).css({'vertical-align': 'middle'});
	});
	$('.rowspan-remove').remove();
}
</script>