<?php

use app\models\MDefaultValue;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>
<div class="row">
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
                    <form id="filter-rekap">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Periode', 'startdate', ['class' => 'control-label']) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group input-daterange">
                                            <input type="text" class="form-control" name="startdate" value="<?= date('01/m/Y') ?>">
                                            <div class="input-group-addon">s/d</div>
                                            <input type="text" class="form-control" name="enddate" value="<?= date('t/m/Y', time()) ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Kode', 'kode', ['class' => 'control-label']) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <?= Html::input('text', 'kode', null, ['class' => 'form-control', 'placeholder' => 'Input kode...']) ?>
                                    </div>
                                </div>
                                <?php if (isset($_GET['tab']) && $_GET['tab'] == '2') : ?>
                                    <div class="form-group row margin-bottom-10">
                                        <div class="col-md-4">
                                            <?= Html::label('Status I/O', 'status_in_out', ['class' => 'control-label']) ?>
                                        </div>
                                        <div class="col-md-8">
                                            <?= Html::dropDownList('status_in_out', null, ['INPUT' => 'INPUT', 'OUTPUT' => 'OUTPUT'], ['class' => 'form-control', 'prompt' => '-- Pilih Status I/O --']) ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="col-md-4">
                                <?php if (isset($_GET['tab']) && $_GET['tab'] == '2') : ?>
                                    <div class="form-group row margin-bottom-10">
                                        <div class="col-md-4">
                                            <?= Html::label('Kategori Proses', 'kategori_proses', ['class' => 'control-label']) ?>
                                        </div>
                                        <div class="col-md-8">
                                            <?= Html::dropDownList('kategori_proses', null, MDefaultValue::getOptionList('kategori-proses'), ['class' => 'form-control', 'prompt' => '-- Pilih kategori --']) ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Shift', 'shift', ['class' => 'control-label']) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <?= Html::dropDownList('shift', null, ['' => '-- Pilih Shift --', 'A' => 'A', 'B' => 'B'], ['class' => 'form-control']) ?>
                                    </div>
                                </div>
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Jenis Kayu', 'jenis_kayu', ['class' => 'control-label']) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <?= Html::dropDownList('jenis_kayu', null, ['Sengon' => 'Sengon', 'Jabon' => 'Jabon'], ['class' => 'form-control', 'prompt' => '-- Pilih Jenis Kayu --']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Display Data', 'length', ['class' => 'control-label']) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <?= Html::dropDownList('length', 25, [10 => 10, 25 => 25, 50 => 50, -1 => 'All'], ['class' => 'form-control']) ?>
                                    </div>
                                </div>
                                <div class="form-group row margin-bottom-10">
                                    <div class="col-md-4">
                                        <?= Html::label('Display Font', 'font', ['class' => 'control-label']) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <?= Html::dropDownList('font', 'medium', ['medium' => 'Medium', 'small' => 'Small'], ['class' => 'form-control']) ?>
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

<script>
    function search() {
        $('.input-daterange input').each(function() {
            $(this).datepicker({
                format: 'dd/mm/yyyy'
            });
        });
    }
</script>