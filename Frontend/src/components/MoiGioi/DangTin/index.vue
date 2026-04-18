<template>
  <div class="property-posting-wrapper">
    <!-- Progress Steps -->
    <div class="progress-header">
      <div class="progress-track">
        <div
          v-for="(step, index) in steps"
          :key="index"
          class="progress-item"
          :class="{
            active: currentStep === index + 1,
            completed: currentStep > index + 1,
          }"
        >
          <div class="step-indicator">
            <span class="step-number">{{ index + 1 }}</span>
            <i
              v-if="currentStep > index + 1"
              class="fas fa-check check-icon"
            ></i>
          </div>
          <span class="step-name">{{ step }}</span>
        </div>
      </div>
    </div>

    <div class="posting-grid">
      <!-- Left Sidebar - Navigation -->
      <aside class="sidebar-left">
        <div class="sidebar-section">
          <h3 class="sidebar-title">Quản lý tin đăng</h3>
          <nav class="sidebar-nav">
            <a href="#" class="nav-item active">
              <i class="fas fa-plus-circle"></i>
              <span>Đăng tin mới</span>
            </a>
            <a href="#" class="nav-item">
              <i class="fas fa-list"></i>
              <span>Tin đang hoạt động</span>
            </a>
            <a href="#" class="nav-item">
              <i class="fas fa-clock"></i>
              <span>Chờ duyệt</span>
            </a>
            <a href="#" class="nav-item">
              <i class="fas fa-archive"></i>
              <span>Tin đã hết hạn</span>
            </a>
          </nav>
        </div>

        <div class="sidebar-section upgrade-box">
          <div class="upgrade-icon">
            <i class="fas fa-crown"></i>
          </div>
          <h4>Nâng cấp tài khoản</h4>
          <p>Để sử dụng nhiều tính năng cao cấp hơn</p>
          <button class="btn-upgrade">Nâng cấp ngay</button>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="main-form-area">
        <!-- Step 1: Basic Information -->
        <div v-if="currentStep === 1" class="form-step fade-in">
          <div class="section-header">
            <h2 class="section-title">Thông tin chi tiết tài sản</h2>
            <p class="section-desc">
              Cung cấp thông tin cơ bản về bất động sản của bạn
            </p>
          </div>

          <div class="form-container">
            <!-- Title -->
            <div class="form-row">
              <div class="form-group full-width">
                <label class="form-label required">
                  Tiêu đề tin đăng
                  <span class="label-hint"
                    >Viết hoa chữ cái đầu, không viết hoa toàn bộ</span
                  >
                </label>
                <input
                  v-model="form.tieu_de"
                  type="text"
                  class="form-input"
                  placeholder="Ví dụ: Căn hộ Penhouse Indochina Riverside 3PN view sông Hàn"
                />
                <div class="char-count">{{ form.tieu_de.length }}/150</div>
              </div>
            </div>

            <div class="form-row">
              <!-- Property Type -->
              <div class="form-group">
                <label class="form-label required"
                  >Loại hình bất động sản</label
                >
                <div class="select-wrapper">
                  <select v-model="form.loai_id" class="form-select">
                    <option value="">Chọn loại BĐS</option>
                    <option
                      v-for="loai in loaiBDSList"
                      :key="loai.id"
                      :value="loai.id"
                    >
                      {{ loai.ten }}
                    </option>
                  </select>
                  <i class="fas fa-chevron-down select-icon"></i>
                </div>
              </div>

              <!-- Project Name -->
              <div class="form-group">
                <label class="form-label">Tên dự án (nếu có)</label>
                <input
                  v-model="form.ten_du_an"
                  type="text"
                  class="form-input"
                  placeholder="Indochina Estate"
                />
              </div>
            </div>

            <div class="form-row">
              <!-- Price -->
              <div class="form-group">
                <label class="form-label required">Giá bán/thuê</label>
                <div class="input-group">
                  <input
                    v-model="form.gia"
                    type="number"
                    class="form-input"
                    placeholder="0"
                  />
                  <span class="input-addon">VNĐ</span>
                </div>
                <small class="form-hint">Để trống nếu thương lượng</small>
              </div>

              <!-- Area -->
              <div class="form-group">
                <label class="form-label required">Diện tích</label>
                <div class="input-group">
                  <input
                    v-model="form.dien_tich"
                    type="number"
                    step="0.01"
                    class="form-input"
                    placeholder="0"
                  />
                  <span class="input-addon">m²</span>
                </div>
              </div>
            </div>

            <div class="form-row">
              <!-- Bedrooms -->
              <div class="form-group">
                <label class="form-label">Số phòng ngủ</label>
                <div class="button-group">
                  <button
                    v-for="n in [1, 2, 3]"
                    :key="n"
                    type="button"
                    class="btn-chip"
                    :class="{ active: form.so_phong_ngu === n }"
                    @click="form.so_phong_ngu = n"
                  >
                    {{ n }}
                  </button>
                  <button
                    type="button"
                    class="btn-chip"
                    :class="{ active: form.so_phong_ngu === 4 }"
                    @click="form.so_phong_ngu = 4"
                  >
                    4+
                  </button>
                </div>
              </div>

              <!-- Bathrooms -->
              <div class="form-group">
                <label class="form-label">Số phòng tắm</label>
                <div class="button-group">
                  <button
                    v-for="n in [1, 2]"
                    :key="n"
                    type="button"
                    class="btn-chip"
                    :class="{ active: form.so_phong_tam === n }"
                    @click="form.so_phong_tam = n"
                  >
                    {{ n }}
                  </button>
                  <button
                    type="button"
                    class="btn-chip"
                    :class="{ active: form.so_phong_tam === 3 }"
                    @click="form.so_phong_tam = 3"
                  >
                    3+
                  </button>
                </div>
              </div>
            </div>

            <!-- Description -->
            <div class="form-row">
              <div class="form-group full-width">
                <label class="form-label">Mô tả chi tiết</label>
                <textarea
                  v-model="form.mo_ta"
                  class="form-textarea"
                  rows="6"
                  placeholder="Mô tả chi tiết về vị trí, tiện ích, pháp lý, hướng nhà..."
                ></textarea>
                <div class="textarea-toolbar">
                  <button type="button" class="toolbar-btn" title="In đậm">
                    <i class="fas fa-bold"></i>
                  </button>
                  <button type="button" class="toolbar-btn" title="In nghiêng">
                    <i class="fas fa-italic"></i>
                  </button>
                  <button type="button" class="toolbar-btn" title="Gạch chân">
                    <i class="fas fa-underline"></i>
                  </button>
                  <button type="button" class="toolbar-btn" title="Danh sách">
                    <i class="fas fa-list-ul"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Upload Preview -->
          <!-- <div class="quick-upload-section"> -->
          <!-- <div
              class="upload-card"
              @click="triggerFileInput"
              @dragover.prevent
              @drop="handleDrop"
            >
              <div class="upload-content">
                <div class="upload-icon-wrapper">
                  <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <h4>Tải ảnh lên ngay</h4>
                <p>Kéo thả ảnh vào đây hoặc click để chọn file</p>
                <span class="upload-hint">Hỗ trợ: JPG, PNG (Tối đa 10MB)</span>
              </div>
              <input
                ref="fileInput"
                type="file"
                multiple
                accept="image/*"
                class="hidden-input"
                @change="handleFileSelect"
              />
            </div> -->

          <!-- Preview Gallery -->
          <!-- <div v-if="form.images.length > 0" class="image-preview-grid">
              <div
                v-for="(img, idx) in form.images"
                :key="idx"
                class="preview-item"
              >
                <img :src="getImageUrl(img)" :alt="'Preview ' + (idx + 1)" />
                <button class="remove-img" @click="removeImage(idx)">
                  <i class="fas fa-times"></i>
                </button>
                <span v-if="idx === 0" class="main-badge">Ảnh bìa</span>
              </div>
            </div> -->
          <!-- </div> -->
        </div>

        <!-- Step 2: Images & Video -->
        <div v-else-if="currentStep === 2" class="form-step fade-in">
          <div class="quick-upload-section">
            <div
              class="upload-card"
              @click="triggerFileInput"
              @dragover.prevent
              @drop="handleDrop"
            >
              <div class="upload-content">
                <div class="upload-icon-wrapper">
                  <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <h4>Tải ảnh lên ngay</h4>
                <p>Kéo thả ảnh vào đây hoặc click để chọn file</p>
                <span class="upload-hint">Hỗ trợ: JPG, PNG (Tối đa 10MB)</span>
              </div>
              <input
                ref="fileInput"
                type="file"
                multiple
                accept="image/*"
                class="hidden-input"
                @change="handleFileSelect"
              />
            </div>

            <!-- Preview Gallery -->
            <div v-if="form.images.length > 0" class="image-preview-grid">
              <div
                v-for="(img, idx) in form.images"
                :key="idx"
                class="preview-item"
              >
                <img :src="getImageUrl(img)" :alt="'Preview ' + (idx + 1)" />
                <button class="remove-img" @click="removeImage(idx)">
                  <i class="fas fa-times"></i>
                </button>
                <span v-if="idx === 0" class="main-badge">Ảnh bìa</span>
              </div>
            </div>
          </div>
          <!-- <div class="section-header">
            <h2 class="section-title">Hình ảnh & Video</h2>
            <p class="section-desc">
              Thêm hình ảnh chất lượng cao để thu hút người xem
            </p>
          </div>
          <div class="empty-state">
            <i class="fas fa-images"></i>
            <h3>Quản lý media</h3>
            <p>Chức năng đang được phát triển</p>
          </div> -->
        </div>

        <!-- Step 3: Location & Map -->
        <div v-else-if="currentStep === 3" class="form-step fade-in">
          <div class="section-header">
            <h2 class="section-title">Vị trí & Bản đồ</h2>
            <p class="section-desc">
              Chọn vị trí chính xác của bất động sản trên bản đồ
            </p>
          </div>

          <div class="form-container">
            <!-- Province -->
            <div class="form-row">
              <div class="form-group">
                <label class="form-label required">Tỉnh / Thành phố</label>
                <div class="select-wrapper">
                  <select
                    v-model="form.tinh_id"
                    @change="onTinhChange"
                    class="form-select"
                  >
                    <option value="">-- Chọn tỉnh/thành --</option>
                    <option
                      v-for="tinh in tinhThanhList"
                      :key="tinh.id"
                      :value="tinh.id"
                    >
                      {{ tinh.ten }}
                    </option>
                  </select>
                  <i class="fas fa-chevron-down select-icon"></i>
                </div>
              </div>

              <!-- District -->
              <div class="form-group">
                <label class="form-label required">Quận / Huyện</label>
                <div class="select-wrapper">
                  <select
                    v-model="form.quan_id"
                    class="form-select"
                    @change="onQuanChange"
                  >
                    <option value="">Chọn quận huyện</option>
                    <option
                      v-for="quan in quanHuyenList"
                      :key="quan.id"
                      :value="quan.id"
                    >
                      {{ quan.ten }}
                    </option>
                  </select>
                  <i class="fas fa-chevron-down select-icon"></i>
                </div>
              </div>
            </div>

            <!-- Address Detail -->
            <div class="form-row">
              <div class="form-group full-width">
                <label class="form-label">Địa chỉ chi tiết</label>
                <input
                  v-model="form.dia_chi_chi_tiet"
                  type="text"
                  class="form-input"
                  placeholder="Số nhà, tên đường, khu phố..."
                />
              </div>
            </div>

            <!-- Map Container -->
            <div class="form-group full-width">
              <label class="form-label required"
                >🗺️ Chọn vị trí trên bản đồ</label
              >
              <div class="map-wrapper">
                <div v-if="!mapLoaded" class="map-loading">
                  <i class="fas fa-spinner fa-spin"></i>
                  <span>Đang tải bản đồ...</span>
                </div>
                <div id="leaflet-map" class="leaflet-map-container"></div>
              </div>
              <small class="form-hint"
                >💡 Click vào bản đồ để chọn vị trí bất động sản</small
              >

              <!-- Selected Coordinates Display -->
              <div
                v-if="form.latitude && form.longitude"
                class="coords-display mt-2"
              >
                <i class="fas fa-check-circle text-success"></i>
                <strong>Đã chọn:</strong>
                {{ form.latitude.toFixed(6) }}, {{ form.longitude.toFixed(6) }}
              </div>
            </div>
          </div>
        </div>

        <!-- Step 4: Package -->
        <div v-else-if="currentStep === 4" class="form-step fade-in">
          <div class="section-header">
            <h2 class="section-title">Chọn gói tin đăng</h2>
            <p class="section-desc">Chọn gói phù hợp với nhu cầu của bạn</p>
          </div>
          <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h3>Gói tin đăng</h3>
            <p>Chức năng đang được phát triển</p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-actions">
          <button
            v-if="currentStep > 1"
            class="btn btn-secondary"
            @click="prevStep"
          >
            <i class="fas fa-arrow-left"></i>
            Quay lại
          </button>

          <button
            v-if="currentStep < 4"
            class="btn btn-primary btn-lg"
            @click="nextStep"
          >
            Tiếp tục
            <i class="fas fa-arrow-right"></i>
          </button>

          <button v-else class="btn btn-success btn-lg" @click="submitForm">
            <i class="fas fa-check-circle"></i>
            Đăng tin ngay
          </button>

          <button class="btn btn-outline" @click="saveDraft">
            <i class="fas fa-save"></i>
            Lưu nháp
          </button>
        </div>

        <!-- Auto Save Indicator -->
        <div class="auto-save-indicator" :class="{ saving: isSaving }">
          <i
            class="fas"
            :class="isSaving ? 'fa-spinner fa-spin' : 'fa-check-circle'"
          ></i>
          <span>{{
            isSaving ? "Đang lưu..." : `Tự động lưu: ${lastSaveTime}`
          }}</span>
        </div>
      </main>

      <!-- Right Sidebar -->
      <aside class="sidebar-right">
        <!-- Tips Card -->
        <div class="info-card tips-card">
          <div class="card-header warning">
            <i class="fas fa-lightbulb"></i>
            <h3>Mẹo chuyên nghiệp</h3>
          </div>
          <div class="card-body">
            <ul class="tips-list">
              <li>
                <i class="fas fa-check-circle"></i
                ><span>Sử dụng ít nhất 6 hình ảnh chất lượng cao</span>
              </li>
              <li>
                <i class="fas fa-check-circle"></i
                ><span>Cung cấp đầy đủ thông tin pháp lý</span>
              </li>
              <li>
                <i class="fas fa-check-circle"></i
                ><span>Mô tả chi tiết tiện ích xung quanh</span>
              </li>
              <li>
                <i class="fas fa-check-circle"></i
                ><span>Đặt giá cạnh tranh so với thị trường</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Stats Card -->
        <div class="info-card stats-card">
          <div class="stat-item">
            <div class="stat-value">85%</div>
            <div class="stat-label">Tin có ảnh thu hút hơn</div>
          </div>
          <div class="stat-item">
            <div class="stat-value">3x</div>
            <div class="stat-label">Nhiều lượt xem hơn</div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</template>

