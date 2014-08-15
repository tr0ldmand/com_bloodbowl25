<?php

// No direct access to this file

defined('_JEXEC') or die('Restricted access');

 

jimport('joomla.application.component.modelform');

 

class BloodBowlModelBloodBowl extends JModelForm

{
        protected $tour;
		protected $iscoach = false;
		protected $matches;
		protected $myteams;
		protected $mymatches;

		
		
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
		
		public function getIsCoach()
		{
			$this->iscoach = false;
			if (!JFactory::getUser()->guest)
			{
				$id = JFactory::getUser()->id; 
				$table = $this->getTable('Teams', 'BloodBowlTable');
				if ($table->getNumberOfTeams(JFactory::getUser()->id)>0)
				{
					$this->iscoach = true;
				}
			}
			return $this->iscoach;
		}
		
		private function setTour()
		{
			$table = $this->getTable('Tourney', 'BloodBowlTable');
			$this->tour = $table->getNewestTour();
			return true;
		}
		
		public function getTour()
		{
			if (!isset($this->tour))
			{
				$this->setTour();
			}
			return $this->tour;
		}
		
		public function getMatches()
		{
			if (!isset($this->tour)) $this->setTour();
			if (!isset($this->matches))
			{
				$table = $this->getTable('BloodBowl', 'BloodBowlTable');
				$this->matches = $table->getMatchesInTourney($this->tour->tour_id, 0, 10);
			}
			return $this->matches;
		}
		
		public function getMyMatches()
		{
			$id = JFactory::getUser()->id; 
			if (!isset($this->mymatches))
			{
				$table = $this->getTable('Matches', 'BloodBowlTable');
				$this->mymatches = $table->getMatchesByCoach($id);
			}
			return $this->mymatches;
		}
		
		public function getMyTeams()
		{
			$id = JFactory::getUser()->id; 
			if (!isset($this->myteams))
			{
				$table = $this->getTable('Teams', 'BloodBowlTable');
				$this->myteams = $table->getTeams($id, $order="name");
			}
			return $this->myteams;
		}
		

}

?>