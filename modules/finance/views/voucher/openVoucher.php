<?php 
app\assets\DatatableAsset::register($this); 
?>
?>
<div class="modal fade" id="modal-open-voucher" tabindex="-1" role="basic" aria-hidden="true">
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
									<th style="width: 80px;"><?= Yii::t('app', 'Dept'); ?></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Reff No'); ?></th>
                                    <th style="width: 180px;"><?= Yii::t('app', 'Penerima'); ?></th>
									<th><?= Yii::t('app', 'Total Tagihan'); ?></th>
									<th><?= Yii::t('app', 'Prepared By'); ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Keterangan'); ?></th>
                                    <th style="line-height: 1;"><?= Yii::t('app', 'Status<br>Approve'); ?></th>                                    
                                    <th style="line-height: 1"><?= Yii::t('app', 'Status<br>Pembayaran'); ?></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/finance/voucher/cariOpenVoucher') ?>',data:{dt: 'table-open-voucher'} },
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
			{	targets: 5, class:"text-align-left td-kecil" },
			{	targets: 6, class:"text-align-left td-kecil" },
			{	targets: 7, class:"text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
                    if(full[22] == "IDR"){
                        ret = formatInteger(data);
                    } else {
                        ret = formatNumberForUser2Digit(data);
                    }
					return ret;
                }
            },
			{	targets: 8, class:"text-align-center td-kecil", },
            {	targets: 9, class:"text-align-left td-kecil",
                render: function ( data, type, full, meta ) {
                    if(data == null || data == ''){
                        ket =  full[15];
                    } else {
                        ket = data + '<br>' + full[15];
                    }
                    return ket;
                }
            },
			{	targets: 10, class:"text-align-left td-kecil2", 
                render: function ( data, type, full, meta ) {
                    var ret = "";
                    if( (full[11]=="<?= app\models\TApproval::STATUS_APPROVED ?>") ) {
                        ret = '<a style="font-size: 10px;" onclick="infoApprover(\''+full[1]+'\')"><span class="label label-sm label-success" style="font-size: 1.2rem; padding: 3px 6px;">'+full[11]+'</span></a>';
                    }else if(full[11]=='Not Confirmed'){
                        ret = '<a style="font-size: 10px;" onclick="infoApprover(\''+full[1]+'\')"><span class="label label-sm label-warning" style="font-size: 1.2rem; padding: 3px 6px;">'+full[11]+'</span></a>';
                        
                    }else if(full[11]=='REJECTED'){
                        ret = '<a style="font-size: 10px;" onclick="infoApprover(\''+full[1]+'\')"><span class="label label-sm label-warning" style="font-size: 1.2rem; padding: 3px 6px;">'+full[11]+'</span></a>';
                        
                    }
                    return '<center>'+ret+'</center>';
                }
            },            
            {	targets: 11, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(full[16] == "IDR"){
                        jml = formatInteger(unformatNumber(full[14]));
                    } else {
                        jml = formatNumberForUser2Digit(unformatNumber(full[14]));
                    }
                    if(full[10]=='PAID'){
                        ret = '<span class="label label-sm label-success" style="font-size: 10px; padding: 0px 6px;">'+full[10]+'</span><br><a style="font-size: 10px;" onclick="infoVoucher('+full[12]+')">'+( (full[13])?full[13]:'' )+'</a><br>'+jml;
                    }else if(full[10]=='UNPAID'){
                        ret = '<span class="label label-sm label-warning" style="font-size: 10px; padding: 0px 6px;">'+full[10]+'</span><br><a style="font-size: 10px;" onclick="infoVoucher('+full[12]+')">'+( (full[13])?full[13]:'' )+'</a><br>'+jml;
                    }else if(full[10]=='WAITING'){
                        ret = '<span class="label label-xs label-default">'+full[10]+'</span>';
                    }
                    return ret;
                }
            },
			{	targets: 12, class:"text-align-left td-kecil2", 
                render: function ( data, type, full, meta ) {
                    var display = "";
                    if( (full[10]=="WAITING") ) {
                        if( (full[11]=="<?= app\models\TApproval::STATUS_APPROVED ?>") ) {
                            display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline blue tooltips" data-original-title="Silahkan Proses voucher ini karena telah di approve" onclick="setOpenVoucher('+full[0]+')"><i class="icon-action-redo"></i></a>';
                        }else{
                            display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Voucher belum / tidak bisa diproses dikarenakan proses approval masih berjalan"><i class="icon-action-redo"></i></a>';
                        }
					}else{
                        display =  '<i class="fa fa-check font-green-seagreen"></i>';
                    }
                    
					var ret =  '<center>\n\
									'+display+'\n\
								</center>';
					return ret;
                }
            },  			
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>