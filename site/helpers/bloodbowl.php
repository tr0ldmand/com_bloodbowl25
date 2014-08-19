<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * HelloWorld component helper.
 */
abstract class BloodBowlHelper
{
        /**
         * Returns html to show top navigation bar.
         */
        public static function addMenubar() 
        {
			$user =& JFactory::getUser();
			
			$html .= '<div class="bbtopmenu">';
			$html .=  JHtml::_('link', JRoute::_('index.php?view=teamslist'), JText::_('COM_BLOODBOWL_TEAMSLIST'), null);
			$html .=  ' | ';
			$html .=  JHtml::_('link', JRoute::_('index.php?view=tourney'), JText::_('COM_BLOODBOWL_TOURNEYLIST'), null);
			if (!$user->guest)
			{
				$html .=  ' | ';
				$html .=  JHtml::_('link', JRoute::_('index.php'), JText::_('COM_BLOODBOWL_MY_TEAMS'), null);
			}
			$html .=  ' | ';
			$html .=  JHtml::_('link', JRoute::_('index.php?view=tourneydetail&show=33'), 'Rating 2014', null);
			$html .= '</div>';
			
			return $html;
		}
		
		/**
		 *  Expecting array $matches and int $start
		 */
		
		public static function showMatchHistory($matches=array(), $start=0)
		{
			$jinput =& JFactory::getApplication()->input;
			$show = $jinput->get('show','29','int');
			$view = $jinput->get('view','bloodbowl','str');
			if ($view=='tourneydetail') $view .= '2';
			$url = 'index.php?view='. $view .'&show='. $show;
			$nummatches = count($matches);
			$matchpages = ceil($nummatches/20);
			if ($start>$nummatches) $start=0;
			$limitmatches = array_slice($matches, $start*20, 20);
			
			$html .= '<p><h2>';
			$html .= JText::_('COM_BLOODBOWL_MATCH_HISTORY');
			$html .= '</h2><table width="100%"><tr>';
				$html .= '<th>'. JText::_('COM_BLOODBOWL_TOUR_NAME') .'</th>';
				$html .= '<th>'. JText::_('COM_BLOODBOWL_HOME') .'</th>';
				$html .= '<th>'. JText::_('COM_BLOODBOWL_AWAY') .'</th>';
				$html .= '<th style="text-align:center;" colspan="3">'. JText::_('COM_BLOODBOWL_SCORE') .'</th>';
				$html .= '<th></th>';
				$html .= '<th style="text-align:center;">'. JText::_('COM_BLOODBOWL_DATE_PLAYED') .'</th>';
				$html .= '<th></th>';
			$html .='</tr>';
			foreach ($limitmatches as $match)
			{
				//alternate bgcolor
				if ($bgcolor=='WhiteSmoke') {
						$bgcolor='#FEE6BC';
				} else {
					$bgcolor   = 'WhiteSmoke';
				}
				$stylecenter = 'text-align:center;';
				$styleleft = 'text-align:left;';
				$html .= "<tr style=\"background-color: $bgcolor;\">";
				 $html .= "<td style=\"$styleleft\">". JHtml::_('link', JRoute::_('index.php?view=tourneydetail&show='. $match->f_tour_id ), $match->tour_name, null) ."</td>";
				 if ( $match->challenge==1 )
				 {
					$html .= "<td style=\"$styleleft\">". JFactory::getUser($match->team1_id)->name ."</a></td>";
					$html .= "<td style=\"$styleleft\">". JFactory::getUser($match->team2_id)->name ."</a></td>";
				 }
				 else 
				 {
					$html .= "<td style=\"$styleleft\">". JHtml::_('link', JRoute::_('index.php?view=teamdetail&show='. $match->team1_id ), $match->team1_name, null) ."</td>";
					$html .= "<td style=\"$styleleft\">". JHtml::_('link', JRoute::_('index.php?view=teamdetail&show='. $match->team2_id ), $match->team2_name, null) ."</td>";
				 }
				 $html .= "<td style=\"$stylecenter\">".$match->team1_score."</td>";
				 $html .= "<td style=\"$stylecenter\">-</td>";
				 $html .= "<td style=\"$stylecenter\">".$match->team2_score."</td>";
				 $html .= "<td style=\"$stylecenter\">".(strlen($match->comment)>30 ? '<img src="media/com_bloodbowl/images/icons/discuss.gif" title="'. JText::_('COM_BLOODBOWL_MATCH_SUMMARY') .'"/>':'&nbsp;')."</td>";			
				 $html .= "<td style=\"$stylecenter\">".(is_null($match->date_played) ? '' : date("d-m-Y",strtotime($match->date_played))) ."</td>";
				 $html .= "<td style=\"$stylecenter\">". JHtml::_('link', JRoute::_('index.php?view=matchdetail&show='. $match->match_id), JText::_('COM_BLOODBOWL_MATCH_VIEW'), null) ."</td>";
				$html .= '</tr>';
			}
			$html .= '</table>';
			
			if ($matchpages>1)
			{
				$html .= JText::_('COM_BLOODBOWL_GOTO_PAGE');
				for ($i=0; $i<$matchpages; $i++)
				{
					if ($i!=$start)
					{
						$html .= '&nbsp;';
						$html .= JHtml::_('link', JRoute::_($url .'&match='. $i ), $i+1, null);
					}
					else
					{
						$html .= '&nbsp;<u>'. ($i+1) .'</u>';
					}
				}
				$html .= ". ";
			}
			$html .= JText::sprintf('COM_BLOODBOWL_MATCHES_IN_TOTAL',$nummatches);
			$html .= '.';
			
			$html .= '</p>';
			
			return $html;
		}
		
}