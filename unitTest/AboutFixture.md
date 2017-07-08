# Fixtureについて

基本的には、下記の構成でテストを行います。そのため、Fixtureも下記の内容を用意して下さい。

### コンテンツのFixture
````
// * ルーム管理者が書いたコンテンツ＆一度公開して、下書き中
//   (id=1とid=2で区別できるものをセットする)
array(
	'id' => '1',
	'block_id' => '2',
	'key' => 'content_key_1',
	'language_id' => '2',
	'status' => '1',
	'is_active' => true,
	'is_latest' => false,
	//TODO:その他のフィールドデータ
	'created_user' => '1'
),
array(
	'id' => '2',
	'block_id' => '2',
	'key' => 'content_key_1',
	'language_id' => '2',
	'status' => '3',
	'is_active' => false,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '1'
),
// * 一般が書いたコンテンツ＆一度も公開していない（承認待ち）
array(
	'id' => '3',
	'block_id' => '2',
	'key' => 'content_key_2',
	'language_id' => '2',
	'status' => '2',
	'is_active' => false,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '4'
),
// * 一般が書いたコンテンツ＆公開して、一時保存
//   (id=4とid=5で区別できるものをセットする)
array(
	'id' => '4',
	'block_id' => '2',
	'key' => 'content_key_3',
	'language_id' => '2',
	'status' => '1',
	'is_active' => true,
	'is_latest' => false,
	//TODO:その他のフィールドデータ
	'created_user' => '4'
),
array(
	'id' => '5',
	'block_id' => '2',
	'key' => 'content_key_3',
	'language_id' => '2',
	'status' => '3',
	'is_active' => false,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '4'
),
// * 編集者が書いたコンテンツ＆一度公開して、差し戻し
//   (id=6とid=7で区別できるものをセットする)
array(
	'id' => '6',
	'block_id' => '2',
	'key' => 'content_key_4',
	'language_id' => '2',
	'category_id' => '1',
	'status' => '1',
	'is_active' => true,
	'is_latest' => false,
	//TODO:その他のフィールドデータ
	'created_user' => '3'
),
array(
	'id' => '7',
	'block_id' => '2',
	'key' => 'content_key_4',
	'language_id' => '2',
	'status' => '4',
	'is_active' => false,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '3'
),
// * 編集長が書いたコンテンツ＆一度も公開していない（下書き中）
array(
	'id' => '8',
	'block_id' => '2',
	'key' => 'content_key_5',
	'language_id' => '2',
	'status' => '3',
	'is_active' => false,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '2'
),
````


### ブロックIDの紐付くFixture
````
array(
	'id' => '2',
	'block_id' => '2',
	'key' => 'content_block_1',
	//TODO:その他のフィールド追記
),
array(
	'id' => '4',
	'block_id' => '4',
	'key' => 'content_block_2',
	//TODO:その他のフィールド追記
),
````
その他、使用できるBlockデータは、<a href="https://github.com/NetCommons3/Blocks/blob/master/Test/Fixture/BlockFixture.php#L56-L178">こちら</a>を参考に設定して下さい。


### ブロックKeyに紐付くFixture
````
array(
	'id' => '1',
	'block_key' => 'block_1',
	//TODO:その他のフィールド追記
),
array(
	'id' => '2',
	'block_key' => 'block_2',
	//TODO:その他のフィールド追記
),
````
その他、使用できるBlockデータは、<a href="https://github.com/NetCommons3/Blocks/blob/master/Test/Fixture/BlockFixture.php#L56-L178">こちら</a>を参考に設定して下さい。


### フレームIDに紐付くFixture

基本的には、フレームIDと紐付くテーブルは無いはずですが、もしFixtureを生成する場合、

<u>frame_id = '6', '16', '18'</u>を除いて作成して下さい。


### フレームKeyに紐付くFixture
````
array(
	'id' => '6',
	'frame_key' => 'frame_3',
	//TODO:その他のフィールド追記
),
````
その他、使用できるFrameデータは、<a href="https://github.com/NetCommons3/Frames/blob/master/Test/Fixture/FrameFixture.php#L47-L178">こちら</a>を参考に設定して下さい。


### テストで使用する主なFrameデータ
````
//メイン
array(
	'id' => '6',
	'language_id' => '2',
	'room_id' => '1',
	'box_id' => '3',
	'plugin_key' => 'test_plugin',
	'block_id' => '2',
	'key' => 'frame_3',
	'name' => 'Test frame main',
	'weight' => '1',
	'is_deleted' => '0',
),
//フレームのブロックなし
array(
	'id' => '14',
	'language_id' => '2',
	'room_id' => '1',
	'box_id' => '3',
	'plugin_key' => 'test_plugin',
	'block_id' => null,
	'key' => 'frame_7',
	'name' => 'Test frame main 3',
	'weight' => '2',
	'is_deleted' => '0',
),
````
その他、使用できるFrameデータは、<a href="https://github.com/NetCommons3/Frames/blob/master/Test/Fixture/FrameFixture.php#L47-L178">こちら</a>を参考に設定して下さい。

