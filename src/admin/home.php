<?
require "../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('home.html'); 

function insertQuestionaire($details) {
	global $questionaireID, $alerts, $db;
	try {
		$stmt = new tidy_sql($db, "INSERT INTO Questionaires (QuestionaireName, QuestionaireDepartment) VALUES (?,?)", "ss");
		$stmt->query($questionaireID, $details["questionaireName"], $details["questionaireDepartment"]);
		
		$alerts[] = array("type"=>"success",  "message"=>"Sucessfully added questionnairep");
	}
	catch (Exception $e) {
		$alerts[] = array("type"=>"danger",  "message"=>"Sorry, an error occurred adding questionnaire ({$e->getMessage()})");
	}
}

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["action"])) {
	$action = $_POST["action"];
	if ($action == "add_questionaire") {
		insertQuestionaire($_POST);
	}
}

$questionaires = getQuestionaires();

echo $template->render(array(
	"url"=>$url,
	"questionaires"=>$questionaires
));
