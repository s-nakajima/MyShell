# テストシェル

## nc3PluginTest

<pre>
cd /var/www/MyShell/nc3PluginTest
bash nc3PluginTest.sh プラグイン名(キャメル記法)
</pre>

### ≪第一引数≫

プラグイン名(キャメル記法)

(例)bash nc3PluginTest.sh AccessCounters

※All.Pluginにすると、すべてのプラグインのテストを実施する


### ≪第二引数≫

第二引数に「phpcs」「phpmd」「phpcpd」「gjslint」「phpunit」を付けることで、個別にテストを実行することができる。
省略すると「all」を指定した場合と同じとなる。

| パラメータ         | 説明                    |
| ------------------ | ----------------------- |
| remove             | 改行コードをLFに変換    |
| pear_install       | Pear等に関する最新化    |
| all                | 下記の全てのテスト実施  |
| phpcs              | PHP CodeSniffer         |
| phpmd              | PHP Mess Detector       |
| phpcpd             | PHP Copy/Paste Detector |
| gjslint            | JavaScript Style Check  |
| phpunit            | PHP UnitTest            |
| phpdoc             | PHP Mess Detector       |
| phpmd              | PHP Documentor(phpdoc)  |

環境構築直後は、`pear_install`を実施してください。

### ≪第三引数(all)≫

第二引数がallの場合で、第三引数を付けることで、カバレッジレポートを詳細に表示することができる。

| パラメータ         | 説明                               |
| ------------------ | ---------------------------------- |
| caverageAll        | カバレッジレポートを詳細に表示する |


### ≪第三引数(phpunit)≫

第二引数がphpunitの場合で、第三引数を付けることで、個別にテストを実行することができる。

| パラメータ         | 説明                                             |
| ------------------ | ------------------------------------------------ |
| list               | 一覧を表示する                                   |
| list.caverageAll   | 一覧を表示し、カバレッジレポートを詳細に表示する |
| (テストファイル名) | 個別にテストを実施する                           |
| 上記以外、省略     | プラグインの全テストを実施する                   |

（個別で実行例）
<pre>
# cd /var/www/MyShell/nc3PluginTest
# bash nc3PluginTest.sh Announcements phpunit Model/Announcement
</pre>

（list例）
<pre>
# cd /var/www/MyShell/nc3PluginTest
# bash nc3PluginTest.sh Announcements phpunit list

Welcome to CakePHP v2.6.5 Console
---------------------------------------------------------------
App : app
Path: /var/www/app/app/
---------------------------------------------------------------
CakePHP Test Shell
---------------------------------------------------------------
Announcements Test Cases:
[1] AllAnnouncements
[2] Controller/AnnouncementsApp
[3] Controller/AnnouncementsController
[4] Controller/AnnouncementsControllerError
[5] Controller/AnnouncementsControllerValidateError
[6] Model/Announcement
[7] Model/AnnouncementAppModel
[8] Model/AnnouncementError
[9] Model/AnnouncementValidateError
What test case would you like to run?  
[q] > 
</pre>
