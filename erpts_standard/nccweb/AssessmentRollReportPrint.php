<?php
# Setup PHPLIB in this Area
include_once("web/prepend.php");
include_once("assessor/Address.php");
include_once("assessor/LocationAddress.php");
include_once("assessor/Company.php");
include_once("assessor/CompanyRecords.php");
include_once("assessor/Person.php");
include_once("assessor/PersonRecords.php");
include_once("assessor/Owner.php");
include_once("assessor/OwnerRecords.php");
include_once("assessor/OD.php");
include_once("assessor/ODRecords.php");
include_once("assessor/AFS.php");
include_once("assessor/AFSRecords.php");
include_once("assessor/TD.php");
include_once("assessor/TDRecords.php");

include_once("assessor/Barangay.php");
include_once("assessor/BarangayRecords.php");
include_once("assessor/District.php");
include_once("assessor/DistrictRecords.php");
include_once("assessor/MunicipalityCity.php");
include_once("assessor/MunicipalityCityRecords.php");
include_once("assessor/Province.php");
include_once("assessor/ProvinceRecords.php");

include_once("assessor/LandClasses.php");
include_once("assessor/PlantsTreesClasses.php");
include_once("assessor/ImprovementsBuildingsClasses.php");
include_once("assessor/MachineriesClasses.php");

include_once("assessor/eRPTSSettings.php");

include_once("assessor/ODHistory.php");
include_once("assessor/ODHistoryRecords.php");

#####################################
# Define Interface Class
#####################################
class AssessmentRollReport{
	
	var $tpl;
	var $formArray;
	var $sess;

	function AssessmentRollReport($http_post_vars,$sess,$formAction,$formArray,$page=1){
		global $auth;
		//echo "username=>".$auth->auth["uname"];

		$this->formArray = $formArray;
		
		$this->formArray["uid"] = $auth->auth["uid"];
		$this->tpl = new rpts_Template(getcwd(),"keep");

		$this->tpl->set_file("rptsTemplate", "AssessmentRollReportPrint.htm") ;
		$this->tpl->set_var("TITLE", "Assessment Roll Report");
		
		$this->sess = $sess;

		$this->formArray["formAction"] = $formAction;

		foreach ($http_post_vars as $key=>$value) {
			$this->formArray[$key] = $value;
		}

		$this->formArray["barangayID"] = $this->formArray["barangay"];
		$this->formArray["districtID"] = $this->formArray["district"];
		$this->formArray["municipalityCityID"] = $this->formArray["municipalityCity"];
		$this->formArray["provinceID"] = $this->formArray["province"];

		$this->formArray["page"] = $page;

		if($this->formArray["page"]==""){
			$this->formArray["page"] = 1;
		}
	}

	function initSelected($tempVar,$compareTo,$actionStr="selected"){
		if ($this->formArray[$tempVar] == $compareTo){
			$this->tpl->set_var($tempVar."_sel", $actionStr);
		}
		else{
			$this->tpl->set_var($tempVar."_sel", "");
		}
	}

	function filterDate(){
		$filterFromDate = $this->formArray["filterFrom_year"]
			."-"
			.putPreZero($this->formArray["filterFrom_month"])
			."-"
			.putPreZero($this->formArray["filterFrom_day"]);

		$filterToDate = $this->formArray["filterTo_year"]
			."-"
			.putPreZero($this->formArray["filterTo_month"])
			."-"
			.putPreZero($this->formArray["filterTo_day"])
			." 23:59:59";

		$filterFromTimeStr = strtotime($filterFromDate);
		$filterToTimeStr = strtotime($filterToDate);

		$condition = " ".OD_TABLE.".dateCreated >= ".$filterFromTimeStr."";
		$condition .= " AND ".OD_TABLE.".dateCreated <= ".$filterToTimeStr."";

		return $condition;
	}

