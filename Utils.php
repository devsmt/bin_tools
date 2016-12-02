<?php




namespace {
    //----------------------------------------------------------------------------
    // CLI related routines
    //----------------------------------------------------------------------------
    class CLI {
        public static function hasFlag($flag) {
            if (isset($_SERVER['argv']) && !empty($_SERVER['argv'])) {
                $argv = $_SERVER['argv'];
                $s_argv = implode(' ', $argv) . ' ';
                $substr = " --$flag ";
                return strpos($s_argv, $substr) !== false;
            } else {
                return false;
            }
        }

        // stampa stringa colorata
        public static function colored($str, $foreground_color = '', $background_color = '') {

            if (php_sapi_name() != 'cli') {
                return $str;
            }

            // ForeGround
            static $a_fg = [
                'black' => '0;30',
                'red' => '0;31',
                'green' => '0;32',
                'brown' => '0;33',
                'blue' => '0;34',
                'purple' => '0;35',
                'cyan' => '0;36',
                'white' => '0;37',
                // Bold
                'bblack' => '1;30',
                'bred' => '1;31',
                'bgreen' => '1;32',
                'byellow' => '1;33',
                'bblue' => '1;34',
                'bpurple' => '1;35',
                'bcyan' => '1;36',
                'bwhite' => '1;37',
            ];
            // background
            static $a_bg = [
                'black' => '40',
                'red' => '41',
                'green' => '42',
                'yellow' => '43',
                'blue' => '44',
                'magenta' => '45',
                'cyan' => '46',
                'light_gray' => '47',
            ];
            $str_result = '';
            // FG color
            if (isset( $a_fg[$foreground_color])) {
                $str_result .= sprintf("\e[%sm", $a_fg[$foreground_color] );
            }
            // BG color
            if (isset( $a_bg[$background_color])) {
                $str_result .= sprintf("\033[%sm",  $a_bg[$background_color] );
            }
            $str_result .= $str . "\033[0m";
            return $str_result;
        }
    }

    function is($val, $expected_val, $description = '') {
        $pass = ($val == $expected_val);
        CLITest::ok($pass, $description);
        if (!$pass) {
            CLITest::diag("         got: '$val'");
            CLITest::diag("    expected: '$expected_val'");
        }
        return $pass;
    }

    // test util
    function ok($res, $expected, $label) {
        if( $res === $expected ) {
            echo colored("ok $label \n", 'green');
        } else {
            echo colored(sprintf("ERROR(%s)  got=expected  %s==%s \n",
                $label, json_encode($res), json_encode($expected)
            ), 'red');
        }
    }

    // stampa stringa colorata
    function colored($str, $foreground_color = '', $background_color = '') {
        return CLI::colored($str, $foreground_color, $background_color);
    }


    /*
    class CLITest {
        static $errc = 0;
        public static function ok($test, $label, $data = null) {
            if ($test == false) {
                echo CLI::sprintc("ERROR $label: $test", 'red')."\n\n";
                if (!empty($data)) {
                    echo  var_export($data, 1) ;
                }
                self::$errc++;
            } else {
                echo CLI::sprintc("OK $label", 'green')."\n\n";
            }
        }
        public static function diag($l, $data = '') {
            if (!empty($data)) {
                echo CLI::sprintc( $l );
            } else {
                echo CLI::sprintc( $l.':'.var_export($data, 1) );
            }
            echo "\n\n";
        }
    }*/

    function println($msg) { echo $msg . "\n"; }

}

