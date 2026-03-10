<div class="modal fade" id="modal-logalam-edit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Update'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php

                        use yii\bootstrap\ActiveForm;
                        use app\models\TPengajuanPembelianlog;
                        use app\models\MKayu;
                        use yii\bootstrap\Html;

                        $form = ActiveForm::begin([
                            'id' => 'form-transaksi-detail',
                            'fieldConfig' => [
                                'template' => '{label}<div class="col-md-7">{input} {error}</div>',
                                'labelOptions' => ['class' => 'col-md-4 control-label'],
                            ],
                        ]);

                        if ($terima->area_pembelian === 'Luar Jawa') {
                            echo $form->field($model, 'pengajuan_pembelianlog_id')->dropDownList(TPengajuanPembelianlog::getOptionListPenerimaanLogAlamLuarJawa($terima->spk_shipping_id));
                        }

                        echo $form->field($model, 'kayu_id')->dropDownList(MKayu::getOptionListIlimiahKayu());
                        echo $form->field($model, 'no_lap')->textInput(['disabled' => true]);
                        echo $form->field($model, 'no_grade')->textInput();
                        echo $form->field($model, 'no_btg')->textInput();
                        echo $form->field($model, 'no_produksi')->textInput();
                        echo $form->field($model, 'kode_potong')->textInput();
                        echo $form->field($model, 'panjang')->textInput(['type' => 'number', 'oninput' => 'hitungRata(this, "form")']);
                        echo $form->field($model, 'diameter_ujung1')->textInput(['type' => 'number', 'oninput' => 'hitungRata(this, "form")']);
                        echo $form->field($model, 'diameter_ujung2')->textInput(['type' => 'number', 'oninput' => 'hitungRata(this, "form")']);
                        echo $form->field($model, 'diameter_pangkal1')->textInput(['type' => 'number', 'oninput' => 'hitungRata(this, "form")']);
                        echo $form->field($model, 'diameter_pangkal2')->textInput(['type' => 'number', 'oninput' => 'hitungRata(this, "form")']);
                        echo $form->field($model, 'diameter_rata')->textInput(['type' => 'number', 'disabled' => true]);
                        echo $form->field($model, 'cacat_panjang')->textInput(['type' => 'number', 'oninput' => 'hitungVolume(this, "form")']);
                        echo $form->field($model, 'cacat_gb')->textInput(['type' => 'number', 'oninput' => 'hitungVolume(this, "form")']);
                        echo $form->field($model, 'cacat_gr')->textInput(['type' => 'number', 'oninput' => 'hitungVolume(this, "form")']);
                        echo $form->field($model, 'volume')->textInput(['type' => 'number', 'disabled' => true, 'step' => '0.01']);
                        // TAMBAH FSC
                        echo '<div class="col-md-4 control-label">Status FSC</div>';
                        if($model->fsc){
                            $checked = 'checked';
                        } else {
                            $checked = '';
                        }
                        echo '<div class="col-md-7"><input type="checkbox" id="tterimalogalamdetail-fsc" class="td-kecil" name="TTerimaLogalamDetail[fsc]" value="'.$model->fsc.'" '.$checked.' disabled="disabled"></div>';
                        // eo FSC
                        ActiveForm::end();
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Update', ['class' => 'btn hijau btn-outline ciptana-spin-btn', 'form' => 'form-transaksi-detail'])?>
            </div>
        </div>
    </div>
</div>