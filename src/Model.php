<?php
namespace Notconnected\Testing;

/**
 * Description of Test_model
 *
 * @author Dmitry Leonov (notconnected@yandex.ru)
 */
class Model
{
    protected $dbh;
    
    public function __construct()
    {
        try {
            $this->dbh = new \PDO('mysql:host=localhost;dbname=testing', 'root', 'root');
            $this->dbh->exec("SET names utf8");
            $this->dbh->exec("SET CHARACTER SET utf8");
        } catch(PDOException $e) {
              echo $e->getMessage();
        }
    }
    
    public function __destruct()
    {
        $this->dbh = null;
    }
    
    public function countQuestions()
    {
        $sth = $this->dbh->prepare("SELECT count(question_id) cnt FROM questions;");
        $sth->execute();
        return $sth->fetchObject();
    }
    
    public function addQuestion(int $question_complexity)
    {
        $sth = $this->dbh->prepare("INSERT INTO questions (question_complexity) VALUES (?);");
        return $sth->execute([$question_complexity]);
    }
    
    public function getQuestionById(int $question_id)
    {
        $sth = $this->dbh->prepare("SELECT * FROM questions WHERE question_id = ?;");
        $sth->execute([$question_id]);
        return $sth->fetchObject();
    }
    
    public function updateQuestionStats($question_id)
    {
        $sth = $this->dbh->prepare("SELECT * FROM questions_stats WHERE question_id = ?;");
        $sth->execute([$question_id]);
        $question = $sth->fetchObject();
        if (!is_object($question)) {
            $sth = $this->dbh->prepare("INSERT INTO questions_stats (question_id, question_used) VALUE (?, ?);");
            return $sth->execute([$question_id, 1]);
        } else {
            $sth = $this->dbh->prepare("UPDATE questions_stats SET question_used = ? WHERE question_id = ?;");
            return $sth->execute([++$question->question_used, $question_id]);
        }
    }
    
    public function getQuestionsByComplexityAndStat(int $question_complexity_min, int $question_complexity_max)
    {
        $sth = $this->dbh->prepare("SELECT * FROM testing.questions q LEFT JOIN "
        . "(SELECT qs.question_id qid, qs.question_used FROM testing.questions_stats qs) ss ON q.question_id = ss.qid "
        . "WHERE q.question_complexity >= ? AND q.question_complexity <= ? "
        . "ORDER BY ss.question_used ASC LIMIT 0,40;");
        $sth->execute([$question_complexity_min, $question_complexity_max]);
        return $sth->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public function addUser(int $user_iq)
    {
        $sth = $this->dbh->prepare("INSERT INTO users (user_iq) VALUES (?);");
        return $sth->execute([$user_iq]);
    }
    
    public function getUserById(int $user_id)
    {
        $sth = $this->dbh->prepare("SELECT * FROM users WHERE user_id = ?;");
        $sth->execute([$user_id]);
        return $sth->fetchObject();
    }
    
    public function getCurrentUser()
    {
        $sth = $this->dbh->prepare("SELECT * FROM users ORDER BY user_id DESC LIMIT 0,1;");
        $sth->execute();
        return $sth->fetchObject();
    }
    
    public function setSettingValue(string $setting_name, int $setting_value)
    {
        $sth = $this->dbh->prepare("SELECT * FROM settings WHERE setting_name = ?;");
        $sth->execute([$setting_name]);
        if (!is_object($sth->fetchObject())) {
            $sth = $this->dbh->prepare("INSERT INTO settings (setting_name, setting_value) VALUE (?, ?);");
            return $sth->execute([$setting_name, $setting_value]);
        } else {
            $sth = $this->dbh->prepare("UPDATE settings SET setting_value = ? WHERE setting_name = ?;");
            return $sth->execute([$setting_value, $setting_name]);
        }
    }
    
    public function getSettingValue(string $setting_name)
    {
        $sth = $this->dbh->prepare("SELECT * FROM settings WHERE setting_name = ?;");
        $sth->execute([$setting_name]);
        return $sth->fetchObject();
    }
    
    public function setQuestionComplexity(int $question_complexity_min, int $question_complexity_max)
    {
        $sth = $this->dbh->prepare("UPDATE questions SET question_complexity = RAND()*(? - ?)+?;");
        return $sth->execute([$question_complexity_max, $question_complexity_min, $question_complexity_min]);
    }
    
    public function saveTestResult(array $result)
    {
        $sth = $this->dbh->prepare("INSERT INTO testing_stats "
                . "(user_id, "
                . "testing_stat_min_complexity, "
                . "testing_stat_max_complexity, "
                . "testing_stat_result) "
                . "VALUE (?, ?, ?, ?);");
        return $sth->execute([
            $result['user_id'], 
            $result['testing_stat_min_complexity'], 
            $result['testing_stat_max_complexity'],
            $result['total_true']
        ]);
    }
    
    public function getTestResult()
    {
        $sth = $this->dbh->prepare("SELECT * FROM testing_stats");
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_OBJ);
    }
}
