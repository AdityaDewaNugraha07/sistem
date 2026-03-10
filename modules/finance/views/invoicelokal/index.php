<?php
/* @var $this yii\web\View */
$this->title = 'Invoice Lokal';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
use app\components\Params;
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
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
					<span class="pull-right">
						<a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Riwayat Invoice'); ?></a> 
					</span>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>
										<?php
										if(!isset($_GET['invoice_lokal_id'])){
											echo "Invoice Baru";
										}else{
											echo "Data Invoice";
										}
										?>
									</h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk'); ?></label>
											<div class="col-md-7">
												<?=  \yii\bootstrap\Html::activeDropDownList($model, 'jenis_produk', \app\models\MDefaultValue::getOptionList('jenis-produk'),['class'=>'form-control','onchange'=>'setDisplay(); setDropdownCustomer();','style'=>'width:100%;']); ?>
											</div>
										</div>
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nomor Invoice'); ?></label>
                                            <div class="col-md-7" style="padding-bottom: 5px;">
                                                <span class="input-group-btn" style="width: 20%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode1', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode2', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;','disabled'=>true]) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode3', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;','disabled'=>true]) ?>
                                                    <?php // echo \yii\bootstrap\Html::activeDropDownList($model, 'kode3', ["JASA"=>"JASA"], ['class'=>'form-control','style'=>'width:100%;padding:2px;']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode4', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;']) ?>
                                                </span>
                                                <span class="input-group-btn" style="width: 20%">
                                                    <?= \yii\bootstrap\Html::activeTextInput($model, 'kode5', ['class'=>'form-control','style'=>'width:100%;padding:2px;text-align:center;']) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?= $form->field($model, 'tanggal',[
																	'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
																	<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																	{error}</div>'])->textInput(['readonly'=>'readonly', 'onchange'=>'setNomor();'])->label("Tanggal Invoice"); ?>
                                        <div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Customer'); ?></label>
											<div class="col-md-7">
												<?=  \yii\bootstrap\Html::activeDropDownList($model, 'cust_id', [],['class'=>'form-control select2','prompt'=>'','onchange'=>'setCustomer(); ','style'=>'width:100%;']); ?>
											</div>
										</div>
                                        <?= $form->field($model, 'po_ko_id')->hiddenInput()->label(false); ?> 
										<?= $form->field($model, 'cust_an_alamat')->textarea(['disabled'=>true])->label("Alamat Customer"); ?>
										<?= $form->field($model, 'cust_no_npwp')->textInput()->label("NPWP Customer"); ?>  
                                        <?= $form->field($model, 'no_faktur_pajak')->textInput()->label("No. Faktur Pajak"); ?>                                      
										
									</div>
									<div class="col-md-6">
                                        
                                        <div class="form-group" id="place-nota" style="margin-bottom: 5px;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Nota'); ?></label>
											<div class="col-md-7">
                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'nota_penjualan[]', [],['multiple'=>'','class'=>'form-control','onchange'=>'setNota()','style'=>'height: 35px;'] ); ?> <!-- display: none; -->
											</div>
										</div>
                                        <div class="form-group" id="place-op" style="margin-bottom: 5px;">
											<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode OP'); ?></label>
											<div class="col-md-7">
                                                <?php echo \yii\bootstrap\Html::activeDropDownList($model, 'op_ko_id', [], ['class'=>'form-control','prompt'=>'','onchange'=>'setOpKo()','style'=>'width:100%;'] ); ?>
											</div>
										</div>
                                        <?= $form->field($model, 'penerbit')->dropDownList( app\models\MPegawai::getOptionListAtasan(),['class'=>'form-control','prompt'=>'','onchange'=>'','style'=>'width:100%;'] ); ?>
                                        <?= $form->field($model, 'cara_bayar')->dropDownList( ["Transfer Bank"=>"Transfer Bank"],['class'=>'form-control','style'=>'width:100%;'] ); ?>
                                        <?= $form->field($model, 'mata_uang')->dropDownList( app\models\MDefaultValue::getOptionList("mata-uang"),['class'=>'form-control','style'=>'width:100%;'] ); ?>
                                        <?= $form->field($model, 'bank_id')->dropDownList( app\models\MBank::getOptionList(),['prompt'=>'', 'class'=>'form-control','style'=>'width:100%;'] ); ?>
                                        <?= $form->field($model, 'include_ppn',['template' => '<label class="col-md-5 control-label">Include PPN</label><div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>'])
                                                    ->checkbox(['onchange'=>'total()'],false); ?>
                                        <?= $form->field($model, 'kawasan_berikat',['template' => '<label class="col-md-5 control-label">Kawasan Berikat</label><div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>'])
                                                    ->checkbox(['onchange'=>'totalInvoice()'],false); ?>
                                        <?= $form->field($model, 'ceklis_pph',['template' => '<label class="col-md-5 control-label">Include PPh</label><div class="mt-checkbox-list col-md-7"><label class="mt-checkbox mt-checkbox-outline">{input} {error}</label> </div>'])
                                                    ->checkbox(['onchange'=>'totalInvoice()'],false); ?>
									</div>
								</div><br><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 id='place-title'><?= Yii::t('app', 'Detail Order'); ?></h5>
                                        <h5 id='place-title-log'><?= Yii::t('app', 'Rekap Nota Pembayaran'); ?></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-detail">
												<thead>
													<tr>
                                                        <th rowspan="2" style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.3rem;">No.</th>
														<th rowspan="2" style="width: 200px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Nota'); ?></th>
														<th rowspan="2" style="line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Deskripsi'); ?></th>
														<th colspan="2" style="line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Pengiriman'); ?></th>
														<th colspan="2" style="line-height: 0.9;  padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Qty'); ?></th>
														<th rowspan="2" style="width: 120px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Harga'); ?></th>
														<th rowspan="2" style="width: 120px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Total'); ?></th>
														<!-- <th rowspan="2"h style="width: 40px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"></th> -->
													</tr>
                                                    <tr>
                                                        <th style="width: 80px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">Tanggal</th>
                                                        <th style="width: 170px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">Nopol / Supir</th>
                                                        <th style="width: 60px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">Pcs</th>
                                                        <th style="width: 100px; line-height: 0.9; padding: 5px; font-size: 1.2rem;">M<sup>3</sup></th>
                                                    </tr>
												</thead>
												<tbody>

												</tbody>
												<tfoot>
                                                    <tr>
														<td colspan="4">
															<!-- <a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a> -->
														</td>
														<td style="vertical-align: middle; text-align: right;">
															Total &nbsp; 
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_pcs',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_m3',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
                                                        <td style="vertical-align: middle; text-align: right;">
															<!-- Total Harga &nbsp;  -->
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_harga',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
												</tfoot>
											</table>
                                            <hr>
                                            <h5><?= Yii::t('app', 'Detail Invoice'); ?></h5>
                                            <table class="table table-striped table-bordered table-advance table-hover" style="width: 90%" id="table-invoice">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px; line-height: 0.9; padding: 5px; font-size: 1.3rem;">No</th>
                                                        <th><?= Yii::t('app', 'Uraian'); ?></th>
                                                        <th style="width: 100px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Qty (m<sup>3</sup>)'); ?></th>
                                                        <th style="width: 200px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Harga/m<sup>3</sup>'); ?></th>
                                                        <th style="width: 200px; line-height: 0.9; padding: 5px; font-size: 1.3rem;"><?= Yii::t('app', 'Total (Rp)'); ?></th>
                                                        <th style="width: 30px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td style="display: flex; justify-content: space-between;">
                                                            <span><a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="addItem();" style="margin-top: 10px;"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Item'); ?></a></span>
															<span style="text-align: right; margin-top: 10px;">Total &nbsp; </span>
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_kubikasi_invoice',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
                                                        <td style="vertical-align: middle; text-align: right;">
                                                            Total Harga &nbsp; 
                                                        </td>
                                                        <td style="vertical-align: middle; text-align: right;">
                                                            <?php echo yii\bootstrap\Html::textInput('all_total_inv',0,['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
                                                    </tr>
                                                    <tr id="tr-potongan">
                                                        <td colspan="3"></td>
                                                        <td style="vertical-align: middle; text-align: right;">
                                                            <?php 
                                                            if(!isset($_GET['invoice_lokal_id'])){
                                                                echo yii\bootstrap\Html::activeTextarea($model, 'label_potongan',['class' => 'form-control', 'rows' => 1,'placeholder' => 'Isikan keterangan potongan harga','style' => 'margin-top:5px; text-align:left; font-weight: normal; font-size: 1.1rem;']); 
                                                            } else {
                                                                if(isset($_GET['edit'])){
                                                                    echo yii\bootstrap\Html::activeTextarea($model, 'label_potongan',['class' => 'form-control', 'rows' => 1,'placeholder' => 'Isikan keterangan potongan harga','style' => 'margin-top:5px; text-align:left; font-weight: normal; font-size: 1.1rem;', 'value'=>$model->label_potongan]);
                                                                } else {
                                                                    if(!empty($model->label_potongan)){
                                                                        echo yii\bootstrap\Html::activeTextarea($model,'label_potongan', ['class' => 'form-control','rows' => 1,'style' => 'margin-top:5px; text-align:left; font-weight: normal; font-size: 1.1rem;', 'value'=>$model->label_potongan]);
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo yii\bootstrap\Html::activeTextInput($model, 'total_potongan',['class'=>'form-control float','style'=>'font-size:1.2rem; padding:5px;', 'onchange'=>'totalInvoice();']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <td style="vertical-align: middle; text-align: right;">PPN</td>
                                                        <td><?php echo yii\bootstrap\Html::activeTextInput($modDetail, 'ppn',['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <td style="vertical-align: middle; text-align: right;">PPN Tidak Dipungut (Kawasan Berikat)</td>
                                                        <td><?php echo yii\bootstrap\Html::activeTextInput($modDetail, 'ppn_berikat',['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <td style="vertical-align: middle; text-align: right;">
                                                            <span id='place-ppn-pph-log'>PPh Pasal 22</span>
                                                            <span id='place-ppn-pph'>PPh Pasal 23</span>
                                                        </td>
                                                        <td><?php echo yii\bootstrap\Html::activeTextInput($modDetail, 'pph',['class'=>'form-control float','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?></td>
                                                    </tr>
                                                    <tr>
														<td colspan="3"></td>
														<td style="vertical-align: middle; text-align: right;" class="font-red-mint">
															Grand Total &nbsp; 
														</td>
														<td style="vertical-align: middle; text-align: right;">
															<?= yii\bootstrap\Html::textInput('total_bayar',0,['class'=>'form-control float font-red-mint','disabled'=>'disabled','style'=>'font-size:1.2rem; padding:5px;']); ?>
														</td>
													</tr>
                                                </tfoot>
                                            </table>
										</div>
                                    </div>
                                </div><br><br>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
								<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print Invoice 1'),['id'=>'btn-print-1','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printInvoice1('.(isset($_GET['invoice_lokal_id'])?$_GET['invoice_lokal_id']:'').');','disabled'=>true]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Print Invoice 2'),['id'=>'btn-print-2','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printInvoice2('.(isset($_GET['invoice_lokal_id'])?$_GET['invoice_lokal_id']:'').');','disabled'=>true]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
$pagemode = "";
if(isset($_GET['invoice_lokal_id'])){
    $pagemode = "afterSave(".$_GET['invoice_lokal_id'].");";
}else{
	$pagemode = "resetTableDetail(); setDisplay(); setDropdownCustomer(); addItem();";
}
?>
<?php $this->registerJs(" 
    $pagemode
	formconfig();
    $('select[name*=\"[cust_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Customer',
	});
    $('select[name*=\"[bank_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Nama Bank',
	});
    // $('#". yii\bootstrap\Html::getInputId($model, 'cust_no_npwp') ."').inputmask({'mask': '99.999.999.9-999.9999'});
    // $('#". yii\bootstrap\Html::getInputId($model, 'no_faktur_pajak') ."').inputmask({'mask': '999.999-99.999999999'});
", yii\web\View::POS_READY); ?>
<script>
function setCustomer(){
    var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var cust_id = $('#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>').val(); 
    var id = <?= isset($_GET['invoice_lokal_id'])?$_GET['invoice_lokal_id']:0 ?>;
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/setCustomer']); ?>',
        type   : 'POST',
        data   : {cust_id:cust_id,jenis_produk:jenis_produk, id:id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_alamat") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_no_npwp") ?>").val('');
            $("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").empty();
			resetTableDetail();
            total();
			if(data.cust_id){
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_alamat") ?>").val(data.cust_an_alamat);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_no_npwp") ?>").val(data.cust_no_npwp);
			}

            if(data.po_id){
                $("#<?= yii\bootstrap\Html::getInputId($model, "po_ko_id") ?>").val(data.po_id);
            }
            
            setDDNotaOp(cust_id, jenis_produk); 
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

/** function setCustomer(){
    var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
	var cust_id = $('#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>').val(); // po_ko_id - cuts_id
    var id = <?= isset($_GET['invoice_lokal_id'])?$_GET['invoice_lokal_id']:0 ?>;
	$.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/setCustomer']); ?>',
        type   : 'POST',
        data   : {cust_id:cust_id,jenis_produk:jenis_produk, id:id},
        success: function (data) {
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_alamat") ?>").val('');
			$("#<?= yii\bootstrap\Html::getInputId($model, "cust_no_npwp") ?>").val('');
            $("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").empty();
			resetTableDetail();
            total();
			if(data.cust_id){
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_alamat") ?>").val(data.cust_an_alamat);
				$("#<?= yii\bootstrap\Html::getInputId($model, "cust_no_npwp") ?>").val(data.cust_no_npwp);
			}

            if(data.po_id){
                $("#<?= yii\bootstrap\Html::getInputId($model, "po_ko_id") ?>").val(data.po_id);
            }

            // pisah dan ambil cust_idnya
            var part = cust_id.split("-"); 
            cust_id = part[1]; 
            setDDNotaOp(cust_id, jenis_produk); 
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
} **/

function resetTableDetail(){
	$('#table-detail tbody').html('');
}

function addItem(){
	// var op_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>').val();
    // if(op_ko_id){
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/addItem']); ?>',
            type   : 'POST',
            data   : {},
            success: function (data) {
                if(data.item){
                    $(data.item).hide().appendTo('#table-invoice tbody').fadeIn(200,function(){
                        $(this).find(".tooltips").tooltip({ delay: 50 });
                        reordertable('#table-invoice');
                    });
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    // }else{
    //     cisAlert("OP harus diisi dulu");
    // }
    totalInvoice();
    
}

function setOpKo(){
    var op_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>').val();
    var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
    // $("#<?= \yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val("");
    $("#table-detail > tbody").html("");
    resetTableDetail();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/setOpKo']); ?>',
        type   : 'POST',
        data   : {op_ko_id:op_ko_id, jenis_produk:jenis_produk},
        success: function (data) {
            // if(data){
            //     $("#<?php //echo \yii\helpers\Html::getInputId($model, "jenis_produk") ?>").val(data.jenis_produk);
            // }
            if(data.detail){
                $("#table-detail > tbody").html(data.detail);
                reordertable('#table-detail');
            }
            total();
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function total(){
    var pcs = 0; var m3 = 0; var total = 0; var dpp = 0; var harga_nota =0; var harga_inv=0; var subtotal=0; var total_m3=0;
    var ppn_berikat = 0;
    var pph = unformatNumber( $("input[name*='total_pph']").val() );
    
    $("#table-detail > tbody > tr").each(function(i,v){
        m3 = unformatNumber( $(this).find("input[name*='[kubikasi]']").val() );
        harga_nota = unformatNumber( $(this).find("input[name*='[harga_nota]']").val() );
        if($("#<?= \yii\helpers\Html::getInputId($model, "include_ppn") ?>").is(":checked")){
            harga_inv = Math.ceil( harga_nota / 1.11 ); // sebelumnya 1.1
        }else{
            harga_inv = Math.round( harga_nota );
        }
        subtotal = Math.round( harga_inv * m3 );
        
        $(this).find("input[name*='[harga_invoice]']").val( formatNumberForUser(harga_inv) );
        $(this).find("input[name*='[subtotal]']").val( formatNumberForUser(subtotal) );
        
        pcs += unformatNumber( $(this).find("input[name*='[qty_kecil]']").val() );
        dpp += subtotal;
        total_m3 += unformatNumber( $(this).find("input[name*='[kubikasi]']").val() );
    });
    
    setTimeout(function(){
        ppn = Math.round( dpp * 0.1 );
        bayar = dpp + ppn - pph;
        $("input[name*='total_pcs']").val( pcs );
        $("input[name*='total_m3']").val( (Math.round( total_m3 * 10000 ) / 10000 ).toString() );
        $("input[name*='total_harga']").val( formatNumberForUser(dpp) );
        // $("input[name*='total_ppn']").val( formatNumberForUser(ppn) );
        // $("input[name*='total_bayar']").val( formatNumberForUser(bayar) );
    },500);
}

function save(){
    var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
    var $form = $('#form-transaksi');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
            if(jenis_produk == "Log"){
                cisAlert('Pilih kode nota terlebih dahulu');
            } else {
                cisAlert('Pilih kode OP terlebih dahulu');
            }
			
            return false;
        }
        if(validatingDetail()){
            submitform($form);
        }
    }
    return false;
}

function validatingDetail(){
    var has_error = 0;
    var field2 = $("#<?= yii\bootstrap\Html::getInputId($model, "kode1") ?>");
    if( (!field2.val()) ){ //|| (!field3.val()) || (!field4.val()) 
        $(field2).parents('.form-group').addClass('error-tb-detail');
		has_error = has_error + 1;
    }else{
        if( field2.val() == "000" ){
            has_error = has_error + 1;
            $(field2).parents('.form-group').addClass('error-tb-detail');
        }else{
            $(field2).parents('.form-group').removeClass('error-tb-detail');
        }
    }

    var potongan = $("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>");
    if( unformatNumber(potongan.val()) !== 0){
        var label = $("#<?= yii\bootstrap\Html::getInputId($model, "label_potongan") ?>");
        if(!label.val()){
            has_error = has_error + 1;
            $(label).addClass('error-tb-detail');
        } else {
            $(label).removeClass('error-tb-detail');
        }
    }

    $('#table-invoice tbody > tr').each(function(){
        var field1 = $(this).find('textarea[name*="[uraian]"]');
        var field7 = $(this).find('input[name*="[total_inv]"]');
        if(!field1.val()){
            $(this).find('textarea[name*="[uraian]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('textarea[name*="[uraian]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field7 || field7.val() == 0){
            $(this).find('input[name*="[total_inv]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[total_inv]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    
	<?php if(isset($_GET['edit'])){ ?>
        var potongan = $("#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>");
        if( unformatNumber(potongan.val()) !== 0){
            var label = $("#<?= yii\bootstrap\Html::getInputId($model, "label_potongan") ?>");
            if(!label.val()){
                has_error = has_error + 1;
                $(label).addClass('error-tb-detail');
            } else {
                $(label).removeClass('error-tb-detail');
            }
        }
		// has_error = 0;
	<?php } ?>
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
	$('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print-1').removeAttr('disabled');
    $('#btn-print-2').removeAttr('disabled');
    setDropdownCustomer(id);
    setTimeout(function(){
        $("#<?= yii\bootstrap\Html::getInputId($model, "op_ko_id") ?>").empty().append('<option value="<?= $model->op_ko_id ?>"><?= (isset($model->op_ko_id)?$model->opKo->kode." - ".app\components\DeltaFormatter::formatDateTimeForUser2($model->opKo->tanggal):"") ?></option>').val('<?= (isset($model->op_ko_id)?$model->op_ko_id:"") ?>').trigger('change');
        <?php if(isset($_GET['edit'])){ ?>
            $("#<?= yii\helpers\Html::getInputId($model, "kode1") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "kode4") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "kode5") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "tanggal") ?>").prop("disabled",false);
            $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', false);
            $("#<?= yii\helpers\Html::getInputId($model, "cust_id") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "cust_no_npwp") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "no_faktur_pajak") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "op_ko_id") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "penerbit") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "cara_bayar") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "mata_uang") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "include_ppn") ?>").prop("disabled",false);
            $("input[name='TInvoiceLokal[include_ppn]'][type='hidden']").prop("disabled", false);
            $("#<?= yii\helpers\Html::getInputId($model, "bank_id") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "kawasan_berikat") ?>").prop("disabled",false);
            $("input[name='TInvoiceLokal[kawasan_berikat]'][type='hidden']").prop("disabled", false);
            $("#<?= yii\helpers\Html::getInputId($model, "ceklis_pph") ?>").prop("disabled",false);
            $("input[name='TInvoiceLokal[ceklis_pph]'][type='hidden']").prop("disabled", false);
            $("#<?= yii\helpers\Html::getInputId($model, "label_potongan") ?>").prop("disabled",false);
            $("#<?= yii\helpers\Html::getInputId($model, "total_potongan") ?>").prop("disabled",false);

            $('#btn-save').prop('disabled',false);
            $('#btn-print-1').prop('disabled',true);
            $('#btn-print-2').prop('disabled',true);
            getItems(id,1);
        <?php }else{ ?>
            getItems(id);
        <?php } ?>
    },500);
    <?php if((!isset($_GET['edit'])) && (empty($model->label_potongan)) && ($model->total_potongan <= 0)){ ?>
        $('#tr-potongan').css('display', 'none');
    <?php } ?>
}

function getItems(invoice_lokal_id,edit=null){
    var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
    if(jenis_produk == "Log"){
        $("#place-nota").show();
        $("#place-op").hide();
        $("#place-ppn-pph-log").show();
        $("#place-ppn-pph").hide();
        $("#place-title-log").show();
        $("#place-title").hide();
    } else {
        $("#place-nota").hide();
        $("#place-op").show();
        $("#place-ppn-pph-log").hide();
        $("#place-ppn-pph").show();
        $("#place-title-log").hide();
        $("#place-title").show();
    }
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/getItems']); ?>',
		type   : 'POST',
		data   : {invoice_lokal_id:invoice_lokal_id,edit:edit},
		success: function (data) {
			if(data.html){
				$('#table-detail tbody').html(data.html);
				reordertable('#table-detail');
                $("#table-detail > tbody > tr").each(function(){
                    if(edit){
                        $(this).prop("disabled",false);
                    }else{
                        $('#btn-add-item').hide();
                        $(this).prop("disabled",true);
                    }
                });
			}
            if(data.html2){
				$('#table-invoice tbody').html(data.html2);
				reordertable('#table-invoice');
                $("#table-invoice > tbody > tr").each(function(){
                    if(!edit){
                        $('#btn-add-item').hide();
                        $(this).find("textarea[name*='uraian']").prop("disabled",true);
                        $(this).find("input[name*='kubikasi_inv']").prop("disabled",true);
                        $(this).find("input[name*='harga_inv']").prop("disabled",true);
                        $(this).find("input[name*='total_inv']").prop("disabled",true);
                        $(this).find("a.btn.btn-xs.red").removeAttr('onclick').removeClass('red').addClass('grey');
                    }
                });
			}
            console.log(data.html);
			setTimeout(function(){
				total();
                totalInvoice();
			},500);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function printInvoice1(id){
	window.open("<?= yii\helpers\Url::toRoute('/finance/invoicelokal/printInvoice1') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function setDisplay(){
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
    $("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val('');
    $("#<?= yii\bootstrap\Html::getInputId($model, "cust_an_alamat") ?>").val('');
	$("#<?= yii\bootstrap\Html::getInputId($model, "cust_no_npwp") ?>").val('');
    resetTableDetail();
    total();
    // console.log($('#<?= yii\bootstrap\Html::getInputId($model, "nota_penjualan") ?>').val());
    if(jenis_produk == 'Log'){
        // $('#<?= yii\bootstrap\Html::getInputId($model, "kode3") ?>').val('LG');
        $('#<?= yii\bootstrap\Html::getInputId($model, "kode3") ?>').val('LOG');
    } else if(jenis_produk == 'JasaGesek' || jenis_produk == 'JasaKD' || jenis_produk == 'JasaMoulding'){
        $('#<?= yii\bootstrap\Html::getInputId($model, "kode3") ?>').val('JASA');
    } else {
        $('#<?= yii\bootstrap\Html::getInputId($model, "kode3") ?>').val(jenis_produk); 
    }
    if(jenis_produk == "Log"){
        $("#place-nota").show();
        $("#place-op").hide();
        $("#place-ppn-pph-log").show();
        $("#place-ppn-pph").hide();
        $("#place-title-log").show();
        $("#place-title").hide();
    } else {
        $("#place-nota").hide();
        $("#place-op").show();
        $("#place-ppn-pph-log").hide();
        $("#place-ppn-pph").show();
        $("#place-title-log").hide();
        $("#place-title").show();
    } 
}

function setDropdownCustomer(id =null, edit = null){
	var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
    var cust_id = $('#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>').val();
    $('#<?= yii\bootstrap\Html::getInputId($model, 'nota_penjualan') ?>').select2();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/setDropdownCustomer']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk, edit:edit, id:id},
		success: function (data) {
            $('#<?= yii\bootstrap\Html::getInputId($model, 'op_ko_id') ?>').empty();
            $('#<?= yii\bootstrap\Html::getInputId($model, 'nota_penjualan') ?>').empty();
            $('#<?= yii\bootstrap\Html::getInputId($model, 'nota_penjualan') ?>').select2('destroy');
            $("#<?= yii\bootstrap\Html::getInputId($model, 'cust_id') ?>").html(data.html);
			$('#<?= yii\bootstrap\Html::getInputId($model, 'cust_id') ?>').siblings('.select2').removeClass('animation-loading');
            if(id){
                $("#<?= yii\bootstrap\Html::getInputId($model, "cust_id") ?>").val( data.cust_id ).change();
                $("#<?= yii\bootstrap\Html::getInputId($model, "po_ko_id") ?>").val( data.po_ko_id );
            }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setDDNotaOp(cust_id, jenis_produk){
    var id = <?= isset($_GET['invoice_lokal_id'])?$_GET['invoice_lokal_id']:'null'; ?>;
    var po_ko_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'po_ko_id') ?>').val();
    
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/setDDNotaOp']); ?>',
        type   : 'POST',
        data   : { cust_id:cust_id, jenis_produk: jenis_produk, id:id,po_ko_id:po_ko_id},
        success: function (data){
            if(data.html){
                if(jenis_produk == "Log"){
                    $("#<?= \yii\helpers\Html::getInputId($model, "nota_penjualan") ?>").html(data.html);
                    $('select[name*=\"[nota_penjualan]\"]').select2({
                        placeholder: 'Ketik Kode Nota',
                    });
                    
                    if(data.nota_penjualan){
                        var nota_penjualan = data.nota_penjualan.join(',');
                        var nota_split = nota_penjualan.split(',');
                        // console.log(nota_split);
                        setTimeout(function(){
                            $("#<?= \yii\helpers\Html::getInputId($model, "nota_penjualan") ?>").val( nota_split ).change();
                            setTimeout(function(){
                                <?php if(!isset($_GET['edit']) && isset($_GET['invoice_lokal_id'])){ ?>
                                    $("#<?= \yii\helpers\Html::getInputId($model, "nota_penjualan") ?>").prop("disabled",true);
                                <?php } else { ?>
                                    $("#<?= \yii\helpers\Html::getInputId($model, "nota_penjualan") ?>").prop("disabled",false);
                                <?php } ?>
                            },500);
                        },500);
                    }
                    // console.log(data.nota_penjualan);

                    // if(data.op_ko){
                    //     if(data.op_ko.terima_logalam_id){
                    //         $('#<?= yii\bootstrap\Html::getInputId($model, "kode3") ?>').val('LP');
                    //     }
                    // } 
                } else {
                    $("#<?= \yii\helpers\Html::getInputId($model, "op_ko_id") ?>").html(data.html);
                    $('select[name*=\"[op_ko_id]\"]').select2({
                        placeholder: 'Ketik Kode OP',
                    });
                    if(data.op_ko_id){
                        setTimeout(function(){
                            $("#<?= \yii\helpers\Html::getInputId($model, "op_ko_id") ?>").val( data.op_ko_id ).change();
                            setTimeout(function(){
                                <?php if(!isset($_GET['edit'])){ ?>
                                    $("#<?= \yii\helpers\Html::getInputId($model, "op_ko_id") ?>").prop("disabled",true);
                                <?php } else { ?>
                                    $("#<?= \yii\helpers\Html::getInputId($model, "op_ko_id") ?>").prop("disabled",false);
                                <?php } ?>
                            },500);
                        },500);
                    }
                }
            }
            
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setNota(){
    var nota_penjualan_id = $('#<?= yii\bootstrap\Html::getInputId($model, "nota_penjualan") ?>').val();
    var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
    $("#table-detail > tbody").html("");
    resetTableDetail();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/finance/invoicelokal/setNota']); ?>',
        type   : 'POST',
        data   : {nota_penjualan_id:nota_penjualan_id, jenis_produk:jenis_produk},
        success: function (data) {
            if(data.detail){
                $("#table-detail > tbody").html(data.detail);
                reordertable('#table-detail');
            }
            total();
        }, 
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function totalInvoice(){
    var jenis_produk = $('#<?= yii\bootstrap\Html::getInputId($model, "jenis_produk") ?>').val();
    var m3 = 0; var total_harga_inv = 0; var harga_invoice =0; var subtotal=0; var total_m3=0; var bayar = 0; var pph = 0; var potongan = 0;

    $("#table-invoice > tbody > tr").each(function(i,v){
        var field1 = $(this).find("input[name*='[kubikasi_inv]']").val();
        var field2 = $(this).find("input[name*='[harga_inv]']").val();
        if(field1 == 0 && field2 == 0){
            subtotal = unformatNumber($(this).find("input[name*='[total_inv]']").val());
        } else {
            m3 = unformatNumber( $(this).find("input[name*='[kubikasi_inv]']").val() );
            harga_invoice = unformatNumber( $(this).find("input[name*='[harga_inv]']").val() );
            subtotal = Math.round( harga_invoice * m3 );
            
            $(this).find("input[name*='[harga_inv]']").val( formatNumberForUser(field2) );
            $(this).find("input[name*='[total_inv]']").val( formatNumberForUser(subtotal) );
            total_m3 += unformatNumber( $(this).find("input[name*='[kubikasi_inv]']").val() );
        }
        total_harga_inv += subtotal;
    });
    
    setTimeout(function(){
        var potongan = unformatNumber($('#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>').val());
        // total_harga_inv -= potongan;
        total_ppn = Math.round( (total_harga_inv - potongan) * <?= Params::DEFAULT_PPN?> );
        if(jenis_produk == "Log"){
            pph = Math.round( total_harga_inv * 0.25 / 100);
        } else {
            pph = Math.round( total_harga_inv * 2 / 100);
        }

        // utk kawasan berikat = -ppn
        if($("#<?= \yii\helpers\Html::getInputId($model, "kawasan_berikat") ?>").is(":checked")){
            $("input[name*='ppn_berikat']").val( '-' + formatNumberForUser(total_ppn) );
        } else {
            $("input[name*='ppn_berikat']").val( 0 );
        }
        // jk pph ada
        if($("#<?= \yii\helpers\Html::getInputId($model, "ceklis_pph") ?>").is(":checked")){
            $('#<?= yii\bootstrap\Html::getInputId($modDetail, "pph") ?>').val( '-' +formatNumberForUser(pph) );
        } else {
            $('#<?= yii\bootstrap\Html::getInputId($modDetail, "pph") ?>').val( 0 );
        }

        if($("#<?= \yii\helpers\Html::getInputId($model, "kawasan_berikat") ?>").is(":checked")){
            if($("#<?= \yii\helpers\Html::getInputId($model, "ceklis_pph") ?>").is(":checked")){
                bayar = (total_harga_inv - potongan) - pph;
            } else {
                bayar = (total_harga_inv - potongan);
            }
        } else {
            if($("#<?= \yii\helpers\Html::getInputId($model, "ceklis_pph") ?>").is(":checked")){
                bayar = (total_harga_inv - potongan) + total_ppn - pph;
            } else {
                bayar = (total_harga_inv - potongan) + total_ppn;
            }
        }

        $("input[name*='total_kubikasi_invoice']").val( (Math.round( total_m3 * 10000 ) / 10000 ).toString() );
        $("input[name*='all_total_inv']").val( formatNumberForUser(total_harga_inv) );
        $('#<?= yii\bootstrap\Html::getInputId($modDetail, "ppn") ?>').val(formatNumberForUser(total_ppn));
        // $("input[name*='ppn']").val( formatNumberForUser(total_ppn) );
        // $("input[name*='pph']").val( formatNumberForUser(pph) );
        
        $("input[name*='total_bayar']").val( formatNumberForUser(bayar) );
        $('#<?= yii\bootstrap\Html::getInputId($model, "total_potongan") ?>').val(formatNumberForUser(potongan));
    },500);
}

function printInvoice2(id){
	window.open("<?= yii\helpers\Url::toRoute('/finance/invoicelokal/printInvoice2') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}

function setNomor(){
    var tanggal = $('#<?= yii\bootstrap\Html::getInputId($model, "tanggal") ?>').val();
    var tgl = tanggal.split("/");
    var bulan = tgl[1];
    var tahun = tgl[2];

    const romawi = [
        "",       // index 0 (tidak digunakan)
        "I", "II", "III", "IV", "V", "VI",
        "VII", "VIII", "IX", "X", "XI", "XII"
    ];

    let bulanRomawi = romawi[parseInt(bulan, 10)];
    $('#<?= yii\bootstrap\Html::getInputId($model, "kode4") ?>').val(bulanRomawi);
    $('#<?= yii\bootstrap\Html::getInputId($model, "kode5") ?>').val(tahun);
}
</script>