<?
require "../../lib.php";
require_once "{$root}/lib/Twig/Autoloader.php";
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("{$root}/admin/tpl/");
$twig = new Twig_Environment($loader, array());


$template = $twig->loadTemplate('students.html');
echo $template->render(array("url"=>$url, "title"=>"test","content"=>"hello world!"));

