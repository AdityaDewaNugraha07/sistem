<span class="preheader"><?= $params['judul'] ?></span>
<table role="presentation" class="main" style="border: #dadce0 solid 1px;">
    <!-- START MAIN CONTENT AREA -->
    <tr>
        <td><center><img src="http://103.255.240.80/cis/web/themes/metronic/cis/img/logo-login.png" style="width: 160px; margin-top: 10px; margin-bottom: -10px;"></center></td>
    </tr>
    <tr>
        <td class="wrapper">
            <center style="font-size: 1.2rem; color: #7C7C7C"><b><?= $params['judul'] ?></b></center><br>
            <p>
                Kepada Yth,<br>
                <b>Jajaran Management</b><br>
                Dengan hormat,
            </p>
            <p style="margin-bottom: 0px;">Kami informasikan bahwa terdapat Keputusan Pembelian Log Alam yang menunggu approval dengan detail sebagai berikut :</p>
            <?php
            $sql_t_approval = "select * from t_approval where reff_no = '".$model->kode."' and level = 2 ";
            $query_t_approval = Yii::$app->db->createCommand($sql_t_approval)->queryOne();
            $approved_by = $query_t_approval['approved_by'];

            $sql_supplier = "select suplier_nm_company from m_suplier where suplier_id = $model->suplier_id ";
            $supplier_nama = Yii::$app->db->createCommand($sql_supplier)->queryScalar();
            
            $sql_approval_2_nama = "select pegawai_nama from m_pegawai where pegawai_id = $approved_by ";
            $approval_2_nama = Yii::$app->db->createCommand($sql_approval_2_nama)->queryScalar();
            ?>
            <table style="width: 550px; margin-left: 20px; margin-bottom: 10px;">
                <tr>
                    <td style="width: 200px;">Kode / Tanggal</td>
                    <td style="width: 10px;">:</td>
                    <td><b><?= $model->kode." / ".app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal); ?></b></td>
                </tr>
                <tr>
                    <td style="width: 200px;">Nomor Kontrak</td>
                    <td style="width: 10px;">:</td>
                    <td><b><?php echo $model->nomor_kontrak;?></b></td>
                </tr>
                <tr>
                    <td style="width: 200px;">Volume Kontrak</td>
                    <td style="width: 10px;">:</td>
                    <td><b><?php echo app\components\DeltaFormatter::formatNumberForAllUser($model->volume_kontrak);?> m<sup>3</sup></b></td>
                </tr>
                <tr>
                    <td style="width: 200px;">Total Volume</td>
                    <td style="width: 10px;">:</td>
                    <td><b><?php echo app\components\DeltaFormatter::formatNumberForAllUser($model->total_volume);?> m<sup>3</sup></b></td>
                </tr>
                <tr>
                    <td style="width: 200px;">Waktu Penyerahan Awal-Akhir</td>
                    <td style="width: 10px;">:</td>
                    <td><b><?php echo app\components\DeltaFormatter::formatDateTimeForUser($model->waktu_penyerahan_awal)." - ".app\components\DeltaFormatter::formatDateTimeForUser($model->waktu_penyerahan_awal); ?></b></td>
                </tr>
                <tr>
                    <td style="width: 200px;">Supplier</td>
                    <td style="width: 10px;">:</td>
                    <td><b><?php echo $supplier_nama;?></b></td>
                </tr>                
                <tr>
                    <td style="width: 200px;">Asal Kayu</td>
                    <td style="width: 10px;">:</td>
                    <td><b><?php echo $model->asal_kayu;?></b></td>
                </tr>
                <tr>
                    <td style="width: 200px;">Status</td>
                    <td style="width: 10px;">:</td>
                    <td><b><?php echo $query_t_approval['status']." by ".$approval_2_nama;?></b></td>
                </tr>
            </table>
            Mohon kepada semua PIC yang terkait untuk meng-konfirmasi permintaan tersebut agar dapat melanjutkan ke proses selanjutnya.<br>
            Demikian informasi yang dapat kami sampaikan, Terimakasih.
        </td>
    </tr>
    <tr>
        <td>
            <p align="center">
                <a href="http://103.255.240.80" 
                   style="font-family:Arial,Helvetica,sans-serif;
                          font-size:15px;line-height:16px;
                          text-align:center;
                          color:#333333;
                          background: rgba(166, 192, 84, 0.2) none repeat scroll 0 0;
                          text-decoration:none;
                          border-radius:3px;padding:8px 10px;border:1px solid #90a746;display:inline-block" 
                    target="_blank" >Masuk CIS</a>
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px; color: #666"><small style="font-size: 0.7rem;"><i>* Email ini dikirim secara otomatis oleh system CIS.</i></small></td>
    </tr>
<!-- END MAIN CONTENT AREA -->
</table>