# DBAI

This document describes how to deploy DBAI web application based on Laravel framework


## Install Required Framework and Tools

1. Check out https://laravel.com/docs/12.x/installation to learn how to install Laravel.

2. Check out https://getcomposer.org/ to learn how to install Composer. 

## Deploy Web Application
1. Download and move project folder under **/var/www/laravel**.

2. In MySQL, create a new database called **dbai**.

3. Install Laravel Dependencies
In project folder, run this command
```
Composer install
```

4. Set up database and update date in **.env** file

5. Generate a new larval project key
```
php artisan key:generate
```

6. **Data Migration**

Check if all required data and related dictionary is placed as following hierarchy:
```
/storage/app/

├── data
│   ├── db_vyyyymmdd 
│   │   └── [Datasets files]
│   ├── dictinary.xlsx
│   └── history.csv
│   
└── download
    ├── dbai_cytof
    │   └── [cytof files]
    ├── dbai_receptor
    │   └── [receptor files]
    └── dbai_scrna
        └── [scrna files]
```

migrate database schema
```
php artisan migrate
```

feed database separatly
```
FOLDER_NAME=db_v20250113 php artisan db:seed --class=DatasetSeeder
FOLDER_NAME=db_v20250113 php artisan db:seed --class=UserSeeder
FOLDER_NAME=db_v20250113 php artisan db:seed --class=HistorySeeder
```

To update a specitif dataset, run following command respectively after update specific feeder file
```
php artisan migrate:refresh --path=/database/migrations/create_datasets_table.php FOLDER_NAME=db_v20250113 php artisan db:seed --class=DatasetSeeder
```

7. Lauch Application (for local testing)
```
php artisan serve
```
