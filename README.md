How to get set up developing on CBDQ:

- clone this repo
- set up mysql, php and node
- copy `credentials.php.example` as `credentials.php`.
- create a database - see dbconfig for details. put the mysql password in `credentials.php`
- create a twitter app - see https://apps.twitter.com. note, you will need to supply a callback URL, even if it's not used later. copy the application id & secret into `credentials.php`
- add the path to `node` in `credentials.php`
- if you want the tweet button to work, clone `https://github.com/v21/traceryhosting-send_tweet`, run `npm update` to fetch the dependencies, and add the path to `send_tweet.js` to `credentials.php`
- you can spin up a dev server with the builtin php server like so: `php -S localhost:8000`