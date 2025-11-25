
//show mobile menu
function displayMenu(){
     let main_menu = document.getElementById("mobile_log");
     if(window.innerWidth <= "800"){
          $("#menu_icon").click(function(){
               if(main_menu.style.display == "block"){
                    $(".main_menu").hide();
                    $("#menu_icon").html("<a href='javascript:void(0)'><i class='fas fa-bars'></i></a>");
               }else{
                    $(".main_menu").show();
                    $("#menu_icon").html("<a href='javascript:void(0)'><i class='fas fa-close'></i></a>");
               }
          })
               
          
     }
     // else{
          /* $("#menu_icon").click(function(){
               if(main_menu.style.display == "block"){
               alert (window.innerWidth);

                    main_menu.style.display == "none"
                    $("#menu_icon").html("<a href='javascript:void(0)'><i class='fas fa-close'></i></a>");
                    document.getElementById("contents").style.width = "100vw";
                    document.getElementById("contents").style.marginLeft = "0";
               }
          })
     } */
}
displayMenu();
//checck the screen width 
function checkMobile(){
     let screen_width = window.innerWidth;
     if(screen_width <= "800"){
          // alert(screen_width);
          $("#contents").click(function(){
               $(".main_menu").hide();
               $("#menu_icon").html("<a href='javascript:void(0)'><i class='fas fa-bars'></i></a>");
          
          })
     }
}
checkMobile();

// toggle password
function togglePassword(){
    let pw = document.querySelectorAll(".password");
    pw.forEach(ps => {
       if(ps.type === "password"){
            ps.type = "text";
            document.querySelector(".icon").innerHTML = "<i class='fas fa-eye-slash'></i>";
            document.querySelector(".icon_txt").innerHTML = "Hide password";
       }else{
            ps.type = "password";
            document.querySelector(".icon").innerHTML = "<i class='fas fa-eye'></i>";
            document.querySelector(".icon_txt").innerHTML = "Show password";
       } 
    });
}

//toggle logout
$(document).ready(function(){
     $("#loginDiv").click(function(){
          $(".login_option").toggle();
     })
})

//toggle menu with more options
$(document).ready(function(){
     $(".addMenu").click(function(){
          $(".nav1Menu").toggle();
          //change icon from plus to miinus and vice versa
          let option_icon = document.querySelector(".options");
          if(document.querySelector(".nav1Menu").style.display == "block"){
               option_icon.innerHTML = "<i style='background:none; color:#fff!important; box-shadow:none!important;' class='fas fa-minus'></i>";
          }else{
               option_icon.innerHTML = "<i style='background:none; color:#fff!important; box-shadow:none!important;' class='fas fa-plus'></i>";
          }

     })
})
//toggle all submenu
/* show frequenty asked questions */
function toggleMenu(subMenu){
     let menus = document.querySelectorAll(".subMenu");
     menu_id = document.getElementById(subMenu);
     if(menu_id.style.display == "block"){
          menu_id.style.display = "none";
     }else{
          menus.forEach(function(menu){
               menu.style.display = "none";
          })
          menu_id.style.display = "block";
     }
}

//show payment mode forms
function showCash(){
     document.querySelectorAll(".payment_form").forEach(function(forms){
          forms.style.display = "none";
     })
     $("#cash").show();
}
function showPos(){
     document.querySelectorAll(".payment_form").forEach(function(forms){
          forms.style.display = "none";
     })
     $("#pos").show();
}
function showTransfer(){
     document.querySelectorAll(".payment_form").forEach(function(forms){
          forms.style.display = "none";
     })
     $("#transfer").show();
}
//show pages dynamically with xhttp request
function showPage(page){
     let xhr = false;
     if(window.XMLHttpRequest){
          xhr = new XMLHttpRequest();
     }else{
          xhr = new ActiveXObject("Microsoft.XMLHTTP");
     }
     if(xhr){
          $(".contents").html("<div class='processing'><div class='loader'></div></div>");
          xhr.onreadystatechange = function(){
               if(xhr.readyState == 4 && xhr.status == 200){
                    document.querySelector(".contents").innerHTML = xhr.responseText;
               }
          }
          xhr.open("GET", page, true );
          xhr.send(null);
     }
}

//add users
function addUser(){
     let username = document.getElementById("username").value;
     let full_name = document.getElementById("full_name").value;
     let staff_id = document.getElementById("staff_id").value;
     let user_role = document.getElementById("user_role").value;
     let store_id = document.getElementById("store_id").value;
     // alert(store);
     if(full_name.length == 0 || full_name.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter user full name!");
          $("#full_name").focus();
          return;
     }else if(username.length == 0 || username.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter a username!");
          $("#username").focus();
          return;
     }else if(user_role.length == 0 || user_role.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select user role!");
          $("#user_role").focus();
          return;
     }else if(store_id.length == 0 || store_id.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select store!");
          $("#store").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_users.php",
               data : {username:username, staff_id:staff_id,full_name:full_name, user_role:user_role, store_id:store_id},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#username").val('');
     $("#item").val('');
     $("#staff_id").val('');
     $("#full_name").val('');
     $("#user_role").val('');
     $("#store_id").val('');
     $("#full_name").focus();
     return false;
}

//add departments
function addDepartment(){
     let department = document.getElementById("department").value;
     if(department.length == 0 || department.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input department!");
          $("#department").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_department.php",
               data : {department:department},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#department").val('');
     $("#department").focus();
     return false;
}
//add expense head
function addExpHead(){
     let exp_head = document.getElementById("exp_head").value;
     if(exp_head.length == 0 || exp_head.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input expense head!");
          $("#exp_head").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_exp_head.php",
               data : {exp_head:exp_head},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#exp_head").val('');
     $("#exp_head").focus();
     return false;
}
//add categories
function addCategory(){
     let category = document.getElementById("category").value;
     let department = document.getElementById("department").value;
     if(category.length == 0 || category.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter category!");
          $("#category").focus();
          return;
     }else if(department.length == 0 || department.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a department!");
          $("#department").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_category.php",
               data : {category:category, department:department},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#category").val('');
     $("#category").focus();
     return false;
}
//add bank
function addBank(){
     let bank = document.getElementById("bank").value;
     let account_num = document.getElementById("account_num").value;
     if(bank.length == 0 || bank.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input bank name!");
          $("#bank").focus();
          return;
     }else if(account_num.length == 0 || account_num.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input account number!");
          $("#account_num").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_bank.php",
               data : {bank:bank, account_num:account_num},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#bank").val('');
     $("#account_num").val('');
     $("#bank").focus();
     return false;
}
//search for data within table
function searchData(data){
     let $row = $(".searchTable tbody tr");
     let val = $.trim(data).replace(/ +/g, ' ').toLowerCase();
     $row.show().filter(function(){
          var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
          return !~text.indexOf(val);
     }).hide();
}

// disale user
function disableUser(user_id){
     let disable = confirm("Do you want to disable this user?", "");
     if(disable){
          // alert(user_id);
          $.ajax({
               type: "GET",
               url : "../controller/disable_user.php?id="+user_id,
               success : function(response){
                    $("#disable_user").html(response);
               }
          })
          setTimeout(function(){
               $('#disable_user').load("disable_user.php #disable_user");
          }, 3000);
          return false;
     }
}

// activate disabled user
function activateUser(user_id){
     let activate = confirm("Do you want to activate this user account?", "");
     if(activate){
          $.ajax({
               type : "GET",
               url : "../controller/activate_user.php?user_id="+user_id,
               success : function(response){
                    $("#activate_user").html(response);
               }
          })
          setTimeout(function(){
               $("#activate_user").load("activate_user.php #activate_user");
          }, 3000);
          return false;
     }
}
// Reset user password
function resetPassword(user_id){
     let reset = confirm("Do you want to reset this user password?", "");
     if(reset){
          $.ajax({
               type : "GET",
               url : "../controller/reset_user_password.php?user_id="+user_id,
               success : function(response){
                    $("#reset_password").html(response);
               }
          })
          setTimeout(function(){
               $("#reset_password").load("reset_password.php #reset_password");
          }, 3000);
          return false;
     }
}

// add items 
function addItem(){
     let department = document.getElementById("department").value;
     let item_category = document.getElementById("item_category").value;
     let item = document.getElementById("item").value;
     // let barcode = document.getElementById("barcode").value;
     let item_type = document.getElementById("item_type").value;
     if(item_category.length == 0 || item_category.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select item category!");
          $("#item_category").focus();
          return;
     }else if(item.length == 0 || item.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter item name");
          $("#item").focus();
          return;
     /* }else if(barcode.length == 0 || barcode.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter item barcode");
          $("#barcode").focus();
          return; */
     }else if(item_type.length == 0 || item_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select item type");
          $("#item_type").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_item.php",
               data : {department:department, item_category:item_category, item:item, item_type:item_type/* barcode:barcode */},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     // $("#room_category").val('');
     $("#item").val('');
     $("#barcode").val('');
     $("#item_type").val('');
     $("#item").focus();
     return false;    
}
// add stores
function addStore(){
     let store_name = document.getElementById("store_name").value;
     let store_address = document.getElementById("store_address").value;
     let phone = document.getElementById("phone").value;
     if(store_name.length == 0 || store_name.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter store name!");
          $("#store_name").focus();
          return;
     }else if(store_address.length == 0 || store_address.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter store address");
          $("#store_address").focus();
          return;
     }else if(phone.length == 0 || phone.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter store phone numbers");
          $("#phone").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_store.php",
               data : {store_name:store_name, store_address:store_address, phone:phone},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     // $("#room_category").val('');
     $("#store_name").val('');
     $("#store_address").val('');
     $("#phone").val('');
     $("#store").focus();
     return false;    
}
// add staffs 
function addStaff(){
     let staff_name = document.getElementById("staff_name").value;
     let phone_number = document.getElementById("phone_number").value;
     let home_address = document.getElementById("home_address").value;
     if(staff_name.length == 0 || staff_name.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff name!");
          $("#staff_name").focus();
          return;
     }else if(home_address.length == 0 || home_address.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff residential address");
          $("#home_address").focus();
          return;
     }else if(phone_number.length == 0 || phone_number.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff phone number");
          $("#phone_number").focus();
          return;
     }else if(phone_number.length < 11){
          alert("Phone number is too short");
          $("#phone_number").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_staff.php",
               data : {staff_name:staff_name, phone_number:phone_number, home_address:home_address},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     // $("#room_category").val('');
     $("#staff_name").val('');
     $("#phone_number").val('');
     $("#home_address").val('');
     $("#staff_name").focus();
     return false;    
}
// add suppliers 
function addSupplier(){
     let supplier = document.getElementById("supplier").value;
     let contact_person = document.getElementById("contact_person").value;
     let phone = document.getElementById("phone").value;
     // let email = document.getElementById("email").value;
     let address = document.getElementById("address").value;
     if(supplier.length == 0 || supplier.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input supplier name!");
          $("#supplier").focus();
          return;
     /* }else if(contact_person.length == 0 || contact_person.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input contact person name");
          $("#contact_person").focus();
          return; */
     }else if(phone.length == 0 || phone.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input phone number");
          $("#phone").focus();
          return;
     /* }else if(email.length == 0 || email.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input email address");
          $("#email").focus();
          return; */
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_vendor.php",
               data : {supplier:supplier, contact_person:contact_person, phone:phone, address:address},
                beforeSend : function(){
                    $(".info").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#supplier").val('');
     $("#contact_person").val('');
     $("#phone").val('');
     $("#address").val('');
     $("#supplier").focus();
     return false;    
}

// get item categories
function getCategory(post_department){
     let department = post_department;
     if(department){
          $.ajax({
               type : "POST",
               url :"../controller/get_categories.php",
               data : {department:department},
               success : function(response){
                    $("#item_category").html(response);
               }
          })
          return false;
     }else{
          $("#item_category").html("<option value'' selected>Select department first</option>")
     }
     
}


//calculate days from check in and check out
function calculateDays(){
     let check_in_date = document.getElementById("check_in_date").value;
     let check_out_date = document.getElementById("check_out_date").value; 
     let amount = document.getElementById("amount");
     let room_fee = document.getElementById("room_fee").value;
     let num_days = document.getElementById("days");
     firstDay = new Date(check_in_date);
     secondDay = new Date(check_out_date);
     days = secondDay.getTime() - firstDay.getTime();
     totalDays = days / (1000 * 60 * 60 * 24);
     newAmount = totalDays * parseInt(room_fee);
     amount.innerHTML = "<input type='number' name='amount_due' id='amount_due' value='"+newAmount+"'>";
     num_days.innerHTML = "<p>(Checking in for "+totalDays+" days)</p>";
     // alert(totalDays);
}

//post guest cash payment
function postCash(){
     let posted_by = document.getElementById("posted_by").value;
     let guest = document.getElementById("guest").value;
     let payment_mode = document.getElementById("payment_mode").value;
     let bank_paid = document.getElementById("bank_paid").value;
     let sender = document.getElementById("sender").value;
     let guest_amount = document.getElementById("guest_amount").value;
     let amount_paid = document.getElementById("amount_paid").value;
     
     if(amount_paid.length == 0 || amount_paid.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input amount paid!");
          $("#amount_paid").focus();
          return;
     // }else if(parseInt(amount_paid) < parseInt(guest_amount)){
     //      alert("Insufficient funds!");
     //      $("#guest_amount").focus();
     //      return;
     }else{
     $.ajax({
          type : "POST",
          url : "../controller/post_payments.php",
          data : {posted_by:posted_by, guest:guest, payment_mode:payment_mode, bank_paid:bank_paid, sender:sender, guest_amount:guest_amount, amount_paid:amount_paid},
          success : function(response){
               $("#all_payments").html(response);
          }
     })
          setTimeout(function(){
               $('#all_payments').load("post_payment.php?guest_id=+"+guest + "#all_payments");
          }, 3000);
     }
     return false;    

}
//post guest POS payment
function postPos(){
     let posted_by = document.getElementById("posted_by").value;
     let guest = document.getElementById("guest").value;
     let payment_mode = document.getElementById("pos_mode").value;
     let bank_paid = document.getElementById("pos_bank_paid").value;
     let sender = document.getElementById("sender").value;
     let guest_amount = document.getElementById("guest_amount").value;
     let amount_paid = document.getElementById("pos_amount_paid").value;
     
     if(amount_paid.length == 0 || amount_paid.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input amount paid!");
          $("#pos_amount_paid").focus();
          return;
     }else if(bank_paid.length == 0 || bank_paid.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select POS Bank!");
          $("#pos_bank_paid").focus();
          return;
     }else{
     $.ajax({
          type : "POST",
          url : "../controller/post_payments.php",
          data : {posted_by:posted_by, guest:guest, payment_mode:payment_mode, bank_paid:bank_paid, sender:sender, guest_amount:guest_amount, amount_paid:amount_paid},
          success : function(response){
               $("#all_payments").html(response);
          }
     })
          setTimeout(function(){
               $('#all_payments').load("post_payment.php?guest_id=+"+guest + "#all_payments");
          }, 3000);
     }
     return false;    

}
//post other cash payments for guest
function postOtherCash(){
     let posted_by = document.getElementById("posted_by").value;
     let guest = document.getElementById("guest").value;
     let payment_mode = document.getElementById("payment_mode").value;
     let bank_paid = document.getElementById("bank_paid").value;
     let sender = document.getElementById("sender").value;
     let guest_amount = document.getElementById("guest_amount").value;
     let amount_paid = document.getElementById("amount_paid").value;
     
     if(amount_paid.length == 0 || amount_paid.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input amount paid!");
          $("#amount_paid").focus();
          return;
     // }else if(parseInt(amount_paid) < parseInt(guest_amount)){
     //      alert("Insufficient funds!");
     //      $("#guest_amount").focus();
     //      return;
     }else{
     $.ajax({
          type : "POST",
          url : "../controller/post_other_payments.php",
          data : {posted_by:posted_by, guest:guest, payment_mode:payment_mode, bank_paid:bank_paid, sender:sender, guest_amount:guest_amount, amount_paid:amount_paid},
          success : function(response){
               $("#guest_details").html(response);
          }
     })
          setTimeout(function(){
               $('#guest_details').load("guest_details.php?guest_id=+"+guest + "#guest_details");
          }, 3000);
     }
     return false;    

}
//post other Pos payments for guest
function postOtherPos(){
     let posted_by = document.getElementById("posted_by").value;
     let guest = document.getElementById("guest").value;
     let payment_mode = document.getElementById("pos_mode").value;
     let bank_paid = document.getElementById("pos_bank_paid").value;
     let sender = document.getElementById("sender").value;
     let guest_amount = document.getElementById("guest_amount").value;
     let amount_paid = document.getElementById("pos_amount_paid").value;
     
     if(amount_paid.length == 0 || amount_paid.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input amount paid!");
          $("#pos_amount_paid").focus();
          return;
     }else if(bank_paid.length == 0 || bank_paid.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select POS Bank!");
          $("#pos_bank_paid").focus();
          return;
     }else{
     $.ajax({
          type : "POST",
          url : "../controller/post_other_payments.php",
          data : {posted_by:posted_by, guest:guest, payment_mode:payment_mode, bank_paid:bank_paid, sender:sender, guest_amount:guest_amount, amount_paid:amount_paid},
          success : function(response){
               $("#guest_details").html(response);
          }
     })
          setTimeout(function(){
               $('#guest_details').load("guest_details.php?guest_id=+"+guest + "#guest_details");
          }, 3000);
     }
     return false;    

}
//post other Transfer payments for guest
function postOtherTransfer(){
     let posted_by = document.getElementById("posted_by").value;
     let guest = document.getElementById("guest").value;
     let payment_mode = document.getElementById("transfer_mode").value;
     let bank_paid = document.getElementById("transfer_bank_paid").value;
     let sender = document.getElementById("transfer_sender").value;
     let guest_amount = document.getElementById("guest_amount").value;
     let amount_paid = document.getElementById("transfer_amount").value;
     
     if(amount_paid.length == 0 || amount_paid.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input amount paid!");
          $("#transfer_amount").focus();
          return;
     }else if(bank_paid.length == 0 || bank_paid.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Bank Transferred to!");
          $("#transfer_bank_paid").focus();
          return;
     }else if(sender.length == 0 || sender.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please Input Name of sender!");
          $("#transfer_sender").focus();
          return;
     }else{
     $.ajax({
          type : "POST",
          url : "../controller/post_other_payments.php",
          data : {posted_by:posted_by, guest:guest, payment_mode:payment_mode, bank_paid:bank_paid, sender:sender, guest_amount:guest_amount, amount_paid:amount_paid},
          success : function(response){
               $("#guest_details").html(response);
          }
     })
          setTimeout(function(){
               $('#guest_details').load("guest_details.php?guest_id=+"+guest + "#guest_details");
          }, 3000);
     }
     return false;    

}


//display any item form
function getForm(item, link){
     // alert(item_id);
     $.ajax({
          type : "GET",
          url : "../controller/"+link+"?item_id="+item,
          beforeSend : function(){
               $(".info").html("<div class='processing'><div class='loader'></div></div>");
          },
          success : function(response){
               $(".info").html(response);
               window.scrollTo(0, 0);
          }
     })
     return false;
 
 }


//display stockin form
function displayStockinForm(item_id){
     let item = item_id;
          $.ajax({
               type : "GET",
               url : "../controller/get_stockin_details.php?item="+item,
               beforeSend : function(){
                    $(".info").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $(".info").html(response);
                    document.getElementById("info").scrollIntoView();
               }
          })
          $("#sales_item").html("");
          $("#item").val('');
          $("#quantity").focus();

          return false;
     // }
     
 }
//display purchase order form
function displayPOForm(item_id, vendor, invoice){
     let item = item_id;
          $.ajax({
               type : "GET",
               url : "../controller/get_po_details.php?item="+item+"&vendor="+vendor+"&invoice="+invoice,
               beforeSend : function(){
                    $(".info").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $(".info").html(response);
                    document.getElementById("info").scrollIntoView();
               }
          })
          $("#sales_item").html("");
          $("#item").val('');
          $("#quantity").focus();

          return false;
     // }
     
 }
//display transfer item form
function addTransfer(item_id){
     let item = item_id;
          
          $.ajax({
               type : "GET",
               url : "../controller/get_transfer_details.php?item="+item,
               success : function(response){
                    $(".info").html(response);
               }
          })
          $("#sales_item").html("");
          $("#item").val('');
          return false;
     // }
     
 }
//display transfer item form
function addIssue(item_id){
     let item = item_id;
          
          $.ajax({
               type : "GET",
               url : "../controller/get_issue_details.php?item="+item,
               success : function(response){
                    $(".info").html(response);
               }
          })
          $("#sales_item").html("");
          $("#item").val('');
          return false;
     // }
     
 }

 //display item purchase history
function checkStockinHistory(item_id){
     // alert(item_id);
/*      let invoice = document.getElementById("invoice").value;
     let vendor = document.getElementById("vendor").value; */
     let item = item_id;
          
          $.ajax({
               type : "GET",
               url : "../controller/stockin_history.php?item="+item,
               success : function(response){
                    $(".new_data").html(response);
               }
          })
          $("#sales_item").html("");
          return false;
     // }
     
 }
 //display item issue history
function checkIssuedHistory(item_id){
     // alert(item_id);
/*      let invoice = document.getElementById("invoice").value;
     let vendor = document.getElementById("vendor").value; */
     let item = item_id;
          
          $.ajax({
               type : "GET",
               url : "../controller/item_issued_history.php?item="+item,
               success : function(response){
                    $(".new_data").html(response);
               }
          })
          $("#sales_item").html("");
          $("#item").val("");
          return false;
     // }
     
 }
 //display customer statement/transaction history
function getCustomerStatement(customer_id){
     let customer = customer_id;
          
          $.ajax({
               type : "GET",
               url : "../controller/customer_statement.php?customer="+customer,
               success : function(response){
                    $("#customer_statement").html(response);
               }
          })
          $("#sales_item").html("");
          $("#customer").val("")
          return false;
     // }
     
 }
 //display vendor statement/transaction history
function getVendorStatement(customer_id, fromDate, toDate){
     let customer = customer_id;
          
          $.ajax({
               type : "GET",
               url : "../controller/vendor_statement.php?vendor="+customer+"&fromDate="+fromDate+"&toDate="+toDate,
               beforeSend : function(){
                    $("#customer_statement").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#customer_statement").html(response);
               }
          })
          $("#sales_item").html("");
          $("#customer").val("")
          return false;
     // }
     
 }
 //display items in each customer inivoice under statement/transaction history
function viewCustomerInvoice(invoice_id){
     let invoice = invoice_id;
          
          $.ajax({
               type : "GET",
               url : "../controller/customer_invoices.php?invoice="+invoice,
                beforeSend : function(){
                    $("#customer_invoices").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#customer_invoices").html(response);
                    // window.scrollTo(0, 0);
                    document.getElementById("customer_invoices").scrollIntoView();
               }
          })
          $("#sales_item").html("");
          return false;
     // }
     
 }
 //update items in each customer inivoice under statement/transaction history
function updateCustomerInvoice(invoice_id){
     let invoice = invoice_id;
          
          $.ajax({
               type : "GET",
               url : "../controller/update_invoices.php?invoice="+invoice,
               success : function(response){
                    $("#customer_invoices").html(response);
                    // window.scrollTo(0, 0);
                    document.getElementById("customer_invoices").scrollIntoView();
               }
          })
          $("#sales_item").html("");
          return false;
     // }
     
 }
 //display items in each vendor inivoice under statement/transaction history
function viewVendorInvoice(invoice_id, vendor){
     let invoice = invoice_id;
          
          $.ajax({
               type : "GET",
               url : "../controller/vendor_invoices.php?invoice="+invoice+"&vendor="+vendor,
               beforeSend : function(){
                    document.getElementById("customer_invoices").scrollIntoView();
                    $("#customer_invoices").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#customer_invoices").html(response);
                    // window.scrollTo(0, 0);
               }
          })
          $("#sales_item").html("");
          return false;
     // }
     
 }
 //display payment form for credit payments
function addPayment(invoice_id){
     let invoice = invoice_id;          
          $.ajax({
               type : "GET",
               url : "../controller/get_payment.php?invoice="+invoice,
               success : function(response){
                    $("#customer_invoices").html(response);
                    // window.scrollTo(0, 0);
                    document.getElementById("customer_invoices").scrollIntoView();
               }
          })
          // $("#sales_item").html("");
          return false;
     // }
     
 }
 //stockin in items
function stockin(){
     let posted_by = document.getElementById("posted_by").value;
     let store = document.getElementById("store").value;
     let invoice_number = document.getElementById("invoice_number").value;
     let vendor = document.getElementById("vendor").value;
     // let vendor = document.getElementById("vendor").value;
     let item_id = document.getElementById("item_id").value;
     let quantity = document.getElementById("quantity").value;
     let cost_price = document.getElementById("cost_price").value;
     let item_type = document.getElementById("item_type").value;
     /* let sales_price = document.getElementById("sales_price").value;
     let pack_price = document.getElementById("pack_price").value;
     let pack_size = document.getElementById("pack_size").value;
     let wholesale_price = document.getElementById("wholesale_price").value;
     let wholesale_pack = document.getElementById("wholesale_pack").value; */
    /*  let expiration_date = document.getElementById("expiration_date").value;
     let todayDate = new Date();
     let today = todayDate.toLocaleDateString(); */
     // alert(vendor);
     if(quantity.length == 0 || quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity purchased!");
          $("#quantity").focus();
          return;
     }else if(quantity <= 0){
          alert("Please input quantity purchased!");
          $("#quantity").focus();
          return;
     }else if(cost_price.length == 0 || cost_price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input cost price");
          $("#cost_price").focus();
          return;
     /* }else if(sales_price.length == 0 || sales_price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input selling price");
          $("#sales_price").focus();
          return; */
     /* }else if(expiration_date.length == 0 || expiration_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input item expiration date");
          $("#expiration_date").focus();
          return;
     }else if(new Date(today).getTime() > new Date(expiration_date).getTime()){
          alert("You can not stock in expired items!");
          $("#expiration_date").focus();
          return; */
     /* }else if(parseInt(cost_price) >= parseInt(sales_price)){
          alert("Cost price cannot be greater than selling price!");
          $("#sales_price").focus();
          return;*/
     }else if(parseInt(cost_price) < 0){
          alert("Cost price cannot be lesser than 0!");
          $("#cost_price").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/stock_in.php",
               data : {posted_by:posted_by, store:store, /* supplier:supplier, */ vendor:vendor, invoice_number:invoice_number, item_id:item_id, quantity:quantity, cost_price:cost_price, item_type:item_type/* sales_price:sales_price, pack_price:pack_price, pack_size:pack_size, wholesale_price:wholesale_price, wholesale_pack:wholesale_pack,  expiration_date:expiration_date*/},
               beforeSend : function(){
                    $(".stocked_in").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $(".stocked_in").html(response);
               }
          })
          /* $("#quantity").val('');
          $("#expiration_date").val('');
          $("#quantity").focus(); */
          $("#item").focus();
          $(".info").html('');
          return false; 
     }
}
 //transfer in items
function transfer(){
     let posted_by = document.getElementById("posted_by").value;
     let store_from = document.getElementById("store_from").value;
     let store_to = document.getElementById("store_to").value;
     let invoice = document.getElementById("invoice").value;
     let item_id = document.getElementById("item_id").value;
     let quantity = document.getElementById("quantity").value;
     if(quantity.length == 0 || quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity purchased!");
          $("#quantity").focus();
          return;
     }else if(quantity <= 0){
          alert("Please input quantity transfered!");
          $("#quantity").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/transfer.php",
               data : {posted_by:posted_by, store_to:store_to, store_from:store_from, invoice:invoice, item_id:item_id, quantity:quantity},
               success : function(response){
               $(".stocked_in").html(response);
               }
          })
          /* $("#quantity").val('');
          $("#expiration_date").val('');
          $("#quantity").focus(); */
          $(".info").html('');
          return false; 
     }
}
 //issue out items
function issueItem(){
     let posted_by = document.getElementById("posted_by").value;
     let store_from = document.getElementById("store_from").value;
     // let store_to = document.getElementById("store_to").value;
     let invoice = document.getElementById("invoice").value;
     let item_id = document.getElementById("item_id").value;
     let quantity = document.getElementById("quantity").value;
     if(quantity.length == 0 || quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity transferred!");
          $("#quantity").focus();
          return;
     }else if(quantity <= 0){
          alert("Please input quantity transferred!");
          $("#quantity").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/issue_item.php",
               data : {posted_by:posted_by,store_from:store_from, invoice:invoice, item_id:item_id, quantity:quantity},
               success : function(response){
               $(".stocked_in").html(response);
               }
          })
          /* $("#quantity").val('');
          $("#expiration_date").val('');
          $("#quantity").focus(); */
          $(".info").html('');
          return false; 
     }
}

//delete individual purchases
function deletePurchase(purchase, item){
     let confirmDel = confirm("Are you sure you want to delete this purchase?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_purchase.php?purchase_id="+purchase+"&item_id="+item,
               beforeSend : function(){
                    $(".stocked_in").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $(".stocked_in").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}
//delete individual purchase order
function deletePO(purchase, item){
     let confirmDel = confirm("Are you sure you want to remove this item from the purchase order?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_purchase_order.php?purchase_id="+purchase+"&item_id="+item,
               beforeSend : function(){
                    $(".stocked_in").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $(".stocked_in").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}
//close stock in form
function closeStockin(){
     $("#stockin").load("stockin_purchase.php #stockin");
}


 //adjust item quantity
 function adjustQty(){
     let item_id = document.getElementById("item_id").value;
     let quantity = document.getElementById("quantity").value;
     if(quantity.length == 0 || quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity!");
          $("#item_name").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/stock_adjustment.php",
               data: {item_id:item_id, quantity:quantity},
               success : function(response){
                    $("#adjust_quantity").html(response);
               }
          })
          setTimeout(function(){
               $("#adjust_quantity").load("stock_adjustment.php #adjust_quantity");
          }, 2000);
          return false
     }
 }
 
 //remove item quantity from store
 function removeQty(){
     let item_id = document.getElementById("item_id").value;
     let quantity = document.getElementById("quantity").value;
     let remove_reason = document.getElementById("remove_reason").value;
     if(quantity.length == 0 || quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity!");
          $("#item_name").focus();
          return;
     }else if(remove_reason.length == 0 || remove_reason.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select reason for removal!");
          $("#remove_reason").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/remove_item.php",
               data: {item_id:item_id, quantity:quantity, remove_reason:remove_reason},
               success : function(response){
                    $("#remove_item").html(response);
               }
          })
          setTimeout(function(){
               $("#remove_item").load("remove_item.php #remove_item");
          }, 2000);
          return false
     }
 }
 //adjust item expiration
 function adjustExpiry(){
     let item_id = document.getElementById("item_id").value;
     let exp_date = document.getElementById("exp_date").value;
     let todayDate = new Date();
     let today = todayDate.toLocaleDateString();
     if(exp_date.length == 0 || exp_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input date!");
          $("#item_name").focus();
          return;
     }else if(new Date(today).getTime() > new Date(exp_date).getTime()){
          alert("Expiration date is invalid!");
          $("#expiration_date").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/adjust_expiration.php",
               data: {item_id:item_id, exp_date:exp_date},
               success : function(response){
                    $("#adjust_expiration").html(response);
               }
          })
          setTimeout(function(){
               $("#adjust_expiration").load("adjust_expiration.php #adjust_expiration");
          }, 2000);
          return false
     }
 }
//  display change rom price
function roomPriceForm(item_id){
     // alert(item_id);
     $.ajax({
          type : "GET",
          url : "../controller/get_room_details.php?item_id="+item_id,
          success : function(response){
               $(".info").html(response);
          }
     })
     return false;
 
 }
 
 //close price form
 function closeForm(){
     
         $(".priceForm").hide();
     
 }

 //change room price
 function changeRoomPrice(){
     let item_id = document.getElementById("item_id").value;
     let price = document.getElementById("price").value;

     $.ajax({
          type : "POST",
          url : "../controller/edit_room_price.php",
          data: {item_id:item_id, price:price},
          success : function(response){
               $("#edit_price").html(response);
          }
     })
     setTimeout(function(){
          $("#edit_price").load("room_price.php #edit_price");
     }, 1500);
     return false;
 }
 //change product price
 function changeItemPrice(){
     let item_id = document.getElementById("item_id").value;
     let cost_price = document.getElementById("cost_price").value;
     let sales_price = document.getElementById("sales_price").value;
     /* let pack_price = document.getElementById("pack_price").value;
     let pack_size = document.getElementById("pack_size").value; */
     // let wholesale_price = document.getElementById("wholesale_price").value;
     /* let wholesale_pack = document.getElementById("wholesale_pack").value;*/
     if(parseInt(cost_price) >= parseInt(sales_price)){
          alert("Selling price can not be lesser than cost price!");
          $("#sales_price").focus();
          return;
     /*}else if(parseInt(cost_price) >= parseInt(wholesale_price)){
          alert("Guest price can not be lesser than cost price!");
          $("#wholesale_price").focus();
          return;*/
     }else if(cost_price.length == 0 || cost_price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter cost price!");
          $("#cost_price").focus();
          return; 
     }else if(sales_price.length == 0 || sales_price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter selling price!");
          $("#sales_price").focus();
          return;
     /*}else if(wholesale_price.length == 0 || wholesale_price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter wholesale price!");
          $("#wholesale_price").focus();
          return;
     /* }else if(pack_price.length == 0 || pack_price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter pack price!");
          $("#pack_price").focus();
          return; */
     /* }else if(pack_price <= cost_price){
          alert("Error! Pack price cannot be lesser than cost price!");
          $("#pack_price").focus();
          return; */
     /* }else if(pack_size.length == 0 || pack_size.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter pack size!");
          $("#pack_size").focus();
          return; */
     }else if(sales_price <= 0 || cost_price < 0){
          alert("Prices can not be less than or  equal to 0");
          return;
     
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/edit_price.php",
               data: {item_id:item_id, cost_price:cost_price, sales_price:sales_price, /* pack_price:pack_price, wholesale_price:wholesale_price,wholesale_pack:wholesale_pack, pack_size:pack_size */},
               beforeSend : function(){
                    $("#edit_item_price").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#edit_item_price").html(response);
               }
          })
          setTimeout(function(){
               $("#edit_item_price").load("item_price.php #edit_item_price");
          }, 1500);
          return false
     }
 }
 //change raw matrial cost price
 function changeCostPrice(){
     let item_id = document.getElementById("item_id").value;
     let cost_price = document.getElementById("cost_price").value;
     
     if(parseInt(cost_price) <= 0){
          alert("Cost price can not be lesser than or equal to 0!");
          $("#cost_price").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/edit_cost_price.php",
               data: {item_id:item_id, cost_price:cost_price},
               success : function(response){
                    $("#edit_item_price").html(response);
               }
          })
          setTimeout(function(){
               $("#edit_item_price").load("raw_material_price.php #edit_item_price");
          }, 1500);
          return false
     }
 }
 //modify item name
 function modifyItem(){
     let item_id = document.getElementById("item_id").value;
     let item_name = document.getElementById("item_name").value;
     if(item_name.length == 0 || item_name.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input item name!");
          $("#item_name").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/modify_item.php",
               data: {item_id:item_id, item_name:item_name},
               beforeSend : function(){
                    $("#edit_item_name").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#edit_item_name").html(response);
               }
          })
          setTimeout(function(){
               $("#edit_item_name").load("modify_item.php #edit_item_name");
          }, 1500);
          return false
     }
 }
 //update item barcode
 function updateBarcode(){
     let item_id = document.getElementById("item_id").value;
     let barcode = document.getElementById("barcode").value;
     if(barcode.length == 0 || barcode.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input item barcode!");
          $("#barcode").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_barcode.php",
               data: {item_id:item_id, barcode:barcode},
               success : function(response){
                    $("#update_barcode").html(response);
               }
          })
          setTimeout(function(){
               $("#update_barcode").load("update_barcode.php #update_barcode");
          }, 1500);
          return false
     }
 }
 //change item category
 function changeCategory(){
     let item_id = document.getElementById("item_id").value;
     let department = document.getElementById("department").value;
     let item_category = document.getElementById("item_category").value;
     if(department.length == 0 || department.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select item category!");
          $("#department").focus();
          return;
     }else if(item_category.length == 0 || item_category.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select item subcategory!");
          $("#item_category").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/change_category.php",
               data: {item_id:item_id, department:department, item_category:item_category},
               success : function(response){
                    $("#change_category").html(response);
               }
          })
          setTimeout(function(){
               $("#change_category").load("change_category.php #change_category");
          }, 1500);
          return false
     }
 }





