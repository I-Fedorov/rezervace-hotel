<?php
if (!isset($_SESSION)) {
    session_start();

    $sign_section = '<ul class="nav navbar-nav navbar-right">
                                <li><a href="./signin.php">Sign In</a></li>
                                <li><a href="./signup.php">Sign Up</a></li>

                            </ul>';
}




if (isset($_SESSION["user_id"])) {

    if (isset($_SESSION["name"])) {
        $as = $_SESSION["name"];
    } else {
        $as = $_SESSION["email"];
    }



    $sign_section = ' <ul class="nav navbar-nav navbar-right">

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sign in as ' . $as . '<span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="my_reservations.php">My reservations</a></li>
                                        <li class="divider"></li>
                                        <li><a href="./signout.php">Sign Out</a></li>
                                    </ul>
                                </li>
                            </ul>';
}

$admin_link = "";
if (isset($_SESSION["isAdmin"])) {
    if ($_SESSION["isAdmin"]) {
        $admin_link = '<li><a href="./admin.php">Admin Page</a></li>';
    }
}
?>





<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./index.php">Main Page</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php echo $admin_link; ?>
                </ul>

                <?php echo $sign_section; ?>
            </div>
        </div>
    </nav>

</header>