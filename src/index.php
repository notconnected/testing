<?php
error_reporting(0);
use Notconnected\Testing;
include dirname(__FILE__).'/../vendor/autoload.php';

$test = new Testing\Controller();

//init
if ($test->countQuestions()->cnt == 0) {
    for ($i = 0; $i < 100; $i++) {
        $test->addQuestion();
    }
}

switch ($_POST['action']) {
    case "setComplexity":
        $result1 = $test->setSettingValue("question_complexity_min", filter_input(INPUT_POST, 'complexityMin', FILTER_VALIDATE_INT));
        $result2 = $test->setSettingValue("question_complexity_max", filter_input(INPUT_POST, 'complexityMax', FILTER_VALIDATE_INT));
        $result3 = $test->setQuestionComplexity(filter_input(INPUT_POST, 'complexityMin', FILTER_VALIDATE_INT), filter_input(INPUT_POST, 'complexityMax', FILTER_VALIDATE_INT));
        if ($result1 && $result2 && $result3) {
            echo 1;
        } else {
            echo 0;
        }
        break;
    case "setUserIq":
        if ($test->addUser(filter_input(INPUT_POST, 'userIQ', FILTER_VALIDATE_INT))) {
            echo 1;
        } else {
            echo 0;
        }
        break;
    case "run":
        $result = $test->runTest();
        if (is_array($result)) {
            $test->saveTestResult($result);
            echo json_encode($result);
        } else {
            echo 0;
        }
        break;
    case "getSettings":
        $complexityMin = $test->getSettingValue("question_complexity_min")->setting_value;
        $complexityMax = $test->getSettingValue("question_complexity_max")->setting_value;
        $user_iq = $test->getCurrentUser()->user_iq;
        echo json_encode([$complexityMin, $complexityMax, $user_iq]);
        break;
}