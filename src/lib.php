<?
/**
 * @file
 * @version 1.0
 * @date 07/09/2014
 * @author Keiron-Teilo O'Shea <keo7@aber.ac.uk> 
 * 	
 * This contains library functions that are useful for every page:
 * 
 *  - benchmark, just outputs data on page end
 * 
 *  - tidy_sql class which abstracts mysqli's prepared statements.
 *       Prepared statements are used as it reduces the likelyhood of
 *       exploits down to 0, as no data is being concatenated to the query at all
 */ 

//initialise db connection
require "db.php";
if ($db->connect_errno)
	throw new Exception("Failed to connect");

///stores benchmark data after page load
$benchmark = (object)array(
	"db_preparetime" => 0,
	"db_querytime" => 0,
	"db_numqueries" => 0,
	"db_numresults" => 0,
);

///stores the time page started loading
$start = microtime(true);

register_shutdown_function('output_timer');

/**
 * This is ran at the end of page load, it generates benchmark 
 *  data that is appended to the end of every page
 * 
 * Example output:
 *	  <!-- Benchmark/stats ;) times are in seconds
 *	stdClass Object
 *	(
 *		[db_preparetime] => 0.00033783912658691
 *		[db_querytime] => 0.00038003921508789
 *		[db_numqueries] => 4
 *		[db_numresults] => 129
 *		[total_time] => 0.0026710033416748
 *		[db_time] => 0.0007178783416748
 *		[php_time] => 0.001953125
 *		[php_percent] => 73.123270552531
 *		[db_percent] => 26.876729447469
 *	)
 *	mysqlnd enabled: true
 *	-->
 * 
 * prepare-time is the time spent within mysqli->prepare
 * querytime is the time sent executing queries and retrieving their results
 * 
 * numberqueries is the number of ->query calls
 * numberresults is the total rowcount returned from query
 * 
 * total_time is the time of whole page including db
 * php_time is calculated by subtracting the time spent in db from the total
 * this is then used to calculate the percentage split
 * 
 * 
 * mysqlnd is documented as it improves the code reliability and speed
 * 	if it's available, if it is not available, #
 *  a hacky method of querying is used as a fallback that requires many roundtrips
 *  betweeen the mysql server
 */
function output_timer() {
	global $start, $benchmark;
	$benchmark->total_time = microtime(true)-$start;
	$benchmark->db_time = $benchmark->db_preparetime+$benchmark->db_querytime;
	$benchmark->php_time = $benchmark->total_time - $benchmark->db_time;
	$benchmark->php_percent = ($benchmark->php_time/$benchmark->total_time)*100;
	$benchmark->db_percent = ($benchmark->db_time/$benchmark->total_time)*100;
	echo "<!-- Benchmark/stats ;) times are in seconds\n";
	print_r($benchmark);
	echo "mysqlnd enabled: " . (function_exists('mysqli_stmt_get_result')?"true":"false") ."\n";
	echo "-->";
}


/**
 * A mysqli prepared statements wrapper, makes mysqli prepared statements easier to use
 * which are beneficial from a both a security perspective and performance if doing a lot of queries.
 */
class tidy_sql {
	private $db;
	private $types;
	private $stmt;
	
	
	/**
	 * Constructor, prepares the query
	 * 
	 * @param MySqli $db mysqli object
	 * @param String $query query, ? for any data.
	 *      Look at <http://php.net/manual/en/mysqli.prepare.php> for more info.
	 * @param String $types The types, i for int, d for double, s for string, b for blob
	 *      Look at <http://php.net/manual/en/mysqli-stmt.bind-param.php> for more info.
	 */
	public function tidy_sql($db, $query, $types = "") {
		global $benchmark;
		$this->db = $db;
		$this->types = $types;
		
		$s = microtime(true);
		$result = $this->stmt = $db->prepare($query);
		$benchmark->db_preparetime += microtime(true)-$s;
		
		if (!$result) {
			throw new Exception("SQL prepare: ".strval($db->errno)." - ".$db->error);
		}
	}
	
	/** Binds values, executes the query and returns the data.
	 * 
	 * @pre Params: The values for the fields specified in the query string (using ?),
	 *      it uses func_get_args() to retrieve the args, so shows as accepting none (unfortunately).
	 * 
	 * @returns Returns a multidimensional array containing the results. First array is the row, inner array is indexed by the column names.
	 */
	public function query() {
		global $benchmark;
		$s = microtime(true);
		$benchmark->db_numqueries ++;
		
		$args = func_get_args();
		if (count($args) > 0) {
			//param args, bind_param works by reference
			//	so we create a 2nd array consisting of just pointers to the first
			$pargs = array();
			foreach($args as &$arg) { $pargs[] = &$arg; }
			array_unshift($pargs,$this->types);
			call_user_func_array(array($this->stmt, "bind_param"),$pargs);
		}
		
		$exec = $this->stmt->execute();
		
		$benchmark->db_querytime += microtime(true)-$s;
		
		if ($this->stmt->errno != 0) {
			$message = "SQL Execute: ".strval($this->stmt->errno)." - ".$this->stmt->error;
			$this->stmt->reset();
			throw new Exception($message);
		}
		elseif ($this->stmt->result_metadata()) {
			$rows = $this->getRows();
			$this->stmt->reset();
			return $rows;
		}
		else {
			$this->stmt->reset();
			return $exec;
		}
	}
	
	/* This is only used within the query() func.
	 * 
	 * Retrieves all rows for the current query, with the inner array indexed by key
	 * 
	 * The function uses mysqlnd (mysql native driver) if available,
	 *   if not, it falls back to a hack that retrieves the column names
	 *   and retrieves the whole row by index and maps the two together
	 * 
	 * @returns Returns a multidimensional array containing the results. First array is the row, inner array is indexed by the column names.
	 */
	private function getRows() {
		global $benchmark;
		$s = microtime(true);
		
		$output;
		if (function_exists("mysqli_stmt_get_result")) {
			$result = $this->stmt->get_result();
			$rows = array();
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$rows[] = $row;
			}
			$output = $rows;
		}
		
		else { //mysqlnd not being used, error prone :(
			$meta = $this->stmt->result_metadata();
			while ($field = $meta->fetch_field())
			{
				$params[] = &$row[$field->name];
			}

			call_user_func_array(array($this->stmt, 'bind_result'), $params);

			while ($this->stmt->fetch()) {
				foreach($row as $key => $val)
				{
					$c[$key] = $val;
				}
				$result[] = $c;
			}
			$output = isset($result)?$result:array();
		}
		
		$benchmark->db_querytime += microtime(true)-$s;
		$benchmark->db_numresults += count($output);
		return $output;
	}
}

class twig_common { //class as we may chuck more functionality in later
	public $loader;
	public $twig;
	
	public function twig_common() {
		require_once __DIR__."/lib/Twig/Autoloader.php";
		
		Twig_Autoloader::register();
		$this->loader = new Twig_Loader_Filesystem(__DIR__."/admin/tpl/");
		$this->twig = new Twig_Environment($this->loader, array());
	}
}
