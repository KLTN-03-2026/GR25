<template>
  <div class="dashboard-container bg-light min-vh-100">
    <div class="container-fluid px-4 py-4">
      <!-- STATS CARDS - TẦNG 1 -->
      <div class="row g-4 mb-4">
        <!-- 1. Tổng tin đăng -->
        <div class="col-md-6 col-xl-3">
          <div
            class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden"
          >
            <div class="card-body p-4">
              <div
                class="d-flex justify-content-between align-items-start mb-3"
              >
                <div>
                  <div
                    class="text-uppercase text-muted small fw-bold mb-1"
                    style="font-size: 11px; letter-spacing: 0.5px"
                  >
                    Tổng tin đăng
                  </div>
                  <h3 class="fw-bold text-primary mb-0 display-6">
                    {{ stats.totalPosted }}
                  </h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                  <i class="bi bi-house-door fs-4 text-primary"></i>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span
                  class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small"
                >
                  <i class="bi bi-arrow-up-short"></i> +2 tháng này
                </span>
              </div>
            </div>
            <div class="position-absolute top-0 end-0 m-3 opacity-10">
              <i
                class="bi bi-house-door-fill text-primary"
                style="font-size: 80px"
              ></i>
            </div>
          </div>
        </div>

        <!-- 2. Tin còn lại (ĐÃ SỬA) -->
        <div class="col-md-6 col-xl-3">
          <div
            class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden"
          >
            <div class="card-body p-4">
              <div
                class="d-flex justify-content-between align-items-start mb-3"
              >
                <div>
                  <div
                    class="text-uppercase text-muted small fw-bold mb-1"
                    style="font-size: 11px; letter-spacing: 0.5px"
                  >
                    Tin còn lại
                  </div>
                  <h3 class="fw-bold text-success mb-0 display-6">
                    {{ stats.remainingPosts }}
                  </h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-4">
                  <i class="bi bi-inbox fs-4 text-success"></i>
                </div>
              </div>
              <div class="text-muted small">Sẵn sàng đăng tin</div>
            </div>
            <div class="position-absolute top-0 end-0 m-3 opacity-10">
              <i
                class="bi bi-inbox-fill text-success"
                style="font-size: 80px"
              ></i>
            </div>
          </div>
        </div>

        <!-- 3. Giao dịch thành công -->
        <div class="col-md-6 col-xl-3">
          <div
            class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden"
          >
            <div class="card-body p-4">
              <div
                class="d-flex justify-content-between align-items-start mb-3"
              >
                <div>
                  <div
                    class="text-uppercase text-muted small fw-bold mb-1"
                    style="font-size: 11px; letter-spacing: 0.5px"
                  >
                    Giao dịch thành công
                  </div>
                  <h3 class="fw-bold text-info mb-0 display-6">
                    {{ stats.transactionCount }}
                  </h3>
                </div>
                <div class="bg-info bg-opacity-10 p-3 rounded-4">
                  <i class="bi bi-hand-thumbs-up fs-4 text-info"></i>
                </div>
              </div>
              <div class="text-muted small">Mục tiêu: <strong>15</strong></div>
            </div>
            <div class="position-absolute top-0 end-0 m-3 opacity-10">
              <i
                class="bi bi-hand-thumbs-up-fill text-info"
                style="font-size: 80px"
              ></i>
            </div>
          </div>
        </div>

        <!-- 4. Hoa hồng tháng này -->
        <div class="col-md-6 col-xl-3">
          <div
            class="card border-0 rounded-4 h-100 text-white"
            style="
              background: linear-gradient(135deg, #0a164d 0%, #1a237e 100%);
            "
          >
            <div class="card-body p-4 position-relative">
              <div
                class="d-flex justify-content-between align-items-start mb-3"
              >
                <div>
                  <div
                    class="text-uppercase small fw-bold mb-1 opacity-75"
                    style="font-size: 11px; letter-spacing: 0.5px"
                  >
                    Hoa hồng tháng này
                  </div>
                  <h3 class="fw-bold mb-0 display-6">
                    {{ Number(stats.totalPaid).toLocaleString("vi-VN") }}
                  </h3>
                </div>
                <div class="bg-white bg-opacity-25 p-3 rounded-4">
                  <i class="bi bi-cash-coin fs-4"></i>
                </div>
              </div>
              <div class="small opacity-75">VNĐ</div>
            </div>
            <div class="position-absolute top-0 end-0 m-3 opacity-10">
              <i class="bi bi-cash-coin" style="font-size: 80px"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- CHART & CUSTOMER LIST - TẦNG 2 -->
      <div class="row g-4">
        <!-- Biểu đồ hiệu suất (Bên trái) -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
              <div
                class="d-flex justify-content-between align-items-center mb-4"
              >
                <h5 class="fw-bold mb-0 text-dark">
                  Hiệu suất tin đăng theo tuần
                </h5>
                <div class="btn-group" role="group">
                  <button
                    type="button"
                    class="btn btn-sm"
                    :class="
                      chartPeriod === 'month'
                        ? 'btn-outline-secondary'
                        : 'btn-light'
                    "
                    @click="changeChartPeriod('month')"
                  >
                    Tháng qua
                  </button>
                  <button
                    type="button"
                    class="btn btn-sm"
                    :class="
                      chartPeriod === 'week' ? 'btn-primary' : 'btn-light'
                    "
                    @click="changeChartPeriod('week')"
                  >
                    Tuần này
                  </button>
                </div>
              </div>

              <!-- ✅ Dùng Vue3-ApexCharts -->
              <apexchart
                type="bar"
                height="300"
                :options="chartOptions"
                :series="chartSeries"
              >
              </apexchart>
            </div>
          </div>
        </div>

        <!-- Danh sách khách hàng liên hệ (Bên phải) -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
              <div class="d-flex align-items-center gap-2 mb-4">
                <div class="bg-warning bg-opacity-25 p-2 rounded-3">
                  <i class="bi bi-exclamation-triangle text-warning fs-5"></i>
                </div>
                <h5 class="fw-bold mb-0 text-dark">Liên hệ ngay</h5>
              </div>

              <div
                class="customer-list"
                style="max-height: 500px; overflow-y: auto"
              >
                <div v-if="loading.customers" class="text-center py-5">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </div>

                <div
                  v-else-if="customers.length === 0"
                  class="text-center py-5 text-muted"
                >
                  <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                  <p class="mb-0 small">Chưa có khách hàng liên hệ</p>
                </div>

                <div v-else class="space-y-3">
                  <div
                    v-for="(customer, index) in customers"
                    :key="index"
                    class="card border-0 bg-light bg-opacity-50 rounded-4 mb-3 hover-shadow transition-all cursor-pointer"
                  >
                    <div class="card-body p-3">
                      <div class="d-flex gap-3">
                        <img
                          :src="
                            customer.khach_hang?.avatar ||
                            'https://ui-avatars.com/api/?name=' +
                              encodeURIComponent(
                                customer.khach_hang?.ten || 'KH'
                              )
                          "
                          class="rounded-circle border border-2 border-white shadow-sm"
                          width="48"
                          height="48"
                          alt="Customer"
                        />

                        <div class="flex-grow-1">
                          <div
                            class="d-flex justify-content-between align-items-start"
                          >
                            <div>
                              <h6 class="fw-semibold mb-0 text-dark small">
                                {{ customer.khach_hang?.ten }}
                              </h6>
                              <div
                                class="text-primary small"
                                style="font-size: 11px"
                              >
                                {{
                                  customer.bat_dong_san?.tieu_de ||
                                  "Quan tâm BĐS"
                                }}
                              </div>
                            </div>
                            <button
                              class="btn btn-sm btn-light rounded-circle p-1"
                            >
                              <i class="bi bi-phone small text-primary"></i>
                            </button>
                          </div>

                          <div class="mt-2">
                            <p
                              class="text-muted small mb-0 fst-italic"
                              style="font-size: 12px"
                            >
                              "{{
                                customer.bat_dong_san?.mo_ta?.substring(
                                  0,
                                  50
                                ) || "Đang quan tâm"
                              }}..."
                            </p>
                          </div>

                          <div
                            class="mt-2 d-flex justify-content-between align-items-center"
                          >
                            <span
                              class="badge bg-light text-dark border small"
                              style="font-size: 10px"
                            >
                              <i class="bi bi-clock me-1"></i>
                              {{ formatRelativeTime(customer.last_contact) }}
                            </span>
                            <span
                              class="text-muted small"
                              style="font-size: 10px"
                            >
                              {{ customer.contact_count }} lần liên hệ
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <button
                class="btn btn-outline-primary w-100 rounded-4 mt-3 small fw-semibold"
              >
                XEM TẤT CẢ KHÁCH HÀNG
                <i class="bi bi-arrow-right ms-2"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- ALERT BANNER -->
      <div class="mt-4">
        <div class="card border-0 rounded-4" style="background-color: #fef3c7">
          <div class="card-body p-4">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h6 class="fw-bold text-dark mb-2">
                  <i class="bi bi-exclamation-circle text-warning me-2"></i>
                  Gia hạn tin đăng sắp hết hạn
                </h6>
                <p class="text-muted mb-0 small">
                  Có <strong>3 tin đăng</strong> tại dự án Heritage West Lake sẽ
                  hết hiệu lực trong 48 giờ tới. Hãy gia hạn ngay để không bỏ lỡ
                  khách hàng tiềm năng.
                </p>
              </div>
              <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button class="btn btn-dark rounded-4 px-4 small fw-semibold">
                  GIA HẠN NGAY
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import VueApexCharts from "vue3-apexcharts";

