<template>
    <div class="flex min-h-screen bg-gray-100 font-sans text-slate-800">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white p-6 shadow-sm hidden md:block">
            <div class="mb-8">
                <h2 class="text-indigo-900 font-bold text-lg">Premium Member</h2>
                <p class="text-xs text-gray-400 uppercase">Verified Client</p>
            </div>

            <nav class="space-y-4">
                <a href="#" class="flex items-center space-x-3 p-3 bg-indigo-50 text-indigo-700 rounded-lg font-medium">
                    <i class="pi pi-user"></i>
                    <span>Personal Info</span>
                </a>

                <a href="#" class="flex items-center space-x-3 p-3 text-gray-500 hover:bg-gray-50 rounded-lg">
                    <i class="pi pi-heart"></i>
                    <span>Saved Properties</span>
                </a>

                <a href="#" class="flex items-center space-x-3 p-3 text-gray-500 hover:bg-gray-50 rounded-lg">
                    <i class="pi pi-shield"></i>
                    <span>Security</span>
                </a>
            </nav>

            <div class="mt-10 bg-indigo-900 p-4 rounded-xl text-white text-center">
                <button class="font-semibold text-sm">Upgrade to Platinum</button>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="flex-1 p-8">

            <!-- HEADER -->
            <header class="bg-white rounded-2xl p-6 mb-6 flex justify-between items-center shadow-sm">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img :src="avatarUrl" class="w-20 h-20 rounded-xl object-cover" />

                        <span
                            class="absolute -bottom-2 -right-2 bg-teal-400 text-[10px] text-white px-2 py-0.5 rounded-full border-2 border-white">
                            GOLD TIER
                        </span>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-indigo-950">
                            {{ profile.ten }}
                        </h1>

                        <div class="flex items-center space-x-2">
                            <span
                                class="bg-teal-50 text-teal-600 text-xs px-2 py-1 rounded-md border border-teal-100 font-medium">
                                Premium Member
                            </span>

                            <span class="text-xs text-gray-400 italic">
                                Hội viên từ {{ joinDate }}
                            </span>
                        </div>
                    </div>
                </div>

                <button @click="updateProfile"
                    class="!bg-indigo-950 text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-indigo-800 transition">
                    Edit Profile
                </button>
            </header>

            <div class="grid grid-cols-12 gap-6">

                <!-- LEFT -->
                <div class="col-span-12 lg:col-span-8 space-y-6">

                    <!-- THÔNG TIN CÁ NHÂN -->
                    <section class="bg-white rounded-2xl p-8 shadow-sm">
                        <h3 class="text-lg font-bold mb-6">Thông tin cá nhân</h3>

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                    Full Name
                                </label>

                                <input v-model="profile.ten" type="text"
                                    class="w-full bg-gray-50 border-none rounded-lg p-3 text-sm focus:ring-2 ring-indigo-500" />
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                    Email Address
                                </label>

                                <input v-model="profile.email" type="email"
                                    class="w-full bg-gray-50 border-none rounded-lg p-3 text-sm" />
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                    Phone Number
                                </label>

                                <input v-model="profile.so_dien_thoai" type="text"
                                    class="w-full bg-gray-50 border-none rounded-lg p-3 text-sm" />
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">
                                    Location
                                </label>

                                <input value="Việt Nam" type="text"
                                    class="w-full bg-gray-50 border-none rounded-lg p-3 text-sm" />
                            </div>

                        </div>

                        <div
                            class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg flex items-start space-x-3">
                            <i class="pi pi-exclamation-triangle text-yellow-600 mt-1"></i>

                            <div>
                                <p class="font-bold text-sm text-yellow-800">
                                    Cập nhật hồ sơ
                                </p>

                                <p class="text-xs text-yellow-700">
                                    Thông tin cá nhân của bạn đang ở mức 85%.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- ĐỔI MẬT KHẨU -->
                    <section class="bg-white rounded-2xl p-8 shadow-sm">
                        <h3 class="text-lg font-bold mb-6">
                            Đổi mật khẩu
                        </h3>

                        <div class="grid grid-cols-1 gap-4">

                            <div>
                                <label class="block text-sm mb-2 font-medium">
                                    Mật khẩu cũ
                                </label>

                                <input v-model="password.old_password" type="password"
                                    class="w-full border rounded-lg p-3" />
                            </div>

                            <div>
                                <label class="block text-sm mb-2 font-medium">
                                    Mật khẩu mới
                                </label>

                                <input v-model="password.password" type="password"
                                    class="w-full border rounded-lg p-3" />
                            </div>

                            <div>
                                <label class="block text-sm mb-2 font-medium">
                                    Xác nhận mật khẩu mới
                                </label>

                                <input v-model="password.password_confirmation" type="password"
                                    class="w-full border rounded-lg p-3" />
                            </div>

                            <button @click="changePassword"
                                class="!bg-indigo-900 text-white px-6 py-3 rounded-lg hover:bg-indigo-800">
                                Cập nhật mật khẩu
                            </button>

                        </div>
                    </section>

                </div>

                <!-- RIGHT -->
                <div class="col-span-12 lg:col-span-4 space-y-6">

                    <!-- GIỮ NGUYÊN -->
                    <section class="bg-white rounded-2xl p-6 shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold">Bất động sản đã lưu</h3>
                            <a href="#" class="text-xs text-teal-600 font-bold">
                                VIEW ALL
                            </a>
                        </div>

                        <div class="space-y-4">

                            <div class="flex space-x-3">
                                <img src="https://via.placeholder.com/60" class="w-16 h-16 rounded-lg object-cover" />
                                <div>
                                    <h4 class="text-xs font-bold">Vinhomes Golden River</h4>
                                    <p class="text-[10px] text-gray-400">Quận 1, TP. HCM</p>
                                    <p class="text-teal-600 font-bold text-xs">From $450,000</p>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <img src="https://via.placeholder.com/60" class="w-16 h-16 rounded-lg object-cover" />
                                <div>
                                    <h4 class="text-xs font-bold">The Metropole Thủ Thiêm</h4>
                                    <p class="text-[10px] text-gray-400">TP. Thủ Đức</p>
                                    <p class="text-teal-600 font-bold text-xs">From $620,000</p>
                                </div>
                            </div>

                        </div>
                    </section>

                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from "vue"
