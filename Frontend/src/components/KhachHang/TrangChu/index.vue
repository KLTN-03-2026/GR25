<template>
  <div class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen font-['Inter']">
    <!-- Hero Section -->
    <section class="relative h-[600px] lg:h-[700px] flex items-center overflow-hidden">
      <div class="absolute inset-0">
        <img
          src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80"
          class="w-full h-full object-cover"
          alt="Luxury Real Estate"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/80 to-transparent"></div>
      </div>

      <div class="relative z-10 container mx-auto px-6 lg:px-12">
        <div class="max-w-3xl">
          <div class="inline-block px-4 py-2 bg-blue-600/20 backdrop-blur-sm border border-blue-400/30 rounded-full mb-6">
            <span class="text-blue-300 text-sm font-semibold tracking-wide">BẤT ĐỘNG SẢN CAO CẤP</span>
          </div>
          <h1 class="font-['Be_Vietnam_Pro'] text-5xl lg:text-7xl font-bold text-white leading-tight mb-6">
            Kiến tạo không gian<br />
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">sống thượng lưu</span>
          </h1>
          <p class="text-lg text-gray-300 mb-10 max-w-xl leading-relaxed">
            Khám phá những bất động sản đẳng cấp nhất, được tuyển chọn kỹ lưỡng cho phong cách sống đặc quyền.
          </p>

          <!-- Quick Stats -->
          <div class="flex gap-8">
            <div v-for="(stat, index) in stats" :key="`stat-${stat.id}`" class="contents">
              <div>
                <div class="text-3xl font-bold text-white uppercase">{{ stat.current }}{{ stat.suffix }}</div>
                <div class="text-sm text-gray-400">{{ stat.label }}</div>
              </div> 
              <div v-if="index < stats.length - 1" class="w-px bg-gray-600"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Search Section -->
    <section class="relative -mt-20 z-20 px-6">
      <div class="container mx-auto max-w-5xl">
        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Vị trí</label>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">location_on</span>
                <input
                  v-model="search.location"
                  placeholder="Quận, thành phố..."
                  class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                />
              </div>
            </div>

            <div class="relative">
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Loại hình</label>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">category</span>
                <select
                  v-model="search.type"
                  class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer"
                >
                  <option value="">Tất cả</option>
                  <option v-for="loai in propertyTypes" :key="loai.id" :value="loai.id">
                    {{ loai.ten_loai }}
                  </option>
                </select>
              </div>
            </div>

            <div class="relative">
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Khoảng giá</label>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">payments</span>
                <select
                  v-model="search.price"
                  class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer"
                >
                  <option value="">Mức giá</option>
                  <option value="duoi-10">Dưới 10 tỷ</option>
                  <option value="10-30">10 - 30 tỷ</option>
                  <option value="tren-30">Trên 30 tỷ</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Properties Section -->
    <section class="py-20 px-6">
      <div class="container mx-auto max-w-7xl">
        <div class="flex justify-between items-end mb-12">
          <div>
            <h2 class="font-['Be_Vietnam_Pro'] text-4xl font-bold text-slate-800 mb-3">Bất động sản nổi bật</h2>
            <p class="text-gray-500">Những cơ hội đầu tư sinh lời cao nhất</p>
          </div>
         <router-link to="/khach-hang/danh-sach-bat-dong-san" class="hidden md:flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-700 transition-colors">
            Xem tất cả
            <span class="material-symbols-outlined">arrow_forward</span>
          </router-link>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="i in 3" :key="`loading-${i}`" class="animate-pulse">
            <div class="bg-white rounded-2xl overflow-hidden h-[450px]">
              <div class="h-64 bg-gray-200"></div>
              <div class="p-6 space-y-4">
                <div class="h-6 bg-gray-200 rounded w-3/4"></div>
                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                <div class="h-8 bg-gray-200 rounded w-1/3 mt-4"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Properties Grid -->
        <div v-else-if="properties.length > 0" class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
          <div
            v-for="item in properties"
            :key="item.id"
            class="group block bg-white rounded-3xl overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_60px_rgba(37,99,235,0.1)] transition-all duration-500 cursor-pointer border border-gray-100/50 hover:-translate-y-3"
            @click.prevent="viewProperty(item.id)"
          >
            <div class="relative h-80 overflow-hidden">
              <img
                :src="item.image"
                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                :alt="item.name || 'Bất động sản'"
                @error="handleImageError"
                loading="lazy"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-500 pointer-events-none"></div>

              <!-- Badge Exclusive -->
              <div class="absolute top-5 left-5 pointer-events-none">
                <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md text-slate-900 text-[10px] font-black uppercase tracking-[0.15em] rounded-full shadow-sm">
                  Exclusive
                </span>
              </div>

              <!-- ❤️ Icon Trái Tim - Nút Favorite -->
              <button
                @click.stop="toggleFavorite(item.id, $event)"
                class="absolute top-5 right-5 w-11 h-11 rounded-full bg-white/95 backdrop-blur-md shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 group/btn"
                :class="item.isFavorite ? 'bg-gradient-to-br from-pink-500 to-rose-500' : ''"
                :aria-label="item.isFavorite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích'"
              >
                <span 
                  class="material-symbols-outlined text-xl transition-all duration-300"
                  :class="item.isFavorite ? 'text-white fill-current' : 'text-gray-400 group-hover/btn:text-pink-500'"
                >
                  favorite
                </span>
              </button>
            </div>

            <div class="p-8">
              <div class="flex items-center gap-2 mb-3">
                <span class="text-blue-600 font-bold text-[11px] uppercase tracking-widest">{{ item.loai }}</span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span class="text-gray-400 text-[11px] font-medium uppercase tracking-widest">
                  {{ (item.location || 'Việt Nam').split(',')[0] }}
                </span>
              </div>

              <h3 class="font-['Be_Vietnam_Pro'] text-2xl font-bold text-slate-800 mb-6 leading-tight group-hover:text-blue-600 transition-colors line-clamp-2 h-[67px]">
                {{ item.name }}
              </h3>

              <div class="flex items-baseline justify-between">
                <div>
                  <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold mb-1">Giá đặc quyền</p>
                  <p class="text-2xl font-black text-slate-900">{{ formatPriceDisplay(item.gia) }}</p>
                </div>

                <div class="flex items-center gap-2 text-blue-600 font-semibold text-sm group/btn">
                  <span>Chi tiết</span>
                  <span class="material-symbols-outlined transition-transform group-hover/btn:translate-x-1">arrow_right_alt</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="!loading && properties.length === 0" class="text-center py-20">
          <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-gray-400 text-5xl">search_off</span>
          </div>
          <h3 class="text-xl font-bold text-gray-700 mb-2">Không tìm thấy bất động sản</h3>
          <p class="text-gray-500">Hãy thử điều chỉnh tiêu chí tìm kiếm của bạn</p>
        </div>
      </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 bg-gray-50/50 border-t border-gray-100">
      <div class="container mx-auto max-w-7xl px-6">
        <div class="text-center max-w-2xl mx-auto mb-16">
          <h2 class="font-['Be_Vietnam_Pro'] text-[32px] md:text-[38px] font-bold text-[#0a0e27] mb-4">
            Tại sao chọn Architectural Curator?
          </h2>
          <p class="text-gray-500 text-[16px] leading-relaxed">
            Chúng tôi không chỉ là sàn giao dịch, mà là hệ sinh thái toàn diện dành cho giới thượng lưu.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div class="bg-white p-8 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_40px_rgba(10,14,39,0.08)] hover:-translate-y-2 transition-all duration-500 group border border-gray-100">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-colors duration-500">
              <span class="material-symbols-outlined text-[32px] text-blue-600 group-hover:text-white transition-colors duration-500">verified</span>
            </div>
            <h3 class="font-['Be_Vietnam_Pro'] text-[20px] font-bold text-[#0a0e27] mb-3">Pháp lý chuẩn mực</h3>
            <p class="text-gray-500 text-[14px] leading-relaxed">
              100% dự án được kiểm định pháp lý bởi đội ngũ luật sư chuyên nghiệp, đảm bảo an toàn tuyệt đối.
            </p>
          </div>

          <div class="bg-white p-8 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_40px_rgba(10,14,39,0.08)] hover:-translate-y-2 transition-all duration-500 group border border-gray-100">
            <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors duration-500">
              <span class="material-symbols-outlined text-[32px] text-purple-600 group-hover:text-white transition-colors duration-500">diamond</span>
            </div>
            <h3 class="font-['Be_Vietnam_Pro'] text-[20px] font-bold text-[#0a0e27] mb-3">Sản phẩm độc quyền</h3>
            <p class="text-gray-500 text-[14px] leading-relaxed">
              Tuyển chọn kỹ lưỡng những BĐS hạng sang tại các vị trí kim cương của thành phố.
            </p>
          </div>

          <div class="bg-white p-8 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_40px_rgba(10,14,39,0.08)] hover:-translate-y-2 transition-all duration-500 group border border-gray-100">
            <div class="w-16 h-16 bg-cyan-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-cyan-600 transition-colors duration-500">
              <span class="material-symbols-outlined text-[32px] text-cyan-600 group-hover:text-white transition-colors duration-500">support_agent</span>
            </div>
            <h3 class="font-['Be_Vietnam_Pro'] text-[20px] font-bold text-[#0a0e27] mb-3">Tư vấn tận tâm</h3>
            <p class="text-gray-500 text-[14px] leading-relaxed">
              Đội ngũ chuyên viên am hiểu thị trường, hỗ trợ 24/7 với thái độ phục vụ đẳng cấp 5 sao.
            </p>
          </div>

          <div class="bg-white p-8 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_40px_rgba(10,14,39,0.08)] hover:-translate-y-2 transition-all duration-500 group border border-gray-100">
            <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-600 transition-colors duration-500">
              <span class="material-symbols-outlined text-[32px] text-orange-600 group-hover:text-white transition-colors duration-500">chart_data</span>
            </div>
            <h3 class="font-['Be_Vietnam_Pro'] text-[20px] font-bold text-[#0a0e27] mb-3">Tiềm năng sinh lời</h3>
            <p class="text-gray-500 text-[14px] leading-relaxed">
              Phân tích dữ liệu thị trường chuyên sâu, tối ưu hóa lợi nhuận đầu tư cho khách hàng.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-white">
      <div class="container mx-auto max-w-7xl px-6">
        <div class="relative overflow-hidden rounded-[32px] bg-gradient-to-br from-[#0a0e27] via-[#0d1542] to-[#0a0e27] px-8 md:px-16 py-12 md:py-16">
          <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
          <div class="absolute bottom-0 left-0 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

          <div class="relative z-10 grid lg:grid-cols-2 gap-10 items-center">
            <div>
              <h2 class="font-['Be_Vietnam_Pro'] text-[32px] md:text-[40px] font-bold text-white leading-tight mb-5">
                Trở thành đối tác<br />
                <span class="text-blue-400">môi giới chuyên nghiệp</span>
              </h2>

              <p class="text-[#94a3b8] text-[15px] md:text-[16px] leading-relaxed max-w-xl mb-8">
                Gia nhập đội ngũ ArchiEstate để tiếp cận nguồn khách hàng cao cấp và hệ thống quản lý bất động sản hiện đại nhất.
              </p>

              <button
                style="border-radius: 20px"
                class="bg-white hover:bg-gray-100 text-[#0a0e27] px-8 py-4 rounded-full font-['Be_Vietnam_Pro'] font-bold text-[14px] tracking-wide shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98] transition-all duration-300 flex items-center gap-2.5 group"
              >
                <span>Đăng ký ngay</span>
                <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform duration-300">
                  arrow_forward
                </span>
              </button>
            </div>

            <div class="flex lg:justify-end">
              <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[24px] px-8 py-7 max-w-sm w-full">
                <div class="flex items-start gap-4 mb-6">
                  <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-blue-400 text-[24px]">trending_up</span>
                  </div>
                  <div>
                    <div class="text-white font-bold text-[18px] mb-0.5">Tăng trưởng 45%</div>
                    <div class="text-[#64748b] text-[13px] font-medium">Doanh thu trung bình đối tác</div>
                  </div>
                </div>

                <div class="flex items-start gap-4">
                  <div class="w-12 h-12 rounded-xl bg-cyan-400/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-cyan-400 text-[24px]">groups</span>
                  </div>
                  <div>
                    <div class="text-white font-bold text-[18px] mb-0.5">1,200+ Môi giới</div>
                    <div class="text-[#64748b] text-[13px] font-medium">Đang hoạt động trên toàn quốc</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ✅ TOASTER CONTAINER (Cuối template) -->
    <div id="toaster-container" class="fixed top-4 right-4 z-[9999] space-y-2 pointer-events-none"></div>
  </div>
