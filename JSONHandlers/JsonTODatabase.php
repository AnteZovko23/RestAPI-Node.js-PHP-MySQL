<?php

/**
 * Analyzes JSON Files and extracts data based on keywords
 * Inserts data into database tables dynamically
 * 
 */

include "JsonTODatabaseHelpers.php";


// Opens Connection to database
$conn = OpenCon();
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$jsonFiles = array();

// Gets each json file, extracts data from it, and inserts into database
foreach (new DirectoryIterator('./JSONFiles/') as $file) {
    if ($file->getExtension() === 'json') {
        
        $jsonFiles[0] = json_decode(file_get_contents($file->getPathname()), true);


        /************************** */
        // Get Event Table
        $keywords = ["feedId","feedEventId", "type", "status", "sportId",  "sportName",  "categoryId", 
        "categoryName","tournamentId",  "tournamentName",  "name",  "startAt", "active", "deleted"];
        $specifier = [];
        $userDepth = 0;


        $mainArr = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $mainArr = getDataOrdered($mainArr);

        foreach ($mainArr as $key => $value) {
            $mainArr[$key][12] = strtotime($value[12]) * 1000;
        };


        insertQuery($conn, $mainArr, "Events");



        // // /********************************************** */


        /************************** */
        // Get Competitors Table
        $keywords = ["id", "name", "teamId", "type"];
        $specifier = ["eventCompetitors"];
        $userDepth = 2;


        $mainArr = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $mainArr = getDataOrdered($mainArr);



        insertQuery($conn, $mainArr, "Competitors");



        // // /********************************************** */

        // // // Get Market Table

        $keywords = ["id", "name"];
        $userDepth = 2;
        $specifier = ["markets"];
        $idName = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $idName = getDataOrdered($idName);
        // print_r($idName);


        $keywords = ["marketId", "sourceMarketId", "id"];
        $userDepth = 4;
        $specifier = ["markets", "marketSourceMaps"];
        $mainArr = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $mainArr = getDataOrdered($mainArr);


        $keywords = ["marketSourceMapId", "betradarSpecialType"];
        $userDepth = 6;
        $specifier = ["markets", "marketSourceMaps", "marketSourceMapSpecials"];
        $specialType = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $specialType = getDataOrdered($specialType);


        $idName = array_merge_callback($idName, $mainArr, 0, 1, function ($item1, $item2) {
            return ($item1[0] == $item2[0]) && ($item1[1] == $item2[1]);
        });
        array_merge_callbackKeep($idName, $specialType, 0, 1, function ($item1, $item2) {
            return ($item1[0] == $item2[0]) && ($item1[4] == $item2[1]);
        });



        $abc = array_unique($idName, SORT_REGULAR);


        insertQuery($conn, $abc, "Markets");

        // /********************************************** */

        // // Get EventMarket Table


        $keywords = ["id","marketId", "name", "active"];
        $userDepth = 4;
        $specifier = ["markets", "marketOutcomes"];
        $mainArr = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $mainArr = getDataOrdered($mainArr);

        $keywords = ["id", "marketOutcomeId", "sourceMarketOutcomeId"];
        $userDepth = 6;
        $specifier = ["markets", "marketOutcomes", "marketOutcomeSourceMaps"];
        $sourceID = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $sourceID = getDataOrdered($sourceID);


        $mainArr = array_merge_callback($mainArr, $sourceID, 0, 2, function ($item1, $item2) {
            return ($item1[0] == $item2[0]) && ($item1[1] == $item2[2]);
        });

        $keywords = ["marketOutcomeSourceMapId", "specialValue", "betradarSpecialType"];
        $userDepth = 8;
        $specifier = ["markets", "marketOutcomes", "marketOutcomeSourceMaps", "marketOutcomeSourceMapSpecials"];
        $specialVal = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $specialVal = getDataOrdered($specialVal);


        array_merge_callbackKeep($mainArr, $specialVal, 0, 1, function ($item1, $item2) {
            return ($item1[0] == $item2[0]) && ($item1[5] == $item2[1]);
        });



        $keywords = ["id", "marketId", "favourite", "status", "active", "deleted"];
        $userDepth = 2;
        $specifier = ["eventMarkets"];
        $mainArr2 = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $mainArr2 = getDataOrdered($mainArr2);

        $keywords = ["eventMarketId", "specialValue"];
        $userDepth = 4;
        $specifier = ["eventMarkets", "eventMarketSpecials"];
        $specialVal = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $specialVal = getDataOrdered($specialVal);

        array_merge_callbackKeep($mainArr2, $specialVal, 0, 1, function ($item1, $item2) {
            return ($item1[0] == $item2[0]) && ($item1[1] == $item2[1]);
        });



        $mainArr = array_merge_callback($mainArr, $mainArr2, 0, 2, function ($item1, $item2) {

            return ($item1[0] == $item2[0]) && ($item1[2] == $item2[2]);
            });


        insertQuery($conn, array_unique($mainArr, SORT_REGULAR), "MarketOutcomes");

        // /**************************************************** */

        // // Odds Table

        $keywords = ["marketId", "id"];
        $userDepth = 4;
        $specifier = ["markets", "marketOutcomes"];
        $viableIDMarketId = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $viableIDMarketId = getDataOrdered($viableIDMarketId);


        $keywords = ["marketId", "sourceMarketId"];
        $userDepth = 4;
        $specifier = ["markets", "marketSourceMaps"];
        $idSourceId = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $idSourceId = getDataOrdered($idSourceId);


        $viableIDMarketId = array_merge_callback($viableIDMarketId, $idSourceId, 0, 1, function ($item1, $item2) {
                return ($item1[0] == $item2[0]) && ($item1[1] == $item2[1]);
            }, 2);



        $keywords = ["marketId", "id"];
        $userDepth = 2;
        $specifier = ["eventMarkets"];
        $oddID = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $oddID = getDataOrdered($oddID);

        $viableIDMarketId = array_merge_callback($viableIDMarketId, $oddID, 0, 1, function ($item1, $item2) {
            return ($item1[0] == $item2[0]) && ($item1[1] == $item2[1]);
        });



        $keywords = ["id", "eventMarketId", "marketOutcomeId", "status", "odd"];
        $userDepth = 4;
        $specifier = ["eventMarkets", "eventMarketOutcomes"];
        $odds = getArrayForInsert($jsonFiles, $keywords, $specifier, $userDepth);
        $odds = getDataOrdered($odds);


        $viableIDMarketId = array_merge_callback($viableIDMarketId, $odds, 0, 2, function ($item1, $item2) {
                return ($item1[0] == $item2[0]) && ($item1[3] == $item2[2]) && ($item1[2] == $item2[3]);
            }, 3);


        insertQuery($conn, array_unique($viableIDMarketId, SORT_REGULAR), "Odds");
            }
}

?>