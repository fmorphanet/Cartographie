
<?php
/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
/** PHPExcel_IOFactory */
include 'PHPExcel/IOFactory.php';

/** Xml parser Centre Expert**/
$myXMLDataExp = "resultExpertCenter06-08-18.xml";												//mettre à jour avec extraction exor
$xmlExp=simplexml_load_file($myXMLDataExp) or die("Error: Cannot create object");
/** Xml parser Laboratoire**/
// $myXMLDataLabo = "resultLaboratoireRegion.xml";												//mettre à jour avec extraction exor
// $xmlLabo=simplexml_load_file($myXMLDataLabo) or die("Error: Cannot create object");
/** Xml parser **/
// $myXMLDataAsso = "resultAssociationRegion.xml";												//mettre à jour avec extraction exor
// $xmlAsso=simplexml_load_file($myXMLDataAsso) or die("Error: Cannot create object");

/** Excel parser **/
$fileName = 'Filières_02_08_18_prio.xlsx';														//mettre à jour avec le fichier filière
// $fileName = 'Filières_24_10_17_prio.xlsx';														//mettre à jour avec le fichier filière
$excelReader = PHPExcel_IOFactory::createReaderForFile($fileName);
$loadSheets = array('Feuil1');  // Name of the sheet
$excelReader->setLoadSheetsOnly($loadSheets);
$excelObj = $excelReader->load($fileName);
$sheetData = $excelObj->getActiveSheet()->toArray(null,true,true,true);
$errorData = "";
$Filiere=[];
$Combo=array();
foreach ($sheetData as $i){
//Liste Centre Expert
	if ($i['F']=="Exp"){
		$ExpData[]=$i;
	}
//Liste Laboratoire
	// if ($i['F']=="Labo"){
		// $LaboData[]=$i;
	// }
//Liste Association
	// if ($i['F']=="Asso"){
		// $AssoData[]=$i;
	// }
}

