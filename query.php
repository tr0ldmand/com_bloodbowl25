<?
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
		return $this->_db->loadObjectList();
	}
	return false;
}


$query->update($this->_db->quoteName('#__bb_match_data'));
$query->set($values);
$query->where($this->_db->quoteName('f_player_id') ."=". $this->_db->quote($player->f_player_id));
$this->_db->setQuery($query);

		if ($this->_db->getErrorNum()>0)
		{
			JFactory::getApplication()->enqueueMessage('Database error. '.$this->_db->getErrorMsg(), 'warning');
		}

		
$insert[] = $this->_db->insertObject('#__bb_matches',$matchdata,'match_id');
$matchdata = new stdClass;
$matchdata->match_id = $match->match_id;
$matchdata->c_rating_1 = $this->coaches[$cid1]->rating;
$matchdata->c_rating_1 = $this->coaches[$cid2]->rating;
$matchdata->c_rat_change = $change;
$insert[] = $this->_db->updateObject('#__bb_matches',$matchdata,'match_id');


$q = "SELECT a.match_id, a.f_tour_id, b.name AS tour_name, a.team1_id, c.name AS team1_name, c.coach AS team1_coach, a.team2_id, d.name AS team2_name, d.coach AS team2_coach, a.team1_score, a.team2_score, a.fame1, a.fame2, a.gate, a.challenge, a.date_played, b.admins, a.comment
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
				
				$where = "( a.team1_id = ". $id ." OR a.team2_id = ". $id ." )";

		
		
		
		
		
		
		
JFactory::getApplication()->enqueueMessage('Tabel: '.$query->dump(), 'warning');		
		
		
		
		SELECT GROUP_CONCAT(d.tour_id, ', ') AS tour_ids, GROUP_CONCAT(d.name, ', ') AS tour_names, d.finished, a.*
FROM gd8h_bb_teams AS a
LEFT JOIN (SELECT b.tour_id, c.name, c.finished, b.team_id FROM gd8h_bb_teams_in_tourney AS b INNER JOIN gd8h_bb_tourneys AS c ON b.tour_id=c.tour_id WHERE c.finished='0000-00-00') AS d ON a.id=d.team_id
WHERE `a`.`retired` < 2 AND `a`.`coach`>0 AND `a`.`name` != ''
GROUP BY a.id
ORDER BY `tour_ids`  DESC
		
		
		?>