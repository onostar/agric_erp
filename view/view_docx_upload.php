<?php
    session_start();
    // $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
       $store = $_SESSION['store_id'];
       if(isset($_GET['customer'])){
            $customer_id = htmlspecialchars(stripslashes($_GET['customer']));
            //get customer details
            $get_details = new selects();
            $customer_details = $get_details->fetch_details_cond('customers', 'customer_id', $customer_id);
            foreach($customer_details as $customer_detail){
                $client_name = $customer_detail->customer;
            }
       
    

?>
<style>
    table td{
        font-size:.75rem!important;
        /* padding:2px!important; */
    }
    
</style>
<div id="revenueReport" class="displays management" style="margin:0!important;width:100%!important">
    
<div class="displays allResults new_data" id="revenue_report">
    <h2>Documents uploaded for <?php echo $client_name?></h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'DOcument uploads for <?php echo $client_name?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Doc. Type</td>
                <td>Title</td>
                <td>Uploaded By</td>
                <td>Date</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $details = $get_details->fetch_details_cond('document_uploads', 'customer', $customer_id);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php
                        echo $detail->doc_type;
                    ?>
                </td>
                <td>
                    <?php
                        echo $detail->title;
                    ?>
                </td>
                <td>
                    <?php
                        //get uploader
                        $users = $get_details->fetch_details_group('users', 'full_name', 'user_id', $detail->uploaded_by);
                        echo $users->full_name;
                    ?>
                
                <td>
                    <?php echo date("d-M-Y, h:ia", strtotime($detail->upload_date))?>
                </td>
                <td>
                    <a href="../documents/<?php echo $detail->document?>" target=" _blank"style="color:#fff; background:var(--otherColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="view Document">View <i class="fas fa-eye"></i></a>
                    
                    
                </td>
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    ?>
       
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>
<?php 
       }else{
        echo "<p class='no_result'>No customer selected. Please select a customer to view uploaded documents</p>";
       }
    }else{
        echo "<p class='no_result'>Session expired. Please login again</p>";
    }
    ?>