// src/axios/config.js
import axios from 'axios';
import router from '@/router';
import Swal from 'sweetalert2';

// Flag chống spam popup
let isShowingAuthAlert = false;

// Tạo axios instance
const api = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
  timeout: 10000,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
});

// === HELPER FUNCTIONS ===

const getLoginPath = (path) => {
  if (path?.startsWith('/admin')) return '/admin/dang-nhap';
  if (path?.startsWith('/moi-gioi')) return '/moi-gioi/dang-nhap';
  return '/khach-hang/dang-nhap';
};

const clearAuth = () => {
  localStorage.removeItem('auth_token');
  localStorage.removeItem('user_type');
  localStorage.removeItem('user');
  localStorage.removeItem('admin_token');
  localStorage.removeItem('khach-hang_token');
  localStorage.removeItem('moi-gioi_token');
};

// === REQUEST INTERCEPTOR ===
api.interceptors.request.use(
  config => {
    const token = localStorage.getItem("auth_token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  error => Promise.reject(error)
);

// === RESPONSE INTERCEPTOR ===
api.interceptors.response.use(
  response => response,
  error => {
    const status = error.response?.status;
    const currentPath = router.currentRoute.value?.path || window.location.pathname;

    // 🔴 Xử lý 401
    if (status === 401) {
      // Token hết hạn thực sự -> Redirect về login
      if (!isShowingAuthAlert) {
        isShowingAuthAlert = true;
        const loginPath = getLoginPath(currentPath);
        
        clearAuth();

        Swal.fire({
          icon: 'warning',
          title: 'Phiên đăng nhập hết hạn',
          text: 'Vui lòng đăng nhập lại!',
          confirmButtonText: 'Đăng nhập',
          allowOutsideClick: false,
          allowEscapeKey: false,
          showCancelButton: false
        }).then(() => {
          isShowingAuthAlert = false;
          
          if (currentPath !== loginPath) {
            router.push(loginPath);
          } else {
            window.location.href = loginPath;
          }
        });
      }
    }
    
    return Promise.reject(error);
  }
);

export default api;