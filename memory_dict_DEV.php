<?php
declare (strict_types = 1);
// keys myst be lowercase
$commands = [
    //---------------------------------------------------------------------------
    // android
    //---------------------------------------------------------------------------
    'cordova' => '
    cordova create myApp org.apache.cordova.myApp myApp
    cordova plugin add cordova-plugin-camera
    cordova platform add android
    cordova platform update android@6.2.2
    cordova plugin add cordova-plugin-camera --nosave
    cordova platform add android --nosave
    cordova requirements android
    cordova build android --verbose
    cordova clean android
    cordova run android
    # for release
    cordova build android --release -- --keystore="..\android.keystore" --storePassword=android --alias=mykey
    cordova config ls
',
    'android_avd' => '
# scarica una immagine avd
sdkmanager --list
sdkmanager --update
# download a system image
# for testing
sdkmanager "system-images;android-24;default;x86_64"
# for deploy to playstore
sdkmanager "system-images;android-27;google_apis_playstore;x86"
# install a system on a vistual device AVD
avdmanager create avd -n emulator_name -k "system-images;android-24;default;x86_64" -g "default"
avdmanager list avd
avdmanager delete avd -n emulator_name
',
    //
    'android_adb' => '
    # tablet > settings > search USB > revoca autorizzazioni debug USB
    # tablet > settings > search USB > enable debug USB
    # unplug
    sudo service udev restart
    killall adb
    # plug, accept device
    adb devices
',
    //----------------------------------------------------------------------------
    //  java
    //----------------------------------------------------------------------------
    'java' => '
# set the classpath
java -classpath ".:/mylib/*"  org.mypackage.HelloWorld
java -classpath /home/user/myprogram org.mypackage.HelloWorld
# set multiple classpath
java -cp /path/to/folder;/path/lib/supportLib.jar org.mypackage.HelloWorld
# set the CP with a variable
set CLASSPATH=/path/myprogram
java org.mypackage.HelloWorld
# using a jar+manifest
Main-Class: org.mypackage.HelloWorld
Class-Path: lib/supportLib.jar
# invocation
java -jar /path/helloWorld.jar [app arguments]
# @see https://en.wikipedia.org/wiki/Classpath_(Java)
    ',
    //----------------------------------------------------------------------------
    //  dev specific commands
    //----------------------------------------------------------------------------
    // pfff tools
    'bower' => ('bower install --save $lib_name'),
    'npm' => ('
        sudo npm install -g typescript # global install
        npm install --save $lib_name
        npm install --save @types/$lib_name
    '),
    'php_install' => '
        sudo add-apt-repository -y ppa:ondrej/php
        sudo apt-get update
        sudo apt-get install php7.2 php7.2-cli php7.2-common
        # sudo apt search php7.2
        sudo apt-get install php7.2-curl php7.2-gd php7.2-json php7.2-mbstring php7.2-intl php7.2-mysql php7.2-xml php7.2-zip
        sudo apt install php7.*-bcmath php7.*-bz2 php7.*-cli php7.*-common php7.*-curl php7.*-dba php7.*-gd php7.*-gmp php7.*-imap php7.*-json php7.*-ldap php7.*-mbstring php7.*-mcrypt php7.*-mysql php7.*-opcache php7.*-readline php7.*-xml php7.*-zip
        php -v
        php -m
        a2dismod php7.0
        a2enmod php7.2
        systemctl restart apache2
        nano /var/www/html/phpinfo.php && links http://localhost/phpinfo.php
        #
        # install LAMP
        sudo apt install apache2
        sudo apt install mysql-server
        sudo apt install php7.2 libapache2-mod-php7.2 php-mysql
        # additional modules
        sudo apt install php-cli php-curl php-json
        #
    ',
    // generate a standard package dir
    'php_package' => '
Install package in your project:
composer require --dev pds/skeleton
Run the validator:
If no path is specified, the project in which pds-skeleton is installed will be used.
vendor/bin/pds-skeleton validate [path]
Generate a compliant package skeleton by following these steps:
composer require --dev pds/skeleton
Run the generator:
vendor/bin/pds-skeleton generate [path]
    ',
    'php_standard_dirs' => '
If a package has a root-level directory for => then it MUST be named:
command-line executables                    bin/
configuration files                         config/
documentation files                         docs/
PHP source code                             src/
test code                                   tests/
other resource files                        resources/
web server files                            public/
    ',
    'php_unit' => '
    wget --no-check-certificate https://phar.phpunit.de/phpunit.phar
    chmod +x phpunit.phar
    ./phpunit.phar markdown/markdown_test.php
',
    'mysql_install' => '
        sudo apt install mysql-server php-mysql
        sudo mysql_secure_installation
        # use MEDIUM password validation
        sudo mysql -u root -p # I had to use "sudo" since is new installation
    ',
    'php_lint' => (' find ./tests/ -name *.php -type f -exec php -l \'{}\' \; | grep "PHP Parse error:" '),
    'hhvm' => ('
        hhvm -m server -p 8080
        hhvm whatever.php'),
    'php_grep' => "sgrep -e 'array(...)' /var/www/dir/",
    'ag' => ('
        ag --php -i -f "file_put_contents" /var/www*
        ag -G "^(.*).ts$" -i "MemCache" /var/www*
        ag -G "^(.*).js\$"  "var Select " /home/taz/Dropbox/
        '),
    //----------------------------------------------------------------------------
    //  // GIT
    //----------------------------------------------------------------------------
    'git_status' => '
        git status
        git status -uall
        # See what the last commit changed
        git show -p
        # list unstaged files
        git diff --name-only --diff-filter=M`
        # list conficting files
        git diff --name-only --diff-filter=U
    ',
    'git_commit' => '
        # add all already in index
        git commit -m"merge v4" -a
        # commit all modified files
        for file in `git status  | grep modified | awk \'{print $2}\'`; do git commit -m "correzioni" _file_; done
        # change last commit
        git commit --amend
        git commit --amend --no-edit
        git commit --amend --no-verify -m
        # undo last commit
        git reset --soft HEAD^
    ',
    'git_undo' => '
        # undo last file change
        git checkout -- file.php
        # go back and look at an old commit
        git checkout [SHA1]
        # delete all local changes
        git reset --hard && git pull
        # delete local changes to a single file
        git reset {{file}} && git checkout {{file}}
        #
        git checkout --theirs $file_name
        #ATTENZIONE: will drop all your local changes and commits
        # git fetch origin
        # git reset --hard origin/master
    ',
    'git_pull_dryrun' => '
        git fetch origin
        # see change files
        git diff --name-only origin/master
        # see diff line by line
        git diff origin/master directory_foo/file_bar.m
    ',
    'git_revert' => 'git_undo ',
    'git_NLG_workflow' => '
        #
        # @see https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow
        git branch # where are you? should be develop
        #
        # merge & push su CERT
        git checkout cert
        git merge develop # su cert applica develop
        git push
        # merge & push su PROD
        git checkout master
        git merge develop # su master applica develop
        git push
        # come back to develop!
        git checkout develop
        git branch
    ',
    'git_NLG_hotfix' => 'git_branching',
    'git_branching' => '
        # create a new branch named "feature_x" and switch to it using
        git checkout -b feature_x # crea nuovo branch dalla branch e situazione corrente
        # switch back to master
        git checkout master
        # and delete the branch again
        git branch -d feature_x
        # a branch is not available to others unless you push the branch to your remote repository
        git push origin <branch>
        git branch -v -a # mostra anche branch remoti
        # ottiene tutti i commit/branche remoti
        git fetch
        git fetch --all
        @see git_merge_hotfix
    ',
    'git_merge_hotfix' => '
        #
        # hotfix workflow
        #
        git checkout hotfix-name
        commit + push
        # come back to develop! + merge hotfix
        git checkout develop
        git branch
        git merge hotfix-name
        commit + push
        ## merge prod of HOTFIX
        git checkout master
        git pull
        git merge hotfix-schedulerAutoEmail
        git commit
        git push
        # come back to develop!
        git checkout develop && git branch
    ',
    'git_branch_new' => '
        checkout master
        git checkout -b iss53 # crea branch da master
        ',
    'git_merge' => '
        git checkout cert
        git pull
        git merge develop
        git push
        # torna su develop
        git checkout develop
        git branch
        # applica a dev una correzione
        checkout dev
        git branch # should be dev
        git merge iss53 # su dev applica iss53
        #
        git merge --abort # undo/elimina l ultimo merge
     ',
    'git_log' => <<<__END__
        # vedere storia del progetto, cosa è stato committato
        git log -n 10 # only last n commits
        git log -n 2 -p # inspect, mostra diff sui files committati
        git log -n1 --name-only # mostra files dell'ultimo commit
        git log --stat
        git log --after=2015-05-01 --pretty=format:'%s'
        git log --oneline --decorate --graph --all # as tree, DAG-like
        # search specif commit
        git log --author="" # by author
        git log --grep=""
        #
        # visione storia comit DAG-like in CLI
        git log --graph --abbrev-commit --decorate --date=relative --format=format:'%C(bold blue)%h%C(reset) - %C(bold green)(%ar)%C(reset) %C(white)%s%C(reset) %C(dim white) - %an%C(reset)%C(bold yellow)%d%C(reset)' --all
__END__,
    'git_remote_url'=>'
        git config --get remote.origin.url
        # where the repo is stored?
        git remote show origin
    ',
    'git_config'=>"
        # cahe my password 10h
        git config --global credential.helper 'cache --timeout=300'
        git config user.email 'email'
        git config user.name 'user'
        git config color.ui true
        git config format.pretty oneline
    ",
    'git_add'=>'
        # git add variants
        git add
        git add --all :/
        git add -u # add all modified
        git add `git status -uall | egrep "#\tboth modified:" | cut -d: -f2`',
    'git_diff'=>'
        #
        git diff [file]
        git diff [SHA1] [SHA1] # between 2 commits
        git diff --cached
        git difftool
        git diff -w
    ',
    'git_conflict'=>'
        # find conflict
        grep -lr \'<<<<<<<\'
        # get someone copy
        git checkout --ours PATH/FILE
        git checkout --theirs PATH/FILE
        # "Your local changes to the following files would be overwritten by merge:"
        git checkout HEAD^ file/to/overwrite && git pull
    ',
    'git_submodules'=>'
        #
        git submodule
        git submodule update --init --recursive
        git submodule sync && git submodule update --init
        ',
    'git_stash'=>'
        # to save local tests
        git stash save --keep-index
        git stash drop
        #
        git stash
        git stash list
        git stash pop
        git stash save
        git stash save --patch
        git stash show -p
    ',
    'git_as_svn'=>'
        svn command           git command
        svn checkout url      git clone url
        svn update            git pull
        svn diff              git diff
        svn status            git status
        svn revert path       git checkout path
        svn add file          git add file
        svn rm file           git rm file
        svn mv file           git mv file
        svn commit            git commit file; git push
        svn log               git log
        ',
    'git_version_number'=>'
        # get version from git
        git rev-list HEAD --count
    ',
    'git_tag'=>'git tag 1.0.0 1b2e1d63ff',
    'github_pull_request'=>"
        Fork it ( https://github.com/*/*/fork )
        Create your feature branch (git checkout -b my-new-feature)
        Commit your changes (git commit -am 'Add some feature')
        Push to the branch (git push origin my-new-feature)
        Create a new Pull Request   in web UI   ",
    'git_etc'=><<<__END__
__END__
    ,
    //----------------------------------------------------------------------------
    //
    //----------------------------------------------------------------------------
    'svn_get'=>'svn cat -r 175 mydir/myfile > mydir/myfile',
    'svn_ignore'=>'
        Add the directory, while ignoring all of the files it contains:
        svn add -N [directory]
        cd [directory]
        svn propset svn:ignore "*.*" .
        then Commit
    ',
    'svn_file_version'=>'svn info --show-item revision  file_path',
    //
    'ctags'=>('
ctags -R --languages=PHP --php-kinds=-vj
ctags -R --languages=PHP --php-kinds=-vj --exclude="*server/data" --exclude="*server/__lib*"  /var/www/xxxxxxxxx/server
ctags -R --languages=php --php-kinds=cdfint --fields=+aimS --tag-relative=yes --totals=yes --exclude=tags --exclude="config*.php" \
       --exclude="lang/*" --exclude="install/lang/*" --exclude="vendor" --exclude="node_modules" --exclude="moodledata/*" \
       --exclude=".git/*" --extra=+q
cd $path/../public/js && javascript-ctags
ctags -R --append --languages=typescript /var/www/xxxxxxxxx/client/src/ts
# configure ctags
cat ~/.ctags
--langdef=typescript
--langmap=typescript:.ts
--regex-typescript=/^[ \t]*(export)?[ \t]*class[ \t]+([a-zA-Z0-9_]+)/\2/c,classes/
--regex-typescript=/^[ \t]*(export)?[ \t]*module[ \t]+([a-zA-Z0-9_]+)/\2/n,modules/
--regex-typescript=/^[ \t]*(export)?[ \t]*namespace[ \t]+([a-zA-Z0-9_]+)/\2/n,modules/
--regex-typescript=/^[ \t]*(export)?[ \t]*function[ \t]+([a-zA-Z0-9_]+)/\2/f,functions/
--regex-typescript=/^[ \t]*export[ \t]+var[ \t]+([a-zA-Z0-9_]+)/\1/v,variables/
--regex-typescript=/^[ \t]*var[ \t]+([a-zA-Z0-9_]+)[ \t]*=[ \t]*function[ \t]*\(\)/\1/v,varlambdas/
--regex-typescript=/^[ \t]*(export)?[ \t]*(public|private)[ \t]+(static)?[ \t]*([a-zA-Z0-9_]+)/\4/m,members/
--regex-typescript=/^[ \t]*(export)?[ \t]*interface[ \t]+([a-zA-Z0-9_]+)/\2/i,interfaces/
--regex-typescript=/^[ \t]*(export)?[ \t]*enum[ \t]+([a-zA-Z0-9_]+)/\2/e,enums/
--regex-typescript=/^[ \t]*type[ \t]+([a-zA-Z0-9_]+)/\1/e,enums/
--regex-Basic=/$x/x/x/e/ TODO: gestisce let _log = (step:string, V:Backbone.View ) => {
--regex-typescript=/^[ \t]*let[ \t]+([a-zA-Z0-9_]+)[ \t]+=[ \t]+\(/\1/f,functions/
'),
    'composer'=>('
        # get the latest
        composer require predis/predis
        # modifiers: https://getcomposer.org/doc/articles/versions.md
        #
        # get exact version:
        composer require "swiftmailer/swiftmailer:5.4.3"
        #
        # "get this or next release" equivalent to >=5.4 < 6.0.0, but ~5.4.3 is equivalent to >=5.4.3 <5.5.0
        composer require "swiftmailer/swiftmailer:~5.4.0"
        #
        # "Caret Version Range" package:^1.2.3
        # similar to ~ but accept only non-breaking updates, ^1.2.3 is equivalent to >=1.2.3 <2.0.0
        #
        # "1..*" means the biggest available on the 1.3 series
        #
        # global tools, requires setting  ~/.bashrc  export PATH=~/.composer/vendor/bin:$PATH
        # or export PATH="$(composer config -g home)/vendor/bin:$PATH"
        composer global require vimeo/psalm
        composer global require phpunit/phpunit
        composer global require phpunit/dbunit
        composer global require sebastian/phpcpd
        composer global require phploc/phploc
        composer global require phpmd/phpmd
        composer global require squizlabs/php_codesniffer
        composer global update
    '),
    'redis'=>(
        'redis-cli
set test test1
get test
keys user:* # le chiavi che rispondono alla regex
del test
    '),
    'phpmd'=>('phpmd  /path/to/file.php text unusedcode'),
    'inotifywait'=><<<'__END__'
# available actions: ,moved_to,create
BASE_DIR=$(cd $(dirname "$0"); pwd)/..
inotifywait -e close_write -m $BASE_DIR/public/* |
while read -r directory events filename; do
  #echo "$filename change!";
  $BASE_DIR/bin/compile file $filename
done
# run cmd tool everytime a file in a directory is modified
while true ; do inotifywait -r -e MODIFY dir/ && ls dir/ ; done;
__END__
    ,
    'regexp'=>('
Regex quick reference
[abc]      A single character: a, b or c
[^abc]     Any single character but a, b, or c
[a-z]      Any single character in the range a-z
[a-zA-Z]   Any single character in the range a-z or A-Z
^          Start of line
$          End of line
\A         Start of string
\z         End of string
.          Any single character
\s         Any whitespace character
\S         Any non-whitespace character
\d         Any digit
\D         Any non-digit
\w         Any word character (letter, number, underscore)
\W         Any non-word character
\b         boundary: "/\bweb\b/i"  verrà riconosciuta solo "web" e non "webbing"
(...)      Capture everything enclosed
(a|b)      a or b
a?         Zero or one of a
a*         Zero or more of a
a+         One or more of a
a{3}       Exactly 3 of a
a{3,}      3 or more of a
a{3,6}     Between 3 and 6 of a
options:   i case insensitive m make dot match newlines x ignore whitespace in regex o perform #{...} substitutions only once
^[0-9]+$   //match just ints
^          ->   the start of a line.
[0-9]      -> Matches any digit between 0 and -
+          -> Matches one or more instance of the preceding expression.
$          -> Signifies the end of the line.
'),
    // color manipulation
    'color'=>'
    pastel list
    ',
    'geoip'=>'
    /usr/bin/geoipupdate
    /usr/bin/geoiplookup -v 123.23.45.32
    # php has_attribute a geoip extension
    ',
    'php_processing'=>'
        echo "test" | php /tmp/test.php
    ',
    'docker'=>'
# get the image from registry
docker pull php
docker pull redis:3.0.0
# download Apache with latest PHP on /path/to/your/php/files as the document root, visible at  localhost:8080
# This will initialize and launch your container.
# -d makes it runs in the background.
docker run -d --name my-php-webserver -p 8080:80 -v /path/to/your/php/files:/var/www/html/ php:apache
docker logs b4ec9
# start+login into a bash session
docker run -it ubuntu /bin/bash
// CTRL + P + Q  # put container in back ground
# login into shell of a running contrainer
docker attach 113e7c31aae9
# To stop and start it, simply run
docker stop my-php-webserver
docker start my-php-webserver
# build a VM from definition above
docker build -t <Image name> .
# list builded VM
docker images
# run the new VM, flag -d tells that the container should run as background job
docker run -p 80:80 -d <Image name>
# check container CPU usage
docker ps
docker ps -a #mostra anche img non attivi
# check VM logs
docker logs <Container id>
#
docker top 33
docker ps
# delete a container
docker rm 113e7c
# -v data volume, condividere filesize tra container (mountpoint /logs) e OS host (/var/data_volume/logs)
docker run -it -v /var/data_volume/logs:/logs ubuntu /bin/bash
#
#
docker run -d httpd:2.4 # non espone la porta del container
docker run -d -p 8088:80 httpd:2.4  # espone porta locale 8088 sul container 80
docker-machine ip # ottiene Ip container, a cui puntare il browser, quindi http://192.168.99.100:8088
@see dockerfile
',
    'dockerfile'=>'
# Create a new file named Dockerfile in the root folder of project and then put the following contents:
FROM php:7.0-apache   # which image should be used as base of the new image
COPY /etc/php/php.ini /usr/local/etc/php/ # upload php.ini file to our image
COPY . /var/www/html/ # copy file projects to VM
EXPOSE 80
#
#ENV <chiave>=<valore>
# to get the value, of key FTP_HOME,is $FTP_HOME
#
# exec a command at boot
RUN <comando> <parametro1> ... <parametroN>
# spostare file e directory at boot
ADD <src> <dest>
#eseguire un comando all’interno del container non appena questo si è avviato, restituendo il risultato all host console
ENTRYPOINT <comando> <parametro_1> ... <parametro_n>
ENTRYPOINT ["ls"] # esempio
# cmd è uguale ma può essere sovrascritto avviando la macchina dall host
CMD <comando> <parametro_1> ... <parametro_n>
# sposta il cwd al boot
WORKDIR /path1/path2
------------------------------
EXPOSE
Grazie a questa istruzione è possibile dichiarare su quali porte il container resterà in ascolto. Tale istruzione non apre direttamente le porte specificate, ma grazie ad essa Docker saprà, in fase di avvio dell’immagine, che sarà necessario effettuarne il forwarding. La sintassi è molto semplice:
EXPOSE <porta_1> [<porta_n>]
Copy
È possibile inserire più porte semplicemente aggiungendo uno spazio. Una volta effettuato il build del container, all’avvio dello stesso potremo utilizzare il parametro -P in modo da esporre le porte dichiarate nell’attributo EXPOSE su delle porte casuali. Il tag -P (maiuscolo) si comporta in maniera del tutto simile al tag -p (minuscolo), con l’unica differenza che nell’ultimo caso eravamo noi a dover scegliere sia la porta del container che quella dell’host.
Una volta avviato il container, per sapere su quale porta è stata mappata la porta esposta nel Dockerfile, abbiamo a disposizione l’istruzione docker port <porta esposta> <nome del container | id del container>. Se nel dockerfile abbiamo esposto la porta 4444 e il nostro container si chiama htmlit, il comando sarà:
docker port 4444 htmlit
------------------------------
VOLUME ["/www"]
# Quando avvieremo il nostro container, potremo specificare a quale path locale far corrispondere il path /www definito nel container
# partendo dal Dockerfile, genererà l’immagine
docker build
',
    'docker_build'=>'
## dockerfile
FROM httpd:2.4
LABEL Author="xxx vvv"
EXPOSE 80
COPY ./src/ /usr/local/apache2/htdocs/
docker build -t esempio_1:v1 .
# il . che rappresenta il contesto di build. Questo è un concetto fondamentale in quanto, come già anticipato, darà un senso ai path relativi
docker run -d --name esempio_1 -p 8088:80 esempio_1
# usa per vedere dove è installata esattamente
docker-machine ip
',
];
return $commands;