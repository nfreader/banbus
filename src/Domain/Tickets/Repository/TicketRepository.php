<?php

namespace App\Domain\Tickets\Repository;

use ParagonIE\EasyDB\EasyDB;
use App\Repository\Database;
use App\Domain\Tickets\Factory\TicketFactory;

class TicketRepository extends Database
{
    private $ticketFactory;

    public function __construct(EasyDB $db, TicketFactory $ticketFactory)
    {
        parent::__construct($db);
        $this->ticketFactory = $ticketFactory;
    }

    public function getTicketsByCkey(string $ckey, int $page = 1, int $per_page = 60): self
    {
        $this->setPages((int) ceil($this->db->cell(
            "SELECT
          count(t.id) 
          FROM ticket t
          WHERE t.action = 'Ticket Opened' 
          AND (t.recipient = ? OR t.sender = ?);",
            $ckey,
            $ckey
        ) / $per_page));
        $this->setResults(
            $this->db->run(
                "SELECT 
                t.server_ip,
                t.server_port as port,
                t.round_id as round,
                t.ticket,
                t.action,
                t.message,
                t.timestamp,
                t.recipient as r_ckey,
                t.sender as s_ckey,
                r.rank as r_rank,
                s.rank as s_rank,
                (SELECT `action` 
                  FROM ticket 
                  WHERE t.ticket = ticket AND t.round_id = round_id 
                  ORDER BY id DESC LIMIT 1)
                as `status`,
                (SELECT COUNT(id) 
                  FROM ticket 
                  WHERE t.ticket = ticket 
                  AND t.round_id = round_id) 
                as `replies`
                FROM ticket t
                LEFT JOIN `admin` AS r ON r.ckey = t.recipient	
                LEFT JOIN `admin` AS s ON s.ckey = t.sender
                WHERE t.action = 'Ticket Opened'
                AND (t.recipient = ? OR t.sender = ?)
                GROUP BY t.id
                ORDER BY `timestamp` DESC
                LIMIT ?, ?",
                $ckey,
                $ckey,
                ($page * $per_page) - $per_page,
                $per_page
            )
        );
        return $this;
    }

    public function getSingleTicket(int $round, int $ticket): self
    {
        try {
            $this->setResults(
                $this->db->run(
                    "SELECT 
                    t.server_ip,
                    t.server_port as `port`,
                    t.round_id as `round`,
                    t.ticket,
                    t.action,
                    t.message,
                    t.timestamp,
                    t.recipient as r_ckey,
                    t.sender as s_ckey,
                    r.rank as r_rank,
                    s.rank as s_rank,
                    (SELECT COUNT(id) 
                      FROM ticket 
                      WHERE t.ticket = ticket 
                      AND t.round_id = round_id) 
                    as `replies`
                    FROM ticket t
                    LEFT JOIN `admin` AS r ON r.ckey = t.recipient	
                    LEFT JOIN `admin` AS s ON s.ckey = t.sender
                    WHERE t.round_id = ?
                    AND t.ticket = ? 
                    GROUP BY t.id
                    ORDER BY `timestamp` ASC",
                    $round,
                    $ticket
                )
            );
        } catch (\Exception $e) {
            die($e->getMessage());
        }
        return $this;
    }
}