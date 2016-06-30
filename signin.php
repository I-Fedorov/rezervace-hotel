<?php
session_start();
require 'processing/db.php';
$err_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];


    $stmt = $db->query("SELECT email FROM users ");
    $all_emails = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
//    var_dump($all_emails);

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute(array($email));
    $existing_user = @$stmt->fetchAll()[0];

    if (password_verify($password, $existing_user["password"])) {
        $_SESSION['user_id'] = $existing_user["id"];
        $_SESSION['name'] = $existing_user["name"];
        $_SESSION['email'] = $existing_user["email"];
        $_SESSION['isAdmin'] = $existing_user["isAdmin"];
    } else {
        $err_msg = "*Wrong password";
    }

    if (!in_array($email, $all_emails)) {
        $err_msg = "*Wrong email";
    }


    if ($err_msg == "") {
        header('Location: index.php');
    }
}
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign In</title>
        <?php include './includes/links.php' ?>

    </head>
    <body>


        <div class=" container">
            <header>
                <nav class="navbar navbar-default">
                    <div class="container-fluid">

                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>

                            </button>
                            <a class="navbar-brand" href="./index.php">Main Page</a>
                        </div>

                    </div>
                </nav>

            </header>

            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4">
                    <h3 >Sign in to continue</h3>
                    <div class="account-wall">
                        <img class="profile-img" src="img/01.png"
                             alt="">
                        <form class="form-signin"  method="POST">

                            <input type="text" class="form-control" name="email" placeholder="Email" required autofocus>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                            <div class="err">  <?php echo $err_msg; ?> </div>
                            <button class="btn btn-lg btn-primary btn-block" type="submit">
                                Sign in</button>

                        </form>
                    </div>
                    <a href="signup.php" class="text-center new-account">Create an account </a>

                </div>
            </div>
        </div>

    </body>
</html>
