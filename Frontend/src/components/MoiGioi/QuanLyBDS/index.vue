<template>
  <div>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-8 font-sans text-slate-800">
    <!-- Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
      <div class="space-y-2">
        <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
          Bất Động Sản Của Tôi
        </h1>
        <p class="text-slate-500 font-medium">Quản lý danh sách bất động sản của bạn</p>
      </div>
      
      <button @click="$router.push('/moi-gioi/dang-tin')"
        class=" relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-2xl flex items-center gap-3 shadow-xl shadow-blue-500/30 transition-all duration-300 hover:scale-105 hover:shadow-2xl active:scale-95">
        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        <span class="font-bold text-base relative z-10">Đăng Tin Mới</span>
      </button>
    </header>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
      <div class="bg-white p-6 rounded-3xl shadow-lg shadow-blue-900/5 border border-blue-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 group">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
          <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">TỔNG</span>
        </div>
        <p class="text-4xl font-black text-slate-800 mb-1">{{ stats.total }}</p>
        <p class="text-sm font-semibold text-slate-400">Bất Động Sản</p>
      </div>

      <div class="bg-white p-6 rounded-3xl shadow-lg shadow-emerald-900/5 border border-emerald-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 group">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">ĐÃ DUYỆT</span>
        </div>
        <p class="text-4xl font-black text-slate-800 mb-1">{{ stats.approved }}</p>
        <p class="text-sm font-semibold text-slate-400">Tin đã duyệt</p>
      </div>

      <div class="bg-white p-6 rounded-3xl shadow-lg shadow-amber-900/5 border border-amber-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 group">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <span class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">CHỜ DUYỆT</span>
        </div>
        <p class="text-4xl font-black text-slate-800 mb-1">{{ stats.pending }}</p>
        <p class="text-sm font-semibold text-slate-400">Đang chờ xử lý</p>
      </div>

      <div class="bg-white p-6 rounded-3xl shadow-lg border border-slate-200 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 group">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-slate-400 to-slate-500 rounded-2xl flex items-center justify-center shadow-lg shadow-slate-400/30 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-full">ĐÃ BÁN</span>
        </div>
        <p class="text-4xl font-black text-slate-800 mb-1">{{ stats.sold }}</p>
        <p class="text-sm font-semibold text-slate-400">Giao dịch thành công</p>
      </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white/80 backdrop-blur-xl p-4 rounded-3xl shadow-lg border border-white/50 mb-8">
      <div class="flex flex-col lg:flex-row gap-4">
        <div class="relative flex-1">
          <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input v-model="searchKeyword" type="text" placeholder="Tìm kiếm theo tên, địa chỉ hoặc giá..."
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl py-3.5 pl-12 pr-4 text-sm font-medium text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all placeholder:text-slate-400" />
        </div>

        <div class="flex gap-3">
          <div class="relative min-w-[180px]">
            <select v-model="filterStatus"
              class="appearance-none w-full bg-slate-50 border-2 border-slate-100 rounded-2xl pl-4 pr-12 py-3.5 text-sm font-semibold text-slate-700 outline-none cursor-pointer focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
              <option value="">Tất cả trạng thái</option>
              <option value="1">Đã duyệt</option>
              <option value="0">Chờ duyệt</option>
            </select>
            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
              <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </div>

          <button v-if="searchKeyword || filterStatus" @click="resetFilters"
            class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-bold transition-all duration-200 hover:scale-105 active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Reset
          </button>
        </div>
      </div>
    </div>

    <div class="space-y-6 mb-10">
      <!-- Messages -->
      <div v-if="errorMessage" class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3 animate-pulse">
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-semibold">{{ errorMessage }}</span>
      </div>

      <div v-if="successMessage" class="bg-gradient-to-r from-emerald-50 to-green-50 border-l-4 border-emerald-500 text-emerald-700 px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3 animate-pulse">
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-semibold">{{ successMessage }}</span>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center py-16">
        <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-4"></div>
        <p class="text-slate-500 font-semibold animate-pulse">Đang tải dữ liệu...</p>
      </div>

      <!-- Result Info -->
      <div v-if="!loading && (searchKeyword || filterStatus) && filteredProperties.length > 0"
        class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 text-blue-700 px-6 py-3 rounded-2xl text-sm font-bold flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Tìm thấy {{ filteredProperties.length }} kết quả phù hợp
      </div>

      <!-- Property List -->
      <div v-for="item in filteredProperties" :key="item.id"
        class="group bg-white rounded-3xl p-6 shadow-lg border border-slate-100 hover:shadow-2xl hover:border-blue-200 transition-all duration-300 hover:-translate-y-1">
        
        <div class="flex flex-col lg:flex-row gap-6 items-start">
          <!-- Image -->
          <div class="relative w-full lg:w-72 h-48 shrink-0 rounded-2xl overflow-hidden shadow-lg group-hover:shadow-xl transition-shadow">
            <img :src="item.image" :alt="item.title" 
              class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
              @error="(e) => { e.target.src = 'https://via.placeholder.com/400x250?text=No+Image'; }" />
            
            <div v-if="item.premium"
              class="absolute top-4 left-4 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg shadow-orange-500/30">
              ⭐ PREMIUM
            </div>

            <div v-if="item.status === 'Đã bán'"
              class="absolute inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm">
              <span class="bg-white text-slate-800 px-6 py-2 text-sm font-black rounded-full shadow-xl">ĐÃ BÁN</span>
            </div>
          </div>

          <!-- Content -->
          <div class="flex-1 space-y-3">
            <div>
              <h3 class="text-xl font-bold text-slate-800 mb-2 leading-tight group-hover:text-blue-600 transition-colors">
                {{ item.title }}
              </h3>
              <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ item.dia_chi_id }}
              </div>
            </div>

            <div class="flex flex-wrap gap-3">
              <span v-if="item.dien_tich" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                {{ item.dien_tich }} m²
              </span>
              <span v-if="item.so_phong_ngu" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{ item.so_phong_ngu }} PN
              </span>
              <span v-if="item.so_phong_tam" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-lg text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
                {{ item.so_phong_tam }} WC
              </span>
            </div>
          </div>

          <!-- Price & Status -->
          <div class="flex flex-col items-end gap-4 w-full lg:w-auto">
            <div class="text-right">
              <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Giá bán</p>
              <p class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                {{ item.price }}
              </p>
            </div>

            <div class="flex items-center gap-3">
              <span :class="statusClasses(item.status)" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold shadow-sm">
                <span class="w-2 h-2 rounded-full bg-current"></span>
                {{ item.status }}
              </span>
            </div>

            <!-- ✅ ACTION BUTTONS - ĐÃ FIX: Thay Bootstrap Icons bằng SVG -->
            <div class="flex gap-2">
              <!-- Edit Button -->
              <button @click="handleEdit(item)" title="Chỉnh sửa" 
                class=" group/btn w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 hover:scale-110 transition-all duration-200">
                <svg class="w-5 h-5 group-hover/btn:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
              </button>

              <!-- Delete Button -->
              <button @click="handleDelete(item)" title="Xóa" 
                class="group/btn w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 hover:scale-110 transition-all duration-200">
                <svg class="w-5 h-5 group-hover/btn:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="!loading && filteredProperties.length == 0" class="text-center py-16 bg-white rounded-3xl shadow-lg border border-slate-100">
        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <p v-if="searchKeyword || filterStatus" class="text-xl font-bold text-slate-600 mb-2">
          Không tìm thấy kết quả
        </p>
        <p v-else class="text-xl font-bold text-slate-600 mb-2">
          Chưa có bất động sản nào
        </p>
        <p class="text-slate-400 font-medium">
          {{ searchKeyword || filterStatus ? 'Thử thay đổi từ khóa tìm kiếm' : 'Hãy bắt đầu đăng tin mới ngay!' }}
        </p>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" class="flex justify-center items-center gap-3 mt-10 flex-wrap">
        <button @click="loadBatDongSan(pagination.current_page - 1)" :disabled="pagination.current_page === 1"
          class="px-6 py-3 rounded-xl bg-white border-2 border-slate-200 text-slate-700 font-bold hover:border-blue-500 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 hover:scale-105 active:scale-95 flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Trước
        </button>

        <div class="flex gap-2">
          <button v-for="page in pagination.last_page" :key="page" @click="loadBatDongSan(page)" 
            :class="[
              'w-12 h-12 rounded-xl font-bold transition-all duration-200 hover:scale-110 active:scale-95',
              page === pagination.current_page
                ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30'
                : 'bg-white border-2 border-slate-200 text-slate-700 hover:border-blue-500 hover:text-blue-600'
            ]">
            {{ page }}
          </button>
        </div>

        <button @click="loadBatDongSan(pagination.current_page + 1)"
          :disabled="pagination.current_page === pagination.last_page"
          class="px-6 py-3 rounded-xl bg-white border-2 border-slate-200 text-slate-700 font-bold hover:border-blue-500 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 hover:scale-105 active:scale-95 flex items-center gap-2">
          Sau
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="cancelEdit"></div>
    <div class="relative bg-white rounded-[32px] w-full max-w-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-[slideUp_0.3s_ease-out]">
      
      <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
        <div>
          <h2 class="text-2xl font-black text-slate-800">Chỉnh sửa bài đăng</h2>
          <p class="text-sm text-slate-500 mt-1">Cập nhật thông tin bất động sản</p>
        </div>
        <button @click="cancelEdit" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div class="px-8 py-6 overflow-y-auto custom-scrollbar bg-slate-50/50">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Tiêu đề</label>
            <input v-model="editingProperty.title" type="text"
              class="w-full px-5 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-semibold text-slate-800"
              placeholder="Nhập tiêu đề bất động sản..." />
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Địa chỉ</label>
            <input v-model="editingProperty.dia_chi_id" type="text"
              class="w-full px-5 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-semibold text-slate-800"
              placeholder="Nhập địa chỉ chi tiết..." />
          </div>

          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Giá (VNĐ)</label>
            <input v-model="editingProperty.gia" type="number"
              class="w-full px-5 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-bold text-slate-800" />
          </div>

          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Diện tích (m²)</label>
            <input v-model="editingProperty.dien_tich" type="number"
              class="w-full px-5 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-semibold text-slate-800" />
          </div>

          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Phòng ngủ</label>
            <input v-model.number="editingProperty.so_phong_ngu" type="number"
              class="w-full px-5 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-semibold text-slate-800" />
          </div>

          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Phòng tắm</label>
            <input v-model.number="editingProperty.so_phong_tam" type="number"
              class="w-full px-5 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-semibold text-slate-800" />
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Mô tả</label>
            <textarea v-model="editingProperty.mo_ta" rows="4"
              class="w-full px-5 py-3.5 bg-white border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all resize-none text-slate-700"
              placeholder="Mô tả chi tiết về bất động sản..."></textarea>
          </div>
        </div>
      </div>

      <div class="px-8 py-6 bg-slate-50 border-t border-slate-200 flex gap-4">
        <button @click="cancelEdit"
          class="flex-1 h-14 border-2 border-slate-300 rounded-xl font-bold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
          Hủy bỏ
        </button>
        <button @click="submitEdit" :disabled="isSubmitting"
          class="flex-1 h-14 rounded-xl font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
          <span v-if="isSubmitting">
            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Đang lưu...
          </span>
          <span v-else>Lưu thay đổi</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="cancelDelete"></div>
    <div class="relative bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden animate-[scaleIn_0.3s_ease-out]">
      
      <div class="px-8 py-8 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-red-100 to-rose-100 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        </div>
        
        <h2 class="text-2xl font-black text-slate-800 mb-2">Xác nhận xóa</h2>
        <p class="text-slate-500 mb-6">Bạn có chắc chắn muốn xóa bài đăng này?</p>
        
        <div class="bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-200">
          <p class="text-sm font-bold text-slate-700 mb-1">"{{ deleteProperty?.title }}"</p>
          <p class="text-xs text-red-600 font-semibold">⚠️ Hành động này không thể hoàn tác</p>
        </div>

        <div class="flex gap-3">
          <button @click="cancelDelete" :disabled="isDeleting"
            class="flex-1 px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all duration-200 disabled:opacity-50">
            Hủy
          </button>
          <button @click="confirmDelete" :disabled="isDeleting"
            class="flex-1 px-6 py-3.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-bold rounded-xl shadow-lg shadow-red-500/30 hover:shadow-xl transition-all duration-200 disabled:opacity-50 flex items-center justify-center gap-2">
            <span v-if="isDeleting">
              <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Đang xóa...
            </span>
            <span v-else>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
              Xóa
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import api from "@/axios/config.js";

