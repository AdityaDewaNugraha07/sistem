<?php
/* @var $this yii\web\View */
$this->title = 'Plan Alokasi Stok Log';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\WebcodecamAsset::register($this);
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
        'labelOptions'=>['class'=>'col-md-5 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
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
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet light bordered">
                                <div class="portlet-body">
                                    <div class="row" style="text-align: center;">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'jenis_alokasi')->dropDownList( ['Plymill'=>'Plymill', 'Sawmill'=>'Sawmill'] ,['class'=>'form-control','prompt'=>'','onchange'=>'setScanner(); getItemsScanned();'])->label("Jenis Alokasi"); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row" style="text-align: center; padding-bottom: 10px;">
									<div class="col-md-12" id="place-inputmanual" style="display: none;">
										<a class="btn blue btn-sm btn-outline pull-right" onclick="inputManual()"
											style="margin-right: 5px;"><i class="fa fa-edit"></i>
											<?= Yii::t('app', 'Input Manual'); ?></a>
									</div>
                                    <div class="col-md-12" id="place-inputmanual-dis">
										<a class="btn grey btn-sm btn-outline pull-right"
											style="margin-right: 5px; cursor: not-allowed;"><i class="fa fa-edit"></i>
											<?= Yii::t('app', 'Input Manual'); ?></a>
									</div>
								</div>
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
								<br>
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: -10px; margin-bottom: -20px;">
                                        <h5>Daftar Stok Log</h5>
                                    </div>
                                </div><br>
								<div class="row">
                                    <div class="col-md-12">
										<!--<div class="table-scrollable">-->
											<table class="table table-striped table-bordered table-advance table-hover table-laporan" style="width: 100%; border: 1px solid #A0A5A9;" id="table-master">
												<thead>
													<tr>
														<th style="width: 10px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
														<th style="width: 100px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'No. Barcode'); ?></th>
														<th style="width: 100px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
														<th style="width: 60px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kubikasi'); ?></th>
														<th style="width: 60px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Scan At'); ?></th>
														<th style="width: 30px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Hapus'); ?></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3" style="text-align: right;">TOTAL</th>
                                                        <th style="text-align: right;">0</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
											</table>
										<!--</div>-->
                                    </div>
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
	reading();
    setScanner();
", yii\web\View::POS_READY); ?>
<script>
function setScanner(){
    var jenis_alokasi = $('#<?= \yii\bootstrap\Html::getInputId($model, "jenis_alokasi") ?>').val();
	if(!jenis_alokasi){
		$("#place-btnenabled").attr("style","display:none;");
		$("#place-btndisabled").attr("style","");
		$("#place-inputmanual").attr("style","display:none;");
		$("#place-inputmanual-dis").attr("style","");
	}else{
		$("#place-btnenabled").attr("style","");
		$("#place-btndisabled").attr("style","display:none;");
		$("#place-inputmanual").attr("style","");
		$("#place-inputmanual-dis").attr("style","display:none;");
    }
}

