<?php
require_once dirname(__DIR__, 1) . '/entity/SongData.php';

class SongDataAccessor
{
    private $getAllStmtString = "select * from SONGDATA";
    private $getByIDStmtString = "select * from SONGDATA where songID = :songID";
    private $deleteStmtString = "delete from SONGDATA where songID = :songID";
    private $insertStmtString = "insert INTO SONGDATA values (:songID, :songGenreID, :songTitle, :artist, :album, :length)";
    private $updateStmtString = "update SONGDATA set songGenreID = :songGenreID, songTitle = :songTitle, artist = :artist, album = :album, length = :length where songID = :songID";

    private $getAllStmt = null;
    private $getByIDStmt = null;
    private $deleteStmt = null;
    private $insertStmt = null;
    private $updateStmt = null;

    public function __construct($conn)
    {
        if (is_null($conn)) {
            throw new Exception("No Connection");
        }

        $this->getAllStmt = $conn->prepare($this->getAllStmtString);
        if (is_null($this->getAllStmt)) {
            throw new Exception("Bad Statement: '" . $this->getAllStmtString . "'");
        }

        $this->getByIDStmt = $conn->prepare($this->getByIDStmtString);
        if (is_null($this->getByIDStmt)) {
            throw new Exception("Bad Statement: '" . $this->getByIDStmtString . "'");
        }

        $this->deleteStmt = $conn->prepare($this->deleteStmtString);
        if (is_null($this->deleteStmt)) {
            throw new Exception("Bad Statement: '" . $this->deleteStmtString . "'");
        }

        $this->insertStmt = $conn->prepare($this->insertStmtString);
        if (is_null($this->insertStmt)) {
            throw new Exception("Bad Statement: '" . $this->insertStmtString . "'");
        }

        $this->updateStmt = $conn->prepare($this->updateStmtString);
        if (is_null($this->updateStmt)) {
            throw new Exception("Bad Statement: '" . $this->updateStmtString . "'");
        }
    } //end construct conn

    public function getAllSongs()
    {
        $results = [];

        try {
            $this->getAllStmt->execute();
            $dbResults = $this->getAllStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbResults as $r) {
                $songID = $r['songID'];
                $songGenreID = $r['songGenreID'];
                $songTitle = $r['songTitle'];
                $artist = $r['artist'];
                $album = $r['album'];
                $length = $r['length'];

                $obj = new SongData($songID, $songGenreID, $songTitle, $artist, $album, $length);
                array_push($results, $obj);
            }
        } catch (Exception $e) {
            $results = [];
        } finally {
            if (!is_null($this->getAllStmt)) {
                $this->getAllStmt->closeCursor();
            }
        }

        return $results;
    } //end getAllSongs

    private function getSongByID($id)
    {
        $result = null;

        try {
            $this->getByIDStmt->bindParam(":songID", $id);
            $this->getByIDStmt->execute();

            $dbResults = $this->getByIDStmt->fetch(PDO::FETCH_ASSOC);

            if ($dbResults) {
                $songID = $dbResults['songID'];
                $songGenreID = $dbResults['songGenreID'];
                $songTitle = $dbResults['songTitle'];
                $artist = $dbResults['artist'];
                $album = $dbResults['album'];
                $length = $dbResults['length'];

                $result = new SongData($songID, $songGenreID, $songTitle, $artist, $album, $length);
            }
        } catch (Exception $e) {
            $result = null;
        } finally {
            if (!is_null($this->getByIDStmt)) {
                $this->getByIDStmt->closeCursor();
            }
        }

        return $result;
    } //end getSongByID

    public function songExists($song)
    {
        return $this->getSongByID($song->getSongByID()) !== null;
    } //end songExists

    public function deleteSong($song)
    {
        if (!$this->songExists($song)) {
            return false;
        }

        $success = false;
        $songID = $song->getSongByID();

        try {
            $this->deleteStmt->bindParam(":songID", $songID);
            $success = $this->deleteStmt->execute();
            $success = $success && $this->deleteStmt->rowCount() === 1;
        } catch (Exception $e) {
            $success = false;
        } finally {
            if (!is_null($this->deleteStmt)) {
                $this->deleteStmt->closeCursor();
            }
        }

        return $success;
    } //end deleteSong

    public function insertSong($song)
    {
        if ($this->songExists($song)) {
            return false;
        }

        $success = false;

        $songID = $song->getSongByID();
        $songGenreID = $song->getSongGenreID();
        $songTitle = $song->getSongTitle();
        $artist = $song->getArtist();
        $album = $song->getAlbum();
        $length = $song->getLength();

        try {
            $this->insertStmt->bindParam(":songID", $songID);
            $this->insertStmt->bindParam(":songGenreID", $songGenreID);
            $this->insertStmt->bindParam(":songTitle", $songTitle);
            $this->insertStmt->bindParam(":artist", $artist);
            $this->insertStmt->bindParam(":album", $album);
            $this->insertStmt->bindParam(":length", $length);

            $success = $this->insertStmt->execute();
            $success = $this->insertStmt->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->insertStmt)) {
                $this->insertStmt->closeCursor();
            }
        }

        return $success;
    } //end insertSong

    public function updateSong($song)
    {
        if (!$this->songExists($song)) {
            return false;
        }

        $success = false;

        $songID = $song->getSongByID();
        $songGenreID = $song->getSongGenreID();
        $songTitle = $song->getSongTitle();
        $artist = $song->getArtist();
        $album = $song->getAlbum();
        $length = $song->getLength();

        try {
            $this->updateStmt->bindParam(":songID", $songID);
            $this->updateStmt->bindParam(":songGenreID", $songGenreID);
            $this->updateStmt->bindParam(":songTitle", $songTitle);
            $this->updateStmt->bindParam(":artist", $artist);
            $this->updateStmt->bindParam(":album", $album);
            $this->updateStmt->bindParam(":length", $length);

            $success = $this->updateStmt->execute();
            $success = $this->updateStmt->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->updateStmt)) {
                $this->updateStmt->closeCursor();
            }
        }

        return $success;
    } //end updateSong

} //end SongDataAccessor class
