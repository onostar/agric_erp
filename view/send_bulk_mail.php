<div id="mails" class="displays">
    <style>
        label{
            font-size:.8rem!important;
        }
    </style>
<?php
    session_start();
    if (isset($_SESSION['user_id'])){
        
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
            
    ?>
    
    <div class="add_user_form priceForm" style="margin:10px 20px; width:70%;">
        <h3 style="background:var(--tertiaryColor)">Send Bulk E-mail to Customers</h3>
        <form style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:1rem; justify-content:left">
                
                <div class="data" style="width:100%">
                    <label for="subject">Title</label>
                    <input type="text" name="subject" id="subject"  placeholder="Message subject">
                    
                </div>
                <div class="data" style="width:100%">
                    <label for="message">Message Body</label>
                    <textarea name="message" id="message" placeholder="Type your message here..." style="height:150px"></textarea>
                    
                </div>
               
                <div class="data" style="width:auto">
                    <button type="button" id="change_price" name="change_price" onclick="sendBulkEmail()">Send <i class="fas fa-mail-bulk"></i></button>
                    
                </div>
                
            </div>
        </form>   
    </div>
    
<?php
    
    }else{
        echo "<p class='exist'>Your session has expired. You are not logged in</p>";
        header("Location: ../index.php");
    }   
?>
</div>