<?php
/* @var $this yii\web\View */
$this->title = 'Verifikasi Data Gudang Barang Jadi';
app\assets\DatepickerAsset::register($this);
app\assets\WebcodecamAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<style>
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
.table-laporan, 
.table-laporan > tbody > tr > td, 
.table-laporan > tbody > tr > th, 
.table-laporan > tfoot > tr > td, 
.table-laporan > tfoot > tr > th, 
.table-laporan > thead > tr > td, 
.table-laporan > thead > tr > th {
    border: 1px solid #B8BBBE;
	line-height: 1.2 !important;
	font-size: 1.2rem;
}
/*table#table-master tr td a.btn { display:none;  }
table#table-master tr:hover td a.btn { display:inline }*/
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/stockopname/index") ?>"> <?= Yii::t('app', 'Agenda Verifikasi Data'); ?> </a>
					</li>
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/stockopname/scan") ?>"> <?= Yii::t('app', 'Scan Verifikasi Data'); ?> </a>
					</li>
                    <li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/stockopname/hasil") ?>"> <?= Yii::t('app', 'Hasil Verifikasi Data'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
								<div class="row" style="text-align: center;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="col-md-4 control-label" >Nama Peserta</label>
                                            <div class="col-md-8">
                                                <?= \yii\helpers\Html::activeTextInput($model, "nama_peserta", ['class'=>'form-control','disabled'=>true]) ?>
                                            </div>
                                        </div>
									</div>
									<div class="col-md-4">
                                        <div class="form-group">
                                        <label class="col-md-4 control-label">Kode Agenda</label>
                                            <div class="col-md-8">
                                                <?= \yii\helpers\Html::activeDropDownList($model, "kode_agenda", app\models\TStockopnameAgenda::getOptionListScan(), ['class'=>'form-control','prompt'=>'','onchange'=>'setScanner(); getItemsScanned();']) ?>
                                            </div>
                                        </div>
									</div>
									<div class="col-md-4">
                                        <div class="form-group">
                                        <label class="col-md-4 control-label" >Lokasi Gudang</label>
                                            <div class="col-md-8">
                                                <?= \yii\helpers\Html::activeDropDownList($model, "gudang_id", app\models\MGudang::getOptionList(), ['class'=>'form-control','prompt'=>'','onchange'=>'setScanner(); getItemsScanned();']) ?>
                                            </div>
                                        </div>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="portlet light bordered">
                            <div class="portlet-body">
								<div class="row" style="text-align: center;">
									<div class="col-md-12">
										<div class="well" style="position: relative;display: inline-block;">
											<canvas id="webcodecam-canvas"></canvas>
											<div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
											<div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
											<div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
											<div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
										</div>
										<div class="row" style="display: none;">
											<p id="scanned-QR" class="text-align-center"></p>
											<select class="form-control" id="camera-select"></select>
										</div>
									</div>
								</div>
								<div class="row" style="text-align: center;">
									<div class="col-md-12" style="margin-top: -15px; display: none;" id="place-btnenabled">
										<a id="play" class="btn hijau btn-sm"><i class="fa fa-play"></i></a>
										<a id="pause" class="btn yellow btn-sm"><i class="fa fa-pause"></i></a>
										<a id="stop" class="btn red-flamingo btn-sm"><i class="fa fa-stop"></i></a>
									</div>
									<div class="col-md-12" style="margin-top: -15px;" id="place-btndisabled">
										<a id="" class="btn grey btn-sm" style="cursor: not-allowed;"><i class="fa fa-play"></i></a>
										<a id="" class="btn grey btn-sm" style="cursor: not-allowed;"><i class="fa fa-pause"></i></a>
										<a id="" class="btn grey btn-sm" style="cursor: not-allowed;"><i class="fa fa-stop"></i></a>
									</div>
								</div>
                                <div class="row" style="margin-left: -30px; margin-right: -30px;">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<!--<div class="table-scrollable">-->
											<table class="table table-striped table-bordered table-advance table-hover table-laporan" style="width: 100%; border: 1px solid #A0A5A9;" id="table-master">
												<thead>
													<tr>
														<th style="width: 25px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th style="width: 100px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
														<th style="font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Produk'); ?></th>
														<th style="width: 40px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Lokasi<br>Gudang'); ?></th>
														<th style="width: 80px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Agenda'); ?></th>
														<th style="width: 35px; line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty'); ?></th>
														<th style="width: 45px; font-size: 1.2rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
														<th style="width: 90px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Scaned'); ?></th>
														<th style="width: 35px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Stat'); ?></th>
                                                        <th style="width: 35px;"></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
										<!--</div>-->
                                    </div>
                                </div>
                                <br>
<!--                                <div class="row">
                                    <div class="col-md-12" style="margin-top: -10px; margin-bottom: -20px;">
                                        <h5 id="judul-hasil"></h5>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Verifikasi Data'))."');
	formconfig();
	reading();
    getItemsScanned();
    setScanner();
