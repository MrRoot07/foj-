-- Add refund fields to cancellation_requests table
ALTER TABLE `cancellation_requests` 
ADD COLUMN `customer_paypal_account` VARCHAR(255) DEFAULT NULL COMMENT 'Customer PayPal account for refund',
ADD COLUMN `refund_status` ENUM('pending', 'completed') DEFAULT NULL COMMENT 'Refund status',
ADD COLUMN `refund_date` DATETIME DEFAULT NULL COMMENT 'Date when refund was completed',
ADD COLUMN `refund_transaction_id` VARCHAR(255) DEFAULT NULL COMMENT 'PayPal transaction ID for refund',
ADD COLUMN `refund_amount` DECIMAL(10,2) DEFAULT NULL COMMENT 'Refund amount';

