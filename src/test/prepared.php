<pre><?

require("../lib.php");
$user = $_GET["user"];
print_r(getPreparedQuestions($user));
