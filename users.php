<?php
    session_start();
    ob_start();
    if(isset($_SESSION['caissier'])):
        $pageTitle = "Utilisateurs";
        include "init.php";
        $do = isset($_GET['do']) ? htmlspecialchars($_GET['do']) : "changepass";
        echo "<div class='container'>";
        $Users = new Users();

        if($do == "changepass" ) {
                echo "<h1 class='text-center mt-3 mb-3'>Changer votre password</h1>";
            
            if(isset($_POST['modifierPass'])) {
                $prevPass    = htmlspecialchars($_POST['prevPass']);
                $newPass     = htmlspecialchars($_POST['newPass']);
                $confirmPass = htmlspecialchars($_POST['confirmPass']);
                $userid      = $_SESSION['userid'];
                
                $getUser = $Users->getUser($userid);
                $formErrors = [];
                
                if(empty($prevPass)) {
                    $formErrors[] = "Donner votre ancien mot de passe";
                }
                
                if(empty($newPass)) {
                    $formErrors[] = "Donner le nouveau mot de passe";
                }
                
                if($newPass == $prevPass) {
                    $formErrors[] = "l'ancien et le nouveau mot de passe se ressemblent";
                }

                if(empty($confirmPass)) {
                    $formErrors[] = "Confirmer votre mot de passe";
                }

                if(is_numeric($newPass)){
                    $formErrors[] = "Donner un mot de passe plus complexe";
                }

                if(!password_verify($prevPass,$getUser['uuPassword']) && !empty($prevPass)) {
                    $formErrors[] = "Ancien mot de passe <strong>Est incorrect</strong>";
                }

                if($newPass != $confirmPass){
                    $formErrors[] = "La confirmation <strong>n'est pas valide</strong>";
                }

                if(empty($formErrors)) {
                    $newPassHash = password_hash($newPass,PASSWORD_DEFAULT);
                    try {

                        try{
                            $setpass = $Users->setPassword($newPassHash,$userid);
                        }catch(PDOException $e){
                            $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                        } 
    
                        if($setpass > 0) {
                            $theMsg = "<div class='alert alert-success msg'>Mot de Passe a ete modifier</div>";
                        }
                    } catch(PDOException $e){
                        $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                    } 

                }

            }

        ?>

            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <p><?php
                        if(isset($theMsg)){
                            echo $theMsg;
                            header("refresh:2;url=users.php?do=changepass");
                        }
                    ?></p>
                    <?php
                        if(isset($formErrors)){
                            foreach($formErrors as $error):
                                echo "<div class='alert alert-danger msg'>{$error}</div>";
                            endforeach;
                        }
                    ?>
                    <form method="POST">
                        <div class="form-group mb-2">
                            <input type="password" class="form-control" name="prevPass" placeholder="donner votre ancien mot de passe" value="<?=$prevPass??null?>" >
                        </div>
                        <div class="form-group mb-2">
                            <input type="password" class="form-control" name="newPass" placeholder="donner le nouveau mot de passe" value="<?=$newPass??null?>">
                        </div>
                        <div class="form-group mb-2">
                            <input type="password" class="form-control" name="confirmPass" placeholder="confirmer le mot de passe" value="<?=$confirmPass??null?>">
                        </div>
                        <div class="d-grid mb-2">
                            <input type="submit" name="modifierPass" class="btn btn-primary" name="prevPass" value="Modifier">
                        </div>
                    </form>
                </div>
            </div>

        <?php        
        } else {
            header("Location: users.php");
        }  

        echo "</div>"; 
        include $tpl . "footer.php";
    else:
        header("Location: index.php");
    endif;
    ob_end_flush();
?>