// update password
function updatePassword(url){
     let username = document.getElementById('username').value;
     let current_password = document.getElementById('current_password').value;
     let new_password = document.getElementById('new_password').value;
     let retype_password = document.getElementById('retype_password').value;
     /* authentication */
     if(current_password == 0 || current_password.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter current password");
          $("#current_password").focus();
          return;
     }else if(new_password == 0 || new_password.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter new password");
          $("#new_password").focus();
          return;
     }else if(new_password.length < 6){
          alert("New password must be greater or equal to 6 characters");
          $("#new_password").focus();
          return;
     }else if(retype_password == 0 || retype_password.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please retype new password");
          $("#retype_password").focus();
          return;
     }else if(new_password !== retype_password){
          alert("Passwords does not match!");
          $("#retype_password").focus();
          return;
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/"+url,
               data: {username:username, current_password:current_password, new_password:new_password, retype_password:retype_password},
               success: function(response){
               $(".info").html(response);
               }
          });
     }
     return false;
}
//  Get room reports 
function getRoomReports(room){
     let room_id = room;
     /* authentication */
     if(room_id){
          $.ajax({
               type: "POST",
               url: "../controller/room_reports.php",
               data: {room_id:room_id},
               success: function(response){
                    $(".new_data").html(response);
               }
          });
     }else{
          alert("select a room!");
          return;
     }
     return false;
}

//get vendors
function getVendors(vendor){
     let ven_input = vendor;
     if(ven_input){
          $.ajax({
               type : "POST",
               url :"../controller/get_vendors.php",
               data : {ven_input:ven_input},
               success : function(response){
                    $("#vendors").html(response);
               }
          })
          return false;
     }else{
          $("#vendors").html("<option value='' selected>No result</option>")
     }
     
}

//show bill types forms
/* function showRetail(){
     $("#retail_customers").show();
     $("#wholesale_customers").hide();
}
function showWholesale(){
     $("#retail_customers").hide();
     $("#wholesale_customers").show();
} */
//show sales form categories (bar and restuarant)
function showBar(){
     $("#bar_items").show();
     $("#restaurant_items").hide();
}
function showRestaurant(){
     $("#bar_items").hide();
     $("#restaurant_items").show();
}

