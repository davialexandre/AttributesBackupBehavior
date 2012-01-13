<?php

class AttributesBackupBehavior extends CActiveRecordBehavior {

    private $oldAttributes = array();

    public $reloadAfterSave = true;

    public function afterFind($event) {
        parent::afterFind($event);
        $this->getAttributesFromOwner();
    }

    public function afterSave($event) {
        parent::afterSave($event);
        if($this->reloadAfterSave) {
            $this->getAttributesFromOwner();
        }
    }

    private function getAttributesFromOwner() {
        $this->oldAttributes = array();
        foreach($this->owner->attributes as $name => $value) {
            $this->oldAttributes[$name] = $value;
        }
    }

    public function attributesChanged() {
        if(empty($this->oldAttributes)) {
            return false;
        }
        
        $number_of_changed_attributes = count(array_diff($this->owner->attributes, $this->oldAttributes));
        return $number_of_changed_attributes > 0;
    }

    public function attributeChanged($attribute_name) {
        if(array_key_exists($attribute_name, $this->oldAttributes) && array_key_exists($attribute_name, $this->owner->attributes)) {
            return $this->oldAttributes[$attribute_name] != $this->owner->$attribute_name;
        }

        return false;
    }

    public function getOldAttributeValue($attribute_name) {
        if(array_key_exists($attribute_name, $this->oldAttributes)) {
            return $this->oldAttributes[$attribute_name];
        }

        return null;
    }
    
}

?>