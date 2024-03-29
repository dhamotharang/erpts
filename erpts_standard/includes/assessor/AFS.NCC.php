<?php
//include files

include_once("web/prepend.php");

include_once("assessor/OD.php");
include_once("assessor/Person.php");
include_once("assessor/Land.php");
include_once("assessor/LandRecords.php");
include_once("assessor/PlantsTrees.php");
include_once("assessor/PlantsTreesRecords.php");
include_once("assessor/ImprovementsBuildings.php");
include_once("assessor/ImprovementsBuildingsRecords.php");
include_once("assessor/Machineries.php");
include_once("assessor/MachineriesRecords.php");

class AFS
{
	//attributes
	var $afsID;
	var $odID;
	var $arpNumber;
	var $propertyIndexNumber;
	var $taxability;
	var $effectivity;
	var $cadastralLotNumber;
	var $gisTechnicalDescription;
	var $landTotalMarketValue;
	var $landTotalAssessedValue;
	var $plantTotalMarketValue;
	var $plantTotalAssessedValue;
	var $bldgTotalMarketValue;
	var $bldgTotalAssessedValue;
	var $machTotalMarketValue;
	var $machTotalAssessedValue;
	var $totalMarketValue;
	var $totalAssessedValue;
	var $landArray;
	var $plantsTreesArray;
	var $improvementsBuildingsArray;
	var $machineriesArray;
	var $dateCreated;
	var $createdBy;
	var $dateModified;
	var $modifiedBy;
	var $valueAdjustment;
	var $archive;

	var $domDocument;
	var $db;
	
	//constructor
	function AFS(){
	
	}
	function setDB(){
		$this->db = new DB_RPTS;
	}
	//methods
	//set
	function setAfsID($tempVar) {
		$this->afsID = $tempVar;
	}
	function setOdID($tempVar) {
		$this->odID = $tempVar;
	}
	function setArpNumber($tempVar){
		$this->arpNumber = $tempVar;
	}
	function setPropertyIndexNumber($tempVar){
		$this->propertyIndexNumber = $tempVar;
	}
	function setTaxability($tempVar){
		$this->taxability = $tempVar;
	}
	function setEffectivity($tempVar){
		$this->effectivity = $tempVar;
	}
	function setCadastralLotNumber($tempVar){
		$this->cadastralLotNumber = $tempVar;
	}
	function setGISTechnicalDescription($tempVar){
		$this->gisTechnicalDescription = $tempVar;
	}
	function setLandTotalMarketValue ($tempVar) {
		$this->landTotalMarketValue = $tempVar;
	}
	function setLandTotalAssessedValue ($tempVar) {
		$this->landTotalAssessedValue = $tempVar;
	}
	function setPlantTotalMarketValue ($tempVar) {
		$this->plantTotalMarketValue = $tempVar;
	}
	function setPlantTotalPlantAssessedValue ($tempVar) {
		$this->plantTotalAssessedValue = $tempVar;
	}
	function setBldgTotalMarketValue ($tempVar) {
		$this->bldgTotalMarketValue = $tempVar;
	}
	function setBldgTotalAssessedValue ($tempVar) {
		$this->bldgTotalAssessedValue = $tempVar;
	}
	function setMachTotalMarketValue ($tempVar) {
		$this->machTotalMarketValue = $tempVar;
	}
	function setValueAdjustment ($tempVar) {
		$this->valueAdjustment = $tempVar;
	}
	function setMachTotalAssessedValue ($tempVar) {
		$this->machTotalAssessedValue = $tempVar;
	}
	function setTotalMarketValue ($tempVar) {
		$this->totalMarketValue = $tempVar;
	}
	function setTotalAssessedValue ($tempVar) {
		$this->totalAssessedValue = $tempVar;
	}
	function setLandArray ($tempVar) {
		$this->landArray[] = $tempVar;
	}
	function setPlantsTreesArray ($tempVar) {
		$this->plantsTreesArray[] = $tempVar;
	}
	function setImprovementsBuildingsArray ($tempVar) {
		$this->improvementsBuildingsArray[] = $tempVar;
	}	
	function setMachineriesArray ($tempVar) {
		$this->machineriesArray[] = $tempVar;
	}
	function setDateCreated($tempVar){
		$this->dateCreated = $tempVar;
	}
	function setCreatedBy($tempVar){
		$this->createdBy = $tempVar;
	}
	function setDateModified($tempVar){
		$this->dateModified = $tempVar;
	}
	function setModifiedBy($tempVar){
		$this->modifiedBy = $tempVar;
	}
	function setArchive($tempVar){
		$this->archive = $tempVar;
	}
	
