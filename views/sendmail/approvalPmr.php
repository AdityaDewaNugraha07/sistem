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
            <p style="margin-bottom: 0px;">Kami informasikan bahwa permintaan pembelian log :</p>
            <table style="width: 100%; margin-left: 20px; margin-bottom: 10px;">
                <tr>
                    <td style="width: 100px;">Kode / Tanggal</td>
                    <td style="width: 10px;">:</td>
                    <td><b><?= $model->kode." / ".app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal); ?></b></td>
                </tr>
                <tr>
                    <td>Jenis Log</td>
                    <td>:</td>
                    <td><b><?= ($model->jenis_log=="LA")?"Log Alam (UNTUK ".$model->tujuan.")":""; ?></b></td>
                </tr>
                <?php
                $approver_1 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->approver_1)->queryOne();
                $approver_2 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->approver_2)->queryOne();
                $approver_3 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->approver_3)->queryOne();
                if(!empty($model->approver_4)){
                    $approver_4 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->kode}' AND assigned_to = ".$model->approver_4)->queryOne();
                }
                
                ?>
                <tr>
                    <td style="line-height: 0.9; padding-top: 5px;">Approver 1<br><?= ($model->tujuan=="TRADING")?"<span style='font-size:0.7rem;'><i>(Kadiv Marketing)</i></span>":"<span style='font-size:0.7rem;'><i>(Kadiv Operasional)</i></span>" ?></td>
                    <td>:</td>
                    <td style="line-height: 1; padding-top: 5px;"><b><?= $model->approver1->pegawai_nama; ?></b>
                        <?php if($approver_1['status']==\app\models\TApproval::STATUS_APPROVED){
                            echo " <br><span style='color:#4db3a2; font-size:0.6rem; padding:3px;'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
                        }else if($approver_1['status']==\app\models\TApproval::STATUS_REJECTED){
                            echo " <br><span style='color:#e26a6a; font-size:0.6rem; padding:3px;'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
                            $reasons = \yii\helpers\Json::decode($approver_1['keterangan']);
                            if(count($reasons)>0){
                                foreach($reasons as $i => $reason){
                                    if($reason['by']==$approver_1['assigned_to']){
                                        echo " <br><span style='color:#e26a6a; font-size:0.6rem;'> Reason : <i>".$reason['reason']."</i></span>";
                                    }
                                }
                            }
                        }else {
                            echo "<br><span style='color:#bac3d0; font-size:0.6rem; padding:3px;'>Not Confirm</span>";
                        }?>
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 0.9; padding-top: 5px;">Approver 2<br><?= ($model->tujuan=="TRADING")?"<span style='font-size:0.7rem;'><i>(GM Operasional)</i></span>":"<span style='font-size:0.7rem;'><i>(GM Operasional)</i></span>" ?></td>
                    <td>:</td>
                    <td style="line-height: 1; padding-top: 5px;"><b><?= $model->approver2->pegawai_nama; ?></b>
                        <?php if($approver_2['status']==\app\models\TApproval::STATUS_APPROVED){
                            echo " <br><span style='color:#4db3a2; font-size:0.6rem; padding:3px;'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
                        }else if($approver_2['status']==\app\models\TApproval::STATUS_REJECTED){
                            echo " <br><span style='color:#e26a6a; font-size:0.6rem; padding:3px;'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
                            $reasons = \yii\helpers\Json::decode($approver_2['keterangan']);
                            if(count($reasons)>0){
                                foreach($reasons as $i => $reason){
                                    if($reason['by']==$approver_2['assigned_to']){
                                        echo " <br><span style='color:#e26a6a; font-size:0.6rem;'> Reason : <i>".$reason['reason']."</i></span>";
                                    }
                                }
                            }
                        }else {
                            echo "<br><span style='color:#bac3d0; font-size:0.6rem; padding:3px;'>Not Confirm</span>";
                        }?>
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 0.9; padding-top: 5px; padding-bottom: 5px;">Approver 3<br><?= ($model->tujuan=="TRADING")?"<span style='font-size:0.7rem;'><i>(Direktur Utama)</i></span>":"<span style='font-size:0.7rem;'><i>(Direktur Utama)</i></span>" ?></td>
                    <td>:</td>
                    <td style="line-height: 1; padding-top: 5px; padding-bottom: 5px;"><b><?= $model->approver3->pegawai_nama; ?></b>
                        <?php if($approver_3['status']==\app\models\TApproval::STATUS_APPROVED){
                            echo " <br><span style='color:#4db3a2; font-size:0.6rem; padding:3px;'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
                        }else if($approver_3['status']==\app\models\TApproval::STATUS_REJECTED){
                            echo " <br><span style='color:#e26a6a; font-size:0.6rem; padding:3px;'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
                            $reasons = \yii\helpers\Json::decode($approver_3['keterangan']);
                            if(count($reasons)>0){
                                foreach($reasons as $i => $reason){
                                    if($reason['by']==$approver_3['assigned_to']){
                                        echo " <br><span style='color:#e26a6a; font-size:0.6rem;'> Reason : <i>".$reason['reason']."</i></span>";
                                    }
                                }
                            }
                        }else {
                            echo "<br><span style='color:#bac3d0; font-size:0.6rem; padding:3px;'>Not Confirm</span>";
                        } ?>
                    </td>
                </tr>
                <?php if(!empty($model->approver_4)){ ?>
                <tr>
                    <td style="line-height: 0.9; padding-top: 5px; padding-bottom: 5px;">Approver 4<br><span style='font-size:0.7rem;'><i>(Owner)</i></span></td>
                    <td>:</td>
                    <td style="line-height: 1; padding-top: 5px; padding-bottom: 5px;"><b><?= $model->approver4->pegawai_nama; ?></b>
                        <?php if($approver_4['status']==\app\models\TApproval::STATUS_APPROVED){
                            echo " <br><span style='color:#4db3a2; font-size:0.6rem; padding:3px;'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
                        }else if($approver_4['status']==\app\models\TApproval::STATUS_REJECTED){
                            echo " <br><span style='color:#e26a6a; font-size:0.6rem; padding:3px;'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
                            $reasons = \yii\helpers\Json::decode($approver_4['keterangan']);
                            if(count($reasons)>0){
                                foreach($reasons as $i => $reason){
                                    if($reason['by']==$approver_4['assigned_to']){
                                        echo " <br><span style='color:#e26a6a; font-size:0.6rem;'> Reason : <i>".$reason['reason']."</i></span>";
                                    }
                                }
                            }
                        }else {
                            echo "<br><span style='color:#bac3d0; font-size:0.6rem; padding:3px;'>Not Confirm</span>";
                        } ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td>Detail Log</td>
                    <td>:</td>
                    <td style="line-height: 1">
                        <?php
                        $mod = Yii::$app->db->createCommand("SELECT group_kayu, kayu_nama, SUM(qty_m3) AS qty_m3 FROM t_pmr_detail 
                                                            JOIN m_kayu ON m_kayu.kayu_id = t_pmr_detail.kayu_id
                                                            WHERE pmr_id=".$model->pmr_id." GROUP BY 1,2")->queryAll();
                        foreach($mod as $i => $detail){
                            echo ' <b>'.($i+1).'. '.$detail['group_kayu'].' - '.$detail['kayu_nama'].'</b> ('.$detail['qty_m3'].' M<sup>3</sup>)<br>';
                        }
                        ?>
                    </td>
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