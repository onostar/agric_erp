<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
    $store = $_SESSION['store_id'];
    $posted = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    if(isset($_GET['document_id'])){
    //     $id = $_GET['id'];
        $document = $_GET['document_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        include "../classes/delete.php";
        include "../classes/inserts.php";

        //get item details
        $get_details = new selects();
        $rows = $get_details->fetch_details_cond('document_uploads', 'document_id', $document);
        foreach($rows as $row){
            $customer = $row->customer;
            $doc_type = $row->doc_type;
            $title = $row->title;
            $doc = $row->document;
            $upload_date = $row->upload_date;
            $uploaded_by = $row->uploaded_by;
        }
       

        //data to insert into document delete trail
        $audit_data = array(
            'document' => $doc,
            'title' => $title,
            'doc_type' => $doc_type,
            'customer' => $customer,
            'uploaded_by' => $uploaded_by,
            'upload_date' => $upload_date,
            'delete_date' => $date,
            'deleted_by' => $posted
        );
        
        $inser_trail = new add_data('document_delete_trail', $audit_data);
        $inser_trail->create_data();

        //delete document upload record
        $delete = new deletes();
        $delete->delete_item('document_uploads', 'document_id', $document);
        if($delete){
            echo "<div class='success'><p>Document deleted successfully</p></div>";
            
            }            
        }
    
    }else{
        echo "<p class='no_result'>Session expired. Please login again</p>";
    }
?>