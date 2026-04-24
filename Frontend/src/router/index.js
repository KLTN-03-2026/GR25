import { createRouter, createWebHistory } from "vue-router";
import authGuard from "./authGuard";

const routes = [

  // ===== ADMIN =====
  {
    path: "/admin/dang-nhap",
    component: () => import("../components/Admin/DangNhap/index.vue"),
    name: "AdminDangNhap",
    meta: { layout: "blank", guest: true }
  },

  {
    path: "/admin",
    redirect: "/admin/dashboard" // ✅ FIX thêm
  },

  {
    path: "/admin/dashboard",
    component: () => import("../components/Admin/Dashboard/index.vue"),
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/moi-gioi",
    component: () => import("../components/Admin/QuanLyNguoiDung/MoiGioi/index.vue"),
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/khach-hang",
    component: () => import("../components/Admin/QuanLyNguoiDung/KhachHang/index.vue"),
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/bat-dong-san",
    component: () => import("../components/Admin/QuanLyBDS/index.vue"),
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/goi-tin",
    component: () => import("../components/Admin/GoiTin/DanhSachGoi/index.vue"),
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/lich-su-mua-goi",
    component: () => import("../components/Admin/GoiTin/LichSuMuaGoi/index.vue"),
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/loai-bat-dong-san",
    component: () => import("../components/Admin/LoaiBatDongSan/index.vue"),
    meta: { layout: "admin", roles: ["admin"] }
  },

  {
    path: "/admin/phan-quyen",
    component: () => import("../components/Admin/PhanQuyen/index.vue"),
    meta: { layout: "admin", roles: ["admin"] }
  },
  {
    path: "/admin/ho-so-ca-nhan",
    component: () => import("../components/Admin/Profile/index.vue"),
    meta: { layout: "admin", roles: ["admin"] }
  },

  // ===== KHÁCH HÀNG =====
  {
    path: "/",
    component: () => import("../components/KhachHang/TrangChu/index.vue"),
    name: "KhachHangTrangChu",
    meta: { layout: "khach-hang" }
  },

  {
    path: "/khach-hang/dang-nhap",
    component: () => import("../components/KhachHang/DangNhap/index.vue"),
    name: "KhachHangDangNhap",
    meta: { layout: "auth" }
  },

  {
    path: "/khach-hang/dang-ky",
    component: () => import("../components/KhachHang/DangKy/index.vue"),
    name: "KhachHangDangKy",
    meta: { layout: "auth" }
  },

  {
    path: "/khach-hang/trang-chu",
    component: () => import("../components/KhachHang/TrangChu/index.vue"),
    name: "KhachHangTrangChuFull",
    meta: { layout: "khach-hang", role: ["khach-hang", "moi-gioi"] }
  },

  {
    path: "/khach-hang/danh-sach-bat-dong-san",
    component: () => import("../components/KhachHang/DanhSachBatDongSan/index.vue"),
    name: "KhachHangDanhSachBatDongSan",
    meta: { layout: "khach-hang" }
  },

  {
    path: "/khach-hang/chi-tiet-bat-dong-san/:id",
    component: () => import("../components/KhachHang/ChiTietBatDongSan/index.vue"),
    name: "KhachHangChiTietBatDongSan",
    meta: { layout: "khach-hang" }
  },
  {
    path: "/khach-hang/profile",
    component: () => import("../components/KhachHang/Profile/index.vue"),
    name: "KhachHangProfile",
    meta: { layout: "khach-hang" }
  },


  // Môi Giới
  {
    path: "/moi-gioi/dang-nhap",
    component: () => import("../components/MoiGioi/DangNhap/index.vue"),
    name: "MoiGioiDangNhap",
    meta: { layout: "blank", guest: true }
  },

  {
    path: "/moi-gioi/trang-chu",
    component: () => import("../components/MoiGioi/TrangChu/index.vue"),
    name: "MoiGioiTrangChu",
    meta: { layout: "moi-gioi" }
  },

  {
    path: "/moi-gioi/dang-tin",
    component: () => import("../components/MoiGioi/DangTin/index.vue"),
    name: "MoiGioiDangTin",
    meta: { layout: "moi-gioi" }
  },

  {
    path: "/moi-gioi/quan-ly-khach-hang",
    component: () => import("../components/MoiGioi/KhachHang/index.vue"),
    name: "MoiGioiQuanLyKhachHang",
    meta: { layout: "moi-gioi" }
  },

  {
    path: "/moi-gioi/quan-ly-bat-dong-san",
    component: () => import("../components/MoiGioi/QuanLyBDS/index.vue"),
    name: "MoiGioiQuanLyBDS",
    meta: { layout: "moi-gioi" }
  },

  {
    path: "/moi-gioi/goi-tin",
    component: () => import("../components/MoiGioi/GoiTin/index.vue"),
    name: "MoiGioiGoiTin",
    meta: { layout: "moi-gioi" }
  },

  {
    path: "/moi-gioi/ho-so-ca-nhan",
    component: () => import("../components/MoiGioi/Profile/index.vue"),
    name: "MoiGioiProfile",
    meta: { layout: "moi-gioi" }
  },

  // ===== 404 =====
  {
    path: "/:pathMatch(.*)*",
    redirect: "/"
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});


// ================= AUTH GUARD (FIX CHUẨN) =================
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("auth_token");
  const userType = localStorage.getItem("user_type");

  // ===== 1. ROUTE CẦN LOGIN (ƯU TIÊN CHECK) =====
  if (to.meta.roles) {
    // ❌ chưa login
    if (!token || !userType) {
      return next(getLoginPath(to.path));
    }

    // ❌ sai quyền
    if (!to.meta.roles.includes(userType)) {
      return next(getHomePath(userType));
    }
  }

  // ===== 2. TRANG LOGIN =====
  if (to.meta.guest) {
    if (token && userType) {
      return next(getHomePath(userType));
    }
    return next();
  }

  // ===== 3. PUBLIC =====
  if (["/", "/khach-hang/trang-chu"].includes(to.path)) {
    return next();
  }

  next();
});


// ================= HELPER =================
function getLoginPath(path) {
  if (path.startsWith("/admin")) return "/admin/dang-nhap";
  return "/khach-hang/dang-nhap";
}

function getHomePath(role) {
  const map = {
    admin: "/admin/dashboard",
    "khach-hang": "/khach-hang/trang-chu",
    "moi-gioi": "/moi-gioi/trang-chu",
  };
  return map[role] || "/";
}

export default router;