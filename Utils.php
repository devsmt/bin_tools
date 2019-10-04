<?php
namespace {
    //----------------------------------------------------------------------------
    // CLI related routines
    //----------------------------------------------------------------------------
    class CLI {
        //
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
        //
        // parsing argomenti con dato
        //
        // @param   $argv
        // @return hash [ param => val ]
        //
        public static function getConsoleArgs($argv = null) {
            if (empty($argv)) {
                if (isset($_SERVER['argv']) && !empty($_SERVER['argv'])) {
                    $argv = $_SERVER['argv'];
                }
            }
            $args = [];
            foreach ($argv as $p) {
                if (is_string($p)) {
                    // TODO: gestire il singolo flag
                    // echo "arg: '$p' ".PHP_EOL;
                    // if (preg_match("/$--([a-z_0-9]+)([^=\s]+)^/", $p, $regs)) {
                    //
                    //
                    // } else
                    if (preg_match("/--([a-z_0-9]+)[=](.+)/", $p, $regs)) {
                        $k = $regs[1];
                        $v = $regs[2];
                        $args[$k] = $v;
                    }
                }
            }
            return $args;
        }
        //
        // test:
        // $str1= "gksrek";
        // $str2 = "geeksforgeeks";
        // $t = str_is_subsequence($str1, $str2, $m, $n)  ;
        //
        //------------------------------------------------------------------------------
        //  colored output / CliUI
        //------------------------------------------------------------------------------
        // ForeGround colors
        static $a_fg = [
            'black' => '0;30',
            'dark_gray' => '1;30',
            'blue' => '0;34',
            'light_blue' => '1;34',
            'green' => '0;32',
            'light_green' => '1;32',
            'cyan' => '0;36',
            'light_cyan' => '1;36',
            'red' => '0;31',
            'light_red' => '1;31',
            'purple' => '0;35',
            'light_purple' => '1;35',
            'brown' => '0;33',
            'yellow' => '1;33',
            'light_gray' => '0;37',
            'white' => '1;37',
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
        // usa FG o BG
        public static function getColoredString(string $str, string $foreground_color = 'green', string $background_color = null): string{
            $s = '';
            // FG color
            if (isset(self::$a_fg[$foreground_color])) {
                $s .= "\e[" . self::$a_fg[$foreground_color] . 'm';
            }
            // BG color
            if ($background_color) {
                if (isset(self::$a_bg[$background_color])) {
                    $s .= "\033[" . self::$a_bg[$background_color] . 'm';
                }
            }
            $s .= $str . "\033[0m";
            return $s;
        }
        // stampa stringa colorata
        public static function printc(string $str, string $foreground_color = 'green', string $background_color = null): void {
            //echo "\n$str\n";
            echo self::getColoredString($str, $foreground_color, $background_color) . "\n";
        }
    }
    // formatta le occorrenze $query all'interno del testo $str
    function str_highlight($str, $search, $replacement = '<em>${0}</em>') {
        if (!empty($search)) {
            $ind = stripos($str, $search);
            $is_found = $ind !== false;
            $len = strlen($search);
            if ($is_found) {
                // usa espressione regolare per preservare il case della parola cercata
                $pattern = "/$search/i";
                $str = preg_replace($pattern, $replacement, $str);
            }
        }
        // if (mb_detect_encoding($str) != "UTF-8") {
        //     $str = utf8_encode($str);
        // }
        return $str;
    }
    function str_contains($str, $search) {
        return stripos($str, $search) !== false;
    }
    // restituisce il primo valore non vuoto
    function coalesce() {
        $args = func_get_args();
        foreach ($args as $arg) {
            if (!empty($arg)) { // 0 do not pass
                return $arg;
            }
        }
        return null;
    }
    //
    // $h_data2 = array_map_keys($h_data,
    // $f_k_mapper = function ($k, $v) {return $k;},
    // $f_v_mapper = function ($v, $k) {return $v;} );
    //
    // map both keys and values
    function array_map_keys(array $a1, \Closure $f_k_mapper = null, \Closure $f_v_mapper = null) {
        $f_k_mapper = $f_k_mapper ?? function ($k, $v) {return $k;};
        $f_v_mapper = $f_v_mapper ?? function ($v, $k) {return $v;};
        $a2 = [];
        foreach ($a1 as $k => $v) {
            $a2[$f_k_mapper($k, $v)] = $f_v_mapper($v, $k);
        }
        return $a2;
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
        if ($res === $expected) {
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
    //
    // class CLITest {
    //     static $errc = 0;
    //     public static function ok($test, $label, $data = null) {
    //         if ($test == false) {
    //             echo CLI::sprintc("ERROR $label: $test", 'red')."\n\n";
    //             if (!empty($data)) {
    //                 echo  var_export($data, 1) ;
    //             }
    //             self::$errc++;
    //         } else {
    //             echo CLI::sprintc("OK $label", 'green')."\n\n";
    //         }
    //     }
    //     public static function diag($l, $data = '') {
    //         if (!empty($data)) {
    //             echo CLI::sprintc( $l );
    //         } else {
    //             echo CLI::sprintc( $l.':'.var_export($data, 1) );
    //         }
    //             echo "\n\n";
    //     }
    // }
    //
    function println($msg) {echo $msg . "\n";}
    // stmap un msg progressivo
    function print_prgr($str = '') {
        echo $str . "\r";
    }
    function request_is_cli() {
        return strtoupper(PHP_SAPI) === 'CLI';
    }
    define('SIZE_KB', pow(1024, $factor = 1), false);
    define('SIZE_MB', pow(1024, $factor = 2), false);
    // ottieni una chiave di hash o un defualt
    function h_get($h, $k, $def = null) {
        if (array_key_exists($k, $h)) {
            return $h[$k];
        } else {
            return $def;
        }
    }
}