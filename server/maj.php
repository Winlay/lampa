<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
header("Access-Control-Allow-Origin: *");
header('X-Frame-Options:ALL');
include './db_connexion.php';
include './db_getList.php';
$category = $_GET['category'];
$liste = getListArticle($category);
?>
<ul style="list-style: none">
<?php
if (count($liste)) {
    foreach ($liste as $info) {
        ?>

        <?php if ($category == 'videos') { ?>

            <li class="">
                <div class="timeline-badge"></div>
                <div class="panel ">
                    <div class="panel-heading panel-colorize">

                        <div class="panel-title">
                            <a href="#" class="v-mid badge badge-primary-lt badge-rounded badge-empty badge-grow"></a>
                            <span class="mid"> <?php echo $info['titre'] ?></span>
                        </div>
                        <span class="pull-right text-semi"><i class="fa fa-info-circle"></i></span>
                    </div>

                    <div class="panel-body panel-colorize">
                        <iframe style="width: 100%; height: 100%; min-height: 300px;" height="" src="<?php echo createEmbed($info['contenu']) ?>" frameborder="0" allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </li>
        <?php } elseif ($category == 'galerie') { ?>
            <li class="">
                <img src="http://palam-admin.noflay.com<?php echo $info['image'] ?>" class="responsive-image" alt="img">
                <div>
                    <b><?php echo $info['titre'] ?></b>
                </div>
                <div>
                    <?php echo $info['contenu'] ?>.
                </div>
                <div class="decoration"></div>
            </li> 

        <?php } else { ?>
            <li class="">
                <div class="timeline-badge"></div>
                <div class="panel ">
                    <div class="panel-heading panel-colorize">

                        <div class="panel-title">
                            <a href="#" class="v-mid badge badge-primary-lt badge-rounded badge-empty badge-grow"></a>
                            <span class="mid"> <?php echo $info['titre'] ?></span>
                        </div>
                        <span class="pull-right text-semi"><i class="fa fa-info-circle"></i></span>
                    </div>

                    <div class="panel-body panel-colorize">
                        <p>
                            <?php echo $info['contenu'] ?>.
                        </p>
                    </div>
                </div>
            </li> 

            <?php
        }
    }
} else {
    ?>
    <li class="">
        <div class="timeline-badge"></div>
        <div class="panel ">
            <div class="panel-heading panel-colorize">

                <div class="panel-title">
                    <a href="#" class="v-mid badge badge-primary-lt badge-rounded badge-empty badge-grow"></a>
                    <span class="mid">OOps!</span>
                </div>
                <span class="pull-right text-semi"><i class="fa fa-info-circle"></i></span>
            </div>

            <div class="panel-body panel-colorize">
                <p>
                    Pas de nouvel article dans cette categorie.
                </p>
            </div>
        </div>
    </li>
<?php } ?>

</ul>