## Smoke Alam Matrix

A matrix to summarise Smoke Alarms for each property and report their compliance

### default restrictions

* note by default the user is restricted see ```src\application\app\currentUser.php``` for detail

#### Install Standalone (Development / Windows 10)
1. Install Pre-Requisits
   1. Install PHP : http://windows.php.net/download/
      * Install the non threadsafe binary
        * Test by running php -v from the command prompt
          * If required install the VC++ runtime available from the php > download page
        * by default there is no php.ini (required)
          * copy php.ini-production to php.ini
   2. Install Git : https://git-scm.com/
      * Install the *Git Bash Here* option
   3. Install Composer : https://getcomposer.org/
2. Setup a new project
   ```
   git clone https://github.com/bravedave/smokealarm.git
   cd smokealarm
   rm -fR .git
   ```
2. Install dependencies &amp; run
   ```
   composer update
   ```
2. The will error, because DB is not configured _you might want to review to defaults.json_
   ```
   mv src/application/data/defaults-sample.json src/application/data/defaults.json
   composer update
   run.cmd
   ```

   ... the result is visible at http://localhost/

#### Install as component
```
composer require bravedave/smokealarm
```
