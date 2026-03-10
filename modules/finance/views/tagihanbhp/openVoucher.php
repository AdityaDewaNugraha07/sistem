<?php 
app\assets\DatatableAsset::register($this); 
?>
?>
<div class="modal fade" id="modal-openvoucher" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Open Voucher'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-open-voucher">
							<thead>
								<tr>
									<th></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th style="width: 80px;"><?= Yii::t('app', 'Tanggal'); ?></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Tipe'); ?></th>
                                    <th style="width: 180px;"><?= Yii::t('app', 'Penerima'); ?></th>
									<th><?= Yii::t('app', 'Total Tagihan'); ?></th>
									<th><?= Yii::t('app', 'Prepared By'); ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Keterangan'); ?></th>
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
    dtAfterSave();
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-open-voucher').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/finance/tagihanbhp/openVoucher') ?>',data:{dt: 'table-open-voucher'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, class:"text-align-center td-kecil" },
			{	targets: 2, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, class:"text-align-left td-kecil" },
			{	targets: 4, class:"text-align-left td-kecil"},
			{	targets: 5, class:"text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
                    if(full[14] == "IDR"){
                        ret = formatInteger(data);
                    } else {
                        ret = formatNumberForUser2Digit(data);
                    }
					return ret;
                }
            },
			{	targets: 6, class:"text-align-center td-kecil" },
			{	targets: 7, class:"text-align-left td-kecil",
                render: function ( data, type, full, meta ) {
                    if(data == null || data == ''){
                        ket =  full[13];
                    } else {
                        ket = data + '<br>' + full[13];
                    }
                    return ket;
                }
            },
            {   targets: 8, class:"text-align-center",
                render: function ( data, type, full, meta ) {
                    var ret = "<a onclick='addOpenVoucher(\""+full[0]+"\",\""+full[1]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
					return ret;
                }
            }		
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>