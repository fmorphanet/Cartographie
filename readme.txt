#####   Cartographie Filière   #####

EQUIPE_INFO/Cartographie/scriptCarto.php : sert à créer le fichier de Log nécessaire au fonctionnement de la cartographie des filières.

	ARGS   : NONE
	entrée : un fichier excel mise en forme provenant des ressources expertes (équipe Orphanet) & une requete exor spécifique
	Le document technique détaille avec précision les besoins de ces 2 données d'entrée (EQUIPE_INFO\Consor_UTF8\Docs\doc scripts)
	sortie : Fichiers AllFiliere.txt (incomplet), ComboRegion.txt et ComboFiliere.txt

EQUIPE_INFO/Cartographie/globalBashScript_quimarche.bash : permet de compléter les données du fichier AllFiliere.txt en associant les géocodes correspondant aux adresses des centres.

	ARGS   : NONE
	entrée : Fichier AllFiliere.txt (incomplet)
	sortie : Fichier AllFiliere.txt (complet)
