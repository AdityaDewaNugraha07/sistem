<?php
namespace app\commands;
use yii\console\Controller;
use Yii;

// Command Line : yii sendmail/exportreminder

class SendmailController extends Controller
{
	public function actionExportreminder(){
        
        $this->layout = '@views/layouts/metronic/email';
        
        $toDay=date('Y-m-d');$current_date=date('Y-m-d');
        $xselisih=0;
        $settingemail = Yii::$app->db->createCommand("select * from view_alert_export order by alert_id asc")->queryAll();

        foreach($settingemail as $i => $a){
            if($a['alert_asc']=='1'){
                $jnsField = "etd_tgl";
                $elseField = "and emaildoc_jns='TT' and emaildoc_tgl IS NULL";
            }elseif($a['alert_asc']=='2'){
                $jnsField="etd_tgl";
                $elseField = "and emaildoc_jns='LC' and emaildoc_tgl IS NULL";
            }elseif($a['alert_asc']=='3'){
                $jnsField = "emaildoc_tgl";
                $elseField = "and emaildoc_jns='TT' and ttcopy_tgl IS NULL";
            }elseif($a['alert_asc']=='4'){
                $jnsField="ttcopy_tgl";
                $elseField = " and emaildoc_jns='TT' and (dp_uangmasuk_jml+uangmasuk_jml)='0'";   
            }elseif($a['alert_asc']=='5'){
                $jnsField = "emaildoc_tgl";
                $elseField = " and emaildoc_jns='LC' and (dp_uangmasuk_jml+uangmasuk_jml)='0'";
            }

            $mods = Yii::$app->db->createCommand("select to_char(DATE(staffing_tgl), 'DD/MM/YYYY') as staffing,
                                                to_char(DATE(etd_tgl), 'DD/MM/YYYY') as etd,to_char(DATE(eta_tgl), 'DD/MM/YYYY') as eta,
                                                to_char(DATE(bayar_tgl), 'DD/MM/YYYY') as bayar,to_char(DATE(emaildoc_tgl), 'DD/MM/YYYY') as emaildoc,
                                                to_char(DATE(ttcopy_tgl), 'DD/MM/YYYY') as ttcopy,to_char(DATE(uangmasuk_tgl), 'DD/MM/YYYY') as uangmasuk,* from 
                                                view_alert_docexport where {$jnsField} {$a['alert_sum']} {$a['alert_jml']}<='{$current_date}' {$elseField} 
                                                order by customer_nm, customer_invoice, staffing_tgl asc")->queryAll();
            foreach($mods as $ii => $xd){
                $xselishHari =0; $Keterlambatan = "";
                $xtglN = "$xd[$jnsField]";
                $xselishHari= $this->selisihHari($xtglN,$current_date);
                if($a['alert_jml']<=$xselishHari){ //echo"<br>telat<br>";		
                    $xke=$xselishHari - $a['alert_jml'];
                    if($xke==0){
                        $params['xkali'] = "1";
                    }else{
                        $xkalinya = $xke+1;
                        $params['xkali'] = "$xkalinya";
                    }
                    
                    $qqs = Yii::$app->db->createCommand("select * from view_alert_export order by alert_id asc")->queryAll();
                    foreach($qqs as $iii => $qq){
                        $xsqe = Yii::$app->db->createCommand("select email_almt from view_email_export")->queryAll();
                        $mailto = [];
                        foreach($xsqe as $i => $emailnya){
                            foreach($emailnya as $email_almt){
                                $mailto[] = $email_almt;
                            }
                        }
                    }
                    $DocKet="";$Dpmasuk="";
                    if($a['alert_asc']==1){

                    }elseif($a['alert_asc']==2){

                    }elseif($a['alert_asc']==3){
                        $DocKet="Email Doc TT tanggal : <b>{$xd['emaildoc']}</b><br>";	
                        if(!empty($xd['alertex_ket_emaildoc'])){
                            $Keterlambatan="Keterangan : <b>{$xd['alertex_ket_emaildoc']}</b><br>";
                        }else{
                            $Keterlambatan="";
                        }
                    }elseif($a['alert_asc']==4){
                        $DocKet="Email Doc TT tanggal : <b>{$xd['emaildoc']}</b><br>TT Coppy Tanggal : <b>{$xd['ttcopy']}</b><br>";	
                        if(!empty($xd['alertex_ket_ttcopy'])){
                            $Keterlambatan="Keterangan : <b>{$xd['alertex_ket_ttcopy']}</b><br>";
                        }else{
                            $Keterlambatan="";
                        }
                        if(isset($xd['dp_uang_masuk_jml']) && $xd['dp_uang_masuk_jml']>0){
                            $Dpmasuk=" Telah diterima DP sebesar {$xd['dp_uangmasuk_jml']}, ";
                        }else{
                            $Dpmasuk="";
                        }
                    }elseif($a['alert_asc']==5){
                        $DocKet="Email Doc tanggal : <b>{$xd['emaildoc']}</b><br>";
                        if(!empty($xd['alertex_ket_ttcopy'])){
                            $Keterlambatan="Keterangan : <b>{$xd['alertex_ket_ttcopy']}</b><br>";
                        }else{
                            $Keterlambatan="";
                        }
                        if(isset($xd['dp_uang_masuk_jml']) && $xd['dp_uang_masuk_jml']>0){
                            $Dpmasuk=" Telah diterima DP sebesar {$xd['dp_uangmasuk_jml']}, ";
                        }else{
                            $Dpmasuk="";
                        }
                    }
                    
                    $params['xd'] = $xd;
                    $params['DocKet'] = $DocKet;
                    $params['Keterlambatan'] = $Keterlambatan;
                    $params['Dpmasuk'] = $Dpmasuk;
                    $params['a'] = $a;
                    $msg = $this->renderPartial('exportReminderContent',['params'=>$params]);
                    $subjek = $xd['customer_nm']."({$xd['customer_invoice']}) (CIS Export Reminder)";
                    $email = 'asd';
//                    $email = \Yii::$app->mailer->compose('@app/views/sendmail/exportReminder', ['msg' => $msg,'mailto'=>$mailto])
//                                ->setFrom('it.ciptana@gmail.com')
//                                ->setTo($mailto)
//                                ->setSubject($subjek)
//                                ->send();
                    if($email){
                        echo implode(", ", $mailto)."<br><b>(berhasil)</b><br>";
                        $modEmail = new \app\models\TEmail();
                        $modEmail->alertex_id = $xd['alertex_id'];
                        $modEmail->temail_tgl = $toDay;
                        if($modEmail->validate()){
                            if($modEmail->save()){
                                echo "data berhasil disimpan t_email";
                            }
                        }
                    }else{
                        echo"<br><p>(gagal)<br><p>";
                    }
                }
            }
        }
//        return $this->render('@app/views/sendmail/exportReminder',['msg' => $msg,'mailto'=>$mailto]);
    }
    
    public function render($path, array $data = array()){
        $path = $path.".php";
        return $this->renderFile($path, $data, true);
    }
    
    function selisihHari($tglAwal, $tglAkhir)
    {
        // list tanggal merah selain hari minggu
        //$tglLibur = Array("01/01/2018", "16/02/2018", "17/03/2018", "30/03/2018", "13/06/2018", "14/06/2018", "15/06/2018", "16/06/2018", "17/06/2018", "18/06/2018", "19/06/2018", "17/08/2018", "22/08/2018", "11/09/2018", "20/11/2018", "25/12/2018");
        $tglLibur = Array('$hariLibur');
        // memecah string tanggal awal untuk mendapatkan
        // tanggal, bulan, tahun
        $pecah1 = explode("-", $tglAwal);
        $date1 = $pecah1[2];
        $month1 = $pecah1[1];
        $year1 = $pecah1[0];

        // memecah string tanggal akhir untuk mendapatkan
        // tanggal, bulan, tahun
        $pecah2 = explode("-", $tglAkhir);
        $date2 = $pecah2[2];
        $month2 = $pecah2[1];
        $year2 =  $pecah2[0];

        // mencari selisih hari dari tanggal awal dan akhir
        $jd1 = GregorianToJD($month1, $date1, $year1);
        $jd2 = GregorianToJD($month2, $date2, $year2);

        $selisih = $jd2 - $jd1;

        $libur1=0;
        $libur2=0;

        // proses menghitung tanggal merah dan hari minggu
        // di antara tanggal awal dan akhir
        for($i=1; $i<=$selisih; $i++)
        {
            // menentukan tanggal pada hari ke-i dari tanggal awal
            $tanggal = mktime(0, 0, 0, $month1, $date1+$i, $year1);
            $tglstr = date("Y-m-d", $tanggal);

            // menghitung jumlah tanggal pada hari ke-i
            // yang masuk dalam daftar tanggal merah selain minggu

            if(in_array($tglstr, $tglLibur))
            {
               $libur1++;
            }

            // menghitung jumlah tanggal pada hari ke-i
            // yang merupakan hari minggu
            if((date("N", $tanggal) == 7) OR (date("N", $tanggal) == 6))
            {
               $libur2++;
            }

        }

        // menghitung selisih hari yang bukan tanggal merah dan hari minggu
        return $selisih-$libur1-$libur2;//-$libur3
    }
    
}
