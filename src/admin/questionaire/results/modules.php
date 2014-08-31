<?
require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionaire/results/modules.html');

$questionaireID = $_GET["questionaireID"];
$alerts = array();


$stmt = new tidy_sql($db, "
	SELECT Modules.ModuleID,Modules.ModuleTitle,COUNT(DISTINCT AnswerGroup.AnswerID) as NumAnswers FROM Modules
	JOIN StudentsToModules ON StudentsToModules.ModuleID = Modules.ModuleID AND StudentsToModules.QuestionaireID = Modules.QuestionaireID
	LEFT JOIN Answers ON Answers.ModuleID = Modules.ModuleID
	LEFT JOIN AnswerGroup ON AnswerGroup.QuestionaireID = Modules.QuestionaireID
	WHERE Modules.QuestionaireID=?
	GROUP BY Modules.ModuleID
	", "i");
$modules = $stmt->query($questionaireID);

echo $template->render(array(
	"url"=>$url, "questionaireID"=> $questionaireID, "alerts"=>$alerts,
	"modules"=>$modules
));
