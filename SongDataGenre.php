<?php
class SongDataGenre implements JsonSerializable
{
    private $songGenreID;
    private $songGenreDescription;

    public function __construct($songGenreID, $songGenreDescription)
    {
        $this->songGenreID = $songGenreID;
        $this->songGenreDescription = $songGenreDescription;
    }

    public function getSongGenreID()
    {
        return $this->songGenreID;
    }

    public function getSongGenreDescription()
    {
        return $this->songGenreDescription;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
} //end SongDataCategory Class
