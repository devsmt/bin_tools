#!/bin/sh
# <?php
# error_reporting(E_ALL|E_STRICT);
# /* this scripts show itself beautified */
# require_once ('PHP/Beautifier.php');
# require_once ('PHP/Beautifier/Batch.php');
# try {
#     $oBeaut = new PHP_Beautifier();
#     $oBeaut->setIndentNumber(4); /* default */
#     $oBeaut->setIndentChar(' '); /* default */
#     $oBeaut->setNewLine("\n"); /* default */
#     $oBeaut->addFilter('ArrayNested');
#     $oBeaut->addFilter('ListClassFunction');
#     //$oBeaut->addFilter('Pear', array( 'add_header'=>'php' ));
#     $oBeaut->setInputFile(__FILE__);
#     $oBeaut->process();
#     if (php_sapi_name() == 'cli') {
#         $oBeaut->show();
#     }
#     // else {
#     //    echo '<pre>'.$oBeaut->show() .'</pre>';
#     // }
#     } catch(Exception $oExp) {
#         echo ($oExp);
#     }
# ?>
if [ ! -n "$1" ]; then
    echo "Syntax is: recurse.sh dirname filesuffix"
    echo "Syntax is: recurse.sh filename"
    echo "Example: recurse.sh temp cpp"
    exit 1
fi

if [ -d "$1" ]; then
#echo "Dir ${1} exists"
if [ -n "$2" ]; then
    filesuffix=$2
else
    filesuffix="*"
fi

#echo "Filtering files using suffix ${filesuffix}"

file_list=`find ${1} -name "*.${filesuffix}" -type f`
    for file2indent in $file_list
    do
        echo "Indenting file $file2indent"
        #!/bin/bash
        #php_beautifier -f "$file2indent" -o indentoutput.tmp -l \"Pear(newline_class=true)\" -l \"Pear(newline_function=true)\" -t1 -l \"Pear(add_header=php)\" -l \"IndentStyles(style=k&r)\"
        mv indentoutput.tmp "$file2indent"

    done
else
if [ -f "$1" ]; then
    echo "Indenting one file $1"
    #!/bin/bash

    # universalindentgui aoutput
    #php_beautifier -f "$1" -o indentoutput.tmp -l \"Pear(newline_class=true)\" -l \"Pear(newline_function=true)\" -t1 -l \"Pear(add_header=php)\" -l \"IndentStyles(style=k&r)\"

    #basic filter as documented
    #php_beautifier -f "$1" -o indentoutput.tmp -s4

    #fuel filter

    #Pear(newline_class=true) Pear(newline_function=true)
    ## k&r
    # NewLines(before=comment:return:break:T_COMMENT,after=T_COMMENT)
    php_beautifier -f "$1" -o indentoutput.tmp -s4 --indent_tabs  -l "Pear(newline_class=true)" -l "Pear(newline_function=true)"  -l "IndentStyles(style=Allman)" -l "ArrayNested()" -l "NewLines(before=return:break:T_COMMENT)"

    mv indentoutput.tmp "$1"

else
echo "ERROR: As parameter given directory or file does not exist!"
echo "Syntax is: call_PHP_Beautifier.sh dirname filesuffix"
echo "Syntax is: call_PHP_Beautifier.sh filename"
echo "Example: call_PHP_Beautifier.sh temp cpp"
exit 1
fi
fi
