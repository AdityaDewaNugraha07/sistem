<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<div class="modal fade" id="modal-available-produk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Available Produk'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
							<thead>
								<tr>
                                    <th><?= Yii::t('app', 'Pick'); ?></th>
                                    <th style="width: 100px;"><?= Yii::t('app', 'Jenis Produk') ?></th>
                                    <th style="width: 150px;"><?= Yii::t('app', 'Kode Barang Jadi') ?></th>
                                    <th><?= Yii::t('app', 'Nama Produk') ?></th>
                                    <th style="width: 200px;"><?= Yii::t('app', 'Dimensi') ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Lokasi<br>Gudang') ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Qty Pcs') ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Kubikasi M<sup>3</sup>') ?></th>
                                </tr>
							</thead>
						</table>
						</div>
                    </div>
                </div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs(" dtProduk();", yii\web\View::POS_READY); ?>
<script>
function dtProduk(){
    var dt_table =  $('#table-produk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/availableStockPalet']) ?>',data:{dt: 'table-produk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
			{	targets: 0, class:'td-kecil', 
                render: function ( data, type, full, meta ) {
					return "<a onclick='pick(\""+full[2]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
                }
            },
            { 	targets: 1, class:'td-kecil'},
            { 	targets: 2, class:'td-kecil'},
            { 	targets: 3, class:'td-kecil'},
            { 	targets: 4, class:'td-kecil'},
			{ 	targets: 5, class: "td-kecil text-align-center", searchable:false },
			{ 	targets: 6, class: "td-kecil text-align-center", searchable:false, 
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{ 	targets: 7, class: "td-kecil text-align-right", searchable:false, 
                render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>