//get item for direct sales
function getItems(item_name){
     let item = item_name;
     // alert(check_room);
     // return;
     if(item.length >= 3){
          if(item){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_items.php",
                    data : {item:item},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}
//get item for sales order
function getItemsOrder(item_name){
     let item = item_name;
     // alert(check_room);
     // return;
     if(item.length >= 3){
          if(item){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_sales_order_items.php",
                    data : {item:item},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}
//get item for wholesale direct sales
function getWholesaleItems(item_name){
     let item = item_name;
     let customer = document.getElementById("customer").value;
     // alert(check_room);
     // return;
     if(item.length >= 3){
          if(item){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_wholesale_items.php",
                    beforeSend : function(){
                         $("#sales_item").html("<p>Searching...</p>");
                    },
                    data : {item:item, customer:customer},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}
//get item for wholesale direct sales
function getUpdateItems(item_name){
     let item = item_name;
     let customer = document.getElementById("customer").value;
     // alert(check_room);
     // return;
     if(item.length >= 3){
          if(item){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_update_items.php",
                    data : {item:item, customer:customer},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}
//get item for stockin
function getItemStockin(item_name){
     let item = item_name;
     // alert(check_room);
     // return;
     let invoice = document.getElementById("invoice").value;
     let vendor = document.getElementById("vendor").value;
     if(invoice.length == 0 || invoice.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input invoice number!");
          $("#invoice").focus();
          return;
     }else if(vendor.length == 0 || vendor.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select supplier!");
          $("#vendor").focus();
          return;
     }else{
          if(item.length >= 3){
               if(item){
                    $.ajax({
                         type : "POST",
                         url :"../controller/get_item_stockin.php",
                         data : {item:item, invoice:invoice, vendor:vendor},
                         beforeSend : function(){
                              $("#sales_item").html("<p>Searching....</p>")
                         },
                         success : function(response){
                              $("#sales_item").html(response);
                         }
                    })
                    $("#invoice").attr("readonly", true);
                    $("#vendor").attr("readonly", true);
                    return false;
               }else{
                    $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
               }
          }
     }
     
}
//get item for purchase order
function getItemPO(item_name){
     let item = item_name;
     let vendor = document.getElementById("vendor").value;
     let invoice = document.getElementById("invoice").value;
     if(vendor.length == 0 || vendor.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select supplier!");
          $("#vendor").focus();
          return;
     }else{
          if(item.length >= 3){
               if(item){
                    $.ajax({
                         type : "POST",
                         url :"../controller/get_item_po.php",
                         data : {item:item, vendor:vendor, invoice:invoice},
                         beforeSend : function(){
                              $("#sales_item").html("<p>Searching....</p>")
                         },
                         success : function(response){
                              $("#sales_item").html(response);
                         }
                    })
                    $("#vendor").attr("readonly", true);
                    $("#supplier").attr("readonly", true);
                    return false;
               }else{
                    $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
               }
          }
     }
     
}
//get item for transfer
function getItemTransfer(item_name){
     let item = item_name;
     // alert(check_room);
     // return;
     let invoice = document.getElementById("invoice").value;
     let store_to = document.getElementById("store_to").value;
     if(store_to.length == 0 || store_to.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a store!");
          $("#store_to").focus();
          return;
     }else{
          if(item.length >= 3){
               if(item){
                    $.ajax({
                         type : "POST",
                         url :"../controller/get_item_transfer.php",
                         data : {item:item,  store_to:store_to, invoice:invoice},
                         success : function(response){
                              $("#sales_item").html(response);
                         }
                    })
                    $("#store_to").attr("readonly", true);
                    return false;
               }else{
                    $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
               }
          }
     }
     
}
//get item for issue
function getItemIssue(item_name){
     let item = item_name;
     // alert(check_room);
     // return;
     let invoice = document.getElementById("invoice").value;
     // let store_to = document.getElementById("store_to").value;
     /* if(store_to.length == 0 || store_to.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a store!");
          $("#store_to").focus();
          return;
     }else{ */
          if(item.length >= 3){
               if(item){
                    $.ajax({
                         type : "POST",
                         url :"../controller/get_item_issue.php",
                         data : {item:item,/*   store_to:store_to, */ invoice:invoice},
                         success : function(response){
                              $("#sales_item").html(response);
                         }
                    })
                    // $("#store_to").attr("readonly", true);
                    return false;
               }else{
                    $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
               }
          }
     // }
     
}
//get item issued hostory
function getIssuedItem(item_name){
     let item = item_name;
     // alert(check_room);
     // return;
     let fromDate = document.getElementById("fromDate").value;
     let toDate = document.getElementById("toDate").value;
     if(fromDate.length == 0 || fromDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#fromDate").focus();
          return;
     }else if(toDate.length == 0 || toDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#toDate").focus();
          return;
     }else{
          if(item.length >= 3){
               if(item){
                    $.ajax({
                         type : "POST",
                         url :"../controller/get_issued_item.php",
                         data : {item:item, fromDate:fromDate, toDate:toDate},
                         success : function(response){
                              $("#sales_item").html(response);
                         }
                    })
                    /* $("#fromDate").attr("readonly", true);
                    $("#toDate").attr("readonly", true); */
                    return false;
               }else{
                    $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
               }
          }
     }
}
//get customer statement
function getCustomer(customer_id){
     let customer = customer_id;
     // alert(check_room);
     // return;
     let fromDate = document.getElementById("fromDate").value;
     let toDate = document.getElementById("toDate").value;
     if(fromDate.length == 0 || fromDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#fromDate").focus();
          return;
     }else if(toDate.length == 0 || toDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#toDate").focus();
          return;
     }else{
     if(customer.length >= 3){
          if(customer){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_customer.php",
                    data : {customer:customer, fromDate:fromDate, toDate:toDate},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               /* $("#fromDate").attr("readonly", true);
               $("#toDate").attr("readonly", true); */
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
}
     
}
//get vendor statement
function getVendor(customer_id){
     let customer = customer_id;
     // alert(check_room);
     // return;
     let fromDate = document.getElementById("fromDate").value;
     let toDate = document.getElementById("toDate").value;
     if(fromDate.length == 0 || fromDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#fromDate").focus();
          return;
     }else if(toDate.length == 0 || toDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#toDate").focus();
          return;
     }else{
     if(customer.length >= 3){
          if(customer){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_vendor.php",
                    data : {customer:customer, fromDate:fromDate, toDate:toDate},
                    beforeSend : function(){
                         $("#sales_item").html("<p>Searching....</p>")
                    },
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               /* $("#fromDate").attr("readonly", true);
               $("#toDate").attr("readonly", true); */
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
}
     
}
//get item for stockin history
function getStockinItem(item_name){
     let item = item_name;
     // alert(check_room);
     // return;
     let fromDate = document.getElementById("fromDate").value;
     let toDate = document.getElementById("toDate").value;
     if(fromDate.length == 0 || fromDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#fromDate").focus();
          return;
     }else if(toDate.length == 0 || toDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#toDate").focus();
          return;
     }else{
     if(item.length >= 3){
          if(item){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_item_purchase.php",
                    data : {item:item, fromDate:fromDate, toDate:toDate},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               /* $("#fromDate").attr("readonly", true);
               $("#toDate").attr("readonly", true); */
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
}
     
}
//search vendor history
function vendorHistory(){
     let vendor = document.getElementById("vendor").value;
     // alert(check_room);
     // return;
     let fromDate = document.getElementById("fromDate").value;
     let toDate = document.getElementById("toDate").value;
     if(fromDate.length == 0 || fromDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#fromDate").focus();
          return;
     }else if(toDate.length == 0 || toDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#toDate").focus();
          return;
     }else if(vendor.length == 0 || vendor.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a vendor!");
          $("#toDate").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url :"../controller/vendor_history.php",
               data : {vendor:vendor, fromDate:fromDate, toDate:toDate},
               success : function(response){
                    $(".new_data").html(response);
               }
          })
         
     }
}
     


//add direct sales 
function addSales(item_id){
     let item = item_id;
     let invoice = document.getElementById("invoice").value;
     $.ajax({
          type : "GET",
          url : "../controller/add_sales.php?sales_item="+item+"&invoice="+invoice,
          success : function(response){
               $(".sales_order").html(response);
          }
     })
     $("#sales_item").html("");
     $("#item").val('');

     return false;
}

//add sales order
function addSalesOrder(item_id){
     let item = item_id;
     $.ajax({
          type : "GET",
          url : "../controller/add_sales_order.php?sales_item="+item,
          success : function(response){
               $(".sales_order").html(response);
          }
     })
     $("#sales_item").html("");
     $("#item").val('');

     return false;
}
//add direct wholesales 
function addWholeSales(item_id){
     let item = item_id;
     let customer = document.getElementById("customer").value;
     let invoice = document.getElementById("invoice").value;
     $.ajax({
          type : "GET",
          url : "../controller/add_wholesale.php?sales_item="+item+"&customer="+customer+"&invoice="+invoice,
          success : function(response){
               $(".sales_order").html(response);
          }
     })
     $("#sales_item").html("");
     $("#item").val('');
     $("#item").focus();

     return false;
}
//add direct sales for invoice update 
function addUpdateSales(item_id){
     let item = item_id;
     let customer = document.getElementById("customer").value;
     let invoice = document.getElementById("invoice").value;
     $.ajax({
          type : "GET",
          url : "../controller/add_update.php?sales_item="+item+"&customer="+customer+"&invoice="+invoice,
          success : function(response){
               $(".sales_order").html(response);
          }
     })
     $("#sales_item").html("");
     $("#item").val('');

     return false;
}
//delete individual items from direct sales
function deleteSales(sales, item){
     let confirmDel = confirm("Are you sure you want to remove this item?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_sales.php?sales_id="+sales+"&item_id="+item,
               success : function(response){
                    $(".sales_order").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}
//delete item
function deleteItem(item){
     let confirmDel = confirm("Are you sure you want to delete this item?", "");
     if(confirmDel){
          $.ajax({
               type : "GET",
               url : "../controller/delete_item.php?item="+item,
               success : function(response){
                    $("#delete_item").html(response);
               }
          })
          setTimeout(function(){
               $("#delete_item").load("delete_item.php #delete_item");
          }, 1500);
          return false;
          
     }else{
          return;
     }
}
//delete individual items from sales order
function deleteSalesOrder(sales, item){
     let confirmDel = confirm("Are you sure you want to remove this item?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_sales_order.php?sales_id="+sales+"&item_id="+item,
               success : function(response){
                    $(".sales_order").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}
//delete individual items from direct wholesale
function deleteWholesale(sales, item){
     let confirmDel = confirm("Are you sure you want to remove this item?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_wholesale.php?sales_id="+sales+"&item_id="+item,
               success : function(response){
                    $(".sales_order").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}
//delete individual items from direct wholesale updates
function deleteUpdate(sales, item){
     let confirmDel = confirm("Are you sure you want to remove this item?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_update.php?sales_id="+sales+"&item_id="+item,
               success : function(response){
                    $(".sales_order").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}
//increase quantity for direct sales item
function increaseQty(sales, item){
     // alert(sales);
     $.ajax({
          type : "GET",
          url : "../controller/increase_qty.php?sales_id="+sales+"&item_id="+item,
          success : function(response){
               $(".sales_order").html(response);
          }
          
     })
     return false;
}
//increase quantity for sales order item
function increaseQtyOrder(sales, item){
     // alert(sales);
     $.ajax({
          type : "GET",
          url : "../controller/increase_qty_order.php?sales_id="+sales+"&item_id="+item,
          success : function(response){
               $(".sales_order").html(response);
          }
          
     })
     return false;
}
//increase quantity for direct wholesalesales item
function increaseQtyWholesale(sales, item){
     // alert(sales);
     $.ajax({
          type : "GET",
          url : "../controller/increase_qty_wholesale.php?sales_id="+sales+"&item_id="+item,
          success : function(response){
               $(".sales_order").html(response);
          }
          
     })
     return false;
}
//increase quantity during update of invoice
function increaseQtyUpdate(sales, item){
     // alert(sales);
     $.ajax({
          type : "GET",
          url : "../controller/increase_qty_update.php?sales_id="+sales+"&item_id="+item,
          success : function(response){
               $(".sales_order").html(response);
          }
          
     })
     return false;
}
//decrease quantity for direct sales item
function reduceQty(sales){
     $.ajax({
          type : "GET",
          url : "../controller/decrease_qty.php?item="+sales,
          success : function(response){
               $(".sales_order").html(response);
          }
          
     })
     return false;
}
//decrease quantity for sales order item
function reduceQtyOrder(sales){
     $.ajax({
          type : "GET",
          url : "../controller/decrease_qty_order.php?item="+sales,
          success : function(response){
               $(".sales_order").html(response);
          }
          
     })
     return false;
}
//decrease quantity for direct wholesalesales item
function reduceQtyWholesale(sales){
     $.ajax({
          type : "GET",
          url : "../controller/decrease_qty_wholesale.php?item="+sales,
          success : function(response){
               $(".sales_order").html(response);
          }
          
     })
     return false;
}
//decrease quantity for update wholesalesales item
function reduceQtyUpdate(sales){
     $.ajax({
          type : "GET",
          url : "../controller/decrease_qty_update.php?item="+sales,
          success : function(response){
               $(".sales_order").html(response);
          }
          
     })
     return false;
}
//show more options for sales item to edit price and quantity
function showMore(sales){
     $.ajax({
          type : "GET",
          url : "../controller/edit_price_qty.php?item="+sales,
          success : function(response){
               $(".show_more").html(response);
               window.scrollTo(0, 0);
          }
          
     })
     return false;
}
//show more options for sales order item to edit price and quantity
function showMoreOrder(sales){
     $.ajax({
          type : "GET",
          url : "../controller/edit_price_qty_order.php?item="+sales,
          success : function(response){
               $(".show_more").html(response);
               window.scrollTo(0, 0);

          }
          
     })
     return false;
}
//show more options for sales item to edit price and quantity
function showMoreWholesale(sales){
     $.ajax({
          type : "GET",
          url : "../controller/edit_price_qty_wholesale.php?item="+sales,
          success : function(response){
               $(".show_more").html(response);
               window.scrollTo(0, 0);

          }
          
     })
     return false;
}
//show more options for sales item to edit price and quantity during invoice update
function showMoreUpdate(sales){
     $.ajax({
          type : "GET",
          url : "../controller/edit_price_qty_update.php?item="+sales,
          success : function(response){
               $(".show_more").html(response);
               $(".show_more").scrollIntoView();

          }
          
     })
     return false;
}
//update sales quantity and price for direct sales
function updatePriceQty(){
     let sales_id = document.getElementById("sales_id").value;
     let qty = document.getElementById("qty").value;
     let price = document.getElementById("price").value;
     // let inv_qty = document.getElementById("inv_qty").value;
     /* authentication */
     if(qty.length == 0 || qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity!");
          $("#qty").focus();
          return;
     }else if(price.length == 0 || price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input unit price!");
          $("#price").focus();
          return;
     }else if(qty < 1){
          alert("Qauntity cannot be zero or negative!");
          $("#qty").focus();
          return;
     }else if(price < 1){
          alert("Price cannot be zero or negative!");
          $("#price").focus();
          return;
     /* }else if(qty > inv_qty){
          alert("Available quantity is less than required!");
          $("#qty").focus();
          return; */
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/update_price_qty.php",
               data: {sales_id:sales_id, qty:qty, price:price},
               success: function(response){
               $(".sales_order").html(response);
               }
          });

     }
     $(".show_more").html('');
     return false;
}
//update sales quantity and price for sales order
function updatePriceQtyOrder(){
     let sales_id = document.getElementById("sales_id").value;
     let qty = document.getElementById("qty").value;
     let price = document.getElementById("price").value;
     // let inv_qty = document.getElementById("inv_qty").value;
     /* authentication */
     if(qty.length == 0 || qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity!");
          $("#qty").focus();
          return;
     }else if(price.length == 0 || price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input unit price!");
          $("#price").focus();
          return;
     }else if(qty < 1){
          alert("Qauntity cannot be zero or negative!");
          $("#qty").focus();
          return;
     }else if(price < 1){
          alert("Price cannot be zero or negative!");
          $("#price").focus();
          return;
     /* }else if(qty > inv_qty){
          alert("Available quantity is less than required!");
          $("#qty").focus();
          return; */
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/update_price_qty_order.php",
               data: {sales_id:sales_id, qty:qty, price:price},
               success: function(response){
               $(".sales_order").html(response);
               }
          });

     }
     $(".show_more").html('');
     return false;
}
//update sales quantity and price for wholesale
function updatePriceQtyWh(){
     let sales_id = document.getElementById("sales_id").value;
     let qty = document.getElementById("qty").value;
     let price = document.getElementById("price").value;
     // let inv_qty = document.getElementById("inv_qty").value;
     /* authentication */
     if(qty.length == 0 || qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity!");
          $("#qty").focus();
          return;
     }else if(price.length == 0 || price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input unit price!");
          $("#price").focus();
          return;
     }else if(qty < 1){
          alert("Qauntity cannot be zero or negative!");
          $("#qty").focus();
          return;
     }else if(price < 1){
          alert("Price cannot be zero or negative!");
          $("#price").focus();
          return;
     /* }else if(qty > inv_qty){
          alert("Available quantity is less than required!");
          $("#qty").focus();
          return; */
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/update_price_qty_who.php",
               data: {sales_id:sales_id, qty:qty, price:price},
               success: function(response){
               $(".sales_order").html(response);
               }
          });

     }
     $(".show_more").html('');
     return false;
}
//update sales quantity and price for wholesale update
function updatePriceQtyUp(){
     let sales_id = document.getElementById("sales_id").value;
     let qty = document.getElementById("qty").value;
     let prev_qty = document.getElementById("prev_qty").value;
     let price = document.getElementById("price").value;
     // let inv_qty = document.getElementById("inv_qty").value;
     /* authentication */
     if(qty.length == 0 || qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity!");
          $("#qty").focus();
          return;
     }else if(price.length == 0 || price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input unit price!");
          $("#price").focus();
          return;
     }else if(qty < 1){
          alert("Qauntity cannot be zero or negative!");
          $("#qty").focus();
          return;
     }else if(price < 1){
          alert("Price cannot be zero or negative!");
          $("#price").focus();
          return;
     /* }else if(qty > inv_qty){
          alert("Available quantity is less than required!");
          $("#qty").focus();
          return; */
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/update_price_qty_up.php",
               data: {sales_id:sales_id, qty:qty, prev_qty:prev_qty, price:price},
               success: function(response){
               $(".sales_order").html(response);
               }
          });

     }
     $(".show_more").html('');
     return false;
}
//get item pack price and size for direct sales
function getPack(sales){
     $.ajax({
          type : "GET",
          url : "../controller/get_pack.php?sales_id="+sales,
          success : function(response){
               $(".show_more").html(response);
          }
          
     })
     return false;
}
//get item pack price and size for sales order
function getPackSo(sales){
     $.ajax({
          type : "GET",
          url : "../controller/get_pack_so.php?sales_id="+sales,
          success : function(response){
               $(".show_more").html(response);
          }
          
     })
     return false;
}
//get item pack price and size for wholesale
function getWholesalePack(sales){
     $.ajax({
          type : "GET",
          url : "../controller/get_pack_wholesale.php?sales_id="+sales,
          success : function(response){
               $(".show_more").html(response);
          }
          
     })
     return false;
}
//sell item in pack for direct sales
function sellPack(){
     let sales_id = document.getElementById("sales_id").value;
     let pack_qty = document.getElementById("pack_qty").value;
     let pack_price = document.getElementById("pack_price").value;
     let pack_size = document.getElementById("pack_size").value;
     // let inv_qty = document.getElementById("inv_qty").value;
     /* authentication */
     if(pack_qty.length == 0 || pack_qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity!");
          $("#pack_qty").focus();
          return;
     }else if(pack_price.length == 0 || pack_price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input unit price!");
          $("#pack_price").focus();
          return;
     }else if(pack_qty <= 0 ){
          alert("Qauntity cannot be zero or negative!");
          $("#pack_qty").focus();
          return;
     }else if(pack_price <= 0){
          alert("Price cannot be zero or negative!");
          $("#pack_price").focus();
          return;
     /* }else if(qty > inv_qty){
          alert("Available quantity is less than required!");
          $("#qty").focus();
          return; */
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/sell_pack.php",
               data: {sales_id:sales_id, pack_qty:pack_qty, pack_price:pack_price, pack_size:pack_size},
               success: function(response){
               $(".sales_order").html(response);
               }
          });

     }
     $(".show_more").html('');
     return false;
}
//sell item in pack for sales order
function sellPackSo(){
     let sales_id = document.getElementById("sales_id").value;
     let pack_qty = document.getElementById("pack_qty").value;
     let pack_price = document.getElementById("pack_price").value;
     let pack_size = document.getElementById("pack_size").value;
     // let inv_qty = document.getElementById("inv_qty").value;
     /* authentication */
     if(pack_qty.length == 0 || pack_qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity!");
          $("#pack_qty").focus();
          return;
     }else if(pack_price.length == 0 || pack_price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input unit price!");
          $("#pack_price").focus();
          return;
     }else if(pack_qty <= 0){
          alert("Qauntity cannot be zero or negative!");
          $("#pack_qty").focus();
          return;
     }else if(pack_price <= 0){
          alert("Price cannot be zero or negative!");
          $("#pack_price").focus();
          return;
     /* }else if(qty > inv_qty){
          alert("Available quantity is less than required!");
          $("#qty").focus();
          return; */
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/sell_pack_so.php",
               data: {sales_id:sales_id, pack_qty:pack_qty, pack_price:pack_price, pack_size:pack_size},
               success: function(response){
               $(".sales_order").html(response);
               }
          });

     }
     $(".show_more").html('');
     return false;
}
//sell item in pack for wholesale
function sellPackWholesale(){
     let sales_id = document.getElementById("sales_id").value;
     let pack_qty = document.getElementById("pack_qty").value;
     let pack_price = document.getElementById("pack_price").value;
     let pack_size = document.getElementById("pack_size").value;
     // let inv_qty = document.getElementById("inv_qty").value;
     /* authentication */
     if(pack_qty.length == 0 || pack_qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity!");
          $("#pack_qty").focus();
          return;
     }else if(pack_price.length == 0 || pack_price.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input unit price!");
          $("#pack_price").focus();
          return;
     }else if(pack_qty <= 0 ){
          alert("Qauntity cannot be zero or negative!");
          $("#pack_qty").focus();
          return;
     }else if(pack_price <= 0){
          alert("Price cannot be zero or negative!");
          $("#pack_price").focus();
          return;
     /* }else if(qty > inv_qty){
          alert("Available quantity is less than required!");
          $("#qty").focus();
          return; */
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/sell_pack_wholesale.php",
               data: {sales_id:sales_id, pack_qty:pack_qty, pack_price:pack_price, pack_size:pack_size},
               success: function(response){
               $(".sales_order").html(response);
               }
          });

     }
     $(".show_more").html('');
     return false;
}
//check payment mode
function checkMode(mode){
     let pay_mode = mode;
     let bank_input = document.getElementById("selectBank");
     // let multiples = document.getElementById("multiples");
     // let wallet = document.getElementById("account_balance");
     if(pay_mode == "POS" || pay_mode == "Transfer"){
          bank_input.style.display = "block";
          // multiples.style.display = "none";
          // wallet.style.display = "none";
          // deposit_amount.style.display = "none";
     /* }else if(pay_mode == "Multiple"){
          multiples.style.display = "block";
          bank_input.style.display = "block";
          wallet.style.display = "none";
     }else if(pay_mode == "Wallet"){
          wallet.style.display = "block";
          multiples.style.display = "none";
          bank_input.style.display = "none"; */
     }else{
          bank_input.style.display = "none";
          // multiples.style.display = "none";
          // wallet.style.display = "none";

     }
}
//check payment mode for purchases

function checkOtherMode(mode){
     let pay_mode = mode;
     let deposit = document.getElementById("deposited_amount");
     let cr_ledger = document.getElementById("cr_ledger");
     if(pay_mode == "Deposit"){
          deposit.style.display = "block";
          
     }else{
          deposit.style.display = "none";
     }
     if(pay_mode != "Credit"){
          cr_ledger.style.display = "block";
     }else{
          cr_ledger.style.display = "none";
     }
}
//check payment mode for wholesales
function checkRepMode(mode){
     let pay_mode = mode;
     let bank_input = document.getElementById("selectBank");
     let multiples = document.getElementById("multiples");
     let deposited = document.getElementById("deposited");
     let dep_mode = document.getElementById("dep_mode");
     let wallet = document.getElementById("account_balance");
     if(pay_mode == "POS" || pay_mode == "Transfer"){
          bank_input.style.display = "block";
          multiples.style.display = "none";
          wallet.style.display = "none";
          deposited.style.display = "none";
          dep_mode.style.display = "none";
     }else if(pay_mode == "Multiple"){
          multiples.style.display = "block";
          bank_input.style.display = "block";
          wallet.style.display = "none";
          deposited.style.display = "none";
          dep_mode.style.display = "none";
     }else if(pay_mode == "Wallet"){
          wallet.style.display = "block";
          multiples.style.display = "none";
          bank_input.style.display = "none";
          deposited.style.display = "none";
          dep_mode.style.display = "none";
     }else if(pay_mode == "Deposit"){
          wallet.style.display = "none";
          multiples.style.display = "none";
          bank_input.style.display = "none";
          deposited.style.display = "block";
          dep_mode.style.display = "block";

     }else{
          bank_input.style.display = "none";
          multiples.style.display = "none";
          wallet.style.display = "none";
          deposited.style.display = "none";
          dep_mode.style.display = "none";

     }
}
//post direct sales payment
function postSales(){
     let confirmPost = confirm("Are you sure you want to post this sales?", "");
     if(confirmPost){
          let total_amount = document.getElementById("total_amount").value;
          let sales_invoice = document.getElementById("sales_invoice").value;
          let discount = document.getElementById("discount").value;
          let store = document.getElementById("store").value;
          let payment_type = document.getElementById("payment_type").value;
          let bank = document.getElementById("bank").value;
          let multi_cash = document.getElementById("multi_cash").value;
          let multi_pos = document.getElementById("multi_pos").value;
          let multi_transfer = document.getElementById("multi_transfer").value;
          let sum_amount = parseInt(multi_cash) + parseInt(multi_pos) + parseInt(multi_transfer);
          if(document.getElementById("multiples").style.display == "block"){
               if(sum_amount != (parseInt(total_amount) - parseInt(discount))){
                    alert("Amount entered is not equal to total amount");
                    $("#multi_cash").focus();
                    return;
               }
          }
          if(payment_type == "Multiple" || payment_type == "POS" || payment_type == "Transfer"){
               if(bank.length == 0 || bank.replace(/^\s+|\s+$/g, "").length == 0){
                    alert("Please select bank!");
                    $("#bank").focus();
                    return;
               }
          }
          if(payment_type.length == 0 || payment_type.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select a payment option!");
               $("#payment_type").focus();
               return;
          }else if(discount.length == 0 || discount.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please enter discount value or 0!");
               $("#discount").focus();
               return;
          }else{
               $.ajax({
                    type : "POST",
                    url : "../controller/post_sales.php",
                    data : {sales_invoice:sales_invoice, payment_type:payment_type, bank:bank, multi_cash:multi_cash, multi_pos:multi_pos, multi_transfer:multi_transfer, discount:discount, store:store},
                    success : function(response){
                         $("#direct_sales").html(response);
                    }
               })
               $(".sales_order").html('');
               /* setTimeout(function(){
                    $("#direct_sales").load("direct_sales.php #direct_sales");
               }, 200);
               return false; */
          }
     // }
     }else{
          return;
     }
}
//post sales order payment
function postSalesOrder(){
     let confirmPost = confirm("Are you sure you want to post this sales?", "");
     if(confirmPost){
          let total_amount = document.getElementById("total_amount").value;
          let discount = document.getElementById("discount").value;
          // alert(total_amount);
          let sales_invoice = document.getElementById("sales_invoice").value;
          let payment_type = document.getElementById("payment_type").value;
          let bank = document.getElementById("bank").value;
          let store = document.getElementById("store").value;
          let multi_cash = document.getElementById("multi_cash").value;
          let multi_pos = document.getElementById("multi_pos").value;
          let multi_transfer = document.getElementById("multi_transfer").value;
          let sum_amount = parseInt(multi_cash) + parseInt(multi_pos) + parseInt(multi_transfer);
          if(document.getElementById("multiples").style.display == "block"){
               //check if total amount is greater than sum
               if(sum_amount != (total_amount - discount)){
                    alert("Amount entered is not equal to total amount");
                    $("#multi_cash").focus();
                    return;
               }
          }
          if(payment_type.length == 0 || payment_type.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select a payment option!");
               $("#payment_type").focus();
               return;
          }else if(discount.length == 0 || discount.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please enter discount value or 0!");
               $("#discount").focus();
               return;
          }else{
               $.ajax({
                    type : "POST",
                    url : "../controller/post_sales_order.php",
                    data : {sales_invoice:sales_invoice, payment_type:payment_type, bank:bank, multi_cash:multi_cash, multi_pos:multi_pos, multi_transfer:multi_transfer, discount:discount, store:store},
                    success : function(response){
                         $("#sales_details").html(response);
                    }
               })
               // $(".sales_order").html('');
               /* setTimeout(function(){
                    $("#direct_sales").load("direct_sales.php #direct_sales");
               }, 200);
               return false; */
          }
     }else{
          return;
     }
}
//post sales order ticket
function printSalesOrder(){
     let confirmPost = confirm("Are you sure you want to post this sales?", "");
     if(confirmPost){
          let sales_invoice = document.getElementById("sales_invoice").value;
          
          
          $.ajax({
               type : "POST",
               url : "../controller/post_ticket.php",
               data : {sales_invoice:sales_invoice},
               success : function(response){
                    $("#sales_order").html(response);
               }
          })
          $(".sales_order").html('');
          /* setTimeout(function(){
               $("#direct_sales").load("direct_sales.php #direct_sales");
          }, 200);
          return false; */
     }
}
// prinit transfer receipt
function printTransferReceipt(invoice){
     window.open("../controller/transfer_receipt.php?receipt="+invoice);
     // alert(item_id);
     /* $.ajax({
          type : "GET",
          url : "../controller/sales_receipt.php?receipt="+invoice,
          success : function(response){
               $("#direct_sales").html(response);
          }
     }) */
     setTimeout(function(){
          $("#direct_sales").load("direct_sales.php #direct_sales");
     }, 100);
     return false;
 
 }
 //post direct wholesale payment
function postWholesale(){
     let total_amount = document.getElementById("total_amount").value;
     let sales_invoice = document.getElementById("sales_invoice").value;
     let discount = document.getElementById("discount").value;
     let store = document.getElementById("store").value;
     let customer_id = document.getElementById("customer_id").value;
     let payment_type = document.getElementById("payment_type").value;
     let bank = document.getElementById("bank").value;
     let multi_cash = document.getElementById("multi_cash").value;
     let multi_pos = document.getElementById("multi_pos").value;
     let multi_transfer = document.getElementById("multi_transfer").value;
     let wallet = document.getElementById("wallet").value;
     let deposit = document.getElementById("deposit").value;
     let contra = document.getElementById("contra").value;
     let sum_amount = parseInt(multi_cash) + parseInt(multi_pos) + parseInt(multi_transfer);
     if(document.getElementById("multiples").style.display == "block"){
          if(sum_amount != (parseInt(total_amount) - parseInt(discount))){
               alert("Amount entered is not equal to total amount");
               $("#multi_cash").focus();
               return;
          }
     }
     if(document.getElementById("account_balance").style.display == "block"){
          if(parseInt(total_amount - discount) > parseInt(wallet)){
               alert("Insufficient balance! Kindly fund wallet");
               $("#payment_type").focus();
               return;
          }
     }
     if(document.getElementById("deposited").style.display == "block"){
          if(!deposit || parseInt(deposit) <= 0){
               alert("Input deposit amount");
               $("#deposit").focus();
               return;
          }
          /* if(deposit.length == 0 || deposit.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please input deposited amount!");
               $("#deposit").focus();
               return;
          } */
          if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select deposit mode!");
               $("#contra").focus();
               return;
          }
     }
     if(payment_type == "Transfer" || payment_type == "POS" || payment_type == "Multiple"){
          if(bank.length == 0 || bank.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select bank!");
               $("#bank").focus();
               return;
          }
     }
     if(payment_type.length == 0 || payment_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a payment option!");
          $("#payment_type").focus();
          return;
     }else if(discount.length == 0 || discount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter discount value or 0!");
          $("#discount").focus();
          return;
     }else{
          let confirmPost = confirm("Are you sure you want to post this sales?", "");
          if(confirmPost){
               $.ajax({
                    type : "POST",
                    url : "../controller/post_wholesale.php",
                    data : {sales_invoice:sales_invoice, payment_type:payment_type, bank:bank, multi_cash:multi_cash, multi_pos:multi_pos, multi_transfer:multi_transfer, discount:discount, wallet:wallet, store:store, deposit:deposit, customer_id:customer_id,contra:contra},
                    beforeSend : function(){
                         $("#direct_sales").html("<div class='processing'><div class='loader'></div></div>");
                    },
                    success : function(response){
                         $("#direct_sales").html(response);
                    }
               })
               $(".sales_order").html('');
               /* setTimeout(function(){
                    $("#direct_sales").load("direct_sales.php #direct_sales");
               }, 200); */
               return false;
          }else{
               return;
          }
     // }
     }
}
 //adjust item quantity
 function adjustReorderLevel(){
     let item_id = document.getElementById("item_id").value;
     let rol = document.getElementById("rol").value;
     if(rol.length == 0 || rol.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input reorder level!");
          $("#rol").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/reorder_level.php",
               data: {item_id:item_id, rol:rol},
               success : function(response){
                    $("#reorder_levels").html(response);
               }
          })
          setTimeout(function(){
               $("#reorder_levels").load("reorder_level.php #reorder_levels");
          }, 2000);
          return false
     }
 }

 //display sales return form
function displaySales(sales_id){
     // alert(item_id);
     $.ajax({
          type : "GET",
          url : "../controller/get_sales.php?sales_id="+sales_id,
          success : function(response){
               $(".select_date").html(response);
          }
     })
     return false;
 
 }
 //sales return
 function returnSales(){
     let return_sales = confirm("Are you sure you want to return this sales?", "");
     if(return_sales){
          let item = document.getElementById("item").value;
          let sold_qty = document.getElementById("sold_qty").value;
          let sales_id = document.getElementById("sales_id").value;
          let user_id = document.getElementById("user_id").value;
          let store = document.getElementById("store").value;
          let quantity = document.getElementById("quantity").value;
          let reason = document.getElementById("reason").value;
          if(quantity.length == 0 || quantity.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please input quantity!");
               $("#quantity").focus();
               return;
          }else if(quantity > sold_qty){
               alert("You cannot return more than what was sold!");
               $("#quantity").focus();
               return;
          }else if(reason.length == 0 || reason.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please input reason for return!");
               $("#reason").focus();
               return;
          }else{
               $.ajax({
                    type : "POST",
                    url : "../controller/return_sales.php",
                    data: {item:item, sales_id:sales_id, user_id:user_id, quantity:quantity, reason:reason, store:store},
                    success : function(response){
                         $("#sales_return").html(response);
                    }
               })
               setTimeout(function(){
                    $("#sales_return").load("sales_return.php #sales_return");
               }, 1500);
               return false
          }
     }else{
          return;
     }
}


// reprint receipt
function printReceipt(invoice){
     // alert(item_id);
     window.open("../controller/print_receipt.php?receipt="+invoice);
     /* $.ajax({
          type : "GET",
          url : "../controller/print_receipt.php?receipt="+invoice,
          success : function(response){
               $("#printReceipt").html(response);
          }
     })
     setTimeout(function(){
          $("#printReceipt").load("print_receipt.php #printReceipt");
     }, 100);
     return false; */
 
 }
// prinit sales receipt for direct sales
function printSalesReceipt(invoice){
     window.open("../controller/sales_receipt.php?receipt="+invoice);
     // alert(item_id);
     /* $.ajax({
          type : "GET",
          url : "../controller/sales_receipt.php?receipt="+invoice,
          success : function(response){
               $("#direct_sales").html(response);
          }
     }) */
     setTimeout(function(){
          $("#direct_sales").load("wholesale.php #direct_sales");
     }, 100);
     return false;
 
 }
// prinit sales receipt for sales order
function printSalesOrderReceipt(invoice){
     window.open("../controller/sales_order_receipt.php?receipt="+invoice);
     showPage('post_sales_order.php');
     // alert(item_id);
     /* $.ajax({
          type : "GET",
          url : "../controller/sales_order_receipt.php?receipt="+invoice,
          success : function(response){
               $("#sales_details").html(response);
          }
     }) */
     /* setTimeout(function(){
          $("#direct_sales").load("direct_sales.php #direct_sales");
     }, 100);
     return false; */
 
 }
// prinit sales order ticket
function printSalesTicket(invoice){
     window.open("../controller/sales_order_ticket.php?receipt="+invoice);
     // alert(item_id);
     /* $.ajax({
          type : "GET",
          url : "../controller/sales_order_ticket.php?receipt="+invoice,
          success : function(response){
               $("#sales_order").html(response);
          }
     }) */
     setTimeout(function(){
          $("#sales_order").load("sales_order.php #sales_order");
     }, 100);
     return false;
 
 }
 //perform any type of search with just two date
 function search(url){
     let from_date = document.getElementById('from_date').value;
     let to_date = document.getElementById('to_date').value;
     /* authentication */
     if(from_date.length == 0 || from_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date!");
          $("#from_date").focus();
          return;
     }else if(to_date.length == 0 || to_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#to_date").focus();
          return;
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/"+url,
               data: {from_date:from_date, to_date:to_date},
               beforeSend : function(){
                    $(".new_data").html("<div class='processing'><div class='loader'></div></div>");
               },
               success: function(response){
               $(".new_data").html(response);
               }
          });
     }
     return false;
}
 //search dashboard reports
 function searchDashboard(board){
     let store = board;
     /* authentication */
     if(store.length == 0 || store.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a store!");
          $("#store").focus();
          return;
    /*  }else if(from_date.length == 0 || from_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date!");
          $("#from_date").focus();
          return;
     }else if(to_date.length == 0 || to_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#to_date").focus();
          return; */
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/search_dashboard.php",
               data: {store:store},
               success: function(response){
               $("#general_dashboard").html(response);
               }
          });
     }
     return false;
}
//change store
function changeStore(store, user){
     window.open("../controller/change_store.php?store="+store+"&user="+user, "_self");
}
// Post daily expense 
function postExpense(){
     let posted = document.getElementById("posted").value;
     let store = document.getElementById("store").value;
     let exp_date = document.getElementById("exp_date").value;
     let exp_head = document.getElementById("exp_head").value;
     let contra = document.getElementById("contra").value;
     let amount = document.getElementById("amount").value;
     let details = document.getElementById("details").value;
     let todayDate = new Date();
     // let today = todayDate.toLocaleDateString();
     if(exp_date.length == 0 || exp_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter transaction date!");
          $("#exp_date").focus();
          return;
     }else if(exp_head.length == 0 || exp_head.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select an expense ledger");
          $("#exp_head").focus();
          return;
     }else if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Contra ledger");
          $("#contra").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(new Date(exp_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#exp_date").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_expense.php",
               data : {posted:posted, exp_date:exp_date, exp_head:exp_head, contra:contra, amount:amount, details:details, store:store},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#exp_date").val('');
     $("#exp_head").val('');
     $("#contra").val('');
     $("#amount").val('');
     $("#details").val('');
     $("#exp_date").focus();
     return false;    
}

// edit posted expense 
function updateExpense(){
     let exp_id = document.getElementById("exp_id").value;
     let exp_date = document.getElementById("exp_date").value;
     let exp_head = document.getElementById("exp_head").value;
     let amount = document.getElementById("amount").value;
     let details = document.getElementById("details").value;
     if(exp_date.length == 0 || exp_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter transaction date!");
          $("#exp_date").focus();
          return;
     }else if(exp_head.length == 0 || exp_head.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select an expense head").focus();
          $("#exp_head").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/edit_expense.php",
               data : {exp_id:exp_id, exp_date:exp_date, exp_head:exp_head, amount:amount, details:details},
               success : function(response){
               $("#reverse_dep").html(response);
               }
          })
     }
     setTimeout(function(){
          $("#reverse_dep").load("reverse_expense.php #reverse_dep");
     }, 1000);
     return false;    
}
//add reasons for removal
function addReason(){
     let reason = document.getElementById("reason").value;
     if(reason.length == 0 || reason.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input reason!");
          $("#reason").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_reason.php",
               data : {reason:reason},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#reason").val('');
     $("#reason").focus();
     return false;
}
//  get item history
function getItemHistory(item){
     let from_date = document.getElementById('from_date').value;
     let to_date = document.getElementById('to_date').value;
     let history_item = item;
     /* authentication */
     if(from_date.length == 0 || from_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date!");
          $("#from_date").focus();
          return;
     }else if(to_date.length == 0 || to_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a date range!");
          $("#to_date").focus();
          return;
     }else if(history_item.length == 0 || history_item.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select an item!");
          $("#history_item").focus();
          return;
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/get_history.php",
               data: {from_date:from_date, to_date:to_date, history_item:history_item},
               success: function(response){
               $(".new_data").html(response);
               }
          });
          $("#sales_item").html('');
          $("#history_item").val('');
     }
     return false;
}

// get sub menus to add to rights
function getSubmenu(menu_id){
     let menu = menu_id;
     // alert(menu_id);
     // return;
     if(menu_id){
          $.ajax({
               type : "POST",
               url :"../controller/get_submenu.php",
               data : {menu:menu},
               success : function(response){
                    $("#sub_menu").html(response);
               }
          })
          return false;
     }else{
          $("#sub_menu").html("<option value'' selected>Select a menu first</option>")
     }
     
}
// get user rights
function getRights(user_id){
     let user = user_id;;
     if(user){
          $.ajax({
               type : "POST",
               url :"../controller/get_rights.php",
               data : {user:user},
               success : function(response){
                    $(".rights").html(response);
               }
          })
          return false;
     }else{
          $(".rights").html("<h3>Select a user</h3>")
     }
     
}
//add user rights
function addRights(right){
     let sub_menu = right;
     let menu = document.getElementById("menu").value;
     let user = document.getElementById("user").value;
     if(user.length == 0 || user.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select user!");
          $("#user").focus();
          return;
     }else if(menu.length == 0 || menu.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a menu!");
          $("#menu").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_right.php",
               data : {user:user, menu:menu, sub_menu:sub_menu},
               success : function(response){
                    $(".info").html(response);
                    getRights(user);
               }              
          })
          return false;
     }

}
//delete right from user
function removeRight(right, user){
     let remove = confirm("Do you want to remove this right from the user?", "");
     if(remove){
          $.ajax({
               type : "GET",
               url : "../controller/delete_right.php?right="+right,
               success : function(response){
                    $(".info").html(response);
                    getRights(user);

               }
          })
     }else{
          return;
     }
}

/* download any table data to excel */
function convertToExcel(table, title){
     $(`#${table}`).table2excel({
          filename: title
     });
}

//show help
/* show frequenty asked questions */
function showFaq(answer){
     let all_answers = document.querySelectorAll(".faq_notes");
     all_answers.forEach(function(notes){
          notes.style.display = "none";
     })
     document.getElementById(answer).style.display = "block";
}

//display items in revenue by category for current date
function viewItems(department_id){
     let department = department_id;
     $.ajax({
          type : "Get",
          url : "../controller/view_revenue_cat_items.php?department="+department,
          success : function(response){
               $(".category_info").html(response);
          }
     })
     return false;
}
//display items in revenue by category for current date
function viewItemsDate(from, to, department_id){
     let department = department_id;
     $.ajax({
          type : "Get",
          url : "../controller/view_revenue_cat_items_date.php?department="+department+"&from="+from+"&to="+to,
          success : function(response){
               $(".category_info").html(response);
          }
     })
     return false;
}

//give discount
function giveDiscount(){
     discount = document.getElementById("discount").value;
     discount_invoice = document.getElementById("discount_invoice").value;
     discount_total = document.getElementById("discount_total").value;
     if(discount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("please enter a discount value!");
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/give_discount.php",
               data : {discount_invoice:discount_invoice, discount_total:discount_total, discount},
               success : function(response){
                    $(".sales_order").html(response);
               }
          })
          return false;
     }

}

