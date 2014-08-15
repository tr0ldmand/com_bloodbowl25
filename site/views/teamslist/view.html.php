<?php

// No direct access to this file

defined('_JEXEC') or die;

 

jimport('joomla.application.component.view');

 

class BloodBowlViewTeamsList extends JView

{

        function display($tpl = null) 
        {
                //$user &= JFactory::getUser();
				$jinput = JFactory::getApplication()->input;
				
				$this->showteams = $jinput->get('show','0','int');
				if ($this->showteams>0)
				{
					$this->ekstraoption = "<option value=\"". $this->showteams ."\">". @JFactory::getUser($this->showteams)->name . JText::_( 'COM_BLOODBOWL_NAMES_TEAMS') ."</option>";
				}
				$this->showorder = $jinput->get('order','name','str');
				$this->showraces = $jinput->get('races','all','str');
				$this->showtvbund = $jinput->get('tvbund','0','int');
				$this->showtvtop = $jinput->get('tvtop','0','int');
				$this->showstart = $jinput->get('start','0','int');
				
				
				// Assign data to the view
				//$this->limit = 6;
				$this->races = $this->get('races');
				$this->nofteams = $this->get('numberofteams');
				$this->teams = $this->get('teams');
				$this->form = $this->get('form');
				
				// Calculate number of pages
				$this->numberofpages = ceil(($this->nofteams)/30);

 
                // Display the view
                parent::display($tpl);
        }

}

?>