# NExT-Comment-Loop Plugin Specification

## 概要
NExT-Comment-Loop は、WordPress のコメント一覧を表示するためのカスタム Gutenberg ブロックを提供するプラグインです。**固定ページに設置し、指定した投稿タイプ（複数選択可）のコメントをサイト横断的にループ表示します。** 各コメントの日時、投稿者名、内容を表示することができます。

## 設置方法

- **設置場所**：任意の固定ページ（掲示板ページなど）
- **表示対象**：ブロック設定で指定した投稿タイプ全体のコメント（現在のページのコメントではない）

## 機能要件

### ブロック構成
1. **Comment Loop Block (親ブロック)**
   - 指定した投稿タイプのコメントをクエリしてループ表示するブロック
   - 設定可能な属性:
     - `postTypes`: 対象の投稿タイプ（複数選択可、デフォルト: `["post"]`）
     - `sortOrder`: ソート順
       - `date_asc`: 日時昇順 (古いコメントから表示)
       - `date_desc`: 日時降順 (新しいコメントから表示、デフォルト)
     - `number`: 取得するコメント数 (デフォルト: 10, 最大: 100)

2. **Comment Date Block (子ブロック)**
   - コメントの日時を表示するブロック
   - フォーマット: WordPress の日時フォーマット設定に従う

3. **Comment Author Block (子ブロック)**
   - コメント投稿者のハンドル名を表示するブロック
   - 匿名コメントの場合は "名無し" を表示

4. **Comment Content Block (子ブロック)**
   - コメントの内容を表示するブロック
   - `wp_kses_post()` でサニタイズ済み

### 使用方法
- Comment Loop Block をエディタに挿入
- サイドバー設定で対象投稿タイプ（チェックボックス）、ソート順、取得件数を指定
- 子ブロックとして Comment Date, Comment Author, Comment Content を追加
- フロントエンドで指定投稿タイプ全体のコメント一覧が表示される

## ブロック詳細仕様

### Comment Loop Block（`next/comment-loop`）

#### 属性

| 属性名 | 型 | デフォルト | 説明 |
|---|---|---|---|
| `postTypes` | `array` | `["post"]` | 対象の投稿タイプスラッグ配列（複数選択可） |
| `sortOrder` | `string` | `"date_desc"` | ソート順。`"date_asc"`（古い順）または `"date_desc"`（新しい順） |
| `number` | `integer` | `10` | 取得するコメント数（1〜100） |

#### インスペクターパネル（サイドバー設定）

- **対象の投稿タイプ**：チェックボックス（公開済みの全投稿タイプを列挙）
- **ソート順**：セレクトボックス
  - 新しい順（降順）← デフォルト
  - 古い順（昇順）
- **取得件数**：スライダー（1〜100、デフォルト 10）

#### その他

- `allowedBlocks` で子ブロックを `next/comment-date`、`next/comment-author`、`next/comment-content` に制限
- 承認済みコメント（`comment_approved = 1`）のみを対象
- 投稿タイプは `get_post_types(['public' => true])` で allowlist 検証
- コメントが0件の場合はフォールバックメッセージを表示

---

### Comment Date Block（`next/comment-date`）

- **ブロック名**: `next/comment-date`
- **親**: `next/comment-loop` 内のみ使用可能
- **属性**: `format`（string、デフォルト `"Y/m/d H:i"`）
- **表示**: PHP の `date_i18n()` でフォーマットした日時文字列

### Comment Author Block（`next/comment-author`）

- **ブロック名**: `next/comment-author`
- **親**: `next/comment-loop` 内のみ使用可能
- **属性**: なし
- **表示**: `comment_author`（匿名の場合は "名無し" など適切な表示）

### Comment Content Block（`next/comment-content`）

- **ブロック名**: `next/comment-content`
- **親**: `next/comment-loop` 内のみ使用可能
- **属性**: なし
- **表示**: `comment_content`（`wp_kses_post()` でサニタイズ）

---

## 技術仕様

### 実装言語
- JavaScript (ES6+)
- PHP 8.0 以上（サーバーサイドレンダリング）

### 依存関係
- WordPress 6.4 以上
- Gutenberg エディタ

### ビルドツール
- `@wordpress/scripts`
- npm

### ファイル構造
```
NExT-Comment-Loop/
├── src/
│   ├── comment-loop/
│   │   ├── block.json
│   │   ├── edit.js
│   │   ├── save.js
│   │   └── render.php
│   ├── comment-date/
│   │   ├── block.json
│   │   ├── edit.js
│   │   └── render.php
│   ├── comment-author/
│   │   ├── block.json
│   │   ├── edit.js
│   │   └── render.php
│   └── comment-content/
│       ├── block.json
│       ├── edit.js
│       └── render.php
├── build/
├── NExT-Comment-Loop.php
├── package.json
└── SPEC.md
```

### データ取得
- `get_comments()` または `WP_Comment_Query` を使用
- 現在の投稿のコメントのみを表示（省略可能な `postId` 属性で制御）
- ブロック登録は `register_block_type_from_metadata()` を使用

### セキュリティ
- コメント内容: `wp_kses_post()` でサニタイズ
- 投稿者名・日時: `esc_html()` でエスケープ
- 権限チェック: 承認済みコメントのみ表示

## 将来の拡張性
- コメントステータスフィルタ（承認済みのみなど）
- 特定の投稿タイプのコメント表示
- ページネーション対応
- コメント返信の階層表示
- アバター表示ブロック（`next/comment-avatar`）