//delete individual transfered items from transfer
function deleteTransfer(transfer, item){
     let confirmDel = confirm("Are you sure you want to remove this item?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_transfer.php?transfer_id="+transfer+"&item_id="+item,
               success : function(response){
                    $(".stocked_in").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}
//delete individual issued items from issue
function deleteIssued(issue_id, item){
     let confirmDel = confirm("Are you sure you want to remove this item?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_issued.php?issue_id="+issue_id+"&item_id="+item,
               success : function(response){
                    $(".stocked_in").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}

//post transfer
function postTransfer(invoice_number){
     invoice = invoice_number;
     confirmPost = confirm("Are you sure to post this transfer?", "");
     if(confirmPost){
          $.ajax({
               method : "GET",
               url : "../controller/post_transfer.php?invoice="+invoice,
               success : function(response){
                    $("#stockin").html(response);
               }
          })
          return false;
     }else{
          return;
     }
}
//post issued items
function postIssued(invoice_number){
     invoice = invoice_number;
     confirmPost = confirm("Are you sure to issue these items?", "");
     if(confirmPost){
          $.ajax({
               method : "GET",
               url : "../controller/post_issued.php?invoice="+invoice,
               success : function(response){
                    $("#stockin").html(response);
               }
          })
          setTimeout(function(){
               $("#issue_items").load("issue_items.php #issue_items");
          }, 1000);
          return false;
     }else{
          return;
     }
}
//Accept items transferred
function acceptItem(invoice_number){
     invoice = invoice_number;
     confirmPost = confirm("Are you sure to accept this item?", "");
     if(confirmPost){
          $.ajax({
               method : "GET",
               url : "../controller/accept_item.php?transfer_id="+invoice,
               success : function(response){
                    $("#accept_item").html(response);
               }
          })
          setTimeout(function(){
               $("#accept_item").load("accept_items.php #accept_item");
          }, 2000);
          return false
     }else{
          return;
     }
}
//Reject items transferred
function rejectItem(invoice_number){
     invoice = invoice_number;
     confirmPost = confirm("Are you sure to reject this item?", "");
     if(confirmPost){
          $.ajax({
               method : "GET",
               url : "../controller/reject_item.php?transfer_id="+invoice,
               success : function(response){
                    $("#accept_item").html(response);
               }
          })
          setTimeout(function(){
               $("#accept_item").load("accept_items.php #accept_item");
          }, 2000);
          return false
     }else{
          return;
     }
}
//Get stock balance by store
function getStockBalance(store_id){
     store = store_id;
     $.ajax({
          method : "POST",
          url : "../controller/get_stock_balance.php",
          data : {store:store},
          success : function(response){
               $(".store_balance").html(response);
          }
     })
     return false
     
}
//Get stock balance by category
function filterStockBalance(dep){
     dep_id = dep;
     $.ajax({
          method : "POST",
          url : "../controller/filter_cat_balance.php",
          data : {dep_id:dep_id},
          success : function(response){
               $("#all_balance").html(response);
          }
     })
     return false
     
}
function isValidEmail(email) {
    // Basic regex pattern for most email formats
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}
// Add new customer
function addCustomer(){
     let customer = document.getElementById("customer").value;
     let phone_number = document.getElementById("phone_number").value;
     let address = document.getElementById("address").value;
     let email = document.getElementById("email").value;
     let customer_type = document.getElementById("customer_type").value;
     if(customer.length == 0 || customer.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter customer name!");
          $("#customer").focus();
          return;
     }else if(phone_number.length == 0 || phone_number.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input customer phone number");
          $("#phone_number").focus();
          return;
     }else if(address.length == 0 || address.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input customer address");
          $("#address").focus();
          return;
     }else if(customer_type.length == 0 || customer_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select customer type");
          $("#customer_type").focus();
          return;
     }else if(email.length == 0 || email.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input customer email address");
          $("#email").focus();
          return;
     }else if(!isValidEmail(email)){
          alert("Please input a valid email address");
          $("#email").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_customer.php",
               data : {customer:customer, phone_number:phone_number, email:email, address:address, customer_type:customer_type},
               beforeSend : function(){
                    $(".info").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $(".info").html(response);
               }
          })
     }
     $("#customer").val('');
     $("#email").val('');
     $("#address").val('');
     $("#phone_number").val('');
     $("#customer_type").val('');
     $("#customer").focus();
     return false;    
}

//post other payments
//post other Transfer payments for guest
function postOtherPayment(){
     let mode = document.getElementById("mode").value;
     let posted = document.getElementById("posted").value;
     let customer = document.getElementById("customer").value;
     let invoice = document.getElementById("invoice").value;
     let amount = document.getElementById("amount").value;
     
     $.ajax({
          type : "POST",
          url : "../controller/post_other_payments.php",
          data : {posted:posted, customer:customer, mode:mode, amount:amount, invoice:invoice},
          success : function(response){
               $("#debt_payment").html(response);
          }
     })
     
     return false;    

}

//add menu
function addMenu(){
     let menu = document.getElementById("menu").value;
     if(menu.length == 0 || menu.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input menu!");
          $("#menu").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_menu.php",
               data : {menu:menu},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#menu").val('');
     $("#menu").focus();
     return false;
}
//add sub-menu
function addSubMenu(){
     let menus = document.getElementById("menus").value;
     let sub_menu = document.getElementById("sub_menu").value;
     let sub_menu_url = document.getElementById("sub_menu_url").value;
     if(menus.length == 0 || menus.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select menu!");
          $("#menus").focus();
          return;
     }else if(sub_menu.length == 0 || sub_menu.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input sub-menu!");
          $("#sub_menu").focus();
          return;
     }else if(sub_menu_url.length == 0 || sub_menu_url.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input sub-menu url!");
          $("#sub_menu_url").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_sub_menu.php",
               data : {menus:menus, sub_menu:sub_menu, sub_menu_url:sub_menu_url},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     // $("#menus").val('');
     $("#sub_menu").val('');
     $("#sub_menu_url").val('');
     $("#sub_menu").focus();
     return false;
}
//update submenu details
function updateSubMenu(){
     let sub_menu_id = document.getElementById("sub_menu_id").value;
     let menu = document.getElementById("menu").value;
     let sub_menu = document.getElementById("sub_menu").value;
     let url = document.getElementById("url").value;
     let status = document.getElementById("status").value;
     if(menu.length == 0 || menu.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select menuy!");
          $("#menu").focus();
          return;
     }else if(sub_menu.length == 0 || sub_menu.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input sub-menu!");
          $("#sub_menu").focus();
          return;
     }else if(url.length == 0 || url.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input sub-menu url!");
          $("#url").focus();
          return;
     }else if(status.length == 0 || status.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input sub-menu status!");
          $("#status").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_submenu.php",
               data: {sub_menu_id:sub_menu_id, menu:menu, sub_menu:sub_menu, url:url, status:status},
               success : function(response){
                    $("#change_sub_menu").html(response);
               }
          })
          setTimeout(function(){
               $("#change_sub_menu").load("edit_sub_menu.php #change_sub_menu");
          }, 1000);
          return false
     }
 }
 //get customer on key press
function getCustomers(input){
     let invoice = document.getElementById("invoice").value;
     let customer = input;
     $("#search_results").show();
     if(invoice.length == 0 || invoice.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input invoice number!");
          $("#invoice").focus();
          return;
     }else{
          if(input.length >= 3){
               $.ajax({
                    type : "POST",
                    url : "../controller/get_customer_name.php",
                    data : {customer:customer, invoice:invoice},
                    success : function(response){
                         $("#search_results").html(response);
                    }
               })
          }
     }
}
//get customer on key press
function getCustomersName(input, link){
     $("#search_results").show();
     if(input.length >= 3){
          $.ajax({
               type : "POST",
               url : "../controller/"+link+"?input="+input,
               success : function(response){
                    $("#search_results").html(response);
               }
          })
     }
     
}
//get sales rep on key press
function getSalesRep(input){
     $("#search_results").show();
     let invoice = document.getElementById("invoice").value;
     let customer = input;
     if(invoice.length == 0 || invoice.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input invoice number!");
          $("#invoice").focus();
          return;
     }else{
          if(input.length >= 3){
               $.ajax({
                    type : "POST",
                    url : "../controller/get_sales_rep.php",
                    data : {customer:customer, invoice:invoice},
                    success : function(response){
                         $("#search_results").html(response);
                    }
               })
          }
     }
}
 //get customer on key press for editing
function getCustomerEdit(input){
     $("#search_results").show();
     if(input.length >= 3){
          $.ajax({
               type : "POST",
               url : "../controller/get_customer_edit.php?input="+input,
               success : function(response){
                    $("#search_results").html(response);
               }
          })
     }
     
}
// update customer details
function updateCustomer(){
     let customer_id = document.getElementById("customer_id").value;
     let customer = document.getElementById("customer").value;
     let phone_number = document.getElementById("phone_number").value;
     let address = document.getElementById("address").value;
     let email = document.getElementById("email").value;
     let customer_type = document.getElementById("customer_type").value;
     if(customer.length == 0 || customer.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter customer name!");
          $("#customer").focus();
          return;
     }else if(phone_number.length == 0 || phone_number.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter customer phone number").focus();
          $("#phone_number").focus();
          return;
     }else if(address.length == 0 || address.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input customer address");
          $("#address").focus();
          return;
     }else if(email.length == 0 || email.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter customer email address");
          $("#email").focus();
          return;
     }else if(customer_type.length == 0 || customer_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select customer type");
          $("#customer_type").focus();
          return;
                return;
     }else if(!isValidEmail(email)){
          alert("Please input a valid email address");
          $("#email").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_customer.php",
               data : {customer_id:customer_id, customer:customer, phone_number:phone_number, email:email, address:address, customer_type:customer_type},
                beforeSend : function(){
                    $("#update_customer").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#update_customer").html(response);
                    setTimeout(function(){
                         showPage("edit_customer_info.php");
                    },2000)
               }
          })
     }
     $("#customer").focus();
     return false;    
}

 //pay debt
 function payDebt(invoice, customer, balance, amount_owed){
     /* if(parseFloat(amount_owed) > parseFloat(balance)){ */
     if(parseFloat(balance) > 0 ){
          alert("Insufficient balance! Kindly fund customer wallet to continue");
          return;
     }else{
          let confirm_pay = confirm("Are you sure to complete this transaction?", "");
          if(confirm_pay){
               $.ajax({
                    type : "GET",
                    url : "../controller/pay_debt.php?receipt="+invoice+"&customer="+customer+"&amount_owed="+amount_owed,
                    beforeSend :function(){
                          $("#pay_debts").html("<div class='processing'><div class='loader'></div</div>");
                    },
                    success : function(response){
                         $("#pay_debts").html(response);
                    }
               })
               setTimeout(() => {
                    $("#pay_debts").load("debt_payment.php?customer="+customer + "#pay_debts");
               }, 2000);
               return false;
          }else{
               return;
          }
     }
}

//reverse deposits
function reverseDeposit(deposit, customer){
     let confirm_reverse = confirm("Are you sure you want to reverse this transaction?", "");
     if(confirm_reverse){
          $.ajax({
               type : "GET",
               url : "../controller/reverse_deposit.php?deposit_id="+deposit+"&customer="+customer,
               success : function(response){
                    $("#reverse_dep").html(response);
               }
          })
          setTimeout(() => {
               $("#reverse_dep").load("reverse_deposit.php #reverse_dep");
          }, 1500);
          return false;
          
     }else{
          return;
     }
}
//reverse expenses
function reverseExpense(expense){
     let confirm_reverse = confirm("Are you sure you want to reverse this transaction?", "");
     if(confirm_reverse){
          $.ajax({
               type : "GET",
               url : "../controller/reverse_expense.php?expense_id="+expense,
               success : function(response){
                    $("#reverse_dep").html(response);
               }
          })
          setTimeout(() => {
               $("#reverse_dep").load("reverse_expense.php #reverse_dep");
          }, 1500);
          return false;
          
     }else{
          return;
     }
}
//edit expense
function editExpense(expense){
     let expense_id = expense;
     $.ajax({
          type : "GET",
          url : "../controller/expense_form.php?expense_id="+expense_id,
          success : function(response){
               $(".info").html(response);
               window.scrollTo(0, 0);
          }
     })
     return false;
}
// Fund customer wallet via deposit 
function deposit(){
     let invoice = document.getElementById("invoice").value;
     let posted = document.getElementById("posted").value;
     let trans_date = document.getElementById("trans_date").value;
     let customer = document.getElementById("customer").value;
     let store = document.getElementById("store").value;
     let amount = document.getElementById("amount").value;
     let payment_mode = document.getElementById("payment_mode").value;
     let details = document.getElementById("details").value;
     let bank = document.getElementById("bank").value;
     if(payment_mode == "POS" || payment_mode == "Transfer"){
          if(bank.length == 0 || bank.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select bank!");
               $("#bank").focus();
               return;
          }    
     }
     if(payment_mode.length == 0 || payment_mode.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select payment_mode!");
          $("#payment_mode").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(trans_date.length == 0 || trans_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction date");
          $("#trans_date").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/deposit.php",
               data : {posted:posted, customer:customer, payment_mode:payment_mode, amount:amount, details:details, store:store, invoice:invoice, bank:bank, trans_date:trans_date},
               success : function(response){
               $("#fund_account").html(response);
               }
          })
     }
     return false;    
}
// Fund customer wallet via dcustomer statement 
function customerDeposit(){
     let invoice = document.getElementById("invoice").value;
     let posted = document.getElementById("posted").value;
     let trans_date = document.getElementById("trans_date").value;
     let customer = document.getElementById("customer").value;
     let store = document.getElementById("store").value;
     let amount = document.getElementById("amount").value;
     let payment_mode = document.getElementById("payment_mode").value;
     let details = document.getElementById("details").value;
     let bank = document.getElementById("bank").value;
     if(payment_mode == "POS" || payment_mode == "Transfer"){
          if(bank.length == 0 || bank.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select bank!");
               $("#bank").focus();
               return;
          }    
     }
     if(payment_mode.length == 0 || payment_mode.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select payment_mode!");
          $("#payment_mode").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(trans_date.length == 0 || trans_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction date");
          $("#trans_date").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/customer_deposit.php",
               data : {posted:posted, customer:customer, payment_mode:payment_mode, amount:amount, details:details, store:store, invoice:invoice, bank:bank, trans_date:trans_date},
               success : function(response){
               $("#fund_account").html(response);
               }
          })
          /* setTimeout(function(){
               $("#fund_account").load("fund_account.php?customer="+customer+ "#fund_account");
          }, 100); */
     }
     return false;    
}

// prinit deposit receipt for fund wallet
function printDepositReceipt(invoice){
     window.open("../controller/deposit_receipt.php?receipt="+invoice)
     return false;
 
 }
// prinit receipt for customer land payment
function printPaymentReceipt(invoice){
     window.open("../controller/field_payment_receipt.php?receipt="+invoice)
     return false;
 
 }
// prinit receipt for customer documentation payment
function printDocReceipt(invoice){
     window.open("../controller/documentation_receipt.php?receipt="+invoice)
     return false;
 
 }
 //get item to check history
function getHistoryItems(item_name){
     let item = item_name;
     if(item.length >= 3){
          if(item){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_history_items.php",
                    data : {item:item},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}

//get product to make from raw materials
function getProduct(product){
     let item = product;
     // alert(check_room);
     // return;
     if(item.length >= 3){
          if(product){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_product.php",
                    data : {item:item},
                    beforeSend : function(){
                         $("#sales_item").html("<p>Searching....</p>");
                    },
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}
//add produce for production
function addProduce(id, name){
     let product = document.getElementById("product");
     let item = document.getElementById("item");
     product.value = id;
     item.value = name;
     product.setAttribute('readonly', true);
     item.setAttribute('readonly', true);
     $("#sales_item").html('');
}

//get raw material for production
function getRawItem(raw_item){
     let item_raw = raw_item;
     let item = document.getElementById("item").value;
     let product = document.getElementById("product").value;
     let product_qty = document.getElementById("product_qty").value;
     let product_num = document.getElementById("product_num").value;
     // let product_input = document.getElementById("product_qty");
     // return;
     if(product.length == 0 || product.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select product for production!");
          $("#item").focus();
          return;
     }else if(product_qty.length == 0 || product_qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter product quantity!");
          $("#product_qty").focus();
          return;
     }else if(product_qty <= 0){
          alert("Product quantity can not be less than 1.");
          $("#product_qty");
          return
     }else{
          if(item_raw.length >= 3){
               if(item_raw){
                    $.ajax({
                         type : "POST",
                         url :"../controller/get_raw_material_form.php",
                         beforeSend : function(){
                              $("#raw_item").html("<p>Searching...</p>");
                         },
                         data : {item_raw:item_raw},
                         success : function(response){
                              $("#raw_item").html(response);
                         }
                    })
                    product_input.setAttribute('readonly', true);
                    return false;
               }else{
                    $("#raw_item").html("<p>Please enter atleast 3 letters</p>");
               }
               /* alert(item_raw);
               return; */
          }
     }
     
     
}
//display raw material form
function displayRawMaterial(item_id, raw_qty){
     let product = document.getElementById("product").value;
     let product_qty = document.getElementById("product_qty").value;
     let product_num = document.getElementById("product_num").value;
     // let product_input = document.getElementById("product_qty");
     // let item = item_id;
     // let prod = document.getElementById("product");
     if(parseFloat(raw_qty) <= 0){
          alert("Item has 0 quantity! Cannot proceed");
          $("#item_raw").focus();
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/get_raw_material_details.php",
               data : {item_id:item_id, product:product, product_qty:product_qty, product_num:product_num, raw_qty:raw_qty},
               beforeSend : function(){
                    $(".info").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $(".info").html(response);
               }
          })
          $("#raw_item").html("");
          $("#item_raw").val('');
          prod.setAttribute('readonly', true);
          return false;
     }
     
 }

 //add raw material for production
function addRawMaterial(){
     let posted_by = document.getElementById("posted_by").value;
     let store = document.getElementById("store").value;
     let product_number = document.getElementById("product_number").value;
     let product = document.getElementById("product").value;
     let product_qty = document.getElementById("product_qty").value;
     let item_id = document.getElementById("item_id").value;
     let item_quantity = document.getElementById("item_quantity").value;
     if(item_quantity.length == 0 || item_quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity used!");
          $("#item_quantity").focus();
          return;
     }else if(parseFloat(item_quantity) <= 0){
          alert("Please input quantity used!");
          $("#item_quantity").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_raw_material.php",
               data : {posted_by:posted_by, store:store, product:product, product_number:product_number, product_qty:product_qty, item_id:item_id, item_quantity:item_quantity},
               beforeSend : function(){
                    $(".stocked_in").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $(".stocked_in").html(response);
               }
          })
          /* $("#quantity").val('');
          $("#expiration_date").val('');
          $("#quantity").focus(); */
          $(".info").html('');
          return false; 
     }
}

//delete individual raw materials from production
function deleteMaterial(production, item){
     let confirmDel = confirm("Are you sure you want to remove this item?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_production_material.php?production_id="+production+"&item_id="+item,
               success : function(response){
                    $(".stocked_in").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}

//post final production
function postProduction(production_number){
     invoice = production_number;
     confirmPost = confirm("Are you sure to post this production?", "");
     if(confirmPost){
          $.ajax({
               method : "GET",
               url : "../controller/post_production.php?invoice="+invoice,
               success : function(response){
                    $("#produce").html(response);
               }
          })
          setTimeout(function(){
               $("#produce").load("make_product.php #produce");
          }, 2000)
          return false;
     }else{
          return;
     }
}
//ice cream section
//get raw material to make product
function getIceMaterial(material){
     let item = material;
     // alert(check_room);
     // return;
     if(item.length >= 3){
          if(product){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_ice_material.php",
                    data : {item:item},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}
//add ice cream material for production
function addIceMaterial(id, name){
     let material = document.getElementById("material");
     let item = document.getElementById("item");
     material.value = id;
     item.value = name;
     material.setAttribute('readonly', true);
     item.setAttribute('readonly', true);
     $("#sales_item").html('');
}
//get ice product for production
function getIceProduct(raw_item){
     let product = raw_item;
     let item = document.getElementById("item").value;
     let material = document.getElementById("material").value;
     let material_qty = document.getElementById("material_qty").value;
     let product_num = document.getElementById("product_num").value;
     let product_input = document.getElementById("material_qty");
     // return;
     if(material.length == 0 || material.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select raw material for production!");
          $("#item").focus();
          return;
     }else if(item.length == 0 || item.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter raw material quantity!");
          $("#item").focus();
          return;
     }else if(material_qty.length == 0 || material_qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter raw material quantity!");
          $("#product_qty").focus();
          return;
     }else if(material_qty <= 0){
          alert("Raw material quantity can not be less than 1.");
          $("#product_qty");
          return
     }else{
          if(product.length >= 3){
               if(product){
                    $.ajax({
                         type : "POST",
                         url :"../controller/get_ice_product_form.php",
                         data : {product:product, material:material, material_qty:material_qty, product_num:product_num},
                         success : function(response){
                              $("#raw_item").html(response);
                         }
                    })
                    product_input.setAttribute('readonly', true);
                    return false;
               }else{
                    $("#raw_item").html("<p>Please enter atleast 3 letters</p>");
               }
               /* alert(item_raw);
               return; */
          }
     }
     
     
}
//display ice product form
function displayIceProduct(item_id){
     let item = item_id;
     let prod = document.getElementById("material");
          $.ajax({
               type : "GET",
               url : "../controller/get_ice_product_details.php?item="+item,
               success : function(response){
                    $(".info").html(response);
               }
          })
          $("#raw_item").html("");
          $("#item_raw").val('');
          prod.setAttribute('readonly', true);
          return false;
     // }
     
 }
 //add ice product for production
function addIceProduct(){
     let posted_by = document.getElementById("posted_by").value;
     let store = document.getElementById("store").value;
     let product_number = document.getElementById("product_number").value;
     let material = document.getElementById("material").value;
     let material_qty = document.getElementById("material_qty").value;
     let item_id = document.getElementById("item_id").value;
     let item_quantity = document.getElementById("item_quantity").value;
     if(item_quantity.length == 0 || item_quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity produced!");
          $("#item_quantity").focus();
          return;
     }else if(item_quantity <= 0){
          alert("Please input quantity produced!");
          $("#item_quantity").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_ice_product.php",
               data : {posted_by:posted_by, store:store, material:material, product_number:product_number, material_qty:material_qty, item_id:item_id, item_quantity:item_quantity},
               success : function(response){
               $(".stocked_in").html(response);
               }
          })
          /* $("#quantity").val('');
          $("#expiration_date").val('');
          $("#quantity").focus(); */
          $(".info").html('');
          return false; 
     }
}
//delete individual products from production
function deleteIceProduct(production, item){
     let confirmDel = confirm("Are you sure you want to remove this item?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_production_product.php?production_id="+production+"&item_id="+item,
               success : function(response){
                    $(".stocked_in").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}
//post final ice cream production
function postIceProduction(production_number){
     invoice = production_number;
     confirmPost = confirm("Are you sure to post this production?", "");
     if(confirmPost){
          $.ajax({
               method : "GET",
               url : "../controller/post_ice_production.php?invoice="+invoice,
               success : function(response){
                    $("#produce").html(response);
               }
          })
          setTimeout(function(){
               $("#produce").load("make_ice_cream.php #produce");
          }, 2000)
          return false;
     }else{
          return;
     }
}
//get item to transfer quantity from
function getItemToTransfer(product){
     let item = product;
     // alert(check_room);
     // return;
     if(item.length >= 3){
          if(product){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_item_to_transfer.php",
                    data : {item:item},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}

//add item to transfer quantity from
function addItemToTransfer(id, name){
     let transfer_qty_from = document.getElementById("transfer_qty_from");
     let item = document.getElementById("item");
     transfer_qty_from.value = id;
     item.value = name;
     /* transfer_qty_from.setAttribute('readonly', true);
     item.setAttribute('readonly', true); */
     $("#sales_item").html('');
}
//get item to transfer quantity to
function getItemToTransferTo(product){
     let item_to = product;
     // alert(check_room);
     // return;
     if(item_to.length >= 3){
          if(product){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_item_to_transfer_to.php",
                    data : {item_to:item_to},
                    success : function(response){
                         $("#transfer_item").html(response);
                    }
               })
               return false;
          }else{
               $("#transfer_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}

//add item to transfer quantity into
function addItemToTransferTo(id, name){
     let transfer_qty_to = document.getElementById("transfer_qty_to");
     let item = document.getElementById("item");
     let item_to = document.getElementById("item_to");
     transfer_qty_to.value = id;
     item_to.value = name;
     // transfer_qty_from.setAttribute('readonly', true);
     item.setAttribute('readonly', true);
     $("#transfer_item").html('');
}

// transfer quantity from one item to another
function transferQty(){
     let transfer_qty_from = document.getElementById("transfer_qty_from").value;
     let transfer_qty_to = document.getElementById("transfer_qty_to").value;
     let remove_qty = document.getElementById("remove_qty").value;
     let add_qty = document.getElementById("add_qty").value;
     if(transfer_qty_to.length == 0 || transfer_qty_to.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Item to transfer quantity into!");
          $("#transfer_qty_to").focus();
          return;    
     }else if(remove_qty.length == 0 || remove_qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity to remove!");
          $("#remove_qty").focus();
          return;
     
     }else if(add_qty.length == 0 || add_qty.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity to add!");
          $("#add_qty").focus();
          return;
     }else if(add_qty <= 0 || remove_qty <= 0){
          alert("Quantity cannot be less than or equal to 0!");
          $("#remove_qty").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/transfer_quantity.php",
               data : {transfer_qty_from:transfer_qty_from, transfer_qty_to:transfer_qty_to, remove_qty:remove_qty, add_qty:add_qty},
               success : function(response){
                    $("#transfer_quantities").html(response);
               }
          })
          setTimeout(function(){
               $("#transfer_quantities").load("transfer_qty.php #transfer_quantities");
          }, 1500)
          return false;
     }
}

//post CUSTOMER debt payments
function postDebt(){
     let customer = document.getElementById("customer").value;
     let amount = document.getElementById("amount").value;
     
     if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter amount!");
          $("#amount").focus();
          return;
     
     }else if(amount <= 0){
          alert("Amount can not be less than or  equal to 0");
          return;
     
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_debt.php",
               data: {customer:customer, amount:amount},
               success : function(response){
                    $("#post_debt").html(response);
               }
          })
          setTimeout(function(){
               $("#post_debt").load("post_debt.php #post_debt");
          }, 1500);
          return false
     }
 }
//post vendor balance
function postBalance(){
     let vendor = document.getElementById("vendor").value;
     let amount = document.getElementById("amount").value;
     
     if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter amount!");
          $("#amount").focus();
          return;
     
     }else if(amount <= 0){
          alert("Amount can not be less than or  equal to 0");
          return;
     
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_balance.php",
               data: {vendor:vendor, amount:amount},
               success : function(response){
                    $("#post_debt").html(response);
               }
          })
          setTimeout(function(){
               $("#post_debt").load("post_vendor_balance.php #post_debt");
          }, 1500);
          return false
     }
 }
 //post vendor payment
function postPayment(){
     let vendor = document.getElementById("vendor").value;
     let contra = document.getElementById("contra").value;
     let amount = document.getElementById("amount").value;
     let trans_date = document.getElementById("trans_date").value;
     let todayDate = new Date();
     if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter amount!");
          $("#amount").focus();
          return;
     }else if(trans_date.length == 0 || trans_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter transaction date!");
          $("#trans_date").focus();
          return;
     }else if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select ledger!");
          $("#contra").focus();
          return;
     
     }else if(amount <= 0){
          alert("Amount can not be less than or  equal to 0");
          $("#amount").focus();

          return;
     }else if(new Date(trans_date) > todayDate){
          alert("You can not post a futuristic transaction");
          $("#trans_date").focus();
          return;
     
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_vendor_payments.php",
               data: {vendor:vendor, amount:amount, trans_date:trans_date, contra:contra},
               beforeSend :function(){
                    $("#post_debt").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#post_debt").html(response);
               }
          })
          setTimeout(function(){
               $("#post_debt").load("post_vendor_payments.php #post_debt");
          }, 1500);
          return false
     }
 }

 //post purchases
function postPurchases(){
     let total_amount = document.getElementById("total_amount").value;
     let waybill = document.getElementById("waybill").value;
     let purchase_invoice = document.getElementById("purchase_invoice").value;
     let store = document.getElementById("store").value;
     let vendor = document.getElementById("vendor").value;
     let payment_type = document.getElementById("payment_type").value;
     let deposited_amount = document.getElementById("deposited_amount");
     let cr_ledger = document.getElementById("cr_ledger");
     let deposit_amount = document.getElementById("deposit_amount").value;
     let contra = document.getElementById("contra").value;
     
     if(deposited_amount.style.display == "block"){
          if(parseFloat(deposit_amount) <= 0){
               alert("Deposit Amount cannot be less than 0 or equal to 0");
               $("#deposit_amount").focus();
               return;
          }
     }
     
     if(cr_ledger.style.display == "block"){
          
          if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select ledger!");
               $("#contra").focus();
               return;
          }
     }
     
     if(payment_type.length == 0 || payment_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a payment option!");
          $("#payment_type").focus();
          return;
     }else if(parseFloat(waybill) < 0){
          alert("Please enter logistics/waybill amount or enter 0!");
          $("#waybill").focus();
          return;
     }else{
          let confirmPost = confirm("Are you sure you want to post this purchase?", "");
          if(confirmPost){
               $.ajax({
                    type : "POST",
                    url : "../controller/post_purchase.php",
                    data : {purchase_invoice:purchase_invoice, payment_type:payment_type, waybill:waybill, store:store, deposit_amount, vendor:vendor, total_amount:total_amount, contra:contra},
                    beforeSend :function(){
                         $("#post_purchase").html("<div class='processing'><div class='loader'></div></div>");
                    },
                    success : function(response){
                         $("#post_purchase").html(response);
                    }
               })
               setTimeout(function(){
                    $("#post_purchase").load("post_purchase.php #post_purchase");
               }, 1500);
               return false;
          }else{
               return;
          }
     }
}

