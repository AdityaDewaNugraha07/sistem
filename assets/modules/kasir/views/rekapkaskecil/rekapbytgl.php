<div class="modal fade" id="modal-rekap" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Laporan Pengeluaran Kas Kecil Tanggal ').app\components\DeltaFormatter::formatDateTimeForUser(substr($models[0]['tanggal'], 0,10) ); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover" id="table-list" style="width: 100%;">
								<thead>
									<tr>
										<th style="text-align: center; width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
										<th style="text-align: center; width: 75px;"><?= Yii::t('app', 'Kode'); ?></th>
										<th style="text-align: center; "><?= Yii::t('app', 'Deskripsi'); ?></th>
										<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Debit'); ?></th>
										<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Kredit'); ?></th>
										<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Saldo'); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="td-kecil" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right; background-color: #daffaa">SALDO AWAL</td>
										<td class="td-kecil" style="background-color: #daffaa"> &nbsp; </td>
										<td class="td-kecil" style="background-color: #daffaa"> &nbsp; </td>
										<td class="td-kecil text-align-right" style="background-color: #daffaa; font-weight: bold; "><?= app\components\DeltaFormatter::formatNumberForUserFloat( \app\models\HSaldoKaskecil::getSaldoAwal($models[0]['tanggal']) ); ?></td>
									</tr>
									<?php 
									$totalkredit = 0;
									$totaldebit = 0;
									foreach($models as $i => $model){ ?>
									<?php
									$highlight = false;
									if(isset($highlight_kode)){
										if($highlight_kode == $model['reff_no']){
											$highlight = true;
										}
									}
									?>
									<tr style="<?= ($highlight==true)?"background-color: #fceeb1":""; ?>">
										<td class="td-kecil text-align-center"><?= $i+1; ?></td>
										<td class="td-kecil" style="font-weight: bold; text-align: center;"><?= $model['reff_no']; ?></td>
										<td class="td-kecil" style="font-size: 1.2rem;"><?= $model['deskripsi'] ?></td>
										<td class="td-kecil text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($model['debit']); ?></td>
										<td class="td-kecil text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($model['kredit']); ?></td>
										<!--<td class="td-kecil text-align-right"><?php // echo app\components\DeltaFormatter::formatNumberForUserFloat($model->saldo); ?></td>-->
										<td class="td-kecil text-align-right">-</td>
									</tr>
									<?php 
									$totaldebit += $model['debit'];
									$totalkredit += $model['kredit'];
									} ?>
									<tr style="background-color: #e2e3e5;">
										<td class="td-kecil" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right;">TOTAL</td>
										<td class="td-kecil text-align-right" style="font-weight: bold; "><?= app\components\DeltaFormatter::formatNumberForUserFloat( $totaldebit ); ?></td>
										<td class="td-kecil text-align-right" style="font-weight: bold; "><?= app\components\DeltaFormatter::formatNumberForUserFloat( $totalkredit ); ?></td>
										<td class="td-kecil"> &nbsp; </td>
									</tr>
									<tr style="background-color: #e2e3e5;">
										<td class="td-kecil" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right;">SALDO AKHIR</td>
										<td class="td-kecil" > &nbsp; </td>
										<td class="td-kecil" > &nbsp; </td>
										<td class="td-kecil text-align-right" style="font-weight: bold; "><?= app\components\DeltaFormatter::formatNumberForUserFloat( \app\models\HSaldoKaskecil::getSaldoAkhir($models[0]['tanggal']) ); ?></td>
									</tr>
									<tr style="background-color: #e2e3e5;">
										<td class="td-kecil" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right;">DANA TETAP</td>
										<td class="td-kecil" > &nbsp; </td>
										<td class="td-kecil" > &nbsp; </td>
										<td class="td-kecil text-align-right" style="font-weight: bold; "><?= app\components\DeltaFormatter::formatNumberForUserFloat( \app\components\Params::DANA_TETAP_KAS_KECIL ); ?></td>
									</tr>
									<tr style="background-color: #e2e3e5;">
										<td class="td-kecil" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right;">TOTAL TOP-UP</td>
										<td class="td-kecil" > &nbsp; </td>
										<td class="td-kecil" > &nbsp; </td>
										<td class="td-kecil text-align-right" style="font-weight: bold; "><?= app\components\DeltaFormatter::formatNumberForUserFloat( (\app\components\Params::DANA_TETAP_KAS_KECIL)-(\app\models\HSaldoKaskecil::getSaldoAkhir($models[0]['tanggal'])) ); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
                <?= yii\helpers\Html::button(Yii::t('app', 'Print'),['class'=>'btn blue-steel ciptana-spin-btn btn-outline','onclick'=>'printout("PRINT","'.$models[0]['tanggal'].'")']); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Excel'),['class'=>'btn green-seagreen ciptana-spin-btn btn-outline','onclick'=>'printout("EXCEL","'.$models[0]['tanggal'].'")']); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'PDF'),['class'=>'btn red-flamingo ciptana-spin-btn btn-outline','onclick'=>'printout("PDF","'.$models[0]['tanggal'].'")']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>