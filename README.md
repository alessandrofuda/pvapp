# PvApp

###### Leads management

---

### Software Architecture

**Dev environment**: Docker **Containers** orchestrated through Docker-compose

- frontend (CLI Vue js) ---> http://localhost:3000 
- backend (Laravel) ---> http://localhost:8081
- webserver (nginx) ---> reverse proxy, port mapping (NO `proxy_pass`!)
- db (mysql) ---> mysql://localhost:3309   
- supervisor (queues/jobs worker monitoring)
- scheduler (crontab process)

**Docker global/env. config**: `.env.global` (root) env variables injected into containers via Docker-compose.yml

**STACK**: LEMP (php:8.0-fpm)

**BE & Api Routing**: Laravel 8 (API Rest only)

**FE & routing**: Vue.js framework

**Auth scaffold**: Laravel Fortify (FE agnostic, routes: `php artisan route:list`)

**API Auth**: Laravel Sanctum

**Specific configs**: `.env` moved to root project (through AppServiceProvider setting)