export default {
  components: {
    apexchart: VueApexCharts,
  },
  data() {
    return {
      loading: {
        stats: true,
        chart: true,
        customers: true,
      },
      stats: {
        totalPosted: 0,
        remainingPosts: 0,
        totalFavorites: 0,
        totalPaid: 0,
        transactionCount: 0,
      },
      chartPeriod: "week",
      customers: [],
      userInfo: { ten: "Minh Quân", avatar: null },
      newContactsToday: 5,
      baseURL: "http://127.0.0.1:8000/api",

      // ✅ Cấu hình ApexCharts đã fix
      chartOptions: {
        chart: {
          type: "bar",
          height: 300,
          toolbar: { show: false },
          fontFamily: "Inter, sans-serif",
        },
        plotOptions: {
          bar: {
            borderRadius: 8,
            columnWidth: "40%",
            distributed: false,
          },
        },
        colors: ["#1a237e"],
        dataLabels: { enabled: false },
        grid: {
          borderColor: "#f3f4f6",
          strokeDashArray: 4,
        },
        xaxis: {
          categories: [],
          labels: {
            style: { fontSize: "11px", colors: "#64748b" },
            rotate: -45,
          },
          axisBorder: { show: false },
          axisTicks: { show: false },
        },
        yaxis: {
          labels: {
            style: { fontSize: "11px", colors: "#64748b" },
            formatter: (val) => Math.round(val),
          },
          min: 0,
          tickAmount: 5,
          stepSize: 1,
        },
        tooltip: {
          theme: "light",
          y: {
            formatter: (val) => `${val} tin đăng`,
          },
        },
        legend: { show: false },
        states: {
          hover: { filter: { type: "darken", value: 0.85 } },
        },
      },

      chartSeries: [
        {
          name: "Số tin đăng",
          data: [],
        },
      ],
    };
  },

  mounted() {
    this.fetchUserProfile();
    this.fetchAllStats();
    this.fetchChartData();
    this.fetchCustomers();
  },

  methods: {
    fetchUserProfile() {
      const user = localStorage.getItem("moi_gioi_user");
      if (user) {
        this.userInfo = JSON.parse(user);
      }
    },

    async fetchAllStats() {
      this.loading.stats = true;
      try {
        const token = localStorage.getItem("auth_token");
        const config = {
          headers: { Authorization: `Bearer ${token}` },
        };

        const [postedRes, remainingRes, favoritesRes, paymentRes] =
          await Promise.all([
            axios.get(
              `${this.baseURL}/moi-gioi/thong-ke/tong-tin-da-dang`,
              config
            ),
            axios.get(`${this.baseURL}/moi-gioi/thong-ke/tin-con-lai`, config),
            axios.get(
              `${this.baseURL}/moi-gioi/thong-ke/tong-yeu-thich`,
              config
            ),
            axios.get(`${this.baseURL}/moi-gioi/thong-ke/tong-tien`, config),
          ]);

        this.stats.totalPosted = postedRes.data.data || 0;
        this.stats.remainingPosts = remainingRes.data.data || 0;
        this.stats.totalFavorites = favoritesRes.data.data || 0;
        this.stats.totalPaid = paymentRes.data.data || 0;
        this.stats.transactionCount =
          Math.floor(this.stats.totalPaid / 200000000) || 12;
      } catch (error) {
        console.error("Lỗi khi lấy thống kê:", error);
        this.showError("Không thể tải dữ liệu thống kê");
      } finally {
        this.loading.stats = false;
      }
    },

    async fetchChartData() {
      this.loading.chart = true;
      try {
        const token = localStorage.getItem("auth_token");
        const range = this.chartPeriod === "week" ? 7 : 30;

        const response = await axios.get(
          `${this.baseURL}/moi-gioi/thong-ke/bieu-do-bai-dang?range=${range}`,
          {
            headers: { Authorization: `Bearer ${token}` },
          }
        );

        this.updateChart(response.data.data);
      } catch (error) {
        console.error("Lỗi khi lấy dữ liệu biểu đồ:", error);
      } finally {
        this.loading.chart = false;
      }
    },

    // ✅ Method updateChart đã fix
    updateChart(chartData) {
      const labels = chartData.labels.map((date) => {
        const d = new Date(date);
        const days = ["CN", "T2", "T3", "T4", "T5", "T6", "T7"];
        const dayName = days[d.getDay()];
        const dayDate = d.getDate();
        const month = d.getMonth() + 1;
        return `${dayName} ${dayDate}/${month}`;
      });

      const colors = chartData.values.map((val, idx) => {
        const max = Math.max(...chartData.values);
        if (val === max && val > 0) return "#1a237e";
        if (idx === chartData.values.length - 1) return "#06b6d4";
        return "#e2e8f0";
      });

      this.chartOptions.xaxis.categories = labels;
      this.chartOptions.colors = colors;
      this.chartOptions.plotOptions.bar.distributed = false;

      this.chartSeries = [
        {
          name: "Số tin đăng",
          data: chartData.values,
        },
      ];
    },

    async fetchCustomers() {
      this.loading.customers = true;
      try {
        const token = localStorage.getItem("auth_token");
        const response = await axios.get(
          `${this.baseURL}/moi-gioi/thong-ke/khach-hang-lien-he?limit=10`,
          {
            headers: { Authorization: `Bearer ${token}` },
          }
        );

        this.customers = response.data.data || [];
      } catch (error) {
        console.error("Lỗi khi lấy danh sách khách hàng:", error);
        this.showError("Không thể tải danh sách khách hàng");
      } finally {
        this.loading.customers = false;
      }
    },

    changeChartPeriod(period) {
      this.chartPeriod = period;
      this.fetchChartData();
    },

    formatCurrency(value) {
      if (!value) return "0";
      return Number(value).toLocaleString("vi-VN");
    },

    formatRelativeTime(dateString) {
      if (!dateString) return "";
      const date = new Date(dateString);
      const now = new Date();
      const diffMs = now - date;
      const diffMins = Math.floor(diffMs / 60000);
      const diffHours = Math.floor(diffMs / 3600000);
      const diffDays = Math.floor(diffMs / 86400000);

      if (diffMins < 1) return "Vừa xong";
      if (diffMins < 60) return `${diffMins} phút trước`;
      if (diffHours < 24) return `${diffHours} giờ trước`;
      if (diffDays < 7) return `${diffDays} ngày trước`;
      return date.toLocaleDateString("vi-VN");
    },

    showError(message) {
      alert(message);
    },
  },
};
</script>
<style scoped>
.dashboard-container {
  font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
    sans-serif;
}
.hover-shadow:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
  transform: translateY(-2px);
  transition: all 0.2s ease;
}
.cursor-pointer {
  cursor: pointer;
}
.transition-all {
  transition: all 0.2s ease;
}

/* Custom scrollbar */
.customer-list::-webkit-scrollbar {
  width: 6px;
}
.customer-list::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}
.customer-list::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}
.customer-list::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

@media (max-width: 768px) {
  .display-6 {
    font-size: 2rem;
  }
}
/* Font và màu sắc chủ đạo */
.dashboard-container {
  font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
    sans-serif;
}

.hover-shadow:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
  transform: translateY(-2px);
  transition: all 0.2s ease;
}

.cursor-pointer {
  cursor: pointer;
}

.transition-all {
  transition: all 0.2s ease;
}

/* Custom scrollbar */
.customer-list::-webkit-scrollbar {
  width: 6px;
}

.customer-list::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

.customer-list::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.customer-list::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Chart container */
.chart-container {
  position: relative;
}

/* Card hover effects */
.card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .display-6 {
    font-size: 2rem;
  }
}
</style>