<?php
// No direct access to this file
defined('_JEXEC') or die;
 
jimport('joomla.database.table');
 
class BloodBowlTableBloodBowl extends JTable
{
        function __construct(&$db) 
        {
                parent::__construct('#__bb_tourneys', 'tour_id', $db);
        }
		
		public function getTeamStats($id)
		{
			if ($id>0)
			{
				$qF="SELECT SUM(if(result>0,1,0)) AS win, SUM(if(result=0,1,0)) AS draw, SUM(if(result<0,1,0)) AS loss, SUM(td_for) AS td_for, SUM(td_against) AS td_against FROM
					((SELECT team1_score AS td_for, team2_score AS td_against, (team1_score-team2_score) as result
						FROM #__bb_matches
						WHERE team1_id = ". $id ." AND `gate` IS NOT NULL
						ORDER BY date_played ASC, date_created ASC)
					UNION ALL
					(SELECT team2_score AS td_for, team1_score AS td_against, (team2_score-team1_score) as result
						FROM #__bb_matches
						WHERE team2_id = ". $id ." AND `gate` IS NOT NULL
						ORDER BY date_played ASC, date_created ASC)) AS a";
				$this->_db->setQuery($qF);
				$rows = $this->_db->loadObject();
				return $rows;
			}
		}
		public function getMatches($id=0, $return="all", $tour=0, $start=0, $limit=20)
		{
			if ($return!="new" && $return!="played") $return="all";
			$where = "( a.team1_id = ". $id ." OR a.team2_id = ". $id ." )";
			if ($tour>0)
			{
				$id=$tour;
				$where = "(a.f_tour_id=". $id ." OR a.f_tour_id IN (SELECT child FROM #__bb_tourneys_in_tourneys WHERE parent=". $id .")) ";
			}
			if ($start==-1 || $limit<=0)
			{
				$limit="";
			}
			else
			{
				$limit = "LIMIT $start,$limit";
			}
			if ($id>0)
			{
				      
				$q = "SELECT a.match_id, a.f_tour_id, b.name AS tour_name, a.team1_id, c.name AS team1_name, c.coach AS team1_coach, a.team2_id, d.name AS team2_name, d.coach AS team2_coach, a.team1_score, a.team2_score, a.fame1, a.fame2, a.gate, a.TV1, a.TV2, a.challenge, a.date_played, b.admins, a.comment
					FROM #__bb_matches AS a
					LEFT JOIN #__bb_tourneys as b ON a.f_tour_id = b.tour_id
					LEFT JOIN #__bb_teams AS c ON a.team1_id = c.id
					LEFT JOIN #__bb_teams AS d ON a.team2_id = d.id ";
				// Get matches that haven't been played
				$qD = $q ."WHERE a.gate IS NULL AND $where 
					ORDER BY date_played DESC, date_created DESC";
				$this->_db->setQuery($qD);
				$newmatches = $this->_db->loadObjectList();
				
				// Get matches that have been played
				$qE = $q ."WHERE a.gate IS NOT NULL AND $where 
					ORDER BY date_played DESC, date_created DESC 
					$limit";
				$this->_db->setQuery($qE);
				$playedmatches = $this->_db->loadObjectList();
				//JFactory::getApplication()->enqueueMessage('Tabel: '.$qE, 'warning');		
				
				if ($return=="new")
				{
					return $newmatches;
				}
				elseif ($return=="played")
				{
					
					return $playedmatches;
				}
				else
				{
					return array_merge($newmatches,$playedmatches);
				}
			}
			return FALSE;
		}
		
