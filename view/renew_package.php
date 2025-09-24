<?php 
    $title = "Dorthpro | Software Renewal";
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    session_start();
     $get_company = new selects();
    $rows = $get_company->fetch_details('companies');
    foreach($rows as $row){
        $company = $row->company_id;
        $company_name = $row->company;
        $amount = $row->amount;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Invoicing management, Accounting, Asset manager, trial balance, income statement, cash flow, balance sheet, accounting software">
    <meta name="description" content="An online/offline Invoicing and Accounting Management system for manageing invoices, asset management, vendors, customer payments, as well complete accounting information">
	<meta name="author" content="Onostar Media"/>
	<meta name="og:url" property="og:url" content="https://">
    <meta name="og:type "property="og:type" content="website">
    <meta name="og:title" property="og:title" content="" />
    <meta name="og:site_name" property="og:site_name" content="" />
    <meta name="og:description" property="og:description" content="Invoicing management, Accounting, Asset manager, trial balance, income statement, cash flow, balance sheet, accounting software">
    <meta name="og:image" property="og:image" itemprop="image" content="images/icon.png">
    <meta property="og:image:width" content="300" />
    <meta property="og:image:height" content="300" />
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/png" size="32x32" href="../images/icon.png">
    <link rel="stylesheet" href="../fontawesome-free-6.0.0-web/css/all.css">
    <link rel="stylesheet" href="../fontawesome-free-6.0.0-web/css/all.min.css">
    <link rel="stylesheet" href="../fontawesome-free-5.15.1-web/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../select2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<style>
    .invest_form{
        width:50%;
    }
    h2{
        font-size:1.2rem;
    }
    h3{
        font-size:1rem;
    }
    .logos img{
        width:40vh;
        border-radius:0;
        padding:20px;
    }
    .add_user_form{
        width:50%;
        margin:10px auto;
        padding:20px;
    }
    .add_user_form .inputs{
        gap:.5rem
    }
    .add_user_form .inputs .data{
        width:48%!important;
        margin:5px 0;
    }
    .add_user_form label{
        font-size:.9rem!important;
    }
    @media screen and (max-width:650px){
        .invest_form{
            width:100%;
        }
        .add_user_form{
            width:95%;
            padding:10px;
        }
        .add_user_form .inputs .data input{
            width:100%!important;
            margin:0!important;
        }
        
    }

</style>
<body>
    
    <main>
        <section id="investment" style="align-items:center;flex-direction:column;">
            
           <div class="logos">
           <a href="../index.php"><img src="../images/dorthpro1.png" alt="logo"></a>
           </div>
           <?php
                    if(isset($_SESSION['success'])){
                        echo "<p class='success succeed' style='color:green'>" . $_SESSION['success']. "</p>
                        <script>
                            setTimeout(function(){
                                $('.succeed').hide();
                            }, 5000);
                        </script>
                        ";
                        unset($_SESSION['success']);
                    }
                ?>
                
                <?php
                    if(isset($_SESSION['error'])){
                        echo "<p class='error succeed'>" . $_SESSION['error']. "</p>
                        <script>
                            setTimeout(function(){
                                $('.succeed').hide();
                            }, 5000)
                        </script>";
                        unset($_SESSION['error']);
                    }
                ?>
            <div class="add_user_form">
                <form>
                    <h2 style="color:var(--secondaryColor);text-transform:uppercase;text-align:center; font-size:1.1rem"><?php echo $company_name?></h2>
                    <h3 style="text-align:center!important; background:var(--tertiaryColor)">Update Service Package</h3>
                    <div class="inputs">
                        <div class="data">
                            <label for="">Email Address</label>
                            <input type="email" name="email_add" id="email_add" required>
                        </div>
                        <div class="data">
                            <label for="package">Package</label>
                            <input type="text" name="package" id="package" value="Dorthpro Enterprise Solution" readonly>
                        </div>
                        <div class="data">
                            <label for="duration">Duration</label>
                            <input type="text" name="duration" id="duration" value="1 Year" readonly>
                        </div>
                        <div class="data">
                            <label for="fee">Package Fee</label>
                            <input type="text" value="<?php echo "₦".number_format($amount, 2)?>" readonly>
                            <input type="hidden" name="fee" id="fee" value="<?php echo $amount?>" readonly>
                        </div>
                        <div class="data">
                            <label for="amount">Processing Fee</label>
                            <?php
                                $processing_fee = 2000; // processing fee
                            ?>
                            <input type="text" value="<?php echo "₦".number_format($processing_fee, 2)?>" readonly>
                            <input type="hidden" name="processing" id="processing" value="<?php echo $processing_fee?>" readonly>
                        </div>
                        <div class="data">
                            <label for="amount">Total Due</label>
                            <?php
                                $total_due = $processing_fee + $amount; // 3% processing fee
                            ?>
                            <input type="text" value="<?php echo "₦".number_format($total_due, 2)?>" readonly>
                            <input type="hidden" name="total_due" id="total_due" value="<?php echo $total_due?>">
                        </div>
                        <div class="data" style="width:100%!important; text-align:center">
                            <input type="hidden" name="company" id="company" value="<?php echo $company?>">
                            <?php /* if($role == "Admin"){ */?>
                            <button type="button" style="background:var(--tertiaryColor)!important" onclick="renewPackage()">Continue to payment <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                
                    <!-- <div class="data">
                        <p style="text-align:center">Kindy Contact your Admin to update your service package</p>
                    </div> -->
                    <?php /* } */?>
                </form>
                
            </div>
        </section>
        
    </main>
    <footer>
        <section class="mainFooter">
            <section class="mainFooter1">
                <div class="contactAddress">
                    <div class="address phone">
                        <i class="fas fa-tty"></i>
                        <div class="addtext">
                            <h4>Help Lines: </h4>
                            <p>+2347068897068</p>
                        </div>
                    </div>
                    <div class="address email">
                        <i class="fas fa-envelope-open-text"></i>
                        <div class="addtext">
                            <h4>Email:</h4>
                            <p>info@dorthpro.com, contact@dorthpro.com</p>
                        </div>
                    </div>
                    <div class="socialMedia">
                        <h3>Follow us on social media</h3>
                        <div class="socialLinks">
                            <a target="_blank" href="https://facebook.com/onostarmedia" title="Follow Onostar Media on facebook"><i class="fab fa-facebook-square"></i></a>
                            <a target="_blank" href="https://twitter.com/onostarmedia" title="Follow Onostar Media on twitter"><i class="fab fa-twitter-square"></i></a>
                            <a target="_blank" href="https://instagram.com/onostarmedia" title="Follow Onostar Media on instagram"><i class="fab fa-instagram-square"></i></a>
                            <a target="_blank" href="https://www.linkedin.com/company/onostar-media" title="Follow Onostar media on Linkedin"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="socialMedia_workHours">
                    <div class="workingHours">
                        <h3>Working hours</h3>
                        <div class="hours">
                            <p>We are Online 24/7 to take your complaint and support you</p>
                        </div>
                    </div> 
                    <div class="category">
                        <h3>Quick Links</h3>
                        <div class="categories">
                            <li><a href="about.php">About us</a></li>
                            <!-- <li><a href="#">Career Opportunities</a></li>
                            <li><a href="#"></a></li> -->
                            <li><a href="onostarmedia.dorthpro.com#core_service">Other Products</a></li>
                            <li><a href="https://wa.me/+2347068897068" target="_blank">Contact</a></li>
                            <li><a href="tutorials.php" target="_blank">Training</a></li>
                            
                            
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <section class="secondaryFooter">
            <p>&copy;<?php echo date("Y")?> Onostar Media. All Rights Reserved.</p>
        </section>
    </footer>
    
    
    
    <script src="../jquery.js"></script>
    <script src="../script.js"></script>
    <!-- <script src="https://dropin-sandbox.vpay.africa/dropin/v1/initialise.js"></script> -->
    <script src="https://dropin.vpay.africa/dropin/v1/initialise.js"></script>
</body>
</html>