	function filterLocationAddress(){
		$condition = "";

		if(is_numeric($this->formArray["provinceID"])){
			$condition .= LOCATIONADDRESS_TABLE.".province='".$this->formArray["provinceID"]."'";
		}
		if(is_numeric($this->formArray["municipalityCityID"])){
			if(is_numeric($this->formArray["provinceID"])){
				$condition .= " AND ";
			}
			$condition .= LOCATIONADDRESS_TABLE.".municipalityCity='".$this->formArray["municipalityCityID"]."'";
		}
		if(is_numeric($this->formArray["districtID"])){
			if(is_numeric($this->formArray["municipalityCityID"])){
				$condition .= " AND ";
			}
			$condition .= LOCATIONADDRESS_TABLE.".district='".$this->formArray["districtID"]."'";
		}
		if(is_numeric($this->formArray["barangayID"])){
			if(is_numeric($this->formArray["districtID"])){
				$condition .= " AND ";
			}
			$condition .= LOCATIONADDRESS_TABLE.".barangayID='".$this->formArray["barangayID"]."'";
		}

		if($this->formArray["provinceID"]=="" && $this->formArray["municipalityCityID"]=="" && $this->formArray["districtID"]=="" && $this->formArray["barangayID"]==""){
			$condition = LOCATIONADDRESS_TABLE.".province=''";
			$condition.= " AND ";
			$condition.= LOCATIONADDRESS_TABLE.".municipalityCity=''";
			$condition.= " AND ";
			$condition.= LOCATIONADDRESS_TABLE.".district=''";
			$condition.= " AND ";
			$condition.= LOCATIONADDRESS_TABLE.".barangayID=''";
		}

		return $condition;
	}

	function filterArchives(){
		$condition = " AND ".OD_TABLE.".transactionCode <> 'CA'";
		$condition.= " GROUP BY ".OD_TABLE.".odID "; 
		$condition.= " ORDER BY ".OD_TABLE.".odID ASC";
		return $condition;
	}

