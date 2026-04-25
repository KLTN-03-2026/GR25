import axios from 'axios';
import router from '@/router';

let isShowingAuthAlert = false;

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api',
  timeout: 15000,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
});

// Request Interceptor
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    if (import.meta.env.DEV) {
      console.log('🔐 Request:', config.method?.toUpperCase(), config.url);
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response Interceptor
api.interceptors.response.use(
  (response) => response,
  (error) => {
    const status = error.response?.status;
    const currentPath = router.currentRoute.value?.path || window.location.pathname;

    if (status === 401 && !isShowingAuthAlert) {
      isShowingAuthAlert = true;

      let loginPath = '/khach-hang/dang-nhap';
      if (currentPath?.startsWith('/admin')) loginPath = '/admin/dang-nhap';
      else if (currentPath?.startsWith('/moi-gioi')) loginPath = '/moi-gioi/dang-nhap';

      const confirmed = confirm('⚠️ Phiên đăng nhập đã hết hạn.\n\nBạn có muốn đăng nhập lại không?');

      if (confirmed) {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_type');
        localStorage.removeItem('user_info');

        if (currentPath !== loginPath) {
          router.push({ path: loginPath, query: { redirect: currentPath } });
        } else {
          window.location.href = loginPath;
        }
      }

      setTimeout(() => { isShowingAuthAlert = false; }, 3000);
    }

    return Promise.reject(error);
  }
);

export function clearAuth() {
  localStorage.removeItem('auth_token');
  localStorage.removeItem('user_type');
  localStorage.removeItem('user_info');
}

export function isAuthenticated() {
  return !!(localStorage.getItem('auth_token') && localStorage.getItem('user_type'));
}

export default api;