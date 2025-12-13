-- Add rating columns to request table
ALTER TABLE `request` 
ADD COLUMN `rating` INT(1) DEFAULT NULL COMMENT 'Rating from 1 to 5',
ADD COLUMN `rating_comment` TEXT DEFAULT NULL COMMENT 'Optional comment with rating',
ADD COLUMN `rating_date` DATETIME DEFAULT NULL COMMENT 'Date when rating was submitted';

-- Add index for better query performance
ALTER TABLE `request` ADD INDEX `idx_rating` (`rating`);

