<table class="table table-striped table-bordered table-hover" id="table-laporan">
    <thead>
        <tr>
            <th style="line-height: 1; width: 40px; padding: 5px;">No.</th>
            <th style="line-height: 1; padding: 5px;"> Jenis Mutasi</th>
            <th style="line-height: 1; padding: 5px;"> Movement </th>
            <th style="line-height: 1; width: 120px; padding: 5px;"> Kode Mutasi</th>
            <th style="line-height: 1; width: 100px; padding: 5px;"> Tanggal<br>Mutasi</th>
            <th style="line-height: 1; width: 80px; padding: 5px;"> Panjang </th>
            <th style="line-height: 1; width: 80px; padding: 5px;"> Diameter </th>
            <th style="line-height: 1; width: 80px; padding: 5px;"> Pcs </th>
            <th style="line-height: 1; width: 100px; padding: 5px;"> M<sup>3</sup> </th>
            <th style="line-height: 1; width: 50px; padding: 5px;">  </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $tgl_awal = $model->tgl_awal;
        $tgl_akhir = $model->tgl_akhir;
        $modMutasi = app\models\TMutasiSengon::find()->where("cancel_transaksi_id IS NULL")->andWhere("tanggal between '".$tgl_awal."' and '".$tgl_akhir."' ")->orderBy("created_at DESC")->all();
        if(count($modMutasi)){
            foreach($modMutasi as $i => $mutasi){
                echo "<tr>";
                echo    "<td style='text-align:center'>".($i+1)."</td>";
                echo    "<td style='text-align:left'>".$mutasi->jenis_mutasi."</td>";
                echo    "<td style='text-align:left'>".$mutasi->dari." --> ".$mutasi->ke."</td>";
                echo    "<td style='text-align:center'>".$mutasi->kode."</td>";
                echo    "<td style='text-align:center'>".\app\components\DeltaFormatter::formatDateTimeForUser2($mutasi->tanggal)."</td>";
                echo    "<td style='text-align:right'>".$mutasi->panjang." Cm</td>";
                echo    "<td style='text-align:right'>".$mutasi->diameter." Cm</td>";
                echo    "<td style='text-align:right'>". number_format($mutasi->pcs)." Btg</td>";
                echo    "<td style='text-align:right'>". number_format($mutasi->m3,3)." m<sup>3</sup></td>";
                echo    '<td style="text-align:center"><a class="btn btn-xs btn-outline red-flamingo tooltips" onclick="openModal(\''.\yii\helpers\Url::toRoute(['/ppic/mutasikeluarsengon/delete','id'=>$mutasi->mutasi_sengon_id,'tableid'=>'table-master']).'\',\'modal-delete-record\')" data-original-title="Hapus Mutasi"><i class="icon-trash"></i></a></td>';
                echo "</tr>";
            }
        }else{
            echo "<tr><td colspan='10' style='text-align:center'><i>Data tidak ditemukan.</i></td></tr>";
        }
        ?>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>