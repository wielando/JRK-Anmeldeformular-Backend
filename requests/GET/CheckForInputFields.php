<?php


if(empty($_GET['action'])) {
    die();
}

$pdo = new PDO("mysql:host=localhost;dbname=jrk", "root", "");

$stmt = $pdo->prepare("SELECT a.name AS action_name, a.min_needed_helper, a.running, f.inputfield, f.field_description, f.input_requirements, vt.name AS value_type_name FROM actions AS a
                    LEFT JOIN fields AS f ON a.id = f.action_id
                    LEFT JOIN input_types AS it ON f.inputfield = it.id      
                    LEFT JOIN value_types AS vt ON f.value_type = vt.id
                    WHERE a.id = ? AND a.running = ?");

$stmt->bindValue(1, $_GET['action']);
$stmt->bindValue(2, 1);

$stmt->execute();

$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$actionInformation = ["action_name" => $actions[0]['action_name'], "min_needed_helper" => $actions[0]['min_needed_helper']];

$currentCount = 1;
$numItems = 0;

foreach ($actions as $key => $action) {
    $numItems = count($actions);
    $actionInformation[$key]["input"]["formular"] = '
        <label for="'.$action["field_description"].'">
            <p>'.$action["field_description"].'</p>
                '.$action["inputfield"].'
        </label>
    ';

    $actionInformation[$key]["ruleset"]["input_requirements"] = $action["input_requirements"];
    $actionInformation[$key]["ruleset"]["value_type_name"] = $action["value_type_name"];

    if($currentCount == $numItems) {
        $keyNumber = $currentCount + 1;
        $actionInformation[$keyNumber]["input"]["formular"] = '<input type="submit" id="PostFormular" name="PostFormular" />';
    } else {

        $currentCount++;
    }

}

header('Content-type: application/json');
echo json_encode($actionInformation);
//echo '<pre>';
//var_dump($actionInformation);
?>


