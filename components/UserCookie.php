<?php
namespace app\components;

use Yii;
use yii\base\Component;
 
class UserCookie extends Component {
    
    const THEME_COLORS = 'theme-colors';
    const LANGUANGE = 'user-language';
    const LAST_URL = 'user-last-url';
    const USER_SIDEBAR_TOGGLE = 'user-sidebar-toggle';
    
    public static function setAll()
    {
        self::setThemeColors();
        self::setLanguage();
    }
    
    public static function setThemeColors($html_code=null)
    {
        if($html_code == null){
            $html_code = (!empty(Yii::$app->user->identity->userProfile->theme_colors) ? Yii::$app->user->identity->userProfile->theme_colors : \app\components\Params::DEFAULT_COLOR_THEME);
        }
        
        $buttonhover = \app\models\ThemeColorM::find()->addSelect(["split_part(name, ' ', 1) as name"])->where(['html_code'=>  $html_code])->one()->name;
        $buttonhover = \app\models\ThemeColorM::find()->where(['name'=>$buttonhover.' lighten-1'])->one()->html_code;
        $buttonfocus = \app\models\ThemeColorM::find()->addSelect(["split_part(name, ' ', 1) as name"])->where(['html_code'=>  $html_code])->one()->name;
        $buttonfocus = \app\models\ThemeColorM::find()->where(['name'=>$buttonfocus.' darken-4'])->one()->html_code;
        $value = ['main'=>$html_code,'button-hover'=>$buttonhover,'button-focus'=>$buttonfocus];
        
        Yii::$app->response->cookies->add( new \yii\web\Cookie([
                'name' => self::THEME_COLORS,
                'value' => \yii\helpers\Json::encode($value),
                'expire' => time() + 3600*24*30,]
            )
        );
        
        return true;
    }
    
    public static function setLanguage($lang_code=null)
    {
        if($lang_code == null){
            $lang_code = (!empty(Yii::$app->user->identity->userProfile->language)?Yii::$app->user->identity->userProfile->language : \app\components\Params::DEFAULT_LANGUAGE);
        }
        Yii::$app->response->cookies->add( new \yii\web\Cookie([
                'name' => self::LANGUANGE,
                'value' => $lang_code,
                'expire' => time() + 3600*24*30,]
            )
        );
        
        return true;
    }
	
	public static function setLastUserUrl()
    {
        Yii::$app->response->cookies->add( new \yii\web\Cookie([
                'name' => self::LAST_URL,
                'value' => \yii\helpers\Url::current() ,
                'expire' => time() + 3600*24*2,] // 2 Hari
            )
        );
        
        return true;
    }
    
    public function removeUserCookie()
    {
        Yii::$app->response->cookies->remove(self::USER_COOKIE_KEY);
    }
    
}

?>