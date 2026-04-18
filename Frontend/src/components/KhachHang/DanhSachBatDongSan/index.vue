<template>
  <div class="bg-[#f8fafc] min-h-screen font-['Inter']">
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <div class="text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
        <p class="mt-4 text-gray-500 font-medium">Đang tải danh sách bất động sản...</p>
      </div>
    </div>

    <!-- Content -->
    <template v-else>
      <!-- Breadcrumb -->
      <div class="bg-white border-b border-gray-100">
        <div class="container mx-auto max-w-7xl px-6 py-4">
          <nav class="flex items-center gap-2 text-sm text-gray-500">
            <router-link to="/" class="hover:text-blue-600 transition-colors">Trang chủ</router-link>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-[#0a0e27] font-semibold">Bất động sản</span>
          </nav>
        </div>
      </div>

      <!-- Header -->
      <div class="bg-white border-b border-gray-100">
        <div class="container mx-auto max-w-7xl px-6 py-8">
          <h1 class="font-['Be_Vietnam_Pro'] text-[32px] md:text-[36px] font-bold text-[#0a0e27] mb-2">
            Danh sách bất động sản
          </h1>
          <p class="text-gray-500">Tìm kiếm và khám phá những bất động sản phù hợp với bạn</p>
        </div>
      </div>

      <!-- Search & Filters -->
      <div class="bg-white border-b border-gray-100 sticky top-0 z-40 shadow-sm">
        <div class="container mx-auto max-w-7xl px-6 py-4">
          <!-- Main Search Bar -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
            <!-- Location -->
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">location_on</span>
              <select v-model="filters.tinh" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none appearance-none cursor-pointer">
                <option value="">Tất cả tỉnh/thành</option>
                <option v-for="tinh in danhSachTinh" :key="tinh.id" :value="tinh.id">{{ tinh.ten_tinh }}</option>
              </select>
            </div>

            <!-- District -->
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">map</span>
              <select v-model="filters.quan" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none appearance-none cursor-pointer">
                <option value="">Tất cả quận/huyện</option>
                <option v-for="quan in danhSachQuan" :key="quan.id" :value="quan.id">{{ quan.ten_quan }}</option>
              </select>
            </div>

            <!-- Property Type -->
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">category</span>
              <select v-model="filters.loai" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none appearance-none cursor-pointer">
                <option value="">Tất cả loại BĐS</option>
                <option v-for="loai in danhSachLoai" :key="loai.id" :value="loai.id">{{ loai.ten_loai }}</option>
              </select>
            </div>

            <!-- Price Range -->
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">attach_money</span>
              <select v-model="filters.gia" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none appearance-none cursor-pointer">
                <option value="">Tất cả mức giá</option>
                <option value="0-1">Dưới 1 tỷ</option>
                <option value="1-3">1 - 3 tỷ</option>
                <option value="3-5">3 - 5 tỷ</option>
                <option value="5-10">5 - 10 tỷ</option>
                <option value="10-999999">Trên 10 tỷ</option>
              </select>
            </div>
          </div>

          <!-- Advanced Filters -->
          <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
            <!-- Area -->
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">square_foot</span>
              <select v-model="filters.dien_tich" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none appearance-none cursor-pointer">
                <option value="">Diện tích</option>
                <option value="0-50">Dưới 50m²</option>
                <option value="50-100">50 - 100m²</option>
                <option value="100-200">100 - 200m²</option>
                <option value="200-999999">Trên 200m²</option>
              </select>
            </div>

            <!-- Bedrooms -->
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">bed</span>
              <select v-model="filters.phong_ngu" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none appearance-none cursor-pointer">
                <option value="">Phòng ngủ</option>
                <option value="1">1 PN</option>
                <option value="2">2 PN</option>
                <option value="3">3 PN</option>
                <option value="4">4 PN</option>
                <option value="5">5+ PN</option>
              </select>
            </div>

            <!-- Bathrooms -->
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">bathtub</span>
              <select v-model="filters.phong_tam" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none appearance-none cursor-pointer">
                <option value="">Phòng tắm</option>
                <option value="1">1 PT</option>
                <option value="2">2 PT</option>
                <option value="3">3 PT</option>
                <option value="4">4+ PT</option>
              </select>
            </div>

            <!-- Sort -->
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">sort</span>
              <select v-model="filters.sort" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none appearance-none cursor-pointer">
                <option value="newest">Mới nhất</option>
                <option value="price_asc">Giá tăng dần</option>
                <option value="price_desc">Giá giảm dần</option>
                <option value="area_desc">Diện tích giảm dần</option>
              </select>
            </div>

            <!-- Search Button -->
            <button @click="searchProperties" class="bg-[#0a0e27] hover:bg-[#0d1542] text-white font-bold py-2 px-4 rounded-lg transition-all hover:shadow-lg flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-[18px]">search</span>
              Tìm kiếm
            </button>
          </div>

          <!-- Active Filters -->
          <div v-if="hasActiveFilters" class="flex flex-wrap items-center gap-2">
            <span class="text-sm text-gray-500">Bộ lọc:</span>
            <span v-for="(filter, key) in activeFilters" :key="key" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-semibold border border-blue-200">
              {{ filter.label }}: {{ filter.value }}
              <button @click="removeFilter(key)" class="hover:text-blue-800">
                <span class="material-symbols-outlined text-[14px]">close</span>
              </button>
            </span>
            <button @click="clearAllFilters" class="text-sm text-gray-500 hover:text-gray-700 underline">
              Xóa tất cả
            </button>
          </div>
        </div>
      </div>

      <!-- Results Count -->
      <div class="container mx-auto max-w-7xl px-6 py-6">
        <div class="flex items-center justify-between mb-6">
          <p class="text-gray-600">
            Tìm thấy <span class="font-bold text-[#0a0e27]">{{ totalProperties }}</span> bất động sản
          </p>
          <div class="flex items-center gap-2">
            <button @click="toggleView('grid')" :class="{'bg-blue-600 text-white': viewMode === 'grid', 'bg-gray-100 text-gray-600': viewMode !== 'grid'}" class="p-2 rounded-lg transition-all">
              <span class="material-symbols-outlined text-[20px]">grid_view</span>
            </button>
            <button @click="toggleView('list')" :class="{'bg-blue-600 text-white': viewMode === 'list', 'bg-gray-100 text-gray-600': viewMode !== 'list'}" class="p-2 rounded-lg transition-all">
              <span class="material-symbols-outlined text-[20px]">view_list</span>
            </button>
          </div>
        </div>

        <!-- Properties Grid -->
        <div v-if="properties.length > 0" :class="viewMode === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8' : 'space-y-4'">
          <div
            v-for="bds in properties"
            :key="bds.id"
            @click="viewProperty(bds.id)"
            class="group bg-white rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.04)] hover:shadow-[0_12px_40px_rgba(10,14,39,0.1)] transition-all duration-500 cursor-pointer border border-gray-100 hover:-translate-y-2"
          >
            <!-- Image -->
            <div class="relative h-[240px] overflow-hidden">
              <img
                :src="getImageUrl(bds.hinh_anh?.[0]?.url)"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                :alt="bds.tieu_de"
              />
              <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-md text-[#0a0e27] text-[10px] font-bold uppercase tracking-wider rounded-full">
                {{ bds.loai?.ten_loai || 'BĐS' }}
              </div>
              <div v-if="bds.is_featured" class="absolute top-4 right-4 px-3 py-1 bg-yellow-400 text-white text-[10px] font-bold uppercase tracking-wider rounded-full">
                Nổi bật
              </div>
            </div>

            <!-- Content -->
            <div class="p-6">
              <h3 class="font-['Be_Vietnam_Pro'] font-bold text-[18px] text-[#0a0e27] mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                {{ bds.tieu_de }}
              </h3>
              <p class="text-gray-500 text-sm flex items-center gap-1 mb-4">
                <span class="material-symbols-outlined text-[16px]">location_on</span>
                {{ bds.dia_chi?.ten_quan || bds.location || '—' }}
              </p>

              <!-- Stats -->
              <div class="grid grid-cols-3 gap-3 mb-4 pb-4 border-b border-gray-100">
                <div class="text-center">
                  <span class="material-symbols-outlined text-blue-600 text-[18px] block mb-1">square_foot</span>
                  <p class="text-xs text-gray-500">{{ bds.dien_tich || '—' }} m²</p>
                </div>
                <div class="text-center">
                  <span class="material-symbols-outlined text-blue-600 text-[18px] block mb-1">bed</span>
                  <p class="text-xs text-gray-500">{{ bds.so_phong_ngu || '—' }} PN</p>
                </div>
                <div class="text-center">
                  <span class="material-symbols-outlined text-blue-600 text-[18px] block mb-1">bathtub</span>
                  <p class="text-xs text-gray-500">{{ bds.so_phong_tam || '—' }} PT</p>
                </div>
              </div>

              <!-- Price & Action -->
              <div class="flex items-center justify-between">
                <p class="font-['Be_Vietnam_Pro'] text-xl font-black text-[#0a0e27]">
                  {{ formatPriceFull(bds.gia) }}
                </p>
                <span class="text-blue-600 font-semibold text-sm flex items-center gap-1 group/btn">
                  Chi tiết
                  <span class="material-symbols-outlined text-[16px] group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-16">
          <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">search_off</span>
          <h3 class="text-xl font-bold text-gray-700 mb-2">Không tìm thấy bất động sản</h3>
          <p class="text-gray-500 mb-6">Thử thay đổi bộ lọc hoặc tìm kiếm với từ khóa khác</p>
          <button @click="clearAllFilters" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all">
            Xóa bộ lọc
          </button>
        </div>

        <!-- Pagination -->
        <div v-if="properties.length > 0" class="mt-12 flex items-center justify-center">
          <nav class="flex items-center gap-2">
            <button
              @click="changePage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              <span class="material-symbols-outlined">chevron_left</span>
            </button>

            <button
              v-for="page in totalPages"
              :key="page"
              @click="changePage(page)"
              :class="page === currentPage ? 'bg-[#0a0e27] text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'"
              class="px-4 py-2 rounded-lg font-semibold transition-all min-w-[40px]"
            >
              {{ page }}
            </button>

            <button
              @click="changePage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              <span class="material-symbols-outlined">chevron_right</span>
            </button>
          </nav>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PropertyList',
  data() {
    return {
      loading: false,
      properties: [],
      totalProperties: 0,
      currentPage: 1,
      perPage: 9,
      viewMode: 'grid', // 'grid' or 'list'
      backendUrl: 'http://127.0.0.1:8000',
      apiUrl: 'http://127.0.0.1:8000/api/client',
      filters: {
        tinh: '',
        quan: '',
        loai: '',
        gia: '',
        dien_tich: '',
        phong_ngu: '',
        phong_tam: '',
        sort: 'newest',
        search: ''
      },
      danhSachTinh: [],
      danhSachQuan: [],
      danhSachLoai: []
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.totalProperties / this.perPage);
    },
    hasActiveFilters() {
      return Object.values(this.filters).some(value => value !== '' && value !== 'newest');
    },
    activeFilters() {
      const active = {};
      const filterLabels = {
        tinh: 'Tỉnh',
        quan: 'Quận',
        loai: 'Loại',
        gia: 'Giá',
        dien_tich: 'Diện tích',
        phong_ngu: 'Phòng ngủ',
        phong_tam: 'Phòng tắm'
      };

      for (const [key, value] of Object.entries(this.filters)) {
        if (value && value !== 'newest') {
          active[key] = {
            label: filterLabels[key],
            value: this.getFilterValueLabel(key, value)
          };
        }
      }
      return active;
    }
  },
  async mounted() {
    await Promise.all([
      this.loadProperties(),
      this.loadFilterData()
    ]);
  },
  methods: {
    async loadProperties() {
      this.loading = true;
      try {
        const params = {
          ...this.filters,
          page: this.currentPage,
          per_page: this.perPage
        };

        // Remove empty filters
        Object.keys(params).forEach(key => {
          if (!params[key]) delete params[key];
        });

        const res = await axios.get(`${this.apiUrl}/bat-dong-san`, { params });
        
        if (res.data.status) {
          this.properties = res.data.data.data || res.data.data;
          this.totalProperties = res.data.data.total || this.properties.length;
        }
      } catch (err) {
        console.error('Lỗi load properties:', err);
      } finally {
        this.loading = false;
      }
    },
    async loadFilterData() {
      try {
        const [tinhRes, quanRes, loaiRes] = await Promise.all([
          axios.get(`${this.apiUrl}/tinh`),
          axios.get(`${this.apiUrl}/quan`),
          axios.get(`${this.apiUrl}/loai`)
        ]);

        if (tinhRes.data.status) this.danhSachTinh = tinhRes.data.data;
        if (quanRes.data.status) this.danhSachQuan = quanRes.data.data;
        if (loaiRes.data.status) this.danhSachLoai = loaiRes.data.data;
      } catch (err) {
        console.error('Lỗi load filter data:', err);
      }
    },
    searchProperties() {
      this.currentPage = 1;
      this.loadProperties();
    },
    changePage(page) {
      if (page < 1 || page > this.totalPages) return;
      this.currentPage = page;
      this.loadProperties();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    toggleView(mode) {
      this.viewMode = mode;
    },
    viewProperty(id) {
      this.$router.push(`/chi-tiet-bds/${id}`);
    },
    clearAllFilters() {
      this.filters = {
        tinh: '',
        quan: '',
        loai: '',
        gia: '',
        dien_tich: '',
        phong_ngu: '',
        phong_tam: '',
        sort: 'newest',
        search: ''
      };
      this.currentPage = 1;
      this.loadProperties();
    },
    removeFilter(key) {
      this.filters[key] = '';
      if (key === 'tinh') this.filters.quan = '';
      this.currentPage = 1;
      this.loadProperties();
    },
    getFilterValueLabel(key, value) {
      if (key === 'tinh') {
        const tinh = this.danhSachTinh.find(t => t.id == value);
        return tinh?.ten_tinh || value;
      }
      if (key === 'quan') {
        const quan = this.danhSachQuan.find(q => q.id == value);
        return quan?.ten_quan || value;
      }
      if (key === 'loai') {
        const loai = this.danhSachLoai.find(l => l.id == value);
        return loai?.ten_loai || value;
      }
      if (key === 'gia') {
        const labels = {
          '0-1': 'Dưới 1 tỷ',
          '1-3': '1 - 3 tỷ',
          '3-5': '3 - 5 tỷ',
          '5-10': '5 - 10 tỷ',
          '10-999999': 'Trên 10 tỷ'
        };
        return labels[value] || value;
      }
      if (key === 'dien_tich') {
        const labels = {
          '0-50': 'Dưới 50m²',
          '50-100': '50 - 100m²',
          '100-200': '100 - 200m²',
          '200-999999': 'Trên 200m²'
        };
        return labels[value] || value;
      }
      return value;
    },
    getImageUrl(url) {
      if (!url) return 'https://via.placeholder.com/800x600?text=No+Image';
      if (url.startsWith('http')) return url;
      return `${this.backendUrl}/storage/${url}`;
    },
    formatPriceFull(gia) {
      if (!gia && gia !== 0) return 'Liên hệ';
      return new Intl.NumberFormat('vi-VN').format(gia) + ' VNĐ';
    }
  },
  watch: {
    'filters.tinh'() {
      this.filters.quan = '';
    }
  }
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>