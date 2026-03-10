<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Proforma Packinglist'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Jenis<br>Produk'); ?></th>
									<th style="line-height: 1; width: 130px;"><?= Yii::t('app', 'Nomor<br>Kontrak'); ?></th>
									<th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Kode<br>Proforma'); ?></th>
									<th style="line-height: 1;"></th>
									<th style="line-height: 1; width: 120px;"><?= Yii::t('app', 'No.<br>Packinglist'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Buyer<br>Name'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Buyer<br>Address'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Total<br>Container'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Total<br>Bundles'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Total<br>Volume'); ?></th>
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
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/exim/packinglist/masterProforma') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
			{ 	targets: 0, 
                render: function ( data, type, full, meta ) {
					return "<a onclick='pick(\""+full[0]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
                }
            },
			{ 	targets: 1, class: "td-kecil text-align-center", },
			{ 	targets: 2, class: "td-kecil text-align-center", },
			{ 	targets: 3, class: "td-kecil text-align-center", },
			{ 	targets: 4, visible:false },
			{ 	targets: 5, class: "td-kecil text-align-center", 
				render: function ( data, type, full, meta ) {
					var ret = "";
					if(data){
						ret = "<b>"+data+"</b>";
					}
					return ret;
                }
			},
			{ 	targets: 6, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 7, class: "td-kecil", },
			{ 	targets: 8, class: "td-kecil", },
			
			{ 	targets: 9, class: "td-kecil text-align-center", },
			{ 	targets: 10, class: "td-kecil text-align-center", },
			{ 	targets: 11, class: "td-kecil text-align-right", },
			{	targets: 12, class:'text-align-center',
				render: function ( data, type, full, meta ) {
					if(data=="PROFORMA"){
						return '<span class="label label-warning" style="font-size: 11px; padding: 2px 3px;">PROFORMA</span>'
					}else if(data=="FINAL"){
						return '<span class="label label-success" style="font-size: 11px; padding: 2px 3px;">FINAL</span>'
					}
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"createdRow": function ( row, data, index ) {
            if(data[14]){
				$(row).addClass("cancelBackground");
			}
        }
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/proforma/index','packinglist_id'=>'']); ?>'+id);
}

</script>