		public function getPlayers($teamid, $date = null, $matchid = null)
		{
			if ($matchid == null) 
			{ 
				$matchid = '%';
			}
			if ($date == null) { 
				$date = date("Y-m-d H:i:s");
			}
			$db = &$this->_db;
			$qC = $db->getQuery(true);
			$qC->select(array('b.*', 'a.*',
							"sum({$db->qn('c.mvp')}) as {$db->qn('mvp')}",
							"sum({$db->qn('c.cp')}) as {$db->qn('cp')}",
							"sum({$db->qn('c.td')}) as {$db->qn('td')}",
							"sum({$db->qn('c.intcpt')}) as {$db->qn('intcpt')}",
							"sum({$db->qn('c.bh')}) as {$db->qn('bh')}",
							"sum({$db->qn('c.si')}) as {$db->qn('si')}",
							"sum({$db->qn('c.ki')}) as {$db->qn('ki')}", 
							"GROUP_CONCAT({$db->qn('c.inj')} SEPARATOR ' ') as {$db->qn('inj')}",
							$db->qn('c.inj', 'newinj'),
							$db->qn('b.move', 'orgmo'),
							$db->qn('b.strength', 'orgst'),
							$db->qn('b.agility', 'orgag'),
							$db->qn('b.armor', 'orgar')));
			$qC->from($db->qn('#__bb_players', 'a'));
			$qC->join('INNER', $db->qn('#__bb_roster', 'b') .' ON '. $db->qn('a.roster_id') .'='. $db->qn('b.id'));
			$qC->join('LEFT', $db->qn('#__bb_match_data', 'c') .' ON ('. $db->qn('a.player_id') .'='. $db->qn('c.f_player_id') .' AND '. $db->qn('c.f_match_id') .' LIKE '. $db->q($matchid) .')');
			$qC->where($db->qn('a.owned_by_team_id') ."= $teamid");
			$qC->where("(isnull(a.date_sold) OR a.date_sold >= '$date')");
			$qC->where($db->qn('a.date_bought') .'<='. $db->q($date));
			$qC->group($db->qn('a.player_id'));
			$qC->order($db->qn('a.nr'));
			
			/*$oldqC = "SELECT b.*, a.*,
				sum(c.mvp) as mvp, sum(c.cp) as cp,sum(c.td) as td,sum(c.intcpt) AS intcpt,sum(c.bh) as bh,sum(c.si) as si,sum(c.ki) as ki, 
				GROUP_CONCAT(c.inj SEPARATOR ' ') as inj, c.inj AS newinj, b.move AS orgmo, b.strength AS orgst, b.agility AS orgag, b.armor AS orgar
				FROM #__bb_players AS a INNER JOIN #__bb_roster AS b ON a.roster_id=b.id".
				" LEFT JOIN #__bb_match_data AS c ON (a.player_id = c.f_player_id AND c.f_match_id LIKE '$matchid')".
				" WHERE a.owned_by_team_id =".$teamid.
				" AND (isnull(a.date_sold) OR a.date_sold >= '$date')".
				" AND a.date_bought <= '$date'".
				" GROUP BY a.player_id".
				" ORDER BY a.nr";*/
			$this->_db->setQuery($qC);
			//JFactory::getApplication()->enqueueMessage('Executed MySQL. '.$qC->dump(), 'warning');
			//JFactory::getApplication()->enqueueMessage('Original MySQL. <pre>'.$oldqC .'</pre>', 'warning');
			$players = $this->_db->loadObjectList('nr');
			
			$ekstrainfo[0] = new stdClass;
			$ekstrainfo[0]->playervalue=0;
			$ekstrainfo[0]->numplayers=0;
			$ekstrainfo[0]->activeplayers=0;
			$ekstrainfo[0]->newskills=0;
			$ekstrainfo[0]->positions=array();
			
			// get current status for all players
			if ($matchid <> '%' ) {
				// $status = getStatus(date("Y-m-d H:i:s", strtotime($date)+1));
				$matchtime = date("Y-m-d H:i:s", strtotime($date));
			}
			else 
			{
				// $status = getStatus();
				$matchtime = date("Y-m-d H:i:s",time());
			}
			$qD = $db->getQuery(true);
			$qD->select(array(
						"max({$db->qn('b.date_created')}) as {$db->qn('date_created')}",
						$db->qn('a.f_match_id', 'match_id'),
						$db->qn('a.f_player_id'),
						$db->qn('a.inj', 'inj')));
			$qD->from($db->qn('#__bb_matches', 'b'));
			$qD->join('LEFT', $db->qn('#__bb_match_data','a') .' ON '. $db->qn('b.match_id') .'='. $db->qn('a.f_match_id'));
			$qD->where($db->qn('b.date_created') .'<'. $db->q($matchtime));
			$qD->group($db->qn(array('b.date_created', 'a.f_player_id')));
			
			/*$qD = "SELECT max(b.date_created) as date_created, a.f_match_id as match_id, a.f_player_id, a.inj as inj 
					FROM #__bb_matches as b
					JOIN #__bb_match_data as a ON b.match_id = a.f_match_id
					WHERE b.date_created < '$matchtime'
					GROUP BY b.date_created, a.f_player_id";*/
			$this->_db->setQuery($qD);
			$status = $this->_db->loadObjectList('f_player_id');
			
			// clean injury text and apply modifiers to ability scores
			foreach ( $players as $key => $player ) 
			{
				// count the number of players on each position
				$ekstrainfo[0]->positions[$player->roster_id]++;
				
				// add loner skill to journeymen
				if (strstr($player->position, "journey") == "journeyman")
				{
					$players[$key]->skills="Loner, ".$player->skills;
				}
				// replace injury: NONE and MNG with blanks
				$players[$key]->inj = preg_replace('/,?(NONE|MNG),?/', '', $players[$key]->inj);
				// update active MNG's
				if ( $status[$player->player_id]->inj <> 'NONE' AND !is_null($status[$player->player_id]->inj))
				{
					if ($matchid == '%')
					{
                        $players[$key]->inj = $players[$key]->inj.(strlen($players[$key]->inj)>0 ? ' ' : '').'MNG';
                    }
					else
					{
                        $players[$key]->inj = $players[$key]->inj.(strlen($players[$key]->inj)>0 ? '' : 'MNG');
                    }
                }
				// apply injury MA, AG, ST, AV modifiers
				$players[$key]->move = $players[$key]->move - substr_count($players[$key]->inj, "MA");
				$players[$key]->strength = $players[$key]->strength - substr_count($players[$key]->inj, "ST");
				$players[$key]->agility = $players[$key]->agility - substr_count($players[$key]->inj, "AG");
				$players[$key]->armor = $players[$key]->armor - substr_count($players[$key]->inj, "AV");
				
				// set newskill to false, later change it if update is needed
				$players[$key]->newskill = FALSE;
				// calculate SPP and indicate skill upgrades
				$spp = $player->cp + $player->td * 3 + $player->intcpt * 2 + $player->mvp * 5 + ( $player->bh + $player->si + $player->ki ) * 2;
				$players[$key]->spp = $spp;
				$spp_levels = array(0,6,16,31,51,76,176);
				foreach ( $spp_levels as $key2=>$spp_level ) 
				{
					if ( $spp >= intval($spp_level) ) 
					{
						$level = $key2;
					}
				}
				if ($level > 0 )
				{
					$ach_skills = explode(", ",$player->ach_skills);
					//ensure no duplicate skills exist
					$ach_skills = array_unique($ach_skills);
					$players[$key]->ach_skills = implode(", ",$ach_skills);
					$curr_skills = count($ach_skills);
					if ( $curr_skills < $level || $player->ach_skills==null) 
					{
						$players[$key]->newskill = TRUE;
						$ekstrainfo[0]->newskills++;
					}
					// Calculate player value increase based on skills and apply stat increases
					//$qE = "SELECT * FROM #__bb_skills ORDER BY name";
					$query = $this->_db->getQuery(true);
					$query->select('*')->from($this->_db->qn('#__bb_skills'))->order($this->_db->qn('name'));
					$this->_db->setQuery($query);
					$skill_list = $this->_db->loadObjectList('name');
				
					if ($player->ach_skills<>null)
					{
						foreach ($ach_skills as $skill )
						{
							$type = $skill_list[trim($skill)]->type;
							if ($type<>null)
							{
								if (strstr($player->normal, $type ) )
								{
									$players[$key]->cost=$players[$key]->cost+'20000';
								}
								elseif (strstr($player->double , $type ) )
								{
									$players[$key]->cost=$players[$key]->cost+'30000';
								}
								elseif (trim($skill) == "+1 MA")
								{
									$players[$key]->move = $players[$key]->move +'1';
									$players[$key]->cost=$players[$key]->cost+'30000';
								}
								elseif (trim($skill) == "+1 ST")
								{
									$players[$key]->strength = $players[$key]->strength +'1';
									$players[$key]->cost=$players[$key]->cost+'50000';
								}
								elseif (trim($skill) == "+1 AG")
								{
									$players[$key]->agility = $players[$key]->agility +'1';
									$players[$key]->cost=$players[$key]->cost+'40000';
								}
								elseif (trim($skill) == "+1 AV")
								{
									$players[$key]->armor = $players[$key]->armor +'1';
									$players[$key]->cost=$players[$key]->cost+'30000';
								}
							}
						}
					}
				}
				//set value to 0 for MNG players
				if (strstr($players[$key]->inj, "MNG"))
				{ 
					$players[$key]->cost = 0; 
				}
				else
				{
					$ekstrainfo[0]->activeplayers++;
				}
				$ekstrainfo[0]->playervalue += $players[$key]->cost;
				$ekstrainfo[0]->numplayers++;
			}
			
			return $ekstrainfo + $players;
		}
		
