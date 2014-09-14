<?php

// No direct access to this file

defined('_JEXEC') or die('Restricted access');

 

jimport('joomla.application.component.modelform');

 

class BloodBowlModelStats extends JModelForm

{				
		public function getForm($data = array(), $loadData = true)
        {
		//$app = JFactory::getApplication('site');
			$form = $this->loadForm('com_bloodbowl.newteam', 'newteam', array('control' => 'jform', 'load_data' => true));
			if (empty($form)) 
			{
				return false;
			}
			return $form;
        }
		
		public function getStats($stattype,$year)
		{
			$table = $this->getTable('BloodBowl','BloodBowlTable');
			$this->result = $table->getStats($stattype,1);
			return $this->result;
		}
		

}

?>