if (isset($ExpData)){
	$ExpIdFiliere=array_column($ExpData,'A');
	$ExpFiliere=array_column($ExpData,'B');
	$ExpInstiID=array_column($ExpData,'C');
	$ExpsousType=array_column($ExpData,'E');
	$Exptype=array_column($ExpData,'F');
	$Priorite=array_column($ExpData,'G');
	// if (array_search("21652",$ExpInstiID)){echo "\n\nIl est encore là\n\n";}
	foreach ($xmlExp->ExpertCentreList->ExpertCentre as $ExpertCentre){
		$indexarray=array_keys($ExpInstiID, $ExpertCentre->attributes()->id);
		// $index=array_search($ExpertCentre->attributes()->id, $ExpInstiID);
		foreach ($indexarray as $index){
			$expertLink = cleanExpertLink($ExpertCentre->ExpertLink);
			$hostingInstitutionName = $ExpertCentre->InstitutionList->Institution->Address->Hosting_Institution;
			$coordinatorName = sansVirg($ExpertCentre->PersonList->Person->Firstname).' '.sansVirg($ExpertCentre->PersonList->Person->Lastname);
			if ($coordinatorName==" "){
				$errorData=$errorData."\n".sansVirg($ExpInstiID[$index]);
			}
			$name = (string)$ExpertCentre->Name;
			$address = $ExpertCentre->InstitutionList->Institution->Address->Country->Name.' '.$ExpertCentre->InstitutionList->Institution->Address->PostalCode.' '.$ExpertCentre->InstitutionList->Institution->Address->Town->Name.' '.$ExpertCentre->InstitutionList->Institution->Address->StreetNum;
			$region = $ExpertCentre->InstitutionList->Institution->Address->GeoLocation->Region->Name;
			// if (isset($Filiere[$ExpFiliere[$index]])){
				// $Filiere[$ExpFiliere[$index]]=$Filiere[$ExpFiliere[$index]].",\n"."[\"".sansVirg($name)."\",\"".CleanAddress(sansVirg($address))."\",\"".sansVirg($Exptype[$index])."\",\"".sansVirg($Priorite[$index])."\",\"".sansVirg($ExpFiliere[$index])."\",\"".sansVirg($ExpInstiID[$index])."\",\"".sansVirg($region)."\",\"".$expertLink."\",\"".sansVirg($hostingInstitutionName)."\",\"".sansVirg($coordinatorName)."\"]";
			// }else{
				// $Filiere[$ExpFiliere[$index]]= "[[\"".sansVirg($name)."\",\"".CleanAddress(sansVirg($address))."\",\"".sansVirg($Exptype[$index])."\",\"".sansVirg($Priorite[$index])."\",\"".sansVirg($ExpFiliere[$index])."\",\"".sansVirg($ExpInstiID[$index])."\",\"".sansVirg($region)."\",\"".$expertLink."\",\"".sansVirg($hostingInstitutionName)."\",\"".sansVirg($coordinatorName)."\"]";
			// }
			if (isset($Filiere['AllFiliere'])){
				$Filiere['AllFiliere']=$Filiere['AllFiliere'].",\n"."[\"".sansVirg($name)."\",\"".CleanAddress(sansVirg($address))."\",\"".sansVirg($Exptype[$index])."\",\"".sansVirg($Priorite[$index])."\",\"".sansVirg($ExpFiliere[$index])."\",\"".sansVirg($ExpInstiID[$index])."\",\"".sansVirg($region)."\",\"".$expertLink."\",\"".sansVirg($hostingInstitutionName)."\",\"".sansVirg($coordinatorName)."\"]";
			}else{
				$Filiere['AllFiliere']= "[[\"".sansVirg($name)."\",\"".CleanAddress(sansVirg($address))."\",\"".sansVirg($Exptype[$index])."\",\"".sansVirg($Priorite[$index])."\",\"".sansVirg($ExpFiliere[$index])."\",\"".sansVirg($ExpInstiID[$index])."\",\"".sansVirg($region)."\",\"".$expertLink."\",\"".sansVirg($hostingInstitutionName)."\",\"".sansVirg($coordinatorName)."\"]";
			}
			if (isset($Combo[$ExpFiliere[$index]])){
				
			}else{
				$Combo[$ExpFiliere[$index]]=sansVirg($ExpIdFiliere[$index])."|".sansVirg($ExpFiliere[$index]);
			}
		}
	}
	ksort($Combo);
}

foreach ($Combo as $key=>$value){
	if (isset($Filiere['ComboFiliere'])){
		$Filiere['ComboFiliere']=$Filiere['ComboFiliere'].",\n".$value;
	} else {
		$Filiere['ComboFiliere']="[".$value;
	}
}



