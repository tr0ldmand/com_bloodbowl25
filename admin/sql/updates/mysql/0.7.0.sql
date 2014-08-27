
--
-- Struktur-dump for tabellen `#__bb_matches`
--

CREATE TABLE IF NOT EXISTS `#__bb_matches` (
  `match_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `round` tinyint(3) unsigned DEFAULT NULL,
  `f_tour_id` mediumint(8) unsigned DEFAULT NULL,
  `challenge` tinyint(1) DEFAULT NULL,
  `submitter_id` mediumint(8) unsigned DEFAULT NULL,
  `stadium` mediumint(8) unsigned DEFAULT NULL,
  `gate` mediumint(8) unsigned DEFAULT NULL,
  `ffactor1` tinyint(4) DEFAULT NULL,
  `ffactor2` tinyint(4) DEFAULT NULL,
  `fame1` tinyint(4) DEFAULT NULL,
  `fame2` tinyint(4) DEFAULT NULL,
  `income1` mediumint(9) DEFAULT NULL,
  `income2` mediumint(9) DEFAULT NULL,
  `team1_id` mediumint(8) unsigned DEFAULT NULL,
  `team2_id` mediumint(8) unsigned DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_played` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `team1_score` tinyint(2) DEFAULT NULL,
  `team2_score` tinyint(2) DEFAULT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_bin,
  `c_rating_1` smallint(6) NOT NULL DEFAULT '0',
  `c_rating_2` smallint(6) NOT NULL DEFAULT '0',
  `c_rat_change` smallint(6) NOT NULL DEFAULT '0',
  `TV1` mediumint(9) NOT NULL DEFAULT '0',
  `TV2` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`match_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1745 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `#__bb_match_data`
--

CREATE TABLE IF NOT EXISTS `#__bb_match_data` (
  `f_coach_id` mediumint(8) unsigned DEFAULT NULL,
  `f_team_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `f_player_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `f_match_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `mvp` tinyint(3) unsigned DEFAULT '0',
  `cp` tinyint(3) unsigned DEFAULT '0',
  `td` tinyint(3) unsigned DEFAULT '0',
  `intcpt` tinyint(3) unsigned DEFAULT '0',
  `bh` tinyint(3) unsigned DEFAULT '0',
  `si` tinyint(3) unsigned DEFAULT '0',
  `ki` tinyint(3) unsigned DEFAULT '0',
  `inj` tinytext CHARACTER SET utf8 COLLATE utf8_bin,
  PRIMARY KEY (`f_team_id`,`f_player_id`,`f_match_id`),
  KEY `f_match_id` (`f_match_id`),
  KEY `f_coach_id` (`f_coach_id`),
  KEY `f_team_id` (`f_team_id`),
  KEY `f_player_id` (`f_player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `#__bb_players`
--

CREATE TABLE IF NOT EXISTS `#__bb_players` (
  `player_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `roster_id` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_danish_ci DEFAULT NULL,
  `owned_by_team_id` mediumint(8) unsigned DEFAULT NULL,
  `nr` mediumint(8) unsigned DEFAULT NULL,
  `position` varchar(50) CHARACTER SET latin1 COLLATE latin1_danish_ci DEFAULT NULL,
  `date_bought` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_sold` datetime DEFAULT NULL,
  `ach_skills` text CHARACTER SET latin1 COLLATE latin1_danish_ci,
  PRIMARY KEY (`player_id`),
  KEY `owned_by_team_id` (`owned_by_team_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9325 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `#__bb_roster`
--

CREATE TABLE IF NOT EXISTS `#__bb_roster` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `race` varchar(30) CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `max` tinyint(4) NOT NULL DEFAULT '0',
  `position` tinytext CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `move` tinyint(4) NOT NULL DEFAULT '0',
  `strength` tinyint(4) NOT NULL DEFAULT '0',
  `agility` tinyint(4) NOT NULL DEFAULT '0',
  `armor` tinyint(4) NOT NULL DEFAULT '0',
  `skills` tinytext CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `normal` tinytext CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `double` tinytext CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `cost` mediumint(9) NOT NULL DEFAULT '0',
  `icon` tinytext CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `race` (`race`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=108 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `#__bb_skills`
--

CREATE TABLE IF NOT EXISTS `#__bb_skills` (
  `name` varchar(20) CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `type` char(1) CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL DEFAULT '',
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `#__bb_teams`
--

CREATE TABLE IF NOT EXISTS `#__bb_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `coach` int(11) NOT NULL DEFAULT '0',
  `race` tinytext CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `RR` smallint(6) NOT NULL DEFAULT '0',
  `FF` smallint(6) NOT NULL DEFAULT '0',
  `A_Coach` smallint(6) NOT NULL DEFAULT '0',
  `CheerLeader` smallint(6) NOT NULL DEFAULT '0',
  `Apoth` int(11) NOT NULL DEFAULT '0',
  `Treasury` bigint(20) NOT NULL DEFAULT '0',
  `icon` tinytext CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `RRcost` int(11) NOT NULL DEFAULT '0',
  `Startval` bigint(20) NOT NULL DEFAULT '0',
  `locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `retired` tinyint(4) NOT NULL DEFAULT '0',
  `miscvalue` mediumint(9) NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `teamvalue` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=802 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `#__bb_teams_in_tourney`
--

CREATE TABLE IF NOT EXISTS `#__bb_teams_in_tourney` (
  `tour_id` smallint(4) unsigned NOT NULL,
  `team_id` int(11) unsigned NOT NULL,
  `branch` smallint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`tour_id`,`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `#__bb_tourneys`
--

CREATE TABLE IF NOT EXISTS `#__bb_tourneys` (
  `tour_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` mediumtext CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `type` tinytext CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `created` date NOT NULL DEFAULT '0000-00-00',
  `finished` date NOT NULL DEFAULT '0000-00-00',
  `admins` varchar(50) CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `teams` text CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `other_tour` varchar(60) CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `rules` text CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `news` text CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `newsdate` date NOT NULL,
  PRIMARY KEY (`tour_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `#__bb_tourneys_in_tourneys`
--

CREATE TABLE IF NOT EXISTS `#__bb_tourneys_in_tourneys` (
  `parent` int(11) NOT NULL,
  `child` int(11) NOT NULL,
  PRIMARY KEY (`parent`,`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


--
-- Data dump for tabellen `#__bb_roster`
--

INSERT IGNORE INTO `#__bb_roster` (`id`, `race`, `max`, `position`, `move`, `strength`, `agility`, `armor`, `skills`, `normal`, `double`, `cost`, `icon`) VALUES
(56, 'Norse', 16, 'Lineman', 6, 3, 3, 7, 'Block', 'G', 'A S P', 50000, 'nlineman1an'),
(55, 'Norse', 2, 'Berserkers', 6, 3, 3, 7, 'Block, Frenzy, Jump Up', 'G S', 'A P', 90000, 'ncatcher1an'),
(54, 'Necromantic', 16, 'Zombie', 4, 3, 2, 8, 'Regeneration', 'G', 'A S P', 40000, 'uzombie1'),
(53, 'Necromantic', 2, 'Wight', 6, 3, 3, 8, 'Block, Regeneration', 'G S', 'A P', 90000, 'uwight1an'),
(52, 'Necromantic', 2, 'Werewolf', 8, 3, 3, 8, 'Frenzy, Claws, Regeneration', 'G A', 'S P', 120000, 'nwerewolf1an'),
(51, 'Necromantic', 2, 'Ghoul', 7, 3, 3, 7, 'Dodge', 'G A', 'S P', 70000, 'ughoul1an'),
(50, 'Necromantic', 2, 'Flesh Golem', 4, 4, 2, 9, 'Standfirm, Thick Skull, Regeneration', 'G S', 'A P', 110000, 'ngolem1an'),
(49, 'Lizardmen', 16, 'Skink', 8, 2, 3, 7, 'Dodge, Stunty', 'A', 'G S P', 60000, 'lmskink1an'),
(48, 'Lizardmen', 6, 'Saurus', 6, 4, 1, 9, '', 'G S', 'A P', 80000, 'lmsaurus1an'),
(47, 'Lizardmen', 1, 'Kroxigor', 6, 5, 1, 9, 'Loner, Mighty Blow, Thick Skull, Prehensile Tail, Bonehead', 'S', 'G A P', 140000, 'kroxigor1an'),
(45, 'Khemri', 16, 'Skeleton', 5, 3, 2, 7, 'Regeneration, Thick Skull', 'G', 'A S P', 40000, 'kmskeleton1an'),
(46, 'Khemri', 2, 'Thro-Ra', 6, 3, 2, 7, 'Sure Hands, Pass, Regeneration', 'G P', 'A S', 70000, 'kmthrower1an'),
(44, 'Khemri', 4, 'Tomb Guardian', 4, 5, 1, 9, 'Decay, Regeneration', 'S', 'G A P', 100000, 'kmmummy1an'),
(43, 'Khemri', 2, 'Blitz-Ra', 6, 3, 2, 8, 'Block, Regeneration', 'G S', 'A P', 90000, 'kmblitzer1an'),
(42, 'Human', 2, 'Thrower', 6, 3, 3, 8, 'Pass, Sure Hands', 'G P ', 'A S', 70000, 'hthrower1an'),
(41, 'Human', 1, 'Ogre', 5, 5, 2, 9, 'Loner, Mighty Blow, Thick Skull, Bonehead, Throw Teammate', 'S', 'G A P', 140000, 'ogre4an'),
(40, 'Human', 16, 'Lineman', 6, 3, 3, 8, '', 'G', 'A S P', 50000, 'hlineman1an'),
(39, 'Human', 4, 'Catcher', 8, 2, 3, 7, 'Catch, Dodge', 'G A', 'S P', 70000, 'hcatcher1an'),
(38, 'Human', 4, 'Blitzer', 7, 3, 3, 8, 'Block', 'G S', 'A P', 90000, 'hblitzer1an'),
(37, 'High Elf', 2, 'Thrower', 6, 3, 4, 8, 'Pass, Safe Throw', 'G A P', 'S', 90000, 'hethrower1an'),
(32, 'Halfling', 16, 'Halfling', 5, 2, 3, 6, 'Dodge, Stunty, Right Stuff', 'A', 'G S P', 30000, 'halfling3an'),
(33, 'Halfling', 2, 'Treeman', 2, 6, 1, 10, 'Mighty Blow, Stand Firm, Strong Arm, Thick Skull, Take Root, Throw Teammate', 'S', 'G A P', 120000, 'treeman1an'),
(36, 'High Elf', 16, 'Lineelf', 6, 3, 4, 8, '', 'G A', 'S P', 70000, 'helineman1an'),
(35, 'High Elf', 4, 'Catcher', 8, 3, 4, 7, 'Catch', 'G A', 'S P', 90000, 'hecatcher1an'),
(34, 'High Elf', 2, 'Blitzer', 7, 3, 4, 8, 'Block', 'G A', 'S P', 100000, 'heblitzer1an'),
(31, 'Goblin', 2, 'Troll', 4, 5, 1, 9, 'Loner, Mighty Blow, Regeneration, Really Stupid, Always Hungry, Throw Teammate', 'S', 'G A P', 110000, 'troll1an'),
(30, 'Goblin', 1, 'Pogoer', 7, 2, 3, 7, 'Dodge, Leap, Stunty, Very Long Legs', 'A', 'G S P', 70000, 'goblin5an'),
(29, 'Goblin', 1, 'Looney', 6, 2, 3, 7, 'Chainsaw, Secret Weapon, Stunty', 'A', 'G S P', 40000, 'goblin2an'),
(28, 'Goblin', 16, 'Goblin', 6, 2, 3, 7, 'Dodge, Stunty, Right Stuff', 'A', 'G S P', 40000, 'goblin4an'),
(27, 'Goblin', 1, 'Fanatic', 3, 7, 3, 7, 'Ball & Chain, No Hands, Secret Weapon, Stunty', 'S', 'G A P', 70000, 'goball1an'),
(26, 'Goblin', 1, 'Bombardier', 6, 2, 3, 7, 'Bombardier, Dodge, Secret Weapon, Stunty', 'A', 'G S P', 40000, 'goblin3an'),
(25, 'Elf', 2, 'Thrower', 6, 3, 4, 7, 'Pass', 'G A P', 'S', 70000, 'wethrower1an'),
(23, 'Elf', 4, 'Catcher', 8, 3, 4, 7, 'Catch, Nerves of Steel', 'G A', 'S P', 100000, 'wecatcher1an'),
(24, 'Elf', 16, 'Lineelf', 6, 3, 4, 7, '', 'G A', 'S P', 60000, 'welineman1an'),
(22, 'Elf', 2, 'Blitzer', 7, 3, 4, 8, 'Block, Sidestep', 'G A', 'S P', 110000, 'weblitzer1an'),
(19, 'Dwarf', 1, 'Deathroller', 4, 7, 1, 10, 'Loner, Break Tackle, Dirty Player, Juggernaut, Mighty Blow, No Hands, Secret Weapon, Stand Firm', 'S', 'G A P', 160000, 'ddeathroller1an'),
(20, 'Dwarf', 2, 'Runner', 6, 3, 3, 8, 'Sure Hands, Thick Skull', 'G P ', 'A S', 80000, 'drunner1an'),
(21, 'Dwarf', 2, 'Troll Slayer', 5, 3, 2, 8, 'Block, Dauntless, Frenzy, Thick Skull', 'G S', 'A P', 90000, 'dslayer1an'),
(17, 'Dwarf', 2, 'Blitzer', 5, 3, 3, 9, 'Block, Thick Skull', 'G S', 'A P', 80000, 'dblitzer1an'),
(18, 'Dwarf', 16, 'Blocker', 4, 3, 2, 9, 'Block, Tackle, Thick Skull', 'G S', 'A P', 70000, 'dlongbeard1an'),
(16, 'Dark Elf', 2, 'Witch Elf', 7, 3, 4, 7, 'Frenzy, Dodge, Jump Up', 'G A', 'S P', 110000, 'dewitchelf1an'),
(14, 'Dark Elf', 16, 'Lineelf', 6, 3, 4, 8, '', 'G A', 'S P', 70000, 'delineman1an'),
(15, 'Dark Elf', 2, 'Runner', 7, 3, 4, 7, 'Dump Off', 'G A P', 'S', 80000, 'deblitzer1'),
(13, 'Dark Elf', 4, 'Blitzer', 7, 3, 4, 8, 'Block', 'G A', 'S P', 100000, 'deblitzer1an'),
(12, 'Dark Elf', 2, 'Assassin', 6, 3, 4, 7, 'Shadowing, Stab', 'G A', 'S P', 90000, 'delineman1'),
(11, 'Chaos Dwarf', 1, 'Minotaur', 5, 5, 2, 8, 'Loner, Frenzy, Horns, Mighty Blow, Thick Skull, Wild Animal', 'S', 'G A P M', 150000, 'minotaur2an'),
(10, 'Chaos Dwarf', 16, 'Hobgoblin', 6, 3, 3, 7, '', 'G', 'A S P', 40000, 'cdhobgoblin1an'),
(9, 'Chaos Dwarf', 6, 'Chaos Dwarf Blocker', 4, 3, 2, 9, 'Block, Tackle, Thick Skull', 'G S', 'A P M', 70000, 'cddwarf1an'),
(8, 'Chaos Dwarf', 2, 'Bull Centaur', 6, 4, 2, 9, 'Sprint, Sure Feet, Thick Skull', 'G S', 'A P', 130000, 'centaur1an'),
(6, 'Chaos', 4, 'Chaos Warrior', 5, 4, 3, 9, '', 'G S M', 'A P', 100000, 'cwarrior4an'),
(7, 'Chaos', 1, 'Minotaur', 5, 5, 2, 8, 'Loner, Frenzy, Horns, Mighty Blow, Thick Skull, Wild Animal', 'S M ', 'G A P', 150000, 'minotaur2an'),
(4, 'Amazon', 2, 'Thrower', 6, 3, 3, 7, 'Dodge, Pass', 'G P ', 'A S', 70000, 'amthrower1an'),
(5, 'Chaos', 16, 'Beastman', 6, 3, 3, 8, 'Horns', 'G S M', 'A P', 60000, 'cbeastman1an'),
(3, 'Amazon', 16, 'Linewoman', 6, 3, 3, 7, 'Dodge', 'G', 'A S P', 50000, 'amlineman1an'),
(1, 'Amazon', 4, 'Blitzer', 6, 3, 3, 7, 'Dodge, Block', 'G S', 'A P', 90000, 'amblitzer1an'),
(2, 'Amazon', 2, 'Catcher', 6, 3, 3, 7, 'Dodge, Catch', 'G A', 'S P', 70000, 'amcatcher1an'),
(57, 'Norse', 2, 'Catcher', 7, 3, 3, 7, 'Block, Dauntless', 'G A', 'S P', 90000, 'ncatcher1an'),
(58, 'Norse', 1, 'Yehtee', 5, 5, 1, 8, 'Loner, Claws, Disturbing Presence, Frenzy, Wild Animal', 'S', 'G A P', 140000, 'troll1an'),
(59, 'Norse', 2, 'Thrower', 6, 3, 3, 7, 'Block, Pass', 'G P ', 'A S', 70000, 'nthrower1an'),
(60, 'Norse', 2, 'Norse Werewolf', 6, 4, 2, 8, 'Frenzy', 'G S', 'A P', 110000, 'nlineman2an'),
(61, 'Nurgle', 1, 'Beast of Nurgle', 4, 5, 1, 9, 'Loner, Disturbing Presence, Foul Appearance, Mighty Blow, Nurgle''s Rot, Really Stupid, Regeneration, Tentacles', 'S', 'G A P M', 140000, 'troll2an'),
(62, 'Nurgle', 4, 'Nurgle Warrior', 4, 4, 2, 9, 'Disturbing Presence, Foul Appearance, Nurgle''s Rot, Regeneration', 'G S M', 'A P', 110000, 'troll2an'),
(63, 'Nurgle', 4, 'Pestigor', 6, 3, 3, 8, 'Horns, Nurgle''s Rot, Regeneration', 'G S M', 'A P', 80000, 'troll2an'),
(64, 'Nurgle', 16, 'Rotter', 5, 3, 3, 8, 'Decay, Nurgle''s Rot', 'G M', 'A S P', 40000, 'troll2an'),
(65, 'Ogre', 6, 'Ogre', 5, 5, 2, 9, 'Mighty Blow, Thick Skull, Bonehead, Throw Teammate', 'S', 'G A P', 140000, 'ogre4an'),
(66, 'Ogre', 16, 'Snotling', 5, 1, 3, 5, 'Dodge, Right Stuff, Side Step, Stunty, Titchy', 'A', 'G S P', 20000, 'goblin1an'),
(67, 'Orc', 4, 'Black Orc Blocker', 4, 4, 2, 9, '', 'G S', 'A P', 80000, 'oblackorc1an'),
(68, 'Orc', 4, 'Blitzer', 6, 3, 3, 9, 'Block', 'G S', 'A P', 80000, 'oblitzer1an'),
(69, 'Orc', 4, 'Goblin', 6, 2, 3, 7, 'Dodge, Stunty, Right Stuff', 'A', 'G S P', 40000, 'goblin1an'),
(70, 'Orc', 16, 'Lineorc', 5, 3, 3, 9, '', 'G', 'A S P', 50000, 'olineman1an'),
(71, 'Orc', 2, 'Thrower', 5, 3, 3, 8, 'Pass, Sure Hands', 'G P ', 'A S', 70000, 'othrower1an'),
(72, 'Orc', 1, 'Troll', 4, 5, 1, 9, 'Loner, Mighty Blow, Regeneration, Really Stupid, Always Hungry, Throw Teammate', 'S', 'G A P', 110000, 'troll1an'),
(73, 'Skaven', 2, 'Blitzer', 7, 3, 3, 8, 'Block', 'G S', 'A P M', 90000, 'skstorm1an'),
(74, 'Skaven', 4, 'Gutter Runner', 9, 2, 4, 7, 'Dodge', 'G A', 'S P M', 80000, 'skrunner1an'),
(75, 'Skaven', 16, 'Linerat', 7, 3, 3, 7, '', 'G', 'A S P M', 50000, 'sklineman1an'),
(76, 'Skaven', 1, 'Rat Ogre', 6, 5, 2, 8, 'Loner, Mighty Blow, Frenzy, Prehensile Tail,Wild Animal', 'S', 'G A P M', 150000, 'ratogre1an'),
(77, 'Skaven', 2, 'Thrower', 7, 3, 3, 7, 'Pass, Sure Hands', 'G P', 'A S M', 70000, 'skthrower1an'),
(78, 'Undead', 4, 'Ghoul', 7, 3, 3, 7, 'Dodge', 'G A', 'S P', 70000, 'ughoul4an'),
(79, 'Undead', 2, 'Mummy', 3, 5, 1, 9, 'Mighty Blow, Regeneration', 'S', 'G A P', 120000, 'kmmummy1an'),
(80, 'Undead', 16, 'Skeleton', 5, 3, 2, 7, 'Regeneration, Thick Skull', 'G', 'A S P', 40000, 'kmskeleton1an'),
(81, 'Undead', 2, 'Wight', 6, 3, 3, 8, 'Block, Regeneration', 'G S', 'A P', 90000, 'uwight2an'),
(82, 'Undead', 16, 'Zombie', 4, 3, 2, 8, 'Regeneration', 'G', 'A S P', 40000, 'uzombie2an'),
(83, 'Vampire', 16, 'Thrall', 6, 3, 3, 7, '', 'G', 'A S P', 40000, 'vampire2an'),
(84, 'Vampire', 6, 'Vampire', 6, 4, 4, 8, 'Blood Lust, Hypnotic Gaze, Regeneration', 'G A S', 'P', 110000, 'vampire1an'),
(85, 'Wood Elf', 4, 'Catcher', 8, 2, 4, 7, 'Catch, Dodge, Sprint', 'G A', 'S P', 90000, 'wecatcher1an'),
(86, 'Wood Elf', 16, 'Lineelf', 7, 3, 4, 7, '', 'G A', 'S P', 70000, 'welineman1an'),
(87, 'Wood Elf', 2, 'Thrower', 7, 3, 4, 7, 'Pass', 'G A P', 'S', 90000, 'wethrower1an'),
(88, 'Wood Elf', 1, 'Treeman', 2, 6, 1, 10, 'Loner, Mighty Blow, Stand Firm, Thick Skull, Strong Arm, Take Root, Throw Teammate', 'S', 'G A P', 120000, 'treeman1an'),
(89, 'Wood Elf', 2, 'Wardancer', 8, 3, 4, 7, 'Block, Dodge, Leap', 'G A', 'S P', 120000, 'weblitzer1an'),
(90, 'Chaos Pact', 12, 'Marauders', 6, 3, 3, 8, '', 'G S P M', 'A', 50000, 'cbeastman2b'),
(91, 'Chaos Pact', 1, 'Goblin Renegade', 6, 2, 3, 7, 'Animosity, Dodge, Right Stuff, Stunty', 'A M', 'G S P', 40000, 'goblin5'),
(92, 'Chaos Pact', 1, 'Skaven Renegade', 7, 3, 3, 7, 'Animosity', 'G M', 'A S P', 50000, 'sklineman1b'),
(93, 'Chaos Pact', 1, 'Dark Elf Renegade', 6, 3, 4, 8, 'Animosity', 'G A M', 'S P', 70000, 'delineman1'),
(94, 'Chaos Pact', 1, 'Chaos Troll', 4, 5, 1, 9, 'Loner, Always Hungry, Mighty Blow, Really Stupid, Regeneration, Throw Team-mate', 'S', 'G A P M', 110000, 'troll2b'),
(95, 'Chaos Pact', 1, 'Minotaur', 5, 5, 2, 8, 'Loner, Frenzy, Horns, Mighty Blow, Thick Skull, Wild Animal', 'S', 'G A P M', 150000, 'minotaur1'),
(96, 'Chaos Pact', 1, 'Chaos Ogre', 5, 5, 2, 9, 'Loner, Bone-head, Mighty Blow, Thick Skull, Throw Team-mate', 'S', 'G A P M', 140000, 'ogre1'),
(97, 'Slann', 16, 'Lineman', 6, 3, 3, 8, 'Leap, Very Long Legs', 'G', 'A S P', 60000, 'lmskink1an'),
(98, 'Slann', 4, 'Catcher', 7, 2, 4, 7, 'Diving Catch, Leap, Very Long Legs', 'G A', 'S P', 80000, 'lmskink2an'),
(99, 'Slann', 4, 'Blitzer', 7, 3, 3, 8, 'Diving Tackle, Jump Up, Leap, Very Long Legs', 'G A S', 'P', 110000, 'lmsaurus2an'),
(100, 'Slann', 1, 'Kroxigor', 6, 5, 1, 9, 'Loner, Bone-head, Mighty Blow, Prehensile Tail, Thisk Skull', 'S', 'G A P', 140000, 'kroxigor1'),
(101, 'Underworld', 12, 'Underworld Goblin', 6, 2, 3, 7, 'Right Stuff, Dodge, Stunty', 'A M', 'G S P', 40000, 'goblin2b'),
(102, 'Underworld', 2, 'Skaven Lineman', 7, 3, 3, 7, 'Animosity', 'G M', 'A S P', 50000, 'sklineman1an'),
(103, 'Underworld', 2, 'Skaven Thrower', 7, 3, 3, 7, 'Animosity, Pass, Sure Hands', 'G P M', 'A S', 70000, 'skthrower1b'),
(104, 'Underworld', 2, 'Skaven Blitzer', 7, 3, 3, 8, 'Animosity, Block', 'G S M', 'A P', 90000, 'skstorm1b'),
(105, 'Underworld', 1, 'Warpstone Troll', 4, 5, 1, 9, 'Loner, Always Hungry, Mighty Blow, Really Stupid, Regeneration, Thow Team-mate', 'S M', 'G A P', 110000, 'troll1an'),
(106, 'Bonus', 4, 'Blitzer', 7, 3, 3, 9, 'Block, +1 AV', 'G S', 'A P', 110000, 'hblitzer1an'),
(107, 'Bonus', 4, 'Catcher', 8, 2, 4, 7, 'Catch, Dodge, Sprint, Fan Favourite', 'G A', 'S P', 90000, 'wecatcher1an');

--
-- Data dump for tabellen `#__bb_skills`
--

INSERT IGNORE INTO `#__bb_skills` (`name`, `type`) VALUES
('Catch', 'A'),
('Diving Catch', 'A'),
('Diving Tackle', 'A'),
('Dodge', 'A'),
('Jump Up', 'A'),
('Leap', 'A'),
('Side Step', 'A'),
('Sneaky Git', 'A'),
('Sprint', 'A'),
('Sure Feet', 'A'),
('Block', 'G'),
('Dauntless', 'G'),
('Dirty Player', 'G'),
('Fend', 'G'),
('Frenzy', 'G'),
('Kick', 'G'),
('Kick-Off Return', 'G'),
('Pass Block', 'G'),
('Pro', 'G'),
('Shadowing', 'G'),
('Strip Ball', 'G'),
('Sure Hands', 'G'),
('Tackle', 'G'),
('Wrestle', 'G'),
('Accurate', 'P'),
('Dump-Off', 'P'),
('Hail Mary Pass', 'P'),
('Leader', 'P'),
('Nerves of Steel', 'P'),
('Pass', 'P'),
('Safe Throw', 'P'),
('Break Tackle', 'S'),
('Grab', 'S'),
('Guard', 'S'),
('Juggernaut', 'S'),
('Mighty Blow', 'S'),
('Multiple Block', 'S'),
('Piling On', 'S'),
('Stand Firm', 'S'),
('Strong Arm', 'S'),
('Thick Skull', 'S'),
('Big Hand', 'M'),
('Claw', 'M'),
('Disturbing Presence', 'M'),
('Extra Arms', 'M'),
('Foul Appearance', 'M'),
('Horns', 'M'),
('Prehensile Tail', 'M'),
('Tentacles', 'M'),
('Two Heads', 'M'),
('Very Long Legs', 'M'),
('+1 MA', 'X'),
('+1 ST', 'X'),
('+1 AG', 'X'),
('+1 AV', 'X');

-- --------------------------------------------------------
