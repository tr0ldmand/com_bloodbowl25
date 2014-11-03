<?php
// No direct access to this file
defined('_JEXEC') or die("Router error");
jimport('joomla.application.categories');

function BloodBowlBuildRoute(&$query)
{
		$segments = array();
		if(isset($query['view']))
		{
                $segments[] = $query['view'];
                unset( $query['view'] );
		}
		if (isset($query['show']))
		{
				$segments[] = $query['show'];
				unset( $query['show'] );
		}
		if (isset($query['order']))
		{
				$segments[] = $query['order'];
				unset( $query['order'] );
		}
		if (isset($query['races']))
		{
				$segments[] = $query['races'];
				unset( $query['races'] );
		}
		if (isset($query['coach']))
		{
				$segments[] = $query['coach'];
				unset( $query['coach'] );
		}
		if (isset($query['match']))
		{
				$segments[] = $query['match'];
				unset( $query['match'] );
		}
		if (isset($query['standA']))
		{
				$segments[] = $query['standA'];
				unset( $query['standA'] );
		}
		if (isset($query['standB']))
		{
				$segments[] = $query['standB'];
				unset( $query['standB'] );
		}
		if (isset($query['standC']))
		{
				$segments[] = $query['standC'];
				unset( $query['standC'] );
		}
		if (isset($query['toggleedit']))
		{
				$segments[] = $query['toggleedit'];
				unset( $query['toggleedit'] );
		}
		if (isset($query['pop']))
		{
				$segments[] = $query['pop'];
				unset( $query['pop'] );
		}

		return $segments;
}


function BloodBowlParseRoute($segments)
{
       $vars = array();
	   $count = count($segments);
       switch($segments[0])
       {
  			case 'stats':
					$vars['view'] = 'stats';
					$vars['show'] = $segments[1];
					$vars['order'] = $segments[2];
					$vars['races'] = $segments[3];
					break;
			case 'teamslist':
					$vars['view'] = 'teamslist';
					$vars['show'] = $segments[1];
					$vars['order'] = $segments[2];
					$vars['races'] = $segments[3];
					break;
			case 'tourney':
					$vars['view'] = 'tourney';
					$vars['show'] = $segments[1];
					$vars['order'] = $segments[2];
					break;
			case 'tourneydetail':
					$vars['view'] = 'tourneydetail';
					$vars['show'] = $segments[1];
					$vars['order'] = $segments[2];
					$vars['races'] = $segments[3];
					$vars['coach'] = $segments[4];
					$vars['match'] = $segments[5];
					$vars['standA'] = $segments[6];
					$vars['standB'] = $segments[7];
					$vars['standC'] = $segments[8];
					break;
			case 'tourneydetail2':
					$vars['view'] = 'tourneydetail';
					$vars['show'] = $segments[1];
					$vars['match'] = $segments[2];
					$vars['standA'] = $segments[3];
					$vars['standB'] = $segments[4];
					$vars['standC'] = $segments[5];
					break;
			case 'edittourney':
					$vars['view'] = 'tourneydetail';
					$vars['show'] = $segments[1];
					$vars['order'] = $segments[2];
					$vars['coach'] = $segments[3];
					$vars['toggleedit'] = $segments[4];
					break;
			case 'teamdetail':
					$vars['view'] = 'teamdetail';
					$vars['show'] = $segments[1];
					$vars['toggleedit'] = $segments[2];
					$vars['pop'] = $segments[3];
					break;
			case 'teamdetail2':
					$vars['view'] = 'teamdetail';
					$vars['show'] = $segments[1];
					$vars['match'] = $segments[2];
					break;
			case 'matchdetail':
					$vars['view'] = 'matchdetail';
					$vars['show'] = $segments[1];
					$vars['toggleedit'] = $segments[2];
					break;
			case 'matchdetailold':
					$vars['view'] = 'matchdetailold';
					$vars['show'] = $segments[1];
					$vars['toggleedit'] = $segments[2];
					break;
			case 'bloodbowl':
			default:
                    $vars['view'] = 'bloodbowl';
                    $vars['show'] = $segments[1];
                    break;
       }
       return $vars;
}

?>