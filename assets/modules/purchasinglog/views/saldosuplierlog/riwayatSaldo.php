<div class="modal fade" id="modal-riwayatsaldo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title">Informasi Riwayat Saldo Suplier <b><?= $modSuplier->suplier_nm ?></b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        Filter Periode : &nbsp; <?php echo yii\helpers\Html::dropDownList("periode_riwayat","bulan_ini",["30hari_terakhir"=>"30 Hari Terakhir","3bln_terakhir"=>"3 Bulan Terakhir","1tahun_terakhir"=>"1 Tahun Terakhir","all"=>"All"],['class'=>'','onchange'=>'getItemsRiwayat('.$modSuplier->suplier_id.')']) ?>
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-hover" id="table-detail-riwayat" style="width: 100%;">
								<thead>
									<tr>
										<th style="text-align: center; width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
                                        <th style="text-align: center; width: 150px; line-height: 1" class=""><?= Yii::t('app', 'Waktu Transaksi'); ?></th>
										<th style="text-align: center; width: 130px; line-height: 1"><?= Yii::t('app', 'Reff Number'); ?></th>
										<th style="text-align: center; "><?= Yii::t('app', 'Deskripsi'); ?></th>
										<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Masuk'); ?></th>
										<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Keluar'); ?></th>
										<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Saldo'); ?></th>
									</tr>
								</thead>
								<tbody>
                                    <tr style="background-color: #e2e3e5;" class="font-blue">
										<td class="td-kecil" colspan="4" style="font-size: 1.2rem; font-weight: bold; text-align: right;">SALDO AWAL</td>
										<td class="td-kecil"> &nbsp; </td>
										<td class="td-kecil"> &nbsp; </td>
                                        <td class="td-kecil text-align-right" style="font-weight: bold; "><span id="place-saldoawal">0</span></td>
									</tr>
                                    <tr style="background-color: #e2e3e5;" class="">
										<td class="td-kecil" colspan="4" style="font-size: 1.2rem; font-weight: bold; text-align: right;">TOTAL</td>
										<td class="td-kecil text-align-right" style="font-weight: bold; "><span id="place-totalin">0</span></td>
										<td class="td-kecil text-align-right" style="font-weight: bold; "><span id="place-totalout">0</span></td>
										<td class="td-kecil"> &nbsp; </td>
									</tr>
									<tr style="background-color: #e2e3e5;" class="font-blue">
										<td class="td-kecil" colspan="4" style="font-size: 1.2rem; font-weight: bold; text-align: right;">SALDO AKHIR</td>
										<td class="td-kecil" > &nbsp; </td>
										<td class="td-kecil" > &nbsp; </td>
										<td class="td-kecil text-align-right" style="font-weight: bold; font-size: 1.5rem !important;"><span id="place-saldoakhir">0</span></td>
									</tr>
                                </tbody>
                                <tfoot>
                                    
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-align-center">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs(" 
    getItemsRiwayat(".$modSuplier->suplier_id.");
", yii\web\View::POS_READY); ?>
<script>
function getItemsRiwayat(suplier_id){
    var periode = $("select[name*='periode_riwayat']").val();
	$.ajax({
		url    : '<?php echo \yii\helpers\Url::toRoute(['/purchasinglog/saldosuplierlog/riwayatSaldo','id'=>'']); ?>'+suplier_id,
		type   : 'POST',
		data   : {getItems:true,suplier_id:suplier_id,periode:periode},
		success: function (data) {
			$('#table-detail-riwayat > tbody > tr.item-saldo').remove();
			if(data.html){
                $('#table-detail-riwayat > tbody > tr').find('#place-saldoawal').parents('tr').after(data.html);
                $('#table-detail-riwayat > tbody > tr').each(function(){
                    $(this).find(".tooltips").tooltip({ delay: 50 });
                });
			}
            if(data.saldo){
                $("#place-saldoawal").html( formatNumberForUser(data.saldo.saldoawal) );
                $("#place-totalin").html( formatNumberForUser(data.saldo.totalin) );
                $("#place-totalout").html( formatNumberForUser(data.saldo.totalout) );
                $("#place-saldoakhir").html( formatNumberForUser(data.saldo.saldoakhir) );
            }
			reordertable('#table-detail-riwayat');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>