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
require_once "{$root}/lib/Twig/Autoloader.php";

require_once "modules.php";
require_once "moduleresults.php";

if (__MAIN__ == __FILE__) { // only output if directly requested (for include purposes)
	Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
	$twig = new Twig_Environment($loader, array());
	
	$template = $twig->loadTemplate('questionnaire/results/allmoduleresults.html');
	
	
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