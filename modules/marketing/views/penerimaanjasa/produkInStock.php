<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<div class="modal fade" id="modal-master-produk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<!--<a class="btn btn-icon-only btn-default tooltips" onclick="create()" data-original-title="Create New" style="float: right; margin-right: 5px;"><i class="fa fa-plus"></i></a>-->
                <h4 class="modal-title"><?= Yii::t('app', 'Master Produk'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
							<thead>
								<tr>
									<th></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Jenis Produk') ?></th>
									<th style="width: 300px;"><?= Yii::t('app', 'Kode Produk') ?></th>
									<th style="width: 300px;"><?= Yii::t('app', 'Nama') ?></th>
									<th style="width: 240px;"><?= Yii::t('app', 'Dimensi') ?></th>
									<?php /* <th style="width: 100px;"><?= Yii::t('app', 'Stock<br>Palet') ?></th>
									<th style="width: 100px;"><?= Yii::t('app', 'Stock<br>Pcs') ?></th>
									<th style="width: 100px;"><?= Yii::t('app', 'Stock<br>M<sup>3</sup>') ?></th>*/?>
									<th></th>
									<?php /* <th style="width: 100px;"><?= Yii::t('app', 'Harga Jual') ?></th> */?>
								</tr>
							</thead>
						</table>
						</div>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/produkInStock2','jenis_produk'=>'']) ?><?= $jenis_produk ?>',data:{dt: 'table-produk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 2, visible: false },
			{	
				targets: 3,
				className: 'dt-body-left',
				width: '240px',
                render: function ( data, type, full, meta ) {
					return full[3];
                }
            },
			{	
				targets: 4,
				className: 'dt-body-center',
				width: '200px',
                render: function ( data, type, full, meta ) {
					return full[4];
                }
            },
			/*{	
				targets: 5,
				className: 'dt-body-right',
				width: '50px',
                render: function ( data, type, full, meta ) {
					return parseFloat(full[7]).toLocaleString(window.document.documentElement.lang);
                }
            },
			{	
				targets: 6,
				className: 'dt-body-right',
				width: '50px',
                render: function ( data, type, full, meta ) {
					return parseFloat(full[5]).toLocaleString(window.document.documentElement.lang);
                }
            },
			{	
				targets: 7,
				className: 'dt-body-right',
				width: '50px',
                render: function ( data, type, full, meta ) {
					return parseFloat(full[6]).toLocaleString(window.document.documentElement.lang);
                }
            },
            */
			{	
				targets: 5,
				className: 'dt-body-center',
				width: '50px',
                render: function ( data, type, full, meta ) {
                	// palet - kubik - harga
					return "<a onclick='pickProduk(\""+full[0]+"\"<?= (!empty($tr_seq)?",".$tr_seq:""); ?>,\""+data+"\",\""+full[5]+"\",\""+full[6]+"\",\""+full[7]+"\",\""+full[8]+"\",\""+full[9]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
                }
            },
			/*{	
				targets: 9,
				className: 'dt-body-right',
                render: function ( data, type, full, meta ) {
					return parseFloat(full[8]).toLocaleString(window.document.documentElement.lang);
                }
            },*/            
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function create(){
	var url = '<?= \yii\helpers\Url::toRoute(['/ppic/produk/create']); ?>';
	$(".modals-place-2-min").load(url, function() {
		$("#modal-produk-create").modal('show');
		$("#modal-produk-create").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
function info(id,disableAction=null){
	if(disableAction){
		var url = '<?= \yii\helpers\Url::toRoute(['/ppic/produk/info','id'=>'']); ?>'+id+'&disableAction=1';
	}else{
		var url = '<?= \yii\helpers\Url::toRoute(['/ppic/produk/info','id'=>'']); ?>'+id;
	}
	$(".modals-place-2-min").load(url, function() {
		$("#modal-produk-info").modal('show');
		$("#modal-produk-info").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
</script>
