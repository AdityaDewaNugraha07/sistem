<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Pengiriman Palet Ke Gudang'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode Pengiriman'); ?></th>
									<th><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
									<th><?= Yii::t('app', 'Kode Produk'); ?></th>
									<th><?= Yii::t('app', 'Nama Produk'); ?></th>
									<th><?= Yii::t('app', 'Tanggal<br>Kirim'); ?></th>
									<th><?= Yii::t('app', 'Tanggal<br>Produksi'); ?></th>
									<th><?= Yii::t('app', 'Qty Pcs'); ?></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/pengajuanrepacking/DaftarAfterSavePengiriman') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class:'text-align-left',
                render: function ( data, type, full, meta ) {
					var ret = data;
                    if(full[10]){
                        ret = ret+' <i class="fa fa-check font-green-seagreen"></i>';
                    }
					return ret;
                }
            },
            {	targets: 2, class:'text-align-center', },
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
			{	targets: 7, class:'text-align-center',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{	targets: 8, visible: false },
			{	targets: 9, 
				class:'text-align-right',
				render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
				},
			},
			{	targets: 10, 
				width: '75px',
				class:'td-kecil text-align-center',
				render: function ( data, type, full, meta ) {
                    var display = "";
					if(full[10]){
						display =  'visibility: hidden;';
					}
					var ret =  '<center>\n\
                                    <a style="margin-right: 0px; '+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Print QRCode" onclick="printKartuBarang('+full[0]+')"><i class="fa fa-print"></i></a>\n\
									<a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
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
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/kirimgudang','hasil_repacking_id'=>'']); ?>'+id);
}

</script>