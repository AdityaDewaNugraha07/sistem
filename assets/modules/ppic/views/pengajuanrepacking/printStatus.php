<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
				<div class="row">
                    <div class="col-md-12">
						<?php echo $header; ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
                                        <tr>
											<th>No.</th>
                                            <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Kode /<br>Tanggal'); ?></th>
                                            <th><?= Yii::t('app', 'Produk / Dimensi'); ?></th>
                                            <th style="width: 50px;"><?= Yii::t('app', 'Permintaan<br>Mutasi Palet'); ?></th>
                                            <th style="width: 110px; line-height: 1"><?= Yii::t('app', 'Palet Dimutasi<br>Keluar Gudang'); ?></th>
                                            <th style="width: 110px; line-height: 1"><?= Yii::t('app', 'Palet Diterima<br>Oleh PPIC'); ?></th>
                                            <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Palet Kirim<br>Ke Gudang'); ?></th>
                                            <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Palet Diterima<br>Oleh Gudang'); ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
                                        $dataPrints = \app\components\DtParamsToRawQuery::generate(Yii::$app->runAction("/ppic/pengajuanrepacking/statusQuery"))['data'];
										if(count($dataPrints)>0){ 
											foreach($dataPrints as $i => $data){
//                                                echo "<pre>";
//                                                print_r($data);
//                                                exit;
                                                $data_mutasi_keluar = yii\helpers\Json::decode($data['mutasi_keluar']); $mutasi_keluar = "";
                                                if(!empty($data_mutasi_keluar)){
                                                    foreach($data_mutasi_keluar as $ii => $mutasi_k){
                                                        $mutasi_keluar .= $mutasi_k['nomor_produksi']."<br>";
                                                    }
                                                }
                                                $data_terima_mutasi = yii\helpers\Json::decode($data['terima_mutasi']); $terima_mutasi = "";
                                                if(!empty($data_terima_mutasi)){
                                                    foreach($data_terima_mutasi as $ii => $mutasi_k){
                                                        $terima_mutasi .= $mutasi_k['nomor_produksi']."<br>";
                                                    }
                                                }
                                                $data_nomor_produksi = yii\helpers\Json::decode($data['nomor_produksi']); $nomor_produksi = "";
                                                if(!empty($data_nomor_produksi)){
                                                    foreach($data_nomor_produksi as $ii => $mutasi_k){
                                                        $nomor_produksi .= $mutasi_k['nomor_produksi']."<br>";
                                                    }
                                                }
                                                $data_terima_gudang_kembali = yii\helpers\Json::decode($data['terima_gudang_kembali']); $terima_gudang_kembali = "";
                                                if(!empty($data_terima_gudang_kembali)){
                                                    foreach($data_terima_gudang_kembali as $ii => $mutasi_k){
                                                        $terima_gudang_kembali .= $mutasi_k['nomor_produksi']."<br>";
                                                    }
                                                }
                                            ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
                                                <td class="text-align-center" style="font-size: 1.1rem;">
                                                    <?= "<b>".$data['kode']."</b><br>".app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal'])."<br>".$data['keperluan']; ?></td>
												<td class="text-align-left" style="font-size: 1.1rem;"><?= $data['produk']; ?></td>
												<td class="text-align-center"><?= $data['pcs']; ?></td>
												<td class="text-align-left" style="font-size: 1.1rem;"><?= $mutasi_keluar; ?></td>
												<td class="text-align-left" style="font-size: 1.1rem;"><?= $terima_mutasi; ?></td>
												<td class="text-align-left" style="font-size: 1.1rem;"><?= $nomor_produksi; ?></td>
												<td class="text-align-left" style="font-size: 1.1rem;"><?= $terima_gudang_kembali; ?></td>
											</tr>
										<?php }
										}else{
											"<tr colspan='5'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
										}
										?>
									</tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
mergeSameValue();
function mergeSameValue(){
	var arr = [];
	var coll = [1,6,7];
	$("#table-laporan").find('tr').each(function (r, tr) {
		$(this).find('td').each(function (d, td) {
			if ( coll.indexOf(d) !== -1) {
				var $td = $(td);
				var v_dato = $td.html();
				if(typeof arr[d] != 'undefined' && 'dato' in arr[d] && arr[d].dato == v_dato) {
					var rs = arr[d].elem.data('rowspan');
					if(rs == 'undefined' || isNaN(rs)) rs = 1;
					arr[d].elem.data('rowspan', parseInt(rs) + 1).addClass('rowspan-combine');
					$td.addClass('rowspan-remove');
				} else {
					arr[d] = {dato: v_dato, elem: $td};
				};
			}
		});
	});
	$('.rowspan-combine').each(function (r, tr) {
	  var $this = $(this);
	  $this.attr('rowspan', $this.data('rowspan')).css({'vertical-align': 'middle'});
	});
	$('.rowspan-remove').remove();
}
</script>