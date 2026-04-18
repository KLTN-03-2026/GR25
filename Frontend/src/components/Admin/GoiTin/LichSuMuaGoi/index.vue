<template>
  <div class="package-history p-4">
    <!-- Header -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm custom-header-card">
          <div
            class="card-body d-flex justify-content-between align-items-center py-3"
          >
            <div class="p-2">
              <h4 class="mb-0 fw-bold text-primary">
                <i class="bi bi-clock-history me-2"></i>Lịch Sử Mua Gói
              </h4>
              <small class="text-muted"
                >Tổng quan lịch sử mua gói tin của môi giới</small
              >
            </div>
            <div class="d-flex align-items-center justify-content-center gap-2">
              <div class="input-group" style="max-width: 280px">
                <input
                  v-model="searchKeyword"
                  @input="debouncedSearch"
                  type="text"
                  class="form-control custom-input rounded-start-pill"
                  placeholder="Tìm theo tên, SĐT, email..."
                />
                <button
                  class="btn btn-outline-secondary shadow-none rounded-end-circle"
                  type="button"
                >
                  <i class="bi bi-search"></i>
                </button>
              </div>

              <!-- Export tổng báo cáo -->
              <div class="position-relative">
                <button
                  @click="showExportMenu = !showExportMenu"
                  class="btn btn-success btn-lg text-nowrap px-4 shadow-sm fw-bold rounded-pill"
                >
                  <i class="bi bi-download me-1"></i> Export
                  <i
                    class="bi bi-chevron-down ms-1"
                    style="font-size: 12px"
                  ></i>
                </button>
                <div
                  v-if="showExportMenu"
                  class="dropdown-menu show position-absolute end-0 mt-2 shadow-sm border-0 rounded-3 p-2 z-3"
                  style="min-width: 200px"
                >
                  <a
                    href="#"
                    @click.prevent="exportData('csv')"
                    class="dropdown-item rounded py-2 d-flex align-items-center"
                  >
                    <i class="bi bi-filetype-csv text-success fs-5 me-2"></i>
                    Xuất file CSV
                  </a>
                  <a
                    href="#"
                    @click.prevent="exportData('excel')"
                    class="dropdown-item rounded py-2 d-flex align-items-center"
                  >
                    <i
                      class="bi bi-file-earmark-excel text-primary fs-5 me-2"
                    ></i>
                    Xuất file Excel
                  </a>
                  <a
                    href="#"
                    @click.prevent="exportData('pdf')"
                    class="dropdown-item rounded py-2 d-flex align-items-center"
                  >
                    <i class="bi bi-filetype-pdf text-danger fs-5 me-2"></i>
                    Xuất file PDF
                  </a>
                </div>
                <div
                  v-if="showExportMenu"
                  @click="showExportMenu = false"
                  class="position-fixed top-0 start-0 w-100 h-100 z-2"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary Cards (Hiển thị tổng quan từ API) -->
    <div class="row mb-4 g-3" v-if="summaryStats">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
          <div
            class="card-body d-flex justify-content-between align-items-center"
          >
            <div>
              <small class="opacity-75">TỔNG DOANH THU</small>
              <h3 class="fw-bold mb-0">
                {{ formatCurrency(summaryStats.tong_doanh_thu) }}
              </h3>
              <small class="opacity-75"
                >{{ summaryStats.tong_moi_gioi }} môi giới đã mua</small
              >
            </div>
            <i class="bi bi-currency-dollar fs-1 opacity-25"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div
            class="card-body d-flex justify-content-between align-items-center"
          >
            <div>
              <small class="text-muted fw-bold">TỔNG LẦN MUA</small>
              <h3 class="fw-bold text-primary mb-0">
                {{ summaryStats.tong_lan_mua }}
              </h3>
              <small class="text-muted"
                >{{ summaryStats.goi_dang_hoat_dong }} gói đang active</small
              >
            </div>
            <i class="bi bi-receipt fs-1 text-primary opacity-25"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
          <div
            class="card-body d-flex justify-content-between align-items-center"
          >
            <div>
              <small class="text-warning fw-bold">MÔI GIỚI MỚI</small>
              <h3 class="fw-bold text-warning mb-0">
                {{ summaryStats.moi_gioi_moi_thang }}
              </h3>
              <small class="text-muted">Trong tháng này</small>
            </div>
            <i class="bi bi-people fs-1 text-warning opacity-50"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Table: Danh sách môi giới (Grouped) -->
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive custom-scrollbar">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Môi giới</th>
                <th>Số lần mua</th>
                <th class="text-center">Gói đang active</th>
                <th class="text-center">Tổng tiền đã mua</th>
                <th class="text-center">Lần mua gần nhất</th>
                <th class="text-end pe-4">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="item in danhSachGrouped"
                :key="item.moi_gioi.id"
                class="transition-all"
              >
                <td class="ps-4">
                  <div class="d-flex align-items-center">
                    <div
                      class="avatar-sm me-3 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold"
                    >
                      {{ getInitials(item.moi_gioi.ten) }}
                    </div>
                    <div>
                      <div class="fw-bold text-dark">
                        {{ item.moi_gioi.ten }}
                      </div>
                      <small class="text-muted d-block">{{
                        item.moi_gioi.email
                      }}</small>
                      <small class="text-muted">{{
                        item.moi_gioi.so_dien_thoai
                      }}</small>
                    </div>
                  </div>
                </td>
                <td class="ps-4">
                  <span class="badge bg-light text-dark border px-3 py-2">
                    {{ item.thong_ke.tong_so_lan_mua }} lần
                  </span>
                </td>
                <td class="text-center">
                  <span
                    :class="
                      item.thong_ke.so_goi_dang_hoat_dong > 0
                        ? 'badge-active'
                        : 'badge-secondary'
                    "
                    class="badge px-3 py-2 rounded-pill small fw-bold"
                  >
                    {{ item.thong_ke.so_goi_dang_hoat_dong }} gói
                  </span>
                </td>
                <td class="text-center fw-bold text-primary">
                  {{ item.thong_ke.tong_tien_formatted }}
                </td>
                <td class="text-center text-muted small">
                  {{ item.thoi_gian.lan_mua_gan_nhat || "-" }}
                </td>
                <td class="text-end pe-4 text-nowrap">
                  <button
                    @click="viewChiTiet(item.moi_gioi.id)"
                    class="btn btn-icon btn-light-primary me-2"
                    title="Xem chi tiết lịch sử mua"
                  >
                    <i class="bi bi-eye"></i>
                  </button>
                  <button
                    @click="copyInfo(item.moi_gioi)"
                    class="btn btn-icon btn-light-secondary"
                    title="Copy thông tin"
                  >
                    <i class="bi bi-clipboard"></i>
                  </button>
                </td>
              </tr>

              <!-- Loading state -->
              <tr v-if="loading">
                <td colspan="6" class="text-center py-5">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <p class="mt-2 text-muted">Đang tải dữ liệu...</p>
                </td>
              </tr>

              <!-- Empty state -->
              <tr v-else-if="danhSachGrouped.length === 0">
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                  {{
                    searchKeyword
                      ? "Không tìm thấy kết quả"
                      : "Chưa có lịch sử mua nào"
                  }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div
        class="card-footer bg-white border-0 d-flex justify-content-between align-items-center py-3 px-4"
        v-if="pagination.total > 0"
      >
        <small class="text-muted">
          Hiển thị {{ pagination.from }}-{{ pagination.to }} của
          {{ pagination.total }} môi giới
        </small>
        <nav>
          <ul class="pagination pagination-sm mb-0">
            <li
              class="page-item"
              :class="{ disabled: pagination.current_page === 1 }"
            >
              <button
                class="page-link"
                @click="changePage(pagination.current_page - 1)"
              >
                «
              </button>
            </li>
            <li
              v-for="page in visiblePages"
              :key="page"
              class="page-item"
              :class="{ active: page === pagination.current_page }"
            >
              <button class="page-link" @click="changePage(page)">
                {{ page }}
              </button>
            </li>
            <li
              class="page-item"
              :class="{
                disabled: pagination.current_page === pagination.last_page,
              }"
            >
              <button
                class="page-link"
                @click="changePage(pagination.current_page + 1)"
              >
                »
              </button>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- Modal: Chi tiết lịch sử mua của 1 môi giới -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pt-4 px-4">
            <div>
              <h5 class="fw-bold text-primary mb-1">
                <i class="bi bi-receipt me-2"></i>Lịch sử mua của
                {{ chiTietData.moi_gioi?.ten }}
              </h5>
              <small class="text-muted">
                <i class="bi bi-phone me-1"></i
                >{{ chiTietData.moi_gioi?.so_dien_thoai }} •
                <i class="bi bi-envelope me-1"></i
                >{{ chiTietData.moi_gioi?.email }}
              </small>
            </div>
            <button
              @click="closeModal"
              type="button"
              class="btn-close shadow-none"
              aria-label="Close"
            ></button>
          </div>

          <div class="modal-body px-4 py-3">
            <!-- Stats summary trong modal -->
            <div class="row g-3 mb-4" v-if="chiTietData.thong_ke">
              <div class="col-4">
                <div class="p-3 bg-light rounded-3 text-center">
                  <small class="text-muted d-block">Tổng đơn</small>
                  <span class="fs-5 fw-bold text-primary">{{
                    chiTietData.thong_ke.tong_don
                  }}</span>
                </div>
              </div>
              <div class="col-4">
                <div class="p-3 bg-light rounded-3 text-center">
                  <small class="text-muted d-block">Đang active</small>
                  <span class="fs-5 fw-bold text-success">{{
                    chiTietData.thong_ke.don_dang_hoat_dong
                  }}</span>
                </div>
              </div>
              <div class="col-4">
                <div class="p-3 bg-light rounded-3 text-center">
                  <small class="text-muted d-block">Tổng tiền</small>
                  <span class="fs-5 fw-bold text-primary">{{
                    chiTietData.thong_ke.tong_tien
                  }}</span>
                </div>
              </div>
            </div>

            <!-- List of purchases -->
            <div v-if="chiTietData.lich_su_mua?.length > 0">
              <h6 class="fw-bold mb-3">Danh sách gói đã mua</h6>
              <div class="list-group list-group-flush">
                <div
                  v-for="don in chiTietData.lich_su_mua"
                  :key="don.id"
                  class="list-group-item border-0 px-0 py-3"
                >
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                      <div class="d-flex align-items-center gap-2 mb-1">
                        <span
                          class="badge bg-light text-dark border rounded-pill"
                        >
                          #{{ don.ma_don_hang }}
                        </span>
                        <span
                          :class="`badge-${don.trang_thai_label.class}`"
                          class="badge rounded-pill small fw-bold"
                        >
                          {{ don.trang_thai_label.text }}
                        </span>
                      </div>
                      <h6 class="fw-bold mb-1">{{ don.goi_tin.ten_goi }}</h6>
                      <small class="text-muted d-block">
                        <i class="bi bi-calendar-check me-1"></i>
                        {{ don.ngay_bat_dau }} → {{ don.ngay_ket_thuc }}
                        <span class="mx-2">•</span>
                        <i class="bi bi-ticket me-1"></i>
                        {{ don.goi_tin.so_luong_tin }} tin /
                        {{ don.goi_tin.so_ngay }} ngày
                      </small>
                    </div>
                    <div class="text-end ms-3">
                      <div class="fw-bold text-primary fs-5">
                        {{ don.so_tien_formatted }}
                      </div>
                      <small class="text-muted d-block">{{
                        don.phuong_thuc
                      }}</small>
                      <small class="text-muted d-block">{{
                        don.ngay_mua
                      }}</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div
              v-else-if="!loadingChiTiet"
              class="text-center py-4 text-muted"
            >
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Chưa có lịch sử mua nào
            </div>

            <!-- Loading chi tiết -->
            <div v-if="loadingChiTiet" class="text-center py-4">
              <div class="spinner-border text-primary" role="status"></div>
              <p class="mt-2 text-muted">Đang tải chi tiết...</p>
            </div>
          </div>

          <div class="modal-footer border-0 pb-4 px-4">
            <button
              @click="closeModal"
              class="btn btn-light rounded-pill px-4"
              type="button"
            >
              <i class="bi bi-x-lg me-1"></i>Đóng
            </button>
            <div class="dropdown dropup">
              <button
                class="btn btn-primary dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                :disabled="!moiGioiId"
              >
                <i class="bi bi-download me-1"></i> Xuất chi tiết
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a
                    class="dropdown-item"
                    href="#"
                    @click.prevent="exportChiTiet('csv')"
                  >
                    <i class="bi bi-filetype-csv text-success me-2"></i>CSV
                  </a>
                </li>
                <li>
                  <a
                    class="dropdown-item"
                    href="#"
                    @click.prevent="exportChiTiet('excel')"
                  >
                    <i class="bi bi-file-earmark-excel text-primary me-2"></i
                    >Excel
                  </a>
                </li>
                <li>
                  <a
                    class="dropdown-item"
                    href="#"
                    @click.prevent="exportChiTiet('pdf')"
                  >
                    <i class="bi bi-filetype-pdf text-danger me-2"></i>PDF
                  </a>
                </li>
              </ul>
            </div>
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
      moiGioiId: null,
      // Data chính
      danhSachGrouped: [],
      chiTietData: {
        moi_gioi: null,
        thong_ke: null,
        lich_su_mua: [],
      },

      // Pagination
      pagination: {
        current_page: 1,
        last_page: 1,
        total: 0,
        from: 0,
        to: 0,
        per_page: 10,
      },

      // Stats summary
      summaryStats: null,
      showExportMenu: false,

      // Loading states
      loading: false,
      loadingChiTiet: false,

      // Search & filter
      searchKeyword: "",
      searchTimeout: null,

      // Modal
      modalInstance: null,
      API_BASE: "http://127.0.0.1:8000/api/admin/giao-dich",
    };
  },

  computed: {
    // Generate visible page numbers for pagination
    visiblePages() {
      const pages = [];
      const maxVisible = 5;
      let start = Math.max(
        1,
        this.pagination.current_page - Math.floor(maxVisible / 2)
      );
      let end = Math.min(this.pagination.last_page, start + maxVisible - 1);

      if (end - start < maxVisible - 1) {
        start = Math.max(1, end - maxVisible + 1);
      }

      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      return pages;
    },
  },

  mounted() {
    this.loadLichSu();
    // Init Bootstrap modal
    this.$nextTick(() => {
      if (typeof bootstrap !== "undefined") {
        this.modalInstance = new bootstrap.Modal(
          document.getElementById("detailModal")
        );
      }
    });
    this.$nextTick(() => {
      this.initModal();
    });
  },

  methods: {
    getToken() {
      return (
        localStorage.getItem("token") || localStorage.getItem("auth_token")
      );
    },
    // Initialize modal with fallback
    initModal() {
      const modalEl = document.getElementById("detailModal");
      if (!modalEl) {
        console.warn("Modal element not found!");
        return;
      }

      if (typeof bootstrap !== "undefined" && bootstrap.Modal) {
        this.modalInstance = new bootstrap.Modal(modalEl, {
          backdrop: true,
          keyboard: true,
        });
      } else {
        // Fallback: dùng jQuery hoặc manual show
        this.modalInstance = {
          show: () => {
            modalEl.classList.add("show");
            modalEl.style.display = "block";
            document.body.classList.add("modal-open");
          },
          hide: () => {
            modalEl.classList.remove("show");
            modalEl.style.display = "none";
            document.body.classList.remove("modal-open");
          },
        };
      }
    },
    // Close modal
    closeModal() {
      if (this.modalInstance) {
        this.modalInstance.hide();
      } else {
        // Fallback nếu không có modalInstance
        const modalEl = document.getElementById("detailModal");
        if (modalEl) {
          modalEl.classList.remove("show");
          modalEl.style.display = "none";
          modalEl.removeAttribute("aria-modal");
          modalEl.removeAttribute("role");

          // Remove backdrop
          const backdrop = document.querySelector(".modal-backdrop");
          if (backdrop) {
            backdrop.remove();
          }

          document.body.classList.remove("modal-open");
          document.body.style.overflow = "";
          document.body.style.paddingRight = "";
        }
      }

      // Reset data sau khi đóng
      this.$nextTick(() => {
        this.chiTietData = { moi_gioi: null, thong_ke: null, lich_su_mua: [] };
      });
    },
    // Load danh sách grouped (API: lichSuMua)
    async loadLichSu(page = 1) {
      this.loading = true;
      try {
        const params = {
          page,
          per_page: this.pagination.per_page,
          ...(this.searchKeyword && { search: this.searchKeyword }),
        };
        const res = await axios.get("/admin/goi-tin/lich-su-mua", { params });

        if (res.data.status) {
          this.danhSachGrouped = res.data.data.data;
          this.pagination = {
            current_page: res.data.data.current_page,
            last_page: res.data.data.last_page,
            total: res.data.data.total,
            from: res.data.data.from,
            to: res.data.data.to,
            per_page: res.data.data.per_page,
          };

          // Tính summary stats từ data hiện có (hoặc gọi API riêng nếu cần)
          this.calculateSummaryStats();
        }
      } catch (error) {
        console.error("Lỗi load lịch sử:", error);
        toaster.error("Không thể tải danh sách lịch sử");
      } finally {
        this.loading = false;
      }
    },

    // Load chi tiết 1 môi giới (API: chiTietLichSuMua/{id})
    // Load chi tiết 1 môi giới - có debug đầy đủ
    async viewChiTiet(moiGioiId) {
      this.moiGioiId = moiGioiId;
      this.loadingChiTiet = true;
      this.chiTietData = { moi_gioi: null, thong_ke: null, lich_su_mua: [] };
      try {
        // Kiểm tra URL API - sửa lại cho đúng route của bạn
        const apiUrl = `/admin/goi-tin/lich-su-mua/${moiGioiId}/chi-tiet`;
        const res = await axios.get(apiUrl);
        if (res.data.status) {
          this.chiTietData = res.data.data;
          // Show modal
          if (this.modalInstance) {
            this.modalInstance.show();
            // Debug: kiểm tra modal có hiển thị không
            setTimeout(() => {
              const modal = document.getElementById("detailModal");
            }, 100);
          } else {
            // Fallback show modal thủ công
            const modalEl = document.getElementById("detailModal");
            if (modalEl) {
              modalEl.classList.add("show");
              modalEl.style.display = "block";
              document.body.classList.add("modal-open");
            }
          }
        } else {
          toaster.error(res.data.message || "Không có dữ liệu");
        }
      } catch (error) {
        // Hiển thị lỗi chi tiết cho dev
        if (error.response) {
          console.error("Status:", error.response.status);
          console.error("Data:", error.response.data);
          toaster.error(
            `Lỗi ${error.response.status}: ${error.response.data.message || ""}`
          );
        } else if (error.request) {
          console.error("No response received:", error.request);
          toaster.error("Không thể kết nối đến server");
        } else {
          toaster.error("Lỗi: " + error.message);
        }
      } finally {
        this.loadingChiTiet = false;
      }
    },
    // Debounced search (tránh gọi API liên tục khi gõ)
    debouncedSearch() {
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        this.pagination.current_page = 1;
        this.loadLichSu(1);
      }, 300);
    },

    // Change page
    changePage(page) {
      if (
        page < 1 ||
        page > this.pagination.last_page ||
        page === this.pagination.current_page
      )
        return;
      this.pagination.current_page = page;
      this.loadLichSu(page);
      // Scroll to top of table
      document
        .querySelector(".package-history")
        ?.scrollIntoView({ behavior: "smooth" });
    },

    // 🔹 Calculate summary stats from current page data
    calculateSummaryStats() {
      if (!this.danhSachGrouped?.length) {
        this.summaryStats = null;
        return;
      }

      const stats = this.danhSachGrouped.reduce(
        (acc, item) => {
          acc.tong_doanh_thu += item.thong_ke.tong_tien_da_mua;
          acc.tong_lan_mua += item.thong_ke.tong_so_lan_mua;
          acc.goi_dang_hoat_dong += item.thong_ke.so_goi_dang_hoat_dong;
          return acc;
        },
        {
          tong_doanh_thu: 0,
          tong_lan_mua: 0,
          goi_dang_hoat_dong: 0,
          tong_moi_gioi: this.danhSachGrouped.length,
          moi_gioi_moi_thang: this.danhSachGrouped.filter((i) => {
            const muaGanNhat = i.thoi_gian.lan_mua_gan_nhat;
            return (
              muaGanNhat &&
              new Date(muaGanNhat).getMonth() === new Date().getMonth()
            );
          }).length,
        }
      );

      this.summaryStats = stats;
    },

    // Helper: Get initials for avatar
    getInitials(name) {
      if (!name) return "?";
      return name
        .split(" ")
        .map((part) => part[0])
        .slice(0, 2)
        .join("")
        .toUpperCase();
    },

    // Helper: Copy info to clipboard
    copyInfo(moiGioi) {
      const text = `${moiGioi.ten}\nSĐT: ${moiGioi.so_dien_thoai}\nEmail: ${moiGioi.email}`;
      navigator.clipboard
        .writeText(text)
        .then(() => {
          toaster.success("Đã copy thông tin!");
        })
        .catch(() => {
          toaster.error("Không thể copy");
        });
    },

    // Export chi tiết (có thể gọi API export riêng)
    exportChiTiet(format) {
      if (!this.moiGioiId) {
        this.showToast("Không tìm thấy ID môi giới!", "error");
        return;
      }

      const token = localStorage.getItem("auth_token");
      if (!token) {
        this.showToast("Vui lòng đăng nhập lại!", "error");
        return;
      }

      const url = `http://127.0.0.1:8000/api/admin/moi-gioi/export/${this.moiGioiId}?format=${format}`;

      console.log("📥 Export URL:", url);
      console.log("🆔 Moi Gioi ID:", this.moiGioiId);

      window.open(url, "_blank");
      this.showToast(`Đang tải file ${format.toUpperCase()}...`, "success");
    },
    closeDetailModal() {
      this.showDetailModal = false;
      this.moiGioiId = null; // ✅ Clear ID
      this.chiTietData = {
        moi_gioi: null,
        thong_ke: null,
        lich_su_mua: [],
      };
    },

    // Export báo cáo tổng
    exportData(format) {
      const token = this.getToken();
      if (!token) {
        alert("Vui lòng đăng nhập lại");
        return;
      }

      const params = new URLSearchParams({
        format,
        search: this.search || "",
        status: this.statusFilter || "",
      });

      // URL phải khớp với route backend: /api/admin/-----/export
      const exportUrl = `${this.API_BASE}/export?${params}`;
      console.log("📤 Export URL:", exportUrl); // Debug

      window.open(exportUrl, "_blank");
      this.showExportMenu = false;
      this.showToast(`Đang tải file ${format.toUpperCase()}...`, "success");
    },

    // Format currency
    formatCurrency(value) {
      if (value === null || value === undefined) return "0 đ";
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        maximumFractionDigits: 0,
      }).format(value);
    },
  },

  // Cleanup
  beforeUnmount() {
    clearTimeout(this.searchTimeout);
    if (this.modalInstance?.hide) {
      this.modalInstance.hide();
    }
    if (this.modalInstance?.dispose) {
      this.modalInstance.dispose();
    }
  },
  watch: {
    moiGioiId(newVal) {
      console.log("🆔 moiGioiId changed to:", newVal);
    },
  },
};
</script>

