# AgriS MOVE – demo trang chủ

Một trang tĩnh (`index.html`) chạy bằng Caddy. Railway tự nhận `Dockerfile` và cấp cổng qua biến `PORT`.

## Deploy lên Railway

**Cách 1 – từ GitHub**
1. Đưa thư mục này lên một repo GitHub.
2. Trên Railway: New Project → Deploy from GitHub repo → chọn repo.
3. Vào service → Settings → Networking → Generate Domain để lấy link chia sẻ.

**Cách 2 – bằng Railway CLI**
```
npm i -g @railway/cli
railway login
railway init
railway up
railway domain
```

## Giữ chi phí thấp
- Settings → bật **Serverless (App Sleeping)** để service ngủ khi không ai truy cập.
- Xóa service khi demo xong.
