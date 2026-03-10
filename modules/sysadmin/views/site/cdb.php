<?php
/* @var $this yii\web\View */
$this->title = 'Compare Database';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/index"); ?>"> <?= Yii::t('app', 'General'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/systemconfig"); ?>"> <?= Yii::t('app', 'System Config'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/pengumuman/index"); ?>"> <?= Yii::t('app', 'Pengumuman'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/extensiontelepon/index"); ?>"> <?= Yii::t('app', 'Extension Telepon'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/servermonitor"); ?>"> <?= Yii::t('app', 'Server Monitor'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/servermonitors"); ?>"> <?= Yii::t('app', 'Backup Monitor'); ?> </a>
                    </li>
					<li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/site/cdb"); ?>"> <?= Yii::t('app', 'Compare Database'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">

                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered form-search">
                            <div class="portlet-title">
                                <div class="tools panel-cari">
                                    <button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
                                    <span style=""> <?= Yii::t('app', '&nbsp;Advance Search'); ?></span>
                                </div>
                            </div>
                            <div class="portlet-body" style="">
                                <?php $form = \yii\bootstrap\ActiveForm::begin([
                                    'id' => 'form-search-laporan',
                                    'fieldConfig' => [
                                        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                                        'labelOptions'=>['class'=>'col-md-3 control-label'],
                                    ],
                                    'enableClientValidation'=>false
                                ]); ?>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Tanggal</label>
                                                <div class="col-md-6">
                                                    <span class="input-group-btn" style="width: 50%">
                                                        <?= $form->field($model, 'tgl_awal',[
                                                                    'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                                <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                                {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                                    </span>
                                                    <span class="input-group-addon textarea-addon" style="width: 10%; background-color: #fff; border: 0;"> sd </span>
                                                    <span class="input-group-btn" style="width: 50%">
                                                        <?= $form->field($model, 'tgl_akhir',[
                                                                    'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                                <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                                {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                                    </span>
                                                <span class="help-block"></span>
                                                </div>
                                            </div>

                                            <?= $form->field($model, 'spb_kode')->textInput()->label(Yii::t('app', 'Kode SPB')); ?>
                                            <?= $form->field($model, 'departement_id')->dropDownList(\app\models\MDepartement::getOptionList(),['prompt'=>'','onchange'=>'setDropdownPegawai()'])->label(Yii::t('app', 'Departement')); ?>
                                        </div>
                                        <div class="col-md-5">
                                            <?= $form->field($model, 'spb_status')->dropDownList(\app\models\MDefaultValue::getOptionList('spb-status'),['prompt'=>''])->label(Yii::t('app', 'Status SPB')); ?>
                                            <?= $form->field($model, 'approve_status')->dropDownList(\app\models\MDefaultValue::getOptionList('approve_status'),['prompt'=>''])->label(Yii::t('app', 'Approve Status')); ?>
                                            <?= $form->field($model, 'spb_diminta')->dropDownList( [],['class'=>'form-control select2','prompt'=>''] )->label(Yii::t('app', 'Pegawai Pemesan')); ?>
                                        </div>
                                    </div>
                                    <?php echo $this->render('@views/apps/form/tombolSearch') ?>
                                </div>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                        
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-list"></i>
                                    <span class="caption-subject hijau bold text-danger"><?= Yii::t('app', 'List Semua SPB Yang Masuk (SERVER 10.10.10.2) - ('.$total_rows1.' rows)'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover" id="table-penerimaan">
										<thead>
											<tr>
												<th>No.</th>
												<th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
												<th><?= Yii::t('app', 'Tanggal'); ?></th>
												<th><?= Yii::t('app', 'Sifat Permintaan'); ?></th>
												<th><?= Yii::t('app', 'Dept. Pemesan'); ?></th>
												<th><?= Yii::t('app', 'Pegawai Pemesan'); ?></th>
												<th><?= Yii::t('app', 'Status SPB'); ?></th>
												<th><?= Yii::t('app', 'Status Approval'); ?></th>
												<th style="width: 50px;"></th>
											</tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($models1 as $model) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i;?></td>
                                            <td class="text-center"><?php echo $model['spb_kode'];?></td>
                                            <td class="text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($model['spb_tanggal']);?></td>
                                            <td><?php echo $model['spb_tipe'];?></td>
                                            <td><?php echo $model['departement_nama'];?></td>
                                            <td><?php echo $model['pegawai_nama'];?></td>
                                            <td><?php echo $model['spb_status'];?></td>
                                            <td><?php echo $model['approve_status'];?></td>
                                            <td><center><a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="info(<?php echo $model['spb_id'];?>)"><i class="fa fa-info-circle"></i></a></td>
                                        </tr>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                        </tbody>
									</table>
								</div>
                            </div>
                        </div>

                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-list"></i>
                                    <span class="caption-subject hijau bold text-success"><?= Yii::t('app', 'List Semua SPB Yang Masuk (SERVER 10.10.10.3) - ('.$total_rows2.' rows)'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover" id="table-penerimaan">
										<thead>
											<tr>
												<th>No.</th>
												<th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
												<th><?= Yii::t('app', 'Tanggal'); ?></th>
												<th><?= Yii::t('app', 'Sifat Permintaan'); ?></th>
												<th><?= Yii::t('app', 'Dept. Pemesan'); ?></th>
												<th><?= Yii::t('app', 'Pegawai Pemesan'); ?></th>
												<th><?= Yii::t('app', 'Status SPB'); ?></th>
												<th><?= Yii::t('app', 'Approve Status'); ?></th>
												<th style="width: 50px;"></th>
											</tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($models2 as $model) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i;?></td>
                                            <td class="text-center"><?php echo $model['spb_kode'];?></td>
                                            <td class="text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($model['spb_tanggal']);?></td>
                                            <td><?php echo $model['spb_tipe'];?></td>
                                            <td><?php echo $model['departement_nama'];?></td>
                                            <td><?php echo $model['pegawai_nama'];?></td>
                                            <td><?php echo $model['spb_status'];?></td>
                                            <td><?php echo $model['approve_status'];?></td>
                                            <td><center><a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="info(<?php echo $model['spb_id'];?>)"><i class="fa fa-info-circle"></i></a></td>
                                        </tr>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                        </tbody>
									</table>
								</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
formconfig();
setDropdownPegawai(2986);
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Site'))."');
", yii\web\View::POS_READY); ?>

<script>
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/site/cdbInfo','id'=>'']) ?>'+id,'modal-penerimaanspb-info');
}

function setDropdownPegawai(pegawai_id){
    var departement_id = document.getElementById("tspb-departement_id").value;
    if (departement_id > 0) {
        $('#tspb-spb_diminta').addClass('animation-loading');
        $.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/sysadmin/site/setDropdownPegawai']); ?>',
            type   : 'POST',
            data   : {departement_id:departement_id, pegawai_id:pegawai_id},
            success: function (data) {
                $('#tspb-spb_diminta').removeClass('animation-loading');
                $("#tspb-spb_diminta").html(data.html);
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });
    }
}
</script>