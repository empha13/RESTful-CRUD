<?php
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/db/SongDataAccessor.php';
require_once dirname(__DIR__, 1) . '/entity/SongData.php';
require_once dirname(__DIR__, 1) . '/utils/Constants.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, Constants::$MYSQL_PASSWORD);
    $sda = new SongDataAccessor($cm->getConnection());

    if ($method === "GET") {
        doGet($sda);
    } else if ($method === "POST") {
        doPost($sda);
    } else if ($method === "DELETE") {
        doDelete($sda);
    } else if ($method === "PUT") {
        doPut($sda);
    } else {
        sendResponse(405, null, "method not allowed");
    }
} catch (Exception $e) {
    sendResponse(500, null, "error " . $e->getMessage());
} finally {
    if (!is_null($cm)) {
        $cm->closeConnection();
    }
}

function doGet($sda)
{

    if (isset($_GET['songid'])) {
        sendResponse(405, null, "individual GETs not allowed");
    } else {
        try {
            $results = $sda->getAllSongs();
            if (count($results) > 0) {
                $results = json_encode($results, JSON_NUMERIC_CHECK);
                sendResponse(200, $results, null);
            } else {
                sendResponse(404, null, "could not retrieve songs");
            }
        } catch (Exception $e) {
            sendResponse(500, null, "error " . $e->getMessage());
        }
    }
}

function doPost($sda)
{
    if (isset($_GET['songid'])) {

        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        try {
            // create a song object
            $songDataObj = new SongData($contents['songID'], $contents['songGenreID'], $contents['songTitle'], $contents['artist'], $contents['album'], $contents['length']);

            // add the object to the database
            $success = $sda->insertSong($songDataObj);
            if ($success) {
                sendResponse(201, $success, null);
            } else {
                sendResponse(409, null, "could not insert song as it already exists");
            }
        } catch (Exception $e) {
            sendResponse(400, null, $e->getMessage());
        }
    } else {
        sendResponse(405, null, "bulk INSERTs not allowed");
    }
}

function doDelete($sda)
{
    if (isset($_GET['songid'])) {
        $songID = $_GET['songid'];
        $songDataObj = new SongData($songID, "genre", "title", "artist", "album", "length");

        // delete the object from database
        $success = $sda->deleteSong($songDataObj);
        if ($success) {
            sendResponse(200, $success, null);
        } else {
            sendResponse(404, null, "could not delete song as it does not exist");
        }
    } else {
        sendResponse(405, null, "bulk DELETEs not allowed");
    }
}

function doPut($sda)
{
    if (isset($_GET['songid'])) {
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        try {
            // create a song object
            $songDataObj = new SongData($contents['songID'], $contents['songGenreID'], $contents['songTitle'], $contents['artist'], $contents['album'], $contents['length']);

            // update the object in the database
            $success = $sda->updateSong($songDataObj);
            if ($success) {
                sendResponse(200, $success, null);
            } else {
                sendResponse(404, null, "could not update song as it does not exist");
            }
        } catch (Exception $e) {
            sendResponse(400, null, $e->getMessage());
        }
    } else {
        sendResponse(405, null, "bulk UPDATEs not allowed");
    }
}

function sendResponse($statusCode, $data, $error)
{
    header("Content-Type: application/json");
    http_response_code($statusCode);
    $resp = ['data' => $data, 'error' => $error];
    echo json_encode($resp, JSON_NUMERIC_CHECK);
}
