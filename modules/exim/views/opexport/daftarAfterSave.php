<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Order Penjualan Export'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Nomor Order'); ?></th>
									<th style="width: 200px;"><?= Yii::t('app', 'Nomor Kontrak'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th style="width: 250px;"><?= Yii::t('app', 'Applicant'); ?></th>
									<th></th>
									<th style="width: 250px;"><?= Yii::t('app', 'Notify Party'); ?></th>
									<th></th>
									<th><?= Yii::t('app', 'Jenis<br>Produk'); ?></th>
									<th><?= Yii::t('app', 'Payment<br>Method'); ?></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/exim/opexport/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 1, class: "text-align-center", },
			{ 	targets: 2, class: "text-align-center", },
			{ 	targets: 3, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 4, class: "td-kecil",
				render: function ( data, type, full, meta ) {
					return "<b>"+((data)?data:"")+"</b><br>"+((full[5])?full[5]:"");
                }
			},
			{ 	targets: 5, visible: false, },
			
			{ 	targets: 6, class: "td-kecil",
				render: function ( data, type, full, meta ) {
					return "<b>"+((data)?data:"")+"</b><br>"+((full[7])?full[7]:"");
                }
			},
			{ 	targets: 7, visible: false, },
			
			{ 	targets: 8, class: "td-kecil text-align-center", },
			{ 	targets: 9, class: "td-kecil text-align-center", },
			{	targets: 10, 
				width: '75px',
				render: function ( data, type, full, meta ) {
					var display = "";
					if(full[10]){
						display =  'visibility: hidden;';
					}
					var ret =  '<center>\n\
									<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"createdRow": function ( row, data, index ) {
            if(data[10]){
				$(row).addClass("cancelBackground");
			}
        }
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/exim/opexport/index','op_export_id'=>'']); ?>'+id);
}

</script>