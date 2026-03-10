<?php 
namespace app\components;
/**
* Class untuk konverter format number dan date
* @author Arie Satriananta <ariesatriananta@yahoo.com>
*/
class DeltaFormatter extends \yii\helpers\BaseFormatConverter
{
    /**
     * untuk format database
     * @param mixed $inputdatetime = date or datetime
     * 1) d-M-Y | '15-Jan-2014'
     * 2) d-m-Y | '15-01-2014'
     * 3) Y-M-d | '2014-Jan-15'
     * 4) Y-m-d | '2014-01-15'
     * 5) d/M/Y | '15/Jan/2014'
     * 6) d/m/Y | '15/01/2014'
     * 7) Y/M/d | '2014/Jan/15'
     * 8) Y/m/d | '2014/01/15'
     * 9) d M Y | '15 Jan 2014'
     * 10) d m Y | '15 01 2014'
     * 11) Y M d | '2014 Jan 15'
     * 12) Y m d | '2014 01 15'
     * M = Jan = January = Januari
     * @return mixed $return Y-m-d | '2014-01-15'
     */
    public static function formatDateTimeForDb($inputdatetime)
    {
        if(empty($inputdatetime)) 
            return null;
        $datetime = explode('/',trim($inputdatetime));
        if(count($datetime) > 1)
            $inputdatetime = str_replace ("/", "-", $inputdatetime);
        $datetime = explode(' ',trim($inputdatetime));
        $return = null;
        if(strlen($datetime[0]) > 9){
            $date = explode('-',trim($datetime[0]));
            if(strlen($date[1]) > 2) 
                if(strlen($date[0])>3)//ex:2014-Jan-15
                    $return = $date[0]."-".self::getMonthDb($date[1])."-".self::getTwoDigit($date[2]).(isset($datetime[1]) ? " ".$datetime[1]:"");
                else//ex:15-Jan-2014
                    $return = $date[2]."-".self::getMonthDb($date[1])."-".self::getTwoDigit($date[0]).(isset($datetime[1]) ? " ".$datetime[1]:"");
            else{ 
                if(strlen($date[0])>3)//ex:2014-01-15
                    $return = $inputdatetime;
                else//ex:15-01-2014
                    $return = $date[2]."-".$date[1]."-".$date[0].(isset($datetime[1]) ? " ".$datetime[1]:"");
            }
        }else{
            if(strlen($datetime[0]) > 3){ //ex: 2014 Jan 15 | 2014 01 15
                $return = $datetime[0]."-".self::getMonthDb($datetime[1])."-".self::getTwoDigit($datetime[2]).(isset($datetime[3]) ? " ".$datetime[3]:"");
            }else{ //ex: 15 Jan 2014 | 15 01 2014
                $return = $datetime[2]."-".self::getMonthDb($datetime[1])."-".self::getTwoDigit($datetime[0]).(isset($datetime[3]) ? " ".$datetime[3]:"");
            }
        }
        return $return;
    }

    public static function formatDateTimesForDb($inputdatetime)
    {
        if(empty($inputdatetime)) 
            return null;
        $datetime = explode('/',trim($inputdatetime));
        if(count($datetime) > 1)
            $inputdatetime = str_replace ("/", "-", $inputdatetime);
        $datetime = explode(' ',trim($inputdatetime));
        $return = null;
        if(strlen($datetime[0]) > 9){
            $date = explode('-',trim($datetime[0]));
            if(strlen($date[1]) > 2) 
                if(strlen($date[0])>3)//ex:2014-Jan-15
                    $return = $date[0]."-".self::getMonthDb($date[1])."-".self::getTwoDigit($date[2]).(isset($datetime[1]) ? " ".$datetime[1]:"");
                else //ex:15-Jan-2014
                    $return = $date[2]."-".self::getMonthDb($date[1])."-".self::getTwoDigit($date[0]).(isset($datetime[1]) ? " ".$datetime[1]:"");
            else{ 
                if(strlen($date[0])>3)//ex:2014-01-15
                    $return = $inputdatetime;
                else//ex:15-01-2014
                    $return = $date[2]."-".$date[1]."-".$date[0].(isset($datetime[1]) ? " ".$datetime[1]:"");
            }
        }else{
            if(strlen($datetime[0]) > 3){ //ex: 2014 Jan 15 | 2014 01 15
                $return = $datetime[0]."-".self::getMonthDb($datetime[1])."-".self::getTwoDigit($datetime[2]).(isset($datetime[3]) ? " ".$datetime[3]:"");
            }else{ //ex: 15 Jan 2014 | 15 01 2014
                $return = $datetime[2]."-".self::getMonthDb($datetime[1])."-".self::getTwoDigit($datetime[0]).(isset($datetime[3]) ? " ".$datetime[3]:"");
            }
        }
        return $return;
    }    
    
    /**
     * merubah format bulan untuk database (tanpa hari)
     * @param type $inputmonth = "m-Y" | "M-Y" | "m Y" | "M Y" | "m/Y" | "M/Y" | "Y-m" | ...
     * @return type '2012-04'
     */
    public static function formatMonthForDb($inputmonth)
    {
        if(empty($inputmonth)) 
            return null;
        $return = null;
        $month = explode("/",$inputmonth);
        if(count($month) > 1)
            $inputmonth = str_replace ("/", "-", $inputmonth);
        $month = explode(' ', trim($inputmonth));
        if (count($month) == 1){
            $month = explode("-",$inputmonth);
        }
        if (count($month) > 1){
            if(is_numeric($month[0])){
                $return = $month[0]."-".self::getMonthDb($month[1]);
            }else{
                $return = $month[1]."-".self::getMonthDb($month[0]);
            }
        }
        return $return;
    }
    
