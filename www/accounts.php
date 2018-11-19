<form method="post" action="block/addAccount.php">

    <label for="account_name">Account Name</label><br>
    <input type="text" name="account_name"><br>

    <label for="account_type">Account Type</label><br>
    <select name="account_type">
        <option value="personal">Personal</option>
        <option value="business">Business</option>
    </select><br>

    <label for="account_number">Account Number</label><br>
    <input type="text" name="account_number"><br>

    <br>

    <button type="submit">Add Account</button>
</form>