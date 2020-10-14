<?php
declare (strict_types = 1);
// keys myst be lowercase
$commands = [
    //----------------------------------------------------------------------------
    //  bash basics
    //----------------------------------------------------------------------------
    'ls_dir' => 'find . -maxdepth 1 -type d | sort',
    'bash_while' => ('while [ 1 ]; do  cmd;  done'),
    'bash_for' => ('
    for i in {A..C} "label:" {1..3}; do echo $i; done
    for file in `ls *.png`; do echo $file; done
    '),
    'bash_allargs' => 'java com.myserver.Program "$@"',
    'bash_awk' => alias_create('awk'),
    'bash_sed' => alias_create('awk'),
    'bash_get_PID' => "
    pidof prog_name
    pgrep prog_name
    ps -eaf -o pid,cmd | awk '/regex-to-match-command-name/{ print $1 }'
    top -p $(pgrep -d , <str>)
    ",
    'bash_stop_process' => <<<__END__
# find the process ID of the child process you want to stop
THEPID=$( bash_get_pid_ )
# Send SIGSTOP to the child process.
kill -SIGSTOP \${THEPID}
#  resume the process
kill -SIGCONT \${THEPID}
# Kill a process running on port
kill -9 \$(lsof -i :<port> | awk '{l=\$2} END {print l}')
__END__
    ,
    'bash_io_redirect'=>'
# Redirect stdout to file
1> filename.txt
# Redirect and append stdout to file
1>> filename.txt
# Redirect stderr to file
2> filename.txt
# Redirect and append stderr to file
2>> filename.txt
# Redirect both stdout and stderr to file
&> filename ',
    'date_log'=>('>> /var/www/app/logs/module/program_`date +"%F"`.log'),
    //----------------------------------------------------------------------------
    //  linux basic commands
    //----------------------------------------------------------------------------
    'top'=>'top -p $(pgrep -d , apache) ',
    'cpulimit'=>'
#Limit the cpu usage of a process
cpulimit -p pid -l 50
',
    'grep'=>('
# Search all text files
grep "word*" *.txt
grep -e {{PATTERN}} -f {{FILE}}
# only an extension
grep -Rn --include \*.php Controller .
# before and after lines
grep teststr -B 5 -A 5
# exclude pattern, or select non-matching lines
grep -v "myexclude"
# Search for a "pattern" inside all files in the current directory
grep -rn "pattern"
grep -RnisI "pattern" *
fgrep "pattern" * -R
# Remove blank lines from a file and save output to new file
grep . filename > newfilename
# Except multiple patterns
grep -vE \'(error|critical|warning)\' filename
# Show data from file without comments
grep -v ^[[:space:]]*# filename
# Show data from file without comments and new lines
egrep -v \'#|^$\' filename
# match multiple strings
grep "word1\|word2\|word3" /path/to/file
grep -e string1 -e string2 *.txt
# print do not match given pattern
grep -v "bar\|foo" /path/to/file
egrep -v "pattern1|pattern2" /path/to/file
#
echo $f | pcregrep -o1 -Ei \'[0-9]+_([a-z]+)_[0-9a-z]*\'
# parallel search
find . -name "*.php" -exec grep \$i {} \;
find . -name "*.sql" -exec grep "whatever" {} \;
# php specific
ag --php Controller
'),
    'parallel'=>'
        find . -name *.php | parallel  php_fmt
        find . -name "*jpeg" | parallel -I% --max-args 1 convert % %.png
    ',
    'xargs'=>'
        ## execute commands, each line from standard input, is passed as argument o the command
        find . -name *.php | xargs gedit
    ',
    'tee'=>'TODO',
    'split'=>'
    # break / split big files into smaller files
    # n files 500MB each.
    split -b 500MB file.log
    # option -d for filenams as numbers
    #
    # csplit uses  "context lines", es 4, will split first 3 in file 1, rest of lines in file2
    csplit list.txt 4
    ',
    //----------------------------------------------------------------------------
    //
    //----------------------------------------------------------------------------
    'find'=><<<__END__
        find {{dir}} -type f -name
        find -name *.mp3 -mtime -1 -exec cp {} /home/my_path/ \;
        # parallel
        find .  -name *.php | parallel  php_fmt {} \;
        @see find_*
        #Find files that have been modified on your system in the past 60 minutes
        find / -mmin 60 -type f
        #Find all files larger than 20M
        find / -type f -size +20M
        #Find duplicate files (based on MD5 hash)
        find -type f -exec md5sum '{}' ';' | sort | uniq --all-repeated=separate -w 33
        #Change permission only for files
        cd /var/www/site && find . -type f -exec chmod 766 {} \;
        cd /var/www/site && find . -type f -exec chmod 664 {} +
        #Change permission only for directories
        cd /var/www/site && find . -type d -exec chmod g+x {} \;
        cd /var/www/site && find . -type d -exec chmod g+rwx {} +
        #Find files and directories for specific user
        find . -user <username> -print
        Find files and directories for all without specific user
        find . \!-user <username> -print
        #Delete older files than 60 days
        find . -type f -mtime +60 -delete
        #Recursively remove all empty sub-directories from a directory
        find . -depth  -type d  -empty -exec rmdir {} \;
        # find + zip
        find . -name "xxx" -type f -mtime +7 | xargs gzip -v
        #How to find all hard links to a file
        find </path/to/dir> -xdev -samefile filename
        #Recursively find the latest modified files
        find . -type f -exec stat --format '%Y :%y %n' "{}" \; | sort -nr | cut -d: -f2- | head
        #Recursively find/replace of a string with sed
        find . -not -path '*/\.git*' -type f -print0 | xargs -0 sed -i 's/foo/bar/g'
        #Recursively find suid executables
        find / \( -perm -4000 -o -perm -2000 \) -type f -exec ls -la {} \;
        locate {{file_name}}
        # find + print formatted

        find /path -name "*.php" -exec printf 'require_once "%s";\n' {} +
        # find and move to new path
        find /path -name 'test*' -exec mv -t /path_to {} +
        # find .git repo on the disc @see find_locate

        find DMS -name "*.php" -exec printf 'require_once "%s";\n' {} +

__END__
    ,
    'find_delete'=>('#-print for debug
        find {{dir}} -maxdepth 1 -type f -mtime +30  -name "*.log"  -delete  '),
    'find_size'=>('#-exec echo {} \;
        find {{dir}} -maxdepth 1 -type f -size +500M -name "*.log" -print '),

    'find_exec' => ('find . -type f -perm 777 -exec chmod 755 {} \;'),
    'find_locate' => '
        locate -i "*.jpg"
        locate "*/.git"
    ',
    'rename' => "",

    'find_exec'=>('find . -type f -perm 777 -exec chmod 755 {} \;'),
    'find_locate'=>('locate -i "*.jpg"'),
    'rename'=>"
        find -type f -name '*.php' | rename 's/^/_/' *
        for file in *.txt; do    mv '\$file' '\${file%.txt}_1.txt' done
        mmv '*.hh' '#1.php'
    ",
    'find_duplicates'=>'
  fdupes .
  fslint .
  find . ! -empty -type f -exec md5sum {} + | sort | uniq -w32 -dD
',
    'find_locked_files'=>'
        # Kills a process that is locking a file
        fuser -k filename
        # Show what PID is listening on specific port
        fuser -v 53/udp
    ',
    'img_resize'=>'
convert myfigure.png -resize 200x100 myfigure.jpg
convert -define jpeg:size=500x180 path.jpeg -auto-orient -thumbnail 250x90 -unsharp 0x.5 sample_thumb.jpeg
# -thumbnail - It resizes the image and strips all the profile and comment data to make it smaller.
# -define jpeg:size= - This is used for setting initial size original image which has been set twice that of final image so that it is clearly visible and doesn t look blurry. It is used to avoid operation over large input images.
# -auto-orient - It is used to set the image to correct orientation. This is helpful if the image is from Cameras.
# - unsharp - We can improve the above result by sharpening the image slightly after the "-thumbnail" resize operation.
# Specify the required thumbnail dimension after -thumbnail.
    ',
    'img_optimize'=>'
# optimise a JPG and make it progressive
convert -strip -interlace Plane -quality 80 input-file.jpg output-file.jpg
# Batch all the images in a folder like this:
for i in source/images/backgrounds/*.jpg; do convert -strip -interlace Plane -quality 80 $i $i; done
',
    'imagemagick'=>alias_create('img_resize'),
    'jpg_to_png'=>'find . -name "*.jpg" -exec mogrify -format png {} \; ',
    'ramdisk'=>alias_create('tmpfs'),
    'tmpfs'=>"
free -gh
mkdir /mnt/ramdisk
# mount -t [TYPE] -o size=[SIZE] [FSTYPE] [MOUNTPOINT]
mount -t tmpfs -o size=512m tmpfs /mnt/ramdisk
# persistent
sudo gedit /etc/fstab
tmpfs  /mnt/ramdisk tmpfs   nodev,nosuid,noexec,nodiratime,size=1024M   0 0
df -h /mnt/ramdisk
",
    //
    'mount'=>'
        @see tmpfs
        @see ftp_merge
        #My music collection sits on my home server so that I can access it from anywhere. It is mounted using SSHFS and automount in the /etc/fstab/:
        root@server:/media/media  /mnt/media  fuse.sshfs noauto,x-systemd.automount,idmap=user,IdentityFile=/root/.ssh/id_rsa,allow_other,reconnect 0 0
    ',
    //
    'nice'=>('
# lunch with increased  priority:
nice -n -5 program_name
# decreased priority:
nice -n 5 progrm_name
# renice
renice -n 19 -p pid_number
    '),
    'ps'=>'
        # count processes per user
        ps hax -o user | sort | uniq -c | sort -r
        # java ps specific
        jps
    ',
    'aspell'=>'aspell --lang=it -c docs/manuale_api_rivenditori/index.md',
    'md5file'=>' md5sum file ',
    // get a specific line of the file, useful for debug scripting
    'file_get_line'=>"sed '5!d' <file_path>",
    //---- compression decompression -------------------------------------------
    /* gzip è + veloce,
    bzip2 fa una compressione migliore del 5% */
    'zip_compress'=>'
        zip -9 -j --junk-paths -r {{zip_file.zip}} {{folder_name}}/*.jpg
        zip -m -T # elimina dopo compressione
    ',
    'extract_unzip'=>'unzip {{zip_file}} -d {{destination_folder}} ',
    'zip_list'=>'
        unzip -l files.zip
        # count compressed items
        unzip -l filename.zip | tail -1 | awk \'{ print $2 }\'
    ',
    'zip_verify'=>alias_create('zip_list'),
    'compress_bzip'=>('bzip2 -zkfq --best {{filename}}.bz2 '),
    'extract_bzip'=>('bzip2  -dk  {{filename}}.bz2 '),
    'compress_targz'=>("
tar -czf /path/to/backup.tar.gz /path/to/*.pdf 2>/dev/null
tar -czfvp /archive/full-backup-`date '+%d-%B-%Y'`.tar.gz --directory /var/mydir --exclude=dirname1  --exclude=dirname2 .
"),
    'extract_targz'=>('tar -xzfv archive.tar.gz'),
    'compress_gzip'=>'
        cat {{file}} | gzip > file.gz
        # -p preserve permissions
        tar -czf -pv  {{archive-name}}.tar.gz {{path_to_folder}}
        ',
    'extract_gunzip'=>('gunzip {{file}}.gz   '),
    'tar'=><<<__END__
# System backup with exclude specific directories
cd /
# tar -czvpf /mnt/system$(date +%d%m%Y%s).tgz --directory=/ \
--exclude=proc/* --exclude=sys/* --exclude=dev/* --exclude=mnt/* .
# System backup with exclude specific directories (pigz)
cd /
tar cvpf /backup/snapshot-$(date +%d%m%Y%s).tgz --directory=/ \
--exclude=proc/* --exclude=sys/* --exclude=dev/* \
--exclude=mnt/* --exclude=tmp/* --use-compress-program=pigz .
__END__
    ,
    //--------------------------------------------------------
    'secure-delete'=>'
        Secure delete with shred
        shred -vfuz -n 10 file
        shred --verbose --random-source=/dev/urandom -n 1 /dev/sda
        Secure delete with scrub
        scrub -p dod /dev/sda
        scrub -p dod -r file
        Secure delete with badblocks
        badblocks -s -w -t random -v /dev/sda
        badblocks -c 10240 -s -w -t random -v /dev/sda
        Secure delete with secure-delete
        srm -vz /tmp/file
        sfill -vz /local
        sdmem -v
        swapoff /dev/sda5 && sswap -vz /dev/sda5
    ',
    //----------------------------------------------------------------------------
    // start a process at boot
    // process manager. Ubuntu 16.04 and later ship with Systemd.
    'autostart'=>'
Create a new file called /etc/systemd/system/my-fathom-site.service with the following contents. Replace $USER with your actual username.
[Unit]
Description=Starts the fathom server
Requires=network.target
After=network.target
[Service]
Type=simple
User=$USER
Restart=always
RestartSec=3
WorkingDirectory=/home/$USER/my-fathom-site
ExecStart=/usr/local/bin/fathom server
[Install]
WantedBy=multi-user.target
Reload the Systemd configuration & enable our service so that Fathom is automatically started whenever the system boots.
systemctl daemon-reload
systemctl enable my-fathom-site
You should now be able to manually start your Fathom web server by issuing the following command.
systemctl start my-fathom-site
    ',
    //--------------------------------------------------------------------------
    'dictionary'=>('loook word  #cerca una parola nel dizionario del sistema'),
    'user_groups'=>'
# create new user
    sudo useradd -G  {{group}}  {{user}}
    sudo passwd {{user}}
# edit existing user
    sudo usermod -a -G www-data  {{user}}
# method 2
    sudo adduser {{user}} {{group}}
    sudo deluser {{user}} {{group}}
#  list groups for user
    sudo groups {{user}}
# list of all groups on your system
    sudo getent {{group}}
# numerical IDs associated with each group by username
    sudo id {{user}
# add a new group
    sudo groupadd {{group}}
# remove all additional groups from user
    sudo usermod -G "" {{user}}
# dir hardening recipe
cd {{dir}}
ll {{dir}}
# aggiungi user www-data al gruppo di user, x vedere anche files privati
sudo usermod -a -G nformici www-data
# user + group www-data
sudo groups www-data
sudo groups {{user}}
# dir appartiene a user, ma group www-data permette accesso agli altri
sudo chown -R {{user}}.www-data {{dir}}
sudo chmod -R 770 {{dir}}
#
sudo chmod -R u=+rwx,g=+rwx,o=+r ./dir
#  sets the group ID (setgid) on the directory
# all new files and subdirectories created within the current directory inherit the group ID of the directory, rather than the primary group ID of the user who created the file. This will also be passed on to new subdirectories created in the current directory.
# g+s affects the file  group ID but does not affec t the owner ID.
# Note that this applies only to newly-created files
sudo find ./dir -type d -exec chmod g+s {} \;
',
    'sudo_wwwdata'=>('sudo -u www-data bin/{{script}} $param'),
    'tee'=>('
        #duplicate output to file e STDOUT
        ls -la / | tee /tmp/out.log'),
    'ubuntu_version'=>('lsb_release -cds'),
    'hostname'=>('hostname -f'),
    'open_ports'=>alias_create('ports_open'),
    'ports_open'=><<<__END__
        sudo netstat -lptu
        sudo netstat -lntp # quali software occupano le porte
        # Graph # of connections for each hosts
        netstat -an | \
        grep ESTABLISHED | \
        awk '{print $5}' | \
        awk -F: '{print $1}' | \
        grep -v -e '^[[:space:]]*$' | \
        sort | uniq -c | \
        awk '{ printf("%s\t%s\t",$2,$1) ; for (i = 0; i < $1; i++) {printf("*")}; print "" }'
__END__
    ,
    'gpg'=>('
#
# gpg --decrypt-files
# gpg --default-recipient-self --armor --encrypt-files
#
gpg -c your_file.odt
gpg your_file.odt.gpg
echo $your_password | gpg --passphrase-fd 0 --batch --yes --no-tty your_file.gpg'),
    'awk'=>("
awk '{print \$1}' # field 1
awk '{print \$2}' # field 2
ls -lh | awk '{ print $2 }'
#
cut -f 1
echo 'someletters_12345_moreleters.ext' | cut -d'_' -f 2 # explode by _ and take 2
"),
    'sed'=>"
        # sed 's/^/    /'
        sed -i 's/ugly/beautiful/g' /home/usr/test/test.txt
        # To print a specific line from a file
        sed -n 10p /path/to/file
        # Remove a specific line from a file
        sed -i 10d /path/to/file
        # alternative (BSD): sed -i'' 10d /path/to/file
        # Remove a range of lines from a file
        sed -i <file> -re '<start>,<end>d'
    ",
    'perl'=>"perl -pi -e 's/\x0d/\x0a/gs' ",
    'count_lines'=>("wc -l"),
    'apt'=>'
sudo apt-get install
apt list --installed # elenca pacchetti installati
sudo apt search php7.2 # elenca pacchetti disponibili
apt-cache show
# sudo apt-get remove
sudo apt-get remove --purge
#
# dpkg --list # elenca pacchetti installati ? o installabili
apt-cache search
#
sudo dpkg -i zip_2.32-1_i386.deb # installa un pacchetto scaricato locale
#
# elenca  i file installati da un pacchetto
dpkg-query -L  <package_name>
#
dpkg -l | grep -i # pacchetti installati nel sistema
#
dpkg -S /etc/host.conf # quale pacchetto ha installato un file
# solo aggiornabili
apt list --upgradable
apt-get --just-print upgrade
# mostra solo aggiornamenti di sicurezza
sudo apt-get upgrade -s| grep ^Inst | awk "{print $2}"
#
sudo apt update && sudo apt upgrade -y && sudo apt-get autoremove
# synaptic bug
xhost +si:localuser:root && sudo synaptic
',
    'apt_installed'=>('sudo apt list --installed | grep php7.0 '),
    'apt_list'=>('sudo apt-cache search php7-*'),
    'firewall'=>'@see ufw || iptables ',
    'ufw'=>('
sudo ufw app list
sudo ufw allow 80/tcp
sudo ufw enable
sudo ufw status
sudo ufw deny from 10.10.10.10
sudo ufw allow tcp/80
sudo ufw allow proto tcp to 0.0.0.0/0 port 80
# sudo ufw allow tcp/22
# sudo ufw deny tcp/22
# sudo ufw allow in on eth1 to any port 22 proto tcp
# accept port 22 from 202.54.1.5/29 only:
sudo ufw allow from 202.54.1.5/29 to any port 22
# A differenza del comando iptables, le modifiche effettuate con il comando ufw sono persistenti e non è quindi necessario dare altri comandi per memorizzarle in via definitiva.
'),
    'iptables'=>'
    sudo iptables -L
    ',
    'crontab'=>
    "
0 23 * * * root  /usr/local/bin/command   # specifi howr
*/10 * * * user  /path/to/script          # every ten min
@reboot     root      duplicati-server --webservice-interface=any &
",
    'cron_log'=>('grep -i CRON /var/log/syslog'),
    'at'=>('
        sudo echo "reboot" | sudo at -m 13:10 today
        echo "ping -c 4 www.google.com" | at -m now + 1 minute
    '),
    'cpu_info'=>('sudo lshw | grep -i cpu'),
    'ram_usage'=>"free -gh",
    'strace'=><<<__END__
Track child process
strace -f -p $(pidof glusterfsd)
Track process after 30 seconds
timeout 30 strace $(< /var/run/zabbix/zabbix_agentd.pid)
Track child process and redirect output to a file
ps auxw | grep 'sbin/[a]pache' | awk '{print " -p " $2}' | xargs strace -o /tmp/strace-apache-proc.out
Track the open request of a network port
strace -f -e trace=bind nc -l 80
Track the open request of a network port (show TCP/UDP)
strace -f -e trace=network nc -lu 80
__END__
    ,
    //----------------------------------------------------------------------------
    //  mysql commands
    //----------------------------------------------------------------------------
    'mysql_create_db'=>"
        mysql -u root -p
        CREATE DATABASE {{database}};
        SHOW DATABASES;
        #
        # GRANT [type of permission] ON [database name].[table name] TO '[username]'@'localhost';
        GRANT ALL PRIVILEGES ON {{database}}.* TO '{{database}}@{{localhost}};
        #
        # create user
        GRANT ALL PRIVILEGES ON {{db}}.* TO '{{user}}'@'localhost' IDENTIFIED BY '{{pass}}';
        FLUSH PRIVILEGES;
        SHOW GRANTS FOR '{{db}}'@'localhost';
        # password setting error?
        SHOW VARIABLES LIKE 'validate_password%';
        #
        mysql -u {{username}}  -p {{database}}
",
    'mysql_user' => "
        # basic:
        CREATE USER '{{username}}'@'localhost' IDENTIFIED BY 'password';
        GRANT ALL PRIVILEGES ON {{database_name}}.* TO '{{username}}'@'localhost';
        # TEST: mysql -u newuser -p

        # may need to relax password validation plugin
        # mysql> SHOW VARIABLES LIKE 'validate_password_policy';
        # mysql> SET GLOBAL validate_password_policy = 1;

        GRANT USAGE ON *.* TO '{{username}}'@'localhost';
        GRANT ALL  ON `{{db_name}}`.* TO '{{username}}'@'localhost' WITH GRANT OPTION;
        FLUSH PRIVILEGES;
        SHOW GRANTS FOR '{{username}}'@'localhost';
        #
        # get existing users
        SELECT `user`, `host`, `authentication_string` FROM `mysql`.`user`;
        # create user
        GRANT ALL PRIVILEGES ON *.* TO '{{username}}'@'localhost' IDENTIFIED BY 'password';
        # inspect
        SHOW GRANTS FOR '_user_'@'localhost';
        SET PASSWORD FOR '_user_'@'localhost' = PASSWORD('xxx');
        FLUSH PRIVILEGES;
        SHOW GRANTS FOR '_user_'@'localhost';
        #
        GRANT SELECT, EXECUTE, SHOW VIEW, ALTER, ALTER ROUTINE, CREATE, CREATE ROUTINE, CREATE TEMPORARY TABLES, CREATE VIEW, DELETE, DROP, EVENT, INDEX, INSERT, REFERENCES, TRIGGER, UPDATE, LOCK TABLES
        ON `{{database}}`.* TO '{{username}}'@'localhost' WITH GRANT OPTION;
        FLUSH PRIVILEGES;
        SHOW GRANTS FOR '{{username}}'@'localhost';
    ",
    'mysql_debug'=>"
        SHOW VARIABLES LIKE '%version%';
        SHOW TABLE STATUS FROM supportpal LIKE 'email_log';
        SHOW CREATE TABLE xxxx
    ",
    'mysql_dump'=>'
          mysqldump -u user --p=$password $dbname [$tablemane]   > $backup_db_file
          mysqldump --all-databases -uroot -p$pass > /data/mysql_dump/full-`date +%F`.sql && find /data/mysql_dump -type f -mtime +30  -exec rm -f {} \;
          for I in `echo "show databases;" | mysql -uroot -p$pass | grep -v Database`; do mysqldump -uroot -p$pass $I > "/data/mysql_dump/$I.sql"; done
          @see backupper script for single tables
          --skip-extended-insert  fa creare un singolo insert per ogni record, creerà dump di dimensioni maggiori ma più comodo da modificare
          --quick   per tabelle molto grandi (per esempio 2 Gb), evita di mettere in cache i record e li scrive direttamente, richiede meno RAM.
          tablename:   è possibile specificare quale tabella del db si vuole salvare. comoda per tabelle di grandi dimensioni.
          # spezzare tabelle grandi
          mysqldump  db table --where="1 limit 100, 1000"
      ',
    'mysql_dump2'=><<<'__END__'
        function action_dump($dbname, string $password) {
            $path =  realpath( __DIR__.\'/../sql\' );
            if( false === $path ){ die( "error path:$path " ); }
            // TODO: parse application file to get password value
            if( empty($password)  ) {
                die("provide password as 2 arg".PHP_EOL);
            }
            echo "export schema to $path/schema.sql \n";
            e("mysqldump -u root --password=$password $dbname --no-data > $path/schema.sql");
            echo "export data to $path/data.sql \n";
            e("mysqldump -u root --password=$password $dbname --no-create-info > $path/data.sql");
            echo "less $path/schema.sql \n";
            echo "less $path/data.sql \n";
            //
            $_mysqldump = function($flag_data, $path ) use( $user, $password, $host, $dbname ) {
                $flags = "--extended-insert=FALSE --skip-comments";
                return "mysqldump --user=$user --password=$password -h $host $dbname  $flag_data 2>/dev/null > $path ";
            };
            e( $_mysqldump( "--no-data"         , $path_schema ) );
            e( $_mysqldump( "--no-create-info"  , $path_data   ) );
        }
        // ordina la tabella per facilitare merge
        function order_table(array $a_config, $tablename, $table_key) {
            extract($a_config);
            $sql = "ALTER TABLE $tablename ORDER BY $table_key ASC";
            $cmd = "mysql --user=$username --password=$password  $dbname  -e \"$sql\" ";
            e($cmd);
        }
        // main
        $action = isset($_SERVER["argv"][1]) ? $_SERVER["argv"][1] : "dump";// 0 => pgr name
        $password = isset($_SERVER["argv"][2]) ? $_SERVER["argv"][2] : "";
        switch($action) {
            case "dump":
                action_dump($password);
                break;
            default:
                action_usage();
                break;
        }
__END__
    ,
    'mysql_restore'=><<<__END__
        mysql -u user --p={{password}} {{dbname}} [\$tablemane] < {{backup_db_file}}
        # monitor long restores
        watch -n 5 'echo "show processlist;" | mysql -uuser -ppassword';
__END__
    ,
    // dump +restore di database grandi
    'mysql_big_dump_restore'=><<<'__END__'
    ## mydumper
    mydumper \
        --database=DB_NAME \
        --host=DB_HOST \
        --user=DB_USER \
        --password=DB_PASS \
        --outputdir=DB_DUMP \
        --rows=500000 \
        --compress \
        --build-empty-files \
        --threads=2 \
        --compress-protocol
    ## myloader
    myloader \
        --database=DB_NAME \
        --directory=DB_DUMP \
        --queries-per-transaction=50000 \
        --threads=10 \
        --compress-protocol \
        --verbose=3
    ##
    http://www.maatkit.org/ has a mk-parallel-dump and mk-parallel-restore
    @see "High Performance MySQL" book.
    -   Extended inserts in dumps
    -   Dump with --tab format so you can use mysqlimport, which is faster than mysql < dumpfile
    -   Import with multiple threads, one for each table.
    -   Use a different database engine if possible. importing into a heavily transactional engine like innodb is awfully slow. Inserting into a non-transactional engine like MyISAM is much much faster.
    ## InnoDB is owfully slow, try
    innodb_flush_log_at_trx_commit = 2
__END__
    ,
    'mysql_explain'=>'EXPLAIN SELECT * FROM table where x=1 ',
    'mysql_import'=>'
        DROP TABLE IF EXISTS `_dbname`.`_table_name`;
        CREATE TABLE `_dbname`.`_table_name` LIKE `_dbname_source`.`_table_name`;
        INSERT INTO `_dbname`.`_table_name` SELECT * FROM `_dbname_source`.`_table_name`;
    ',
    'mysql_compare_case_sensitive'=>"
        // mysql is not case sensitive by default, try changing the language collation to binary
        // cant use indexes
        // this returns 0 (false)
        select 'A' like binary 'a'
        col_name COLLATE latin1_general_cs LIKE 'a%'
        col_name LIKE 'a%' COLLATE latin1_general_cs
        col_name COLLATE latin1_bin LIKE 'a%'
        col_name LIKE 'a%' COLLATE latin1_bin
        // can use index
        WHERE `column` = BINARY 'value'
    ",
    'mysql_tuning'=>'
        mysqltuner ## program to detect various misconfigurations
    ',
    'mysql_optimize'=>'
        ## list the size of every table in every database, largest first:
        SELECT
             table_schema as `Database`,
             table_name AS `Table`,
             round(((data_length + index_length) / 1024 / 1024), 2) `Size in MB`
        FROM information_schema.TABLES
        ORDER BY (data_length + index_length) DESC;
        ##
        optimize table tableName;
        ## To optimize all tables on your server in all databases you can use e.g. commandline command:
        mysqlcheck --all-databases --optimize --skip-write-binlog
    ',
    'adminer'=>alias_create('mysql_adminer'),
    'mysql_adminer'=>'
    cd /var/www/html
    sudo wget "http://www.adminer.org/latest.php" -O /var/www/html/adminer__version.php
    # put IP checking and denying on the program for security @see ip_check_  _is_secure_IP()
    # or php HTTP basic auth @see auth_
    sudo echo "your secure php code here" > adminer_auth.php
    # put into the file
    sudo nano adminer__version.php
    require __DIR__. "/adminer_auth.php";
    ',
    //--------------------------------------------------------------------------
    'repeat'=>'watch -n1 ls -lt #repeat the command every 1 sec ',
    'watch'=>alias_create('repeat'),
    //----------------------------------------------------------------------------
    // network commands
    //----------------------------------------------------------------------------
    'scp_upload'=>('sshpass -p "_pass" scp -o  StrictHostKeyChecking=no _l_path tmirandola@_host:_r_path'),
    'scp_download'=>('sshpass -p "_pass" scp -o  StrictHostKeyChecking=no tmirandola@_host:_r_path _l_path'),
    'ssh'=>'
# Supported escape sequences:
~.  - terminate connection (and any multiplexed sessions)
~B  - send a BREAK to the remote system
~C  - open a command line
~R  - Request rekey (SSH protocol 2 only)
~^Z - suspend ssh
~#  - list forwarded connections
~&  - background ssh (when waiting for connections to terminate)
~?  - this message
~~  - send the escape character by typing it twice
# Compare a remote file with a local file
ssh user@host cat /path/to/remotefile | diff /path/to/localfile -
    ',
    //
    'ssh_rpc'=>"
            ssh user@host '( cat ~/myscript.sh )'
            ssh user@host 'ls -l; ps -aux; whoami'
    ",
    'tcpdump'=><<<__END__
#Filter incoming (on interface) traffic (specific ip:port)
tcpdump -ne -i eth0 -Q in host 192.168.252.1 and port 443
-n - don't convert addresses (-nn will not resolve hostnames or ports)
-e - print the link-level headers
-i [iface|any] - set interface
-Q|-D [in|out|inout] - choose send/receive direction (-D - for old tcpdump versions)
host [ip|hostname] - set host, also [host not]
[and|or] - set logic
port [1-65535] - set port number, also [port not]
#Filter incoming (on interface) traffic (specific ip:port) and write to a file
tcpdump -ne -i eth0 -Q in host 192.168.252.1 and port 443 -c 5 -w tcpdump.pcap
-c [num] - capture only num number of packets
-w [filename] - write packets to file, -r [filename] - reading from file
#Capture all ICMP packets
tcpdump -nei eth0 icmp
#Check protocol used (TCP or UDP) for service
tcpdump -nei eth0 tcp port 22 -vv -X | egrep "TCP|UDP"
#Display ASCII text (to parse the output using grep or other)
tcpdump -i eth0 -A -s0 port 443
#Grab everything between two keywords
tcpdump -i eth0 port 80 -X | sed -n -e '/username/,/=ldap/ p'
#Grab user and pass ever plain http
tcpdump -i eth0  port http -l -A | egrep -i 'pass=|pwd=|log=|login=|user=|username=|pw=|passw=|passwd=|password=|pass:|user:|username:|password:|login:|pass |user ' --color=auto --line-buffered -B20
#Extract HTTP User Agent from HTTP request header
tcpdump -ei eth0 -nn -A -s1500 -l | grep "User-Agent:"
#Capture only HTTP GET and POST packets
tcpdump -ei eth0 -s 0 -A -vv 'tcp[((tcp[12:1] & 0xf0) >> 2):4] = 0x47455420' or 'tcp[((tcp[12:1] & 0xf0) >> 2):4] = 0x504f5354'
or simply:
tcpdump -ei eth0 -s 0 -v -n -l | egrep -i "POST /|GET /|Host:"
Rotate capture files
tcpdump -ei eth0 -w /tmp/capture-%H.pcap -G 3600 -C 200
-G <num> - pcap will be created every <num> seconds
-C <size> - close the current pcap and open a new one if is larger than <size>
Top hosts by packets
tcpdump -ei enp0s25 -nnn -t -c 200 | cut -f 1,2,3,4 -d '.' | sort | uniq -c | sort -nr | head -n 20
__END__
    ,
    'ngrep'=><<<__END__
ngrep -d eth0 "www.google.com" port 443
-d [iface|any] - set interface
[domain] - set hostname
port [1-65535] - set port number
ngrep -d eth0 "www.google.com" (host 10.240.20.2) and (port 443)
(host [ip|hostname]) - filter by ip or hostname
(port [1-65535]) - filter by port number
ngrep -d eth0 -qt -O ngrep.pcap "www.google.com" port 443
-q - quiet mode (only payloads)
-t - added timestamps
-O [filename] - save output to file, -I [filename] - reading from file
ngrep -d eth0 -qt 'HTTP' 'tcp'
HTTP - show http headers
tcp|udp - set protocol
[src|dst] host [ip|hostname] - set direction for specific node
ngrep -l -q -d eth0 -i "User-Agent: curl*"
-l - stdout line buffered
-i - case-insensitive search
__END__
    ,
    'mp3'=>'
        mpg123 --loop=3 *.mp3
        totem *.mp3
        mpv *.mp3
    ',
    'curl'=>('
        curl -I -d "method=Say.Hello" http://www.google.com
        curl -O http://www.google.com/image.gif
        curl http://www.google.com/image.gif -o /tmp/img.gif
        # POST request
        curl -d "param1=value1&param2=value2" -X POST http://localhost:3000/data
        # POST multipart form encoded, file upload
        curl \
          -F "userid=1" \
          -F "image=@/home/user1/Desktop/test.jpg" \
          localhost/uploader.php
        #
        httpstat http://localhost/project
        #
curl -Iks https://www.google.com
    -I - show response headers only
    -k - insecure connection when using ssl
    -s - silent mode (not display body)
curl -Iks --location -X GET -A "x-agent" https://www.google.com
    --location - follow redirects
    -X - set method
    -A - set user-agent
curl -Iks --location -X GET -A "x-agent" --proxy http://127.0.0.1:16379 https://www.google.com
    --proxy [socks5://|http://] - set proxy server
# set a session ID
curl -I -H \'Cookie: PHPSESSID=u2vl43aruqgrqck38jq72t3ov8\' http://zzzzz.xxxxx.test/items/downloadcategoryxls
# scrap a web page and convert to markdown
curl --silent http://www.taziomirandola.it | pandoc --from html --to textile -o /tmp/tm.md
        '),
    'wget'=>('
wget -r -l1 -P035 -nd --no-parent
wget -t 5 -c -nH -r -k -p -N --no-parent
wget -l 1 -v -k -p -E http://test.com/test -- mirror completo di un sito
'),
    'nethogs'=>('
        # conoscere i processi che stanno utilizzando la nostra connessione
        sudo nethogs wlan0'),
    //----------------------------------------
    //  rsync -azv -e ssh --delete --progress
    'rsync_push'=>('
        #upload di tutta la directory (PUSH)
        rsync -v $cmd_dry_run --archive --cvs-exclude --compress --stats --progress --human-readable --force --delete --no-perms --no-owner --no-group $exclude  --rsh \"ssh -p22\" $local_path $user@$host:$remote_path'),
    'rsync_pull'=>('
        # remote to local (PULL)
        rsync -v --compress --rsh ssh user@host:/path/to/local/file.txt /path/to/remote/file.txt'),
    'rsync_base'=>'
rsync options source destination
common options:
    -v : verbose
    -r : copies data recursively (but don’t preserve timestamps and permission while transferring data
    -a : archive mode, archive mode allows copying files recursively and it also preserves symbolic links, file permissions, user & group ownerships and timestamps
    -z : compress file data
    -h : human-readable, output numbers in a human-readable format
',
    'rsync_local'=>('
# sincronizza in modo efficiente directory locali
rsync -a -v /home /backup
rsync -v --archive --compress --delete /local_path_A/ /local_path_B'),
    //
    'rsync_pull_push_php'=><<<'__END__'
// sincronizza in modo efficiente directory
//
// trasferimento con ssh + rsync
// v: verbose
// a: archive mode, copia i symlink
// z: zipped compresssed
function rsync_push($user, $passwd, $host, $local_path, $remote_path, $exclude, $cmd_dry_run = true ) {
    $OPT = $cmd_dry_run -vazC --force --delete --stats --progress -h  $exclude  -e \"ssh -p22\"
    $SRC  = $local_path
    $DEST = $user@$host:$remote_path
    $cmd = "rsync $OPT $SRC DEST";
    echo "\n" . $cmd . "\n\n";
    // working upload: rsync  -vazC --force --delete --stats -e "ssh -p22" $local_path $user@$host:$remote_path
    // echo `$cmd`;
    //
    // more options
    //
    $no_meta_update = '--checksum --no-perms --no-owner --no-group --no-times';
    $OPT = "-vazC --stats --progress -h --force --delete $no_meta_update $exclude -e \"ssh -p22\" ";
    $SRC = $local_path;
    $DEST = "$user@$host:$remote_path";
    $cmd = "rsync  $OPT  $SRC $DEST";
    echo "\n" . $cmd . "\n\n";
    echo `$cmd`;
}
//------------------------
// pull from remote
// scarica una directory
function remote_download( $user, $passwd, $host, $local_path, $remote_path, $exclude, $cmd_dry_run = true ) {
    // echo "scp   $user@$host:$remote_path $local_path" . "\n\n";
    // basic
    $SRC = "$user@$host:$remote_path";
    $DEST = "$local_path";
    $OPT = "$cmd_dry_run -chavzP --stats";
    $cmd = " rsync $OPT $SRC $DEST ";
    echo "\n" . $cmd . "\n\n";
    // echo `$cmd`;
    // more options:
    // only jpg, works in this exact order
    $only_jpg = " --include='*.jpg' --exclude='*.*' ";
    $OPT = "-v --archive --compress --update  --ignore-existing --dry-run $only_jpg ";
    $SRC = "$user@$host:$remote_path";
    $DEST = " $local_path ";
    $cmd = "rsync $OPT $SRC $DEST ";
    echo "\n" . $cmd . "\n\n";
    // echo `$cmd`;
}
function rsync_pull($user, $passwd, $host, $local_path, $remote_path, $exclude) { }
__END__
    ,
    'openssl'=><<<'__END__'
 openssl
Testing connection to remote host
echo | openssl s_client -connect google.com:443 -showcerts
Testing connection to remote host (with SNI support)
echo | openssl s_client -showcerts -servername google.com -connect google.com:443
Testing connection to remote host with specific ssl version
openssl s_client -tls1_2 -connect google.com:443
Testing connection to remote host with specific ssl cipher
openssl s_client -cipher 'AES128-SHA' -connect google.com:443
Generate private key
# _len: 2048, 4096
( _fd="private.key" ; _len="4096" ; \
openssl genrsa -out ${_fd} ${_len} )
Generate private key with passphrase
# _ciph: des3, aes128, aes256
# _len: 2048, 4096
( _ciph="aes128" ; _fd="private.key" ; _len="4096" ; \
openssl genrsa -${_ciph} -out ${_fd} ${_len} )
Remove passphrase from private key
( _fd="private.key" ; _fd_unp="private_unp.key" ; \
openssl rsa -in ${_fd} -out ${_fd_unp} )
Encrypt existing private key with a passphrase
# _ciph: des3, aes128, aes256
( _ciph="aes128" ; _fd="private.key" ; _fd_pass="private_pass.key" ; \
openssl rsa -${_ciph} -in ${_fd} -out ${_fd_pass}
Check private key
( _fd="private.key" ; \
openssl rsa -check -in ${_fd} )
Get public key from private key
( _fd="private.key" ; _fd_pub="public.key" ; \
openssl rsa -pubout -in ${_fd} -out ${_fd_pub} )
Generate private key + csr
( _fd="private.key" ; _fd_csr="request.csr" ; _len="4096" ; \
openssl req -out ${_fd_csr} -new -newkey rsa:${_len} -nodes -keyout ${_fd} )
Generate csr
( _fd="private.key" ; _fd_csr="request.csr" ; \
openssl req -out ${_fd_csr} -new -key ${_fd} )
Generate csr (metadata from exist certificate)
( _fd="private.key" ; _fd_csr="request.csr" ; _fd_crt="cert.crt" ; \
openssl x509 -x509toreq -in ${_fd_crt} -out ${_fd_csr} -signkey ${_fd} )
Generate csr with -config param
( _fd="private.key" ; _fd_csr="request.csr" ; \
openssl req -new -sha256 -key ${_fd} -out ${_fd_csr} \
-config <(
cat <<-EOF
[req]
default_bits = 2048
prompt = no
default_md = sha256
req_extensions = req_ext
distinguished_name = dn
[ dn ]
C=<two-letter ISO abbreviation for your country>
ST=<state or province where your organization is legally located>
L=<city where your organization is legally located>
O=<legal name of your organization>
OU=<section of the organization>
CN=<fully qualified domain name>
[ req_ext ]
subjectAltName = @alt_names
[ alt_names ]
DNS.1 = <fully qualified domain name>
DNS.2 = <next domain>
DNS.3 = <next domain>
EOF
))
List available EC curves
openssl ecparam -list_curves
Generate ECDSA private key
# _curve: prime256v1, secp521r1, secp384r1
( _fd="private.key" ; _curve="prime256v1" ; \
openssl ecparam -out ${_fd} -name ${_curve} -genkey )
# _curve: X25519
( _fd="private.key" ; _curve="x25519" ; \
openssl genpkey -algorithm ${_curve} -out ${_fd} )
Print ECDSA private and public keys
( _fd="private.key" ; \
openssl ec -in ${_fd} -noout -text )
# For x25519 only extracting public key
( _fd="private.key" ; _fd_pub="public.key" ; \
openssl pkey -in ${_fd} -pubout -out ${_fd_pub} )
Generate private key with csr (ECC)
# _curve: prime256v1, secp521r1, secp384r1
( _fd="domain.com.key" ; _fd_csr="domain.com.csr" ; _curve="prime256v1" ; openssl ecparam -out ${_fd} -name ${_curve} -genkey ; openssl req -new -key ${_fd} -out ${_fd_csr} -sha256 )
Convert DER to PEM
( _fd_der="cert.crt" ; _fd_pem="cert.pem" ; \
openssl x509 -in ${_fd_der} -inform der -outform pem -out ${_fd_pem} )
Convert PEM to DER
( _fd_der="cert.crt" ; _fd_pem="cert.pem" ; \
openssl x509 -in ${_fd_pem} -outform der -out ${_fd_der} )
Checking whether the private key and the certificate match
(openssl rsa -noout -modulus -in private.key | openssl md5 ; openssl x509 -noout -modulus -in certificate.crt | openssl md5) | uniq
__END__
    ,
    //----------------------------------------------------------------------------
    // FTP
    //----------------------------------------------------------------------------
    'ftp_session'=>'
    ftp user@ftpdomain.com
    lcd /path
    rcd /path
    ls
    mget *.xls # multi get, download multiple
    put /path/file #upload a single file
    mput *.xls # upload multi
    exit
    ',
    'ftp_php_pull_push'=><<<'__END__'
function action_sync_xxx() {
    $username = '';
    $password = '';
    $host     = '192.168.0.0';
    $lcd      = realpath(__DIR__.'/..');
    $rcd      = '/the/path';
    return ftp_sync_dir($username, $password, $host, $lcd, $rcd);
}
function ftp_upload_dir($username, $password, $host, $lcd, $rcd){
    // --delete elimina ogni file non presente
    // --only-newer carica solo i files più recenti sul path locale
    $cmd_options = ' --reverse --delete  --no-perms  --continue --verbose  --parallel=2 ';
    // aggiunge opzione dryrun ammeno che non si dia il comando --go
    $cmd_options .= init_dry_run();
    // serve a sovrascrivere files nascosti come .htaccess
    // set ftp:list-options -a;
    $cmd = "lftp -c \" open ftp://$username:$password@$host; lcd $lcd; cd $rcd; mirror $cmd_options\"";
    echo $cmd."\n";
    echo `$cmd`;
}
function ftp_download_dir($username, $password, $host, $lcd, $rcd){
    // --delete elimina ogni file non presente
    // --only-newer carica solo i files più recenti sul path locale
    $cmd_options = ' --delete  --no-perms  --continue --verbose  --parallel=2 ';
    $cmd_options = ' --no-perms  --continue --verbose  --parallel=2 --only-missing  --only-newer --no-recursion --no-perms ';
    $cmd = "lftp -c \" open ftp://$username:$password@$host; lcd $lcd; cd $rcd; mirror $cmd_options\"";
    echo $cmd."\n";
    echo `$cmd`;
}
//
function ftp_download_files($username, $password, $host, $lcd, $rcd, array $a_file_name){
    if( !is_dir($lcd) ){
        die("$lcd path do not exists \n");
    }
    $cmd_options = ' ';
    // aggiunge opzione dryrun ammeno che non si dia il comando --go
    $cmd_options .= ftp_init_dry_run();
    // get $file_name;
    $a_file_name = array_map(function($file_name) {
        return " get $file_name; ";
    }, $a_file_name );
    $cmd_file_name = implode(' ', $a_file_name );
    // xfer:clobber   di default non sovrascrive files già esistenti
    $cmd = "lftp -c \"set xfer:clobber on; open ftp://$username:$password@$host; lcd $lcd; cd $rcd;  $cmd_file_name  bye;\" ";
    echo "trasferisco files \n";
    echo $cmd . "\n";
    echo `$cmd`;
}
// testa $arg_index per verificare la presenza del parametro
function ftp_init_dry_run( ){
    $argv = $GLOBALS['argv'];
    foreach($argv as $param ) {
        if( $param === '--go') {
            echo colored_ko("attenzione, sto sovrascrivendo i files presenti localmente")."\n";
            $cmd_dry_run = '';
            return $cmd_dry_run;
        }
    }
    echo colored_ok("sync dry run.")."\n";
    $cmd_dry_run = '--dry-run';
    return $cmd_dry_run;
}
__END__
    ,
    'ftp_pull'=>('
        lftp -c " open ftp://\$user:\$pass@192.168.1.31; lcd /var/www/dms_caleffi/trunk/RPG/SRCMOD/; cd /www/zend-applications/PHP_SRC/SRCMOD; mirror  --delete  --no-perms  --continue --verbose  --parallel=2 "
    '),
    'ftp_push'=>alias_create('ftp_upload_file'),
    // singolo file
    'ftp_upload_file'=>('
function ftp_upload($local_file, $username, $password, $host, $rcd = "/", $is_dry_run = false) {
    $file_name = basename($local_file);
    $lcd = dirname($local_file);
    list( $r, $a_out) = e("which lftp") ;
    if ( !$r ) {
        $msg = sprintf(\'Errore lftp non è installato"%s" \', $r);
        throw new \Exception($msg);
    }
    $r = e("lftp -c \"open ftp://$username:$password@$host; lcd $lcd; cd $rcd; put $file_name\" ");
}
function ftp_upload2($local_file, $username, $password, $host, $remote_dir=\'\', $is_dry_run=false) {
    list( $r, $a_out) = e("which curl") ;
    if ( !$r ) {
        $msg = sprintf(\'Errore curl non è installato "%s" \', $r);
        throw new \Exception($msg);
    }
    $ftp_cmd = "curl --user $username:$password --upload-file $local_file ftp://$host/$remote_dir";
    if (!$is_dry_run) {
        echo e($ftp_cmd) . "\n";
    } else {
        echo "dry run: $ftp_cmd ";
    }
}
'
    ),
    'ftp_upload_dir'=><<<'__END__'
#!/bin/bash
HOST='mysite.com'
USER='myuser'
PASS='myuser'
TARGETFOLDER='/new'
SOURCEFOLDER='/home/myuser/backups'
lftp -f "
open $HOST
user $USER $PASS
lcd $SOURCEFOLDER
mirror --reverse --delete --verbose $SOURCEFOLDER $TARGETFOLDER
bye
"
__END__
    ,
    'ftp_merge'=>'
         curlftpfs  caleffigroup:xxxx@www.caleffigroup.it/website /mnt/ftp/
         meld /var/www/dms_investor_relations/ /mnt/ftp/
         sudo umount  ',
    'rclone'=>('
        rclone lsl gdrive_remote:UbuntuOne/AA_invoices
        rclone lsl gdrive_remote:/tmp
        rclone copy /local/path remote:path
        rclone copy /tmp/LA_promo_0419.csv  gdrive_remote:/tmp/LA_promo_0419b.csv
        ## List directories in top level of your drive
        rclone lsd gdrive_remote:
        ## List all the files in your drive
        rclone ls   gdrive_remote:
        ## To copy a local directory to a drive directory called backup
        rclone copy /home/source remote:backup
    '),
    'mime'=>'
        sudo apt-get install mpack
        munpack -f mime-attachments-file    ',
    'csv'=>'
csvgrep -e iso-8859-1 -c 1 -m "de" worldcitiespop | csvgrep -c 5 -r "\d+"
  | csvsort -r -c 5 -l | csvcut -c 1,2,4,6 | head -n 11 | csvlook
',
    'dns'=><<<'__END__'
dig caleffigroup.it
#
Check DNS and HTTP trace with headers for specific domains
### Set domains and external dns servers.
_domain_list=(google.com) ; _dns_list=("8.8.8.8" "1.1.1.1")
for _domain in "${_domain_list[@]}" ; do
  printf '=%.0s' {1..48}
  echo
  printf "[\\e[1;32m+\\e[m] resolve: %s\\n" "$_domain"
  for _dns in "${_dns_list[@]}" ; do
    # Resolve domain.
    host "${_domain}" "${_dns}"
    echo
  done
  for _proto in http https ; do
    printf "[\\e[1;32m+\\e[m] trace + headers: %s://%s\\n" "$_proto" "$_domain"
    # Get trace and http headers.
    curl -Iks -A "x-agent" --location "${_proto}://${_domain}"
    echo
  done
done
unset _domain_list _dns_list
#
# Resolves the domain name (using external dns server)
host google.com 9.9.9.9
# Checks the domain administrator (SOA record)
host -t soa google.com 9.9.9.9
#  dig
# Resolves the domain name (short output)
dig google.com +short
# Lookup NS record for specific domain
dig @9.9.9.9 google.com NS
# Query only answer section
dig google.com +nocomments +noquestion +noauthority +noadditional +nostats
# Query ALL DNS Records
dig google.com ANY +noall +answer
# DNS Reverse Look-up
dig -x 172.217.16.14 +short
__END__
    ,
    // find pc on a network
    'nmap'=>'
nmap -sP 192.168.1.*
# or more comonly
nmap -sn 192.168.1.0/24
# will scan the entire .1 to .254 range
# This does a simple ping scan in the entire subnet to see which all hosts are online.
# also gives all the machines in your subnet
ifconfig eth0
arp -a
# services of a host
nmap -T4 -F 192.168.1.89
# find a open ftp in subnet
nmap -p 21 155.100.100.*
nmap --top-ports 20 192.168.1.106
# faster queries,  Disabling DNS name resolution
nmap -p 80 -n 8.8.8.8
# OS resolution
nmap -A -T4 IP
# Detect service/daemon versions
nmap -sV localhost
#Ping scans the network
nmap -sP 192.168.0.0/24
#Show only open ports
nmap -F --open 192.168.0.0/24
#Full TCP port scan using with service version detection
nmap -p 1-65535 -sV -sS -T4 192.168.0.0/24
# scan and pass output to Nikto
nmap -p80,443 192.168.0.0/24 -oG - | nikto.pl -h -
    ',
    'namap_pentest'=><<<'__END__'
Recon specific ip:service with Nmap NSE scripts stack
# Set variables:
_hosts="192.168.250.10"
_ports="80,443"
# Set Nmap NSE scripts stack:
_nmap_nse_scripts="+dns-brute,\
                   +http-auth-finder,\
                   +http-chrono,\
                   +http-cookie-flags,\
                   +http-cors,\
                   +http-cross-domain-policy,\
                   +http-csrf,\
                   +http-dombased-xss,\
                   +http-enum,\
                   +http-errors,\
                   +http-git,\
                   +http-grep,\
                   +http-internal-ip-disclosure,\
                   +http-jsonp-detection,\
                   +http-malware-host,\
                   +http-methods,\
                   +http-passwd,\
                   +http-phpself-xss,\
                   +http-php-version,\
                   +http-robots.txt,\
                   +http-sitemap-generator,\
                   +http-shellshock,\
                   +http-stored-xss,\
                   +http-title,\
                   +http-unsafe-output-escaping,\
                   +http-useragent-tester,\
                   +http-vhosts,\
                   +http-waf-detect,\
                   +http-waf-fingerprint,\
                   +http-xssed,\
                   +traceroute-geolocation.nse,\
                   +ssl-enum-ciphers,\
                   +whois-domain,\
                   +whois-ip"
# Set Nmap NSE script params:
_nmap_nse_scripts_args="dns-brute.domain=${_hosts},http-cross-domain-policy.domain-lookup=true,http-waf-detect.aggro,http-waf-detect.detectBodyChanges,http-waf-fingerprint.intensive=1"
# Perform scan:
nmap --script="$_nmap_nse_scripts" --script-args="$_nmap_nse_scripts_args" -p "$_ports" "$_hosts"
__END__
    ,
    'lsof'=><<<__END__
#Show process that use internet connection at the moment
lsof -P -i -n
#Show process that use specific port number
lsof -i tcp:443
#Lists all listening ports together with the PID of the associated process
lsof -Pan -i tcp -i udp
#List all open ports and their owning executables
lsof -i -P | grep -i "listen"
#Show all open ports
lsof -Pnl -i
#Show open ports (LISTEN)
lsof -Pni4 | grep LISTEN | column -t
#List all files opened by a particular command
lsof -c "process"
#View user activity per directory
lsof -u username -a +D /etc
#Show 10 largest open files
lsof / | \
awk '{ if($7 > 1048576) print $7/1048576 "MB" " " $9 " " $1 }' | \
sort -n -u | tail | column -t
__END__
    ,
    //
    'httpie'=>'
http -p Hh https://www.google.com
-p - print request and response headers
H - request headers
B - request body
h - response headers
b - response body
http -p Hh --follow --max-redirects 5 --verify no https://www.google.com
-F, --follow - follow redirects
--max-redirects N - maximum for --follow
--verify no - skip SSL verification
http -p Hh --follow --max-redirects 5 --verify no --proxy http:http://127.0.0.1:16379 https://www.google.com
--proxy [http:] - set proxy server
',
    //
    'vpn'=><<<__END__
    /home/taz/Dropbox/etc/VPN_DMS.ovpn
    # import an open VPN conf
    sudo nmcli connection import type openvpn file /path/to/your.ovpn
    # up
    nmcli connection up DMS
    # info
    nmcli
    nmcli connection show DMS | grep ipv4
    # le connessioni mportate da Gnome NetworkManager
sudo gedit /etc/NetworkManager/system-connections/DMS
__END__
    ,
    //----------------------------------------------------------------------------
    //  sysadmin
    //----------------------------------------------------------------------------
    # remove executable bit from all files in the current directory
    'chmod'=>'chmod -R -x+X *',
    'path'=>"
    echo \$PATH | tr : '\\n' | sort
    ",
    'logrotate'=>('
        sudo vi /etc/logrotate.d/appname.log
        /var/log/appname.log {
                rotate 2
                weekly
                size 250k
                compress
        } '),
    'mail'=>'
        curl http://localhost/server-status | mail -s"apache is eating memory $apache_count" devsmt@gmail.com
        # To send emails I use msmtp, to configure:
        account default
        host smtp.1und1.de
        tls on
        tls_trust_file /etc/ssl/certs/ca-certificates.crt
        auth on
        from dennis@felsin9.de
        user dennis@felsin9.de
        password XXX
        #
        # @see /var/www/xxxxxwwww/bin/phpmail
        ',
    'disk'=>'
        du -sh
        df -h
        # controllo rapido
        sudo smartctl -t short /dev/sda
        #temperatura disco
        sudo hddtemp /dev/sda
        # mysql space
        sudo du -h /var/lib/mysql/
        # monitor disks I/O read/writes
        sudo iotop --only
        dstat -tdD total,sda5,sdb3 60
        # show the processes accessing a file
        fuser -v -m <file>
        # for htop,  F2 (Setup) > Select columns > Select RBYTES WBYTES > F10 (Done)
        ',
    //'";
    # show the first 20 biggest dirs from .
    ## du | \
    ## sort -r -n | \
    // awk  {split("K M G",v); s=1; while(\$1>1024){\$1/=1024; s++} print int(\$1)" "v[s]"\t"\$2}  | \ head -n 20
    'diff'=>'
        md5deep -r -l . | sort | md5sum
        find -s $dir1 -type f -exec md5sum {} \; | sort | md5sum
        diff -qr $dir1 $dir2
        # dif 2 dirs
        diff <(cd directory1 && find | sort) <(cd directory2 && find | sort)
        ',
    //
    // apache related maintanance
    'apache'=>'
        goaccess -f /var/log/apache2/other_vhosts_access.log -a > /tmp/report.html
        #
        # report text only
visitors access.log | less
# html report
visitors -A -m 30 access.log -o html > report.html
# If you want information on the usage patterns for your site you must provide the url prefix of your web site, and specify the --trails option.
visitors -A -m 30 access.log -o html --trails --prefix http://www.hping.org > report.html
# multiple files
visitors /var/log/apache/access.log.*
# compressed logs
zcat access.log.*.gz | visitors -
',
    'swap'=>'smem',
    'tail'=>'
# Annotate tail -f with timestamps
tail -f file | while read ; do echo "$(date +%T.%N) $REPLY" ; done
# Analyse an Apache access log for the most common IP addresses
tail -10000 access_log | awk \'{print $1}\' | sort | uniq -c | sort -n | tail
# Analyse web server log and show only 5xx http codes
tail -n 100 -f /path/to/logfile | grep "HTTP/[1-2].[0-1]\" [5]"
',
    'pwdx'=>
    'Show current working directory of a process
            pwdx <pid>  ',
    'jobs'=>'
        jobs       # Lists all jobs
        bg %n      # Places the current or specified job in the background, where n is the job ID
        fg %n      # Brings the current or specified job into the foreground, where n is the job ID
        Control-Z  # Stops the foreground job and places it in the background as a stopped job ',
];
return $commands;
