<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-madul" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Keputusan Pembelian Log'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Kontrak'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Volume<br>Kontrak m<sup>3</sup>'); ?></th>
									<th><?= Yii::t('app', 'Suplier'); ?></th>
									<th><?= Yii::t('app', 'Asal Kayu'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Volume<br>Pembelian m<sup>3</sup>'); ?></th>
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
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/dinasgrader/openPengajuanPembelianlog') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 1, 
                render: function ( data, type, full, meta ) {
                    return data;
                }
            },
			{ 	targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            }, 
			{ 	targets: 3, class :"text-align-center", },
			{ 	targets: 4, class :"text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data)+" M<sup>3</sup>";
                }
            }, 
			{ 	targets: 7, class :"text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data)+" M<sup>3</sup>";
                }
            },
            { 	targets: 8, 
                render: function ( data, type, full, meta ) {
                    var ret =  "<a onclick='pickPengajuanPembelianlog(\""+full[0]+"\",\""+full[1]+"\")' class='btn btn-xs btn-icon-only btn-default tooltips' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
                    return ret;
                }
            },

        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

</script>