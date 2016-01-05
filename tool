#!/usr/bin/env hhvm
<?php
/*
scopo del programma
// unified lint + compiler + beautifier/fmt + compressor
// for css js php
*/



// ogni linguaggio deve implementare le azioni previste
interface ILangCommand {
    public function lint();
    public function compile();
    public function format();
    public function compress();
}

// ogni linguaggio ha le relative funzioni incapsulate in una classe
class CSSLang implements ILangCommand {
    public $filename;
    public function lint(){}
    public function compile(){}
    public function format(){}
    public function compress(){}
}
class JSLang implements ILangCommand {
    public $filename;
    public function lint(){}
    public function compile(){}
    public function format(){}
    public function compress(){}
}
class PHPLang implements ILangCommand {
    public $filename;
    public function lint(){
        return `php -l {$this->filename}`;
    }
    public function compile(){
        // unapplicable
    }
    public function format(){
        return `php_fmt {$this->filename}`;
    }
    public function compress(){
        // unapplicable
    }
}

// il front controller determina il linguaggio corrente dall'estensione del file e
// passa l'azione al controller opportuno
class LangDispatcher {
    // ritorna un controller
    public static function getController($filename) {
        $extension = strtolower( ( new SplFileInfo($filename) )->getExtension() );
        switch($extension){
          case 'js':
              $c = new JSLang();
          break;

          case 'ts':
              $c = new TSLang();
          break;

          case 'php':
              $c = new PHPLang();
          break;

          case 'css':
              $c = new CSSLang();
          break;

          default:
              die(__FUNCTION__.' unhandled file type '.$filename );
          break;
        }
        // switch extension
        $c->filename = $filename;
        return $c;
    }
}


//----------------------------------------------------------------------------
//  utils
//----------------------------------------------------------------------------
function ok($test, $description = '') {
    return TestCLI::ok($test, $description, $data = null);
}
function is($val, $expected_val, $description = '') {
    $pass = ($val == $expected_val);
    TestCLI::ok($pass, $description);
    if (!$pass) {
        TestCLI::diag("         got: '$val'");
        TestCLI::diag("    expected: '$expected_val'");
    }
    return $pass;
}
class TestCLI {
    static $errc = 0;
    public static function ok($test, $label, $data = null) {
        if ($test == false) {
            echo CLI::sprintc("ERROR $label: $test\n\n",'red');
            if (!empty($data)) {
                echo CLI::sprintc( var_export($data, 1),'');
            }
            Test::$errc++;
        } else {
            echo CLI::sprintc("OK $label </p>\n\n",'green');
        }
    }
    public static function diag($l, $data = '') {
        if (!empty($data)) {
            echo CLI::sprintc( $l );
        } else {
            echo CLI::sprintc( $l.':'.var_export($data, 1) );
        }
    }
}

// cli utils
class CLI {
    //
    public function parse(array $argv) {
        //$qs='--host=localhost --db=test';
        $qs = implode(' ', $argv);
        $a_p = explode('--', $qs);
        foreach ($a_p as $k => $v) {
            $a_par = explode('=', $v);
            if (isset($a_par[1])) {
                $this->args[$a_par[0]] = trim($a_par[1]);
            } else {
                // l'unico parametro che non ha il char '=' e un elemento seguente
                $this->action = $a_par[0];
            }
        }
    }

    // verifica quali flags tra quelli definiti sono stati usati
    public static function getFlags($argv, array $a_options){
        return $a_options;
    }

    //
    public static function getUsageFlags($a_options) {
        $s = '';
        foreach($a_options as $k=>$v){
            $s .= 'TODO';
        }
        return $s;
    }

    // Returns colored string
    public static function sprintc($str, $foreground_color = null, $background_color = null) {
        // Set up shell colors
        $a_fg = [
        'black'=>'0,30',
        'dark_gray'=>'1,30',
        'blue'=>'0,34',
        'light_blue'=>'1,34',
        'green'=>'0,32',
        'light_green'=>'1,32',
        'cyan'=>'0,36',
        'light_cyan'=>'1,36',
        'red'=>'0,31',
        'light_red'=>'1,31',
        'purple'=>'0,35',
        'light_purple'=>'1,35',
        'brown'=>'0,33',
        'yellow'=>'1,33',
        'light_gray'=>'0,37',
        'white'=>'1,37',
        ];
        // background
        $a_bg = [
        'black'=>'40',
        'red'=>'41',
        'green'=>'42',
        'yellow'=>'43',
        'blue'=>'44',
        'magenta'=>'45',
        'cyan'=>'46',
        'light_gray'=>'47',
        ];
        $s = '';
        // Check if given foreground color found
        if (isset($a_fg[$foreground_color])) {
            $s.= "\033[" . $a_fg[$foreground_color].'m';
        }
        // Check if given background color found
        if (isset($a_bg[$background_color])) {
            $s.= "\033[" . $a_bg[$background_color].'m';
        }
        // Add string and end coloring
        $s .= $str . "\033[0m";
        return $s;
    }
}
// actions
class ProgController {
    static $a_flags = array();
    public static function actionUsage($argv){
        $usage_flags = CLI::getUsageFlags(self::$a_flags);
        return <<<__END__
uso:
    {$argv[0]} [action] $usage_flags
uso del programma
__END__;
    }

    // perform unit tests for this program
    public static function actionTest() {
        return true;
    }
}

class CommandLineOption {
    public function __construct($descr, Callable $cb) {
        $this->descr = $descr;
        $this->cb    = $cb   ;
    }
}


//------------------------------------------------------------------------------
//  main
//------------------------------------------------------------------------------
ProgController::$a_flags = [
    'go'   => new CommandLineOption('not a simulation', function(){ }),
    'test' => new CommandLineOption('do common tests', function(){ })
];
$a_flags = CLI::getFlags($argv, ProgController::$a_flags );
$action = isset($argv[1])?$argv[1]:'';
switch($action) {
    case 'format':
        $filename = $argv[2];
        $r = LangDispatcher::getController($filename)->format();
        die($r);
    break;

    case 'lint':
        $filename = $argv[2];
        $r = LangDispatcher::getController($filename)->lint();
        die($r);
    break;
    case 'compile':
        $filename = $argv[2];
        $r = LangDispatcher::getController($filename)->format();
        die($r);
    break;
    case 'compress':
        $filename = $argv[2];
        $r = LangDispatcher::getController($filename)->format();
        die($r);
    break;


    case 'test':
        die( ProgController::actionTest() );
    break;
    default:
        die( ProgController::actionUsage($argv) );
    break;
}

