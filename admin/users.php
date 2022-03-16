<?php
    session_start();
    ob_start();
    if(isset($_SESSION['username'])):
        $pageTitle = "Utilisateurs";
        include "init.php";
        $do = isset($_GET['do']) ? htmlspecialchars($_GET['do']) :"Home";
        echo "<div class='container'>";
        $Users = new Users();

        if($do == "Home"){
            echo "<h1 class='text-center mt-3 mb-3'>Utilisateurs</h1>";
    ?>
        

    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addModalLabel">Ajouter un utilisateur</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="username" autocomplete="off" placeholder="Donner le Nom d'utilisateur">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="name" autocomplete="off" placeholder="Donner le Nom Complet">
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" class="form-control" name="phone" autocomplete="off" placeholder="Donner le numero de telephone">
                    </div>
                    <div class="form-group mb-2 p-select">
                        <select name="status" class="form-control">
                            <option value="">Choisir Son Status</option>
                            <option value="Admin">Admin</option>
                            <option value="Caissier">Caissier</option>
                        </select>
                        <i class='fas fa-angle-down p-arrow'></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" name="addUser" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <?php
        if(isset($_POST['addUser'])) {
            $user       = htmlspecialchars($_POST['username']);
            $name       = htmlspecialchars($_POST['name']);
            $phone      = htmlspecialchars($_POST['phone']);
            $status     = htmlspecialchars($_POST['status']);
            
            $passHash = password_hash("1234",PASSWORD_DEFAULT);

            $formErrors = [];

            $check = $Users->checkUser($user);
            if($check>0) {
                $formErrors[] = "le Nom d'utilisateur Existe deja";
            }

            if(empty($user)){
                $formErrors[] = "Nom d'utilisateur est obligatoire";
            }

            if(empty($name)){
                $formErrors[] = "Nom Complet est obligatoire";
            }

            if(empty($phone)){
                $formErrors[] = "le numero de telephone est obligatoire";
            }

            if(!is_numeric($phone) && !empty($phone)){
                $formErrors[] = "Numero telephone doit contenir uniquement des nombres";
            }

            if($status == "Admin"){
                $status = 1;
            }elseif($status == "Caissier") {
                $status = 0;
            }else {
                $formErrors[] = "Status est obligatoire";
            }
        
            foreach($formErrors as $error):
                $theMsg = "<div class='alert alert-danger msg'>{$error}</div>";
            endforeach;

            if(empty($formErrors)){
                try{
                    $insert = $Users->insertUser($user,$passHash,$name,$phone,$status);
                    if($insert > 0){
                        $theMsg = "<div class='alert alert-success msg'>Utilisateur Ajouter Avec Success</div>";
                    }
                }catch(PDOException $e){
                    $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                }
            }

        }
    
    ?>



    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Modification Utilisateur</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <input type="text" name="username" id="username" class="form-control mb-2" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="text" name="name" id="name" class="form-control mb-2" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="text" name="phone" id="phone" class="form-control mb-2" autocomplete="off">
                    </div>
                    <div class="form-group p-select">
                        <select name="status" id="status" class="form-control mb-2">
                            <option value="Admin">Admin</option>
                            <option value="Caissier">Caissier</option>
                        </select>
                        <i class='fas fa-angle-down p-arrow'></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="editUser" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <?php 
        if(isset($_POST['editUser'])) {
            $id         = htmlspecialchars($_POST['id']);
            $user       = htmlspecialchars($_POST['username']);
            $name       = htmlspecialchars($_POST['name']);
            $phone      = htmlspecialchars($_POST['phone']);
            $status     = htmlspecialchars($_POST['status']);
        

            $zzuser = $Users->getUser($id);

            $formErrors = [];

            $check = $Users->checkUserForUpdate($user,$id);
            
            if($check > 0) {
                $formErrors[] = "le Nom d'utilisateur Existe deja";
            }

            if(empty($user)){
                $user = $zzuser['uuUsername'];
            }

            if(empty($name)){
                $name = $zzuser['uuFullName'];
            }

            if(empty($phone)){
                $phone = $zzuser['uuCellphone'];
            }

            if(!is_numeric($phone) && !empty($phone)){
                $formErrors[] = "Numero telephone doit contenir uniquement des nombres";
            }

            if($status == "Admin"){
                $status = 1;
            }elseif($status == "Caissier") {
                $status = 0;
            }else {
                $status = $zzuser['uuStatus'];
            }
        
            foreach($formErrors as $error):
                $theMsg = "<div class='alert alert-danger msg'>{$error}</div>";
            endforeach;

            try {
                $update = $Users->updateUser($user,$name,$phone,$status,$id);
                if($update > 0){
                    $theMsg = "<div class='alert alert-success msg'>Utilisateur Modifié Avec Succés</div>";
                }
            }catch(PDOException $e){
                $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
            }

        }
    
    ?>


    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Supprimer l'Utilisateur</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST">
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" name="deleteUser" class="btn btn-danger">Supprimer</button>
            </div>
        </form>
        </div>
    </div>
    </div>


    <?php
        if(isset($_POST['deleteUser'])) {
            $id = htmlspecialchars($_POST['id']);
            $checkUserNotInBranch = $Users->checkUserNotInBranch($id);
            
            $formErrors = [];

            if($checkUserNotInBranch > 0):
                $formErrors[] = "Changer le caissier de [ {$checkUserNotInBranch['bbBrancheName']} ] pour pouvoir supprimer cet utilisateur";
            endif;

            foreach($formErrors as $error) {
                echo  "<div class='alert alert-danger'>{$error}</div>";
            }

            if(empty($formErrors)){
                try {
                    $delete = $Users->deleteUser($id);
                    if($delete > 0){
                        $theMsg = "<div class='alert alert-success msg'>Utilisateur Supprimé Avec succés</div>";
                    }
                } catch(PDOException $e){
                    $theMsg = "<div class='alert alert-danger msg'>{$e->getMessage()}</div>";
                }
            } 

        }
    
    ?>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class='fas fa-plus'></i> Nouveau
            </button>
            <?php
                if(isset($theMsg)) {
                    echo $theMsg;
                    header("refresh: 2; url=users.php");
                }
            ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-primary">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Nom d'utilisateur</th>
                            <th scope="col">Nom Complet</th>
                            <th scope="col">Telephone</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $users = $Users->getUsers(); foreach($users as $user):?>
                        <tr>
                            <td><?=$user['uuid'];?></td>
                            <td><?=$user['uuUsername'];?></td>
                            <td><?=$user['uuFullName'];?></td>
                            <td><?=$user['uuCellphone'];?></td>
                            <td><?=$user['uuStatus'] == 1 ? "Admin" : "Caissier";?></td>
                            <td>
                                <button type="button" class="btn btn-primary mb-2 btnEditUser" data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class='fas fa-edit'></i>
                                </button>
                                <button type="button" class="btn btn-danger mb-2 btnDeleteUser" data-del="<?=$user['uuid'];?>" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class='fas fa-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php    
        } elseif($do == "changepass" ) {
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
                        $setpass = $Users->setPassword($newPassHash,$userid);
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
                            <input type="text" class="form-control" name="prevPass" placeholder="donner votre ancien mot de passe" value="<?=$prevPass??null?>" >
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" class="form-control" name="newPass" placeholder="donner le nouveau mot de passe" value="<?=$newPass??null?>">
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" class="form-control" name="confirmPass" placeholder="confirmer le mot de passe" value="<?=$confirmPass??null?>">
                        </div>
                        <div class="d-grid mb-2">
                            <input type="submit" name="modifierPass" class="btn btn-primary" name="prevPass" value="Modifier">
                        </div>
                    </form>
                </div>
            </div>

        <?php        
        }  

        echo "</div>"; 
        include $tpl . "footer.php";
    else:
        header("Location: ../index.php");
    endif;
    ob_end_flush();
?>