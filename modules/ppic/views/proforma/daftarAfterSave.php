<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Proforma Packinglist'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Jenis<br>Produk'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Nomor<br>Kontrak'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Nomor<br>Order'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Kode<br>Proforma'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Revisi<br>Ke'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Buyer<br>Name'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Buyer<br>Address'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Total<br>Container'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Total<br>Bundles'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Total<br>Volume'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Packinglist<br>Status'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Approval<br>Status'); ?></th>
									<th></th>
									<th style="width: 35px;"></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/proforma/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 1, class: "td-kecil text-align-center", },
			{ 	targets: 2, class: "td-kecil text-align-center", },
			{ 	targets: 3, class: "td-kecil text-align-center", },
			{ 	targets: 4, class: "td-kecil text-align-center", },
			{ 	targets: 5, class: "td-kecil text-align-center", },
			{ 	targets: 6, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 7, class: "td-kecil", },
			{ 	targets: 8, class: "td-kecil", },
			
			{ 	targets: 9, class: "td-kecil text-align-center", },
			{ 	targets: 10, class: "td-kecil text-align-center", },
			{ 	targets: 11, class: "td-kecil text-align-right", },
			{ 	targets: 12, class: "td-kecil text-align-center", 
				render: function ( data, type, full, meta ) {
					if(data === "PROFORMA"){
						return '<span style="font-size: 11px; padding: 2px 3px;" class="label label-warning">'+data+'</span>';
					}
					if(data === "FINAL"){
						return '<span style="font-size: 11px; padding: 2px 3px;" class="label label-success">'+data+'</span>';
					}
				}
			},
			{ 	targets: 13, class: "td-kecil text-align-center",
				render: function ( data, type, full, meta ) {
					if(data === "Not Confirmed"){
						return '<span style="font-size: 11px; padding: 2px 3px;" class="label label-default">'+data+'</span>';
					}
					if(data === "APPROVED"){
						return '<span style="font-size: 11px; padding: 2px 3px;" class="label label-success">'+data+'</span>';
					}
					if(data === "REJECTED"){
						return '<span style="font-size: 11px; padding: 2px 3px;" class="label label-danger">'+data+'</span>';
					}
				}
			},
			{	targets: 14, visible: false },
			{	targets: 15, 
				width: '75px',
				render: function ( data, type, full, meta ) {
                    let display = "";
                    if(full[14]){
						display =  'visibility: hidden;';
					}
                    return '<div style="text-align: center;">\n\
									<a style="' + display + '" class="btn btn-xs btn-outline blue tooltips" data-original-title="Buat Revisi" onclick="revisi(' + full[0] + ')"><i class="fa fa-retweet"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail(' + full[0] + ')"><i class="fa fa-eye"></i></a>\n\
								</div>';
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"createdRow": function ( row, data, index ) {
            if(data[15]){
				$(row).addClass("cancelBackground");
			}
        }
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/proforma/index','packinglist_id'=>'']); ?>'+id);
}
function revisi(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/proforma/index']); ?>?packinglist_id='+id+'&revisi='+id);
}

</script>