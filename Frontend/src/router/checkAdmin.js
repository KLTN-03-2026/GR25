import axios from "axios";
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ position: "top-right" });

export default function (to, from, next) {
    const token = localStorage.getItem("admin_token");

    // 1. Chặn ngay nếu không có token (chưa đăng nhập)
    if (!token) {
        toaster.error("Vui lòng đăng nhập để tiếp tục");
        return next("/admin/dang-nhap"); // Đổi lại thành /admin/dang-nhap nếu router bạn đặt như vậy
    }

    // 2. Nếu có token thì mới gọi API kiểm tra
    axios.get("http://localhost:8000/api/admin/check-token", {
        headers: {
            "Authorization": `Bearer ${token}`
        }
    })
        .then((res) => {
            // Token hợp lệ
            if (res.status === 200 || res.data.status === "success") {
                next();
            } else {
                toaster.error("Phiên đăng nhập đã hết hạn, vui lòng đăng nhập lại");
                localStorage.removeItem("admin_token"); // Xóa token rác
                next("/admin/dang-nhap");
            }
        })
        .catch((error) => {
            // 3. Bắt lỗi khi server trả về 401 (Hết hạn token) hoặc lỗi mạng
            toaster.error("Phiên đăng nhập đã hết hạn, vui lòng đăng nhập lại");
            localStorage.removeItem("admin_token"); // Xóa token hết hạn
            next("/admin/dang-nhap");
        });
}