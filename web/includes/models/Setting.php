<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;

class Setting extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'setting';
    }
    
    /**
     * Get a setting with the given key
     *
     * @param key string
     * @return setting|null
     */
    public static function findSetting($key)
    {
        return Setting::find()
            ->where(['key' => $key])
            ->one();
    }

    /**
     * Get the key of the setting
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * Get all settings
     *
     * @return array|null
     */
    public static function getSettings()
    {
        return Setting::find()->all();
    }
    
    /**
     * Get value of setting
     *
     * @return mixed
     */
    public function getValue() {
        switch(intval($this->type)) {
            case(1): return ($this->value==="true");
            case(2): return intval($this->value);
            case(3): return $this->value;
        }
        return null;
    }
    
    /**
     * Set value of setting
     *
     * @param val mixed
     */
    public function setValue($val) {
        switch(intval($this->type)) {
            case(1): $val===true ? $this->value="true" : $this->value="false"; break;
            case(2): $this->value=strval($val); break;
            case(3): $this->value=$val; break;
            default: break;
        }
        $this->save();
    }
}
