<?php
app\assets\InputMaskAsset::register($this);
?>
<div class="row">
	<div class="col-md-12">
		<div class="caption" style="margin-bottom: 30px;">
			<button onclick="closePickPanel()" type="button" class="btn btn-icon-only btn-default fa fa-close" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
			<span class="caption-subject bold"><h4><?= Yii::t('app', 'Item SPP yang diterima'); ?></h4></span>
		</div>
		<div class="table-scrollable">
			<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-spp">
				<thead>
					<tr>
						<th style="width: 30px;" ><?= Yii::t('app', 'Add'); ?></th>
						<th style="text-align: center;" ><?= Yii::t('app', 'Nama Item'); ?></th>
						<th style="width: 50px;"><?= Yii::t('app', 'Harga Terakhir @'); ?></th>
						<th style="text-align: center;"><?= Yii::t('app', 'Qty'); ?></th>
						<th style="text-align: center;"><?= Yii::t('app', 'Satuan'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php						
						if(count($modGroup)>0){
						foreach($modGroup as $i => $group){
					?>
					<tr>
						<td>
							<a class="btn btn-xs btn-success btn-outline" data-pick="<?= $group->bhp_id; ?>" onclick="pick(this)"><i class="fa fa-plus"></i></a>
							<?php echo yii\bootstrap\Html::activeHiddenInput($group, '[ii]sppd_qty',['readonly'=>'readonly']); ?>
						</td>
						<td><?= $group->bhp->bhp_nm; ?></td>
						<td style=" text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($group->bhp->bhp_harga); ?></td>
						<td><center><?= $group->sppd_qty; ?></center></td>
						<td><?= $group->bhp->bhp_satuan; ?></td>
					</tr>
					<?php } ?>
					<?php }else{ ?>
					<tr><td colspan="5"><i><?= Yii::t('app', 'Data tidak ditemukan'); ?></i></td></tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
function pick(ele){
	var qty = $(ele).parents("tr").find('input[name*="[sppd_qty]"]').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasing/spl/addItem']); ?>',
		type   : 'POST',
		data   : {bhp_id:$(ele).data('pick'),qty:qty},
		success: function (data) {
			if(data.html){
				var unique = true;
				var bhp_id = data.detail.bhp_id;
				$('#table-detail tbody tr').each(function(index){
					if($(this).find('select[name*="[bhp_id]"]').val() == bhp_id){
						unique &= false;
					}else{
						unique &= true;
					}
				});
				if(unique){
					$(data.html).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
						setDropdownBhp($(this));
						$(this).find('select[name*="[bhp_id]"]').select2({
							allowClear: !0,
							placeholder: 'Ketik nama item',
							width: null
						});
						setTimeout(function(){
							var obj = $('#table-detail tbody tr:last').find('#no_urut');
							$(obj).parents('tr').find('select[name*="[bhp_id]"]').val(bhp_id).trigger("change");
							$(obj).parents('tr').find('select[name*=\"[bhp_id]\"]').on('change',function(){
								setItem(this);
							});
							$(obj).parents('tr').find('input[name*="[spld_qty]"]').val(qty);
							$(obj).parents('tr').find('input[name*="[spld_harga_estimasi]"]').val( formatInteger(data.detail.bhp_harga) );
							reordertable('#table-detail');
						},300);
						
					});
				}else{
					cisAlert("Barang tersebut sudah ada di list");
				}
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
	return false;
}




</script>