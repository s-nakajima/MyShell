# UnitTestファイル作成シェル

## unitTest

<pre>
cd /var/www/MyShell/unitTest
bash startUnitTest.sh [--add-all-test] &lt;plugin_name&gt; [&lt;type&gt; [&lt;file&gt; [&lt;method&gt;]]]
</pre>

※ --add-all-test を指定すると、AllXxxxxTest.phpを作成する。


### ≪第一引数(plugin_name  キャメル形式)≫

プラグイン名(キャメル記法)

(例)bash startUnitTest.sh AccessCounters


### ≪第二引数(type)≫

タイプ

| パラメータ           | 説明                    |
| -------------------- | ----------------------- |
| Config               | Configのテストファイル(routes.php)を作成する |
| Console/Command      | Console/Commandのテストファイルを作成する |
| Console/Command/Task | Console/Command/Taskのテストファイルを作成する |
| Controller           | Controllerのテストファイルを作成する |
| Controller/Component | Controller/Componentのテストファイルを作成する |
| Model                | Modelのテストファイルを作成する |
| Model/Behavior       | Model/Behaviorのテストファイルを作成する |
| View/Elements        | View/Elementsのテストファイルを作成する |
| View/Helper          | View/Helperのテストファイルを作成する |
| TestSuite            | TestSuiteのテストファイルを作成する |
| Other                | その他のテストファイルを作成する |
| All or 省略          | 全ファイルのテストファイルを作成する |

(例)bash startUnitTest.sh AccessCounters Model


### ≪第三引数(file)≫

ファイル
※省略すると、すべてのファイルが対象となる

(例)bash startUnitTest.sh AccessCounters Model AccessCounter



### ≪第四引数(method)≫

メソッド
※省略すると、すべてのメソッドが対象となる

(例)bash startUnitTest.sh AccessCounters Model AccessCounter saveAccessCounter

<br>

# Fixtureについて
一般プラグインについて、各プラグインで必要なFixtureを作る必要があります。

https://github.com/s-nakajima/MyShell/blob/master/unitTest/AboutFixture.md