const router = useRouter();

const properties = ref([]);
const loading = ref(false);
const searchKeyword = ref("");
const filterStatus = ref("");
const stats = ref({
  total: 0,
  approved: 0,
  pending: 0,
  sold: 0,
});
const errorMessage = ref("");
const successMessage = ref("");
const isDeleting = ref(false);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 5,
  total: 0,
});

const showDeleteModal = ref(false);
const showEditModal = ref(false);
const deleteProperty = ref(null);
const editingProperty = ref(null);
const isSubmitting = ref(false);

const token = localStorage.getItem("auth_token") || localStorage.getItem("token");

const loadBatDongSan = async (page = 1) => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const res = await api.get(`/moi-gioi/bds/data?page=${page}&per_page=5`);

    if (res.data.status) {
      const result = res.data.data;
      const items = result.data || [];

      properties.value = items.map((item) => ({
        id: item.id,
        title: item.tieu_de,
        dia_chi_id: formatDiaChi(item),
        price: Number(item.gia).toLocaleString("vi-VN") + " VNĐ",
        status: item.is_duyet == 1 ? "Đã duyệt" : "Chờ duyệt",
        premium: item.is_noi_bat == 1,
        image: getImageUrl(item),
        dien_tich: item.dien_tich,
        so_phong_ngu: item.so_phong_ngu,
        so_phong_tam: item.so_phong_tam,
        raw: item,
      }));

      pagination.value = {
        current_page: result.current_page,
        last_page: result.last_page,
        per_page: result.per_page,
        total: result.total,
      };

      calculateStats(result);
      loadStats();
    }
  } catch (error) {
    errorMessage.value = "Lỗi tải dữ liệu";
  } finally {
    loading.value = false;
  }
};

