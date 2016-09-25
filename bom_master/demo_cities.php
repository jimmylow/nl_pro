<?php
 
// Data could be pulled from a DB or other source
$cities = array(
	array('city'=>'New York', state=>'NY', zip=>'10001'),
	array('city'=>'Los Angeles', state=>'CA', zip=>'90001'),
	array('city'=>'Chicago', state=>'IL', zip=>'60601'),
	array('city'=>'Houston', state=>'TX', zip=>'77001'),
	array('city'=>'Phoenix', state=>'AZ', zip=>'85001'),
	array('city'=>'Philadelphia', state=>'PA', zip=>'19019'),
	array('city'=>'San Antonio', state=>'TX', zip=>'78201'),
	array('city'=>'Dallas', state=>'TX', zip=>'75201'),
	array('city'=>'San Diego', state=>'CA', zip=>'92101'),
	array('city'=>'San Jose', state=>'CA', zip=>'95101'),
	array('city'=>'Detroit', state=>'MI', zip=>'48201'),
	array('city'=>'San Francisco', state=>'CA', zip=>'94101'),
	array('city'=>'Jacksonville', state=>'FL', zip=>'32099'),
	array('city'=>'Indianapolis', state=>'IN', zip=>'46201'),
	array('city'=>'Austin', state=>'TX', zip=>'73301'),
	array('city'=>'Columbus', state=>'OH', zip=>'43085'),
	array('city'=>'Fort Worth', state=>'TX', zip=>'76101'),
	array('city'=>'Charlotte', state=>'NC', zip=>'28201'),
	array('city'=>'Memphis', state=>'TN', zip=>'37501'),
	array('city'=>'Baltimore', state=>'MD', zip=>'21201'),
);	
 
// Cleaning up the term
$term = trim(strip_tags($_GET['term']));
 
// Rudimentary search
$matches = array();
foreach($cities as $city){
	if(stripos($city['city'], $term) !== false){
		// Add the necessary "value" and "label" fields and append to result set
		$city['value'] = $city['city'];
		$city['label'] = "{$city['city']}, {$city['state']} {$city['zip']}";
		$matches[] = $city;
	}
}
 
// Truncate, encode and return the results
$matches = array_slice($matches, 0, 5);
print json_encode($matches);