<script setup>
import {
  ref,
  reactive,
  onMounted,
  onUnmounted,
  computed,
  watch,
  nextTick,
} from "vue";
import { api } from "@/main";
import Swal from "sweetalert2";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

// ===== STATE =====
const currentStep = ref(1);
const lastSaveTime = ref("Vừa xong");
const isSaving = ref(false);
const fileInput = ref(null);
const mapLoaded = ref(false);
const mapInstance = ref(null);
const markerInstance = ref(null);

const steps = [
  "Thông tin cơ bản",
  "Hình ảnh & Video",
  "Vị trí & Tiện ích",
  "Chọn gói tin",
];

const form = reactive({
  tieu_de: "",
  mo_ta: "",
  gia: 0,
  dien_tich: 0,
  loai_id: "",
  trang_thai_id: 1,
  moi_gioi_id: null,
  dia_chi_id: null,
  latitude: null,
  longitude: null,
  dia_chi_chi_tiet: "",
  so_phong_ngu: 1,
  so_phong_tam: 1,
  ten_du_an: "",
  tinh_id: "",
  quan_id: "",
  is_duyet: false,
  is_noi_bat: false,
  images: [],
});

const loaiBDSList = ref([]);
const tinhThanhList = ref([]);
const quanHuyenList = ref([]);

