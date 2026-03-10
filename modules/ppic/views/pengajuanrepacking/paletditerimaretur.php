<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<div class="modal fade" id="modal-palet" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Palet yang diterima'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-palet">
							<thead>
								<tr>
									<th></th>
									<th style="width: 100px;"><?= Yii::t('app', 'Jenis Produk') ?></th>
                                    <th><?= Yii::t('app', 'Permintaan') ?></th>
                                    <th></th>
									<th><?= Yii::t('app', 'KBJ / Produk') ?></th>
									<th><?= Yii::t('app', 'Dimensi') ?></th>
									<th><?= Yii::t('app', 'Pcs') ?></th>
									<th><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
									<th><?= Yii::t('app', 'Tanggal<br>Mutasi Keluar') ?></th>
									<th><?= Yii::t('app', 'Tanggal<br>Terima Mutasi') ?></th>
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
    var dt_table =  $('#table-palet').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/pengajuanrepacking/paletditerimaretur') ?>',data:{dt: 'table-palet'} },
        order: [
//            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class:"td-kecil text-align-center" },
            {	targets: 2, class:"td-kecil", },
            {	targets: 3, visible: false, class:"td-kecil", },
            {	targets: 4, class:"td-kecil", 
                render: function ( data, type, full, meta ) {
					return "<a onclick='pickPalet(\""+full[3]+"\",\""+full[10]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
            {	targets: 5, class:"td-kecil", 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            { 	targets: 6, class:"td-kecil text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
            { 	targets: 7, class:"td-kecil text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser4Digit(data);
                }
            },
            { 	targets: 8, class:"td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            { 	targets: 9, class:"td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

</script>