<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class DeltaBaseController extends Controller
{   
    public $defaultAction = 'index';
    
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($this->enableCsrfValidation && Yii::$app->getErrorHandler()->exception === null && !Yii::$app->getRequest()->validateCsrfToken()) {
                throw new BadRequestHttpException(Yii::t('yii', 'Unable to verify your data submission.'));
            }
			if($action->controller->id != "apps"){
				if(!\Yii::$app->request->isAjax){
					\app\components\UserCookie::setLastUserUrl();
				}
			}
			
            return true;
        }
        
        return false;
    }
    
    
    // This fucntion make no case sensitive action url's
    public function createAction($id)
    {
        Yii::$app->language = Yii::$app->session->get('language', 'id-ID');
        if ($id === '') {
            $id = $this->defaultAction;
        }
        $actionMap = $this->actions();
        if (isset($actionMap[$id])) {
            return Yii::createObject($actionMap[$id], [$id, $this]);
        } elseif (preg_match('/^[a-zA-Z0-9\\-_]+$/', $id) && strpos($id, '--') === false && trim($id, '-') === $id) {
            $methodName = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
            if (method_exists($this, $methodName)) {
                $method = new \ReflectionMethod($this, $methodName);
                if ($method->isPublic() && $method->getName() === $methodName) {
                    return new \yii\base\InlineAction($id, $this, $methodName);
                }
            }
        }
        return null;
    }
    
//    List All Controller and Action
    public function Getcontrollersandactions($controller_aja=null)
    {
        $controllerlist = [];
        if ($handle = opendir('../controllers')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerlist);
        $result = $controllerlist;
        
        if(!$controller_aja){
            $result = [];
            foreach ($controllerlist as $controller):
                $handle = fopen('../controllers/' . $controller, "r");
                if ($handle) {
                    while (($line = fgets($handle)) !== false) {
                        if (preg_match('/public function action(.*?)\(/', $line, $display)):
                            if (strlen($display[1]) > 2):
                                $result[substr($controller, 0, -4)][] = strtolower($display[1]);
                            endif;
                        endif;
                    }
                }
                fclose($handle);
            endforeach;
        }else{
            $result_new = [];
            foreach ($result as $i => $v){
                $result_new[] = substr(\app\components\SSP::reverse_strrchr( $v,"Controller"),0,-1);
            }
            $result = $result_new;
        }
        
        return $result;
    }
    
    function actionSetFlashAlert(){
        if(\Yii::$app->request->isAjax){
            return $this->renderPartial('@views/apps/partial/_flashAlert',[]);
        }
    }
}