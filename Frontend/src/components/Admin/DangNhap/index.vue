<template>
  <div class="login-wrapper container-fluid p-0 d-flex flex-column vh-100">
    <div class="row g-0 flex-grow-1">
      <div class="col-lg-7 d-none d-lg-flex left-panel position-relative">
        <div class="overlay"></div>
        <div
          class="content position-relative p-5 d-flex flex-column justify-content-center text-white w-100"
        >
          <div class="badge-custom mb-4 fw-semibold">
            THE INTELLIGENT ESTATE
          </div>
          <h1 class="main-heading fw-bold">
            Editorial <span class="highlight-text">Intelligence</span><br />
            in Real Estate
          </h1>
        </div>
      </div>

      <div
        class="col-lg-5 d-flex flex-column justify-content-center align-items-center right-panel p-4 p-md-5 bg-white"
      >
        <div class="form-container w-100">
          <div class="mb-4">
            <h5 class="brand-text mb-4">The Intelligent Estate</h5>
            <h2 class="fw-bold mb-2 text-dark" style="font-size: 28px">
              Đăng nhập Quản trị viên
            </h2>
            <p class="text-muted small">
              Vui lòng truy cập cổng bảo mật dành cho Admin
            </p>
          </div>

          <form @submit.prevent="xuLyDangNhap">
            <div class="mb-3 w-100">
              <label
                class="form-label text-uppercase text-muted fw-bold small-label"
                >Email Admin</label
              >
              <div class="custom-field">
                <i class="fa-solid fa-envelope"></i>
                <input
                  type="email"
                  v-model="email"
                  placeholder="admin@intelligentestate.com"
                  required
                />
              </div>
            </div>

            <div class="mb-4 w-100">
              <div
                class="d-flex justify-content-between align-items-center mb-1"
              >
                <label
                  class="form-label text-uppercase text-muted fw-bold small-label mb-0"
                  >Mật khẩu</label
                >
                <a
                  href="#"
                  class="text-primary text-decoration-none small fw-semibold"
                  style="font-size: 12px"
                  >Quên mật khẩu?</a
                >
              </div>
              <div class="custom-field">
                <i class="fa-solid fa-lock"></i>
                <input
                  type="password"
                  v-model="password"
                  placeholder="••••••••••••"
                  required
                />
              </div>
            </div>
            <div class="w-100">
              <button
                type="submit"
                class="btn btn-submit w-100 py-3 fw-bold"
                :disabled="isLoading"
              >
                <span
                  v-if="isLoading"
                  class="spinner-border spinner-border-sm me-2"
                ></span>
                <span v-else>
                  Đăng nhập Hệ thống
                  <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                </span>
              </button>
            </div>
          </form>

          <div class="info-box mt-3 p-3 d-flex align-items-start gap-3">
            <i class="fa-solid fa-shield-halved text-primary mt-1"></i>
            <p
              class="mb-0 text-muted"
              style="font-size: 12px; line-height: 1.6"
            >
              Đây là hệ thống quản trị nội bộ. Mọi hoạt động truy cập đều được
              ghi lại theo <strong>Chính sách Bảo mật 2.4.0</strong>. Hành vi
              truy cập trái phép sẽ bị xử lý theo quy định.
            </p>
          </div>
        </div>
      </div>
    </div>

    <div
      class="footer d-flex flex-column flex-md-row justify-content-between align-items-center px-4 py-3 bg-light border-top"
    >
      <span
        class="text-muted fw-semibold mb-2 mb-md-0"
        style="font-size: 10px; letter-spacing: 1px"
      >
        © 2026 KLTN NHOM 25.
      </span>
      <div class="d-flex gap-4">
        <a
          href="#"
          class="text-muted text-decoration-none fw-semibold"
          style="font-size: 10px; letter-spacing: 1px"
          >SECURITY POLICY</a
        >
        <a
          href="#"
          class="text-muted text-decoration-none fw-semibold"
          style="font-size: 10px; letter-spacing: 1px"
          >SYSTEM STATUS</a
        >
        <a
          href="#"
          class="text-muted text-decoration-none fw-semibold"
          style="font-size: 10px; letter-spacing: 1px"
          >ADMIN HELP</a
        >
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import { createToaster } from "@meforma/vue-toaster";

const email = ref("");
const password = ref("");
const isLoading = ref(false);
const route = useRoute();
const router = useRouter();
const toaster = createToaster({ position: "top-right" });

