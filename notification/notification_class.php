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

    public function getChats($user_id){
        $sql = "select * from group_chat inner join user on group_chat.user_id = user.user_id join teams on group_chat.team_id = teams.team_id where not group_chat.user_id = ? order by date desc";
        $stmt = $this->query($sql, [$user_id]);
        $chats = $stmt->fetchAll();
        return $chats;
    }

    public function getGlobalChats($user_id){
        $sql = "select * from global_chat inner join user on global_chat.user_id = user.user_id where not global_chat.user_id = ? order by date desc";
        $stmt = $this->query($sql, [$user_id]);
        $globalChats = $stmt->fetchAll();
        return $globalChats;
    }

    public function getAll($buser_id, $puser_id){
        $sql = "select pbl2.message, pbl2.date, pbl2.user_id, pbl2.team_id, posts.id, posts.post_title, posts.user_id, teams.name, user.username from (select message, date, user_id, team_id, is_deleted from group_chat union all select message, date, user_id, null, is_deleted from global_chat union all select comment_text, created_at, user_id, null, post_id from comments) pbl2 left outer join posts on pbl2.is_deleted = posts.id left outer join teams on pbl2.team_id = teams.team_id inner join user on pbl2.user_id = user.user_id  where not pbl2.user_id = ? and (posts.user_id = ? or posts.user_id is null) order by pbl2.date desc";
        $stmt = $this->query($sql, [$buser_id, $puser_id]);
        $all = $stmt->fetchAll();
        return $all;
    }
}