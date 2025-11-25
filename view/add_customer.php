<div id="add_bank" class="displays">
    <div class="info"  style="width:30%; margin:20px"></div>
    <div class="add_user_form" style="width:30%; margin:20px">
        <h3>Add Customers/Client</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form class="addUserForm">
            <div class="inputs" style="gap:.5rem;">
                <div class="data" style="width:100%">
                    <label for="customer">Customer Name</label>
                    <input type="text" name="customer" id="customer">
                </div>
                <div class="data" style="width:100%">
                    <label for="phone_number">Phone number</label>
                    <input type="text" name="phone_number" id="phone_number">
                </div>
                <div class="data" style="width:100%">
                    <label for="Address">Address</label>
                    <input type="text" name="address" id="address">
                </div>
                <div class="data" style="width:100%">
                    <label for="email">Email address</label>
                    <input type="text" name="email" id="email" required>
                </div>
                <div class="data"style="width:100%;">
                    <label for="customer_type">Customer type</label>
                    <select name="customer_type" id="customer_type">
                        <option value="" selected>Select type</option>
                        <option value="Investor">INVESTOR</option>
                        <option value="Retail Customer">RETAIL CUSTOMER</option>
                    </select>
                </div>
            </div>
            <div class="inputs">
                <button type="button" id="add_customer" name="add_customer" onclick="addCustomer()">Add Customer <i class="fas fa-plus"></i></button>
            </div>
</form>    
    </div>
</div>
