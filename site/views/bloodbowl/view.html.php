<?php

// No direct access to this file

defined('_JEXEC') or die;

 

jimport('joomla.application.component.view');

 

class BloodBowlViewBloodBowl extends JView

{

        function display($tpl = null) 
        {
                //$user &= JFactory::getUser();
				$app =& JFactory::getApplication();
				$jinput = $app->input;
				
				$this->ratingtour = $this->get('tour');
				$this->iscoach = $this->get('iscoach');
				$this->matches = $this->get('matches');
				
				if ($this->iscoach)
				{
					$this->form = $this->get('form');
					$this->myteams = $this->get('myteams');
					$this->mymatches = $this->get('mymatches');
				}
 
                // Display the view
                parent::display($tpl);
        }

}

?>