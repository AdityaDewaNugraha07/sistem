<?php

use app\models\MCustomer;
use yii\helpers\Url;
use yii\bootstrap\Html;
use app\models\MPegawai;
use app\models\TSpkShipping;
use yii\bootstrap\ActiveForm;
use app\models\TPengajuanPembelianlog;
use yii\helpers\Json;
use yii\web\JqueryAsset;

$this->title = 'Penerimaan Log Alam';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);

$viewMode = false;
function itemRender($index, $label, $name, $checked, $value)
{
    return Html::radio($name, $checked, [
        'value' => $value,
        'label' => $label,
    ]);
}
$area_pembelian = ['Jawa' => 'Jawa', 'Luar Jawa' => 'Luar Jawa'];
$peruntukan = ['Industri' => 'Industri', 'Trading' => 'Trading'];

?>
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <a class="btn blue btn-sm btn-outline pull-right" onclick="openModal(
                                '<?= Url::toRoute(['/ppic/terimalogalam/daftarPenerimaanLogAlam']) ?>', 
                                'modal-daftarPenerimaanLogAlam',
                                '95%'
                            )">
                            <i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Penerimaan Log Alam'); ?>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold">
                                        <h4><?= Yii::t('app', 'Input Penerimaan Log Alam'); ?></h4>
                                    </span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'form-transaksi',
                                        'fieldConfig' => [
                                            'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                                            'labelOptions' => ['class' => 'col-md-4 control-label'],
                                        ],
                                    ]);
                                    echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
                                    <div class="col-md-6">
                                        <?= Html::activeHiddenInput($model, "terima_logalam_id"); ?>
                                        <?= $form->field($model, 'kode')->textInput(['disabled' => true, 'style' => 'font-weight:bold']) ?>
                                        <?= $form->field($model, 'tanggal') ?>
                                        <?= $form->field($model, 'no_truk')->textInput(['class' => 'capitalize form-control', 'maxlength' => 11])->label('NOPOL Truk') ?>
                                        <div class="form-group">
                                            <label for="" class="col-md-4 control-label">No Dokumen</label>
                                            <div class="col-md-7" style="display: flex; justify-content: space-between;align-items: baseline;">
                                                <?php
                                                if (isset($_GET['terima_logalam_id'])) {
                                                    $no_dokumens = explode('.', $model->no_dokumen);
                                                    $kode_partai = $no_dokumens[0];
                                                    $nomor_urut  = end($no_dokumens);
                                                }
                                                ?>
                                                <input type="text" class="form-control capitalize" id="kode_partai" name="kode_partai" onchange="setNoDok()" maxlength="4" value="<?= isset($_GET['terima_logalam_id']) ? $kode_partai : '' ?>" />
                                                <strong style="margin-right: 5px;margin-left: 5px; vertical-align: bottom;">.</strong>
                                                <input type="text" class="form-control" id="nomor_urut" name="nomor_urut" onchange="setNoDok()" oninput="handleNomorUrut(this)" value="<?= isset($_GET['terima_logalam_id']) ? $nomor_urut : '' ?>" />
                                            </div>
                                        </div>
                                        <?= $form->field($model, 'no_dokumen')->hiddenInput()->label(false) ?>
                                        <?= $form->field($model, 'pic_ukur')->dropDownList(MPegawai::getOptionListPicUkur(), ['class' => 'form-control select2', 'prompt' => 'Pilih PIC'])->label('PIC Ukur'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                        echo $form->field($model, 'area_pembelian')
                                            ->inline()
                                            ->radioList($area_pembelian, ['onchange' => 'showKodeKeputusan();', 'item' => "itemRender", 'class' => 'radiolist'])
                                            ->label('Area Pembelian', ['style' => 'padding-top: 3px']);
                                        echo $form->field($model, 'peruntukan')
                                            ->inline()
                                            ->radioList($peruntukan, ['onchange' => 'showLokasiTujuan();', 'item' => "itemRender", 'class' => 'radiolist'])
                                            ->label('Peruntukan', ['style' => 'padding-top: 1px']);
                                        ?>
                                        <div id="kode_keputusan" class="form-group" style="margin-bottom: 5px;">
                                            <label id="label_kode_keputusan" class="col-md-4 control-label"><?= Yii::t('app', 'Kode Keputusan'); ?></label>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <?= Html::activeDropDownList($model, 'pengajuan_pembelianlog_id', TPengajuanPembelianlog::getOptionListPenerimaanLogAlam(), [
                                                        'class' => 'form-control select2',
                                                        'prompt' => 'Ketik Kode Keputusan',
                                                        'onchange' => 'setKeputusanPembelianLog();'
                                                    ]);
                                                    ?>
                                                    <span class="input-group-btn">
                                                        <a id="span_button_kode_keputusan" class="btn btn-icon-only btn-default tooltips" onclick="openDaftarKeputusanPembelianLog()" style="margin-left: 3px; border-radius: 4px;">
                                                            <i class="fa fa-list"></i>
                                                        </a>
                                                        <a id="span_button_lihat_keputusan" class="btn btn-icon-only btn-default tooltips" onclick="infoKeputusan();" style="margin-left: 3px; border-radius: 4px; display:none;">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="kode_spmlog" class="form-group" style="margin-bottom: 5px;">
                                            <label id="label_kode_spm_log" class="col-md-4 control-label text-left"><?= Yii::t('app', 'Kode SPM Log'); ?></label>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <?= Html::activeDropDownList($model, 'spk_shipping_id', TSpkShipping::getOptionList(), [
                                                        'class' => 'form-control select2',
                                                        'prompt' => 'Ketik Kode SPM Log',
                                                        'onchange' => 'setSpmLog();'
                                                    ]);
                                                    ?>
                                                    <span class="input-group-btn">
                                                        <a id="span_button_kode_spm_log" class="btn btn-icon-only btn-default tooltips" onclick="openDaftarSpmLog();" style="margin-left: 3px; border-radius: 4px;">
                                                            <i class="fa fa-list"></i>
                                                        </a>
                                                        <a id="span_button_lihat_spm_log" class="btn btn-icon-only btn-default tooltips" onclick="infoSpmLog();" style="margin-left: 3px; border-radius: 4px; display:none;">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="lokasi_tujuan" class="form-group" style="margin-bottom: 5px;">
                                            <label id="lokasi_tujuan" class="col-md-4 control-label text-left"><?= Yii::t('app', 'Ditujukan'); ?></label>
                                            <div class="col-md-7">
                                                <?php if(!isset($_GET['terima_logalam_id'])){ ?>
                                                    <div class="input-group">
                                                        <?= Html::activeDropDownList($model, 'lokasi_tujuan', MCustomer::getOptionListNama(), [
                                                                                        'class' => 'form-control select2', 'prompt'=>'',
                                                                                        'onchange' => 'setCustAddress();'
                                                                                    ]); ?>
                                                        <span class="input-group-btn">
                                                            <a id="span_button_kode_spm_log" class="btn btn-icon-only btn-default tooltips" onclick="modalCustomer();" style="margin-left: 3px; border-radius: 4px;">
                                                                <i class="fa fa-list"></i>
                                                            </a>
                                                            <a id="span_button_loktujuan" class="btn btn-icon-only btn-default tooltips" onclick="infoPO();" style="margin-left: 3px; border-radius: 4px; display:none;">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </span>
                                                    </div>
                                                <?php } else { 
                                                    if($model->peruntukan == 'Trading' && !isset($_GET['edit'])){
                                                        if (strpos($model->lokasi_tujuan, '-') !== false) {
                                                            $customer = explode("-", $model->lokasi_tujuan);
                                                            $customer = trim($customer[0]);
                                                            $model->lokasi_tujuan = $customer;
                                                        }
                                                        echo Html::activeTextInput($model, "lokasi_tujuan", ['class'=>'form-control', 'disabled'=>'']);
                                                    } else { ?>
                                                        <div class="input-group">
                                                            <?= Html::activeDropDownList($model, 'lokasi_tujuan', ($model->peruntukan=="Industri"?MCustomer::getOptionListNama():MCustomer::getOptionListCustPO()), [
                                                                                            'class' => 'form-control select2', 'prompt'=>'',
                                                                                            'onchange' => 'setCustAddress();'
                                                                                        ]); ?>
                                                            <span class="input-group-btn">
                                                                <a id="span_button_kode_spm_log" class="btn btn-icon-only btn-default tooltips" onclick="modalCustomer();" style="margin-left: 3px; border-radius: 4px;">
                                                                    <i class="fa fa-list"></i>
                                                                </a>
                                                                <a id="span_button_loktujuan" class="btn btn-icon-only btn-default tooltips" onclick="infoPO();" style="margin-left: 3px; border-radius: 4px; display:none;">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    <?php }
                                                } ?>
                                            </div>
                                        </div>
                                        <?= $form->field($model, 'alamat_tujuan')->textarea() ?>
                                        <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>
                                    </div>
                                </div>
                                <div id="detail-terima-row">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                                <h4><?= Yii::t('app', 'Detail Penerimaan Log Alam'); ?></h4>
                                        </div>
                                        <div class="col-md-6" style="position: relative;">
                                            <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'): ?>
                                                <span style="color: red;font-style: italic;position: absolute;bottom: -42px;">*Nomor Lapangan akan di generate ulang. jika anda ingin mempertahankan nomor lapangan, gunakan mode view</span>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                            <span class="spb-info-place pull-right"></span>
                                            <div class="table-scrollable" style="margin-left: 10px;">
                                                <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2" style="font-size: 1.1rem;"><?= Yii::t('app', 'No'); ?></th>
                                                            <th rowspan="2" style="font-size: 1.1rem; min-width: 140px;" class="is-jawa"><?= Yii::t('app', 'Pengajuan Pembelian'); ?></th>
                                                            <th rowspan="2" style="font-size: 1.1rem; min-width: 150px;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                                            <th colspan="3"><?= Yii::t('app', 'Nomor'); ?></th>
                                                            <th colspan="2"><?= Yii::t('app', 'Panjang'); ?></th>
                                                            <th colspan="5"><?= Yii::t('app', 'Diameter (cm)'); ?></th>
                                                            <th colspan="3"><?= Yii::t('app', 'Unsur Cacat (cm)'); ?></th>
                                                            <th rowspan="2" style="width: 80px;"><?= Yii::t('app', 'Vol (m<sup>3</sup>)'); ?></th>
                                                            <th rowspan="2" style="font-size: 1.1rem; width: 50px;"><?= Yii::t('app', 'Status<br>FSC 100%'); ?></th>
                                                            <th rowspan="2" style="width: 50px;">
                                                                <?php if (isset($_GET['view']) && $model->peruntukan === 'Industri') : ?>
                                                                    <a class="btn btn-xs default" id="print-btn-this" onclick="window.open(
                                                                        '<?= Url::toRoute('/ppic/terimalogalam/printall?terima_logalam_id=' . $model->terima_logalam_id . '&caraprint=PRINT') ?>', 
                                                                        'Print Barcode',
                                                                        'width=1200',
                                                                        false,
                                                                        '_blank',
                                                                    )" title="Print Semua"><i class="fa fa-print"></i>
                                                                    </a>
                                                                <?php endif ?>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <!-- <th style="min-width: 90px; font-size: 1.1rem;">
                                                                <?php // Yii::t('app', 'Lap'); 
                                                                ?></th> -->
                                                            <th style="min-width: 90px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Grades'); ?></th>
                                                            <th style="min-width: 90px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Batang'); ?></th>
                                                            <th style="min-width: 90px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Produksi'); ?></th>
                                                            <th style="width: 50px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Kode Potong'); ?></th>
                                                            <th style="width: 50px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Panjang<br>(m)'); ?></th>
                                                            <th style="width: 50px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Ujung1'); ?></th>
                                                            <th style="width: 50px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Ujung2'); ?></th>
                                                            <th style="width: 50px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Pangkal1'); ?></th>
                                                            <th style="width: 50px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Pangkal2'); ?></th>
                                                            <th style="width: 50px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Rata<sup>2</sup>'); ?></th>
                                                            <th style="width: 50px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'Panjang'); ?></th>
                                                            <th style="width: 50px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'GB'); ?></th>
                                                            <th style="width: 50px; font-size: 1.1rem;">
                                                                <?= Yii::t('app', 'GR'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>

                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        echo Html::button('<i class="fa fa-plus-square" aria-hidden="true"></i> Penerimaan', ['onclick' => 'addItem()', 'class' => 'btn btn-outline blue pull-left']);
                        echo Html::submitButton('Save', ['class' => 'btn hijau btn-outline ciptana-spin-btn ladda-button pull-right']);
                        echo Html::button('Reset', ['id' => 'btn-reset', 'class' => 'btn grey-gallery btn-outline ciptana-spin-btn pull-right margin-right-10', 'onclick' => 'resetForm();']);
                        ActiveForm::end();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pick-panel"></div>
<?php
$this->registerJs("init();");
// $this->registerJsFile("@web/themes/metronic/global/plugins/jquery-resizable-columns/dist/jquery.resizableColumns.js", ['depends' => [JqueryAsset::class]]);
// $this->registerCssFile("@web/themes/metronic/global/plugins/jquery-resizable-columns/dist/jquery.resizableColumns.css");
$this->registerCss("
.item-data {
    text-align: center;
    padding: 2px;
    font-size: 10px;
}

.item-input {
    padding: 2px;
    font-size: 13px;
    height: 25px;
}
");
$lokasiIndustri = MCustomer::getOptionListNama();
$lokasiTrading = MCustomer::getOptionListCustPO(); 
?>
<script>
    function init() {
        <?php
        if (isset($_GET['terima_logalam_id'])) {
            if (isset($_GET['edit'])) {
                echo ("showDetail('edit');");
                if (strpos($model->lokasi_tujuan, '-') !== false){
                    echo ("setCustAddress();");
                }
            }

            if (isset($_GET['view'])) {
                echo ("
                showDetail();
                $(':input').each(function() {
                    if($(this).attr('id') !== 'btn-reset') {
                        $(this).prop('disabled', true);
                    }
                });
                $('#span_button_kode_spm_log').addClass('disabled').off('click');
            ");
            }

            if (!empty($model->pengajuan_pembelianlog_id)) {
                echo ("
                $('#kode_keputusan').show();
                $('#kode_spmlog').hide();
                $('.is-jawa').hide();"
                );
            }

            if (!empty($model->spk_shipping_id)) {
                echo ("
                $('#kode_keputusan').hide();
                $('#kode_spmlog').show();
                $('.is-jawa').show();
            ");
            }
        } else {
            echo ("
            $('#kode_keputusan').hide();
            $('#kode_spmlog').show();
            $('.is-jawa').show();
        ");
        }
        ?>

        // console.log(JSON.parse('<?= Json::encode($model->attributes) ?>'));
        formconfig();

        // $("#table-detail").resizableColumns();

        $('.capitalize').each(function(i, e) {
            $(e).on('keyup', function() {
                $(e).val($(e).val().toUpperCase());
            });
        });

        $('.select2').each(function(i, e) {
            $(this).select2({
                placeholder: $(e).find('option').first().text(),
                width: null
            })
        });
        $('#form-transaksi').on('beforeSubmit', submit);
        // $('#<?= Html::getInputId($model, 'lokasi_tujuan')?>').select2({placeholder: 'Cari Customer'});
    }

    function submit() {
        const that = this;
        let master = '';
        let detail = '';
        let masterObj = {};
        $(that).find(':input').each(function(i, e) {
            if ($(e).is(':visible') || $(e).attr('type') === 'hidden') {
                if ($(e).attr('name') === '<?= Html::getInputName($model, 'area_pembelian') ?>') {
                    masterObj[$(e).attr('name')] = $('input[name="<?= Html::getInputName($model, 'area_pembelian') ?>"]:checked').val();
                } else if ($(e).attr('name') === '<?= Html::getInputName($model, 'peruntukan') ?>') {
                    masterObj[$(e).attr('name')] = $('input[name="<?= Html::getInputName($model, 'peruntukan') ?>"]:checked').val();
                } else {
                    masterObj[$(e).attr('name')] = $(e).val();
                }
            }
        });

        for (let key in masterObj) {
            master += `&${encodeURIComponent(key)}=${encodeURIComponent(masterObj[key])}`;
        }

        $('#table-detail tbody > tr').each(function(itr, tr) {
            $(':input', tr).each(function(iinput, input) {
                detail +=
                    `&${encodeURIComponent($(input).attr('name'))}=${encodeURIComponent($(input).val())}`;
            })
        })

        if (!detail.length) {
            cisAlert('Detail penerimaan belum diisi');
            return false;
        }

        <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'): ?>
            const confirmEdit = confirm('Nomor Lapangan akan di generate ulang. jika anda yakin silahkan pililh "OK"');
            if(!confirmEdit) {
                return false;
            }
        <?php endif ?>
        // console.log(detail);
        // return false;
        if (validatingDetail()) {
            $.ajax({
                url: '<?= Url::toRoute('/ppic/terimalogalam/index') ?>',
                type: 'POST',
                data: master.substring(1) + detail,
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.status) {
                        window.location.href = '<?= Url::toRoute(['/ppic/terimalogalam/index', 'terima_logalam_id' => '']); ?>' + res.data.terima_logalam_id + '&view=1'
                    }
                    cisAlert(res.message);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }

        return false;
    }

    async function addItem(param = {}) {
        $('#table-detail > tbody').addClass('animation-loading');
        const tr = $('#table-detail tbody tr');
        const kayu_id = tr.find(':input[name*="kayu_id"]').val();
        const spk_shipping_id = $('#<?= Html::getInputId($model, 'spk_shipping_id') ?>').val();
        const area_pembelian = $('input[name="<?= Html::getInputName($model, 'area_pembelian') ?>"]:checked').val();
        const peruntukan = $('input[name="<?= Html::getInputName($model, 'peruntukan') ?>"]:checked').val();
        const pengajuan_pembelianlog_id = tr.last().find(':input[name*="pengajuan_pembelianlog_id"]').val();
        const next_nomor = tr.length ? tr.length + 1 : 1;

        const fsc = tr.last().find('input[type="checkbox"][name*="fsc"]').val(); // TAMBAH FSC

        if (area_pembelian === 'Luar Jawa' && spk_shipping_id === '') {
            cisAlert('Kode SPM Log belum dipilih');
            return;
        }

        /* let data = {
                kayu_id: kayu_id,
                pengajuan_pembelianlog_id: pengajuan_pembelianlog_id,
                area_pembelian: area_pembelian,
                spk_shipping_id: spk_shipping_id,
                peruntukan: peruntukan,
                next_nomor: param.next_nomor ? param.next_nomor : next_nomor,
            } 
        */

        // TAMBAH FSC - diubah untuk menentukan checkbox status fsc
        if(area_pembelian === 'Luar Jawa'){
            var data = {
                kayu_id: kayu_id,
                pengajuan_pembelianlog_id: pengajuan_pembelianlog_id,
                area_pembelian: area_pembelian,
                spk_shipping_id: spk_shipping_id,
                peruntukan: peruntukan,
                next_nomor: param.next_nomor ? param.next_nomor : next_nomor,
                fsc:fsc 
            };
        } else {
            var data = {
                kayu_id: kayu_id,
                pengajuan_pembelianlog_id: $('#<?= Html::getInputId($model, 'pengajuan_pembelianlog_id') ?>').val(), // agar tdk null, ambil dari field pengajuan_pembelianlog_id
                area_pembelian: area_pembelian,
                spk_shipping_id: spk_shipping_id,
                peruntukan: peruntukan,
                next_nomor: param.next_nomor ? param.next_nomor : next_nomor,
                fsc:fsc
            }
        }
        // eo FSC

        data = {
            ...data,
            ...param
        }
        const html = await requestAddItem(data);
        $(html).hide().appendTo('#table-detail tbody').fadeIn(200, function() {
            reordertable('#table-detail');
            $('#table-detail > tbody').removeClass('animation-loading');
        });
    }

    function showDetail(mode) {
        const details = JSON.parse('<?= Json::encode($modDetail) ?>');
        let type = {}
        if (mode === 'edit') {
            type = {
                edit: true
            }
        } else {
            type = {
                view: true
            }
        }

        if (Object.keys(details).length) {
            let no = 1;
            for (let data of details) {
                addItem({
                    next_nomor: no,
                    terima_logalam_detail_id: data.terima_logalam_detail_id,
                    ...type
                })
                no++;
            }
        }
    }

    function requestAddItem(data) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: '<?= Url::toRoute(['/ppic/terimalogalam/addItem']); ?>',
                type: 'POST',
                data:  data,
                async: false,
                success: function(data) {
                    // if (data.html) {
                    //     $(data.html).hide().appendTo('#table-detail tbody').fadeIn(200, function() {
                    //         // $("#table-detail > tbody").find('input[name*="[diameter_rata]"]').attr('readonly', true);
                    //         // $("#table-detail > tbody").find('input[name*="[volume]"]').attr('readonly', true);
                    //         reordertable('#table-detail');
                    //     });
                    // }
                    if (data.html) {
                        resolve(data.html);
                    }

                    if($('input[name="<?= Html::getInputName($model, 'area_pembelian') ?>"]:checked').val() == 'Luar Jawa'){
                        $("#span_button_lihat_spm_log").css('display', '');
                        $("#span_button_lihat_keputusan").css('display', 'none');
                    } else {
                        $("#span_button_lihat_spm_log").css('display', 'none');
                        $("#span_button_lihat_keputusan").css('display', '');
                    }
                },
                error: function(jqXHR) {
                    reject(jqXHR);
                    getdefaultajaxerrorresponse(jqXHR);
                },
            });
        })
    }

    function validatingDetail() {
        let valid = false;
        $('#table-detail tbody > tr').each(function(itr, tr) {
            $(':input', tr).each(function(iinput, input) {
                const excepts = ['no_barcode', 'terima_logalam_id', 'terima_logalam_detail_id'];
                let next = true;
                excepts.forEach(function(v) {
                    if ($(input).attr('name').includes(v)) {
                        next = false;
                    }
                });

                if (next) {
                    if ($(input).val() === '') {
                        $(input).parents('td').addClass('error-tb-detail');
                        valid = false;
                    } else {
                        $(input).parents('td').removeClass('error-tb-detail');
                        valid = true;
                    }
                }
            })
        })

        return valid;
    }

    // SPM LOG
    const openDaftarSpmLog = () => openModal('<?= Url::toRoute(['/ppic/terimalogalam/daftarSpmLog']); ?>', 'modal-daftarSpmLog');

    function setSpmLog() {
        var spk_shipping_id = $('#<?= Html::getInputId($model, "spk_shipping_id") ?>').val();
        $.ajax({
            url: '<?= Url::toRoute(['/ppic/terimalogalam/findSpmLog']); ?>',
            type: 'POST',
            data: {
                spk_shipping_id: spk_shipping_id
            },
            success: function(data) {
                if (data.length > 0) {
                    $("#modal-master").find('button.fa-close').trigger('click');
                    $("#table-detail > tbody").empty();
                    showDetail('edit');
                    $("#span_button_lihat_spm_log").css('display', '');
                    $("#span_button_lihat_keputusan").css('display', 'none');
                } else {
                    $("#span_button_lihat_spm_log").css('display', 'none');
                }
            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function pickDaftarSpmLog(spk_shipping_id, kode) {
        $("#modal-daftarSpmLog").find('button.fa-close').trigger('click');
        $("#<?= Html::getInputId($model, "spk_shipping_id") ?>")
            .empty()
            .append('<option value="' + spk_shipping_id + '">' + kode + '</option>').val(spk_shipping_id).trigger('change');
    }
    // eo SPM LOG !!!

    // PENGAJUAN PEMBELIAN LOG !!! 
    function openDaftarKeputusanPembelianLog() {
        var url = '<?= Url::toRoute(['/ppic/terimalogalam/daftarKeputusanPembelianLog']); ?>';
        $(".modals-place-3-min").load(url, function() {
            $("#modal-daftarKeputusanPembelianLog .modal-dialog").css('width', '90%');
            $("#modal-daftarKeputusanPembelianLog").modal('show');
            $("#modal-daftarKeputusanPembelianLog").on('hidden.bs.modal', function() {});
            spinbtn();
            draggableModal(1);
        });
    }

    function setKeputusanPembelianLog() {
        var pengajuan_pembelianlog_id = $('#<?= Html::getInputId($model, "pengajuan_pembelianlog_id") ?>').val();
        $.ajax({
            url: '<?= Url::toRoute(['/ppic/terimalogalam/findPengajuanPembelianLog']); ?>',
            type: 'POST',
            data: {
                pengajuan_pembelianlog_id: pengajuan_pembelianlog_id
            },
            success: function(data) {
                if (data.length > 0) {
                    $("#modal-master").find('button.fa-close').trigger('click');
                    $("#table-detail > tbody").empty();
                    showDetail('edit');
                    $("#span_button_lihat_keputusan").css('display', '');
                    $("#span_button_lihat_spm_log").css('display', 'none');
                } else {
                    $("#span_button_lihat_keputusan").css('display', 'none');
                }
            },
            error: function(jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function pickDaftarKeputusanPembelianLog(pengajuan_pembelianlog_id, kode) {
        $("#modal-daftarKeputusanPembelianLog").find('button.fa-close').trigger('click');
        $("#<?= Html::getInputId($model, "pengajuan_pembelianlog_id") ?>").empty().append('<option value="' +
            pengajuan_pembelianlog_id + '">' + kode + '</option>').val(pengajuan_pembelianlog_id).trigger('change');
    }
    // EO PENGAJUAN PEMBELIAN LOG !!!

    function hitungRata(obj, parent = 'tr') {
        var ujung1 = parseInt($(obj).parents(parent).find('input[name*="[diameter_ujung1]"]').val());
        var ujung2 = parseInt($(obj).parents(parent).find('input[name*="[diameter_ujung2]"]').val());
        var pangkal1 = parseInt($(obj).parents(parent).find('input[name*="[diameter_pangkal1]"]').val());
        var pangkal2 = parseInt($(obj).parents(parent).find('input[name*="[diameter_pangkal2]"]').val());

        // SEMUA PERHITUNGAN DIANGGAP PEMBELIAN DARI JAWA DAN DIAMETER DIANGGAP 4 !!!
        // diameter 4
        //D rata = ROUND((diamater ujung + diamater ujung + diameter pangkal + diameter pangkal) /4 ;0)
        var ratarata = Math.round(((ujung1 + ujung2 + pangkal1 + pangkal2) / 4));
        $(obj).parents(parent).find('input[name*="[diameter_rata]"]').val(ratarata);

        hitungVolume(obj);
    }

    function hitungVolume(obj, parent = 'tr') {
        var panjang = $(obj).parents(parent).find('input[name*="[panjang]"]').val();
        var ratarata = $(obj).parents(parent).find('input[name*="[diameter_rata]"]').val();
        var cacat_panjang = $(obj).parents(parent).find('input[name*="[cacat_panjang]"]').val();
        var cacat_gb = $(obj).parents(parent).find('input[name*="[cacat_gb]"]').val();
        var cacat_gr = $(obj).parents(parent).find('input[name*="[cacat_gr]"]').val();

        panjang == '' ? panjang = 0 : panjang = parseFloat(panjang);
        ratarata == '' ? ratarata = 0 : ratarata = parseFloat(ratarata);
        cacat_panjang == '' ? cacat_panjang = 0 : cacat_panjang = parseFloat(cacat_panjang);
        cacat_gb == '' ? cacat_gb = 0 : cacat_gb = parseFloat(cacat_gb);
        cacat_gr == '' ? cacat_gr = 0 : cacat_gr = parseFloat(cacat_gr);

        // SEMUA PERHITUNGAN DIANGGAP PEMBELIAN DARI JAWA dAN DIAMETER 4 !!!
        // Prosentase Growong (GR) = ROUND((0.7854  x cacat_gr(GR) x cacat_gr(GR) x (panjang - panjang cacat/100)) / 10000 ; 2)
        // Vol = ROUND( 0.7854 x (panjang - (panjang cacat/100)) x ((D rata - Gubal/100) x (D rata – (Gubal/100) ) x 1) /10000) - (Prosentase Growong (GR)) ; 2)
        var pGrowong = (0.7854 * cacat_gr * cacat_gr * (panjang - (cacat_panjang / 100)) / 10000).toFixed(2);
        pGrowong == '' ? pGrowong = 0 : pGrowong = pGrowong;
        var zzz = (0.7854 * (panjang - (cacat_panjang / 100)) * ((ratarata - cacat_gb) * (ratarata - (cacat_gb)) * 1) / 10000) - (pGrowong);
        // var Vol = ((zzz * 100) / 100).toFixed(2);
        var Vol = zzz.toFixed(2);
        $(obj).parents(parent).find('input[name*="[volume]"]').val(Vol);
    }

    function showKodeKeputusan() {
        var area_pembelian = ($('input[name*="[area_pembelian]"]:checked').val());
        if (area_pembelian == 'Jawa') {
            $('#kode_keputusan').show();
            $('#kode_spmlog').hide();
            $('select[name*="[spk_shipping_id]"]').val('').trigger('change');
            $('.is-jawa').hide();
            $("#table-detail > tbody").empty();
            showDetail('edit');
        } else {
            $('#kode_keputusan').hide();
            $('#kode_spmlog').show();
            $('select[name*="[pengajuan_pembelianlog_id]"]').val('').trigger('change');
            $('.is-jawa').show();
            $("#table-detail > tbody").empty();
            showDetail('edit');
        }
    }

    function showLokasiTujuan() {
        var lokasiIndustri = <?= json_encode(MCustomer::getOptionListNama()) ?>;
        var lokasiTrading = <?= json_encode(MCustomer::getOptionListCustPO()) ?>;
        const peruntukan = $('input[name*="[peruntukan]"]:checked').val();
        const select = $('#<?= Html::getInputId($model, 'lokasi_tujuan') ?>');
        const alamat = $('#<?= Html::getInputId($model, 'alamat_tujuan') ?>');
        select.empty();

        var data = peruntukan === 'Industri' ? lokasiIndustri : lokasiTrading;
        $.each(data, function(value, text) {
            select.append(new Option(text, value));
        });

        if (peruntukan === 'Industri') {
            select.val('PT. CIPTA WIJAYA MANDIRI').trigger('change');
            alamat.val('Jl. Raya Semarang - Purwodadi Km 16.5 No. 349 Mranggen Demak Jawa Tengah 59567');
        } else {
            select.val('').trigger('change');
            alamat.val('');
        }
        console.log(select.val());
    }

    /**function showLokasiTujuan() {
        var peruntukan = ($('input[name*="[peruntukan]"]:checked').val());
        if (peruntukan == 'Industri') {
            $('#<?= Html::getInputId($model, 'lokasi_tujuan') ?>').val('PT. CIPTA WIJAYA MANDIRI').trigger('change');
            $('#<?= Html::getInputId($model, 'alamat_tujuan')?>').val('Jl. Raya Semarang - Purwodadi Km 16.5 No. 349 Mranggen Demak Jawa Tengah 59567');
        } else {
            $('#<?= Html::getInputId($model, 'lokasi_tujuan') ?>').val(null).trigger('change');
            $('#<?= Html::getInputId($model, 'alamat_tujuan')?>').val(null);
        }
    }*/

    function cancelItemThisUI(ele) {
        $(ele).parents('tr').fadeOut(200, function() {
            var kubikasi = $("#table-detail > tbody > tr:last").find('input[name*="[volume_value]"]').val();
            $(this).remove();
            reordertable('#table-detail');
        });
    }

    function cancelItemThis(ele, terima_logalam_detail_id) {
        if (terima_logalam_detail_id == '') {
            cancelItemThisUI(ele);
        } else {
            $.ajax({
                url: '<?= Url::toRoute(['/ppic/terimalogalam/cekTerimaLogalamPabrik']); ?>',
                type: 'POST',
                data: {
                    terima_logalam_detail_id
                },
                success: function(data) {
                    if (data > 0) {
                        cisAlert("Data sudah diterima oleh pabrik !<br>Refresh ulang halaman ini !");
                        window.location.reload();
                    } else {
                        openModal('<?= Url::toRoute(['/ppic/terimalogalam/deleteItemDetail', 'id' => '']) ?>' + terima_logalam_detail_id, 'modal-confirm', '250px', function(res) {
                            cancelItemThisUI(ele);
                        });
                    }

                },
                error: function(jqXHR) {
                    getdefaultajaxerrorresponse(jqXHR);
                },
            });
        }
    }

    function setNoDok() {
        const kode_partai = $('#kode_partai').val();
        const no_urut = $('#nomor_urut').val();
        const no_dok = `${kode_partai}.${no_urut.padStart(7, '0')}`;
        $('#<?= Html::getInputId($model, 'no_dokumen') ?>').val(no_dok);
    }

    function handleKodePotong(e) {
        const no_btg = $(e).parents('tr').find('input[name*="no_btg"]');
        const arrVal = no_btg.val().split('.');

        if (['01', '02', '03'].includes(arrVal[arrVal.length - 1])) {
            arrVal.splice(-1);
            no_btg.val(arrVal.join(''));
        }

        if ($(e).val() !== '-') {
            no_btg.val(no_btg.val() + '.' + $(e).val());
        } else {
            no_btg.val(arrVal[0]);
        }
    }

    function handleNomorUrut(e) {
        let value = $(e).val();
        if ($.isNumeric(value)) {
            let paddedValue = ("0000000" + value).slice(-7);
            $(e).val(paddedValue);
        }
    }

    function numericInput(e) {
        var inputValue = $(e).val();
        $(e).val(inputValue.replace(/[^0-9]/g, ''));
    }

    function modalCustomer() {
        var peruntukan = ($('input[name*="[peruntukan]"]:checked').val());
        if (peruntukan == 'Industri') {
            var url = '<?= Url::toRoute(['/ppic/terimalogalam/daftarcustomer']); ?>';
        } else {
            var url = '<?= Url::toRoute(['/ppic/terimalogalam/daftarcustomerPO']); ?>';
        }
        openModal(url, 'modal-daftar-customer', '85%');
        
    }

    function pickCustomer(customer, alamat, kode) {
        if(kode){
            var lokasi_tujuan = customer + ' - ' + kode;
            $('#<?= Html::getInputId($model, 'lokasi_tujuan')?>').val(lokasi_tujuan).trigger('change');
        } else {
            $('#<?= Html::getInputId($model, 'lokasi_tujuan')?>').val(customer).trigger('change');
        }
        $('#<?= Html::getInputId($model, 'alamat_tujuan')?>').val(alamat);
        $("#modal-daftar-customer").modal('toggle');
    }

    function setCustAddress() {
        const customer = $('#<?= Html::getInputId($model, 'lokasi_tujuan')?>').val();
        if(customer) {
            $.ajax({
                url: "<?= Url::toRoute('/ppic/terimalogalam/setcustaddress')?>",
                type: "POST",
                data: {customer: customer},
                success: function(res) {
                    $('#<?= Html::getInputId($model, 'alamat_tujuan')?>').val(res);
                }
            });
            if($('input[name="<?= Html::getInputName($model, 'peruntukan') ?>"]:checked').val() == 'Trading'){
                var cust = customer.split('-');
                var kode = cust[1].trim();
                $('#span_button_loktujuan').css('display', '');
                $('#span_button_loktujuan').attr("onclick","infoPO('"+kode+"');");
            } else {
                $('#span_button_loktujuan').css('display', 'none');
            }
        } else {
            if($('input[name="<?= Html::getInputName($model, 'peruntukan') ?>"]:checked').val() == 'Trading'){
                $('#span_button_loktujuan').css('display', 'none');
            } else {
                $('#span_button_loktujuan').css('display', 'none');
            }
        }
    }

    // TAMBAH FSC - set checkbox saat area pembelian = 'Luar Jawa'
    function setCheckboxfsc(ele){
        var id = $(ele).val();
        if(id){
            $.ajax({
                url: "<?= Url::toRoute('/ppic/terimalogalam/setcheckboxfsc')?>",
                type: "POST",
                data: {id: id},
                success: function(data) {
                    if(data.fsc){
                        $(ele).closest('tr').find('input[name*="fsc"]').prop('checked', true);
                    } else {
                        $(ele).closest('tr').find('input[name*="fsc"]').prop('checked', false);
                    }
                    $(ele).closest('tr').find('input[name*="fsc"]').val(data.value).trigger('change');
                }
            })
        }        
    }
    // eo FSC

    function infoSpmLog(){    
        var id = $('#<?= Html::getInputId($model, 'spk_shipping_id')?>').val();
        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/terimalogalam/infoSpmLog','id'=>'']) ?>'+id,'modal-info');
    }

    function infoPO(kode){
        var url = '<?= \yii\helpers\Url::toRoute(['/ppic/terimalogalam/infoPO','kode'=>'']); ?>'+kode;
        $(".modals-place-2").load(url, function() {
            $("#modal-info").modal('show');
            $("#modal-info").on('hidden.bs.modal', function () { });
            spinbtn();
            draggableModal();
        });
    }

    function infoKeputusan(){
        var id = $('#<?= Html::getInputId($model, 'pengajuan_pembelianlog_id')?>').val();
        openModal('<?= \yii\helpers\Url::toRoute(['/ppic/terimalogalam/infoKeputusan','id'=>'']) ?>'+id,'modal-info');
    }
</script>