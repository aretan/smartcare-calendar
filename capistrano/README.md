### Install

```
$ sudo gem install bundle
$ bundle install
```

### Config

1. develop.pemを発見する
2. eval \`ssh-agent\` # 起動してなかったら
3. ssh-add develop.pem

### Deploy

```
$ bundle exec cap develop deploy branch=master
```
