-- Fragment begins: 1 --
INSERT INTO changelog
                                (change_number, delta_set, start_dt, applied_by, description) VALUES (1, 'five-guys-crm', NOW(), 'DBDeploy', '001_changing_contacts_ownership.sql');
-- // 001: Changing contact ownership

UPDATE `contact` SET `member_id` = 1 WHERE `contact_id` <> 1;


UPDATE changelog
	                         SET complete_dt = NOW()
	                         WHERE change_number = 1
	                         AND delta_set = 'five-guys-crm';
-- Fragment ends: 1 --
