# Warnock Variety Show

## Why
1.  I didn't want the event to have the prerequisite of an account with any existing social media. 
2.  I wasn't crazy about the existing options designed for this market (ex. Evite) 
3. A fun challenge that gave me total control over the events. 

## Features
##### Event Creation 
- Admins can create event for a specific time and date as well as set a max attendance.

##### Invitations / RSVPs
- Individual Invitations - the best option for tracking the status of invitations and talents
- General/Open Invitations - best option for quickly disseminating information about the show. *However this opens the issue of making the event public and people rsvp multiple times accidentally.*

## Tech
- Laravel 8
- Livewire
- Vue.js
- Npm

## Installation
copy .env.example to .env and fill in machine relevant credentials
```sh
composer install
npm install
php artisan serve
```

## Production
**For dreamhost**
```sh
nvm use 17
npm run dev/prod
```


