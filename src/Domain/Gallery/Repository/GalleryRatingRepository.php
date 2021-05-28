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
}