    /**
     * untuk format user (indonesia)
     * @param string $inputdatetime = date or datetime
     * 1) d-M-Y | '15-Jan-2014'
     * 2) d-m-Y | '15-01-2014'
     * 3) Y-M-d | '2014-Jan-15'
     * 4) Y-m-d | '2014-01-15'
     * 5) d/M/Y | '15/Jan/2014'
     * 6) d/m/Y | '15/01/2014'
     * 7) Y/M/d | '2014/Jan/15'
     * 8) Y/m/d | '2014/01/15'
     * 9) d M Y | '15 Jan 2014'
     * 10) d m Y | '15 01 2014'
     * 11) Y M d | '2014 Jan 15'
     * 12) Y m d | '2014 01 15'
     * M = Jan = January = Januari
     * @return string $return d M Y | '15 Jan 2014' (Indonesia)
     */
    public static function formatDateTimeForUser($inputdatetime)
    {
        if (empty($inputdatetime)) {
            return null;
        }
        $datetime = explode('/', trim($inputdatetime));
        if (count($datetime) > 1) {
            $inputdatetime = str_replace("/", "-", $inputdatetime);
        }
        $datetime = explode(' ', trim($inputdatetime));
        if (strlen($datetime[0]) > 9) {
            $date = explode('-', trim($datetime[0]));
            if (strlen($date[1]) > 2) {
                if (strlen($date[0]) > 3)//ex:2014-Jan-15
                {
                    $return = $date[2] . " " . self::getMonthUser($date[1]) . " " . $date[0] . (isset($datetime[1]) ? " " . $datetime[1] : "");
                } else//ex:15-Jan-2014
                {
                    $return = $date[0] . " " . self::getMonthUser($date[1]) . " " . $date[2] . (isset($datetime[1]) ? " " . $datetime[1] : "");
                }
            } else if (strlen($date[0]) > 3)//ex:2014-01-15
            {
                $return = $date[2] . " " . self::getMonthUser($date[1]) . " " . $date[0] . (isset($datetime[1]) ? " " . $datetime[1] : "");
            } else//ex:15-01-2014
            {
                $return = $date[0] . " " . self::getMonthUser($date[1]) . " " . $date[2] . (isset($datetime[1]) ? " " . $datetime[1] : "");
            }
        } else if (strlen($datetime[0]) > 3) { //ex: 2014 Jan 15 | 2014 01 15
            $return = $datetime[2] . " " . self::getMonthUser($datetime[1]) . " " . $datetime[0] . (isset($datetime[3]) ? " " . $datetime[3] : "");
        } else { //ex: 15 Jan 2014 | 15 01 2014
            $return = $datetime[0] . " " . self::getMonthUser($datetime[1]) . " " . $datetime[2] . (isset($datetime[3]) ? " " . $datetime[3] : "");
        }
        return $return;
    }
	
	/**
     * untuk format user (indonesia)
     * @param string $inputdatetime = date or datetime
     * 1) d-M-Y | '15-Jan-2014'
     * 2) d-m-Y | '15-01-2014'
     * 3) Y-M-d | '2014-Jan-15'
     * 4) Y-m-d | '2014-01-15'
     * 5) d/M/Y | '15/Jan/2014'
     * 6) d/m/Y | '15/01/2014'
     * 7) Y/M/d | '2014/Jan/15'
     * 8) Y/m/d | '2014/01/15'
     * 9) d M Y | '15 Jan 2014'
     * 10) d m Y | '15 01 2014'
     * 11) Y M d | '2014 Jan 15'
     * 12) Y m d | '2014 01 15'
     * M = Jan = January = Januari
     * @return string $return d M Y | '15/01/2014' (Indonesia)
     */
    public static function formatDateTimeForUser2($inputdatetime)
    {
        if(empty($inputdatetime)) 
            return null;
        $datetime = explode('/',trim($inputdatetime));
        if(count($datetime) > 1)
            $inputdatetime = str_replace ("/", "-", $inputdatetime);
        $datetime = explode(' ',trim($inputdatetime));
        $return = null;
        if(strlen($datetime[0]) > 9){
            $date = explode('-',trim($datetime[0]));
			
            if(strlen($date[1]) > 2) {
                if(strlen($date[0])>3){//ex:2014-Jan-15
                    $return = $date[2]."/".$date[1]."/".$date[0].(isset($datetime[1]) ? " ".$datetime[1]:"");
				}else{ //ex:15-Jan-2014
                    $return = $date[0]."/".$date[1]."/".$date[2].(isset($datetime[1]) ? " ".$datetime[1]:"");
				}
			}else{
                if(strlen($date[0])>3){ //ex:2014-01-15
					$return = $date[2]."/".$date[1]."/".$date[0].(isset($datetime[1]) ? " ".$datetime[1]:"");
				}else{//ex:15-01-2014
                    $return = $date[0]."/".$date[1]."/".$date[2].(isset($datetime[1]) ? " ".$datetime[1]:"");
				}
            }
        }else{
            if(strlen($datetime[0]) > 3){ //ex: 2014 Jan 15 | 2014 01 15
                $return = $datetime[2]."/".$datetime[1]."/".$datetime[0].(isset($datetime[3]) ? " ".$datetime[3]:"");
            }else{ //ex: 15 Jan 2014 | 15 01 2014
                $return = $datetime[0]."/".$datetime[1]."/".$datetime[2].(isset($datetime[3]) ? " ".$datetime[3]:"");
            }
        }
        return $return;
    }
    
    public static function formatDateTimeDBTPHPLV($inputdatetime)
    {
        if(empty($inputdatetime)) 
            return null;
        
        // 2020-01-01 00:00:00
        if(strlen($inputdatetime) == 19){
            $datetime = explode(" ", $inputdatetime);
            $date = $datetime[0];
                $dates = explode("-",$datetime[0]);
                $tahun = $dates[0];
                $bulan = self::getMonthEn($dates[1]);
                $tanggal = $dates[2];
            $time = $datetime[1];
                $times = explode(":", $time);
                $jam = $times[0];
                $menit = $times[1];
                $detik = $times[2];
            $date_time = $tanggal." ".$bulan." ".$tahun." - ".$jam.":".$menit;
        } else {
            $date_time = '';
        }

        return $date_time;
    }
    
