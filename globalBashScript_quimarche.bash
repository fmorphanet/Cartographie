#!/bin/sh
php scriptCarto.php
chmod a+x ./geocodeScript.sh
declare -A monHash                                  ##gestion des hash dans bash 
declare -A maBaseAdr                                  ##gestion des hash dans bash 
for fichier in ./AdrGeo/*.txt; do
	if [[ $fichier =~ ^[./]*[a-z]*[/](Combo.*) ]]; then ##Récupération des adresses et géocode du fichier de stockage
		while read line ; do
			if [[ $line =~ ([^,]+),(.+) ]]; then
				adrencourt="${BASH_REMATCH[1]}"
				loc="${BASH_REMATCH[2]}"
				if [ "${maBaseAdr[$adrencourt]}" ]; then 
					loc="${maBaseAdr[$adrencourt]}"
				else
					maBaseAdr+=(["$adrencourt"]="$loc") ##Création du Hash contenant les adresses et géocode du fichier de stockage
				fi
			fi
		done < $fichier
	fi
done
echo "" > err.log
for file in ./NewLogRegion/*.txt; do
	echo $file
	if [[ $file =~ ^[./]*[a-z]*[/](Combo.*) ]]; then
		echo ${BASH_REMATCH[1]}
	else
		while read ligne ; do
			if [[ $ligne =~ \[\[?\"[^\",]+\",\"([^\",]*)\", ]]; then
				loc=""
				adrencourt="${BASH_REMATCH[1]}"
				if [ "${maBaseAdr[$adrencourt]}" ]; then    ##gestion des hash dans bash
					loc="${maBaseAdr[$adrencourt]}"
				else
					if [ "${monHash[$adrencourt]}" ]; then    ##gestion des hash dans bash 
						loc="${monHash[$adrencourt]}"
					else
						sh ./geocodeScript.sh $adrencourt		##lance les geoloc si l'adresse n'a pas encore de geoloc recherché
						loc=$(<loc.txt)
						rm "loc.txt"
						monHash+=(["$adrencourt"]="$loc")    ##gestion des hash dans bash 
						echo $loc
						if [ $loc!='error' ]; then
							addtoAdrGeo="$adrencourt$loc"
							echo $addtoAdrGeo >> "./AdrGeo/ComboAdrGeocode.txt" ##Complète les adresses&géocodes du fichier de stockage si pas error
						fi
					fi
				fi
				echo $ligne |sed -e "s/\]/,$loc\]/i" >> "temp.txt"
			else
				echo "Bug ligne $ligne $file" >> "temp.txt"
			fi
		done < $file
		cat "temp.txt" > $file
		rm "temp.txt"
	fi
done

#uniquement pour encodage en utf8
#php maconv.php

##Script de création des Log nécessaire pour la création de la cartographie filiere.
##Les log se trouvent dans le dossier NewLogRegion
