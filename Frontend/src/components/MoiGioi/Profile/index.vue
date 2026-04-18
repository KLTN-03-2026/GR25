<template>
  <div class="max-w-6xl mx-auto p-6 bg-slate-50 min-h-screen space-y-6 font-sans">

    <!-- HEADER -->
    <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
      <div class="h-40 bg-[#1a237e] relative"></div>

      <div class="px-10 pb-8 flex justify-between items-end">
        <div class="flex items-end space-x-6 -mt-16 relative">

          <div class="relative group">
            <img :src="defaultAvatar"
              class="w-40 h-40 rounded-3xl border-4 border-white shadow-lg object-cover bg-white">

            <button
              class="absolute bottom-3 right-3 bg-white p-2 rounded-xl shadow-md border border-slate-100 text-[#1a237e]">
              <i class="pi pi-camera"></i>
            </button>
          </div>

          <div class="pb-2">
            <h3 class="text-3xl font-bold text-slate-900">
              {{ profile.ten }}
            </h3>

            <p class="text-slate-500 mt-1">
              <span class="text-emerald-600 font-bold text-sm">
                {{ profile.mo_ta || 'Chuyên viên tư vấn cao cấp' }}
              </span>

              <span class="mx-2 opacity-30">•</span>

              <span class="text-sm">
                Mã số: MG-{{ profile.id }}
              </span>
            </p>
          </div>
        </div>

        <button @click="saveProfile" :disabled="loading"
          class="!bg-[#1a237e] text-white px-8 py-3 rounded-2xl flex items-center space-x-2 font-bold text-sm shadow-indigo-100 shadow-xl hover:bg-blue-900 transition-all mb-2">
          <i class="pi pi-pencil text-xs"></i>
          <span>
            {{ loading ? 'Đang lưu...' : 'Chỉnh sửa hồ sơ' }}
          </span>
        </button>
      </div>
    </div>

    <div class="grid grid-cols-12 gap-6">

      <!-- LEFT -->
      <div class="col-span-12 lg:col-span-8 space-y-6">

        <!-- THÔNG TIN CÁ NHÂN -->
        <div class="bg-white p-10 rounded-[32px] shadow-sm border border-slate-100">

          <div class="flex items-center space-x-3 mb-8">
            <i class="pi pi-id-card text-[#1a237e] text-xl"></i>
            <h4 class="font-bold text-xl text-slate-900">
              Thông tin cá nhân
            </h4>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

            <div v-for="(field, index) in infoFields" :key="index">
              <label class="text-[11px] uppercase font-black text-slate-400 tracking-widest mb-2 block">
                {{ field.label }}
              </label>

              <div class="flex items-center space-x-4 bg-slate-50/80 p-4 rounded-2xl border border-slate-50">
                <i :class="field.icon" class="text-slate-400"></i>

                <span class="text-[15px] text-slate-700 font-medium">
                  {{ field.value }}
                </span>
              </div>
            </div>

          </div>
        </div>

        <!-- BẢO MẬT -->
        <div class="bg-white p-10 rounded-[32px] shadow-sm border border-slate-100">

          <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-3">
              <i class="pi pi-shield text-[#1a237e] text-xl"></i>
              <h4 class="font-bold text-xl text-slate-900">
                Quản lý bảo mật
              </h4>
            </div>

            <span
              class="bg-emerald-50 text-emerald-600 text-[11px] font-black px-4 py-1.5 rounded-full border border-emerald-100">
              AN TOÀN
            </span>
          </div>

          <!-- FORM ĐỔI MẬT KHẨU -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
              <label class="text-xs font-bold text-slate-500 mb-2 block">
                Mật khẩu hiện tại
              </label>

              <input type="password" v-model="passwordForm.old_password"
                class="w-full border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:border-[#1a237e]">
            </div>

            <div>
              <label class="text-xs font-bold text-slate-500 mb-2 block">
                Mật khẩu mới
              </label>

              <input type="password" v-model="passwordForm.password"
                class="w-full border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:border-[#1a237e]">
            </div>

            <div class="md:col-span-2">
              <label class="text-xs font-bold text-slate-500 mb-2 block">
                Xác nhận mật khẩu mới
              </label>

              <input type="password" v-model="passwordForm.re_password"
                class="w-full border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:border-[#1a237e]">
            </div>

          </div>

          <button @click="changePassword"
            class="mt-3 !bg-[#1a237e] text-white px-8 py-3 rounded-2xl font-bold hover:!bg-blue-900 transition-all">Đổi
            mật khẩu</button>

        </div>
      </div>

      <!-- RIGHT -->
      <div class="col-span-12 lg:col-span-4 space-y-6">

        <!-- GIỮ NGUYÊN -->
        <div class="bg-[#1a237e] text-white p-10 rounded-[32px] shadow-2xl shadow-indigo-200 relative overflow-hidden">
          <p class="text-[11px] uppercase font-bold text-indigo-300 tracking-[0.2em] mb-3">
            Tổng giá trị giao dịch
          </p>

          <div class="flex items-baseline space-x-2">
            <h4 class="text-5xl font-black">1.2T</h4>
            <span class="text-lg font-medium text-indigo-300 italic uppercase">
              VND
            </span>
          </div>

          <div class="mt-6 flex items-center space-x-2 text-emerald-400 text-[13px] font-bold">
            <i class="pi pi-arrow-up-right"></i>
            <span>+15% so với năm ngoái</span>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm text-center">
            <p class="text-[10px] uppercase font-bold text-slate-400 mb-2 tracking-widest">
              Tổng giao dịch
            </p>

            <p class="text-2xl font-black text-[#1a237e]">200+</p>
          </div>

          <div class="bg-emerald-50/50 p-6 rounded-[24px] border border-emerald-100 shadow-sm text-center">
            <p class="text-[10px] uppercase font-bold text-emerald-600 mb-2 tracking-widest">
              Tỷ lệ quay lại
            </p>

            <p class="text-2xl font-black text-emerald-700">98%</p>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from "vue"
