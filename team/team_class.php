<?php
require_once __DIR__ . '../../input_daily_record/classes/dbdata.php';

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

    public function addTeam($name, $detail)
    {
        $sql = "insert into teams (name, detail, size) values(?, ?, 1)";
        $result = $this->exec($sql, [$name, $detail]);
    }

    public function addUserTeam($name, $rname, $user_id)
    {
        $sql = "update user set teams = case when teams like '%' ? '%' then teams else concat(teams, ? ',') end where user_id = ?";
        $result = $this->exec($sql, [$name, $rname, $user_id]);
    }
}