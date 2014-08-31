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
SELECT Modules.ModuleID,Modules.ModuleTitle,(
	SELECT COUNT(DISTINCT Answers.AnswerID)
	FROM Answers
	JOIN AnswerGroup ON AnswerGroup.QuestionaireID=(SELECT Modules.QuestionaireID)
	WHERE Answers.ModuleID=Modules.ModuleID
) as NumAnswers
FROM Modules
WHERE Modules.QuestionaireID=?
ORDER BY NumAnswers DESC
	", "i");
$modules = $stmt->query($questionaireID);

echo $template->render(array(
	"url"=>$url, "questionaireID"=> $questionaireID, "alerts"=>$alerts,
	"modules"=>$modules
));
