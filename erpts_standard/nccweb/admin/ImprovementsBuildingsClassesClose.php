<?php 
# Setup PHPLIB in this Area
include("web/prepend.php");
#####################################
# Define Interface Class
#####################################
class ImprovementsBuildingsClassesClose{
	
	var $tpl;
	var $formArray;
	var $sess;
	
	function ImprovementsBuildingsClassesClose($improvementsBuildingsClassesID,$sess){
		$this->sess = $sess;
		$this->tpl = new rpts_Template(getcwd(),"keep");
		$this->tpl->set_file("rptsTemplate", "PageClose.htm") ;
		$this->tpl->set_var("TITLE", "Encode ImprovementsBuildingsClasses");
		
		$this->formArray = array("improvementsBuildingsClassesID" => $improvementsBuildingsClassesID);
	}
	
	
	function Main(){
		$this->tpl->set_var("location", "ImprovementsBuildingsClassesList.php");
		$this->tpl->set_var("Session",$this->sess->url("").$this->sess->add_query(array("improvementsBuildingsClassesID"=>$this->formArray["improvementsBuildingsClassesID"],"formAction"=>"view")));
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
$improvementsBuildingsClassesClose = new ImprovementsBuildingsClassesClose($improvementsBuildingsClassesID,$sess);
$improvementsBuildingsClassesClose->Main();
?>
<?php page_close(); ?>