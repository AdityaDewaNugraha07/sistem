<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-produklist2" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Product List'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode Barang Jadi') ?></th>
									<th><?= Yii::t('app', 'Kode Produk') ?></th>
									<th><?= Yii::t('app', 'Nama Produk') ?></th>
									<th><?= Yii::t('app', 'Tanggal<br>Produksi') ?></th>
									<th><?= Yii::t('app', 'Shift/Line') ?></th>
									<th></th>
									<th><?= Yii::t('app', 'Lokasi Gudang') ?></th>
									<th><?= Yii::t('app', 'Qty<br>Palet') ?></th>
									<th><?= Yii::t('app', 'Qty') ?></th>
									<th></th>
									<th><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
								</tr>
							</thead>
						</table>
						</div>
                    </div>
                </div>
            <div class="modal-footer">
                <?= yii\bootstrap\Html::hiddenInput('reff_ele',$tr_seq) ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("  dtProduk(); formconfig();", yii\web\View::POS_READY); ?>
<script>
function dtProduk(){
    var dt_table =  $('#table-produk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/gudang/availablestockproduk/produkListOnModal','tr_seq'=>'']) ?><?= $tr_seq ?>&jns_produk=<?= $jns_produk ?>&notin=<?= $notin ?>',data:{dt: 'table-produk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1,
				width:"180px",
                render: function ( data, type, full, meta ) {
					return "<a onclick='pickProdukList(\""+data+"\"<?= (!empty($tr_seq)?',\"'.$tr_seq.'\"':""); ?>)' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
			{ 	targets: 4, 
				render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 5,
                class:"text-align-center",
				render: function ( data, type, full, meta ) {
                    var ret = '';
					if(full[5]!='-'){
						ret = full[5];
					}
					if(full[6]!='-'){
						ret = full[6];
					}
                    return ret;
                }
            },
			{	targets: 6, visible: false },
			{	targets: 8, class: "text-align-right", searchable:false },
			{	targets: 9,
				searchable: false,
				render: function ( data, type, full, meta ) {
                    var ret = formatNumberForUser(data);
                    return data+" ("+full[10]+")";
                }
            },
			{	targets: 10, visible: false },
			{	targets: 11,
                class:"text-align-right",
				searchable: false,
				render: function ( data, type, full, meta ) {
                    return formatNumberForUser(data);
                }
			}
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>