    /**
     * merubah format bulan untuk user (tanpa hari)
     * @param type $inputmonth = "m-Y" | "M-Y" | "m Y" | "M Y" | "m/Y" | "M/Y" | "Y-m" | ...
     * @return type 'Jan 2012'
     */
    public static function formatMonthForUser($inputmonth)
    {
        if(empty($inputmonth)) 
            return null;
        $return = null;
        $month = explode("/",$inputmonth);
        if(count($month) > 1)
            $inputmonth = str_replace ("/", "-", $inputmonth);
        $month = explode(' ', trim($inputmonth));
        if (count($month) == 1){
            $month = explode("-",$inputmonth);
        }
        if (count($month) > 1){
            if(strlen($month[0]) == 4){
                $return = self::getMonthUser($month[1])." ".$month[0];
            }else{
                $return = self::getMonthUser($month[0])." ".$month[1];
            }
        }
        return $return;
    }
    public static function formatMonthForUser2($inputmonth)
    {
        if(empty($inputmonth)) 
            return null;
        $return = null;
        $month = explode("/",$inputmonth);
        if(count($month) > 1)
            $inputmonth = str_replace ("/", "-", $inputmonth);
        $month = explode(' ', trim($inputmonth));
        if (count($month) == 1){
            $month = explode("-",$inputmonth);
        }
        if (count($month) > 1){
            if(strlen($month[0]) == 4){
                $return = self::getMonthDb($month[1])."/".$month[0];
            }else{
                $return = self::getMonthDb($month[0])."/".$month[1];
            }
        }
        return $return;
    }
    
    /**
     * untuk format user (indonesia)
     * @param type $inputdatetime = date or datetime
     * 1) d-M-Y | '15-Jan-2014'
     * 2) d-m-Y | '15-01-2014'
     * 3) Y-M-d | '2014-Jan-15'
     * 4) Y-m-d | '2014-01-15'
     * 5) d/M/Y | '15/Jan/2014'
     * 6) d/m/Y | '15/01/2014'
     * 7) Y/M/d | '2014/Jan/15'
     * 8) Y/m/d | '2014/01/15'
     * 9) d M Y | '15 Jan 2014'
     * 10) d m Y | '15 01 2014'
     * 11) Y M d | '2014 Jan 15'
     * 12) Y m d | '2014 01 15'
     * M = Jan = January = Januari
     * @return type $return '15 Januari 2014' (Indonesia)
     */
    public static function formatDateTimeId($inputdatetime)
    {
        if(empty($inputdatetime)) 
            return null;
        $datetime = explode('/',trim($inputdatetime));
        if(count($datetime) > 1)
            $inputdatetime = str_replace ("/", "-", $inputdatetime);
        $datetime = explode(' ',trim($inputdatetime));
        $return = null;
        if(strlen($datetime[0]) > 9){
            $date = explode('-',trim($datetime[0]));
            if(strlen($date[1]) > 2) 
                if(strlen($date[0])>3)//ex:2014-Jan-15
                    $return = $date[2]." ".self::getMonthId($date[1])." ".$date[0].(isset($datetime[1]) ? " ".$datetime[1]:"");
                else//ex:15-Jan-2014
                    $return = $date[0]." ".self::getMonthId($date[1])." ".$date[2].(isset($datetime[1]) ? " ".$datetime[1]:"");
            else{ 
                if(strlen($date[0])>3)//ex:2014-01-15
                    $return = $date[2]." ".self::getMonthId($date[1])." ".$date[0].(isset($datetime[1]) ? " ".$datetime[1]:"");
                else//ex:15-01-2014
                    $return = $date[0]." ".self::getMonthId($date[1])." ".$date[2].(isset($datetime[1]) ? " ".$datetime[1]:"");
            }
        }else{
            if(strlen($datetime[0]) > 3){ //ex: 2014 Jan 15 | 2014 01 15
                $return = $datetime[2]." ".self::getMonthId($datetime[1])." ".$datetime[0].(isset($datetime[3]) ? " ".$datetime[3]:"");
            }else{ //ex: 15 Jan 2014 | 15 01 2014
                $return = $datetime[0]." ".self::getMonthId($datetime[1])." ".$datetime[2].(isset($datetime[3]) ? " ".$datetime[3]:"");
            }
        }
        return $return;
    }
    
    /**
     * untuk format user (english)
     * @param type $inputdatetime = date or datetime
     * 1) d-M-Y | '15-Jan-2014'
     * 2) d-m-Y | '15-01-2014'
     * 3) Y-M-d | '2014-Jan-15'
     * 4) Y-m-d | '2014-01-15'
     * 5) d/M/Y | '15/Jan/2014'
     * 6) d/m/Y | '15/01/2014'
     * 7) Y/M/d | '2014/Jan/15'
     * 8) Y/m/d | '2014/01/15'
     * 9) d M Y | '15 Jan 2014'
     * 10) d m Y | '15 01 2014'
     * 11) Y M d | '2014 Jan 15'
     * 12) Y m d | '2014 01 15'
     * M = Jan = January = Januari
     * @return type $return '15 January 2014' (English)
     */
    public static function formatDateTimeEn($inputdatetime)
    {
        if(empty($inputdatetime)) 
            return null;
        $datetime = explode('/',trim($inputdatetime));
        if(count($datetime) > 1)
            $inputdatetime = str_replace ("/", "-", $inputdatetime);
        $datetime = explode(' ',trim($inputdatetime));
        $return = null;
        if(strlen($datetime[0]) > 9){
            $date = explode('-',trim($datetime[0]));
            if(strlen($date[1]) > 2) 
                if(strlen($date[0])>3)//ex:2014-Jan-15
                    $return = self::getMonthEn($date[1])." ".$date[2].", ".$date[0].(isset($datetime[1]) ? " ".$datetime[1]:"");
                else//ex:15-Jan-2014
                    $return = self::getMonthEn($date[1])." ".$date[0].", ".$date[2].(isset($datetime[1]) ? " ".$datetime[1]:"");
            else{ 
                if(strlen($date[0])>3)//ex:2014-01-15
                    $return = self::getMonthEn($date[1])." ".$date[2].", ".$date[0].(isset($datetime[1]) ? " ".$datetime[1]:"");
                else//ex:15-01-2014
                    $return = self::getMonthEn($date[1])." ".$date[0].", ".$date[2].(isset($datetime[1]) ? " ".$datetime[1]:"");
            }
        }else{
            if(strlen($datetime[0]) > 3){ //ex: 2014 Jan 15 | 2014 01 15
                $return = self::getMonthEn($datetime[1])." ".$datetime[2].", ".$datetime[0].(isset($datetime[3]) ? " ".$datetime[3]:"");
            }else{ //ex: 15 Jan 2014 | 15 01 2014
                $return = self::getMonthEn($datetime[1])." ".$datetime[0].", ".$datetime[2].(isset($datetime[3]) ? " ".$datetime[3]:"");
            }
        }
        return $return;
    }
    
