import { createRouter, createWebHistory } from "vue-router";
// import authGuard from "./authGuard"; // 👈 Nếu không dùng thì comment lại

const routes = [

  // ===== ADMIN =====
  {
    path: "/admin/dang-nhap",
    component: () => import("../components/Admin/DangNhap/index.vue"),
    name: "AdminDangNhap",
    meta: { layout: "blank", guest: true, userType: "admin" }
  },

  {
    path: "/admin",
    redirect: "/admin/dashboard"
  },

  {
    path: "/admin/dashboard",
    component: () => import("../components/Admin/Dashboard/index.vue"),
    name: "AdminDashboard",
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/moi-gioi",
    component: () => import("../components/Admin/QuanLyNguoiDung/MoiGioi/index.vue"),
    name: "AdminQuanLyMoiGioi",
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/khach-hang",
    component: () => import("../components/Admin/QuanLyNguoiDung/KhachHang/index.vue"),
    name: "AdminQuanLyKhachHang",
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/bat-dong-san",
    component: () => import("../components/Admin/QuanLyBDS/index.vue"),
    name: "AdminQuanLyBDS",
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/goi-tin",
    component: () => import("../components/Admin/GoiTin/DanhSachGoi/index.vue"),
    name: "AdminGoiTin",
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/lich-su-mua-goi",
    component: () => import("../components/Admin/GoiTin/LichSuMuaGoi/index.vue"),
    name: "AdminLichSuMuaGoi",
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/loai-bat-dong-san",
    component: () => import("../components/Admin/LoaiBatDongSan/index.vue"),
    name: "AdminLoaiBDS",
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/phan-quyen",
    component: () => import("../components/Admin/PhanQuyen/index.vue"),
    name: "AdminPhanQuyen",
    meta: { layout: "admin", roles: ["admin"] }
  },
  
  {
    path: "/admin/ho-so-ca-nhan",
    component: () => import("../components/Admin/Profile/index.vue"),
    name: "AdminProfile",
    meta: { layout: "admin", roles: ["admin"] }
  },

  // ===== KHÁCH HÀNG (PUBLIC + AUTH) =====
  {
    path: "/",
    component: () => import("../components/KhachHang/TrangChu/index.vue"),
    name: "Home",
    meta: { layout: "khach-hang", public: true }
  },

  {
    path: "/khach-hang/dang-nhap",
    component: () => import("../components/KhachHang/DangNhap/index.vue"),
    name: "KhachHangDangNhap",
    meta: { layout: "auth", guest: true, userType: "khach-hang" }
  },

  {
    path: "/khach-hang/dang-ky",
    component: () => import("../components/KhachHang/DangKy/index.vue"),
    name: "KhachHangDangKy",
    meta: { layout: "auth", guest: true, userType: "khach-hang" }
  },

  {
    path: "/khach-hang/trang-chu",
    component: () => import("../components/KhachHang/TrangChu/index.vue"),
    name: "KhachHangTrangChuFull",
    meta: { layout: "khach-hang", roles: ["khach-hang", "moi-gioi"] }
  },

  {
    path: "/khach-hang/danh-sach-bat-dong-san",
    component: () => import("../components/KhachHang/DanhSachBatDongSan/index.vue"),
    name: "KhachHangDanhSachBatDongSan",
    meta: { layout: "khach-hang", public: true }
  },

  {
    path: "/khach-hang/chi-tiet-bat-dong-san/:id",
    component: () => import("../components/KhachHang/ChiTietBatDongSan/index.vue"),
    name: "KhachHangChiTietBatDongSan",
    meta: { layout: "khach-hang", public: true }
  },
  
  {
    path: "/khach-hang/profile",
    component: () => import("../components/KhachHang/Profile/index.vue"),
    name: "KhachHangProfile",
    meta: { layout: "khach-hang", roles: ["khach-hang"] }
  },


  // ===== MÔI GIỚI =====
  {
    path: "/moi-gioi/dang-nhap",
    component: () => import("../components/MoiGioi/DangNhap/index.vue"),
    name: "MoiGioiDangNhap",
    meta: { layout: "blank", guest: true, userType: "moi-gioi" } 
  },


  {
    path: "/moi-gioi/dang-ky",                                   
    component: () => import("../components/MoiGioi/DangKy/index.vue"), 
    name: "MoiGioiDangKy",                                       
    meta: { 
      layout: "auth",       
      guest: true,           
      userType: "moi-gioi"   
    }
  },

  {
    path: "/moi-gioi/trang-chu",
    component: () => import("../components/MoiGioi/TrangChu/index.vue"),
    name: "MoiGioiTrangChu",
    meta: { layout: "moi-gioi", roles: ["moi-gioi"] }
  },

  {
    path: "/moi-gioi/dang-tin",
    component: () => import("../components/MoiGioi/DangTin/index.vue"),
    name: "MoiGioiDangTin",
    meta: { layout: "moi-gioi", roles: ["moi-gioi"] }
  },

  {
    path: "/moi-gioi/quan-ly-khach-hang",
    component: () => import("../components/MoiGioi/KhachHang/index.vue"),
    name: "MoiGioiQuanLyKhachHang",
    meta: { layout: "moi-gioi", roles: ["moi-gioi"] }
  },

  {
    path: "/moi-gioi/quan-ly-bat-dong-san",
    component: () => import("../components/MoiGioi/QuanLyBDS/index.vue"),
    name: "MoiGioiQuanLyBDS",
    meta: { layout: "moi-gioi", roles: ["moi-gioi"] }
  },

  {
    path: "/moi-gioi/goi-tin",
    component: () => import("../components/MoiGioi/GoiTin/index.vue"),
    name: "MoiGioiGoiTin",
    meta: { layout: "moi-gioi", roles: ["moi-gioi"] }
  },

  {
    path: "/moi-gioi/ho-so-ca-nhan",
    component: () => import("../components/MoiGioi/Profile/index.vue"),
    name: "MoiGioiProfile",
    meta: { layout: "moi-gioi", roles: ["moi-gioi"] }
  },

  // ===== 404 =====
  {
    path: "/:pathMatch(.*)*",
    redirect: "/"
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior() {
    return { top: 0, behavior: 'smooth' }
  }
});


