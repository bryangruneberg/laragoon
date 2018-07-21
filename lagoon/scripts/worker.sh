#!/bin/bash

cd /app/ 
while [ 1 -gt 0 ]; do 
  php -d memory_limit=${PHP_MEMORY_LIMIT} artisan queue:work --timeout=3600 --tries=2 
  sleep 5
done
