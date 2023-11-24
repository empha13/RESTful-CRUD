<?php
require_once dirname(__DIR__, 1) . '/entity/SongDataGenre.php';

class SongDataGenreAccessor
{
    private $getAllStmtString = "select * from SONGDATACATEGORY";
    private $getAllStmt = null;

    public function __construct($conn)
    {
        if (is_null($conn)) {
            throw new Exception("No Connection");
        }

        $this->getAllStmt = $conn->prepare($this->getAllStmtString);
        if (is_null($this->getAllStmt)) {
            throw new Exception("Bad Statement: '" . $this->getAllStmtString . "'");
        }
    } //end construct

    public function getAllGenres()
    {
        $result = [];

        try {
            $this->getAllStmt->execute();
            $dbResults = $this->getAllStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbResults as $r) {
                $songGenreID = $r['songGenreID'];
                $songGenreDescription = $r['songGenreDescription'];

                $obj = new SongDataGenre($songGenreID, $songGenreDescription);
                array_push($result, $obj);
            }
        } catch (Exception $e) {
            $result = [];
        } finally {
            if (!is_null($this->getAllStmt)) {
                $this->getAllStmt->closeCursor();
            }
        }

        return $result;
    }
}//end SonDataGenreAccessor class
