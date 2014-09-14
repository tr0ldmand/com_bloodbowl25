<?php

// No direct access to this file

defined('_JEXEC') or die('Restricted access');

 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
// import Joomla modelitem library
//jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');


 

class BloodBowlModelTourneyDetail extends JModelForm

{
		protected $tourneys;
		// Input vars
		protected $id;
		protected $order;
		protected $race;
		protected $coach;
		protected $rr_order;
		
		// Output vars
		protected $tourney;
		protected $teams;
		protected $matches;
		protected $races;
		protected $coaches;
		protected $standing;
		protected $bracket;
		protected $newtours;
		protected $newteams;
		

        /**
         * Get the message
         * @return string The message to be displayed to the user
         */
		
		function setInputVars()
		{
			$app =& JFactory::getApplication();
			$jinput = $app->input;
			// Set newest Rating Tourney as the std tourney if no tourney is specified
			$stdtour = $this->getTable('Tourney','BloodBowlTable')->getNewestTour()->tour_id;
			$this->id = $jinput->get('show',"$stdtour",'int');
			$this->order = $jinput->get('order','cname','str');
			$this->race = $jinput->get('races','all','str');
			$this->coach = $jinput->get('coach','0','int');
			$this->rr_order = $jinput->get('standA','point','str');
		}
        		
		public function getTourneyList($limit=10)
		{
			if (!isset($this->tourneys))
			{
				$table = $this->getTable('Tourney','BloodBowlTable');
				
				$this->tourneys = $table->getTourneyList($limit);
			}
			return $this->tourneys;
		}
		
		public function getTourney()
		{
			if (!isset($this->tourney))
			{
				if (!isset($this->id)) $this->setInputVars();
							
				$table = $this->getTable('Tourney','BloodBowlTable');
				$this->tourney = $table->getTourney($this->id);
				
				if (is_null($this->tourney->parent_id)) // You don't have a parent, then you might have children
				{
					$this->tourney->children = $table->getTourneyChildren($this->id);
				}
			}
			return $this->tourney;
		}
		
		public function getTeams()
		{
			if (!isset($this->teams))
			{
				if (!isset($this->id) || !isset($this->order) || !isset($this->race) || !isset($this->coach)) $this->setInputVars();
				$order = $this->order;
				if ($this->tourney->type=="playoff") $order = "branch";
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				$this->teams = $table->getTeamsInTourney($this->id, $order, $this->race, $this->coach);
			}
			return $this->teams;
		}
		
