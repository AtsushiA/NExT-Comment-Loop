# NExT Comment Loop

WordPress Gutenberg ブロックプラグイン。**固定ページに設置し、指定した投稿タイプのコメントをループ表示します。**

## 機能

- コメントを投稿タイプ横断でループ表示
- 対象投稿タイプを複数選択可能
- ソート順（新しい順・古い順）と取得件数を設定可能
- 日時・投稿者名・コメント本文・関連投稿タイトルをインナーブロックで自由に配置

## ブロック一覧

| ブロック名 | 説明 |
|---|---|
| `next/comment-loop` | コメントループ（親ブロック） |
| `next/comment-date` | コメント日時（フォーマット自由設定） |
| `next/comment-author` | 投稿者ハンドル名 |
| `next/comment-content` | コメント本文 |
| `next/comment-post-title` | 関連投稿タイトル（リンク有無切替） |

## 動作要件

- WordPress 6.4 以上
- PHP 8.0 以上

## インストール

1. このリポジトリを `wp-content/plugins/NExT-Comment-Loop/` に配置
2. WordPress 管理画面でプラグインを有効化

## 開発

```bash
npm install
npm run build
```

開発時のウォッチモード:

```bash
npm run start
```

## 使い方

1. 任意の固定ページ（例: 掲示板ページ）にエディタで **Comment Loop** ブロックを追加
2. サイドバーで対象の投稿タイプにチェック、ソート順・取得件数を設定
3. インナーブロックとして **Comment Date**・**Comment Author**・**Comment Content**・**Comment Post Title** を好みの順で配置
4. 保存するとフロントエンドにコメント一覧が表示される

## ライセンス

GPL-2.0-or-later
