<?php
    session_start();
    ob_start();
    $pageTitle = "Login";
    $noNav = "";
    include "init.php";

    if(isset($_SESSION['username'])):
        header("Location: admin/DashBoard.php");
    elseif(isset($_SESSION['caissier'])):
        header("Location: DashBoard.php");
    endif;

    if(isset($_POST['submitForm'])){
        // define class
        $users = new Users();

        $user = htmlspecialchars($_POST['username']);
        $pass = htmlspecialchars($_POST['password']);
        
        try {
            $check = $users->checkUser($user);
            if($check > 0) {
                if(password_verify($pass,$check['uuPassword'])) {
                    $_SESSION['userid'] = $check['uuid'];
                    $_SESSION['uuStatus'] = $check['uuStatus'];
                    
                    if($_SESSION['uuStatus'] == 1) {
                        $_SESSION['username'] = $user;                            
                        if(password_verify("1234",$check['uuPassword'])) {
                            header("Location: admin/users.php?do=changepass");
                        }else {
                            header("Location: admin/DashBoard.php");
                        }
                    } elseif($_SESSION['uuStatus'] == 0) {
                        $_SESSION['caissier'] = $user;                            
                        if(password_verify("1234",$check['uuPassword'])) {
                            header("Location: users.php?do=changepass");
                        }else {
                            header("Location: DashBoard.php");
                        }
                    }



                    

                    if(password_verify("1234",$check['uuPassword'])) {
                        header("Location: users.php?do=changepass");
                    }

                }else {
                    $theMsg = "<div class='alert alert-danger msg'>Mot de passe est incorrect</div>";
                }    
            } else {
                $theMsg = "<div class='alert alert-danger msg'>Nom d'utilisateur est incorrect</div>";
            }
        } catch(PDOException $e) {
            $theMsg = "<div class='alert alert-danger'>{$e->getMessage()}</div>";
        }

    }

?>

<div class="container">
    <h1 class="text-center mt-3 mb-3">LOGIN</h1>
    <div class="row">
        <div class="col-sm-6 offset-sm-3 offset-lg-4 col-lg-4">
            <p><?=$theMsg??null?></p>
            <form method="POST">
                <div class="form-group mb-2">
                    <input type="text" class="form-control" name="username" placeholder="Entrer Votre Nom d'utilisateur" autocomplete="off" value="<?= $user??null?>">
                </div>
                <div class="form-group mb-2">
                    <input type="password" class="form-control" name="password" placeholder="Entre Votre Mot de passe" autocomplete="off" value="">
                </div>
                <div class="d-grid">
                    <input type="submit" class="btn btn-primary" name="submitForm" value="Connecter">
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    include $tpl . "footer.php";
    ob_end_flush();
?>
