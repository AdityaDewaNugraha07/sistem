<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TJobdesc]].
 *
 * @see TJobdesc
 */
class TJobdescQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TJobdesc[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TJobdesc|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
