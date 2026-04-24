<template>
  <div class="min-h-screen bg-[#F8F9FD] p-4 md:p-8 font-sans text-[#2D3748]">
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
      <div>
        <h1 class="text-3xl font-extrabold text-[#1A1A40]">Bất Động Sản Của Tôi</h1>

      </div>
      <button @click="$router.push('/moi-gioi/dang-tin')"
        class="!bg-[#0A0A33] hover:bg-blue-900 text-white px-6 py-3 rounded-2xl flex items-center gap-2 shadow-lg transition-all active:scale-95">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="font-semibold">Đăng Tin Mới</span>
      </button>
    </header>

    <div class="grid grid-cols-3 md:grid-cols-4 gap-4 mb-10">
      <div
        class="bg-white p-6 rounded-[24px] shadow-sm border-b-4 border-[#3B41E3] transition-transform hover:-translate-y-1">
        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Tổng Bất Động Sản</p>
        <p class="text-4xl font-black mt-2 text-[#3B41E3]">{{ stats.total }}</p>
      </div>

      <div
        class="bg-white p-6 rounded-[24px] shadow-sm border-b-4 border-[#10B981] transition-transform hover:-translate-y-1">
        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Đã Duyệt</p>
        <p class="text-4xl font-black mt-2 text-[#10B981]">{{ stats.approved }}</p>
      </div>

      <div
        class="bg-white p-6 rounded-[24px] shadow-sm border-b-4 border-[#F59E0B] transition-transform hover:-translate-y-1">
        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Chờ Duyệt</p>
        <p class="text-4xl font-black mt-2 text-[#F59E0B]">{{ stats.pending }}</p>
      </div>

      <div
        class="bg-white p-6 rounded-[24px] shadow-sm border-b-4 border-[#94A3B8] transition-transform hover:-translate-y-1">
        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Từ Chối</p>
        <p class="text-4xl font-black mt-2 text-[#94A3B8]">{{ stats.sold }}</p>
      </div>
    </div>

    <div class="bg-white p-3 rounded-[20px] shadow-sm flex flex-wrap gap-3 items-center mb-8 border border-gray-100">
      <div class="relative flex-1 min-w-[250px]">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
        <input v-model="searchKeyword" type="text" placeholder="Tìm theo tên, địa chỉ hoặc giá..."
          class="w-full bg-[#F3F4F6] border-none rounded-xl py-3 pl-10 pr-4 text-sm focus:ring-2 focus:ring-blue-100 outline-none" />
      </div>

      <div class="flex gap-2">
        <div class="relative">
          <select v-model="filterStatus"
            class="appearance-none bg-[#F3F4F6] border-none rounded-xl pl-4 pr-10 py-3 text-sm font-medium text-gray-600 outline-none cursor-pointer">
            <option value="">Tất cả trạng thái</option>
            <option value="1">Đã duyệt</option>
            <option value="0">Chờ duyệt</option>
          </select>

          <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </div>
        </div>

        <!-- Reset Button -->
        <button v-if="searchKeyword || filterStatus" @click="resetFilters"
          class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-3 rounded-xl text-sm font-medium transition-colors">
          ✕ Reset
        </button>
      </div>
    </div>

    <div class="space-y-4 mb-10">
      <!-- Error Message -->
      <div v-if="errorMessage"
        class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-[20px] text-center font-medium animate-pulse">
        {{ errorMessage }}
      </div>

      <!-- Success Message -->
      <div v-if="successMessage"
        class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-[20px] text-center font-medium animate-pulse">
        {{ successMessage }}
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-10 text-gray-500">
        ⏳ Đang tải dữ liệu...
      </div>

      <!-- Search Result Info -->
      <div v-if="!loading && (searchKeyword || filterStatus) && filteredProperties.length > 0"
        class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded-xl text-sm font-medium">
        ✅ Tìm thấy {{ filteredProperties.length }} kết quả
      </div>

      <!-- Danh sách -->
      <div v-for="item in filteredProperties" :key="item.id"
        class="bg-white p-4 rounded-[24px] flex flex-col md:flex-row items-center gap-6 shadow-sm hover:shadow-md transition-shadow border border-transparent hover:border-blue-50">
        <!-- IMAGE -->
        <div class="relative w-full md:w-56 h-36 shrink-0">
          <img :src="item.image" :alt="item.title" class="w-full h-full object-cover rounded-2xl shadow-inner"
            @error="(e) => { e.target.src = 'https://via.placeholder.com/400x250?text=No+Image'; }" />

          <div v-if="item.premium"
            class="absolute top-3 left-3 bg-[#0D9488] text-white text-[9px] font-bold px-2 py-1 rounded-md">
            PREMIUM
          </div>

          <div v-if="item.status === 'Đã bán'"
            class="absolute inset-0 flex items-center justify-center rounded-2xl bg-black/40">
            <span class="bg-black/70 text-white border border-white px-4 py-1 text-xs font-bold rounded">ĐÃ BÁN</span>
          </div>
        </div>

        <!-- INFO -->
        <div class="flex-1  md:text-left">
          <h3 class="text-lg font-bold text-[#1A1A40] mb-1 leading-tight">
            {{ item.title }}
          </h3>
          <p class="text-sm text-gray-400 flex items-center justify-center md:justify-start gap-1 mb-2">
            📍 {{ item.dia_chi_id }}
          </p>
          <!-- Thông tin chi tiết -->
          <div class="flex gap-3 text-xs text-gray-500 justify-center md:justify-start">
            <span v-if="item.dien_tich" class="flex items-center gap-1">
              📐 {{ item.dien_tich }} m²
            </span>
            <span v-if="item.so_phong_ngu" class="flex items-center gap-1">
              🛏️ {{ item.so_phong_ngu }} phòng
            </span>
            <span v-if="item.so_phong_tam" class="flex items-center gap-1">
              🚿 {{ item.so_phong_tam }} WC
            </span>
          </div>
        </div>

        <!-- PRICE -->
        <div class="w-32 text-center md:text-left">
          <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">
            Giá
          </p>

          <p class="text-lg font-extrabold text-[#3B41E3]">
            {{ item.price }}
          </p>
        </div>

        <!-- STATUS -->
        <div class="w-36 flex justify-center">
          <span :class="statusClasses(item.status)" class="inline-flex items-center justify-center gap-1
           min-w-[115px] h-8 px-4
           rounded-full
           text-[12px] font-semibold
           whitespace-nowrap">
            <span class="text-sm leading-none">•</span>
            <span>{{ item.status }}</span>
          </span>
        </div>

        <!-- ACTION -->
        <div class="flex items-center gap-3 shrink-0">

          <!-- Edit -->
          <button @click="handleEdit(item)" title="Cập nhật" class="w-11 h-11 flex items-center justify-center
           rounded-xl
           bg-blue-50 text-blue-600
           border border-blue-100
           shadow-sm
           transition-all duration-200
           hover:bg-blue-600  hover:shadow-md hover:-translate-y-0.5">
            <i class="bi bi-pencil-square text-lg"></i>
          </button>

          <!-- Delete -->
          <button @click="handleDelete(item)" title="Xóa" class="w-11 h-11 flex items-center justify-center
           rounded-xl
           bg-red-50 text-red-600
           border border-red-100
           shadow-sm
           transition-all duration-200
           hover:bg-red-600  hover:shadow-md hover:-translate-y-0.5">
            <i class="bi bi-trash text-lg"></i>
          </button>

        </div>
      </div>

      <!-- Empty -->
      <div v-if="!loading && filteredProperties.length == 0" class="text-center py-10 text-gray-400">
        <p v-if="searchKeyword || filterStatus" class="text-lg">
          🔍 Không tìm thấy bất động sản phù hợp với tìm kiếm
        </p>
        <p v-else class="text-lg">
          📭 Chưa có bất động sản nào
        </p>
      </div>

      <!-- PAGINATION -->
      <div v-if="pagination.last_page > 1" class="flex justify-center items-center gap-2 mt-8 flex-wrap">
        <!-- Prev -->
        <button @click="loadBatDongSan(pagination.current_page - 1)" :disabled="pagination.current_page === 1"
          class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
          ← Trước
        </button>

        <!-- Number -->
        <button v-for="page in pagination.last_page" :key="page" @click="loadBatDongSan(page)" :class="[
          'w-10 h-10 rounded-xl font-semibold',
          page === pagination.current_page
            ? 'bg-[#3B41E3] '
            : 'bg-white border hover:bg-gray-100'
        ]">
          {{ page }}
        </button>

        <!-- Next -->
        <button @click="loadBatDongSan(pagination.current_page + 1)"
          :disabled="pagination.current_page === pagination.last_page"
          class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
          Sau →
        </button>
      </div>
    </div>

  </div>
  <!-- EDIT MODAL -->
  <div v-if="showEditModal"
    class="fixed inset-0 bg-[#0A0A33]/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div
      class="bg-white rounded-[32px] w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] transition-all">

      <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-white">
        <div>
          <h2 class="text-xl font-extrabold text-[#1A1A40]">Chỉnh sửa bài đăng</h2>
          <p class="text-xs text-gray-400 mt-0.5">Cập nhật thông tin chi tiết cho bất động sản của bạn</p>
        </div>
        <button @click="cancelEdit"
          class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-colors">
          ✕
        </button>
      </div>

      <div class="px-8 py-6 overflow-y-auto custom-scrollbar">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

          <div class="md:col-span-2">
            <label class="block text-[13px] font-bold text-gray-500 mb-2 uppercase tracking-wide">📌 Tiêu Đề</label>
            <input v-model="editingProperty.title" type="text"
              class="w-full px-4 py-3 bg-[#F3F4F6] border-none rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all placeholder:text-gray-400 font-medium"
              placeholder="Ví dụ: Căn hộ cao cấp Empire City..." />
          </div>
          <div class="md:col-span-2">
            <label class="block text-[13px] font-bold text-gray-500 mb-2 uppercase tracking-wide">📌 Địa Chỉ</label>
            <input v-model="editingProperty.dia_chi_id" type="text"
              class="w-full px-4 py-3 bg-[#F3F4F6] border-none rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all placeholder:text-gray-400 font-medium"
              placeholder="Ví dụ: 123 Đường ABC, Quận XYZ, Tỉnh XYZ..." />
          </div>
          <div>
            <label class="block text-[13px] font-bold text-gray-500 mb-2 uppercase tracking-wide">💰 Giá (VNĐ)</label>
            <input v-model="editingProperty.gia" type="number"
              class="w-full px-4 py-3 bg-[#F3F4F6] border-none rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all font-semibold" />
          </div>

          <div>
            <label class="block text-[13px] font-bold text-gray-500 mb-2 uppercase tracking-wide">📐 Diện Tích
              (m²)</label>
            <input v-model="editingProperty.dien_tich" type="number"
              class="w-full px-4 py-3 bg-[#F3F4F6] border-none rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" />
          </div>

          <div class="md:col-span-2 grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[13px] font-bold text-gray-500 mb-2 uppercase tracking-wide">🛏️ Phòng
                Ngủ</label>
              <div class="relative">
                <input v-model.number="editingProperty.so_phong_ngu" type="number"
                  class="w-full px-4 py-3 bg-[#F3F4F6] border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500/20" />
              </div>
            </div>
            <div>
              <label class="block text-[13px] font-bold text-gray-500 mb-2 uppercase tracking-wide">🚿 Phòng Tắm</label>
              <input v-model.number="editingProperty.so_phong_tam" type="number"
                class="w-full px-4 py-3 bg-[#F3F4F6] border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500/20" />
            </div>
          </div>

          <div class="md:col-span-2">
            <label class="block text-[13px] font-bold text-gray-500 mb-2 uppercase tracking-wide">📝 Mô Tả</label>
            <textarea v-model="editingProperty.mo_ta" rows="3"
              class="w-full px-4 py-3 bg-[#F3F4F6] border-none rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all resize-none"
              placeholder="Mô tả ưu điểm, tiện ích của căn hộ..."></textarea>
          </div>
        </div>
      </div>

      <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 grid grid-cols-2 gap-4">
        <button @click="cancelEdit"
          class="h-14 border border-gray-300 rounded-xl font-semibold text-gray-600 bg-white hover:bg-gray-100 transition-colors">
          Hủy
        </button>

        <button @click="submitEdit"
          class="h-14 rounded-xl font-semibold text-primary bg-blue-600 hover:bg-blue-700 shadow-sm flex items-center justify-center transition-all">
          Lưu Thay Đổi
        </button>
      </div>
    </div>
  </div>

  <!-- DELETE MODAL -->
  <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl p-8 w-full max-w-lg shadow-2xl transform transition-all">
      <!-- Icon -->
      <div class="flex justify-center mb-4">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
          <span class="text-4xl">⚠️</span>
        </div>
      </div>

      <!-- Title -->
      <h2 class="text-2xl font-bold text-center text-[#1A1A40] mb-2">Xác Nhận Xóa</h2>

      <!-- Message -->
      <p class="text-center text-gray-600 mb-2">
        Bạn sắp xóa bài đăng:
      </p>
      <p class="text-center text-lg font-semibold text-[#1A1A40] mb-6">
        "{{ deleteProperty?.title }}"
      </p>

      <!-- Warning -->
      <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <p class="text-sm text-red-700 font-medium">
          ⚠️ Hành động này không thể hoàn tác. Bài đăng sẽ bị xóa vĩnh viễn.
        </p>
      </div>

      <!-- Actions -->
      <div class="flex gap-4">
        <button @click="cancelDelete" :disabled="isDeleting"
          class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed">
          Hủy
        </button>

        <button @click="confirmDelete" :disabled="isDeleting"
          class="flex-1 px-4 py-3 font-semibold rounded-xl transition-all shadow-sm active:scale-95 disabled:cursor-not-allowed"
          :class="isDeleting ? 'bg-gray-300 text-gray-500' : 'bg-red-600 hover:bg-red-700 text-success shadow-red-200'">
          <span v-if="isDeleting">⏳ Đang xóa...</span>
          <span v-else><i class="bi bi-trash text-lg me-2"></i>  Xóa Vĩnh Viễn</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import api from "@/axios/config.js";

