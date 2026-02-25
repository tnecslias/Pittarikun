# 本番公開手順（Render）

このプロジェクトは `render.yaml` を使って、Webサービス + PostgreSQL を自動作成できます。

## 1. GitHub に push

```bash
git add Dockerfile docker/entrypoint.sh render.yaml docs/deploy-render.md
git commit -m "Add production deployment config for Render"
git push
```

## 2. Render でデプロイ

1. Render にログイン
2. `New +` -> `Blueprint` を選択
3. このリポジトリを接続
4. `render.yaml` が検出されるので、そのまま作成

作成されるもの:
- Web: `pittarikun`
- DB: `pittarikun-db` (PostgreSQL)

## 3. 初回デプロイ後に環境変数を設定

`pittarikun` サービスの `Environment` で以下を設定:

- `APP_URL`: Render が発行した本番URL（例: `https://pittarikun.onrender.com`）
- `STRIPE_PUBLISHABLE_KEY`: Stripe公開キー
- `STRIPE_SECRET`: Stripe秘密キー

設定後に `Manual Deploy` を実行。

### 検証用途で公開サイトを使う場合（推奨）

- `STRIPE_PUBLISHABLE_KEY` / `STRIPE_SECRET` は Stripe の **test キー**（`pk_test_...` / `sk_test_...`）を設定する
- 決済確認は Stripe の **テストカード**を使う（例: `4242 4242 4242 4242`）
- 公開環境（`APP_ENV=production`）では、ローカル開発用の「任意カード番号を通す」挙動は無効

## 4. 公開URL

Render の `pittarikun` サービスに表示される `https://...onrender.com` が公開URLです。

## 補足

- 起動時に `php artisan migrate --force` が自動実行されます。
- `storage:link` も起動時に自動実行されます。
