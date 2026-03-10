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
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalcenter/spb") ?>"> <?= Yii::t('app', 'Not Confirmed'); ?> </a>
					</li>
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvalcenter/spbConfirmed") ?>"> <?= Yii::t('app', 'Confirmed'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <?= $this->render('_searchSPB', ['model' => $model]) ?>
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<?php echo Yii::t('app', 'SPB Confirmed List'); ?>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
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
<?php $this->registerJs(" 
    $('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
    formconfig(); 
	dtLaporan();
	changePertanggalLabel();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Approve SPB'))."');", yii\web\View::POS_READY); 
?>
<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvalcenter/'.$this->context->action->id); ?>
<?php
//$filtertahunaktif = Yii::$app->db->createCommand("SELECT EXTRACT(YEAR FROM spb_tanggal) FROM t_spb GROUP BY 1 ORDER BY 1 DESC")->all();
?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/topmanagement/approvalcenter/spbConfirmed') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, class: 'text-align-center td-kecil',searchable:false ,
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
			{	targets: 2, visible: false, class:'td-kecil',searchable:false },
            { 	targets: 3, class:"text-align-center td-kecil", searchable:false,
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return date;
                }
            },
			{	targets: 4, class: 'td-kecil', },
			{	targets: 5, class: 'td-kecil', },
			{	targets: 6, class: 'td-kecil', searchable:false,
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
            {	targets: 7, class:'td-kecil text-align-center',searchable:false,
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
            {	targets: 8, class:'td-kecil', searchable:false,
                orderable: false,
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
			{	targets: 9, visible: false, searchable:false},
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
			var api = this.api(), data;
			// Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            // Total over all pages
            var total_palet = api.column( 4 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            var total_pcs = api.column( 5 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            var total_m3 = api.column( 6 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
            var total_harga = api.column( 7 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
			$("#place-totalpalet").html( formatNumberForUser(total_palet) );
			$("#place-totalpcs").html( formatNumberForUser(total_pcs) );
			$("#place-totalm3").html( formatNumberFixed4(total_m3) );
			$("#place-totalharga").html( formatNumberForUser(total_harga) );
			
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
		"autoWidth" : false,
    });
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalcenter/info','id'=>'']) ?>'+id,'modal-master-info',"80%"," $('#table-master').dataTable().fnClearTable(); ");
}
function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>