</template>

<script>
import axios from "axios";

// ✅ Tạo toaster đơn giản (không cần install package)
const toaster = {
  show(message, type = 'success', duration = 2500) {
    const container = document.getElementById('toaster-container');
    if (!container) return;

    const toast = document.createElement('div');
    const isSuccess = type === 'success';
    
    toast.className = `
      pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg 
      backdrop-blur-md border animate-slide-in
      ${isSuccess 
        ? 'bg-emerald-500/95 border-emerald-400 text-white' 
        : 'bg-rose-500/95 border-rose-400 text-white'}
    `;
    
    toast.innerHTML = `
      <span class="material-symbols-outlined text-xl">${isSuccess ? 'check_circle' : 'favorite'}</span>
      <span class="font-medium text-sm">${message}</span>
    `;
    
    container.appendChild(toast);
    
    // Auto remove
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(100%)';
      setTimeout(() => toast.remove(), 200);
    }, duration);
  }
};

export default {
  name: "HomePage",

  data() {
    return {
      loading: false,
      propertyTypes: [],
      search: { location: "", type: "", price: "" },
      properties: [],
      stats: [
        { id: 1, label: "Dự án", value: 500, suffix: "+", current: 0 },
        { id: 2, label: "Khách hàng", value: 12, suffix: "K+", current: 0 },
        { id: 3, label: "Năm kinh nghiệm", value: 15, suffix: "+", current: 0 },
      ],
      defaultImage: "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800",
      searchTimer: null,
    };
  },

  watch: {
    search: {
      handler() {
        if (this.searchTimer) clearTimeout(this.searchTimer);
        this.searchTimer = setTimeout(() => this.handleSearch(), 600);
      },
      deep: true
    }
  },

  mounted() {
    this.loadProperties();
    this.loadPropertyTypes();
    this.animateStats();
  },

  methods: {
    async loadPropertyTypes() {
  try {
    const res = await axios.get("http://127.0.0.1:8000/api/client/loai-bat-dong-san");

    if (Array.isArray(res.data)) {
      this.propertyTypes = res.data;
    } else if (res.data?.data) {
      this.propertyTypes = res.data.data;
    } else {
      this.propertyTypes = [];
    }

  } catch (error) {
    console.error("Lỗi loại BĐS:", error);
    this.propertyTypes = [];
  }
},

    getImageUrl(url) {
      if (!url) return this.defaultImage;
      if (url.startsWith('http')) return url;
      return `http://127.0.0.1:8000/storage/${url}`;
    },

    async loadProperties() {
      this.loading = true;
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/client/bat-dong-san");
        let raw = [];
        if (Array.isArray(res.data)) {
          raw = res.data;
        } else if (res.data?.data?.data) {
          raw = res.data.data.data;
        } else if (res.data?.data) {
          raw = res.data.data;
        }
        this.properties = this.mapProperties(raw);
      } catch (error) {
        console.error("Load lỗi:", error);
        this.properties = [];
      } finally {
        this.loading = false;
      }
    },

   mapProperties(rawData) {
      return rawData.map((item) => {
        let imageUrl = this.defaultImage;

        const isValidUrl = (u) => {
          return typeof u === "string" &&
                u.trim() !== "" &&
                u !== "null" &&
                u !== "undefined";
        };

        // 🔥 ƯU TIÊN hinh_anh trước (FIX LẶP)
        if (Array.isArray(item.hinh_anh)) {
          const img = item.hinh_anh.find(i => i && isValidUrl(i.url));
          if (img) {
            imageUrl = this.getImageUrl(img.url.trim());
          }
        }

        // 🔥 fallback mới dùng anh_dai_dien
        else if (
          item.anh_dai_dien &&
          isValidUrl(item.anh_dai_dien.url)
        ) {
          imageUrl = this.getImageUrl(item.anh_dai_dien.url.trim());
        }

        let location = "Đang cập nhật";
        if (item.dia_chi) {
          if (item.dia_chi.ten_quan && item.dia_chi.ten_tinh) {
            location = `${item.dia_chi.ten_quan}, ${item.dia_chi.ten_tinh}`;
          } else if (item.dia_chi.ten_tinh) {
            location = item.dia_chi.ten_tinh;
          }
        }

        return {
          id: item.id,
          name: item.tieu_de || "Bất động sản",
          location,
          loai: item.loai?.ten_loai || "BĐS",
          gia: item.gia_display || item.gia,
          image: imageUrl,
          isFavorite: item.is_favorite || false,
        };
      });
    },

    async handleSearch() {
      this.loading = true;
      try {
        let gia_min = null, gia_max = null;
        switch (this.search.price) {
          case "duoi-10": gia_max = 10000000000; break;
          case "10-30": gia_min = 10000000000; gia_max = 30000000000; break;
          case "tren-30": gia_min = 30000000000; break;
        }
        const payload = {
          tieu_de: this.search.location || "",
          loai_id: this.search.type || "",
          gia_min, gia_max,
        };
        const res = await axios.post("http://127.0.0.1:8000/api/client/tim-kiem", payload);
        if (res.data?.status) {
          const raw = res.data.data?.data || [];
          this.properties = this.mapProperties(raw);
        } else {
          this.properties = [];
        }
      } catch (error) {
        console.error("Search lỗi:", error);
        this.properties = [];
      } finally {
        this.loading = false;
      }
    },

    viewProperty(id) {
      const token = localStorage.getItem('auth_token');
      const userType = localStorage.getItem('user_type');
      if (!token || userType !== 'khach-hang') {
        this.$router.push('/khach-hang/dang-nhap');
        return;
      }
      this.$router.push(`/khach-hang/chi-tiet-bat-dong-san/${id}`);
    },

    // ❤️ Toggle Favorite - ✅ CÓ TOASTER NOTIFICATION
   async toggleFavorite(id, ev) {
  ev.stopPropagation();

  const token = localStorage.getItem('auth_token');
  if (!token) {
    toaster.show('Vui lòng đăng nhập để lưu tin', 'error');
    setTimeout(() => this.$router.push('/khach-hang/dang-nhap'), 800);
    return;
  }

  try {
    // ✅ GỌI API HIỆN TẠI (KHÔNG dùng /toggle nữa)
    await axios.post(
      "http://127.0.0.1:8000/api/khach-hang/bds/yeu-thich",
      { bds_id: id },
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    );

    // ✅ LOAD LẠI DANH SÁCH YÊU THÍCH
    const res = await axios.get(
      "http://127.0.0.1:8000/api/khach-hang/bds/yeu-thich/data",
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    );

    // lấy danh sách id đã thích
    const favoriteIds = res.data.data.map(item =>
      item.bat_dong_san_id || item.bds_id || item.batDongSan?.id
    );

    // update lại toàn bộ properties
    this.properties = this.properties.map(p => ({
      ...p,
      isFavorite: favoriteIds.includes(p.id)
    }));

    const current = this.properties.find(p => p.id === id);

    toaster.show(
      current?.isFavorite
        ? 'Đã lưu tin vào yêu thích'
        : 'Đã xóa khỏi yêu thích',
      current?.isFavorite ? 'success' : 'error'
    );

    // sync header
    window.dispatchEvent(new Event('favorite-updated'));

  } catch (err) {
    console.error("API lỗi:", err.response?.data || err);
    toaster.show('Có lỗi xảy ra, vui lòng thử lại', 'error');
  }
},

    formatPriceDisplay(gia) {
      if (!gia) return "Liên hệ";
      if (gia >= 1_000_000_000) return Math.floor(gia / 1_000_000_000) + " Tỷ";
      if (gia >= 1_000_000) return Math.floor(gia / 1_000_000) + " Triệu";
      return "Liên hệ";
    },

    animateStats() {
      this.stats.forEach((stat) => {
        let current = 0;
        const step = stat.value / 60;
        const interval = setInterval(() => {
          current += step;
          if (current >= stat.value) {
            stat.current = stat.value;
            clearInterval(interval);
          } else {
            stat.current = Math.floor(current);
          }
        }, 30);
      });
    },
  },
};
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  line-clamp: 1;
  overflow: hidden;
  word-break: break-all;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}
.bg-blue-500\/10, .bg-cyan-500\/10 {
  animation: float 6s ease-in-out infinite;
}

/* ✅ Toaster Animation */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(120%);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
.animate-slide-in {
  animation: slideIn 0.25s ease-out forwards;
}
</style>