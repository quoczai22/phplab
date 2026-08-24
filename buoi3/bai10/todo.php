<?php
$file = 'tasks.json';
$tasks = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
if (!is_array($tasks)) $tasks = [];

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

if ($action == 'add' && !empty($_POST['text'])) {
    $tasks[] = [
        'id' => (string)time() . rand(100, 999),
        'text' => $_POST['text'],
        'completed' => false
    ];
    file_put_contents($file, json_encode($tasks));
}

if ($action == 'toggle' && isset($_POST['id'])) {
    foreach ($tasks as &$t) {
        if ($t['id'] == $_POST['id']) {
            $t['completed'] = !$t['completed'];
            break;
        }
    }
    file_put_contents($file, json_encode($tasks));
}

if ($action == 'delete' && isset($_POST['id'])) {
    $tasks = array_values(array_filter($tasks, fn($t) => $t['id'] != $_POST['id']));
    file_put_contents($file, json_encode($tasks));
}

header('Content-Type: application/json');
echo json_encode($tasks);
?>
