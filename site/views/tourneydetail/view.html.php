<?php

// No direct access to this file

defined('_JEXEC') or die;

 

jimport('joomla.application.component.view');

 

class BloodBowlViewTourneyDetail extends JViewLegacy

{

        function display($tpl = null) 
        {
                
				//$user &= JFactory::getUser();
				$app =& JFactory::getApplication();
				$jinput = $app->input;
				
				$this->show = $jinput->get('show','33','int');
				$this->order = $jinput->get('order','skjul','str');
				$this->race = $jinput->get('race','all','str');
				$this->coach = $jinput->get('coach','0','int');
				$this->matchstart = $jinput->get('match','0','int');
				$this->edit = $jinput->get('toggleedit','0','int');
				$this->nextedit = (-1*$this->edit)+1;
				$this->standA = $jinput->get('standA','empty','str');
				//$this->visview = $jinput->get('view','view','str');
				
				// Assign data to the view
				//$this->limit = 6;
				//$this->tourneys = $this->get('tourneylist');
				$this->tourney = $this->get('tourney');
				
				// Replace admin numbers with names
				foreach(explode(',',$this->tourney->admins) as $admin)
				{
					$admins .= JFactory::getUser($admin)->name .", ";
				}
				$this->admins = rtrim($admins,', ');
				$this->isadmin=FALSE;
				if (in_array(JFactory::getUser()->id, explode(',',$this->tourney->admins))) $this->isadmin=TRUE;
				$this->edit = ( (bool)$this->edit && $this->isadmin);
				
				if ($this->edit)
				{
					$this->form = $this->get('Form');
					$this->newteams=$this->get('TeamsNotIn');
					$this->newtours=$this->get('toursnotin');
				}
				
				$this->newchallengeform = $this->get('newchallengeform');
				$this->races = $this->get('races');
				$this->teams = $this->get('teams');
				$this->matches = $this->get('matches');
				$this->coaches = $this->get('coaches');
				if ($this->tourney->type != "rating") $this->standings = $this->get('standing');
				if ($this->tourney->type == "playoff") $this->competitors = $this->get('bracket');
				
				$url = "index.php?view=edittourney&show={$this->show}&order=coach&coach=0&toggleedit={$this->nextedit}";
				$text = JHtml::_('image', 'system/edit.png', JText::_('JGLOBAL_EDIT'), NULL, true);
				$this->editbutton = JHtml::_('link', JRoute::_($url), $text, null);
 
                // Display the view
                parent::display($tpl);
        }

}

?>