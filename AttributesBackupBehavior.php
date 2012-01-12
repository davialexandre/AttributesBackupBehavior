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
    if($this->reloadAfterSave)
      $this->getAttributesFromOwner();
  }

  private function getAttributesFromOwner() {
    $this->oldAttributes = $this->owner->attributes;
  }

  public function attributesChanged() {
    if ($this->owner->isNewRecord)
      return false;

    $number_of_changed_attributes = count(array_diff($this->owner->attributes, $this->oldAttributes));
    return $number_of_changed_attributes > 0;
  }

  public function attributeChanged($attribute_name) {
    if ($this->owner->isNewRecord)
      return false;

    if(isset($this->oldAttributes[$attribute_name]) && isset($this->owner->attributes[$attribute_name]))
      return $this->oldAttributes[$attribute_name] != $this->owner->$attribute_name;

    return false;
  }

  public function getOldAttributeValue($attribute_name) {
    if ($this->owner->isNewRecord)
      return null;

    if(isset($this->oldAttributes[$attribute_name]))
      return $this->oldAttributes[$attribute_name];

    return null;
  }

}