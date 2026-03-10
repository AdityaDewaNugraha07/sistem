<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-info-palet" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Info Palet'); ?></h4>
            </div>
            <div class="modal-body">
				<?php if(!empty($modTerima)){ ?>
                <div class="row">
                    <div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Jenis Produk</label>
							<div class="col-md-7"><strong><?= $modTerima->produk->produk_group ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Kode Produk</label>
							<div class="col-md-7"><strong><?= $modTerima->produk->produk_kode ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Nama Produk</label>
							<div class="col-md-7"><strong><?= $modTerima->produk->produk_nama ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Dimensi</label>
							<div class="col-md-7"><strong><?= $modTerima->produk->produk_dimensi ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Isi Palet</label>
							<div class="col-md-7"><strong><?= $modTerima->qty_kecil ?> <?= $keperluan == 'Penanganan Barang Retur'?'Pcs':$modTerima->qty_kecil_satuan ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Kubikasi</label>
							<div class="col-md-7"><strong><?= $keperluan == 'Penanganan Barang Retur'?app\components\DeltaFormatter::formatNumberForUserFloat($modTerima->kubikasi,4):app\components\DeltaFormatter::formatNumberForUserFloat($modTerima->qty_m3,4) ?> M<sup>3</sup></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Jenis Palet</label>
							<div class="col-md-7">
								<strong>
									<?php 
									if($keperluan == 'Penanganan Barang Retur'){
										echo '-';
									} else {
										if($modTerima->jenis_penerimaan=="Khusus"){
											echo "<span class='font-blue-steel'>Random</span>";
										} else {
											echo $modTerima->jenis_penerimaan;
										}
									}
									?>
									<?php //echo ($modTerima->jenis_penerimaan=="Khusus")?"<span class='font-blue-steel'>Random</span>":$modTerima->jenis_penerimaan; ?>
								</strong>
							</div>
						</div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Nomor Produksi</label>
							<div class="col-md-7"><strong class="font-blue-steel"><?= $modTerima->nomor_produksi ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Kode Penerimaan Gudang</label>
							<div class="col-md-7"><strong ><?= $keperluan == 'Penanganan Barang Retur'?$modTerima->gudang->gudang_nm:$modTerima->kode ?></strong></div>
						</div>
						<?php if($keperluan !== 'Penanganan Barang Retur'){ ?>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Tgl Penerimaan Gudang</label>
							<div class="col-md-7"><strong ><?= app\components\DeltaFormatter::formatDateTimeForUser2($modTerima->tanggal) ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Tgl Produksi</label>
							<div class="col-md-7"><strong ><?= app\components\DeltaFormatter::formatDateTimeForUser2($modTerima->tanggal_produksi) ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Lokasi Gudang</label>
							<div class="col-md-7"><strong ><?= $modTerima->gudang->gudang_nm ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Shift / Line</label>
							<div class="col-md-7"><strong ><?= ($modProduksi->plymill_shift!="-")?$modProduksi->plymill_shift:""; ($modProduksi->sawmill_line!="-")?$modProduksi->sawmill_line:""; ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Keterangan</label>
							<div class="col-md-7"><strong ><?= $modTerima->keterangan ?></strong></div>
						</div>
						<?php } ?>
                    </div>
                </div>
				<?php if(count($modTerimaRandom)>0){ ?>
				<br>
				<div class="row">
                    <div class="col-md-12">
						<h4>Detail Komposisi Random</h4>
						<div class="table-scrollable">
							<table id="table-detail" class="table table-striped table-bordered table-advance table-hover" style="width: 90%">
								<thead>
									<tr>
										<th rowspan="2" style="width: 30px;">No.</th>
										<th colspan="3">Dimensi</th>
										<th rowspan="3">Qty</th>
										<th rowspan="3">Kubikasi M<sup>3</sup></th>
									</tr>
									<tr>
										<th>Tebal</th>
										<th>Panjang</th>
										<th>Lebar</th>
									</tr>
								</thead>
								<?php $totalpcs=0;$totalm3=0; foreach($modTerimaRandom as $i => $random){ $totalpcs+=$random->qty; $totalm3+=$random->kapasitas_kubikasi; ?>
								<tbody>
									<td style="vertical-align: middle; text-align: center; padding: 3px;"><?= $i+1; ?></td>
									<td style="vertical-align: middle; text-align: center; padding: 3px;">
										<?= $random->t; ?> <?= $random->t_satuan ?>
									</td>
									<td style="vertical-align: middle; text-align: center; padding: 3px;">
										<?= $random->p; ?> <?= $random->p_satuan ?>
									</td>
									<td style="vertical-align: middle; text-align: center; padding: 3px;">
										<?= $random->l; ?> <?= $random->l_satuan ?>
									</td>
									<td style="vertical-align: middle; text-align: right; padding: 3px;">
										<?= $random->qty; ?> (<?= $random->qty_satuan ?>)	
									</td>
									<td style="vertical-align: middle; text-align: right; padding: 3px;">
										<?= app\components\DeltaFormatter::formatNumberForUserFloat($random->kapasitas_kubikasi) ?>
									</td>
								</tbody>
								<?php } ?>
								<tfoot>
									<tr style="background-color: #F1F4F7;">
										<td colspan="4" style="text-align: right"><b>TOTAL &nbsp;</b></td>
										<td style="text-align: right"><b><?= $totalpcs ?></b></td>
										<td style="text-align: right"><b><?= $totalm3 ?></b></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php }else{ "<center>Data tidak ditemukan</center>"; } ?>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    
", yii\web\View::POS_READY); ?>
<script>

</script>