//get correct customer to merge files
function getCorCustomer(customer){
     let item = customer;
     // alert(check_room);
     // return;
     if(item.length >= 3){
          if(item){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_cor_customer.php",
                    data : {item:item},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}

//add correct customer  for merging files
function addCorCustomer(id, name){
     let correct_customer = document.getElementById("correct_customer");
     let item = document.getElementById("item");
     correct_customer.value = id;
     item.value = name;
     // correct_customer.setAttribute('readonly', true);
     // item.setAttribute('readonly', true);
     $("#sales_item").html('');
}
//get wrong customer to merge files
function getWrongCustomer(customer){
     let item_raw = customer;
     // alert(check_room);
     // return;
     if(item_raw.length >= 3){
          if(item_raw){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_wrong_customer.php",
                    data : {item_raw:item_raw},
                    success : function(response){
                         $("#raw_item").html(response);
                    }
               })
               return false;
          }else{
               $("#raw_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}

//add wrong customer  for merging files
function addWrongCustomer(id, name){
     let wrong_customer = document.getElementById("wrong_customer");
     let item_raw = document.getElementById("item_raw");
     wrong_customer.value = id;
     item_raw.value = name;
     // correct_customer.setAttribute('readonly', true);
     // item.setAttribute('readonly', true);
     $("#raw_item").html('');
}

//merge files
function mergeFiles(){
     let correct_customer = document.getElementById("correct_customer").value;
     let wrong_customer = document.getElementById("wrong_customer").value;
     if(correct_customer == wrong_customer){
          alert("You can not merge same customer");
          $("#item").focus();
          return;
     }else if(correct_customer.length == 0 || correct_customer.replace(/^\s+|\s+$/g, "").length == 0){
          alert("please select the correct customer");
          $("#correct_customer").focus();
          return;
     }else if(wrong_customer.length == 0 || wrong_customer.replace(/^\s+|\s+$/g, "").length == 0){
          alert("please select the wrong  customer name");
          $("#wrong_customer").focus();
          return;
     }else{
          let confirmMerge = confirm("Are you sure you want to merge these files?", "");
          if(confirmMerge){
               $.ajax({
                    type : "POST",
                    url : "../controller/merge_files.php",
                    data : {correct_customer:correct_customer, wrong_customer:wrong_customer},
                    success : function(response){
                        $("#merge_files").html(response); 
                    }
               })
               setTimeout(function(){
                    $("#merge_files").load("merge_files.php #merge_files");
               }, 1000);
               return false;
          }else{
               return;
          }
     }
}

//Update invoice
function updateInvoice(){
     let sales_invoice = document.getElementById("sales_invoice").value;
     // let store = document.getElementById("store").value;
     let customer_id = document.getElementById("customer_id").value;
     // let payment_type = document.getElementById("payment_type").value;
     // let post_date = document.getElementById("post_date").value;
     // let todayDate = new Date();
     // let today = todayDate;
     let confirmPost = confirm("Are you sure you want to update this invoice?", "");
     if(confirmPost){
          $.ajax({
               type : "GET",
               url : "../controller/update_customer_invoice.php?invoice="+sales_invoice+"&customer="+customer_id,
               success : function(response){
                    $("#direct_sales").html(response);
               }
          })
          $(".sales_order").html('');
          setTimeout(function(){
               $("#direct_sales").load("update_invoices.php #direct_sales");
          }, 200);
          return false;
     }
     
     
}

// Add new invoice
function addInvoice(){
     let customer = document.getElementById("customer").value;
     let posted_by = document.getElementById("posted_by").value;
     let details = document.getElementById("details").value;
     let invoice = document.getElementById("invoice").value;
     let amount = document.getElementById("amount").value;
     
     if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter prescription details!");
          $("#details").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input drug name!");
          $("#drug").focus();
          return;
     
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_invoice.php",
               data : {customer:customer, posted_by:posted_by, invoice:invoice, details:details, amount:amount},
               success : function(response){
               $(".sales_order").html(response);
               }
          })
     }
     $("#amount").val('');
     $("#details").val('');
     return false;    
}
//get form to update details for a single invoice
function updateDetails(id){
     $.ajax({
          type : "GET",
          url : "../controller/edit_invoice.php?item="+id,
          success : function(response){
               $(".show_more").html(response);
               // window.scrollTo(0, 0);
               document.getElementById("showMore").scrollIntoView();
          }
          
     })
     return false;
}
//update details in invoice
function editDetails(){
     let invoice_id = document.getElementById("invoice_id").value;
     let invoice = document.getElementById("invoice").value;
     let amount_update = document.getElementById("amount_update").value;
     let details_update = document.getElementById("details_update").value;
     /* authentication */
     if(amount_update.length == 0 || amount_update.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter agreed amount!");
          $("#amount_update").focus();
          return;
     }else if(details_update.length == 0 || details_update.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter details!");
          $("#details_update").focus();
          return;
     
     }else{
          $.ajax({
               type: "POST",
               url: "../controller/update_invoice.php",
               data: {invoice_id:invoice_id, amount_update:amount_update, details_update:details_update, invoice:invoice},
               success: function(response){
               $(".sales_order").html(response);
               }
          });

     }
     $(".show_more").html('');
     return false;
}

//delete details from invoices
function deleteDetails(id, invoice){
     let confirmDel = confirm("Are you sure you want to remove this item?", "");
     if(confirmDel){
          
          $.ajax({
               type : "GET",
               url : "../controller/delete_details.php?invoice="+invoice+"&item_id="+id,
               success : function(response){
                    $(".sales_order").html(response);
               }
               
          })
          return false;
     }else{
          return;
     }
}

//post prescription
function postInvoice(invoice){
     let confirmPost = confirm("Are you sure you want to post this Invoice?", "");
     if(confirmPost){
          
          $.ajax({
               type : "GET",
               url : "../controller/post_invoice.php?invoice="+invoice,
               success : function(response){
                    $("#sales_order").html(response);
               }
          })
          $(".sales_order").html('');
              
     // }
     }else{
          return;
     }
}

//print prescription
function printInvoice(invoice){
     window.open("../controller/invoice_receipt.php?receipt="+invoice);
     
     return false;
 
 }

 //get supplier for stockin
function getSupplier(sup){
     let supplier = sup;
     let invoice = document.getElementById("invoice").value;
     if(invoice.length == 0 || invoice.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input invoice number!");
          $("#invoice").focus();
          return;
     }else{
          if(supplier.length >= 3){
               if(supplier){
                    $.ajax({
                         type : "POST",
                         url :"../controller/get_supplier.php",
                         data : {supplier:supplier},
                         beforeSend : function(){
                              $("#transfer_item").html("<p>Searching...</p>");
                         },
                         success : function(response){
                              $("#transfer_item").html(response);
                         }
                    })
                    $("#invoice").attr("readonly", true);
                    return false;
               }else{
                    $("#transfer_item").html("<p>Please enter atleast 3 letters</p>");
               }
          }
     }
}
 //get supplier for purchase order
function getPOSupplier(sup){
     let supplier = sup;
     if(supplier.length >= 3){
          if(supplier){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_po_supplier.php",
                    data : {supplier:supplier},
                    beforeSend : function(){
                         $("#transfer_item").html("<p>Searching...</p>");
                    },
                    success : function(response){
                         $("#transfer_item").html(response);
                    }
               })
               return false;
          }else{
               $("#transfer_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}
//select vendor during stocking
function addvendor(id, vendor_name){
     let supplier = document.getElementById("supplier");
     let vendor = document.getElementById("vendor");
     supplier.value = vendor_name;
     vendor.value = id;
     // alert(vendor.value)
     $("#vendor").attr("readonly", true);
     $("#supplier").attr("readonly", true);
     $("#transfer_item").html('');
}

// Post stockin
function postStockin(){
     let sales_invoice = document.getElementById("sales_invoice").value;
     let suppliers = document.getElementById("suppliers").value;
     let waybill = document.getElementById("waybill").value;
     if(waybill.length == 0 || waybill.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input waybill");
          $("#waybill").focus();
          return;
     }else if(waybill < 0){
          alert("Please input waybill");
          $("#waybill").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_stockin.php",
               data : {sales_invoice:sales_invoice,suppliers:suppliers, waybill:waybill},
               beforeSend : function(){
                    $("#stockin").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $("#stockin").html(response);
               }
          })
          setTimeout(function(){
               // $('#stockin').load("stockin_purchase.php #stockin");
               showPage("stockin_purchase.php");
          }, 1500);
          return false;    
     }
}
// Post purchase order
function postPO(){
     let sales_invoice = document.getElementById("sales_invoice").value;
     let suppliers = document.getElementById("suppliers").value;
     let confirm_post = confirm("Are you sure you want to post this purchase Order?", "");
     if(confirm_post){
           $.ajax({
               type : "POST",
               url : "../controller/post_po.php",
               data : {sales_invoice:sales_invoice,suppliers:suppliers},
               beforeSend : function(){
                    $("#stockin").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $("#stockin").html(response);
               }
          })
          
     }else{
          return;
     }
}

//add account group
function addAccountGroup(){
     let account_group = document.getElementById("account_group").value;
     if(account_group.length == 0 || account_group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input account group!");
          $("#account_group").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_account_group.php",
               data : {account_group:account_group},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#account_group").val('');
     $("#account_group").focus();
     return false;
}
//add account sub group
function addSubGroup(){
     let group = document.getElementById("group").value;
     let sub_group = document.getElementById("sub_group").value;
     if(group.length == 0 || group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select account group!");
          $("#group").focus();
          return;
     }else if(sub_group.length == 0 || sub_group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter account sub-group!");
          $("#sub_group").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_account_sub_group.php",
               data : {group:group, sub_group:sub_group},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#sub_group").val('');
     $("#sub_group").focus();
     return false;
}
//add account class
function addClass(){
     let group = document.getElementById("group").value;
     let sub_group = document.getElementById("sub_group").value;
     let account_class = document.getElementById("account_class").value;
     if(group.length == 0 || group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select account group!");
          $("#group").focus();
          return;
     }else if(sub_group.length == 0 || sub_group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select account sub-group!");
          $("#sub_group").focus();
          return;
     }else if(account_class.length == 0 || account_class.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input account class!");
          $("#account_class").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_account_class.php",
               data : {group:group, sub_group:sub_group, account_class:account_class},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#account_class").val('');
     $("#account_class").focus();
     return false;
}
//add account ledgers
function addLedger(){
     let group = document.getElementById("group").value;
     let sub_group = document.getElementById("sub_group").value;
     let account_class = document.getElementById("account_class").value;
     let ledger = document.getElementById("ledger").value;
     if(group.length == 0 || group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select account group!");
          $("#group").focus();
          return;
     }else if(sub_group.length == 0 || sub_group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select account sub-group!");
          $("#sub_group").focus();
          return;
     }else if(account_class.length == 0 || account_class.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select account class!");
          $("#account_class").focus();
          return;
     }else if(account_class.length == 0 || account_class.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input account ledger!");
          $("#ledger").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_account_ledger.php",
               data : {group:group, sub_group:sub_group, account_class:account_class, ledger:ledger},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#ledger").val('');
     $("#ledger").focus();
     return false;
}
// get account sub group from account group for creatig class
function getSubGroup(post_group){
     let group = post_group;
     if(group){
          $.ajax({
               type : "POST",
               url :"../controller/get_sub_group.php",
               data : {group:group},
               success : function(response){
                    $("#sub_group").html(response);
               }
          })
          return false;
     }else{
          $("#sub_group").html("<option value'' selected>Select group first</option>")
     }
     
}
 // get account class from account sub group for creatig class
function getClass(post_sub){
     let sub_group = post_sub;
     // alert(sub_group)
     if(sub_group){
          $.ajax({
               type : "POST",
               url :"../controller/get_class.php",
               data : {sub_group:sub_group},
               success : function(response){
                    $("#account_class").html(response);
               }
          })
          return false;
     }else{
          $("#account_class").html("<option value'' selected>Select sub group first</option>")
     }
     
}

//get ledger name to update
function getLedger(ledger){
     let item = ledger;
     // alert(check_room);
     // return;
     if(item.length >= 3){
          if(item){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_ledger_details.php",
                    data : {item:item},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}

// update ledger details
function updateLedger(){
     let ledger_id = document.getElementById("ledger_id").value;
     let ledger_name = document.getElementById("ledger_name").value;
     let account_class = document.getElementById("account_class").value;
     let sub_group = document.getElementById("sub_group").value;
     let group = document.getElementById("group").value;
     if(ledger_name.length == 0 || ledger_name.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter ledger name!");
          $("#ledger_name").focus();
          return;
     }else if(group.length == 0 || group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select account group")
          $("#group").focus();
          return;
     }else if(sub_group.length == 0 || sub_group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select account sub-group");
          $("#sub_group").focus();
          return;
     }else if(account_class.length == 0 || account_class.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select account class");
          $("#account_class").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_ledger.php",
               data : {ledger_id:ledger_id, ledger_name:ledger_name, group:group, sub_group:sub_group, account_class:account_class},
               success : function(response){
               $("#update_customer").html(response);
               }
          })
     }
     // $("#customer").focus();
     setTimeout(function(){
          $("#update_customer").load("update_ledger.php #update_customer");
     }, 1000)
     return false;    
}

//get ledger
function getAccountLedger(item_name){
     let ledger = item_name;
     // alert(check_room);
     // return;
     if(ledger.length >= 3){
          if(ledger){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_account_ledger.php",
                    data : {ledger:ledger},
                    success : function(response){
                         $("#search_results").html(response);
                    }
               })
               return false;
          }else{
               $("#search_results").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}

//get contra ledger
function getContraLedger(item_name){
     let contra = item_name;
     // alert(check_room);
     // return;
     if(contra.length >= 3){
          if(contra){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_contra_ledger.php",
                    data : {contra:contra},
                    success : function(response){
                         $("#transfer_item").html(response);
                    }
               })
               return false;
          }else{
               $("#transfer_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}
//add account ledger while posting opening balance
function addAccount(ledger_id, ledger_name){
     let ledger = document.getElementById("ledger");
     let bal_ledger = document.getElementById("bal_ledger");
     ledger.value = ledger_name;
     bal_ledger.value = ledger_id;
     $("#search_results").html('');
}
//add contra ledger while posting other transactions
function addContra(ledger_id, ledger_name){
     let contra = document.getElementById("contra");
     let contra_ledger = document.getElementById("contra_ledger");
     contra.value = ledger_name;
     contra_ledger.value = ledger_id;
     $("#transfer_item").html('');
     // alert(contra_ledger.value);
}

//post opening balance
function postOpeningBalance(){
     let posted = document.getElementById("posted").value;
     let store = document.getElementById("store").value;
     let exp_date = document.getElementById("exp_date").value;
     let trans_type = document.getElementById("trans_type").value;
     let bal_ledger = document.getElementById("bal_ledger").value;
     // let contra = document.getElementById("contra").value;
     let amount = document.getElementById("amount").value;
     let details = document.getElementById("details").value;
     let todayDate = new Date();
     // let today = todayDate.toLocaleDateString();
     if(exp_date.length == 0 || exp_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter transaction date!");
          $("#exp_date").focus();
          return;
     }else if(bal_ledger.length == 0 || bal_ledger.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select ledger")
          $("#ledger").focus();
          return;
     /* }else if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Contra ledger");
          $("#contra").focus();
          return; */
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(amount <= 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(trans_type.length == 0 || trans_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select transaction type");
          $("#trans_type").focus();
          return;
     }else if(new Date(exp_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#exp_date").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_opening_balance.php",
               data : {posted:posted, exp_date:exp_date, bal_ledger:bal_ledger, /* contra:contra, */ amount:amount, details:details, store:store, trans_type:trans_type},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     // $("#exp_date").val('');
     $("#ledger").val('');
     $("#bal_ledger").val('');
     // $("#contra").val('');
     $("#amount").val('');
     $("#trans_type").val('');
     $("#ledger").val('');
     // $("#details").val('');
     $("#exp_date").focus();
     return false;    
}
//post other transactions
function postOtherTrx(){
     let posted = document.getElementById("posted").value;
     let store = document.getElementById("store").value;
     let exp_date = document.getElementById("exp_date").value;
     let trans_type = document.getElementById("trans_type").value;
     let bal_ledger = document.getElementById("bal_ledger").value;
     let contra_ledger = document.getElementById("contra_ledger").value;
     let amount = document.getElementById("amount").value;
     let details = document.getElementById("details").value;
     let todayDate = new Date();
     // let today = todayDate.toLocaleDateString();
     if(exp_date.length == 0 || exp_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter transaction date!");
          $("#exp_date").focus();
          return;
     }else if(bal_ledger.length == 0 || bal_ledger.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select ledger")
          $("#ledger").focus();
          return;
     }else if(contra_ledger.length == 0 || contra_ledger.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Contra ledger");
          $("#contra").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(amount <= 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(trans_type.length == 0 || trans_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select transaction type");
          $("#trans_type").focus();
          return;
     }else if(new Date(exp_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#exp_date").focus();
          return;
     }else if(contra_ledger == bal_ledger){
          alert("Contra ledger cannot be same as main ledger!");
          $("#contra").focus();
          return;
     
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_other_transactions.php",
               data : {posted:posted, exp_date:exp_date, bal_ledger:bal_ledger, contra_ledger:contra_ledger, amount:amount, details:details, store:store, trans_type:trans_type},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#exp_date").val('');
     $("#ledger").val('');
     $("#bal_ledger").val('');
     $("#contra").val('');
     $("#contra_ledger").val('');
     $("#amount").val('');
     $("#trans_type").val('');
     $("#ledger").val('');
     // $("#details").val('');
     $("#exp_date").focus();
     return false;    
}

//delete asset
function deleteAsset(item){
     let confirmDel = confirm("Are you sure you want to delete this asset?", "");
     if(confirmDel){
          $.ajax({
               type : "GET",
               url : "../controller/delete_asset.php?item="+item,
               success : function(response){
                    $("#delete_asset").html(response);
               }
          })
          setTimeout(function(){
               $("#delete_asset").load("delete_asset.php #delete_asset");
          }, 1500);
          return false;
          
     }else{
          return;
     }
}

//get ledger
function getAssetLedger(item_name){
     let asset = item_name;
     if(asset){
          $.ajax({
               type : "POST",
               url :"../controller/get_asset_ledger.php",
               data : {asset:asset},
               success : function(response){
                    $("#exp_head").html(response);
               }
          })
          return false;
     }else{
          $("#exp_head").html("<option value'' selected>Select asset first</option>");
     }
     
     
}

//check dispose asset reason
function checkDisposeReason(mode){
     let reason = mode;
     let contra_form = document.getElementById("contra_form");
     let amount_form = document.getElementById("amount_form");
     if(reason == "sold"){
          contra_form.style.display = "block";
          amount_form.style.display = "block";
          
     }else{
          contra_form.style.display = "none";
          amount_form.style.display = "none";
     }
}
//toggle bank and cash
function toggleCash(){
     $(".cash_bank").toggle();
     // $("#cash_equiv").hide();
}
//togle receivables
function toggleReceivables(){
     $(".trade_rec").toggle();
     // $("#receiveables").hide();
}
//togle fixed assets
function toggleFixedAsset(){
     $(".fixed").toggle();
     // $("#receiveables").hide();
}
//togle payables
function togglePayables(){
     $(".payables").toggle();
     // $("#receiveables").hide();
}
//togle fixed assets
function toggleLongDebts(){
     $(".long_debts").toggle();
     // $("#receiveables").hide();
}
//togle expenses
function toggleExpense(){
     $(".expenses").toggle();
     // $("#receiveables").hide();
}


 //get account to display account statement
 function getAccount(customer_id){
     let customer = customer_id;
     // alert(check_room);
     // return;
     let fromDate = document.getElementById("fromDate").value;
     let toDate = document.getElementById("toDate").value;
     if(fromDate.length == 0 || fromDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select a startb date!");
          $("#fromDate").focus();
          return;
     }else if(toDate.length == 0 || toDate.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select an end date!");
          $("#toDate").focus();
          return;
     }else{
     if(customer.length >= 3){
          if(customer){
               $.ajax({
                    type : "POST",
                    url :"../controller/get_account.php",
                    data : {customer:customer, fromDate:fromDate, toDate:toDate},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               /* $("#fromDate").attr("readonly", true);
               $("#toDate").attr("readonly", true); */
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
}
     
}

//display account statement/transaction history
function getAccountStatement(customer_id){
     let customer = customer_id;
          
          $.ajax({
               type : "GET",
               url : "../controller/account_statement.php?customer="+customer,
               success : function(response){
                    $("#customer_statement").html(response);
               }
          })
          $("#sales_item").html("");
          $("#customer").val("")
          return false;
     // }
     
 }

 //get cash flow
 function getCashFlow(){
     let fin_month = document.getElementById("fin_month").value;
     let fin_year = document.getElementById("fin_year").value;
     if(fin_month.length == 0 || fin_month.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select financial month");
          $("#fin_month").focus();
          return;
     }else if(fin_year.length == 0 || fin_year.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select financial year");
          $("#fin_year").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/monthly_fin_position.php",
               data : {fin_month:fin_month, fin_year:fin_year},
               success : function(response){
                    $(".new_data").html(response)
               }
          })
     }
 }

 // Post loans
function postLoan(){
     let posted = document.getElementById("posted").value;
     let store = document.getElementById("store").value;
     let exp_date = document.getElementById("exp_date").value;
     let trans_type = document.getElementById("trans_type").value;
     let financier = document.getElementById("financier").value;
     let contra = document.getElementById("contra").value;
     let loan = document.getElementById("loan").value;
     let amount = document.getElementById("amount").value;
     let details = document.getElementById("details").value;
     let todayDate = new Date();
     // let today = todayDate.toLocaleDateString();
     if(exp_date.length == 0 || exp_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter transaction date!");
          $("#exp_date").focus();
          return;
     }else if(financier.length == 0 || financier.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select lender");
          $("#financier").focus();
          return;
     }else if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Contra ledger");
          $("#contra").focus();
          return;
     }else if(loan.length == 0 || loan.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select loan account");
          $("#loan").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(amount <= 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(trans_type.length == 0 || trans_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select transaction type");
          $("#trans_type").focus();
          return;
     }else if(new Date(exp_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#exp_date").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_loan.php",
               data : {posted:posted, exp_date:exp_date, financier:financier, contra:contra, amount:amount, details:details, store:store, trans_type:trans_type, loan:loan},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#exp_date").val('');
     $("#financier").val('');
     $("#contra").val('');
     $("#amount").val('');
     $("#loan").val('');
     $("#trans_type").val('');
     $("#details").val('');
     $("#exp_date").focus();
     return false;    
}
 // Post directors transaction
function postDirectorTrx(){
     let posted = document.getElementById("posted").value;
     let store = document.getElementById("store").value;
     let exp_date = document.getElementById("exp_date").value;
     let trans_type = document.getElementById("trans_type").value;
     let financier = document.getElementById("financier").value;
     let contra = document.getElementById("contra").value;
     let amount = document.getElementById("amount").value;
     let details = document.getElementById("details").value;
     let todayDate = new Date();
     // let today = todayDate.toLocaleDateString();
     if(exp_date.length == 0 || exp_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter transaction date!");
          $("#exp_date").focus();
          return;
     }else if(financier.length == 0 || financier.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select ledger");
          $("#financier").focus();
          return;
     }else if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Contra ledger");
          $("#contra").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(amount <= 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(trans_type.length == 0 || trans_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select transaction type");
          $("#trans_type").focus();
          return;
     }else if(new Date(exp_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#exp_date").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_director_trx.php",
               data : {posted:posted, exp_date:exp_date, financier:financier, contra:contra, amount:amount, details:details, store:store, trans_type:trans_type},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#exp_date").val('');
     $("#financier").val('');
     $("#contra").val('');
     $("#amount").val('');
     $("#trans_type").val('');
     $("#details").val('');
     $("#exp_date").focus();
     return false;    
}
// Post finance cost
function postFinanceCost(){
     let posted = document.getElementById("posted").value;
     let store = document.getElementById("store").value;
     let exp_date = document.getElementById("exp_date").value;
     let trans_type = document.getElementById("trans_type").value;
     let financier = document.getElementById("financier").value;
     let contra = document.getElementById("contra").value;
     let amount = document.getElementById("amount").value;
     let details = document.getElementById("details").value;
     let todayDate = new Date();
     // let today = todayDate.toLocaleDateString();
     if(exp_date.length == 0 || exp_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter transaction date!");
          $("#exp_date").focus();
          return;
     }else if(financier.length == 0 || financier.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select ledger");
          $("#financier").focus();
          return;
     }else if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Contra ledger")
          $("#contra").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(amount <= 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(trans_type.length == 0 || trans_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select transaction type");
          $("#trans_type").focus();
          return;
     }else if(new Date(exp_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#exp_date").focus();
          return;
     }else if(financier == contra){
          alert("Debit and Credit ledger cannot be thesame!");
          $("#financier").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_finance_cost.php",
               data : {posted:posted, exp_date:exp_date, financier:financier, contra:contra, amount:amount, details:details, store:store, trans_type:trans_type},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#exp_date").val('');
     $("#financier").val('');
     $("#contra").val('');
     $("#amount").val('');
     $("#trans_type").val('');
     $("#details").val('');
     $("#exp_date").focus();
     return false;    
}

//get monthly account statement
function monthlyAccountStatement(account, month, year){
     $.ajax({
          type : "GET",
          url : "../controller/monthly_account_statement.php?customer="+account+"&month="+month+"&year="+year,
          success : function(response){
               $(".new_data").html(response);
               window.scrollTo(0, 0);
          }
          
     })
     return false;
}
//get yearly account statement
function yearlyAccountStatement(account, year){
     $.ajax({
          type : "GET",
          url : "../controller/yearly_account_statement.php?customer="+account+"&year="+year,
          success : function(response){
               $(".new_data").html(response);
               window.scrollTo(0, 0);
          }
          
     })
     return false;
}
//get monthly financial position
 function getMonthlyFinPos(){
     let fin_month = document.getElementById("fin_month").value;
     let fin_year = document.getElementById("fin_year").value;
     if(fin_month.length == 0 || fin_month.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select financial month");
          $("#fin_month").focus();
          return;
     }else if(fin_year.length == 0 || fin_year.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select financial year");
          $("#fin_year").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/monthly_fin_position.php",
               data : {fin_month:fin_month, fin_year:fin_year},
               success : function(response){
                    $(".new_data").html(response)
               }
          })
     }
 }
 //get yearly financial position
 function getYearlyFinPos(){
     let fin_year = document.getElementById("fin_year").value;
     if(fin_year.length == 0 || fin_year.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select financial year");
          $("#fin_year").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/yearly_fin_position.php",
               data : {fin_year:fin_year},
               success : function(response){
                    $(".new_data").html(response)
               }
          })
     }
 }
 //get yearly income statement
 function getYearlyIncome(){
     let fin_year = document.getElementById("fin_year").value;
     if(fin_year.length == 0 || fin_year.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select financial year");
          $("#fin_year").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/yearly_income_statement.php",
               data : {fin_year:fin_year},
               success : function(response){
                    $(".new_data").html(response)
               }
          })
     }
 }
 //reverse other transactions
function reverseTrx(trx){
     let confirm_reverse = confirm("Are you sure you want to reverse this transaction?", "");
     if(confirm_reverse){
          $.ajax({
               type : "GET",
               url : "../controller/reverse_transaction.php?trx_number="+trx,
               success : function(response){
                    $("#reverse_dep").html(response);
               }
          })
          setTimeout(() => {
               $("#reverse_dep").load("reverse_transactions.php #reverse_dep");
          }, 1500);
          return false;
          
     }else{
          return;
     }
}

//add asset location
function addLocation(){
     let location = document.getElementById("location").value;
     if(location.length == 0 || location.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input asset location!");
          $("#location").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_asset_location.php",
               data : {location:location},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#location").val('');
     $("#location").focus();
     return false;
}
//add asset
function addAsset(){
     let asset = document.getElementById("asset").value;
     let supplier = document.getElementById("supplier").value;
     let purchase_date = document.getElementById("purchase_date").value;
     let cost = document.getElementById("cost").value;
     let quantity = document.getElementById("quantity").value;
     let salvage_value = document.getElementById("salvage_value").value;
     let useful_life = document.getElementById("useful_life").value;
     let deployment = document.getElementById("deployment").value;
     let location = document.getElementById("location").value;
     let ledger = document.getElementById("ledger").value;
     todayDate = new Date();
     let specification = document.getElementById("specification").value;
     if(asset.length == 0 || asset.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input asset name!");
          $("#asset").focus();
          return;
     }else if(quantity.length == 0 || quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity purchased!");
          $("#quantity").focus();
          return;
     }else if(quantity <= 0){
          alert("Please input quantity purchased!");
          $("#quantity").focus();
          return;
     }else if(supplier.length == 0 || supplier.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input supplier name!");
          $("#supplier").focus();
          return;
     }else if(purchase_date.length == 0 || purchase_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter purchase date!");
          $("#purchase_date").focus();
          return;
     }else if(new Date(purchase_date) > todayDate){
          alert("Purchase date can not be futuristic!");
          $("#purchase_date").focus();
          return;
     }else if(new Date(purchase_date) > new Date(deployment)){
          alert("DPurchase date cannot be greater than deployment date!");
          $("#deployment").focus();
          return;
     }else if(cost.length == 0 || cost.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input asset cost!");
          $("#cost").focus();
          return;
     }else if(salvage_value.length == 0 || salvage_value.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input salvage value!");
          $("#salvage_value").focus();
          return;
     }else if(useful_life.length == 0 || useful_life.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input useful life!");
          $("#useful_life").focus();
          return;
     }else if(deployment.length == 0 || deployment.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input deployment date!");
          $("#deployment").focus();
          return;
     }else if(location.length == 0 || location.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select asset location!");
          $("#location").focus();
          return;
     }else if(ledger.length == 0 || ledger.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select asset class!");
          $("#ledger").focus();
          return;
     }else if(specification.length == 0 || specification.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input asset specifications!");
          $("#specification").focus();
          return;
     }else if(cost <= 0 || salvage_value <= 0 || useful_life <= 0){
          alert("Values cannot be less than or equal to 0!");
          $("#salvage_value").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_asset.php",
               data : {asset:asset, supplier:supplier, purchase_date:purchase_date, cost:cost, salvage_value:salvage_value, useful_life:useful_life, deployment:deployment, location:location, ledger:ledger, specification:specification, quantity:quantity},
               beforeSend: function(){
                    $("#assets").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#assets").html(response);
                    setTimeout(function(){
                         showPage("add_asset.php");
                    }, 2000);
               }
          })
     }
     $("#asset").val('');
     $("#supplier").val('');
     $("#purchase_date").val('');
     $("#deployment").val('');
     $("#cost").val('');
     $("#salvage_value").val('');
     $("#useful_life").val('');
     $("#location").val('');
     $("#ledger").val('');
     $("#specification").val('');
     $("#asset").focus();
     return false;
}
//update asset
function updateAsset(){
     let asset_id = document.getElementById("asset_id").value;
     let asset = document.getElementById("asset").value;
     let supplier = document.getElementById("supplier").value;
     let purchase_date = document.getElementById("purchase_date").value;
     let cost = document.getElementById("cost").value;
     let quantity = document.getElementById("quantity").value;
     let salvage_value = document.getElementById("salvage_value").value;
     let useful_life = document.getElementById("useful_life").value;
     let deployment = document.getElementById("deployment").value;
     let location = document.getElementById("location").value;
     let ledger = document.getElementById("ledger").value;
     todayDate = new Date();
     let specification = document.getElementById("specification").value;
     if(asset.length == 0 || asset.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input asset name!");
          $("#asset").focus();
          return;
     }else if(quantity.length == 0 || quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity purchased!");
          $("#quantity").focus();
          return;
     }else if(quantity <= 0){
          alert("Please input quantity purchased!");
          $("#quantity").focus();
          return;
     }else if(supplier.length == 0 || supplier.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input supplier name!");
          $("#supplier").focus();
          return;
     }else if(purchase_date.length == 0 || purchase_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter purchase date!");
          $("#purchase_date").focus();
          return;
     }else if(new Date(purchase_date) > todayDate){
          alert("Purchase date can not be futuristic!");
          $("#purchase_date").focus();
          return;
     }else if(new Date(purchase_date) > new Date(deployment)){
          alert("DPurchase date cannot be greater than deployment date!");
          $("#deployment").focus();
          return;
     }else if(cost.length == 0 || cost.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input asset cost!");
          $("#cost").focus();
          return;
     }else if(salvage_value.length == 0 || salvage_value.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input salvage value!");
          $("#salvage_value").focus();
          return;
     }else if(useful_life.length == 0 || useful_life.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input useful life!");
          $("#useful_life").focus();
          return;
     }else if(deployment.length == 0 || deployment.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input deployment date!");
          $("#deployment").focus();
          return;
     }else if(location.length == 0 || location.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select asset location!");
          $("#location").focus();
          return;
     }else if(ledger.length == 0 || ledger.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select asset class!");
          $("#ledger").focus();
          return;
     }else if(specification.length == 0 || specification.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input asset specifications!");
          $("#specification").focus();
          return;
     }else if(cost <= 0 || salvage_value <= 0 || useful_life <= 0){
          alert("Values cannot be less than or equal to 0!");
          $("#salvage_value").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_asset.php",
               data : {asset_id:asset_id, asset:asset, supplier:supplier, purchase_date:purchase_date, cost:cost, salvage_value:salvage_value, useful_life:useful_life, deployment:deployment, location:location, ledger:ledger, specification:specification, quantity:quantity},
               beforeSend: function(){
                    $("#assets").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#assets").html(response);
                    setTimeout(function(){
                        showPage("asset_register.php");
                    }, 2000);
               }
          })
     }
     
     return false;
}

//aalocate/reassign asset
function allocateAsset(){
     let asset_id = document.getElementById("asset_id").value;
     let location = document.getElementById("location").value;
     if(location.length == 0 || location.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select asset location");
          $("#location").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/allocate_asset.php",
               data : {asset_id:asset_id, location:location},
               success :function(response){
                    $("#allocate_asset").html(response);
               }
          })
          setTimeout(function(){
               $("#allocate_asset").load("allocate_asset.php #allocate_asset");
          }, 1500)
          return false;
     }
}
//dispose asset
function disposeAsset(){
     let asset_id = document.getElementById("asset_id").value;
     let reason = document.getElementById("reason").value;
     let contra = document.getElementById("contra").value;
     let amount = document.getElementById("amount").value;
     let quantity = document.getElementById("quantity").value;
     if(reason == "sold"){
          if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select contra ledger");
               $("#contra").focus();
               return;
          }
          if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please input amount sold");
               $("#amount").focus();
               return;
          }
     }
     if(reason.length == 0 || reason.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input reason for disposition");
          $("#reason").focus();
          return;
     }else if(quantity.length == 0 || quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity to be disposed");
          $("#quantity").focus();
          return;
     }else if(quantity <= 0){
          alert("Please input quantity to be disposed");
          $("#quantity").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/dispose_asset.php",
               data : {asset_id:asset_id, reason:reason, quantity:quantity, contra:contra, amount:amount},
               success :function(response){
                    $("#allocate_asset").html(response);
               }
          })
          setTimeout(function(){
               $("#allocate_asset").load("dispose_asset.php #allocate_asset");
          }, 1500)
          return false;
     }
}
//setup depreciation
function addDepreciation(){
     let asset = document.getElementById("asset").value;
     let start_date = document.getElementById("start_date").value;
     if(start_date.length == 0 || start_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select start date");
          $("#start_date").focus();
          return;
     }else if(asset.length == 0 || asset.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please asset");
          $("#asset").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/setup_depreciation.php",
               data : {asset:asset, start_date:start_date},
               success :function(response){
                    $("#depreciation").html(response);
               }
          })
          setTimeout(function(){
               $("#depreciation").load("setup_depreciation.php #depreciation");
          }, 1500)
          return false;
     }
}

// Post assets 
function postAsset(){
     let posted = document.getElementById("posted").value;
     let store = document.getElementById("store").value;
     let asset = document.getElementById("asset").value;
     let exp_date = document.getElementById("exp_date").value;
     let exp_head = document.getElementById("exp_head").value;
     let contra = document.getElementById("contra").value;
     // let amount = document.getElementById("amount").value;
     let details = document.getElementById("details").value;
     let todayDate = new Date();
     // let today = todayDate.toLocaleDateString();
     if(exp_date.length == 0 || exp_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter transaction date!");
          $("#exp_date").focus();
          return;
     }else if(exp_head.length == 0 || exp_head.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select an asset ledger");
          $("#exp_head").focus();
          return;
     }else if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Contra ledger");
          $("#contra").focus();
          return;
     }else if(asset.length == 0 || asset.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Asset");
          $("#contra").focus();
          return;
     /* }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return; */
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(new Date(exp_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#exp_date").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_asset.php",
               data : {posted:posted, exp_date:exp_date, exp_head:exp_head, contra:contra, /* amount:amount, */ details:details, store:store, asset:asset},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#exp_date").val('');
     $("#exp_head").val('');
     $("#contra").val('');
     // $("#amount").val('');
     // $("#details").val('');
     $("#exp_date").focus();
     return false;    
}
// Post depreication
function postDepreciation(){
     let posted = document.getElementById("posted").value;
     let store = document.getElementById("store").value;
     let exp_date = document.getElementById("exp_date").value;
     let asset = document.getElementById("asset").value;
     let dr_ledger = document.getElementById("dr_ledger").value;
     let contra = document.getElementById("contra").value;
     // let amount = document.getElementById("amount").value;
     let details = document.getElementById("details").value;
     let todayDate = new Date();
     // let today = todayDate.toLocaleDateString();
     if(exp_date.length == 0 || exp_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter transaction date!");
          $("#exp_date").focus();
          return;
     }else if(dr_ledger.length == 0 || dr_ledger.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select debit ledger");
          $("#dr_ledger").focus();
          return;
     }else if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Contra ledger");
          $("#contra").focus();
          return;
     /* }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return; */
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(asset.length == 0 || asset.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select asset");
          $("#asset").focus();
          return;
     }else if(new Date(exp_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#exp_date").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/post_depreciation.php",
               data : {posted:posted, exp_date:exp_date, dr_ledger:dr_ledger, contra:contra, /* amount:amount, */ details:details, store:store, asset:asset},
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#exp_date").val('');
     $("#dr_ledger").val('');
     $("#contra").val('');
     $("#asset").val('');
     // $("#details").val('');
     $("#exp_date").focus();
     return false;    
}

//get depreciation by year
function getDepreciationYear(year){
     let dep_year = year;
     if(dep_year){
         $.ajax({
             type : "POST",
             url : "../controller/get_yearly_depreciation.php",
             data: {dep_year:dep_year},
             success : function(response){
                 $(".new_data").html(response);
             }
         })
         return false;
     }
 }
 
 //generate random characters
function generateString(length) {
     // random characters
     const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = ' ';
    const charactersLength = characters.length;
    for ( let i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    return result;
}

 //online payment for renewal of package with vpay
function renewPackage(){
     // event.preventDefault();
     let fee = document.getElementById("fee").value;
     let processing = document.getElementById("processing").value;
     let total_due = document.getElementById("total_due").value;
     let company = document.getElementById("company").value;
     let package = document.getElementById("package").value;
     let email_add = document.getElementById("email_add").value;
     let date = new Date();
     let year = date.getFullYear();
     let transNum = generateString(5)+company+year;
     // alert(transNum);
     
     if(email_add.length == 0 || email_add.replace(/^\s+|\s+$/g, "").length == 0){
         alert("Please input email address!");
         $("#email_add").focus();
         return;
     }else if (!isValidEmail(email_add)) {
          alert("Please enter a valid email address.");
          $("#email_add").focus();
          return;
    
     }else{
         confirm_booking = confirm("Are you sure you want to continue with your payment", "");
         if(confirm_booking){
             const options = {
                 amount: fee,
                 currency: 'NGN',
                 domain: 'live',
                 key: 'c48a27aa-9cbc-416f-9793-099ad78f2fd5',
                 email: email_add,
                 transactionref: transNum,
                 customer_logo: 'https://www.dorthpro.com/company/images/logo.png',
                 customer_service_channel: '+2347068897068, support@dorthpro.com',
                 txn_charge: 3,
                 txn_charge_type: 'percentage',
                 onSuccess: function(response) { 
                     $.ajax({
                         type : "POST",
                         url : "../controller/renew_package.php",
                         data : {fee:fee, email_add:email_add, total_due:total_due, processing:processing, package:package, company:company, transNum:transNum},
                     });
                     alert('Payment Successful!', response.message);
                     window.open("../view/users.php", "_parent");
                     return;
                 },
             onExit: function(response) { console.log('Hello World!',
         response.message); }
         }
         
         if(window.VPayDropin){
             const {open, exit} = VPayDropin.create(options);
             open();                    
         }
         }    
     }            
 };

 // add fields to farm
function addField(){
     let field = document.getElementById("field").value;
     let field_size = document.getElementById("field_size").value;
     let soil_type = document.getElementById("soil_type").value;
     let soil_ph = document.getElementById("soil_ph").value;
     let topography = document.getElementById("topography").value;
     let latitude = document.getElementById("latitude").value;
     let longitude = document.getElementById("longitude").value;
     let location = document.getElementById("location").value;
     let purchase_cost = document.getElementById("purchase_cost").value;
     if(field.length == 0 || field.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input field name!");
          $("#field").focus();
          return;
     }else if(field_size.length == 0 || field_size.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input field size!");
          $("#field_size").focus();
          return;
     }else if(soil_type.length == 0 || soil_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input soil type!");
          $("#soil_type").focus();
          return;
     }else if(soil_ph.length == 0 || soil_ph.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input soil ph!");
          $("#soil_ph").focus();
          return;
     }else if(!latitude){
          alert("Please input field latitude!");
          $("#latitude").focus();
          return;
     }else if(!longitude){
          alert("Please input field longitude!");
          $("#longitude").focus();
          return;
     }else if(!purchase_cost){
          alert("Please input field purchase cost!");
          $("#purchase_cost").focus();
          return;
     }else if(!location){
          alert("Please input field location!");
          $("#location").focus();
          return;
     }else if(parseFloat(latitude) < 0 || parseFloat(longitude) < 0){
          alert("Latitude or longitude values cannot be less than or equals to zero!");
          $("#latitude").focus();
          return;
     }else if(parseFloat(purchase_cost) <= 0){
          alert("Field purchase cost cannot be less than or equal to zero!");
          $("#purchase_cost").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_field.php",
               data : {field:field, field_size:field_size, soil_type:soil_type, soil_ph:soil_ph, topography:topography, purchase_cost:purchase_cost, latitude:latitude, longitude:longitude, location:location},
               beforeSend: function(){
                    $(".info").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#field").val('');
     $("#field_size").val('');
     $("#soil_type").val('');
     $("#soil_ph").val('');
     $("#topography").val('');
     $("#location").val('');
     $("#rent").val('0');
     $("#latitude").val('0');
     $("#longitude").val('0');
     $("#field").focus();
     return false;    
}

// create a crop cycle
function addCropCycle(){
     let field = document.getElementById("field").value;
     // let crop = document.getElementById("crop").value;
     let area_used = document.getElementById("area_used").value;
     // let variety = document.getElementById("variety").value;
     let start_date = document.getElementById("start_date").value;
     // let harvest = document.getElementById("harvest").value;
     // let yield = document.getElementById("yield").value;
     let notes = document.getElementById("notes").value;
     if(field.length == 0 || field.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please Select field!");
          $("#field").focus();
          return;
     /* }else if(crop.length == 0 || crop.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select crop!");
          $("#crop").focus();
          return; */
     }else if(area_used.length == 0 || area_used.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input area used in hacters");
          $("#area_used").focus();
          return;
     /* }else if(variety.length == 0 || variety.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input crop variety!");
          $("#variety").focus();
          return; */
     }else if(start_date.length == 0 || start_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input cycle start date!");
          $("#start_date").focus();
          return;
     /* }else if(harvest.length == 0 || harvest.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input expected harvest date!");
          $("#harvest").focus();
          return; */
     /* }else if(yield.length == 0 || yield.replace(/^\s+|\s+$/g, "").length == 0 || yield <= 0){
          alert("Please input expected harvest yield!");
          $("#yield").focus();
          return; */
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_crop_cycle.php",
               data : {field:field, area_used:area_used,  start_date:start_date, /* harvest:harvest,  */notes:notes /* crop:crop, variety:variety,yield:yield */},
               beforeSend: function(){
                    $("#crop_cycle").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#crop_cycle").html(response);
                    setTimeout(function(){
                         showPage("crop_cycle.php")
                    }, 2000)
               }
          })
         
          return false;    
     }
}
//show all forms
function showForm(url){
     $.ajax({
          type : "GET",
          url : "../controller/"+url,
          beforeSend : function(){
               $("#all_forms").html("<div class='processing'><div class='loader'></div></div>");
          },
          success : function(response){
               document.getElementById("all_forms").scrollIntoView();
               $("#all_forms").html(response)
          }
     })
     return false;
}

//close all forms
function closeAllForms(){
     $("#all_forms").html("");
     $("#all_order_forms").html("");
}

//get item to change details
function getItemDetails(item_name, url){
     let item = item_name;
     if(item.length >= 3){
          if(item){
               $.ajax({
                    type : "POST",
                    url :"../controller/"+url,
                    data : {item:item},
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}

//select drug allergy
function selectCycleItem(id, name, quantity){
     if(quantity <= 0){
          alert(name+" has 0 quantity. Cannot proceed");
          $("#sales_item").html('');
          $("#item_qty").hide();
          return;
     }else{
          let item = document.getElementById("item");
          let task_item = document.getElementById("task_item");
          item.value = name;
          task_item.value = id;
          $("#sales_item").html('');
          $("#item_qty").show();
     }
    
}

//add task for a crop cycle
function addCycleTask(){
     let cycle = document.getElementById("cycle").value;
     let task_title = document.getElementById("task_title").value;
     // let description = document.getElementById("description").value;
     let workers = document.getElementById("workers").value;
     // let labour_cost = document.getElementById("labour_cost").value;
     let start_date = document.getElementById("start_date").value;
     // let end_date = document.getElementById("end_date").value;
     todayDate = new Date();
     if(task_title.length == 0 || task_title.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input select task!");
          $("#task_title").focus();
          return;
     }else if(start_date.length == 0 || start_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input date and time task started!");
          $("#start_date").focus();
          return;
     /* }else if(end_date.length == 0 || end_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input date and time task ended!");
          $("#end_date").focus();
          return;
     }else if(new Date(start_date) > new Date(end_date)){
          alert("Start date can not be greater than end date!");
          $("#start_date").focus();
          return; */
     }else if(new Date(start_date) > todayDate){
          alert("You cannot start a futuristic task!");
          $("#start_date").focus();
          return;
    /*  }else if(new Date(end_date) > todayDate){
          alert("You can only end a task today or later!");
          $("#end_date").focus();
          return;
     }else if(description.length == 0 || description.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input task description!");
          $("#description").focus();
          return; */
     }else if(workers.length == 0 || workers.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input assigned workers!");
          $("#workers").focus();
          return;
    /*  }else if(parseFloat(labour_cost) < 0){
          alert("Labour Cost must be a number!");
          $("#labour_cost").focus();
          return; */
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/start_cycle_task.php",
               data : {task_title:task_title, /* description:description, */ workers:workers, cycle:cycle, /* labour_cost:labour_cost, */ start_date:start_date /* end_date:end_date */},
               beforeSend: function(){
                    $("#all_forms").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
                    // $(".tasks").html(response);
                    setTimeout(function(){
                         showPage("cycle_details.php?cycle="+cycle);
                    }, 2000);
               }
          })
     }
}
//update task for a crop cycle
function updateCycleTask(){
     let cycle = document.getElementById("cycle").value;
     let task_id = document.getElementById("task_id").value;
     let description = document.getElementById("description").value;
     let workers = document.getElementById("workers").value;
     if(workers.length == 0 || workers.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input assigned workers!");
          $("#workers").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_cycle_task.php",
               data : {task_id:task_id, description:description, workers:workers},
               beforeSend: function(){
                    $("#all_forms").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
                    // $(".tasks").html(response);
                    setTimeout(function(){
                         showPage("cycle_details.php?cycle="+cycle);
                    }, 2000);
               }
          })
     }
}
//update general task
function updateTask(){
     
     let task_id = document.getElementById("task_id").value;
     let description = document.getElementById("description").value;
     let workers = document.getElementById("workers").value;
     if(workers.length == 0 || workers.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input assigned workers!");
          $("#workers").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_general_task.php",
               data : {task_id:task_id, description:description, workers:workers},
               beforeSend: function(){
                    $("#all_forms").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
                    // $(".tasks").html(response);
                    setTimeout(function(){
                         showPage("general_task_details.php?task="+task_id);
                    }, 2000);
               }
          })
     }
}
//end task for crop cycle
function endTask(){
     let task_id = document.getElementById("task_id").value;
     let cycle = document.getElementById("cycle").value;
     let labour_cost = document.getElementById("labour_cost").value;
     let end_date = document.getElementById("end_date").value;
     todayDate = new Date();
    if(end_date.length == 0 || end_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input date and time task was completed!");
          $("#end_date").focus();
          return;
     
     }else if(new Date(end_date) > todayDate){
          alert("You can only end a task today or later!");
          $("#end_date").focus();
          return;
    
     }else if(parseFloat(labour_cost) < 0){
          alert("Labour Cost must be a number!");
          $("#labour_cost").focus();
          return;
     }else{
          let confirm_end = confirm("Are you sure you want to end this task?", "");
          if(!confirm_end){
               return;
          }else{
               $.ajax({
                    type : "POST",
                    url : "../controller/end_task.php",
                    data : {task_id:task_id, labour_cost:labour_cost, end_date:end_date},
                    beforeSend: function(){
                         $("#all_forms").html("<div class='processing'><div class='loader'></div></div>");
                    },
                    success : function(response){
                         $("#cycles").html(response);
                         // $(".tasks").html(response);
                         setTimeout(function(){
                              showPage("cycle_details.php?cycle="+cycle);
                         }, 2000);
                    }
               })
          }
     }
}
//end general task
function endGeneralTask(){
     let task_id = document.getElementById("task_id").value;
     let cycle = document.getElementById("cycle").value;
     let labour_cost = document.getElementById("labour_cost").value;
     let end_date = document.getElementById("end_date").value;
     todayDate = new Date();
    if(end_date.length == 0 || end_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input date and time task was completed!");
          $("#end_date").focus();
          return;
     
     }else if(new Date(end_date) > todayDate){
          alert("You can only end a task today or later!");
          $("#end_date").focus();
          return;
    
     }else if(parseFloat(labour_cost) < 0){
          alert("Labour Cost must be a number!");
          $("#labour_cost").focus();
          return;
     }else{
          let confirm_end = confirm("Are you sure you want to end this task?", "");
          if(!confirm_end){
               return;
          }else{
               $.ajax({
                    type : "POST",
                    url : "../controller/end_task.php",
                    data : {task_id:task_id, labour_cost:labour_cost, end_date:end_date},
                    beforeSend: function(){
                         $("#all_forms").html("<div class='processing'><div class='loader'></div></div>");
                    },
                    success : function(response){
                         $("#cycles").html(response);
                         // $(".tasks").html(response);
                         setTimeout(function(){
                              showPage("general_task.php");
                         }, 2000);
                    }
               })
          }
     }
}
//add task
function addObservation(){
     let cycle = document.getElementById("cycle").value;
     let observation = document.getElementById("observation").value;
    if(observation.length == 0 || observation.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input observations!");
          $("#observation").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/complete_observation.php",
               data : {observation:observation, cycle:cycle},
               beforeSend: function(){
                    $("#all_forms").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#all_forms").html("");
                    $(".observations").html(response);
               }
          })
     }
}

//harvest crop
function harvest(){
     let cycle = document.getElementById("cycle").value;
     // let crop = document.getElementById("crop").value;
     let quantity = document.getElementById("quantity").value;
    if(parseFloat(quantity) <= 0){
          alert("Please input harvested quantity!");
          $("#quantity").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/harvest_crop.php",
               data : {quantity:quantity, cycle:cycle /* crop:crop */},
               beforeSend: function(){
                    $("#cycles").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
                    setTimeout(function(){
                         showPage("cycle_details.php?cycle="+cycle);
                    }, 2000);
               }
          })
     }
}
//remove  crop
function removeCrop(){
     let cycle = document.getElementById("cycle").value;
     // let crop = document.getElementById("crop").value;
     let quantity = document.getElementById("quantity").value;
     let reason = document.getElementById("reason").value;
     let other_notes = document.getElementById("other_notes").value;
    if(parseFloat(quantity) <= 0){
          alert("Please input quantity!");
          $("#quantity").focus();
          return;
     }else if(reason.length == 0 || reason.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select reason for removal!");
          $("#reason").focus();
          return;
    }else if(other_notes.length == 0 || other_notes.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input other details!");
          $("#other_notes").focus();
          return;
    
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/remove_crop.php",
               data : {quantity:quantity, cycle:cycle, reason:reason, other_notes:other_notes},
               beforeSend: function(){
                    $("#cycles").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
                    setTimeout(function(){
                         showPage("cycle_details.php?cycle="+cycle);
                    }, 2000);
               }
          })
     }
}

//get owners of field
function getFieldOwners(input){
     let item = input;
     $("#search_results").show();
     if(item.length >= 3){
          $.ajax({
               type : "POST",
               url : "../controller/get_owner_name.php",
               data : {item:item},
               success : function(response){
                    $("#search_results").html(response);
               }
          })
     }
     
}

//add correct owner  for updating fields
function addFieldOwner(id, name){
     let customer = document.getElementById("customer");
     let item = document.getElementById("item");
     customer.value = id;
     item.value = name;
     $("#search_results").html('');
}

 // update farmfield details
function updateField(){
     let field_id = document.getElementById("field_id").value;
     let field = document.getElementById("field").value;
     let field_size = document.getElementById("field_size").value;
     let soil_type = document.getElementById("soil_type").value;
     let soil_ph = document.getElementById("soil_ph").value;
     let topography = document.getElementById("topography").value;
     let latitude = document.getElementById("latitude").value;
     let longitude = document.getElementById("longitude").value;
     let location = document.getElementById("location").value;
     // let purchase_cost = document.getElementById("purchase_cost").value;
     if(field.length == 0 || field.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input field name!");
          $("#field").focus();
          return;
     }else if(field_size.length == 0 || field_size.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input field size!");
          $("#field_size").focus();
          return;
     }else if(soil_type.length == 0 || soil_type.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input soil type!");
          $("#soil_type").focus();
          return;
     }else if(soil_ph.length == 0 || soil_ph.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input soil ph!");
          $("#soil_ph").focus();
          return;
     }else if(!location){
          alert("Please input field location!");
          $("#location").focus();
          return;
     }else if(!latitude){
          alert("Please input field latitude!");
          $("#latitude").focus();
          return;
     }else if(!longitude){
          alert("Please input field longitude!");
          $("#longitude").focus();
          return;
     /* }else if(!purchase_cost){
          alert("Please input field purchase cost!");
          $("#purchase_cost").focus();
          return; */
     }else if(parseFloat(latitude) < 0 || parseFloat(longitude) < 0){
          alert("Latitude or longitude values cannot be less than or equals to zero!");
          $("#latitude").focus();
          return;
     /* }else if(parseFloat(purchase_cost) <= 0){
          alert("Field purchase cost cannot be less than or equal to zero!");
          $("#purchase_cost").focus();
          return; */
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_field.php",
               data : {field:field, field_id:field_id,field_size:field_size, soil_type:soil_type, soil_ph:soil_ph, topography:topography, /* purchase_cost:purchase_cost,  */latitude:latitude, longitude:longitude, location:location},
               beforeSend: function(){
                    $("#farm_fields").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#farm_fields").html(response);
                    setTimeout(function(){
                         showPage("farm_fields.php");
                    }, 2000);
               }
          })
          return false; 
     }
}
 // assign farm field to client
function assignField(){
     let field_id = document.getElementById("field_id").value;
     let customer = document.getElementById("customer").value;
     let duration = document.getElementById("duration").value;
     let purchase_cost = document.getElementById("purchase_cost").value;
     let discount = document.getElementById("discount")?.value || 0;
     let total_due = document.getElementById("total_due").value;
     let payment_duration = document.getElementById("payment_duration").value;
     let annual_rent = document.getElementById("annual_rent").value;
     let rent_percentage = document.getElementById("rent_percentage").value;
     let start_date = document.getElementById("start_date").value;
     let installment_amount = document.getElementById("installment_amount").value;
     let documentation = document.getElementById("documentation").value;
     let today = new Date();
     let start = new Date(start_date);
     if(customer.length == 0 || customer.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select client!");
          $("#item").focus();
          return;
     }else if(!duration){
          alert("Please select contract duration!");
          $("#duration").focus();
          return;
     }else if(!total_due || parseFloat(total_due) <= 0){
          alert("Please input purchase amount!");
          $("#purchase_cost").focus();
          return;
     }else if(!documentation || parseFloat(documentation) <= 0){
          alert("Please input documentation fee!");
          $("#documentation").focus();
          return;
     }else if(!payment_duration){
          alert("Please select purchase payment duration!");
          $("#payment_duration").focus();
          return;
     }else if(!installment_amount){
          alert("Please select frequency to get installment!");
          $("#frequency").focus();
          return;
     }else if(!rent_percentage){
          alert("Please select rent percentage to get annual rent!");
          $("#rent_percentage").focus();
          return;
     }else if(!annual_rent){
          alert("Please select rent percentage to get annual rent!");
          $("#rent_percentage").focus();
          return;
     }else if(!start_date){
          alert("Please input contract start date!");
          $("#start_date").focus();
          return;
     }else if(start < today){
          alert("Start date cannot be less than current date!");
          $("#start_date").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/assign_field.php",
               data : {field_id:field_id, customer:customer, duration:duration, purchase_cost:purchase_cost, discount:discount, total_due:total_due,payment_duration:payment_duration, rent_percentage:rent_percentage, annual_rent:annual_rent, documentation:documentation, start_date:start_date, installment_amount:installment_amount},
               beforeSend: function(){
                    $("#farm_fields").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#farm_fields").html(response);
                    setTimeout(function(){
                         showPage("assign_field.php");
                    }, 2000);
               }
          })
          
          return false; 
     }
}

//complete crop cycle
function closeCycle(cycle_id){
     let confirm_close = confirm("Are you sure you want to close this crop cycle?", "");
     if(confirm_close){
          $.ajax({
               type : "GET",
               url : "../controller/close_cycle.php?cycle_id="+cycle_id,
               beforeSend: function(){
                    $("#cycles").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
                    setTimeout(function(){
                         showPage("crop_cycle.php");
                    }, 2000);
               }
          })
          return false;
     }else{
          return;
     }
}
//abandon crop cycle
function abandonCycle(cycle_id){
     let confirm_close = confirm("Are you sure you want to abandon this crop cycle?", "");
     if(confirm_close){
          $.ajax({
               type : "GET",
               url : "../controller/abandon_cycle.php?cycle_id="+cycle_id,
               beforeSend: function(){
                    $("#cycles").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
               }
          })
          setTimeout(function(){
               $("#cycles").load("crop_cycle.php #cycles");
          }, 2000);
          return false;
     }else{
          return;
     }
}

//get crops during adding new cycle
function getCrops(input){
     let item = input;
     $("#search_results").show();
     if(item.length >= 3){
          $.ajax({
               type : "POST",
               url : "../controller/get_crop_name.php",
               data : {item:item},
               success : function(response){
                    $("#search_results").html(response);
               }
          })
     }
     
}

//add crop during adding new cycle
function addCrop(id, name){
     let crop = document.getElementById("crop");
     let item = document.getElementById("item");
     crop.value = id;
     item.value = name;
     $("#search_results").html('');
}

//add itemused fo a crop cycle task
function addTaskItem(){
     let task_id = document.getElementById("task_id").value;
     let cycle = document.getElementById("cycle").value;
     let task_item = document.getElementById("task_item").value;
     let invoice = document.getElementById("invoice").value;
     let quantity = document.getElementById("quantity").value;
     if(task_item.length == 0 || task_item.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input item name!");
          $("#task_item").focus();
          return;
     }else if(parseFloat(quantity) <= 0){
          alert("Please input quantity used!");
          $("#quantity").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_task_item.php",
               data : {task_id:task_id, task_item:task_item, quantity:quantity,cycle:cycle, invoice:invoice},
               beforeSend : function(){
                    // document.getElementById("new_data").scrollIntoView();
                    $("#new_data").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#new_data").html(response);
               }
          });
          $("#task_item").val("");
          $("#item").val("");
          $("#quantity").val("");
          $("#task_item").focus();
     }
}
//add itemused fo a general task
function addGeneralTaskItem(){
     let task_id = document.getElementById("task_id").value;
     // let cycle = document.getElementById("cycle").value;
     let task_item = document.getElementById("task_item").value;
     let invoice = document.getElementById("invoice").value;
     let quantity = document.getElementById("quantity").value;
     if(task_item.length == 0 || task_item.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input item name!");
          $("#task_item").focus();
          return;
     }else if(parseFloat(quantity) <= 0){
          alert("Please input quantity used!");
          $("#quantity").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_general_task_item.php",
               data : {task_id:task_id, task_item:task_item, quantity:quantity,invoice:invoice},
               beforeSend : function(){
                    // document.getElementById("new_data").scrollIntoView();
                    $("#new_data").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#new_data").html(response);
               }
          });
          $("#task_item").val("");
          $("#item").val("");
          $("#quantity").val("");
          $("#task_item").focus();
     }
}

