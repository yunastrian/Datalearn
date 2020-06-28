# Datalearn

Datalearn is simple LMS with spreadsheet and autograder tools. It uses Google Spreadsheet with Sheets and Drive API. This application is developed for my final project.

## Tech Stack
- Framework used is **Laravel 6.18.3** with **PHP 7**
- Database used is **MySQL**

## Requirements
- PHP 7.4 or newer
- MySQL 10.4.11-MariaDB or newer
- Composer 1.9.3 or newer

## Installation
1. Make a copy `.env.example` and change its name to `.env`
2. Customize `.env` file with your configuration
3. Create a new database with the same name as defined in `.env` file
4. Run these commands in terminal
```
composer install
composer update
php artisan storage:link
php artisan key:generate
php artisan config:cache
php artisan migrate
```
5. The application uses Google Spreadsheet. Configure the Google Service Account to use it
6. This application uses TinyMCE. Configure it
7. There are problems with the libraries. Fix it
8. Congratulation, the application has been successfully installed. Run the application with this command
```
php artisan serve
```

## Configure Google Service Account
1. Open [Google API Console](https://console.developers.google.com)
2. Go to **Credentials tab** and open **CREATE CREDENTIALS --> Service Account**
3. Fill the forms in first step. Second step and third step are optional
4. After done, there is a service account that newly created under **Service Accounts** list. Open it
5. Click the button **ADD KEY** and choose **JSON**
6. Download the file and change its name to `credentials.json`
7. Move this file to project directory in `app/Http/Controllers`
8. Finish

## Configure TinyMCE
1. Open [TinyMCE](https://www.tiny.cloud)
2. Complete the registration
3. After that, you got API Key in Dashboard
4. Copy the API Key and paste to `app.blade.php` file in project direcotry `resources/views/layouts` on `line 16`
```php
<script src="https://cdn.tiny.cloud/1/<API KEY>/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
```
5. Finish

## Solve Libraries Problem
There are problems with the libraries. Do this step to solve the problems
### Problem 1
#### Error
```
ErrorException
implode(): Passing glue string after array is deprecated. Swap the parameters 
```
#### Solution 
Open `Resouce.php` file in project directory `vendor/google/apiclient/src/Google/Service`. In `line 291`, change
```php
$requestUrl .= '?' . implode($queryVars, '&');
```
to
```php
$requestUrl .= '?' . implode('&', $queryVars);
```

### Problem 2
#### Error
```
ErrorException
count(): Parameter must be an array or an object that implements Countable 
```
#### Solution
Open `CurlFactory.php` file in project directory `vendor/guzzlehttp/guzzle/src/Handler`. In `line 67`, change
```php
if (count($this->handles) >= $this->maxHandles) {
```
to
```php
if (count( (array) $this->handles) >= $this->maxHandles) {
```

## Author
Kurniandha Sukma Yunastrian