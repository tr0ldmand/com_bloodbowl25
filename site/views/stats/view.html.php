<?php

// No direct access to this file

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class BloodBowlViewStats extends JViewLegacy

{

        function display($tpl = null) 
        {
				$jinput =& JFactory::getApplication()->input;
				
				$model = $this->getModel();
				$this->alltime_topscorers = $model->getStats("alltime_topscorers",1);
				$this->alltime_casualties = $model->getStats("alltime_casualties",1);
				$this->alltime_completions = $model->getStats("alltime_completions",1);
				$this->alltime_kills = $model->getStats("alltime_kills",1);
				$this->alltime_interceptions = $model->getStats("alltime_interceptions",1);
				$this->alltime_starplayer = $model->getStats("alltime_starplayer",1);
				$this->alltime_teamcas = $model->getStats("team_casualties",1);
				$this->alltime_teamcomp = $model->getStats("team_completions",1);
				$this->alltime_teamdefense = $model->getStats("team_defense",1);
				$this->alltime_teamgate = $model->getStats("team_gate",1);
				$this->alltime_teamkills = $model->getStats("team_kills",1);
				$this->alltime_teamscore = $model->getStats("team_topscorers",1);
				
                // Display the view
                parent::display($tpl);
        }

}

?>