		public function getMatchesInTourney($id=29, $start=-1, $limit=20)
		{
			if ($id>0)
			{
				return $this->getMatches(0, 'all', $id, $start, $limit);
			}
			return FALSE;
		}
		
		public function getTeamsInTourney($id=29, $order="cname", $race="all", $coach=0)
		{
			
			if ($id>0)
			{
				$db = &$this->_db;
				$qB = $db->getQuery(true);
				$qB->select($db->qn(array('a.team_id', 'b.name','b.race','b.icon','b.teamvalue','c.name','c.id','a.branch'),array('team_id','team_name','race','icon','teamvalue','coach_name','coach_id','branch')));
				$qB->from($db->qn('#__bb_teams_in_tourney','a'));
				$qB->join('INNER',"{$db->qn('#__bb_teams','b')} ON {$db->qn('a.team_id')}={$db->qn('b.id')}");
				$qB->join('LEFT',"{$db->qn('#__users','c')} ON {$db->qn('b.coach')}={$db->qn('c.id')}");
				$qB->where("{$db->qn('a.tour_id')} = {$db->q($id)}");
				if ($race!="all") 	$qB->where("{$db->qn('b.race')}={$db->q($race)}");
				if ($coach>0) 		$qB->where("{$db->qn('c.id')}={$db->q($coach)}");
				if ($order=="race")			$qB->order("b.race, c.name, b.name");
				elseif ($order=="tv") 		$qB->order("b.teamvalue, c.name, b.name");
				elseif ($order=="tname") 	$qB->order("b.name, c.name");
				elseif ($order=="branch") 	$qB->order("a.branch, b.name");
				else 						$qB->order("c.name, b.name"); //($order=="cname")
				
				$this->_db->setQuery($qB);
				//JFactory::getApplication()->enqueueMessage('Executed MySQL. '.$qB->dump(), 'warning');
				//JFactory::getApplication()->enqueueMessage('Original MySQL. <pre>'.$oldqB .'</pre>', 'warning');
				$rows = $this->_db->loadObjectList('team_id');
				return $rows;
			}
			return FALSE;
		}
		
		public function getCoachesInTourney($id=0)
		{
			if ($id>0)
			{
				$qG = "SELECT a.coach AS id, 1200 AS rating, 0 AS won, 0 AS draw, 0 AS lost, 0 AS rchange, 0 AS matches, 0 AS winpct
					FROM #__bb_teams AS a 
					WHERE a.id IN ( 
						SELECT team_id FROM #__bb_teams_in_tourney
						WHERE tour_id =". $id ." OR tour_id IN (
							SELECT child FROM #__bb_tourneys_in_tourneys
							WHERE parent =". $id ." ) 
					) GROUP BY a.coach";
				$this->_db->setQuery($qG);
				$rows = $this->_db->loadObjectList('id');
				return $rows;
			}
			return FALSE;
		}
		
		public function getTourneyStandings($id=0, $order=array('point','win','td_for','matches'))
		{
			if ($id>0)
			{
				$where = "";
				foreach ($order as $field)
				{
					$where .= "`". $field ."` DESC, ";
				}
				$where = rtrim($where,', ');
				// This is how we calculate points
				// win 3 point if(result>0,3,0)
				// draw 1 point if(result=0,1,0)
				// loss 0 point
				$calc_points= 'SUM(if(result>0,3,0)+if(result=0,1,0))';
				
				$qH = "SELECT team_id, SUM(1) AS `matches`, ". $calc_points ." AS point, SUM(if(result>0,1,0)) AS win, SUM(if(result=0,1,0)) AS draw, SUM(if(result<0,1,0)) AS loss, SUM(td_for) AS td_for, SUM(td_against) AS td_against, SUM(result) AS td_diff 
					FROM (
						(SELECT team1_id AS team_id, team1_score AS td_for, team2_score AS td_against, (team1_score-team2_score) AS result 
							FROM #__bb_matches 
							WHERE f_tour_id= ". $id ." AND `gate` IS NOT NULL 
							ORDER BY date_played ASC, date_created ASC) 
						UNION ALL 
						(SELECT team2_id AS team_id, team2_score AS td_for, team1_score AS td_against, (team2_score-team1_score) AS result 
							FROM #__bb_matches 
							WHERE f_tour_id= ". $id ." AND `gate` IS NOT NULL 
							ORDER BY date_played ASC, date_created ASC)
						) AS a
					GROUP BY team_id
					ORDER BY ". $where;
				$this->_db->setQuery($qH);
				/* New query
				$query = $this->_db->getQuery(true);
				$query->select("team_id, SUM(1) AS `matches`, ". $calc_points ." AS point, SUM(if(result>0,1,0)) AS win, SUM(if(result=0,1,0)) AS draw, SUM(if(result<0,1,0)) AS loss, SUM(td_for) AS td_for, SUM(td_against) AS td_against, SUM(result) AS td_diff");
				$query->from("(
						(SELECT team1_id AS team_id, team1_score AS td_for, team2_score AS td_against, (team1_score-team2_score) AS result 
							FROM #__bb_matches 
							WHERE f_tour_id= ". $id ." AND `gate` IS NOT NULL 
							ORDER BY date_played ASC, date_created ASC) 
						UNION ALL 
						(SELECT team2_id AS team_id, team2_score AS td_for, team1_score AS td_against, (team2_score-team1_score) AS result 
							FROM #__bb_matches 
							WHERE f_tour_id= ". $id ." AND `gate` IS NOT NULL 
							ORDER BY date_played ASC, date_created ASC)
						)");
				$query->groupby
				$query->order
				*/
				$rows = $this->_db->loadObjectList();
				//JFactory::getApplication()->enqueueMessage($qH, 'notice');
				return $rows;
			}
			return FALSE;
		}
		
