<?php

// No direct access to this file

defined('_JEXEC') or die;

 

jimport('joomla.application.component.view');

 

class BloodBowlViewTourney extends JViewLegacy

{

        function display($tpl = null) 
        {
                //$user &= JFactory::getUser();
				$app =& JFactory::getApplication();
				$jinput = $app->input;
				
			
				// Assign data to the view
				//$this->limit = 6;
				$this->tourneys = $this->get('tourneylist');
				
 
                // Display the view
                parent::display($tpl);
        }

}

?>