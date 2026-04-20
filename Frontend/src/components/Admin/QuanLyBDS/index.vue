<template>
  <div class="property-management p-4">
    
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm custom-header-card">
          <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3 gap-3">
            <div>
              <h4 class="mb-0 fw-bold text-primary">
                <i class="bi bi-buildings-fill me-2"></i>Quản lý bất động sản
              </h4>
              <small class="text-muted">Giám sát và phê duyệt danh mục tin đăng toàn hệ thống với độ chính xác cao.</small>
            </div>
            
            <div class="d-flex flex-wrap gap-2">
              <button 
                @click="showAdvancedFilter = !showAdvancedFilter" 
                class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center"
              >
                <i class="bi bi-funnel me-2"></i> Lọc nâng cao
              </button>
              
              <button 
                @click="createNewProperty" 
                class="btn btn-primary text-nowrap rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center"
              >
                <i class="bi bi-plus-circle me-2"></i> Tạo tin mới
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4 g-3">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <small class="text-muted fw-bold">TỔNG TIN ĐĂNG</small>
              <div class="d-flex align-items-baseline gap-2">
                <h3 class="fw-bold text-primary mb-0">{{ statistics.total.toLocaleString() }}</h3>
                <small class="text-success fw-bold"><i class="bi bi-arrow-up-short"></i>12%</small>
              </div>
            </div>
            <i class="bi bi-building fs-1 text-primary opacity-25"></i>
          </div>
        </div>
      </div>
      
      <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <small class="text-warning fw-bold">ĐANG CHỜ DUYỆT</small>
              <h3 class="fw-bold text-warning mb-0">{{ statistics.pending.toLocaleString() }}</h3>
              <small class="text-danger fw-bold">Cần xử lý</small>
            </div>
            <i class="bi bi-hourglass-split fs-1 text-warning opacity-50"></i>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success bg-opacity-10">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <small class="text-success fw-bold">ĐÃ DUYỆT THÁNG NÀY</small>
              <h3 class="fw-bold text-success mb-0">{{ statistics.approved.toLocaleString() }}</h3>
              <small class="text-muted fw-medium">89% tỷ lệ</small>
            </div>
            <i class="bi bi-check-circle fs-1 text-success opacity-50"></i>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <small class="opacity-75 fw-bold">BĐS NỔI BẬT</small>
              <h3 class="fw-bold mb-0 text-white">{{ statistics.featured.toLocaleString() }}</h3>
              <small class="opacity-75 fw-medium">Gói VIP</small>
            </div>
            <i class="bi bi-star-fill fs-1 opacity-25"></i>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showAdvancedFilter" class="card border-0 shadow-sm mb-4 bg-white rounded-4">
      <div class="card-body p-4">
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label small fw-bold text-muted text-uppercase tracking-widest">Loại bất động sản</label>
            <select v-model="filters.loai" class="form-select custom-input text-muted fw-medium">
              <option value="">Tất cả</option>
              <option value="1">Căn hộ</option>
              <option value="2">Nhà phố</option>
              <option value="3">Đất nền</option>
              <option value="4">Văn phòng</option>
            </select>
          </div>
          
          <div class="col-md-3">
            <label class="form-label small fw-bold text-muted text-uppercase tracking-widest">Trạng thái</label>
            <select v-model="filters.trang_thai" class="form-select custom-input text-muted fw-medium">
              <option value="">Tất cả</option>
              <option value="cho_duyet">Chờ duyệt</option>
              <option value="da_duyet">Đã duyệt</option>
              <option value="tu_choi">Từ chối</option>
              <option value="da_ban">Đã bán</option>
            </select>
          </div>
          
          <div class="col-md-3">
            <label class="form-label small fw-bold text-muted text-uppercase tracking-widest">Khoảng giá</label>
            <select v-model="filters.gia" class="form-select custom-input text-muted fw-medium">
              <option value="">Tất cả</option>
              <option value="0-1000000000">Dưới 1 tỷ</option>
              <option value="1000000000-3000000000">1 - 3 tỷ</option>
              <option value="3000000000-5000000000">3 - 5 tỷ</option>
              <option value="5000000000-999999999999">Trên 5 tỷ</option>
            </select>
          </div>
          
          <div class="col-md-3">
            <label class="form-label small fw-bold text-muted text-uppercase tracking-widest">Thời gian</label>
            <select v-model="filters.thoi_gian" class="form-select custom-input text-muted fw-medium">
              <option value="">Tất cả</option>
              <option value="today">Hôm nay</option>
              <option value="week">Tuần này</option>
              <option value="month">Tháng này</option>
            </select>
          </div>
        </div>
        
        <div class="d-flex justify-content-end gap-2 mt-4">
          <button @click="resetFilters" class="btn btn-light rounded-pill px-4 fw-medium text-muted">
            Đặt lại
          </button>
          <button @click="applyFilters" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            Áp dụng bộ lọc
          </button>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body p-0">
        <div class="table-responsive custom-scrollbar">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Bất động sản</th>
                <th>Loại & Vị trí</th>
                <th>Giá niêm yết</th>
                <th>Môi giới đăng</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-end pe-4">Hành động</th>
              </tr>
            </thead>
            
            <tbody>
              <tr v-if="properties.length === 0">
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                  Chưa có dữ liệu bất động sản
                </td>
              </tr>

              <tr v-for="bds in properties" :key="bds.id" class="transition-all">
                <td class="ps-4 py-3">
                  <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                      <img 
                        :src="getImageUrl(bds.hinh_anh?.[0]?.url)" 
                        class="rounded-3 object-cover shadow-sm border border-2 border-white"
                        style="width: 60px; height: 60px;"
                        :alt="bds.tieu_de"
                      />
                      <span v-if="bds.is_featured" class="position-absolute top-0 start-0 translate-middle p-1 bg-warning rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 24px; height: 24px;">
                        <i class="bi bi-star-fill" style="font-size: 12px;"></i>
                      </span>
                    </div>
                    <div>
                      <p class="fw-bold text-dark mb-0 text-truncate" style="max-width: 250px;" :title="bds.tieu_de">{{ bds.tieu_de }}</p>
                      <span class="badge bg-light text-secondary border px-2 py-1 mt-1 small">
                        ID: {{ bds.ma_bds || 'RE-' + String(bds.id).padStart(4, '0') }}
                      </span>
                    </div>
                  </div>
                </td>
                
                <td>
                  <p class="fw-bold text-dark small mb-0">{{ bds.loai?.ten_loai || '—' }}</p>
                  <small class="text-muted d-block mt-1 text-truncate" style="max-width: 200px;">
                    <i class="bi bi-geo-alt me-1"></i>{{ bds.dia_chi?.dia_chi_chi_tiet || bds.dia_chi || '—' }}
                  </small>
                </td>
                
                <td>
                  <p class="fw-bold text-primary mb-0">{{ formatPrice(bds.gia) }}</p>
                  <small class="text-muted d-block mt-1">{{ formatPricePerSqm(bds.gia, bds.dien_tich) }}</small>
                </td>
                
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 32px; height: 32px; font-size: 0.8rem;">
                      {{ getInitials(bds.moiGioi?.ten) }}
                    </div>
                    <span class="small fw-medium text-dark">{{ bds.moiGioi?.ten }}</span>
                  </div>
                </td>
                
                <td class="text-center">
                  <span v-if="bds.trang_thai === 'cho_duyet'" class="badge-warning badge px-3 py-2 rounded-pill small fw-bold border-0">
                    Chờ duyệt
                  </span>
                  <span v-else-if="bds.trang_thai === 'da_duyet'" class="badge-active badge px-3 py-2 rounded-pill small fw-bold border-0">
                    Đã duyệt
                  </span>
                  <span v-else-if="bds.trang_thai === 'tu_choi'" class="badge-inactive badge px-3 py-2 rounded-pill small fw-bold border-0">
                    Từ chối
                  </span>
                  <span v-else-if="bds.trang_thai === 'da_ban'" class="badge-secondary badge px-3 py-2 rounded-pill small fw-bold border-0">
                    Đã bán
                  </span>
                </td>
                
                <td class="text-end pe-4 text-nowrap">
                  <div class="d-flex justify-content-end gap-1">
                    <template v-if="bds.trang_thai === 'cho_duyet'">
                      <button @click="approveProperty(bds.id)" class="btn btn-sm btn-success rounded-pill fw-bold px-3 me-1 shadow-sm" style="font-size: 0.75rem;">
                        <i class="bi bi-check-lg me-1"></i>Duyệt
                      </button>
                      <button @click="rejectProperty(bds.id)" class="btn btn-sm btn-danger rounded-pill fw-bold px-3 me-2 shadow-sm" style="font-size: 0.75rem;">
                        <i class="bi bi-x-lg me-1"></i>Từ chối
                      </button>
                    </template>

                    <button @click="viewProperty(bds.id)" class="btn btn-icon btn-light-secondary me-1" title="Xem chi tiết">
                      <i class="bi bi-eye"></i>
                    </button>
                    <button @click="editProperty(bds.id)" class="btn btn-icon btn-light-primary me-1" title="Chỉnh sửa">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <button @click="deleteProperty(bds.id)" class="btn btn-icon btn-light-danger" title="Xóa">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center py-3 px-4">
        <small class="text-muted fw-medium">
          Hiển thị <span class="fw-bold text-dark">{{ pagination.from }}</span> - <span class="fw-bold text-dark">{{ pagination.to }}</span> của <span class="fw-bold text-dark">{{ pagination.total }}</span> kết quả
        </small>
        
        <nav aria-label="Page navigation" v-if="pagination.last_page > 1">
          <ul class="pagination pagination-sm mb-0">
            <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
              <button class="page-link shadow-none" @click="changePage(pagination.current_page - 1)">&laquo;</button>
            </li>
            <li v-for="page in pagination.last_page" :key="page" class="page-item" :class="{ active: page === pagination.current_page }">
              <button class="page-link shadow-none" @click="changePage(page)">{{ page }}</button>
            </li>
            <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
              <button class="page-link shadow-none" @click="changePage(pagination.current_page + 1)">&raquo;</button>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <div v-if="urgentApprovals > 0" class="alert alert-warning border-0 shadow-sm rounded-4 p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-0" role="alert" style="background: linear-gradient(145deg, #fff3cd, #fff8e6);">
      <div class="d-flex align-items-center gap-3 mb-3 mb-md-0">
        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
          <i class="bi bi-exclamation-triangle-fill fs-4"></i>
        </div>
        <div>
          <h6 class="alert-heading fw-bold mb-1 text-dark">Yêu cầu phê duyệt gấp</h6>
          <p class="mb-0 small text-muted">Có <strong class="text-danger">{{ urgentApprovals }}</strong> tin đăng của đối tác hạng VIP đang chờ xử lý quá 24h.</p>
        </div>
      </div>
      <button @click="handleUrgentApprovals" class="btn btn-warning fw-bold rounded-pill px-4 shadow-sm text-dark">
        Xử lý ngay
      </button>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminPropertyManagement',
  
  data() {
    return {
      searchQuery: '',
      showAdvancedFilter: false,
      urgentApprovals: 12,
      statistics: {
        total: 1284,
        pending: 42,
        approved: 315,
        featured: 18
      },
      pagination: {
        current_page: 1,
        last_page: 129,
        from: 1,
        to: 10,
        total: 1284
      },
      filters: {
        loai: '',
        trang_thai: '',
        gia: '',
        thoi_gian: ''
      },
      properties: []
    };
  },
  
  mounted() {
    this.loadProperties();
  },
  
  methods: {
    async loadProperties() {
  try {
    const token = localStorage.getItem("auth_token");

    if (!token) {
      console.error("❌ Không có token → chưa đăng nhập");
      return;
    }

    const response = await axios.get(
      'http://127.0.0.1:8000/api/admin/bds/data',
      {
        headers: {
          Authorization: 'Bearer ' + token
        },
        params: {
          page: this.pagination.current_page,
          ...this.filters
        }
      }
    );

    if (response.data.status) {
      this.properties = response.data.data.data || response.data.data;

      this.pagination = {
        current_page: response.data.data.current_page || 1,
        last_page: response.data.data.last_page || 1,
        from: response.data.data.from || 1,
        to: response.data.data.to || this.properties.length,
        total: response.data.data.total || this.properties.length
      };
    } else {
      console.error("❌ API trả status false:", response.data.message);
    }

  } catch (error) {
    console.error("🔥 Lỗi tải danh sách BĐS:", error.response || error);

    if (error.response?.status === 401) {
      console.error("❌ Token sai hoặc hết hạn");
      localStorage.clear();
      this.$router.push("/login");
    }
  }
},
    
    async searchProperties() {
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/admin/bds/tim-kiem', {
          keyword: this.searchQuery
        });
        
        if (response.data.status) {
          this.properties = response.data.data;
        }
      } catch (error) {
        console.error('Lỗi tìm kiếm:', error);
      }
    },
    
    applyFilters() {
      this.pagination.current_page = 1;
      this.loadProperties();
      this.showAdvancedFilter = false;
    },
    
    resetFilters() {
      this.filters = {
        loai: '',
        trang_thai: '',
        gia: '',
        thoi_gian: ''
      };
      this.searchQuery = '';
      this.pagination.current_page = 1;
      this.loadProperties();
    },
    
    changePage(page) {
      if (page < 1 || page > this.pagination.last_page) return;
      this.pagination.current_page = page;
      this.loadProperties();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    
    async approveProperty(id) {
      if (!confirm('Bạn có chắc chắn muốn duyệt tin đăng này?')) return;
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/admin/bds/duyet', {
          bds_id: id,
          trang_thai: 'da_duyet'
        });
        if (response.data.status) {
          alert('Đã duyệt tin đăng thành công!');
          this.loadProperties();
        }
      } catch (error) {
        console.error('Lỗi duyệt tin:', error);
      }
    },
    
    async rejectProperty(id) {
      const lyDo = prompt('Lý do từ chối:');
      if (!lyDo) return;
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/admin/bds/change-status', {
          bds_id: id,
          trang_thai: 'tu_choi',
          ly_do_tu_choi: lyDo
        });
        if (response.data.status) {
          alert('Đã từ chối tin đăng');
          this.loadProperties();
        }
      } catch (error) {
        console.error('Lỗi từ chối tin:', error);
      }
    },
    
    async deleteProperty(id) {
      if (!confirm('Bạn có chắc chắn muốn xóa tin đăng này? Hành động này không thể hoàn tác!')) return;
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/admin/bds/delete', {
          bds_id: id
        });
        if (response.data.status) {
          alert('Đã xóa tin đăng thành công!');
          this.loadProperties();
        }
      } catch (error) {
        console.error('Lỗi xóa tin:', error);
      }
    },
    
    viewProperty(id) {
      this.$router.push(`/admin/bat-dong-san/${id}`);
    },
    editProperty(id) {
      this.$router.push(`/admin/bat-dong-san/${id}/edit`);
    },
    createNewProperty() {
      this.$router.push('/admin/bat-dong-san/create');
    },
    handleUrgentApprovals() {
      this.filters.trang_thai = 'cho_duyet';
      this.applyFilters();
    },
    
    getImageUrl(url) {
      if (!url) return 'https://via.placeholder.com/100x100?text=No+Image';
      if (url.startsWith('http')) return url;
      return `http://127.0.0.1:8000/storage/${url}`;
    },
    
    formatPrice(gia) {
      if (!gia && gia !== 0) return 'Liên hệ';
      if (gia >= 1000000000) {
        return (gia / 1000000000).toFixed(1).replace(/\.0$/, '') + ' Tỷ VNĐ';
      }
      return (gia / 1000000).toFixed(0) + ' Triệu VNĐ';
    },
    
    formatPricePerSqm(gia, dienTich) {
      if (!gia || !dienTich) return '';
      const pricePerSqm = gia / dienTich;
      if (pricePerSqm >= 1000000) {
        return '~' + (pricePerSqm / 1000000).toFixed(1) + ' Triệu/m²';
      }
      return '~' + Math.round(pricePerSqm) + ' Nghìn/m²';
    },
    
    getInitials(ten) {
      if (!ten) return '—';
      const parts = ten.split(' ');
      if (parts.length >= 2) {
        return (parts[parts.length - 1][0] + parts[0][0]).toUpperCase();
      }
      return parts[0]?.[0]?.toUpperCase() || '—';
    }
  }
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css");