//remove item added to task
function removeTaskItem(id){
     let confirm_removal = confirm("Are you sure you want to remove this item?", "");
     if(confirm_removal){
          $.ajax({
               type : "GET",
               url : "../controller/remove_task_item.php?task_item_id="+id,
               beforeSend : function(){
                    $("#new_data").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#new_data").html(response);
               }
          })
     }else{
          return;
     }
}

//post labour cost
function postLabour(){
     let total_amount = document.getElementById("total_amount").value;
     let task = document.getElementById("task").value;
     let store = document.getElementById("store").value;
     let contra = document.getElementById("contra").value;
     let exp_head = document.getElementById("exp_head").value;
     if(contra.length == 0 || contra.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select contra ledger!");
          $("#contra").focus();
          return;
     }else if(exp_head.length == 0 || exp_head.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select expense ledger!");
          $("#exp_head").focus();
          return;
          
     }else{
          let confirmPost = confirm("Are you sure you want to post this payment?", "");
          if(confirmPost){
               $.ajax({
                    type : "POST",
                    url : "../controller/post_labour_cost.php",
                    data : {store:store, task:task, total_amount:total_amount, contra:contra, exp_head:exp_head},
                    beforeSend : function(){
                         $("#post_purchase").html("<div class='processing'><div class='loader'></div></div>");
                    },
                    success : function(response){
                         $("#post_purchase").html(response);
                    }
               })
               setTimeout(function(){
                    $("#post_purchase").load("post_labour.php #post_purchase");
               }, 1000);
               return false;
          
          }else{
               return;
          }
     }
}

