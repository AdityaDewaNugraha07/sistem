<style>
#detail-ajuandinas tr td{
	font-size: 1.3rem !important;
	vertical-align: top;
}
</style>
<div class="modal fade" id="modal-ajuandinas" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title text-align-center"><?= Yii::t('app', 'Form Pengajuan Uang Dinas Grader Pembelian Log'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<table style="width: 20cm;" frame="box" id="detail-ajuandinas">
							<tr>
								<td colspan="4" class="text-align-center" style="padding-top: 10px; padding-bottom: 15px;">
									<h5><u><b><?= Yii::t('app', 'Form Pengajuan Uang Dinas Grader Pembelian Log'); ?></b></u></h5>
								</td>
							</tr>
							<tr style="height: 50px;">
								<td style="padding-left: 15px; width: 2.5cm;">Kepada</td>
								<td style="width: 0.5cm;">:</td>
								<td style="width: 13cm;">Kepala Divisi Finance Accounting<br>Ditempat</td>
								<td style="width: 4cm;">Kode : <b><?= $model->kode; ?></b></td>
							</tr>
							<tr>
								<td colspan="4" style="padding-left: 15px;">Dengan ini,</td>
							</tr>
							<tr>
								<td style="padding-left: 40px;">Nama</td>
								<td>:</td>
								<td><?= $model->kanitGrader->pegawai_nama ?></td>
							</tr>
							<tr style="height: 35px;">
								<td style="padding-left: 40px;">Jabatan</td>
								<td>:</td>
								<td>Kadep. Grader Pembelian Log</td>
							</tr>
							<tr>
								<td colspan="4" style="padding-left: 15px;">Menugaskan Grader untuk melaksanakan <b><?= $model->dkg->tipe ?></b> di wilayah dinas <b><?= $model->wilayahDinas->wilayah_dinas_nama ?></b> :</td>
							</tr>
							<tr>
								<td style="padding-left: 40px;">Nama</td>
								<td>:</td>
								<td><?= $model->graderlog->graderlog_nm ?></td>
							</tr>
							<tr>
								<td style="padding-left: 40px;">Jabatan</td>
								<td>:</td>
								<td><?= Yii::t('app', 'Grader'); ?></td>
							</tr>
							<tr>
								<td style="padding-left: 40px;">Tempat</td>
								<td>:</td>
								<td><?= !empty($model->dkg->tujuan)?$model->dkg->tujuan:"-" ?></td>
							</tr>
							<tr style="height: 2.3cm;">
								<td style="padding-left: 40px;">Jumlah</td>
								<td>:</td>
								<td>
									<table style="background-color: #DDDDDD; width: 60%;">
										<tr>
											<td style="width: 3.5cm; padding-left: 5px;">Maksimal Plafon</td>
											<td style="width: 0.5cm;">=</td>
											<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatUang($model->wilayah_dinas_plafon) ?> &nbsp;&nbsp; </td>
										</tr>
										<tr>
											<td style="width: 3.5cm; padding-left: 5px;">Sisa Saldo</td>
											<td style="width: 0.5cm;">=</td>
											<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatUang($model->saldo_sebelumnya) ?> &nbsp;&nbsp; </td>
										</tr>
										<tr>
											<td style="border-top: 1px solid #000; padding-left: 5px;"><b>Total Ajuan</b></td>
											<td style="border-top: 1px solid #000"><b>=</b></td>
											<td style="text-align: right; border-top: 1px solid #000"><b><?= \app\components\DeltaFormatter::formatUang($model->total_ajuan) ?> &nbsp;&nbsp; </b></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="4" style="padding-left: 15px;">Mohon Divisi Finance dapat membantu pencairan dana uang dinas grader sesuai dengan jumlah diatas,<br>terimakasih.</td>
							</tr>
							<tr style="height: 3cm;">
								<td colspan="4">
									<table style="width: 100%;">
										<tr>
											<td style="width: 45%;">&nbsp;</td>
											<td style="width: 45%; text-align: center;">Demak, <?= \app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal) ?> &nbsp;</td>
										</tr>
										<tr>
											<td style="width: 45%; text-align: center; height: 1.5cm;">Disetujui,</td>
											<td style="width: 45%; text-align: center; height: 1.5cm;">Dibuat,</td>
										</tr>
										<tr style="line-height: 1px;">
											<td style="text-align: center; font-size: 1rem !important;"><b><?= $model->approvedBy->pegawai_nama ?></td>
											<td style="text-align: center; font-size: 1rem !important;"><b><?= app\models\MUser::findOne($model->created_by)->pegawai->pegawai_nama ?></td>
										</tr>
										<tr style="line-height: 1px;">
											<td style="text-align: center; font-size: 1rem !important; padding-top: 10px;">
                                                <?php
                                                if( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_SENGON)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_SENGON) ){
                                                    echo "Kadep Purchasing BP";
                                                }else{
                                                    echo "Kadiv. Purchasing Log";
                                                }
                                                ?>
                                            </td>
											<td style="text-align: center; font-size: 1rem !important; padding-top: 10px;">
                                                <?php
                                                if( (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_KANIT_LOG_SENGON)||(Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_STAFF_LOG_SENGON) ){
                                                    echo "Adm. Purch Log Sengon";
                                                }else{
                                                    echo "Adm. Purch Log Alam";
                                                }
                                                ?>
                                            </td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
                <?= yii\helpers\Html::button(Yii::t('app', 'Print'),['class'=>'btn blue-steel ciptana-spin-btn btn-outline','onclick'=>'printAjuanDinas('.$model->ajuandinas_grader_id.')']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>