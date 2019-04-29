<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
    $(document).ready(function() {
        $(".button-deconnect").click(function() {
            alert("Vous allez être déconnecté.");
        });
    });
</script>

<script>
    $(function() {
        $('#recherche').autocomplete({
            source: 'header/recherche.php'
        });
    });
</script>

<header class>
    <div class="header-block leftSide">
        <?php
        // On affiche la photo de profil
        if (isset($_SESSION['util'])) { 
            $_SESSION['util']->afficherPhoto(40,40);
        }
        ?>
    </div>
    <div class="header-block rightSide">
        <a class="button-deconnect" href="index.php?deconnect=true"> <span class=" glyphicon glyphicon-log-out"> Deconnect </span> </a>
    </div>
    <div class="header-block centerPart">
        <form>
            <input class="max-width" type="text" id="recherche" />
        </form>
    </div>
</header>
