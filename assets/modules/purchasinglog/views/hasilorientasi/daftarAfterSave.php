<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Hasil Orientasi Log Alam'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Nama IUPHHK'); ?></th>
                                                                        <th><?= Yii::t('app', 'Nama IPK'); ?></th>
									<th><?= Yii::t('app', 'Lokasi Muat'); ?></th>
									<th style="width:80px;"><?= Yii::t('app', 'RKT / Thn'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Reviewed By<br>GM Purchasing'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Approved By<br>Direktur Utama'); ?></th>
									<th style="width: 60px;"></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/hasilorientasi/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            }, 
			{ 	targets: 6, class :"text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data)+" M<sup>3</sup>";
                }
            }, 
			{ 	targets: 7, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[9];
					if(status=="APPROVED"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="REJECTED"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					return "<span style='font-size:1rem;'><b>"+data+"</b></span><br>"+status+"";
                }
            }, 
			{ 	targets: 8, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[10];
					if(status=="APPROVED"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="REJECTED"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					return "<span style='font-size:1rem;'><b>"+data+"</b></span><br>"+status+"";
                }
            }, 
			{	targets: 9, 
				render: function ( data, type, full, meta ) {
					var display = "";
//					if((full[11]=="<?php // echo app\models\TApproval::STATUS_APPROVED ?>")&&(full[12]=="<?php // echo app\models\TApproval::STATUS_APPROVED ?>")&&(full[13]=="<?php // echo app\models\TApproval::STATUS_APPROVED ?>")&&(full[14]=="<?php // echo app\models\TApproval::STATUS_APPROVED ?>")&&(full[15]=="<?php // echo app\models\TApproval::STATUS_APPROVED ?>")){
					if((full[9]!="<?= app\models\TApproval::STATUS_NOT_CONFIRMATED ?>") || (full[10]!="<?= app\models\TApproval::STATUS_NOT_CONFIRMATED ?>")){
						display =  'visibility: hidden;';
					}
					var ret =  '<center>\n\
									<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
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
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/index','hasil_orientasi_id'=>'']); ?>'+id);
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/index','hasil_orientasi_id'=>'']); ?>'+id+'&edit=1');
}

</script>