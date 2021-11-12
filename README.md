## PvApp

###### Leads management

---

### Software Architecture

**Dev environment**: Docker Containers orchestrated through Docker-compose
- frontend (CLI Vue js)
- backend (Laravel)
- webserver (nginx)
- db (mysql)
- supervisor (queues/jobs worker monitoring)
- scheduler (crontab process)


**STACK**: LEMP (php:8.0-fpm)

**BE & Api Routing**: Laravel 8 (API Rest only)

**FE & routing**: Vue.js framework

**Browser**: http://localhost:8082

**Auth scaffold**: Laravel Fortify (FE agnostic, routes: `php artisan route:list`)

**API Auth**: Laravel Sanctum