	function setDocNode($elementName,$elementValue,$domDoc,$indexNode){
		$nodeName = "";
		$nodeText = "";
		$nodeName = $domDoc->create_element($elementName);
		$nodeName = $indexNode->append_child($nodeName);

		$trans = get_html_translation_table(HTML_ENTITIES);
		$elementValue = strtr(htmlentities($elementValue), $trans);
		$nodeText = $domDoc->create_text_node($elementValue);

		//$nodeText = $domDoc->create_text_node(htmlentities($elementValue));

		$nodeText = $nodeName->append_child($nodeText);
	}
	
	//sub function of setDomDocument
	function setArrayDocNode ($elementName,$arrayList,$indexNode){
		$list = $this->domDocument->create_element($elementName);
		$list = $indexNode->append_child($list);
		if (is_array($arrayList)){
			foreach ($arrayList as $key => $value){
                $domTmp = $value->getDomDocument();
				//$list->append_child($domTmp->document_element());

				// test clone_node()
				$nodeTmp = $domTmp->document_element();
				$nodeClone = $nodeTmp->clone_node(true);
				$list->append_child($nodeClone);
			}
		}
	}
	function setDomDocument(){
		$this->domDocument = domxml_new_doc("1.0");
		$rec = $this->domDocument->create_element("AFS");
		$rec = $this->domDocument->append_child($rec);
		$this->setDocNode("afsID",$this->afsID,$this->domDocument,$rec);
		$this->setDocNode("odID",$this->odID,$this->domDocument,$rec);
		$this->setDocNode("arpNumber",$this->arpNumber,$this->domDocument,$rec);
		$this->setDocNode("propertyIndexNumber",$this->propertyIndexNumber,$this->domDocument,$rec);
		$this->setDocNode("taxability",$this->taxability,$this->domDocument,$rec);
		$this->setDocNode("effectivity",$this->effectivity,$this->domDocument,$rec);

		$this->setDocNode("cadastralLotNumber",$this->cadastralLotNumber,$this->domDocument,$rec);
		$this->setDocNode("gisTechnicalDescription",$this->gisTechnicalDescription,$this->domDocument,$rec);
		$this->setDocNode("landTotalMarketValue",$this->landTotalMarketValue,$this->domDocument,$rec);
		$this->setDocNode("landTotalAssessedValue",$this->landTotalAssessedValue,$this->domDocument,$rec);
		$this->setDocNode("plantTotalMarketValue",$this->plantTotalMarketValue,$this->domDocument,$rec);
		$this->setDocNode("plantTotalAssessedValue",$this->plantTotalAssessedValue,$this->domDocument,$rec);
		$this->setDocNode("bldgTotalMarketValue",$this->bldgTotalMarketValue,$this->domDocument,$rec);
		$this->setDocNode("bldgTotalAssessedValue",$this->bldgTotalAssessedValue,$this->domDocument,$rec);
		$this->setDocNode("machTotalMarketValue",$this->machTotalMarketValue,$this->domDocument,$rec);
		$this->setDocNode("machTotalAssessedValue",$this->machTotalAssessedValue,$this->domDocument,$rec);
		$this->setDocNode("totalMarketValue",$this->totalMarketValue,$this->domDocument,$rec);
		$this->setDocNode("totalAssessedValue",$this->totalAssessedValue,$this->domDocument,$rec);
		$this->setDocNode("dateCreated",$this->dateCreated,$this->domDocument,$rec);
		$this->setDocNode("createdBy",$this->createdBy,$this->domDocument,$rec);
		$this->setDocNode("dateModified",$this->dateModified,$this->domDocument,$rec);
		$this->setDocNode("modifiedBy",$this->modifiedBy,$this->domDocument,$rec);
		$this->setDocNode("archive",$this->archive,$this->domDocument,$rec);
		if (count($this->landArray))
			$this->setArrayDocNode("landArray",$this->landArray,$rec);
		if (count($this->plantsTreesArray))
			$this->setArrayDocNode("plantsTreesArray",$this->plantsTreesArray,$rec);
		if (count($this->improvementsBuildingsArray))
			$this->setArrayDocNode("improvementsBuildingsArray",$this->improvementsBuildingsArray,$rec);
		if (count($this->machineriesArray))
			$this->setArrayDocNode("machineriesArray",$this->machineriesArray,$rec);
	}
	function parseDomDocument($domDoc){
		$baseNode = $domDoc->document_element();
		if ($baseNode->has_child_nodes()){
			$child = $baseNode->first_child();
			while ($child){
				switch($child->tagname){
					case "landArray":
						$landNode = $child->first_child();
						while ($landNode){
							//if ($landNode->tagname=="Land") {
							if ($landNode->tagname) {
								$tempXmlStr = $domDoc->dump_node($landNode);
								$tempDomDoc = domxml_open_mem($tempXmlStr);
								$land = new Land;
								$land->parseDomDocument($tempDomDoc);
								$this->landArray[] = $land;		
							}
							$landNode = $landNode->next_sibling();
						}
						break;						
					case "plantsTreesArray":
						$plantsTreesNode = $child->first_child();
						while ($plantsTreesNode){
							//if ($plantsTreesNode->tagname=="PlantsTrees") {
							if ($plantsTreesNode->tagname) {
								$tempXmlStr = $domDoc->dump_node($plantsTreesNode);
								$tempDomDoc = domxml_open_mem($tempXmlStr);
								$plantsTrees = new PlantsTrees;
								$plantsTrees->parseDomDocument($tempDomDoc);
								$this->plantsTreesArray[] = $plantsTrees;
							}
							$plantsTreesNode = $plantsTreesNode->next_sibling();
						}
						break;						
					case "improvementsBuildingsArray":
						$improvementsBuildingsNode = $child->first_child();
						while ($improvementsBuildingsNode){
							//if ($improvementsBuildingsNode->tagname=="ImprovementsBuildings") {
							if ($improvementsBuildingsNode->tagname) {
								$tempXmlStr = $domDoc->dump_node($improvementsBuildingsNode);
								$tempDomDoc = domxml_open_mem($tempXmlStr);
								$improvementsBuildings = new ImprovementsBuildings;
								$improvementsBuildings->parseDomDocument($tempDomDoc);
								$this->improvementsBuildingsArray[] = $improvementsBuildings;
							}
							$improvementsBuildingsNode = $improvementsBuildingsNode->next_sibling();
						}
						break;						
					case "machineriesArray":
						$machineriesNode = $child->first_child();
						while ($machineriesNode){
							//if ($machineriesNode->tagname=="Machineries") {
							if ($machineriesNode->tagname) {
								$tempXmlStr = $domDoc->dump_node($machineriesNode);
								$tempDomDoc = domxml_open_mem($tempXmlStr);
								$machineries = new Machineries;
								$machineries->parseDomDocument($tempDomDoc);
								$this->machineriesArray[] = $machineries;
							}
							$machineriesNode = $machineriesNode->next_sibling();
						}
						break;										
					default:
						//eval("\$this->".$child->tagname."= \"".$child->get_content()."\";");

						// test varvars
						$varvar = $child->tagname;

						$trans = array_flip(get_html_translation_table(HTML_ENTITIES));
						$childContent = strtr(html_entity_decode($child->get_content()), $trans);

						$this->$varvar = html_entity_decode($childContent);

						//$this->$varvar = html_entity_decode($child->get_content());
				}
				$child = $child->next_sibling();
			}
		}
		$this->setDomDocument();
	}
	function getDomDocument(){
		return $this->domDocument;
	}
	//get
	function getAfsID() {
		return $this->afsID;
	}
	function getOdID() {
		return $this->odID;
	}
	function getArpNumber(){
		return $this->arpNumber;
	}
	function getValueAdjustment(){
		return $this->valueAdjustment;
	}
	function getPropertyIndexNumber(){
		return $this->propertyIndexNumber;
	}
	function getTaxability(){
		return $this->taxability;
	}
	function getEffectivity(){
		return $this->effectivity;
	}
	function getCadastralLotNumber(){
		return $this->cadastralLotNumber;
	}
	function getGISTechnicalDescription(){
		return $this->gisTechnicalDescription;
	}
	function getLandTotalMarketValue () {
		return $this->landTotalMarketValue;
	}
	function getLandTotalAssessedValue () {
		return $this->landTotalAssessedValue;
	}
	function getPlantTotalMarketValue () {
		return $this->plantTotalMarketValue;
	}
	function getPlantTotalAssessedValue () {
		return $this->plantTotalAssessedValue;
	}
	function getBldgTotalMarketValue () {
		return $this->bldgTotalMarketValue;
	}
	function getBldgTotalAssessedValue () {
		return $this->bldgTotalAssessedValue;
	}
	function getMachTotalMarketValue () {
		return $this->machTotalMarketValue;
	}
	function getMachTotalAssessedValue () {
		return $this->machTotalAssessedValue;
	}
	function getTotalMarketValue () {
		return $this->totalMarketValue;
	}
	function getTotalAssessedValue () {
		return $this->totalAssessedValue;
	}
	function getDateCreated(){
		return $this->dateCreated;
	}
	function getCreatedBy(){
		return $this->createdBy;
	}
	function getDateModified(){
		return $this->dateModified;
	}
	function getModifiedBy(){
		return $this->modifiedBy;
	}
	function getArchive(){
		return $this->archive;
	}
	function getLandArray () {
		//$ret = (!$this->landArray) ? $this->landArray : false;
		return $this->landArray;
	}
	function getPlantsTreesArray () {
		//$ret = (!$this->plantsTreesArray) ? $this->plantsTreesArray : false;
		return $this->plantsTreesArray;
	}
	function getImprovementsBuildingsArray () {
		//$ret = (!$this->improvementsBuildingsArray) ? $this->improvementsBuildingsArray : false;
		return $this->improvementsBuildingsArray;
	}
	function getMachineriesArray () {
		//$ret = (!$this->machineriesArray) ? $this->machineriesArray : false;
		return $this->machineriesArray;
	}

	
	function selectRecord($afsID,$limit="",$odID="",$condition=""){

		if ($afsID==""){
			if($odID!=""){
				$condition = sprintf("WHERE odID='%s'",fixQuotes($odID));
			}
			else{
				if($condition==""){
					return;
				}
			}
		}
		else{
			$condition = sprintf("WHERE afsID='%s'",fixQuotes($afsID));
		}


		$this->setDB();
		$sql = sprintf("SELECT * FROM %s %s %s;",
			AFS_TABLE, $condition, $limit);

		$this->db->query($sql);
		
		if ($this->db->next_record()) {
			$this->afsID = $this->db->f("afsID");
			$this->odID = $this->db->f("odID");
			$this->arpNumber = $this->db->f("arpNumber");
			$this->valueAdjustment = $this->db->f("valueAdjustment");
			$this->propertyIndexNumber = $this->db->f("propertyIndexNumber");
			$this->taxability = $this->db->f("taxability");
			$this->effectivity = $this->db->f("effectivity");
			$this->cadastralLotNumber = $this->db->f("cadastralLotNumber");
			$this->gisTechnicalDescription = $this->db->f("gisTechnicalDescription");
			$this->landTotalMarketValue = $this->db->f("landTotalMarketValue");
			$this->landTotalAssessedValue = $this->db->f("landTotalAssessedValue");
			$this->plantTotalMarketValue = $this->db->f("plantTotalMarketValue");
			$this->plantTotalAssessedValue = $this->db->f("plantTotalAssessedValue");
			$this->bldgTotalMarketValue = $this->db->f("bldgTotalMarketValue");
			$this->bldgTotalAssessedValue = $this->db->f("bldgTotalAssessedValue");
			$this->machTotalMarketValue = $this->db->f("machTotalMarketValue");
			$this->machTotalAssessedValue = $this->db->f("machTotalAssessedValue");
			$this->totalMarketValue = $this->db->f("totalMarketValue");
			$this->totalAssessedValue = $this->db->f("totalAssessedValue");
			$this->dateCreated = $this->db->f("dateCreated");
			$this->createdBy = $this->db->f("createdBy");
			$this->dateModified = $this->db->f("dateModified");
			$this->modifiedBy = $this->db->f("modifiedBy");
			$this->archive = $this->db->f("archive");
			
			$landRecords = new LandRecords;
			$landRecords->selectRecords($this->afsID);
			$this->landArray = $landRecords->getArrayList();

			$improvementsBuildingsRecords = new ImprovementsBuildingsRecords;
			$improvementsBuildingsRecords->selectRecords($this->afsID);
			$this->improvementsBuildingsArray = $improvementsBuildingsRecords->getArrayList();

			$plantsTreesRecords = new PlantsTreesRecords;
			$plantsTreesRecords->selectRecords($this->afsID);
			$this->plantsTreesArray = $plantsTreesRecords->getArrayList();

			$machineriesRecords = new MachineriesRecords;
			$machineriesRecords->selectRecords($this->afsID);
			$this->machineriesArray = $machineriesRecords->getArrayList();		
			
			$this->setDomDocument();
			$ret = true;
		}
		else $ret = false;
		return $ret;
	}

