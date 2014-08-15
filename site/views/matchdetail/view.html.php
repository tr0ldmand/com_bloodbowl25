<?php
// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class BloodBowlViewMatchDetail extends JView

{

        function display($tpl = null) 
        {
                // $user =& JFactory::getUser();
				// $app =& JFactory::getApplication();
				// $jinput =& $app->input;
				
				// Assign data to the view
				//$this->limit = 6;
				
				$this->match = $this->get('match');
				$this->teams = $this->get('teams');
				if ($this->match->challenge!=1) 
				{
					$this->nextedit = $this->get('nextedit');
					$this->curedit = $this->get('currentedit');
					$this->form = $this->get('Form');
				}
				else
				{
					$this->form = $this->get('ChallengeForm');
					$this->curedit=2;
				}
				$this->delform = $this->get('DelForm');
				$this->showedit=FALSE;
				if ($this->match->editor && $this->curedit==0) 
				{
					$this->match->editor=FALSE;
					$this->showedit=TRUE;
				}
				if ($this->match->isadmin && $this->curedit==2) $this->match->disabled=FALSE;
				
				$this->match->editor = ((bool)$this->match->editor && !((bool)$this->match->disabled));
				
 
                // Display the view
                parent::display($tpl);
        }

}

?>