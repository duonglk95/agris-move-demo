#!/usr/bin/env bash
# Dựng WordPress local (SQLite) để xem trước theme + plugin. Chạy từ thư mục repo: bash dev/bootstrap.sh
set -e
REPO="$(cd "$(dirname "$0")/.." && pwd)"; WP=/home/claude/wptest
if [ ! -f "$WP/wp-load.php" ]; then
  mkdir -p /tmp/wpdl && cd /tmp/wpdl
  curl -sL https://github.com/WordPress/WordPress/archive/refs/tags/6.8.3.tar.gz | tar xz
  mv WordPress-6.8.3 "$WP"
  curl -sL https://github.com/WordPress/sqlite-database-integration/archive/refs/heads/main.tar.gz | tar xz
  mv sqlite-database-integration-main "$WP/wp-content/plugins/sqlite-database-integration"
  sed "s#{SQLITE_IMPLEMENTATION_FOLDER_PATH}#$WP/wp-content/plugins/sqlite-database-integration#; s#{SQLITE_PLUGIN}#sqlite-database-integration/load.php#" \
    "$WP/wp-content/plugins/sqlite-database-integration/db.copy" > "$WP/wp-content/db.php"
  cp "$WP/wp-config-sample.php" "$WP/wp-config.php"
fi
# Liên kết theme/plugin từ repo (sửa trong repo là thấy ngay)
rm -rf "$WP/wp-content/themes/agris-move" "$WP/wp-content/plugins/agris-move-vote"
cp -r "$REPO/wordpress/agris-move" "$WP/wp-content/themes/"
cp -r "$REPO/wordpress/agris-move-vote" "$WP/wp-content/plugins/"
php "$REPO/dev/setup.php" && php "$REPO/dev/seed.php" || true
echo "Chạy server: php -S localhost:8080 $REPO/dev/router.php"
