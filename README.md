Adding configuration : 

edit pennant.php config file and add this as stores : 

```
        'posthog' => [
            'driver' => 'posthog',
        ],
```

Don't forget to install and configure posthog library and initialize it in your app.

Edit .env file :
```
POSTHOG_API_KEY=your-api-key
POSTHOG_HOST=your-host
```



Todo : 

- [] Add scope


Pennant method not available : 
- set
- setForAllScopes
- delete
- purge
