<?php
namespace app\components;

use Yii;
use yii\base\Component;
 
/**
* Class ini adalah global class, class bebas.
* Selalu tereksekusi setiap request
* dan class ini tereksekusi pada Event Before Request.
* @author Arie Satriananta <ariesatriananta@yahoo.com>
*/
class DeltaGlobalClass extends Component {
    
    public function init() {
        
        // set language config
        \Yii::$app->language = \app\components\Params::DEFAULT_LANGUAGE;
        
        // set aliases :D
//        Yii::setAlias('path_user_avatar', '@app/storage/images/user-profile-avatar');
//        Yii::setAlias('url_user_avatar', '@web/storage/images/user-profile-avatar');
//        Yii::setAlias('path_user_bg', '@app/storage/images/user-profile-bg');
//        Yii::setAlias('url_user_bg', '@web/storage/images/user-profile-bg');
        
        parent::init();
    }
    
    private static function deltaAuth(){
        echo "<pre>";
        print_r('delta auth');
        exit;
        return "delta auth";
    }
    
    public static function setLoginStatus($par){
        $user = \app\models\MUser::findOne(Yii::$app->user->id);
        $user->login_status = $par;
        $user->last_login_time = date('Y-m-d H:i:s');
        return $user->update();
    }
	
	public static function authByUserDeptId( $dept_id ){
		$logindataid = Yii::$app->user->identity->pegawai->departement_id;
		if( $logindataid == $dept_id ){
			$result_1 = TRUE;
		}else{
			$result_1 = FALSE;
		}
		if(Yii::$app->user->identity->user_group_id == Params::USER_GROUP_ID_SUPER_USER){
			$result_2 = TRUE;
		}else{
			$result_2 = FALSE;
		}
		return ($result_1 || $result_2);
	}
	
	public static function getBerkasNamaByBerkasKode( $berkas_kode ){
		$berkas_initial = substr($berkas_kode,0,3);
		switch ($berkas_initial){
		case "SPB":
			return "SPB";
			break;
		case "SPO":
			return "PO Bahan Pembantu";
			break;
		case "PDG":
			return "Pengajuan Uang Dinas Grader";
			break;
		case "PMG":
			return "Pengajuan Uang Makan Grader";
			break;
		case "POP": case "VOP": case "SOP": case "MOP": case "LOP": case "BOP": case "FOP":
			return "Order Penjualan";
			break;
		case "PNP": case "VNP": case "SNP": case "MNP": case "LNP": case "BNP": case "FNP":
			return "Diskon Nota Penjualan";
			break;		
                case "PAL":
			return "Keterlambatan Input Alert Piutang";
			break;
		}
	}
    
}

?>