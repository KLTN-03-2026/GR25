<template>
  <div class="package-manager p-4">
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm custom-header-card">
          <div
            class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3 gap-3"
          >
            <div class="p-2">
              <h4 class="mb-0 fw-bold text-primary">
                <i class="bi bi-box-seam-fill me-2"></i>Quản Lý Gói Tin
              </h4>
              <small class="text-muted"
                >Quản lý danh sách các gói dịch vụ và bảng giá cho môi
                giới.</small
              >
            </div>
            <button
              class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center"
              data-bs-toggle="modal"
              data-bs-target="#createGTModal"
            >
              <i class="bi bi-plus-circle me-2"></i> Tạo Gói Mới
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4 g-3">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div
            class="card-body d-flex justify-content-between align-items-center"
          >
            <div>
              <small class="text-muted fw-bold">TỔNG SỐ GÓI TIN</small>
              <h3 class="fw-bold text-primary mb-0">
                {{ danhSachGoiTin.length }}
              </h3>
            </div>
            <i class="bi bi-boxes fs-1 text-primary opacity-25"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white">
          <div
            class="card-body d-flex justify-content-between align-items-center"
          >
            <div>
              <small class="opacity-75 fw-bold">ĐANG CHẠY</small>
              <h3 class="fw-bold mb-0">
                {{
                  danhSachGoiTin.filter((x) => x.trang_thai === "active").length
                }}
              </h3>
            </div>
            <i class="bi bi-play-circle-fill fs-1 opacity-25"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
          <div
            class="card-body d-flex justify-content-between align-items-center"
          >
            <div>
              <small class="text-danger fw-bold">ĐÃ NGƯNG</small>
              <h3 class="fw-bold text-danger mb-0">
                {{
                  danhSachGoiTin.filter((x) => x.trang_thai === "inactive")
                    .length
                }}
              </h3>
            </div>
            <i class="bi bi-pause-circle-fill fs-1 text-danger opacity-50"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body p-0">
        <div class="table-responsive custom-scrollbar">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Thông tin gói</th>
                <th class="text-end">Giá tiền</th>
                <th class="text-center">Số lượng tin</th>
                <th class="text-center">Thời hạn</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-end pe-4">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="danhSachGoiTin.length === 0">
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                  Chưa có gói tin nào
                </td>
              </tr>

              <tr
                v-for="(value, index) in paginatedList"
                :key="index"
                class="transition-all"
              >
                <td class="ps-4 py-3">
                  <div class="d-flex align-items-center">
                    <div class="package-icon me-3 shadow-sm">
                      <i class="bi bi-gem text-white"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-dark">{{ value.ten_goi }}</div>
                      <span
                        class="badge bg-light text-secondary border px-2 py-1 mt-1 small"
                      >
                        ID: #PK{{ String(value.id).padStart(3, "0") }}
                      </span>
                    </div>
                  </div>
                </td>
                <td class="text-end fw-bold text-primary fs-5">
                  {{ formatCurrency(value.gia) }}
                </td>
                <td class="text-center">
                  <span
                    class="badge bg-light text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill"
                  >
                    <i class="bi bi-chat-dots me-1"></i>
                    {{ value.so_luong_tin }} tin
                  </span>
                </td>
                <td class="text-center fw-medium text-muted">
                  <i class="bi bi-calendar3 me-1"></i> {{ value.so_ngay }} ngày
                </td>
                <td class="text-center">
                  <!-- ✅ Fix: So sánh string để đảo trạng thái -->
                  <span
                    @click="changeStatus(value, value.trang_thai !== 'active')"
                    :class="
                      value.trang_thai === 'active'
                        ? 'badge-active'
                        : 'badge-inactive'
                    "
                    class="badge px-3 py-2 rounded-pill small fw-bold border-0 cursor-pointer"
                  >
                    {{
                      value.trang_thai === "active"
                        ? "Đang hoạt động"
                        : "Đã khóa"
                    }}
                  </span>
                </td>
                <td class="text-end pe-4 text-nowrap">
                  <button
                    @click="Object.assign(goi_tin_update, value)"
                    class="btn btn-icon btn-light-primary me-2"
                    data-bs-toggle="modal"
                    data-bs-target="#updateModal"
                    title="Cập nhật"
                  >
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button
                    @click="id_can_xoa = value.id"
                    class="btn btn-icon btn-light-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteModal"
                    title="Xóa"
                  >
                    <i class="bi bi-trash3"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Pagination Footer -->
      <div class="card-footer bg-white border-0 py-3 px-4">
        <div
          class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3"
        >
          <!-- Info -->
          <small class="text-muted fw-medium">
            {{ paginationInfo }}
          </small>

          <!-- Pagination Buttons -->
          <div class="d-flex align-items-center gap-1" v-if="totalPages > 1">
            <!-- Previous -->
            <button
              @click="prevPage"
              :disabled="currentPage === 1"
              class="btn btn-sm btn-light border"
              :class="{ disabled: currentPage === 1 }"
            >
              &laquo;
            </button>

            <!-- Page Numbers -->
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="goToPage(page)"
              class="btn btn-sm btn-primary"
              :class="
                page === currentPage
                  ? 'btn-primary'
                  : 'btn-light border text-muted'
              "
            >
              {{ page }}
            </button>

            <!-- Next -->
            <button
              @click="nextPage"
              :disabled="currentPage === totalPages"
              class="btn btn-sm btn-light border"
              :class="{ disabled: currentPage === totalPages }"
            >
              &raquo;
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="createGTModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold text-primary mb-0">
              <i class="bi bi-plus-circle-fill me-2"></i>Thêm Gói Tin Mới
            </h5>
            <button
              type="button"
              class="btn-close shadow-none"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body px-4 py-4">
            <div class="mb-3">
              <label
                class="form-label small fw-bold text-muted text-uppercase tracking-widest"
                >Tên gói tin <span class="text-danger">*</span></label
              >
              <input
                v-model="goi_tin_create.ten_goi"
                type="text"
                class="form-control custom-input fw-bold"
                placeholder="VD: Gói VIP 1"
              />
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label
                  class="form-label small fw-bold text-muted text-uppercase tracking-widest"
                  >Giá tiền (VNĐ)</label
                >
                <input
                  v-model="goi_tin_create.gia"
                  type="number"
                  class="form-control custom-input fw-bold"
                  placeholder="0"
                />
              </div>
              <div class="col-md-6">
                <label
                  class="form-label small fw-bold text-muted text-uppercase tracking-widest"
                  >Số lượng tin</label
                >
                <input
                  v-model="goi_tin_create.so_luong_tin"
                  type="number"
                  class="form-control custom-input fw-bold"
                  placeholder="0"
                />
              </div>
              <div class="col-12">
                <label
                  class="form-label small fw-bold text-muted text-uppercase tracking-widest"
                  >Thời hạn (Ngày)</label
                >
                <input
                  v-model="goi_tin_create.so_ngay"
                  type="number"
                  class="form-control custom-input fw-bold"
                  placeholder="30"
                />
              </div>
            </div>
          </div>
          <div class="modal-footer border-0 pb-4 px-4 pt-0">
            <button
              class="btn btn-light rounded-pill px-4"
              data-bs-dismiss="modal"
            >
              Hủy bỏ
            </button>
            <button
              @click="themMoiGoiTin()"
              class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"
              data-bs-dismiss="modal"
            >
              Lưu Gói Tin
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold text-primary mb-0">
              <i class="bi bi-pencil-square me-2"></i>Cập Nhật Gói Tin
            </h5>
            <button
              type="button"
              class="btn-close shadow-none"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body px-4 py-4">
            <div class="mb-3">
              <label
                class="form-label small fw-bold text-muted text-uppercase tracking-widest"
                >Tên gói tin</label
              >
              <input
                v-model="goi_tin_update.ten_goi"
                type="text"
                class="form-control custom-input fw-bold"
              />
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label
                  class="form-label small fw-bold text-muted text-uppercase tracking-widest"
                  >Giá tiền (VNĐ)</label
                >
                <input
                  v-model="goi_tin_update.gia"
                  type="number"
                  class="form-control custom-input fw-bold"
                />
              </div>
              <div class="col-md-6">
                <label
                  class="form-label small fw-bold text-muted text-uppercase tracking-widest"
                  >Số lượng tin</label
                >
                <input
                  v-model="goi_tin_update.so_luong_tin"
                  type="number"
                  class="form-control custom-input fw-bold"
                />
              </div>
              <div class="col-12">
                <label
                  class="form-label small fw-bold text-muted text-uppercase tracking-widest"
                  >Thời hạn (Ngày)</label
                >
                <input
                  v-model="goi_tin_update.so_ngay"
                  type="number"
                  class="form-control custom-input fw-bold"
                />
              </div>
            </div>
          </div>
          <div class="modal-footer border-0 pb-4 px-4 pt-0">
            <button
              class="btn btn-light rounded-pill px-4"
              data-bs-dismiss="modal"
            >
              Hủy
            </button>
            <button
              @click="capNhatGoiTin()"
              class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"
              data-bs-dismiss="modal"
            >
              Lưu thay đổi
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4">
          <div class="mb-3">
            <div
              class="avatar-sm bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center mx-auto"
              style="width: 60px; height: 60px"
            >
              <i class="bi bi-trash3-fill fs-1"></i>
            </div>
          </div>
          <h5 class="fw-bold mb-3">Xóa gói tin?</h5>
          <p class="text-muted small mb-4">
            Hành động này không thể hoàn tác. Bạn có chắc chắn muốn xóa gói tin
            này khỏi hệ thống?
          </p>
          <div class="d-flex justify-content-center gap-2">
            <button
              class="btn btn-light rounded-pill px-4"
              data-bs-dismiss="modal"
            >
              Hủy
            </button>
            <button
              @click="xoaGoiTin()"
              class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm"
              data-bs-dismiss="modal"
            >
              Xóa ngay
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "../../../../axios/config";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({ position: "top-right" });

