<?php 
# Setup PHPLIB in this Area
include_once("web/prepend.php");

include_once("assessor/Barangay.php");

include_once("assessor/Address.php");
include_once("assessor/Company.php");
include_once("assessor/CompanyRecords.php");
include_once("assessor/Person.php");
include_once("assessor/PersonRecords.php");
include_once("assessor/Owner.php");
include_once("assessor/OwnerRecords.php");
include_once("assessor/RPTOP.php");
include_once("assessor/AFS.php");

include_once("assessor/LandClasses.php");
include_once("assessor/LandSubclasses.php");
include_once("assessor/LandActualUses.php");

include_once("assessor/PlantsTreesClasses.php");
include_once("assessor/PlantsTreesActualUses.php");
include_once("assessor/ImprovementsBuildingsClasses.php");
include_once("assessor/ImprovementsBuildingsActualUses.php");
include_once("assessor/MachineriesClasses.php");
include_once("assessor/MachineriesActualUses.php");

include_once("assessor/ODHistoryRecords.php");
include_once("assessor/ODHistory.php");

include_once("collection/Due.php");
include_once("collection/DueRecords.php");
include_once("collection/TreasurySettings.php");
include_once("collection/BacktaxTD.php");
include_once("collection/BacktaxTDRecords.php");

include_once("collection/PaymentRecords.php");
include_once("collection/ReceiptRecords.php");
include_once("collection/CollectionRecords.php");

#####################################
# Define Interface Class
#####################################
class RPTOPDetails{
	
	var $tpl;
	var $formArray;

	var $now;

	function RPTOPDetails($http_post_vars,$sess,$rptopID){
		global $auth;

		$this->now = "now";

		$this->sess = $sess;
		$this->user = $auth->auth;
		$this->formArray["uid"] = $auth->auth["uid"];
		$this->user = $auth->auth;

		// must have atleast TM-VIEW access
		$pageType = "%%%%1%%%%%";
		if (!checkPerms($this->user["userType"],$pageType)){
			header("Location: Unauthorized.php".$this->sess->url(""));
			exit;
		}

		$this->tpl = new rpts_Template(getcwd(),"keep");

		$this->tpl->set_file("rptsTemplate", "PaymentDetails.htm") ;
		$this->tpl->set_var("TITLE", "Apply Payments");
		$this->formArray = array(
			"cityAssessorName" => ""
			, "cityTreasurerName" => ""
		);
		$this->formArray["rptopID"] = $rptopID;
		$this->formArray["landTotalMarketValue"] = 0;
		$this->formArray["landTotalAssessedValue"] = 0;
		$this->formArray["plantTotalMarketValue"] = 0;
		$this->formArray["plantTotalAssessedValue"] = 0;
		$this->formArray["bldgTotalMarketValue"] = 0;
		$this->formArray["bldgTotalAssessedValue"] = 0;
		$this->formArray["machTotalMarketValue"] = 0;
		$this->formArray["machTotalAssessedValue"] = 0;	

		$this->formArray["advancedDiscountPercentage"] = "";
		$this->formArray["q1AdvancedDiscountPercentage"] = "";
		$this->formArray["discountPercentage"] = "";
		$this->formArray["discountPeriod"] = "";
		$this->formArray["advancedPaymentDiscount[Annual]"] = "";
		$this->formArray["advancedPaymentDiscount[Q1]"] = "";
		$this->formArray["advancedPaymentDiscount[Q2]"] = "";
		$this->formArray["advancedPaymentDiscount[Q3]"] = "";
		$this->formArray["advancedPaymentDiscount[Q4]"] = "";
		$this->formArray["earlyPaymentDiscount[Annual]"] = "";
		$this->formArray["earlyPaymentDiscount[Q1]"] = "";
		$this->formArray["earlyPaymentDiscount[Q2]"] = "";
		$this->formArray["earlyPaymentDiscount[Q3]"] = "";
		$this->formArray["earlyPaymentDiscount[Q4]"] = "";
		$this->formArray["monthsOverDue[Annual]"] = "";
		$this->formArray["monthsOverDue[Q1]"] = "";
		$this->formArray["monthsOverDue[Q2]"] = "";
		$this->formArray["monthsOverDue[Q3]"] = "";
		$this->formArray["monthsOverDue[Q4]"] = "";
		$this->formArray["penaltyPercentage[Annual]"] = "";
		$this->formArray["penaltyPercentage[Q1]"] = "";
		$this->formArray["penaltyPercentage[Q2]"] = "";
		$this->formArray["penaltyPercentage[Q3]"] = "";
		$this->formArray["penaltyPercentage[Q4]"] = "";
		$this->formArray["penalty[Annual]"] = "";
		$this->formArray["penalty[Q1]"] = "";
		$this->formArray["penalty[Q2]"] = "";
		$this->formArray["penalty[Q3]"] = "";
		$this->formArray["penalty[Q4]"] = "";

		$this->formArray["totalTaxDue"] = 0.00;
		$this->formArray["totalNetDue"] = 0.00;

		foreach ($http_post_vars as $key=>$value) {
			$this->formArray[$key] = $value;
		}
	}
	
	function setForm(){
		$this->formArray["landTotalMarketValue"] = number_format($this->formArray["landTotalMarketValue"], 2, '.', ',');
		$this->formArray["plantTotalMarketValue"] = number_format($this->formArray["plantTotalMarketValue"], 2, '.', ',');
		$this->formArray["bldgTotalMarketValue"] = number_format($this->formArray["bldgTotalMarketValue"], 2, '.', ',');
		$this->formArray["machTotalMarketValue"] = number_format($this->formArray["machTotalMarketValue"], 2, '.', ',');
		$this->formArray["landTotalAssessedValue"] = number_format($this->formArray["landTotalAssessedValue"], 2, '.', ',');
		$this->formArray["plantTotalAssessedValue"] = number_format($this->formArray["plantTotalAssessedValue"], 2, '.', ',');
		$this->formArray["bldgTotalAssessedValue"] = number_format($this->formArray["bldgTotalAssessedValue"], 2, '.', ',');
		$this->formArray["machTotalAssessedValue"] = number_format($this->formArray["machTotalAssessedValue"], 2, '.', ',');
		$this->formArray["totalTaxDue"] = number_format($this->formArray["totalTaxDue"], 2, '.', ',');

		$this->formArray["totalMarketValue"] = number_format($this->formArray["totalMarketValue"], 2, '.', ',');
		$this->formArray["totalAssessedValue"] = number_format($this->formArray["totalAssessedValue"], 2, '.', ',');

		$this->formArray["totalNetDue"] = number_format($this->formArray["totalNetDue"],2, ".", ",");

		foreach ($this->formArray as $key => $value){
			$this->tpl->set_var($key, $value);
		}
	}

	function hideBlock($tempVar){
		$this->tpl->set_block("rptsTemplate", $tempVar, $tempVar."Block");
		$this->tpl->set_var($tempVar."Block", "");
	}

	function setPageDetailPerms(){
		if(!checkPerms($this->user["userType"],"%%%1%%%%%%")){
			// hide Blocks if userType is not at least TM-Edit
			$this->hideBlock("TreasuryMaintenanceLink");
		}
		else{
			$this->hideBlock("TreasuryMaintenanceLinkText");
		}
	}

	function displayNetDue(){
		$this->netDue["Annual"] = ($this->initialNetDue["Annual"] + $this->formArray["totalBacktaxesBalance"]) - $this->formArray["totalPaid"];
		$this->netDue["Q1"] = ($this->initialNetDue["Q1"] + $this->formArray["totalBacktaxesBalance"]) - $this->formArray["totalPaid"];
		$this->netDue["Q2"] = ($this->initialNetDue["Q2"] + $this->formArray["totalBacktaxesBalance"]) - $this->formArray["totalPaid"];
		$this->netDue["Q3"] = ($this->initialNetDue["Q3"] + $this->formArray["totalBacktaxesBalance"]) - $this->formArray["totalPaid"];
		$this->netDue["Q4"] = ($this->initialNetDue["Q4"] + $this->formArray["totalBacktaxesBalance"]) - $this->formArray["totalPaid"];

		$this->tpl->set_var("netDue[Annual]",number_format($this->netDue["Annual"],2));
		$this->tpl->set_var("netDue[Q1]",number_format($this->netDue["Q1"],2));
		$this->tpl->set_var("netDue[Q2]",number_format($this->netDue["Q2"],2));
		$this->tpl->set_var("netDue[Q3]",number_format($this->netDue["Q3"],2));
		$this->tpl->set_var("netDue[Q4]",number_format($this->netDue["Q4"],2));
		$this->tpl->set_var("netDue",number_format($this->netDue["Annual"],2));

		$this->formArray["totalNetDue"] += $this->netDue["Annual"];
	}