    function selectRecordForList($afsID,$odID="",$totalAssessedValue="",$limit=""){
		if ($afsID=="") return;

		$this->setDB();
		$sql = sprintf("SELECT * FROM %s WHERE afsID=%s %s;",
			AFS_TABLE, $afsID, $limit);
		$this->db->query($sql);
		
		if ($this->db->next_record()) {
			$this->afsID = $this->db->f("afsID");
			$this->odID = $this->db->f("odID");
			$this->arpNumber = $this->db->f("arpNumber");
			$this->propertyIndexNumber = $this->db->f("propertyIndexNumber");
			$this->taxability = $this->db->f("taxability");
			$this->effectivity = $this->db->f("effectivity");
			$this->cadastralLotNumber = $this->db->f("cadastralLotNumber");
			$this->gisTechnicalDescription = $this->db->f("gisTechnicalDescription");
			$this->landTotalMarketValue = $this->db->f("landTotalMarketValue");
			$this->landTotalAssessedValue = $this->db->f("landTotalAssessedValue");
			$this->plantTotalMarketValue = $this->db->f("plantTotalMarketValue");
			$this->plantTotalAssessedValue = $this->db->f("plantTotalAssessedValue");
			$this->bldgTotalMarketValue = $this->db->f("bldgTotalMarketValue");
			$this->bldgTotalAssessedValue = $this->db->f("bldgTotalAssessedValue");
			$this->machTotalMarketValue = $this->db->f("machTotalMarketValue");
			$this->machTotalAssessedValue = $this->db->f("machTotalAssessedValue");
			$this->totalMarketValue = $this->db->f("totalMarketValue");
			$this->totalAssessedValue = $this->db->f("totalAssessedValue");
			$this->dateCreated = $this->db->f("dateCreated");
			$this->createdBy = $this->db->f("createdBy");
			$this->dateModified = $this->db->f("dateModified");
			$this->modifiedBy = $this->db->f("modifiedBy");
			$this->archive = $this->db->f("archive");
			$this->setDomDocument();
			$ret = true;
		}
		else $ret = false;
		return $ret;
	}
	