// ===== COMPUTED =====
const isFormValid = computed(() => {
  if (currentStep.value === 1) {
    return form.tieu_de && form.loai_id && form.gia && form.dien_tich;
  }
  if (currentStep.value === 3) {
    return form.latitude && form.longitude;
  }
  return true;
});

// ===== FILE HANDLING =====
const triggerFileInput = () => fileInput.value?.click();

const handleFileSelect = (event) => {
  const files = Array.from(event.target.files);
  handleFiles(files);
};

const handleDrop = (event) => {
  const files = Array.from(event.dataTransfer.files);
  handleFiles(files);
};

const handleFiles = (files) => {
  const validFiles = files.filter((file) => {
    const isValidType = file.type.startsWith("image/");
    const isValidSize = file.size <= 10 * 1024 * 1024;
    return isValidType && isValidSize;
  });

  if (validFiles.length < files.length) {
    Swal.fire({
      icon: "warning",
      title: "File không hợp lệ",
      text: "Chỉ chấp nhận ảnh JPG, PNG dưới 10MB",
      timer: 3000,
    });
  }
  form.images = [...form.images, ...validFiles];
};

const removeImage = (index) => form.images.splice(index, 1);
const getImageUrl = (file) => URL.createObjectURL(file);

// ===== LOCATION DROPDOWNS =====
const onTinhChange = async () => {
  quanHuyenList.value = [];
  form.quan_id = "";
  if (!form.tinh_id) return;
  try {
    const res = await api.get(`/moi-gioi/quan-huyen?tinh_id=${form.tinh_id}`);
    if (res.data?.status) {
      quanHuyenList.value = res.data.data.map((item) => ({
        id: item.id,
        ten: item.ten,
      }));
    }
  } catch (error) {
    console.error("Load districts error:", error);
  }
};

