<?
$options_query = mysql_query("SELECT * FROM `options`");
$options = [];
while($row_opt = mysql_fetch_assoc($options_query)){
	$options[$row_opt["name"]] = $row_opt["value"];
}

$turbo_levels = [
	1=>["table"=>"turbo_column","level"=>1,"name"=>"Турбо дерево"],
	2=>["table"=>"turbo_wood","level"=>1,"name"=>"Резервное окно 1*"],
	3=>["table"=>"turbo_column","level"=>2,"name"=>"Турбо дерево"],
	4=>["table"=>"turbo_wood","level"=>2,"name"=>"Резервное окно 2*"],
	5=>["table"=>"turbo_column","level"=>3,"name"=>"Турбо дерево"],
	6=>["table"=>"turbo_wood","level"=>3,"name"=>"Резервное окно 3*"],
	7=>["table"=>"turbo_column","level"=>4,"name"=>"Турбо дерево"],
	8=>["table"=>"turbo_wood","level"=>4,"name"=>"Резервное окно 4*"],
	9=>["table"=>"turbo_column","level"=>5,"name"=>"Турбо дерево"],
	10=>["table"=>"turbo_wood","level"=>5,"name"=>"Резервное окно 5*"],
	11=>["table"=>"turbo_column","level"=>6,"name"=>"Турбо дерево"],
	12=>["table"=>"turbo_wood","level"=>6,"name"=>"Резервное окно 6*"],
	13=>["table"=>"turbo_column","level"=>7,"name"=>"Турбо дерево"]
];

$turbo_forward = [
	"turbo_column" => [1=>1,2=>3,3=>5,4=>7,5=>9,6=>11,7=>13],
	"turbo_wood" => [1=>2,2=>4,3=>6,4=>8,5=>10,6=>12],
];

?>