		public function getRoster($race=null)
		{
			if (!is_null($race))
			{
				$query = $this->_db->getQuery(true);
				$query->select('*');
				$query->from($this->_db->quoteName('#__bb_roster'));
				$query->where($this->_db->quoteName('race') ." LIKE ". $this->_db->quote($race));
				$this->_db->setQuery($query);
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				return $this->_db->loadObjectList('id');
			}
			return false;
		}
		
		public function getTeamsNotInTourney($id=0, $coach=0, $order=null)
		{
			if ($id>0)
			{
				$query = $this->_db->getQuery(true);
				$query->select('id, name, coach, race, Startval');
				$query->from($this->_db->quoteName('#__bb_teams'));
				if ($coach>0) $query->where($this->_db->quoteName('coach') ."=$coach");
				else $query->where($this->_db->quoteName('coach') .">0");
				$query->where($this->_db->quoteName('retired') ."=0");
				$query->where($this->_db->quoteName('id') ." NOT IN (SELECT ". $this->_db->qn('team_id') ." FROM ". $this->_db->qn('#__bb_teams_in_tourney') ." WHERE ". $this->_db->qn('tour_id') ."=$id)");
				if ($order=="coach") $query->order('coach');
				if ($order=="race") $query->order(array('race','coach'));
				$query->order('name');
				
				$this->_db->setQuery($query);
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				$tmp=$this->_db->loadObjectList();
				//JFactory::getApplication()->enqueueMessage('SQL passed '. $query->dump() .'Result obtained. '.print_r($tmp, true), 'notice');
				return $tmp;
			}
			return array();
		}
		
		public function getToursNotInTourney($id=0)
		{
			if ($id>0)
			{
				$query = $this->_db->getQuery(true);
				$query->select('tour_id, name, type');
				$query->from($this->_db->quoteName('#__bb_tourneys'));
				$query->where($this->_db->quoteName('finished') ."=". $this->_db->q('0000-00-00'));
				$query->where($this->_db->quoteName('type') ."NOT LIKE". $this->_db->q('rating'));
				$query->where($this->_db->quoteName('tour_id') ." NOT IN (SELECT child FROM `#__bb_tourneys_in_tourneys` WHERE `parent`=$id)");
				$this->_db->setQuery($query);
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				//JFactory::getApplication()->enqueueMessage('SQL executed. '.$query->dump(), 'notice');
				return $this->_db->loadObjectList();
			}
			return array();
		}

}

class BloodBowlTableTourney extends JTable
{
        function __construct(&$db) 
        {
                parent::__construct('#__bb_tourneys', 'tour_id', $db);
        }
		
		public function getTourneyList($limit=30)
		{
			//$qA="SELECT * FROM #__bb_tourneys WHERE finished IS NULL ORDER BY created, name LIMIT 0,$limit";
			$qA = $this->_db->getQuery(true);
			$qA->select('*')->from('#__bb_tourneys')->where('finished IS NULL')->order('created, name');
			$this->_db->setQuery($qA, 0, $limit);
			$rows = $this->_db->loadObjectList();
			$limit = $limit - count($rowsA);
			if ($limit>0)
			{
				//$qB="SELECT * FROM #__bb_tourneys WHERE finished NOT LIKE '0000-00-00' ORDER BY finished DESC, name LIMIT 0,$limit";
				$qB = $this->_db->getQuery(true);
				$qB->select('*')->from('#__bb_tourneys')->where("finished NOT LIKE '0000-00-00'")->order('finished DESC, name');
				$this->_db->setQuery($qB, 0, $limit);
				$rowsB = $this->_db->loadObjectList();
				$rows = array_merge($rows,$rowsB);
			}
			return $rows;
		}
		
		public function getTourney($id=0)
		{
			/*$qC="SELECT a.*, b.parent AS parent_id, c.name AS parent_name FROM #__bb_tourneys AS a
				LEFT JOIN #__bb_tourneys_in_tourneys AS b ON b.child = a.tour_id 
				LEFT JOIN #__bb_tourneys AS c ON c.tour_id = b.parent 
				WHERE a.tour_id=". $id ."";*/
			$qC = $this->_db->getQuery(true);
			$qC->select('a.*, b.parent AS parent_id, c.name AS parent_name');
			$qC->from('#__bb_tourneys AS a');
			$qC->join('LEFT','#__bb_tourneys_in_tourneys AS b ON b.child = a.tour_id');
			$qC->join('LEFT','#__bb_tourneys AS c ON c.tour_id = b.parent');
			$qC->where("a.tour_id=$id");
			$this->_db->setQuery($qC, 0 ,1);
			$row = $this->_db->loadObject();
			return $row;
		}
		
		public function getNewestTour($type='rating')
		{
			$query = $this->_db->getQuery(true);
			$query->select('*')->from($this->_db->qn('#__bb_tourneys'));
			$query->where($this->_db->qn('finished') .'='. $this->_db->q('0000-00-00'));
			$query->where($this->_db->qn('type') .' LIKE '. $this->_db->q("%{$type}%"));
			$query->order($this->_db->qn('created') .'DESC');
			$query->setLimit(1,0);
			$this->_db->setQuery($query);
			
			return $this->_db->loadObject();
		}
		
