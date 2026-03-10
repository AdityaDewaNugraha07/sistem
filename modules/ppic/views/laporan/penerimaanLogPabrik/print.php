<?php

use app\components\DeltaFormatter;
$this->title    = 'Print '.$paramprint['judul'];
$header         = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);

if($_GET['caraprint'] === "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="' . $paramprint['judul'].'.xls"');
	header('Cache-Control: max-age=0');
	$header = $paramprint['judul']." ". $paramprint['judul2'];
}
?>

<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
				<div class="row">
                    <div class="col-md-12">
						<?= $header; ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="td-kecil" rowspan="2"><?= Yii::t('app', 'No.'); // 0 ?></th> 
                                            <th class="td-kecil" rowspan="2"><?= Yii::t('app', 'Tanggal'); // 2 ?></th>
                                            <th class="td-kecil" rowspan="2"><?= Yii::t('app', 'Kayu'); // 3?></th>
                                            <th class="td-kecil" rowspan="2"><?= Yii::t('app', 'PIC'); // 4 ?></th>
                                            <th class="td-kecil" colspan="5"><?= Yii::t('app', 'Nomor'); // 5 ?></th>
                                            <th class="td-kecil" rowspan="2"><?= Yii::t('app', 'Panjang<br>(m)'); // 8 ?></th>
                                            <th class="td-kecil" rowspan="2"><?= Yii::t('app', 'Kode<br>Potong'); // 9 ?></th>
                                            <th class="td-kecil" colspan="5"><?= Yii::t('app', 'Diameter (cm)') ?></th>
                                            <th class="td-kecil" colspan="3"><?= Yii::t('app', 'Cacat (cm)') ?></th>
                                            <th class="td-kecil" rowspan="2"><?= Yii::t('app', 'Volume<br>(m<sup>3</sup>)') ?></th>
                                            <th class="td-kecil" rowspan="2"><?= Yii::t('app', 'Status FSC') ?></th>
                                        </tr>
                                        <tr>
                                            <th class="td-kecil">QRCode</th> <!-- 10 -->
                                            <th class="td-kecil">Grade</th> <!-- 10 -->
                                            <th class="td-kecil">Lapangan</th> <!-- 10 -->
                                            <th class="td-kecil">Batang</th> <!-- 10 -->
                                            <th class="td-kecil">Produksi</th> <!-- 10 -->
                                            <th class="td-kecil">Ujung 1</th> <!-- 10 -->
                                            <th class="td-kecil">Pangkal 1</th> <!-- 11 -->
                                            <th class="td-kecil">Ujung 2</th> <!-- 12 -->
                                            <th class="td-kecil">Pangkal 2</th> <!-- 13 -->
                                            <th class="td-kecil">Rata-rata</th> <!-- 14 -->
                                            <th class="td-kecil">Panjang</th> <!-- 15 -->
                                            <th class="td-kecil">Gubal</th> <!-- 16 -->
                                            <th class="td-kecil">Growong</th> <!-- 17 -->
                                        </tr>
                                    </thead>
									<tbody>
										<?php 
                                        if(count($data)> 0 ){ 
											foreach($data as $i => $data){ 
                                            ?>
											<tr>
												<td class="td-kecil text-center"><?= $i+1; ?></td>
                                                <td class="td-kecil text-center"><?= DeltaFormatter::formatDateTimeForUser2($data['tanggal']);?></td>
                                                <td class="td-kecil text-center"><?= $data['kayu_nama'];?></td>
                                                <td class="td-kecil text-center"><?= $data['pegawai_nama'];?></td>
                                                <td class="td-kecil text-center"><?= $data['kode'];?></td>
                                                <td class="td-kecil text-center"><?= $data['no_grade'];?></td>
                                                <td class="td-kecil text-center"><?= $data['no_lap'];?></td>
                                                <td class="td-kecil text-center"><?= $data['no_btg'];?></td>
                                                <td class="td-kecil text-center"><?= $data['no_produksi'];?></td>
                                                <td class="td-kecil text-center"><?= $data['panjang'];?></td>
                                                <td class="td-kecil text-center"><?= $data['kode_potong'];?></td>
                                                <td class="td-kecil text-center"><?= $data['diameter_ujung1'];?></td>										
                                                <td class="td-kecil text-center"><?= $data['diameter_pangkal1'];?></td>										
                                                <td class="td-kecil text-center"><?= $data['diameter_ujung2'];?></td>										
                                                <td class="td-kecil text-center"><?= $data['diameter_pangkal2'];?></td>								
                                                <td class="td-kecil text-center"><?= $data['diameter_rata'];?></td>								
                                                <td class="td-kecil text-center"><?= $data['cacat_panjang'];?></td>								
                                                <td class="td-kecil text-center"><?= $data['cacat_gb'];?></td>								
                                                <td class="td-kecil text-center"><?= $data['cacat_gr'];?></td>								
                                                <td class="td-kecil text-right"><?= $data['volume'];?></td>	
                                                <td class="td-kecil text-center"><?= ($data['fsc'])?'FSC 100%':'Non FSC';?></td>								
                                            </tr>
										    <?php 
                                            }
										}else{
											"<tr><td colspan='25'>".Yii::t('app', 'Data tidak ditemukan')."</td></tr>";
										}
										?>
									</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>