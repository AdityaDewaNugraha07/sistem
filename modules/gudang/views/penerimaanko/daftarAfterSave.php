<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Penerimaan Kayu Olahan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode Terima'); ?></th>
									<th><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
									<th><?= Yii::t('app', 'Kode Produk'); ?></th>
									<th><?= Yii::t('app', 'Nama Produk'); ?></th>
									<th><?= Yii::t('app', 'Tanggal<br>Terima'); ?></th>
									<th><?= Yii::t('app', 'Tanggal<br>Produksi'); ?></th>
									<th><?= Yii::t('app', 'Gudang'); ?></th>
									<th><?= Yii::t('app', 'Qty'); ?></th>
									<th><?= Yii::t('app', 'Satuan'); ?></th>
									<th><?= Yii::t('app', 'Volume M<sup>3</sup>'); ?></th>
									<th></th>
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
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    dtAfterSave();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/gudang/penerimaanko/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 5, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 6, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 8, 
				render: function ( data, type, full, meta ) {
					return data+" "+full[9];
				}
			},
			{	targets: 9, visible: false },
			{	targets: 10, 
				class:'text-align-right',
				render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
				}
			},
			{	targets: 11, 
				width: '75px',
				class:'td-kecil text-align-center',
				render: function ( data, type, full, meta ) {
					var ret =  '<center>\n\
									<a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
									<a style="margin-left: -8px;" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Print Label" onclick="printKartuBarang('+full[0]+')"><i class="fa fa-print"></i></a>\n\
									<a style="margin-left: -8px;" class="btn btn-xs btn-outline red-flamingo tooltips" data-original-title="Hapus" onclick="openModal(\'<?php echo \yii\helpers\Url::toRoute(['/sysadmin/managetransaction/stockproduk','id'=>'']) ?>'+full[2]+'&tableid=table-produk\',\'modal-delete-record\')"><i class="fa fa-trash-o"></i></a>\n\
								</center>';
					return ret;
				}
			},
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/index','tbko_id'=>'']); ?>'+id);
}

</script>