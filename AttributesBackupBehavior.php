<?php

class AttributesBackupBehavior extends CActiveRecordBehavior {

    private $oldAttributes;
    
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
        $number_of_changed_attributes = count(array_diff($this->owner->attributes, $this->oldAttributes));
        return $number_of_changed_attributes > 0 && !$this->owner->isNewRecord;
    }
    
    public function attributeChanged($attribute_name) {
    	if(array_key_exists($attribute_name, $this->oldAttributes) && !$this->owner->isNewRecord) {
    		return $this->oldAttributes[$attribute_name] != $this->owner->$attribute_name;
    	}
    	
    	return false;
    }
    
    public function getOldAttributeValue($attribute_name) {
    	if(array_key_exists($attribute_name, $this->old_attribtues) && !$this->owner->isNewRecord) {
    		return $this->oldAttributes[$attribute_name];
    	}
    	
    	return null;
    }
    
}

?>