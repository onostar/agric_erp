<?php

    $group = htmlspecialchars(stripslashes($_POST['group']));
    $sub_group = htmlspecialchars(stripslashes($_POST['sub_group']));
    $class = htmlspecialchars(stripslashes($_POST['account_class']));
    $ledger = strtoupper(htmlspecialchars(stripslashes($_POST['ledger'])));
    $acn = $group."0".$sub_group."0".$class;
    $data = array(
        'account_group' => $group,
        'sub_group' => $sub_group,
        'class' => $class,
        'ledger' => $ledger,
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";

    //check if class exists
    $check = new selects();
    $results = $check->fetch_count_3cond('ledgers', 'sub_group', $sub_group, 'class', $class, 'ledger', $ledger);
    if($results > 0){
        echo "<p class='exist'>$ledger already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('ledgers', $data);
        $add_data->create_data();
        //update account number
        $get_id = new selects();
        $ids = $get_id->fetch_lastInserted('ledgers', 'ledger_id');
        $id = $ids->ledger_id;
        $new_acn = $acn.$id;
        $update_acn = new Update_table();
        $update_acn->update('ledgers', 'acn', 'ledger_id', $new_acn, $id);
        if($add_data){
            //check if class is a bank
            //ge class name
            $get_class = new selects();
            $cls = $get_class->fetch_details_group('account_class', 'class', 'class_id', $class);
            $class_name = $cls->class;
            if($class_name == "Bank"){
                //insert into bank
                $bank_data = array(
                    'bank' => $ledger,
                    'account_number' => $new_acn
                );
                $add_bank = new add_data('banks', $bank_data);
                $add_bank->create_data();
            }
            //check if account group is expenses
            $get_group = new selects();
            $grp = $get_group->fetch_details_group('account_groups', 'account_group', 'account_id', $group);
            $group_name = $grp->account_group;
            if($group_name == "Expenses"){
                //insert into bank
                $exp_data = array(
                    'expense_head' => $ledger,
                    'acn' => $new_acn,
                    'ledger_id' => $id
                );
                $add_exp = new add_data('expense_heads', $exp_data);
                $add_exp->create_data();
            }
            echo "<p>$ledger added</p>";
        }
    }
    