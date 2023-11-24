<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/db/SongDataGenreAccessor.php';
require_once dirname(__DIR__, 1) . '/entity/SongDataGenre.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, Constants::$MYSQL_PASSWORD);
    $sdg = new SongDataGenreAccessor($cm->getConnection());
    if ($method === "GET") {
        doGet($sdg);
    }
} catch (Exception $e) {
    sendResponse(500, null, "error " . $e->getMessage());
} finally {
    if (!is_null($cm)) {
        $cm->closeConnection();
    }
}

function doGet($sdg)
{
    if (!isset($_GET['genreid'])) {
        try {
            $results = $sdg->getAllGenres();
            $results = json_encode($results, JSON_NUMERIC_CHECK);
            sendResponse(200, $results, null);
        } catch (Exception $e) {
            sendResponse(500, null, "error " . $e->getMessage());
        }
    } else {
        ChromePhp::log($_GET['genreid']);
    }
}

function sendResponse($statusCode, $data, $error)
{
    header("Content-Type: application/json");
    http_response_code($statusCode);
    $resp = ['data' => $data, 'error' => $error];
    echo json_encode($resp, JSON_NUMERIC_CHECK);
}
