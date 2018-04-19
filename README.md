# SilverStripe Exact Online API connector #

This module establish an API connection with Exact Online (accounting software). At this moment it's for Dutch Exact Online accounts only, but in the next versions we will make it working for other countries as well (Germany, UK, ...).

### Requirements ###

SilverStripe 4

### Version ###

Using Semantic Versioning.

### Installation ###

Install via Composer:

composer require "hestec/silverstripe-exactonline": "1.*"

### Configuration ###

1. In the Exact Online app center (https://apps.exactonline.com) add an app for your API connection (instructions with screenshots shortly...)
2. After you registered the app, you will see **ClientId**, **ClientSecret** and **WebhookSecret**.  ![exact-app](https://res.cloudinary.com/hestec/image/upload/v1524145550/silverstripe-exactonline/exact-app.jpg)

3. Add this 3 keys to your mysite.yml:
```
Hestec\ExactOnline\ExactOnlineConnection:
  ClientId: 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'
  ClientSecret: 'xxxxxxxxxxxx'
  WebhookSecret: 'xxxxxxxxxxxxxxxx'
  ```

do a dev/build and flush.

### Usage ###

After installation and configuration go to Exact Online in the main menu of the CMS and follow the instructions to connect.

![connect](https://res.cloudinary.com/hestec/image/upload/v1524144117/silverstripe-exactonline/connect.jpg)

### Issues ###

No known issues.

### Todo ###

* Add other Exact Online countries.
* Expand the readme/instructions.