<link rel="icon" href="data:">
<style>
.datepicker{z-index:99999 !important;}
</style>

<div class="modal fade" id="modal-terima-bpb" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
<!-- <div class="modal fade" id="modal-terima-bpb" role="basic" aria-hidden="true"> -->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Rincian Target BPB'); ?> <strong><?= $model->bpb_kode; ?></strong></h4>
            </div>
			<?php 
			$form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'terima-barang-form',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                    'labelOptions'=>['class'=>'col-md-4 control-label'],
                ],
				'options' => [
					'method' => 'post',
				]
            ]); 
			?>
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Kode BPB'); ?></strong></label>
                            <div class="col-md-7"><?= $model->bpb_kode ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Nomor Berkas SPB'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->spb_nomor)?$model->spb_nomor:" - ") ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Dept. Pemesan'); ?></strong></label>
                            <div class="col-md-7"><?= $model->departement->departement_nama; ?></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Status'); ?></strong></label>
                            <div class="col-md-7 bpb_status">
                                <?php 
                                    if($model->bpb_status == 'BELUM DITERIMA'){
                                        echo '<span class="label label-sm label-info"> '.$model->bpb_status.' </span>';
                                    }else if($model->bpb_status == 'SUDAH DITERIMA'){
                                        echo '<span class="label label-sm label-success"> '.$model->bpb_status.' </span>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Tanggal Keluar'); ?></strong></label>
							<div class="col-md-7"><?= (!empty($model->bpb_tgl_keluar)?\app\components\DeltaFormatter::formatDateTimeForUser2($model->bpb_tgl_keluar):" - "); ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Dikeluarkan Oleh'); ?></strong></label>
                            <div class="col-md-7"><?= (!empty($model->bpb_dikeluarkan)?$model->bpbDikeluarkan->pegawai_nama:" - "); ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Tanggal Diterima'); ?></strong></label>
                            <div class="col-md-7 bpb_tgl_diterima"><?= $model->bpb_tgl_diterima; ?></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><strong><?= Yii::t('app', 'Diterima Oleh'); ?></strong></label>
                            <div class="col-md-7 bpb_diterima"><?= $model->bpb_diterima; ?></div>
                        </div>
						
                    </div>
                </div><br>
				<div class="row">
					<div class="col-md-12">
						<table class="table table-bordered table-advance table-detail-hover" id="table-detail-terimaBarang" style="background-color: #fff;">
							<thead>
								<tr>
									<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
									<th><?= Yii::t('app', 'Nama Barang'); ?></th>
									<th><?= Yii::t('app', 'Jumlah Pesan'); ?></th>
									<th><?= Yii::t('app', 'Jumlah Terpenuhi'); ?></th>
									<th style="display:none;"><?= Yii::t('app', 'Bpbd id'); ?></th>
									<th><?= Yii::t('app', 'Keterangan'); ?></th>
									<th><?= Yii::t('app', 'Set Plan'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($modDetail as $i => $detail){ 
									$detailspb = \app\models\TSpbDetail::getDetailItemSpb($model->spb_id, $detail->bhp_id);
								?>
								<tr>
									<td style="text-align: center;"><?php echo $i+1 ?></td>
									<td><?php echo $detail->bhp->bhp_nm; ?></td>
									<td style="text-align: center; "><?php echo $detailspb->spbd_jml; ?></td>
									<td style="text-align: center; "><?php echo $detail->bpbd_jml; ?></td>
									<td style="text-align: center; display:none;"><?php echo $detail->bpbd_id; ?></td>
									<td style="text-align: center; "><?php echo $detail->bpbd_ket; ?></td>
									<td style="text-align: center; ">
										<?php echo \yii\helpers\Html::checkbox('set_plan['.$i.']', false, ['id' => 'selected', 'class'=>'checkbox_'.$i, 'onclick'=>'setCheckbox('.$i.'); ']) ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="col-md-12">
						<!-- Bagian List Rincian -->
						<h4><?= Yii::t('app', 'Rincian Set Plan BPB'); ?></strong></h4>
							<table class="table table-bordered table-advance table-rincian-hover" id="table-rincian" style="background-color: #fff">
							<thead>
								<tr>
									<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
									<th style="text-align: center;"><?= Yii::t('app', 'Nama Barang'); ?></th>
									<th style="text-align: center;"><?= Yii::t('app', 'Target Plan'); ?></th>
									<th style="text-align: center;"><?= Yii::t('app', 'Peruntukan'); ?></th>
									<th style="text-align: center;"><?= Yii::t('app', 'Qty'); ?></th>
									<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
									<th style="text-align: center;"></th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
							<tfoot>
								<tr>
									<td colspan="6">
										<?php 
											echo \yii\helpers\Html::button('<i class="fa fa-plus"></i> Tambah Item', ['id' => 'btn-add-item', 'class' => 'btn btn-sm blue-hoki btn-add-item', 'disabled' => 'disabled', 'onclick'=>'addItemRincian('.$model->bpb_id.');']);
										?>
										<!-- <a class="btn btn-sm blue-hoki" id="btn-add-item" onclick="addItemRincian(<?php //echo $model->bpb_id; ?>);" style="margin-top: 10px;" disabled><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a> -->
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
            <div class="modal-footer">
				<?php echo \yii\helpers\Html::button(Yii::t('app', 'Save'), [
                    'class' => 'btn hijau btn-outline ciptana-spin-btn',
                    'onclick' => 'saveRincian();'
                ]); ?>
            </div>
			<?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
formconfig();
", yii\web\View::POS_READY); ?>
<script>
function addItemRincian(bpb_id) {
	$.ajax({
		url: '<?= \yii\helpers\Url::toRoute(['/logistik/spb/addItemRincian', 'bpb_id'=>'']); ?>'+bpb_id,
		type: 'POST',
		data: {},
		success: function(data) {
			if (data.item) {
				$(data.item).hide().appendTo('#table-rincian tbody').fadeIn(500, function() {
					$(this).find('select[name*="[bhp_id]"]').select({
						allowClear: !0,
						width: null,
						ajax: {
							url: '<?= \yii\helpers\Url::toRoute('/logistik/spb/addItemRincian') ?>',
							dataType: 'json',
							delay: 250,
							processResults: function(data) {
								return {
									results: data
								};
							},
							cache: true
						}
					});
					reordertable('#table-rincian');
				});
			}
		},
		error: function(jqXHR) {
			getdefaultajaxerrorresponse(jqXHR);
		},
	});
}

	function cancelItemRincian(ele) {
		$(ele).parents('tr').fadeOut(500, function() {
			$(this).remove();
			reordertable('#table-rincian');
		});
	}

	function reordertable(obj_table) {
		var row = 0;
		$(obj_table).find("tbody > tr").each(function() {
			$(this).find("#no_urut").val(row + 1);
			$(this).find("span.no_urut").text(row + 1);
			$(this).find('input,select,textarea').each(function() { //element <input>
				var old_name = $(this).attr("name").replace(/]/g, "");
				var old_name_arr = old_name.split("[");
				if (old_name_arr.length == 3) {
					$(this).attr("id", old_name_arr[0] + "_" + row + "_" + old_name_arr[2]);
					$(this).attr("name", old_name_arr[0] + "[" + row + "][" + old_name_arr[2] + "]");
				}
			});
			row++;
		});
		formconfig();
	}

	function setItemRincian(obj, bpbd_id = null) {
		if (!bpbd_id) {
			bpbd_id = $(obj).val();
		}
		$.ajax({
			url: '<?= \yii\helpers\Url::toRoute(['/logistik/spb/setItemRincian']); ?>',
			type: 'POST',
			data: {
				bpbd_id: bpbd_id
			},
			success: function(data) {
				if (data.bhp_harga) {
					$(obj).parents('tr').find('input[name*="[harga_peritem]"]').val(data.bhp_harga);
				}
			},
			error: function(jqXHR) {
				getdefaultajaxerrorresponse(jqXHR);
			},
		});
	}

	function setBhpNama(obj, bpbd_id = null) {
		if (!bpbd_id) {
			bpbd_id = $(obj).val();
		}
		$.ajax({
			url: '<?= \yii\helpers\Url::toRoute(['/logistik/spb/setBhpNama']); ?>',
			type: 'POST',
			data: {
				bpbd_id: bpbd_id
			},
			success: function(data) {
				if (data.bhp_nm) {
					$(obj).parents('tr').find('input[name*="[bhp_nm]"]').val(data.bhp_nm);
				}
			},
			error: function(jqXHR) {
				getdefaultajaxerrorresponse(jqXHR);
			},
		});
	}

	function setBhp(ele, bpb_id) {
		bpbd_id = $(ele).val();
		// console.log(bpbd_id);
		// console.log(bpb_id);
		$.ajax({
			url: '<?= \yii\helpers\Url::toRoute(['/logistik/spb/setBhp', 'bpb_id'=>'']); ?>' + bpb_id ,
			type: 'POST',
			data: {
				bpbd_id: bpbd_id, bpb_id: bpb_id
			},
			success: function(data) {
				if (data.bhp_id) {
					$(ele).parents('tr').find('input[name*="[bhp_id]"]').val(data.bhp_id);
				}
				if (data.bpbd_ket) {
					$(ele).parents('tr').find('textarea[name*="[keterangan]"]').val(data.bpbd_ket);
				}
			},
			error: function(jqXHR) {
				getdefaultajaxerrorresponse(jqXHR);
			},
		});
	}

	function setCheckbox(index){
		var checkboxes = document.querySelectorAll('input[type=checkbox]');
		var anyChecked = false;
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                anyChecked = true;
            }
        });
        if (anyChecked) {
            // console.log('OKE');
			$('.btn-add-item').prop('disabled', false);
			return true;
        } else {
             // console.log('NOOOOO');
			 $('.btn-add-item').prop('disabled', true);
			 $('#table-rincian tbody tr').remove();
			return false;
        }
	}

	function saveRincian() {
		var $form = $('#terima-barang-form');

		if (formrequiredvalidate($form)) {
			var jumlah_item = $('#table-rincian tbody tr').length;
			if(setCheckbox()){
				if(jumlah_item <= 0){
					cisAlert('Isi rincian set plan terlebih dahulu');
					return false;
				} else {
					if(validatingDetail()){
						if(validateBarang()){
							if(validateJml()){
								submitformajax($form , "$(\'#modal-terima-bpb\').modal(\'hide\');javascript:window.location.reload()");
								return true;
							}
							return false;
						}
						return false;
					} 
					return false;
				}
			} else {
				save();
			}
		} 
		return false;
	}

	function validatingDetail() {
		var has_error = 0;

		$('#table-rincian tbody > tr').each(function() {
			if ($(this).find('select[name*="[bhp_id]"]').length > 0) {
				var field1content = 'select[name*="[bhp_id]"]';
			} else {
				var field1content = 'input[name*="[bhp_id]"]';
			}
			if ($(this).find('select[name*="[target_plan]"]').length > 0) {
				var field3content = 'select[name*="[target_plan]"]';
			} else {
				var field3content = 'input[name*="[target_plan]"]';
			}
			if ($(this).find('select[name*="[target_peruntukan]"]').length > 0) {
				var field4content = 'select[name*="[target_peruntukan]"]';
			} else {
				var field4content = 'input[name*="[target_peruntukan]"]';
			}
			var field1 = $(this).find(field1content);
			var field2 = $(this).find('input[name*="[qty]"]');
			var field3 = $(this).find(field3content);
			var field4 = $(this).find(field4content);

			if (!field1.val()) {
				$(this).find(field1content).parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			} else {
				$(this).find(field1content).parents('td').removeClass('error-tb-detail');
			}
			if (!field2.val()) {
				has_error = has_error + 1;
				$(this).find('input[name*="[qty]"]').parents('td').addClass('error-tb-detail');
			} else {
				if (unformatNumber(field2.val()) <= 0) {
					has_error = has_error + 1;
					$(this).find('input[name*="[qty]"]').parents('td').addClass('error-tb-detail');
				} else {
					$(this).find('input[name*="[qty]"]').parents('td').removeClass('error-tb-detail');
				}
			}
			if (!field3.val()) {
				$(this).find(field3content).parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			} else {
				$(this).find(field3content).parents('td').removeClass('error-tb-detail');
			}
			if (!field4.val()) {
				$(this).find(field4content).parents('td').addClass('error-tb-detail');
				has_error = has_error + 1;
			} else {
				$(this).find(field4content).parents('td').removeClass('error-tb-detail');
			}
		});
		if (has_error === 0) {
			return true;
		}
		return false;
	}

	function save(){
		$(".modals-place-confirm").load('<?php echo \yii\helpers\Url::toRoute(['/logistik/spb/terimaBarang','id'=>$model->bpb_id]) ?>', function() {
			$("#modal-delete-record").modal('show');
			$("#modal-delete-record").on('hidden.bs.modal', function () {
			});
			spinbtn();
			draggableModal();
		});
		return false; 
	}

	function validateBarang(){
		var has_error = 0;

		var bhp_ceklis = [];
		var jml_data = $('#table-detail-terimaBarang tbody tr').length;
		for (var j = 0; j < jml_data; j++){
			var index = j+1;
			var set_plan = $('.checkbox_' + j);
			var bpbd_id = $('#table-detail-terimaBarang tbody').find('tr:nth-child('+ index +') td:nth-child(5)').text();
			if(set_plan.is(':checked')){
				bhp_ceklis.push(bpbd_id);
			}
		}
		
		var bhp_input = [];
		var jumlah_item = $('#table-rincian tbody tr').length;
		for(var i = 0; i < jumlah_item; i++){
			var bpbd_input = document.querySelector('[name*="['+ i +'][bpbd_id]"]').value;
			bhp_input.push(bpbd_input);
		}

		if (isSubset(bhp_ceklis, bhp_input)) {
			has_error;
		} else {
			has_error += 1;
			cisAlert('Ada data yang belum diset!! Mohon cek kembali');
		}

		if (isSubsets(bhp_ceklis, bhp_input)) {
			has_error;
		} else {
			has_error += 1;
			cisAlert('Silakan cek kembali pada CEKLIS set plan di atas!!');
		}		
		
		if(has_error === 0){
			return true;
		}
		return false;
	}

	function isSubset(A, B) {
		return A.every(function(element) {
			return B.includes(element);
		});
	}

	function isSubsets(A, B) {
		return B.every(function(element) {
			return A.includes(element);
		});
	}

	function validateJml(){
		var data = [];
		var jml_data = $('#table-detail-terimaBarang tbody tr').length;
		for (var j = 0; j < jml_data; j++){
			var index = j+1;
			var set_plan = $('.checkbox_' + j);
			var bpbd_id = $('#table-detail-terimaBarang tbody').find('tr:nth-child('+ index +') td:nth-child(5)').text();
			var qty_ceklis = unformatNumber($('#table-detail-terimaBarang tbody').find('tr:nth-child('+ index +') td:nth-child(4)').text());
			if(set_plan.is(':checked')){
				data.push({bpbd_id: bpbd_id, qty_ceklis: qty_ceklis});
			}
		}
		
		var data_input = [];
		var data_harga = [];
		var jumlah_item = $('#table-rincian tbody tr').length;
		for(var i = 0; i < jumlah_item; i++){
			var bpbd_input = document.querySelector('[name*="['+ i +'][bpbd_id]"]').value;
			var qty_input = unformatNumber(document.querySelector('[name*="['+ i +'][qty]"]').value);
			data_input.push({bpbd_input: bpbd_input, qty_input: qty_input});

			var bhp_nm = document.querySelector('[name*="['+ i +'][bhp_nm]"]').value;
			var harga_peritem = unformatNumber(document.querySelector('[name*="['+ i +'][harga_peritem]"]').value);
			data_harga.push({bhp_nm:bhp_nm, harga_peritem: harga_peritem});
		}

		if(cekJml(data, data_input) && cekHarga(data, data_harga)){
			return true;
		} else {
			return false;
		}
	}

	function cekJml(data, data_input){
		var has_error = 0;
		var qty_keluar = {};

		data.forEach(barang => {
			qty_keluar[barang.bpbd_id] = 0;
		});

		data_input.forEach(barang => {
			qty_keluar[barang.bpbd_input] += barang.qty_input;
		});

		for(var i = 0; i < data.length; i++){
			var qty_terpenuhi = data[i].qty_ceklis;
			var qty = qty_keluar[data[i].bpbd_id];
			if(qty === qty_terpenuhi){
				has_error;
			}
			if(qty > qty_terpenuhi){
				has_error = has_error + 1;
				cisAlert('Terdapat qty barang yang <b>MELEBIHI</b> jumlah terpenuhi! Mohon cek kembali');
			} 
			if(qty < qty_terpenuhi){
				has_error = has_error + 1;
				cisAlert('Terdapat qty barang yang <b>KURANG</b> dari jumlah terpenuhi! Mohon cek kembali');
			} 
		}

		if(has_error === 0){
			// console.log('tidak ada error');
			return true;
		} 
		// console.log('error!!!!');
		return false;
	}

	function cekHarga(data, data_harga){
		var has_error = 0;

		data_harga.forEach(barang => {
			if(barang.harga_peritem == '' || barang.harga_peritem == 0 || barang.harga_peritem < 0){
				has_error = has_error + 1;
				cisAlert(barang.bhp_nm +' belum diupdate harga penerimaan, mohon hubungi Purchasing!!');
			}
		});

		if(has_error === 0){
			return true;
		} 
		return false;
	}

</script>