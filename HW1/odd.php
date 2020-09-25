<?php
    
    function primeNumber($number){
        
        $output = ""; // setting the return value to null
        
        if ($number < 0) // Edge case #1
            $output = "Negative Number";
        elseif ($number == 0 || $number == 1) // Edge case #2
            $output = "";
        elseif ($number > 1) { // general case
            
            for ($i = 2; $i < $number; $i ++) {
                $isPrime = true; // flag for checking prime
                for($j = 2; $j < $i; $j ++)
                    if($i % $j == 0)
                        $isPrime = false;
                if ($isPrime == true)
                     $output .= "$i "; // Adding prime numbers
                
            }
            echo "Printing prime numbers from 0 to $number: $output <br>";
            return $output;
        }
        
    }
    
    function tester() {
        // Test 1
          if (primeNumber(0) == "")
              echo "Test 1: Passed <br><br>";
          else
              echo "Test 1: Failed <br><br>";
          // Test 2
          if (primeNumber(1) == "")
              echo "Test 2: Passed <br><br>";
          else
              echo "Test 2: Failed <br><br>";
          // Test 3:
          if (primeNumber(11) == "2 3 5 7 ")
              echo "Test 3: Passed <br><br>";
          else
              echo "Test 3: Failed <br><br>";
          // Test 4
          if (primeNumber(200) == "2 3 5 7 11 13 17 19 23 29 31 37 41 43 47 53 59 61 67 71 73 79 83 89 97 101 103 107 109 113 127 131 137 139 149 151 157 163 167 173 179 181 191 193 197 199 ")
              echo "Test 4: Passed <br><br>";
          else
              echo "Test 4: Failed <br><br>";
          // Test 5: Empty string
          if (primeNumber("") == "") // Empty string
              echo "Test 5: Passed <br><br>";
          else
              echo "Test 5: Failed <br><br>";
          // Test 6: String
          if (primeNumber("Hello!") == "") // string
              echo "Test 6: Passed <br><br>";
          else
              echo "Test 6: Failed <br><br>";
    }
    
    tester();
    
?>
