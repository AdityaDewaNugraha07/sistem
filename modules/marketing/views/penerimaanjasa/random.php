<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<style>
.table-random, 
.table-random > tbody > tr > td, 
.table-random > tbody > tr > th, 
.table-random > tfoot > tr > td, 
.table-random > tfoot > tr > th, 
.table-random > thead > tr > td, 
.table-random > thead > tr > th {
    border: 1px solid #A0A5A9;
	line-height: 0.9 !important;
	font-size: 1.2rem;
}
</style>
<div class="modal fade" id="modal-random" tabindex="-1" role="basic" aria-hidden="true" style="margin-top: -50px; height: 650px;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="height: 560px;">
            <div class="modal-header" style="height: 40px;">
                <button type="button" class="btn btn-xs fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-bottom: 2px;"></button>
                <h6 class="modal-title"><?= Yii::t('app', 'Random Produk'); ?> <b><?= $modProduk->produk_kode ?> - <?= $modProduk->produk_nama ?></b></h6>
            </div>
            <div class="modal-body" style="max-height: 480px;">
                <div class="row">
                    <div class="col-md-4">
						<h5>Available Palet</h5>
                        <table class="table table-striped table-bordered table-hover table-laporan table-random" id="table-palet">
							<thead>
								<tr>
									<th ><?= Yii::t('app', 'Kode Barang Jadi') ?></th>
									<th style="width: 20%;">Pilih</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(count($models)>0){
									foreach($models as $i => $model){ 
										$checked = "";
										if (in_array($model['nomor_produksi'], $nomor_produksi_random)) {
											$checked = "checked=''";
										}
								?>
										<tr>
											<td style="text-align: center">
												<?= yii\bootstrap\Html::hiddenInput('nomor_produksi',$model['nomor_produksi']) ?>
												<?= $model['nomor_produksi'] ?>
											</td>
											<td style="text-align: center"><input type="checkbox" <?= $checked; ?> name="[<?= $i ?>]pilih" onclick="check(this,'<?= $model['nomor_produksi'] ?>')"></td>
										</tr>
								<?php } ?>
								<?php } ?>
							</tbody>
						</table>
                    </div>
                    <div class="col-md-8">
						<h5>Detail Random Produk Terpilih</h5>
                        <table class="table table-striped table-bordered table-hover table-laporan table-random" id="table-random">
							<thead>
								<tr>
									<th>No.</th>
									<th>Kode Barang Jadi</th>
									<th>Dimension (t x l x p)</th>
									<th style="width: 50px;">Qty (Pcs)</th>
									<th style="width: 80px;">M<sup>3</sup></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3" style="text-align: right"><b>Total</b></td>
									<td style="text-align: right"><b><input type="text" disabled="disabled" name="tot_qty" style="width: 100px; font-weight: 600; text-align: center;"></b></td>
									<td style="text-align: right"><b><input type="text" disabled="disabled" name="tot_kubikasi" style="width: 100px; font-weight: 600; text-align: right;"></b></td>
								</tr>
							</tfoot>
						</table>
                    </div>
                </div>

        </div>
        <div class="modal-footer" style="height: 40px;">
            <?= yii\bootstrap\Html::hiddenInput('reff_ele',$tr_seq) ?>
            <?= yii\bootstrap\Html::hiddenInput('nomor_produksi_all') ?> 
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs(" 
	setTimeout(function(){ 
		check();  
	}, 300); ", yii\web\View::POS_READY); ?>
<script>
function check(){
	var kbj = "";
	var total_checked = 0;
	var tot_qty = 0;
	var tot_kubikasi = 0;
	$("#table-palet > tbody > tr").each(function(i){
		if($(this).find("input[name*='pilih']").is(":checked")){
			kbj += "'"+$(this).find("input[name*='nomor_produksi']").val()+"'";
			total_checked = total_checked+1;
			if($("#table-palet > tbody > tr").find("input[name*='pilih']:checked").length != (total_checked)){
				kbj += ",";
			}
		}
	});
	$("input[name='nomor_produksi_all']").val(kbj);
	$('#table-random > tbody').html("<tr><td colspan='5' style='text-align:center'><i>Not Found</i></td></tr>");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/getRandom']); ?>',
		type   : 'POST',
		data   : {nomor_produksi:kbj},
		success: function (data) {
			if(data.html){
				$('#table-random > tbody').html(data.html);
				$("input[name='tot_qty']").val(data.tot_qty);
				$("input[name='tot_kubikasi']").val(data.tot_kubikasi);
			}else{
				$("input[name='tot_qty']").val("0");
				$("input[name='tot_kubikasi']").val("0");
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>