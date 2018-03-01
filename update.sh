#!/usr/bin/env bash
read -n1 -p "Update to latest master? [y,n]" doGit
if [ $doGit = "y" ]; then
    echo; echo "Updating from GIT master".
    git fetch origin master
    echo;
    read -n1 -p "Update composer? [y,n]" doComp
    if [ $doComp = "y" ]; then
        echo;echo "Updating composer".
        composer update
        echo
    else
        echo;echo "Skipping composer"
    fi
else
    echo;echo "Cancelled update"
fi