const onQuanChange = () => {};

// ===== MAP FUNCTIONS =====
const initMap = async () => {
  await nextTick();
  if (mapInstance.value) return;

  const defaultLat = 10.8231;
  const defaultLng = 106.7621;

  mapInstance.value = L.map("leaflet-map").setView(
    [defaultLat, defaultLng],
    13
  );

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
    maxZoom: 19,
  }).addTo(mapInstance.value);

  mapInstance.value.on("click", async (e) => {
    await placeMarker(e.latlng);
    await reverseGeocode(e.latlng.lat, e.latlng.lng);
  });

  if (form.latitude && form.longitude) {
    await placeMarker({ lat: form.latitude, lng: form.longitude });
    mapInstance.value.setView([form.latitude, form.longitude], 15);
  }

  mapLoaded.value = true;
  setTimeout(() => mapInstance.value?.invalidateSize(), 100);
};

const placeMarker = async (latlng) => {
  if (markerInstance.value) {
    mapInstance.value.removeLayer(markerInstance.value);
  }

  markerInstance.value = L.marker([latlng.lat, latlng.lng])
    .addTo(mapInstance.value)
    .bindPopup("📍 Vị trí bất động sản")
    .openPopup();

  form.latitude = latlng.lat;
  form.longitude = latlng.lng;
};

