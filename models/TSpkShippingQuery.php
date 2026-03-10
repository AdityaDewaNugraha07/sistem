<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TSpkShipping]].
 *
 * @see TSpkShipping
 */
class TSpkShippingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TSpkShipping[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TSpkShipping|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