	function checkAfsID($odID){
		$sql = sprintf("SELECT * FROM %s WHERE odID=%s;",
			AFS_TABLE, $odID);
		$this->setDB();
		$this->db->query($sql);
		$afs = new AFS;
		if ($this->db->next_record()) {
			$ret = $this->db->f("afsID");
		}
		else $ret = false;
		return $ret;
	}
	
	function checkAFSYear($odID,$year){
		$sql = sprintf("SELECT * FROM %s WHERE odID=%s AND effectivity <= %s;",
			AFS_TABLE, $odID, $year);
		//echo $sql."<br>";
		$this->setDB();
		$this->db->query($sql);
		$afs = new AFS;
		if ($this->db->next_record()) {
			$ret = $this->db->f("afsID");
		}
		else $ret = false;
		return $ret;
	}

	function checkOdID($afsID){
		$sql = sprintf("SELECT * FROM %s WHERE afsID=%s;",
			AFS_TABLE, $afsID);
		$this->setDB();
		$this->db->query($sql);
		if ($this->db->next_record()) {
			$ret = $this->db->f("odID");
		}
		else $ret = false;
		return $ret;
	}

	function insertRecord(){
		if(count($this->administratorArray)){
			foreach($this->administratorArray as $key => $value){
				$personID = $value->insertRecord();
			}
		}
		$sql = sprintf("insert into %s (".
			"odID".
			", arpNumber".
			", propertyIndexNumber".
			", taxability".
			", effectivity".
			", cadastralLotNumber".
			", gisTechnicalDescription".
			", landTotalMarketValue".
			", landTotalAssessedValue".
			", plantTotalMarketValue".
			", plantTotalAssessedValue".
			", bldgTotalMarketValue".
			", bldgTotalAssessedValue".
			", machTotalMarketValue".
			", machTotalAssessedValue".
			", totalMarketValue".
			", totalAssessedValue".
			", dateCreated".
			", createdBy".
			", dateModified".
			", modifiedBy".
			", archive".
			") ".
			"values (".
			"'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', ".
			"'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',".
			"'%s', '%s', '%s', '%s', '%s', '%s'".
			");",
			AFS_TABLE
			, fixQuotes($this->odID)
			, fixQuotes($this->arpNumber)
			, fixQuotes($this->propertyIndexNumber)
			, fixQuotes($this->taxability)
			, fixQuotes($this->effectivity)
			, fixQuotes($this->cadastralLotNumber)
			, fixQuotes($this->gisTechnicalDescription)
			, fixQuotes($this->landTotalMarketValue)
			, fixQuotes($this->landTotalAssessedValue)
			, fixQuotes($this->plantTotalMarketValue)
			, fixQuotes($this->plantTotalAssessedValue)
			, fixQuotes($this->bldgTotalMarketValue)
			, fixQuotes($this->bldgTotalAssessedValue)
			, fixQuotes($this->machTotalMarketValue)
			, fixQuotes($this->machTotalAssessedValue)
			, fixQuotes($this->totalMarketValue)
			, fixQuotes($this->totalAssessedValue)
			, fixQuotes(time())
			, fixQuotes($this->createdBy)
			, fixQuotes(time())
			, fixQuotes($this->modifiedBy)
			, fixQuotes($this->archive)
			);
		//echo $sql;
		$this->setDB();
		$this->db->beginTransaction();
		$this->db->query($sql);
		$afsID = $this->db->insert_id();
		if ($this->db->Errno!=0) {
			$this->db->rollbackTransaction();
			$this->db->resetErrors();
			$ret = false;
		}
		else {
			$this->db->endTransaction();
			$ret = $afsID;
		}
		return $ret;
	}
	
