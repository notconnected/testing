<?php
namespace Notconnected\Testing;
/**
 * Description of test
 *
 * @author Dmitry Leonov (notconnected@yandex.ru)
 */
class Controller
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new Model();
    }
    
    public function addUser(int $user_iq)
    {
        if ($user_iq > 100) {
            $user_iq = 100;
        }
        if ($user_iq < 0) {
            $user_iq = 0;
        }
        return $this->model->addUser($user_iq);
    }
    
    public function getCurrentUser()
    {
        return $this->model->getCurrentUser();
    }
    
    public function getUserById(int $user_id)
    {
        return $this->model->getUserById($user_id);
    }
    
    public function countQuestions()
    {
        return $this->model->countQuestions();
    }
    
    public function addQuestion(int $question_complexity = 0)
    {
        $this->model->addQuestion($question_complexity);
        
        $complexity_min = $this->getSettingValue('question_complexity_min')->setting_value;
        $complexity_max = $this->getSettingValue('question_complexity_max')->setting_value;

        if ($complexity_max && $complexity_min) {
            $this->setQuestionComplexity($complexity_min, $complexity_max);
        }
    }
    
    public function getQuestionById(int $question)
    {
        return $this->model->getQuestionById($question);
    }
    
    public function getQuestionsByComplexityAndStat(int $question_complexity_min, int $question_complexity_max)
    {
        return $this->model->getQuestionsByComplexityAndStat($question_complexity_min, $question_complexity_max);
    }
    
    public function setSettingValue(string $setting_name, int $setting_velue)
    {
        if ($setting_velue > 100) {
            $setting_velue = 100;
        }
        if ($setting_velue < 0) {
            $setting_velue = 0;
        }
        return $this->model->setSettingValue($setting_name, $setting_velue);
    }
    
    public function getSettingValue(string $setting_name)
    {
        return $this->model->getSettingValue($setting_name);
    }
    
    public function setQuestionComplexity(int $question_complexity_min, int $question_complexity_max)
    {
        if ($question_complexity_min > 100) {
            $question_complexity_min = 100;
        }
        if ($question_complexity_min < 0) {
            $question_complexity_min = 0;
        }
        
        if ($question_complexity_max > 100) {
            $question_complexity_max = 100;
        }
        if ($question_complexity_max < 0) {
            $question_complexity_max = 0;
        }
        return $this->model->setQuestionComplexity($question_complexity_min, $question_complexity_max);
    }
    
    public function runTest()
    {
        $current_user = $this->model->getCurrentUser();
        $complexity_min = $this->getSettingValue('question_complexity_min')->setting_value;
        $complexity_max = $this->getSettingValue('question_complexity_max')->setting_value;

        if (!$complexity_min || !$complexity_max || !is_object($current_user)) return false;
        
        /* 
         * Избыточная проверка, на текущий момент установка настройки сложности
         * и изменение сложностей вопросов - один процесс и выбор по сложности
         * вопросов по сути не требуется, можно было взять просто 40 вопросов с 
         * учетом статистики.
         * Но так, в будущем, можно будет разделить процес на изменение настроек 
         * для тестирования и перегенерации сложности всех вопросов.
         * Например, если вопросов будет избыточное количество, тогда настройка 
         * сложности позволит выбирать вопросы по уровню тестируемого без
         * перегенерации сложности самих вопросов.
         */
        $questions = $this->getQuestionsByComplexityAndStat($complexity_min, $complexity_max);

        $result = [];
        $result['total_true'] = 0;
        //1 - Да, 0 - Нет.
        foreach ($questions as $question) {
            $this->model->updateQuestionStats($question->question_id);
            if ($current_user->user_iq >= $question->question_complexity) {
                 /* 
                  * Шанс ответить правильно выше (ограничиваем нижнюю границу
                  * рандома с учетом интелекта)
                  */
                $chance = rand($current_user->user_iq, 100) / 100; //шансы ответить правильно, если испытуемый робот
                /*
                 * человеческий фактор (ошибки)
                 * чем умнее испытуемый, тем реже он ошибается, ниже вроятность ошибки
                 */
                $human_factor = rand(0, 1+$current_user->user_iq);
                if ($human_factor == 1) { //можно взять любое число, чем шире диапазон, тем ниже вероятность выпадения
                    $chance = 0;
                }
                $result['questions'][$question->question_id]['answer'] = round($chance); // 1 * $chance
            } elseif ($current_user->user_iq < $question->question_complexity) {
                 /*
                  * Шанс ответить ошибочно выше (ограничиваем верхнюю границу
                  * рандома с учетом интелекта)
                  */
                $chance = rand(0, (100 - $current_user->user_iq)) / 100;
                /*
                 * человеческий фактор (везение)
                 * IQ не влияет
                 */
                $human_factor = rand(0, 100);
                if ($human_factor == 1) {
                    $chance = 1;
                }
                $result['questions'][$question->question_id]['answer'] = round($chance);
            }
            
            //override if question complexity = 0
            if ($question->question_complexity == 0) {
                $result['questions'][$question->question_id]['answer'] = 1;
            }
            
            //override if user iq = 0
            if ($current_user->user_iq == 0) {
                $result['questions'][$question->question_id]['answer'] = 0;
            }
            
            //override if user iq = 100
            if ($current_user->user_iq == 100) {
                $result['questions'][$question->question_id]['answer'] = 1;
            }
            
            //override if question complexity = 100
            if ($question->question_complexity == 100) {
                $result['questions'][$question->question_id]['answer'] = 0;
            }
            
            if ($result['questions'][$question->question_id]['answer'] == 1) {
                $result['total_true']++;
            }
            
            $result['questions'][$question->question_id]['used'] = $question->question_used;
            $result['questions'][$question->question_id]['complexity'] = $question->question_complexity;
        }
        $result['user_id'] = $current_user->user_id;
        $result['testing_stat_min_complexity'] = $complexity_min;
        $result['testing_stat_max_complexity'] = $complexity_max;
        return $result;
    }
    
    public function saveTestResult(array $result)
    {
        return $this->model->saveTestResult($result);
    }
    
    public function getTestResult()
    {
        return $this->model->getTestResult();
    }
}
