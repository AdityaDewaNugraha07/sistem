<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Voucher Penerimaan'); ?></h4>
            </div>
            <div class="modal-body">
				<input type="hidden" id="place-piutangterbayar">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" id="table-info">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Jenis'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Kode<br>Voucher'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Kode<br>BBM'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Akun<br>Kredit'); ?></th>
									<th><?= Yii::t('app', 'Sender'); ?></th>
									<th><?= Yii::t('app', 'Deskripsi'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Mata<br>Uang'); ?></th>
                                    <th style="width: 80px;"><?= Yii::t('app', 'Nominal'); ?></th>
									<th style="width: 80px;"><?= Yii::t('app', 'Terpakai'); ?></th>
									<th style="width: 80px;"><?= Yii::t('app', 'Sisa'); ?></th>
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
    $('#table-info').addClass("animation-loading");
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/finance/piutangpenjualan/setHighlightListVoucher']); ?>',
		type   : 'POST',
		data   : { tipe:"<?= $action ?>" },
		success: function (data) {
			if(data){
				var datastring = JSON.stringify(data);
				$("#place-piutangterbayar").val(datastring);
                $('#table-info').removeClass("animation-loading");
			}
			if( callback ){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}	

function dtInfo(){
    var dt_table =  $('#table-info').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/finance/piutangpenjualan/').'/'.$action ?>',data:{dt: 'table-info'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
			{	targets: 0,
				class:'td-kecil text-align-center',
                render: function ( data, type, full, meta ) {
                    return "<a onclick='pickVoucher(\""+full[2]+"\")' class='btn btn-xs btn-icon-only btn-default tooltips' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
                }
            },
            {	targets: 1, class:'td-kecil text-align-center' },
            {	targets: 2, class:'td-kecil text-align-center', },
            {	targets: 3, class:'td-kecil text-align-center', },
            {	targets: 4, class:'td-kecil text-align-center' },
            {	targets: 5, class:'td-kecil text-align-center' },
            {	targets: 7, class:'td-kecil' },
            {	targets: 8, class:'td-kecil text-align-center' },
            {	targets: 9, 
				class:'td-kecil text-align-right',
				render : function(data, type, full, meta){
					var ret = 0;
                    if(data){
                        ret = formatNumberForUser(data)
                    }
					return ret;
				}
			},
            {	targets: 10, 
				class:'td-kecil text-align-right',
				render : function(data, type, full, meta){
                    var ret = 0;
                    if(data){
                        ret = formatNumberForUser(data)
                    }
					return ret;
				}
			},
            {	targets: 11, 
				class:'td-kecil text-align-right',
				render : function(data, type, full, meta){
					var ret = 0;
                    if(data){
                        ret = formatNumberForUser(data)
                    }
					return ret;
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
            if(aData[10] > 0){ // terpakai
				$(nRow).attr("style","background-color: #E8FFDA;");
				
				if(aData[11] > 0){ //sisa
					$(nRow).attr("style","background-color: #FFF2DA;");
				} else {
					$(nRow).find('.btn-icon-only').remove();
				}
            } 
		},
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