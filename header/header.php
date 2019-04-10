<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
    $(document).ready(function() {
        $("button").click(function() {
            alert("Vous allez être déconnecté.");
            // Appel page d'accueil
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
    <div class="header_leftPart">
        <?php
        // On affiche la photo de profile
        // print_r($_SESSION['util']);
        if (isset($_SESSION['util'])) { 
            $_SESSION['util']->afficherPhoto(40,40);
        }
        ?>
    </div>
    <div class="header_rightPart">
        <button class=" button "> <span class=" glyphicon glyphicon-log-out"> Deconnect </span> </button>
    </div>
    <div class="header_centerPart">
        <form>
            <input class="max-dim" type="text" id="recherche" />
        </form>
    </div>
</header>