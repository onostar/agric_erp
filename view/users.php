<?php
date_default_timezone_set("Africa/Lagos");
    session_start();
    include "cache_control.php";
    include "../classes/dbh.php";
    include "../classes/select.php";

    if(isset($_SESSION['user'])){
        $username = $_SESSION['user'];
        // instantiate classes
        $fetch_user = new selects();
        $users = $fetch_user->fetch_details_cond('users', 'username', $username);
        foreach($users as $user){
            $fullname = $user->full_name;
            $role = $user->user_role;
            $user_id = $user->user_id;
            $store_id = $user->store;
        }
        //check if user is a staff and get details
        $stfs = $fetch_user->fetch_details_cond('staffs', 'user_id', $user_id);
        if(is_array($stfs) || is_object($stfs)){
            foreach($stfs as $stf){
                $staff_id = $stf->staff_id;
                $design = $stf->designation;
            }
            //get designation
            $desig = $fetch_user->fetch_details_group('designations', 'designation', 'designation_id', $design);
            $designation = $desig->designation;
        }else{
            $staff_id = 0;
            $designation = $role;
        }
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;

        /* get company */
        $fetch_comp = new selects();
        $comps = $fetch_comp->fetch_details('companies');
        foreach($comps as $com){
            $company = $com->company;
            $comp_id = $com->company_id;
            $logo = $com->logo;
            $date_created = $com->date_created;
        }
        $_SESSION['company_id'] = $comp_id;
        $_SESSION['company'] = $company;
        $_SESSION['company_logo'] = $logo;
        /* get store */
        $get_store = new selects();
        $strs = $get_store->fetch_details_cond('stores', 'store_id', $store_id);
        foreach($strs as $str){
            $store = $str->store;
            $store_address = $str->store_address;
            $phone = $str->phone_number;
        }
        $_SESSION['store_id'] = $store_id;
        $_SESSION['store'] = $store;
        $_SESSION['address'] = $store_address;
        $_SESSION['phone'] = $phone;
        function greeting($staff_name){
            $hour = date('H'); // Get the current hour in 24-hour format (00 to 23)
            if ($hour >= 0 && $hour < 12) {
                return "Good morning! <span style='font-weight:bold'>$staff_name</span>";
            } elseif ($hour >= 12 && $hour < 18) {
                return "Good afternoon! <span style='font-weight:bold'>$staff_name</span>";
            } else {
                return "Good evening! <span style='font-weight:bold'>$staff_name</span>";
            }
        }
    
        
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="keywords" content="Inventory system, point of sales, inventory and sales management, retail, supermarket software, sales application">
    <meta name="description" content="An online/offline inventory and sales management software for retail and wholesale stores and pharmacies, stock register, etc">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm ERP & Inventory Management</title>
    <link rel="icon" type="image/png" size="32x32" href="../images/icon.png">
    <link rel="stylesheet" href="../fontawesome-free-6.0.0-web/css/all.css">
    <link rel="stylesheet" href="../fontawesome-free-6.0.0-web/css/all.min.css">
    <link rel="stylesheet" href="../fontawesome-free-5.15.1-web/css/all.min.css">
    <link rel="stylesheet" href="../style.css?v=<?php echo APP_VERSION?>">
    <link rel="stylesheet" href="../select2.min.css">
</head>
<body>
   
    <main>
        <!-- show package soon to expire -->
    <?php
            //get date to shut down
            $reg_date = $date_created;
            $expiration = date("Y-m-d", strtotime("+1 year", strtotime($reg_date)));
            $current_date = date("Y-m-d");
            $interval = abs(strtotime($expiration) - strtotime($current_date));
            $days = $interval/86400;
           
                
            if($days <= 30){
        ?>
    <div class="about_expire">
        
        <marquee behavior="smooth" direction="left">
            <?php  echo "Heads up! Your software license will expire in <strong>$days</strong> day(s). Please renew soon to keep enjoying seamless access to our services.";?>
        </marquee>
    </div>
    <?php }?>
        <header>
            <div class="menu_icon" id="menu_icon">
                <a href="javascript:void(0)"><i class="fas fa-bars"></i></a>
            </div>
            <h1 class="logo for_mobile">
                <a href="users.php" title="Home">
                    <img src="../images/logo.png" alt="Logo" class="img-fluid">
                </a>
            </h1>
            <h2 style="margin-left:50px!important"><?php echo $store?></h2>
            <!-- <div class="other_menu">
                <a href="#" title="Our Gallery"><?php echo ucwords($designation);?></a>
            </div> -->
            <!-- <a href="#" title="current store" class="other_menu"><?php echo ucwords($store);?></a> -->

            <div class="login">
                
                <button id="loginDiv"><i class="far fa-user"></i> <?php echo ucwords($fullname);?> <i class="fas fa-chevron-down"></i><br><p><?php echo ucwords($designation);?></p></button>
                
                <div class="login_option">
                    <div>
                        <a class="password_link page_navs" href="javascript:void(0)" data-page="update_password" onclick="showPage('update_password.php')">Change password <i class="fas fa-key"></i></a>
                        <button id="loginBtn"><a href="../controller/logout.php">Log out <i class="fas fa-power-off"></i></a></button>
                    </div>
                </div>
            </div>
            
        </header>
        <div class="admin_main">
            
            <!-- side menu -->
            <?php include "side_menu.php"?>
            <!-- main contents -->
            <section id="contents">
                <!-- header -->
                
                <!-- quick links -->
                <div id="quickLinks">
                    <div class="quick_links">
                        <div class="links page_navs" onclick="showPage('wholesale.php')" title="Make a sales order">
                            <i class="fas fa-pen-alt"></i>
                            <!-- <p>Direct sales</p> -->
                        </div>
                        <div class="links page_navs" onclick="showPage('reached_reorder.php')" title="Reached reorder level">
                            <i class="fas fa-sort-amount-down"></i>
                            <p>
                                <?php
                                    $get_level = new selects();
                                    $levels = $get_level->fetch_lesser_cond('inventory',  'quantity', 'reorder_level', 'store', $store_id);
                                    echo $levels;
                                ?>
                            </p>
                        </div>
                        <div class="links page_navs" onclick="showPage('out_of_stock.php')" title="Out of stock">
                            <i class="fas fa-drum" style="color:red"></i>
                            <p style="color:red">
                                <?php
                                    $out_stock = new selects();
                                    $stock = $out_stock->fetch_count_2cond('inventory', 'quantity', 0, 'store', $store_id);
                                    echo $stock;
                                ?>
                            </p>
                        </div>
                        <div class="greetings">
                            <p>
                                <?php
                                    echo greeting($fullname);
                                ?>
                            </p>
                        </div>
                        <!-- check if user has checked in for the day -->
                        <?php
                            if($username != "Sysadmin"){
                                $check_attendance = $fetch_user->check_attendance($staff_id);
                                if($check_attendance == 0){
                            
                        ?>
                        <div class="attendance_alert">
                            <p><i class="fas fa-exclamation-triangle"></i> You have not marked your attendance for today. <button onclick="markAttendance(<?php echo $staff_id?>)">Check in <i class="fas fa-briefcase"></i></button></p>
                        </div>
                        <?php }
                        if($check_attendance == 1){
                            //check if user has checked out
                            $check_checkout = $fetch_user->check_checkout($staff_id);
                            if($check_checkout == 0){ 
                        ?>
                            <div class="attendance_alert">
                            <button style="background:brown" onclick="closeWork('<?php echo $staff_id?>')">Check out <i class="fas fa-door-open"></i></button>
                        </div>
                        <?php }}}?>
                    </div>
                    <?php
                        if($role == "Admin" || $role == "Inventory Officer" || $role == "Accountant" || $designation == "HR MANAGER"){
                    ?>
                    <div class="change_dashboard">
                        <!-- check other stores dashboard -->
                        <!-- <form method="POST"> -->
                        <section>
                            <label>Change Location</label><br>
                            <select name="store" id="store" required onchange="changeStore(this.value, <?php echo $user_id?>)">
                                <option value="<?php echo $store_id?>"><?php echo $store?></option>
                                <!-- get stores -->
                                <?php
                                    $get_store = new selects();
                                    $strs = $get_store->fetch_details_negCond1('stores', 'store_id', $store_id);
                                    foreach($strs as $str){
                                ?>
                                <option value="<?php echo $str->store_id?>"><?php echo $str->store?></option>
                                <?php }?>
                            </select>
                        </section>
                    </div>
                    <?php }?>
                </div>
                
                <div class="contents">

                    <?php
                        if(isset($_SESSION['success'])){
                            echo "<div class='success'>".
                                $_SESSION['success'].
                            "</div>";
                            unset($_SESSION['success']);
                        }
                    ?>
                    <?php
                        if(isset($_SESSION['error'])){
                            echo "<div class='error'>".
                                $_SESSION['error'].
                            "</div>";
                            unset($_SESSION['error']);
                        }
                    ?>
                    <!-- dashboard -->
                    <?php include "dashboard.php"?>
                </div>
            </section>
        </div>
    </main>
    
    <script src="../jquery.js"></script>
    <script src="../jquery.table2excel.js"></script>
    <script src="../select2.min.js"></script>
    <script src="../Chart.min.js"></script> 
    <script src="../script.js?v=<?php echo APP_VERSION?>"></script>
    <script>
        
           setTimeout(function(){
                $(".success").hide();
            }, 4000);
            setTimeout(function(){
                $(".error").hide();
            }, 4000);

            var ctx = document.getElementById("chartjs_bar2").getContext('2d');
            // Function to generate random colors
            function generateColors(numColors) {
                var colors = [];
                for (var i = 0; i < numColors; i++) {
                    // Generate a random color in RGB format
                    var randomColor = 'rgb(' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ',' + Math.floor(Math.random() * 256) + ')';
                    colors.push(randomColor);
                }
                return colors;
            }

            // Get the number of months (or data points)
            var numMonths = <?php echo count($month); ?>; // Assuming $month is an array of months

            // Generate an array of colors based on the number of months
            var backgroundColors = generateColors(numMonths);

            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($month); ?>,
                    datasets: [{
                        label: 'Revenue',
                        backgroundColor: backgroundColors, // Use the dynamic color array
                        data: <?php echo json_encode($revenue); ?>,
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: 'white', // Font color
                                font: {
                                    family: 'Circular Std Book',
                                    size: 14,
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: 'white' // X-axis label color
                            }
                        },
                        y: {
                            ticks: {
                                color: 'white' // Y-axis label color
                            }
                        }
                    }
                }
            }); 
    </script>
</body>
</html>


<?php
    }else{
        header("Location: ../index.php");
    }

?>