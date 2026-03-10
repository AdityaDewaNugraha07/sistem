<?php
/* @var $this yii\web\View */
$this->title = 'Home';
//$this->registerCssFile($this->theme->baseUrl."/global/plugins/dnd/tablednd.css",['depends'=>[yii\web\YiiAsset::className()]]);
app\assets\JexcelAsset::register($this);
?>

<!--<blockquote class="trello-board-compact">
  <a href="https://trello.com/b/CM7Al1l9/cis">Trello Board</a>
</blockquote>
<script src="https://p.trellocdn.com/embed.min.js"></script>-->


<?php
$IP = "10.10.10.244";
$Key="0";


$Connect = fsockopen($IP, "4370", $errno, $errstr, 1);
if ($Connect) {
  $soap_request = "<GetAttLog>
    <ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey>
    <Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg>
  </GetAttLog>";

  $newLine = "\r\n";
  fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
  fputs($Connect, "Content-Type: text/xml".$newLine);
  fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
  fputs($Connect, $soap_request.$newLine);
  $buffer = "";
  while($Response = fgets($Connect, 1024)) {
    $buffer = $buffer.$Response;
  }
} else echo "Koneksi Gagal";

$buffer = Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
$buffer = explode("\r\n",$buffer);

for ($a=0; $a<count($buffer); $a++) {
  $data=Parse_Data($buffer[$a],"<Row>","</Row>");

  $export[$a]['pin'] = Parse_Data($data,"<PIN>","</PIN>");
  $export[$a]['waktu'] = Parse_Data($data,"<DateTime>","</DateTime>");
  $export[$a]['status'] = Parse_Data($data,"<Status>","</Status>");
}

echo '<pre>';
print_r($export);


function Parse_Data ($data,$p1,$p2) {
  $data = " ".$data;
  $hasil = "";
  $awal = strpos($data,$p1);
  if ($awal != "") {
    $akhir = strpos(strstr($data,$p1),$p2);
    if ($akhir != ""){
      $hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
    }
  }
  return $hasil;    
}
?>

<?php $this->registerJs(" 
	
", yii\web\View::POS_READY); ?>

<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jexcel/js/jquery.jexcel.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<script>

</script>
