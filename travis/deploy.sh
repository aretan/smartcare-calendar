#!/bin/bash

if [[ $TRAVIS_PULL_REQUEST == 'false' ]]; then
  cd capistrano && bundle install && bundle exec cap develop deploy branch=${TRAVIS_BRANCH}
  curl -vv -X POST -H "Authorization: token ${github_access_token}" \
     https://api.github.com/repos/aretan/smartcare-calendar/statuses/${TRAVIS_COMMIT} \
     -d "{\"state\": \"success\", \"target_url\": \"http://${TRAVIS_BRANCH//\//-}.smartcare-calendar.net/\", \"description\": \"Detailsから画面を確認できます\", \"context\": \"capistrano/${TRAVIS_BRANCH}\"}"
fi
