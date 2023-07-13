<?php

class Master{
    public $conn;
    function __construct(){
        $this->conn = new mysqli("localhost", "root", "", "notes_db");
        if(!$this->conn){
            throw new ErrorException("Database Connection failed.");
            exit;
        }
    }

    function save_note(){
        foreach($_POST as $k => $v){
            if(!empty($v) && !is_numeric($v) && !is_null($v))
            $_POST[$k] = $this->conn->real_escape_string(addslashes($v));
        }
        extract($_POST);
        if(empty($id)){
            $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('{$title}', '{$description}')";
        }else{
            $sql = "UPDATE `notes` set `title` = '{$title}', `description` = '{$description}' where `id` = '{$id}'";
        }
        $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
            $_SESSION['message']['success'] = "New Note has been added successfully";
            else
            $_SESSION['message']['success'] = "Note Details has been updated successfully";

        }else{
            $resp['status'] = 'error';
            $resp['error'] = 'Saving data failed. Error: '. $this->conn->error;
        }

        return json_encode($resp);
    }
    function list_notes(){
        $sql = "SELECT * FROM `notes` order by abs(`pinned`) desc, abs(unix_timestamp(`created_at`)) asc ";
        $qry = $this->conn->query($sql);
        return $qry->fetch_all(MYSQLI_ASSOC);
    }
    function get_note_details($id = ""){
        if(!empty($id) && is_numeric($id)){
            $sql = "SELECT * FROM `notes` where `id` = '{$id}'";
            $get = $this->conn->query($sql);
            if($get->num_rows > 0){
                return $get->fetch_assoc();
            }
        }
        return false;
    }

    function get_note(){
        extract($_POST);
        $note = $this->get_note_details($id);
        if(!$note){
            throw new ErrorException("Invalid Note ID. Given ID = {$id}");
            return;
        }else{
            $note['created_at'] = date("Y-m-d g:i A", strtotime($note['created_at']));
            return json_encode($note);
        }
    }

    function delete_note(){
        extract($_POST);
        if(!isset($id)){
            $resp['status'] = 'error';
            $resp['error'] = "Invalid Note ID";
        }else{
            $sql = "DELETE FROM `notes` where `id` = '{$id}'";
            $delete = $this->conn->query($sql);
            if($delete){
                $resp['status'] = 'success';
                $_SESSION['message']['success'] = "Note Data has been deleted successfully.";
            }else{
                $resp['status'] = 'success';
                $resp['error'] = "Deleting Note Data failed. Error: ". $this->conn->error;
            }
        }
        return json_encode($resp);
    }
    function pin_note(){
        extract($_POST);
        $is_pinned = $pinned == 1 ? 0: 1;
        $sql = "UPDATE `notes` set `pinned` = '{$is_pinned}' where `id` = '{$id}'";
        $update  = $this->conn->query($sql);
        if($update){
            $resp['status'] = "success";
            if($is_pinned == 0){
                $_SESSION['message']['success'] = "Note has been unpinned.";
            }else{
                $_SESSION['message']['success'] = "Note has been pinned to top.";
            }
        }else{
            $resp['status'] = "error";
        }
        return json_encode($resp);
    }
    function __destruct(){
        if(isset($this->conn)){
            $this->conn->close();
        }
    }
}