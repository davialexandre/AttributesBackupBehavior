<?php

class AttributesBackupBehavior extends CActiveRecordBehavior {

    private $old_attributes;
    
    public function afterFind($event) {
        parent::afterFind($event);
        foreach($this->owner->attributes as $name => $value) {
            $this->old_attributes[$name] = $value;
        }
    }
    
    public function attributesChanged() {
        $changed_attributes = array_diff($this->owner->attributes, $this->old_attributes);
        return !empty($changed_attributes);
    }
    
}

?>