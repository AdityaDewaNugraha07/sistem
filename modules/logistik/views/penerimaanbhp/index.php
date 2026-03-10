<?php
/* @var $this yii\web\View */

use app\components\Params;
use app\models\TSpl;
use app\models\TSpo;
use yii\bootstrap\Html;

$this->title = 'Penerimaan Barang';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Penerimaan Bahan Pembantu'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-terima-bhp',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions' => ['class' => 'col-md-4 control-label'],
    ],
]);
echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <div class="col-md-12">
                        <a class="btn blue btn-sm btn-outline pull-right" onclick="daftarTerimaBhp()"><i
                                    class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Penerimaan'); ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Penerimaan'); ?></h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:void(0)" class="reload"> </a>
                                    <a href="javascript:void(0)" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php
                                        if (!isset($_GET['terima_bhp_id'])) {
                                            echo $form->field($model, 'terimabhp_kode')->textInput(['disabled' => 'disabled', 'style' => 'font-weight:bold']);
                                        } else { ?>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode Penerimaan'); ?></label>
                                                <div class="col-md-7" style="padding-bottom: 5px;">
													<span class="input-group-btn" style="width: 90%">
														<?= Html::activeTextInput($model, 'terimabhp_kode', ['class' => 'form-control', 'style' => 'width:100%']) ?>
													</span>
                                                    <span class="input-group-btn" style="width: 10%">
														<a class="btn btn-icon-only btn-default tooltips"
                                                           data-original-title="Copy to Clipboard"
                                                           onclick="copyToClipboard('<?= $model->terimabhp_kode ?>');">
															<i class="icon-paper-clip"></i>
														</a>
													</span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="form-group field-tterimabhp-tglterima required"
                                             style='margin-bottom: 5px;'>
                                            <?php
                                            $tglterima = $model->tglterima;
                                            //echo "tglterima = ".$tglterima;
                                            isset($tglterima) ? $tanggal_terima = $tglterima : $tanggal_terima = date('d/m/Y');
                                            //echo "tanggal_terima = ".$tanggal_terima;
                                            ?>
                                            <label class='col-md-4 control-label'>Tanggal Terima</label>
                                            <div class='col-md-7' style='height: 27px; padding-top: 3px;'>
                                                <span><?php echo $tanggal_terima; ?></span>
                                                <input type='hidden' id='tterimabhp-tglterima'
                                                       name='TTerimaBhp[tglterima]' class='form-control col-md-4'
                                                       value='<?php echo $tanggal_terima; ?>'>
                                            </div>
                                        </div>

                                        <?php /*<?= $form->field($model, 'tglterima',[
											'template'=>'{label}<div class="col-md-8"><div class="input-group input-medium date date-picker" data-date-start-date="-0d" data-date-end-date="+0d">{input} <span class="input-group-btn">
														 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
														 {error}</div>'])->textInput(['readonly'=>'readonly', 'value'=>date('d/m/Y')]); ?> */ ?>
                                        <?= $form->field($model, 'pegawaipenerima')->dropDownList(\app\models\MPegawai::getOptionListByDept(107), ['class' => 'form-control select2', 'prompt' => '']); ?>

                                        <?php /*<div class="form-group field-tterimabhp-tanggal_checker required" style='margin-bottom: 5px;'>
											<label class='col-md-4 control-label'>Tanggal Checker</label>
											<div class='col-md-7' style='height: 27px; padding-top: 3px;'><span><?php echo date('d/m/Y');?></span>
												<input type='hidden' id='tterimabhp-tanggal_checker' name='TTerimaBhp[tanggal_checker]' class='form-control col-md-4' value='<?php echo date('d/m/Y');?>'>
											</div>
										</div>*/ ?>

                                        <?= $form->field($model, 'tanggal_jam_checker', [
                                            'template' => '{label}<div class="col-md-8"><div class="input-group input-medium date time-picker">{input} <span class="input-group-btn">
														 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-clock-o"></i></button></span></div> 
														 {error}</div>'])->textInput(['readonly' => 'readonly']); ?>

                                        <?= $form->field($model, 'pegawai_checker')->dropDownList(\app\models\MPegawai::getOptionListByDept(115), ['class' => 'form-control select2', 'prompt' => '']); ?>

                                        <?php if ((isset($_GET['terima_bhp_id'])) && ($model->cancel_transaksi_id == NULL)) { ?>
                                            <div class="form-group">
                                                <?php
                                                //echo $model->status_approval;
                                                if (trim($model->status_approval) == 'REJECTED') {
                                                    ?>
                                                    <label class="col-md-4 control-label"><a href="javascript:void(0);"
                                                                                             onclick=""
                                                                                             class="btn red btn-sm"><i
                                                                    class="fa fa-minus-circle"></i> REJECTED</a></label>
                                                    <div class="col-md-7" style="margin-top:7px;">
                                                        <?php
                                                        $jsons = $model->reject_reason;
                                                        $json = json_decode($jsons);

                                                        foreach ($json as $key) {
                                                            $sql_pegawai_nama = "select pegawai_nama from m_pegawai where pegawai_id = '" . $key->by . "' ";
                                                            $pegawai_nama = Yii::$app->db->createCommand($sql_pegawai_nama)->queryScalar();
                                                            echo "<font style='font-size: 11px; color: #f00;'>" . $pegawai_nama;
                                                            echo " : " . $key->reason . "</font>";
                                                            echo "<br>";
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                } else if (trim($model->status_approval) == 'APPROVED' || trim($model->status_approval) == 'ALLOWED') {
                                                    ?>
                                                    <label class="col-md-4 control-label"><a href="javascript:void(0);"
                                                                                             onclick=""
                                                                                             class="btn btn-sm"
                                                                                             style='background-color: #06cc06; color: #fff;'><i
                                                                    class="fa fa-check-circle"></i> <?php echo $model->status_approval; ?>
                                                        </a></label>
                                                    <div class="col-md-7" style="margin-top:7px;">
                                                        <!--pembatasan menu akses-->
                                                        <a href="javascript:void(0);"
                                                           onclick="cancelTerima(<?= $model->terima_bhp_id ?>);"
                                                           class="btn red btn-sm <?= (Yii::$app->user->identity->user_group_id == Params::USER_GROUP_ID_SUPER_USER) ? "" : "hidden"; ?>"><i
                                                                    class="fa fa-minus-circle"></i> <?= Yii::t('app', 'Batalkan Penerimaan'); ?>
                                                        </a>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <label class="col-md-4 control-label"><a href="javascript:void(0);"
                                                                                             onclick=""
                                                                                             class="btn btn-sm"
                                                                                             style='background-color: darkgray; color: #fff;'><i
                                                                    class="fa fa-clock-o"></i> <?php echo $model->status_approval; ?>
                                                        </a></label>
                                                    <div class="col-md-7" style="margin-top:7px;">
                                                        <a href="javascript:void(0);" onclick="" class="btn btn-sm"
                                                           style='background-color: darkgray; color: #fff;'><i
                                                                    class="fa fa-clock-o"></i> Menunggu Approval</a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <?php if (trim($model->status_approval) <> 'ALLOWED') { ?>
                                                    <div class="col-md-7" style="margin-top:7px;font-size:10px;">
                                                        <?php
                                                        $modApproval = \app\models\TApproval::findAll(['reff_no' => $model->terimabhp_kode]);
                                                        foreach ($modApproval as $modApproval_id) {
//                                                                                                                $modAssined = \app\models\MPegawai::findOne(['pegawai_id'=>$modApproval_id->assigned_to]);

                                                            if ($modApproval_id->status == 'ABORTED') {
                                                                $modAssined = \app\models\ViewUser::findOne(['user_id' => $modApproval_id->updated_by]);
                                                            } else {
                                                                $modAssined = \app\models\MPegawai::findOne(['pegawai_id' => $modApproval_id->assigned_to]);
                                                            }

                                                            if ($modApproval_id->status == 'Not Confirmed') {
                                                                $textstyle = "style='background-color: darkgray; color: #fff;font-size:10px;'";
                                                            } elseif ($modApproval_id->status == 'APPROVED') {
                                                                $textstyle = "style='background-color: green; color: #fff;font-size:10px;'";
                                                            } elseif ($modApproval_id->status == 'REJECTED') {
                                                                $textstyle = "style='background-color: red; color: #fff;font-size:10px;'";
                                                            } elseif ($modApproval_id->status == 'Aborted') {
                                                                $textstyle = "style='background-color: red; color: #fff;font-size:10px;'";
                                                            } else {
                                                                $textstyle = "";
                                                            }


                                                            if ($modApproval_id->approved_by <> '') {
                                                                $tglapprove = "at: " . $modApproval_id->updated_at;
                                                            } else {
                                                                $tglapprove = "";
                                                            }

                                                            ?>
                                                            <?= $modAssined->pegawai_nama ?>
                                                            <h <?= $textstyle ?> > <?= $modApproval_id->status ?><?php echo $tglapprove; ?> </h>
                                                            <br>

                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <?php if ($model->cancel_transaksi_id != null) { ?>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', ''); ?></label>
                                                <div class="col-md-7" style="margin-top:7px;">
                                                    <span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
                                                    <?php
                                                    $modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
                                                    echo "<span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena " . $modCancel->cancel_reason . "</span>";
                                                    ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'nofaktur', ['template' => '{label}<div class="col-md-7">
                                                                                    <span class="input-group-btn">{input}</span> 
                                                                                    <span class="input-group-btn">' .
                                            Html::activeTextInput($model, 'no_fakturpajak', ['class' => 'form-control', 'placeholder' => 'No. Faktur']) . '</span> {error}</div>'])
                                            ->textInput(['placeholder' => 'No. Invoice', ''])->label("No. Invoice / No. Faktur"); ?>
                                        <?= $form->field($model, 'no_suratjalan')->textInput( ['placeholder' => 'No. Surat Jalan']); ?>
                                        <?php if (!isset($_GET['terima_bhp_id'])) { ?>
                                            <?= $form->field($model, 'suplier_id')->dropDownList([\app\models\MSuplier::getOptionListPo('BHP')], ['class' => 'form-control select2', 'prompt' => '', 'onchange' => 'setDropdownPO(); setDropdownSPL()']); ?>
                                        <?php } else { ?>
                                            <?= $form->field($model, 'suplier_id')->dropDownList([\app\models\MSuplier::getOptionListPo('BHP')], ['class' => 'form-control select2', 'prompt' => '']); ?>
                                            <?php
                                                if(!empty($model->spo_id)){
                                                    if((Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_PCH) || (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER)){ ?>
                                                        <div class="row">
                                                            <label class="col-md-4 control-label">Bank</label>
                                                            <div class="col-md-7">
                                                                <?= $model->suplier->suplier_bank; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label class="col-md-4 control-label">Nomor Rekening</label>
                                                            <div class="col-md-7">
                                                                <?= $model->suplier->suplier_norekening; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label class="col-md-4 control-label">Atas Nama</label>
                                                            <div class="col-md-7">
                                                                <?= $model->suplier->suplier_an_rekening; ?>
                                                            </div>
                                                        </div>
                                        <?php       }   
                                                }
                                            } ?>
                                        <?= $form->field($model, 'terimabhp_keterangan', ['inputOptions' => ['id' => 'keterangan_textarea']])->textarea(['placeholder' => 'Keterangan penerimaan barang']); ?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"></label>
                                            <div class="col-md-7">
                                                <span id="status_terima_bhp" style="display: none; color: #E93D4A;">Keterangan tidak boleh kosong.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        <h4><?= Yii::t('app', 'Detail Penerimaan'); ?></h4>
                                    </div>
                                    <div class="col-md-7">
                                        <?php if (!isset($_GET['terima_bhp_id'])) { ?>
                                            <label class="col-md-4"
                                                   style="margin-top:5px; text-align: right;"><?= Yii::t('app', 'Load Penerimaan :'); ?></label>
                                            <div class="col-md-8">
                                                <span class="input-group-btn">
													<?= yii\bootstrap\Html::activeDropDownList($model, 'spo_id', [], ['prompt' => '-- Pilih PO --', 'class' => 'form-control', 'style' => 'margin-top:10px; width: 180px', 'onchange' => 'getItemDariSPO()']) ?>
                                                    <input type="hidden" id='tglspo' value="">
                                                </span>
                                                <span class="input-group-btn">
													<?= yii\bootstrap\Html::activeDropDownList($model, 'spl_id', [], ['prompt' => '-- Pilih SPL --', 'class' => 'form-control', 'style' => 'margin-top:10px; width: 180px', 'onchange' => 'getItemDariSPL()']) ?>
                                                    <input type="hidden" id='tglspl' value="">
                                                </span>
                                                <span style="font-size: 1.03rem;" class="font-red-mint"
                                                      id="place-tbpexist"></span>
                                            </div>
                                        <?php } else {
                                            echo Html::activeHiddenInput($model, 'spo_id');
                                            echo Html::activeHiddenInput($model, 'spl_id');
                                        } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right" style="text-align: right;">
                                            <?php
                                            if (isset($_GET['terima_bhp_id'])) {
                                                if (!empty($model->spo_id)) {

                                                    
                                                    echo "Kode PO : <b><a onclick='infoSPO(" . $model->spo_id . "," . $_GET['terima_bhp_id'] . ")'>" . $model->spo->spo_kode . "</a></b><br>";
                                                    echo "PO Dibuat : <b>" . \app\components\DeltaFormatter::formatDateTimeForUser2($model->spo->created_at) . "</a></b><br>";
                                                    echo '<span class="spo-info-place"></span>';
                                                    $modspo = TSpo::findOne($model->spo_id);
                                                    echo '<input type="hidden" id="tglspo" value="'. $modspo->spo_tanggal .'">';

                                                    $modApproval = \app\models\TApproval::findAll(['reff_no' => $model->spo->spo_kode]);
                                                    foreach ($modApproval as $modApproval_id) {
                                                        if ($modApproval_id->status == 'ABORTED') {
                                                            $modAssined = \app\models\ViewUser::findOne(['user_id' => $modApproval_id->updated_by]);
                                                        } else {
                                                            $modAssined = \app\models\MPegawai::findOne(['pegawai_id' => $modApproval_id->assigned_to]);
                                                        }
                                                        if ($modApproval_id->status == 'Not Confirmed') {
                                                            $textstyle = "style='background-color: darkgray; color: #fff;font-size:10px;'";
                                                        } elseif ($modApproval_id->status == 'APPROVED') {
                                                            $textstyle = "style='background-color: green; color: #fff;font-size:10px;'";
                                                        } elseif ($modApproval_id->status == 'REJECTED') {
                                                            $textstyle = "style='background-color: red; color: #fff;font-size:10px;'";
                                                        } elseif ($modApproval_id->status == 'ABORTED') {
                                                            $textstyle = "style='background-color: red; color: #fff;font-size:10px;'";
                                                        } else {
                                                            $textstyle = "";
                                                        }

                                                        if ($modApproval_id->approved_by <> '') {
                                                            $tglapprove = "at: " . \app\components\DeltaFormatter::formatDateTimeForUser2($modApproval_id->updated_at);
                                                        } else {
                                                            $tglapprove = "";
                                                        }
                                                        ?>
                                                        <h <?= $textstyle ?> > <?= $modApproval_id->status ?></h>
                                                        <h style='font-size:10px;'>
                                                            By <?= $modAssined->pegawai_nama ?></h>
                                                        <b class='text-primary'><?php echo $tglapprove; ?></b>

                                                        <?php
                                                    }

                                                }
                                                if (!empty($model->spl_id)) {
                                                    echo "Kode SPL : <b><a onclick='infoSPL(" . $model->spl_id . "," . $_GET['terima_bhp_id'] . ")'>" . $model->spl->spl_kode . "</a></b><br>";
                                                    echo '<span class="spl-info-place"></span>';
                                                    $modspl = TSpl::findOne($model->spl_id);
                                                    echo '<input type="hidden" id="tglspl" value="'. $modspl->spl_tanggal .'">';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        if (isset($_GET['terima_bhp_id'])) {
                                            if (empty($model->voucher_pengeluaran_id)) {
                                                ?>
                                                <span class="btn-group-updateharga">
												<?php
                                                if (($model->status_approval == "APPROVED" || $model->status_approval == "ALLOWED") && $model->cancel_transaksi_id == null) {
                                                    ?>
                                                    <a class="btn yellow btn-sm btn-outline" onclick="editHarga()"
                                                       id="btn-edit-hargarealisasi"><i
                                                                class="fa fa-edit"></i> <?= Yii::t('app', 'Update Harga'); ?></a>
                                                    <?php
                                                }
                                                ?>
												<a class="btn hijau btn-sm btn-outline" onclick="saveEditHarga()"
                                                   id="btn-save-hargarealisasi" style="display: none;"><i
                                                            class="fa fa-checklist"></i> <?= Yii::t('app', 'Update'); ?></a>
												<a class="btn red btn-sm btn-outline" onclick="cancelEditHarga()"
                                                   id="btn-cancel-hargarealisasi" style="display: none;"><i
                                                            class="fa fa-cancel"></i> <?= Yii::t('app', 'Cancel'); ?></a>
											</span>
                                                <?php
                                            } else {
                                                $modVoucher = app\models\TVoucherPengeluaran::findOne($model->voucher_pengeluaran_id);
                                                echo "<b>" . $modVoucher->status_bayar . "</b><br><span style='font-size:1.1rem'>" . \app\components\DeltaFormatter::formatDateTimeForUser2($modVoucher->tanggal_bayar) . "</span>";
                                            }
                                            ?>
                                            <?php
                                        }
                                        ?>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover"
                                                   id="table-detail">
                                                <thead>
                                                <tr>
                                                    <th style="width: 30px; vertical-align: middle; text-align: center;">
                                                        No.
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center; width: 250px;"><?= Yii::t('app', 'Nama Item'); ?></th>
                                                    <th class="header-spo"
                                                        style="width: 60px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Qty PO'); ?></th>
                                                    <th class="header-spl"
                                                        style="width: 60px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Qty SPL'); ?></th>
                                                    <th style="width: 100px; text-align: center;  vertical-align: middle;"><?= Yii::t('app', 'Qty'); ?></th>
                                                    <th class="header-spo"
                                                        style="width: 60px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Satuan'); ?></th>
                                                    <th class="header-spo"
                                                        style="width: 180px; vertical-align: middle;"><?= Yii::t('app', 'Harga'); ?>
                                                        <span class="place-mata-uang" style="font-size: 1.2rem;"></span>
                                                    </th>
                                                    <th class="header-spl"
                                                        style="display: none; width: 80px; vertical-align: middle; font-size: 1.2rem;"><?= Yii::t('app', 'Harga<br> Estimasi'); ?>
                                                        <span class="place-mata-uang" style="font-size: 1.2rem;"></span>
                                                    </th>
                                                    <th class="header-spl"
                                                        style="display: none; width: 80px; vertical-align: middle; font-size: 1.2rem;"><?= Yii::t('app', 'Harga<br> Realisasi'); ?>
                                                        <span class="place-mata-uang" style="font-size: 1.2rem;"></span>
                                                    </th>
                                                    <th style="width: 160px; vertical-align: middle; text-align: center;"><?= Yii::t('app', 'Sub Total'); ?>
                                                        <span class="place-mata-uang" style="font-size: 1.2rem;"></span>
                                                    </th>
                                                    <th class="header-spl"
                                                        style="display: none; width: 120px; vertical-align: middle;"><?= Yii::t('app', 'PPN'); ?>
                                                        <span class="place-mata-uang" style="font-size: 1.2rem;"></span>
                                                    </th>
                                                    <th class=""
                                                        style="width: 120px; vertical-align: middle;"><?= Yii::t('app', 'Pph'); ?>
                                                        <span class="place-mata-uang" style="font-size: 1.2rem;"></span>
                                                    </th>
                                                    <th class="header-spl"
                                                        style="display: none; width: 130px; vertical-align: middle; font-size: 1.2rem;"><?= Yii::t('app', 'Suplier'); ?></th>
                                                    <th class=""
                                                        style="vertical-align: middle; text-align: center;"><?php echo Yii::t('app', 'Keterangan'); ?></th>
                                                    <th style="width: 20px; vertical-align: middle; text-align: center;"><?= Yii::t('app', ''); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                <?php $hide = '';
                                                if (Yii::$app->user->identity->pegawai->departement_id === Params::DEPARTEMENT_ID_LOGISTIC) {
                                                    $hide = 'none';
                                                } ?>
                                                <tr>
                                                    <td colspan="7"
                                                        style="text-align: right; font-weight: 500;  vertical-align: middle;">
                                                        &nbsp;<span> Total </span></td>
                                                    <td style="padding: 3px"
                                                        colspan="2">
                                                        <?= yii\bootstrap\Html::textInput('total', 0, ['class' => 'form-control float', 'style' => 'width:100%; font-style: bold; padding:3px;display:' . $hide, 'disabled' => 'disabled', 'id' => 'total']); ?>
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <!-- <tr>
														<td colspan="7" style="text-align: right; font-weight: 500; vertical-align: middle;">&nbsp; <?= Yii::t('app', 'Potongan Harga (Rp)'); ?></td>
														<td style="padding-left: 8px; padding-right: 8px; padding: 3px 8px;">
															<?php // echo yii\bootstrap\Html::activeTextInput($model,'potonganharga',['value'=>0,'class'=>'form-control money-format','style'=>'width:100%; font-style: bold;','onblur'=>'setTotal()']); ?>
														</td>
														<td colspan="2"></td>
													</tr>-->
                                                <tr>
                                                    <td colspan="7"
                                                        style="text-align: right; font-weight: 500; vertical-align: middle;">
                                                        <span class="span-include-ppn"></span> 
                                                        <!-- PPN <span id="label_ppn"></span> -->
                                                         <?= Yii::t('app', 'PPN'); ?>
                                                    </td>
                                                    <td style="padding: 3px;" colspan="2">
                                                        <?php
                                                        $is_pkp = false;
                                                        if (!empty($model->spo_id)) {
                                                            $is_pkp = ($model->spo->spo_is_pkp) ? true : false;
                                                        }
                                                        ?>
                                                        <?= yii\bootstrap\Html::hiddenInput('is_pkp', $is_pkp, ['id' => 'is_pkp']); ?>
                                                        <?= yii\bootstrap\Html::activeHiddenInput($model, 'is_ppn'); ?>
                                                        <?= yii\bootstrap\Html::activeTextInput($model, 'ppn_nominal', ['value' => 0, 'class' => 'form-control float', 'style' => 'width:100%; font-style: bold; padding:3px;display:' . $hide, 'onblur' => 'setTotal(true)', 'disabled' => TRUE]); ?>
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7"
                                                        style="text-align: right; font-weight: 500; vertical-align: middle;"> <?= Yii::t('app', 'Pph'); ?>
                                                        <span id="pphtotalpersen"></span></td>
                                                    <td style="padding: 3px;" colspan="2">
                                                        <?= yii\bootstrap\Html::activeTextInput($model, 'totalpph', ['value' => 0, 'class' => 'form-control text-right', 'style' => 'width:100%; font-style: bold; padding:3px;display:' . $hide, 'disabled' => TRUE]); ?>
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7"
                                                        style="text-align: right; font-weight: 500; vertical-align: middle;"> <?= Yii::t('app', 'PBBKB'); ?>
                                                        <span id="pphtotalpersen"></span></td>
                                                    <td style="padding: 3px;" colspan="2">
                                                        <?= yii\bootstrap\Html::activeTextInput($model, 'total_pbbkb', ['value' => 0, 'class' => 'form-control float', 'style' => 'width:100%; font-style: bold; padding:3px;display:' . $hide, 'disabled' => TRUE, 'onblur' => 'setTotal()']); ?>
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7"
                                                        style="text-align: right; font-weight: 500; vertical-align: middle;">
                                                        <?= Yii::t('app', 'Biaya Tambahan') ?>
                                                        </td>
                                                    <td style="padding: 3px;" colspan="2">
                                                        <?= yii\bootstrap\Html::activeTextInput($model, 'total_biayatambahan', ['value' => 0, 'class' => 'form-control float', 'style' => 'width:100%; font-style: bold; padding:3px;display:' . $hide, 'disabled' => TRUE, 'onblur' => 'setTotal()']); ?>
                                                    </td>
                                                    <td colspan="2">
                                                        <?= yii\bootstrap\Html::activeTextInput($model, 'label_biayatambahan', ['class' => 'form-control pull-right', 'placeholder' => 'Contoh: Materai, dll.', 'style' => 'display:' . $hide, 'disabled' => TRUE]); ?>
                                                        <span id="pphtotalpersen"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7"
                                                        style="text-align: right; font-weight: 500; vertical-align: middle;">
                                                        <?= Yii::t('app', 'Potongan Harga') ?>
                                                    </td>
                                                    <td style="padding: 3px;" colspan="2">
                                                        <?= Html::activeTextInput($model, 'potonganharga', ['value' => 0, 'class' => 'form-control float', 'style' => 'width:100%; font-style: bold; padding:3px;display:' . $hide, 'disabled' => TRUE, 'onblur' => 'setTotal()']) ?>
                                                    </td>
                                                    <td colspan="2">
                                                        <?= Html::activeTextInput($model, 'label_potonganharga', ['class' => 'form-control pull-right', 'placeholder' => 'Contoh: Diskon, dll.', 'style' => 'display:' . $hide, 'disabled' => TRUE]) ?>
                                                        <span id="label_potonganharga"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" style="text-align: right; vertical-align: middle;">
                                                        &nbsp;<span> <?= Yii::t('app', 'Total Bayar'); ?> </span></td>
                                                    <td style="padding: 3px;" colspan="2">
                                                        <?= yii\bootstrap\Html::activeTextInput($model, 'totalbayar', ['value' => 0, 'class' => 'form-control float', 'style' => 'width:100%; font-style: bold; padding:3px;display:' . $hide, 'disabled' => 'disabled']); ?>
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Save'), ['id' => 'btn-save', 'class' => 'btn hijau btn-outline ciptana-spin-btn', 'onclick' => 'save();']); ?>
                                <?php
                                if (isset($_GET['terima_bhp_id'])) {
                                    $disabled = FALSE;
                                } else {
                                    $disabled = TRUE;
                                }
                                ?>
                                <?php //echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>(($disabled==FALSE)? 'printout('.(isset($_GET['terima_bhp_id'])?$_GET['terima_bhp_id']:'').')' :''),'disabled'=>$disabled]); ?>
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Print Rincian'), ['id' => 'btn-print-rincian', 'class' => 'btn blue btn-outline ciptana-spin-btn', 'onclick' => (($disabled == FALSE) ? 'printRincian(' . (isset($_GET['terima_bhp_id']) ? $_GET['terima_bhp_id'] : '') . ')' : ''), 'disabled' => $disabled]); ?>
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Reset'), ['id' => 'btn-reset', 'class' => 'btn grey-gallery btn-outline ciptana-spin-btn', 'onclick' => 'resetForm();']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .popover {
        width: 2000px;
        max-width: 60%
    }

    .popover-table th, td {
        padding: 0px 15px;
        white-space: nowrap;
    }
</style>

<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if (isset($_GET['terima_bhp_id'])) {
    $pagemode = "afterSave()";
} else {
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	formconfig();
    $pagemode;
	$('select[name*=\"[pegawaipenerima]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih pegawai logistik',
	});	$('select[name*=\"[pegawai_checker]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih pegawai security',
	});
	$('select[name*=\"[spo_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik kode PO',
	});
	$('select[name*=\"[spl_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Ketik kode SPL',
	});
	$('select[name*=\"[spo_id]\"]').siblings('span').click(function(){
		$('#" . yii\bootstrap\Html::getInputId($model, 'spl_id') . "').val('').trigger('change'); 
	});
	$('select[name*=\"[spl_id]\"]').siblings('span').click(function(){
		$('#" . yii\bootstrap\Html::getInputId($model, 'spo_id') . "').val('').trigger('change'); 
	});
	$('select[name*=\"[suplier_id]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Supplier',
		ajax: {
			url: '" . \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/findSupplier') . "',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		}
	});
	$('#tterimabhp-tanggal_jam_checker').datetimepicker({
		autoclose: !0,
		isRTL: App.isRTL(),
		format: 'dd/mm/yyyy hh:ii',
		fontAwesome: !0,
		pickerPosition: App.isRTL() ? 'bottom-right' : 'bottom-left',
		orientation: 'left',
		clearBtn:true,
		todayHighlight:true,
		minuteStep: 1,
		todayBtn: true,
		//startDate: new Date(new Date().setDate(new Date().getDate() - 30)),
		endDate: new Date(new Date().setDate(new Date().getDate() + 0))
	});
	setDropdownSPL();
    $('#" . yii\bootstrap\Html::getInputId($model, 'no_fakturpajak') . "').inputmask({'mask': '999.999-99.999999999'});
", yii\web\View::POS_READY); ?>
<script>
    // 2020-07-09 jika input penerimaan barang terlamat, keterangan wajib diisi
    function cekStatusTerimaBarang() {
        // hari ini
        var datetime = new Date();
        var year = datetime.getFullYear();
        var month = datetime.getMonth() + 1;
        var date = datetime.getDate();
        var day = datetime.getDay();
        var hours = datetime.getHours();
        var minutes = datetime.getMinutes();
        var seconds = datetime.getSeconds();
        var today = new Intl.DateTimeFormat(['ban', 'id'], {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        }).format(datetime);

        // hari terima barang
        var tanggal_jam_checker = $('#tterimabhp-tanggal_jam_checker').val();
        var tanggal_jam_checker = tanggal_jam_checker.split(' ');
        var tanggal_checker = tanggal_jam_checker[0];
        var ymd_checker = tanggal_checker.split('/');
        var yy_checker = ymd_checker[2];
        var mm_checker = ymd_checker[1];
        var dd_checker = ymd_checker[0];
        var jam_menit_checker = tanggal_jam_checker[1];
        var jam_menit_checker = jam_menit_checker.split(':');
        var j_checker = jam_menit_checker[0];
        var m_checker = jam_menit_checker[1];

        // hitung hari ini - tanggal checker
        var todayx = today.match(/(\d{2})\/(\d{2})\/(\d{4})/);
        var todayy = Date.UTC(+todayx[3], todayx[2] - 1, +todayx[1]);
        var todayz = year + '-' + month + '-' + date;
        var tanggal_checkerx = tanggal_checker.match(/(\d{2})\/(\d{2})\/(\d{4})/);
        var tanggal_checkery = Date.UTC(+tanggal_checkerx[3], tanggal_checkerx[2] - 1, +tanggal_checkerx[1]);
        var tanggal_checkerz = yy_checker + '-' + mm_checker + '-' + dd_checker;
        var days = (todayy - tanggal_checkery) / (1000 * 3600 * 24);

        // hitung jumlah minggu
        // https://stackoverflow.com/questions/37407418/how-to-count-the-number-of-sundays-between-two-dates
        var start = new Date(tanggal_checkerz);
        var finish = new Date(todayz);
        var dayMilliseconds = 1000 * 60 * 60 * 24;
        var jumlah_minggu = 0;
        while (start.getTime() <= finish.getTime()) {
            var day = start.getDay();
            if (day == 0) {
                jumlah_minggu++;
            }
            start = new Date(+start + dayMilliseconds);
        }

        // hitung hari minggu
        // jika barang masuk hari sabtu diatas jam 13, hari minggu nggak usah dihitung cuy
        if (day == 6 && j_checker >= 13 && m_checker > 0) {
            var hitung_hari = days - hari_minggu;
        } else if (day == 0) {
            var hari_minggu = 1;
            var hitung_hari = days - jumlah_minggu + hari_minggu;
        } else {
            var hitung_hari = days - jumlah_minggu;
        }

        // BUAT APPROVAL JIKA BARANG DITERIMA KEMAREN TAPI INPUTNYA KE CIS DIATAS JAM 10
        // hitung hari
        // tambah attribut required pada kolom textarea
        // https://stackoverflow.com/questions/17381043/how-to-dynamically-add-required-attribute-to-textarea-tag-using-jquery
        // https://stackoverflow.com/questions/5059596/jquery-css-remove-add-displaynone
        if (hitung_hari >= 2) {
            $('#status_terima_bhp').show();
            //alert('\n11 \nday '+day+'\nj_checker '+j_checker+'\nm_checker '+m_checker+'\nhitung_hari '+hitung_hari);
            var telat = true;
        } else if (hitung_hari >= 1 && (hours >= 10 && minutes >= 01)) {
            $('#status_terima_bhp').show();
            //alert('\n22 \nday '+day+'\nj_checker '+j_checker+'\nm_checker '+m_checker+'\nhitung_hari '+hitung_hari);
            var telat = true;
        } else {
            $('#tterimabhp-terimabhp_keterangan').prop('required', false);
            $('#status_terima_bhp').hide();
            //alert('\n22 \nday '+day+'\nj_checker '+j_checker+'\nm_checker '+m_checker+'\nhitung_hari '+hitung_hari);
            var telat = false;
        }

        return telat;

    }

    function editHarga() {
        $('#btn-edit-hargarealisasi').attr('style', 'display:none');
        $('#btn-save-hargarealisasi').attr('style', 'display:');
        $('#btn-cancel-hargarealisasi').attr('style', 'display:');
        $('#<?= Html::getInputId($model, 'suplier_id') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'nofaktur') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'no_fakturpajak') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'no_suratjalan') ?>').removeAttr('disabled');

        <?php if(!empty($model->spo_id)){ ?>
        getItemDariSPO();
        $('#<?= Html::getInputId($model, 'ppn_nominal') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'total_pbbkb') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'total_biayatambahan') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'label_biayatambahan') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'potonganharga') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'label_potonganharga') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'totalbayar') ?>').removeAttr('disabled').attr('readonly', 'readonly');
        <?php }else{ ?>
        $('#<?= Html::getInputId($model, 'total_biayatambahan') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'label_biayatambahan') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'potonganharga') ?>').removeAttr('disabled');
        $('#<?= Html::getInputId($model, 'label_potonganharga') ?>').removeAttr('disabled');
        $('#table-detail > tbody > tr').each(function () {
            $(this).find('input[name*="terimabhpd_harga"]').removeAttr('disabled');
            $(this).find('input[name*="is_ppn_peritem"]').removeAttr('disabled');
            $(this).find('input[name*="is_pph_peritem"]').removeAttr('disabled');
            $(this).find('select[name*="nofaktur"]').removeAttr('disabled');
            $(this).find('select[name*="no_fakturpajak"]').removeAttr('disabled');
            $(this).find('select[name*="no_suratjalan"]').removeAttr('disabled');
            $(this).find('select[name*="suplier_id"]').removeAttr('disabled');
            $(this).find('select[name*=\"[suplier_id]\"]').select2({
                allowClear: !0,
                placeholder: 'Pilih Supplier',
                ajax: {
                    url: '<?= \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/findSupplier') ?>',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
            $(this).find('.select2-selection').css('font-size', '1.1rem');
            $(this).find('textarea[name*="terimabhpd_keterangan"]').removeAttr('disabled');
        });
        <?php } ?>
    }

    function cancelEditHarga() {
        var terima_bhp_id = '<?= (isset($_GET['terima_bhp_id']) ? $_GET['terima_bhp_id'] : '') ?>';
        getItemsByTerimaBhp(terima_bhp_id);
        setTimeout(function () {
            $('#btn-edit-hargarealisasi').attr('style', 'display:');
            $('#btn-save-hargarealisasi').attr('style', 'display:none');
            $('#btn-cancel-hargarealisasi').attr('style', 'display:none');
            $('#<?= Html::getInputId($model, 'suplier_id') ?>').attr('disabled', 'disabled');
            $('#<?= Html::getInputId($model, 'nofaktur') ?>').attr('disabled', 'disabled');
            $('#<?= Html::getInputId($model, 'no_fakturpajak') ?>').attr('disabled', 'disabled');
            $('#<?= Html::getInputId($model, 'no_suratjalan') ?>').attr('disabled', 'disabled');
            $('#<?= Html::getInputId($model, 'ppn_nominal') ?>').attr('disabled', 'disabled');
            $('#<?= Html::getInputId($model, 'total_pbbkb') ?>').attr('disabled', 'disabled');
            $('#<?= Html::getInputId($model, 'total_biayatambahan') ?>').attr('disabled', 'disabled');
            $('#<?= Html::getInputId($model, 'label_biayatambahan') ?>').attr('disabled', 'disabled');
            $('#<?= Html::getInputId($model, 'potonganharga') ?>').attr('disabled', 'disabled');
            $('#<?= Html::getInputId($model, 'label_potonganharga') ?>').attr('disabled', 'disabled');
            $('#<?= Html::getInputId($model, 'totalbayar') ?>').attr('disabled', 'disabled').removeAttr('readonly');
            $('#table-detail > tbody > tr').each(function () {
                $(this).find('input[name*="terimabhpd_harga"]').attr('disabled', 'disabled');
                $(this).find('textarea[name*="terimabhpd_keterangan"]').attr('disabled');
                $(this).find('input[name*="is_ppn_peritem"]').attr('disabled', 'disabled');
                $(this).find('input[name*="is_pph_peritem"]').attr('disabled', 'disabled');
                $(this).find('input[name*="[ppn_peritem]"]').attr('disabled', 'disabled');
                $(this).find('input[name*="[pph_peritem]"]').attr('disabled', 'disabled');
                $(this).find('select[name*="suplier_id"]').attr('disabled', 'disabled');
                $(this).find('input[name*="nofaktur"]').attr('disabled', 'disabled');
                $(this).find('input[name*="no_fakturpajak"]').attr('disabled', 'disabled');
                $(this).find('input[name*="no_suratjalan"]').attr('disabled', 'disabled');
                $(this).find('input[name*="terimabhpd_qty"]').attr('disabled', 'disabled');
                $(this).find('textarea[name*="terimabhpd_keterangan"]').attr('disabled', 'disabled');
                $(this).find('#btn-cancel-item').remove();
            });
        }, 300)

    }

    function saveEditHarga() {
        $('#btn-edit-hargarealisasi').attr('style', 'display:');
        $('#btn-save-hargarealisasi').attr('style', 'display:none');
        $('#btn-cancel-hargarealisasi').attr('style', 'display:none');
        $('.btn-group-updateharga').addClass('animation-loading');
        $('#table-detail > tbody > tr').each(function () {
            var unformatted_harga = unformatNumber($(this).find('input[name*="terimabhpd_harga"]').val());
            var unformatted_ppn = unformatNumber($(this).find('input[name*="[ppn_peritem]"]').val());
            var unformatted_pph = unformatNumber($(this).find('input[name*="[pph_peritem]"]').val());
            $(this).find('input[name*="terimabhpd_harga"]').val(unformatted_harga);
            $(this).find('input[name*="[ppn_peritem]"]').val(unformatted_ppn);
            $(this).find('input[name*="[ppn_peritem]"]').removeAttr('disabled', 'disabled');
            $(this).find('input[name*="[pph_peritem]"]').val(unformatted_pph);
            $(this).find('input[name*="[pph_peritem]"]').removeAttr('disabled', 'disabled');
        });
        var terima_bhp_id = '<?= (isset($_GET['terima_bhp_id']) ? $_GET['terima_bhp_id'] : '') ?>';
        var totalbayar = unformatNumber($('#<?= Html::getInputId($model, 'totalbayar') ?>').val());
        var total_pbbkb = unformatNumber($('#<?= Html::getInputId($model, 'total_pbbkb') ?>').val());
        var total_biayatambahan = unformatNumber($('#<?= Html::getInputId($model, 'total_biayatambahan') ?>').val());
        var label_biayatambahan = $('#<?= Html::getInputId($model, 'label_biayatambahan') ?>').val();
        var ppn_nominal = unformatNumber($('#<?= Html::getInputId($model, 'ppn_nominal') ?>').val());
        var pph_nominal = unformatNumber($('#<?= Html::getInputId($model, 'pph_nominal') ?>').val());
        var suplier_id = $('#<?= Html::getInputId($model, 'suplier_id') ?>').val();
        var nofaktur = $('#<?= Html::getInputId($model, 'nofaktur') ?>').val();
        var no_fakturpajak = $('#<?= Html::getInputId($model, 'no_fakturpajak') ?>').val();
        var no_suratjalan = $('#<?= Html::getInputId($model, 'no_suratjalan') ?>').val();
        var potonganharga = unformatNumber($('#<?= Html::getInputId($model, 'potonganharga') ?>').val());
        var label_potonganharga = $('#<?= Html::getInputId($model, 'label_potonganharga') ?>').val();
        <?php if($model->spl_id){ ?>
        $.ajax({
            url: '<?php echo \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/updateHargaRealisasi']); ?>',
            type: 'POST',
            data: {
                terima_bhp_id: terima_bhp_id,
                formdata: $('form').serialize(),
                totalbayar: totalbayar,
                total_pbbkb: total_pbbkb,
                total_biayatambahan: total_biayatambahan,
                label_biayatambahan: label_biayatambahan,
                suplier_id: suplier_id,
                ppn_nominal: ppn_nominal,
                pph_nominal: pph_nominal,
                nofaktur: nofaktur,
                no_fakturpajak: no_fakturpajak,
                no_suratjalan: no_suratjalan,
                potonganharga,
                label_potonganharga
            },
            success: function (data) {
                if (data.message) {
                    cisAlert(data.message);
                    setTotal();
                    cancelEditHarga();
                    $('.btn-group-updateharga').removeClass('animation-loading');
                }
            },
            error: function (jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
        <?php }else{ ?>
        $.ajax({
            url: '<?php echo \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/updateHargaRealisasi']); ?>',
            type: 'POST',
            data: {
                terima_bhp_id: terima_bhp_id,
                suplier_id: suplier_id,
                formdata: $('form').serialize(),
                totalbayar: totalbayar,
                total_pbbkb: total_pbbkb,
                total_biayatambahan: total_biayatambahan,
                label_biayatambahan: label_biayatambahan,
                ppn_nominal: ppn_nominal,
                nofaktur: nofaktur,
                no_fakturpajak: no_fakturpajak,
                no_suratjalan: no_suratjalan,
                potonganharga,
                label_potonganharga
            },
            success: function (data) {
                if (data.message) {
                    cisAlert(data.message);
                    cancelEditHarga();
                    $('.btn-group-updateharga').removeClass('animation-loading');
                }
            },
            error: function (jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
        <?php } ?>
    }

    function setDropdownPO() {
        $('#table-detail tbody').html("");
//	checkTBP(null,null,null,null);
        checkTBP(null, null, null, null, null);
        checkApproval(null, null);
        setTotal();
        $('#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').parents('.input-group-btn').addClass('animation-loading');
        var suplier_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
        $.ajax({
            url: '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/setDropdownPO']); ?>',
            type: 'POST',
            data: {suplier_id: suplier_id},
            success: function (data) {
                if (data) {
                    $('#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').html(data.html);
                    $('#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').parents('.input-group-btn').removeClass('animation-loading');

                    $('#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').on('select2:open', function (e) {
                        setTimeout(function () {
                            $('#select2-tterimabhp-spo_id-results').find('li').each(function () {
                                var li = $(this);
                                var asd = $(this).attr('id');
                                var fr_el = asd.split('-')[5];
                                $(data.spo).each(function () {
                                    var fr_db = $(this)[0].spo_id;
                                    if (fr_el == fr_db) {
                                        $(li).attr('style', 'color:red');
                                    }
                                });
                            });
                        }, 500);
                    });
                }
            },
            error: function (jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function setDropdownSPL() {
        $('#table-detail tbody').html("");
        checkTBP(null, null, null, null);
        setTotal();
        $('#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').parents('.input-group-btn').addClass('animation-loading');
        var suplier_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
        $.ajax({
            url: '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/setDropdownSPL']); ?>',
            type: 'POST',
            data: {suplier_id: suplier_id},
            success: function (data) {
                if (data) {
                    $('#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').html(data.html);
                    $('#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').parents('.input-group-btn').removeClass('animation-loading');
                    if (data.spl) {
                        $('#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').on('select2:open', function (e) {
                            setTimeout(function () {
                                $('#select2-tterimabhp-spl_id-results').find('li').each(function () {
                                    var li = $(this);
                                    var asd = $(this).attr('id');
                                    var fr_el = asd.split('-')[5];
                                    $(data.spl).each(function () {
                                        var fr_db = $(this)[0].spl_id;
                                        if (fr_el == fr_db) {
                                            $(li).attr('style', 'color:red');
                                        }
                                    });
                                });
                            }, 500);
                        });
                    }
                }
            },
            error: function (jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function getItemDariSPO() {
        $('#table-detail').addClass('animation-loading');
        $('.header-spl').hide();
        $('.header-spo').show();
        $('#table-detail tfoot tr').each(function () {
            $(this).find('td:first').attr('colspan', '6');
        });
        $('.span-include-ppn').html("");
        var spo_id = $("#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>").val();
        var terima_bhp_id = '<?= isset($_GET['terima_bhp_id']) ? $_GET['terima_bhp_id'] : '' ?>';
        $.ajax({
            url: '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/getItemDariSPO']); ?>',
            type: 'POST',
            data: {spo_id: spo_id, terima_bhp_id: terima_bhp_id},
            success: function (data) {
                if (data) {
                    $('#table-detail tbody').html(data.html);
                    //$("#<?php //= yii\bootstrap\Html::getInputId($model, 'potonganharga') ?>//").val(0);
                    //checkTBP(null,null,null);
//				checkTBP(null,null,null,null);
                    checkTBP(null, null, null, null, null);
                    checkApproval(null, null);
                    if (data.modSpo) {
                        if (data.modSpo.spo_is_pkp === true) {
                            $('#is_pkp').val(1);
                        } else {
                            $('#is_pkp').val(0);
                        }
                        if (data.modSpo.spo_is_ppn === true) {
//						$('.span-include-ppn').html("Include");
                            $('#<?= Html::getInputId($model, 'is_ppn') ?>').val(1);
                        } else {
//						$('.span-include-ppn').html("Exclude");
                            $('#<?= Html::getInputId($model, 'is_ppn') ?>').val(0);
                        }
                        $("#<?= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>").val(formatInteger(data.modSpo.spo_ppn_nominal));
                        $('#tglspo').val(data.modSpo.spo_tanggal);

                        if (data.tbp) {
                            //checkTBP(data.modSpo.spo_kode,data.tbp.terimabhp_kode,data.modSpo.terima_bhp_id);
//						checkTBP(data.modSpo.spo_kode,data.tbp.terimabhp_kode,data.modSpo.terima_bhp_id,data.modSpo.status_approval);
                            checkTBP(data.modSpo.spo_kode, data.tbp.terimabhp_kode, data.modSpo.terima_bhp_id, data.modSpo.status_approval, data.modSpo.spo_id);
                        } else {
                            checkApproval(data.modSpo.spo_id, data.modSpo.spo_kode);
                        }

                        $('.place-mata-uang').html("(" + data.modSpo.name_en + ")");
                    } else {
                        $("#<?= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>").val(0);
                    }
                    setTotal();
//				if(data.modSpo){
//					if(data.modSpo.suplier_id){
//						$("#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>").val(data.modSpo.suplier_id).trigger('change');
//					}else{
//						$("#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>").val(null).trigger('change');
//					}
//				}else{
//					$("#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>").val(null).trigger('change');
//				}
                    $('#table-detail').removeClass('animation-loading');
                    reordertable('#table-detail');

                }
            },
            error: function (jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
        return false;
    }

    function getItemDariSPL() {
        $('#table-detail').addClass('animation-loading');
        $('.header-spl').show();
        $('.header-spo').hide();
        $('#table-detail tfoot tr').each(function () {
            $(this).find('td:first').attr('colspan', '6')
        });
        $('.span-include-ppn').html("");
        $('#<?= Html::getInputId($model, 'is_ppn') ?>').val(0);
        var spl_id = $("#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>").val();
        $.ajax({
            url: '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/getItemDariSPL']); ?>',
            type: 'POST',
            data: {spl_id: spl_id},
            success: function (data) {
                checkTBP(null, null, null);
                if (data) {
                    $('#table-detail tbody').html(data.html);
                    $('#table-detail > tbody > tr').each(function () {
                        $(this).find('select[name*=\"[suplier_id]\"]').select2({
                            allowClear: !0,
                            placeholder: 'Pilih Supplier',
                            ajax: {
                                url: '<?= \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/findSupplier') ?>',
                                dataType: 'json',
                                delay: 250,
                                processResults: function (data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: true
                            }
                        });
                        $(this).find('.select2-selection').css('font-size', '1.1rem');
                    });
                    $('#tglspl').val(data.modSpl.spl_tanggal);
                    setTotal();
                    $("#<?= yii\bootstrap\Html::getInputId($model, 'potonganharga') ?>").val(0);
                    formatNumberAll();
                    $('#table-detail').removeClass('animation-loading');
                    reordertable('#table-detail');
//				$('.span-include-ppn').html("NON");
                    if (data.tbp) {
                        checkTBP(data.modSpl.spl_kode, data.tbp.terimabhp_kode, data.modSpl.terima_bhp_id);
                    }
                }
            },
            error: function (jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
        return false;
    }

    function setSubtotal(ele) {
        var elestr = $(ele).attr('id');
        $('#table-detail tbody tr').each(function (index) {
            var qty = unformatNumber($(this).find('input[name*="[terimabhpd_qty]"]').val());
            var harga = unformatNumber($(this).find('input[name*="[terimabhpd_harga]"]').val());
            var subtotal = qty * harga;

            if (elestr.indexOf("diskon_rp") != -1) {
                var diskon_rp = unformatNumber($(this).find('input[name*="[diskon_rp]"]').val())
                var diskon_persen = ((diskon_rp / qty) / harga) * 100;
            } else {
                var diskon_persen = unformatNumber($(this).find('input[name*="[terimabhpd_diskon]"]').val());
                var diskon_rp = (diskon_persen / 100) * subtotal;
            }

            var subtotal_afterdisc = subtotal - diskon_rp;
            $(this).find('input[name*="[terimabhpd_diskon]"]').val(diskon_persen);
            $(this).find('input[name*="[diskon_rp]"]').val(formatInteger(diskon_rp));
            $(this).find('input[name*="[subtotal]"]').val(formatFloat(subtotal_afterdisc, 2));
        });
        setTotal();
    }

    function setTotal(edit_ppn, display_mode) {
        setTimeout(function () {
            var total = 0;
            var ppn = 0;
            var pph = 0;
            var pph_persen = 0.02;
            var potongan = unformatNumber($("#<?= yii\bootstrap\Html::getInputId($model, 'potonganharga') ?>").val());
            var subtotal = 0;
            var total_pbbkb = unformatNumber($("#<?= yii\bootstrap\Html::getInputId($model, 'total_pbbkb') ?>").val());
            var total_biayatambahan = unformatNumber($("#<?= yii\bootstrap\Html::getInputId($model, 'total_biayatambahan') ?>").val());

            $('#table-detail tbody tr').each(function (index) {
                subtotal = unformatNumber($(this).find('input[name*="[subtotal]"]').val());
                total += subtotal;
                var terimabhpd_harga = unformatNumber($(this).find('input[name*="[terimabhpd_harga]"]').val());
                if ($('#<?= Html::getInputId($model, 'spo_id') ?>').val()) {
                    // jika sebelum tgl 2 Januari 2025, ppn 11%
                    var tglspo = new Date($('#tglspo').val());
                    tglspo = tglspo.toString('yyyy-MM-dd');
                    if(tglspo < '2025-01-02'){
                        ppn += subtotal * 0.11;
                    } else {
                        ppn += subtotal * <?= Params::DEFAULT_PPN?>;
                    }
                    console.log($('#tglspo').val());
                    
                } else if ($('#<?= Html::getInputId($model, 'spl_id') ?>').val()) {
                    ppn += unformatNumber($(this).find('input[name*="[ppn_peritem]"]').val());
                }
                pph += unformatNumber($(this).find('input[name*="[pph_peritem]"]').val());
            });

            // total = Math.ceil(total);
            if (edit_ppn) {
                ppn = unformatNumber($('#<?= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>').val());
            }

            if ($('#is_pkp').val() == '1') {
                ppn = ppn;
            } else {
                if ($('#<?= Html::getInputId($model, 'spl_id') ?>').val()) {
                    ppn = ppn;
                } else {
                    ppn = 0;
                }
            }

            if (isNaN(ppn)) {
                ppn = 0;
            }

            if (('<?= isset($_GET['terima_bhp_id']) ? $_GET['terima_bhp_id'] : '' ?>' != '') && display_mode) {
                var id = '<?= (isset($_GET['terima_bhp_id']) ? $_GET['terima_bhp_id'] : '') ?>';
                $.ajax({
                    url: '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/getData']); ?>',
                    type: 'POST',
                    data: {id: id},
                    success: function (data) {
                        ppn = data.model.ppn_nominal;
                        if (data.model.total_pbbkb) {
                            total_pbbkb = data.model.total_pbbkb;
                        }
                        if (data.model.total_biayatambahan) {
                            total_biayatambahan = data.model.total_biayatambahan;
                        }

                        if(data.model.potonganharga) {
                            potongan = data.model.potonganharga;
                        }
                        //var totalbayar = total - potongan + ppn - pph + total_pbbkb + total_biayatambahan;
                        const totalbayar = total - potongan + ppn + pph + total_pbbkb + total_biayatambahan;
                        //$('#<?php //= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>//').val(formatFloat(ppn, 2)); perubahan per 2022-10-17
                        $('#<?= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>').val(formatNumber(Math.round(ppn)));
                        $('#<?= yii\bootstrap\Html::getInputId($model, 'totalpph') ?>').val(formatNumberForUser(pph));
                        $('#<?= yii\bootstrap\Html::getInputId($model, 'total_pbbkb') ?>').val(formatNumberForUser(total_pbbkb));
                        $('#<?= yii\bootstrap\Html::getInputId($model, 'total_biayatambahan') ?>').val(formatNumberForUser(total_biayatambahan));
                        $('#<?= yii\bootstrap\Html::getInputId($model, 'potonganharga') ?>').val(formatNumberForUser(potongan));
                        $('#total').val(formatNumberForUser(total));
                        $('#<?= yii\bootstrap\Html::getInputId($model, 'totalbayar') ?>').val(formatNumber(Math.round(totalbayar)));

                        //ubah label % ppn
                        persen_ppn = ppn / total * 100;
                        label = ''; // Params::DEFAULT_LABEL_PPN
                        if(persen_ppn > 0){
                            label = "("+ formatNumber(Math.round(persen_ppn)) +"%)";
                        } 
                        $('#label_ppn').text(label);
                    },
                    error: function (jqXHR) {
                        getdefaultajaxerrorresponse(jqXHR);
                    },
                });
            } else {
                //var totalbayar = total - potongan + ppn - pph + total_pbbkb + total_biayatambahan;
                const totalbayar = total - potongan + ppn + pph + total_pbbkb + total_biayatambahan;
                //$('#<?php //= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>//').val(formatFloat(ppn, 2)); perubahan per 2022-10-17
                $('#<?= yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>').val(formatNumber(Math.round(ppn)));
                $('#<?= yii\bootstrap\Html::getInputId($model, 'totalpph') ?>').val(formatNumberForUser(pph));
                $('#<?= yii\bootstrap\Html::getInputId($model, 'total_pbbkb') ?>').val(formatNumberForUser(total_pbbkb));
                $('#<?= yii\bootstrap\Html::getInputId($model, 'total_biayatambahan') ?>').val(formatNumberForUser(total_biayatambahan));
                $('#<?= yii\bootstrap\Html::getInputId($model, 'potonganharga') ?>').val(formatNumberForUser(potongan));
                $('#total').val(formatNumberForUser(total));
                $('#<?= yii\bootstrap\Html::getInputId($model, 'totalbayar') ?>').val(formatNumber(Math.round(totalbayar)));
            }
        }, 300);
    }

    function cancelItemThis(ele) {
        $(ele).parents('tr').fadeOut(200, function () {
            $(this).remove();
            reordertable('#table-detail');
            setSubtotal($(ele).parents('tr').find('input[name*="[terimabhpd_harga]"]'));
        });
    }

    function save() {
        /*var $form = $('#form-terima-bhp');
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
        */

        //alert('aaa');
        var $form = $('#form-terima-bhp');
        if (formrequiredvalidate($form)) {
            // 2020-07-09 jika input penerimaan barang terlamat, keterangan wajib diisi
            cekStatusTerimaBarang();
            //alert(cekStatusTerimaBarang());
            if (cekStatusTerimaBarang() == true) {
                if (!$.trim($("#keterangan_textarea").val())) {
                    var status_keterangan = 'jojon';
                } else {
                    var status_keterangan = 'sip';
                }
            } else {
                var status_keterangan = 'sip';
            }

            var jumlah_item = $('#table-detail tbody tr').length;
            //alert(status_keterangan+'\n'+jumlah_item);
            if (status_keterangan == 'jojon' && jumlah_item <= 0) {
                cisAlert('Isi keterangan dan detail terlebih dahulu');
                $('.form-group.field-keterangan_textarea.has-success').removeClass('has-success');
                $('.form-group.field-keterangan_textarea').addClass('has-error');
                $('#status_terima_bhp').show();
                var cek = 1;
                return false;
            } else if (status_keterangan == 'sip' && jumlah_item <= 0) {
                cisAlert('Isi detail terlebih dahulu');
                $('#status_terima_bhp').hide();
                var cek = 2;
                return false;
            } else if (status_keterangan == 'jojon' && jumlah_item >= 0) {
                cisAlert('Isi keterangan terlebih dahulu');
                $('.form-group.field-keterangan_textarea.has-success').removeClass('has-success');
                $('.form-group.field-keterangan_textarea').addClass('has-error');
                $('#status_terima_bhp').show();
                var cek = 3;
                return false;
            } else if (status_keterangan == 'sip' && jumlah_item >= 0) {
                var cek = 4;
                //return true; <-- gak perlu jon
            } else {
                var cek = 5;
                return false;
            }

            //alert(cek);

            if (validatingDetail()) {
                submitform($form);
            }
        }
        //alert('bbb');
        return false;
    }

    function validatingDetail() {
        var has_error = 0;
        $('#table-detail tbody > tr').each(function () {
            var field1 = $(this).find('input[name*="[bhp_id]"]');
            var field2 = $(this).find('input[name*="[terimabhpd_qty]"]');
            var field3 = $(this).find('input[name*="[terimabhpd_qty_old]"]');
            if (!field1.val()) {
                $(this).find('input[name*="[bhp_id]"]').parents('td').addClass('error-tb-detail');
                has_error = has_error + 1;
            } else {
                $(this).find('input[name*="[bhp_id]"]').parents('td').removeClass('error-tb-detail');
            }
            if (!field2.val()) {
                has_error = has_error + 1;
                $(this).find('input[name*="[terimabhpd_qty]"]').parents('td').addClass('error-tb-detail');
            } else {
                if ($(this).find('input[name*="[terimabhpd_qty]"]').val() == 0) {
                    has_error = has_error + 1;
                    $(this).find('input[name*="[terimabhpd_qty]"]').parents('td').addClass('error-tb-detail');
                } else {
                    if (unformatNumber(field2.val()) > unformatNumber(field3.val())) {
                        has_error = has_error + 1;
                        $(this).find('input[name*="[terimabhpd_qty]"]').parents('td').addClass('error-tb-detail');
                    } else {
                        $(this).find('input[name*="[terimabhpd_qty]"]').parents('td').removeClass('error-tb-detail');
                    }
                }
            }
        });
        if (has_error === 0) {
            return true;
        }
        return false;
    }

    function afterSave(id) {
        $('form').find('input').each(function () {
            $(this).prop("disabled", true);
        });
        $('form').find('select').each(function () {
            $(this).prop("disabled", true);
        });
        $('form').find('textarea').each(function () {
            $(this).attr("disabled", "disabled");
        });
        $('#tterimabhp-tglterima').siblings('.input-group-btn').find('button').prop('disabled', true);
        $('#btn-add-item').hide();
        $('#btn-save').attr('disabled', '');
//    $('#btn-print').removeAttr('disabled');
        $('#btn-print-rincian').removeAttr('disabled');
        setTimeout(function () {
            <?php if(!empty($model->spl_id)){ ?>
            cancelEditHarga();
            <?php }else{ ?>
            getItemsByTerimaBhp(id);
            <?php } ?>
        }, 500);
    }

    function getItemsByTerimaBhp() {
        $('#table-detail').addClass('animation-loading');
        var terima_bhp_id = '<?= (isset($_GET['terima_bhp_id']) ? $_GET['terima_bhp_id'] : '') ?>';
        var html = "";
        if (terima_bhp_id) {
            $.ajax({
                url: '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/GetItemsByTerimaBhp']); ?>',
                type: 'POST',
                data: {terima_bhp_id: terima_bhp_id},
                success: function (data) {
                    if (data) {
                        html = data.html;
                        $('#table-detail tbody').html(html);
                        if (data.terimabhp) {
                            if (data.terimabhp.spo_id) {
//							if(data.terimabhp.is_ppn === true){
//								$('.span-include-ppn').html("Include");
//								$('#<?php // echo \yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>').val(1);
//							}else{
//								$('.span-include-ppn').html("Exclude");
//								$('#<?php // echo \yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>').val(0);
//								$("#<?php // echo yii\bootstrap\Html::getInputId($model, 'ppn_nominal') ?>").val(0);
//							}
                            } else {
                                $("#<?= yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>").val(0);
                            }
                            $('.place-mata-uang').html("(" + data.name_en + ")");
                        } else {
                            $("#<?= yii\bootstrap\Html::getInputId($model, 'is_ppn') ?>").val(0);
                        }
                        $('#table-detail').removeClass('animation-loading');
                        setTotal(null, true);
                        reordertable('#table-detail');
                        if (data.terimabhp.spo_id) {
                            setPopoverInfoSpo(data.terimabhp.spo_id)
                        }
                        if (data.terimabhp.spl_id) {
                            setPopoverInfoSpl(data.terimabhp.spl_id)
                        }
                    }
                },
                error: function (jqXHR) {
                    getdefaultajaxerrorresponse(jqXHR);
                },
            }).done(function () {
                $('#table-detail > tbody > tr').each(function () {
                    $(this).find(":input:not([type=hidden])").attr('disabled', 'disabled');
                    $(this).find('select').attr('disabled', 'disabled');
                    $(this).find('textarea').attr('disabled', 'disabled');
                });
            });
        } else {
            html = "<tr><td colspan='7'><center><i>Data tidak ditemukan</i></center></td></tr>"
            $('#table-detail tbody').html(html);
            $('#table-detail').removeClass('animation-loading');
        }
    }

    function daftarTerimaBhp() {
        openModal('<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/daftarTerimaBhp']) ?>', 'modal-daftar-terimabhp', '95%');
    }

    function setPopoverInfoSpo(spo_id) {
        $('.header-spl').hide();
        $('.header-spo').show();
        $('#table-detail tfoot tr').each(function () {
            $(this).find('td:first').attr('colspan', '6')
        });
//    if(spo_id){
//		$('.spo-info-place').html('<i class="fa fa-info-circle popover-spo" data-ajaxload="" style="cursor: default;"> Detail PO</i> ');
//    }else{
//		$('.spo-info-place').html('');
//    }
//    $('.spo-info-place').hover(function(){
//		var e= $(this);
//		e.off('hover');
//		$.get('<?php // echo \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/infoSpo','id'=>'']); ?>'+spo_id+'',function(d){
//			e.popover({html : true,placement: 'left',content: d, title:'Detail PO'}).popover('show');
//		});
//    }, function(){
//		$('.spo-info-place').popover('hide');
//    });
    }

    function setPopoverInfoSpl(spl_id) {
        $('.header-spl').show();
        $('.header-spo').hide();
        $('#table-detail tfoot tr').each(function () {
            $(this).find('td:first').attr('colspan', '6')
        });
//    if(spl_id){
//		$('.spl-info-place').html('<i class="fa fa-info-circle popover-spl" data-ajaxload="" style="cursor: default;"> Detail SPL</i> ');
//    }else{
//		$('.spl-info-place').html('');
//    }
//    $('.spl-info-place').hover(function(){
//		var e= $(this);
//		e.off('hover');
//		$.get('<?php // echo \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/infoSpl','id'=>'']); ?>'+spl_id+'',function(d){
//			e.popover({html : true,placement: 'left',content: d, title:'Detail SPL'}).popover('show');
//		});
//    }, function(){
//		$('.spl-info-place').popover('hide');
//    });
    }

    function setPpnPerItem(ele, value) {
        ele = $(ele).parents('td').find('input[name*="is_ppn_peritem"]');
        var qty = unformatNumber($(ele).parents('tr').find('input[name*="[terimabhpd_qty]"]').val());
        var harga = unformatNumber($(ele).parents('tr').find('input[name*="[terimabhpd_harga]"]').val());
        var tglspl = $('#tglspl').val();
        var ppnitem = 0;
        if ($(ele).is(':checked')) {
            if (value) {
                ppnitem = value;
            } else {
                // jika tanggal spl sebelum 2025 maka ppn 11%
                if(tglspl < '2025-01-02'){
                    ppnitem = (qty * harga) * 0.11;
                } else {
                    ppnitem = (qty * harga) * <?= Params::DEFAULT_PPN?>;
                }
            }
            $(ele).parents('td').find('input[name*="[ppn_peritem]"]').removeAttr('disabled');
        } else {
            $(ele).parents('td').find('input[name*="[ppn_peritem]"]').attr('disabled', 'disabled');
        }
        $(ele).parents('tr').find('input[name*="[ppn_peritem]"]').val(formatNumberForUser(ppnitem));
        setTotal();
        return false;
    }

    function setPphPerItem(ele, value) {
        ele = $(ele).parents('td').find('input[name*="is_pph_peritem"]');
        var qty = unformatNumber($(ele).parents('tr').find('input[name*="[terimabhpd_qty]"]').val());
        var harga = unformatNumber($(ele).parents('tr').find('input[name*="[terimabhpd_harga]"]').val());
        var pphitem = 0;
        var ada_checked = false;
        var subtotal = unformatNumber($(ele).parents('tr').find('input[name*="subtotal"]').val());
        if ($(ele).is(':checked')) {
            // get NPWP Suplier
            <?php if( isset($_GET['terima_bhp_id']) ){ ?>
            <?php if( !empty($model->spo_id) ){ ?>
            var suplier_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
            <?php }else if( !empty($model->spl_id) ){ ?>
            var suplier_id = $(ele).parents('tr').find('select[name*="suplier_id"]').val();
            <?php } ?>
            <?php }else{ ?>
            if ($('#<?= yii\bootstrap\Html::getInputId($model, 'spo_id') ?>').val()) {
                var suplier_id = $('#<?= yii\bootstrap\Html::getInputId($model, 'suplier_id') ?>').val();
            } else if ($('#<?= yii\bootstrap\Html::getInputId($model, 'spl_id') ?>').val()) {
                var suplier_id = $(ele).parents('tr').find('select[name*="suplier_id"]').val();
            }
            <?php } ?>
            if(suplier_id){
                $.ajax({
                    url: '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/getNpwp']); ?>',
                    type: 'POST',
                    data: {suplier_id: suplier_id, subtotal: subtotal},
                    success: function (data) {
                        if (value) {
                            pphitem = value;
                        } else {
                            pphitem = data.total;
                        }
                        if (data.suplier_npwp) {
                            $('#npwpitemplace').html('NPWP : ' + data.suplier_npwp);
                        } else {
                            $('#npwpitemplace').html('');
                        }
                        $(ele).parents('tr').find('input[name*="[pph_peritem]"]').val(pphitem);
                        setTotal();
                    },
                    error: function (jqXHR) {
                        getdefaultajaxerrorresponse(jqXHR);
                    },
                });
            } else {
                cisAlert("Isi suplier terlebih dahulu!");
            }
            
            // end get NPWP Suplier

            $(ele).parents('td').find('input[name*="[pph_peritem]"]').removeAttr('disabled');
        } else {
            $('#npwpitemplace').html('');
            $(ele).parents('td').find('input[name*="[pph_peritem]"]').attr('disabled', 'disabled');
            $(ele).parents('tr').find('input[name*="[pph_peritem]"]').val(formatNumberForAllUser(pphitem));
            setTotal();
        }
        return false;
    }

    function cancelTerima(terima_bhp_id) {
        openModal('<?php echo \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/cancelTerima']) ?>?id=' + terima_bhp_id, 'modal-transaksi');
    }

    function printout(id) {
        window.open("<?= yii\helpers\Url::toRoute('/purchasing/tracking/printTbp') ?>?id=" + id + "&caraprint=PRINT", "", 'location=_new, width=1200px, scrollbars=yes');
    }

    function printRincian(id) {
        window.open("<?= yii\helpers\Url::toRoute('/purchasing/tracking/printTbpRincian') ?>?id=" + id + "&caraprint=PRINT", "", 'location=_new, width=1200px, scrollbars=yes');
    }

    function checkTBP(spo_kode, kode_tbp, tbp_id, status_approval, spo_id) {
        if (spo_kode && kode_tbp) {
            //var status_approval_keterangan = "<font style='font-size: 10px; color: #f50018; background-color: #ddd; font-weight: bold;'>(REJECTED)</font>";
//		var label = '<b>NOTE :</b> '+spo_kode+' ini sudah pernah di terima pada : <b><u><a onclick="infoTBP('+tbp_id+')">'+kode_tbp+'</a></u></b>';
            var label = '<b>NOTE :</b> <b><u><a onclick="infoApproval(' + spo_id + ')">' + spo_kode + '</a></u></b> ini sudah pernah di terima pada : <b><u><a onclick="infoTBP(' + tbp_id + ')">' + kode_tbp + '</a></u></b>';
        } else {
            var label = '';
        }
        $('#place-tbpexist').html(label);
    }

    function infoTBP(terima_bhp_id) {
        var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoTbp', 'id' => '']); ?>' + terima_bhp_id;
        $(".modals-place-2").load(url, function () {
            $("#modal-info-tbp").modal('show');
            $("#modal-info-tbp").on('hidden.bs.modal', function () {

            });
            spinbtn();
            draggableModal();
        });
    }

    function infoSPO(spo_id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpo']) ?>?id=' + spo_id, 'modal-info-spo', '75%', '');
    }

    function infoSPL(spl_id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoSpl']) ?>?id=' + spl_id, 'modal-info-spl', '75%', '');
    }

    function returBHP(terima_bhpd_id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/returBHP', 'terima_bhpd_id' => '']); ?>' + terima_bhpd_id, 'modal-transaksi');
    }

    function infoReturBHP(spo_id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/tracking/infoReturBHP']) ?>?id=' + spo_id, 'modal-info-spo', '75%', '');
    }

    function checkApproval(spo_id, spo_kode) {
        if (spo_id) {
            var label = '<b>Klick Info SPO :</b> <b><u><a onclick="infoApproval(' + spo_id + ')">' + spo_kode + '</a></u></b>';
        } else {
            var label = '';
        }
        $('#place-tbpexist').html(label);
    }

    function infoApproval(id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/infoApproval', 'id' => '']) ?>' + id, 'modal-master-info', '85%', " $('#table-master').dataTable().fnClearTable(); ");
    }

</script>