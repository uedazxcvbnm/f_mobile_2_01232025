<?php
require_once __DIR__ . '../../input_daily_record/classes/dbdata.php';

class Team extends dbdata
{
    public function getTeams($ident, $id)
    {
        $sql = "select * from teams right outer join (select * from joined_teams order by user_id = ? desc, team_id asc limit 10000) joined_teams on teams.team_id = joined_teams.team_id group by joined_teams.team_id having not joined_teams.user_id = ?";
        $stmt = $this->query($sql, [$ident, $id]);
        $teams = $stmt->fetchAll();
        return $teams;
    }

    public function getTeam($ident)
    {
        $sql = "select * from teams where team_id = ?";
        $stmt = $this->query($sql, [$ident]);
        $team = $stmt->fetch();
        return $team;
    }

    public function getNewTeam()
    {
        $sql = "select max(team_id) from teams";
        $stmt = $this->query($sql, []);
        $new_team = $stmt->fetch();
        return $new_team;
    }

    public function addTeam($name, $detail)
    {
        $sql = "insert into teams (name, detail, size) values(?, ?, 1)";
        $result = $this->exec($sql, [$name, $detail]);
    }

    public function joinedTeam($team_id, $user_id)
    {
        $sql = "insert into joined_teams (team_id, user_id) values(?, ?)";
        $result = $this->exec($sql, [$team_id, $user_id]);
    }

    public function addTeamSize($team_id)
    {
        $sql = "update teams set size = size+1 where team_id = ?";
        $result = $this->exec($sql, [$team_id]);
    }
}