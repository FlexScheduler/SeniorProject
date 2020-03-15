<?php
if (isset($_POST["login"])) {
    session_start();
    $_SESSION["username"] = $_POST["username"];
    $_SESSION["password"] = $_POST["password"];

    header("Location: uploadDriver.php");
}
?>

<html>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Enter your username:</label>
        <input type="text" id="username" name="username"/>
        <br/>
        <label for="password">Enter your password:</label>
        <input type="password" id="password" name="password"/>
        <br/>
        <input type="submit" value="Login" id="login" name="login"/>
    </form>
</body>

</html>
