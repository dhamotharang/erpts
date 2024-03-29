<?php

class ImprovementsBuildingsClasses
{
	//attributes
	var $improvementsBuildingsClassesID;
	var $code;
	var $description;
	var $rangeLowerBound;
	var $rangeUpperBound;
	var $value;
	var $type;
	var $status;
	var $domDocument;
	var $db;
	
	//constructor
	function ImprovementsBuildingsClasses() {
	
	}
	//methods
	//set
	function setDB(){
		$this->db = new DB_RPTS;
	}
	
	function setImprovementsBuildingsClassesID($tempVar) {
		$this->improvementsBuildingsClassesID = $tempVar;
	}
	function setCode($tempVar) {
		$this->code = $tempVar;
	}
	function setDescription($tempVar) {
		$this->description = $tempVar;
	}
	function setRangeLowerBound($tempVar){
		$this->rangeLowerBound = $tempVar;
	}
	function setRangeUpperBound($tempVar){
		$this->rangeUpperBound = $tempVar;
	}
	function setValue($tempVar) {
		$this->value = $tempVar;
	}
	function setType($tempVar){
		// different from PHP settype() function 
		$this->type = $tempVar;
	}
	function setStatus($tempVar) {
		$this->status = $tempVar;
	}
	
	//DOM
	function setDocNode($elementName,$elementValue,$domDoc,$indexNode){
		$nodeName = "";
		$nodeText = "";
		$nodeName = $domDoc->create_element($elementName);
		$nodeName = $indexNode->append_child($nodeName);
		$nodeText = $domDoc->create_text_node(htmlentities($elementValue));
		$nodeText = $nodeName->append_child($nodeText);
	}
	function setArrayDocNode($elementName,$arrayList,$indexNode){
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
	function setObjectDocNode($elementName,$elementObject,$domDoc,$indexNode){
		$nodeName = "";
		$nodeDomDoc = $elementObject->getDomDocument();
		$nodeObject = $nodeDomDoc->document_element();

		$nodeClone = $nodeObject->clone_node(true);
			
		$nodeName = $domDoc->create_element($elementName);
		$nodeName = $indexNode->append_child($nodeName);
		$nodeObject = $nodeName->append_child($nodeClone);
	}
	function setDomDocument() {
		$this->domDocument = domxml_new_doc("1.0");
		$rec = $this->domDocument->create_element("ImprovementsBuildingsClasses");
		$rec = $this->domDocument->append_child($rec);
		//$rec->set_attribute("improvementsBuildingsClassesID",$this->improvementsBuildingsClassesID);
		$this->setDocNode("improvementsBuildingsClassesID",$this->improvementsBuildingsClassesID,$this->domDocument,$rec);
		$this->setDocNode("code",$this->code,$this->domDocument,$rec);
		$this->setDocNode("description",$this->description,$this->domDocument,$rec);
		$this->setDocNode("rangeLowerBound",$this->rangeLowerBound,$this->domDocument,$rec);
		$this->setDocNode("rangeUpperBound",$this->rangeUpperBound,$this->domDocument,$rec);
		$this->setDocNode("value",$this->value,$this->domDocument,$rec);
		$this->setDocNode("type",$this->type,$this->domDocument,$rec);
		$this->setDocNode("status",$this->status,$this->domDocument,$rec);
	}
	function parseDomDocument($domDoc){
		$ret = true;
		$baseNode = $domDoc->document_element();
		if ($baseNode->has_child_nodes()){
			$child = $baseNode->first_child();
			while ($child){
				//eval("\$this->".$child->tagname." = \"".$child->get_content()."\";");
				//eval("\$this->set".ucfirst($child->tagname)."(\"".$child->get_content()."\");");

				// test varvars
				$varvar = $child->tagname;
				$this->$varvar = html_entity_decode($child->get_content());

				$child = $child->next_sibling();
			}
		}
		$this->setDomDocument();
		return $ret;
	}
	function getDomDocument() {
		return $this->domDocument;
	}
	
	//get
	function getImprovementsBuildingsClassesID() {
		return $this->improvementsBuildingsClassesID;
	}
	function getCode() {
		return $this->code;
	}
	function getDescription() {
		return $this->description;
	}
	function getRangeLowerBound(){
		return $this->rangeLowerBound;
	}
	function getRangeUpperBound(){
		return $this->rangeUpperBound;
	}
	function getValue() {
		return $this->value;
	}	
	function getType(){
		// different from PHP gettype() function
		return $this->type;
	}
	function getStatus() {
		return $this->status;
	}

	//DB
	function selectRecord($improvementsBuildingsClassesID){
		if ($improvementsBuildingsClassesID=="") return;

		$this->setDB();
		$sql = sprintf("SELECT * FROM %s WHERE improvementsBuildingsClassesID=%s;",
			IMPROVEMENTSBUILDINGS_CLASSES_TABLE, $improvementsBuildingsClassesID);
			$this->db->query($sql);
		$improvementsBuildingsClasses = new ImprovementsBuildingsClasses;
		if ($this->db->next_record()) {
			//*
			$this->improvementsBuildingsClassesID = $this->db->f("improvementsBuildingsClassesID");
			$this->code = $this->db->f("code");
			$this->description = $this->db->f("description");
			$this->rangeLowerBound = $this->db->f("rangeLowerBound");
			$this->rangeUpperBound = $this->db->f("rangeUpperBound");
			$this->value = $this->db->f("value");	
			$this->type = $this->db->f("type");
			$this->status = $this->db->f("status");
			//*/
			foreach ($this->db->Record as $key => $value){
				$this->$key = $value;
			}
			$this->setDomDocument();
			$ret = true;
		}
		else $ret = false;
		return $ret;
	}
	
	function insertRecord(){
		$sql = sprintf("insert into %s (".
			"code".
			", description".
			", rangeLowerBound".
			", rangeUpperBound".
			", value".
			", type".
			", status".
			") ".
			"values ('%s', '%s', %s, '%s', '%s', '%s', '%s');"
			, IMPROVEMENTSBUILDINGS_CLASSES_TABLE
			, fixQuotes($this->code)
			, fixQuotes($this->description)
			, fixQuotes($this->rangeLowerBound)
			, fixQuotes($this->rangeUpperBound)
		    , fixQuotes($this->value)
			, fixQuotes($this->type)
			, fixQuotes($this->status)
		);
	
		$this->setDB();
		$this->db->beginTransaction();
		$this->db->query($sql);
		$improvementsBuildingsClassesID = $this->db->insert_id();
		if ($this->db->Errno!=0) {
			$this->db->rollbackTransaction();
			$this->db->resetErrors();
			$ret = false;
		}
		else {
			$this->db->endTransaction();
			$ret = $improvementsBuildingsClassesID;
		}
		
		//echo $sql;
		return $ret;
	}
	
	function deleteRecord($improvementsBuildingsClassesID){
		$this->setDB();
		$this->db->beginTransaction();
		$this->selectRecord($improvementsBuildingsClassesID);
		$sql = sprintf("delete from %s where improvementsBuildingsClassesID=%s;",
			IMPROVEMENTSBUILDINGS_CLASSES_TABLE, $improvementsBuildingsClassesID);
		$this->db->query($sql);
		$improvementsBuildingsClassesRows = $this->db->affected_rows();
		
		if ($this->db->Errno != 0) {
			$errno = $this->db->Errno;
			$this->db->rollbackTransaction();
			$this->db->resetErrors();
			$ret = false;
		}
		else {
			$this->db->endTransaction();
			$ret = $improvementsBuildingsClassesRows;
		}
		return $ret;
	}
	
	function updateRecord(){
		
		$sql = sprintf("update %s set".
			" code = '%s'".
			", description = '%s'".
			", rangeLowerBound = '%s'".
			", rangeUpperBound = '%s'".
			", value = '%s'".
			", type = '%s'".
			", status = '%s'".
			" where improvementsBuildingsClassesID = '%s';",
			IMPROVEMENTSBUILDINGS_CLASSES_TABLE
			, fixQuotes($this->code)
			, fixQuotes($this->description)
			, fixQuotes($this->rangeLowerBound)
			, fixQuotes($this->rangeUpperBound)
			, fixQuotes($this->value)
			, fixQuotes($this->type)
			, fixQuotes($this->status)
			, $this->improvementsBuildingsClassesID
		);
		//echo $sql;
		$this->setDB();
		$this->db->beginTransaction();
		$this->db->query($sql);
		if ($this->db->Errno!=0) {
			$this->db->rollbackTransaction();
			$this->db->resetErrors();
			$ret = false;
		}
		else {
			$this->db->endTransaction();
			$ret = $this->improvementsBuildingsClassesID;
		}
		return $ret;
	}
	
}
?>
