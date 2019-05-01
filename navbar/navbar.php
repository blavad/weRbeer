<link rel="stylesheet" type="text/css" href="style.css" media="all" />
<link rel="stylesheet" type="text/css" href="navbar/stylenav.css" media="all" />
<nav>
	<ul>
		<li> <a href="index.php">Actualités</a> </li>
		<li> <a href="profil.php?id=<?php echo $_SESSION['util']->getId();?> ">Mon profil</a> </li>
		<li> <a href="cave.php">Ma cave</a> </li>
		<li> <a href="listeamis.php">Mes amis</a> </li>
		<li> <a href="param.php">Paramètres</a> </li>
	</ul>
</nav>