		public function getMatches()
		{
			if (!isset($this->matches))
			{
				if (!isset($this->id)) $this->setInputVars();
			
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				$this->matches = $table->getMatchesInTourney($this->id);
			}
			return $this->matches;
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
		
		public function getCoaches()
		{
			if (!isset($this->coaches))
			{
				if (!isset($this->id)) $this->setInputVars();
			
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				$this->coaches = $table->getCoachesInTourney($this->id);
			}
			if (!isset($this->tourney)) $this->getTourney();
			if ($this->tourney->type=="rating") $this->calcRating();
			return $this->coaches;
		}
		
		public function getStanding()
		{
			if (!isset($this->standing))
			{
				if (!isset($this->id)) $this->setInputVars();
				switch ($this->rr_order) {
					case 'win':
						$rrorder=array('win','td_for','matches');
						break;
					case 'matches':
						$rrorder=array('matches', 'point', 'td_for');
						break;
					case 'draw':
						$rrorder=array('draw','matches','win');
						break;
					case 'loss':
						$rrorder=array('loss','matches','draw');
						break;
					case 'td_for':
						$rrorder=array('td_for','matches');
						break;
					case 'td_against':
						$rrorder=array('td_against','matches');
						break;
					case 'td_diff':
						$rrorder=array('td_diff','matches');
						break;
					case 'point':
					default:
						$rrorder=array('point','win','td_for','matches');
				}
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				//$order=array('point','win','td_for','matches');
				$this->standing = $table->getTourneyStandings($this->id, $rrorder);
			}
			return $this->standing;
		}
		
		public function getBracket()
		{
			if (!isset($this->bracket))
			{
				if (!isset($this->id)) $this->setInputVars();
			
				//$table = $this->getTable('BloodBowl','BloodBowlTable');
				//$localteams = $table->getTeamsInTourney($this->id, "branch");
				$localteams = $this->getTeams();
				$numteams=count($localteams);
				$plus=ceil($numteams/2);
				$competitors = array();
				$i=0;
				$j=0;
				foreach ($localteams as $team)
				{
					if ($i%2 == 0) $competitors[$j] = $team->team_id;
					else 
					{
						$competitors[$j+$plus] = $team->team_id;
						$j++;
					}
					$i++;
				}
				//ksort($competitors);			
				$this->bracket = $competitors;
				
			}
			return $this->bracket;
			
		}
		
		function calcRating()
		{
			if (!isset($this->tourney)) $this->getTourney();
			if ($this->tourney->type!="rating") return FALSE;
			if (!isset($this->coaches)) $this->getCoaches;
			if (!isset($this->matches)) $this->getMatches;
			$match_reverse = array_reverse($this->matches);
			//$i=0;
			foreach ($match_reverse as $match){
				//find We
				//We = 1 / (10(-dr/400) + 1)
				//dr = din rating - modstanders rating + tourney_fame_rating*FAME forskel
				$cid1 = $match->team1_coach;
				$cid2 = $match->team2_coach;
				
				$dRating = ($this->coaches[$cid1]->rating)-($this->coaches[$cid2]->rating);
				//$dFAME= ($match->fame1 - $match->fame2)*25;
				$dFAME= ($match->fame1 - $match->fame2)*($this->tourney->fame_rating);
				$dTV = 2*floor(($match->TV1 - $match->TV2)/10000); // For hvert 10k pointforskel i TV t'ller som 1 rating point forskel
				
				$match->dRating = $dRating;
				$match->dFAME = $dFAME;
				$match->dTV = $dTV;
				if (!is_null($match->gate)) 
				{
					$We= 1/(1+pow(10,(-($dRating+$dFAME+$dTV))/400));
					//Rn = Ro + K × (W - We)
					$goaldiff=($match->team1_score)-($match->team2_score);
					if ($goaldiff<0)
					{ //team2 won
						$W=0;
						$this->coaches[$cid2]->won = $this->coaches[$cid2]->won+1;
						$this->coaches[$cid1]->lost = $this->coaches[$cid1]->lost+1;
					}
					elseif ($goaldiff==0)
					{ //draw
						$W=0.5;
						$this->coaches[$cid1]->draw = $this->coaches[$cid1]->draw+1;
						$this->coaches[$cid2]->draw = $this->coaches[$cid2]->draw+1;
					}
					else 
					{ //team1 won
						$W=1;
						$this->coaches[$cid1]->won = $this->coaches[$cid1]->won+1;
						$this->coaches[$cid2]->lost = $this->coaches[$cid2]->lost+1;
					}
					$goaldiff = abs($goaldiff);
					$K = max(40,40*(2-pow(0.5,-1+$goaldiff)));
					//$cid=$cid1;
					$change = round($K * ($W - $We));
					
					$this->coaches[$cid1]->matches += 1;
					$this->coaches[$cid1]->winpct = round(($this->coaches[$cid1]->won / $this->coaches[$cid1]->matches)*100,1);
					$this->coaches[$cid1]->rchange = $change;
					$this->coaches[$cid1]->rating += $change;
					
					$this->coaches[$cid2]->matches += 1;
					$this->coaches[$cid2]->winpct = round(($this->coaches[$cid2]->won / $this->coaches[$cid2]->matches)*100,1);
					$this->coaches[$cid2]->rchange = (-1*$change);
					$this->coaches[$cid2]->rating -= $change;

			
			
					if ($match->c_rating_1 != $this->coaches[$cid1]->rating){
						//update match with rating and change
						/* $query = "UPDATE #__bb_matches
								SET c_rating_1 = {$this->coaches[$cid1]->rating},
									c_rating_2 = {$this->coaches[$cid2]->rating},
									c_rat_change = {$change}
								WHERE match_id = {$match->match_id}";
						$database->setQuery( $query );
						$database->query();
						echo $database->geterrormsg();
						*/
						$matchdata = new stdClass;
						$matchdata->match_id = $match->match_id;
						$matchdata->c_rating_1 = $this->coaches[$cid1]->rating;
						$matchdata->c_rating_2 = $this->coaches[$cid2]->rating;
						$matchdata->c_rat_change = $change;
						
						$this->getTable('Matches','BloodBowlTable')->updMatch($matchdata);
						unset($matchdata);
					}
				}
								//if ($i++>180) JFactory::getApplication()->enqueueMessage("Model $i: ".print_r($match,true), 'warning');
			}
			uasort($this->coaches, array($this,'ratingSort'));
			
			return TRUE;
		}
		
		function ratingSort($c1, $c2)
		{
			if (round($c2->rating - $c1->rating)==0) return $c2->winpct - $c1->winpct;
			return $c2->rating - $c1->rating;
		}
		
		public function getForm($data = array(), $loadData = true)
        {
		//$app = JFactory::getApplication('site');
			$form = $this->loadForm('com_bloodbowl.tourneyadmin', 'tourneyadmin', array('control' => 'jform', 'load_data' => true));
			if (empty($form)) 
			{
				return false;
			}
			return $form;
        }
		
		public function getNewChallengeForm($data = array(), $loadData = true)
        {
		//$app = JFactory::getApplication('site');
			$form = $this->loadForm('com_bloodbowl.newchallenge', 'newchallenge', array('control' => 'jform', 'load_data' => true));
			if (empty($form)) 
			{
				return false;
			}
			return $form;
        }
		
		public function getTeamsNotIn()
		{
			if (!isset($this->newteams))
			{
				if (!isset($this->id) || !isset($this->coach)) $this->setInputVars();
			
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				$this->newteams = $table->getTeamsNotInTourney($this->id, $this->coach, $this->order);
			}
			//JFactory::getApplication()->enqueueMessage('Model says (teams): '.print_r($this->newteams, true), 'notice');
			return $this->newteams;
		}
		
		public function getToursNotIn()
		{
			if (!isset($this->newtours))
			{
				if (!isset($this->id)) $this->setInputVars();
			
				$table = $this->getTable('BloodBowl','BloodBowlTable');
				$this->newtours = $table->getToursNotInTourney($this->id);
			}
			//JFactory::getApplication()->enqueueMessage('Model says (tours): '.print_r($this->newtours, true), 'notice');
			return $this->newtours;
		}
		
		public function addTeams($newteams=null, $tour=0)
		{
			if (!empty($newteams) && is_array($newteams) && $tour>0)
			{
				$table = $this->getTable('Tourney','BloodBowlTable');
				foreach ($newteams AS $team)
				{
					$res[] = $table->addTeam($tour, $team);
				}
				if (!in_array(false,$res)) return true;
			}
			return false;
		}
		
		public function addTours($newtours=null, $tour=0)
		{
			if (!empty($newtours) && is_array($newtours) && $tour>0)
			{
				$table = $this->getTable('Tourney','BloodBowlTable');
				foreach ($newteams AS $team)
				{
					$res[] = $table->addTour($tour, $team);
				}
				if (!in_array(false,$res)) return true;
			}
			return false;
		}
}

?>