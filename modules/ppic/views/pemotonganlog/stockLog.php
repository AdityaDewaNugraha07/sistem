<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-stock-log" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Log Siap Potong'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
                                    <th style="line-height: 1; width: 10px;"></th>
									<th style="line-height: 1; width: 250px;"><?= Yii::t('app', 'Kayu<br>No. Barcode<br>No. Grade<br>No. Batang<br>No. Lapangan'); ?></th>
									<th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Panjang<br>(cm)'); ?></th>
                                    <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Diameter<br>(cm)'); ?></th>
									<th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Vol<br>(m<sup>3</sup>)'); ?></th>
                                    <th></th>
                                    <th></th>
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
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    dtAfterSave();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var nomor = '<?= $nomor; ?>';
    var edit = '<?= $edit; ?>';
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { 
                url: '<?= \yii\helpers\Url::toRoute(( isset($action)?$action:"/ppic/pemotonganlog/stockLog" )) ?>',
                data:{dt: 'modal-aftersave', nomor: nomor, edit:edit} 
              },
        order: [ [1, 'desc'] ],
        columnDefs: [
            {   targets: 0, class:"text-align-left td-kecil",
                render: function(data, type, full, meta){
                    return "<a onclick='pick(\""+full[2]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a> ";
                }
            },
			{ 	targets: 1, class: " text-align-left td-kecil", 
                render: function ( data, type, full, meta ) {
					var ret = data + '<br>' + full[2] + '<br>' + full[3] + '<br>' + full[4] + '<br>' + full[5];
					return ret;
                }
            },
			{ 	targets: 2, class: " text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
					return Math.round(full[7] * 100); // convert panjang dari m ke cm
                }
            },
			{ 	targets: 3, class: " text-align-right td-kecil", 
                render: function ( data, type, full, meta ) {
					return full[6];
                }
            },
			{ 	targets: 4, class: " text-align-right td-kecil", 
                render: function ( data, type, full, meta ) {
					return full[8];
                }
            },
            {	targets: 5, visible: false },
            {	targets: 6, visible: false },
            {	targets: 7, visible: false },
            {	targets: 8, visible: false },
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

</script>