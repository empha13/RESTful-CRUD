<?php

class SongData implements JsonSerializable
{

    private $songID;
    private $songGenreID;
    private $songTitle;
    private $artist;
    private $album;
    private $length;

    public function __construct($songID, $songGenreID, $songTitle, $artist, $album, $length)
    {
        $this->songID = $songID;
        $this->songGenreID = $songGenreID;
        $this->songTitle = $songTitle;
        $this->artist = $artist;
        $this->album = $album;
        $this->length = $length;
    }

    public function getSongByID()
    {
        return $this->songID;
    }

    public function getSongGenreID()
    {
        return $this->songGenreID;
    }

    public function getSongTitle()
    {
        return $this->songTitle;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function getAlbum()
    {
        return $this->album;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
} //end SongData Class
