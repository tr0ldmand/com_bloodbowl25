<p><table>
	<tr>
		<th><?php echo JText::_('COM_BLOODBOWL_QFINAL'); ?></th>
		<th class="arrow"> </th>
		<th><?php echo JText::_('COM_BLOODBOWL_SEMIFINAL'); ?></th>
		<th class="arrow"> </th>
		<th><?php echo JText::_('COM_BLOODBOWL_FINAL'); ?></th>
		<th> </th>
	</tr>
	<tr>
		<td class="bgA" style="border-bottom: 1px solid;"><?php echo $this->teams[$bracket[1][0]['c1']]->team_name; ?></td>
		<td class="bgA arrow" rowspan="2">></td>
		<td class="bgA" style="border-bottom: 1px solid;" rowspan="2"><?php echo $this->teams[$bracket[2][0]['c1']]->team_name; ?></td>
		<td class="bgA arrow" rowspan="4">></td>
		<td class="bgA" style="border-bottom: 1px solid;" rowspan="4"><?php echo "VS1-2"; ?></td>
		<td> </td>
	</tr>
	<tr>
		<td class="bgA"><?php echo $this->teams[$bracket[1][0]['c2']]->team_name; ?></td>
		<td> </td>
	</tr>
	<tr>
		<td class="bgB" style="border-bottom: 1px solid;"><?php echo $this->teams[$bracket[1][1]['c1']]->team_name; ?></td>
		<td class="bgB arrow" rowspan="2">></td>
		<td class="bgA" rowspan="2"><?php echo $this->teams[$bracket[2][0]['c2']]->team_name; ?></td>
		<td> </td>
	</tr>
	<tr>
		<td class="bgB"><?php echo $this->teams[$bracket[1][1]['c2']]->team_name; ?></td>
		<td> </td>
	</tr>
	<tr>
		<td class="bgA" style="border-bottom: 1px solid;"><?php echo $this->teams[$bracket[1][2]['c1']]->team_name; ?></td>
		<td class="bgA arrow" rowspan="2">></td>
		<td class="bgB" style="border-bottom: 1px solid;" rowspan="2"><?php echo $this->teams[$bracket[2][1]['c1']]->team_name; ?></td>
		<td class="bgB arrow" rowspan="4">></td>
		<td class="bgA" rowspan="4"><?php echo "VS3-4"; ?></td>
		<td> </td>
	</tr>
	<tr>
		<td class="bgA"><?php echo $this->teams[$bracket[1][2]['c2']]->team_name; ?></td>
		<td> </td>
	</tr>
	<tr>
		<td class="bgB" style="border-bottom: 1px solid;"><?php echo $this->teams[$bracket[1][3]['c1']]->team_name; ?></td>
		<td class="bgB arrow" rowspan="2">></td>
		<td class="bgB" rowspan="2"><?php echo $this->teams[$bracket[2][1]['c2']]->team_name; ?></td>
		<td> </td>
	</tr>
	<tr>
		<td class="bgB"><?php echo $this->teams[$bracket[1][3]['c2']]->team_name;; ?></td>
		<td> </td>
	</tr>
</table></p>