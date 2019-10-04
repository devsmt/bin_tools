<?php
declare(strict_types=1);


// keys myst be lowercase
$commands = [
    //----------------------------------------------------------------------------
    //  AS400
    //----------------------------------------------------------------------------
    'as400'=>'
EDTLIBL
// top:
WRKACTJOB
// ram usage
WRKSYSSTS
DSPSYSSTS
// disk usage
GO DISKTASKS
// kill pgm
WRKUSRJOB USER(usrweb)
// Look at scheduled jobs
WRKJOBSCDE
// messaggi dal sistema
WRKMSGF QCPFMSG
DSPJOBLOG JOB(PHPJOB)
DSPLOG
// diff:  option 54 da PDM lista sorgenti
CMPPFM
Search In File: PDM +25
WRKACTJOB
// call a program
call PGM(R105) PARM(\'99\' \'aaa\') # multiple args
// ls
DISPLIB
// cp
CPYF
// find
WRKOBJ *ALL/EC_CLAGE0F
DSPOBJD OBJ(*ALL/LISTQ*) OBJTYPE(*FILE)
// read db file / CRUD
DSPPFM LIB1/FILE01F
// verificare errori di compilazione
// e quali files con percorso assoluto sono utilizzati
WRKSPLF SELECT(*CURRENT *ALL *ALL *ALL *ALL OR*)
// errori a livello di sistema
DSPMSG QSYSOPR
// job in esebuzione bloccati?
// con quale *LIBL gira il programma?
WRKUSRJOB USER(PHPWEB)
WRKACTJOB job(PHPJOB)
// quali files vengono utilizzati dal programma
DSPPGMREF PHP_OBJ/MYPROGRAM004
// chi ha compilato il programma, sorgenti
DSPPGM PHP_OBJ/MYPROGRAM004
// id formato file, numero campi, lunghezza record
DSPFD MILIB/MYFILE00L
// commento testo dei campi
DSPFFD FILE(LIB1/MATPF00F) OUTPUT(*OUTFILE) OUTFILE(QTEMP/DFF_MATPF)
CPYTOIMPF FROMFILE(qtemp/dff_matpf) TOSTMF(\'/www/zend-applications/MATPF00F.txt\') MBROPT(*ADD) RCDDLM(*CRLF)
',


    'as400_all'=>file_get_contents(__DIR__.'/as400_all.txt'),
];

return $commands;