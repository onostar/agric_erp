<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    $store = $_SESSION['store_id'];
    // $month = htmlspecialchars(stripslashes($_POST['fin_month']));
    $month = date("m");
    $year = htmlspecialchars(stripslashes($_POST['fin_year']));
    $fin_date = $year."-".$month."-01";
    $prev_year = intval($year) - 1;
    $prev_date = $prev_year."-".$month."-01";
    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    /* $get_revenue = new selects();
    $details = $get_revenue->fetch_monthly_fin_pos($year, $to);
    $n = 1; */
?>
<div class="search" style="display:flex;align-items:flex-end">
    <h2 style="text-align:center; color:green">Statement Of Financial Position as at the end of <?php echo  date("Y", strtotime($fin_date))?></h2>
    <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Statement Of Financial Position as at the end of <?php echo date('Y', strtotime($fin_date))?>')" title="Download to excel"><i class="fas fa-file-excel"></i></a>
</div>

<hr>
<div class="statements">
    <!-- <h2>ASSETS</h2> -->
        <?php
            $group = 1;
        ?>
    <div class="sub_statements">
        
        <table id="data_table" class="searchTable">
            <thead>
                <tr style="background:var(--primaryColor)">
                    <td>Account No.</td>
                    <td>Account Name</td>
                    <td><?php echo $year?> (₦)</td>
                    <td>Previous Year <?php echo $prev_year?> (₦)</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" style="color:var(--secondaryColor);text-align:left;text-transform:uppercase; font-weight:bold">Assets</td>
                </tr>
                <tr>
                    
                    <td colspan="4" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Non-Current Assets</td>
                </tr>
                <tr id="fixed_asset">
                    <td style="color:#222; text-align:left;">1020</td>
                    <td style="color:#222; text-align:left; cursor:pointer" onclick="toggleFixedAsset()">PROPERTY & EQUIPMENT</td>
                    <td>
                        <?php
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', 2, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td >
                        <?php
                            //fetch total for previous month by su group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', 2, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                <?php
                    
                        $sub_group = 2;
                
                    // $n = 1;
                    $get_users = new selects();
                    $details = $get_users->fetch_details_cond('ledgers', 'sub_group', $sub_group);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr class="fixed">
                    <td style="color:#222; text-align:left">
                        <?php echo $detail->acn;?>
                    </td>
                    <td style="cursor:pointer"onclick="yearlyAccountStatement('<?php echo $detail->acn?>', '<?php echo $year?>')"><?php echo $detail->ledger?></td>
                    
                    <td>
                        <?php
                            //fetch selected month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            //fetch previous month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                
                <?php endforeach;}?>
                <tr>
                    <td></td>
                    <td style="text-decoration:underline;font-weight:bold">Total</td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for current month by sub group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for previous month by su group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Current Assets</td>
                </tr>
                <tr id="cash_equiv">
                    <td style="color:#222; text-align:left;">10102</td>
                    <td style="color:#222; text-align:left; cursor:pointer" onclick="toggleCash()">CASH AND CASH EQUIVALENTS</td>
                    <td>
                        <?php
                            //get all cash and bank
                            $get_cash = new selects();
                            $cashs = $get_cash->fetch_details_eitherCon('ledgers', 'class', 1, 2);
                            if(gettype($cashs) == 'array'){
                                $total = 0;
                                foreach($cashs as $csh){
                                    $get_psts = new selects();
                                    $psts = $get_psts->fetch_yearly_pos('account', $csh->acn, $year);
                                    if(gettype($psts) == 'array'){
                                        foreach($psts as $pst){
                                            $debits = $pst->debits;
                                            $credits = $pst->credits;
                                            $balance = $debits - $credits;
                                        }
                                    }
                                    if(gettype($psts) == 'strinig'){
                                        $balance = 0;
                                    }
                                    $total += $balance;
                                }
                                echo number_format($total, 2);
                            }
                        ?>
                    </td>
                   
                    <td>
                        <?php
                            //fetch previous month financial position
                            //get all cash and bank
                            $get_cash = new selects();
                            $cashs = $get_cash->fetch_details_eitherCon('ledgers', 'class', 1, 2);
                            if(gettype($cashs) == 'array'){
                                $total = 0;
                                foreach($cashs as $csh){
                                    $get_psts = new selects();
                                    $psts = $get_psts->fetch_yearly_pos('account', $csh->acn, $prev_year);
                                    if(gettype($psts) == 'array'){
                                        foreach($psts as $pst){
                                            $debits = $pst->debits;
                                            $credits = $pst->credits;
                                            $balance = $debits - $credits;
                                        }
                                    }
                                    if(gettype($psts) == 'strinig'){
                                        $balance = 0;
                                    }
                                    $total += $balance;
                                }
                                echo number_format($total, 2);
                            }
                        ?>
                    </td>
                </tr>
                <!-- all cash and bank -->
                    <?php
                        //get cash and bank
                        $get_users = new selects();
                        $details = $get_users->fetch_details_eitherCon('ledgers', 'class', 1, 2);
                        if(gettype($details) === 'array'){
                        foreach($details as $detail):
                    ?>
                <tr class="cash_bank">
                    
                    <td style="color:#222; text-align:left">
                        <?php echo $detail->acn;?>
                    </td>
                    <td style="cursor:pointer"onclick="yearlyAccountStatement('<?php echo $detail->acn?>', '<?php echo $year?>')"><?php echo $detail->ledger?></td>
                    
                    <td>
                        <?php
                            //fetch selected month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn,$year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            //fetch previous month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach;}?>
                <!-- GET ONLY RECEVIABLES -->
                <tr id="receiveables">
                    <td style="color:#222; text-align:left;">10104</td>
                    <td style="color:#222; text-align:left; cursor:pointer" onclick="toggleReceivables()">ACCOUNT RECEIVABLES</td>
                    <td>
                        <?php
                            //get all receivables
                            $get_rec = new selects();
                            $recs = $get_rec->fetch_details_cond('ledgers', 'class', 4);
                            if(gettype($recs) == 'array'){
                                $total = 0;
                                foreach($recs as $rec){
                                    $get_psts = new selects();
                                    $psts = $get_psts->fetch_yearly_pos('account', $rec->acn, $year);
                                    if(gettype($psts) == 'array'){
                                        foreach($psts as $pst){
                                            $debits = $pst->debits;
                                            $credits = $pst->credits;
                                            $balance = $debits - $credits;
                                        }
                                    }
                                    if(gettype($psts) == 'strinig'){
                                        $balance = 0;
                                    }
                                    $total += $balance;
                                }
                                echo number_format($total, 2);
                            }
                        ?>
                    </td>
                   
                    <td>
                        <?php
                            //fetch previous month financial position
                            //get all cash and bank
                            $get_rec = new selects();
                            $recs = $get_rec->fetch_details_cond('ledgers', 'class', 4);
                            if(gettype($recs) == 'array'){
                                $total = 0;
                                foreach($recs as $rec){
                                    $get_psts = new selects();
                                    $psts = $get_psts->fetch_yearly_pos('account', $rec->acn, $prev_year);
                                    if(gettype($psts) == 'array'){
                                        foreach($psts as $pst){
                                            $debits = $pst->debits;
                                            $credits = $pst->credits;
                                            $balance = $debits - $credits;
                                        }
                                    }
                                    if(gettype($psts) == 'strinig'){
                                        $balance = 0;
                                    }
                                    $total += $balance;
                                }
                                echo number_format($total, 2);
                            }
                        ?>
                    </td>
                </tr>
                <!-- all receivables -->
                    <?php
                        //get receivables
                        $get_users = new selects();
                        $details = $get_users->fetch_details_cond('ledgers', 'class', 4);
                        if(gettype($details) === 'array'){
                        foreach($details as $detail):
                    ?>
                <tr class="trade_rec">
                    
                    <td style="color:#222; text-align:left">
                        <?php echo $detail->acn;?>
                    </td>
                    <td style="cursor:pointer"onclick="yearlyAccountStatement('<?php echo $detail->acn?>', '<?php echo $year?>')"><?php echo $detail->ledger?></td>
                    
                    <td>
                        <?php
                            //fetch selected month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            //fetch previous month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach;}?>
                <?php
                    
                        $sub_group = 1;
                    $get_users = new selects();
                    $details = $get_users->fetch_details_neitherCon('ledgers', 'sub_group', $sub_group, 'class', 1, 2, 4);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr>
                    <td style="color:#222; text-align:left">
                        <?php echo $detail->acn;?>
                    </td>
                    <td style="cursor:pointer"onclick="yearlyAccountStatement('<?php echo $detail->acn?>', '<?php echo $year?>')"><?php echo $detail->ledger?></td>
                    
                    <td>
                        <?php
                            //fetch selected month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            //fetch previous month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                
                <?php endforeach;}?>
                <tr>
                    <td></td>
                    <td style="text-decoration:underline;font-weight:bold">Total</td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for current month by sub group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for previous month by su group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2" style="color:#222;text-align:left;text-transform:uppercase; font-weight:bold">Total Assets</td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for current month by sub group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account_type', $group, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for previous month by su group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account_type', $group, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                $balance = $debit - $credit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
            
                <tr>
                    <?php
                        $group = 2;
                        $group2 = 5;
                    ?>
                    <td colspan="4" style="color:var(--secondaryColor);text-align:left;text-transform:uppercase; font-weight:bold">FINANCED BY EQUITY & LIABILITIES</td>
                </tr>
                <tr>
                    
                    <td colspan="4" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Equity</td>
                </tr>
                <?php
                    
                        $sub_group = 9;
                
                    // $n = 1;
                    $get_users = new selects();
                    $details = $get_users->fetch_details_cond('ledgers', 'sub_group', $sub_group);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr>
                    <td style="color:#222; text-align:left">
                        <?php echo $detail->acn;?>
                    </td>
                    <td style="cursor:pointer"onclick="yearlyAccountStatement('<?php echo $detail->acn?>', '<?php echo $year?>')"><?php echo $detail->ledger?></td>
                    
                    <td>
                        <?php
                            //fetch selected month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            //fetch previous month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                
                <?php endforeach;}?>
                <tr>
                    <td></td>
                    <td style="text-decoration:underline;font-weight:bold">Total</td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for current month by sub group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for previous month by su group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Current Liabilities</td>
                </tr>
                <tr>
                    <td style="color:#222; text-align:left;">2030</td>
                    <td style="color:#222; text-align:left; cursor:pointer" onclick="togglePayables()">ACCOUNT PAYABLES</td>
                    <td>
                        <?php
                            //fetch total for current month by sub group
                            $sub_group = 3;
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            //fetch total for previous month by su group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                <?php
                    
                        $sub_group = 3;
                    $get_users = new selects();
                    $details = $get_users->fetch_details_cond('ledgers', 'sub_group', $sub_group);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr class="payables">
                    <td style="color:#222; text-align:left">
                        <?php echo $detail->acn;?>
                    </td>
                    <td style="cursor:pointer"onclick="yearlyAccountStatement('<?php echo $detail->acn?>', '<?php echo $year?>')"><?php echo $detail->ledger?></td>
                    
                    <td>
                        <?php
                            //fetch selected month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            //fetch previous month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                
                <?php endforeach;}?>
                <tr>
                    <td></td>
                    <td style="text-decoration:underline;font-weight:bold">Total</td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for current month by sub group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for previous month by su group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Non-Current Liabilities</td>
                </tr>
                <tr>
                    <td style="color:#222; text-align:left;">2040</td>
                    <td style="color:#222; text-align:left; cursor:pointer" onclick="toggleLongDebts()">LONG TERM DEBTS</td>
                    <td>
                        <?php
                            //fetch total for current month by sub group
                            $sub_group = 4;
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            //fetch total for previous month by su group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                <?php
                    
                        $sub_group = 4;
                    $get_users = new selects();
                    $details = $get_users->fetch_details_cond('ledgers', 'sub_group', $sub_group);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr class="long_debts">
                    <td style="color:#222; text-align:left">
                        <?php echo $detail->acn;?>
                    </td>
                    <td style="cursor:pointer"onclick="yearlyAccountStatement('<?php echo $detail->acn?>', '<?php echo $year?>')"><?php echo $detail->ledger?></td>
                    
                    <td>
                        <?php
                            //fetch selected month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            //fetch previous month financial position
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account', $detail->acn, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
                
                <?php endforeach;}?>
                <tr>
                    <td></td>
                    <td style="text-decoration:underline;font-weight:bold">Total</td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for current month by sub group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for previous month by su group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('sub_group', $sub_group, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance = $debit - $credit;
                                $balance = $credit - $debit;
                                // echo $credit;
                                echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                        ?>
                    </td>
                </tr>
           
                <tr>
                    <td colspan="2" style="color:#222;text-align:left;text-transform:uppercase; font-weight:bold">Total Liabilities</td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for current month by sub group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account_type', $group, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance1 = $debit - $credit;
                                $balance1 = $credit - $debit;
                                // echo $credit;
                                // echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                // echo "0.00";
                                $balance1 = 0;
                            }
                           
                            echo number_format($balance1, 2);
                        ?>
                    </td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for current month by sub group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account_type', $group, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance1 = $debit - $credit;
                                $balance1 = $credit - $debit;
                                // echo $credit;
                                // echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                // echo "0.00";
                                $balance1 = 0;
                            }
                            
                            echo number_format($balance1, 2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="color:#222;text-align:left;text-transform:uppercase; font-weight:bold">Total Liabilities & Shareholder's equity</td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for current month by sub group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account_type', $group, $year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance1 = $debit - $credit;
                                $balance1 = $credit - $debit;
                                // echo $credit;
                                // echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                // echo "0.00";
                                $balance1 = 0;
                            }
                            //fetch total for current month by sub group
                            $get_eqs = new selects();
                            $eqs = $get_eqs->fetch_yearly_pos('account_type', $group2, $year);
                            if(gettype($eqs) == 'array'){
                                foreach($eqs as $eq){
                                    $eq_debit = $eq->debits;
                                    $eq_credit = $eq->credits;
                                }
                                // $balance2 = $eq_debit - $eq_credit;
                                $balance2 = $eq_credit - $eq_debit;
                                // echo $credit;
                                // echo number_format($balance,2);
                            }
                            if(gettype($eqs) == 'string'){
                               $balance2 = 0;
                            }
                            $balance = $balance1 + $balance2;
                            echo number_format($balance, 2);
                        ?>
                    </td>
                    <td style="text-decoration:underline;font-weight:bold">
                        <?php
                            //fetch total for current month by sub group
                            $get_pos = new selects();
                            $rows = $get_pos->fetch_yearly_pos('account_type', $group, $prev_year);
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $debit = $row->debits;
                                    $credit = $row->credits;
                                }
                                // $balance1 = $debit - $credit;
                                $balance1 = $credit - $debit;
                                // echo $credit;
                                // echo number_format($balance,2);
                            }
                            if(gettype($rows) == 'string'){
                                // echo "0.00";
                                $balance1 = 0;
                            }
                            //fetch total for current month by sub group
                            $get_eqs = new selects();
                            $eqs = $get_eqs->fetch_yearly_pos('account_type', $group2, $prev_year);
                            if(gettype($eqs) == 'array'){
                                foreach($eqs as $eq){
                                    $eq_debit = $eq->debits;
                                    $eq_credit = $eq->credits;
                                }
                                // $balance2 = $eq_debit - $eq_credit;
                                $balance2 = $eq_credit - $eq_debit;
                                // echo $credit;
                                // echo number_format($balance,2);
                            }
                            if(gettype($eqs) == 'string'){
                               $balance2 = 0;
                            }
                            $balance = $balance1 + $balance2;
                            echo number_format($balance, 2);
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>