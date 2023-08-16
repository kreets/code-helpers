# code-helpers
Code snippet helper dashboard

# pre-require
create empty database
copy .env.example to .env
modify database credentials and data

create storage directory

cd storage/
mkdir -p framework/{sessions,views,cache}
chmod -R 775 framework
cd ..

# install
- composer install
- php artisan migrate
- php artisan voyager:admin admin@localhost.com --create 
- npm run build
