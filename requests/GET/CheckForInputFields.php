<?php


if(empty($_GET['action'])) {
    die();
}

$pdo = new PDO("mysql:host=localhost;dbname=jrk", "root", "");

$stmt = $pdo->prepare("SELECT a.name AS action_name, a.min_needed_helper, a.running, f.inputfield, f.field_description FROM actions AS a
                    LEFT JOIN fields AS f ON a.id = f.action_id
                    LEFT JOIN input_types AS it ON f.inputfield = it.id
                    WHERE a.id = ? AND a.running = ?");

$stmt->bindValue(1, $_GET['action']);
$stmt->bindValue(2, 1);

$stmt->execute();

$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$actionInformation = ["action_name" => $actions[0]['action_name'], "min_needed_helper" => $actions[0]['min_needed_helper']];

foreach ($actions as $key => $action) {
    $actionInformation['formular'][] = '
        <label for="'.$action["field_description"].'">
            <p>'.$action["field_description"].'</p>
                '.$action["inputfield"].'
        </label>
    ';
}

header('Content-type: application/json');
echo json_encode($actionInformation);
//echo '<pre>';
//var_dump($actionInformation);
?>


