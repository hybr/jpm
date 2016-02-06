<?php
$arr1 = array (
		1,
		2,
		3,
		4,
		5,
		6,
		7,
		8,
		9,
		10,
		11,
		12,
		13,
		14,
		15,
		16,
		17,
		18,
		19,
		20,
		21,
		22,
		23,
		24,
		25,
		25,
		26,
		
		28,
		29,
		30,
		31,
		32,
		33,
		34,
		35,
		36,
		37,
		38,
		39,
		40,
		41,
		42,
		43,
		44,
		45,
		46,
		47,
		48,
		49,
		50,
		51,
		52,
		53,
		54,
		55,
		56,
		57,
		58,
		59,
		60,
		61,
		62,
		63,
		64,
		65,
		67,
		68,
		69,
		70,
		71,
		72,
		73,
		74,
		75,
		76,
		78,
		79,
		80,
		81,
		82,
		83,
		84,
		85,
		86,
		87,
		88,
		89,
		90,
		91,
		92,
		93,
		94,
		95,
		96,
		97,
		98,
		99 
);
print_r ( $arr1 );
echo '<hr />';

echo "More Storage ";
$arr2 = range ( 1, max ( $arr1 ) );
print_r ( array_diff ( $arr2, $arr1 ) );
echo '<hr />';

echo "More CPU ";
function gap($arr) {
	while ( list ( $k, $v ) = each ( $arr ) ) {
		if ($k != ($v - 1))
			return $k;
	}
	return - 1;
}
print "missing index " . gap ( $arr1 );
echo '<hr />';

echo "Less Storage More CPU ";
$start_counting = 1;
$max_value = count ( $arr1 );
while ( $start_counting <= $max_value - 1 ) {
	if (! (in_array ( $start_counting, $arr1 ))) {
		echo "Value: " . $start_counting . " is missing!" . "<br>";
	}
	$start_counting ++;
}
echo '<hr />';

echo "Less Storage Less CPU ";
sort ( $arr1 );
for($i = 1; $i <= sizeof ( $arr1 ); ++ $i) {
	if ($arr1 [$i + 1] - $arr1 [$i] > 1) {
		echo "Gap between " . $arr1 [$i] . " and " . $arr1 [$i + 1] . "<br />\n";
	}
}

?>