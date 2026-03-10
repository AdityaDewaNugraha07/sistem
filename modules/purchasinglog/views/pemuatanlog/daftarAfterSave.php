<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Pelunasan Log'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode Pengajuan'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Kode Loglist'); ?></th>
									<th><?= Yii::t('app', 'Kode Keputusan'); ?></th>
									<th><?= Yii::t('app', 'Kode PO'); ?></th>
									<th><?= Yii::t('app', 'Nomor Kontrak'); ?></th>
									<th><?= Yii::t('app', 'Total Bayar'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Payment<br>Status'); ?></th>
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
<?php $this->registerJs("
    dtAfterSave();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/pemuatanlog/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ], 
        columnDefs: [ 
            {	targets: 0, visible: false }, 
            {	targets: 1, class: "text-align-center" }, 
            {	targets: 7, 
                render: function ( data, type, full, meta ) { 
                    var ret = 'Rp. '+formatInteger(data);
                    return ret; 
                } 
            }, 
            {	targets: 8, class: "text-align-center", 
                render: function ( data, type, full, meta ) { 
					if(data=='PAID'){
						data = "<span class='label label-sm label-success'>PAID</span>";
					}else if(data=='UNPAID'){
						data = "<span class='label label-sm label-warning'>UNPAID</span>";
					}else{
						data = "-";
					}
                    return data; 
                } 
            }, 
            {	targets: 9, 
                width: '50px', 
                render: function ( data, type, full, meta ) { 
                    var ret =  '<center><a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat detail" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret; 
                } 
            }, 
        ], 
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pemuatanlog/index','log_bayar_muat_id'=>'']); ?>'+id);
}
</script>