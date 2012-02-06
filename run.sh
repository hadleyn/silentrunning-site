#!/bin/bash
cd ..
rsync -va --delete --exclude=".git" sr/ /var/www/sr/