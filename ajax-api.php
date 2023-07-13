<?php 
session_start();
require_once("Master.php");
$master = new Master();
$action = isset($_GET['action']) ? $_GET['action'] : "";
switch($action){
    case 'save_note':
        echo $master->save_note();
        break;
    case 'pin_note':
        echo $master->pin_note();
        break;
    case 'delete_note':
        echo $master->delete_note();
        break;
    case 'get_note':
        echo $master->get_note();
        break;
    default:
        echo json_encode(['status' => "error", "error" => "Invalid Ajax Request."]);
        break;
}
?>