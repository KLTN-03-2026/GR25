<template>
    <div class="p-8 bg-[#F8F9FC] min-h-screen font-sans text-gray-800">

        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-bold text-[#202B5D]">Quản lý khách hàng</h2>
                <p class="text-gray-500 text-sm mt-2">Duy trì và quản lý cơ sở dữ liệu khách hàng tiềm năng.</p>
            </div>
            <div class="flex gap-3">
                <button
                    class="bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export CSV
                </button>
                <button @click="showAdd = true"
                    class="bg-[#202B5D] text-white hover:bg-blue-900 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Thêm khách hàng
                </button>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-5 rounded-xl border-l-4 border-[#202B5D] shadow-sm">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Tổng khách hàng</p>
                <h3 class="text-2xl font-bold text-gray-900">1,284</h3>
                <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    12% so với tháng trước
                </p>
            </div>
            <div class="bg-white p-5 rounded-xl border-l-4 border-green-500 shadow-sm">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Đang hoạt động</p>
                <h3 class="text-2xl font-bold text-gray-900">1,102</h3>
                <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    86% tỷ lệ duy trì
                </p>
            </div>
            <div class="bg-white p-5 rounded-xl border-l-4 border-yellow-500 shadow-sm">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Khách hàng mới</p>
                <h3 class="text-2xl font-bold text-gray-900">48</h3>
                <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Trong 7 ngày qua
                </p>
            </div>
            <div class="bg-white p-5 rounded-xl border-l-4 border-red-500 shadow-sm">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Bị khóa</p>
                <h3 class="text-2xl font-bold text-gray-900">12</h3>
                <p class="text-xs text-red-500 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Cần xem xét lại
                </p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm mb-4 flex justify-between items-center">
            <div class="flex gap-4">
                <div class="flex flex-col">
                    <label class="text-[10px] text-gray-500 uppercase font-semibold mb-1">Trạng thái</label>
                    <select
                        class="bg-gray-50 border border-gray-200 text-sm rounded-lg px-3 py-2 w-40 focus:outline-none focus:ring-1 focus:ring-[#202B5D]">
                        <option>Tất cả trạng thái</option>
                        <option>Đang hoạt động</option>
                        <option>Ngừng hoạt động</option>
                        <option>Bị khóa</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[10px] text-gray-500 uppercase font-semibold mb-1">Ngày tham gia</label>
                    <select
                        class="bg-gray-50 border border-gray-200 text-sm rounded-lg px-3 py-2 w-40 focus:outline-none focus:ring-1 focus:ring-[#202B5D]">
                        <option>Mọi thời gian</option>
                        <option>Tháng này</option>
                        <option>Tuần này</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[10px] text-gray-500 uppercase font-semibold mb-1">Sắp xếp theo</label>
                    <select
                        class="bg-gray-50 border border-gray-200 text-sm rounded-lg px-3 py-2 w-40 focus:outline-none focus:ring-1 focus:ring-[#202B5D]">
                        <option>Mới nhất</option>
                        <option>Cũ nhất</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">Bulk:</span>
                <button class="text-gray-400 hover:text-gray-700"><svg class="w-5 h-5" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                    </svg></button>
                <button class="text-gray-400 hover:text-gray-700"><svg class="w-5 h-5" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                            clip-rule="evenodd"></path>
                    </svg></button>
                <button class="text-red-400 hover:text-red-600"><svg class="w-5 h-5" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg></button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white border-b border-gray-100">
                    <tr>
                        <th class="py-4 px-6 w-12"><input type="checkbox"
                                class="rounded border-gray-300 text-[#202B5D] focus:ring-[#202B5D]"></th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Khách hàng
                        </th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Liên hệ</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Ngày tham gia
                        </th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">
                            BĐS Đã lưu</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">
                            Trạng thái</th>
                        <th class="py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-right">
                            Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    <tr v-if="isLoading">
                        <td colspan="7" class="text-center py-8 text-gray-500">Đang tải dữ liệu...</td>
                    </tr>

                    <tr v-for="(v, index) in danhSachKhachHang" :key="v.id || index"
                        class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6"><input type="checkbox"
                                class="rounded border-gray-300 text-[#202B5D] focus:ring-[#202B5D]"></td>

                        <td class="py-4 px-6 flex items-center gap-3">
                            <div class="relative">
                                <img :src="`https://ui-avatars.com/api/?name=${v.ten}&background=random`"
                                    class="w-10 h-10 rounded-full object-cover">
                                <span v-if="index === 0"
                                    class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ v.ten }}</p>
                                <p class="text-xs text-gray-400">ID: CUS-{{ String(v.id || index + 1).padStart(3, '0')
                                    }}</p>
                            </div>
                        </td>

                        <td class="py-4 px-6">
                            <p class="text-sm font-medium text-gray-800">{{ v.email }}</p>
                            <p class="text-xs text-gray-400">{{ v.so_dien_thoai }}</p>
                        </td>

                        <td class="py-4 px-6 text-sm text-gray-600 font-medium">
                            {{ v.created_at || '12/04/2023' }}
                        </td>

                        <td class="py-4 px-6 text-center">
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded bg-indigo-50 text-[#202B5D] font-bold text-xs">
                                {{ v.bds_da_luu || Math.floor(Math.random() * 20) }}
                            </span>
                        </td>

                        <td class="py-4 px-6 text-center">
                            <span v-if="index === 0"
                                class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-green-100 text-green-700">Đang
                                hoạt động</span>
                            <span v-else-if="index === 1"
                                class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-gray-100 text-gray-600">Ngừng
                                hoạt động</span>
                            <span v-else
                                class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-red-100 text-red-700">Bị
                                khóa</span>
                        </td>

                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-1.5 text-gray-400 hover:text-[#202B5D] hover:bg-gray-100 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                                <button @click="openEdit(v)"
                                    class="p-1.5 text-gray-400 hover:text-[#202B5D] hover:bg-gray-100 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                </button>
                                <button class="p-1.5 text-gray-400 hover:text-[#202B5D] hover:bg-gray-100 rounded"
                                    @click="xoaKhachHang(v)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="!isLoading && danhSachKhachHang.length === 0">
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            Không có dữ liệu khách hàng
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-sm text-gray-500">Hiển thị <span class="font-bold text-gray-800">1 - 3</span> trên
                    <span class="font-bold text-gray-800">1,284</span> khách hàng</span>
                <div class="flex gap-1">
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded text-gray-400 hover:bg-gray-100"><svg
                            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg></button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded bg-[#202B5D] text-white font-medium text-sm shadow-sm">1</button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded text-gray-600 hover:bg-gray-100 font-medium text-sm">2</button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded text-gray-600 hover:bg-gray-100 font-medium text-sm">3</button>
                    <span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded text-gray-600 hover:bg-gray-100 font-medium text-sm">42</button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded text-gray-400 hover:bg-gray-100"><svg
                            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg></button>
                </div>
            </div>
        </div>

        <div class="bg-[#F1F3F8] rounded-xl p-6 flex gap-4 border border-[#E2E8F0]">
            <div class="bg-[#202B5D] w-12 h-12 rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                    </path>
                </svg>
            </div>
            <div>
                <h4 class="text-[#202B5D] font-bold mb-1">Lưu ý bảo mật dữ liệu</h4>
                <p class="text-sm text-gray-600 leading-relaxed">Dữ liệu khách hàng là thông tin nhạy cảm. Việc xuất dữ
                    liệu CSV sẽ được ghi lại trong lịch sử hệ thống phục vụ mục đích kiểm soát. Vui lòng tuân thủ nghiêm
                    ngặt quy định bảo mật của công ty Architectural Curator.</p>
            </div>
        </div>

        <div v-if="showAdd" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-[#202B5D]/40 backdrop-blur-md" @click="showAdd = false"></div>

            <div
                class="relative bg-white w-full max-w-[450px] rounded-2xl shadow-[0_20px_50px_rgba(32,43,93,0.3)] overflow-hidden transform transition-all">

                <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4 bg-gray-50/50">
                    <div
                        class="w-12 h-12 bg-[#202B5D] rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-[#202B5D]">Thêm khách hàng</h3>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Tạo mới tài khoản người
                            dùng</p>
                    </div>
                </div>

                <div class="px-8 py-6 space-y-5">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Họ
                            và tên</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input v-model="khach_hang.ten" type="text" placeholder="Ví dụ: Nguyễn Văn A"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#202B5D] focus:ring-4 focus:ring-[#202B5D]/5 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                            <input v-model="khach_hang.email" type="email" placeholder="abc@gmail.com"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#202B5D] focus:ring-4 focus:ring-[#202B5D]/5 outline-none transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Số
                                điện thoại</label>
                            <input v-model="khach_hang.so_dien_thoai" type="text" placeholder="090..."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#202B5D] focus:ring-4 focus:ring-[#202B5D]/5 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Mật
                            khẩu khởi tạo</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-11V7a4 4 0 00-8 0v4h8z"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input v-model="khach_hang.password" type="password" placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#202B5D] focus:ring-4 focus:ring-[#202B5D]/5 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <button @click="showAdd = false"
                        class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
                        Hủy bỏ
                    </button>
                    <button @click="themKhachHang"
                        class="px-8 py-2.5 bg-[#202B5D] hover:bg-[#1a234a] text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 active:scale-95 transition-all flex items-center gap-2">
                        <span>Tạo tài khoản</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showEdit" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl w-[400px] shadow-2xl">
                <h3 class="text-xl font-bold text-[#202B5D] mb-4">Cập nhật thông tin</h3>
                <div class="space-y-3">
                    <input v-model="edit_khach_hang.ten"
                        class="border border-gray-300 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-[#202B5D]">
                    <input v-model="edit_khach_hang.email"
                        class="border border-gray-300 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-[#202B5D]">
                    <input v-model="edit_khach_hang.so_dien_thoai"
                        class="border border-gray-300 rounded-lg p-2.5 w-full text-sm focus:outline-none focus:ring-1 focus:ring-[#202B5D]">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button @click="showEdit = false"
                        class="text-gray-500 font-medium hover:bg-gray-100 px-4 py-2 rounded-lg text-sm">Hủy bỏ</button>
                    <button @click="capNhatKhachHang"
                        class="bg-[#202B5D] text-white font-medium hover:bg-blue-900 px-5 py-2 rounded-lg text-sm shadow-md">Lưu
                        thay đổi</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            danhSachKhachHang: [],
            isLoading: false,
            keyword: "",

            khach_hang: {
                ten: "",
                email: "",
                so_dien_thoai: "",
                password: ""
            },

            edit_khach_hang: {},

            showAdd: false,
            showEdit: false,
        };
    },

    mounted() {
        this.loadKhachHang();
    },

    methods: {
        // ================= LOAD =================
        loadKhachHang() {
            const token = localStorage.getItem("token");
            this.isLoading = true;

            axios.get("http://127.0.0.1:8000/api/admin/khach-hang/data", {
                headers: { Authorization: `Bearer ${token}` }
            })
                .then(res => {
                    if (res.data.status) {
                        this.danhSachKhachHang = res.data.data;
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(() => this.$toast.error("Lỗi load dữ liệu"))
                .finally(() => this.isLoading = false);
        },

        // ================= SEARCH (Nếu cần dùng với Component cha) =================
        searchKhachHang() {
            const token = localStorage.getItem("token");

            axios.post("http://127.0.0.1:8000/api/admin/khach-hang/search", {
                keyword: this.keyword
            }, {
                headers: { Authorization: `Bearer ${token}` }
            })
                .then(res => {
                    if (res.data.status) {
                        this.danhSachKhachHang = res.data.data;
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(() => this.$toast.error("Lỗi tìm kiếm"));
        },

        // ================= ADD =================
        themKhachHang() {
            axios.post("http://127.0.0.1:8000/api/khach-hang/register", this.khach_hang)
                .then(res => {
                    if (res.data.status) {
                        this.$toast.success("Thêm thành công");
                        this.showAdd = false;
                        this.loadKhachHang();

                        this.khach_hang = {
                            ten: "",
                            email: "",
                            so_dien_thoai: "",
                            password: ""
                        };
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(() => this.$toast.error("Lỗi thêm"));
        },

        // ================= UPDATE =================
        openEdit(v) {
            this.edit_khach_hang = { ...v };
            this.showEdit = true;
        },

        capNhatKhachHang() {
            const token = localStorage.getItem("token");

            axios.post("http://127.0.0.1:8000/api/admin/khach-hang/update", this.edit_khach_hang, {
                headers: { Authorization: `Bearer ${token}` }
            })
                .then(res => {
                    if (res.data.status) {
                        this.$toast.success("Cập nhật thành công");
                        this.showEdit = false;
                        this.loadKhachHang();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(() => this.$toast.error("Lỗi cập nhật"));
        },

        // ================= DELETE =================
        xoaKhachHang(v) {
            if (!confirm("Bạn có chắc muốn xóa?")) return;

            const token = localStorage.getItem("token");

            axios.post("http://127.0.0.1:8000/api/admin/khach-hang/delete", {
                id: v.id
            }, {
                headers: { Authorization: `Bearer ${token}` }
            })
                .then(res => {
                    if (res.data.status) {
                        this.$toast.success("Xóa thành công");
                        this.loadKhachHang();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(() => this.$toast.error("Lỗi xóa"));
        }
    }
};
</script>