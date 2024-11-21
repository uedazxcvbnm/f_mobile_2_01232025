<?php
date_default_timezone_set('Asia/Tokyo'); // PHPのタイムゾーン設定

require_once __DIR__ . '/../input_daily_record/classes/dbdata.php';

class User extends dbdata
{
    public function signUp($userMail, $userName, $userPass)
    {
        // プレーンテキストで保存（セキュリティ上の考慮は必要）
        $sql = 'SELECT * FROM user WHERE user_mail = ?';
        $stmt = $this->query($sql, [$userMail]);
        $result = $stmt->fetch();

        // メールアドレスが既に登録されている場合のチェック
        if ($result) {
            return $result['user_mail'] . 'は既に登録されています。他のメールアドレスで登録してください';
        } else {
            // 新規登録の挿入処理
            $sql = "INSERT INTO user(user_mail, username, password) VALUES(?, ?, ?)";
            $result = $this->exec($sql, [$userMail, $userName, $userPass]);
        }

        return $result ? '' : '登録に失敗しました。';
    }

    public function auth($userName, $password)
    {
        // ユーザー情報を取得
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->query($sql, [$userName]);
        $result = $stmt->fetch();

        // ユーザーが存在しない場合
        if (!$result) {
            return 'ユーザー名、パスワードを確認してください';
        }

        // アカウントがロックされているかのチェック
        if ($result['failed_attempts'] >= 5 && $result['penalty_start'] !== null) {
            $penaltyMultiplier = $result['failed_attempts'] - 4;
            $penaltyMinutes = 5 * $penaltyMultiplier;
            $penaltyStart = strtotime($result['penalty_start']);
            $currentTime = time();
            $diff = $currentTime - $penaltyStart;

            // まだペナルティ時間中である場合
            if ($diff < ($penaltyMinutes * 60)) {
                $remainingTime = ($penaltyMinutes * 60) - $diff;
                $minutes = floor($remainingTime / 60);
                $seconds = $remainingTime % 60;
                return "アカウントがロックされています。あと {$minutes}分 {$seconds}秒後に再試行してください。";
            } else {
                // ペナルティ時間が経過していたらペナルティ開始時間のみをリセット
                $this->resetPenaltyStart($result['user_id']);
            }
        }

        // パスワードが一致するか確認（プレーンテキストでの比較）
        if ($password === $result['password']) {
            $this->resetFailedAttempts($result['user_id']); // 成功時に失敗回数とペナルティ時間をリセット
            return $result; // ログイン成功
        }

        // パスワードが間違っている場合、失敗回数をインクリメント
        $this->incrementFailedAttempts($result['user_id']);

        // ペナルティが解除されてからのミスによるペナルティ時間増加
        if ($result['failed_attempts'] >= 5) {
            $sql = "UPDATE user SET penalty_start = NOW() WHERE user_id = ?";
            $this->exec($sql, [$result['user_id']]);
        }

        // 5回目未満の失敗の場合、残り試行回数を表示
        $remainingAttempts = 5 - $result['failed_attempts'];
        return "ユーザーID、パスワードを確認してください。残り試行回数: {$remainingAttempts}";
    }

    // 失敗回数をリセットするメソッド
    private function resetFailedAttempts($userId)
    {
        $sql = "UPDATE user SET failed_attempts = 0, penalty_start = NULL WHERE user_id = ?";
        $this->exec($sql, [$userId]);
    }

    // ペナルティ開始時間のみをリセットするメソッド
    private function resetPenaltyStart($userId)
    {
        $sql = "UPDATE user SET penalty_start = NULL WHERE user_id = ?";
        $this->exec($sql, [$userId]);
    }

    // 失敗回数をインクリメントするメソッド
    private function incrementFailedAttempts($userId)
    {
        $sql = "UPDATE user SET failed_attempts = failed_attempts + 1 WHERE user_id = ?";
        $this->exec($sql, [$userId]);

        // 5回目の失敗でペナルティ開始時間を設定
        if ($this->getFailedAttempts($userId) >= 5) {
            $sql = "UPDATE user SET penalty_start = NOW() WHERE user_id = ?";
            $this->exec($sql, [$userId]);
        }
    }

    // 失敗回数を取得するメソッド
    private function getFailedAttempts($userId)
    {
        $sql = "SELECT failed_attempts FROM user WHERE user_id = ?";
        $stmt = $this->query($sql, [$userId]);
        $result = $stmt->fetch();
        return $result['failed_attempts'];
    }
}