		public function getTourneyChildren($id=0)
		{
			/*$qD="SELECT b.tour_id, b.name, b.type, b.created, b.finished
				FROM #__bb_tourneys_in_tourneys AS a
				LEFT JOIN #__bb_tourneys AS b ON a.child = b.tour_id
				WHERE a.parent = ". $id ."
				ORDER BY created";*/
			$qD = $this->_db->getQuery(true);
			$qD->select('b.tour_id, b.name, b.type, b.created, b.finished');
			$qD->from('#__bb_tourneys_in_tourneys AS a');
			$qD->join('LEFT','#__bb_tourneys AS b ON a.child = b.tour_id');
			$qD->where("a.parent =$id");
			$qD->order('created');
			$this->_db->setQuery($qD);
			$rows = $this->_db->loadObjectList();
			return $rows;
		}
		
		public function addTeam($tour=0, $team=0, $branch=null)
		{
			if ($tour>0 && $team>0)
			{
			$ins = new stdClass;
			$ins->tour_id = &$tour;
			$ins->team_id = &$team;
			$ins->branch = &$branch;
			return $this->_db->insertObject('#__bb_teams_in_tourney',$ins);
			}
		}
		
		public function addTour($parent=0, $child=0)
		{
			if ($parent>0 && $child>0)
			{
			$ins = new stdClass;
			$ins->{parent} = &$parent;
			$ins->child = &$child;
			return $this->_db->insertObject('#__bb_tourneys_in_tourneys',$ins);
			}
		}
		
		public function updNewsRules($tourdata=null)
		{
			if (!empty($tourdata) && is_object($tourdata))
			{
				$res = $this->_db->updateObject('#__bb_tourneys',$tourdata,'tour_id');
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				return true;
			}
			return false;
		}

}

class BloodBowlTableTeams extends JTable
{
        function __construct(&$db) 
        {
                parent::__construct('#__bb_teams', 'id', $db);
        }
		
		public function getNumberOfTeams($id=0, $race="all", $tvbund=0,$tvtop=0)
		{
			$where="`retired` < 2";
			if ($race!="all")
			{
				$where .= " AND `race` LIKE '$race'";
			}
			if ($id>0) 
			{
				$where .= " AND `coach` =". $id;
			}
			else
			{
				$where .= " AND `coach`>0";
				$where .= " AND `name` != ''";
			}
			if ($tvbund>0)
			{
				$where .= " `teamvalue` >". $tvbund;
			}
			if ($tvtop>500000)
			{
				$where .= " `teamvalue` <". $tvtop;
			}
			// $qA="SELECT COUNT(*) FROM #__bb_teams WHERE $where";
			$qA = $this->_db->getQuery(true);
			$qA->select('COUNT(*)')->from('#__bb_teams')->where($where);
			$this->_db->setQuery($qA);
			$row = $this->_db->loadResult();
			if ($this->_db->getErrorNum()>0)
			{
				JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
			}
			return $row;
		}
		
		public function getTeams($id=0, $order="name", $race="all", $tvbund=0,$tvtop=0, $start=0, $limit=30)
		{
			if ($order=="name") $orderby="retired, name ASC";
			if ($order=="named") $orderby="retired, name DESC";
			if ($order=="race") $orderby="race ASC, retired, name";
			if ($order=="raced") $orderby="race DESC, retired, name";
			if ($order=="teamvalue") $orderby="retired, teamvalue ASC";
			if ($order=="teamvalued") $orderby="retired, teamvalue DESC";
			if ($order=="date") $orderby="id ASC, retired";
			if ($order=="dated") $orderby="id DESC, retired";
			
			$query=$this->_db->getQuery(true);
			$query->select('a.*');
			$query->select("GROUP_CONCAT(DISTINCT d.tour_id ) AS tours, GROUP_CONCAT(DISTINCT d.name ) AS tour_names");
			$query->from('#__bb_teams AS a');
			$query->join('LEFT',"(SELECT b.tour_id, c.name, c.finished, b.team_id FROM #__bb_teams_in_tourney AS b INNER JOIN #__bb_tourneys AS c ON b.tour_id=c.tour_id WHERE c.finished='0000-00-00') AS d ON a.id=d.team_id");
			$query->group('a.id');
			$query->order($orderby);
			
			$query->where('`a`.`retired` < 2');
			if ($race!="all")
			{
				$query->where("`a`.`race` LIKE '$race'");
			}
			if ($id>0) 
			{
				$query->where("`a`.`coach` =$id");
			}
			else
			{
				$query->where('`a`.`coach`>0');
				$query->where("`a`.`name` != ''");
			}
			if ($tvbund>0)
			{
				$query->where("`a`.`teamvalue` > '$tvbund'");
			}
			if ($tvtop>500000)
			{
				$query->where("`a`.`teamvalue` < '$tvtop'");
			}
			
			//JFactory::getApplication()->enqueueMessage('Table: '.$query->dump(), 'warning');
			
			$this->_db->setQuery($query, $start, $limit);
			$rows = $this->_db->loadObjectList();
			if ($this->_db->getErrorMsg()) return $this->_db->getErrorMsg();
			foreach ($rows AS &$team)
			{
				if ($team->tours<>'')
				{
					$tour_ids = explode(',', $team->tours);
					$team->tour_names = explode(',', $team->tour_names);
					$tours=array();
					foreach ($tour_ids AS $k=>$v)
					{
						if ((int)$v>0)
						{
							$localtour = new stdClass;
							$localtour->id = (int)$v;
							$localtour->name = trim($team->tour_names[$k]);
							$tours[] = $localtour;
							unset($localtour);
						}
					}
					$team->tours = $tours;
					unset($tour_ids);
					unset($tours);
				}
				else $team->tours=array();
				unset($team->tour_names);
			}
			
			return $rows;
		}
		
