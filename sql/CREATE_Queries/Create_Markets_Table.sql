CREATE TABLE `Markets` (
  `eventID` varchar(255) NOT NULL,
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sourceId` varchar(255) NOT NULL,
  `specialTypeID` varchar(255) DEFAULT NULL,
  `specialType` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`eventID`,`id`,`sourceId`),
  CONSTRAINT `fk_Markets_2` FOREIGN KEY (`eventID`) REFERENCES `Events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