export default {
  data() {
    return {
      danhSachGoiTin: [],
      id_can_xoa: "",
      goi_tin_create: {},
      goi_tin_update: {},
      currentPage: 1,
      itemsPerPage: 5,
      API_BASE: "http://127.0.0.1:8000/api/admin/goi-tin",
    };
  },
  computed: {
    // ✅ Danh sách đã phân trang
    paginatedList() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.danhSachGoiTin.slice(start, end); // ✅ Dùng danhSachGoiTin
    },

    // ✅ Tổng số trang
    totalPages() {
      return Math.ceil(this.danhSachGoiTin.length / this.itemsPerPage) || 1;
    },

    // ✅ Các số trang hiển thị (1, 2, 3, 4, 5)
    visiblePages() {
      const pages = [];
      const maxVisible = 5;
      let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
      let end = Math.min(this.totalPages, start + maxVisible - 1);

      if (end - start < maxVisible - 1) {
        start = Math.max(1, end - maxVisible + 1);
      }

      for (let i = start; i <= end; i++) pages.push(i);
      return pages;
    },

    // ✅ Info phân trang
    paginationInfo() {
      const total = this.danhSachGoiTin.length;
      if (total === 0) return "Hiển thị 0 gói tin";
      const start = (this.currentPage - 1) * this.itemsPerPage + 1;
      const end = Math.min(this.currentPage * this.itemsPerPage, total);
      return `Hiển thị ${start} - ${end} của ${total} gói tin`;
    },
  },

  mounted() {
    this.loadGoiTin();
  },

  methods: {
    // Chuyển trang
    goToPage(page) {
    if (page < 1 || page > this.totalPages || page === this.currentPage) return;
    this.currentPage = page;
    
    // ✅ Scroll lên đầu table (dùng class có thật trong component này)
    this.$nextTick(() => {
      document.querySelector('.table-responsive')?.scrollIntoView({ behavior: 'smooth' });
    });
  },

    // Trang trước / sau
    prevPage() { this.goToPage(this.currentPage - 1); },
  nextPage() { this.goToPage(this.currentPage + 1); },
  
  // ✅ Reset page về 1 (dùng khi có search/filter sau này)
  resetPagination() { this.currentPage = 1; },
    loadGoiTin(page = 1) {
      this.isLoading = true;

      axios
        .get("http://127.0.0.1:8000/api/admin/goi-tin/data")
        .then((res) => {
          const data = res.data.data;
          this.danhSachGoiTin = data.data || data;
        })
        .catch(() => {
          this.toaster.error("Lỗi load dữ liệu");
        });
    },

    formatCurrency(value) {
      if (!value) return "0 đ";
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(value);
    },

    // ✅ THÊM MỚI GÓI TIN
    themMoiGoiTin() {
      axios
        .post(
          "http://127.0.0.1:8000/api/admin/goi-tin/create",
          this.goi_tin_create
        )
        .then((response) => {
          if (
            response.data.success ||
            response.status === 200 ||
            response.status === 201
          ) {
            const message = response.data.message || "Thêm gói tin thành công!";
            this.$toast.success(
              `<span class="text-nowrap"><b>${message}</b></span>`
            );
            this.loadGoiTin();
            this.goi_tin_create = {};
          } else {
            this.$toast.error(
              `<span class="text-nowrap">Thất Bại <b>${
                response.data.message || "Có lỗi xảy ra"
              }</b></span>`
            );
          }
        })
        .catch((error) => {
          console.error("Lỗi khi thêm mới Gói Tin:", error);
          if (
            error.response &&
            error.response.data &&
            error.response.data.errors
          ) {
            const errors = error.response.data.errors;
            const items = Object.values(errors)
              .flat()
              .map((msg) => `<li>${msg}</li>`)
              .join("");
            const messages = `<span class="text-nowrap">Vui lòng kiểm tra lại:<b>${items}</b></span>`;
            this.$toast.error(messages);
          } else {
            this.$toast.error("Đã xảy ra lỗi khi thêm mới Gói Tin.");
          }
        });
    },

    // ✅ CẬP NHẬT GÓI TIN
    capNhatGoiTin() {
      axios
        .put(
          "http://127.0.0.1:8000/api/admin/goi-tin/update",
          this.goi_tin_update
        )
        .then((response) => {
          if (
            response.data.success ||
            response.status === 200 ||
            response.status === 201
          ) {
            const message =
              response.data.message || "Cập nhật gói tin thành công!";

            this.$toast.success(
              `<span class="text-nowrap"><b>${message}</b></span>`
            );
            this.loadGoiTin();
          } else {
            this.$toast.error(
              `<span class="text-nowrap">Thất Bại <b>${
                response.data.message || "Có lỗi xảy ra"
              }</b></span>`
            );
          }
        })
        .catch((error) => {
          console.error("Lỗi khi cập nhật Gói Tin:", error);
          if (
            error.response &&
            error.response.data &&
            error.response.data.errors
          ) {
            const errors = error.response.data.errors;
            const items = Object.values(errors)
              .flat()
              .map((msg) => `<li>${msg}</li>`)
              .join("");
            const messages = `<span class="text-nowrap">Vui lòng kiểm tra lại:<b>${items}</b></span>`;
            this.$toast.error(messages);
          } else {
            this.$toast.error("Đã xảy ra lỗi khi cập nhật Gói Tin");
          }
        });
    },

    // ✅ XÓA GÓI TIN
    xoaGoiTin() {
      axios
        .delete(
          `http://127.0.0.1:8000/api/admin/goi-tin/delete/${this.id_can_xoa}`
        )
        .then((response) => {
          if (
            response.data.success ||
            response.status === 200 ||
            response.status === 201
          ) {
            const message = response.data.message || "Xóa gói tin thành công!";
            this.$toast.success(
              `<span class="text-nowrap"><b>${message}</b></span>`
            );
            this.loadGoiTin();
          } else {
            this.$toast.error(
              `<span class="text-nowrap">Thất Bại <b>${
                response.data.message || "Có lỗi xảy ra"
              }</b></span>`
            );
          }
        })
        .catch((error) => {
          console.error("Lỗi khi xóa Gói Tin:", error);
          this.$toast.error("Đã xảy ra lỗi khi xóa Gói Tin.");
        });
    },

    //  Lấy token
    getToken() {
      return (
        localStorage.getItem("token") || localStorage.getItem("auth_token")
      );
    },

    async toggleStatus(item) {
      // Đảo trạng thái: 'active' ↔ 'inactive'
      const newStatus = item.trang_thai === "active" ? "inactive" : "active";

      try {
        const token = this.getToken();
        const res = await axios.post(
          `${this.API_BASE}/change-status`,
          {
            id: item.id,
            trang_thai: newStatus, // ✅ Gửi 'active' hoặc 'inactive'
          },
          { headers: { Authorization: `Bearer ${token}` } }
        );

        if (res.data?.status) {
          // ✅ Cập nhật UI ngay
          item.trang_thai = newStatus;
          alert("Cập nhật thành công!");
        }
      } catch (err) {
        alert(err.response?.data?.message || "Lỗi!");
      }
    },

    // THAY ĐỔI TRẠNG THÁI GÓI TIN
    async changeStatus(item, newStatus) {
      try {
        const token = this.getToken();
        const res = await axios.post(
          `${this.API_BASE}/change-status`,
          {
            id: item.id,
            trang_thai: newStatus ? "active" : "inactive", // ✅ Gửi 'active' hoặc 'inactive'
          },
          { headers: { Authorization: `Bearer ${token}` } }
        );

        if (res.data?.status) {
          item.trang_thai = newStatus ? "active" : "inactive";
          alert("Cập nhật thành công!");
        }
      } catch (err) {
        alert(err.response?.data?.message || "Lỗi!");
      }
    },
  },
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css");

