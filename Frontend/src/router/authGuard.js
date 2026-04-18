import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ position: "top-right" });

export default function authGuard(to, from, next) {
  const token = localStorage.getItem("auth_token");
  const userType = localStorage.getItem("user_type"); 
  // admin | moi-gioi | khach-hang

  // ===== LOGIN PAGE =====
  if (to.meta.guest) {
    if (token && userType) {
      return next(getHomePath(userType));
    }
    return next();
  }

  // ===== PUBLIC =====
  if (["/", "/khach-hang/trang-chu"].includes(to.path)) {
    return next();
  }

  // ===== CHƯA LOGIN =====
  if (!token || !userType) {
    return next(getLoginPath(to.path));
  }

  // ===== CHECK ROLE =====
  if (to.meta.roles && !to.meta.roles.includes(userType)) {
    toaster.error("Bạn không có quyền truy cập");
    return next(getHomePath(userType));
  }

  next();
}


// ===== HELPER =====

function getLoginPath(path) {
  if (path.startsWith("/admin")) return "/admin/dang-nhap";
  if (path.startsWith("/moi-gioi")) return "/moi-gioi/dang-nhap";
  return "/khach-hang/dang-nhap";
}

function getHomePath(role) {
  const map = {
    admin: "/admin/dashboard",
    "moi-gioi": "/moi-gioi/trang-chu",
    "khach-hang": "/khach-hang/trang-chu",
  };
  return map[role] || "/";
}