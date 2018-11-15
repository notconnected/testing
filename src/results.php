<!DOCTYPE html>
<html>
    <head>
        <title>Эмулятор тестирования</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <style>
            table td, table th {
                border: 1px solid #ccc;
                padding: 5px;
                margin: 0px;
            }
            
            table {
                border-collapse: collapse;
                margin: 20px;
            }
        </style>
    </head>
    <body>
        <h1>Результаты тестирования</h1>
        <a href="/">Назад</a>
        <?php
        error_reporting(0);
        use Notconnected\Testing;
        include dirname(__FILE__).'/../vendor/autoload.php';

        $test = new Testing\Controller();

        $res = $test->getTestResult();
        ?>
        <table>
            <thead>
                <tr>
                    <th>№</th><th>IQ</th><th>Сложность</th><th>Результат</th>
                    <?php
                        foreach ($res as $k => $v) {
                            $user_iq = (int) $test->getUserById($v->user_id)->user_iq;
                            $testing_stat_max_complexity = (int) $v->testing_stat_max_complexity;
                            $testing_stat_result = (int) $v->testing_stat_result;
                            $testing_stat_min_complexity = (int) $v->testing_stat_min_complexity;
                            echo "<tr><td>",$k,"</td><td>", $user_iq,"</td><td>", $testing_stat_min_complexity,
                                    "...", $testing_stat_max_complexity, "</td><td>", $testing_stat_result," из 40</td></tr>";
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </body>
</html>