const loadStats = async () => {
  try {
    const res = await api.get(`/moi-gioi/bds/data?page=1&per_page=1000`);

    if (res.data.status && res.data.data) {
      const result = res.data.data;
      const items = result.data || [];

      const totalApproved = items.filter((i) => i.is_duyet == 1).length;
      const totalPending = items.filter((i) => i.is_duyet == 0).length;

      stats.value = {
        total: result.total || items.length,
        approved: totalApproved,
        pending: totalPending,
        sold: 0,
      };
    }
  } catch (error) {
    console.log("Lỗi load stats:", error.message);
  }
};

const formatDiaChi = (item) => {
  if (!item.dia_chi) {
    return `Địa chỉ ID: ${item.dia_chi_id}`;
  }

  const quan = item.dia_chi?.quan?.ten || item.dia_chi?.quan?.ten_quan || "Quận/Huyện";
  const tinh = item.dia_chi?.tinh?.ten || item.dia_chi?.tinh?.ten_tinh || "Tỉnh/TP";
  const diaChi = item.dia_chi?.dia_chi_chi_tiet || "";

  if (diaChi) {
    return `${diaChi}, ${quan}, ${tinh}`;
  }

  return `${quan}, ${tinh}`;
};

const getImageUrl = (item) => {
  const baseUrl = "http://127.0.0.1:8000/storage/";

  if (item.anh_dai_dien_url) {
    if (item.anh_dai_dien_url.startsWith("http")) {
      return item.anh_dai_dien_url;
    }
    return baseUrl + item.anh_dai_dien_url;
  }

  if (item.hinh_anh?.length > 0) {
    const anhDaiDien = item.hinh_anh.find((img) => img.is_anh_dai_dien === true);
    if (anhDaiDien) {
      return baseUrl + anhDaiDien.url;
    }
    return baseUrl + item.hinh_anh[0].url;
  }

  if (item.anh_dai_dien && item.anh_dai_dien.url) {
    return baseUrl + item.anh_dai_dien.url;
  }

  return "/no-image.png";
};