    /**
     * @param type $month M = 1 | 01 | Jan | Januari | January
     * @return string m = 01
     */
    public static function getMonthDb($inputmonth)
    {
        if(empty($inputmonth)) 
            return null;
        $return = null;
        $month = strtolower(trim($inputmonth));
        switch($month){
            case '1' : $return = '01'; break;
            case 'jan' : $return = '01'; break;
            case 'januari' : $return = '01'; break;
            case 'january' : $return = '01'; break;
            case '2' : $return = '02'; break;
            case 'feb' : $return = '02'; break;
            case 'februari' : $return = '02'; break;
            case 'february' : $return = '02'; break;
            case '3' : $return = '03'; break;
            case 'mar' : $return = '03'; break;
            case 'maret' : $return = '03'; break;
            case 'march' : $return = '03'; break;
            case '4' : $return = '04'; break;
            case 'apr' : $return = '04'; break;
            case 'april' : $return = '04'; break;
            case '5' : $return = '05'; break;
            case 'mei' : $return = '05'; break;
            case 'may' : $return = '05'; break;
            case '6' : $return = '06'; break;
            case 'jun' : $return = '06'; break;
            case 'juni' : $return = '06'; break;
            case 'june' : $return = '06'; break;
            case '7' : $return = '07'; break;
            case 'jul' : $return = '07'; break;
            case 'juli' : $return = '07'; break;
            case 'july' : $return = '07'; break;
            case '8' : $return = '08'; break;
            case 'aug' : $return = '08'; break;
            case 'agt' : $return = '08'; break;
            case 'ags' : $return = '08'; break;
            case 'agu' : $return = '08'; break;
            case 'agus' : $return = '08'; break;
            case 'agustus' : $return = '08'; break;
            case 'august' : $return = '08'; break;
            case '9' : $return = '09'; break;
            case 'sep' : $return = '09'; break;
            case 'september' : $return = '09'; break;
            case 'okt' : $return = '10'; break;
            case 'oct' : $return = '10'; break;
            case 'oktober' : $return = '10'; break;
            case 'october' : $return = '10'; break;
            case 'nop' : $return = '11'; break;
            case 'nov' : $return = '11'; break;
            case 'nopember' : $return = '11'; break;
            case 'november' : $return = '11'; break;
            case 'des' : $return = '12'; break;
            case 'dec' : $return = '12'; break;
            case 'desember' : $return = '12'; break;
            case 'december' : $return = '12'; break;
            default : $return = $inputmonth; break;
        }
        return $return;
    }
    
    /**
     * Untuk menampilkan hari ini dalam bahasa indonesia
     * @param type $day w = 0 - 6
     * @return string D = Senin
     */
    public static function getDayUser($hari)
    {
        switch ($hari){
            case 0 : $hari= \Yii::t('app', 'Minggu');
                Break;
            case 1 : $hari= \Yii::t('app', 'Senin');
                Break;
            case 2 : $hari= \Yii::t('app', 'Selasa') ;
                Break;
            case 3 : $hari= \Yii::t('app', 'Rabu') ;
                Break;
            case 4 : $hari= \Yii::t('app', 'Kamis') ;
                Break;
            case 5 : $hari= \Yii::t('app', 'Jumat') ;
                Break;
            case 6 : $hari= \Yii::t('app', 'Sabtu') ;
                Break;
        }
        return $hari;
    }

    /**
     * @param type $month m = 01 | 1 | Jan | Januari | January
     * @return string M = Jan
     */
    public static function getMonthUser($inputmonth)
    {
        if(empty($inputmonth)) 
            return null;
        $return = null;
        $month = strtolower(trim($inputmonth));
        switch($month){
            case '1' : $return = 'Jan'; break;
            case '01' : $return = 'Jan'; break;
            case 'januari' : $return = 'Jan'; break;
            case 'january' : $return = 'Jan'; break;
            case '2' : $return = 'Feb'; break;
            case '02' : $return = 'Feb'; break;
            case 'februari' : $return = 'Feb'; break;
            case 'february' : $return = 'Feb'; break;
            case '3' : $return = 'Mar'; break;
            case '03' : $return = 'Mar'; break;
            case 'maret' : $return = 'Mar'; break;
            case 'march' : $return = 'Mar'; break;
            case '4' : $return = 'Apr'; break;
            case '04' : $return = 'Apr'; break;
            case 'april' : $return = 'Apr'; break;
            case '5' : $return = 'Mei'; break;
            case '05' : $return = 'Mei'; break;
            case 'may' : $return = 'Mei'; break;
            case '6' : $return = 'Jun'; break;
            case '06' : $return = 'Jun'; break;
            case 'juni' : $return = 'Jun'; break;
            case 'june' : $return = 'Jun'; break;
            case '7' : $return = 'Jul'; break;
            case '07' : $return = 'Jul'; break;
            case 'juli' : $return = 'Jul'; break;
            case 'july' : $return = 'Jul'; break;
            case '8' : $return = 'Agu'; break;
            case '08' : $return = 'Agu'; break;
            case 'agt' : $return = 'Agu'; break;
            case 'agu' : $return = 'Agu'; break;
            case 'aug' : $return = 'Agu'; break;
            case 'agustus' : $return = 'Agu'; break;
            case 'august' : $return = 'Agu'; break;
            case '9' : $return = 'Sep'; break;
            case '09' : $return = 'Sep'; break;
            case 'september' : $return = 'Sep'; break;
            case '10' : $return = 'Okt'; break;
            case 'oct' : $return = 'Okt'; break;
            case 'oktober' : $return = 'Okt'; break;
            case 'october' : $return = 'Okt'; break;
            case '11' : $return = 'Nov'; break;
            case 'nov' : $return = 'Nov'; break;
            case 'nopember' : $return = 'Nov'; break;
            case 'november' : $return = 'Nov'; break;
            case '12' : $return = 'Des'; break;
            case 'dec' : $return = 'Des'; break;
            case 'desember' : $return = 'Des'; break;
            case 'december' : $return = 'Des'; break;
            default : $return = $inputmonth; break;
        }
        return $return;
    }

