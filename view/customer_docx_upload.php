<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_GET['customer'])){
        $customer_id = htmlspecialchars(stripslashes($_GET['customer']));
    //get customer details
    $get_customer = new selects();
    $cus = $get_customer->fetch_details_group('customers', 'customer', 'customer_id', $customer_id);
    $client = $cus->customer;
?>
<div id="add_room" class="displays">
    <a style="border-radius:15px; background:brown;color:#fff;padding:8px; margin:10px 0!important; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="showPage('client_document_upload.php')"><i class="fas fa-angle-double-left"></i> Return</a>
     <div class="info" style="width:60%; margin:10px;"></div>
    <div class="add_user_form" style="width:60%; margin:10px; box-shadow:none;background:transparent">
        <h3 style="background:var(--tertiaryColor)!important">Upload Document for <?php echo $client?></h3>
        <div class="inputs" style="margin-top:10px; gap:1rem; display:flex; flex-wrap:wrap; justify-content:left;align-items:center">
            <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer_id?>">
            <div class="data" style="width:48%">
                <label for="doc_type">Document Type</label>
                <select name="doc_type" id="doc_type" required>
                    <option value="" selected disabled>Select Document type</option>
                    <option value="Certificate of Occupany">Certificate of Occupany</option>
                    <option value="Survey Plan">Survey Plan</option>
                    <option value="Deed of Assignment">Deed of Assignment</option>
                    <option value="Land Purchase Agreement">Land Purchase Agreement</option>
                    <option value="Farm Management Contract">Farm Management Contract</option>
                    <option value="Investment Contract">Investment Contract</option>
                    <option value="Others">Others</option>
                   
                </select>
            </div>
            <div class="data" style="width:48%">
                <label for="title">Document Title</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div class="data" style="width:48%">
                <label for="document_upload">Upload Document (Max 5MB)</label>
                <input type="file" name="document_upload" id="document_upload" required>
            </div>
            <div class="data">
                <button type="button" style="border-radius:10px; padding:8px; border:1px solid #fff; box-shadow:1px 1px 1px #222;background:green;color:#fff" onclick="uploadDocument()">Upload Document <i class="fas fa-file-upload"></i></button>
            </div>
        </div>
    </form>
    </div>
</div>
<?php
       
    }
    }else{
        echo "<p class='exist'>Your session has expired. You are not logged in</p>";
        header("Location: ../index.php");
    }
?>