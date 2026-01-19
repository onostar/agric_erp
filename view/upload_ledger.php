<div id="add_bank" class="displays">
    <div class="info"></div>
    <div class="add_user_form" style="width:50%; margin:20px 0">
        <h3 style="background:var(--tertiaryColor)">Upload Ledger</h3>
        <form method="POST"  id="addCatForm" action="../controller/upload_ledger.php" enctype="multipart/form-data">
            <div class="inputs">
                <div class="data">
                    <label for="items">Upload Ledger</label>
                    <input type="file" name="items" id="items" style="background:var(--tertiaryColor); color:#fff">
                </div>
                
                <button type="submit">Upload <i class="fas fa-upload"></i></button>
            </div>
        </form>
    </div>
</div>