<style scoped>
/* Giữ nguyên CSS bạn đã có, chỉ bổ sung thêm */
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css");

.package-history {
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

/* Avatar */
.avatar-sm {
  width: 40px;
  height: 40px;
  font-size: 0.9rem;
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
.badge-secondary {
  background-color: #e9ecef;
  color: #495057;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}
.badge-warning {
  background-color: #fff3bf;
  color: #e67700;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

/* Buttons */
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
.btn-light-secondary {
  background: #e9ecef;
  color: #6c757d;
  border: none;
}
.btn-light-primary:hover {
  background: #0d6efd;
  color: white;
  transform: translateY(-1px);
}
.btn-light-secondary:hover {
  background: #6c757d;
  color: white;
  transform: translateY(-1px);
}

/* Inputs */
.custom-input {
  border-radius: 12px;
  padding: 12px 15px;
  border: 2px solid #f1f1f1;
  background-color: #fcfcfc;
  transition: all 0.2s;
}
.custom-input:focus {
  border-color: #0d6efd;
  background-color: white;
  box-shadow: none;
}

/* Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #dee2e6;
  border-radius: 10px;
}

/* Pagination */
.pagination .page-link {
  border: none;
  color: #6c757d;
  border-radius: 8px !important;
  margin: 0 2px;
  font-weight: 500;
}
.pagination .page-item.active .page-link {
  background: #0d6efd;
  color: white;
}
.pagination .page-link:hover {
  background: #e7f1ff;
  color: #0d6efd;
}

/* Modal enhancements */
.modal-content {
  border-radius: 20px;
}
.list-group-item {
  transition: background-color 0.2s;
}
.list-group-item:hover {
  background-color: #f8f9fa;
}

/* Responsive */
@media (max-width: 768px) {
  .package-history {
    padding: 1rem !important;
  }
  .table-responsive {
    font-size: 0.875rem;
  }
  .btn-icon {
    width: 32px;
    height: 32px;
  }
}
</style>