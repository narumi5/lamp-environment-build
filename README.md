# スクリーンショット

# LAMP環境構築

## ■ 実施内容

1. 使用するOSやサーバ環境の選択
2. OSインストール
3. OSインストール後の設定
4. Apacheをインストールしweb表示させる
5. PHPをインストールしweb表示させる
6. MySQLをインストールしweb表示させる
7. WordPressを利用してブログサイトを構築する

## 1.  使用するOSやサーバ環境の選択

- OS: Ubuntu 24.04.4 LTS (VirtualBox)
- Apache: 2.4
- MySQL: 8.0
- PHP: 7.4.33

下記３つを比較しUbuntuを選択しました

- CentOS Linux: サポート終了のため除外しました
- AlmaLinux: 2021年にリリースされ情報量が少ないため除外しました
- Ubuntu: 2004年にリリースされ情報量が豊富なため選択しました

## 2. OSをインストール

1. VirtualBoxをインストール

参考サイト: [https://www.oracle.com/jp/virtualization/technologies/vm/downloads/virtualbox-downloads.html](https://www.oracle.com/jp/virtualization/technologies/vm/downloads/virtualbox-downloads.html)

- M1 Macを使用しているため、アーキテクチャに対応しているApple Silicon版を選択

![スクリーンショット 2026-02-22 22.47.37.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-22_22.47.37.png)

1. Ubuntuをダウンロード

参考サイト: [https://ubuntu.com/download/server/arm](https://ubuntu.com/download/server/arm)

- 最新の24.04.4 LTSをダウンロード

![スクリーンショット 2026-02-21 12.27.45.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_12.27.45.png)

1. VirtualBoxを利用してUbuntuをインストール（M1 macのためARM版をインストール）

- VirtualBoxの新規作成画面

![スクリーンショット 2026-02-21 12.38.59.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_12.38.59.png)

- Ubuntuにログイン後の画面

![スクリーンショット 2026-02-21 12.53.27.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_12.53.27.png)

## 3. OSインストール後の設定

### 実施内容

3-1. サーバのタイムゾーンをJSTにする

3-2. 下記NTPサーバと時刻同期を行う

- ntp1.jst.mfeed.ad.jp
- ntp2.jst.mfeed.ad.jp
- ntp3.jst.mfeed.ad.jp

3-3. サーバのファイアウォールを有効にする

3-4. SELinuxを無効にする

### 3-1. サーバのタイムゾーンをJSTにする

時刻がUTCのためコマンド入力してJSTに設定する

1. `timedatectl`で時刻設定を確認→UTCだった
2. `sudo timedatectl set-timezone Asia/Tokyo` でJSTに変更
3. 再度`timedatectl` でJSTに変更を確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image.png)

### 3-2. NTPサーバと時刻同期を行う

1. chronyがインストールされてなかったので、インストールする

`sudo apt install chrony`

1. chrony.confファイルを編集

`sudo nano /etc/chrony/chrony.conf`

1. pool行をコメントアウト

「指定されたNTPサーバだけを使わせるため」にpool行をコメントアウトする

1. 指定されたNTPサーバーを追加

server [ntp1.jst.mfeed.ad.jp](http://ntp1.jst.mfeed.ad.jp/) iburst
server [ntp2.jst.mfeed.ad.jp](http://ntp2.jst.mfeed.ad.jp/) iburst
server [ntp3.jst.mfeed.ad.jp](http://ntp3.jst.mfeed.ad.jp/) iburst

※ iburstとburstの説明

iburst: 初回同期を高速に行う

burst:  毎回高速で同期を行う

1. 編集内容が反映された確認する

`sudo cat /etc/chrony/chrony.conf`

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%201.png)

1. `chronyc  sources`でNTPサーバーを確認する
2. `sudo systemctl restart chrony` で変更内容を反映する

![スクリーンショット 2026-02-22 23.47.27.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-22_23.47.27.png)

- 参考サイト

[https://chrony-project.org/doc/latest/chrony.conf.html](https://chrony-project.org/doc/latest/chrony.conf.html)　（公式）

[https://qiita.com/ohtsuka-shota/items/11f5100f7dcb52b9996d](https://qiita.com/ohtsuka-shota/items/11f5100f7dcb52b9996d)

[https://www.securewave.co.jp/blog/072](https://www.securewave.co.jp/blog/072)

### 3-3. サーバのファイアウォールを有効にする

1. ubuntuのファイアウォールが有効か確認

`sudo ufw status`

1. ubuntuのファイアウォールポートの番号22番を許可する（macのターミナルで入るため）

`sudo ufw allow 22` 

1. ubuntuのファイアウォールポートの番号80番を許可する（http通信を行うため）

`sudo ufw allow 80`

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%202.png)

- 参考サイト

ubuntu firewall

[https://documentation.ubuntu.com/server/how-to/security/firewalls/](https://documentation.ubuntu.com/server/how-to/security/firewalls/)（公式）

### 3-4. SELinuxを無効にする

- 公式ドキュメントを探してもSELinuxを無効にする方法が見つからず、記事を参考にした

![スクリーンショット 2026-02-21 18.28.57.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_18.28.57.png)

- SELinuxの状態を `getenforce` で確認したが、入っていなかった

![スクリーンショット 2026-02-21 18.33.06.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_18.33.06.png)

- 参考サイト

[https://documentation.ubuntu.com/server/search/?q=SELinux&check_keywords=yes&area=default](https://documentation.ubuntu.com/server/search/?q=SELinux&check_keywords=yes&area=default)（公式）

[https://zenn.dev/ishikawa84g/articles/1f8c3286c162dc1d985f](https://zenn.dev/ishikawa84g/articles/1f8c3286c162dc1d985f)

## 4. Apacheをインストールしweb表示させる

### 実施内容

4-1. apache2.4のインストール

4-2. Apacheの自動起動設定

4-3. VirtualHostの機能を利用し名前解決を行う

### 4-1. apache2.4のインストール

1. `sudo apt install apache2` でインストール
2. `sudo service apache2 start` で起動
3. `sudo service apache2 status` で起動確認

![スクリーンショット 2026-02-21 18.51.32.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_18.51.32.png)

- 参考サイト　apache2.4のインストール

[https://httpd.apache.org/docs/2.4/en/install.html](https://httpd.apache.org/docs/2.4/en/install.html)（公式）

![スクリーンショット 2026-02-21 18.44.33.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_18.44.33.png)

### 4-2. Apacheの自動起動設定

1. `sudo systemctl enable apache2` でApacheを自動起動させる
2. VisualBoxを停止し、再起動
3. `sudo systemctl status apache2` で自動起動できていることを確認 

![スクリーンショット 2026-02-21 19.16.56.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_19.16.56.png)

- 参考サイト

[https://www.freedesktop.org/software/systemd/man/latest/systemctl.html](https://www.freedesktop.org/software/systemd/man/latest/systemctl.html)

![スクリーンショット 2026-02-21 19.04.39.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_19.04.39.png)

1. Apacheのweb画面表示

![スクリーンショット 2026-02-21 19.28.12.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_19.28.12.png)

### 4-3. VirtualHostの機能を利用し名前解決を行う

### 目標

1. Webブラウザで`http://morii-hbtask.local/` にアクセスすると「こんにちは。ありがとう。」と表示される
2. Webブラウザで`http://narumi-hbtask.local/` にアクセスすると「ありがとう。こんにちは。」と表示される

### 実施内容

1. ディレクトリを作る

`sudo mkdir -p /var/www/lastname`
`sudo mkdir -p /var/www/firstname` 

1. 名字用、名前用のconfファイルをそれぞれ作る

`sudo nano /etc/apache2/sites-available/lastname.conf` で名字用のconfファイルを作る

`sudo nano /etc/apache2/sites-available/firstname.conf` で名前用のconfファイルを作る

- それぞれのファイルの中身

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%203.png)

- 参考サイト

[https://httpd.apache.org/docs/2.4/en/vhosts/name-based.html](https://httpd.apache.org/docs/2.4/en/vhosts/name-based.html)（公式）

[https://qiita.com/UedaTakeyuki/items/014ca393b69e6932bdf6](https://qiita.com/UedaTakeyuki/items/014ca393b69e6932bdf6)

![スクリーンショット 2026-02-21 19.38.13.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_19.38.13.png)

1. 名字用と名前用のindex.htmlを2つ作る

`sudo nano /var/www/lastname/index.html`

`sudo nano /var/www/firstname/index.html`

- 文字化けするためUTF-8を設定した

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%204.png)

1. ブラウザが動いているのがmacのためmac側のhostsの設定変更

- `sudo nano /etc/hosts`で下記2つを追加

192.168.11.8 morii-hbtask.local

192.168.11.8 narumi-hbtask.local

- 変更の画面

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%205.png)

1. morii-hbtask.localで接続

![スクリーンショット 2026-02-21 23.33.26.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-21_23.33.26.png)

1. narumi-hbtask.localで接続

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%206.png)

## 5. PHPをインストールしweb表示させる

## 目標

Webブラウザで http://morii-hbtask.local/phpinfo.php にアクセスすると、phpinfoの情報が表示される

## 実施内容

5-1. PHP7.4をインストール

5-2. lastnameフォルダにphpinfo.php ファイルを作る

### 5-1. php7.4インストール

1. `sudo apt install libapache2-mod-php7.4` でphpインストール
2. `php -v` でバージョンの確認
3. `sudo systemctl restart apache2` でApacheの再起動

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%207.png)

- 参考サイト

[https://ubuntu.com/server/docs/how-to/web-services/install-php/](https://ubuntu.com/server/docs/how-to/web-services/install-php/)

### 5-2. lastnameフォルダphpinfo.php ファイルを作る

1. `sudo nano /var/www/lastname/phpinfo.php` でlastnameのサイト用のフォルダphpinfo.php ファイルを作る
2. `ls -l /var/www/lastname/` でファイルが作られたか確認
3. lastnameフォルダのphpinfo.php ファイルの中に`<?php phpinfo(); ?>` を入力して保存
4. `sudo cat /var/www/lastname/phpinfo.php` で入力内容を確認
5. `sudo systemctl restart apache2` でApache再起動

- 確認後の写真

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%208.png)

1. http://morii-hbtask.local/phpinfo.php にアクセスして表示確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%209.png)

## 6. MySQLをインストールしweb表示させる

## 目標

Webブラウザで`http://narumi-hbtask.local/mysql.php`にアクセスすると、`meibo`テーブルに登録した自分のフルネームが表示される

## 実施内容

1. MySQLインストール
2. MySQLの自動起動設定
3. hbtaskというデータベースを新規作成
4. hbtaskデータベースにmeiboというテーブル作成
5. meiboテーブルにデータ登録
6. PHPで `number=1` の `username` を取得してweb表示する

### 6-1. MySQLインストール

1. `sudo apt update`
2. `sudo apt install mysql-server` でMySQLをインストール
3. `mysql --version` でバージョン確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2010.png)

- 参考サイト

[https://dev.mysql.com/doc/mysql-apt-repo-quick-guide/en/#repo-qp-apt-install-from-source](https://dev.mysql.com/doc/mysql-apt-repo-quick-guide/en/#repo-qp-apt-install-from-source)

### 6-2. MySQLの自動起動設定

1. `sudo systemctl start mysql` で起動
2. VisualBoxを停止し、再起動
3. `sudo systemctl status mysql` で起動確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2011.png)

### 6-3. hbtaskデータベースの新規作成

1. `sudo mysql` でMySQLにログイン
2. `CREATE DATABASE hbtask;` で’hbtask’データベースが作られる
3. `SHOW DATABASES;`で内容を確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2012.png)

- 参考サイト

[https://dev.mysql.com/doc/refman/8.0/en/create-database.html](https://dev.mysql.com/doc/refman/8.0/en/create-database.html)

### 6-4. hbtaskデータベースにmeiboテーブル作成

【カラム】

- number（INT）
- username（VARCHAR）

1. `CREATE TABLE meibo (number INT,username VARCHAR(100));` でmeiboテーブル作成
2. `SHOW TABLES;` で内容を確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2013.png)

- 参考サイト

[https://dev.mysql.com/doc/refman/8.0/en/create-table.html](https://dev.mysql.com/doc/refman/8.0/en/create-table.html)

### 6-5. meiboテーブルにデータ登録

【登録する内容】

- number: 1
- username: Morii Narumi

1. `USE hbtask;`で登録するデータベースを選ぶ
2. `INSERT INTO meibo VALUES (1, ‘Morii Narumi');`でテーブルにデータ登録
3. `SELECT * FROM meibo;`で登録できたか確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2014.png)

### 6-6. PHPで number=1 の username を取得してweb表示する

1. `sudo nano /var/www/firstname/mysql.php` でmysql.phpを作成
2. 下記の内容を作成

`<?php`

`$conn = new mysqli("localhost", "root", "", "hbtask");`

`$sql = "SELECT username FROM meibo WHERE number = 1";
$result = $conn->query($sql);`

`$row = $result->fetch_assoc();`

`echo "私の名前は " . $row["username"] . " です。";`

`$conn->close();`

`?>`

1. `sudo cat /var/www/firstname/mysql.php` で内容確認

Webブラウザで`http://narumi-hbtask.local/mysql.php`にアクセスしたが表示されなかった

`curl -i http://localhost/mysql.php`でも確認したが500エラーが返ってきた

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2015.png)

1. `sudo tail -n 50 /var/log/apache2/error.log` でエラーのログ確認

`mysqli not found`でmysqliがないことが原因と判明

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2016.png)

1. `sudo apt install php7.4-mysql` でインストール

`php -m | grep mysqli`でインストールされたことを確認

しかし、webブラウザで確認したが表示されなかったため、再度エラーログを確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2017.png)

`curl -I [http://narumi-hbtask.local](http://morii-hbtask.local/)`で入力すると403エラーが出た

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2018.png)

mysqliでrootユーザーを指定していることが原因と予想

rootは「サーバー管理者専用」だから、Webアプリからは使えないようになっている

- 参考サイト

[https://dev.mysql.com/doc/refman/8.0/en/socket-pluggable-authentication.html](https://dev.mysql.com/doc/refman/8.0/en/socket-pluggable-authentication.html)

1. mysqlをユーザー作成

`CREATE USER 'hbuser'@'localhost' IDENTIFIED BY 'hbpass';`でhbuserを作る

`GRANT ALL PRIVILEGES ON hbtask.* TO 'hbuser'@'localhost';`でhbuser に hbtask データベースを使う権限を与える

`FLUSH PRIVILEGES;` 今変更した権限をすぐ有効にする

- ユーザーを作成し、権限を与えた

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2019.png)

- 権限があるか確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2020.png)