// ================= AUTH GUARD (FIX CHUẨN) =================
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("auth_token");
  const userType = localStorage.getItem("user_type");

  // ===== 1. ROUTE YÊU CẦU ROLE CỤ THỂ =====
  if (to.meta.roles && Array.isArray(to.meta.roles)) {
    // ❌ Chưa login
    if (!token || !userType) {
      return next(getLoginPathByUserType(to.meta.roles[0]));
    }
    // ❌ Sai quyền truy cập
    if (!to.meta.roles.includes(userType)) {
      return next(getHomePath(userType));
    }
  }

  // ===== 2. TRANG LOGIN / REGISTER (GUEST ONLY) =====
  if (to.meta.guest) {
    if (token && userType) {
      // Đã login thì không cho vào trang guest → redirect về home
      return next(getHomePath(userType));
    }
    return next(); // Chưa login → cho phép vào trang login/register
  }

  // ===== 3. PUBLIC ROUTES =====
  if (to.meta.public) {
    return next();
  }

  // ===== 4. DEFAULT =====
  next();
});


// ================= HELPER FUNCTIONS =================

/**
 * Trả về path login phù hợp dựa vào userType hoặc path đích
 */
function getLoginPathByUserType(expectedType) {
  if (expectedType === 'admin') return '/admin/dang-nhap';
  if (expectedType === 'moi-gioi') return '/moi-gioi/dang-nhap';
  return '/khach-hang/dang-nhap';
}

/**
 * Trả về trang chủ phù hợp theo role
 */
function getHomePath(role) {
  const map = {
    admin: "/admin/dashboard",
    "khach-hang": "/khach-hang/trang-chu",
    "moi-gioi": "/moi-gioi/trang-chu",
  };
  return map[role] || "/";
}

/**
 * Optional: Clear localStorage khi logout hoặc token invalid
 */
export function clearAuth() {
  localStorage.removeItem("auth_token");
  localStorage.removeItem("user_type");
  localStorage.removeItem("user_info");
}

export default router;