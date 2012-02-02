#!/bin/bash
cd ..
rsync -va --exclude=".git" sr/ /var/www/sr/