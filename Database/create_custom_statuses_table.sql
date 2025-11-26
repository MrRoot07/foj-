-- Create custom statuses table to store admin-defined statuses
CREATE TABLE IF NOT EXISTS `custom_statuses` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name_en` varchar(2 b55) NOT NULL,
  `status_name_ar` varchar(255) NOT NULL,
  `status_icon` varchar(50) DEFAULT 'fa-circle',
  `status_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`status_id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default statuses
INSERT INTO `custom_statuses` (`status_name_en`, `status_name_ar`, `status_icon`, `status_order`) VALUES
('Order is placed', 'تم وضع الطلب', 'fa-shopping-cart', 1),
('Prepare Order', 'تحضير الطلب', 'fa-box', 2),
('Seller has drop-off your parcel and will be picked up by our logistics partner', 'قام البائع بإسقاط طردك وسيتم استلامه من قبل شريكنا اللوجستي', 'fa-hand-holding-box', 3),
('Your parcel has been picked up by our logistics partner', 'تم استلام طردك من قبل شريكنا اللوجستي', 'fa-truck-pickup', 4),
('Your parcel has arrived at sorting facility', 'وصل طردك إلى منشأة الفرز', 'fa-warehouse', 5),
('Your parcel has departed from sorting facility', 'غادر طردك منشأة الفرز', 'fa-truck', 6),
('Your parcel has arrived at the delivery hub', 'وصل طردك إلى مركز التوصيل', 'fa-building', 7),
('Your parcel is out for delivery', 'طردك في الطريق للتوصيل', 'fa-truck-fast', 8),
('Delivery attempt was unsuccessful', 'فشلت محاولة التوصيل', 'fa-exclamation-triangle', 9),
('Your parcel is ready for collection at collection point', 'طردك جاهز للاستلام من نقطة الاستلام', 'fa-store', 10),
('Parcel has been delivered', 'تم التسليم', 'fa-circle-check', 11),
('Canceled', 'ملغي', 'fa-times-circle', 12);

