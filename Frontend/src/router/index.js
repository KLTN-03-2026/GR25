import { createRouter, createWebHistory } from "vue-router"
import checkAdmin from "./checkAdmin"



const routes = [
  // ADMIN
  {
		path: "/admin/dang-nhap",
		component: () => import("../components/Admin/DangNhap/index.vue"),
		meta: { layout: "blank" },
	},

  {
		path: "/admin/dashboard",
		component: () => import("../components/Admin/Dashboard/index.vue"),
		meta: { middleware: checkAdmin },
	},

  {
    path : '/admin/chuc-vu',
    component: ()=>import('../components/Admin/HeThong/ChucVu/index.vue'),
    meta: { middleware: checkAdmin },
  },
  
  {
    path : '/admin/khach-hang',
    component: ()=>import('../components/Admin/QuanLyNguoiDung/KhachHang/index.vue'),
    meta: { middleware: checkAdmin },
  },
]

const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

export default router
