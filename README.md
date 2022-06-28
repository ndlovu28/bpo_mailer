## BPO MAILER
Send email to contacts on the BPO web portal by CO3.

### REQUIREMENTS
 - PHP 7.4+
 - Composer

### INSTALATION
To install this package run the commands bellow.

```
composer require ndlovu28/bpo_mailer
```
Register the package in config/app.php by appending the line bellow in prividers section
```
Ndlovu28\BpoMailer\Providers\BpoMailerProvider::class,
```
Load the database with the command bellow
```
php artisan migrate
```

### INITIALISATION
Make sure in your .env file you have the key-pair as bellow
```
...

//The name of your applications template file
APP_TEMPLATE=taropa_account
//Version of BPO to use (Legacy: for older version | New: For the latest version)
BPO_VERSION=Legacy

BPO_URL=http://41.160.190.205:50000/bpowebportal/BPOWebPortal.svc
BPO_USER=bpo_user
BPO_PASS=xxxxxxxx
```
To install all requirements and load contacts data, run the command below
```
php artisan bpomailer:install
```

### USAGE
once you have served your application visit *127.0.01:8000/bpo/send/*, or deployed on the server visit *yourhost.co.za/bpo/send/*

