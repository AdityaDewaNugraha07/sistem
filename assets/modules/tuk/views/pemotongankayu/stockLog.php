<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-stock-log" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Persediaan Log Alam (Dokumen)'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th style="line-height: 1; width: 250px;"><?= Yii::t('app', 'Kayu'); ?></th>
									<th style="line-height: 1; width: 275px;"><?= Yii::t('app', 'No. Barcode'); ?></th>
									<th style="line-height: 1; width: 70px;"><?= Yii::t('app', 'No. Grade'); ?></th>
									<th style="line-height: 1; width: 80px;"><?= Yii::t('app', 'No. Batang'); ?></th>
									<th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'No. Cardpad'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Lokasi'); ?></th>
									<th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Diameter'); ?></th>
									<th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Panjang'); ?></th>
									<th style="line-height: 1; width: 80px;"><?= Yii::t('app', 'Reduksi'); ?></th>
									<th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Volume'); ?></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute(( isset($action)?$action:"/tuk/pemotongankayu/stockLog" )) ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
			{ 	targets: 0, class: " text-align-left td-kecil", },
			{ 	targets: 1, class: " text-align-left td-kecil",
                render: function ( data, type, full, meta ) {
					var ret = "<a onclick='pick(\""+full[1]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a> "+full[1];
					return ret;
                }
            },
			{ 	targets: 2, class: " text-align-left td-kecil", },
			{ 	targets: 3, class: " text-align-left td-kecil", },
			{ 	targets: 4, class: " text-align-left td-kecil", },
			{ 	targets: 5, class: " text-align-center td-kecil", },
			{ 	targets: 6, class: " text-align-right td-kecil", searchable: false, },
			{ 	targets: 7, class: " text-align-right td-kecil", searchable: false, },
			{ 	targets: 8, class: " text-align-center td-kecil", searchable: false, },
			{ 	targets: 9, class: " text-align-right td-kecil", searchable: false, },
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/proforma/index','packinglist_id'=>'']); ?>'+id);
}

</script>