import axios from "axios"
import Swal from "sweetalert2"

const loading = ref(false)

const profile = reactive({
  id: "",
  ten: "",
  email: "",
  so_dien_thoai: "",
  dia_chi: "",
  khu_vuc_hoat_dong: "",
  mo_ta: ""
})

const passwordForm = reactive({
  old_password: "",
  password: "",
  re_password: ""
})

const defaultAvatar =
  "https://ui-avatars.com/api/?name=Moi+Gioi&background=1a237e&color=fff"

const token = localStorage.getItem("auth_token")

const infoFields = computed(() => [
  {
    label: "Email",
    value: profile.email,
    icon: "pi pi-envelope"
  },
  {
    label: "Số điện thoại",
    value: profile.so_dien_thoai,
    icon: "pi pi-phone"
  },
  {
    label: "Địa chỉ",
    value: profile.dia_chi || "Chưa cập nhật",
    icon: "pi pi-map-marker"
  },
  {
    label: "Khu vực hoạt động",
    value: profile.khu_vuc_hoat_dong || "Chưa cập nhật",
    icon: "pi pi-map"
  }
])

const fetchProfile = async () => {
  try {
    const res = await axios.get(
      "http://127.0.0.1:8000/api/moi-gioi/profile",
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )

    if (res.data.status) {
      Object.assign(profile, res.data.data)
    }

  } catch (error) {
    Swal.fire("Lỗi", "Không tải được hồ sơ", "error")
  }
}

const saveProfile = async () => {
  loading.value = true

  try {
    await axios.post(
      "http://127.0.0.1:8000/api/moi-gioi/update-profile",
      profile,
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )

    Swal.fire("Thành công", "Cập nhật hồ sơ thành công", "success")

  } catch (error) {
    Swal.fire("Lỗi", "Cập nhật thất bại", "error")
  }

  loading.value = false
}

const changePassword = async () => {
  if (
    passwordForm.password !==
    passwordForm.re_password
  ) {
    Swal.fire("Lỗi", "Xác nhận mật khẩu không khớp", "error")
    return
  }

  try {
    await axios.post(
      "http://127.0.0.1:8000/api/moi-gioi/update-password",
      passwordForm,
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )

    Swal.fire("Thành công", "Đổi mật khẩu thành công", "success")

    passwordForm.old_password = ""
    passwordForm.password = ""
    passwordForm.re_password = ""

  } catch (error) {
    Swal.fire("Lỗi", "Đổi mật khẩu thất bại", "error")
  }
}

onMounted(() => {
  fetchProfile()
})
</script>