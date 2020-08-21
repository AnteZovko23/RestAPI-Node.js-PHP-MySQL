CREATE TABLE `Events` (
  `id` varchar(255) NOT NULL,
  `feedId` varchar(255) DEFAULT NULL,
  `feedEventId` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `sportId` varchar(255) DEFAULT NULL,
  `sportName` varchar(255) DEFAULT NULL,
  `categoryId` varchar(255) DEFAULT NULL,
  `categoryName` varchar(255) DEFAULT NULL,
  `tournamentId` varchar(255) DEFAULT NULL,
  `tournamentName` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `startAt` varchar(255) DEFAULT NULL,
  `active` varchar(255) DEFAULT NULL,
  `deleted` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