.package-manager {
  font-family: "Inter", sans-serif;
  background-color: #f8f9fa;
  min-height: 100vh;
}

/* Card & Header */
.card {
  border-radius: 16px;
}
.custom-header-card {
  background: white;
}

/* Table Design */
.table thead th {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  font-weight: 700;
  color: #6c757d;
  background-color: #fcfcfc;
  padding: 15px;
  border: none;
}
.table tbody td {
  padding: 18px 15px;
  border-bottom: 1px solid #f1f1f1;
}

/* Package Icon Overlay */
.package-icon {
  width: 42px;
  height: 42px;
  background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
}

/* Status Badges */
.badge-active {
  background-color: #e6fcf5;
  color: #087f5b;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}
.badge-inactive {
  background-color: #fff5f5;
  color: #c92a2a;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

/* Buttons & Inputs */
.btn-icon {
  width: 36px;
  height: 36px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  transition: all 0.2s;
}
.btn-light-primary {
  background: #e7f1ff;
  color: #0d6efd;
  border: none;
}
.btn-light-danger {
  background: #ffe5e5;
  color: #dc3545;
  border: none;
}
.btn-light-primary:hover {
  background: #0d6efd;
  color: white;
}
.btn-light-danger:hover {
  background: #dc3545;
  color: white;
}

.custom-input {
  border-radius: 12px;
  padding: 12px 15px;
  border: 1px solid #dee2e6;
  background-color: #fcfcfc;
  transition: all 0.2s;
}
.custom-input:focus {
  border-color: #0d6efd;
  background-color: white;
  box-shadow: none;
}

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #dee2e6;
  border-radius: 10px;
}
</style>