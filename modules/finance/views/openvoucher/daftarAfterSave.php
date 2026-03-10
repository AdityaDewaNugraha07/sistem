<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave-this" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Open Voucher yang telah diajukan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
                                <tr>
                                    <th></th>
                                    <th style="width: 90px;"><?= Yii::t('app', 'Kode'); ?></th>
                                    <th style="width: 75px;"><?= Yii::t('app', 'Tanggal'); ?></th>
                                    <th style="width: 160px;"><?= Yii::t('app', 'Tipe'); ?></th>
                                    <th style="width: 80px;"><?= Yii::t('app', 'Dept'); ?></th>
                                    <th style="width: 90px;"><?= Yii::t('app', 'Reff No'); ?></th>
                                    <th><?= Yii::t('app', 'Penerima'); ?></th>
                                    <th><?= Yii::t('app', 'Penerima QQ'); ?></th>
                                    <th style="width: 80px;"><?= Yii::t('app', 'Cara Bayar'); ?></th>
                                    <th style="width: 100px;"><?= Yii::t('app', 'Total Tagihan'); ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Status<br>Approval'); ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Status / Nominal<br>Pembayaran'); ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Prepared<br>By'); ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Keterangan'); ?></th>
                                    <th style="width: 80px;"></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/finance/openvoucher/daftarAfterSave') ?>',data:{dt: 'modal-aftersave-this'} },
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
			{	targets: 4, class:"text-align-center td-kecil" },
			{	targets: 5, class:"text-align-left td-kecil",
                render: function ( data, type, full, meta ) {
                    var ret = data;
                    if(!data){
                        ret = "-";
                    }
                    return ret;
                }
            },
            {	targets: 6, class:"text-align-left td-kecil" },
			{	targets: 7, class:"text-align-left td-kecil",
                render: function ( data, type, full, meta) {
                    function nl2br (str, is_xhtml) {   
                        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
                        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
                    }                   
                    if (full[17] == '' || full[17] == null) {
                        ret = ''
                    } else {
                        ret = nl2br(full[17]);
                    }
                    return ret;
                }
            },
			{	targets: 8, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta) {
                    return full[7];
                }
            },
			{	targets: 9, class:"text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
                    if (full[19] == "USD") {
                        var mata_uang = "$";
                    } else if (full[19] == "EUR") {
                        var mata_uang = "&#128;";
                    } else if (full[19] == "CNY") {
                        var mata_uang = "¥";
                    } else {
                        var mata_uang = "Rp.";
                    }
                    if (full[19] == "IDR") {
                        var uang = formatInteger(full[8]);
                    } else {
                        var uang = formatNumberForUser2Digit(full[8]);
                    }
                    var ret = "<span class='pull-left'>"+mata_uang+"</span>"+uang;
                    if( full[3]=="PELUNASAN LOG SENGON" ){
                        var asd = $.parseJSON(full[16]);
                        var totalm3=0;
                        $(asd).each(function(i,v){
                            var m3 = 0;
                            $(v.diameter_harga).each(function(ii,vv){
                                m3 += unformatNumber( vv.m3 );
                            });
                            totalm3 += m3;
                        });
                        ret += "<br>("+formatNumberForUser3Digit( totalm3 )+" m<sup>3</sup>)";
                    }
					return ret;
                }
            },
			{	targets: 10, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(full[9]=='APPROVED'){
                        ret = '<span class="label label-sm label-success">'+full[9]+'</span>';
                    }else if(full[9]=='REJECTED'){
                        ret = '<span class="label label-sm label-danger">'+full[9]+'</span>';
                    }else if(full[9]=='Not Confirmed'){
                        ret = '<span class="label label-sm label-default">'+full[9]+'</span>';
                    }else if(full[9]=='ABORTED'){
                        ret = '<span class="label label-sm label-danger">'+full[9]+'</span>';
                    }
                    return ret;
                }
            },
			{	targets: 11, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    if(full[10] == 'PAID'){
                        var date = new Date(full[18]);
					    date = date.toString('dd/MM/yyyy');
                        ret = '<span style="color: green; font-weight: bold;">' + full[10] + '</span>' + '<br><span class="td-kecil2"> at ' + date + '</span>';
                    } else if(full[10] == 'UNPAID'){
                        var date = new Date(full[18]);
					    date = date.toString('dd/MM/yyyy');
                        ret = '<span style="color: orange; font-weight: bold;">' + full[10] + '</span>' + '<br><span class="td-kecil2"> plan ' + date + '</span>';
                    } else {
                        ret = full[10];
                    }
                    return ret;
                }
            },
			{	targets: 12, class:"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
                    return full[11];
                }
            },
			{	targets: 13, class:"text-align-left td-kecil2",
                render: function ( data, type, full, meta ) {
                    var ret = "";
                    if( full[3]=="PELUNASAN LOG SENGON" ){
                        var asd = $.parseJSON(full[16]);
                        $(asd).each(function(i,v){
                            var m3 = 0;
                            ret += "<b>"+v.reff_no+"</b> - "+formatNumberForUser3Digit( v.total_m3 )+" m<sup>3</sup>";
                            if((i+1) < asd.length){
                                ret += "<br>";
                            }
                        });
                    }else{
                        ret = full[15];
                    }
                    return ret;
                }
            },
			{	targets: 14, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					var display = "";
					if( (full[9]=="<?= app\models\TApproval::STATUS_APPROVED ?>")||(full[9]=="<?= app\models\TApproval::STATUS_REJECTED ?>") || (full[9]=="<?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?>") ) {
						display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Edit""><i class="fa fa-edit"></i></a>';
					}else{
                        display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>';
                    }
                    
					var ret =  '<center>\n\
									'+display+'\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/index','open_voucher_id'=>'']); ?>'+id);
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/index','open_voucher_id'=>'']); ?>'+id+'&edit=1');
}

</script>