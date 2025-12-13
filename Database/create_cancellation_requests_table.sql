-- Create cancellation requests table
CREATE TABLE IF NOT EXISTS `cancellation_requests` (
  `cancellation_id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `cancellation_reason` TEXT NOT NULL,
  `cancellation_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `requested_date` datetime NOT NULL,
  `admin_response_date` datetime DEFAULT NULL,
  `admin_response_comment` TEXT DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cancellation_id`),
  KEY `request_id` (`request_id`),
  KEY `customer_id` (`customer_id`),
  KEY `cancellation_status` (`cancellation_status`),
  CONSTRAINT `cancellation_requests_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

