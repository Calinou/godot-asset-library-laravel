#!/usr/bin/env bash

chgrp -R 1000 storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
