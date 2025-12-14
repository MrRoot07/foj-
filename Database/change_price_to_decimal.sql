-- Change price column from INT to DECIMAL to support decimal values like 8.40
-- This allows admins to set prices with decimal precision

ALTER TABLE `price_table` 
MODIFY COLUMN `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00;

