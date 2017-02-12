--
-- Table structure for table `temperatures`
--

CREATE TABLE IF NOT EXISTS `temperatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_code` varchar(6) NOT NULL,
  `month` tinyint(4) NOT NULL,
  `avg_temp` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Table structure for table `sync`
--

CREATE TABLE IF NOT EXISTS `sync` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gds` varchar(32) NOT NULL,
  `type` enum('LowFareSearchReq','HotelSearchAvailabilityReq','HotelDetailsReq','HotelMediaLinksReq') NOT NULL,
  `response` text NOT NULL,
  `status` enum('NEVER','INPROGRESS','COMPLETED') NOT NULL,
  `last_sync_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Table structure for table `cities`
--
CREATE TABLE IF NOT EXISTS `cities` (
  `code` varchar(6) NOT NULL,
  `name` varchar(32) NOT NULL,  
  `hotels_json` text,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `hotels`
--

CREATE TABLE IF NOT EXISTS `hotels` (
  `HotelCode` int(11) NOT NULL PRIMARY KEY,
  `city_code` varchar(6) NOT NULL,
  `Name` varchar(32) NOT NULL,
  `Availability` varchar(32) NOT NULL,
  `Address` tinytext NOT NULL,
  `Transportation` varchar(32) DEFAULT NULL,
  `ReserveRequirement` varchar(16) DEFAULT NULL,
  `ParticipationLevel` varchar(64) DEFAULT NULL,
  `MinimumAmount` varchar(12) DEFAULT NULL,
  `MaximumAmount` varchar(12) DEFAULT NULL,
  `MinimumAmountNum` varchar(12) DEFAULT NULL,
  `TotalMinAmountNum` varchar(12) DEFAULT NULL,
  FOREIGN KEY (city_code) 
        REFERENCES cities(code)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `hotels` ADD `Distance` INT NOT NULL AFTER `Address`;


INSERT INTO `temperatures` (`id`, `city_code`, `month`, `avg_temp`) VALUES
(1, 'AMS', 1, 37),
(2, 'AMS', 2, 39),
(3, 'AMS', 3, 43),
(4, 'AMS', 4, 47),
(5, 'AMS', 5, 54),
(6, 'AMS', 6, 59),
(7, 'AMS', 7, 62),
(8, 'AMS', 8, 63),
(9, 'AMS', 9, 58),
(10, 'AMS', 10, 52),
(11, 'AMS', 11, 44),
(12, 'AMS', 12, 40),
(13, 'BER', 1, 32),
(14, 'BER', 2, 34),
(15, 'BER', 3, 39),
(16, 'BER', 4, 47),
(17, 'BER', 5, 56),
(18, 'BER', 6, 63),
(19, 'BER', 7, 64),
(20, 'BER', 8, 64),
(21, 'BER', 9, 58),
(22, 'BER', 10, 49),
(23, 'BER', 11, 41),
(24, 'BER', 12, 34),
(25, 'BCN', 1, 49),
(26, 'BCN', 2, 49),
(27, 'BCN', 3, 53),
(28, 'BCN', 4, 55),
(29, 'BCN', 5, 60),
(30, 'BCN', 6, 67),
(31, 'BCN', 7, 73),
(32, 'BCN', 8, 74),
(33, 'BCN', 9, 71),
(34, 'BCN', 10, 64),
(35, 'BCN', 11, 55),
(36, 'BCN', 12, 51),
(37, 'LON', 1, 44),
(38, 'LON', 2, 44),
(39, 'LON', 3, 48),
(40, 'LON', 4, 52),
(41, 'LON', 5, 62),
(42, 'LON', 6, 63),
(43, 'LON', 7, 67),
(44, 'LON', 8, 66),
(45, 'LON', 9, 61),
(46, 'LON', 10, 55),
(47, 'LON', 11, 49),
(48, 'LON', 12, 44),
(49, 'CPH', 1, 32),
(50, 'CPH', 2, 33),
(51, 'CPH', 3, 37),
(52, 'CPH', 4, 43),
(53, 'CPH', 5, 53),
(54, 'CPH', 6, 59),
(55, 'CPH', 7, 63),
(56, 'CPH', 8, 62),
(57, 'CPH', 9, 56),
(58, 'CPH', 10, 49),
(59, 'CPH', 11, 41),
(60, 'CPH', 12, 36),
(61, 'PAR', 1, 41),
(62, 'PAR', 2, 42),
(63, 'PAR', 3, 47),
(64, 'PAR', 4, 52),
(65, 'PAR', 5, 59),
(66, 'PAR', 6, 61),
(67, 'PAR', 7, 69),
(68, 'PAR', 8, 68),
(69, 'PAR', 9, 61),
(70, 'PAR', 10, 54),
(71, 'PAR', 11, 45),
(72, 'PAR', 12, 41),
(73, 'IST', 1, 43),
(74, 'IST', 2, 42),
(75, 'IST', 3, 46),
(76, 'IST', 4, 54),
(77, 'IST', 5, 61),
(78, 'IST', 6, 71),
(79, 'IST', 7, 74),
(80, 'IST', 8, 74),
(81, 'IST', 9, 69),
(82, 'IST', 10, 60),
(83, 'IST', 11, 53),
(84, 'IST', 12, 46),
(85, 'BRU', 1, 37),
(86, 'BRU', 2, 37),
(87, 'BRU', 3, 44),
(88, 'BRU', 4, 48),
(89, 'BRU', 5, 55),
(90, 'BRU', 6, 59),
(91, 'BRU', 7, 64),
(92, 'BRU', 8, 64),
(93, 'BRU', 9, 59),
(94, 'BRU', 10, 53),
(95, 'BRU', 11, 44),
(96, 'BRU', 12, 40),
(97, 'AGP', 1, 55),
(98, 'AGP', 2, 55),
(99, 'AGP', 3, 59),
(100, 'AGP', 4, 61),
(101, 'AGP', 5, 65),
(102, 'AGP', 6, 72),
(103, 'AGP', 7, 77),
(104, 'AGP', 8, 77),
(105, 'AGP', 9, 74),
(106, 'AGP', 10, 67),
(107, 'AGP', 11, 61),
(108, 'AGP', 12, 55),
(109, 'ROM', 1, 46),
(110, 'ROM', 2, 48),
(111, 'ROM', 3, 50),
(112, 'ROM', 4, 55),
(113, 'ROM', 5, 63),
(114, 'ROM', 6, 70),
(115, 'ROM', 7, 75),
(116, 'ROM', 8, 75),
(117, 'ROM', 9, 70),
(118, 'ROM', 10, 63),
(119, 'ROM', 11, 54),
(120, 'ROM', 12, 48),
(121, 'KRK', 1, 28),
(122, 'KRK', 2, 28),
(123, 'KRK', 3, 38),
(124, 'KRK', 4, 46),
(125, 'KRK', 5, 57),
(126, 'KRK', 6, 62),
(127, 'KRK', 7, 65),
(128, 'KRK', 8, 64),
(129, 'KRK', 9, 57),
(130, 'KRK', 10, 48),
(131, 'KRK', 11, 37),
(132, 'KRK', 12, 31),
(133, 'TLL', 1, 26),
(134, 'TLL', 2, 24),
(135, 'TLL', 3, 30),
(136, 'TLL', 4, 39),
(137, 'TLL', 5, 50),
(138, 'TLL', 6, 57),
(139, 'TLL', 7, 62),
(140, 'TLL', 8, 60),
(141, 'TLL', 9, 51),
(142, 'TLL', 10, 44),
(143, 'TLL', 11, 35),
(144, 'TLL', 12, 27),
(145, 'LIS', 1, 52),
(146, 'LIS', 2, 54),
(147, 'LIS', 3, 57),
(148, 'LIS', 4, 58),
(149, 'LIS', 5, 64),
(150, 'LIS', 6, 69),
(151, 'LIS', 7, 74),
(152, 'LIS', 8, 73),
(153, 'LIS', 9, 73),
(154, 'LIS', 10, 65),
(155, 'LIS', 11, 57),
(156, 'LIS', 12, 53);


--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`name`, `code`, `hotels_json`) VALUES
('Malaga', 'AGP', ''),
('Amsterdam', 'AMS', ''),
('Athens', 'ATH', ''),
('Barcelona', 'BCN', ''),
('Berlin', 'BER', ''),
('Brussels', 'BRU', ''),
('Copenhagen', 'CPH', ''),
('Istanbul', 'IST', ''),
('St.Petersburg', 'LED', ''),
('Lisbon', 'LIS', ''),
('London', 'LON', ''),
('Madrid', 'MAD', ''),
('Milan', 'MIL', ''),
('Paris', 'PAR', ''),
('Prague', 'PRG', ''),
('Rome', 'ROM', ''),
('Sevilla', 'SVQ', ''),
('Valencia', 'VLC', '');

