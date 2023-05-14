#!/bin/bash
mkdir ./database/migrations-backup

cp ./database/migrations/* ./database/migrations-backup/

echo 'Backup migrations before installing livewire.'

cp ./config/sanctum.php ./install/sanctum.php

echo 'Backup sanctum.php config before installing livewire.'