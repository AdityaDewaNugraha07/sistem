<?php
/* @var $this yii\web\View */
$this->title = 'Master Buyer';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Buyer'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Buyer'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-buyer">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="width: 120px;"><?= Yii::t('app', 'Kode Buyer') ?></th>
                                            <th style="width: 300px;"><?= Yii::t('app', 'Nama Perusahaan') ?></th>
                                            <th><?= Yii::t('app', 'Alamat') ?></th>
                                            <th style="width: 60px;"><?= Yii::t('app', 'Status') ?></th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                
                <?php
                $model = new \app\models\MCustomer();
                ?>
                <div class="row">
                    <div class="col-md-12">
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
dtBuyer();
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Buyer'))."');
", yii\web\View::POS_READY); ?>

<script>
function dtBuyer(){
    var dt_table =  $('#table-buyer').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/exim/buyer/index') ?>',data:{dt: 'table-buyer'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 4,
                orderable: false,	
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data){
                        ret = 'Active'
                    }else{
                        ret = '<span style="color:#B40404">Non-Active</span>'
                    }
                    return ret;
                }
            },
            {	targets: 5, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
        ],
		"autoWidth":false,
    });
}

function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/exim/buyer/create') ?>','modal-buyer-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/exim/buyer/info','id'=>'']) ?>'+id,'modal-buyer-info');
}
function printout(caraPrint){
    var search = $('.form-control.input-sm.input-small.input-inline').val();
	window.open("<?= yii\helpers\Url::toRoute('/exim/buyer/BuyerPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint+"&search="+search,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>