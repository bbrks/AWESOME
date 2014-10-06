<?
@define("__MAIN__", __FILE__); // define the first file to execute

/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk>
 *
 */

require_once "../../../lib.php";

require_once "modules.php";
require_once "moduleresults.php";

if (__MAIN__ == __FILE__) { // only output if directly requested (for include purposes)
	$twig_common = new twig_common();
	$twig = $twig_common->twig; //reduce code changes needed
	
	$template = $twig->loadTemplate('questionnaire/results/allmoduleresults.html');
	
	if (!isset($_GET["questionnaireID"]) || $_GET["questionnaireID"] === null) {
		throw new Exception("Questionnaire ID is required");
	}
	
	$questionnaireID = $_GET["questionnaireID"];
	$alerts = array();
	
	$modules = getModulesList($questionnaireID);
	foreach($modules as &$module) {
		$module["Questions"] = getResults($module["ModuleID"], $questionnaireID);
	}
	
	echo $template->render(array(
		"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
		"modules"=>$modules 
	));
}