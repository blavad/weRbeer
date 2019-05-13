<link rel="stylesheet" href="/resources/demos/style.css">
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="icon" type="image/png" href="img/logo.png" />



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
                minLength: 0,
                source: 'header/recherche.php'
            })
            .data("ui-autocomplete")._renderItem = function(ul, item) {
                if (item.type == "b") {
                    return $("<li>")
                        .data("item.autocomplete", item)
                        .append("<a href='biere?nomB=" + item.value + "' style='margin-left:10px' ><img src='" + item.icon + "' width='10px', height='30px'><span style='margin-left:40px'>" + item.label + "</span></a>")
                        .appendTo(ul);
                } else if (item.type == "u"){
                    return $("<li>")
                        .data("item.autocomplete", item)
                        .append("<a href='profil.php?id=" + item.value + "'><img src='" + item.icon + "' width='30px', height='30px'><span style='margin-left:30px'>" + item.label + "</span></a>")
                        .appendTo(ul);
                } else {
                    return $("<li>")
                        .data("item.autocomplete", item)
                        .append("<a href='rechercheAvancee.php'><img src='" + item.icon + "' width='30px', height='30px'><span style='margin-left:30px'>" + item.label + "</span></a>")
                        .appendTo(ul);
                }

            };
    });
</script>

<header class>
    <div class="leftSide">
        <?php
        echo "<a href='index.php'><img class='leftSide' src='img/logo.png' width='70' height='70'/></a>";
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