    /**
     * Format bulan Indonesia
     * @param type $month m = 01 | 1 | Jan | Januari | January
     * @return string M = Januari
     */
    public static function getMonthId($inputmonth)
    {
        if(empty($inputmonth)) 
            return null;
        $return = null;
        $month = strtolower(trim($inputmonth));
        switch($month){
            case '1' : $return = 'Januari'; break;
            case '01' : $return = 'Januari'; break;
            case 'januari' : $return = 'Januari'; break;
            case 'january' : $return = 'Januari'; break;
            case '2' : $return = 'Februari'; break;
            case '02' : $return = 'Februari'; break;
            case 'februari' : $return = 'Februari'; break;
            case 'february' : $return = 'Februari'; break;
            case '3' : $return = 'Maret'; break;
            case '03' : $return = 'Maret'; break;
            case 'maret' : $return = 'Maret'; break;
            case 'march' : $return = 'Maret'; break;
            case '4' : $return = 'April'; break;
            case '04' : $return = 'April'; break;
            case 'april' : $return = 'April'; break;
            case '5' : $return = 'Mei'; break;
            case '05' : $return = 'Mei'; break;
            case 'may' : $return = 'Mei'; break;
            case '6' : $return = 'Juni'; break;
            case '06' : $return = 'Juni'; break;
            case 'juni' : $return = 'Juni'; break;
            case 'june' : $return = 'Juni'; break;
            case '7' : $return = 'Juli'; break;
            case '07' : $return = 'Juli'; break;
            case 'juli' : $return = 'Juli'; break;
            case 'july' : $return = 'Juli'; break;
            case '8' : $return = 'Agustus'; break;
            case '08' : $return = 'Agustus'; break;
            case 'agt' : $return = 'Agustus'; break;
            case 'agu' : $return = 'Agustus'; break;
            case 'aug' : $return = 'Agustus'; break;
            case 'agustus' : $return = 'Agustus'; break;
            case 'august' : $return = 'Agustus'; break;
            case '9' : $return = 'September'; break;
            case '09' : $return = 'September'; break;
            case 'september' : $return = 'September'; break;
            case '10' : $return = 'Oktober'; break;
            case 'oct' : $return = 'Oktober'; break;
            case 'oktober' : $return = 'Oktober'; break;
            case 'october' : $return = 'Oktober'; break;
            case '11' : $return = 'November'; break;
            case 'nov' : $return = 'November'; break;
            case 'nopember' : $return = 'November'; break;
            case 'november' : $return = 'November'; break;
            case '12' : $return = 'Desember'; break;
            case 'dec' : $return = 'Desember'; break;
            case 'desember' : $return = 'Desember'; break;
            case 'december' : $return = 'Desember'; break;
            default : $return = $inputmonth; break;
        }
        return $return;
    }
    
    /**
     * English long format month
     * @param type $month m = 01 | 1 | Jan | Januari | January
     * @return string M = January
     */
    public static function getMonthEn($inputmonth)
    {
        if(empty($inputmonth)) 
            return null;
        $return = null;
        $month = strtolower(trim($inputmonth));
        switch($month){
            case '1' : $return = 'January'; break;
            case '01' : $return = 'January'; break;
            case 'januari' : $return = 'January'; break;
            case 'january' : $return = 'January'; break;
            case '2' : $return = 'February'; break;
            case '02' : $return = 'February'; break;
            case 'februari' : $return = 'February'; break;
            case 'february' : $return = 'February'; break;
            case '3' : $return = 'March'; break;
            case '03' : $return = 'March'; break;
            case 'maret' : $return = 'March'; break;
            case 'march' : $return = 'March'; break;
            case '4' : $return = 'April'; break;
            case '04' : $return = 'April'; break;
            case 'april' : $return = 'April'; break;
            case '5' : $return = 'May'; break;
            case '05' : $return = 'May'; break;
            case 'may' : $return = 'May'; break;
            case '6' : $return = 'June'; break;
            case '06' : $return = 'June'; break;
            case 'juni' : $return = 'June'; break;
            case 'june' : $return = 'June'; break;
            case '7' : $return = 'July'; break;
            case '07' : $return = 'July'; break;
            case 'juli' : $return = 'July'; break;
            case 'july' : $return = 'July'; break;
            case '8' : $return = 'August'; break;
            case '08' : $return = 'August'; break;
            case 'agt' : $return = 'August'; break;
            case 'agu' : $return = 'August'; break;
            case 'aug' : $return = 'August'; break;
            case 'agustus' : $return = 'August'; break;
            case 'august' : $return = 'August'; break;
            case '9' : $return = 'September'; break;
            case '09' : $return = 'September'; break;
            case 'september' : $return = 'September'; break;
            case '10' : $return = 'October'; break;
            case 'oct' : $return = 'October'; break;
            case 'oktober' : $return = 'October'; break;
            case 'october' : $return = 'October'; break;
            case '11' : $return = 'November'; break;
            case 'nov' : $return = 'November'; break;
            case 'nopember' : $return = 'November'; break;
            case 'november' : $return = 'November'; break;
            case '12' : $return = 'December'; break;
            case 'dec' : $return = 'December'; break;
            case 'desember' : $return = 'December'; break;
            case 'december' : $return = 'December'; break;
            default : $return = $inputmonth; break;
        }
        return $return;
    }
    
    /**
     * set number ke 2 digit
     * @param type $input_number
     * @return string
     */
    public static function getTwoDigit($input_number){
        $return = "00";
        if(strlen(trim($input_number)) == 1){
            $return = "0".$input_number;
        }else{
            $return = substr($input_number, 0, 2);
        }
        return $return;
    }
    
