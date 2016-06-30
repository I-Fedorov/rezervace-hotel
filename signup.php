<?php
session_start();

require 'processing/db.php';


$err_msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    //against XSS / cross-Site Scripting atack
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $password = $_POST['password'];

    //ctype_space Возвращает TRUE, если каждый символ в строке text создает какой-нибудь из пробельных символов, FALSE в противном случае.
    if (ctype_space($name) || empty($name)) {
        $name = NULL;
    }
//password validation 8 charakters 1 letter/number
    $re = "/^(?=.*[a-z])(?=.*\\d).{8,}$/i";
    if (!preg_match($re, $password)) {
        $err_msg = "*Password must contain at least 8 character with 1 number/letter";
    }

    //email validation
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $stmt = $db->query("SELECT email FROM users ");
        $all_emails = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
//        var_dump($all_emails);
        //if email already in db
        if (in_array($email, $all_emails)) {
            $err_msg = "*User with this email already exists";
        }
    } else {
        $err_msg = "Wrong email adress";
    }


    if ($err_msg == "") {
        //password_hash use the bcrypt algorithm (default as of PHP 5.5.0)
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users(name,email, password) VALUES (?,?,?)");
        $stmt->execute(array($name, $email, $hashed));

        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute(array($email));
        $user = @$stmt->fetchAll()[0];
        $_SESSION['user_id'] = $user["id"];
        $_SESSION['name'] = $user["name"];
        $_SESSION['email'] = $user["email"];
        header('Location: ./index.php');
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
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
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="./signin.php">Sign In</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4">
                    <h3>Create your account </h3>
                    <div class="account-wall">
                        <img class="profile-img" src="img/01.png"
                             alt="">

                        <form class="form-signin"  method="POST">

                            <input type="text" class="form-control" name="name" placeholder="Name"  >

                            <input type="text" class="form-control" name="email" placeholder="Email *" required autofocus>
                            <input type="password" class="form-control" name="password" placeholder="Password*" required>
                            <div class="req"> * required</div>
                            <div class="err"> <?php echo $err_msg; ?></div>
                            <button class="btn btn-lg btn-primary btn-block" type="submit">
                                Sign Up</button>

                        </form>
                    </div>
                </div>
            </div>

        </div>

    </body>
</html>
