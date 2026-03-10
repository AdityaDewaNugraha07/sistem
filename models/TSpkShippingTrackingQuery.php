<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TSpkShippingTracking]].
 *
 * @see TSpkShippingTracking
 */
class TSpkShippingTrackingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TSpkShippingTracking[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TSpkShippingTracking|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
