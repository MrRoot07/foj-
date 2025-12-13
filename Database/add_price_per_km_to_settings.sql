-- Add price_per_km column to settings table if it doesn't exist
-- This allows admins to configure the price per kilometer for automatic price calculation

ALTER TABLE `settings` 
ADD COLUMN IF NOT EXISTS `price_per_km` DECIMAL(10,2) DEFAULT 5.00;

-- Set default value if column exists but is NULL
UPDATE `settings` SET `price_per_km` = 1.00 WHERE `price_per_km` IS NULL;