		public function getOneTeam($id=0, $matchtime = null)
		{
			if ($id>0) 
			{
				/*
				$qE = "SELECT a.id, a.name, a.coach, a.race, a.RR, sum( b.ffactor + a.FF ) AS FF, a.A_Coach, a.CheerLeader, a.Apoth, a.Treasury + ( sum( b.income ) *1000 ) AS Treasury, a.icon, a.RRcost, a.Startval, a.retired, a.miscvalue, a.teamvalue, sum( b.gate ) AS gate, sum( b.matches ) AS matches, a.comment FROM (
						SELECT sum(gate) as gate, count(*) as matches, sum(income1) as income, sum(ffactor1) AS ffactor 
							FROM `#__bb_matches` 
							WHERE team1_score IS NOT NULL 
							AND team1_id =". $id ."
							GROUP BY team1_id
						UNION ALL
						SELECT sum(gate) as gate, count(*) as matches, sum(income2) as income, sum(ffactor2) as ffactor 
							FROM `#__bb_matches` 
							WHERE team1_score IS NOT NULL 
							AND team2_id =". $id ."
							GROUP BY team2_id) AS b, `#__bb_teams` AS a 
						WHERE a.id=". $id;
				$this->_db->setQuery($qE);
				$team = $this->_db->loadObject();
				return $team;
				*/
				//$qE="SELECT * FROM #__bb_teams WHERE `id`=". $id ." LIMIT 0,1";
				$qE = $this->_db->getQuery(true);
				$qE->select('*')->from('#__bb_teams')->where("`id`=$id");
				$this->_db->setQuery($qE, 0, 1);
				$team = $this->_db->loadObject();
				
				if ( is_null($matchtime)==true) 
				{
					$matchtime = date("Y-m-d H:i:s",time());
				}
				
				$qF = "SELECT sum(gate) as gate, sum(matches) as matches, sum(income) as income, sum(ffactor) as FF FROM (
						SELECT sum(gate) as gate, count(*) as matches, sum(income1) as income, sum(ffactor1) as ffactor FROM `#__bb_matches` WHERE team1_score IS NOT NULL AND team1_id =$id GROUP BY team1_id
						UNION ALL
						SELECT sum(gate) as gate, count(*) as matches, sum(income2) as income, sum(ffactor2) as ffactor FROM `#__bb_matches` WHERE team1_score IS NOT NULL AND team2_id =$id GROUP BY team2_id) as b";
				$this->_db->setQuery($qF);
				$match_data = $this->_db->loadObject();
				if ($match_data->gate > 0)
				{
					$team->gate = $match_data->gate;
					$team->matches = $match_data->matches;
					$team->Treasury += (intval($match_data->income))*1000;
					$team->income = (intval($match_data->income))*1000;
					$team->FF += intval($match_data->FF);
				}
				return $team;
			}
			return FALSE;
		}
		
		public function getNewTeams()
		{
			$qB="SELECT * FROM #__bb_teams WHERE coach=0 ORDER BY race";
			$this->_db->setQuery($qB);
			$rows = $this->_db->loadObjectList();
			return $rows;
		}
		
		public function insertTeam($coach=0, $race=0, $startvalue=1000000, $name="")
		{
			if ($coach>0 && $race>0 && $startvalue>500000 && $name!="")
			{
				//Get default settings from database
				$this->_db->setQuery("SELECT * FROM #__bb_teams WHERE coach = 0 AND id=$race LIMIT 0,1");
				$default = $this->_db->loadObject();
				
				$default->id = null;
				$default->name = $name;
				$default->coach = $coach;
				$default->Treasury = $startvalue;
				$default->Startval = $startvalue;
				$default->locked = 1;
				
				//Insert the new team in the database
				$res = $this->_db->insertObject('#__bb_teams',$default, 'id');
				//JFactory::getApplication()->enqueueMessage('Tabel: '. print_r($default->id, true), 'warning');
				
				//Check for success
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'error');
				}
				if (is_null($default->id))
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
					return 0;
				}
				return $default->id;
			}
			return 0;
		}
		
		public function updTeam($team=null)
		{
			if (is_array($team))
			{
				foreach ($team AS $k=>$v)
				{
					if ($k=="id" && !($v>21)) return false;
					if ($k=='TreasuryChange') 
					{
						$values[] = $this->_db->qn('Treasury') ."=". $this->_db->qn('Treasury') ."+$v";
					}
					else
					{
						$values[] = $this->_db->qn($k) .'='. $this->_db->q($v);
					}
				}
				$query = $this->_db->getQuery(true);
				$query->update($this->_db->qn('#__bb_teams'));
				$query->set($values);
				$query->where($this->_db->qn('id') ."=". $this->_db->q($team['id']));
				//JFactory::getApplication()->enqueueMessage('Tabel: '.$query->dump(), 'warning');
				$this->_db->setQuery($query);
				$res = $this->_db->query();
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				return $res;
			}
			return false;
		}
		
		public function updPlayers($players=null)
		{
			if (is_array($players))
			{
				foreach ($players AS $player)
				{
					if ($player->player_id=='NULL')
					{
						// Insert new player
						$insert[] = $this->_db->insertObject('#__bb_players',$player);
					}
					elseif ($player->player_id>0)
					{
						// Update player
						$insert[] = $this->_db->updateObject('#__bb_players',$player,'player_id');
					}
				}
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				return $insert;
			}
			return false;
		}

}

class BloodBowlTableMatches extends JTable
{
        function __construct(&$db) 
        {
                parent::__construct('#__bb_matches', 'match_id', $db);
        }
		
		public function getMatchesByCoach($coach=0, $out=3)
		{
			if ($coach>0)
			{
				$query = $this->_db->getQuery(true);
				$query->select('a.match_id, a.f_tour_id, b.name AS tour_name, a.team1_id, c.name AS team1_name, c.coach AS team1_coach, a.team2_id, d.name AS team2_name, d.coach AS team2_coach, a.team1_score, a.team2_score, a.fame1, a.fame2, a.gate, a.challenge, a.date_played, b.admins, a.comment');
				$query->from('#__bb_matches AS a');
				$query->join('LEFT','#__bb_tourneys as b ON a.f_tour_id = b.tour_id');
				$query->join('LEFT','#__bb_teams AS c ON a.team1_id = c.id');
				$query->join('LEFT','#__bb_teams AS d ON a.team2_id = d.id');
				$query->order('date_played DESC, date_created DESC');
				
				// Create query to get challenges including coach
				$challenges = clone $query;
				$challenges->where('a.challenge=1');
				$challenges->where("( a.team1_id=$coach OR a.team2_id=$coach )");
				$this->_db->setQuery($challenges);
				$matches = $this->_db->loadObjectList();
				
				if ($out>1)
				{
					// Create query with unplayed matches
					$unplayed = clone $query;
					$unplayed->where("((a.team1_id IN (SELECT id FROM #__bb_teams WHERE coach=$coach) ) OR (a.team2_id IN (SELECT id FROM #__bb_teams WHERE coach=$coach) ) )");
					$unplayed->where('a.challenge=0');
					$unplayed->where('a.gate IS NULL');
					$this->_db->setQuery($unplayed);
					$matches = array_merge($matches, $this->_db->loadObjectList());
					
					if ($out>2)
					{
						// Create query with latest 10 played matches
						$query->where("((a.team1_id IN (SELECT id FROM #__bb_teams WHERE coach=$coach) ) OR (a.team2_id IN (SELECT id FROM #__bb_teams WHERE coach=$coach) ) )");
						$query->where('a.gate IS NOT NULL');
						$this->_db->setQuery($query, 0, 10);
						$matches = array_merge($matches, $this->_db->loadObjectList());
					}
				}
					
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				
				return $matches;
			}
			return array();
		}
		
