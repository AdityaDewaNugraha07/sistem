<?php
/* @var $this yii\web\View */
$this->title = 'Approval Center';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalcenter/spb") ?>"> <?= Yii::t('app', 'Not Confirmed'); ?> </a>
					</li>
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalcenter/spbConfirmed") ?>"> <?= Yii::t('app', 'Confirmed'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<?php echo Yii::t('app', 'SPB Not Confirmed List'); ?>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-master">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;"></th>
                                            <th style="width: 100px;"><?= Yii::t('app', 'Reff No.') ?></th>
											<th></th>
                                            <th style="line-height: 1; width: 90px;"><?= Yii::t('app', 'Tanggal<br>Berkas') ?></th>
                                            <th style="width: 180px;"><?= Yii::t('app', 'Assign To') ?></th>
                                            <th style="width: 180px;"><?= Yii::t('app', 'Confirm By') ?></th>
                                            <th><?= Yii::t('app', 'Contain') ?></th>
                                            <th style="width: 100px;"><?= Yii::t('app', 'Status') ?></th>
                                            <th style="width: 30px;"></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" dtMaster(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Approve SPB'))."');", yii\web\View::POS_READY); ?>
<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvalcenter/'.$this->context->action->id); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= $url ?>',data:{dt: 'table-master'} },
//		order: [
//            [1, 'desc'],
//        ],
        columnDefs: [
			{ 	targets: 0, class: 'text-align-center td-kecil',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 1, class: 'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
                    return data;
                }
            },
			{	targets: 2, visible: false, class:'td-kecil' },
            { 	targets: 3, class:"text-align-center td-kecil", 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return date;
                }
            },
			{	targets: 4, class: 'td-kecil', },
			{	targets: 5, class: 'td-kecil', },
			{	targets: 6, class: 'td-kecil', 
                render: function ( data, type, full, meta ) {
                    data = $.parseJSON(data);
					var ret = "";
					if(data){
						$(data).each(function(key,val){
							ret += '<span style="font-size:0.9rem;">'+val.bhp_nm+' ('+val.spbd_jml+val.bhp_satuan+')</span>';
                            if(data.length != (key+1)){
                                ret += ', ';
                            }
						});
					}
                    if(ret.length > 100){
                        ret = ret.substring(0, 100)+'...';
                    }
                    
					return ret;
                }
            },
            {	targets: 7, class:'td-kecil text-align-center',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data=='APPROVED'){
                        ret = '<span class="label label-success" style="font-size:1.1rem;">'+data+'</span>';
                    }else if(data=='REJECTED'){
                        ret = '<span class="label label-danger" style="font-size:1.1rem;">'+data+'</span>';
                    }else if(data=='Not Confirmed'){
                        ret = '<span class="label label-default" style="font-size:1.1rem;">'+data+'</span>';
                    }
                    return ret;
                }
            },
            {	targets: 8, class:'td-kecil',
                orderable: false,
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
			{	targets: 9, visible: false, },
        ],
        "autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        "bStateSave": true,
    });
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalcenter/info','id'=>'']) ?>'+id,'modal-master-info',null," $('#table-master').dataTable().fnClearTable(); ");
}
</script>