<template>
  <div class="container-fluid bg-light min-vh-100 py-4">
    <div class="container" style="max-width: 1140px;">
      
      <!-- HEADER -->
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="bg-primary" style="height: 160px; background: linear-gradient(135deg, #1a237e 0%, #283593 100%);"></div>
        
        <div class="card-body px-4 pb-4">
          <div class="d-flex justify-content-between align-items-end">
            <div class="d-flex align-items-end gap-4" style="margin-top: -80px;">
              
              <!-- Avatar -->
              <div class="position-relative">
                <img :src="defaultAvatar"
                  class="rounded-4 border border-4 border-white shadow-lg object-fit-cover bg-white"
                  style="width: 160px; height: 160px;">
                
                <button class="position-absolute bottom-0 end-0 mb-2 me-2 btn btn-light rounded-3 shadow-sm border">
                  <i class="bi bi-camera"></i>
                </button>
              </div>

              <!-- Info -->
              <div class="pb-2">
                <h3 class="h3 fw-bold text-dark mb-1">
                  {{ profile.ten }}
                </h3>
                
                <p class="text-muted mb-0 d-flex align-items-center flex-wrap gap-2">
                  <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                    {{ profile.mo_ta || 'Chuyên viên tư vấn cao cấp' }}
                  </span>
                  
                  <span class="text-muted opacity-50">•</span>
                  
                  <span class="small">
                    Mã số: <strong class="text-dark">MG-{{ profile.id }}</strong>
                  </span>
                </p>
              </div>
            </div>

            <!-- Button -->
            <button @click="saveProfile" :disabled="loading"
              class="btn btn-primary px-4 py-2 rounded-3 fw-semibold shadow-sm d-flex align-items-center gap-2"
              style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%); border: none;">
              <i class="bi bi-pencil-square small"></i>
              <span>{{ loading ? 'Đang lưu...' : 'Chỉnh sửa hồ sơ' }}</span>
            </button>
          </div>
        </div>
      </div>

      <div class="row g-4">
        
        <!-- LEFT COLUMN -->
        <div class="col-lg-8">
          
          <!-- THÔNG TIN CÁ NHÂN -->
          <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
              
              <div class="d-flex align-items-center gap-2 mb-4">
                <div class="p-2 rounded-3" style="background-color: rgba(26, 35, 126, 0.1);">
                  <i class="bi bi-person-badge text-primary fs-5"></i>
                </div>
                <h4 class="h5 fw-bold mb-0 text-dark">
                  Thông tin cá nhân
                </h4>
              </div>

              <div class="row g-3">
                <div class="col-md-6" v-for="(field, index) in infoFields" :key="index">
                  <label class="text-uppercase fw-bold text-muted small mb-2 d-block" style="font-size: 10px; letter-spacing: 0.5px;">
                    {{ field.label }}
                  </label>
                  
                  <div class="d-flex align-items-center gap-3 p-3 rounded-4 bg-light border border-light-subtle hover-shadow transition-all">
                    <div class="p-2 rounded-3 bg-white">
                      <i :class="field.icon" class="text-muted"></i>
                    </div>
                    
                    <span class="text-dark fw-medium">
                      {{ field.value }}
                    </span>
                  </div>
                </div>
              </div>
              
            </div>
          </div>

          <!-- BẢO MẬT -->
          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
              
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                  <div class="p-2 rounded-3" style="background-color: rgba(26, 35, 126, 0.1);">
                    <i class="bi bi-shield-check text-primary fs-5"></i>
                  </div>
                  <h4 class="h5 fw-bold mb-0 text-dark">
                    Quản lý bảo mật
                  </h4>
                </div>

                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2">
                  <i class="bi bi-check-circle me-1"></i> AN TOÀN
                </span>
              </div>

              <!-- FORM ĐỔI MẬT KHẨU -->
              <div class="row g-3">
                
                <div class="col-md-6">
                  <label class="form-label fw-bold text-muted small">
                    Mật khẩu hiện tại
                  </label>
                  <input type="password" v-model="passwordForm.old_password"
                    class="form-control rounded-4 border border-secondary-subtle py-3"
                    placeholder="••••••••">
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold text-muted small">
                    Mật khẩu mới
                  </label>
                  <input type="password" v-model="passwordForm.password"
                    class="form-control rounded-4 border border-secondary-subtle py-3"
                    placeholder="••••••••">
                </div>

                <div class="col-12">
                  <label class="form-label fw-bold text-muted small">
                    Xác nhận mật khẩu mới
                  </label>
                  <input type="password" v-model="passwordForm.re_password"
                    class="form-control rounded-4 border border-secondary-subtle py-3"
                    placeholder="••••••••">
                </div>

              </div>

              <button @click="changePassword"
                class="btn btn-primary mt-3 px-4 py-2 rounded-4 fw-semibold shadow-sm"
                style="background: linear-gradient(135deg, #1a237e 0%, #283593 100%); border: none;">
                Đổi mật khẩu
              </button>

            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-lg-4">
          
          <!-- STATS CARD -->
          <div class="card border-0 text-white rounded-4 shadow-lg mb-4 position-relative overflow-hidden"
            style="background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #1a237e 100%);">
            
            <div class="position-absolute top-0 end-0 translate-middle-y me-n4 opacity-10">
              <i class="bi bi-currency-exchange" style="font-size: 120px;"></i>
            </div>
            
            <div class="card-body p-4 position-relative">
              <p class="text-uppercase fw-bold mb-3 opacity-75 small" style="letter-spacing: 2px; font-size: 10px;">
                Tổng giá trị giao dịch
              </p>

              <div class="d-flex align-items-baseline gap-2 mb-3">
                <h4 class="display-5 fw-black mb-0">1.2T</h4>
                <span class="fs-6 fw-medium opacity-75 fst-italic">VND</span>
              </div>

              <div class="d-flex align-items-center gap-1 text-success fw-bold small">
                <i class="bi bi-arrow-up-right"></i>
                <span>+15% so với năm ngoái</span>
              </div>
              
              <hr class="border-white border-opacity-25 my-3">
              
              <div class="d-flex justify-content-between small mb-2">
                <span class="opacity-75">Mục tiêu năm nay</span>
                <span class="fw-semibold">2.5T VND</span>
              </div>
              <div class="progress rounded-pill" style="height: 6px; background-color: rgba(255,255,255,0.2);">
                <div class="progress-bar bg-success rounded-pill" style="width: 48%"></div>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-6">
              <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                <div class="card-body p-4">
                  <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-2" 
                    style="width: 32px; height: 32px; background-color: rgba(26, 35, 126, 0.1);">
                    <i class="bi bi-cart-check text-primary small"></i>
                  </div>
                  <p class="text-uppercase fw-bold text-muted mb-1 small" style="font-size: 9px; letter-spacing: 0.5px;">
                    Tổng giao dịch
                  </p>
                  <p class="h4 fw-black text-primary mb-0">200+</p>
                </div>
              </div>
            </div>

            <div class="col-6">
              <div class="card border-0 shadow-sm rounded-4 text-center h-100" style="background-color: rgba(40, 167, 69, 0.05);">
                <div class="card-body p-4">
                  <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-2" 
                    style="width: 32px; height: 32px; background-color: rgba(40, 167, 69, 0.2);">
                    <i class="bi bi-arrow-repeat text-success small"></i>
                  </div>
                  <p class="text-uppercase fw-bold text-success mb-1 small" style="font-size: 9px; letter-spacing: 0.5px;">
                    Tỷ lệ quay lại
                  </p>
                  <p class="h4 fw-black text-success mb-0">98%</p>
                </div>
              </div>
            </div>
          </div>

          <!-- QUICK ACTIONS -->
          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
              <p class="text-uppercase fw-bold text-muted mb-3 small" style="letter-spacing: 0.5px;">
                Thao tác nhanh
              </p>
              <div class="list-group list-group-flush">
                <button class="list-group-item list-group-item-action border-0 rounded-3 px-3 py-3 d-flex justify-content-between align-items-center">
                  <span class="fw-medium">Xem báo cáo</span>
                  <i class="bi bi-chevron-right text-muted small"></i>
                </button>
                <button class="list-group-item list-group-item-action border-0 rounded-3 px-3 py-3 d-flex justify-content-between align-items-center">
                  <span class="fw-medium">Cài đặt thông báo</span>
                  <i class="bi bi-chevron-right text-muted small"></i>
                </button>
                <button class="list-group-item list-group-item-action border-0 rounded-3 px-3 py-3 d-flex justify-content-between align-items-center">
                  <span class="fw-medium">Trợ giúp</span>
                  <i class="bi bi-chevron-right text-muted small"></i>
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
import axios from "axios"
import Swal from "sweetalert2"