const reverseGeocode = async (lat, lng) => {
  try {
    const response = await fetch(
      `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=vi`,
      { headers: { "User-Agent": "BDS-App/1.0" } }
    );
    const data = await response.json();
    if (data.display_name) {
      form.dia_chi_chi_tiet = data.display_name;
    }
  } catch (error) {
    console.warn("Reverse geocoding failed:", error);
  }
};

// Watch step changes to init map when Step 3 is active
watch(
  () => currentStep.value,
  (newStep) => {
    if (newStep === 3) {
      nextTick(() => initMap());
    }
  }
);

// ===== NAVIGATION =====
const nextStep = () => {
  if (validateStep()) {
    currentStep.value++;
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
};

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--;
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
};

const validateStep = () => {
  if (currentStep.value === 1) {
    if (!form.tieu_de || !form.loai_id || !form.gia || !form.dien_tich) {
      Swal.fire({
        icon: "warning",
        title: "Thiếu thông tin",
        text: "Vui lòng điền đầy đủ các trường bắt buộc (*)",
        confirmButtonText: "Đã hiểu",
      });
      return false;
    }
    if (form.tieu_de.length < 20) {
      Swal.fire({
        icon: "warning",
        title: "Tiêu đề quá ngắn",
        text: "Tiêu đề nên có ít nhất 20 ký tự để thu hút người xem",
        confirmButtonText: "Tiếp tục",
      });
    }
  }
  if (currentStep.value === 3) {
    if (!form.latitude || !form.longitude) {
      Swal.fire({
        icon: "warning",
        title: "Chưa chọn vị trí",
        text: "Vui lòng click vào bản đồ để chọn vị trí bất động sản",
        confirmButtonText: "Đã hiểu",
      });
      return false;
    }
  }
  return true;
};

