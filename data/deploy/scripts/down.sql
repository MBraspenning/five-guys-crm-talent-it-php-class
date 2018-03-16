-- Fragment begins: 1 --


UPDATE `contact` SET `member_id` = `contact_id` WHERE `contact_id` <> 1;

-- //Done
DELETE FROM changelog
	                         WHERE change_number = 1
	                         AND delta_set = 'five-guys-crm';
-- Fragment ends: 1 --
