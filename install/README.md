# 環境の再構築シェル

## install
※/var/www/app以下のソースを最新化するシェル
（ただし、vagrant環境は構築していること）

<pre>
cd /var/www/MyShell/install
bash install.sh
</pre>

### ≪第一引数≫

| パラメータ         | 説明                                             |
| ------------------ | ------------------------------------------------ |
| develop            | 中島が開発中の環境                               |
| install            | インストーラだけを実行する                       |
| origin / 省略      | NC3コアにコミットされている純粋な環境(git clone) |

### ≪第二引数≫

| パラメータ         | 説明                                  |
| ------------------ | ------------------------------------- |
| docs               | NetCommons3Docsも含めて最新にする     |
| それ以外（省略可） | NetCommons3Docsは最新にしない         |