	function getPaymentHistory($dueArrayList="",$backtaxTDID=""){
		$condition = " WHERE status='' AND (";
		if(!is_array($dueArrayList)){
			$condition .= " backtaxTDID = '".$backtaxTDID."' ";
		}
		else{
			$dueCount = 0;
			foreach($dueArrayList as $due){
				if($dueCount > 0){
					$condition .= " OR";
				}
				$condition .= " dueID='".$due->getDueID()."'";
				$dueCount++;
			}
		}
		$condition .= " ) ";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";

		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			false;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				false;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				return $paymentRecords;
			}
		}
	}

	function getBacktaxQuarterPaymentHistory($backtaxTDID,$btDueType){
		$condition = " WHERE status='' ";
		$condition .= " AND backtaxTDID = '".$backtaxTDID."' ";
		$condition .= " AND dueType LIKE '".$btDueType."'";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";

		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			false;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				false;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				return $paymentRecords;
			}
		}
	}

	function displayTotalPaid(){
		// establish paymentrecords condition

		$this->formArray["totalPaid"] = 0;

		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		$CollectionList = new SoapObject(NCCBIZ."CollectionList.php","urn:Object");

		if(!$xmlStr = $PaymentList->getPaymentListFromIDArrays($this->dueIDArray,$this->backtaxTDIDArray,"")){
			// error xmlStr
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				// error domDoc
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);
				$paymentArrayList = $paymentRecords->getArrayList();

				foreach($paymentArrayList as $payment){
					$this->paymentIDArray[] = $payment->getPaymentID();
				}

				if(!$xmlStr = $CollectionList->getCollectionListFromIDArrays($this->paymentIDArray,"")){
					// error xmlStr
				}
				else{
					if(!$domDoc = domxml_open_mem($xmlStr)){
						// error domDoc
					}
					else{
						$collectionRecords = new CollectionRecords;
						$collectionRecords->parseDomDocument($domDoc);
						$collectionArrayList = $collectionRecords->getArrayList();

						foreach($collectionArrayList as $collection){
							$this->formArray["totalPaid"] += $collection->getAmountPaid();
						}

					}
				}
			}
		}


		$this->tpl->set_var("totalPaid",number_format($this->formArray["totalPaid"],2));

		unset($this->paymentIDArray);
		unset($this->dueIDArray);
		unset($this->backtaxTDIDArray);
	}

	function getTreasurySettings(){
		$treasurySettings = new TreasurySettings;
		$treasurySettings->selectRecord();
		return $treasurySettings;
	}

	function getPenaltyLUTArray(){
		$treasurySettings = $this->getTreasurySettings();
		$penaltyLUTArray = $treasurySettings->getPenaltyLUT();
		$this->formArray["penaltyLUTArray"] = $treasurySettings->getPenaltyLUT();
		return $penaltyLUTArray;
	}


	function displayOwnerList($domDoc){
		$owner = new Owner;
		$owner->parseDomDocument($domDoc);
		//$list = $owner->getArrayList();
		//foreach ($list as $key => $value){
			if (count($owner->personArray)){
				$this->tpl->set_block("rptsTemplate", "PersonDBEmpty", "PersonDBEmptyBlock");
				$this->tpl->set_var("PersonDBEmptyBlock", "");
				$this->tpl->set_block("rptsTemplate", "PersonList", "PersonListBlock");
				foreach($owner->personArray as $personKey =>$personValue){
					$this->tpl->set_var("personID", $personValue->getPersonID());
					if (!$pname = $personValue->getFullName()){
						$pname = "none";
					}
					$this->tpl->set_var("fullName", $pname);
					$this->tpl->set_var("tin", $personValue->getTin());
					$this->tpl->set_var("telephone", $personValue->getTelephone());
					$this->tpl->set_var("mobileNumber", $personValue->getMobileNumber());
					$this->tpl->parse("PersonListBlock", "PersonList", true);
				}
			}
			else{
				$this->tpl->set_block("rptsTemplate", "PersonList", "PersonListBlock");
				$this->tpl->set_var("PersonListBlock", "");
			}
			if (count($owner->companyArray)){
				$this->tpl->set_block("rptsTemplate", "CompanyDBEmpty", "CompanyDBEmptyBlock");
				$this->tpl->set_var("CompanyDBEmptyBlock", "");
				$this->tpl->set_block("rptsTemplate", "CompanyList", "CompanyListBlock");
				//print_r($value->companyArray);
				foreach ($owner->companyArray as $companyKey => $companyValue){
					$this->tpl->set_var("companyID", $companyValue->getCompanyID());
					if (!$cname = $companyValue->getCompanyName()){
						$cname = "none";
					}
					$this->tpl->set_var("companyName", $cname);
					$this->tpl->set_var("tin", $companyValue->getTin());
					$this->tpl->set_var("telephone", $companyValue->getTelephone());
					$this->tpl->set_var("fax", $companyValue->getFax());
					$this->tpl->parse("CompanyListBlock", "CompanyList", true);
				}
			}
			else{
				$this->tpl->set_block("rptsTemplate", "CompanyList", "CompanyListBlock");
				$this->tpl->set_var("CompanyListBlock", "");	
			}
		//}
	}

	function getPrecedingTDArray($td){
		$ODDetails = new SoapObject(NCCBIZ."ODDetails.php", "urn:Object");

		if($this->formArray["odID"] = $ODDetails->getOdIDFromTdID($td->getTdID())){
			$ODHistoryList = new SoapObject(NCCBIZ."ODHistoryList.php", "urn:Object");
			$ODHistoryRecords = new ODHistoryRecords;

			if(!$xmlStr = $ODHistoryList->getPrecedingODList($this->formArray["odID"])){
				// do nothing. no preceding OD
				return false;
			}
			else{
				if(!$domDoc = domxml_open_mem($xmlStr)){
					// no nothing. no preceding OD
					return false;
				}
				else{
					$ODHistoryRecords->parseDomDocument($domDoc);
					$precedingODList = $ODHistoryRecords->arrayList;

					$AFSEncode = new SoapObject(NCCBIZ."AFSEncode.php", "urn:Object");
					$AFSDetails = new SoapObject(NCCBIZ."AFSDetails.php", "urn:Object");
					$TDDetails = new SoapObject(NCCBIZ."TDDetails.php", "urn:Object");

					foreach($precedingODList as $key => $odHistory){
						$previousODID = $odHistory->getPreviousODID();
						$previousAFSID = $AFSEncode->getAfsID($previousODID);
						$previousAFSxml = $AFSDetails->getAfs($previousAFSID);
						$previousAFSdomDoc = domxml_open_mem($previousAFSxml);
						$previousAFS = new AFS;
						$previousAFS->parseDomDocument($previousAFSdomDoc);

						$precedingTDxml = $TDDetails->getTDFromAfsID($previousAFSID);
						$precedingTDdomDoc = domxml_open_mem($precedingTDxml);
						$precedingTD = new TD;
						$precedingTD->parseDomDocument($precedingTDdomDoc);
						$this->precedingTDArray[] = $precedingTD;
					}
				}
				return $this->precedingTDArray;
			}
		}
	}

	function btCalculateQuarterlyPenalty($penaltyComputeDate,$totalTax,$dueType,$dueDate){
		$penalty = 0;
		$penaltyLUTArray = $this->getPenaltyLUTArray();
		if(strtotime($penaltyComputeDate) > strtotime($dueDate)){
			$numYears = date("Y",strtotime($penaltyComputeDate)) - date("Y",strtotime($dueDate));
			$numMonths = date("n",strtotime($penaltyComputeDate)) - date("n",strtotime($dueDate));
			$totalMonths = $this->btCalculateMonthOverDue(date("Y-m-d",strtotime($penaltyComputeDate)),$dueDate,$dueType);
			if($totalMonths >= count($penaltyLUTArray)){
				$penaltyPercentage = 0.72;
			}
			else{			
				$penaltyPercentage = $penaltyLUTArray[$totalMonths];
			}
			$penalty = un_number_format($totalTax) * $penaltyPercentage;
		}
		return $penalty;
	}

	function btCalculateMonthOverDue($today,$dueDate,$dueType){
		$startYear = date("Y",strtotime($dueDate));
		$quarterArr = array("Annual"=>1,"Q1"=>1,"Q2"=>2,"Q3"=>3,"Q4"=>4); 
		$startQuarter = $quarterArr[$dueType];
		$today = strtotime($today);
		$yearCalc = date("Y",$today);
		$monthCalc = date("n",$today);
		$penalty = 0;
		$yearDiff = $yearCalc - $startYear;
		$months = 0;

		if ($yearDiff <= 3 && $yearDiff > 0){
			$months = ($yearDiff * ((4-($startQuarter-1))*3)) + $monthCalc;
		}
		else{
			$months = $monthCalc;
		}

		$months = ($yearDiff * 12) + ($monthCalc - (($startQuarter-1)*3));

		return $months;
	}

	function getDueIDFromBacktaxTDID($backtaxTDID,$dueType){
		$DueDetails = new SoapObject(NCCBIZ."DueDetails.php", "urn:Object");
		if($dueID = $DueDetails->getDueIDFromBacktaxTDID($backtaxTDID,$dueType)){
			return $dueID;			
		}
		return false;
	}

	function displayBacktaxTD($tdID){
		$BacktaxTDDetails = new SoapObject(NCCBIZ."BacktaxTDDetails.php", "urn:Object");
		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		$CollectionList = new SoapObject(NCCBIZ."CollectionList.php","urn:Object");

		if (!$xmlStr = $BacktaxTDDetails->getBacktaxTDList($tdID)){
			$this->tpl->set_block("TDList", "BacktaxesTable", "BacktaxesTableBlock");
			$this->tpl->set_var("BacktaxesTableBlock", "no backtaxes");
			$this->tpl->set_var("BacktaxesListBlock", "");
			$this->tpl->set_var("JSBacktaxesListBlock", "");
		}
		else{
			//echo $xmlStr;
			if(!$domDoc = domxml_open_mem($xmlStr)) {
				$this->tpl->set_block("TDList", "BacktaxesTable", "BacktaxesTableBlock");
				$this->tpl->set_var("BacktaxesTableBlock", "no backtaxes");
				$this->tpl->set_var("BacktaxesListBlock", "");
				$this->tpl->set_var("JSBacktaxesListBlock", "");
			}
			else {
				$backtaxTDRecords = new BacktaxTDRecords;
				$backtaxTDRecords->parseDomDocument($domDoc);

				$backTaxTDList = $backtaxTDRecords->getArrayList();
				//$this->tpl->set_block("TDList", "BacktaxesList", "BacktaxesListBlock");
				//$this->tpl->set_block("rptsTemplate", "BacktaxesDBEmpty", "BacktaxesDBEmptyBlock");
				if(count($backTaxTDList)){
					//$this->tpl->set_var("BacktaxesDBEmptyBlock", "");
					foreach ($backTaxTDList as $key => $val){
						// get paymentHistory

						$defaultDueType = "Annual";
						$allowableDueTypesArray = array("Annual","Q1","Q2","Q3","Q4");

						/* alxjvr 2006.03.22
						if(!$paymentHistory = $this->getPaymentHistory("",$val->getBacktaxTDID())){
							$defaultDueType = "Annual";
							$allowableDueTypesArray = array("Annual","Q1");
						}
						else{
							$defaultDueType = $paymentHistory->arrayList[0]->getDueType();
							if($defaultDueType=="Annual"){
								$allowableDueTypesArray = array("Annual");
							}
							else{
								switch($defaultDueType){
									case "Q1":
										$allowableDueTypesArray = array("Q1", "Q2");
										break;
									case "Q2":
										$allowableDueTypesArray = array("Q2", "Q3");
										break;
									case "Q3":
										$allowableDueTypesArray = array("Q3", "Q4");
										break;
									case "Q4":
										$allowableDueTypesArray = array("Q4");
										break;
								}
							}
						}
						*/

						// check Paid values from Payments and increment to paid value in BacktaxTD
						$condition = " WHERE status='' ";
						$condition .= " AND backtaxTDID = '".$val->getBacktaxTDID()."'";
						$condition .= " ORDER BY paymentDate DESC, paymentID DESC ";
						if(!$xmlStr = $PaymentList->getPaymentList($condition)){
							// error xmlStr. do nothing
						}
						else{
							if(!$domDoc = domxml_open_mem($xmlStr)){
								// error domDoc. do nothing
							}
							else{
								$paymentRecords = new PaymentRecords;
								$paymentRecords->parseDomDocument($domDoc);
								$paymentArrayList = $paymentRecords->getArrayList();
								$collectionCondition = "WHERE status='' AND (";
								$paymentCount = 0;
								foreach($paymentArrayList as $payment){
									if($paymentCount>0){
										$collectionCondition .= " OR";
									}
									else{
										$lastPaymentDate = $payment->getPaymentDate();
										$lastPaymentDueType = $payment->getDueType();
										$amnestyStatus = $payment->getAmnesty();
									}
									$collectionCondition .= " paymentID = '".$payment->getPaymentID()."' ";
									$paymentCount++;
								}
								$collectionCondition .= ") ";
								
								if(!$xmlStr = $CollectionList->getCollectionList($collectionCondition)){
									// error xmlStr
								}
								else{
									if(!$domDoc = domxml_open_mem($xmlStr)){
										// error domDoc
									}
									else{
										$collectionRecords = new CollectionRecords;
										$collectionRecords->parseDomDocument($domDoc);
										$collectionArrayList = $collectionRecords->getArrayList();
										$totalPaid = 0;
										foreach($collectionArrayList as $collection){
											$totalPaid += $collection->getAmountPaid();
										}
										$totalPaid += $val->getPaid();

										// to avoid -0.01, 0.01 balances
										if($totalPaid!=$payment->getAmountPaid()) $totalPaid = $payment->getAmountPaid();

										$val->setPaid($totalPaid);
									}
								}
							}
						}

						if($amnestyStatus=="true"){
							$this->tpl->set_var("btAmnesty_status", "checked");
						}
						else{
							$this->tpl->set_var("btAmnesty_status", "");
						}

						// calculate Penalties verses either today or verses the last paymentDate

						$treasurySettings = $this->getTreasurySettings();
						$btDueDate = date("M. d, Y",strtotime($val->getStartYear()."-".$treasurySettings->getAnnualDueDate()));

						if($lastPaymentDate!=""){
							$val->calculatePenalty($lastPaymentDate);
							// if balance is 0 leave penalty as is, otherwise calculatePenalty according to date now
							$balanceB = $val->getTotalTaxDue();
							$penaltyLUTArray = $this->getPenaltyLUTArray();

							switch($lastPaymentDueType){
								case "Q1":
									$btDueDate = date("M. d, Y",strtotime($val->getStartYear()."-03-31"));
									$btBasicTax = roundUpNearestFiveCent($val->getBasicTax()/4);
									$btSefTax = roundUpNearestFiveCent($val->getSEFTax()/4);
									$btIdleTax = roundUpNearestFiveCent($val->getIdleTax()/4);
									$btTaxDue = $btBasicTax + $btSefTax + $btIdleTax;
									$btPenalty = $this->btCalculateQuarterlyPenalty($lastPaymentDate,$btTaxDue,$lastPaymentDueType,$btDueDate);
									$balanceB = ($btTaxDue + $btPenalty) - $totalPaid;
									break;
								case "Q2":
									$btDueDate = date("M. d, Y",strtotime($val->getStartYear()."-06-30"));
									$btBasicTax = roundUpNearestFiveCent($val->getBasicTax()/4);
									$btSefTax = roundUpNearestFiveCent($val->getSEFTax()/4);
									$btIdleTax = roundUpNearestFiveCent($val->getIdleTax()/4);
									$btTaxDue = $btBasicTax + $btSefTax + $btIdleTax;
									$btPenalty = $this->btCalculateQuarterlyPenalty($lastPaymentDate,$btTaxDue,$lastPaymentDueType,$btDueDate);
									$balanceB = ($btTaxDue + $btPenalty) - $totalPaid;
									break;
								case "Q3":
									$btDueDate = date("M. d, Y",strtotime($val->getStartYear()."-09-30"));
									$btBasicTax = roundUpNearestFiveCent($val->getBasicTax()/4);
									$btSefTax = roundUpNearestFiveCent($val->getSEFTax()/4);
									$btIdleTax = roundUpNearestFiveCent($val->getIdleTax()/4);
									$btTaxDue = $btBasicTax + $btSefTax + $btIdleTax;
									$btPenalty = $this->btCalculateQuarterlyPenalty($lastPaymentDate,$btTaxDue,$lastPaymentDueType,$btDueDate);
									$balanceB = ($btTaxDue + $btPenalty) - $totalPaid;
									break;
								case "Q4":
									$btDueDate = date("M. d, Y",strtotime($val->getStartYear()."-12-31"));
									$btBasicTax = roundUpNearestFiveCent($val->getBasicTax()/4);
									$btSefTax = roundUpNearestFiveCent($val->getSEFTax()/4);
									$btIdleTax = roundUpNearestFiveCent($val->getIdleTax()/4);
									$btTaxDue = $btBasicTax + $btSefTax + $btIdleTax;
									$btPenalty = $this->btCalculateQuarterlyPenalty($lastPaymentDate,$btTaxDue,$lastPaymentDueType,$btDueDate);
									$balanceB = ($btTaxDue + $btPenalty) - $totalPaid;
									break;
							}

							$penaltyComputeDate = $lastPaymentDate;

							// 0.1 is used instead of 0 because sometimes rounded off balances intended to be 0 end up appearing as 0.002 or so...
							if(round($balanceB,4) > 0.1){
								$val->calculatePenalty($this->now);
								$penaltyComputeDate = $this->now;

								$btBasicTax = $val->getBasicTax();
								$btSefTax = $val->getSEFTax();
								$btIdleTax = $val->getIdleTax();
								$btTaxDue = $val->getBasicTax + $val->getSEFTax() + $val->getIdleTax();
								$btPenalty = $val->getPenalties();
								$btDueDate = date("M. d, Y",strtotime($val->getStartYear()."-".$treasurySettings->getAnnualDueDate()));
							}
						}
						else{
							$val->calculatePenalty($this->now);
							$penaltyComputeDate = $this->now;

							$btBasicTax = $val->getBasicTax();
							$btSefTax = $val->getSEFTax();
							$btIdleTax = $val->getIdleTax();
							$btTaxDue = $val->getBasicTax + $val->getSEFTax() + $val->getIdleTax();
							$btPenalty = $val->getPenalties();
							$btDueDate = date("M. d, Y",strtotime($val->getStartYear()."-".$treasurySettings->getAnnualDueDate()));
						}

						// if Backtax Penalties is greater than 0, allow only "Annual" payments
						$defaultDueType = "Annual";
						$allowableDueTypesArray = array("Annual","Q1","Q2","Q3","Q4");

						/* alxjvr 2006.03.22
						if($btPenalty>0){
							$defaultDueType = "Annual";
							$allowableDueTypesArray = array("Annual");
						}
						*/

						foreach($allowableDueTypesArray as $allowableDueType){
							$this->tpl->set_var("backtaxAllowableDueType",$allowableDueType);
							$this->tpl->parse("BacktaxDueTypeListBlock", "BacktaxDueTypeList", true);
						}

						$this->tpl->set_var("backtaxTDID", $val->getBacktaxTDID());

						$this->tpl->set_var("tdNumberB", $val->getTdNumber());
						$this->tpl->set_var("startYearB", $val->getStartYear());
						$this->tpl->set_var("endYearB", $val->getEndYear());
						$this->tpl->set_var("startQuarterB", $val->getStartQuarter());
						$this->tpl->set_var("assessedValueB", number_format($val->getAssessedValue(),2));
						$this->tpl->set_var("basicRateB", number_format($val->getBasicRate(),2));
						$this->tpl->set_var("sefRateB", number_format($val->getSEFRate(),2));

						$this->tpl->set_var("basicTaxB", number_format($val->getBasicTax(),2));
						$this->tpl->set_var("sefTaxB", number_format($val->getSEFTax(),2));
						$this->tpl->set_var("idleTaxB", number_format($val->getIdleTax(),2));
						$this->tpl->set_var("penaltiesB", number_format($val->getPenalties(),2));
						$this->tpl->set_var("paidB", number_format($val->getPaid(),2));
						$this->tpl->set_var("balanceB", number_format($val->getTotalTaxDue(),2));
						$this->tpl->set_var("totalTaxDueB", number_format($val->getTotal(),2));

						$btDueTypeArray = array(1 => "Q1", 2=> "Q2", 3=>"Q3", 4=>"Q4");

						// set default backtax values based on latest payments

						$this->tpl->set_var("btDueDate", $btDueDate);

						$this->tpl->set_var("btBasicTax", formatCurrency($btBasicTax));
						$this->tpl->set_var("btSefTax", formatCurrency($btSefTax));
						$this->tpl->set_var("btIdleTax", formatCurrency($btIdleTax));
						$this->tpl->set_var("btTaxDue", formatCurrency($btTaxDue));
						$this->tpl->set_var("btAdvancedPaymentDiscount", formatCurrency(0));
						$this->tpl->set_var("btEarlyPaymentDiscount", formatCurrency(0));
						$this->tpl->set_var("btPenalty", formatCurrency($btPenalty));
						$this->tpl->set_var("btTotalPaid", formatCurrency($val->getPaid()));

						$btBalance = $val->getTotalTaxDue();
						if($btBalance<0) $btBalance = 0;
						else if($btBalance=="-0") $btBalance = 0;

						$this->tpl->set_var("btBalance", formatCurrency($btBalance));

						$this->tpl->set_var("btDueID[Annual]", $this->getDueIDFromBacktaxTDID($val->getBacktaxTDID(),"Annual"));
						$this->tpl->set_var("btDueID[Q1]", $this->getDueIDFromBacktaxTDID($val->getBacktaxTDID(),"Q1"));
						$this->tpl->set_var("btDueID[Q2]", $this->getDueIDFromBacktaxTDID($val->getBacktaxTDID(),"Q2"));
						$this->tpl->set_var("btDueID[Q3]", $this->getDueIDFromBacktaxTDID($val->getBacktaxTDID(),"Q3"));
						$this->tpl->set_var("btDueID[Q4]", $this->getDueIDFromBacktaxTDID($val->getBacktaxTDID(),"Q4"));

						$this->tpl->set_var("btDueDate[Annual]", date("M. d, Y",strtotime($val->getStartYear()."-".$treasurySettings->getAnnualDueDate())));
						$this->tpl->set_var("btDueDate[Q1]", date("M. d, Y",strtotime($val->getStartYear()."-03-31")));
						$this->tpl->set_var("btDueDate[Q2]", date("M. d, Y",strtotime($val->getStartYear()."-06-30")));

						$this->tpl->set_var("btDueDate[Q3]", date("M. d, Y",strtotime($val->getStartYear()."-09-30")));
						$this->tpl->set_var("btDueDate[Q4]", date("M. d, Y",strtotime($val->getStartYear()."-12-31")));

						$this->tpl->set_var("btBasicTax[Annual]", formatCurrency($val->getBasicTax()));

						$this->tpl->set_var("btBasicTax[Q1]", roundUpNearestFiveCent($val->getBasicTax()/4));
						$this->tpl->set_var("btBasicTax[Q2]", roundUpNearestFiveCent(un_number_format($val->getBasicTax()/4)));
						$this->tpl->set_var("btBasicTax[Q3]", roundUpNearestFiveCent(un_number_format($val->getBasicTax()/4)));
						$this->tpl->set_var("btBasicTax[Q4]", roundUpNearestFiveCent(un_number_format($val->getBasicTax()/4)));

						$this->tpl->set_var("btSefTax[Annual]", formatCurrency($val->getSEFTax()));
						$this->tpl->set_var("btSefTax[Q1]", roundUpNearestFiveCent(un_number_format($val->getSEFTax()/4)));
						$this->tpl->set_var("btSefTax[Q2]", roundUpNearestFiveCent(un_number_format($val->getSEFTax()/4)));
						$this->tpl->set_var("btSefTax[Q3]", roundUpNearestFiveCent(un_number_format($val->getSEFTax()/4)));
						$this->tpl->set_var("btSefTax[Q4]", roundUpNearestFiveCent(un_number_format($val->getSEFTax()/4)));

						$this->tpl->set_var("btIdleTax[Annual]", formatCurrency($val->getIdleTax()));
						$this->tpl->set_var("btIdleTax[Q1]", roundUpNearestFiveCent(un_number_format($val->getIdleTax()/4)));
						$this->tpl->set_var("btIdleTax[Q2]", roundUpNearestFiveCent(un_number_format($val->getIdleTax()/4)));
						$this->tpl->set_var("btIdleTax[Q3]", roundUpNearestFiveCent(un_number_format($val->getIdleTax()/4)));
						$this->tpl->set_var("btIdleTax[Q4]", roundUpNearestFiveCent(un_number_format($val->getIdleTax()/4)));

						$this->tpl->set_var("btTaxDue[Annual]", formatCurrency($val->getBasicTax()+$val->getSEFTax()+$val->getIdleTax()));
						$this->tpl->set_var("btTaxDue[Q1]", formatCurrency(un_number_format($this->tpl->get_var("btBasicTax[Q1]"))+un_number_format($this->tpl->get_var("btSefTax[Q1]"))+un_number_format($this->tpl->get_var("btIdleTax[Q1]"))));
						$this->tpl->set_var("btTaxDue[Q2]", formatCurrency(un_number_format($this->tpl->get_var("btBasicTax[Q2]"))+un_number_format($this->tpl->get_var("btSefTax[Q2]"))+un_number_format($this->tpl->get_var("btIdleTax[Q2]"))));
						$this->tpl->set_var("btTaxDue[Q3]", formatCurrency(un_number_format($this->tpl->get_var("btBasicTax[Q3]"))+un_number_format($this->tpl->get_var("btSefTax[Q3]"))+un_number_format($this->tpl->get_var("btIdleTax[Q3]"))));
						$this->tpl->set_var("btTaxDue[Q4]", formatCurrency(un_number_format($this->tpl->get_var("btBasicTax[Q4]"))+un_number_format($this->tpl->get_var("btSefTax[Q4]"))+un_number_format($this->tpl->get_var("btIdleTax[Q4]"))));

						$this->tpl->set_var("btAdvancedPaymentDiscount[Annual]", formatCurrency(0));
						$this->tpl->set_var("btAdvancedPaymentDiscount[Q1]", formatCurrency(0));
						$this->tpl->set_var("btAdvancedPaymentDiscount[Q2]", formatCurrency(0));
						$this->tpl->set_var("btAdvancedPaymentDiscount[Q3]", formatCurrency(0));
						$this->tpl->set_var("btAdvancedPaymentDiscount[Q4]", formatCurrency(0));

						$this->tpl->set_var("btEarlyPaymentDiscount[Annual]", formatCurrency(0));
						$this->tpl->set_var("btEarlyPaymentDiscount[Q1]", formatCurrency(0));
						$this->tpl->set_var("btEarlyPaymentDiscount[Q2]", formatCurrency(0));
						$this->tpl->set_var("btEarlyPaymentDiscount[Q3]", formatCurrency(0));
						$this->tpl->set_var("btEarlyPaymentDiscount[Q4]", formatCurrency(0));

						$this->tpl->set_var("btPenalty[Annual]", formatCurrency($val->getPenalties()));

						// compute quarterly penalties
						$penaltyLUTArray = $this->getPenaltyLUTArray();

						foreach($btDueTypeArray as $btDueTypeKey => $btDueType){
							$btPenalty = 0;

							if($lastPaymentDueType!=""){
								if($btDueTypeKey > substr($lastPaymentDueType,1,1)){
									$penaltyComputeDate = $this->now;
								}
							}

							if(strtotime($penaltyComputeDate) > strtotime($this->tpl->get_var("btDueDate[".$btDueType."]"))){
								$numYears = date("Y",strtotime($penaltyComputeDate)) - date("Y",strtotime($this->tpl->get_var("btDueDate[".$btDueType."]")));
								$numMonths = date("n",strtotime($penaltyComputeDate)) - date("n",strtotime($this->tpl->get_var("btDueDate[".$btDueType."]")));
								$totalMonths = $this->btCalculateMonthOverDue(date("Y-m-d",strtotime($penaltyComputeDate)),$this->tpl->get_var("btDueDate[".$btDueType."]"),$btDueType);
								if($totalMonths >= count($penaltyLUTArray)){
									$penaltyPercentage = 0.72;
								}
								else{			
									$penaltyPercentage = $penaltyLUTArray[$totalMonths];
								}
								$btPenalty = un_number_format($this->tpl->get_var("btTaxDue[".$btDueType."]")) * $penaltyPercentage;
							}
							$this->tpl->set_var("btPenalty[".$btDueType."]",formatCurrency($btPenalty));
						}

						// get totalPaid for backtax

						$totalPaid = 0;
						if($paymentHistory = $this->getPaymentHistory("",$val->getBacktaxTDID())){
							$paymentArrayList = $paymentHistory->getArrayList();
							foreach($paymentArrayList as $payment){
								$totalPaid += $payment->getAmountPaid();
							}
							$this->tpl->set_var("btTotalPaid[Annual]",formatCurrency($totalPaid));
						}
						else{
							$this->tpl->set_var("btTotalPaid[Annual]",formatCurrency(0));
						}

						// get quarterlyPayments for backtax
						$totalPaidForQuarter=0;
						foreach($btDueTypeArray as $btDueType){
							if($btQuarterPaymentHistory = $this->getBacktaxQuarterPaymentHistory($val->getBacktaxTDID(),$btDueType)){
								$btQuarterPaymentArrayList = $btQuarterPaymentHistory->getArrayList();
								foreach($btQuarterPaymentArrayList as $payment){
									$totalPaidForQuarter += $payment->getAmountPaid();
								}
								$this->tpl->set_var("btTotalPaid[".$btDueType."]",formatCurrency($totalPaidForQuarter));
								if($lastPaymentDueType==$btDueType){
									$this->tpl->set_var("btTotalPaid", formatCurrency($totalPaidForQuarter));
								}
								$totalPaidForQuarter = 0;
							}
							else{
								$this->tpl->set_var("btTotalPaid[".$btDueType."]",formatCurrency(0));
							}
						}

						// set default backtax values based on latest payments

						if($defaultDueType!="Annual"){
							$this->tpl->set_var("btBasicTax", $this->tpl->get_var("btBasicTax[".$defaultDueType."]"));
							$this->tpl->set_var("btSefTax", $this->tpl->get_var("btSefTax[".$defaultDueType."]"));
							$this->tpl->set_var("btIdleTax", $this->tpl->get_var("btIdleTax[".$defaultDueType."]"));
							$this->tpl->set_var("btTaxDue", $this->tpl->get_var("btTaxDue[".$defaultDueType."]"));
							$this->tpl->set_var("btAdvancedPaymentDiscount", formatCurrency(0));
							$this->tpl->set_var("btEarlyPaymentDiscount", formatCurrency(0));
							$this->tpl->set_var("btPenalty", $this->tpl->get_var("btPenalty[".$defaultDueType."]"));
							$this->tpl->set_var("btTotalPaid", $this->tpl->get_var("btTotalPaid[".$defaultDueType."]"));

							$btBalance = un_number_format($this->tpl->get_var("btTaxDue"));
							if($this->tpl->get_var("btAdvancedPaymentDiscount") > 0){
								$btBalance = un_number_format($this->tpl->get_var("btTaxDue")) - un_number_format($this->tpl->get_var("btAdvancedPaymentDiscount"));
							}
							else if($this->tpl->get_var("btEarlyPaymentDiscount") > 0){
								$btBalance = un_number_format($this->tpl->get_var("btTaxDue")) - un_number_format($this->tpl->get_var("btEarlyPaymentDiscount"));
							}

							$btBalance += un_number_format($this->tpl->get_var("btPenalty"));
							$btBalance -= un_number_format($this->tpl->get_var("btTotalPaid"));

							$this->tpl->set_var("btBalance", formatCurrency($btBalance));
						}
						else{
							$this->tpl->set_var("btBasicTax", formatCurrency($val->getBasicTax()));
							$this->tpl->set_var("btSefTax", formatCurrency($val->getSEFTax()));
							$this->tpl->set_var("btIdleTax", formatCurrency($val->getIdleTax()));
							$this->tpl->set_var("btTaxDue", formatCurrency($val->getBasicTax()+$val->getSEFTax()+$val->getIdleTax()));
							$this->tpl->set_var("btAdvancedPaymentDiscount", formatCurrency(0));
							$this->tpl->set_var("btEarlyPaymentDiscount", formatCurrency(0));
							$this->tpl->set_var("btPenalty", formatCurrency($val->getPenalties()));
							$this->tpl->set_var("btTotalPaid", formatCurrency($val->getPaid()));

							$this->tpl->set_var("btBalance", formatCurrency($val->getTotalTaxDue()));
						}
	
						$this->backtaxTDIDArray[] = $val->getBacktaxTDID();

						$this->tpl->parse("BacktaxesListBlock", "BacktaxesList", true);
						$this->tpl->parse("JSBacktaxesListBlock", "JSBacktaxesList", true);
						$this->tpl->set_var("BacktaxDueTypeListBlock","");

					}
					
				}
				else{
					//$this->tpl->set_block("TDList", "Backtax", "BacktaxBlock");
					//$this->tpl->set_var("BacktaxBlock", "");
				}
			}
		}
	}
	
	function getLatestPaymentDateForDue($due){
		$condition = " WHERE status='' AND (";
		$condition .= " dueID='".$due->getDueID()."'";
		
		$condition .= " ) ";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";


		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			return $this->now;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				return $this->now;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				$paymentArrayList = $paymentRecords->getArrayList();
				$paymentCount=0;
				foreach($paymentArrayList as $payment){
					return $payment->getPaymentDate();
				}
			}
		}
	}	

	function getLatestPaymentDate($dueArrayList){
		$condition = " WHERE status='' AND (";
		$dueCount = 0;
		foreach($dueArrayList as $due){
			if($dueCount > 0){
				$condition .= " OR";
			}
			$condition .= " dueID='".$due->getDueID()."'";
			$dueCount++;
		}
		$condition .= " ) ";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";


		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			return $this->now;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				return $this->now;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				$paymentArrayList = $paymentRecords->getArrayList();
				$paymentCount=0;
				foreach($paymentArrayList as $payment){
					return $payment->getPaymentDate();
				}
			}
		}
	}

	function getAmnestyStatusForDue($dueArrayList){
		$condition = " WHERE status='' AND (";
		$dueCount = 0;
		foreach($dueArrayList as $due){
			if($dueCount > 0){
				$condition .= " OR";
			}
			$condition .= " dueID='".$due->getDueID()."'";
			$dueCount++;
		}
		$condition .= ")";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";

		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			return false;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				return false;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				$paymentArrayList = $paymentRecords->getArrayList();
				$paymentCount=0;
				foreach($paymentArrayList as $payment){
					if($payment->getAmnesty()=="true"){
						return true;
					}
				}
			}
		}
		return false;
	}

	function getAmountPaidForDueID($dueID){
		$condition = " WHERE ";
		$condition .= " status = '' ";
		$condition .= " AND dueID='".$dueID."'";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";

		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		$amountPaidForDue = 0;
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			return 0;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				return 0;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				$paymentArrayList = $paymentRecords->getArrayList();
				foreach($paymentArrayList as $payment){
					$amountPaidForDue += $payment->getAmountPaid();
				}
			}
		}
		return $amountPaidForDue;
	}
	
