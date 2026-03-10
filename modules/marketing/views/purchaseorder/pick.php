<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Purchase Order (PO) Customer'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-po">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode PO') ?></th>
									<th><?= Yii::t('app', 'Tanggal PO') ?></th>
									<th><?= Yii::t('app', 'Customer') ?></th>
									<th><?= Yii::t('app', 'Tanggal Kirim') ?></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Nomor PO') ?></th>
									<th style="width: 50px;"></th>
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
	formconfig();
    dtMaster();
", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-po').dataTable({
        ajax: { url: '<?= isset($url)?$url:\yii\helpers\Url::toRoute('/marketing/purchaseorder/daftarPO'); ?>',data:{dt: 'table-po'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
			{	targets: 0, searchable: false,
                width: '5%', class: 'text-align-center',
                render: function ( data, type, full, meta ) {
                    var par = full[1];
                    if(full[7]){
                        var tbl = "<a onclick='pickedPO(\""+data+"\",\""+par+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
                    } else {
                        var tbl = "<a class='btn btn-xs btn-icon-only btn-default' style='width: 25px; height: 25px;' disabled><i class='fa fa-plus-circle'></i></a>";
                    }
                    return tbl;
                }
             },
            {	targets: 2,
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 3,
                render: function ( data, type, full, meta ) {
                    return full[4]?full[4]:data;
                }
            },
            {	targets: 4,
                render: function ( data, type, full, meta ) {
                    var date = new Date(full[5]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 5, class: "text-align-center", searchable: false,
                render: function ( data, type, full, meta ) {
                    return full[6];
                }
            },
            {	targets: 6, visible: false, 
                orderable: false, searchable: false
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"autoWidth":false
    });
}
</script>