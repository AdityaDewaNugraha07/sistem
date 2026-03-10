<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade draggable-modal" id="modal-daftar-dp" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar DP yang telah dilakukan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-dp">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Supplier'); ?></th>
									<th><?= Yii::t('app', 'Cara Bayar'); ?></th>
									<th><?= Yii::t('app', 'Total Bayar'); ?></th>
									<th><?= Yii::t('app', 'Status Bayar'); ?></th>
									<th style="width: 50px;"></th>
									<th></th>
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
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    dtDp();
", yii\web\View::POS_READY); ?>
<script>
function dtDp(){
    var dt_table =  $('#table-dp').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasing/dpbhp/daftarDpBhp') ?>',data:{dt: 'table-dp'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 4, 
                class: 'text-align-center',
            },
			{	targets: 5,
                class: 'text-align-right',
				render: function ( data, type, full, meta ) {
                    return "<span style='float:left;'>"+full[9]+"</span>"+formatNumberForUser(data);
                },
            },
			{	targets: 6, 
				class:"text-align-center",
                render: function ( data, type, full, meta ) {
                    var ret = '-';
                    if(full[7]){
						var date = new Date(full[8]);
						date = date.toString('dd/MM/yyyy');
						var tgl = '<br><span style="font-size:1.1rem">'+date+'</span>';
                        ret = '<b>'+full[7]+'</b>'+tgl;
                    }
                    return ret;
                }
            },
            {	targets: 7, 
                width: '50px',
				orderable: false,
				searchable: false,
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat detail DP" onclick="lihatDp('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
			{	targets: 8, visible: false },
			{	targets: 9, visible: false },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDp(dp_bhp_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasing/dpbhp/index','dp_bhp_id'=>'']); ?>'+dp_bhp_id);
}
</script>