<?php
/* @var $this yii\web\View */
$this->title = 'Hasil Verifikasi Data';
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); 
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
								<div class="row" style="text-align: center;">
									<div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-4 control-label">Pilih Agenda</label>
                                            <div class="col-md-8">
                                                <?= \yii\helpers\Html::activeDropDownList($model, "stockopname_agenda_id", app\models\TStockopnameAgenda::getOptionListScan(), ['class'=>'form-control','prompt'=>'','onchange'=>'setHasil();']) ?>
                                            </div>
                                        </div>
									</div>
									<div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?= yii\helpers\Html::checkbox("checkall",true,['onchange'=>'checkAll()']) ?> Jenis Produk</label>
                                            <div class="col-md-8">
                                                <?= yii\helpers\Html::activeCheckboxList($model, "jenis_produk", ["Plywood"=>"Plywood",
                                                                                                                "Platform"=>"Platform",
                                                                                                                "Lamineboard"=>"Lamineboard",
                                                                                                                "Sawntimber"=>"Sawntimber",
                                                                                                                "Veneer"=>"Veneer",
                                                                                                                "Moulding"=>"Moulding",
                                                                                                                "Lainnya"=>"Lainnya"],['onchange'=>'setHasil()']) ?>
                                            </div>
                                        </div>
									</div>
								</div>
                                <br><hr>
                                <div id="hasil" style="height: 100px;"></div>
                                <div class="row" style="text-align: center;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php $this->registerJs(" 
	formconfig();
    checkAll();