// ===== SAVE & SUBMIT =====
const saveDraft = async () => {
  // 1. Kiểm tra nhanh: Nếu chưa nhập gì thì không cần lưu
  if (!form.tieu_de && form.images.length === 0) {
    console.log("Chưa có dữ liệu để lưu nháp");
    return;
  }

  isSaving.value = true;
  const formData = new FormData();

  // 2. Chuyển đổi dữ liệu form sang FormData (giống submitForm)
  Object.keys(form).forEach((key) => {
    if (key === "images") return; // Xử lý ảnh riêng ở dưới

    let value = form[key];

    // Bỏ qua các trường rỗng/null để tránh lỗi validation không cần thiết
    if (value === null || value === "" || value === undefined) return;

    // Xử lý boolean thành 0/1
    if (key === "is_noi_bat" || key === "is_duyet") {
      value = value ? 1 : 0;
    }

    // Xử lý số
    if (
      [
        "gia",
        "dien_tich",
        "so_phong_ngu",
        "so_phong_tam",
        "loai_id",
        "tinh_id",
        "quan_id",
        "trang_thai_id",
      ].includes(key)
    ) {
      value = parseFloat(value);
    }

    formData.append(key, value);
  });

  // 3. Xử lý hình ảnh
  // ⚠️ Lưu ý: Backend của bạn đang chờ field tên là 'hinh_anh'
  form.images.forEach((image, index) => {
    formData.append(`hinh_anh[${index}]`, image);
  });

  // 4. QUAN TRỌNG: Ghi đè trạng thái thành 0 (Nháp)
  formData.append("trang_thai_id", 0);

  try {
    // Gửi request tương tự như tạo mới, nhưng dữ liệu đã có status 0
    await api.post("/moi-gioi/bds/create", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    lastSaveTime.value = "Vừa xong";

    // Thông báo nhỏ góc phải (Toast) thay vì popup to
    Swal.fire({
      toast: true,
      position: "top-end",
      icon: "success",
      title: "Đã lưu nháp thành công",
      showConfirmButton: false,
      timer: 1500,
    });
  } catch (error) {
    console.error("Lỗi lưu nháp:", error);
    // Nếu lỗi do thiếu trường bắt buộc (Validation), có thể bỏ qua hoặc báo
    if (error.response?.status === 422) {
      console.warn(
        "Dữ liệu chưa đủ điều kiện để lưu xuống Server (VD: Thiếu tiêu đề)"
      );
    }
  } finally {
    isSaving.value = false;
  }
};

const submitForm = async () => {
  if (!validateStep()) return;

  const { value: confirm } = await Swal.fire({
    title: "Xác nhận đăng tin?",
    text: "Kiểm tra kỹ thông tin trước khi đăng",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Đồng ý đăng",
    cancelButtonText: "Hủy",
    confirmButtonColor: "#001f7c",
    cancelButtonColor: "#6c757d",
  });

  if (!confirm) return;

  try {
    // ✅ Bước 1: Tạo địa chỉ nếu có tọa độ
    if (form.latitude && form.longitude && !form.dia_chi_id) {
      try {
        const diaChiRes = await api.post(
          "/moi-gioi/dia-chi",
          {
            latitude: parseFloat(form.latitude).toFixed(6),
            longitude: parseFloat(form.longitude).toFixed(6),
            dia_chi_chi_tiet: form.dia_chi_chi_tiet || "",
            tinh_id: form.tinh_id || null,
            quan_id: form.quan_id || null,
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
              "Content-Type": "application/json",
            },
          }
        );

        if (diaChiRes.data?.status && diaChiRes.data?.data?.id) {
          form.dia_chi_id = diaChiRes.data.data.id;
          console.log("✅ Created dia_chi_id:", form.dia_chi_id);
        }
      } catch (err) {
        console.warn(
          "⚠️ Could not create dia_chi, continuing without it:",
          err
        );
        // Vẫn tiếp tục nếu không tạo được địa chỉ
      }
    }

    // ✅ Bước 2: Chuẩn bị FormData
    const formData = new FormData();

    Object.keys(form).forEach((key) => {
      if (key === "images") return;
      let value = form[key];

      // Skip null dia_chi_id
      if (key === "dia_chi_id" && (!value || value === "")) return;

      if (key === "is_noi_bat" || key === "is_duyet") {
        value = value ? 1 : 0;
      }

      if (
        [
          "gia",
          "dien_tich",
          "so_phong_ngu",
          "so_phong_tam",
          "loai_id",
          "tinh_id",
          "quan_id",
          "trang_thai_id",
        ].includes(key)
      ) {
        if (value === null || value === "" || value === undefined) return;
        value = parseFloat(value);
      }

      if (["latitude", "longitude"].includes(key) && value) {
        value = parseFloat(value).toFixed(6);
      }

      if (value !== null && value !== undefined && value !== "") {
        formData.append(key, value);
      }
    });

    form.images.forEach((image, index) => {
      formData.append(`images[${index}]`, image);
    });

    // ✅ Bước 3: Submit
    const response = await api.post("/moi-gioi/bds/create", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    await Swal.fire({
      icon: "success",
      title: "Thành công!",
      text: "Tin đăng của bạn đã được tạo và đang chờ duyệt",
      timer: 2500,
      showConfirmButton: false,
    });

    window.location.href = "/moi-gioi/bds/data";
  } catch (error) {
    console.error("❌ Submit error:", error.response?.data);
    Swal.fire({
      icon: "error",
      title: "Lỗi",
      text: error.response?.data?.message || "Có lỗi xảy ra. Vui lòng thử lại.",
    });
  }
};
// ===== LOAD DATA =====
const loadData = async () => {
  try {
    const [loaiRes, tinhRes] = await Promise.all([
      api.get("/moi-gioi/loai-bat-dong-san/data"),
      api.get("/moi-gioi/tinh-thanh"),
    ]);
    if (loaiRes.data?.status)
      loaiBDSList.value = loaiRes.data.data.map((item) => ({
        id: item.id,
        ten: item.ten_loai,
      }));
    if (tinhRes.data?.status)
      tinhThanhList.value = tinhRes.data.data.map((item) => ({
        id: item.id,
        ten: item.ten,
      }));
  } catch (error) {
    console.error("Error loading dropdowns:", error);
  }
};

// ===== LIFECYCLE =====
let autoSaveInterval;
onMounted(() => {
  loadData();
  if (currentStep.value === 3) initMap();

  autoSaveInterval = setInterval(() => {
    if (form.tieu_de || form.mo_ta) saveDraft();
  }, 120000);
});

onUnmounted(() => {
  clearInterval(autoSaveInterval);
  if (mapInstance.value) {
    mapInstance.value.remove();
    mapInstance.value = null;
  }
});
</script>

<style scoped>
/* Fix dropdown bị che */
.form-select,
select.form-select {
  position: relative !important;
  z-index: 1050 !important;
}

.form-select option {
  color: #000000 !important;
  padding: 8px 12px !important;
}

/* Nếu có modal hoặc card cha */
.card,
.modal-content {
  overflow: visible !important;
}
/* ===== LEAFLET MAP ===== */
@import "leaflet/dist/leaflet.css";

.map-wrapper {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  border: 2px solid #e5e7eb;
  background: #f9fafb;
}

.map-loading {
  height: 400px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  color: #6b7280;
}

.map-loading i {
  font-size: 2rem;
}

.leaflet-map-container {
  width: 100%;
  height: 400px;
  z-index: 1;
}

.coords-display {
  padding: 0.75rem 1rem;
  background: #f0fdf4;
  border-radius: 8px;
  font-size: 0.9rem;
  color: #166534;
  border: 1px solid #bbf7d0;
}

.coords-display i {
  margin-right: 0.5rem;
}

/* ===== BASE STYLES ===== */
.property-posting-wrapper {
  max-width: 1600px;
  margin: 0 auto;
  padding: 2rem;
  background: #f8f9fa;
  min-height: 100vh;
}

.progress-header {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  margin-bottom: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.progress-track {
  display: flex;
  justify-content: space-between;
  max-width: 800px;
  margin: 0 auto;
  position: relative;
}

.progress-track::before {
  content: "";
  position: absolute;
  top: 20px;
  left: 0;
  right: 0;
  height: 2px;
  background: #e5e7eb;
  z-index: 0;
}

.progress-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
  position: relative;
  z-index: 1;
  flex: 1;
}

.step-indicator {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  color: #6b7280;
  transition: all 0.3s;
}

.progress-item.active .step-indicator {
  background: #001f7c;
  color: white;
  box-shadow: 0 0 0 4px rgba(0, 31, 124, 0.2);
}

.progress-item.completed .step-indicator {
  background: #10b981;
  color: white;
}

.step-name {
  font-size: 0.875rem;
  font-weight: 500;
  color: #6b7280;
}

.progress-item.active .step-name {
  color: #001f7c;
  font-weight: 600;
}

.posting-grid {
  display: grid;
  grid-template-columns: 250px 1fr 350px;
  gap: 1rem;
  align-items: start;
}

.sidebar-left {
  position: s;
  top: 1rem;
}

.sidebar-section {
  background: white;
  border-radius: 12px;
  padding: 1rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  margin-bottom: 1rem;
}

.sidebar-title {
  font-size: 1rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid #f3f4f6;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.875rem 1rem;
  border-radius: 8px;
  color: #4b5563;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.2s;
  cursor: pointer;
}

.nav-item:hover {
  background: #f9fafb;
  color: #001f7c;
}
.nav-item.active {
  background: #001f7c;
  color: white;
}
.nav-item i {
  width: 20px;
  font-size: 1.125rem;
}

.upgrade-box {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  text-align: center;
  padding: 2rem 1.5rem;
}

.upgrade-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}
.upgrade-box h4 {
  font-size: 1.125rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}
