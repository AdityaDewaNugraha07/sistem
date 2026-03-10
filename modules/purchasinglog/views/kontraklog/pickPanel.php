<?php
app\assets\InputMaskAsset::register($this);
?>
<div class="row">
    <div class="col-md-12">
		<div class="caption" style="margin-bottom: 30px;">
			<button onclick="closePickPanel()" type="button" class="btn btn-icon-only btn-default fa fa-close" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
			<span class="caption-subject bold"><h4><?= Yii::t('app', 'Master Supplier Log'); ?></h4></span>
		</div>
		<div class="table-scrollable">
			<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-spp">
				<thead>
					<tr>
						<th style="width: 30px;" ><?= Yii::t('app', 'Add'); ?></th>
						<th ><?= Yii::t('app', 'Wakil Perusahaan'); ?></th>
						<th ><?= Yii::t('app', 'Nama Perusahaan'); ?></th>
						<th><?= Yii::t('app', 'Alamat'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php						
						if(count($modItems)>0){
						foreach($modItems as $i => $item){
					?>
					<tr>
						<td>
							<a class="btn btn-xs btn-success btn-outline" data-pick="<?= $item->suplier_id; ?>" onclick="pick(this)"><i class="fa fa-plus"></i></a>
						</td>
						<td><?= $item->suplier_nm; ?></td>
						<td><?= $item->suplier_nm_company; ?></td>
						<td><?= $item->suplier_almt; ?></td>
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
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/kontraklog/setPickedItem']); ?>',
		type   : 'POST',
		data   : {suplier_id:$(ele).data('pick')},
		success: function (data) {
			if(data){
				$('#<?= yii\bootstrap\Html::getInputId($model, 'pihak1_nama') ?>').val(data.suplier_nm);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val(data.suplier_id);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'pihak1_perusahaan') ?>').val(data.suplier_nm_company);
				$('#<?= yii\bootstrap\Html::getInputId($model, 'pihak1_alamat') ?>').val(data.suplier_almt);
				closePickPanel();
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>