<div class="modal fade draggable-modal" id="modal-daftar-mutasi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Mutasi yang pernah dilakukan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
								<thead>
									<tr>
										<th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
										<th><?= Yii::t('app', 'Kode Mutasi'); ?></th>
										<th><?= Yii::t('app', 'Tanggal'); ?></th>
										<th><?= Yii::t('app', 'Dept Pemesan'); ?></th>
										<th><?= Yii::t('app', 'Pegawai Mutasi'); ?></th>
										<th><?= Yii::t('app', 'SPB Terkait'); ?></th>
										<th style="width: 120px;"><?= Yii::t('app', 'Lihat Detail'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($modMutasi as $i => $detail){ ?>
									<tr>
										<td><?php echo $i+1 ?></td>
										<td><?php echo $detail->kode; ?></td>
										<td><?php echo !empty($detail->tanggal)?\app\components\DeltaFormatter::formatDateTimeForUser2($detail->tanggal):' - '; ?></td>
										<td><?php echo !empty($detail->departement_id)?$detail->departement->departement_nama:' - '; ?></td>
										<td><?php echo !empty($detail->pegawai_mutasi)?$detail->pegawaiMutasi->pegawai_nama:' - '; ?></td>
										<td style="text-align:center">
											<a style='font-size:0.8em;' class='font-blue-hoki' onclick='infoSpb("<?= $detail->spb_id ?>")'> <?= $detail->spb->spb_kode ?> </a>
										</td>
										<td style="text-align: center;">
											<a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat detail mutasi" onclick="lihatMutasi(<?= $detail->mutasi_gudanglogistik_id ?>)"><i class="fa fa-eye"></i></a>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
", yii\web\View::POS_READY); ?>
<script>
function lihatMutasi(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/gudang/mutasigudanglogistik/index','mutasi_gudanglogistik_id'=>'']); ?>'+id);
}
</script>