", yii\web\View::POS_READY); ?>
<script>
function setHasil(){
	var stockopname_agenda_id = $('#<?= \yii\bootstrap\Html::getInputId($model, "stockopname_agenda_id") ?>').val();
    var jenis_produk = []; $("input:checkbox[name*='[jenis_produk]']:checked").each(function(i){ jenis_produk[i] = $(this).val(); });
    $("#hasil").html("");
    $("#hasil").addClass("animation-loading");
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/setHasil']); ?>',
        type   : 'POST',
        data   : {stockopname_agenda_id:stockopname_agenda_id,jenis_produk:jenis_produk},
        success: function (data) {
			if(data.hasil){
                $("#hasil").html(data.hasil);
                $("#table-detail > tbody > tr:last").find("td").each(function(){
                    var isi = $(this).html();
                    $(this).html( "<b>"+isi+"</b>" );
                });
                getItemsScanned(stockopname_agenda_id,jenis_produk);
            }
            $("#hasil").removeClass("animation-loading");
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function checkAll(){
    if($("input:checkbox[name*='checkall']").is(':checked')){
        $("input:checkbox[name*='[jenis_produk]']").each(function(){
            $(this).prop("checked",true);
        });
    }else{
        $("input:checkbox[name*='[jenis_produk]']").each(function(){
            $(this).prop("checked",false);
        });
    }
    setTimeout(function(){
        setHasil();
    },300);
}

function setFilterStatus(){
    var stockopname_agenda_id = $('#<?= \yii\bootstrap\Html::getInputId($model, "stockopname_agenda_id") ?>').val();
    var jenis_produk = []; $("input:checkbox[name*='[jenis_produk]']:checked").each(function(i){ jenis_produk[i] = $(this).val(); });
    getItemsScanned(stockopname_agenda_id, jenis_produk);
}

function getItemsScanned(stockopname_agenda_id,jenis_produk){
    var status = $("#filter_status").val();
    var dt_table =  $('#table-master').dataTable({
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/gudang/stockopname/getHasilDetail') ?>',
			data:{
				dt: 'table-master',
				stockopname_agenda_id : stockopname_agenda_id,
                jenis_produk:jenis_produk,
                status:status
			} 
		},
        "pageLength": 100,
        columnDefs: [
			{ 	targets: 0, class: 'text-align-center td-kecil2',
                orderable: false, 
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
            { 	targets: 1, class:"text-align-center td-kecil2", 
                render: function ( data, type, full, meta ) {
					return "<a onclick='infoPalet(\""+data+"\")'>"+data+"</a>";
                }
            },
            { 	targets: 2, class:"text-align-left td-kecil2", 
                render: function ( data, type, full, meta ) {
					return (data)?data:"-";
                }
            },
            { 	targets: 3, class:"text-align-center td-kecil2", 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            { 	targets: 4, class:"text-align-center td-kecil2", 
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            { 	targets: 5, class:"text-align-center td-kecil2", 
                render: function ( data, type, full, meta ) {
					return "-";
                }
            },
            { 	targets: 6, class:"text-align-center td-kecil2", 
                render: function ( data, type, full, meta ) {
					return "-";
                }
            },
            { 	targets: 7, class:"text-align-center td-kecil2 fontsize-0-9", 
                render: function ( data, type, full, meta ) {
                    if(data != '-'){
                        var date = new Date(data);
                        date = date.toString('dd/MM/yyyy H:m:s');
                        return "<b>"+full[8]+"</b><br>"+date;
                    }else{
                        return '-';
                    }
					
                }
            },
            { 	targets: 8, class:"text-align-center td-kecil2 stat", 
                render: function ( data, type, full, meta ) {
                    return full[9];
//					return full[9]+
//                            '<a style="padding: 0px 1px; margin-right: 0px;" class="btn btn-xs btn-outline red-flamingo tooltips" data-original-title="Delete"  onclick="hapus('+full[0]+')"><i class="fa fa-trash-o"></i></a>';
                }
            },
            { 	targets: 9, class:"text-align-center td-kecil2", 
                render: function ( data, type, full, meta ) {
                    var disp = "";
                    if(full[9] == "FYSY"){
                        var disp = "visibility:hidden";
                    }
                    return '<a style="padding: 0px 1px; margin-right: 0px; '+disp+'" class="btn btn-xs btn-outline blue tooltips" data-original-title="Info Masalah"  onclick="InfoProdukSo('+full[0]+')"><i class="fa fa-info-circle"></i></a>'+
                           '<a style="padding: 0px 1px; margin-right: 0px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>';
                }
            },
        ],
        "autoWidth":false,
        "bDestroy": true,
        "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
            $(nRow).each(function(){
                var stat = $(this).find("td.stat").text();
                if(stat == "FYSY"){
                    $(this).attr('style','background-color:#f1f4e6');
                }else if(stat == "FYSN"){
                    $(this).attr('style','background-color:#f7e8e8');
                }
            });
        },
    });
}
function infoPalet(nomor_produksi){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/spm/infoPalet','nomor_produksi'=>'']) ?>'+nomor_produksi,'modal-info-palet','90%');
}
function InfoProdukSo(nomor_produksi){
	openModal('<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/InfoProdukSo','id'=>'']) ?>'+nomor_produksi,'modal-master-info','90%');
}
function printout(caraPrint){
    var stockopname_agenda_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "stockopname_agenda_id") ?>").val();
    var filterstatus = $("#filter_status").val();
    var jenis_produk = ""; $("input:checkbox[name*='[jenis_produk]']:checked").each(function(i){ jenis_produk += "jenis_produk[]="+$(this).val()+"&"; });
	window.open("<?= yii\helpers\Url::toRoute('/gudang/stockopname/printHasil') ?>?stockopname_agenda_id="+stockopname_agenda_id+"&"+jenis_produk+"caraprint="+caraPrint+"&filterstatus="+filterstatus,"",'location=_new, width=1200px, scrollbars=yes');
}
function confirmHasil(){
	var stockopname_agenda_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'stockopname_agenda_id') ?>').val();
    var jenis_produk = []; $("input:checkbox[name*='[jenis_produk]']:checked").each(function(i){ jenis_produk[i] = $(this).val(); });
	openModal('<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/hasil','confirm'=>true,'id'=>'']); ?>'+stockopname_agenda_id+"&jenis_produk="+jenis_produk,'modal-transaksi','90%');
}

</script>