##Introduction

Sometimes, when using ActiveRecord, we need to know if some attribute value has changed after it was
loaded from the database. To make things easier and avoid duplicated code, I created this behavior 
to help me with this task. Basically, what it does is to make a copy of all attributes values from 
the object, right after it is loaded from the database, so the behavior can work with this original 
values to know if any of them was changed.

##Usage
The behavior usage is pretty simple. First, add the behavior to the ActiveRecord where you intend to 
use it:

	<?php 
		class User extends ActiveRecord {
			...
			public function behaviors() {
				return array(
					'AttributesBackupBehavior' => 'ext.AttributesBackupBehavior',
				);
			}
			...
		}
	?>

After this, 3 new methods will be added to your User class: attributesChanged(), attributeChanged() 
and getOldAttributeValue().

Below is a description of it:

###attributesChanged()
With this method you can check if any of the object attributes was changed after it was loaded from
the database:

	<?php
		$user = User::model()->find(); // User->status == 'Active'
		var_dump($user->attributesChanged()); // FALSE
		$user->status = 'Inactive';
		var_dump($user->attributesChanged()); // TRUE
	?>

###attributeChanged()
This should be used when you want to know if a specific attribute value was changed:

	<?php
		$user = User::model()->find(); // User->status == 'Active'
		$user->status = 'Inactive';
		var_dump($user->attributeChanged('email')); // FALSE
		var_dump($user->attributeChanged('status')); // TRUE
	?>

###getOldAttributeValue()
In case you need to retrieve the original value loaded from the database, this is the method you gonna 
use:

	<?php
		$user = User::model()->find(); // User->status == 'Active'
		$user->status = 'Inactive';
		var_dump($user->getOldAttributeValue('status')); // 'Active'
	?>

Besides those three methods, there's also one property that can be configured for this behavior: 
**$reloadAfterSave**.

By default, after you call the ActiveRecord save method, the old attributes will be erased and filled 
with the just saved new ones, show it will reflect the stored record. If for some reason you want 
to keep the original loaded values, you need to set **$reloadAfterSave** as false:

	<?php 
		class User extends ActiveRecord {
			...
			public function behaviors() {
				return array(
					'AttributesBackupBehavior' => array(
					'class' => 'ext.AttributesBackupBehavior',
					'reloadAfterSave' => false,
				);
			}
			...
		}
	?>
