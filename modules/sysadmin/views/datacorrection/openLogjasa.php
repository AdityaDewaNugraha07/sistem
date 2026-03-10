<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-open-logjasa" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Pilih Penjualan Yang Akan Dikoreksi'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-open-nota">
							<thead>
								<tr>
									<th></th>
                                    <th style="width: 120px;"><?= Yii::t('app', 'Nomor'); ?></th>
									<th style="width: 100px;"><?= Yii::t('app', 'Jenis'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Customer'); ?></th>
									<th><?= Yii::t('app', 'Alamat'); ?></th>
                                    <th style="line-height: 1; width: 55px;"><?= Yii::t('app', 'Tempo<br>Bayar'); ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Jumlah<br>Tagihan'); ?></th>
                                    <th>Dibuat Pada</th>
                                    <th>Dibuat Oleh</th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/datacorrection/openlogjasa') ?>',data:{dt: 'modal-open-logjasa'} },
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
            {	targets: 2, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
                    var ret = "";
                    if(data == "1"){
                        var ret = "KAYU OLAHAN";
                    }else if(data == "2"){
                        var ret = "LOG ALAM";
                    }else if(data == "3"){
                        var ret = "JASA KD";
                    }
					return ret;
                }
            },
			{ 	targets: 3,  class: "td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 4, class: "td-kecil",},
            {	targets: 5, class: "td-kecil",},
            {	targets: 6, class: "td-kecil text-align-right",
                render: function ( data, type, full, meta ) {
					return data+" Hari";
                }
            },
            { 	targets: 7, class: "td-kecil text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
            {	targets: 8, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					return formatDateForUser(data);
                }
            },
            {	targets: 9, class: "td-kecil",},
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