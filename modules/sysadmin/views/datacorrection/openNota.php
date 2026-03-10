<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-open-nota" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Pilih Nota Penjualan Yang Akan Dikoreksi'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-open-nota">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode Nota'); ?></th>
									<th><?= Yii::t('app', 'Jenis Produk'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Nota'); ?></th>
									<th><?= Yii::t('app', 'Customer'); ?></th>
									<th><?= Yii::t('app', 'Kode SPM'); ?></th>
									<th><?= Yii::t('app', 'Nopol'); ?></th>
									<th><?= Yii::t('app', 'Nama Supir'); ?></th>
									<th><?= Yii::t('app', 'Alamat Bongkar'); ?></th>
									<th><?= Yii::t('app', 'Total Bayar'); ?></th>
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
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-open-nota').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/datacorrection/opennota') ?>',data:{dt: 'modal-open-nota'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class: "td-kecil",
                render: function ( data, type, full, meta ) {
					return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
            {	targets: 2, class: "td-kecil",},
			{ 	targets: 3,  class: "td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 4, class: "td-kecil",},
            {	targets: 5, class: "td-kecil",},
            {	targets: 6, class: "td-kecil",},
            {	targets: 7, class: "td-kecil",},
            {	targets: 8, class: "td-kecil",},
			{ 	targets: 9, class: "td-kecil text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{	targets: 10, class: "td-kecil", visible: false },
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
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/marketing/notapenjualan/index','nota_penjualan_id'=>'']); ?>'+id);
}

</script>