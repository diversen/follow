DROP INDEX `idx_user_id` ON follow;

ALTER TABLE `follow` ADD `uniqid` varchar(32) DEFAULT '';

CREATE INDEX idx_fetch ON follow(`user_id`, `parent_id`, `reference`);

CREATE INDEX idx_uniqid ON follow(`uniqid`);