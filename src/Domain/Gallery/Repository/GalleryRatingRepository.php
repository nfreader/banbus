<?php

namespace App\Domain\Gallery\Repository;

use App\Repository\Database;

class GalleryRatingRepository extends Database
{
    public function getRatingsForServer(string $server): self
    {
        $this->setResults($this->alt_db->run(
            "SELECT 
            format(avg(rating),2) as rating, 
            artwork, count(id) as votes 
            FROM art_vote 
            WHERE `server` = ? 
            GROUP BY artwork;",
            $server
        ));
        return $this;
    }

    public function getRatingForArtwork(string $md5): self
    {
        $this->setResults(
            $this->alt_db->row(
                "SELECT 
                format(avg(rating),2) as rating, 
                artwork, count(id) as votes 
                FROM art_vote
                WHERE artwork = ?",
                $md5
            )
        );
        return $this;
    }

    public function castVote($server, $md5, $rating, $ckey): self
    {
        //TODO: Replace with insertOnDuplicateUpdate from EasyDB
        $this->alt_db->run(
            "INSERT INTO `art_vote` (`server`, artwork, rating, ckey) 
            VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE rating = ?",
            $server,
            $md5,
            $rating,
            $ckey,
            $rating
        );
        $this->getRatingForArtwork($md5);
        return $this;
    }
}
