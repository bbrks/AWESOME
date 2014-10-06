<?
@define("__MAIN__", __FILE__); // define the first file to execute

/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 * Just give a basic guide (not used anymore)
 */

require "../../lib.php";

if (__MAIN__ == __FILE__) { // only output if directly requested (for include purposes)
	$twig_common = new twig_common();
	$twig = $twig_common->twig; //reduce code changes needed
	
	$template = $twig->loadTemplate('questionnaire/main.html');
	
	$questionnaireID = $_GET["questionnaireID"];
	$alerts = array();
	
	if ($questionnaireID === null) {
		throw new Exception("Questionnaire ID is required");
	}
	
	echo $template->render(array(
		"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
	));
}