    /**
     * merubah format number untuk database
     * @param type $input_number
     * 1) 1234567
     * 2) 1234567.89
     * 3) 1234567,89
     * 4) 1,234,567.89
     * 5) 1.234.567,89
     * @return 
     * 1) integer = 1234567
     * 2) double/float = 1234567.89
     */
    public static function formatNumberForDb($input_number){
        $return = null;
        $input_number = trim($input_number);
        $number_1 = explode(',',$input_number);
        $number_2 = explode('.',$input_number);
        if(count($number_1) == 2){ //ex: 1.253.432,76 | 12,569
            $number_1_1 = explode('.',$number_1[0]);
            $number_1_2 = explode('.',$number_1[1]);
            if(count($number_1_1) > 1)
                $return = str_replace ('.', '', $number_1[0]).".".str_replace ('.', '', $number_1[1]);
            else{
                if(count($number_1_2) > 1)
                    $return = str_replace (',', '', $input_number);
                else
                    $return = str_replace (',', '.', $input_number);
            }
        }else{
            if(count($number_2) == 2){ //ex: 1,253,432.76 | 12.679 | 125679
                $number_2_1 = explode(',',$number_2[0]);
                $number_2_2 = explode(',',$number_2[1]);
                if(count($number_2_1) > 1)
                    $return = str_replace (',', '', $number_2[0]).".".str_replace (',', '', $number_2[1]);
                else{
                    if(count($number_2_2) > 1)
                        $return = str_replace ('.', '', $input_number);
                    else
                        $return = str_replace (',', '.', $input_number);
                }
            }else{ //ex: 1234567
                if(count($number_1) < count($number_2))
                    $return = str_replace (',', '.', str_replace('.','',$input_number));
                else
                    $return = str_replace (',', '', $input_number);
            }
        }
        return floatval($return);
    }
	
	public static function formatNumberForDb2($input_number){
        $return = null;
        $return = str_replace (',', '', $input_number);
        return $return;
    }
	
    /**
     * Merubah format nomor negative User >:D
     * @param type $input_number
     * @return 12,345,678 | - 12,345,678  
     */
    public static function formatNumberForAllUser($input_number, $decimals = 0){
        $return = 0;
        if($input_number > 0){
            $return = number_format(str_replace(",","",trim($input_number)), (int)$decimals, '.', ',');
        }else{
            $return = number_format(str_replace(",","",trim($input_number)), (int)$decimals, '.', ',');
        }
        return $return;
    }

    /**
     * Merubah format nomor untuk User
     * @param int $input_number
     * @param int $decimals
     * @return int|string 12,345,678.91345 | 12,345,678
     */
    public static function formatNumberForUser($input_number, $decimals = 0){
        $return = 0;
        if($input_number > 0){
            $return = number_format(str_replace(",","",trim($input_number)), (int)$decimals, '.', ',');
        }else{
            $return = $input_number;
        }
        return $return;
    }

//    /**
//     * Merubah format nomor untuk User
//     * @param type $input_number
//     * @return 12,345,678.91345 | 12,345,678.91
//     */
//    public static function formatNumberForUserFloatxxx($input_number,$float_digit=null){
////		if(is_float($input_number) && (strpos($input_number,'.') !== false)){
//		if(isset($_GET['caraprint'])){
//            // Sudah distandartkan standarisasi internasional
////			if($_GET['caraprint'] == "EXCEL"){
////				return (strlen(substr(strrchr($input_number, "."), 1)) > 4)? $input_number*10000/10000:str_replace(".", ",", (string)$input_number);
////			}
//		}
//		if((strpos($input_number,'.') !== false)){
//			$jmldigit = strlen(substr(strrchr($input_number, "."), 1));
//			$jmldigit = (!empty($float_digit)?$float_digit:$jmldigit);
//			switch ( $jmldigit ){
//				case 1:
//					$return = self::formatNumberForUser(round($input_number,1),1);
//				break;
//				case 2:
//					$return = self::formatNumberForUser(round($input_number,2),2);
//				break;
//				case 3:
//					$return = self::formatNumberForUser(round($input_number,3),3);
//				break;
//				case 4:
//					$return = self::formatNumberForUser(round($input_number,4),4);
//				break;
//				case 5:
//					$return = self::formatNumberForUser(round($input_number,5),5);
//				break;
//				case 6:
//					$return = self::formatNumberForUser(round($input_number,6),6);
//				break;
//				case 7:
//					$return = self::formatNumberForUser(round($input_number,7),7);
//				break;
//				case 8:
//					$return = self::formatNumberForUser(round($input_number,8),8);
//				break;
//				case 9:
//					$return = self::formatNumberForUser(round($input_number,9),9);
//				break;
//				case 10:
//					$return = self::formatNumberForUser(round($input_number,10),10);
//				break;
//				case 11:
//					$return = self::formatNumberForUser(round($input_number,11),11);
//				break;
//				case 12:
//					$return = self::formatNumberForUser(round($input_number,12),12);
//				break;
//				case 13:
//					$return = self::formatNumberForUser(round($input_number,13),13);
//				break;
//				case 14:
//					$return = self::formatNumberForUser(round($input_number,14),14);
//				break;
//				case 15:
//					$return = self::formatNumberForUser(round($input_number,15),15);
//				break;
//				case 16:
//					$return = self::formatNumberForUser(round($input_number,16),16);
//				break;
//				case 17:
//					$return = self::formatNumberForUser(round($input_number,17),17);
//				break;
//				case 18:
//					$return = self::formatNumberForUser(round($input_number,18),18);
//				break;
//				case 19:
//					$return = self::formatNumberForUser(round($input_number,19),19);
//				break;
//				case 20:
//					$return = self::formatNumberForUser(round($input_number,20),20);
//				break;
//				case 21:
//					$return = self::formatNumberForUser(round($input_number,21),21);
//				break;
//				case 22:
//					$return = self::formatNumberForUser(round($input_number,22),22);
//				break;
//				case 23:
//					$return = self::formatNumberForUser(round($input_number,23),23);
//				break;
//				case 24:
//					$return = self::formatNumberForUser(round($input_number,24),24);
//				break;
//				case 25:
//					$return = self::formatNumberForUser(round($input_number,25),25);
//				break;
//				default : $return = self::formatNumberForUser(round($input_number,5),5); break;
//			}
//		}else{
//			$return = self::formatNumberForUser($input_number);
//		}
//        return $return;
//    }
    /**
     * Merubah format nomor untuk User (REWRITRE 2022-07-21)
     * @param float|int $input_number
     * @param null $float_digit
     * @return int|string 12,345,678.91345 | 12,345,678.91
     */

