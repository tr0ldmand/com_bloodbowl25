<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');

class BloodBowlModelMatchDetail extends JModelForm
{
        // Input vars
		protected $id;
		protected $userid;
		
		// Both Input and Output
		protected $nextedit;
		protected $curedit;
		
		// Output vars
		protected $match;
		protected $playersonteams;
		protected $teams;
		protected $islocked;

        /**
         * Get the message
         * @return string The message to be displayed to the user
         */
		function setInputVars()
		{
			$app =& JFactory::getApplication();
			$jinput =& $app->input;
			
			$this->id = $jinput->get('show','1735','int');
			$this->nextedit = $jinput->get('toggleedit','0','int');
			$this->curedit = $jinput->get('toggleedit','0','int');
			$this->userid = JFactory::getUser()->id;
			//$this->order = $jinput->get('order','cname','str');
			//$this->race = $jinput->get('races','all','str');
			//$this->coach = $jinput->get('coach','0','int');
		}
		 
		public function getNextEdit()
		{
			if (!isset($this->nextedit)) $this->setInputVars();
			if (!isset($this->match)) $this->getMatch();
			
			if ($this->nextedit==0 && $this->match->editor==TRUE) $this->nextedit=1;
			elseif ($this->nextedit==1 && $this->match->isadmin==TRUE) $this->nextedit=2;
			else $this->nextedit=0;
			
			
			return $this->nextedit;
		}
		
		public function getCurrentEdit()
		{
			if (!isset($this->curedit)) $this->setInputVars();
			return $this->curedit;
		}
		
		public function getMatch()
		{
			if (!isset($this->match))
			{
				if (!isset($this->id)) $this->setInputVars();
				
				$table = $this->getTable('Matches','BloodBowlTable');
				$this->match = $table->getMatchBasics($this->id);
				
				if ($this->match->challenge==1) 
				{
					$this->match->team1_coach = $this->match->team1_id;
					$this->match->team2_coach = $this->match->team2_id;
				}
				$admins = explode(",",$this->match->tour_admins);
				foreach ($admins as $admin)
				{
					if ($admin == $this->userid) 
					{
						$this->match->editor=TRUE;
						$this->match->isadmin=TRUE;
					}
				}
				if ($this->match->team1_coach == $this->userid || $this->match->team2_coach == $this->userid)
				{
					$this->match->editor=TRUE;
					$this->match->iscoach=TRUE;
				}
				if ($this->getLocked()==FALSE && $this->curedit==1)
				{
					$this->match->disabled = FALSE;
				}
			}
			return $this->match;
		}
		
		public function getLocked()
		{
			if (!isset($this->islocked))
			{
				if (!isset($this->match)) $this->getMatch();
				if (is_null($this->match->date_created)==false) {
					$matchtime = $this->match->date_created;
				}elseif ( is_null($this->match->date_played)==true) {
					$matchtime = date("Y-m-d H:i:s",time());
				}else{
					$matchtime = $this->match->date_played;
				}
				$table = $this->getTable('Matches','BloodBowlTable');
				$this->islocked = $table->getMatchLock($this->match->team1_id, $this->match->team2_id, $matchtime);
			}
			return $this->islocked;
		}
		
		public function getPlayersBothTeams()
		{
			if (!isset($this->playersonteams))
			{
				if (!isset($this->match)) $this->getMatch();
				
				if (!is_null($this->match->date_played)) $matchtime = $this->match->date_played;
				elseif (!is_null($this->match->date_created)) $matchtime = $this->match->date_created;
				else $matchtime = date("Y-m-d H:i:s",time());
				
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				$this->playersonteams[] = $table->getPlayers($this->match->team1_id, $matchtime, $this->match->match_id);
				$this->playersonteams[] = $table->getPlayers($this->match->team2_id, $matchtime, $this->match->match_id);
			}
			return $this->playersonteams;
		}
		
		private function getPlayersOnTeam($id=0, $matchtime=null, $matchid=null)
		{
			if ($id>0)
			{
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				$this->players = $table->getPlayers($id, $matchtime, $matchid);
			}
			return $this->players;
		}
		
