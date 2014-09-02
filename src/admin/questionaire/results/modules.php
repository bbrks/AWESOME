<?
require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionaire/results/modules.html');

$questionaireID = $_GET["questionaireID"];
$alerts = array();

function getModulesList($questionaireID) {
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
		
	$modules = $stmt->query($questionaireID);
	return $modules;
}

function getTotalQuestionnaireStudents($questionaireID) {
	global $db;
	
	$stmt = new tidy_sql($db, "SELECT COUNT(DISTINCT UserID) as total FROM Students WHERE QuestionaireID=?", "i");
	$info = $stmt->query($questionaireID);
	return $info[0]["total"];
}

$modules = getModulesList($questionaireID);
$totalstudents = getTotalQuestionnaireStudents($questionaireID);

echo $template->render(array(
	"url"=>$url, "questionaireID"=> $questionaireID, "alerts"=>$alerts,
	"totalstudents"=>$totalstudents,
	"modules"=>$modules
));
