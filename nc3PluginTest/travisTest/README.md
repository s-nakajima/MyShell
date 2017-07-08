# テストシェル

## nc3PluginTest/travisTest
（小まめに最新を取得してね）

<pre>
cd /var/www/MyShell/nc3PluginTest/travisTest
bash nc3PluginTest.sh パッケージ名
    or 
bash nc3PluginTest.sh パッケージ名 github
</pre>

### ≪第一引数≫

パッケージ名

(例)bash travisTest.sh s-nakajima/Install github


### ≪第二引数≫

第二引数に「github」を指定することでgithubにアップされているソースでテストを実行します。
省略すると、すでにgithubからダウンロード済みであれば、そのソースにてテストを実行します。
