<?
require "../../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";

function getQuestionaire($questionnaireID) {
	global $db;

	$stmt = new tidy_sql($db, "
		SELECT * FROM Questionaires WHERE QuestionaireID=?", "i");

	$rows = $stmt->query($questionnaireID);
	
	return $rows[0];
}

function updateQuestionaire($questionnaireID, $fields) {
	global $db;

	$stmt = new tidy_sql($db, "
		UPDATE Questionaires SET QuestionaireName=?, QuestionaireDepartment=? WHERE QuestionaireID=?", "ssi");

	$stmt->query($fields["QuestionaireName"], $fields["QuestionaireDepartment"], $questionnaireID);
}

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());

$template = $twig->loadTemplate('questionnaire/import/basic.html');

$questionnaireID = $_GET["questionnaireID"];
$alerts = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$q = array();
		$q["QuestionaireName"] = $_POST["questionnaireName"];
		$q["QuestionaireDepartment"] = $_POST["questionnaireDepartment"];
		
		updateQuestionaire($questionnaireID, $q);
		
		$alerts[] = array("type"=>"success", "message"=>"Questionnaire modified");
}

$q = getQuestionaire($questionnaireID);

echo $template->render(array(
	"url"=>$url, "questionnaireID"=> $questionnaireID, "alerts"=>$alerts,
	"questionnaire"=>array("name"=>$q["QuestionaireName"], "department"=>$q["QuestionaireDepartment"])
));


