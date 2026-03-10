<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<div class="modal fade" id="modal-master-produk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Master Produk Retur'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
							<thead>
								<tr>
									<th></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Jenis Produk') ?></th>
									<th style="width: 300px;"><?= Yii::t('app', 'Nama') ?></th>
									<th style="width: 240px;"><?= Yii::t('app', 'Dimensi') ?></th>
									<th></th>
								</tr>
							</thead>
						</table>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php //echo yii\bootstrap\Html::hiddenInput('reff_ele',$tr_seq) ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php 
$this->registerJs(" 
	dtProduk();
	$('div.dataTables_filter input').addClass('autofocus');
	$('#modal-master-produk').on('shown.bs.modal', function () {
	    $('.input-sm').focus();
	})  
	", yii\web\View::POS_READY); 
?>
<script>
function dtProduk(){
    var dt_table =  $('#table-produk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/produkInRetur']) ?>',data:{dt: 'table-produk'} },
        order: [
            [1, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	
				targets: 2,
				className: 'dt-body-left',
				width: '240px',
                render: function ( data, type, full, meta ) {
					return data + ' - ' + full[3];
                }
            },
			{	
				targets: 3,
				className: 'dt-body-center',
				width: '200px',
                render: function ( data, type, full, meta ) {
					return full[4];
                }
            },
			{	
				targets: 4,
				className: 'dt-body-center',
				width: '50px',
                render: function ( data, type, full, meta ) {
                	// produk_id - kode retur
					return "<a onclick='pickProduk(\""+full[0]+"\",\""+full[2]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
                }
            },         
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>
