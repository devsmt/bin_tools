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



    'mysql_install' => '
        sudo apt install mysql-server php-mysql
        sudo mysql_secure_installation
    ',
    'php_lint' => (' find ./tests/ -name *.php -type f -exec php -l \'{}\' \; | grep "PHP Parse error:" '),
    'hhvm' => ('
        hhvm -m server -p 8080
        hhvm whatever.php'),
    'php_grep' => "sgrep -e 'array(...)' /var/www/dir/",
    'ag' => ('
        ag --php -i -f "file_put_contents" /var/www*
        ag -G "^(.*).ts$" -i "MemCache" /var/www* '),
    // GIT
    'git' => '
# where is located?
# where the repo is stored?
git remote show origin
# find conflict
grep -lr \'<<<<<<<\'
# get someone copy
git checkout --ours PATH/FILE
git checkout --theirs PATH/FILE
# "Your local changes to the following files would be overwritten by merge:"
git checkout HEAD^ file/to/overwrite && git pull
# delete all local changes
git reset --hard && git pull
# delete local changes to a single file
git reset {{file}} && git checkout {{file}}
# git add variants
clear; git add --all --patch
git add
git add --all :/
git add -u # add all modified
git add `git status -uall | egrep "#\tboth modified:" | cut -d: -f2`
git branch
git branch -v -a
# add all already in index
git commit -m"merge v4" -a
# commit all modified files
for file in `git status  | grep modified | awk \'{print $2}\'`; do git commit -m "correzioni" $file; done
# change last commit
git commit --amend
git commit --amend --no-edit
git commit --amend --no-verify -m
# skip verify hooks
git commit --no-verify -m
git diff
git diff --cached
git difftool
git diff -w
#
git fetch
git fetch --all
git fetch origin && git merge origin/master
git fetch origin && git rebase origin/master
git grep
gitk >/dev/null 2>&1
git merge
git merge origin/master
git mergetool
#
git push
git push origin
git push origin --force-with-lease
git push origin --tags
#
git quicklog-long
git quicklog -n 20
git show -p
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
#
git status
git status -uall
#
git submodule
git submodule update --init --recursive
git submodule sync && git submodule update --init
#
git unstage
git up
# get version from git
git rev-list HEAD --count
vim `git diff --name-only --diff-filter=M`
vim `git diff --name-only --diff-filter=U`
'
    ,
    'git_log' => ("
        git log -n 1
        git log --after=2015-05-01 --pretty=format:'%s'
        git log --oneline --decorate --graph --all
    "),
    'git_remote_url' => ('git config --get remote.origin.url'),
    'git_branch' => ('git checkout -b iss53'),
    'git_merge' => ('git merge iss53'),
    'git_switch_to_master' => ('git checkout master'),
    'git_reset_file' => ('git checkout --theirs $file_name'),
    //
    //
    //
    'svn_get' => 'svn cat -r 175 mydir/myfile > mydir/myfile',
    'svn_ignore' => '
        Add the directory, while ignoring all of the files it contains:
        svn add -N [directory]
        cd [directory]
        svn propset svn:ignore "*.*" .
        then Commit
    ',
    'svn_file_version' => 'svn info --show-item revision  file_path',
    //
    'ctags' => ('
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
    'composer' => ('
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
        # "1.3.*" means the biggest available on the 1.3 series
    '),
    'redis' => (
        'redis-cli
set test test1
get test
keys user:* # le chiavi che rispondono alla regex
del test
    '),
    'phpmd' => ('phpmd  /path/to/file.php text unusedcode'),
    'inotifywait' => <<<'__END__'
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
    'docker' =>  '
# download Apache with latest PHP on /path/to/your/php/files as the document root, visible at  localhost:8080
# This will initialize and launch your container. -d makes it runs in the background.

docker run -d --name my-php-webserver -p 8080:80 -v /path/to/your/php/files:/var/www/html/ php:apache

# To stop and start it, simply run
docker stop my-php-webserver
docker start my-php-webserver

'  ,


];
return $commands;