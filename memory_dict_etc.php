<?php
declare (strict_types = 1);
// keys myst be lowercase
$commands = [
//----------------------------------------------------------------------------
    'wiki_search' => ('#cerca informazioni nel wiki
/home/taz/Dropbox/mywebsite/evidenceweb/bin/tool wiki:search wget'),
    'youtube-dl' => ('
# aggiornare sempre all ultima versione
sudo youtube-dl -U
youtube-dl -x --audio-format mp3   https://www.youtube.com/watch?v=3kW99COiygw
'),
    'ffmpeg' => ('
# mv resulting video to home
mv Game\ of\ Thrones\ soundtrack\;\ Cellocyl\ 2014-6pDlHdcfKbo.webm ~/Desktop/GoTSoundtrack.webm
ffmpeg -i ~/Desktop/GoTSoundtrack.webm -acodec libmp3lame -aq 4  ~/Desktop/GoTSoundtrack.mp3
mpg123  ~/Desktop/GoTSoundtrack.mp3
# encode video for web
ffmpeg -i input.mp4 -c:v libtheora -q:v 7 -c:a libvorbis -q:a 4 output.ogv
ffmpeg -i input.mp4 -vcodec libvpx -acodec libvorbis output.webm
'),
    'fonts' => '
# installare:
# copiare *.ttf in folder ~/.fonts
sudo apt-get install font-manager
    ',
    'google_search' => '
site: returns files located on a particular website or domain.
filetype: followed (without a space) by a file extension returns files of the specified type, such as DOC, PDF, XLS and INI. Multiple file types can be searched for simultaneously by separating extensions with “|”.
inurl: followed by a particular string returns results with that sequence of characters in the URL.
intext: followed by the searcher’s chosen word or phrase returns files with the string anywhere in the text.
',
    'joel_test' => '
Joel Test
    - Source control
    - One-step build / deploy
    - Daily builds
    - Bug database
    - Bugs fixed before writing new code
    - Up-to-date schedule
    - Specs / Requirements
    - Quiet working conditions
    - Best tools that money can buy
    - Testers
    - Code screening / competent coders
    - users or random individuals are used to test software products
',
];
return $commands;