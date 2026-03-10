<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-posengon" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar PO Log Sengon/Jabon'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode PO'); ?></th>
									<th><?= Yii::t('app', 'Tanggal PO'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Suplier'); ?></th>
									<th style=""></th>
									<th><?= Yii::t('app', 'Periode Pengiriman'); ?></th>
									<th></th>
									<th><?= Yii::t('app', 'Kuota'); ?></th>
									<th><?= Yii::t('app', 'Status'); ?></th>
									<th></th>
									<th></th>
									<th></th>
									<th><?= Yii::t('app', 'Kode<br>Open Voucer'); ?></th>
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
<?php $this->registerJs("
    dtAfterSave();
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/posengon/cariPoSengon') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 1, class:"td-kecil", 
                render: function ( data, type, full, meta ) {
                    <?php if(!empty($pick)){ ?>
                        if( (full[8]=="<?= app\models\TApproval::STATUS_APPROVED ?>") ){
                            var ret =  "<a onclick='pick(\""+data+"\",\""+full[11]+"\")' class='btn btn-xs btn-icon-only btn-default tooltips' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                        }else{
                            var ret =   "<a class='btn btn-xs btn-icon-only btn-default grey tooltips' data-original-title='Not Qualified' style='width: 25px; height: 25px;' disabled=disabled><i class='fa fa-plus-circle'></i></a>"+data;
                        }
                    <?php }else{ ?>
                        var ret = data;
                    <?php } ?>
                    
                    return ret;
                }
            }, 
            { 	targets: 2, class:"td-kecil", 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            }, 
            { 	targets: 3, class :"text-align-left td-kecil", 
                render: function ( data, type, full, meta ) {
					var nama = "<b>"+data+"</b>";
					var almt = "<br>"+full[4];
					var jnslog = "Jenis : <b>"+full[13]+"</b><br>";
					return jnslog+nama+almt;
                }
            },
            {	targets: 4, visible: false },
            { 	targets: 5, class :"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					var date2 = new Date(full[6]);
					date = date.toString('dd/MM/yyyy');
					date2 = date2.toString('dd/MM/yyyy');
					return date+" sd "+date2;
                }
            }, 
            {	targets: 6, visible: false },
            { 	targets: 7, class :"text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
                    var ret = '';
                    if(data){
                        var asd = $.parseJSON(data);
                        $.each(asd,function(key,val){
                            ret += key+'cm='+val+'m<sup>3</sup> ';
                        });
                    }
					return ret;
                }
            }, 
            { 	targets: 8, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = data;
					if(status=="<?= app\models\TApproval::STATUS_APPROVED ?>"){
						status = "<span class='font-green-seagreen'>"+data+"</span><br>by "+full[9]+"<br>at "+full[10];
					}else if(status=="<?= app\models\TApproval::STATUS_REJECTED ?>"){
						status = "<span class='font-red-flamingo'>"+data+"</span><br>by "+full[9]+"<br>at "+full[10];
					}
					if(status){
						return status;
					}else{
						return "";
					}
                }
            }, 
            {	targets: 9, visible: false },
            {	targets: 10, visible: false },
            {	targets: 11, visible: false },
            {	targets: 12, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
                    var data = $.parseJSON(data);
                    var ret = '';
                    if(data){
						$(data).each(function(key,val){
							if(key!=0){
								ret += "<br>";
							}
                            ret += "<a class='font-blue-steel' onclick='lihatDetailByKode(\""+val.kode+"\")'>"+val.kode;
						});
					}else{
						ret = "-";
					}
					return ret;
                }
            },
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetailByKode(kode){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/finance/openvoucher/index','kode'=>'']); ?>'+kode);
}
</script>