- 参考サイト

[https://dev.mysql.com/doc/refman/8.0/ja/account-management-statements.html](https://dev.mysql.com/doc/refman/8.0/ja/account-management-statements.html)（公式）

[https://qiita.com/yuu1111main/items/98baacaf381a2b778ccc](https://qiita.com/yuu1111main/items/98baacaf381a2b778ccc)

7. ユーザー名とパスワードを変更

ユーザー名: hbuser

パスワード: hbpass

- 最終的なmysql.phpの記述内容

`<?php`

`$conn = new mysqli("localhost", "hbuser", "hbpass", "hbtask");`

`$sql = "SELECT username FROM meibo WHERE number = 1";
$result = $conn->query($sql);`

`$row = $result->fetch_assoc();`

`echo "私の名前は " . $row["username"] . " です。";`

`$conn->close();`

`?>`

1. `narumi-hbtask.local/mysql.php`にアクセスして確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2021.png)

- その他参考にしたサイト

[https://shinya-tech.com/entry/2022/02/18/041455#PHPからSQLでデータを取得する](https://shinya-tech.com/entry/2022/02/18/041455#PHP%E3%81%8B%E3%82%89SQL%E3%81%A7%E3%83%87%E3%83%BC%E3%82%BF%E3%82%92%E5%8F%96%E5%BE%97%E3%81%99%E3%82%8B)

[https://techplay.jp/column/514](https://techplay.jp/column/514)

[https://dev.mysql.com/doc/refman/8.0/en/insert.html](https://dev.mysql.com/doc/refman/8.0/en/insert.html)（INSERT）

[https://www.php.net/manual/en/mysqli.construct.php](https://www.php.net/manual/en/mysqli.construct.php)

## 7. WordPressを利用してブログサイトを構築する

1. wordpressをダウンロード

`sudo wget [https://wordpress.org/latest.tar.gz](https://wordpress.org/latest.tar.gz)`でダウンロード

lsコマンドでダウンロードされたことを確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2022.png)

1. ダウンロードしたファイル解答して、権限を変更

`sudo chown -R www-data:www-data wordpress`

※ wordpressフォルダを「Apacheが使える持ち主」にする

`sudo chmod -R 755 wordpress`

※ wordpressフォルダのアクセス権を設定する

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2023.png)

1. WordPress 用データベースおよびユーザーの作成

`CREATE DATABASE wordpress`でデータベース作成

`CREATE USER 'wpuser'@'localhost' IDENTIFIED BY 'wppass';`でユーザーを作成

`GRANT ALL PRIVILEGES ON wordpress.* TO 'wpuser'@'localhost';`でwpuserにwordpress データベースを使う権限を与える

`FLUSH PRIVILEGES;`今変更した権限をすぐ有効にする

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2024.png)

1. confファイルの設定

`sudo nano /etc/apache2/sites-available/wordpress.conf`で作成

- wordpress.confの中身

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2025.png)

`sudo a2enmod rewrite`でApacheの「URL書き換え機能」を有効にする

`sudo a2dissite lastname.conf` で無効化（wordpress.confとservernameが重複しているため）

`sudo a2ensite wordpress.conf`でwordpress.confを有効化

1. Ubuntu側でhostの設定を変更

`sudo nano /etc/hosts` でhostの設定を変更

- 追加した内容: 127.0.0.1   morii-hbtask.local

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2026.png)

