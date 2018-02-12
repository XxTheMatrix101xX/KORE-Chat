##TODO: add masternode monitoring via kore-nms

# KOREChat

Required;

1. Server (or rpi!) with good internet connection  
2. A Twilio account  
3. Some basic programing knowledge  
4. PHP 5.6+  
5. ngrok  


Greatly Recommended;

1. tmux
tmux is not needed but will DEFINATELY be helpful. Basically, all servers (or computers) will stop running whatever was active in the shell once you disconnect, tmux stops that, which means you can disconnect from your server without worrying about your code stopping and you not being able to get updates


## Now to get started

I am going to assume this is a fresh install so as always;
`sudo apt-get update && sudo apt-get upgrade -y`
if you do not already have git
`sudo apt-get install git`

now time to download ngrok;
https://ngrok.com/download chose which version is right for you, download and unzip.

or if you have a raspberry pi you want to use;
`wget https://bin.equinox.io/c/4VmDzA7iaHb/ngrok-stable-linux-arm.zip && unzip ngrok-stable-linux-arm.zip`

### Now to download the code
You can download the code into any directory you would like
`git clone https://github.com/xxthematrix101xx/KOREChat.git && cd KOREChat`

Now you need to make your config with the info from your Twilio console
`cp config/config.example.php config/config.php && nano config/config.php`

Once you have the account info in place you can save and exit
`CTRL+o, and hit return`
`CTRL+x, and hit return`

Now is when tmux really comes in handy,
start up tmux
`tmux`
and start up a PHP server on port 8000
`php -S localhost:8000`

now to leave that terminal and keep the php server running
`CTRL+B, D`

and start up another tmux instance
`tmux`

now cd to the directory with ngrok
`cd ~/path/to/ngrok/`

and start ngrok, on port 8000
`./ngrok http 8000`

take note of the url it gives you ( hence forth the url given will be called $nurl )
an example url would be https://eav3521.ngrok.io
you can use http or https,

Now log into your twilio console;
https://www.twilio.com/console/phone-numbers/incoming
click ON your number, this should bring you to a configure page for that number, scroll down to messaging
on "A Message comes in" select "Webhook" and then for the url do $nurl/reply.php

Now you can send messages to your twilio number