		public function getPlayedMatches($id=0)
		{
			if ($id>0)
			{
				$qA = $this->_db->getQuery(true);
				$qA->select('*')->from('#__bb_matches');
				$qA->where("( team1_id = $id OR team2_id = $id )"); 
				$qA->where('NOT isnull(gate)');
				$qA->order('date_played ASC, date_created ASC');
				$this->_db->setQuery($qA);
				$rows = $this->_db->loadObjectList();
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				return $rows;
			}
			return array();
		}
		
		public function getMatchBasics($id=0)
		{
			if ($id>0)
			{
				$qB = $this->_db->getQuery(true);
				$qB->select('a.*, 0 AS spiral1, 0 AS spiral2, b.name AS team1_name, b.coach AS team1_coach, b.icon AS team1_icon, b.teamvalue AS team1_teamvalue, b.locked AS team1_locked, c.name AS team2_name, c.coach AS team2_coach, c.teamvalue AS team2_teamvalue, c.icon AS team2_icon, c.locked AS team2_locked, d.name AS tour_name, d.admins AS tour_admins, 1 AS disabled, 0 AS editor, 0 AS isadmin, 0 AS iscoach');
				$qB->from('#__bb_matches AS a');
				$qB->join('LEFT','#__bb_teams AS b ON a.team1_id=b.id');
				$qB->join('LEFT','#__bb_teams AS c ON a.team2_id=c.id');
				$qB->join('LEFT','#__bb_tourneys AS d ON a.f_tour_id=d.tour_id');
				$qB->where("a.match_id=$id");
				$this->_db->setQuery($qB, 0, 1);
				$row = $this->_db->loadObject();
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				$row->spiral1 = max(0,bcdiv($row->TV1-1600000, '150000', 0)*10000);
				$row->spiral2 = max(0,bcdiv($row->TV2-1600000, '150000', 0)*10000);
				return $row;
			}
			return array();
		}
		
		public function getMatchLock($ida=0, $idb=0, $matchtime=NULL)
		{
			if ($ida>0 && $idb>0 && !is_null($matchtime))
			{
				$qC = "SELECT COUNT(*) FROM #__bb_matches
					WHERE date_created > '$matchtime' AND
					  ( team1_id IN (". $ida .",". $idb .") OR 
						team2_id IN (". $ida .",". $idb .") )";
				$qC = $this->_db->getQuery(true);
				$qC->select('COUNT(*)');
				$qC->from('#__bb_matches');
				$qC->where("date_created > '$matchtime'");
				$qC->where("( team1_id IN ($ida, $idb) OR team2_id IN ($ida, $idb) )");
				$this->_db->setQuery($qC);
				$row = $this->_db->loadResult();
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				if ($row>0) return TRUE;
				return FALSE;
			}
			return TRUE;
		}
		
		public function buyJourneyman($player_id=0)
		{
			if ($player_id>0)
			{
				$query=$this->_db->getQuery(true);
				$query->select($this->_db->qn(array('a.player_id','b.position', 'a.owned_by_team_id', 'b.cost')));
				$query->from($this->_db->qn('#__bb_players', 'a'));
				$query->join('INNER', $this->_db->qn('#__bb_roster', 'b') .' ON ('. $this->_db->qn('a.roster_id') .'='. $this->_db->qn('b.id') .')');
				$query->where($this->_db->qn('a.player_id') .'='. $this->_db->q($player_id));
				$this->_db->setQuery($query);
				$info=$this->_db->loadObject();
				$buy = new stdClass;
				$buy->player_id = $info->player_id;
				$buy->position = $info->position;
				$buy->date_bought = date("Y-m-d H:i:s");
				$res[] = $this->_db->updateObject('#__bb_players',$buy,'player_id');
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				$query=$this->_db->getQuery(true);
				$query->update($this->_db->qn('#__bb_teams'));
				$query->set($this->_db->qn('Treasury') ."=". $this->_db->qn('Treasury') ."-{$info->cost}");
				$query->where($this->_db->qn('id') ."=". $this->_db->q($info->owned_by_team_id));
				$this->_db->setQuery($query);
				$res[] = $this->_db->query();
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				if (!in_array(FALSE,$res)) return true;
				
			}
			return false;
		}
		
