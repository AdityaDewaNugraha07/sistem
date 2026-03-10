<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<div class="modal fade" id="modal-hasilrepacking" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Pengiriman Hasil Mutasi Produksi'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-hasilrepacking">
							<thead>
								<tr>
									<th></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Tanggal<br>Kirim'); ?></th>
									<th><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
									<th><?= Yii::t('app', 'Kode Produk'); ?></th>
									<th><?= Yii::t('app', 'Nama Produk'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Tanggal<br>Produksi'); ?></th>
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
<?php $this->registerJs(" dtHasilrepacking();", yii\web\View::POS_READY); ?>
<script>
function dtHasilrepacking(){
    var dt_table =  $('#table-hasilrepacking').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/gudang/penerimaanko/OpenHasilrepacking') ?>',data:{dt: 'table-hasilrepacking'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 1, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 2, class:'text-align-left', 
                render: function ( data, type, full, meta ) {
                    if(full[9]){
                        return data;
                    }else{
                        return "<a onclick='pick(\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                    }
                }
            },
            {	targets: 3, class:'text-align-left', },
			{ 	targets: 5, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 8, 
				class:'text-align-right',
				render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
				},
			},
            {	targets: 9, visible:false }
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