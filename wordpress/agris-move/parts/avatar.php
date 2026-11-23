      <form class="tk-form st-form" data-avatar>
        <h2>Tạo avatar</h2>
        <label>Ảnh của bạn<input type="file" name="photo" accept="image/*"></label>
        <label>Phóng to<input type="range" name="zoom" min="1" max="3" step="0.01" value="1"></label>
        <p class="tk-note">Kéo ảnh trong khung để căn chỉnh. Ảnh chỉ xử lý trên máy bạn, không tải lên hệ thống.</p>
        <button class="btn" type="button" id="avDown">Tải avatar</button>
      </form>
      <div class="st-preview"><canvas id="avCanvas" width="1080" height="1080" class="round"></canvas></div>
