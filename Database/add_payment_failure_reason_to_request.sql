-- Add payment_failure_reason column to request table
-- This allows admins to provide a reason when marking payment as failed

ALTER TABLE `request` 
ADD COLUMN IF NOT EXISTS `payment_failure_reason` TEXT DEFAULT NULL COMMENT 'Reason for payment failure';