.upgrade-box p {
  font-size: 0.875rem;
  opacity: 0.9;
  margin-bottom: 1.25rem;
}

.btn-upgrade {
  width: 100%;
  padding: 0.875rem;
  background: white;
  color: #667eea;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s;
}

.btn-upgrade:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.main-form-area {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.section-header {
  margin-bottom: 2rem;
}
.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 0.5rem;
}
.section-desc {
  color: #6b7280;
  font-size: 0.95rem;
}

.form-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}
.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.form-group.full-width {
  grid-column: 1 / -1;
}

.form-label {
  font-weight: 600;
  color: #374151;
  font-size: 0.9rem;
}
.label-hint {
  font-weight: 400;
  font-size: 0.75rem;
  color: #6b7280;
}
.required::after {
  content: "*";
  color: #ef4444;
  margin-left: 0.25rem;
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 0.875rem 1rem;
  border: 2px solid #d1d5db;
  border-radius: 8px;
  font-size: 0.95rem;
  background: white;
  color: #111827;
  transition: all 0.2s;
  box-sizing: border-box;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: #001f7c;
  box-shadow: 0 0 0 3px rgba(0, 31, 124, 0.1);
}

.form-input::placeholder {
  color: #9ca3af;
}
.form-textarea {
  resize: vertical;
  min-height: 120px;
}
.char-count {
  text-align: right;
  font-size: 0.75rem;
  color: #6b7280;
}

.select-wrapper {
  position: relative;
}
.select-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
  pointer-events: none;
}

.input-group {
  display: flex;
  gap: 0;
}
.input-group .form-input {
  flex: 1;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
  border-right: none;
}
.input-addon {
  padding: 0.875rem 1rem;
  background: #f3f4f6;
  border: 2px solid #d1d5db;
  border-left: none;
  border-radius: 0 8px 8px 0;
  font-weight: 600;
  color: #374151;
  display: flex;
  align-items: center;
}

.form-hint {
  font-size: 0.8rem;
  color: #6b7280;
}

.button-group {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}
.btn-chip {
  padding: 0.75rem 1.5rem;
  border: 2px solid #d1d5db;
  background: white;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  color: #374151;
}
.btn-chip:hover {
  border-color: #001f7c;
  color: #001f7c;
}
.btn-chip.active {
  background: #001f7c;
  color: white;
  border-color: #001f7c;
}

