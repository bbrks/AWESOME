<?
/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 */

require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionnaire/results/modules.html');

$questionnaireID = $_GET["questionnaireID"];
$alerts = array();


/**
 * Retrieves list of modules from database
 * 
 * @param int $questionnaireID Questionnaire ID to list students from
 * 
 * @returns array of modules (ModuleID, ModuleTitle, NumAnswers, TotalStudents)
 */
function getModulesList($questionnaireID) {
	global $db;
	
	$stmt = new tidy_sql($db, "
	SELECT Modules.ModuleID,Modules.ModuleTitle,(
		SELECT COUNT(DISTINCT Answers.AnswerID)
		FROM Answers
		JOIN AnswerGroup ON Answers.AnswerID=AnswerGroup.AnswerID
		WHERE Answers.ModuleID=Modules.ModuleID AND
		AnswerGroup.QuestionaireID=Modules.QuestionaireID
		) as NumAnswers, (
			SELECT COUNT(*)
			FROM StudentsToModules
			WHERE StudentsToModules.QuestionaireID=Modules.QuestionaireID AND
			StudentsToModules.ModuleID=Modules.ModuleID
		) as TotalStudents,
		Fake
	FROM Modules
	LEFT JOIN StudentsToModules ON StudentsToModules.QuestionaireID=Modules.QuestionaireID AND StudentsToModules.ModuleID=Modules.ModuleID
	WHERE Modules.QuestionaireID=? AND (Modules.Fake = true OR StudentsToModules.ModuleID is not null)
	GROUP BY Modules.ModuleID
	ORDER BY Modules.Fake DESC,NumAnswers/TotalStudents DESC,NumAnswers DESC
		", "i");
		
	$modules = $stmt->query($questionnaireID);
	return $modules;
}

/**
 * Retrieves list of modules from database
 * 
 * @param int $questionnaireID Questionnaire ID to get count from
 * 
 * @returns an integer stating the total student count.
 */
function getTotalQuestionnaireStudents($questionnaireID) {
	global $db;
	
	$stmt = new tidy_sql($db, "SELECT COUNT(DISTINCT UserID) as total FROM Students WHERE QuestionaireID=?", "i");
	$info = $stmt->query($questionnaireID);
	return $info[0]["total"];
}

$modules = getModulesList($questionnaireID);
$totalstudents = getTotalQuestionnaireStudents($questionnaireID);

echo $template->render(array(
	"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
	"totalstudents"=>$totalstudents,
	"modules"=>$modules
));