const calculateStats = (result) => {
  stats.value = {
    total: result.total || 0,
    approved: result.total_approved || result.tong_da_duyet || 0,
    pending: result.total_pending || result.tong_cho_duyet || 0,
    sold: 0,
  };
};

const filteredProperties = computed(() => {
  return properties.value.filter((item) => {
    if (filterStatus.value) {
      if (filterStatus.value === "1" && item.status !== "Đã duyệt") {
        return false;
      }
      if (filterStatus.value === "0" && item.status !== "Chờ duyệt") {
        return false;
      }
    }

    if (searchKeyword.value.trim()) {
      const keyword = searchKeyword.value.toLowerCase();
      return (
        item.title.toLowerCase().includes(keyword) ||
        item.dia_chi_id.toLowerCase().includes(keyword) ||
        item.price.includes(searchKeyword.value)
      );
    }

    return true;
  });
});

const handleEdit = (property) => {
  editingProperty.value = {
    id: property.id,
    title: property.title,
    dia_chi_id: property.dia_chi_id,
    gia: parseInt(property.raw?.gia) || 0,
    dien_tich: parseInt(property.raw?.dien_tich) || 0,
    so_phong_ngu: parseInt(property.raw?.so_phong_ngu) || 0,
    so_phong_tam: parseInt(property.raw?.so_phong_tam) || 0,
    mo_ta: property.raw?.mo_ta || "",
  };
  showEditModal.value = true;
};