	function initLocationDetails($TempVar, $tempVar){

		$getDetails = "get".$TempVar."Details";

		$TempVarDetails = new SoapObject(NCCBIZ.$TempVar."Details.php", "urn:Object");
		if (!$xmlStr = $TempVarDetails->$getDetails($this->formArray[$tempVar."ID"])){
			// error xmlStr
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)) {
				// error domDoc
			}
			else{
				switch($tempVar){
					case "barangay":
						$tempVarObject = new Barangay;
						break;
					case "district":
						$tempVarObject = new District;
						break;
					case "municipalityCity":
						$tempVarObject = new MunicipalityCity;
						break;
					case "province":
						$tempVarObject = new Province;
						break;
				}
				$tempVarObject->parseDomDocument($domDoc);
				$this->formArray[$tempVar] = $tempVarObject->getDescription();
			}
		}
	}

	function initMasterAddressList($TempVar, $tempVar){
	    $getList = "get".$TempVar."List";
	    $getID = "get".$TempVar."ID";

		switch($tempVar){
			case "barangay":
				$propertyTable = BARANGAY_TABLE;
				break;
			case "district":
				$propertyTable = DISTRICT_TABLE;
				break;
			case "municipalityCity":
				$propertyTable = MUNICIPALITYCITY_TABLE;
				break;
			case "province":
				$propertyTable = PROVINCE_TABLE;
				break;
	   }
	
		$this->tpl->set_block("rptsTemplate", $TempVar."List", $TempVar."ListBlock");

		$TempVarList = new SoapObject(NCCBIZ.$TempVar."List.php", "urn:Object");
        if (!$xmlStr = $TempVarList->$getList(0, " WHERE ".$propertyTable.".status='active' ORDER BY description")){
			switch($tempVar){
				case "barangay":
				case "district":
				case "municipalityCity":
					$this->tpl->set_block("rptsTemplate", "JS".$TempVar."List", "JS".$TempVar."ListBlock");
					$this->tpl->set_var("JS".$TempVar."ListBlock", "");
					break;
		   }
		   $this->tpl->set_var($tempVar."ID", "");
           $this->tpl->set_var($tempVar, "empty ".$tempVar." list");
		   $this->tpl->parse($TempVar."ListBlock", $TempVar."List", true);
   	    }
		else {
			if(!$domDoc = domxml_open_mem($xmlStr)) {
				switch($tempVar){
					case "barangay":
					case "district":
					case "municipalityCity":
						$this->tpl->set_block("rptsTemplate", "JS".$TempVar."List", "JS".$TempVar."ListBlock");
						$this->tpl->set_var("JS".$TempVar."ListBlock", "");
						break;
			    }
			    $this->tpl->set_var($tempVar."ID", "");
                $this->tpl->set_var($tempVar, "empty ".$tempVar." list");
		        $this->tpl->parse($TempVar."ListBlock", $TempVar."List", true);
			}
			else {
			    switch($tempVar){
			        case "barangay":
			   	       $this->tpl->set_block("rptsTemplate", "JS".$TempVar."List", "JS".$TempVar."ListBlock");
			           $tempVarRecords = new BarangayRecords;
                       $tempVarID = $getID;
			        break;
			        case "district":
			   	       $this->tpl->set_block("rptsTemplate", "JS".$TempVar."List", "JS".$TempVar."ListBlock");
			           $tempVarRecords = new DistrictRecords;
			           $tempVarID = $getID;
                    break;
                    case "municipalityCity":
			   	       $this->tpl->set_block("rptsTemplate", "JS".$TempVar."List", "JS".$TempVar."ListBlock");
                       $tempVarRecords = new MunicipalityCityRecords;
                       $tempVarID = $getID;
                    break;
                    case "province":
                       $tempVarRecords = new ProvinceRecords;
                       $tempVarID = $getID;
                    break;
			    }

				$tempVarRecords->parseDomDocument($domDoc);
				$list = $tempVarRecords->getArrayList();
				$i = 0;
				foreach ($list as $key => $value){
          			$this->tpl->set_var($tempVar."ID", $value->$tempVarID());

               		$this->tpl->set_var($tempVar, $value->getDescription());
			        $this->initSelected($tempVar,$value->$tempVarID());

			        $this->tpl->parse($TempVar."ListBlock", $TempVar."List", true);

					switch($tempVar){
						case "barangay":
							$this->tpl->set_var("i", $i);
							$this->tpl->set_var("districtID", $value->getDistrictID());
							$this->tpl->parse("JS".$TempVar."ListBlock", "JS".$TempVar."List", true);
	  					    $i++;
						break;
						case "district":
							$this->tpl->set_var("i", $i);
							$this->tpl->set_var("municipalityCityID", $value->getMunicipalityCityID());
							$this->tpl->parse("JS".$TempVar."ListBlock", "JS".$TempVar."List", true);
	  					    $i++;
						break;
						case "municipalityCity":
							$this->tpl->set_var("i", $i);
							$this->tpl->set_var("provinceID", $value->getProvinceID());
							$this->tpl->parse("JS".$TempVar."ListBlock", "JS".$TempVar."List", true);
	  					    $i++;
						break;
					}
				}
			}
		}
	
	}

	function getAFSDetails($odID){
		$AFSEncode = new SoapObject(NCCBIZ."AFSEncode.php", "urn:Object");
        $this->formArray["afsID"] = $AFSEncode->getAFSID($odID);

		if($this->formArray["afsID"]){
			$afs = new AFS;
			if($afs->selectRecord($this->formArray["afsID"])){
				return $afs;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}

	function displayPrecedingOD(){
		$ODHistoryList = new SoapObject(NCCBIZ."ODHistoryList.php", "urn:Object");
		if (!$xmlStr = $ODHistoryList->getPrecedingODList($this->formArray["odID"])){
			$this->tpl->set_var("precedingTransactionCode", "");

			$this->tpl->set_var("precedingcancelsTDNumber", "");
			$this->tpl->set_var("precedingAssessedValue", "");
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)) {
				$this->tpl->set_var("precedingTransactionCode", "");
				//diri edit nako
				$this->tpl->set_var("precedingcancelsTDNumber", "");
				$this->tpl->set_var("precedingAssessedValue", "");
			}
			else{
				$odHistoryRecords = new ODHistoryRecords;
				$odHistoryRecords->parseDomDocument($domDoc);
				$arrayList = $odHistoryRecords->getArrayList();
				if(count($arrayList)){
					$value = $arrayList[0];
						$ODDetails = new SoapObject(NCCBIZ."ODDetails.php", "urn:Object");
						if(!$xmlStr = $ODDetails->getOD($value->getPreviousODID())){
							$this->tpl->set_var("precedingTransactionCode", "");
							//diri edit nako
							$this->tpl->set_var("precedingcancelsTDNumber", "");
							$this->tpl->set_var("precedingAssessedValue", "");
						}
						else{
							if(!$domDoc = domxml_open_mem($xmlStr)){
								$this->tpl->set_var("precedingTransactionCode", "");
								// diri edit nako
								$this->tpl->set_var("precedingcancelsTDNumber", "");
								$this->tpl->set_var("precedingAssessedValue", "");
							}
							else{
								$precedingOD = new OD;
								$precedingOD->parseDomDocument($domDoc);

								$precedingODID = $precedingOD->getOdID();
								$precedingAFS = $this->getAFSDetails($precedingODID);

				$this->tpl->set_var("precedingTransactionCode", $value->getTransactionCode());
			// diri edit nako 					
		$this->tpl->set_var("precedingcancelsTDNumber", $precedingTD->getcancelsTDNumber());
	$this->tpl->set_var("precedingAssessedValue", number_format($precedingAFS->getTotalAssessedValue(), 2, ".", ","));
							}
					}
				}
			}
		}	
	}

	function displaySucceedingOD(){
		$ODHistoryList = new SoapObject(NCCBIZ."ODHistoryList.php", "urn:Object");
		if (!$xmlStr = $ODHistoryList->getSucceedingODList($this->formArray["odID"])){
			$this->tpl->set_var("succeedingcanceledByTDNumber", "");
			$this->tpl->set_var("succeedingTransactionCode", "");
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)) {
				$this->tpl->set_var("succeedingcanceledByTDNumber", "");
				$this->tpl->set_var("succeedingTransactionCode", "");
			}
			else{
				$odHistoryRecords = new ODHistoryRecords;
				$odHistoryRecords->parseDomDocument($domDoc);
				$arrayList = $odHistoryRecords->getArrayList();
				if(count($arrayList)){
					$value = $arrayList[0];
						$ODDetails = new SoapObject(NCCBIZ."ODDetails.php", "urn:Object");
						if(!$xmlStr = $ODDetails->getOD($value->getPresentODID())){
							$this->tpl->set_var("succeedingcanceledByTDNumber", "");
							$this->tpl->set_var("succeedingTransactionCode", "");
						}
						else{
					if(!$domDoc = domxml_open_mem($xmlStr)){
					$this->tpl->set_var("succeedingcanceledByTDNumber", "");
					$this->tpl->set_var("succeedingTransactionCode", "");
					}
					else{
					$succeedingOD = new OD;
					$succeedingOD->parseDomDocument($domDoc);

					$succeedingODID = $succeedingOD->getOdID();
					$succeedingAFS = $this->getAFSDetails($succeedingODID);

			$this->tpl->set_var("succeedingcanceledByTDNumber", $succeedingtd->getcanceledByTDNumber());
				$this->tpl->set_var("succeedingTransactionCode", $value->getTransactionCode());
							}
						}
				}
			}
		}	
	}

	function setForm(){
		$this->tpl->set_var("formArray[filterByLocation]", $this->formArray["filterByLocation"]);
		$this->tpl->set_var("formArray[filterByDate]", $this->formArray["filterByDate"]);
		$this->tpl->set_var("formArray[province]", $this->formArray["province"]);
		$this->tpl->set_var("formArray[municipalityCity]", $this->formArray["municipalityCity"]);
		$this->tpl->set_var("formArray[district]", $this->formArray["district"]);
		$this->tpl->set_var("formArray[barangay]", $this->formArray["barangay"]);
		$this->tpl->set_var("formArray[filterFrom_month]", $this->formArray["filterFrom_month"]);
		$this->tpl->set_var("formArray[filterFrom_day]", $this->formArray["filterFrom_day"]);
		$this->tpl->set_var("formArray[filterFrom_year]", $this->formArray["filterFrom_year"]);
		$this->tpl->set_var("formArray[filterTo_month]", $this->formArray["filterTo_month"]);
		$this->tpl->set_var("formArray[filterTo_day]", $this->formArray["filterTo_day"]);
		$this->tpl->set_var("formArray[filterTo_year]", $this->formArray["filterTo_year"]);

		$this->initLocationDetails("Barangay", "barangay");
		$this->initLocationDetails("District", "district");
		$this->initLocationDetails("MunicipalityCity", "municipalityCity");
		$this->initLocationDetails("Province", "province");

		$this->formArray["todayYmd"] = date("Y-m-d");;

		foreach ($this->formArray as $key => $value){
			$this->tpl->set_var($key, $value);
		}
	}
	
	function Main(){
		switch ($this->formArray["formAction"]){
			case "view":
				$ODList = new SoapObject(NCCBIZ."ODList.php", "urn:Object");

				$condition = "";

				if($this->formArray["filterByLocation"]=="true"){
					if($condition==""){
						$condition = " WHERE ";
					}
					$condition .= $this->filterLocationAddress();
				}
				else{
					$this->tpl->set_block("rptsTemplate", "FilterLocationDetails", "FilterLocationDetailsBlock");
					$this->tpl->set_var("FilterLocationDetailsBlock", "");
				}

				if($this->formArray["filterByDate"]=="true"){
					if($condition==""){
						$condition = " WHERE ";
					}
					else{
						$condition .= " AND ";
					}
					$condition .= $this->filterDate();
				}
				else{
					$this->tpl->set_block("rptsTemplate", "FilterDateDetails", "FilterDateDetailsBlock");
					$this->tpl->set_var("FilterDateDetailsBlock", "");
				}

				$condition .= $this->filterArchives();
				$odRecords = new ODRecords;

				// paging

				if(!$count = $odRecords->countRecords($condition)){
					$this->tpl->set_block("rptsTemplate", "PageNavigationOne", "PageNavigationOneBlock");
					$this->tpl->set_var("PageNavigationOneBlock", "");
				}
				else{
					$numOfPages = ceil($count / PAGE_BY);
					$this->tpl->set_var("currentPage",$this->formArray["page"]);
					$this->tpl->set_var("numOfPages",$numOfPages);
					$this->tpl->set_block("rptsTemplate", "PageListOne", "PageListOneBlock");
					for($p=1 ; $p<=$numOfPages ; $p++){
						$this->tpl->set_var("page",$p);
						$this->initSelected("page",$p);
						$this->tpl->parse("PageListOneBlock", "PageListOne", true);
					}
				}

				if ($this->formArray["page"] > 0){
					$initialLimit = ($this->formArray["page"]-1) * PAGE_BY;
					$condition .= " LIMIT ".$initialLimit.",".PAGE_BY;
				}

				// listing

				if (!$odRecords->selectRecords($condition)){
					$this->tpl->set_block("rptsTemplate", "NotFound", "NotFoundBlock");
					$this->tpl->set_var("message", "properties not found");
					$this->tpl->parse("NotFoundBlock", "NotFound", true);
					
					$this->tpl->set_block("rptsTemplate", "Report", "ReportBlock");
					$this->tpl->set_var("ReportBlock", "");
				}
				else {
					$list = $odRecords->getArrayList();

					if (count($list)){
						$this->tpl->set_block("rptsTemplate", "NotFound", "NotFoundBlock");
						$this->tpl->set_var("NotFoundBlock", "");

						$this->tpl->set_block("rptsTemplate", "ReportList", "ReportListBlock");

						foreach ($list as $key => $value){

							$this->formArray["odID"] = $value->getOdID();

							$this->tpl->set_var("odID", $value->getOdID());
							$afs = $this->getAFSDetails($value->getOdID());

							if(is_object($afs)){

			$this->tpl->set_var("propertyIndexNumber", $afs->getPropertyIndexNumber());
			$this->tpl->set_var("arpNumber", $afs->getArpNumber());

				$this->formArray["odID"] = $value->getOdID();
                        	$this->formArray["afsID"] = $afs->getAfsID();
				//$od = $this->getODDetails($value->getOdID());
				
			
			$this->tpl->set_var("transactionCode", $value->getTransactionCode());
                     	
			$td = new TD;
                        if($td->selectRecord("", $this->formArray["afsID"])){
                        $this->tpl->set_var("taxDeclarationNumber", $td->getTaxDeclarationNumber());
                        }
                        else{
                        $this->tpl->set_var("taxDeclarationNumber","");
                        }
			
                        
			
			$this->tpl->set_var("area", $value->getLandArea());
 			$this->tpl->set_var("marketValue", number_format($afs->getTotalMarketValue()));			

			$this->tpl->set_var("assessedValue", number_format($afs->getTotalAssessedValue(), 2, '.', ','));
			
			$this->tpl->set_var("cancelsTDNumber", $td->getCancelsTDNumber());
			$this->tpl->set_var("previousAssessedValue", $td->getPreviousAssessedValue());
			$this->tpl->set_var("previousOwner", $td->getPreviousOwner());
			$this->tpl->set_var("canceledByTDNumber", $td->getCanceledByTDNumber());	
			

								$landList = $afs->getLandArray();
								$plantsTreesList = $afs->getPlantsTreesArray();
								$improvementsBuildingsList = $afs->getImprovementsBuildingsArray();
								$machineriesList = $afs->getMachineriesArray();

								$this->tpl->set_var("taxability", $afs->getTaxability());
								$this->tpl->set_var("effectivity", $afs->getEffectivity());

								$kind = "";
								$class = "";

								if(count($landList)){
									$kind = "Land";
									$land = $landList[0];
									$class = $land->getClassification();
	
									$landClasses = new LandClasses;
									$landClasses->selectRecord($class);
									$class = $landClasses->getDescription();
								}
								else if(count($plantsTreesList)){
									$kind = "Land";
									$plantsTrees = $plantsTreesList[0];
	
									$class = $plantsTrees->getProductClass();
	
									$plantsTreesClasses = new PlantsTreesClasses;
									$plantsTreesClasses->selectRecord($class);
									$class = $plantsTreesClasses->getDescription();
								}
								else if(count($improvementsBuildingsList)){
									$kind = "Improvements/Buildings";
									$improvementsBuildings = $improvementsBuildingsList[0];
									$class = $improvementsBuildings->getBuildingClassification();
	
									$improvementsBuildingsClasses = new ImprovementsBuildingsClasses;
									$improvementsBuildingsClasses->selectRecord($class);
									$class = $improvementsBuildingsClasses->getDescription();
								}
								else if(count($machineriesList)){
									$kind = "Machineries";
									$machineries = $machineriesList[0];
									$class = $machineries->getKind();
								}
	
								$this->tpl->set_var("kind", $kind);
								$this->tpl->set_var("class", $class);
	
								$oValue = $value->owner;

								if(is_array($oValue->personArray)){
									foreach($oValue->personArray as $person){
										$ownerNamesArray[] = $person->getName();
									}
								}
								if(is_array($oValue->companyArray)){
									foreach($oValue->companyArray as $company){
										$ownerNamesArray[] = $company->getCompanyName();
									}
								}

								if(is_array($ownerNamesArray)){
									$this->tpl->set_var("ownerNames", implode(",<br>",$ownerNamesArray));
								}
								else{
									$this->tpl->set_var("ownerNames", "");
								}
								unset($ownerNamesArray);
	
								if (count($oValue->personArray)){
									$firstOwner = $oValue->personArray[0]->getLastName();
									$firstOwner.= ", ";
									$firstOwner.= $oValue->personArray[0]->getFirstName();
									$firstOwner.= " ";
									$firstOwner.= substr($oValue->personArray[0]->getMiddleName(), 0, 1) . ".";
	
									$pAddress = ($oValue->personArray[0]->addressArray) ? 	$oValue->personArray[0]->addressArray[0]->getFullAddress() : "no address" ;
	
									$firstOwnerAddress = $pAddress;
									$firstOwnerTelephone = $oValue->personArray[0]->getTelephone();
	
									$this->tpl->set_var("firstOwner", $firstOwner);
									$this->tpl->set_var("firstOwnerAddress", $firstOwnerAddress);
									$this->tpl->set_var("firstOwnerTelephone", $firstOwnerTelephone);
								}
								if (count($oValue->companyArray)){
									if($firstOwner==""){
										$firstOwner = $oValue->companyArray[0]->getCompanyName();
	
										$cAddress = ($oValue->companyArray[0]->addressArray) 	?$oValue->companyArray[0]->addressArray[0]->getFullAddress() : "no address";
	
										$firstOwnerAddress = $cAddress;
										$firstOwnerTelephone = $oValue->companyArray[0]->getTelephone();
	
										$this->tpl->set_var("firstOwner", $firstOwner);
										$this->tpl->set_var("firstOwnerAddress", $firstOwnerAddress);
										$this->tpl->set_var("firstOwnerTelephone", $firstOwnerTelephone);
									}
								}
								if ($firstOwner!=""){
									$this->tpl->set_var("none","");
									if((count($oValue->personArray) + count($oValue->companyArray)) > 1){
										$this->tpl->set_var("andOthers", "(and others)");
									}
									else{
										$this->tpl->set_var("andOthers", "");
									}
								}
								else{
									$this->tpl->set_var("none","none");
									$this->tpl->set_var("firstOwner", "");
									$this->tpl->set_var("andOthers", "");
									$this->tpl->set_var("firstOwnerAddress", "");
									$this->tpl->set_var("firstOwnerTelephone", "");
								}

								$locationAddress = $value->locationAddress->getFullAddress();

								$this->tpl->set_var("locationAddress", $locationAddress);

								$this->displayPrecedingOD();
								$this->displaySucceedingOD();
						//$this->tpl->set_var("transactionCode", $value->getTransactionCode());
								
								$this->tpl->parse("ReportListBlock", "ReportList", true);

							
							}

						}
	
					}
				}

				break;
			default:
				$this->tpl->set_block("rptsTemplate", "NotFound", "NotFoundBlock");
				$this->tpl->set_var("message", "select a location to view report");
				$this->tpl->parse("NotFoundBlock", "NotFound", true);

				$this->tpl->set_block("rptsTemplate", "Report", "ReportBlock");
				$this->tpl->set_var("ReportBlock", "");
				break;
		}
		
        $this->setForm();
        $this->tpl->set_var("Session", $this->sess->url(""));
		$this->tpl->set_var("rpts_Session", $this->sess->id);
        $this->tpl->parse("templatePage", "rptsTemplate");
        $this->tpl->finish("templatePage");
        $this->tpl->p("templatePage");		

	}
}

#####################################
# Define Procedures and Functions
#####################################

##########################################################
# Begin Program Script
##########################################################
page_open(array("sess" => "rpts_Session"
	,"auth" => "rpts_Challenge_Auth"
	//"perm" => "rpts_Perm"
	));
	//global $auth;
	//print_r($auth);

$assessmentRollReport = new AssessmentRollReport($HTTP_POST_VARS,$sess,$formAction,$formArray,$page);
$assessmentRollReport->Main();
?>
<?php page_close(); ?>
