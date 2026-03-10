<?php 
namespace app\components;
use app\models\MPegawai;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Console\Helper\Helper;
use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
* Class untuk custom format tertentu
* @author Arie Satriananta <ariesatriananta@yahoo.com>
*/
class DeltaFunctions 
{
	public static function ReadFileExcel($model,$varfile){
		$model->$varfile =  \yii\web\UploadedFile::getInstance($model, 'file');
		if(!empty($model->$varfile)){
			$randomstring = Yii::$app->getSecurity()->generateRandomString(4);
			$dir_path = Yii::$app->basePath.'/web/temp';
			if(!is_dir($dir_path)){
				mkdir($dir_path);
			}
			$file_path = $dir_path.'/'.date('Ymd_His').'-xlsimport-'.$randomstring.'.' . $model->$varfile->extension;
			$model->$varfile->saveAs($file_path,false);
			$objPHPExcel = \PHPExcel_IOFactory::load($file_path);
			$dataArr = array();
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
				for ($row = 1; $row <= $highestRow; ++ $row) {
					for ($col = 0; $col < $highestColumnIndex; ++ $col) {
						$cell = $worksheet->getCellByColumnAndRow($col, $row);
						$val = $cell->getValue();
						$dataArr[$row][$col] = $val;
						if(strstr($dataArr[$row][$col],'=')==true){
							$dataArr[$row][$col] = $worksheet->getCellByColumnAndRow($col, $row)->getOldCalculatedValue();
						}
					}
				}
			}
			$return = $dataArr;
			unlink($file_path);
		}else{
			$return = false;
		}
		return $return;
	}
	
	
	/*
     * ubah angka ke Romawi 
     */
    public static function Romawi($n){
		$n=(int)$n;
        $hasil = "";
        $iromawi =
        array("","I","II","III","IV","V","VI","VII","VIII","IX","X",
        20=>"XX",30=>"XXX",40=>"XL",50=>"L",60=>"LX",70=>"LXX",80=>"LXXX",
        90=>"XC",100=>"C",200=>"CC",300=>"CCC",400=>"CD",500=>"D",
        600=>"DC",700=>"DCC",800=>"DCCC",900=>"CM",1000=>"M",
        2000=>"MM",3000=>"MMM");

            if(array_key_exists($n,$iromawi)){
            $hasil = $iromawi[$n];
            }elseif($n >= 11 && $n <= 99){
            $i = $n % 10;
            $hasil = $iromawi[$n-$i] . self::Romawi($n % 10);
            }elseif($n >= 101 && $n <= 999){
            $i = $n % 100;
            $hasil = $iromawi[$n-$i] . self::Romawi($n % 100);
            }else{
            $i = $n % 1000;
            $hasil = $iromawi[$n-$i] . self::Romawi($n % 1000);
        }
        return $hasil;
    }
	
	
	public static function kursNow(){
		/*$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, 'http://www.adisurya.net/kurs-bca/get?MataUang=USD');
		$kurs_bca = curl_exec($ch);
		curl_close($ch);
		$data = \yii\helpers\Json::decode($kurs_bca);
		$return['kurs_tengah'] = ($data['Data']['USD']['Jual']+$data['Data']['USD']['Beli'])/2;
		$return['last_update'] = date('Y-m-d', strtotime(\app\components\DeltaFormatter::formatDateTimeForDb($data['LastUpdate'])));
		return $return;*/

		// https://blog.rosihanari.net/teknik-grabbing-mengambil-teks-dari-situs-lain-dengan-curl/

		// inisialisasi CURL
		$data = curl_init();

		// setting CURL
		curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($data, CURLOPT_URL, "https://www.klikbca.com");

		// menjalankan CURL untuk membaca isi file
		$hasil = curl_exec($data);
		curl_close($data);

		$pecah = explode('<table width="139" border="0" cellspacing="0" cellpadding="0">', $hasil);

		$pecahLagi = explode('</table>', $pecah[2]);

		//echo "<table border='1'>";
		//echo "<tr><td>KURS</td><td>JUAL</td><td>BELI</td></tr>";
		//echo $pecahLagi[0];
		//echo "</table>";

		$pecahTerus = strip_tags($pecahLagi[0]);
		$pecahTerus = trim($pecahTerus);

		$ambyar = array_filter( explode(" ", $pecahTerus) );

		$kurs_beli = $ambyar[22];
		$kurs_jual = $ambyar[24];

		$kurs_beli = str_replace(".","", $kurs_beli);
		$kurs_jual = str_replace(".","",$kurs_jual);

		$kurs_beli = str_replace(",00","", $kurs_beli);
		$kurs_jual = str_replace(",00","",$kurs_jual);

		$kurs_beli = $kurs_beli * 1;
		$kurs_jual = $kurs_jual * 1;

		$kurs_tengah = ($kurs_beli + $kurs_jual) / 2;
		echo "<br>beli = ".$kurs_beli;
		echo "<br>jual = ".$kurs_jual;
		echo "<br>tengah = ".$kurs_tengah;
		
		$return = [];
		$return['kurs_tengah'] = $kurs_tengah;
		$return['last_update'] = date('Y-m-d');
		
		return $return;		
	}

    /**
     * @param array $data
     * @param $from
     * @param $to
     * @return array|ActiveRecord[]
     */

}
?>