export default {
  data() {
    return {
      loading: false,
      profile: {
        id: "",
        ten: "",
        email: "",
        so_dien_thoai: "",
        dia_chi: "",
        khu_vuc_hoat_dong: "",
        mo_ta: ""
      },
      passwordForm: {
        old_password: "",
        password: "",
        re_password: ""
      },
      defaultAvatar: "https://ui-avatars.com/api/?name=Moi+Gioi&background=1a237e&color=fff",
      token: localStorage.getItem("auth_token")
    };
  },

  computed: {
    infoFields() {
      return [
        {
          label: "Email",
          value: this.profile.email,
          icon: "bi bi-envelope"
        },
        {
          label: "Số điện thoại",
          value: this.profile.so_dien_thoai,
          icon: "bi bi-telephone"
        },
        {
          label: "Địa chỉ",
          value: this.profile.dia_chi || "Chưa cập nhật",
          icon: "bi bi-geo-alt"
        },
        {
          label: "Khu vực hoạt động",
          value: this.profile.khu_vuc_hoat_dong || "Chưa cập nhật",
          icon: "bi bi-map"
        }
      ]
    }
  },

  methods: {
    async fetchProfile() {
      try {
        const res = await axios.get(
          "http://127.0.0.1:8000/api/moi-gioi/profile",
          {
            headers: {
              Authorization: `Bearer ${this.token}`
            }
          }
        )

        if (res.data.status) {
          Object.assign(this.profile, res.data.data)
        }

      } catch (error) {
        Swal.fire("Lỗi", "Không tải được hồ sơ", "error")
      }
    },

    async saveProfile() {
      this.loading = true

      try {
        await axios.post(
          "http://127.0.0.1:8000/api/moi-gioi/update-profile",
          this.profile,
          {
            headers: {
              Authorization: `Bearer ${this.token}`
            }
          }
        )

        Swal.fire("Thành công", "Cập nhật hồ sơ thành công", "success")

      } catch (error) {
        Swal.fire("Lỗi", "Cập nhật thất bại", "error")
      }

      this.loading = false
    },

    async changePassword() {
      if (
        this.passwordForm.password !==
        this.passwordForm.re_password
      ) {
        Swal.fire("Lỗi", "Xác nhận mật khẩu không khớp", "error")
        return
      }

      try {
        await axios.post(
          "http://127.0.0.1:8000/api/moi-gioi/update-password",
          this.passwordForm,
          {
            headers: {
              Authorization: `Bearer ${this.token}`
            }
          }
        )

        Swal.fire("Thành công", "Đổi mật khẩu thành công", "success")

        this.passwordForm.old_password = ""
        this.passwordForm.password = ""
        this.passwordForm.re_password = ""

      } catch (error) {
        Swal.fire("Lỗi", "Đổi mật khẩu thất bại", "error")
      }
    }
  },

  mounted() {
    this.fetchProfile()
  }
}
</script>

<style scoped>
/* Custom styles for smooth transitions */
.hover-shadow:hover {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
  border-color: rgba(26, 35, 126, 0.3) !important;
}

.transition-all {
  transition: all 0.2s ease-in-out;
}

/* Override Bootstrap primary color */
.btn-primary {
  background-color: #1a237e !important;
  border-color: #1a237e !important;
}

.btn-primary:hover {
  background-color: #151c6a !important;
  border-color: #151c6a !important;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>