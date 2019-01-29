<?php
    require('./view/template/top.php');
    require('./view/template/navbar.php');
?>
<div class="container">
    <div class="row mt-3">
        <div class="col-auto">
            <div class="card" style="width: 18rem;"><div class="card-header text-center">Gestion du site</div></center>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="./index.php?action=administation&admin=addAdmin">Ajouter un compte ADMIN</a></li>
                    <li class="list-group-item">
                        <a id="displayPseudo" href="#">Modifier un compte utilisateur</a>
                        <form action="./index.php" method="get">
                            <input class="form-control" id="pseudoField" type="text" name="pseudo" placeholder="Entrez son pseudo">
                            <input type="hidden" name="action" value="myProfile">
                        </form>
                    </li>
                </ul>
            </div>
        </div>
            <div class="card" style="width: 18rem;"><div class="card-header text-center">Signalements</div></center>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="./index.php?action=handleReport">Gérer les signalements</a></li>
                </ul>
            </div>
        </div>        
    </div>
</div>
