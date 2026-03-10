<?php

use app\components\DeltaFormatter;
use yii\helpers\Url;
use yii\bootstrap\Html;
use app\models\MMtrgSetup;
use app\models\MDefaultValue;

?>
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light bordered form-search">
            <div class="portlet-title">
                <div class="tools panel-cari">
                    <button type="button" href="javascript:;" class="expand btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
                    <span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian') ?></span>
                </div>
            </div>
            <div class="portlet-body" style="display: none">
                <div class="modal-body">
                    <form id="filter-setup">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Tanggal', 'tanggal', ['class' => 'control-label'])?>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group date date-picker bs-datetime" data-date-end-date="-0d">
                                            <?= Html::textInput('tanggal', DeltaFormatter::formatDateTimeForUser2(MMtrgSetup::getActiveDate()), ['class' => 'form-control', 'id' => 'tanggal'])?>
                                            <span class="input-group-addon">
                                                <button class="btn btn-default" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Kategori Proses', 'kategori_proses', ['class' => 'control-label']) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <?= Html::dropDownList('kategori_proses', null, MDefaultValue::getOptionList('kategori-proses'), ['class' => 'form-control', 'id' => 'kategori_proses', 'prompt' => '-- Filter Kategori Proses --']) ?>
                                    </div>
                                </div>
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Jenis Proses', 'jenis_proses', ['class' => 'control-label']) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <?= Html::dropDownList('jenis_proses', null, ['INPUT' => 'INPUT', 'OUTPUT' => 'OUTPUT'], ['class' => 'form-control', 'id' => 'jenis_proses', 'prompt' => '-- Filter Jenis Proses --']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Jenis Kayu', 'jenis_kayu', ['class' => 'control-label']) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <?= Html::dropDownList('jenis_kayu', null, MDefaultValue::getOptionList('mrtg-jenis-kayu'), ['class' => 'form-control', 'id' => 'jenis_kayu', 'prompt' => '-- Filter Jenis Kayu --']) ?>
                                    </div>
                                </div>
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Grade', 'grade', ['class' => 'control-label']) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <?= Html::dropDownList('grade', null, MDefaultValue::getOptionList('mtrg-grade'), ['class' => 'form-control', 'id' => 'grade', 'prompt' => '-- Filter Grade --']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs('init()'); ?>
<script>
    function init() {
        const kategori = $('#kategori_proses');
        const jenis = $('#jenis_proses');

        kategori.on('change', setGrade);
        jenis.on('change', setGrade);

        function setGrade() {
            $.ajax({
                url: '<?= Url::toRoute('/ppic/monitoringsetup/getgrade') ?>?kategori=' + kategori.val() + '&proses=' + jenis.val(),
                success: optionBuilder,
                error: function(err) {
                    alert(err.message);
                }
            });
        }

        function optionBuilder(grades) {
            let html = '<option value="">-- Pilih Grade --</option>';
            for (let key in grades) {
                html += `<option value="${key}">${grades[key]}</option>`
            }
            $('#grade').html(html);
        }
    }
</script>