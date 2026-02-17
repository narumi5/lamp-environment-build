# アプリ再稼働方法

```jsx
【EC2内の階層】

ec2
└── omoicho-app
    └── frontend
        ├── package.json
        ├── src/
        └── 
```

1. ssh -i xxx.pem ec2-user@[IPアドレス]
・Cloud Shellで実行
・pemキーを事前にアップロードする
2. cd omoicho-app
3. git pull   
・git hubの情報を更新
4. cd frontend
5. npm run build
・アプリを「本番用に完成形へ変換する」コマンド
6. pm2 start npm --name "name" -- start
・pm2再起動
7. pm2 list
・pm2の稼働状況を確認する
    
    ![image.png](%E3%82%A2%E3%83%97%E3%83%AA%E5%86%8D%E7%A8%BC%E5%83%8D%E6%96%B9%E6%B3%95/image.png)
    
8. sudo systemctl start nginx
・nginxの再起動
9. sudo systemctl status nginx
・nginxの稼働状況を確認する　
    
    ![image.png](%E3%82%A2%E3%83%97%E3%83%AA%E5%86%8D%E7%A8%BC%E5%83%8D%E6%96%B9%E6%B3%95/image%201.png)
    

【補足】

pm2 logs --lines 200
・ログを見ることができる

nginxとは

インターネットから来た通信を、正しくアプリに届ける門番

```
ユーザー（ブラウザ）
   ↓
nginx（受付・交通整理）
   ↓
アプリ（Next.js / Node.js / API）
```