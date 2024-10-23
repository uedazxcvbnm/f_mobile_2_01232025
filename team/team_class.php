<?php
require_once __DIR__ . '../../input_daily_record/classes/dbdata.php';

class Team extends dbdata
{
    public function leaveTeam($team_id, $user_id)
    {
        try {
            $sql = "DELETE FROM joined_teams WHERE team_id = ? AND user_id = ?";
            $stmt = $this->query($sql, [$team_id, $user_id]);
            if ($stmt->rowCount() > 0) {
                error_log("退会処理: team_id: $team_id, user_id: $user_id - 削除成功");
            } else {
                error_log("退会処理: team_id: $team_id, user_id: $user_id - 削除失敗。レコードが見つかりませんでした。");
                throw new Exception('退会処理が行われませんでした。正しいデータがない可能性があります。');
            }
        } catch (Exception $e) {
            error_log("退会処理: SQLエラー - " . $e->getMessage());
            throw new Exception('SQLエラー: ' . $e->getMessage());
        }
    }
    public function decreaseTeamSize($team_id)
    {
        try {
            $sql = "UPDATE teams SET size = size - 1 WHERE team_id = ? AND size > 0";
            $stmt = $this->query($sql, [$team_id]);
            if ($stmt->rowCount() > 0) {
                // 人数の減少に成功
            } else {
                throw new Exception('グループ人数の更新に失敗しました。');
            }
        } catch (Exception $e) {
            throw new Exception('グループ人数の更新中にエラーが発生しました: ' . $e->getMessage());
        }
    }




    public function getJoinedTeams($user_id)
    {
        $sql = "SELECT teams.* FROM teams
            INNER JOIN joined_teams ON teams.team_id = joined_teams.team_id
            WHERE joined_teams.user_id = ?";
        $stmt = $this->query($sql, [$user_id]);
        return $stmt->fetchAll();
    }

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