.property-management {
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
  padding: 15px;
  border-bottom: 1px solid #f1f1f1;
}

/* Status Badges */
.badge-active {
  background-color: #e6fcf5;
  color: #087f5b;
}
.badge-inactive {
  background-color: #fff5f5;
  color: #dc3545;
}
.badge-warning {
  background-color: #fff3cd;
  color: #856404;
}
.badge-secondary {
  background-color: #e9ecef;
  color: #495057;
}

/* Buttons */
.btn-icon {
  width: 34px;
  height: 34px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  transition: all 0.2s;
}
.btn-light-primary {
  background: #e7f1ff;
  color: #0d6efd;
  border: none;
}
.btn-light-danger {
  background: #fff5f5;
  color: #dc3545;
  border: none;
}
.btn-light-secondary {
  background: #e9ecef;
  color: #6c757d;
  border: none;
}
.btn-light-primary:hover { background: #0d6efd; color: white; transform: translateY(-1px); }
.btn-light-danger:hover { background: #dc3545; color: white; transform: translateY(-1px); }
.btn-light-secondary:hover { background: #6c757d; color: white; transform: translateY(-1px); }

/* Inputs */
.custom-input {
  border-radius: 10px;
  padding: 10px 15px;
  border: 1px solid #dee2e6;
  background-color: #fcfcfc;
  transition: all 0.2s;
}
.custom-input:focus {
  border-color: #0d6efd;
  background-color: white;
  box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
}

/* Pagination custom overriding */
.pagination .page-link {
  border-radius: 8px;
  margin: 0 2px;
  border: none;
  color: #6c757d;
  font-weight: 500;
}
.pagination .page-item.active .page-link {
  background-color: #0d6efd;
  color: white;
}
.pagination .page-link:hover:not(.active) {
  background-color: #f1f1f1;
}

/* Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #dee2e6;
  border-radius: 10px;
}
</style>