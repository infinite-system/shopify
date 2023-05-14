#!/bin/bash
mkdir ./database/migrations-backup

cp ./database/migrations/* ./database/migrations-backup/

echo 'Backup migrations before installing livewire.'

cp ./config/sanctum.php ./install/sanctum.php

echo 'Backup sanctum.php config before installing livewire.'

php artisan jetstream:install livewire

cp ./install/navigation-menu.blade.php ./resources/views/

echo 'Install navgation-menu.blade.php over base livewire to have products navigation.'

rm -rf ./database/migrations/*

mv ./database/migrations-backup/* ./database/migrations/

rmdir ./database/migrations-backup

echo 'Restore migrations after livewire install.'

mv ./install/sanctum.php ./config/sanctum.php

echo 'Restore original config/sanctum.php after livewire install.'

php artisan migrate

echo 'Migrations installed.'