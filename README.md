## smartcare-calendar

### Install

```
$ brew install ansible git
$ brew cask install virtualbox vagrant
$ git clone git@github.com:aretan/smartcare-calendar.git
$ cd smartcare-calendar
$ vagrant up
```

### URL

* Frontend - http://192.168.33.10/
* Backend - http://192.168.33.10/api/v1/
* Database - http://192.168.33.10/phpmyadmin/

### Directory

+ ansible - `実行環境の設定スクリプト（Vagrant環境、Development環境、Production環境）`
+ capistrano - `デプロイスクリプト`
+ travis - `CI/CD関連の資材`
+ api - `API本体`
+ public - `静的コンテンツ (Bootstrap)`
+ Vagrantfile - `Vagrant環境の立上げスクリプト`
