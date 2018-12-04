# A modern joind.in clone built with Symfony 4, Vue.js and Mercure

Browse the demo website: https://symfonycon.les-tilleuls.coop
And don't forget to [rate this talk on joind.in](https://joind.in/event/symfonycon-lisbon-2018/integrate-vuejs-components-in-a-symfony-app-add-e2e-tests-with-panther)

## Execute locally

* Install the PHP dependencies: `composer install`
* Install the JS dependencies: `yarn install`
* Start the PHP dev web server: `bin/console server:start`
* Start the Encore dev web server: `yarn encore dev-server --hot`
* Download Mercure hub on https://mercure.rocks
* Start the Mercure hub: `ALLOW_ANONYMOUS=1 CORS_ALLOWED_ORIGINS=http://localhost:8000 JWT_KEY=ChangeMe ADDR=:3000 ./mercure`