// edit a crop cycle details
function editCropCycle(){
     let cycle = document.getElementById("cycle").value;
     let field = document.getElementById("field").value;
     // let crop = document.getElementById("crop").value;
     let area_used = document.getElementById("area_used").value;
     // let variety = document.getElementById("variety").value;
     let start_date = document.getElementById("start_date").value;
     // let harvest = document.getElementById("harvest").value;
     // let yield = document.getElementById("yield").value;
     let notes = document.getElementById("notes").value;
     if(field.length == 0 || field.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please Select field!");
          $("#field").focus();
          return;
     /* }else if(crop.length == 0 || crop.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select crop!");
          $("#crop").focus();
          return; */
     }else if(area_used.length == 0 || area_used.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input area used in hacters");
          $("#area_used").focus();
          return;
     /* }else if(variety.length == 0 || variety.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input crop variety!");
          $("#variety").focus();
          return; */
     }else if(start_date.length == 0 || start_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input cycle start date!");
          $("#start_date").focus();
          return;
    /*  }else if(harvest.length == 0 || harvest.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input expected harvest date!");
          $("#harvest").focus();
          return; */
    /*  }else if(parseFloat(yield) < 0){
          alert("Please input expected harvest yield!");
          $("#yield").focus();
          return; */
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/edit_crop_cycle.php",
               data : {cycle:cycle, field:field, /* crop:crop, */ area_used:area_used, /* variety:variety, */ start_date:start_date, /* harvest:harvest, */ notes:notes/* yield:yield */},
               beforeSend: function(){
                    $("#crop_cycle").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#crop_cycle").html(response);
                    setTimeout(function(){
                         showPage("crop_cycle.php")
                    }, 2000)
               }
          })
          return false;    
     }
}

//add general task / field maintenance
function addTask(){
     let field = document.getElementById("field").value;
     let task_title = document.getElementById("task_title").value;
     // let description = document.getElementById("description").value;
     let workers = document.getElementById("workers").value;
     // let labour_cost = document.getElementById("labour_cost").value;
     let start_date = document.getElementById("start_date").value;
     // let end_date = document.getElementById("end_date").value;
     todayDate = new Date();
     if(field.length == 0 || field.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select field!");
          $("#field").focus();
          return;
     }else if(start_date.length == 0 || start_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input date and time task started!");
          $("#start_date").focus();
          return;
     /* }else if(end_date.length == 0 || end_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input date and time task ended!");
          $("#end_date").focus();
          return;
     }else if(new Date(start_date) > new Date(end_date)){
          alert("Start date can not be greater than end date!");
          $("#start_date").focus();
          return; */
     }else if(new Date(start_date) > todayDate){
          alert("You cannot start a futuristic task!");
          $("#start_date").focus();
          return;
     /* }else if(new Date(end_date) > todayDate){
          alert("You can only end a task today or later!");
          $("#end_date").focus();
          return; */
     }else if(task_title.length == 0 || task_title.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input task title!");
          $("#task_title").focus();
          return;
    /*  }else if(description.length == 0 || description.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input task description!");
          $("#description").focus();
          return; */
     }else if(workers.length == 0 || workers.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input assigned workers!");
          $("#workers").focus();
          return;
     /* }else if(parseFloat(labour_cost) < 0){
          alert("Labour Cost must be a number!");
          $("#labour_cost").focus();
          return; */
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_general_task.php",
               data : {task_title:task_title, /* description:description,  */workers:workers, field:field, /* labour_cost:labour_cost,  */start_date:start_date /* end_date:end_date */},
               beforeSend: function(){
                    $("#general_tasks").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#general_tasks").html(response);
               }
          })
     }
}
//get vendor on key press for editing
function getVendorEdit(input){
     $("#search_results").show();
     if(input.length >= 3){
          $.ajax({
               type : "POST",
               url : "../controller/get_vendor_edit.php?input="+input,
               beforeSend : function(){
                    $("#search_results").html("<p>Searching...</p>");
               },
               success : function(response){
                    $("#search_results").html(response);
               }
          })
     }
     
}
// update vendor details
function updateVendor(){
     let vendor_id = document.getElementById("vendor_id").value;
     let vendor = document.getElementById("vendor").value;
     let phone_number = document.getElementById("phone_number").value;
     let contact = document.getElementById("contact").value;
     // let email = document.getElementById("email").value;
     let address = document.getElementById("address").value;
     if(vendor.length == 0 || vendor.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter vendor name!");
          $("#vendor").focus();
          return;
     }else if(phone_number.length == 0 || phone_number.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter customer phone number").focus();
          $("#phone_number").focus();
          return;
     
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_vendor.php",
               data : {vendor_id:vendor_id, vendor:vendor, phone_number:phone_number, address:address, contact:contact},
               beforeSend : function(){
                    $("#edit_customer").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $("#edit_customer").html(response);
               }
          })
     }
     setTimeout(function(){
          $("#edit_customer").load("edit_supplier_info.php #edit_customer");
     }, 1000);

     return false;    
}

//raise po for individual items
function raisePO(){
     let posted_by = document.getElementById("posted_by").value;
     let store = document.getElementById("store").value;
     let invoice_number = document.getElementById("invoice_number").value;
     let vendor = document.getElementById("vendor").value;
     let item_id = document.getElementById("item_id").value;
     let quantity = document.getElementById("quantity").value;
     let cost_price = document.getElementById("cost_price").value;
     let item_type = document.getElementById("item_type").value;
     if(quantity.length == 0 || quantity.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input quantity purchased!");
          $("#quantity").focus();
          return;
     }else if(parseFloat(quantity) <= 0){
          alert("Please input quantity requested!");
          $("#quantity").focus();
          return;
     }else if(parseFloat(cost_price) < 0){
         alert("Cost price cannot be lesser than 0");
          $("#cost_price").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/raise_po.php",
               data : {posted_by:posted_by, store:store, /* supplier:supplier, */ vendor:vendor, invoice_number:invoice_number, item_id:item_id, quantity:quantity, cost_price:cost_price, item_type:item_type},
               beforeSend : function(){
                    $(".stocked_in").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $(".stocked_in").html(response);
               }
          })
          /* $("#quantity").val('');
          $("#expiration_date").val('');
          $("#quantity").focus(); */
          $("#item").focus();
          $(".info").html('');
          return false; 
     }
}

// prinit transfer receipt
function printPO(invoice){
     window.open("../controller/print_purchase_order.php?receipt="+invoice);
     return false;
 
 }

 //Accept purchase order items
function acceptOrder(){
     let item_id = document.getElementById("item_id").value;
     let item = document.getElementById("item").value;
     let vendor = document.getElementById("vendor").value;
     let invoice = document.getElementById("invoice").value;
     let ordered = document.getElementById("ordered").value;
     let supplied = document.getElementById("supplied").value;
     let balance_qty = document.getElementById("balance_qty").value;
     let cost = document.getElementById("cost").value;
     let quantity = document.getElementById("quantity").value;
     if(parseFloat(quantity) <= 0){
          alert("Please input quantity supplied");
          $("#quantity").focus();
          return
     /* }else if(parseFloat(quantity) > parseFloat(balance_qty)){
          alert("Quantity supplied cannot be higher than balance quantity ordered");
          $("#quantity").focus();
          return; */
     }else{
          confirmPost = confirm("Are you sure to accept this quantity?", "");
          if(confirmPost){
               $.ajax({
                    method : "POST",
                    url : "../controller/accept_po_item.php",
                    data : {vendor:vendor, item:item, item_id:item_id, invoice:invoice, balance_qty:balance_qty, quantity:quantity, cost:cost, ordered:ordered, supplied:supplied},
                    beforeSend : function(){
                         $(".info").html("<div class='processing'><div class='loader'></div></div>");
                    },
                    success : function(response){
                         $("#accept_item").html(response);
                    }
               })
               setTimeout(function(){
                    $("#accept_item").load("receive_purchase_order.php?invoice="+invoice+" #accept_item");
               }, 2000);
               return false
          }else{
               return;
          }
     }
     
}
//view delivery/acceptance log for purchase order
function viewDelivery(id){
          
          $.ajax({
               type : "GET",
               url : "../controller/delivery_log.php?purchase_id="+id,
               beforeSend : function(){
                    document.getElementById("delivery").scrollIntoView();
                    $("#delivery").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#delivery").html(response);
                    
               }
          })
          // $("#sales_item").html("");
          return false;
     // }
     
 }
 //add staff designation
function addDesignation(){
     let designation = document.getElementById("designation").value;
     if(designation.length == 0 || designation.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input designation!");
          $("#designation").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_designation.php",
               data : {designation:designation},
               beforeSend : function(){
                    $(".info").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $(".info").html(response);
               }
          })
     }
     $("#designation").val('');
     $("#designation").focus();
     return false;
}
//add staff discipline
function addDiscipline(){
     let discipline = document.getElementById("discipline").value;
     if(discipline.length == 0 || discipline.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input discipline!");
          $("#discipline").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_discipline.php",
               data : {discipline:discipline},
               beforeSend : function(){
                    $(".info").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#discipline").val('');
     $("#discipline").focus();
     return false;
}
//add staff departments
function addStaffDepartment(){
     let department = document.getElementById("department").value;
     if(department.length == 0 || department.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input department!");
          $("#department").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_staff_department.php",
               data : {department:department},
               beforeSend : function(){
                    $(".info").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $(".info").html(response);
               }
          })
     }
     $("#department").val('');
     $("#department").focus();
     return false;
}

// Add new staff
function addStaff(){
     let last_name = document.getElementById("last_name").value;
     let other_names = document.getElementById("other_names").value;
     let phone_number = document.getElementById("phone_number").value;
     let dob = document.getElementById("dob").value;
     let staff_id = document.getElementById("staff_id").value;
     let title = document.getElementById("title").value;
     let gender = document.getElementById("gender").value;
     let marital_status = document.getElementById("marital_status").value;
     let religion = document.getElementById("religion").value;
     let employed = document.getElementById("employed").value;
     let address = document.getElementById("address").value;
     let email = document.getElementById("email").value;
     let nok = document.getElementById("nok").value;
     let nok_relation = document.getElementById("nok_relation").value;
     let nok_phone = document.getElementById("nok_phone").value;
     let staff_category = document.getElementById("staff_category").value;
     let staff_group = document.getElementById("staff_group").value;
     let department = document.getElementById("department").value;
     let designation = document.getElementById("designation").value;
     let discipline = document.getElementById("discipline").value;
     let bank = document.getElementById("bank").value;
     let account_num = document.getElementById("account_num").value;
     let pension = document.getElementById("pension").value;
     let pension_num = document.getElementById("pension_num").value;
     let todayDate = new Date();
     if(last_name.length == 0 || last_name.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter last name!");
          $("#last_name").focus();
          return;
     }else if(other_names.length == 0 || other_names.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter other names!");
          $("#other_names").focus();
          return;
     }else if(department.length == 0 || department.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select department!");
          $("#department").focus();
          return;
     }else if(gender.length == 0 || gender.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Gender!");
          $("#gender").focus();
          return;
     }else if(title.length == 0 || title.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select title!");
          $("#title").focus();
          return;
     }else if(email.length == 0 || email.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff's email address");
          $("#email").focus();
          return;
     }else if(phone_number.length == 0 || phone_number.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter staff's phone number");
          $("#phone_number").focus();
          return;
     }else if(phone_number.length != 11){
          alert("Phone number is not correct");
          $("#phone_number").focus();
          return;
    
     }else if(dob.length == 0 || dob.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter date of birth");
          $("#dob").focus();
          return;
     }else if(address.length == 0 || address.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff's residential address");
          $("#address").focus();
          return;
     }else if(employed.length == 0 || employed.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input employment date");
          $("#employed").focus();
          return;
     }else if(marital_status.length == 0 || marital_status.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff's marital status");
          $("#marital_status").focus();
          return;
     }else if(religion.length == 0 || religion.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select staff's Religion");
          $("#religion").focus();
          return;
     }else if(discipline.length == 0 || discipline.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please seelct staff's discipline");
          $("#discipline").focus();
          return;
     }else if(staff_category.length == 0 || staff_category.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please seelct staff's category");
          $("#staff_category").focus();
          return;
     }else if(staff_group.length == 0 || staff_group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please seelct staff group");
          $("#staff_group").focus();
          return;
     }else if(nok.length == 0 || nok.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff's Next of Kin");
          $("#nok").focus();
          return;
     }else if(nok_phone.length == 0 || nok_phone.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff's Next of Kin phone number");
          $("#nok_phone").focus();
          return;
     }else if(nok_phone.length != 11){
          alert("Phone number is not correct");
          $("#nok_phone").focus();
          return;
     }else if(nok_relation.length == 0 || nok_relation.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input Next of Kin relationship");
          $("#nok_relation").focus();
          return;
     }else if(designation.length == 0 || designation.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select staff designation");
          $("#designtation").focus();
          return;
     }else if(todayDate <= new Date(dob)){
          alert("You can not enter a futuristic date !");
          $("#dob").focus();
          return;
     }else if(todayDate < new Date(employed)){
          alert("You can not enter a futuristic date !");
          $("#employed").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_staff.php",
               data : {other_names:other_names, last_name:last_name, phone_number:phone_number, email:email, address:address, dob:dob, staff_id:staff_id, title:title, gender:gender, marital_status:marital_status, religion:religion, nok:nok, staff_group:staff_group,nok_phone:nok_phone, nok_relation:nok_relation, staff_category:staff_category, discipline:discipline, designation:designation, bank:bank, account_num:account_num, pension:pension, pension_num:pension_num, employed:employed, department:department},
               beforeSend : function(){
                    $("#add_staff").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#add_staff").html(response);
               }
          })
          /* $("#last_name").val('');
          $("#other_names").val('');
          $("#email").val('');
          $("#address").val('');
          $("#dob").val('');
          $("#phone_number").val('');
          $("#last_name").focus(); */
          setTimeout(function(){
               $("#add_staff").load("add_staff.php");
          }, 1500);
          return false;   
     }
      
}

//update staff details
function updateStaff(){
     let last_name = document.getElementById("last_name").value;
     let other_names = document.getElementById("other_names").value;
     let phone_number = document.getElementById("phone_number").value;
     let dob = document.getElementById("dob").value;
     let staff_id = document.getElementById("staff_id").value;
     let staff_num = document.getElementById("staff_num").value;
     let title = document.getElementById("title").value;
     let gender = document.getElementById("gender").value;
     let marital_status = document.getElementById("marital_status").value;
     let religion = document.getElementById("religion").value;
     let employed = document.getElementById("employed").value;
     let address = document.getElementById("address").value;
     let email = document.getElementById("email").value;
     let nok = document.getElementById("nok").value;
     let nok_relation = document.getElementById("nok_relation").value;
     let nok_phone = document.getElementById("nok_phone").value;
     let staff_category = document.getElementById("staff_category").value;
     let staff_group = document.getElementById("staff_group").value;
     let department = document.getElementById("department").value;
     let designation = document.getElementById("designation").value;
     let discipline = document.getElementById("discipline").value;
     let bank = document.getElementById("bank").value;
     let account_num = document.getElementById("account_num").value;
     let pension = document.getElementById("pension").value;
     let pension_num = document.getElementById("pension_num").value;
     let todayDate = new Date();
     if(last_name.length == 0 || last_name.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter last name!");
          $("#last_name").focus();
          return;
     }else if(other_names.length == 0 || other_names.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter other names!");
          $("#other_names").focus();
          return;
     }else if(department.length == 0 || department.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select department!");
          $("#department").focus();
          return;
     }else if(gender.length == 0 || gender.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select Gender!");
          $("#gender").focus();
          return;
     }else if(title.length == 0 || title.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select title!");
          $("#title").focus();
          return;
     }else if(email.length == 0 || email.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff's email address");
          $("#email").focus();
          return;
     }else if(phone_number.length == 0 || phone_number.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter staff's phone number");
          $("#phone_number").focus();
          return;
     }else if(phone_number.length != 11){
          alert("Phone number is not correct");
          $("#phone_number").focus();
          return;
    
     }else if(dob.length == 0 || dob.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter date of birth");
          $("#dob").focus();
          return;
     }else if(address.length == 0 || address.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff's residential address");
          $("#address").focus();
          return;
     }else if(employed.length == 0 || employed.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input employment date");
          $("#employed").focus();
          return;
     }else if(marital_status.length == 0 || marital_status.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff's marital status");
          $("#marital_status").focus();
          return;
     }else if(religion.length == 0 || religion.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select staff's Religion");
          $("#religion").focus();
          return;
     }else if(discipline.length == 0 || discipline.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please seelct staff's discipline");
          $("#discipline").focus();
          return;
     }else if(staff_category.length == 0 || staff_category.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please seelct staff's category");
          $("#staff_category").focus();
          return;
     }else if(staff_group.length == 0 || staff_group.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please seelct staff group");
          $("#staff_group").focus();
          return;
     }else if(nok.length == 0 || nok.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff's Next of Kin");
          $("#nok").focus();
          return;
     }else if(nok_phone.length == 0 || nok_phone.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input staff's Next of Kin phone number");
          $("#nok_phone").focus();
          return;
     }else if(nok_phone.length != 11){
          alert("Phone number is not correct");
          $("#nok_phone").focus();
          return;
     }else if(nok_relation.length == 0 || nok_relation.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input Next of Kin relationship");
          $("#nok_relation").focus();
          return;
     }else if(designation.length == 0 || designation.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select staff designation");
          $("#designtation").focus();
          return;
     }else if(todayDate <= new Date(dob)){
          alert("You can not enter a futuristic date !");
          $("#dob").focus();
          return;
     }else if(todayDate < new Date(employed)){
          alert("You can not enter a futuristic date !");
          $("#employed").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_staff.php",
               data : {other_names:other_names, last_name:last_name, phone_number:phone_number, email:email, address:address, dob:dob, staff_id:staff_id, staff_num:staff_num, title:title, gender:gender, marital_status:marital_status, religion:religion, nok:nok, staff_group:staff_group,nok_phone:nok_phone, nok_relation:nok_relation, staff_category:staff_category, discipline:discipline, designation:designation, bank:bank, account_num:account_num, pension:pension, pension_num:pension_num, employed:employed, department:department},
               beforeSend : function(){
                    $("#edit_customer").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#edit_customer").html(response);
               }
          })
          /* $("#last_name").val('');
          $("#other_names").val('');
          $("#email").val('');
          $("#address").val('');
          $("#dob").val('');
          $("#phone_number").val('');
          $("#last_name").focus(); */
          setTimeout(function(){
               // $("#edit_customer").load("edit_staff_details.php?customer="+staff_id);
               showPage("edit_staff_details.php?customer="+staff_id);

          }, 1500);
          return false;   
     }
      
}

// suspend active staff
function suspendStaff(staff_id){
     let disable = confirm("Are you sure you want to suspend this staff?", "");
     if(disable){
          // alert(user_id);
          $.ajax({
               type: "GET",
               url : "../controller/suspend_staff.php?id="+staff_id,
               beforeSend : function(){
                    $("#disable_user").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#disable_user").html(response);
               }
          })
          setTimeout(function(){
               // $('#disable_user').load("suspend_staff.php #disable_user");
               showPage("suspend_staff.php")
          }, 3000);
          return false;
     }
}

// recall staff
function recallStaff(staff_id){
     let activate = confirm("Are you sure you want to recall/reactivate this staff?", "");
     if(activate){
          $.ajax({
               type : "GET",
               url : "../controller/recall_staff.php?id="+staff_id,
               beforeSend : function(){
                    $("#activate_user").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#activate_user").html(response);
               }
          })
          setTimeout(function(){
               // $("#activate_user").load("recall_staff.php #activate_user");
               showPage("recall_staff.php")

          }, 3000);
          return false;
     }
}
// resign staff
function resignStaff(staff_id){
     let activate = confirm("Are you sure you want to retire this employee?", "");
     if(activate){
          $.ajax({
               type : "GET",
               url : "../controller/resign_staff.php?id="+staff_id,
               beforeSend : function(){
                    $("#disable_user").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#disable_user").html(response);
               }
          })
          setTimeout(function(){
               // $("#disable_user").load("resign_employee.php #disable_user");
               showPage("resign_employee.php");
          }, 3000);
          return false;
     }
}

// create a leave type
function addLeave(){
     let title = document.getElementById("title").value;
     let max_days = document.getElementById("max_days").value;
     let description = document.getElementById("description").value;
     if(title.length == 0 || title.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input leave title!");
          $("#title").focus();
          return;
     }else if(max_days.length == 0 || max_days.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input leave maximum days!");
          $("#max_days").focus();
          return;
     }else if(description.length == 0 || description.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of leave");
          $("#description").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_leave_type.php",
               data : {title:title, max_days:max_days, description:description},
               beforeSend: function(){
                    $("#crop_cycle").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $("#crop_cycle").html(response);
               }
          })
          setTimeout(function(){
               // $("#crop_cycle").load("add_leave_type.php #crop_cycle");
               showPage("add_leave_type.php");

          }, 2000)
          return false;    
     }
}

// edit a leave type
function editLeaveType(){
     let leave = document.getElementById("leave").value;
     let title = document.getElementById("title").value;
     let max_days = document.getElementById("max_days").value;
     let description = document.getElementById("description").value;
     if(title.length == 0 || title.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input leave title!");
          $("#title").focus();
          return;
     }else if(max_days.length == 0 || max_days.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input maximum leave days!");
          $("#max_days").focus();
          return;
     }else if(description.length == 0 || description.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input details of leave");
          $("#description").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/edit_leave_type.php",
               data : {leave:leave, title:title, max_days:max_days, description:description},
               beforeSend: function(){
                    $("#crop_cycle").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
               $("#crop_cycle").html(response);
               }
          })
          setTimeout(function(){
               // $("#crop_cycle").load("leave_types.php #crop_cycle");
               showPage("leave_types.php");

          }, 2000)
          return false;    
     }
}
// disable leave
function disableLeave(leave){
     let disable = confirm("Are you sure you want to disable this leave type?", "");
     if(disable){
          $.ajax({
               type : "GET",
               url : "../controller/disable_leave.php?id="+leave,
               beforeSend : function(){
                    $("#cycles").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
               }
          })
          setTimeout(function(){
               // $("#cycles").load("leave_types.php #cycles");
               showPage("leave_types.php");
          }, 2000);
          return false;
     }
}
// activate leave
function activateLeave(leave){
     let activate = confirm("Are you sure you want to activate this leave type?", "");
     if(activate){
          $.ajax({
               type : "GET",
               url : "../controller/activate_leave.php?id="+leave,
               beforeSend : function(){
                    $("#cycles").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
               }
          })
          setTimeout(function(){
               // $("#cycles").load("leave_types.php #cycles");
               showPage("leave_types.php");

          }, 2000);
          return false;
     }
}

//get employee details
function getEmployee(item_name, link){
     let employee_name = item_name;
     if(employee_name.length >= 3){
          if(employee_name){
               $.ajax({
                    type : "POST",
                    url :"../controller/"+link,
                    data : {employee_name:employee_name},
                    beforeSend : function(){
                         $("#sales_item").html("<p>Searching...</p>");
                    },
                    success : function(response){
                         $("#sales_item").html(response);
                    }
               })
               return false;
          }else{
               $("#sales_item").html("<p>Please enter atleast 3 letters</p>");
          }
     }
     
}

//select employee during leave application
function selectEmployee(id, staff_name){
     let employee = document.getElementById("employee");
     let staff = document.getElementById("staff");
     employee.value = staff_name;
     staff.value = id;
     // alert(vendor.value)
     /* $("#vendor").attr("readonly", true);
     $("#supplier").attr("readonly", true); */
     $("#sales_item").html('');
}

//get leave details
function getLeave(item_name){
     let leave_name = item_name;
     let staff = document.getElementById("staff").value;
     if(staff.length == 0 || staff.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select staff!");
          $("#employee").focus();
          return;
     }else{
          if(leave_name.length >= 3){
               if(leave_name){
                    $.ajax({
                         type : "POST",
                         url :"../controller/get_leave_type.php",
                         data : {leave_name:leave_name},
                         beforeSend : function(){
                              $("#transfer_item").html("<p>Searching...</p>");
                         },
                         success : function(response){
                              $("#transfer_item").html(response);
                         }
                    })
                    return false;
               }else{
                    $("#transfer_item").html("<p>Please enter atleast 3 letters</p>");
               }
          }
     }
}

//select leave during leave application
function selectLeave(id, leave_name){
     let leave_type = document.getElementById("leave_type");
     // let leave = document.getElementById("leave");
     leave_type.value = leave_name;
     // leave.value = id;
     // alert(vendor.value)
     /* $("#vendor").attr("readonly", true);
     $("#supplier").attr("readonly", true); */
     $("#transfer_item").html('');
     $.ajax({
          type : "GET",
          url : "../controller/get_leave_details.php?leave_id="+id,
          beforeSend : function(){
               $("#leave_details").html("<div class='processing'><div class='loader'></div></div>");
          },
          success : function(response){
               $("#leave_details").html(response);
               $("#start_date").val("");
               $("#end_date").val("");
               $("#total_days").val("");
          }
     })
}

// Check maximum days while applying for leave
function checkMaxDays() {
     let max_days = parseInt(document.getElementById("max_days")?.value || 0);
     let start_date = document.getElementById("start_date").value;
     let end_date = document.getElementById("end_date").value;

     // Convert to Date objects
     let todayDate = new Date();
     let startDate = new Date(start_date);
     let endDate = new Date(end_date);

     // Normalize to midnight (avoid timezone/time issues)
     todayDate.setHours(0, 0, 0, 0);
     startDate.setHours(0, 0, 0, 0);
     endDate.setHours(0, 0, 0, 0);

     // Validations
     if(!max_days){
          alert("Please select leave type to get maximum days!");
          $("#end_date").val("");
          $("#leave_type").focus();
          return;
     }else if(!start_date) {
          alert("Please input leave start date!");
          $("#end_date").val('');
          $("#start_date").focus();
          return;
     }else if(startDate < todayDate) {
          alert("Start date cannot be less than current day!");
          $("#end_date").val("");
          $("#start_date").focus();
          return;
     }else if(!end_date) {
          alert("Please select leave end date!");
          return;
     }

     // Calculate difference (include both start and end dates)
     const diffTime = endDate - startDate;
     const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;

     if(diffDays > max_days) {
          alert("You are not allowed more than " + max_days + " day(s) for the selected leave type");
          $("#end_date").val("");
          $("#end_date").focus();
     }else if(diffDays < 1) {
          alert("End date must be greater than or equal to start date!");
          $("#end_date").val("");
          $("#end_date").focus();
     }else{
          document.getElementById("total_days").value = diffDays;
     }
}
//apply for leave
function applyLeave(){
     let staff = document.getElementById("staff").value;
     let leave = document.getElementById("leave").value;
     let max_days = document.getElementById("max_days").value;
     let start_date = document.getElementById("start_date").value;
     let end_date = document.getElementById("end_date").value;
     let total_days = document.getElementById("total_days").value;
     let reason = document.getElementById("reason").value;

     if(!staff){
          alert("Please select employee");
          $("#employee").focus();
          return;
     }else if(!leave){
          alert("Please select leave type");
          $("#leave_type").focus();
          return;
     }else if(!start_date){
          alert("Please select start date");
          $("#start_date").focus();
          return;
     }else if(!end_date){
          alert("Please select end date");
          $("#start_date").focus();
          return;
     }else if(!reason){
          alert("Please input reason for leave");
          $("#reason").focus();
          return;
     }else{
          $.ajax({
               type: "POST",
               url : "../controller/apply_for_leave.php",
               data : {staff:staff, leave:leave, start_date:start_date, end_date:end_date, max_days:max_days, total_days:total_days, reason:reason},
               beforeSend : function(){
                    $("#add_staff").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#add_staff").html(response);
               }
          })
          return false;
     }
}

