<?php

// No direct access to this file

defined('_JEXEC') or die('Restricted access');

 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
// import Joomla modelitem library
//jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');

 

class BloodBowlModelTeamDetail extends JModelForm

{
		protected $show;
		protected $team;
		protected $players;
		protected $matches;
		protected $stats;
		protected $skills;
		protected $roster;
		protected $has_played = TRUE;

        /**
         * Get the message
         * @return string The message to be displayed to the user
         */
		
		private function setInput()
		{
			$jinput = JFactory::getApplication()->input;
			$this->show=$jinput->get('show','120','int');
			return true;
		}
		
		public function getForm($data = array(), $loadData = true)
        {
                $form = $this->loadForm('com_bloodbowl.updteam', 'updteam', array('control' => 'jform', 'load_data' => true));
                if (empty($form)) {
                        return false;
                }
                return $form;
 
        }
		
		public function getFormDelTeam($data = array(), $loadData = true)
        {
                $form = $this->loadForm('com_bloodbowl.delteam', 'delteam', array('control' => 'jform', 'load_data' => true));
                if (empty($form)) {
                        return false;
                }
                return $form;
 
        }
		
		public function getSkills($types="all")
		{
			if (!isset($this->skills))
			{
				$table = $this->getTable('Skills','BloodBowlTable');
				$this->skills = $table->getSkillCat($types);
			}
			return $this->skills;
		}
		
		public function getTeam($id=0)
		{
			if (!isset($this->team))
			{
				if ($id==0)
				{
					if (!isset($this->show)) $this->setInput();
					$id=$this->show;
				}
				$table = $this->getTable('Teams','BloodBowlTable');
				$this->team = $table->getOneTeam($id);
				if ($this->team->matches==0) $this->has_played==FALSE;
			}
			return $this->team;
		}
		
		public function getHasPlayed() {
			return $this->has_played;
		}
		
		private function setPlayers($id=0)
		{
			//JFactory::getApplication()->enqueueMessage("Vi er nu i setPlayers(). ", 'notice');
			if ($id==0)
			{
				if (!isset($this->show)) $this->setInput();
				$id=$this->show;
			}
			$table = $this->getTable('BloodBowl','BloodBowlTable');
			$this->players = $table->getPlayers($id);
			return true;
		}
		
		public function getPlayers($id=0, $new=FALSE)
		{
			//JFactory::getApplication()->enqueueMessage("Vi er nu i getPlayers(). ", 'notice');
			if (!isset($this->players) || $new===TRUE)
			{
				$this->setPlayers();
			}
			return $this->players;
		}
		
		public function getMatches($id=0)
		{
			if (!isset($this->matches))
			{
				if ($id==0)
				{
					if (!isset($this->show)) $this->setInput();
					$id=$this->show;
				}
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				$this->matches = $table->getMatches($id);
			}
			return $this->matches;
			
		}
		
		public function getStats($id=0)
		{
			if (!isset($this->stats))
			{
				if ($id==0)
				{
					if (!isset($this->show)) $this->setInput();
					$id=$this->show;
				}
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				$this->stats = $table->getTeamStats($id);
			}
			return $this->stats;
		}
		
		public function getRoster()
		{
			if (!isset($this->team)) $this->getTeam();
			if (!isset($this->roster))
			{
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				$this->roster = $table->getRoster($this->team->race);
			}
			return $this->roster;
		}
		
				
		public function getUnlockTeam()
		{
			//JFactory::getApplication()->enqueueMessage("Vi er nu i getUnlockTeam(). ", 'notice');
			if (!isset($this->players)) $this->setPlayers();
			//JFactory::getApplication()->enqueueMessage("Det g;lder at newskills=". $this->players[0]->newskills ." og activeplayers=". $this->players[0]->activeplayers, 'notice');
			if ($this->players[0]->newskills==0 && $this->players[0]->activeplayers>1)
			{
				if ($this->team->retired>0) return false;
				return $this->unlockTeam();
			}
			return false;
		}
		
		public function updTeam($team=null, $players=null)
		{
			$table = $this->getTable('Teams','BloodBowlTable');
			if (is_array($team))
			{
				if (!empty($team)) $res[] = $table->updTeam($team);
			}
			if (is_array($players))
			{
				if (!empty($players)) $res[] = $table->updPlayers($players);
			}
			if (!empty($res)) return $res;
			return array(0=>false);
		}
		
		public function unlockTeam()
		{
			//JFactory::getApplication()->enqueueMessage("Vi er nu i unlockTeam(). ", 'notice');
			$lockedteam = array();
			$lockedteam['id'] = $this->team->id;
			$lockedteam['teamvalue'] = ($this->players[0]->playervalue)+($this->team->RR)*($this->team->RRcost)+($this->team->FF)*10000+($this->team->A_Coach)*10000+($this->team->CheerLeader)*10000+($this->team->Apoth)*50000;
			$lockedteam['locked'] = 0;
			$table = $this->getTable('Teams','BloodBowlTable');
			return $table->updTeam($lockedteam);
			//return true;
		}
		
		public function newTeam($race=0, $name=null, $startval=0, $coach=0)
		{
			if ($coach==0) $coach = JFactory::getUser()->id;
			if ($startval==0) $startval=1000000;
			if ($race>0 && !is_null($name))
			{
				$table = $this->getTable('Teams','BloodBowlTable');
				return $table->insertTeam($coach, $race, $startval, $name);
			}
			return 0;
		}
		
}

?>