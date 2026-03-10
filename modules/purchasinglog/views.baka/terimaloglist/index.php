<?php
/* @var $this yii\web\View */
$this->title = 'Loglist';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\RepeaterAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Loglist'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->

<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<div class="row" style="margin-top: -10px; margin-bottom: 10px;">
				<div class="col-md-12">
					<a class="btn blue btn-sm btn-outline pull-right" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Loglist'); ?></a>
				</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Loglist'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <?php $form = \yii\bootstrap\ActiveForm::begin([
                                        'id' => 'form-transaksi',
                                        'fieldConfig' => [
                                            'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                                            'labelOptions'=>['class'=>'col-md-4 control-label'],
                                        ],
                                    ]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
                                    <div class="col-md-6">
										<?= yii\helpers\Html::activeHiddenInput($model, "loglist_id"); ?>
										<?php
										if(!isset($_GET['loglist_id'])){
											echo $form->field($model, 'loglist_kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
										}else{ ?>
											<div class="form-group">
												<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Log List'); ?></label>
												<div class="col-md-8" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= \yii\bootstrap\Html::activeTextInput($model, 'loglist_kode', ['class'=>'form-control','style'=>'width:100%', 'readonly'=>true]) ?>
													</span>
													<span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->loglist_kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
												</div>
											</div>
										<?php } ?>
                                        <?= $form->field($model, 'tanggal',[
                                            'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker">{input} <span class="input-group-btn">
                                         <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                         {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
										<?= $form->field($model, 'tongkang')->textInput(); ?>
										
                                        <?php if(isset($_GET['edit'])){ ?>
                                            <div class="form-group" style="margin-top: 10px;">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Grader'); ?></label>
                                                <div class="col-md-8">
                                                    <div class="repeater">
                                                        <div data-repeater-list="TLoglist">
                                                            <?php
                                                            if(count($modDkg)>0){
                                                                foreach($modDkg as $i => $dkg){ 
                                                                    $model->grader_id = $dkg->dkg_id;
                                                                ?>
                                                                    <div data-repeater-item style="display: block;">
                                                                        <span class="input-group-btn" style="width: 60%">
                                                                            <?php echo \yii\bootstrap\Html::activeDropDownList($model, 'grader_id', \app\models\TDkg::getOptionListGraderEdit($dkg->dkg_id),['class'=>'form-control','prompt'=>'']) ?>
                                                                        </span>
                                                                        <span class="input-group-btn" style="width: 20%" id="remove-btn">
                                                                            <a href="javascript:;" data-repeater-delete class="btn btn-danger"><i class="fa fa-close"></i></a>
                                                                        </span>
                                                                    </div>
                                                                <?php } ?>
                                                                <input type="hidden" id="total-item-repeater" value="<?= count(\app\models\TDkg::getOptionListGrader()); ?>">
                                                            <?php } ?>
                                                        </div>
                                                        <a href="javascript:;" data-repeater-create class="btn btn-xs btn-info mt-repeater-add" style="margin-top: 5px;">
															<i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Grader'); ?>
														</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }else{ ?>
                                            <div class="form-group" style="">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Grader Terlibat'); ?></label>
                                                <?php if(isset($_GET['loglist_id'])){ ?>
                                                    <div class="col-md-8" style="margin-top: 10px;">
                                                        <?php if(count($modDkg)>0){ ?>
                                                        <?php foreach($modDkg as $i => $dkg){ ?>
                                                        <strong><?= ($i+1).". ".$dkg->graderlog->graderlog_nm." (".$dkg->kode.")"; ?></strong><br>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                <?php }else{ ?>
                                                    <div class="col-md-8">
                                                        <div class="repeater">
                                                            <div data-repeater-list="<?= \yii\helpers\StringHelper::basename(get_class($model));  ?>">
                                                                <div data-repeater-item style="display: block;">
                                                                    <span class="input-group-btn" style="width: 60%">
                                                                        <?php echo \yii\bootstrap\Html::activeDropDownList($model, 'grader_id', \app\models\TDkg::getOptionListGrader(),['class'=>'form-control','prompt'=>'']) ?>
                                                                    </span>
                                                                    <span class="input-group-btn" style="width: 20%" id="remove-btn">
                                                                        <a href="javascript:;" data-repeater-delete class="btn btn-danger"><i class="fa fa-close"></i></a>
                                                                    </span>
                                                                </div>
                                                                <input type="hidden" id="total-item-repeater" value="<?= count(\app\models\TDkg::getOptionListGrader()); ?>">
                                                            </div>
                                                            <a href="javascript:;" data-repeater-create class="btn btn-xs btn-info mt-repeater-add" style="margin-top: 5px;">
                                                                <i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah Grader'); ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6">
										<?= yii\helpers\Html::activeHiddenInput($model, "log_kontrak_id"); ?>
										<div class="form-group" style="margin-bottom: 5px;">
											<label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Keputusan'); ?></label>
											<div class="col-md-8">
												<span class="input-group-btn" style="width: 100%">
													<?= \yii\bootstrap\Html::activeDropDownList($model, 'pengajuan_pembelianlog_id', \app\models\TPengajuanPembelianlog::getOptionListLoglist(),['class'=>'form-control select2','prompt'=>'','onchange'=>'setKeputusan()','style'=>'width:100%;']); ?>
												</span>
												<span class="input-group-btn" style="width: 20%">
													<a class="btn btn-sm grey btn-outline" id="btn-detailkeputusan" onclick="detailKeputusan();" data-original-title="Daftar OP" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-info-circle"></i></a>
												</span>
											</div>
										</div>
										<?= $form->field($model, 'kode_po')->textInput(['disabled'=>true])->label("Kode PO"); ?>
										<?= $form->field($model, 'nomor_kontrak')->textInput(['disabled'=>true]); ?>
										<?= $form->field($model, 'kode_bajg')->textInput(); ?>
										<?= $form->field($model, 'lokasi_muat')->textInput(); ?>
                                        <?= $form->field($model, 'model_ukuran_loglist',['wrapperOptions' => ['style' => 'display:inline-block']])->inline(true)->radioList(['2 Diameter','4 Diameter'],['onchange'=>'setDiameter()'])->label('Model Ukuran Loglist'); ?>
                                        <?= $form->field($model, 'area_pembelian',['wrapperOptions' => ['style' => 'display:inline-block']])->inline(true)->radioList(['Jawa','Luar Jawa'],['onchange'=>'setDiameter()'])->label('Area Pembelian'); ?>
                                    </div>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                                </div>
                                <div class="form-actions pull-right">
                                    <div class="col-md-12 right">
                                        <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                        <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
                                    </div>
                                </div>
                                <br><br>
                                <?php
                                //echo isset($_GET['edit'])." ".isset($_GET['loglist_id']);
                                //print_r($_GET);
                                if (empty($_GET['edit']) && empty($_GET['loglist_id'])) {
                                
                                } else {
                                ?>
                                <hr>
                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-md-3">
                                        <h4><?= Yii::t('app', 'Detail Terima Loglist'); ?></h4>
                                    </div>
                                    <div class="col-md-7 td-kecil">
                                        <?php
                                        // 2021-03-19 daftar lampiran
                                        $sql_lampiran = "select distinct(lampiran) as lampiran from t_loglist_detail where loglist_id = ".$model->loglist_id."";
                                        $query_lampiran = Yii::$app->db->createCommand($sql_lampiran)->queryAll();
                                        if (!empty($query_lampiran)) {
                                            foreach ($query_lampiran as $kolom) {
                                                $lampiran = $kolom['lampiran'];
                                            ?>
                                            <a class="btn btn-xs blue-hoki" id="btn-add-item" onclick="lihatLampiran('<?php echo $_GET['loglist_id'];?>','<?php echo $lampiran;?>');" style="margin-top: 10px;"><i class="fa fa-paste"></i> <?php echo $lampiran;?></a>
                                            <?php
                                            }
                                        } else {
                                            $lampiran = 0;
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php
                                        // 2021-03-19 nomor lampiran
                                        if ($lampiran > 0) {
                                        ?>
                                        <div><button type="button" id="btn-tambah-lampiran" class="btn btn-primary btn-outline ciptana-spin-btn ladda-button" onclick="tambahLampiran(<?php echo $_GET['loglist_id'];?>,<?php echo $lampiran;?>,1);" data-style="zoom-in"><span class="ladda-label">Tambah Lampiran</span><span class="ladda-spinner"></span></button></div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
										<span class="spb-info-place pull-right"></span>
                                        <div class="table-scrollable">
                                            <?php 
                                            $form = \yii\bootstrap\ActiveForm::begin([
                                                'id' => 'form-loglist-detail',
                                                'validateOnChange' => false,
                                                'fieldConfig' => [
                                                    'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                                                    'labelOptions'=>['class'=>'col-md-5 control-label'],
                                                ],
                                            ]);
                                            ?>
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4"><?= Yii::t('app', 'Nomor'); ?></th>
                                                        <th rowspan="2" style="font-size: 1.1rem;"><?= Yii::t('app', 'Kayu'); ?></th>
                                                        <th rowspan="2" style="width: 50px;"><?= Yii::t('app', 'Pjg<sup>m</sup>'); ?></th>
                                                        <th colspan="3" id="diameter-th"><?= Yii::t('app', 'Diameter'); ?></th>
                                                        <th colspan="3"><?= Yii::t('app', 'Unsur Cacat'); ?></th>
                                                        <th colspan="2"><?= Yii::t('app', 'Volume'); ?></th>
                                                        <th rowspan="2" style="width: 30px; font-size: 0.9rem;"><?= Yii::t('app', 'Fresh<br>Cut'); ?></th>
                                                        <th rowspan="2" style="width: 60px; background-color: darkorange;"><div id="lampiran"></div></th>
                                                    </tr>
													<tr>
                                                        <th style="width: 30px; font-size: 1.1rem;">No</th>
                                                        <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Grade'); ?></th>
														<th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Produksi'); ?></th>
                                                        <th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Batang'); ?></th>
                                                        <th class="diameter2" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D1<sup>cm</sup>'); ?></th>
                                                        <th class="diameter2" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D2<sup>cm</sup>'); ?></th>
                                                        <th class="diameter4 hidden" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D1<sup>cm</sup>'); ?></th>
                                                        <th class="diameter4 hidden" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D2<sup>cm</sup>'); ?></th>
                                                        <th class="diameter4 hidden" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D3<sup>cm</sup>'); ?></th>
                                                        <th class="diameter4 hidden" style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'D4<sup>cm</sup>'); ?></th>
                                                        <th style="width: 53px; font-size: 1.1rem;"><?= Yii::t('app', 'Rata2'); ?><sup>cm</sup></th>
														<th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'Panjang'); ?><sup>cm</sup></th>
														<th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'GB'); ?></th>
														<th style="width: 50px; font-size: 1.1rem;"><?= Yii::t('app', 'GR'); ?></th>
														<th style="width: 70px; font-size: 1.1rem;"><?= Yii::t('app', 'Range'); ?></th>
														<th style="width: 50px; font-size: 1.1rem;">m<sup>3</sup></th>
													</tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    /*if (isset($_REQUEST['success']) == 2 && isset($_GET['loglist_id'])) {
                                                        $_REQUEST['success'] = 0;
                                                    }*/
                                                    ?>
                                                    <div style="position: absolute; bottom: -25px; right: -0px">
                                                        <button type="button" id="btn-save-details" class="btn hijau btn-outline ciptana-spin-btn ladda-button" onclick="saveItem();" data-style="zoom-in"><span class="ladda-label">Save</span><span class="ladda-spinner"></span></button>
                                                    </div>
                                                </tbody>
												<tfoot>
												</tfoot>
                                            </table>
                                            <?php \yii\bootstrap\ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="row" style="margin-top: 50px;">
                                    <table class="table table-striped table-bordered table-advance table-hover" id="table-rekap">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="width: 30px;">No.</th>
                                                <th rowspan="2" class="text-center" style="width: 150px;">Jenis</th>
                                                <th colspan="2" class="text-center">25-29</th>
                                                <th colspan="2" class="text-center">30-39</th>
                                                <th colspan="2" class="text-center">40-49</th>
                                                <th colspan="2" class="text-center">50-59</th>
                                                <th colspan="2" class="text-center">60-69</th>
                                                <th colspan="2" class="text-center">70 up</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" style="width: 53px;">Batang</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                                <th class="text-center" style="width: 53px;">Batang</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                                <th class="text-center" style="width: 53px;">Batang</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                                <th class="text-center" style="width: 53px;">Batang</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                                <th class="text-center" style="width: 53px;">Batang</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                                <th class="text-center" style="width: 53px;">Batang</th>
                                                <th class="text-center" style="width: 53px;">Vol <font style="font-size: 1rem;">m<sup>3</sup></font</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php
if(isset($_GET['loglist_id'])){
    $pagemode = "afterSave(); setFieldTombolTOP()";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode;
	$(this).find('select[name*=\"[pengajuan_pembelianlog_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik Kode Keputusan',
		width: null
	});
	$('.repeater').repeater({
        show: function () {
            if(RepeaterSetItemRequired()){
                RepeaterSetDropdown();
                $(this).slideDown();
                setTimeout(function(){
                    RepeaterSetFieldTombol();
                }, 500);
            }
            $('div[data-repeater-item][style=\"display: none;\"]').remove();
        },
        hide: function (e) {
            RepeaterSetDropdown();
            $(this).slideUp(e);
            setTimeout(function(){
                RepeaterSetFieldTombol();
            }, 500);
        },
    });
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Log List'))."');
    $('.alert').fadeOut(5000, function() { 
        $(this).remove(); 
    });
", yii\web\View::POS_READY); ?>
<script>

function addItem(loglist_id, lampiran, tambah_lampiran) {
    <?php
        if (empty($_GET['edit']) && !empty($_GET['loglist_id'])) {
        ?>
        var disabled =  $("#table-detail > tbody > tr:last").find('input:disabled, select:disabled').removeAttr('disabled');
        var last_tr =  $("#table-detail > tbody > tr:last").find("input,select").serialize();
        if (lampiran != '' || $lampiran > 0 || !empty($lampiran)) {
            var lampiran = lampiran;
        } else {
            lampiran = 1;
        }

        if( $('input[name*="[model_ukuran_loglist]"]:checked').val() == "0") { // kondisi 2 diameter
            var ukuran = "2 Diameter";
        }else{
            var ukuran = "4 Diameter";
        }

        if( $('input[name*="[area_pembelian]"]:checked').val() == "0") { // kondisi 2 diameter
            var area_pembelian = "Jawa";
        }else{
            var area_pembelian = "Luar Jawa";
        }

        disabled.attr('disabled','disabled');
        var loglist_id = $('#<?= yii\helpers\Html::getInputId($model, "loglist_id") ?>').val();
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/addItem']); ?>',
            type   : 'POST',
            data   : {last_tr:last_tr,loglist_id:loglist_id,ukuran:ukuran,area_pembelian:area_pembelian,lampiran:lampiran,tambah_lampiran:tambah_lampiran},
            success: function (data){
                if(data.html){
                    $(data.html).hide().appendTo('#table-detail tbody').fadeIn(200,function(){
                        $("#table-detail > tbody").find('input[name*="[diameter_rata]"]').attr('readonly', true);
                        $("#table-detail > tbody").find('input[name*="[volume_value]"]').attr('readonly', true);
                        reordertable('#table-detail');
                    });
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
        <?php
    }
    ?>
}

function hitungRata(obj){
    /*if( $(obj).parents('tr').find('input[name*="[diameter_ujung]"]').length!=0 ) {
        var ujung = parseInt($(obj).parents('tr').find('input[name*="[diameter_ujung]"]').val());
        var pangkal = parseInt($(obj).parents('tr').find('input[name*="[diameter_pangkal]"]').val());
        if (area_pembelian == 0) {
            if((ujung) && (pangkal)){
                $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( ( (ujung) + (pangkal) )/2 );
            }else{
                $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( 0 );
            }
        } else {
            
        }
    }else{
        var ujung1 = parseInt($(obj).parents('tr').find('input[name*="[diameter_ujung1]"]').val());
        var ujung2 = parseInt($(obj).parents('tr').find('input[name*="[diameter_ujung2]"]').val());
        var pangkal1 = parseInt($(obj).parents('tr').find('input[name*="[diameter_pangkal1]"]').val());
        var pangkal2 = parseInt($(obj).parents('tr').find('input[name*="[diameter_pangkal2]"]').val());
        if((ujung1) && (ujung2) && (pangkal1) && (pangkal2)){
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( ( (ujung1) + (ujung2) + (pangkal1) + (pangkal2) )/4 );
        }else{
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( 0 );
        }
    }*/

    var area_pembelian = '<?php echo $model->area_pembelian;?>';
    var model_ukuran_loglist = '<?php echo $model->model_ukuran_loglist;?>';
    
    var ujung = parseInt($(obj).parents('tr').find('input[name*="[diameter_ujung]"]').val());
    var pangkal = parseInt($(obj).parents('tr').find('input[name*="[diameter_pangkal]"]').val());
    
    var ujung1 = parseInt($(obj).parents('tr').find('input[name*="[diameter_ujung1]"]').val());
    var ujung2 = parseInt($(obj).parents('tr').find('input[name*="[diameter_ujung2]"]').val());
    var pangkal1 = parseInt($(obj).parents('tr').find('input[name*="[diameter_pangkal1]"]').val());
    var pangkal2 = parseInt($(obj).parents('tr').find('input[name*="[diameter_pangkal2]"]').val());

    // PEMBELIAN DARI JAWA
    if (area_pembelian == 0) {
        if (model_ukuran_loglist == 0 && ujung && pangkal) {
            // diameter 2
            // D rata = ROUND((diamater ujung + diameter pangkal) /2 ;0)
            var ratarata = Math.round(((ujung + pangkal)/2));
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( ratarata );
        } else if (model_ukuran_loglist == 1 && ujung1 && ujung2 && pangkal1 && pangkal2) {
            // diameter 4
            //D rata = ROUND((diamater ujung + diamater ujung + diameter pangkal + diameter pangkal) /4 ;0)
            var ratarata = Math.round(((ujung1 + ujung2 + pangkal1 + pangkal2)/4));
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( ratarata );
        } else {
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( 0 );
        }

    // PEMBELIAN DARI LUAR JAWA
    } else if (area_pembelian == 1) {
        if (model_ukuran_loglist == 0 && ujung && pangkal) {
            // 2 DIAMETER
            // D rata = ROUNDDOWN((diamater ujung + diameter pangkal) /2 ;0)
            var ratarata = Math.floor((ujung + pangkal)/2);
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( ratarata );
        } else if (model_ukuran_loglist == 1 && ujung1 && ujung2 && pangkal1 && pangkal2) {
            // 4 DIAMETER
            // D rata = ROUNDDOWN((diamater ujung + diamater ujung + diameter pangkal + diameter pangkal) /4 ;0)
            var ratarata = Math.floor((ujung1 + ujung2 + pangkal1 + pangkal2)/4);
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( ratarata );
        } else {
            $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( 0 );
        }
    } else {
        $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val( 0 );
    }

    if (ratarata <= 24.99) {
        var VolRange = "below-25"
    } else if (ratarata>=25 && ratarata<=29.99) {
        var VolRange = "25-29"
    } else if (ratarata>=30 && ratarata<=39.99) {
        var VolRange = "30-39"
    } else if (ratarata>=40 && ratarata<=49.99) {
        var VolRange = "40-49"
    } else if (ratarata>=50 && ratarata<=59.99) {
        var VolRange = "50-59"
    } else if (ratarata>=60 && ratarata<=69.99) {
        var VolRange = "60-69"
    } else {
        var VolRange = "70-up"
    }
    console.log(ratarata);
    $(obj).parents('tr').find('input[name*="[volume_range]"]').val(VolRange);
    hitungVolume(obj);
}

function hitungVolume(obj){
    var area_pembelian = '<?php echo $model->area_pembelian;?>';    
    var panjang = $(obj).parents('tr').find('input[name*="[panjang]"]').val();
    var ratarata = $(obj).parents('tr').find('input[name*="[diameter_rata]"]').val(); 
    var cacat_panjang = $(obj).parents('tr').find('input[name*="[cacat_panjang]"]').val();
    var cacat_gb = $(obj).parents('tr').find('input[name*="[cacat_gb]"]').val();
    var cacat_gr = $(obj).parents('tr').find('input[name*="[cacat_gr]"]').val();
    
    panjang == '' ? panjang = 0 : panjang = parseFloat(panjang);
    ratarata == '' ? ratarata = 0 : ratarata = parseFloat(ratarata);
    cacat_panjang == '' ? cacat_panjang = 0 : cacat_panjang = parseFloat(cacat_panjang);
    cacat_gb == '' ? cacat_gb = 0 : cacat_gb = parseFloat(cacat_gb);
    cacat_gr == '' ? cacat_gr = 0 : cacat_gr = parseFloat(cacat_gr);

    // PEMBELIAN DARI JAWA
    if (area_pembelian == 0) {
        // Prosentase Growong (GR) = ROUND((0.7854  x cacat_gr(GR) x cacat_gr(GR) x (panjang - panjang cacat/100)) / 10000 ; 2)
        // Vol = ROUND( 0.7854 x (panjang - (panjang cacat/100)) x ((D rata - Gubal/100) x (D rata – (Gubal/100) ) x 1) /10000) - (Prosentase Growong (GR)) ; 2)
        var pGrowong = (0.7854 * cacat_gr * cacat_gr * (panjang - (cacat_panjang/100)) / 10000) ;
        pGrowong == '' ? pGrowong = 0 : pGrowong = pGrowong;
        var zzz = (0.7854 * (panjang - (cacat_panjang/100)) * ((ratarata - cacat_gb/100) * (ratarata - (cacat_gb/100) ) * 1) / 10000) - (pGrowong);
        var Vol = Math.round(zzz * 100) / 100;
    } 
    
    // PEMBELIAN DARI LUAR JAWA SUDAH BERES OJO DIUTAK ATIK DUL
    else if (area_pembelian == 1) {
        // Prosentase Gubal (GB) = ROUND(4 x Gubal x (D rata - Gubal) x 100 / D rata / D rata ; 1)
        // Prosentase Growong (GR) = ROUND((1.273 x Growong x Growong x 100) / (D rata x D rata) ; 1)
        // Vol = ROUND( 0.7854 x (panjang - (panjang cacat/100)) x ((D rata x D rata x 1) /10000) x ((100 - Prosentase Gubal (GB) ) / 100) x ((100 - Prosentase Growong (GR) ) / 100) ;2 )
        var pGubal = Math.round( (4 * cacat_gb * (ratarata - cacat_gb) * 100 / ratarata / ratarata) * 10) / 10;
        var pGrowong = Math.round( (1.273 * cacat_gr * cacat_gr * 100) / (ratarata * ratarata) * 10) / 10;
        pGubal == '' ? pGubal = 0 : pGubal = pGubal;
        pGrowong == '' ? pGrowong = 0 : pGrowong = pGrowong;
        var zzz = ((0.7854*((panjang-(cacat_panjang/100))*ratarata*ratarata*1)/10000) * (((100-pGubal)/100) * ((100-pGrowong)/100)));
        var Vol = Math.round( zzz * 100) / 100;
    } else {
        var Vol = 0;
    }

    $(obj).parents('tr').find('input[name*="[volume_value]"]').val(Vol);
}

function setDropdownGrader(obj){
    var selected_items = [];
    $('#table-detail tbody tr').each(function(){
        var graderlog_id = $(this).find('select[name*="[graderlog_id]"]').val();
        if(graderlog_id){
            selected_items.push(graderlog_id);
        }
    });
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/setDropdownGrader']); ?>',
		type   : 'POST',
		data   : {selected_items:selected_items},
		success: function (data) {
			$(obj).find('select[name*="[graderlog_id]"]').html(data.html);
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function RepeaterSetItemRequired(){
    var list_terisi = true;
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        var list = $(this).find('select[name*="[grader_id]"]').val();
        if(list){
            list_terisi &= true;
            $(this).find('select[name*="[grader_id]"]').removeAttr('style');
        }else{
            list_terisi &= false;
            $(this).find('select[name*="[grader_id]"]').attr('style','border-color: #e73d4a;');
        }
    });
    return (list_terisi);
}

function RepeaterSetDropdown(){
    var arr_list = [];
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        var list = $(this).find('select[name*="[grader_id]"]').val();
        arr_list[index] = list;
    });
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/repeaterSetDropdown']); ?>',
        type   : 'POST',
        data   : {list:arr_list},
        success: function (data) {
            if(data.html){
                $('div[data-repeater-item][style!="display: none;"]:last').find('select[name*="[grader_id]"]').html(data.html);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
	return false;
}

function RepeaterSetFieldTombol(){
	if( $('#total-item-repeater').val() == $('div[data-repeater-item][style!="display: none;"]').length ){
        $('.mt-repeater-add').attr('style','visibility:hidden; margin-top: 5px');
    }else{
        $('.mt-repeater-add').attr('style','visibility:visible; margin-top: 5px');
    }
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        $(this).find('select[name*="[grader_id]"]').prop('disabled', true);
        $(this).find('#remove-btn').css('visibility','hidden');
    });
    $('div[data-repeater-item][style!="display: none;"]:last').find('select[name*="[grader_id]"]').prop('disabled', false);
    $('div[data-repeater-item][style!="display: none;"]:last').find('#remove-btn').css('visibility','visible');
}

function save(){
    var $form = $('#form-transaksi');
    if (formrequiredvalidate($form)) {
        /*var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail()){
            submitform($form);
        }*/
        var grader_id = $('#form-transaksi').find('select[name*="[grader_id]"]').val();
        var kode_po = $('#form-transaksi').find('input[name*="[kode_po]"]').val();
        var nomor_kontrak = $('#form-transaksi').find('input[name*="[nomor_kontrak]"]').val();
        if(grader_id <= 0 || kode_po == '' || nomor_kontrak == '' ){
            if (grader_id <= 0) {
                $('#tloglist-grader_id').css({'border':'solid 1px #f00'});
                cisAlert('Grader belum lengkap diinput');
            } else {
                $('#tloglist-pengajuan_pembelianlog_id').css({'border':'solid 1px #f00'});
                $('#tloglist-kode_po').css({'border':'solid 1px #f00'});
                $('#tloglist-nomor_kontrak').css({'border':'solid 1px #f00'});
                cisAlert('Kode keputusan belum diinput');
            }           
            return false;
        } else {
            submitform($form);
        }
    }
    return false;
}

/*function saveDetail (){
    var $form = $('#form-detail');
    if(formrequiredvalidate($form)){
        var jumlah_item = $('#table-detail tbody tr').length;
        if(jumlah_item <= 0){
                cisAlert('Isi detail terlebih dahulu');
            return false;
        }
        if(validatingDetail()){
          submitform($form);
        }
    }
    return false;
}*/

function validatingDetail(ele){
    var has_error = 0;
    var check = "";
    var i = 1;
    $('#table-detail tbody > tr').each(function(){
        var field1 = $(this).find('input[name*="[nomor_grd]"]');
        var field2 = $(this).find('input[name*="[nomor_produksi]"]');
        var field3 = $(this).find('input[name*="[nomor_batang]"]');
        var field4 = $(this).find('select[name*="[kayu_id]"]');
        var field5 = $(this).find('input[name*="[panjang]"]');
        if($(this).find('input[name*="[diameter_ujung]"]').length!=0){
            var field6 = $(this).find('input[name*="[diameter_ujung]"]');
            var field7 = $(this).find('input[name*="[diameter_pangkal]"]');
        }else{
            var field6 = $(this).find('input[name*="[diameter_ujung1]"]');
            var field10 = $(this).find('input[name*="[diameter_ujung2]"]');
            var field7 = $(this).find('input[name*="[diameter_pangkal1]"]');
            var field11 = $(this).find('input[name*="[diameter_pangkal2]"]');
        }
        var field8 = $(this).find('input[name*="[diameter_rata]"]');
        var field9 = $(this).find('input[name*="[volume_value]"]');
        
        /*if(!field1.val()){
            $(this).find('input[name*="[nomor_grd]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "1";
        }else{
            $(this).find('input[name*="[nomor_grd]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field2.val()){
            $(this).find('input[name*="[nomor_produksi]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "2";
        }else{
            $(this).find('input[name*="[nomor_produksi]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field3.val()){
            $(this).find('input[name*="[nomor_batang]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "3";
        }else{
            $(this).find('input[name*="[nomor_batang]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field4.val()){
            $(this).find('select[name*="[kayu_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "4";
        }else{
            $(this).find('select[name*="[kayu_id]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field5.val()){
            $(this).find('input[name*="[panjang]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "5";
        }else{
            $(this).find('input[name*="[panjang]"]').parents('td').removeClass('error-tb-detail');
        }
        if($(this).find('input[name*="[diameter_ujung]"]').length!=0){
            if(!field6.val()){
                $(this).find('input[name*="[diameter_ujung]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "62";
            }else{
                $(this).find('input[name*="[diameter_ujung]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field7.val()){
                $(this).find('input[name*="[diameter_pangkal]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "72";
            }else{
                $(this).find('input[name*="[diameter_pangkal]"]').parents('td').removeClass('error-tb-detail');
            }
        }else{
            if(!field6.val()){
                $(this).find('input[name*="[diameter_ujung1]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "64";
            }else{
                $(this).find('input[name*="[diameter_ujung1]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field7.val()){
                $(this).find('input[name*="[diameter_pangkal1]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "74";
            }else{
                $(this).find('input[name*="[diameter_pangkal1]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field10.val()){
                $(this).find('input[name*="[diameter_ujung2]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "64";
            }else{
                $(this).find('input[name*="[diameter_ujung2]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field11.val()){
                $(this).find('input[name*="[diameter_pangkal2]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "74";
            }else{
                $(this).find('input[name*="[diameter_pangkal2]"]').parents('td').removeClass('error-tb-detail');
            }
        }        
        if(!field8.val()){
            $(this).find('input[name*="[diameter_rata]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "8";
        }else{
            $(this).find('input[name*="[diameter_rata]"]').parents('td').removeClass('error-tb-detail');
        }
        if(!field9.val()){
            $(this).find('input[name*="[volume_value]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1; check += "9";
        }else{
            $(this).find('input[name*="[volume_value]"]').parents('td').removeClass('error-tb-detail');
        }*/
        
        if ( (field5.val() != '' && field8.val() != '') && (field1.val() == '' || field2.val() == '' || field3.val() == '' || field4.val() == '' || field8.val() == '' || field9.val() == '' ) ) {
            field1.val == '' ? $(this).find('input[name*="[nomor_grd]"]').parents('td').addClass('error-tb-detail') : $(this).find('input[name*="[nomor_grd]"]').parents('td').removeClass('error-tb-detail');
            field2.val == '' ? $(this).find('input[name*="[nomor_produksi]"]').parents('td').addClass('error-tb-detail') : $(this).find('input[name*="[nomor_produksi]"]').parents('td').removeClass('error-tb-detail');
            field3.val == '' ? $(this).find('input[name*="[nomor_batang]"]').parents('td').addClass('error-tb-detail') : $(this).find('input[name*="[nomor_batang]"]').parents('td').removeClass('error-tb-detail');
            field4.val == '' ? $(this).find('input[name*="[kayu_id]"]').parents('td').addClass('error-tb-detail') : $(this).find('input[name*="[kayu_id]"]').parents('td').removeClass('error-tb-detail');
            if(!field1.val()){
                $(this).find('input[name*="[nomor_grd]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "1";
            }else{
                $(this).find('input[name*="[nomor_grd]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field2.val()){
                $(this).find('input[name*="[nomor_produksi]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "1";
            }else{
                $(this).find('input[name*="[nomor_produksi]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field3.val()){
                $(this).find('input[name*="[nomor_batang]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "1";
            }else{
                $(this).find('input[name*="[nomor_batang]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field4.val()){
                $(this).find('select[name*="[kayu_id]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "1";
            }else{
                $(this).find('select[name*="[kayu_id]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field8.val() || field8.val() < 1){
                $(this).find('input[name*="[diameter_rata]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "1";
            }else{
                $(this).find('input[name*="[diameter_rata]"]').parents('td').removeClass('error-tb-detail');
            }
            if(!field9.val() || field9.val() < 0 || isNaN(field9.val()) ){
                $(this).find('input[name*="[volume_value]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1; check += "1";
            }else{
                $(this).find('input[name*="[volume_value]"]').parents('td').removeClass('error-tb-detail');
            }
            check += "1";
        } else {
            has_error2 = 0;
        }
        i++;
    });

    if((has_error + has_error2) == 0){
        return true;
    } else {
        return false;
    }
    return false;
}

function afterSave(id){
    setDiameter();
    $('form').find('input').each(function(){ $(this).prop("disabled", true); });
    $('form').find('select').each(function(){ $(this).prop("disabled", true); });
	$('form').find('textarea').each(function(){ $(this).attr("disabled","disabled"); });
    $('input[name*="[model_ukuran_loglist]"]').prop("readonly", true);
    $('input[name*="[area_pembelian]"]').prop("readonly", true);
    $('#tloglist-tanggal').siblings('.input-group-btn').find('button').prop('disabled', true);
    $('#btn-save').attr('disabled','');
    $('#btn-print').removeAttr('disabled');
    $("#<?= \yii\helpers\Html::getInputId($model, "pengajuan_pembelianlog_id") ?>").removeAttr('onchange');
    $("#<?= \yii\helpers\Html::getInputId($model, "pengajuan_pembelianlog_id") ?>").empty().append('<option value="<?= !empty($model->pengajuan_pembelianlog_id)?$model->pengajuan_pembelianlog_id:"" ?>"><?= !empty($model->pengajuanPembelianlog)?$model->pengajuanPembelianlog->kode:""; ?></option>').val('<?= !empty($model->pengajuan_pembelianlog_id)?$model->pengajuan_pembelianlog_id:"" ?>').trigger('change');
    <?php if(isset($_GET['edit'])){ ?>
        $("#<?= \yii\helpers\Html::getInputId($model, "tanggal") ?>").prop("disabled", false);
        $('#tloglist-tanggal').siblings('.input-group-btn').find('button').prop('disabled', false);
        $("#<?= \yii\helpers\Html::getInputId($model, "tongkang") ?>").prop("disabled", false);
        $("#<?= \yii\helpers\Html::getInputId($model, "kode_bajg") ?>").prop("disabled", false);
        $("#<?= \yii\helpers\Html::getInputId($model, "lokasi_muat") ?>").prop("disabled", false);
        $('#btn-save').removeAttr('disabled');
        $('#btn-tambah-lampiran').hide();
        $('#btn-save-details').hide();
    <?php } ?>
}

function saveItem(ele){
    /*if(validatingDetail(ele)){
		$(ele).parents('tr').find('input[name*="[volume_value]"]').val( unformatNumber($(ele).parents('tr').find('input[name*="[volume_value]"]').val()) );
		$(ele).parents('tr').addClass('animation-loading');
        $('#table-detail tbody > tr').each(function(){
            loglist_detail = $("#loglist_detail").serialize();
        });
		$.ajax({
			url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/saveitem']); ?>',
			type   : 'POST',
			//data   : { formData: $(ele).parents('tr').find('input, textarea, select').serialize() },
            data    : { formData: loglist_detail },
			success: function (data) {
			    //$(ele).parents('tr').find('input[name*="[volume_value]"]').val( formatNumberForUser($(ele).parents('tr').find('input[name*="[volume_value]"]').val()) );
                $(ele).parents('tr').find('input[name*="[volume_value]"]').val( ($(ele).parents('tr').find('input[name*="[volume_value]"]').val()) );
				if(data.status){
					$(ele).parents('tr').find('input[name*="[loglist_detail_id]"]').val( data.loglist_detail_id );
                    //  $(ele).parents('tr').find('input[name*="[no_barcode]"]').val( data.no_barcode );
					$(ele).parents('tr').find('input, textarea, select').attr('disabled','disabled');
					$(ele).parents('tr').find('#place-editbtn').attr('style','display:');
					$(ele).parents('tr').find('#place-cancelbtn').attr('style','display:none');
					$(ele).parents('tr').find('#place-savebtn').attr('style','display:none');
					$(ele).parents('tr').find('#place-deletebtn').attr('style','display:');
					$(ele).parents('tr').removeClass('animation-loading');
				}else{
					cisAlert(data.message);
				}
				reordertable('#table-detail');
			},
			error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		});
	}
    return false;*/

    if(validatingDetail(ele)){
        <?php
        if (empty($lampiran) || !isset($lampiran) || $lampiran < 1) {
            $lampiran = 1;
        } else {
            $lampiran = $lampiran;
        }
        ?>

        var $form_loglist_detail = $('#form-loglist-detail');
        $('.table-detail tbody tr').each(function(){
            $(this).find("input[name*='[nomor_grd]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[nomor_produksi]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[nomor_batang]']").parents("td").removeClass("has-error");
            $(this).find("select[name*='[kayu_id]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[panjang]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[diameter_ujung]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[diameter_pangkal]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[diameter_rata]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[cacat_panjang]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[cacat_gb]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[cacat_gr]']").parents("td").removeClass("has-error");
            $(this).find("select[name*='[volume_range]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[volume_value]']").parents("td").removeClass("has-error");
            $(this).find("input[name*='[lampiran]']").val('<?php echo $lampiran;?>');
        });

        if(formrequiredvalidate($form_loglist_detail)){
            /*var jumlah_loglist_detail = $('.table-detail tbody tr.uncount-tr').length;
            if(jumlah_loglist_detail > 0){
                cisAlert('Ada detail loglist yang masih kosong!');
                return false;
            }
            if(validatingDetail()){
                submitform($form_loglist_detail);
            }*/
        }
        submitform($form_loglist_detail);
        return true;
    } else {
        return false;
    }
    
    return false;
}

function edit(ele){
	$(ele).parents('tr').find('input, select').removeAttr('disabled');
	$(ele).parents('tr').find('#place-editbtn').attr('style','display:none');
	$(ele).parents('tr').find('#place-savebtn').attr('style','display:');
    $(ele).parents('tr').find('input[name*="[diameter_rata]"]').attr('readonly', true);
    $(ele).parents('tr').find('input[name*="[volume_value]"]').attr('readonly', true);
}

function deleteItem(ele){
	var loglist_detail_id = $(ele).parents("tr").find("input[name*='[loglist_detail_id]']").val();
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/deleteItem','id'=>''])?>'+loglist_detail_id,'modal-delete-record');
}

/*function getItems(uk=null){
	var loglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'loglist_id') ?>').val();
	if(uk){
        var ukuran = "4 Diameter";
    }else{
        var ukuran = "2 Diameter";
    }
    var lampiran = 1;
	/*$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/getItems']); ?>',
		type   : 'POST',
		data   : {loglist_id:loglist_id,lampiran:lampiran,ukuran:ukuran,edit:'<?= isset($_GET['edit'])?"1":"0" ?>'},
		success: function (data) {
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
			}
			reordertable('#table-detail');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
    lihatLampiran(loglist_id, lampiran);
}*/

function tambahLampiran(loglist_id, lampiran, tambah_lampiran){
    if (tambah_lampiran == '' || tambah_lampiran < 1) {
        tambah_lampiran = tambah_lampiran + 1;
    } else {
        tambah_lampiran = 1;
    }
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/tambahLampiran']); ?>',
        type   : 'POST',
        data   : {loglist_id:loglist_id, lampiran:lampiran, tambah_lampiran:tambah_lampiran},
        success: function (data){
			$('#table-detail > tbody').html("");
            $('#table-rekap').hide();
            $('#lampiran').html('<font style="font-weight: bold; color: #fff; font-size: 20px;">L '+data.lampiran_baru+'</font>');
            addItem(loglist_id,lampiran, 1);
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function lihatLampiran(loglist_id, lampiran){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/lihatLampiran']); ?>',
        type   : 'POST',
        data   : {loglist_id, lampiran},
        success: function (data){
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
                $('#lampiran').html('<font style="font-weight: bold; color: #fff; font-size: 20px;">L '+lampiran+'</font>');
			}
			reordertable('#table-detail');
            <?php
            //isset($_REQUEST['success']) && $_REQUEST['success'] == 2 ? $_REQUEST['GET'] = 0 : $_REQUEST['GET'] = '';
            ?>
            addItem(loglist_id, lampiran, 0);
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });

    lihatRekap(loglist_id, lampiran);
}

function lihatRekap(loglist_id, lampiran) {
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/lihatRekap']); ?>',
        type   : 'POST',
        data   : {loglist_id, lampiran},
        success: function (data){
			$('#table-rekap > tbody').html("");
			if(data.html){
				$('#table-rekap > tbody').html(data.html);
			}
			reordertable('#table-rekap');
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/daftarAfterSave']) ?>','modal-aftersave','95%');
}

function setKeputusan(){
	var pengajuan_pembelianlog_id = $("#<?= yii\helpers\Html::getInputId($model, "pengajuan_pembelianlog_id") ?>").val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/setKeputusan']); ?>?pengajuan_pembelianlog_id='+pengajuan_pembelianlog_id,
		type   : 'POST',
		data   : {},
		success: function (data) {
			if(data){
				if(data.modKontrak && data.model){
					$('#<?= yii\helpers\Html::getInputId($model, "kode_po") ?>').val(data.modKontrak.kode+' - '+data.modKontrak.tanggal_po);
					$('#<?= yii\helpers\Html::getInputId($model, "nomor_kontrak") ?>').val(data.modKontrak.nomor);
					$('#<?= yii\helpers\Html::getInputId($model, "lokasi_muat") ?>').val(data.model.lokasi_muat);
					$('#<?= yii\helpers\Html::getInputId($model, "log_kontrak_id") ?>').val(data.modKontrak.log_kontrak_id);
					$("#btn-detailkeputusan").addClass("blue-hoki");
					$("#btn-detailkeputusan").removeClass("grey");
				}else{
					$('#<?= yii\helpers\Html::getInputId($model, "kode_po") ?>').val("");
					$('#<?= yii\helpers\Html::getInputId($model, "nomor_kontrak") ?>').val("");
					$('#<?= yii\helpers\Html::getInputId($model, "lokasi_muat") ?>').val("");
					$('#<?= yii\helpers\Html::getInputId($model, "log_kontrak_id") ?>').val("");
					$("#btn-detailkeputusan").removeClass("blue-hoki");
					$("#btn-detailkeputusan").addClass("grey");
				}
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function detailKeputusan(){
	var id = $("#<?= yii\helpers\Html::getInputId($model, 'pengajuan_pembelianlog_id') ?>").val();
	openModal("<?= yii\helpers\Url::toRoute('/purchasinglog/laporan/MonitoringPembelianLogDetail') ?>?id="+id,"modal-detail");
}

function cancelItemThis(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        var kubikasi = $("#table-detail > tbody > tr:last").find('input[name*="[volume_value]"]').val();
        $(this).remove();
        reordertable('#table-detail');
    });
}

function setFieldTombolTOP(){
    $('div[data-repeater-item][style!="display: none;"]').each(function(index){
        $(this).find('select[name*="[grader_id]"]').attr('readonly','readonly');
        $(this).find('input[name*="[grader_id]"]').prop('readonly', true);
        $(this).find('#remove-btn').css('visibility','hidden');
    });
    $('div[data-repeater-item][style!="display: none;"]:last').find('select[name*="[grader_id]"]').removeAttr('readonly');
    $('div[data-repeater-item][style!="display: none;"]:last').find('select[name*="[grader_id]"]').removeAttr('disabled');
    $('div[data-repeater-item][style!="display: none;"]:last').find('input[name*="[grader_id]"]').prop('readonly', false);
    $('div[data-repeater-item][style!="display: none;"]:last').find('#remove-btn').css('visibility','visible');
}

function setDiameter(){
    $('input[name*="[model_ukuran_loglist]"]').prop("readonly", true);
    $('input[name*="[area_pembelian]"]').prop("readonly", true);
    $("#table-detail").addClass("animation-loading");
    var loglist_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'loglist_id') ?>').val();
    var ukuran = $('input[name*="[model_ukuran_loglist]"]').val();
    var lampiran = $('#lampiran').text();
    lampiran == '' || lampiran == 0 ? lampiran = 1 : lampiran = lampiran;
    
    if( $('input[name*="[model_ukuran_loglist]"]:checked').val() == "0") { // kondisi 2 diameter
        $(".diameter2").removeClass("hidden");
        $(".diameter4").addClass("hidden");
        $("#diameter-th").attr("colspan","3");
        var modeluk = "2 Diameter";
    }else{
        $(".diameter2").addClass("hidden");
        $(".diameter4").removeClass("hidden");
        $("#diameter-th").attr("colspan","5");
        var modeluk = "4 Diameter";
    }
    
    if( $('input[name*="[area_pembelian]"]:checked').val() == "0") { // kondisi 2 diameter
        $(".jawa").removeClass("hidden");
        $(".luar_jawa").addClass("hidden");
        var modelarea = "Jawa";
    }else{
        $(".jawa").addClass("hidden");
        $(".luar_jawa").removeClass("hidden");
        var modelarea = "Luar Jawa"
    }
    
    $.ajax({
        url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/terimaloglist/updateModelLoglist']); ?>',
        type   : 'POST',
        data   : {loglist_id:loglist_id,ukuran:ukuran,modeluk:modeluk,modelarea:modelarea,edit:'<?= isset($_GET['edit'])?"1":"0" ?>'},
        success: function (data) {
            $("#table-detail").removeClass("animation-loading");
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });

    <?php
    if (isset($_REQUEST['success'])) {
        //$_REQUEST['success'] = 0;
    }

    if (isset($_GET['loglist_id'])) {
    ?>
    lihatLampiran(loglist_id,1);
    //getItems(ukuran);
    <?php
    }
    ?>

}

</script>