// approve leave
function approveLeave(leave_id){
     let approve = confirm("Are you sure you want to approve this leave request?", "");
     if(approve){
          $.ajax({
               type : "GET",
               url : "../controller/approve_leave.php?id="+leave_id,
               beforeSend : function(){
                    $("#leave_details").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#leave_details").html(response);
               }
          })
          setTimeout(function(){
               showPage("approve_leave.php");
          }, 2000);
          return false;
     }
}
// decline leave
function declineLeave(leave_id){
     let decline = confirm("Are you sure you want to reject this leave request?", "");
     if(decline){
          $.ajax({
               type : "GET",
               url : "../controller/decline_leave.php?id="+leave_id,
               beforeSend : function(){
                    $("#leave_details").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#leave_details").html(response);
               }
          })
          setTimeout(function(){
               showPage("approve_leave.php");

          }, 2000);
          return false;
     }
}
// retur from leave
function endLeave(leave_id){
     let approve = confirm("Are you sure you want to end staff leave?", "");
     if(approve){
          $.ajax({
               type : "GET",
               url : "../controller/end_leave.php?id="+leave_id,
               beforeSend : function(){
                    $("#leave_details").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#leave_details").html(response);
               }
          })
          setTimeout(function(){
               showPage("end_leave.php");
          }, 2000);
          return false;
     }
}

//mark staff present
function markPresent(){
     let staff = document.getElementById("staff").value;
     let attendance_time = document.getElementById("attendance_time").value;
     let remark = document.getElementById("remark").value;
     let today = new Date();
     let [hours, minutes] = attendance_time.split(":");
     let selectedTime = new Date(today.getFullYear(), today.getMonth(), today.getDate(), hours, minutes);
     let currentTime = new Date();
     if(!attendance_time){
          alert("Please input attendance time");
          $("#attendance_time").focus();
          return;
     }else if(selectedTime > currentTime){
          alert("Attendance time cannot be greater than current time");
          $("#attendance_time").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/mark_present.php",
               data : {staff:staff, attendance_time:attendance_time, remark:remark},
               beforeSend : function(){
                    $("#attendance").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#attendance").html(response);
                    setTimeout(function(){
                         showPage("attendance.php");
                    }, 2000);
               }
          })
          
     }
}
//mark staff present
function markAbsent(staff){
     let confirmAb = confirm("Are you sure you want to mark this staff absent?", "");
     if(confirmAb){
          $.ajax({
               type : "GET",
               url : "../controller/mark_absent.php?staff="+staff,
               
               beforeSend : function(){
                    $("#attendance").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#attendance").html(response);
                    setTimeout(function(){
                         showPage("attendance.php");
                    }, 2000);
               }
          })
          
     }else{
          return;
     }

}

//checkout staffs for the day
function checkOut(){
     let staff = document.getElementById("staff").value;
     let attendance_id = document.getElementById("attendance_id").value;
     let attendance_time = document.getElementById("attendance_time").value;
     // let remark = document.getElementById("remark").value;
     let today = new Date();
     let [hours, minutes] = attendance_time.split(":");
     let selectedTime = new Date(today.getFullYear(), today.getMonth(), today.getDate(), hours, minutes);
     let currentTime = new Date();
     if(!attendance_time){
          alert("Please input attendance time");
          $("#attendance_time").focus();
          return;
     }else if(selectedTime > currentTime){
          alert("Check out time time cannot be greater than current time");
          $("#attendance_time").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/check_out_staff.php",
               data : {attendance_id:attendance_id,staff:staff, attendance_time:attendance_time},
               beforeSend : function(){
                    $("#attendance").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#attendance").html(response);
                    setTimeout(function(){
                         showPage("check_out.php");
                    }, 2000);
               }
          })
          
     }
}

//calculate total earnings
function getTotalEarnings(){
     let basic_salary = document.getElementById("basic_salary")?.value || 0;
     let housing = document.getElementById("housing")?.value || 0;
     let medical = document.getElementById("medical")?.value || 0;
     let transport = document.getElementById("transport")?.value || 0;
     let utility = document.getElementById("utility")?.value || 0;
     let others = document.getElementById("others")?.value || 0;
     let total = document.getElementById("total");
     let total_pay = document.getElementById("total_pay");
     let totalEarning = parseFloat(basic_salary) + parseFloat(medical) + parseFloat(housing) + parseFloat(transport) + parseFloat(utility) + parseFloat(others);
     total.value = totalEarning;
     total_pay.value = totalEarning.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });;
}
//add salary structure for staff
function addSalary(){
     let staff = document.getElementById("staff").value;
     let basic_salary = document.getElementById("basic_salary").value;
     let housing = document.getElementById("housing").value;
     let medical = document.getElementById("medical").value;
     let transport = document.getElementById("transport").value;
     let utility = document.getElementById("utility").value;
     let others = document.getElementById("others").value;
     let total = document.getElementById("total").value;
     if(!basic_salary){
          alert("Please input basic salary");
          $("#basic_salary").focus();
          return;
     }else if(parseFloat(basic_salary) < 0 || parseFloat(housing) < 0 || parseFloat(medical) < 0 || parseFloat(transport) < 0 || parseFloat(utility) < 0 || parseFloat(others) < 0){
          alert("Values cannot be less than 0");
          $("#basic_salary").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_salary_structure.php",
               data : {staff:staff, basic_salary:basic_salary, housing:housing, medical:medical, transport:transport, utility:utility, others:others, total:total},
               beforeSend : function(){
                    $("#salary_structure").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#salary_structure").html(response);
                    setTimeout(function(){
                         showPage("salary_structure.php");
                    }, 2000);
               }
          })
     }
}
//update salary structure for staff
function updateSalary(){
     let salary_id = document.getElementById("salary_id").value;
     let staff = document.getElementById("staff").value;
     let basic_salary = document.getElementById("basic_salary").value;
     let housing = document.getElementById("housing").value;
     let medical = document.getElementById("medical").value;
     let transport = document.getElementById("transport").value;
     let utility = document.getElementById("utility").value;
     let others = document.getElementById("others").value;
     let total = document.getElementById("total").value;
     if(!basic_salary){
          alert("Please input basic salary");
          $("#basic_salary").focus();
          return;
     }else if(parseFloat(basic_salary) < 0 || parseFloat(housing) < 0 || parseFloat(medical) < 0 || parseFloat(transport) < 0 || parseFloat(utility) < 0 || parseFloat(others) < 0){
          alert("Values cannot be less than 0");
          $("#basic_salary").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_salary_structure.php",
               data : {salary_id:salary_id, staff:staff, basic_salary:basic_salary, housing:housing, medical:medical, transport:transport, utility:utility, others:others, total:total},
               beforeSend : function(){
                    $("#salary_structure").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#salary_structure").html(response);
                    setTimeout(function(){
                         showPage("salary_structure.php");
                    }, 2000);
               }
          })
     }
}
// Calculate net pay after deductions
function getNetPay() {
    const gross = parseFloat(document.getElementById("gross")?.value) || 0;
    const tax = parseFloat(document.getElementById("tax")?.value) || 0;
    const pension = parseFloat(document.getElementById("pension")?.value) || 0;
    const absence = parseFloat(document.getElementById("absence")?.value) || 0;
    const lateness = parseFloat(document.getElementById("lateness")?.value) || 0;
    const others = parseFloat(document.getElementById("others")?.value) || 0;
    const loans = parseFloat(document.getElementById("loans")?.value) || 0;

    const totalDeductions = tax + pension + absence + lateness + others + loans;
    const netEarning = gross - totalDeductions;

    const net_pay = document.getElementById("net_pay");
    const net_salary = document.getElementById("net_salary");
     // Format net pay with commas and 2 decimal places

    net_salary.value = netEarning.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    net_pay.value = netEarning.toFixed(2);
}

//generate pay roll for staff
function generatePayroll(){
     let payroll_date = document.getElementById("payroll_date").value;
     let staff = document.getElementById("staff").value;
     let working_days = document.getElementById("working_days").value;
     let days_at_work = document.getElementById("days_at_work").value;
     let leave_days = document.getElementById("leave_days").value;
     let suspension_days = document.getElementById("suspension_days").value;
     let absent_days = document.getElementById("absent_days").value;
     let basic_salary = document.getElementById("basic_salary").value;
     let housing = document.getElementById("housing").value;
     let medical = document.getElementById("medical").value;
     let transport = document.getElementById("transport").value;
     let utility = document.getElementById("utility").value;
     let other_allow = document.getElementById("other_allow").value;
     let gross = document.getElementById("gross").value;
     let pension = document.getElementById("pension").value;
     let pension_income = document.getElementById("pension_income").value;
     let tax = document.getElementById("tax").value;
     let absence = document.getElementById("absence").value;
     let lateness = document.getElementById("lateness").value;
     let loans = document.getElementById("loans").value;
     let others = document.getElementById("others").value;
     let net_pay = document.getElementById("net_pay").value;
     let employer_contribution = document.getElementById("employer_contribution").value;
     let taxable_income = document.getElementById("taxable_income").value;
     let tax_rate = document.getElementById("tax_rate").value;
     if(parseFloat(gross) < 0 || parseFloat(tax) < 0 || parseFloat(pension) < 0 || parseFloat(lateness) < 0 || parseFloat(absence) < 0 || parseFloat(loans) < 0 || parseFloat(others) < 0){
          alert("Values cannot be less than 0");
          $("#tax").focus();
          return;
     }else{
          let confirm_pay = confirm("Are you sure you want to generate this payroll?", "");
          if(confirm_pay){
               $.ajax({
                    type : "POST",
                    url : "../controller/generate_payroll.php",
                    data : {staff:staff, payroll_date:payroll_date, absent_days:absent_days, suspension_days:suspension_days, leave_days:leave_days, days_at_work:days_at_work, working_days:working_days,basic_salary:basic_salary, housing:housing, medical:medical, transport:transport, utility:utility, other_allow:other_allow, gross:gross, tax:tax, pension:pension, absence:absence, lateness:lateness, others:others, loans:loans, net_pay:net_pay, employer_contribution:employer_contribution, taxable_income:taxable_income, tax_rate:tax_rate, pension_income:pension_income},
                    beforeSend : function(){
                         $("#salary_structure").html("<div class='processing'><div class='loader'></div></div>");
                    },
                    success : function(response){
                         $("#salary_structure").html(response);
                         setTimeout(function(){
                              showPage("generate_payroll.php");
                         }, 2000);
                    }
               })
          }else{
               return;
          }
     }
}

//add tax rules
function addTaxRule(){
     let title = document.getElementById("title").value;
     let min_income = document.getElementById("min_income").value;
     let max_income = document.getElementById("max_income").value;
     let tax_rate = document.getElementById("tax_rate").value;
     if(!title){
          alert("Please input title");
          $("#title").focus();
          return;
     }else if(!min_income){
          alert("Please input Minimum Income value");
          $("#min_income").focus();
          return;
     }else if(!max_income){
          alert("Please input maximum Income value");
          $("#max_income").focus();
          return;
     }else if(!tax_rate){
          alert("Please input Tax rate value");
          $("#tax_rate").focus();
          return;
     }else if(parseFloat(min_income) > parseFloat(max_income)){
          alert("Maximum income must be greater than minimum income");
          $("#max_income").focus();
          return;
     }else if(parseFloat(min_income) <= 0 || parseFloat(max_income) <= 0 || parseFloat(tax_rate) <= 0){
          alert("Values cannot be less than or equal to zero");
          $("#max_income").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_tax_rule.php",
               data : {title:title, min_income:min_income, max_income:max_income, tax_rate:tax_rate},
               beforeSend : function(){
                    $("#tax_rules").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#tax_rules").html(response);
                    setTimeout(function(){
                         showPage("tax_rules.php");
                    }, 2000);
               }
          })
     }
}
//edit tax rules
function editTaxRule(){
     let tax = document.getElementById("tax").value;
     let title = document.getElementById("title").value;
     let min_income = document.getElementById("min_income").value;
     let max_income = document.getElementById("max_income").value;
     let tax_rate = document.getElementById("tax_rate").value;
     if(!title){
          alert("Please input title");
          $("#title").focus();
          return;
     }else if(!min_income){
          alert("Please input Minimum Income value");
          $("#min_income").focus();
          return;
     }else if(!max_income){
          alert("Please input maximum Income value");
          $("#max_income").focus();
          return;
     }else if(!tax_rate){
          alert("Please input Tax rate value");
          $("#tax_rate").focus();
          return;
     }else if(parseFloat(min_income) > parseFloat(max_income)){
          alert("Maximum income must be greater than minimum income");
          $("#max_income").focus();
          return;
     }else if(parseFloat(min_income) <= 0 || parseFloat(max_income) <= 0 || parseFloat(tax_rate) <= 0){
          alert("Values cannot be less than or equal to zero");
          $("#max_income").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_tax_rule.php",
               data : {tax:tax, title:title, min_income:min_income, max_income:max_income, tax_rate:tax_rate},
               beforeSend : function(){
                    $("#tax_rules").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#tax_rules").html(response);
                    setTimeout(function(){
                         showPage("tax_rules.php");
                    }, 2000);
               }
          })
     }
}
//add penalty fees
function addPenalty(){
     let penalty = document.getElementById("penalty").value;
     let amount = document.getElementById("amount").value;
     
     if(!penalty){
          alert("Please input penalty title");
          $("#penalty").focus();
          return;
     }else if(!amount){
          alert("Please input penalty amount");
          $("#amount").focus();
          return;
    
     }else if(parseFloat(amount) <= 0){
          alert("Values cannot be less than or equal to zero");
          $("#amount").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_penalty.php",
               data : {penalty:penalty, amount:amount},
               beforeSend : function(){
                    $("#penalties").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#penalties").html(response);
                    setTimeout(function(){
                         showPage("penalty_fees.php");
                    }, 2000);
               }
          })
     }
}
//update penalty fees
function editPenalty(){
     let penalty_id = document.getElementById("penalty_id").value;
     let penalty = document.getElementById("penalty").value;
     let amount = document.getElementById("amount").value;
     
     if(!penalty){
          alert("Please input penalty title");
          $("#penalty").focus();
          return;
     }else if(!amount){
          alert("Please input penalty amount");
          $("#amount").focus();
          return;
    
     }else if(parseFloat(amount) <= 0){
          alert("Values cannot be less than or equal to zero");
          $("#amount").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/update_penalty.php",
               data : {penalty_id:penalty_id, penalty:penalty, amount:amount},
               beforeSend : function(){
                    $("#penalties").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#penalties").html(response);
                    setTimeout(function(){
                         showPage("penalty_fees.php");
                    }, 2000);
               }
          })
     }
}
//approve payroll
function approvePayroll(payroll){
     let confirm_pay = confirm("Are you sure you want to approve this payroll?","");
     if(confirm_pay){
          $.ajax({
               type : "GET",
               url : "../controller/approve_payroll.php?payroll="+payroll,
               beforeSend : function(){
                    $("#payrolls").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#payrolls").html(response);
                    setTimeout(function(){
                         showPage("approve_payroll.php");
                    }, 2000)
               }
          })
     }else{
          return;
     }
}
//decline payroll
function declinePayroll(payroll){
     let confirm_pay = confirm("Are you sure you want to decline this payroll?","");
     if(confirm_pay){
          $.ajax({
               type : "GET",
               url : "../controller/decline_payroll.php?payroll="+payroll,
               beforeSend : function(){
                    $("#payrolls").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#payrolls").html(response);
                    setTimeout(function(){
                         showPage("approve_payroll.php");
                    }, 2000)
               }
          })
     }else{
          return;
     }
}
//get onthly payroll
function getMonthlyPayroll(payroll_date){
     $.ajax({
          type : "GET",
          url : "../controller/monthly_payroll_report.php?month="+payroll_date,
          beforeSend : function(){
               $(".new_data").html("<div class='processing'><div class='loader'></div></div>");
          },
          success : function(response){
               $(".new_data").html(response);
               
          }
     })
}
//selec staff during creating user
function takeStaff(id, name){
     let staff_id = document.getElementById("staff_id");
     let full_name = document.getElementById("full_name");
     let item = document.getElementById("item");

     staff_id.value = id;
     full_name.value = name;
     item.value = name;
     $("#sales_item").html('');
}

//calculate total repayment
/* function calculateRepayment(){
     let duration = parseFloat(document.getElementById("duration").value);
     let rent = parseFloat(document.getElementById("rent").value);
     let repayment = document.getElementById("repayment");
     let repay = document.getElementById("repay");
     if(!duration){
          alert("Please select contract duration");
          $("#duration").focus();
          return;
     }else{
          let total = duration * rent;
          repayment.value = total;
          repay.value = total.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });;
          $("#frequency").val('');
          $("#installment_amount").val('');
          $("#install").val('');
     }
} */
//calculateannual rentfromcost of purchase and percentagge
function calculateRent(){
    
     // let purchase_cost = parseFloat(document.getElementById("purchase_cost").value);
     let total_due = parseFloat(document.getElementById("total_due").value);
     let rent_percentage = document.getElementById("rent_percentage").value;
     let annual_rent = document.getElementById("annual_rent");
     let rent = document.getElementById("rent");
     
     let total = rent_percentage * total_due / 100;
     annual_rent.value = total;
     rent.value = total.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });;
     

}
//calculate installment
/* function calculateInstallments(){
     let duration = parseFloat(document.getElementById("duration").value);
     let repayment = parseFloat(document.getElementById("repayment").value);
     let frequency = document.getElementById("frequency").value;
     let install = document.getElementById("install");
     let installment_amount = document.getElementById("installment_amount");
     if(!duration){
          alert("Please select contract duration");
          $("#duration").focus();
          $("#frequency").val('');
          return;
     }else{
          // Calculate total number of installments based on duration in YEARS
          let installments = 0;
          if(frequency == "Weekly"){
               // 1 year ≈ 52 weeks
               installments = duration * 52;
          }else if(frequency == "Monthly"){
               // 1 year = 12 months
               installments = duration * 12;
          }else if(frequency == "Yearly"){
               // One installment per year
               installments = duration;
          }else{
               // fallback (default to 1)
               installments = 1;
          }
          //installment amountss
          let installment_pay = repayment /installments;
        
          installment_amount.value = installment_pay;
          install.value = installment_pay.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });;
     }
} */
function calculateInstallments(){
     let payment_duration = parseFloat(document.getElementById("payment_duration").value);
     // let purchase_cost = parseFloat(document.getElementById("purchase_cost").value);
     let total_due = parseFloat(document.getElementById("total_due").value);
  
     let install = document.getElementById("install");
     let installment_amount = document.getElementById("installment_amount");
     if(!payment_duration){
          alert("Please select purchase payment duration");
          $("#payment_duration").focus();
          $("#installment_amount").val('');
          $("#install").val('0.00');
          return;
     }else if(!total_due || total_due <= 0){
          alert("Total due must be greater than 0");
          $("purchase_cost").focus();
          return;
     }else{
          // Calculate total number of installments based on duration in MONTHS
          let installments = 0;
          if(payment_duration == "3"){
               installments = 3;
          }else if(payment_duration == "6"){
               installments = 6;
          }else if(payment_duration == "1"){
               installments = 1;
          }else{
               // fallback (default to 1)
               installments = 1;
          }
          //installment amountss
          let installment_pay = total_due /installments;
        
          installment_amount.value = installment_pay;
          install.value = installment_pay.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });;
     }
}
//calculate total due based on purchase cost and discount
function getTotalDue(){
     let purchase_cost = parseFloat(document.getElementById("purchase_cost").value);
     let discount = parseFloat(document.getElementById("discount")?.value || 0);
     let total_due = document.getElementById("total_due");
     let due = document.getElementById("due");
     
     let total = purchase_cost - discount;
     total_due.value = total;
     due.value = total.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });;
     $("#payment_duration").val('');
     $("#install").val('');
     $("#installment_amount").val('');
     $("#rent_percentage").val('');
     $("#rent").val('');
     $("#annual_rent").val('');

}
//rent repayment
function payRent(){
     let invoice = document.getElementById("invoice").value;
     let posted = document.getElementById("posted").value;
     let trans_date = document.getElementById("trans_date").value;
     let customer = document.getElementById("customer").value;
     let balance = document.getElementById("balance").value;
     let store = document.getElementById("store").value;
     let schedule = document.getElementById("schedule").value;
     let amount = document.getElementById("amount").value;
     let payment_mode = document.getElementById("payment_mode").value;
     let details = document.getElementById("details").value;
     let bank = document.getElementById("bank").value;
     let todayDate = new Date();
     if(payment_mode == "POS" || payment_mode == "Transfer"){
          if(bank.length == 0 || bank.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select bank!");
               $("#bank").focus();
               return;
          }    
     }
     if(payment_mode.length == 0 || payment_mode.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select payment_mode!");
          $("#payment_mode").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(parseFloat(amount) > parseFloat(balance)){
          alert("The amount entered exceeds the balance due. Please enter an amount that is less than or equal to the balance.");
          $("#balance").focus();
          return;
     }else if(trans_date.length == 0 || trans_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction date");
          $("#trans_date").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(new Date(trans_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#trans_date").focus();
          return;
     }else{
          let confirmPost = confirm("Are you sure you want to post this transaction?", "");
          if(confirmPost){
               $.ajax({
                    type : "POST",
                    url : "../controller/pay_rent.php",
                    data : {posted:posted, customer:customer, schedule:schedule, payment_mode:payment_mode, amount:amount, details:details, store:store, invoice:invoice, bank:bank, trans_date:trans_date},
                    beforeSend : function(){
                         $("#fund_account").html("<div class='processing'><div class='loader'></div></div>");
                    },
                    success : function(response){
                    $("#fund_account").html(response);
                    }
               })
               return false;   
          }else{
               return;
          }
     }
}
//payment for field purchase
function payField(id){
     let invoice = document.getElementById("invoice").value;
     let posted = document.getElementById("posted").value;
     let trans_date = document.getElementById("trans_date").value;
     let customer = document.getElementById("customer").value;
     let balance = document.getElementById("balance").value;
     let store = document.getElementById("store").value;
     let schedule = document.getElementById("schedule").value;
     let amount = document.getElementById("amount").value;
     let payment_mode = document.getElementById("payment_mode").value;
     let payment_id = document.getElementById("payment_id")?.value || 0;
     let details = document.getElementById("details").value;
     let bank = document.getElementById("bank").value;
     let todayDate = new Date();
     if(payment_mode == "POS" || payment_mode == "Transfer"){
          if(bank.length == 0 || bank.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select bank!");
               $("#bank").focus();
               return;
          }    
     }
     if(payment_mode.length == 0 || payment_mode.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select payment_mode!");
          $("#payment_mode").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(parseFloat(amount) > parseFloat(balance)){
          alert("The amount entered exceeds the balance due. Please enter an amount that is less than or equal to the balance.");
          $("#balance").focus();
          return;
     }else if(trans_date.length == 0 || trans_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction date");
          $("#trans_date").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(new Date(trans_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#trans_date").focus();
          return;
     }else{
          let confirmPost = confirm("Are you sure you want to post this transaction?", "");
          if(confirmPost){
               $.ajax({
                    type : "POST",
                    url : "../controller/pay_field_purchase.php",
                    data : {posted:posted, customer:customer, schedule:schedule, payment_mode:payment_mode, amount:amount, details:details, store:store, invoice:invoice, bank:bank, trans_date:trans_date, payment_id:payment_id},
                    beforeSend : function(){
                         $("#"+id).html("<div class='processing'><div class='loader'></div></div>");
                    },
                    success : function(response){
                         $("#"+id).html(response);
                    }
               })
               return false;   
          }else{
               return;
          }
     }
}

//start sucker removal
function removeSuckers(){
     let cycle = document.getElementById("cycle").value;
     let task_id = document.getElementById("task_id").value;
     // let crop = document.getElementById("crop").value;
     let quantity = document.getElementById("quantity").value;
    if(parseFloat(quantity) <= 0){
          alert("Please input quantity!");
          $("#quantity").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/remove_suckers.php",
               data : {quantity:quantity, cycle:cycle, task_id:task_id /* crop:crop */},
               beforeSend: function(){
                    $("#cycles").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
                    setTimeout(function(){
                         showPage("cycle_details.php?cycle="+cycle);
                    }, 2000);
               }
          })
     }
}
//start leaves removal
function removeLeaves(){
     let cycle = document.getElementById("cycle").value;
     let task_id = document.getElementById("task_id").value;
     let quantity = document.getElementById("quantity").value;
    if(parseFloat(quantity) <= 0){
          alert("Please input quantity!");
          $("#quantity").focus();
          return;
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/remove_leaves.php",
               data : {quantity:quantity, cycle:cycle, task_id:task_id},
               beforeSend: function(){
                    $("#cycles").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#cycles").html(response);
                    setTimeout(function(){
                         showPage("cycle_details.php?cycle="+cycle);
                    }, 2000);
               }
          })
     }
}

//upload receip/evidence of payment
function uploadReceipt(){
     let assigned_id = document.getElementById("assigned_id").value;
     let customer = document.getElementById("customer").value;
     let receipt = document.getElementById("receipt").value;
     let amount = document.getElementById("amount").value;
     let remark = document.getElementById("remark").value;
     if(receipt.length == 0 || receipt.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please upload receipt!");
          $("#receipt").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter amount paid!");
          $("#amount").focus();
          return;
     }else if(parseFloat(amount) <= 0){
          alert("Amount paid must be greater than zero!");
          $("#amount").focus();
          return;
     }else{
          var fd = new FormData();
          var files = $('#receipt')[0].files[0];
          fd.append('receipt',files);
          fd.append('assigned_id',assigned_id);
          fd.append('customer',customer);
          fd.append('amount',amount);
          fd.append('remark',remark);
          $.ajax({
               type : "POST",
               url : "../controller/upload_receipt.php",
               data: fd,
               contentType: false,
               processData: false,
               beforeSend: function(){
                    $("#upload_receipt_div").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#upload_receipt_div").html(response);
                    setTimeout(function(){
                         showPage("upload_payment.php")
                    }, 2000);
               }
          })
     }
     return false;    
}

//payment for documentation
function payDocumentation(){
     let invoice = document.getElementById("invoice").value;
     let posted = document.getElementById("posted").value;
     let trans_date = document.getElementById("trans_date").value;
     let customer = document.getElementById("customer").value;
     let balance = document.getElementById("balance").value;
     let store = document.getElementById("store").value;
     let assigned_id = document.getElementById("assigned_id").value;
     let amount = document.getElementById("amount").value;
     let payment_mode = document.getElementById("payment_mode").value;
     let payment_id = document.getElementById("payment_id")?.value || 0;
     let details = document.getElementById("details").value;
     let bank = document.getElementById("bank").value;
     let todayDate = new Date();
     if(payment_mode == "POS" || payment_mode == "Transfer"){
          if(bank.length == 0 || bank.replace(/^\s+|\s+$/g, "").length == 0){
               alert("Please select bank!");
               $("#bank").focus();
               return;
          }    
     }
     if(payment_mode.length == 0 || payment_mode.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please select payment_mode!");
          $("#payment_mode").focus();
          return;
     }else if(amount.length == 0 || amount.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction amount");
          $("#amount").focus();
          return;
     }else if(parseFloat(amount) > parseFloat(balance)){
          alert("The amount entered exceeds the balance due. Please enter an amount that is less than or equal to the balance.");
          $("#balance").focus();
          return;
     }else if(trans_date.length == 0 || trans_date.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please input transaction date");
          $("#trans_date").focus();
          return;
     }else if(details.length == 0 || details.replace(/^\s+|\s+$/g, "").length == 0){
          alert("Please enter description of transaction");
          $("#details").focus();
          return;
     }else if(new Date(trans_date) > todayDate){
          alert("Transaction date cannot be futuristic!");
          $("#trans_date").focus();
          return;
     }else{
          let confirmPost = confirm("Are you sure you want to post this transaction?", "");
          if(confirmPost){
               $.ajax({
                    type : "POST",
                    url : "../controller/pay_documentation.php",
                    data : {posted:posted, customer:customer, assigned_id:assigned_id, payment_mode:payment_mode, amount:amount, details:details, store:store, invoice:invoice, bank:bank, trans_date:trans_date, balance:balance},
                    beforeSend : function(){
                         $("#fund_account").html("<div class='processing'><div class='loader'></div></div>");
                    },
                    success : function(response){
                         $("#fund_account").html(response);
                         
                    }
               })
               return false;   
          }else{
               return;
          }
     }
}

//display investment when currency isselected
function showInvestment(){
     let currency = document.getElementById("currency").value;
     let duration = document.getElementById("duration").value;
     let customer = document.getElementById("customer").value;
     let label = document.getElementById("amount_currency");
     let complete_invest = document.getElementById("complete_invest");
     if(!customer){
          alert("Please select an investor");
          $("#item").focus();
          $("#currency").val('');
          return;
     }else if(!duration){
          alert("Please select contract duration");
          $("#duration").focus();
          $("#currency").val('');
          return;
     }else{
          let icon;
          if(currency == "Dollar"){
               icon = "$";
          }else{
               icon = "₦"
          }
          complete_invest.style.display = "flex";
          label.innerText = "Amount in "+currency+" ("+icon+")";
     }
}

//add exchange rate
function addExchangeRate(){
     let rate = document.getElementById("rate").value;
     if(!rate){
          alert("Please input exchange rate");
          $("#rate").focus();
          return;
    
     }else if(parseFloat(rate) <= 0){
          alert("Exchange rate cannot be lesser or equal to 0");
          $("#rate").focus();
          return;
     
     }else{
          $.ajax({
               type : "POST",
               url : "../controller/add_exchange_rate.php",
               data : {rate:rate},
               beforeSend : function(){
                    $("#exchange_rate").html("<div class='processing'><div class='loader'></div></div>");
               },
               success : function(response){
                    $("#exchange_rate").html(response);
                    setTimeout(function(){
                         showPage("exchange_rate.php");
                    }, 2000);
               }
          })
     }
}