.textarea-toolbar {
  display: flex;
  gap: 0.5rem;
  padding-top: 0.5rem;
  border-top: 1px solid #e5e7eb;
}
.toolbar-btn {
  padding: 0.5rem 0.75rem;
  background: #f3f4f6;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  color: #6b7280;
  transition: all 0.2s;
}
.toolbar-btn:hover {
  background: #e5e7eb;
  color: #001f7c;
}

.quick-upload-section {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 2px solid #f3f4f6;
}
.upload-card {
  border: 2px dashed #d1d5db;
  border-radius: 12px;
  padding: 3rem 2rem;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s;
  background: #f9fafb;
}
.upload-card:hover {
  border-color: #001f7c;
  background: rgba(0, 31, 124, 0.05);
}
.upload-icon-wrapper {
  width: 80px;
  height: 80px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
.upload-icon-wrapper i {
  font-size: 2.5rem;
  color: #001f7c;
}
.upload-content h4 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 0.5rem;
}
.upload-content p {
  color: #6b7280;
  margin-bottom: 0.75rem;
}
.upload-hint {
  font-size: 0.875rem;
  color: #9ca3af;
  display: inline-block;
  padding: 0.25rem 0.75rem;
  background: #f3f4f6;
  border-radius: 20px;
}
.hidden-input {
  display: none;
}

.image-preview-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 1rem;
  margin-top: 1.5rem;
}
.preview-item {
  position: relative;
  aspect-ratio: 1;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
.preview-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.remove-img {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  width: 32px;
  height: 32px;
  background: rgba(239, 68, 68, 0.9);
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.2s;
}
.preview-item:hover .remove-img {
  opacity: 1;
}
.main-badge {
  position: absolute;
  bottom: 0.5rem;
  left: 0.5rem;
  background: #f59e0b;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: #f9fafb;
  border-radius: 12px;
}
.empty-state i {
  font-size: 4rem;
  color: #d1d5db;
  margin-bottom: 1rem;
}
.empty-state h3 {
  font-size: 1.25rem;
  color: #374151;
  margin-bottom: 0.5rem;
}
.empty-state p {
  color: #9ca3af;
}

.form-actions {
  display: flex;
  gap: 1rem;
  margin-top: 2.5rem;
  padding-top: 2rem;
  border-top: 2px solid #f3f4f6;
  justify-content: flex-end;
}
.btn {
  padding: 0.875rem 2rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
  font-size: 0.95rem;
}
.btn-lg {
  padding: 1rem 2.5rem;
  font-size: 1rem;
}
.btn-primary {
  background: #001f7c;
  color: white;
}
.btn-primary:hover {
  background: #1e40af;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 31, 124, 0.3);
}
.btn-secondary {
  background: #6b7280;
  color: white;
}
.btn-secondary:hover {
  background: #4b5563;
}
.btn-success {
  background: #10b981;
  color: white;
}
.btn-success:hover {
  background: #059669;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}
.btn-outline {
  background: white;
  color: #001f7c;
  border: 2px solid #001f7c;
}
.btn-outline:hover {
  background: #f9fafb;
}

.auto-save-indicator {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 1.5rem;
  padding: 0.75rem 1rem;
  background: #f9fafb;
  border-radius: 8px;
  font-size: 0.875rem;
  color: #6b7280;
}
.auto-save-indicator.saving {
  color: #001f7c;
}

.sidebar-right {
  position: sticky;
  top: 1rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.info-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}
.card-header {
  padding: 1.25rem 1.5rem;
  background: #f9fafb;
  border-bottom: 2px solid #f3f4f6;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}
.card-header i {
  font-size: 1.25rem;
  color: #001f7c;
}
.card-header.warning i {
  color: #f59e0b;
}
.card-header h3 {
  font-size: 1rem;
  font-weight: 700;
  color: #111827;
}
.card-body {
  padding: 1.5rem;
}

.tips-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.tips-list li {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  font-size: 0.9rem;
  color: #374151;
}
.tips-list i {
  color: #10b981;
  margin-top: 0.125rem;
  flex-shrink: 0;
}

.stats-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}
.stat-item {
  text-align: center;
  padding: 1.25rem;
}
.stat-value {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
}
.stat-label {
  font-size: 0.875rem;
  opacity: 0.9;
}

.fade-in {
  animation: fadeIn 0.4s ease;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 1200px) {
  .posting-grid {
    grid-template-columns: 1fr;
  }
  .sidebar-left,
  .sidebar-right {
    position: static;
  }
  .sidebar-left {
    display: none;
  }
}

@media (max-width: 768px) {
  .property-posting-wrapper {
    padding: 1rem;
  }
  .progress-track {
    flex-direction: column;
    gap: 1rem;
  }
  .progress-track::before {
    display: none;
  }
  .form-row {
    grid-template-columns: 1fr;
  }
  .form-actions {
    flex-direction: column;
  }
  .btn {
    width: 100%;
    justify-content: center;
  }
  .leaflet-map-container {
    height: 300px;
  }
}
</style>