// if (isset($AssoData)){
	// $AssoFiliere=array_column($AssoData,'B');
	// $AssoInstiID=array_column($AssoData,'C');
	// $AssosousType=array_column($AssoData,'E');
	// $Assotype=array_column($AssoData,'F');
	
	// foreach ($xmlAsso->PatientOrganisationList->PatientOrganisation as $PatientOrganisation){
		// $indexarray=array_keys($AssoInstiID, $PatientOrganisation->attributes()->id);
		// foreach ($indexarray as $index){
			// $expertLink = cleanExpertLink($PatientOrganisation->ExpertLink);
			// $hostingInstitutionName = $PatientOrganisation->InstitutionList->Institution->Address->Hosting_Institution;
			// $coordinatorName = sansVirg($PatientOrganisation->PersonList->Person->Firstname).' '.sansVirg($PatientOrganisation->PersonList->Person->Lastname);
			// if ($coordinatorName==" "){
				// $errorData=$errorData."\n".sansVirg($AssoInstiID[$index]);
			// }
			// $name = $PatientOrganisation->Name;
			// $address = $PatientOrganisation->InstitutionList->Institution->Address->StreetNum.' '.$PatientOrganisation->InstitutionList->Institution->Address->Town->Name.' '.$PatientOrganisation->InstitutionList->Institution->Address->PostalCode.' '.$PatientOrganisation->InstitutionList->Institution->Address->Country->Name;
			// $region = $PatientOrganisation->InstitutionList->Institution->Address->GeoLocation->Region->Name;
			// if (isset($Filiere[$AssoFiliere[$index]])){
				// $Filiere[$AssoFiliere[$index]]=$Filiere[$AssoFiliere[$index]].",\n"."['".sansVirg($name)."','".CleanAddress(sansVirg($address))."','".sansVirg($Assotype[$index])."','".sansVirg($AssosousType[$index])."','".sansVirg($AssoFiliere[$index])."','".sansVirg($AssoInstiID[$index])."','".sansVirg($region)."','".$expertLink."','".sansVirg($hostingInstitutionName)."','".sansVirg($coordinatorName)."']";
			// }else{
				// $Filiere[$AssoFiliere[$index]]= "[['".sansVirg($name)."','".CleanAddress(sansVirg($address))."','".sansVirg($Assotype[$index])."','".sansVirg($AssosousType[$index])."','".sansVirg($AssoFiliere[$index])."','".sansVirg($AssoInstiID[$index])."','".sansVirg($region)."','".$expertLink."','".sansVirg($hostingInstitutionName)."','".sansVirg($coordinatorName)."']";
			// }
			// if (isset($Filiere['AllFiliere'])){
				// $Filiere['AllFiliere']=$Filiere['AllFiliere'].",\n"."['".sansVirg($name)."','".CleanAddress(sansVirg($address))."','".sansVirg($Assotype[$index])."','".sansVirg($AssosousType[$index])."','".sansVirg($AssoFiliere[$index])."','".sansVirg($AssoInstiID[$index])."','".sansVirg($region)."','".$expertLink."','".sansVirg($hostingInstitutionName)."','".sansVirg($coordinatorName)."']";
				
				// #################print $Filiere['AllFiliere'];
				
			// }else{
				// $Filiere['AllFiliere']= "[['".sansVirg($name)."','".CleanAddress(sansVirg($address))."','".sansVirg($Assotype[$index])."','".sansVirg($AssosousType[$index])."','".sansVirg($AssoFiliere[$index])."','".sansVirg($AssoInstiID[$index])."','".sansVirg($region)."','".$expertLink."','".sansVirg($hostingInstitutionName)."','".sansVirg($coordinatorName)."']";
			// }
		// }
	// }
// }

// if (isset($LaboData)){
	// $LaboFiliere=array_column($LaboData,'B');
	// $LaboInstiID=array_column($LaboData,'C');
	// $LabosousType=array_column($LaboData,'E');
	// $Labotype=array_column($LaboData,'F');
	
	// foreach ($xmlLabo->InstitutionList->Institution as $Institution){
		// $indexarray=array_keys($LaboInstiID, $Institution->attributes()->id);
		// foreach ($indexarray as $index){
			// $expertLink = cleanExpertLink($Institution->ExpertLink);
			// $hostingInstitutionName = Institution->Address->Hosting_Institution;
			// $hostingInstitutionName = "";
			// $coordinatorName = sansVirg($Institution->PersonList->Person->Firstname).' '.sansVirg($Institution->PersonList->Person->Lastname);
			// if ($coordinatorName==" "){
				// $errorData=$errorData."\n".sansVirg($LaboInstiID[$index]);
			// }
			// $name = $Institution->Department_Service_Lab_PatientOrganisationName;
			// $address = $Institution->Address->StreetNum.' '.$Institution->Address->Town->Name.' '.$Institution->Address->PostalCode.' '.$Institution->Address->Country->Name;
			// $region = $Institution->Address->GeoLocation->Region->Name;
			// if (isset($Filiere[$LaboFiliere[$index]])){
				// $Filiere[$LaboFiliere[$index]]=$Filiere[$LaboFiliere[$index]].",\n"."['".sansVirg($name)."','".CleanAddress(sansVirg($address))."','".sansVirg($Labotype[$index])."','".sansVirg($LabosousType[$index])."','".sansVirg($LaboFiliere[$index])."','".sansVirg($LaboInstiID[$index])."','".sansVirg($region)."','".$expertLink."','".sansVirg($hostingInstitutionName)."','".sansVirg($coordinatorName)."']";
			// }else{
				// $Filiere[$LaboFiliere[$index]]= "[['".sansVirg($name)."','".CleanAddress(sansVirg($address))."','".sansVirg($Labotype[$index])."','".sansVirg($LabosousType[$index])."','".sansVirg($LaboFiliere[$index])."','".sansVirg($LaboInstiID[$index])."','".sansVirg($region)."','".$expertLink."','".sansVirg($hostingInstitutionName)."','".sansVirg($coordinatorName)."']";
			// }
			// if (isset($Filiere['AllFiliere'])){
				// $Filiere['AllFiliere']=$Filiere['AllFiliere'].",\n"."['".sansVirg($name)."','".CleanAddress(sansVirg($address))."','".sansVirg($Labotype[$index])."','".sansVirg($LabosousType[$index])."','".sansVirg($LaboFiliere[$index])."','".sansVirg($LaboInstiID[$index])."','".sansVirg($region)."','".$expertLink."','".sansVirg($hostingInstitutionName)."','".sansVirg($coordinatorName)."']";
			// }else{
				// $Filiere['AllFiliere']= "[['".sansVirg($name)."','".CleanAddress(sansVirg($address))."','".sansVirg($Labotype[$index])."','".sansVirg($LabosousType[$index])."','".sansVirg($LaboFiliere[$index])."','".sansVirg($LaboInstiID[$index])."','".sansVirg($region)."','".$expertLink."','".sansVirg($hostingInstitutionName)."','".sansVirg($coordinatorName)."']";
			// }
		// }
	// }
