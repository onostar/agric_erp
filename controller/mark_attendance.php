
<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(!isset($_POST['staff'])){
        echo "Invalid request";
        exit();
    }
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        $staff = (int) $_POST['staff'];
        $latitude = $_POST['latitude'] ?? null;
        $longitude = $_POST['longitude'] ?? null;
        $accuracy = $_POST['accuracy'] ?? null;
        //get ip address
        $ip = $_SERVER['REMOTE_ADDR'];

        $time = date("H:i:s");
        $currentDay = date("Y-m-d");
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        $get_details = new selects();
        //get staff details
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }
        //check if location data is provided
        if(empty($_POST['latitude']) || empty($_POST['longitude'])){
            echo "<div class='error'>Please enable your location to start work.</div>";
            exit();
        }
        //check if staff already checks in
        $check = $get_details->fetch_count_curDatePosCon('attendance', 'attendance_date', 'staff', $staff);
        if($check > 0){
            echo "<script>alert('You have already checked in today')</script>";
            echo "<div class='success'><p style='background:brown'>$full_name! already checked in today. <i class='fas fa-thumb-tack'></i></p></div>";
            exit();
        }else{
            $location = 'Unknown location';
            //get location from lat and long
            if($latitude && $longitude){
                //valid coordinates
                $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$latitude&lon=$longitude";

                $options = [
                    "http" => [
                        "header" => "User-Agent: OnostarERP/1.0"
                    ]
                ];

                $context = stream_context_create($options);

                $response = @file_get_contents($url, false, $context);
                if($response){
                    $data = json_decode($response, true);
                    if(isset($data['display_name'])){
                        $location = $data['display_name'];
                    }
                }
            }
            $data = array(
                'staff' => $staff,
                'attendance_date' => $currentDay,
                'time_in' => $time,
                'remark' => '',
                'ip_address' => $ip,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'accuracy' => $accuracy,
                'location' => $location,
                'marked_date' => $date,
                'marked_by' => $user,
                'store' => $store
            );
            $mark= new add_data('attendance', $data);
            $mark->create_data();
            if($mark){
                echo "<div class='success'><p>Attendance was marked successfully. <i class='fas fa-thumbs-up'></i></p></div>";
            }
        }
        
    
    }else{
        echo "Your Session has expired! Please login again";
    }