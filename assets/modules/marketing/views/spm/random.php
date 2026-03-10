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
<div class="modal fade" id="modal-random" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Random Produk'); ?> <b><?= $modProduk->produk_kode ?></b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<h5>Detail Random Produk</h5>
                        <table class="table table-striped table-bordered table-hover table-laporan table-random" id="table-random" style="width: 100%;">
							<thead>
								<tr>
									<th>No.</th>
									<th>Produk</th>
									<th>Dimension (t x l x p)</th>
									<th style="width: 50px;">Qty (Pcs)</th>
									<th style="width: 80px;">M<sup>3</sup></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$tot_qty = 0;
								$tot_kubikasi = 0;
								if(count($models)>0){
									foreach($models as $i => $model){
										echo "<tr>";
										echo "<td><center>".($i+1)."</center></td>";
										echo "<td>".$modProduk->NamaProduk."</td>";
										echo "<td>".$model['t'].$model['t_satuan']." x ".$model['l'].$model['l_satuan']." x ".$model['p'].$model['p_satuan']."</td>";
										echo "<td style='text-align:center;'>".$model['qty_kecil']."</td>";
										echo "<td style='text-align:right;'>".$model['kubikasi']."</td>";
										echo "</tr>";
										$tot_qty += $model['qty_kecil'];
										$tot_kubikasi += $model['kubikasi'];
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3" style="text-align: right"><b>Total</b></td>
									<td style="text-align: right"><b><input type="text" disabled="disabled" name="tot_qty" value="<?= $tot_qty ?>" style="width: 100px; font-weight: 600; text-align: center;"></b></td>
									<td style="text-align: right"><b><input type="text" disabled="disabled" name="tot_kubikasi" value="<?= $tot_kubikasi ?>" style="width: 100px; font-weight: 600; text-align: right;"></b></td>
								</tr>
							</tfoot>
						</table>
                    </div>
                </div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs(" 
	setTimeout(function(){ 
		 
	}, 300); ", yii\web\View::POS_READY); ?>
<script>
</script>