import axios from "axios"
import Swal from "sweetalert2"

const token = localStorage.getItem("auth_token")

const profile = reactive({
    ten: "",
    email: "",
    so_dien_thoai: "",
    created_at: ""
})

const password = reactive({
    old_password: "",
    password: "",
    password_confirmation: ""
})

const avatarUrl = computed(() => {
    return `https://ui-avatars.com/api/?name=${profile.ten}&background=1e1b4b&color=fff`
})

const joinDate = computed(() => {
    if (!profile.created_at) return "gần đây"

    return new Date(profile.created_at).toLocaleDateString("vi-VN", {
        month: "long",
        year: "numeric"
    })
})

const loadProfile = async () => {
    try {
        const res = await axios.get(
            "http://127.0.0.1:8000/api/khach-hang/profile",
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

const updateProfile = async () => {
    try {
        const payload = {
            ten: profile.ten,
            email: profile.email,
            so_dien_thoai: profile.so_dien_thoai
        }

        const res = await axios.post(
            "http://127.0.0.1:8000/api/khach-hang/update-profile",
            payload,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        Swal.fire("Thành công", res.data.message, "success")

    } catch (error) {
        Swal.fire("Lỗi", "Cập nhật thất bại", "error")
    }
}

const changePassword = async () => {
    try {
        const res = await axios.post(
            "http://127.0.0.1:8000/api/khach-hang/update-password",
            password,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        Swal.fire("Thành công", res.data.message, "success")

        password.old_password = ""
        password.password = ""
        password.password_confirmation = ""

    } catch (error) {
        Swal.fire(
            "Lỗi",
            error.response?.data?.message || "Đổi mật khẩu thất bại",
            "error"
        )
    }
}

onMounted(() => {
    loadProfile()
})
</script>

<style>
body {
    background-color: #f3f4f6;
}
</style>