		public function getTeams()
		{
			if (!isset($this->teams))
			{
				if (!isset($this->match)) $this->getMatch();
				if ($this->match->challenge==1) 
				{
					//$table = $this->getTable('Teams','BloodBowlTable');
					//$this->teams[$this->match->team1_coach]=$table->getTeams($this->match->team1_coach); //$table->getPlayers($id, $matchtime, $matchid);
					//$this->teams[$this->match->team2_coach]=$table->getTeams($this->match->team2_coach);
					$table = $this->getTable('BloodBowl','BloodBowlTable');
					$this->teams[$this->match->team1_coach]=$table->getTeamsInTourney($this->match->f_tour_id, 'tname', 'all', $this->match->team1_coach);
					$this->teams[$this->match->team2_coach]=$table->getTeamsInTourney($this->match->f_tour_id, 'tname', 'all', $this->match->team2_coach);
				}
				else
				{
					if (!is_null($this->match->date_created)) $matchtime = $this->match->date_created;
					elseif (!is_null($this->match->date_played)) $matchtime = $this->match->date_played;
					else $matchtime = date("Y-m-d H:i:s",time());
					
					$team1 = new stdClass;
					$team1->team_id = $this->match->team1_id;
					$team1->team_name = $this->match->team1_name;
					$team1->coach = $this->match->team1_coach;
					$team1->teamvalue = $this->match->team1_teamvalue;
					$team1->locked = $this->match->team1_locked;
					$team1->team_icon = $this->match->team1_icon;
					$team1->players = $this->getPlayersOnTeam($team1->team_id, $matchtime, $this->match->match_id);
					$this->teams[] =& $team1;
					if ($team1->retired==1) $this->islocked=TRUE;
					
					$team2 = new stdClass;
					$team2->team_id = $this->match->team2_id;
					$team2->team_name = $this->match->team2_name;
					$team2->coach = $this->match->team2_coach;
					$team2->teamvalue = $this->match->team2_teamvalue;
					$team2->locked = $this->match->team2_locked;
					$team2->team_icon = $this->match->team2_icon;
					$team2->players = $this->getPlayersOnTeam($team2->team_id, $matchtime, $this->match->match_id);
					$this->teams[] =& $team2;
					if ($team2->retired==1) $this->islocked=TRUE;
				}
			}
			return $this->teams;
		}
		
		public function getForm($data = array(), $loadData = true)
        {
 
        $app = JFactory::getApplication('site');
 
        // Get the form.
                $form = $this->loadForm('com_bloodbowl.matchdetail', 'matchdetail', array('control' => 'jform', 'load_data' => true));
                if (empty($form)) {
                        return false;
                }
                return $form;
 
        }
		
		public function getChallengeForm($data = array(), $loadData = true)
        {
 
        $app = JFactory::getApplication('site');
 
        // Get the form.
                $form = $this->loadForm('com_bloodbowl.challenge', 'challenge', array('control' => 'jform', 'load_data' => true));
                if (empty($form)) {
                        return false;
                }
                return $form;
 
        }
		
		public function getDelForm($data = array(), $loadData = true)
        {
 
        $app = JFactory::getApplication('site');
 
        // Get the form.
                $form = $this->loadForm('com_bloodbowl.delmatch', 'delmatch', array('control' => 'jform', 'load_data' => true));
                if (empty($form)) {
                        return false;
                }
                return $form;
 
        }
		
		public function updMatch($matchdata=null, $playerdata=null)
        {
			if (is_object($matchdata) || is_array($playerdata))
			{
				$table = $this->getTable('Matches','BloodBowlTable');
				return $table->updMatch($matchdata, $playerdata);
			}
			return false;
        }
		
		public function updChallenge($matchdata=null)
        {
			if (is_object($matchdata))
			{
				$table = $this->getTable('Matches','BloodBowlTable');
				return $table->updChallenge($matchdata);
			}
			return false;
        }
		
		public function delMatch($match=0)
        {
			if ($match>0)
			{
				$table = $this->getTable('Matches','BloodBowlTable');
				return $table->delMatch($match);
			}
			return false;
        }
		
		public function updNewsRules($tourdata=null)
        {
			if (is_object($tourdata))
			{
				$table = $this->getTable('Tourney','BloodBowlTable');
				return $table->updNewsRules($tourdata);
			}
			return false;
        }
}

?>