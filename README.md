# Pennant Posthog Driver

## Presentation
This package wrap Posthog Feature flag to Pennnant package.

After installation, you can use Pennant facade to check feature flag from Posthog.

__Important Note__ : 
- if posthog throw exception, it will always return false when check feature flag
- installation need a personnal access token


## Install 
### pre requise
Create personnal access tocken (https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens)

### workflow to follow (after create access tocken) : 
#### Install token
```
composer config --global --auth github-oauth.github.com your_token
```
#### Check token presence : 
```
composer config --global --list
```
#### Composer repositories :

```
        {
            "type": "vcs",
            "url": "git@github.com:Findymail/pennant-posthog-driver.git"
        }
```



## Command installation
```
composer require findymail/pennant-posthog-driver
```


## Configuration
Adding configuration :

edit pennant.php config file and add this as stores : 

```
        'posthog' => [
            'driver' => 'posthog',
        ],
```

Don't forget to configure posthog library and initialize it in your app.

Edit .env file :
```
PENNANT_STORE=posthog

POSTHOG_API_KEY=your-api-key
POSTHOG_HOST=your-host
```



## Todo : 

- [x] Add scope
- [x] catch exception and return false when catch exception
- [x] EtE test with command and web route

## Pennant method not available : 
- set
- setForAllScopes
- delete
- purge
