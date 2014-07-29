<?

global $db;


$db = new mysqli("d3s.co", "keiron", "bigtits", "database");
if ($db->connect_errno)
	throw "Failed to connect";

global $user
$user = $SESSION["user"]

function getRow($stmt) { //PHP prepared statements are shit
    $meta = $stmt->result_metadata(); 
    while ($field = $meta->fetch_field()) 
    { 
        $params[] = &$row[$field->name]; 
    } 

    call_user_func_array(array($stmt, 'bind_result'), $params); 

    while ($stmt->fetch()) { 
        foreach($row as $key => $val) 
        { 
            $c[$key] = $val; 
        } 
        $result[] = $c; 
    } 
    return $result;
}

function getStudentDetails($user) {
	if ($db->prepare("SELECT * FROM Students WHERE `Student ID`=?"))
	{
		$stmt->bind_param("i", $user);
		$stmt->execute();
		return getRow($stmt);
	}
}
