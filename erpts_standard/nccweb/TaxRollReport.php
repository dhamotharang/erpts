<?php 
# Setup PHPLIB in this Area
include_once("web/prepend.php");

include_once("assessor/OD.php");
include_once("assessor/ODRecords.php");
include_once("assessor/AFS.php");
include_once("assessor/AFSRecords.php");

include_once("assessor/Barangay.php");
include_once("assessor/BarangayRecords.php");
include_once("assessor/District.php");
include_once("assessor/DistrictRecords.php");
include_once("assessor/MunicipalityCity.php");
include_once("assessor/MunicipalityCityRecords.php");
include_once("assessor/Province.php");
include_once("assessor/ProvinceRecords.php");

#####################################
# Define Interface Class
#####################################
class TaxRollReport{
	
	var $tpl;
	function TaxRollReport($sess){
		global $auth;

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

		$this->tpl->set_file("rptsTemplate", "TaxRollReport.htm") ;
		$this->tpl->set_var("TITLE", "Tax Roll Report");
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

	function hideBlock($tempVar){
		$this->tpl->set_block("rptsTemplate", $tempVar, $tempVar."Block");
		$this->tpl->set_var($tempVar."Block", "");
	}

	function setPageDetailPerms(){
		if(!checkPerms($this->user["userType"],"%1%%%%%%%%")){
			// hide Blocks if userType is not at least AM-Edit
			$this->hideBlock("TransactionsLink");
		}
		else{
			$this->hideBlock("TransactionsLinkText");
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

	function setForm(){
        // barangay Listbox	
        $this->initMasterAddressList("Barangay", "barangay");
        // district Listbox	
        $this->initMasterAddressList("District", "district");
        // municipality/city Listbox	
        $this->initMasterAddressList("MunicipalityCity", "municipalityCity");
        // province Listbox	
        $this->initMasterAddressList("Province", "province");

		foreach ($this->formArray as $key => $value){
			$this->tpl->set_var($key, $value);
		}
	}

	function setDB(){
		$this->db = new DB_RPTS;
	}

	function Main(){
		$this->setDB();

		$this->setPageDetailPerms();

		$this->setForm();
		
		$this->tpl->set_var("uname", $this->user["uname"]);
		$this->tpl->set_var("today", date("F j, Y"));

		$this->tpl->set_var("Session", $this->sess->url(""));
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
	,"perm" => "rpts_Perm"
	));
//*/
$taxRollReport = new TaxRollReport($sess);
$taxRollReport->Main();
?>
<?php page_close(); ?>
