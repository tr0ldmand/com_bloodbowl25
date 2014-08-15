<?php

// No direct access to this file

defined('_JEXEC') or die('Restricted access');

 

jimport('joomla.application.component.modelform');

 

class BloodBowlModelTeamsList extends JModelForm

{
        protected $item;
		var $navn;
		protected $tourneys;
		protected $races;
		protected $teams;
		protected $nofteams;


		
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
		
		public function getRaces()
		{
			if (!isset($this->races))
			{
				$table = $this->getTable('Teams','BloodBowlTable');
				
				$this->races = $table->getNewTeams();
			}
			return $this->races;
		}
		
		public function getNumberOfTeams()
		{
			$app =& JFactory::getApplication();
			$jinput = $app->input;
			
			$show=$jinput->get('show','0','int');
			if ($show==-1) $show=JFactory::getUser()->id;
			$order  = $jinput->get('order','name','str');
			$race   = $jinput->get('races','all','str');
			$tvbund = $jinput->get('tvbund','0','int');
			$tvtop  = $jinput->get('tvtop','0','int');
			
			if (!isset($this->nofteams))
			{
				$table = $this->getTable('Teams','BloodBowlTable');
				
				$this->nofteams = $table->getNumberOfTeams($show, $race, $tvbund, $tvtop);
			}
			return $this->nofteams;
		}
		public function getTeams()
		{ // $id=0, $order="name", $race="all", $tvrange=array('bund'=>0,'top'=>0)
			$app =& JFactory::getApplication();
			$jinput = $app->input;
			
			$show=$jinput->get('show','0','int');
			if ($show==-1) $show=JFactory::getUser()->id;
			$order = $jinput->get('order','name','str');
			$race = $jinput->get('races','all','str');
			$tvbund = $jinput->get('tvbund','0','int');
			$tvtop = $jinput->get('tvtop','0','int');
			$start = 30*$jinput->get('start','0','int');
			if ($start > $this->getNumberOfTeams() ) $start=0;
			
			if (!isset($this->teams))
			{
				$table = $this->getTable('Teams','BloodBowlTable');
				
				$this->teams = $table->getTeams($show, $order, $race, $tvbund, $tvtop, $start);
			}
			return $this->teams;
		}
		
		
}

?>