<?
require "../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('home.html'); 

function getQuestionaires() {
	global $db;

	$stmt = new tidy_sql($db, "
		SELECT *, (
				SELECT COUNT(*)
				FROM AnswerGroup
				WHERE Questionaires.QuestionaireID = AnswerGroup.QuestionaireID
			) AS Answers,
			(
				SELECT COUNT(DISTINCT StudentsToModules.UserID)
				FROM StudentsToModules
				WHERE Questionaires.QuestionaireID = StudentsToModules.QuestionaireID
			) AS Total
		FROM Questionaires
	");
	$rows = $stmt->query();
	
	foreach ($rows as &$questionaire) {
		$questionaire["Percent"] = $questionaire["Total"]==0?0:($questionaire["Answers"]/$questionaire["Total"])*100;
	}

	return $rows;
}

$questionaires = getQuestionaires();

echo $template->render(array(
	"url"=>$url,
	"questionaires"=>$questionaires
));
