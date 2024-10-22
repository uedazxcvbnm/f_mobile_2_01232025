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
        $sql = "select * from chat inner join user on chat.user_id = user.user_id where not chat.user_id = ? order by date desc";
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
        $sql  ="select * from posts right outer join (select comment_text, created_at, user_id, post_id from comments union all select message, date, user_id, is_deleted from chat union all select message, date, user_id, is_deleted from global_chat) pbl2 on posts.id = pbl2.post_id inner join user on pbl2.user_id = user.user_id where not pbl2.user_id = ? and (posts.user_id = ? or posts.user_id is null) order by pbl2.created_at desc";
        $stmt = $this->query($sql, [$buser_id, $puser_id]);
        $all = $stmt->fetchAll();
        return $all;
    }
}