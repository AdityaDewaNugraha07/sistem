<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<div class="modal fade" id="modal-master-produk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<?php if(empty($disableAction)){ ?>
				<a class="btn btn-icon-only btn-default tooltips" onclick="create()" data-original-title="Create New" style="float: right; margin-right: 5px;"><i class="fa fa-plus"></i></a>
				<?php } ?>
                <h4 class="modal-title"><?= Yii::t('app', 'Master Produk'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
							<thead>
								<tr>
									<th></th>
									<th style="width: 10%;"><?= Yii::t('app', 'Jenis Produk') ?></th>
									<th><?= Yii::t('app', 'Kode Produk') ?></th>
									<th><?= Yii::t('app', 'Nama Produk') ?></th>
									<th><?= Yii::t('app', 'Dimensi') ?></th>
									<th><?= Yii::t('app', 'Status') ?></th>
									<th style="width: 50px;"></th>
								</tr>
							</thead>
						</table>
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
<?php $this->registerJs(" dtProduk();", yii\web\View::POS_READY); ?>
<script>
function dtProduk(){
    var dt_table =  $('#table-produk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/produk/index') ?>',data:{dt: 'table-produk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 2,
				width:"250px",
                render: function ( data, type, full, meta ) {
					return "<a onclick='pickProduk(\""+full[0]+"\"<?= (!empty($tr_seq)?",".$tr_seq:""); ?>,\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
            {	targets: 5,
                orderable: false,
				class:"text-align-center",
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data){
                        ret = 'Active';
                    }else{
                        ret = '<span style="color:#B40404">Non-Active</span>';
                    }
                    return ret;
                }
            },
            {	targets: 6, 
                orderable: false,
				class:"text-align-center",
                width: '5%',
                render: function ( data, type, full, meta ) {
					<?php if(!empty($disableAction)){ ?>
						return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+',1)\"><i class="fa fa-info-circle"></i></a></center>';
					<?php }else{ ?>
						return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
					<?php } ?>
                }
            },
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