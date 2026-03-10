<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-info-palet" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Info Kayu Bulat : '); ?><strong class="font-blue-steel"><?= $no_barcode; ?></strong></h4>
            </div>
            <div class="modal-body">
				<?php if(!empty($modLogKeluar)){ ?>
                <div class="row">
                    <div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Jenis Kayu</label>
							<div class="col-md-7"><strong><?= $modKayu->group_kayu .' - '. $modKayu->kayu_nama; ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Kode Log</label>
							<div class="col-md-7"><strong><?= $modBrgLog['log_kode']; ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Nama Log</label>
							<div class="col-md-7"><strong><?= $modBrgLog['log_nama']; ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Range Diameter</label>
							<div class="col-md-7"><strong><?= $modBrgLog['range_awal'] .'cm - '. $modBrgLog['range_akhir'] .'cm'?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">No Lapangan</label>
							<div class="col-md-7"><strong><?= $modPersediaan->no_lap; ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">No Grade</label>
							<div class="col-md-7"><strong><?= $modPersediaan->no_grade; ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">No Batang</label>
							<div class="col-md-7"><strong><?= $modPersediaan->no_btg; ?></strong></div>
						</div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">Kode Potong</label>
							<div class="col-md-7"><strong><?= (empty($modPersediaan->pot))?"-":$modPersediaan->pot; ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Diameter Rata</label>
							<div class="col-md-7"><strong><?= $modPersediaan->fisik_diameter; ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Volume</label>
							<div class="col-md-7"><strong><?= $modPersediaan->fisik_volume; ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Cacat Panjang</label>
							<div class="col-md-7"><strong><?= $modPersediaan->cacat_panjang; ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Cacat Gubal</label>
							<div class="col-md-7"><strong><?= $modPersediaan->cacat_gb; ?></strong></div>
						</div>
                        <div class="form-group col-md-12">
							<label class="col-md-5 control-label">Cacat Growong</label>
							<div class="col-md-7"><strong><?= $modPersediaan->cacat_gr; ?></strong></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label">No Produksi</label>
							<div class="col-md-7"><strong><?= $modPersediaan->no_produksi; ?></strong></div>
						</div>
                    </div>
                </div>
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