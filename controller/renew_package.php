<?php
    date_default_timezone_set("Africa/Lagos");

    $fee = htmlspecialchars(stripslashes($_POST['fee']));
    $package = htmlspecialchars(stripslashes($_POST['package']));
    $processing = htmlspecialchars(stripslashes($_POST['processing']));
    $total_due = htmlspecialchars(stripslashes($_POST['total_due']));
    $email = htmlspecialchars(stripslashes($_POST['email_add']));
    // $user = htmlspecialchars(stripslashes($_POST['user']));
    $company = htmlspecialchars(stripslashes($_POST['company']));
    $trx_number = htmlspecialchars(stripslashes($_POST['transNum']));
    $date = date("Y-m-d H:i:s");
    $current_date = date("Y-m-d");
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    //get company create date
    $get_company = new selects();
    $rows = $get_company->fetch_details_cond('companies', 'company_id', $company);
    if(is_array($rows)){
        foreach($rows as $row){
            $reg_date = $row->date_created;
        }
    }
    $new_due_date = date("Y-m-d", strtotime("+365 days", strtotime($reg_date)));

    $data = array(
        'company' => $company,
        'package' => $package,
        'amount' => $fee,
        'processing_fee' => $processing,
        'total_due' => $total_due,
        'previous_date' => $reg_date,
        'new_due_date' => $new_due_date,
        'email_address' => $email,
        'trx_number' => $trx_number,
        // 'renewed_by' => $user,
        'renew_date' => $date
    );

    
    //get due date
    /* $get_det = new selects();
    $pkgs = $get_det->fetch_details_group('companies', 'due_date', 'company_id', $company);
    $due_date = $pkgs->due_date; */

    //update company package
    $update = new Update_table();
    $update->update('companies', 'date_created', 'company_id', $new_due_date, $company);
    if($update){
        //insert into renewal table
        $add_data = new add_data('renewals', $data);
        $add_data->create_data();
    }

    ?>