`sudo systemctl restart apache2` Apacheを再起動

1. 言語を日本語に設定

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2027.png)

1. データベース接続情報を入力

![スクリーンショット 2026-02-22 19.06.27.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-22_19.06.27.png)

1. WordPressの初期設定を入力

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2028.png)

- インストール成功画面

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2029.png)

1. WordPress のURLを `/blog` に固定する

`sudo nano /var/www/wordpress/wp-config.php` で編集し下記の2行を追加

- define('WP_HOME', 'http://morii-hbtask.local/blog');
- define('WP_SITEURL', 'http://morii-hbtask.local/blog');

- 追加した内容

![スクリーンショット 2026-02-24 0.45.14.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88_2026-02-24_0.45.14.png)

1. `http://morii-hbtask.local/blog`で投稿した記事を閲覧できることを確認

![image.png](%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88/image%2030.png)

- 参考サイト

[https://ubuntu.com/tutorials/install-and-configure-wordpress#1-overview](https://ubuntu.com/tutorials/install-and-configure-wordpress#1-overview)（公式）

[https://ja.wordpress.org/support/article/how-to-install-wordpress/](https://ja.wordpress.org/support/article/how-to-install-wordpress/)（公式）

[https://ja.wordpress.org/support/article/changing-the-site-url/](https://ja.wordpress.org/support/article/changing-the-site-url/) (URL固定)

[https://qiita.com/cherubim1111/items/b259493a39e36f5d524b](https://qiita.com/cherubim1111/items/b259493a39e36f5d524b)