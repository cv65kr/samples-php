```php
docker-compose up -d
docker-compose exec app sh
- composer install
- vendor/bin/rr get --no-interaction \
    && mv rr /usr/local/bin/rr \
    && chmod +x /usr/local/bin/rr
```

Open multiple terminals:

On first terminal
```
rr -c .rr.yaml serve
```

on second terminal
```
seq 1 5000 | xargs -I{} php app.php signal
```


on third terminal
```
seq 1 5000 | xargs -I{} php app.php signal
```

open couple more to spam workflows asap