// }

foreach ($Filiere as $key => $value){
	$newname=preg_replace("/[\s-]/","_",$key);
	$path1="NewLogRegion/" . $newname  .".txt";
	// $ansivalue = iconv("UTF-8","WINDOWS-1252",$value);
	// file_put_contents($path1,$ansivalue."]\n");
	file_put_contents($path1,$value."]\n");
}

file_put_contents("noHeadExpertCentre.txt",$errorData);

function sansVirg($val){
	$retval=preg_replace("/[,\[\]\(\)\"]/"," ",$val);
	$retval1=preg_replace("/\s{2,}/"," ",$retval);
	return $retval1;
}

function cleanExpertLink($expertLink){
	$retval=preg_replace("/http:/","https:",$expertLink);
	$retval1=preg_replace("/amp;/","",$retval);
	$retval2=preg_replace("/lng=en/","lng=fr",$retval1);
	return $retval2;
}

function CleanAddress($val1){
	// if (preg_match("/SAINT-PIERRE/",$val1)){
		// print "\navant ".$val1;
	// }
	$newval1=preg_replace("/\s+cs\s*[0-9]+/i"," ",$val1);
	$newval2=preg_replace("/cs\s*[0-9]{1,4}\s+/i","",$newval1);
	$newval3=preg_replace("/\s+bp\s*[0-9]+/i"," ",$newval2);
	$newval4=preg_replace("/bp\s*[0-9]{1,4}\s+/i","",$newval3);
	$newval5=preg_replace("/\s+tsa\s*[0-9]+/i"," ",$newval4);
	$newval6=preg_replace("/tsa\s*[0-9]{1,4}\s+/i","",$newval5);
	$newval7=preg_replace("/quartier La Meynard/","",$newval6);			#curation en dur
	$newval8=preg_replace("/Polonovski/","Polonowski",$newval7);		#curation en dur
	$newval9=preg_replace("/co\s*n°34/","",$newval8);		#curation en dur
	$newval10=preg_replace("/\s{2,}/i"," ",$newval9);
	$newval11=preg_replace("/^\s*/i","",$newval10);
	$newval12=preg_replace("/\s*$/i","",$newval11);
	$newval13=preg_replace("/Pr\./i","Professeur",$newval12);
	// if (preg_match("/SAINT PIERRE/",$val1)){
		// print "\napres: ".$newval12;
	// }
	return $newval13;
}
?>

