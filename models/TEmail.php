<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_email".
 *
 * @property integer $temail_id
 * @property integer $doc_id
 * @property string $temail_tgl
 * @property integer $temail_status
 * @property integer $alertex_id
 */
class TEmail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_email';
    }
    
    public function behaviors(){
		return [\app\components\DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_id', 'temail_status', 'alertex_id'], 'integer'],
            [['temail_tgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'temail_id' => 'Temail',
                'doc_id' => 'Doc',
                'temail_tgl' => 'Temail Tgl',
                'temail_status' => 'Temail Status',
                'alertex_id' => 'Alertex',
        ];
    }
}
