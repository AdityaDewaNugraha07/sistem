<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Penerimaan Tunai Kas Besar'); ?></h4>
            </div>
            <div class="modal-body">
				<input type="hidden" id="place-piutangterbayar">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" id="table-info">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Bayar'); ?></th>
									<th><?= Yii::t('app', 'Deskripsi'); ?></th>
									<th><?= Yii::t('app', 'Nominal'); ?></th>
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
    // getPiutangTerbayar(function(){
	// 	dtInfo();
	// });
	dtInfo();
", yii\web\View::POS_READY); ?>
<script>
function getPiutangTerbayar(callback=null){
	$("#table-info").addClass('animation-loading');
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/SetHighlightListKasbesar']); ?>',
		type   : 'POST',
		data   : {  },
		success: function (data) {
			if(data){
				var datastring = JSON.stringify(data);
				$("#place-piutangterbayar").val(datastring);
			}
			if( callback ){ callback(); }
			$("#table-info").removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}	
	
function dtInfo(){
    var dt_table =  $('#table-info').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/finance/piutangpenjualan/openKasbesar') ?>',data:{dt: 'table-info'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1,
				class:'td-kecil',
                render: function ( data, type, full, meta ) {
                    return "<a onclick='pickKasbesar(\""+data+"\")' class='btn btn-xs btn-icon-only btn-default tooltips' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
            {	targets: 2, 
				class:'td-kecil text-align-center',
				render: function ( data, type, full, meta ) {
                    return data.substr(0,10);
                }
			},
            {	targets: 3, class:'td-kecil' },
			{	targets: 4, 
				class:'td-kecil text-align-right',
				render : function(data, type, full, meta){
					return formatNumberForUser(data);
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		// "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
		// 	var data = JSON.parse( $("#place-piutangterbayar").val() );
		// 	var datakey = Object.keys(data);
		// 	$(datakey).each(function(key,value){
		// 		if(value == aData[0]){
		// 			var sisa = data[ aData[0] ];
		// 			if(sisa > 0){
		// 				$(nRow).attr("style","background-color: #FFF2DA;");
		// 			}else{
		// 				$(nRow).attr("style","background-color: #E8FFDA;");
		// 				$(nRow).find('.btn-icon-only').remove();
		// 			}
		// 		}
		// 	});
		// },
    });
}
</script>