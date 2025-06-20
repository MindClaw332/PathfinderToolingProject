# 📘 Master's Toolkit

Welcome to **Master's Toolkit**, a Laravel-powered web application. This guide will walk you through installing Go, setting up Laravel, and running the project locally.

---

## 🚀 Prerequisites

Before running the project, make sure you have the following installed:

### 1. Go (Golang)

Go is used in this project for our webscraper so make sure you have golang installed.
you can follow the instructions on this webpage: https://go.dev/doc/install

### 2. PHP and Composer
Make sure you have:
    PHP ≥ 8.1
    Composer ≥ 2.x

### 3. Node.js & NPM
These are required for the frotend tooling

you can check if you have these installed by running
```bash
node -v
npm -v
```

### 4. a MySQL/mariaDb database
THE WEBSCRAPER REQUIRES A MYSQL OR MARIADB DATABASE TO BE REGISTERED INSIDE OF THE .ENV

## running the app

### 1. clone the repository
```bash
git clone https://github.com/your-username/masters-toolkit.git
cd masters-toolkit
```

### 2. install the dependencies
```bash
composer install
```

### 3. install node dependencies
```bash
npm install
```

### 4. Copy and configure the environment-variables
```bash
cp .env.example .env
```
Update .env with your database and mail credentials.

if you want to use the stripe functionality create a stripe account and add these variables to the .env
```
STRIPE_KEY=stripe_api_key
STRIPE_SECRET=stripe_secret_key
STRIPE_WEBHOOK_SECRET=your-stripe-webhook-secret

CASHIER_CURRENCY=eur
CASHIER_CURRENCY_LOCALE=nl_BE of course change these to fit your app
```

### 5. generate an application key
```bash
php artisan key:generate
```

### 6. migrate and seed the database
```bash
php artisan migrate --seed
```
this can take a while since this will also run the webscraper
if you do not want this to run the webscraper comment out this line:
```PHP
Process::timeout(800)->run("cd ./scraper && go run .");
```
`database/seeders/DatabaseSeeder.php`

### 7. running the app
```bash
composer run dev
```

### (optional) running the go tool separately
inside of the repository run 
```bash
cd ./scraper && go run .
```

### license
This project is open-source and licensed under the MIT License.