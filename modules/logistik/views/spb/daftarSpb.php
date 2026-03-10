<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar SPB telah diajukan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
								<thead>
									<tr>
										<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
										<th><?= Yii::t('app', 'Kode / No. SPB'); ?></th>
										<th><?= Yii::t('app', 'Tanggal'); ?></th>
										<th><?= Yii::t('app', 'Diminta'); ?></th>
										<th><?= Yii::t('app', 'Disetujui'); ?></th>
										<th><?= Yii::t('app', 'Diketahui'); ?></th>
										<th><?= Yii::t('app', 'Status Approval'); ?></th>
										<th><?= Yii::t('app', 'Status SPB'); ?></th>
										
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
            pageLength: 50,
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/logistik/spb/DaftarSpb') ?>?dept_id=<?= $departement_id ?>',data:{dt: 'table-aftersave'} },
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
			{ 	targets: 3, class:"td-kecil",
				render: function ( data, type, full, meta ) {
					return data;
                }
			},
			{ 	targets: 4, class:"td-kecil",
				render: function ( data, type, full, meta ) {
					return data;
                }
			},
			{ 	targets: 5, class:"td-kecil",
				render: function ( data, type, full, meta ) {
					return data;
                }
			},
			{ 	targets: 6, class:"td-kecil",
				render: function ( data, type, full, meta ) {
					var ret = '';
					if(full[7]=="APPROVED"){
						ret = "<a title='klick untuk melihat info approval' onclick='infoApproval("+full[0]+")'><span style='font-size:1.2rem;' class='label label-sm label-success'>APPROVED</span></a>";
					}else if(full[7]=="Not Confirmed"){
						ret = "<a title='klick untuk melihat info approval' onclick='infoApproval("+full[0]+")'><span style='font-size:1.2rem;' class='label label-sm label-default'>Not Confirmed</span></a>";
					}else if(full[7]=="REJECTED"){
						ret = "<a title='klick untuk melihat info approval' onclick='infoApproval("+full[0]+")'><span style='font-size:1.2rem;' class='label label-sm label-danger'>REJECTED</span></a>";
					}
					return '<center>'+ret+'</center>';
                }
			},
			{ 	targets: 7,  class:"text-align-center",
                render: function ( data, type, full, meta ) {
					var ret = "";
					if(full[8] !=="REJECTED"){					
						if(full[6] == "TERPENUHI"){
							ret = '<span class="label label-sm label-success"> TERPENUHI </span>';
						}else if(full[6] == "SEDANG DIPROSES"){
							ret = '<span class="label label-sm label-warning"> SEDANG DIPROSES </span>';
						}else if(full[6] == "BELUM DIPROSES"){
							ret = '<span class="label label-sm label-default"> BELUM DIPROSES </span>';
						}else if(full[6] == "DITOLAK"){
							ret = '<span class="label label-sm label-danger"> DITOLAK </span>';
						}
					}else{
						ret = '<span class="label label-sm label-danger"> TIDAK DIPROSES </span>';
					}
					return ret;
                }
            },		
            
			// { 	targets: 8,  class:"text-align-center td-kecil",
            //     render: function ( data, type, full, meta ) {
			// 		data = $.parseJSON(full[7]);
			// 		var ret = "";
			// 		if(data){
			// 			$(data).each(function(key,val){
			// 				if(key!=0){
			// 					ret += "<br>";
			// 				}
			// 				if(val.bpb_status=="SUDAH DITERIMA"){
			// 					ret += "<a class='font-green-meadow' onclick='infoBpb("+val.bpb_id+")'>"+val.bpb_kode+" - "+val.bpb_status+"<span>";
			// 				}else if(val.bpb_status=="BELUM DITERIMA"){
			// 					ret += "<a class='font-red-intense' onclick='infoBpb("+val.bpb_id+")'>"+val.bpb_kode+" - "+val.bpb_status+"<span>";
			// 				}
			// 			});
			// 		}else{
			// 			ret = "<i>-- Belum ada BPB --</i>";
			// 		}
			// 		return ret;
            //     }
            // },
			{	targets: 8, class:"text-align-center",
                render: function ( data, type, full, meta ) {
                    var ret =  '<a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat data pengajuan SPB" onclick="lihatSpb('+full[0]+')"><i class="fa fa-eye"></i></a>';
					if(full[6]=='BELUM DIPROSES' && full[7]=='<?= app\models\TApproval::STATUS_NOT_CONFIRMATED ?>'){
						ret +=  '<a class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Ubah data pengajuan SPB" onclick="editSpb('+full[0]+')"><i class="fa fa-edit"></i></a>';
					}
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
function lihatSpb(spb_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/logistik/spb/index','spb_id'=>'']); ?>'+spb_id);
}
function editSpb(spb_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/logistik/spb/index','edit'=>true,'spb_id'=>'']); ?>'+spb_id);
}
</script>