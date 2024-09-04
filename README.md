This package wrap Posthog Feature flag to Pennnant package. 

After installation, you can use Pennant facade to check feature flag from Posthog.

Important Note : if posthog throw exception, it will alwaays return false when check feature flag


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
POSTHOG_API_KEY=your-api-key
POSTHOG_HOST=your-host
```



Todo : 

- [x] Add scope
- [x] catch exception and return false when catch exception

Pennant method not available : 
- set
- setForAllScopes
- delete
- purge