function getItemsScanned(){
    var jenis_alokasi = $('#<?= \yii\bootstrap\Html::getInputId($model, "jenis_alokasi") ?>').val();
    if ( $.fn.DataTable.isDataTable('#table-master') ) {
        $('#table-master').DataTable().clear().destroy();
    }
    if(jenis_alokasi){
        var dt_table =  $('#table-master').dataTable({
            ajax: { url: '<?= yii\helpers\Url::toRoute("/ppic/planalokasi/getItemsScanned") ?>?jenis_alokasi='+jenis_alokasi,data:{dt: 'table-master'} },
            "pageLength": 15,
            columnDefs: [
                { 	targets: 0, class: 'text-align-center',
                    orderable: false, 
                    render: function ( data, type, full, meta ) {
                        return '<center>'+(meta.row+1)+'</center>';
                    }
                },
                { 	targets: 1,
                    render: function ( data, type, full, meta ) {
                        return "<a onclick='info(\""+full[0]+"\")'>"+data+"</a>";
                    }
                },
                { 	targets: 3, class: 'text-align-right',
                    render: function ( data, type, full, meta ) {
                        return formatNumberFixed2(data);
                    }
                },
                { 	targets: 4, class:"text-align-center", 
                    render: function ( data, type, full, meta ) {
                        var date = new Date(data);
                        date = date.toString('dd/MM/yyyy HH:mm:ss');
                        return date;
                    }
                },
                {	targets: 5, class:'td-kecil',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        return '<center><a class="btn btn-xs red" onclick="hapusItem('+full[0]+');"><i class="fa fa-trash-o"></i></a></center>';
                    }
                },
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                total = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                // Update footer
                $.ajax({
                    url: "<?= yii\helpers\Url::toRoute('/ppic/planalokasi/total') ?>?jenis_alokasi="+jenis_alokasi+"&"+$('#form-transaksi').serialize(),
                    success: res => {
                        $('tr:eq(0) th:eq(1)', api.table().footer() ).html(`${(JSON.parse(res).total.toFixed(2).toLocaleString())}`)  //formatNumberForUser
                    }                                                                                                                                                                                                                                                                                                                                                                                                             
                })
            },
            "autoWidth":false,
            "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        });
    } else {
        $('#table-master tfoot th:eq(1)').html('0');
    }
}

function reading(){
	function Q(el) {
        if (typeof el === "string") {
            var els = document.querySelectorAll(el);
            return typeof els === "undefined" ? undefined : els.length > 1 ? els : els[0];
        }
        return el;
    }
    var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
    var scannerLaser = Q(".scanner-laser"),
        play = Q("#play"),
        scannedQR = Q("#scanned-QR"),
        pause = Q("#pause"),
        stop = Q("#stop");
        
    var args = {
        beep: '/'+window.location.pathname.split( '/' )[1]+'/'+window.location.pathname.split( '/' )[2]+'/themes/metronic/global/plugins/webcodecam/audio/beep.mp3',
        decoderWorker: '/'+window.location.pathname.split( '/' )[1]+'/'+window.location.pathname.split( '/' )[2]+'/themes/metronic/global/plugins/webcodecam/DecoderWorker.js',
        autoBrightnessValue: 100,
        zoom: 1.5,
		width: 280,
		height: 210,
        resultFunction: function(res) {
            [].forEach.call(scannerLaser, function(el) {
				el.style.opacity = 1;
				(function fade() {
					if ((el.style.opacity -= 0.1) < 0.5) {
						el.style.display = "none";
						el.classList.add("is-hidden");
					} else {
						requestAnimationFrame(fade);
					}
				})();
                setTimeout(function() {
                    if (el.classList.contains("is-hidden")) {
						el.classList.remove("is-hidden");
					}
					el.style.opacity = 0;
					el.style.display = "block";
					(function fade() {
						var val = parseFloat(el.style.opacity);
						if (!((val += 0.1) > 0.5)) {
							el.style.opacity = val;
							requestAnimationFrame(fade);
						}
					})();
                }, 300);
            });
//            scannedImg.src = res.imgData;
            scannedQR[txt] = res.format + ": " + res.code;
			pick(res.code);
			selesai_reading();
        },
        getDevicesError: function(error) {
            var p, message = "Error detected with the following parameters:\n";
            for (p in error) {
                message += p + ": " + error[p] + "\n";
            }
            alert(message);
        },
        getUserMediaError: function(error) {
            var p, message = "Error detected with the following parameters:\n";
            for (p in error) {
                message += p + ": " + error[p] + "\n";
            }
            alert(message);
        },
        cameraError: function(error) {
            var p, message = "Error detected with the following parameters:\n";
            if (error.name == "NotSupportedError") {
                var ans = confirm("Your browser does not support getUserMedia via HTTP!\n(see: https:goo.gl/Y0ZkNV).\n You want to see github demo page in a new window?");
                if (ans) {
                    window.open("https://andrastoth.github.io/webcodecamjs/");
                }
            } else {
                for (p in error) {
                    message += p + ": " + error[p] + "\n";
                }
                alert(message);
            }
        },
        cameraSuccess: function() {
//            grabImg.classList.remove("disabled");
        }
    };
    
    var decoder = new WebCodeCamJS("#webcodecam-canvas").buildSelectMenu("#camera-select", "environment|back").init(args);
	$("#play").on("click",function(){
		if (!decoder.isInitialized()) {
            scannedQR[txt] = "Scanning ...";
        } else {
            scannedQR[txt] = "Scanning ...";
            decoder.play();
        }
	});
	$("#pause").on("click",function(){
		scannedQR[txt] = "Paused";
        decoder.pause();
	});
	$("#stop").on("click",function(){
		scannedQR[txt] = "Stopped";
        decoder.stop();
	});
//	$("#play").trigger("click");
}

function selesai_reading(){
    $("#pause").trigger("click");
}

function pick(no_barcode){
	var jenis_alokasi = $('#<?= \yii\bootstrap\Html::getInputId($model, "jenis_alokasi") ?>').val();
    var no = no_barcode.match(/No\s*:\s*(\d+)/);
    var no_barcode = no[1];
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/ppic/planalokasi/showDetail']); ?>',
        type   : 'POST',
        data   : {no_barcode:no_barcode, jenis_alokasi:jenis_alokasi},
        success: function (data) {            
			if(data){
                if (data['msg'] == "Data ok") {
                    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/planalokasi/review','no_barcode'=>'']) ?>'+no_barcode+'&jenis_alokasi='+jenis_alokasi,'modal-review','70%');
                } else {
                    cisAlert(data['msg']);
                }
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function hapusItem(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/planalokasi/deleteNomorBarcode','id'=>'']) ?>'+id,'modal-delete-record',null);
}

function inputManual() {
    var jenis_alokasi = $('#<?= \yii\bootstrap\Html::getInputId($model, "jenis_alokasi") ?>').val();
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/planalokasi/inputManual', 'jenis_alokasi'=>'']) ?>'+jenis_alokasi, 'modal-inputManual', '60%');
}

function info(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/planalokasi/info','id'=>'']) ?>'+id,'modal-info');
}
</script>