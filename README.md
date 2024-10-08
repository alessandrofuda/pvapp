## Technical/Ops notes

### Run Containers
`docker compose up`
`docker exec -it pvapp_app bash`

### Show app info
`php artisan about`

### Start dev server
`npm run dev`
(PROD: `npm run build`)

### dev credentials per role
--> see users seeder

### FE used tools
- Inertia.js (on top of Vue.js v.3)
- tailwind css
- vite for FE assets/building management

### roles (spatie pkg)
- admin
- user
- operator

### localization lang on Vue FE
using `useTrans()` function on vue.js
(pick-up translations from `lang/it.json` BE and pass to vue via `HandleInertiaRequests` middleware)
Same translation json for BE and FE