	function updateRecord(){
		$sql = sprintf("update %s set".
			"  arpNumber = '%s'".
			", propertyIndexNumber = '%s'".
			", taxability = '%s'".
			", effectivity = '%s'".
			", cadastralLotNumber = '%s'".
			", gisTechnicalDescription = '%s'".
			", landTotalMarketValue = '%s'".
			", landTotalAssessedValue = '%s'".
			", plantTotalMarketValue = '%s'".
			", plantTotalAssessedValue = '%s'".
			", bldgTotalMarketValue = '%s'".
			", bldgTotalAssessedValue = '%s'".
			", machTotalMarketValue = '%s'".
			", machTotalAssessedValue = '%s'".
			", totalMarketValue = '%s'".
			", totalAssessedValue = '%s'".
			", dateModified = '%s'".
			", modifiedBy = '%s'".
			", archive = '%s'".
			" where afsID = '%s';",
			AFS_TABLE
			, fixQuotes($this->arpNumber)
			, fixQuotes($this->propertyIndexNumber)
			, fixQuotes($this->taxability)
			, fixQuotes($this->effectivity)
			, fixQuotes($this->cadastralLotNumber)
			, fixQuotes($this->gisTechnicalDescription)
			, fixQuotes($this->landTotalMarketValue)
			, fixQuotes($this->landTotalAssessedValue)
			, fixQuotes($this->plantTotalMarketValue)
			, fixQuotes($this->plantTotalAssessedValue)
			, fixQuotes($this->bldgTotalMarketValue)
			, fixQuotes($this->bldgTotalAssessedValue)
			, fixQuotes($this->machTotalMarketValue)
			, fixQuotes($this->machTotalAssessedValue)
			, fixQuotes($this->totalMarketValue)
			, fixQuotes($this->totalAssessedValue)
			, fixQuotes(time())
			, fixQuotes($this->modifiedBy)
			, fixQuotes($this->archive)
			, $this->afsID			
			);
		//echo $sql;
		//*
		$this->setDB();

		//$dummySQL = sprintf("INSERT INTO dummySQL(queryString) VALUES('%s');",fixQuotes($sql));
		//$this->db->query($dummySQL);

		$this->db->beginTransaction();
		$this->db->query($sql);
		if ($this->db->Errno!=0) {
			$this->db->rollbackTransaction();
			$this->db->resetErrors();
			$ret = false;
		}
		else {
			$this->db->endTransaction();
			$ret = $this->afsID;
		}//*/
		return $ret;
	}

	function archiveRecord($afsID,$archiveValue,$userID){
		$sql = sprintf("update %s ".
			"  set ".
			"  archive = '%s'".
			", modifiedBy = '%s'".
			", dateModified = '%s'".
			" where afsID = '%s';",
			AFS_TABLE
			, fixQuotes($archiveValue)
			, fixQuotes($userID)
			, fixQuotes(time())
			, fixQuotes($afsID)
		);

		$this->setDB();

		//$dummySQL = sprintf("INSERT INTO dummySQL(queryString) VALUES('%s');",fixQuotes($sql));
		//$this->db->query($dummySQL);
		
		$this->db->beginTransaction();

		$this->db->query($sql);
		if ($this->db->Errno!=0) {
			$this->db->rollbackTransaction();
			$this->db->resetErrors();
			$ret = false;
		}
		else {
			$this->db->endTransaction();
			$ret = true;
		}
		return $ret;
	}
}
?>