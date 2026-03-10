<div class="modal fade" id="modal-rekap" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-align-center">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Rincian Laporan Pengeluaran Kas Kecil Sebelum Closing ').app\components\DeltaFormatter::formatDateTimeForUser( $models[0]->tanggal ); ?></h4>
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
										<td class="td-kecil" colspan="3" style="font-size: 1.2rem; font-weight: bold; text-align: right; background-color: #fbffb9">SALDO AWAL</td>
										<td class="td-kecil" style="background-color: #fbffb9"> &nbsp; </td>
										<td class="td-kecil" style="background-color: #fbffb9"> &nbsp; </td>
										<td class="td-kecil text-align-right" style="background-color: #fbffb9; font-weight: bold; "><?= app\components\DeltaFormatter::formatNumberForUserFloat( \app\models\HSaldoKaskecil::getSaldoAwal($models[0]->tanggal) ); ?></td>
									</tr>
									<?php 
									$totalkredit = 0; $totaldebit = 0; $debit = 0; $kredit = 0; $saldoakhir = 0;
									foreach($models as $i => $model){ 
										$kodeAwal = \app\components\DeltaGenerator::kodePengeluaranKasKecil($models[0]->tanggal);
										$pref = substr($kodeAwal, 0,4);
										$seq = substr($kodeAwal, 4,3);
										$kodeGenerate = $pref.($seq+$i);
										$highlight = false;
										if(isset($highlight_kode)){
											if($highlight_kode == $kodeGenerate){
												$highlight = true;
											}
										}
										if($model->tipe == 'IN'){
											$debit = $model->nominal;
											$kredit = 0;
										}else if($model->tipe == 'OUT'){
											$debit = 0;
											$kredit = $model->nominal;
										}
										?>
										<tr style="<?= ($highlight==true)?"background-color: #fceeb1":""; ?>">
											<td class="td-kecil text-align-center"><?= $i+1; ?></td>
											<td class="td-kecil" style="font-weight: bold; text-align: center;"><?= $kodeGenerate; ?></td>
											<td class="td-kecil" style="font-size: 1.2rem;"><?= $model->deskripsi ?></td>
											<td class="td-kecil text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($debit); ?></td>
											<td class="td-kecil text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($kredit); ?></td>
											<td class="td-kecil text-align-right">-</td>
										</tr>
										<?php 
										$totaldebit += $debit;
										$totalkredit += $kredit;
									} 
									$saldoawal = \app\models\HSaldoKaskecil::getSaldoAwal($models[0]->tanggal);
									$saldoakhir = $saldoawal-$totalkredit+$totaldebit;
									?>
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
										<td class="td-kecil text-align-right" style="font-weight: bold; "><?= app\components\DeltaFormatter::formatNumberForUserFloat( $saldoakhir ); ?></td>
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
										<td class="td-kecil text-align-right" style="font-weight: bold; "><?= app\components\DeltaFormatter::formatNumberForUserFloat( (\app\components\Params::DANA_TETAP_KAS_KECIL)-(\app\models\HSaldoKaskecil::getSaldoAkhir($models[0]->tanggal)) ); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>