    public static function formatNumberForUserFloat($input_number, $float_digit = null)
    {
        if(strpos($input_number, '.')) {
            $jmldigit = $float_digit !== null ? $float_digit : strlen(substr(strrchr($input_number, "."), 1));
            return self::formatNumberForUser($input_number, $jmldigit);
        }

        return self::formatNumberForUser($input_number);
    }


    /**
     * Merubah format number untuk print
     * @param type $input_number
     * @return Rp. 12.000
     */
    public static function formatNumberForPrint($input_number, $decimals = 0){
        $return = number_format(str_replace(",","",trim($input_number)), (int)$decimals, ',', '.');
        return $return;
    }
    /**
     * Merubah format uang untuk print
     * @param type $input_number
     * @return Rp. 12.000
     */
    public static function formatUang($input_number, $mata_uang = "Rp.", $decimals = 0){
        $return = number_format(str_replace(",","",trim($input_number)), (int)$decimals, ',', '.');
        return $mata_uang." ".$return;
    }
    
    
    /**
     * Nilai Terbilang dari angka
     * @param type $input_number
     * @param type $style : 1=upper | 2=lower | 3=uppercase on first letter for each word | default=uppercase on first letter
     * @param type $strcomma
     * @return string
     */
    public static function formatNumberTerbilang($input_number, $style=4){
        $input_number = self::formatNumberForDb($input_number);
        if ($input_number < 0){
            $return = "minus " . trim(self::kataTerbilang($input_number));
        } else {
            $arrnum = explode('.', $input_number);
            $arrcount = count($arrnum);
            if ($arrcount == 1) {
                $return = trim(self::kataTerbilang($input_number));
            } else if ($arrcount > 1) {
                $return = trim(self::kataTerbilang($arrnum[0])) . " koma " . trim(self::kataTerbilang($arrnum[1]));
            }
			$return = $return." rupiah";
        } 
		if($input_number == 0) {
			$return = "-";
		}
		
        switch ($style){
            case 1: //1=uppercase  dan
                $return = strtoupper($return);
                break;
            case 2: //2= lowercase
                $return = strtolower($return);
                break;
            case 3: //3= uppercase on first letter for each word
                $return = ucwords($return);
                break;
            default: //4= uppercase on first letter
                $return = ucfirst($return);
                break;
        }
        
        return $return;
    }
	
	public static function kataTerbilang($input_number){
        $input_number = abs($input_number);
        $number = array("", \Yii::t('app', "satu"), \Yii::t('app', "dua"), \Yii::t('app', "tiga"), \Yii::t('app', "empat"), \Yii::t('app', "lima"),
            \Yii::t('app', "enam"), \Yii::t('app', "tujuh"), \Yii::t('app', "delapan"), \Yii::t('app', "sembilan"), \Yii::t('app', "sepuluh"), \Yii::t('app', "sebelas"));
        $temp = "";

        if ($input_number < 12) {
            $temp = " " . $number[$input_number];
        } else if ($input_number < 20) {
            $temp = self::kataTerbilang($input_number - 10) . " ".\Yii::t('app', 'belas');
        } else if ($input_number < 100) {
            $temp = self::kataTerbilang($input_number / 10) . " ".\Yii::t('app', 'puluh') . self::kataTerbilang($input_number % 10);
        } else if ($input_number < 200) {
            $temp = " ".\Yii::t('app', 'seratus') . self::kataTerbilang($input_number - 100);
        } else if ($input_number < 1000) {
            $temp = self::kataTerbilang($input_number / 100) . " ".\Yii::t('app', 'ratus') . self::kataTerbilang($input_number % 100);
        } else if ($input_number < 2000) {
            $temp = " ".\Yii::t('app', 'seribu') . self::kataTerbilang($input_number - 1000);
        } else if ($input_number < 1000000) {
            $temp = self::kataTerbilang($input_number / 1000) . " ".\Yii::t('app', 'ribu') . self::kataTerbilang($input_number % 1000);
        } else if ($input_number < 1000000000) {
            $temp = self::kataTerbilang($input_number / 1000000) . " ".\Yii::t('app', 'juta') . self::kataTerbilang($input_number % 1000000);
        } else if ($input_number < 1000000000000) {
            $temp = self::kataTerbilang($input_number / 1000000000) . " ".\Yii::t('app', 'milyar') . self::kataTerbilang(fmod($input_number, 1000000000));
        } else if ($input_number < 1000000000000000) {
            $temp = self::kataTerbilang($input_number / 1000000000000) . " ".\Yii::t('app', 'trilyun') . self::kataTerbilang(fmod($input_number, 1000000000000));
        }
        return $temp;
    }
	
    public static function formatNumberTerbilangDollar($input_number, $style=4){
        $slice_titik = explode('.',$input_number);
        $input_number = self::formatNumberForDb($input_number);
        if(!empty($slice_titik[1])){
            $input_number = explode('.',$input_number)[0].".".$slice_titik[1];
        }else{
            $input_number = str_replace('.','',$input_number);
        }
        
        if ($input_number < 0){
            $return = "minus " . trim(self::kataTerbilangEn($input_number));
        } else {
            $arrnum = explode('.', $input_number);
            $arrcount = count($arrnum);
            if ($arrcount == 1) {
                $return = trim(self::kataTerbilangEn($input_number));
            } else if ($arrcount > 1) {
                $return = trim(self::kataTerbilangEn($arrnum[0])) . " and " . trim(self::kataTerbilangEn($arrnum[1]))." cents ";
            }
            $return = "USD ".$return." dollars only.";
        } 
        if($input_number == 0) {
                $return = "-";
        }
		
        switch ($style){
            case 1: //1=uppercase  dan
                $return = strtoupper($return);
                break;
            case 2: //2= lowercase
                $return = strtolower($return);
                break;
            case 3: //3= uppercase on first letter for each word
                $return = ucwords($return);
                break;
            default: //4= uppercase on first letter
                $return = ucfirst($return);
                break;
        }
        
        return $return;
    }
	
