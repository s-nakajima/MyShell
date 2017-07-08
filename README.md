# なかじの便利なツール(s-nakajima/MyShell)

## [install](https://github.com/s-nakajima/MyShell/tree/master/install)

環境の再構築シェル<br>
※/var/www/app以下のソースを最新化するシェル（ただし、vagrant環境は構築していること）

## [nc3PluginTest](https://github.com/s-nakajima/MyShell/tree/master/nc3PluginTest)

テストシェル（小まめに最新を取得してね）

## [createBakePlugin](https://github.com/s-nakajima/MyShell/tree/master/createBakePlugin)

NC3スケルトンシェル

## [unitTest](https://github.com/s-nakajima/MyShell/tree/master/createBakePlugin)

UnitTestスケルトンシェル

## [issueCheck (private)](https://github.com/s-nakajima/MyShell-issueCheck)
NC3のISSUEを確認し、集計するシェル

## [scanInstall (private)](https://github.com/s-nakajima/MyShell-scanInstall)
脆弱性チェック用のサイト構築

## [nc3Release (private)](https://github.com/s-nakajima/MyShell-nc3Release)
リリースパッケージを作成するシェル

## [nc3Backup](https://github.com/s-nakajima/MyShell-nc3Backup)
NC3 & Github issuesバックアップシェル

## [sakura (private)](https://github.com/s-nakajima/MyShell-sakura)
Sakuraに環境構築および最新化するシェル

## [issueImport (private)](https://github.com/s-nakajima/MyShell-issueImport)
GithubのIssueをリポジトリ移動するシェル

----------

## 環境構築（ただし、本人である私のみ）

~~~~~
cd /var/www/MyShell
git clone https://github.com/s-nakajima/MyShell-config.git
cp -pf MyShell-config/composer.json
~~~~~
