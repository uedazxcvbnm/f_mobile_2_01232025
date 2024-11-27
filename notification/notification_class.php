<?php
    require_once __DIR__ . '../../input_daily_record/classes/dbdata.php';

class Notification extends dbdata
{
    public function getComments($puser_id,$cuser_id){
        $sql = "select * from posts inner join comments on posts.id = comments.post_id inner join user on comments.user_id = user.user_id where posts.user_id = ? and not comments.user_id = ? order by comments.created_at desc";
        $stmt = $this->query($sql, [$puser_id, $cuser_id]);
        $comments = $stmt->fetchAll();
        return $comments;
    }

  
    public function getTeamId($user_id){
        // 11/27書き換えあるいは追加
        $sql = "select * from user join joined_teams on user.user_id = joined_teams.user_id where user.user_id = ?";
        $stmt = $this->query($sql, [$user_id]);
        // 11/27書き換えあるいは追加
        $team_id = $stmt->fetchAll();
        return $team_id;
    }

    // 11/27書き換えあるいは追加
    public function getChats($user_id, $team_id1, $team_id2, $team_id3, $team_id4, $team_id5, $team_id6, $team_id7, $team_id8, $team_id9, $team_id10){
        $sql = "select * from group_chat join teams on group_chat.team_id = teams.team_id join user on group_chat.user_id = user.user_id where not group_chat.user_id = ? and (group_chat.team_id = ? or group_chat.team_id = ? or group_chat.team_id = ? or group_chat.team_id = ? or group_chat.team_id = ? or group_chat.team_id = ? or group_chat.team_id = ? or group_chat.team_id = ? or group_chat.team_id = ? or group_chat.team_id = ?) order by date desc";
        $stmt = $this->query($sql, [$user_id, $team_id1, $team_id2, $team_id3, $team_id4, $team_id5, $team_id6, $team_id7, $team_id8, $team_id9, $team_id10]);
        $test = $stmt->fetchAll();
        return $test;
    }

    public function getGlobalChats($user_id){
        $sql = "select * from global_chat inner join user on global_chat.user_id = user.user_id where not global_chat.user_id = ? order by date desc";
        $stmt = $this->query($sql, [$user_id]);
        $globalChats = $stmt->fetchAll();
        return $globalChats;
    }

    // 11/27書き換えあるいは追加
    public function getAll($buser_id, $puser_id, $team_id1, $team_id2, $team_id3, $team_id4, $team_id5, $team_id6, $team_id7, $team_id8, $team_id9, $team_id10){
        $sql = "select pbl2.message, pbl2.date, pbl2.user_id, pbl2.team_id, posts.id, posts.post_title, posts.user_id, teams.name, user.username from (select message, date, user_id, team_id, is_deleted from group_chat union all select message, date, user_id, null, is_deleted from global_chat union all select comment_text, created_at, user_id, null, post_id from comments) pbl2 left outer join posts on pbl2.is_deleted = posts.id left outer join teams on pbl2.team_id = teams.team_id inner join user on pbl2.user_id = user.user_id  where not pbl2.user_id = ? and (posts.user_id = ? or posts.user_id is null) and (pbl2.team_id = ? or pbl2.team_id = ? or pbl2.team_id = ? or pbl2.team_id = ? or pbl2.team_id = ? or pbl2.team_id = ? or pbl2.team_id = ? or pbl2.team_id = ? or pbl2.team_id = ? or pbl2.team_id = ?)order by pbl2.date desc";
        $stmt = $this->query($sql, [$buser_id, $puser_id, $team_id1, $team_id2, $team_id3, $team_id4, $team_id5, $team_id6, $team_id7, $team_id8, $team_id9, $team_id10]);
        $all = $stmt->fetchAll();
        return $all;
    }
}