	public static function kataTerbilangEn($x) {
	   $nwords = array( "zero", "one", "two", "three", "four", "five", "six", "seven",
						"eight", "nine", "ten", "eleven", "twelve", "thirteen",
						"fourteen", "fifteen", "sixteen", "seventeen", "eighteen",
						"nineteen", "twenty", 30 => "thirty", 40 => "forty",
						50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",
						90 => "ninety" );
					
		if(!is_numeric($x)){
			$w = '#';
		}else if(fmod($x, 1) != 0){
			$w = '#';
		}else{
			if(is_string($x)){
				$x = (int)$x;
			}
			if($x < 0) {
			   $w = 'minus ';
			   $x = -$x;
			} else
			   $w = '';
			// ... now $x is a non-negative integer.

			if($x < 21)   // 0 to 20
			   $w .= $nwords[$x];
			else if($x < 100) {   // 21 to 99
			   $w .= $nwords[10 * floor($x/10)];
			   $r = fmod($x, 10);
			   if($r > 0)
				  $w .= '-'. $nwords[$r];
			} else if($x < 1000) {   // 100 to 999
			   $w .= $nwords[floor($x/100)] .' hundred';
			   $r = fmod($x, 100);
			   if($r > 0)
				  $w .= ' '. self::kataTerbilangEn($r);
			} else if($x < 1000000) {   // 1000 to 999999
			   $w .= self::kataTerbilangEn(floor($x/1000)) .' thousand';
			   $r = fmod($x, 1000);
			   if($r > 0) {
				  $w .= ' ';
				  if($r < 100)
					 $w .= ' ';
				  $w .= self::kataTerbilangEn($r);
			   }
			} else {    //  millions
			   $w .= self::kataTerbilangEn(floor($x/1000000)) .' million';
			   $r = fmod($x, 1000000);
			   if($r > 0) {
				  $w .= ' ';
				  if($r < 100)
					 $word .= ' ';
				  $w .= self::kataTerbilangEn($r);
			   }
			}
		}
		
		return $w;
	}
    
    /**
     * menampilkan urutan (text) dari number
     * @param type $input_number
     * @return string | ex: "Ketiga"
     */
    public static function formatNumberForUrutanText($input_number){
        $text_number = self::kataTerbilang($input_number);
        return "Ke".$text_number;
    }

    /**
     * memberikan nama hari berdasarkan tanggal
     * @param type $date
     * @return string() | ex: "Senin"
     */
    public static function getDayName($date){
        $dayName = date('l',strtotime($date));
        $namaHari = '';
        $namaHari = ($dayName=='Sunday')?'Minggu':$namaHari;
        $namaHari = ($dayName=='Monday')?'Senin':$namaHari;
        $namaHari = ($dayName=='Tuesday')?'Selasa':$namaHari;
        $namaHari = ($dayName=='Wednesday')?'Rabu':$namaHari;
        $namaHari = ($dayName=='Thursday')?'Kamis':$namaHari;
        $namaHari = ($dayName=='Friday')?'Jumat':$namaHari;
        $namaHari = ($dayName=='Saturday')?'Sabtu':$namaHari;
        return $namaHari;
    }
	
	public static function formatNumberForExcel($input_number){
//		if($_GET['caraprint'] == "EXCEL"){
//			return (strlen(substr(strrchr($input_number, "."), 1)) > 4)? $input_number*10000/10000:str_replace(".", ",", (string)$input_number);
//		}
	}
	
	public static function roundUp($value, $precision){
		$pow = pow ( 10, $precision ); 
		return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
	}

    public static function satuanMonitoring($value)
    {
        switch($value) {
            case 'm3':
                return " m<sup>3</sup>";
            case 'prosentase':
                return "%";
            case "jam":
                return " jam";
            case "patch":
                return " patch";
            case "pcs":
                return " pcs";
            default:
                return " " .$value;
        }
    }

    public static function renderDataMonitoring($data, $satuan, $grade)
    {
        $value = number_format(round($data, 2), 2, ".", ",");
        if(in_array($grade, ['INPUT', 'OUTPUT'])) {
            return '';
        }

        $content = '';

        if($satuan !== '') {
            $content = "<td style='border: none; text-align: right; padding-right: 2px;'>$value</td><td style='border: none; text-align: left; padding-left: 2px;'>". self::satuanMonitoring($satuan) ."</td>";
        }else {
            $content = "<td style='border: none; text-align: center;'>$value</td>";
        }
        return "
            <table style='border: none; width: 100%'>
                <tr>
                    $content
                </tr>
            </table>
        ";
    }

    public static function formatNumberTerbilangEurCny($input_number, $mata_uang, $style=4){
        $slice_titik = explode('.',$input_number);
        $input_number = self::formatNumberForDb($input_number);
        if(!empty($slice_titik[1])){
            $input_number = explode('.',$input_number)[0].".".$slice_titik[1];
        }else{
            $input_number = str_replace('.','',$input_number);
        }
        
        if ($input_number < 0){
            $return = "minus " . trim(self::kataTerbilangEn($input_number));
        } else {
            $arrnum = explode('.', $input_number);
            $arrcount = count($arrnum);
            if ($arrcount == 1) {
                $return = trim(self::kataTerbilangEn($input_number));
            } else if ($arrcount > 1) {
                $return = trim(self::kataTerbilangEn($arrnum[0])) . " and " . trim(self::kataTerbilangEn($arrnum[1]))." cents ";
            }
            if($mata_uang == "EUR"){
                $return = $mata_uang. " " .$return." euro only.";
            } else if ($mata_uang== "CNY"){
                $return = $mata_uang. " " .$return." yuan only.";
            }
            
        } 
        if($input_number == 0) {
                $return = "-";
        }
		
        switch ($style){
            case 1: //1=uppercase  dan
                $return = strtoupper($return);
                break;
            case 2: //2= lowercase
                $return = strtolower($return);
                break;
            case 3: //3= uppercase on first letter for each word
                $return = ucwords($return);
                break;
            default: //4= uppercase on first letter
                $return = ucfirst($return);
                break;
        }
        
        return $return;
    }

    public static function hurufPotong($number) {
        $result = '';
        while ($number > 0) {
            $mod = ($number - 1) % 26;
            $result = chr(65 + $mod) . $result;
            $number = intval(($number - $mod) / 26);
        }
        return $result;
    }
    
}
?>