		public function updMatch($matchdata=null, $playerdata=null)
		{
			$insert=array();
			if (!is_null($matchdata))
			{
				//JFactory::getApplication()->enqueueMessage('Vi burde kommer forbi her. ', 'message');
				if (is_null($matchdata->match_id)) //=='NULL')
				{
					$insert[] = $this->_db->insertObject('#__bb_matches',$matchdata,'match_id');
					//JFactory::getApplication()->enqueueMessage('1Hvorfor er vi her? '.print_r($matchdata,true), 'message');
					return $matchdata->match_id;
				}
				else
				{
					$insert[] = $this->_db->updateObject('#__bb_matches',$matchdata,'match_id');
					//JFactory::getApplication()->enqueueMessage('2Hvorfor er vi her? '.print_r($matchdata,true), 'message');
				}
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'error');
				}
			}
			if (!is_null($playerdata))
			{
				$query = $this->_db->getQuery(true);
				$query->select('COUNT(*)');
				$query->from($this->_db->quoteName('#__bb_match_data'));
				$query->where($this->_db->quoteName('f_match_id') ."=". $this->_db->quote($matchdata->match_id));
				$this->_db->setQuery($query);
				$count = $this->_db->loadResult();
				foreach ($playerdata AS $player)
				{
					$query = $this->_db->getQuery(true);
					$values = array();
					foreach ($player AS $key=>$value)
					{
						if ($key=='buy' && $value==true)
						{
							$this->buyJourneyman($player->f_player_id);
							continue;
						}
						else if ($key=='sell' && $value==true)
						{
							$buy = new stdClass;
							$buy->player_id=$player->f_player_id;
							$buy->date_sold=date('Y-m-d H:i:s');
							$this->_db->updateObject('#__bb_players',$buy,'player_id');
							if ($this->_db->getErrorNum()>0)
							{
								JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
							}
							continue;
						}
						if ($value<>0 && empty($value)) $value='NULL';
						if ($key=='inj') $value = $this->_db->q($value);
						//$value = $this->_db->q($value);
						$values[] = $this->_db->quoteName($key) .'='. $value;
					}
					if ($count>0)
					{
						$query->update($this->_db->quoteName('#__bb_match_data'));
						$query->where($this->_db->quoteName('f_match_id') ."=". $this->_db->quote($matchdata->match_id));
						$query->where($this->_db->quoteName('f_player_id') ."=". $this->_db->quote($player->f_player_id));
					}
					else
					{
						$query->insert($this->_db->quoteName('#__bb_match_data'));
					}
					$query->set($values);
					$this->_db->setQuery($query);
					$insert[] = $this->_db->query();
					if ($this->_db->getErrorNum()>0)
					{
						JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
					}
				}
				$query = $this->_db->getQuery(true);
				$query->update($this->_db->quoteName('#__bb_teams'));
				$query->set($this->_db->quoteName('locked').'='.$this->_db->quote('1'));
				$query->where($this->_db->quoteName('id') .'='. $this->_db->quote($matchdata->team1_id));
				$this->_db->setQuery($query);
				$insert[] = $this->_db->query();
				$query = $this->_db->getQuery(true);
				$query->update($this->_db->quoteName('#__bb_teams'));
				$query->set($this->_db->quoteName('locked').'='.$this->_db->quote('1'));
				$query->where($this->_db->quoteName('id') .'='. $this->_db->quote($matchdata->team2_id));
				$this->_db->setQuery($query);
				$insert[] = $this->_db->query();
				//JFactory::getApplication()->enqueueMessage("<pre>". $query->dump() ."</pre>", 'notice');
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
			}
			if (!in_array(FALSE, $insert))
			{
				return TRUE;
			}
			JFactory::getApplication()->enqueueMessage("<pre>". print_r($insert,true) ."</pre>", 'notice');
			return FALSE;
		}
		
		public function updChallenge($data=null)
		{
			if (!is_null($data) && is_object($data))
			{
				if (is_null($data->match_id)) //=='NULL')
				{
					$res = $this->_db->insertObject('#__bb_matches',$data,'match_id');
					//JFactory::getApplication()->enqueueMessage('Hvorfor er vi her? '.print_r($data,true), 'message');
				}
				else
				{
					$res = $this->_db->updateObject('#__bb_matches',$data,'match_id');
				}
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				return $res;
			}
			return FALSE;
		}
		
		public function delMatch($id=0)
		{
			if ($id>0)
			{
				$query = $this->_db->getQuery(true);
				$query->delete('#__bb_matches');
				$query->where($this->_db->quoteName('match_id') . '='. $id);
				$query->LIMIT('1');
				$this->_db->setQuery($query);
				$res[] = $this->_db->query();
				
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				$query = $this->_db->getQuery(true);
				$query->delete('#__bb_match_data');
				$query->where($this->_db->quoteName('f_match_id') . '='. $id);
				$this->_db->setQuery($query);
				$res[] = $this->_db->query();
				if ($this->_db->getErrorNum()>0)
				{
					JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
				}
				
				if (in_array(FALSE, $res))
				{
					JFactory::getApplication()->enqueueMessage('Database error. ', 'notice');
					return FALSE;
				}
				return TRUE;
			}
			return FALSE;
		}
		
}

class BloodBowlTableSkills extends JTable
{
        function __construct(&$db) 
        {
                parent::__construct('#__bb_skills', 'name', $db);
        }
		
		public function getSkillCat($types="all")
		{
			$query = $this->_db->getQuery(true);
			$query->select('*');
			$query->from($this->_db->quoteName('#__bb_skills'));
			if (strlen($types)==1)
			{
				$query->where($this->_db->quoteName('type') .'='. $this->_db->quote($types));
			}
			if (is_array($types))
			{
				foreach ($types AS &$type)
				{
					$type = $this->_db->quote($type);
				}
				unset($type);
				$types=implode(",",$types);
				$query->where($this->_db->quoteName('type') ."IN ($types)");
			}
			$this->_db->setQuery($query);
			if ($this->_db->getErrorNum()>0)
			{
				JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
			}
			
			return $this->_db->loadObjectList();
		}
		

}

class BloodBowlTableStats extends JTable
{
        function __construct(&$db) 
        {
                parent::__construct('#__bb_skills', 'name', $db);
        }
		
		public function getSkillCat($types="all")
		{
			$query = $this->_db->getQuery(true);
			$query->select('*');
			$query->from($this->_db->quoteName('#__bb_skills'));
			if (strlen($types)==1)
			{
				$query->where($this->_db->quoteName('type') .'='. $this->_db->quote($types));
			}
			if (is_array($types))
			{
				foreach ($types AS &$type)
				{
					$type = $this->_db->quote($type);
				}
				unset($type);
				$types=implode(",",$types);
				$query->where($this->_db->quoteName('type') ."IN ($types)");
			}
			$this->_db->setQuery($query);
			if ($this->_db->getErrorNum()>0)
			{
				JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
			}
			
			return $this->_db->loadObjectList();
		}
		

}

?>