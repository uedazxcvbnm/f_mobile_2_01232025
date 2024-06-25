<?php
require_once __DIR__ . '/../../input_daily_record/classes/dbdata.php';

class Team extends dbdata
{
    public function getTeams()
    {
        $sql = "select * from teams";
        $stmt = $this->query($sql, []);
        $teams = $stmt->fetchAll();
        return $teams;
    }

    public function getTeam($ident)
    {
        $sql = "select * from teams where ident = ?";
        $stmt = $this->query($sql, [$ident]);
        $team = $stmt->fetch();
        return $team;
    }
}