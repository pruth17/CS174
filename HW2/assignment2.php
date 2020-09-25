<?

function romanValues($r){
    if($r == "I")
        return 1;
    if($r == "V")
        return 5;
    if($r == "X")
        return 10;
    if($r == "L")
        return 50;
    if($r == "C")
        return 100;
    if($r == "D")
        return 500;
    if($r == "M")
        return 1000;
    else
        return -1;
}

function romanToDecimals($str) {
    $res = 0;
    for ($i = 0; $i < strlen($str); $i ++) {
        $s1 = romanValues($str[$i]);
        
        if($i + 1 < strlen($str)){
            $s2 = romanValues($str[$i+1]);
            
            if($s1 >= $s2) {
                $res = $res + $s1;
            }
            else {
                $res = $res + $s2 - $s1;
            }
        }
        else {
            $res = $res + $s1;
            $i ++;
        }
    }
    return $res;
    
}

function testConvertRoman()
{
    $testCases = array("VI", "IV", "MCMXC", "IX", "I", "III", "V", "M", "C", "MCXX", "DXCIII");
    $expectedOutput = array(6, 4, 1990, 9, 1, 3, 5, 1000, 100, 1120, 593);
    for ($i = 0; $i < 11; $i ++)
        if (romanToDecimals($testCases[$i]) == $expectedOutput[$i])
            echo "Test " . ($i + 1) . ": [Passed] " . " => " . $testCases[$i] . " = " . $expectedOutput[$i] . "<br>";
}
testConvertRoman(); // Test the program

?>
