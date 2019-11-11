#!/bin/bash

remote_repository="git@github.com:aretan/smartcare-calendar.git"
deploy_directory="/var/www/smartcare-calendar"
branch_list_file=$(mktemp)

ls -1 ${deploy_directory} > ${branch_list_file}

for branch in $(git -c core.sshCommand="ssh -i ~/.ssh/develop.pem" ls-remote ${remote_repository} | awk '{print $2}' | fgrep "refs/heads/"); do
    branch=${branch//refs\/heads\//}
    sed -i -e "/^${branch//\//-}$/d" ${branch_list_file}
done

for trash_branch in $(cat ${branch_list_file}); do
    if [ -n "${trash_branch}" ]; then
        if [ -d "${deploy_directory}/${trash_branch}" ]; then
            rm -rf "${deploy_directory}/${trash_branch}"
            echo "removed: ${deploy_directory}/${trash_branch}"
        fi
    fi
done

rm ${branch_list_file}
