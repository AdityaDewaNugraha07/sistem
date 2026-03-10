<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Agenda Verifikasi Data Yang Pernah Dibuat'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
								<thead>
									<tr>
                                        <th></th>
										<th><?= Yii::t('app', 'Kode'); ?></th>
										<th><?= Yii::t('app', 'Tanggal'); ?></th>
										<th><?= Yii::t('app', 'Status'); ?></th>
										<th><?= Yii::t('app', 'Penanggung Jawab<br>Kadep Acct'); ?></th>
										<th><?= Yii::t('app', 'Menyetujui<br>Kadiv FAT'); ?></th>
										<th><?= Yii::t('app', 'Mengetahui<br>Kanit Gudang'); ?></th>
										<th><?= Yii::t('app', 'Mengetahui<br>Kadiv Mkt'); ?></th>
										<th style="width: 60px;"><?= Yii::t('app', ''); ?></th>
									</tr>
								</thead>
							</table>
						</div>
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
    dtAftersave();
", yii\web\View::POS_READY); ?>
<script>
function dtAftersave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/gudang/stockopname/DaftarAfterSave') ?>',data:{dt: 'table-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
			{ 	targets: 0, visible:false },
            { 	targets: 1, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
			{ 	targets: 2,  class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            { 	targets: 3, class:"text-align-center td-kecil",
                render: function(data){
                    if(data=="NOT ACTIVE"){
                        return '<span class="label label-sm label-default"> '+data+' </span>';
                    }else if(data=="ACTIVE"){
                        return '<span class="label label-sm label-warning"> '+data+' </span>';
                    }else if(data=="DONE"){
                        return '<span class="label label-sm label-success"> '+data+' </span>';
                    }else if(data=="REJECTED"){
                        return '<span class="label label-sm label-danger"> '+data+' </span>';
                    }
                }
            },
            { 	targets: 4, class:"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					return "<b>"+data+"</b>";
                }
            },
			{ 	targets: 5, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[8];
					if(status=="<?= app\models\TApproval::STATUS_APPROVED ?>"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="<?= app\models\TApproval::STATUS_REJECTED ?>"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					if(status){
						return "<span style='font-size:1rem;'><b>"+data+"</b></span><br>"+status+"";
					}else{
						return "";
					}
                }
            },
			{ 	targets: 6, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[9];
					if(status=="<?= app\models\TApproval::STATUS_APPROVED ?>"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="<?= app\models\TApproval::STATUS_REJECTED ?>"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					if(status){
						return "<span style='font-size:1rem;'><b>"+data+"</b></span><br>"+status+"";
					}else{
						return "";
					}
                }
            },
			{ 	targets: 7, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[10];
					if(status=="<?= app\models\TApproval::STATUS_APPROVED ?>"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="<?= app\models\TApproval::STATUS_REJECTED ?>"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					if(status){
						return "<span style='font-size:1rem;'><b>"+data+"</b></span><br>"+status+"";
					}else{
						return "";
					}
                }
            },
            {	targets: 8, 
				render: function ( data, type, full, meta ) {
					var display = "";
//					if((full[8]=="<?= app\models\TApproval::STATUS_APPROVED ?>") || (full[9]=="<?= app\models\TApproval::STATUS_APPROVED ?>") || (full[10]=="<?= app\models\TApproval::STATUS_APPROVED ?>") ){
					if((full[3]=="DONE") || (full[3]=="REJECTED") ){
						display =  'visibility: hidden;';
					}
					var ret =  '<center>\n\
									<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihat('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;
				}
			},
            
        ],
		"autoWidth":false,
		"bStateSave": true,
		"bDestroy": true,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihat(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/index','stockopname_agenda_id'=>'']); ?>'+id);
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/index','edit'=>"1",'stockopname_agenda_id'=>'']); ?>'+id);
}
</script>