", yii\web\View::POS_READY); ?>
<script>
function setScanner(){
    var nama_peserta = $("#<?= \yii\bootstrap\Html::getInputId($model, "nama_peserta") ?>").val();
    var kode_agenda = $("#<?= \yii\bootstrap\Html::getInputId($model, "kode_agenda") ?>").val();
	var gudang_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "gudang_id") ?>").val();
    $("#judul-hasil").html("");
    if(nama_peserta && kode_agenda && gudang_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/checkAgendaAktif']); ?>',
            type   : 'GET',
            data   : {},
            success: function (data) {
                if(data.status){
                    if(data.judulhasil){
                        $("#judul-hasil").html(data.judulhasil);
                    }
                    if(data.agenda.status=="ACTIVE"){
                        $("#place-btnenabled").attr("style","");
                        $("#place-btndisabled").attr("style","display:none;");
                    }
                }else{
                    if(data.msg){
                        cisAlert(data.msg,"Warning");
                    }
                }

                return false;
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }else{
        $("#place-btnenabled").attr("style","display:none;");
        $("#place-btndisabled").attr("style","");
    }
}

function getItemsScanned(){
    var kode_agenda = $("#<?= \yii\bootstrap\Html::getInputId($model, "kode_agenda") ?>").val();
	var gudang_id = $("#<?= \yii\bootstrap\Html::getInputId($model, "gudang_id") ?>").val();
    var dt_table =  $('#table-master').dataTable({
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/gudang/stockopname/getItemsScanned') ?>',
			data:{
				dt: 'table-master',
				kode_agenda : kode_agenda,
				gudang_id : gudang_id
			} 
		},
        "pageLength": 10,
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
					return formatNumberForUser(data);
                }
            },
            { 	targets: 6, class:"text-align-center td-kecil2", 
                render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
                }
            },
            { 	targets: 7, class:"text-align-center td-kecil2 fontsize-0-9", 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy H:m:s');
					return "<b>"+full[8]+"</b><br>"+date;
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
                           '<a style="padding: 0px 1px; margin-right: 0px;" class="btn btn-xs btn-outline red-flamingo tooltips" data-original-title="Delete"  onclick="hapusItem('+full[0]+')"><i class="fa fa-trash-o"></i></a>';
                }
            },
        ],
        "autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        "bDestroy": true,
        "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
            $(nRow).each(function(){
                var stat = $(this).find("td.stat").text();
                if(stat == "FYSY"){
                    $(this).attr('style','background-color:#f1f4e6');
                }else if(stat == "FYSN"){
                    $(this).attr('style','background-color:#f7e8e8');
                    $(this).find('td').attr('onclick','InfoProdukSo("'+aData[0]+'")');
                    $(this).find("td:eq(1)").removeAttr('onclick');
                    $(this).find("td:last").removeAttr('onclick');
                }
            });
        }
    });
}

function pick(prod_number){
	var stockopname_agenda_id = $('#<?= \yii\bootstrap\Html::getInputId($model, "kode_agenda") ?>').val();
	var gudang_id = $('#<?= \yii\bootstrap\Html::getInputId($model, "gudang_id") ?>').val();
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/saveNomorProduksi']); ?>',
        type   : 'POST',
        data   : {prod_number:prod_number,stockopname_agenda_id:stockopname_agenda_id,gudang_id:gudang_id},
        success: function (data) {
			if(data.msg){
				cisAlert(data.msg);
			}
			$('#table-master').dataTable().fnClearTable();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function hapusItem(prod_number){
    openModal('<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/deleteSo','id'=>'']) ?>'+prod_number+'&tableid=table-master','modal-delete-record');
}
function infoPalet(nomor_produksi){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/spm/infoPalet','nomor_produksi'=>'']) ?>'+nomor_produksi,'modal-info-palet','90%');
}
function InfoProdukSo(nomor_produksi){
	openModal('<?= \yii\helpers\Url::toRoute(['/gudang/stockopname/InfoProdukSo','id'=>'']) ?>'+nomor_produksi,'modal-master-info','90%');
}

</script>