// ROUTER
const router = useRouter();

// DATA
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
// MODAL STATES
const showDeleteModal = ref(false);
const showEditModal = ref(false);
const deleteProperty = ref(null);
const editingProperty = ref(null);
const isSubmitting = ref(false);

// CHECK AUTH
const token = localStorage.getItem("auth_token") || localStorage.getItem("token");

// LOAD DATA
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

      // Load stats tổng nếu chưa có trong response
      loadStats();
    }
  } catch (error) {
    errorMessage.value = "Lỗi tải dữ liệu";
  } finally {
    loading.value = false;
  }
};

// LOAD STATS (Tổng từ tất cả trang)
const loadStats = async () => {
  try {
    // Load tất cả dữ liệu (page=1, per_page=1000) để tính tổng
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

// Helper: Format địa chỉ
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

// Helper: Lấy ảnh đại diện
const getImageUrl = (item) => {
  const baseUrl = "http://127.0.0.1:8000/storage/";

  // Cách 1: Kiểm tra anh_dai_dien_url (nếu backend trả về)
  if (item.anh_dai_dien_url) {
    // Nếu đã là full URL
    if (item.anh_dai_dien_url.startsWith("http")) {
      return item.anh_dai_dien_url;
    }
    // Nếu chỉ là path
    return baseUrl + item.anh_dai_dien_url;
  }

  // Cách 2: Tìm ảnh đánh dấu là ảnh đại diện
  if (item.hinh_anh?.length > 0) {
    const anhDaiDien = item.hinh_anh.find((img) => img.is_anh_dai_dien === true);
    if (anhDaiDien) {
      return baseUrl + anhDaiDien.url;
    }
    // Cách 3: Lấy ảnh đầu tiên
    return baseUrl + item.hinh_anh[0].url;
  }

  // Cách 4: Kiểm tra anhDaiDien object (nếu backend nested)
  if (item.anh_dai_dien && item.anh_dai_dien.url) {
    return baseUrl + item.anh_dai_dien.url;
  }

  // Fallback: Placeholder
  return "/no-image.png";
};

// Tính toán stats
const calculateStats = (result) => {
  stats.value = {
    total: result.total || 0,
    approved: result.total_approved || result.tong_da_duyet || 0,
    pending: result.total_pending || result.tong_cho_duyet || 0,
    sold: 0,
  };
};

// FILTER & SEARCH
const filteredProperties = computed(() => {
  return properties.value.filter((item) => {
    // Filter theo trạng thái
    if (filterStatus.value) {
      if (filterStatus.value === "1" && item.status !== "Đã duyệt") {
        return false;
      }
      if (filterStatus.value === "0" && item.status !== "Chờ duyệt") {
        return false;
      }
    }

    // Search theo keyword (tìm trong tiêu đề, địa chỉ, giá)
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

// ACTIONS
const handleView = (property) => {
  // TODO: Chuyển hướng tới trang chi tiết
  console.log("View property:", property);
  // router.push(`/moi-gioi/bds/${property.id}`)
};

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

    console.log("Gửi update:", updatePayload);
    const res = await api.post("/moi-gioi/bds/update", updatePayload);

    console.log("Response update:", res.data);
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
    console.log("Gửi delete:", deletePayload);

    const res = await api.post("/moi-gioi/bds/delete", deletePayload);

    console.log("Response delete:", res.data);
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

// STATUS UI
const statusClasses = (status) => {
  switch (status) {
    case "Đã duyệt":
      return "bg-[#CCFBF1] text-[#0D9488]";
    case "Chờ duyệt":
      return "bg-[#FEF3C7] text-[#B45309]";
    case "Đã bán":
      return "bg-[#F1F5F9] text-[#64748B]";
    default:
      return "bg-gray-100 text-gray-500";
  }
};

// RESET FILTERS
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
</style>
