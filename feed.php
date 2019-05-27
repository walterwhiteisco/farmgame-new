<?php
include_once('functions.php');
$obj = new Project();
$rounds_data = $obj->getRounds();
if($rounds_data <= 0)
{
    $round_no = 1;
}
else
{
    $round_no = $rounds_data + 1;
}
$alive_entities = $obj->getAliveEntities();//get alive entites before each feed
$alive_array = explode(',',$alive_entities);
$random_keys = array_rand($alive_array);
$feed = $alive_array[$random_keys];
$feed == '1' ? $farmer = 1 : $farmer = 0;
$feed == '2' ? $cow1 = 1 : $cow1 = 0;
$feed == '3' ? $cow2 = 1 : $cow2 = 0;
$feed == '4' ? $bunny1 = 1 : $bunny1 = 0;
$feed == '5' ? $bunny2 = 1 : $bunny2 = 0;
$feed == '6' ? $bunny3 = 1 : $bunny3 = 0;
$feed == '7' ? $bunny4 = 1 : $bunny4 = 0;
$data = [
	'round_no' => $round_no,
	'farmer' => $farmer,
	'cow1' => $cow1,
	'cow2' => $cow2,
	'bunny1' => $bunny1,
	'bunny2' => $bunny2,
	'bunny3' => $bunny3,
	'bunny4' => $bunny4
];
$insert_round = $obj->insertRound($data);
if($insert_round)
{
    $response = array('success' => 1);
    echo json_encode($response);
}
?>