# なかじの便利なツール(s-nakajima/MyShell)

install 
-------
環境の再構築シェル
※/var/www/app以下のソースを最新化するシェル
（ただし、vagrant環境は構築していること）


nc3PluginTest
-------------
テストシェル（小まめに最新を取得してね）


createBakePlugin
----------------
NC3スケルトンシェル


unitTest
--------
UnitTestスケルトンシェル


issueCheck
-------------------
NC3のISSUEを確認し、集計するシェル

※詳しくは、[s-nakajima/issueCheck](https://github.com/s-nakajima/issueCheck)

~~~~
cd /var/www/MyShell
composer require s-nakajima/issue-check:@dev
~~~~
