<?php
require_once "bootstrap.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$action = request()->get('action');
$task_id = request()->get('task_id');
$task = request()->get('task');
$status = request()->get('status');

$url="../task_list.php";
if (request()->get('filter')) {
    $url.="?filter=".request()->get('filter');
}

switch ($action) {
case "add":
    if (empty($task)) {
        $session->getFlashBag()->add('error', 'Please enter a task');
    } else {
        /*if (createTask(['task'=>$task, 'status'=>$status])) {
            $session->getFlashBag()->add('success', 'New Task Added');
        }*/
		try {
			createTask(['task'=>$task, 'status'=>$status]);
			$session->getFlashBag()->add('success', 'New Task Added');
		} catch (\Exception $e) {
			$session->getFlashBag()->add('error', 'Cannot add task without being logged in');
			redirect('/login.php');
		}
    }
    break;
case "update":
    $data = ['task_id'=>$task_id, 'task'=>$task, 'status'=>$status];
    if (updateTask($data)) {
        $session->getFlashBag()->add('success', 'Task Updated');
    } else {
        $session->getFlashBag()->add('error', 'Could NOT update task');
    }
    break;
case "status":
    if (updateStatus(['task_id'=>$task_id, 'status'=>$status])) {
        if ($status == 1) $session->getFlashBag()->add('success', 'Task Complete');
    }
    break;
case "delete":
    if (deleteTask($task_id)) {
        $session->getFlashBag()->add('success', 'Task Deleted');
    } else {
        $session->getFlashBag()->add('error', 'Could NOT Delete Task');
    }
    break;
}
header("Location: ".$url);