DROP TRIGGER IF EXISTS `bb_date_created`;
DELIMITER //
CREATE TRIGGER `bb_date_created` 
BEFORE UPDATE ON `gd8h_bb_matches` FOR EACH ROW 
BEGIN
	IF OLD.date_created IS NULL THEN
		SET NEW.date_created = NEW.date_modified;
	END IF;
END//
DELIMITER ;
