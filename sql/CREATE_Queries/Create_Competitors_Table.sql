CREATE TABLE `Competitors` (
  `eventID` varchar(180) NOT NULL,
  `id` varchar(180) NOT NULL,
  `name` varchar(255) NOT NULL,
  `teamId` varchar(180) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`eventID`,`id`,`teamId`),
  CONSTRAINT `fk_Competitors_2` FOREIGN KEY (`eventID`) REFERENCES `Events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
