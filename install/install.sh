#!/bin/bash
cp ./install/navigation-menu.blade.php ./resources/views/

echo 'Install navgation-menu.blade.php over base livewire to have products navigation.'

rm -rf ./database/migrations/*

mv ./database/migrations-backup/* ./database/migrations/

echo 'Restore migrations after livewire install.'

mv ./install/sanctum.php ./config/sanctum.php

echo 'Restore original config/sanctum.php after livewire install.'