const xuLyDangNhap = async () => {
  isLoading.value = true;
  try {
    const res = await axios.post("http://localhost:8000/api/admin/dang-nhap", {
      email: email.value,
      password: password.value,
    });

    if (res.status === 200 && res.data.token) {
      localStorage.setItem("admin_token", res.data.token);
      toaster.success("Đăng nhập thành công!");
      const redirectTarget =
        typeof route.query.redirect === "string"
          ? route.query.redirect
          : "/admin/chuc-vu";

      router.push(redirectTarget);
    }
  } catch (error) {
    if (error.response && error.response.status === 401) {
      toaster.error("Tài khoản hoặc mật khẩu không chính xác!");
    } else {
      toaster.error("Lỗi kết nối Server. Vui lòng thử lại!");
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
/* Reset nền tổng thể */
.login-wrapper {
  background-color: #ffffff;
  font-family: "Roboto", sans-serif;
}

/* --- CỘT TRÁI (HÌNH ẢNH) --- */
.left-panel {
  /* Tôi đang dùng một link ảnh nhà đẹp từ Unsplash. Sau này bạn có thể tải ảnh của bạn về thư mục assets rồi đổi link url() thành: url('../../../assets/hinh-anh-cua-ban.jpg') */
  background-image: url("https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80");
  background-size: cover;
  background-position: center;
}

.form-container {
  max-width: 420px;
  margin: 0 auto;
}

.custom-input-group {
  width: 100%;
}

.btn-submit {
  width: 100%;
}
.overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    135deg,
    rgba(15, 23, 42, 0.7) 0%,
    rgba(15, 23, 42, 0.2) 100%
  );
}

.badge-custom {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(8px);
  padding: 6px 16px;
  border-radius: 6px;
  font-size: 11px;
  letter-spacing: 2px;
  display: inline-block;
  width: fit-content;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.main-heading {
  font-size: 56px;
  line-height: 1.1;
  letter-spacing: -1px;
}

.highlight-text {
  color: #93c5fd; /* Màu xanh nhạt */
  font-style: italic;
}

/* --- CỘT PHẢI (FORM) --- */
.form-container {
  max-width: 420px; /* Khống chế chiều rộng form cho đẹp */
}

.brand-text {
  color: #1e3a8a;
  font-weight: 800;
  letter-spacing: -0.5px;
}

.small-label {
  font-size: 11px;
  letter-spacing: 1px;
}

/* Tuỳ chỉnh Input */
.custom-input-group {
  border-radius: 8px;
  overflow: hidden;
}
.input-group {
  width: 100%;
}

.input-group-text {
  min-width: 50px;
  justify-content: center;
}

.custom-field {
  width: 100%;
  display: flex;
  align-items: center;
  background: #f1f5f9;
  border-radius: 8px;
  padding: 14px 16px;
}

.custom-field i {
  margin-right: 10px;
  color: #64748b;
}

.custom-field input {
  border: none;
  outline: none;
  background: transparent;
  width: 100%;
  color: #1e293b;
}

.custom-input-group .input-group-text,
.custom-input-group .form-control {
  background-color: #f1f5f9 !important; /* Nền xám nhạt */
  padding-top: 14px;
  padding-bottom: 14px;
  color: #475569;
}

.custom-input-group .input-group-text {
  padding-left: 20px;
}

.custom-input-group .form-control:focus {
  box-shadow: none;
  background-color: #e2e8f0 !important;
}

.custom-input-group .form-control::placeholder {
  color: #94a3b8;
}

/* Nút Đăng nhập */
.btn-submit {
  background-color: #0f172a; /* Màu xanh đen đậm */
  color: white;
  border-radius: 8px;
  border: none;
  transition: all 0.3s ease;
}

.btn-submit:hover {
  background-color: #1e293b;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
}

/* Hộp thông tin */
.info-box {
  background-color: #f8fafc;
  border-radius: 12px;
  border: 1px solid #f1f5f9;
}

/* Footer */
.footer {
  background-color: #f8fafc !important;
}

/* FIX CĂN CHUẨN 100% */
.form-container {
  max-width: 420px;
  width: 100%;
  margin: 0 auto;
}

.custom-field {
  width: 100%;
  box-sizing: border-box;
}

.custom-field input {
  width: 100%;
}

.btn-submit {
  width: 100%;
  box-sizing: border-box;
}

/* FIX khoảng padding đồng đều */
.custom-field,
.btn-submit {
  height: 52px;
}
</style>