const submitEdit = async () => {
  if (!editingProperty.value.title.trim()) {
    alert("⚠️ Vui lòng nhập tiêu đề");
    return;
  }

  isSubmitting.value = true;
  errorMessage.value = "";

  try {
    const updatePayload = {
      id: editingProperty.value.id,
      tieu_de: editingProperty.value.title,
      gia: parseInt(editingProperty.value.gia) || 0,
      dien_tich: parseInt(editingProperty.value.dien_tich) || 0,
      so_phong_ngu: parseInt(editingProperty.value.so_phong_ngu),
      so_phong_tam: parseInt(editingProperty.value.so_phong_tam),
      mo_ta: editingProperty.value.mo_ta,
    };

    const res = await api.post("/moi-gioi/bds/update", updatePayload);

    if (res.data.status) {
      successMessage.value = "✅ Cập nhật bài đăng thành công! Đang chờ duyệt lại...";
      showEditModal.value = false;

      setTimeout(() => {
        loadBatDongSan();
        loadStats();
        successMessage.value = "";
      }, 2000);
    } else {
      errorMessage.value = `❌ ${res.data.message || 'Cập nhật thất bại'}`;
    }
  } catch (error) {
    console.error("Lỗi khi cập nhật:", error);
    const message = error.response?.data?.message || error.message || "Lỗi không xác định";
    errorMessage.value = `❌ Cập nhật thất bại: ${message}`;
  } finally {
    isSubmitting.value = false;
  }
};

const cancelEdit = () => {
  showEditModal.value = false;
  editingProperty.value = null;
};

const handleDelete = (property) => {
  deleteProperty.value = property;
  showDeleteModal.value = true;
};

const confirmDelete = async () => {
  isDeleting.value = true;
  errorMessage.value = "";
  successMessage.value = "";

  try {
    const deletePayload = { id: deleteProperty.value.id };
    const res = await api.post("/moi-gioi/bds/delete", deletePayload);

    if (res.data.status) {
      successMessage.value = "✅ Xóa bài đăng thành công!";
      showDeleteModal.value = false;

      setTimeout(() => {
        loadBatDongSan();
        loadStats();
        successMessage.value = "";
      }, 1500);
    } else {
      errorMessage.value = `❌ ${res.data.message || 'Xóa thất bại'}`;
    }
  } catch (error) {
    console.error("Lỗi khi xóa:", error);
    const message = error.response?.data?.message || error.message || "Lỗi không xác định";
    errorMessage.value = `❌ Xóa thất bại: ${message}`;
  } finally {
    isDeleting.value = false;
  }
};

const cancelDelete = () => {
  showDeleteModal.value = false;
  deleteProperty.value = null;
};

const statusClasses = (status) => {
  switch (status) {
    case "Đã duyệt":
      return "bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-700 border border-emerald-200";
    case "Chờ duyệt":
      return "bg-gradient-to-r from-amber-100 to-orange-100 text-amber-700 border border-amber-200";
    case "Đã bán":
      return "bg-gradient-to-r from-slate-100 to-gray-100 text-slate-600 border border-slate-200";
    default:
      return "bg-slate-100 text-slate-600 border border-slate-200";
  }
};

const resetFilters = () => {
  searchKeyword.value = "";
  filterStatus.value = "";
};

onMounted(() => {
  loadBatDongSan();
  loadStats();
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

body {
  font-family: 'Inter', sans-serif;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>