function getTotalAdvancedPaymentDiscountForDue($dueArrayList){
		$condition = " WHERE status = '' AND (";
		$dueCount = 0;
		foreach($dueArrayList as $due){
			if($dueCount > 0){
				$condition .= " OR";
			}
			$condition .= " dueID='".$due->getDueID()."'";
			$dueCount++;
		}
		$condition .= ")";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";

		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		$totalAdvancedPaymentDiscount = 0;
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			return 0;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				return 0;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				$paymentArrayList = $paymentRecords->getArrayList();
				foreach($paymentArrayList as $payment){
					$totalAdvancedPaymentDiscount += $payment->getAdvancedPaymentDiscount();
				}
			}
		}
		return $totalAdvancedPaymentDiscount;
	}
	
	function getTotalAdvancedPaymentDiscountForDueType($due){
		$condition = " WHERE status = '' AND (";
		$condition .= " dueID='".$due->getDueID()."'";
		$condition .= ")";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";

		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		$totalAdvancedPaymentDiscount = 0;
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			return 0;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				return 0;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				$paymentArrayList = $paymentRecords->getArrayList();
				foreach($paymentArrayList as $payment){
					$totalAdvancedPaymentDiscount += $payment->getAdvancedPaymentDiscount();
				}
			}
		}
		return $totalAdvancedPaymentDiscount;
	}	

	function getTotalEarlyPaymentDiscountForDue($dueArrayList){
		$condition = " WHERE status = '' AND (";
		$dueCount = 0;
		foreach($dueArrayList as $due){
			if($dueCount > 0){
				$condition .= " OR";
			}
			$condition .= " dueID='".$due->getDueID()."'";
			$dueCount++;
		}
		$condition .= ")";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";

		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		$totalEarlyPaymentDiscount = 0;
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			return 0;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				return 0;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				$paymentArrayList = $paymentRecords->getArrayList();
				foreach($paymentArrayList as $payment){
					$totalEarlyPaymentDiscount += $payment->getEarlyPaymentDiscount();
				}
			}
		}
		return $totalEarlyPaymentDiscount;
	}

	function getTotalEarlyPaymentDiscountForDueType($due){
		$condition = " WHERE status = '' AND (";
		$condition .= " dueID='".$due->getDueID()."'";
		$condition .= ")";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";

		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		$totalEarlyPaymentDiscount = 0;
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			return 0;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				return 0;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				$paymentArrayList = $paymentRecords->getArrayList();
				foreach($paymentArrayList as $payment){
					$totalEarlyPaymentDiscount += $payment->getEarlyPaymentDiscount();
				}
			}
		}
		return $totalEarlyPaymentDiscount;
	}

	function getAmountPaidForDue($dueArrayList){
		$condition = " WHERE status = '' AND (";
		$dueCount = 0;
		foreach($dueArrayList as $due){
			if($dueCount > 0){
				$condition .= " OR";
			}
			$condition .= " dueID='".$due->getDueID()."'";
			$dueCount++;
		}
		$condition .= ")";
		$condition .= " ORDER BY paymentDate DESC, paymentID DESC";

		$PaymentList = new SoapObject(NCCBIZ."PaymentList.php","urn:Object");
		$amountPaidForDue = 0;
		if(!$xmlStr = $PaymentList->getPaymentList($condition)){
			return 0;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				return 0;
			}
			else{
				$paymentRecords = new PaymentRecords;
				$paymentRecords->parseDomDocument($domDoc);

				$paymentArrayList = $paymentRecords->getArrayList();
				foreach($paymentArrayList as $payment){
					$amountPaidForDue += $payment->getAmountPaid();
				}
			}
		}
		return $amountPaidForDue;
	}
	
	function computePenalty($penaltyComputeDate,$due){
		// compute Penalty as of $penaltyComputeDate
		// check if today is exceeding dueDate and compute penalty

		$totalMonths=0;

		if(strtotime($penaltyComputeDate) > strtotime($due->getDueDate()." 23:59:59")){
			// count months

			// numYears = today[year] - dueDate[year]
			$numYears = date("Y",strtotime($penaltyComputeDate)) - date("Y",strtotime($due->getDueDate()));
			// numMonths = today[month] - dueDate[month]
			$numMonths = date("n",strtotime($penaltyComputeDate)) - date("n",strtotime($due->getDueDate()));
			// totalMonths = (numYears*12) + numMonths

			$totalMonths = $due->calculateMonthOverDue(date("Y-m-d",strtotime($penaltyComputeDate)));

			// associate penaltyPercentage

			$penaltyLUTArray = $this->getPenaltyLUTArray();

			if($totalMonths >= count($penaltyLUTArray)){
				$penaltyPercentage = 0.72;
			}
			else{			
				$penaltyPercentage = $penaltyLUTArray[$totalMonths];
			}

			$penalty = $due->getTaxDue() * $penaltyPercentage;

			$due->setMonthsOverDue($totalMonths);
			$due->setPenaltyPercentage($penaltyPercentage);
			$due->setPenalty($penalty);
		}
		else{
			$due->setMonthsOverDue(0);
			$due->setPenaltyPercentage(0.00);
			$due->setPenalty(0.00);
		}

		return $due;
	}

	function Main(){
		switch ($this->formArray["formAction"]){
			case "remove";
				//echo "removeOwnerRPTOP(".$this->formArray["rptopID"].",".$this->formArray["ownerID"].",".$this->formArray["personID"].",".$this->formArray["companyID"].")";
				$OwnerList = new SoapObject(NCCBIZ."OwnerList.php", "urn:Object");
				if (count($this->formArray["personID"]) || count($this->formArray["companyID"])) {
					if (!$deletedRows = $OwnerList->removeOwnerRPTOP($this->formArray["rptopID"],$this->formArray["ownerID"],$this->formArray["personID"],$this->formArray["companyID"])){
						$this->tpl->set_var("msg", "SOAP failed");
						//echo "SOAP failed";
					}
					else{
						$this->tpl->set_var("msg", $deletedRows." records deleted");
					}
				}
				else $this->tpl->set_var("msg", "0 records deleted");
				header("location: RPTOPDetails.php".$this->sess->url("").$this->sess->add_query(array("rptopID"=>$this->formArray["rptopID"])));
				exit;
				break;
			default:
				$this->tpl->set_var("msg", "");
		}//select

		$RPTOPDetails = new SoapObject(NCCBIZ."RPTOPDetails.php", "urn:Object");
		if (!$xmlStr = $RPTOPDetails->getRPTOP($this->formArray["rptopID"])){
			exit("xml failed");
		}
		else{
			//echo($xmlStr);
			if(!$domDoc = domxml_open_mem($xmlStr)) {
				$this->tpl->set_block("rptsTemplate", "OwnerListTable", "OwnerListTableBlock");
				$this->tpl->set_var("OwnerListTableBlock", "error xmlDoc");
			}
			else {
				$rptop = new RPTOP;
				$rptop->parseDomDocument($domDoc);
				//print_r($rptop);
				foreach($rptop as $key => $value){
					switch ($key){
						case "owner":
							//$RPTOPEncode = new SoapObject(NCCBIZ."RPTOPEncode.php", "urn:Object");
							if (is_a($value,"Owner")){
								$this->formArray["ownerID"] = $rptop->owner->getOwnerID();
								$xmlStr = $rptop->owner->domDocument->dump_mem(true);
								if (!$xmlStr){
									$this->tpl->set_block("rptsTemplate", "OwnerListTable", "OwnerListTableBlock");
									$this->tpl->set_var("OwnerListTableBlock", "");
								}
								else {
									if(!$domDoc = domxml_open_mem($xmlStr)) {
										$this->tpl->set_block("rptsTemplate", "OwnerListTable", "OwnerListTableBlock");
										$this->tpl->set_var("OwnerListTableBlock", "error xmlDoc");
									}
									else {
										$this->displayOwnerList($domDoc);
									}
								}
							}
							else {
								$this->tpl->set_block("rptsTemplate", "PersonList", "PersonListBlock");
								$this->tpl->set_var("PersonListBlock", "");
								$this->tpl->set_block("rptsTemplate", "CompanyList", "CompanyListBlock");
								$this->tpl->set_var("CompanyListBlock", "");
							}
						break;
						case "cityAssessor":
							if(is_numeric($value)){
								$cityAssessor = new Person;
								$cityAssessor->selectRecord($value);
								$this->tpl->set_var("cityAssessorID", $cityAssessor->getPersonID());
								$this->tpl->set_var("cityAssessorName", $cityAssessor->getFullName());
								$this->formArray["cityAssessorName"] = $cityAssessor->getFullName();
							}
							else {
								$cityAssessor = $value;
								$this->tpl->set_var("cityAssessorID", $cityAssessor);
								$this->tpl->set_var("cityAssessorName", $cityAssessor);
								$this->formArray["cityAssessorName"] = $cityAssessor;
							}
						break;
						case "cityTreasurer":
							if(is_numeric($value)){
								$cityTreasurer = new Person;
								$cityTreasurer->selectRecord($value);
								$this->tpl->set_var("cityTreasurerID", $cityTreasurer->getPersonID());
								$this->tpl->set_var("cityTreasurerName", $cityTreasurer->getFullName());
								$this->formArray["cityTreasurerName"] = $cityTreasurer->getFullName();
							}
							else {
								$cityTreasurer = $value;
								$this->tpl->set_var("cityTreasurerID", $cityTreasurer);
								$this->tpl->set_var("cityTreasurerName", $cityTreasurer);
								$this->formArray["cityTreasurerName"] = $cityTreasurer;
							}						
							break;
						case "tdArray":
							$this->tpl->set_block("rptsTemplate", "defaultTDList", "defaultTDListBlock");
							$this->tpl->set_block("rptsTemplate", "toggleTDList", "toggleTDListBlock");
							$this->tpl->set_block("rptsTemplate", "TDList", "TDListBlock");
							$this->tpl->set_block("rptsTemplate", "JSTDList", "JSTDListBlock");
							$this->tpl->set_block("TDList", "DueTypeList", "DueTypeListBlock");
							$this->tpl->set_block("TDList", "BacktaxesList", "BacktaxesListBlock");
							$this->tpl->set_block("JSTDList", "JSBacktaxesList", "JSBacktaxesListBlock");
							$this->tpl->set_block("BacktaxesList", "BacktaxDueTypeList", "BacktaxDueTypeListBlock");
							$tdCtr = 0;
							if (count($value)){
								$this->tpl->set_block("rptsTemplate", "TDDBEmpty", "TDDBEmptyBlock");
								$this->tpl->set_var("TDDBEmptyBlock", "");

								foreach($value as $tkey => $tvalue){
									$this->tpl->set_var("tdID",$tvalue->getTDID());
									$this->tpl->set_var("taxDeclarationNumber",$tvalue->getTaxDeclarationNumber());
									$this->tpl->set_var("afsID",$tvalue->getAfsID());
									$this->tpl->set_var("cancelsTDNumber",$tvalue->getCancelsTDNumber());
									$this->tpl->set_var("canceledByTDNumber",$tvalue->getCanceledByTDNumber());
									$this->tpl->set_var("taxBeginsWithTheYear",$tvalue->getTaxBeginsWithTheYear());
									$this->tpl->set_var("ceasesWithTheYear",$tvalue->getCeasesWithTheYear());
									$this->tpl->set_var("enteredInRPARForBy",$tvalue->getEnteredInRPARForBy());
									$this->tpl->set_var("enteredInRPARForYear",$tvalue->getEnteredInRPARForYear());
									$this->tpl->set_var("previousOwner",$tvalue->getPreviousOwner());
									$this->tpl->set_var("previousAssessedValue",$tvalue->getPreviousAssessedValue());
									
									list($dateArr["year"],$dateArr["month"],$dateArr["day"]) = explode("-",$tvalue->getProvincialAssessorDate());
									$this->tpl->set_var("pa_yearValue",removePreZero($dateArr["year"]));
									$this->tpl->set_var("pa_month",removePreZero($dateArr["month"]));
									$this->tpl->set_var("pa_dayValue",removePreZero($dateArr["day"]));
									list($dateArr["year"],$dateArr["month"],$dateArr["day"]) = explode("-",$tvalue->getCityMunicipalAssessorDate());
									$this->tpl->set_var("cm_yearValue",removePreZero($dateArr["year"]));
									$this->tpl->set_var("cm_month",removePreZero($dateArr["month"]));
									$this->tpl->set_var("cm_dayValue",removePreZero($dateArr["day"]));
									
									$this->tpl->set_var("provincialAssessorName",$tvalue->provincialAssessor);
									$this->tpl->set_var("cityMunicipalAssessorName",$tvalue->cityMunicipalAssessor);
									
									$this->tpl->set_var("propertyType",$tvalue->getPropertyType());

									$this->tpl->set_var("basicTax","");
									$this->tpl->set_var("sefTax", "");
									$this->tpl->set_var("total", "");
									
									$AFSDetails = new SoapObject(NCCBIZ."AFSDetails.php", "urn:Object");
									if (!$xmlStr = $AFSDetails->getAFS($tvalue->getAfsID())){
										//
									}
									else{
										if(!$domDoc = domxml_open_mem($xmlStr)) {
											//
										}
										else {
											$afs = new AFS;
											$afs->parseDomDocument($domDoc);
											$this->formArray["landTotalMarketValue"] += $afs->getLandTotalMarketValue();
											$this->formArray["landTotalAssessedValue"] += $afs->getLandTotalAssessedValue();
											$this->formArray["plantTotalMarketValue"] += $afs->getPlantTotalMarketValue();
											$this->formArray["plantTotalAssessedValue"] += $afs->getPlantTotalAssessedValue();
											$this->formArray["bldgTotalMarketValue"] += $afs->getBldgTotalMarketValue();
											$this->formArray["bldgTotalAssessedValue"] += $afs->getBldgTotalAssessedValue();
											$this->formArray["machTotalMarketValue"] += $afs->getMachTotalMarketValue();
											$this->formArray["machTotalAssessedValue"] += $afs->getMachTotalAssessedValue();
											$this->formArray["totalMarketValue"] += $afs->getTotalMarketValue();
											$this->formArray["totalAssessedValue"] += $afs->getTotalAssessedValue();
											$this->tpl->set_var("marketValue", number_format($afs->getTotalMarketValue(), 2, '.', ','));
											$this->tpl->set_var("assessedValue", number_format($afs->getTotalAssessedValue(), 2, '.', ','));
											
											$this->tpl->set_var("taxability", $afs->getTaxability());
											$this->tpl->set_var("effectivity", $afs->getEffectivity());

											$this->formArray["idle"] = "No";

											if($tvalue->getPropertyType()=="Land"){
												if(is_array($afs->landArray)){
													// if land is stripped
													if((count($afs->landArray)>1)){
														foreach($afs->landArray as $land){
															if($land->getIdle()=="Yes"){
																$this->formArray["idle"] = "Yes";
																break;
															}
														}
													}
													else{
														$this->formArray["idle"] = $afs->landArray[0]->getIdle();
													}													
												}
											}

											if($this->formArray["idle"]==""){
												$this->formArray["idle"] = "No";
											}

											$this->tpl->set_var("idle", $this->formArray["idle"]);
									
										}
									}

									// grab DueRecords from tdID
									
									$DueList = new SoapObject(NCCBIZ."DueList.php", "urn:Object");

									$dueArrayList = array(
										"Annual" => ""
										,"Q1" => ""
										,"Q2" => ""
										,"Q3" => ""
										,"Q4" => "");

									$this->tpl->set_var("dueYear", $rptop->getTaxableYear());

									if (!$xmlStr = $DueList->getDueList($tvalue->getTdID(),$rptop->getTaxableYear())){
										foreach($dueArrayList as $dueKey=>$dueValue){
											$this->tpl->set_var("basicTax[".$dueKey."]","uncalculated");
											$this->tpl->set_var("sefTax[".$dueKey."]","uncalculated");
											$this->tpl->set_var("idleTax[".$dueKey."]","uncalculated");
											$this->tpl->set_var("taxDue[".$dueKey."]","uncalculated");
											$this->tpl->set_var("dueDate[".$dueKey."]","-");
										}
									}
									else{
										if(!$domDoc = domxml_open_mem($xmlStr)) {
											foreach($dueArrayList as $dueKey=>$dueValue){
												$this->tpl->set_var("basicTax[".$dueKey."]","uncalculated");
												$this->tpl->set_var("sefTax[".$dueKey."]","uncalculated");
												$this->tpl->set_var("idleTax[".$dueKey."]","uncalculated");
												$this->tpl->set_var("taxDue[".$dueKey."]","uncalculated");
												$this->tpl->set_var("dueDate[".$dueKey."]","-");
											}
										}
										else {
											$dueRecords = new DueRecords;
											$dueRecords->parseDomDocument($domDoc);

											foreach($dueRecords->getArrayList() as $due){
												foreach($due as $dueKey=>$dueValue){
													switch($dueKey){
														case "dueType":
															if($dueValue=="Annual"){
																$this->formArray["totalTaxDue"] += $due->getTaxDue();
															}
															$dueArrayList[$dueValue] = $due;

															$this->tpl->set_var("basicTax[".$dueValue."]",formatCurrency($due->getBasicTax()));
															$this->tpl->set_var("sefTax[".$dueValue."]",formatCurrency($due->getSEFTax()));
															$this->tpl->set_var("idleTax[".$dueValue."]",formatCurrency($due->getIdleTax()));
															$this->tpl->set_var("taxDue[".$dueValue."]",formatCurrency($due->getTaxDue()));
															$this->tpl->set_var("dueDate[".$dueValue."]",date("M. d, Y",strtotime($due->getDueDate())));
															$this->tpl->set_var("dueID[".$dueValue."]",$due->getDueID());
															break;
													}
												}
											}

											// initialize discountPeriod and discountPercentage for earlyPaymentDiscount 

											$treasurySettings = $this->getTreasurySettings();

											$this->tpl->set_var("discountPercentage",$treasurySettings->getDiscountPercentage()."%");
											$this->tpl->set_var("discountPeriod","January 01, ".$rptop->getTaxableYear()." - ".date("F d, Y",strtotime($rptop->getTaxableYear()."-".$treasurySettings->getDiscountPeriod())));

											$this->formArray["discountPercentage"] = $treasurySettings->getDiscountPercentage();
											$this->formArray["discountPeriod"] = $treasurySettings->getDiscountPeriod();

											$this->formArray["discountPeriod_End"] = strtotime($rptop->getTaxableYear()."-".$this->formArray["discountPeriod"]);
											$this->formArray["discountPeriod_Start"] = strtotime($rptop->getTaxableYear()."-01-01");

											// initialize advancedDiscountPercentage for advancedPayment

											$this->tpl->set_var("advancedDiscountPercentage",$treasurySettings->getAdvancedDiscountPercentage()."%");
											$this->formArray["advancedDiscountPercentage"] = $treasurySettings->getAdvancedDiscountPercentage();
											$this->tpl->set_var("q1AdvancedDiscountPercentage",$treasurySettings->getQ1AdvancedDiscountPercentage()."%");
											$this->formArray["q1AdvancedDiscountPercentage"] = $treasurySettings->getQ1AdvancedDiscountPercentage();

											// initialize penaltyLUTArray

											$penaltyLUTArray = $this->getPenaltyLUTArray();

											// get paymentHistory

											$defaultDueType = "Annual";
											$allowableDueTypesArray = array("Annual","Q1","Q2","Q3","Q4");

											/* alxjvr 2006.03.22
											if(!$paymentHistory = $this->getPaymentHistory($dueArrayList,"")){
												$defaultDueType = "Annual";
												$allowableDueTypesArray = array("Annual","Q1");
											}
											else{
												$defaultDueType = $paymentHistory->arrayList[0]->getDueType();

												if($defaultDueType=="Annual"){
													$allowableDueTypesArray = array("Annual");
												}
												else{
													switch($defaultDueType){
														case "Q1":
															$allowableDueTypesArray = array("Q1", "Q2");
															break;
														case "Q2":
															$allowableDueTypesArray = array("Q2", "Q3");
															break;
														case "Q3":
															$allowableDueTypesArray = array("Q3", "Q4");
															break;
														case "Q4":
															$allowableDueTypesArray = array("Q4");
															break;
													}
												}
											}
											*/

											foreach($dueArrayList as $dKey => $due){
												$dueArrayList[$dKey]->setEarlyPaymentDiscountPeriod($this->formArray["discountPeriod"]);
												$dueArrayList[$dKey]->setEarlyPaymentDiscountPercentage($this->formArray["discountPercentage"]);


												// compute earlyPaymentDiscount as of today
												// check if today is within the discountPeriod and compute Discount
												// AND if today is BEFORE annual dueDate

												$dueArrayList[$dKey]->setEarlyPaymentDiscount(0.00);

												if($due->getDueType()=="Annual"){
													if(strtotime($this->now) >= $this->formArray["discountPeriod_Start"] && strtotime($this->now) <= $this->formArray["discountPeriod_End"]){
														if(strtotime($this->now) <= strtotime($dueArrayList[$dKey]->getDueDate())){
															$dueArrayList[$dKey]->setEarlyPaymentDiscount($dueArrayList[$dKey]->getTaxDue() * ($this->formArray["discountPercentage"]/100));
														}
													}
												}
												else{
													// if today is BEFORE dueDate
													if(strtotime($this->now) <= strtotime($due->getDueDate()) && strtotime($this->now) >= $this->formArray["discountPeriod_Start"]){
														$dueArrayList[$dKey]->setEarlyPaymentDiscount($dueArrayList[$dKey]->getTaxDue() * ($this->formArray["discountPercentage"]/100));														
													}

													// commented out: February 08, 2005
													// earlyPaymentDiscount aren't given to Quarterly Dues except for Quarter 1
													/*
													if($due->getDueType()=="Q1"){
														if(strtotime($this->now) >= $this->formArray["discountPeriod_Start"] && strtotime($this->now) <= $this->formArray["discountPeriod_End"]){
															if(strtotime($this->now) <= strtotime($dueArrayList[$dKey]->getDueDate())){
																$dueArrayList[$dKey]->setEarlyPaymentDiscount($dueArrayList[$dKey]->getTaxDue() * ($this->formArray["discountPercentage"]/100));
															}
														}
													}
													*/
												}


												// compute advancedPaymentDiscount as of today
												// check if today is BEFORE January 1 of the year of the annual dueDate

												$dueArrayList[$dKey]->setAdvancedPaymentDiscount(0.00);
												if(strtotime($this->now) < strtotime(date("Y",strtotime($dueArrayList[$dKey]->getDueDate()))."-01-01")){

													// for advanced payments, give 20% discount to annual dues [advanced discount]
													// give 10% discount to quarterly dues [early discount]

													if($due->getDueType()=="Annual"){
														$dueArrayList[$dKey]->setAdvancedPaymentDiscount($dueArrayList[$dKey]->getTaxDue() * ($this->formArray["advancedDiscountPercentage"]/100));
													}
													else{
														// commented out: February 08, 2005
														// advancedPaymentDiscount aren't given to Quarterly Dues
														// except for Quarter 1

														if($due->getDueType()=="Q1"){
															$dueArrayList[$dKey]->setAdvancedPaymentDiscount($dueArrayList[$dKey]->getTaxDue() * ($this->formArray["q1AdvancedDiscountPercentage"]/100));
														}
														else{
															$dueArrayList[$dKey]->setAdvancedPaymentDiscount($dueArrayList[$dKey]->getTaxDue() * ($this->formArray["discountPercentage"]/100));
														}
													}
												}

												$latestPaymentDate[$dKey] = $this->getLatestPaymentDateForDue($dueArrayList[$dKey]);
												
												$amountPaidForDue = $this->getAmountPaidForDue($dueArrayList);
												$amnestyStatus = $this->getAmnestyStatusForDue($dueArrayList);
												$totalEarlyPaymentDiscount = $this->getTotalEarlyPaymentDiscountForDue($dueArrayList);
												$totalAdvancedPaymentDiscount = $this->getTotalAdvancedPaymentDiscountForDue($dueArrayList);
												
												if($totalEarlyPaymentDiscount > 0){
													$earlyPaymentDiscountForDueType = $this->getTotalEarlyPaymentDiscountForDueType($dueArrayList[$dKey]);
													if($earlyPaymentDiscountForDueType > 0){
														$dueArrayList[$dKey]->setEarlyPaymentDiscount($earlyPaymentDiscountForDueType);
													}
												}
												if($totalAdvancedPaymentDiscount > 0){
													$advancedPaymentDiscountForDueType = $this->getTotalAdvancedPaymentDiscountForDueType($dueArrayList[$dKey]);
													if($advancedPaymentDiscountForDueType > 0){
														$dueArrayList[$dKey]->setAdvancedPaymentDiscount($advancedPaymentDiscountForDueType);
													}
												}
																							

												if($amnestyStatus){
													$this->tpl->set_var("amnesty_status","checked");
												}
												else{
													$this->tpl->set_var("amnesty_status","");
												}

												// calculate Penalties verses either today or verses the last paymentDate

												if($latestPaymentDate[$dKey]!="" || $latestPaymentDate[$dKey]!="now"){
													$dueArrayList[$dKey] = $this->computePenalty($latestPaymentDate[$dKey],$dueArrayList[$dKey]);

													// if balance is 0 leave penalty as is, otherwise calculatePenalty according to date now
													$balance = $dueArrayList[$dKey]->getInitialNetDue() - $amountPaidForDue;

													// 0.1 is used instead of 0 because sometimes rounded off balances intended to be 0 end up appearing as 0.002 or so...
													if(round($balance,4) > 0.1){
														$dueArrayList[$dKey] = $this->computePenalty($this->now,$dueArrayList[$dKey]);
													}
												}
												else{
													$dueArrayList[$dKey] = $this->computePenalty($this->now,$dueArrayList[$dKey]);
												}

												$this->tpl->set_var("advancedPaymentDiscount[".$dKey."]",formatCurrency($dueArrayList[$dKey]->getAdvancedPaymentDiscount()));
												$this->tpl->set_var("earlyPaymentDiscount[".$dKey."]",formatCurrency($dueArrayList[$dKey]->getEarlyPaymentDiscount()));
												$this->tpl->set_var("monthsOverDue[".$dKey."]",$dueArrayList[$dKey]->getMonthsOverDue());
												$this->tpl->set_var("penaltyPercentage[".$dKey."]",$dueArrayList[$dKey]->getPenaltyPercentage()*100);
												$this->tpl->set_var("penalty[".$dKey."]",formatCurrency($dueArrayList[$dKey]->getPenalty()));
												$this->tpl->set_var("totalPaid[".$dKey."]",formatCurrency($this->getAmountPaidForDueID($dueArrayList[$dKey]->getDueID())));
												$this->tpl->set_var("initialNetDue[".$dKey."]",formatCurrency($dueArrayList[$dKey]->getInitialNetDue()));
												$this->initialNetDue[$dKey] = $dueArrayList[$dKey]->getInitialNetDue();
											}

											// revert Back to Annual mode if Quarterly Dues have Penalties
											foreach($dueArrayList as $dKey => $due){
												if($dKey!="Annual"){
													if($dueArrayList[$dKey]->getPenalty() > 0){
														$defaultDueType = "Annual";
														$allowableDueTypesArray = array("Annual");
														$revertedBackToAnnual = true;
														break;
													}
												}
											}

											foreach($allowableDueTypesArray as $allowableDueType){
												$this->tpl->set_var("allowableDueType",$allowableDueType);
												$this->tpl->parse("DueTypeListBlock", "DueTypeList", true);
											}

											$this->tpl->set_var("dueType[Annual]_status", "checked");
											$this->tpl->set_var("dueType[Q1]_status", "");
											$this->tpl->set_var("dueType[Q2]_status", "");
											$this->tpl->set_var("dueType[Q3]_status", "");
											$this->tpl->set_var("dueType[Q4]_status", "");

											$this->tpl->set_var("dueDate", date("M. d, Y",strtotime($dueArrayList[$defaultDueType]->getDueDate())));
											$this->tpl->set_var("basicTax", formatCurrency($dueArrayList[$defaultDueType]->getBasicTax()));
											$this->tpl->set_var("sefTax", formatCurrency($dueArrayList[$defaultDueType]->getSEFTax()));
											$this->tpl->set_var("idleTax", formatCurrency($dueArrayList[$defaultDueType]->getIdleTax()));
											$this->tpl->set_var("taxDue", formatCurrency($dueArrayList[$defaultDueType]->getTaxDue()));

											$this->tpl->set_var("advancedPaymentDiscount",formatCurrency($dueArrayList[$defaultDueType]->getAdvancedPaymentDiscount()));
											$this->tpl->set_var("earlyPaymentDiscount",formatCurrency($dueArrayList[$defaultDueType]->getEarlyPaymentDiscount()));
											$this->tpl->set_var("penalty",formatCurrency($dueArrayList[$defaultDueType]->getPenalty()));

											// get amountPaid for defaultDueType
											// but if dues have been reverted back to "Annual" mode from "Quarterly" mode
											// get amountPaid for all the quarters that have been paid so far

											if($revertedBackToAnnual){
												$amountPaid=0;
												$amountPaid+= $this->getAmountpaidForDueID($dueArrayList["Annual"]->getDueID());
												$amountPaid+= $this->getAmountpaidForDueID($dueArrayList["Q1"]->getDueID());
												$amountPaid+= $this->getAmountpaidForDueID($dueArrayList["Q2"]->getDueID());
												$amountPaid+= $this->getAmountpaidForDueID($dueArrayList["Q3"]->getDueID());
												$amountPaid+= $this->getAmountpaidForDueID($dueArrayList["Q4"]->getDueID());
											}
											else{
												$amountPaid = $this->getAmountPaidForDueID($dueArrayList[$defaultDueType]->getDueID());
											}
											$this->tpl->set_var("amountPaid",formatCurrency($amountPaid));

											$balance = $dueArrayList[$defaultDueType]->getTaxDue();
											if($dueArrayList[$defaultDueType]->getAdvancedPaymentDiscount() > 0){
												$balance = $dueArrayList[$defaultDueType]->getTaxDue() - $dueArrayList[$defaultDueType]->getAdvancedPaymentDiscount();
											}
											else if($dueArrayList[$defaultDueType]->getEarlyPaymentDiscount() > 0){
												$balance = $dueArrayList[$defaultDueType]->getTaxDue() - $dueArrayList[$defaultDueType]->getEarlyPaymentDiscount();
											}

											$balance = round((($balance + $dueArrayList[$defaultDueType]->getPenalty()) - $amountPaid),2);
											$this->tpl->set_var("balance",formatCurrency($balance));
										}
									}

									// display Backtaxes and previousTD Backtaxes

									$this->formArray["totalBacktaxesBalance"] = 0;
									$this->displayBacktaxTD($tvalue->getTdID());
									$precedingTDArray = $this->getPrecedingTDArray($tvalue);
									if(is_array($precedingTDArray)){
										foreach($precedingTDArray as $precedingTD){
											$this->displayBacktaxTD($precedingTD->getTdID());
										}
									}
									$this->tpl->set_var("total", number_format($this->formArray["totalBacktaxesDue"],2));
									$this->tpl->set_var("totalBacktaxesBalance",number_format($this->formArray["totalBacktaxesBalance"],2));

									// grab dueID's and backtaxTDID's to run through payments
									// create $dueIDArray
									foreach($dueArrayList as $due){
										$this->dueIDArray[] = $due->getDueID();
									}

									$this->displayTotalPaid();
									$this->displayNetDue();

									$this->tpl->set_var("ctr", $tdCtr);
									$this->tpl->parse("defaultTDListBlock", "defaultTDList", true);
									$this->tpl->parse("toggleTDListBlock", "toggleTDList", true);
									$this->tpl->parse("TDListBlock", "TDList", true);
									$this->tpl->parse("JSTDListBlock", "JSTDList", true);
									$this->tpl->set_var("DueTypeListBlock", "");

									// added following line Feb.22,2005 to solve erpts issue (2005-22), backtaxTD blocking bug.
									$this->tpl->set_var("BacktaxesListBlock","");

									$tdCtr++;
								}
							}
							else {
								$this->tpl->set_var("defaultTDListBlock", "//no default");
								$this->tpl->set_var("toggleTDListBlock", "//no Toggle");
								$this->tpl->set_var("TDListBlock", "");
								$this->tpl->set_var("JSTDListBlock", "");
							}
							$this->tpl->set_var("tdCtr", $tdCtr);
						break;
						case "landTotalMarketValue":
							if (!$this->formArray[$key]) $this->formArray[$key] = $value;
						break;
						case "landTotalAssessedValue":
							if (!$this->formArray[$key]) $this->formArray[$key] = $value;
						break;
						case "plantTotalMarketValue":
							if (!$this->formArray[$key]) $this->formArray[$key] = $value;
						break;
						case "plantTotalAssessedValue":
							if (!$this->formArray[$key]) $this->formArray[$key] = $value;
						break;
						case "bldgTotalMarketValue":
							if (!$this->formArray[$key]) $this->formArray[$key] = $value;
						break;
						case "bldgTotalAssessedValue":
							if (!$this->formArray[$key]) $this->formArray[$key] = $value;
						break;
						case "machTotalMarketValue":
							if (!$this->formArray[$key]) $this->formArray[$key] = $value;
						break;
						case "machTotalAssessedValue":
							if (!$this->formArray[$key]) $this->formArray[$key] = $value;
						break;
						case "totalMarketValue":
							if (!$this->formArray[$key]) $this->formArray[$key] = $value;
						break;
						case "totalAssessedValue":
							if (!$this->formArray[$key]) $this->formArray[$key] = $value;
						break;
						default:
						$this->formArray[$key] = $value;
					}
				}
				$this->formArray["totalMarketValue"] = $this->formArray["landTotalMarketValue"]
											+ $this->formArray["plantTotalMarketValue"]
											+ $this->formArray["bldgTotalMarketValue"]
											+ $this->formArray["machTotalMarketValue"];
				$this->formArray["totalAssessedValue"] = $this->formArray["landTotalAssessedValue"]
											+ $this->formArray["plantTotalAssessedValue"]
											+ $this->formArray["bldgTotalAssessedValue"]
											+ $this->formArray["machTotalAssessedValue"];
				unset($rptop);
				$AFSEncode = new SoapObject(NCCBIZ."AFSEncode.php", "urn:Object");
				$rptop = new RPTOP;
				$rptop->setRptopID($this->formArray["rptopID"]);
				$rptop->setLandTotalMarketValue($this->formArray["landTotalMarketValue"]);
				$rptop->setLandTotalAssessedValue($this->formArray["landTotalAssessedValue"]);
				$rptop->setPlantTotalMarketValue($this->formArray["plantTotalMarketValue"]);
				$rptop->setPlantTotalPlantAssessedValue($this->formArray["plantTotalAssessedValue"]);
				$rptop->setBldgTotalMarketValue($this->formArray["bldgTotalMarketValue"]);
				$rptop->setBldgTotalAssessedValue($this->formArray["bldgTotalAssessedValue"]);
				$rptop->setMachTotalMarketValue($this->formArray["machTotalMarketValue"]);
				$rptop->setMachTotalAssessedValue($this->formArray["machTotalAssessedValue"]);
				$rptop->setTotalMarketValue($this->formArray["totalMarketValue"]);
				$rptop->setTotalAssessedValue($this->formArray["totalAssessedValue"]);

				$rptop->setCreatedBy($this->userID);
				$rptop->setModifiedBy($this->userID);

				$rptop->setDomDocument();
				$RPTOPEncode = new SoapObject(NCCBIZ."RPTOPEncode.php", "urn:Object");
				$rptop->setDomDocument();
				$doc = $rptop->getDomDocument();
				$xmlStr =  $doc->dump_mem(true);
				//echo $xmlStr;
				if (!$ret = $RPTOPEncode->updateRPTOPtotals($xmlStr)){
					echo("ret=".$ret);
				}
				//echo $ret;
			}	
		}
		
		$this->setForm();
		$this->setPageDetailPerms();

		$this->tpl->set_var("uname", $this->user["uname"]);

		$this->tpl->set_var("today", date("F j, Y",strtotime($this->now)));

		$this->tpl->set_var("Session", $this->sess->url("").$this->sess->add_query(array("rptopID"=>$this->formArray["rptopID"],"ownerID" => $this->formArray["ownerID"])));
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
//*
page_open(array("sess" => "rpts_Session"
	,"auth" => "rpts_Challenge_Auth"
	//"perm" => "rpts_Perm"
	));
//*/

$ownerList = new RPTOPDetails($HTTP_POST_VARS,$sess,$rptopID);
$ownerList->main();
?>
<?php page_close(); ?>
