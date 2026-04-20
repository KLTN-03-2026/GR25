<template>
  <div class="bg-[#f8fafc] min-h-screen font-['Inter']" style="contain: layout style;">
    
    <!-- Content -->
    <div>
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
                <option v-for="tinh in danhSachTinh" :key="tinh.id" :value="tinh.id">{{ tinh.ten }}</option>
              </select>
            </div>

            <!-- District -->
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">map</span>
              <select v-model="filters.quan" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none appearance-none cursor-pointer">
                <option value="">Tất cả quận/huyện</option>
                <option v-for="quan in danhSachQuan" :key="quan.id" :value="quan.id">{{ quan.ten }}</option>
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
          <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
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

        <!-- ✅ Properties Grid with Loading Overlay (giống PropertyDetail) -->
        <div class="relative min-h-[400px]" style="contain: content;">
          
          <!-- 🔥 Loading Overlay (chỉ trong khu vực list, không chặn filter) -->
          <transition name="fade-fast">
            <div 
              v-show="loading && properties.length > 0" 
              class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-10 pointer-events-none"
              aria-hidden="true"
              style="will-change: opacity;"
            >
              <div class="animate-spin h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full" style="will-change: transform;"></div>
            </div>
          </transition>

          <!-- Grid/List Content -->
          <transition-group 
            name="list-fade" 
            tag="div" 
            :class="viewMode === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8' : 'space-y-4'"
          >
            <div
              v-for="bds in properties"
              :key="bds.id"
              @click="viewProperty(bds.id)"
              class="group bg-white rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.04)] hover:shadow-[0_12px_40px_rgba(10,14,39,0.1)] transition-all duration-500 cursor-pointer border border-gray-100 hover:-translate-y-2"
            >
              <!-- Image -->
              <div class="relative h-[240px] overflow-hidden">
                <img
                  :src="bds.image"
                  class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                  :alt="bds.tieu_de"
                  @error="handleImageError"
                  loading="lazy"
                />
                
                <!-- Badge Loại BĐS -->
                <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-md text-[#0a0e27] text-[10px] font-bold uppercase tracking-wider rounded-full">
                  {{ bds.loai?.ten_loai || 'BĐS' }}
                </div>
                
                <!-- Badge Nổi bật -->
                <div v-if="bds.is_featured" class="absolute top-4 right-16 px-3 py-1 bg-yellow-400 text-white text-[10px] font-bold uppercase tracking-wider rounded-full">
                  Nổi bật
                </div>

                <!-- ❤️ Icon Trái Tim -->
                <button
                  @click.stop="toggleFavorite(bds.id, $event)"
                  class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/95 backdrop-blur-md shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 group/btn"
                  :class="bds.isFavorite ? 'bg-gradient-to-br from-pink-500 to-rose-500' : ''"
                  title="Yêu thích"
                >
                  <span 
                    class="material-symbols-outlined text-lg transition-all duration-300"
                    :class="bds.isFavorite ? 'text-white fill-current' : 'text-gray-400 group-hover/btn:text-pink-500'"
                  >
                    favorite
                  </span>
                </button>
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
                  <span 
                    @click.stop="viewProperty(bds.id)"
                    class="text-blue-600 font-semibold text-sm flex items-center gap-1 group/btn cursor-pointer relative"
                  >
                    Chi tiết
                    <span class="material-symbols-outlined text-[16px] group-hover/btn:translate-x-1 transition-transform">
                      {{ isAuthenticated() ? 'arrow_forward' : 'lock' }}
                    </span>
                    <!-- Tooltip khi chưa login -->
                    <span v-if="!isAuthenticated()" class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover/btn:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10 shadow-lg">
                      Đăng nhập để xem chi tiết
                      <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-800"></span>
                    </span>
                  </span>
                </div>
              </div>
            </div>
          </transition-group>

          <!-- ✅ Skeleton Loading khi chưa có data lần đầu -->
          <div v-if="loading && properties.length === 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="i in 6" :key="i" class="bg-white rounded-2xl overflow-hidden border border-gray-100 animate-pulse">
              <div class="h-[240px] bg-gray-200"></div>
              <div class="p-6 space-y-4">
                <div class="h-5 bg-gray-200 rounded w-3/4"></div>
                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                <div class="grid grid-cols-3 gap-3">
                  <div class="h-10 bg-gray-200 rounded"></div>
                  <div class="h-10 bg-gray-200 rounded"></div>
                  <div class="h-10 bg-gray-200 rounded"></div>
                </div>
                <div class="h-6 bg-gray-200 rounded w-1/3"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="properties.length === 0 && !loading && searched" class="text-center py-16">
          <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">search_off</span>
          <h3 class="text-xl font-bold text-gray-700 mb-2">Không tìm thấy bất động sản</h3>
          <p class="text-gray-500 mb-6">Thử thay đổi bộ lọc hoặc tìm kiếm với từ khóa khác</p>
          <button @click="clearAllFilters" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all">
            Xóa bộ lọc
          </button>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1 && !loading" class="mt-12 flex items-center justify-center">
          <nav class="flex items-center gap-2">
            <button
              @click="changePage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              <span class="material-symbols-outlined">chevron_left</span>
            </button>

            <button
              v-for="page in visiblePages"
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
    </div>

    <!-- 🍞 Toast Notification -->
    <transition name="toast-slide">
      <div 
        v-if="toast.visible" 
        class="fixed top-5 right-5 z-[9999]"
        :class="toast.type === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-500' : 'bg-gradient-to-r from-amber-500 to-orange-500'"
      >
        <div class="flex items-center gap-3 px-5 py-3.5 text-white rounded-2xl shadow-2xl backdrop-blur-md">
          <span class="material-symbols-outlined text-xl">
            {{ toast.type === 'error' ? 'error' : 'warning' }}
          </span>
          <span class="font-medium text-sm">{{ toast.message }}</span>
          <button @click="hideToast" class="ml-2 hover:opacity-80 transition-opacity">
            <span class="material-symbols-outlined text-lg">close</span>
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PropertyList',
  data() {
    return {
      loading: false, // ✅ Thêm lại loading state
      properties: [],
      totalProperties: 0,
      currentPage: 1,
      perPage: 9,
      viewMode: 'grid',
      apiUrl: 'http://127.0.0.1:8000/api/client',
      searched: false,
      filters: {
        tinh: '', quan: '', loai: '', gia: '', dien_tich: '', phong_ngu: '', phong_tam: '', sort: 'newest', search: ''
      },
      danhSachTinh: [], danhSachQuan: [], danhSachLoai: [],
      toast: { visible: false, message: '', type: 'warning', timer: null },
      defaultImage: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800',
      searchTimer: null,
      lastFetchParams: null, lastFetchTime: 0, FETCH_COOLDOWN: 400
    };
  },
  computed: {
    totalPages() { return Math.ceil(this.totalProperties / this.perPage); },
    visiblePages() {
      const pages = [], max = 5;
      let start = Math.max(1, this.currentPage - 2), end = Math.min(this.totalPages, start + max - 1);
      start = Math.max(1, end - max + 1);
      for (let i = start; i <= end; i++) pages.push(i);
      return pages;
    },
    hasActiveFilters() { return Object.values(this.filters).some(v => v !== '' && v !== 'newest'); },
    activeFilters() {
      const active = {}, labels = { tinh:'Tỉnh', quan:'Quận', loai:'Loại', gia:'Giá', dien_tich:'Diện tích', phong_ngu:'Phòng ngủ', phong_tam:'Phòng tắm' };
      for (const [k,v] of Object.entries(this.filters)) {
        if (v && v !== 'newest') active[k] = { label: labels[k], value: this.getFilterValueLabel(k,v) };
      }
      return active;
    }
  },
  async mounted() {
    await this.loadFilterData();
    if (this.filters.tinh) await this.loadQuan(this.filters.tinh);
    await this.loadProperties();
  },
  beforeUnmount() {
    if (this.toast.timer) clearTimeout(this.toast.timer);
    if (this.searchTimer) clearTimeout(this.searchTimer);
  },
  methods: {
    showToast(msg, type='warning') {
      if (this.toast.timer) clearTimeout(this.toast.timer);
      this.toast = { visible:true, message:msg, type, timer: setTimeout(()=>this.hideToast(),3000) };
    },
    hideToast() { this.toast.visible=false; if(this.toast.timer){clearTimeout(this.toast.timer);this.toast.timer=null;} },
    isAuthenticated() { return !!(localStorage.getItem('auth_token') && localStorage.getItem('user_type')==='khach-hang'); },
    requireAuth(redirectUrl=null) {
      if (!this.isAuthenticated()) {
        this.showToast('Vui lòng đăng nhập để tiếp tục','warning');
        setTimeout(()=>this.$router.push({path:'/khach-hang/dang-nhap',query:{redirect:redirectUrl||this.$route.fullPath}}),800);
        return false;
      } return true;
    },
    viewProperty(id) { if(!this.requireAuth(`/khach-hang/chi-tiet-bat-dong-san/${id}`))return; this.$router.push(`/khach-hang/chi-tiet-bat-dong-san/${id}`); },
    toggleFavorite(pid,ev) {
      ev.stopPropagation(); if(!this.requireAuth())return;
      const p=this.properties.find(x=>x.id===pid); if(p){ p.isFavorite=!p.isFavorite; this.showToast(p.isFavorite?'Đã thêm vào yêu thích':'Đã xóa khỏi yêu thích','success'); }
    },
    handleImageError(e){ if(e.target.src!==this.defaultImage)e.target.src=this.defaultImage; },
    triggerAutoSearch() {
      if(this.searchTimer)clearTimeout(this.searchTimer);
      this.searchTimer=setTimeout(()=>{this.currentPage=1;this.loadProperties();},500);
    },
    async loadProperties() {
      // Guard + cache
      const now=Date.now(), key=JSON.stringify({...this.filters,page:this.currentPage});
      if(key===this.lastFetchParams && now-this.lastFetchTime<this.FETCH_COOLDOWN) return;
      this.lastFetchParams=key; this.lastFetchTime=now; this.searched=true;
      
      // ✅ Chỉ set loading=true nếu đã có data (hiển thị overlay nhẹ)
      // Nếu chưa có data lần đầu: giữ loading để hiện skeleton
      if (this.properties.length > 0) this.loading = true;

      try {
        const params={page:this.currentPage,per_page:this.perPage};
        if(this.filters.tinh)params.tinh_id=this.filters.tinh;
        if(this.filters.quan)params.quan_id=this.filters.quan;
        if(this.filters.loai)params.loai_id=this.filters.loai;
        if(this.filters.gia){const[mn,mx]=this.filters.gia.split('-');params.gia_min=Number(mn)*1e9;params.gia_max=Number(mx)*1e9;}
        if(this.filters.dien_tich){const[mn,mx]=this.filters.dien_tich.split('-');params.dt_min=Number(mn);params.dt_max=Number(mx);}
        if(this.filters.phong_ngu)params.phong_ngu=this.filters.phong_ngu;
        if(this.filters.phong_tam)params.phong_tam=this.filters.phong_tam;
        if(this.filters.sort)params.sort=this.filters.sort;

        const res=await axios.get(`${this.apiUrl}/bat-dong-san`,{params});
        if(res.data?.status){
          const raw=res.data.data?.data||res.data.data||[];
          this.properties=raw.map(it=>{
            let img=this.defaultImage;
            if(it.anh_dai_dien?.url)img=this.getImageUrl(it.anh_dai_dien.url);
            else if(it.hinh_anh?.[0]?.url)img=this.getImageUrl(it.hinh_anh[0].url);
            else if(it.anh_dai_dien_url)img=this.getImageUrl(it.anh_dai_dien_url);
            return {...it,image:img,isFavorite:false};
          });
          this.totalProperties=res.data.data?.total||this.properties.length;
        }
      } catch(err){
        console.error('Load error:',err);
        if(err.response?.status===401){ this.showToast('Phiên đăng nhập đã hết hạn','error'); setTimeout(()=>this.$router.push('/khach-hang/dang-nhap'),800); }
      } finally {
        // ✅ Dùng nextTick để đảm bảo DOM update xong mới tắt loading
        this.$nextTick(()=>{ this.loading=false; });
      }
    },
    async loadFilterData(){
      try{
        const [t,l]=await Promise.all([axios.get('http://127.0.0.1:8000/api/tinh-thanh'),axios.get(`${this.apiUrl}/loai-bat-dong-san`)]);
        if(t.data?.status)this.danhSachTinh=t.data.data||[];
        if(l.data?.status)this.danhSachLoai=l.data.data||[];
      }catch(e){console.error('Filter load error:',e);}
    },
    async loadQuan(tinh_id){
      if(!tinh_id){this.danhSachQuan=[];return;}
      try{
        const r=await axios.get('http://127.0.0.1:8000/api/quan-huyen',{params:{tinh_id}});
        if(r.data?.status)this.danhSachQuan=r.data.data||[];
      }catch(e){console.error('Load quận error:',e);}
    },
    getImageUrl(url){ if(!url)return this.defaultImage; if(url.startsWith('http'))return url; return `${this.apiUrl.replace('/api/client','')}/storage/${url}`; },
    changePage(p){ if(p<1||p>this.totalPages)return; this.currentPage=p; this.loadProperties(); window.scrollTo({top:0,behavior:'smooth'}); },
    toggleView(m){this.viewMode=m;},
    clearAllFilters(){ this.filters={tinh:'',quan:'',loai:'',gia:'',dien_tich:'',phong_ngu:'',phong_tam:'',sort:'newest',search:''}; this.currentPage=1; this.loadProperties(); },
    removeFilter(k){ this.filters[k]=''; if(k==='tinh')this.filters.quan=''; this.currentPage=1; this.loadProperties(); },
    getFilterValueLabel(k,v){
      if(k==='tinh'){const t=this.danhSachTinh.find(x=>x.id==v);return t?.ten||v;}
      if(k==='quan'){const q=this.danhSachQuan.find(x=>x.id==v);return q?.ten||v;}
      if(k==='loai'){const l=this.danhSachLoai.find(x=>x.id==v);return l?.ten_loai||v;}
      if(k==='gia'){const lb={'0-1':'Dưới 1 tỷ','1-3':'1 - 3 tỷ','3-5':'3 - 5 tỷ','5-10':'5 - 10 tỷ','10-999999':'Trên 10 tỷ'};return lb[v]||v;}
      if(k==='dien_tich'){const lb={'0-50':'Dưới 50m²','50-100':'50 - 100m²','100-200':'100 - 200m²','200-999999':'Trên 200m²'};return lb[v]||v;}
      return v;
    },
    formatPriceFull(g){ if(g==null)return'Liên hệ'; return new Intl.NumberFormat('vi-VN').format(g)+' VNĐ'; }
  },
  watch:{
    'filters.tinh':async function(v){this.filters.quan='';await this.loadQuan(v);this.triggerAutoSearch();},
    'filters.quan'(){this.triggerAutoSearch();},'filters.loai'(){this.triggerAutoSearch();},'filters.gia'(){this.triggerAutoSearch();},
    'filters.dien_tich'(){this.triggerAutoSearch();},'filters.phong_ngu'(){this.triggerAutoSearch();},'filters.phong_tam'(){this.triggerAutoSearch();},'filters.sort'(){this.triggerAutoSearch();}
  }
};
</script>

<style scoped>
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}

/* ✅ Fast fade for loading overlay (giống PropertyDetail) */
.fade-fast-enter-active,.fade-fast-leave-active{transition:opacity 0.15s ease-out;}
.fade-fast-enter-from,.fade-fast-leave-to{opacity:0;}

/* ✅ Smooth list transition */
.list-fade-move,.list-fade-enter-active,.list-fade-leave-active{transition:all 0.3s ease;}
.list-fade-enter-from,.list-fade-leave-to{opacity:0;transform:translateY(10px);}
.list-fade-leave-active{position:absolute;}

/* 🍞 Toast */
.toast-slide-enter-active,.toast-slide-leave-active{transition:all 0.25s ease;}
.toast-slide-enter-from{transform:translateX(120%);opacity:0;}
.toast-slide-leave-to{transform:translateX(120%);opacity:0;}

/* Skeleton pulse animation */
@keyframes skeletonPulse{0%{opacity:1}50%{opacity:0.6}100%{opacity:1}}
.animate-pulse{animation:skeletonPulse 1.5s ease-in-out infinite;}

/* Reduce motion */
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:0.01ms!important;transition-duration:0.01ms!important;}}
</style>