<template>
  <div
    class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen font-['Inter']"
  >
    <!-- Hero Section -->
    <section
      class="relative h-[600px] lg:h-[700px] flex items-center overflow-hidden"
    >
      <div class="absolute inset-0">
        <img
          src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80"
          class="w-full h-full object-cover"
          alt="Luxury Real Estate"
        />
        <div
          class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/80 to-transparent"
        ></div>
      </div>

      <div class="relative z-10 container mx-auto px-6 lg:px-12">
        <div class="max-w-3xl">
          <div
            class="inline-block px-4 py-2 bg-blue-600/20 backdrop-blur-sm border border-blue-400/30 rounded-full mb-6"
          >
            <span class="text-blue-300 text-sm font-semibold tracking-wide"
              >BẤT ĐỘNG SẢN CAO CẤP</span
            >
          </div>
          <h1
            class="font-['Be_Vietnam_Pro'] text-5xl lg:text-7xl font-bold text-white leading-tight mb-6"
          >
            Kiến tạo không gian<br />
            <span
              class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300"
              >sống thượng lưu</span
            >
          </h1>
          <p class="text-lg text-gray-300 mb-10 max-w-xl leading-relaxed">
            Khám phá những bất động sản đẳng cấp nhất, được tuyển chọn kỹ lưỡng
            cho phong cách sống đặc quyền.
          </p>

          <!-- Quick Stats -->
          <div class="flex gap-8">
            <template v-for="(stat, index) in stats" :key="stat.id">
              <div>
                <div class="text-3xl font-bold text-white uppercase">
                  {{ stat.current }}{{ stat.suffix }}
                </div>
                <div class="text-sm text-gray-400">{{ stat.label }}</div>
              </div>
              <div
                v-if="index < stats.length - 1"
                class="w-px bg-gray-600"
              ></div>
            </template>
          </div>
        </div>
      </div>
    </section>

    <!-- Search Section -->
    <section class="relative -mt-20 z-20 px-6">
      <div class="container mx-auto max-w-5xl">
        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
              <label
                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2"
                >Vị trí</label
              >
              <div class="relative">
                <span
                  class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                  >location_on</span
                >
                <input
                  v-model="search.location"
                  placeholder="Quận, thành phố..."
                  class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                />
              </div>
            </div>

            <div class="relative">
              <label
                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2"
              >
                Loại hình
              </label>
              <div class="relative">
                <span
                  class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                  >category</span
                >
                <select
                  v-model="search.type"
                  class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer"
                >
                  <option value="">Tất cả</option>
                  <!-- ✅ Duyệt từ API -->
                  <option
                    v-for="loai in propertyTypes"
                    :key="loai.id"
                    :value="loai.id"
                  >
                    {{ loai.ten_loai }}
                  </option>
                </select>
              </div>
            </div>

            <div class="relative">
              <label
                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2"
                >Khoảng giá</label
              >
              <div class="relative">
                <span
                  class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                  >payments</span
                >
                <select
                  v-model="search.price"
                  class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer"
                >
                  <option value="">Mức giá</option>
                  <option>Dưới 10 tỷ</option>
                  <option>10 - 30 tỷ</option>
                  <option>Trên 30 tỷ</option>
                </select>
              </div>
            </div>

            <div class="flex items-end">
              <button
                @click="handleSearch"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl shadow-lg transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2"
                style="background-color: #2563eb; border-radius: 10px"
              >
                <span class="material-symbols-outlined text-lg">search</span>
                Tìm kiếm
              </button>
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
            <h2
              class="font-['Be_Vietnam_Pro'] text-4xl font-bold text-slate-800 mb-3"
            >
              Bất động sản nổi bật
            </h2>
            <p class="text-gray-500">Những cơ hội đầu tư sinh lời cao nhất</p>
          </div>
          <a
            href="/khach-hang/danh-sach-bat-dong-san"
            class="hidden md:flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-700 transition-colors"
          >
            Xem tất cả
            <span class="material-symbols-outlined">arrow_forward</span>
          </a>
        </div>

        <div v-if="loading" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="i in 3" :key="i" class="animate-pulse">
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

        <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
          <router-link
            v-for="item in properties"
            :key="item.id"
            :to="`/khach-hang/chi-tiet-bat-dong-san/${item.id}`"
            class="group block bg-white rounded-3xl overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_60px_rgba(37,99,235,0.1)] transition-all duration-500 cursor-pointer border border-gray-100/50 hover:-translate-y-3"
          >
            <div class="relative h-80 overflow-hidden">
              <img
                :src="item.image"
                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                :alt="item.name"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-500 pointer-events-none"
              ></div>

              <div class="absolute top-5 left-5 pointer-events-none">
                <span
                  class="px-4 py-1.5 bg-white/90 backdrop-blur-md text-slate-900 text-[10px] font-black uppercase tracking-[0.15em] rounded-full shadow-sm"
                >
                  Exclusive
                </span>
              </div>
            </div>

            <div class="p-8">
              <div class="flex items-center gap-2 mb-3">
                <span
                  class="text-blue-600 font-bold text-[11px] uppercase tracking-widest"
                  >{{ item.loai }}</span
                >
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span
                  class="text-gray-400 text-[11px] font-medium uppercase tracking-widest"
                  >{{ item.location.split(",")[0] }}</span
                >
              </div>

              <h3
                class="font-['Be_Vietnam_Pro'] text-2xl font-bold text-slate-800 mb-6 leading-tight group-hover:text-blue-600 transition-colors line-clamp-2 h-[67px]"
              >
                {{ item.name }}
              </h3>

              <div class="flex items-baseline justify-between">
                <div>
                  <p
                    class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold mb-1"
                  >
                    Giá đặc quyền
                  </p>
                  <p class="text-2xl font-black text-slate-900">
                    {{ formatPriceDisplay(item.gia) }}
                  </p>
                </div>

                <div
                  class="flex items-center gap-2 text-blue-600 font-semibold text-sm group/btn"
                >
                  <span>Chi tiết</span>
                  <span
                    class="material-symbols-outlined transition-transform group-hover/btn:translate-x-1"
                    >arrow_right_alt</span
                  >
                </div>
              </div>
            </div>
          </router-link>
        </div>

        <!-- Empty State -->
        <div
          v-if="!loading && properties.length === 0"
          class="text-center py-20"
        >
          <div
            class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
          >
            <span class="material-symbols-outlined text-gray-400 text-5xl"
              >search_off</span
            >
          </div>
          <h3 class="text-xl font-bold text-gray-700 mb-2">
            Không tìm thấy bất động sản
          </h3>
          <p class="text-gray-500">
            Hãy thử điều chỉnh tiêu chí tìm kiếm của bạn
          </p>
        </div>
      </div>
    </section>

    <!-- Đặt ngay trước section <section class="py-16 bg-white"> của Banner -->
    <!-- Pricing Section -->
    <section class="py-24 bg-gradient-to-b from-gray-50 to-white">
      <div class="container mx-auto max-w-7xl px-6">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
          <div class="inline-block px-4 py-2 bg-blue-100 rounded-full mb-4">
            <span class="text-blue-700 text-sm font-bold tracking-wide"
              >AI ĐỊNH GIÁ THÔNG MINH</span
            >
          </div>
          <h2
            class="font-['Be_Vietnam_Pro'] text-[36px] md:text-[44px] font-bold text-[#0a0e27] mb-6 leading-tight"
          >
            Chọn gói phù hợp với<br />
            <span
              class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
            >
              nhu cầu của bạn
            </span>
          </h2>
          <p class="text-gray-500 text-[16px] leading-relaxed">
            Đầu tư thông minh - Hiệu quả tối đa. Tất cả gói đều tích hợp AI định
            giá chính xác đến 95%.
          </p>
        </div>

        <!-- Pricing Toggle -->
        <div class="flex justify-center items-center gap-4 mb-12">
          <span
            :class="!isYearly ? 'text-blue-600 font-bold' : 'text-gray-400'"
            class="text-sm transition-colors"
          >
            Thanh toán tháng
          </span>
          <button
            @click="isYearly = !isYearly"
            class="relative w-16 h-8 bg-gray-200 rounded-full transition-colors duration-300 focus:outline-none"
            :class="isYearly ? 'bg-blue-600' : 'bg-gray-300'"
          >
            <div
              class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full shadow-md transition-transform duration-300"
              :class="isYearly ? 'translate-x-8' : 'translate-x-0'"
            ></div>
          </button>
          <span
            :class="isYearly ? 'text-blue-600 font-bold' : 'text-gray-400'"
            class="text-sm transition-colors"
          >
            Thanh toán năm
            <span
              class="ml-2 px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full"
              >Tiết kiệm 30%</span
            >
          </span>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- FREE -->
          <div
            class="bg-white rounded-[28px] p-8 border-2 border-gray-200 hover:border-gray-300 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
          >
            <div class="mb-6">
              <h3
                class="font-['Be_Vietnam_Pro'] text-xl font-bold text-gray-700 mb-2"
              >
                Dùng Thử
              </h3>
              <p class="text-gray-400 text-sm">Cho người mới bắt đầu</p>
            </div>
            <div class="mb-6">
              <span class="text-5xl font-black text-gray-900">0đ</span>
              <span class="text-gray-400 text-sm">/tháng</span>
            </div>
            <ul class="space-y-3 mb-8">
              <li
                v-for="(feature, i) in freeFeatures"
                :key="i"
                class="flex items-start gap-3"
              >
                <span
                  class="material-symbols-outlined text-green-500 text-[18px] flex-shrink-0"
                  >check_circle</span
                >
                <span class="text-gray-600 text-sm">{{ feature }}</span>
              </li>
            </ul>
            <button
              class="w-full py-3.5 rounded-xl border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-300"
            >
              Bắt đầu miễn phí
            </button>
          </div>

          <!-- STANDARD -->
          <div
            class="bg-white rounded-[28px] p-8 border-2 border-blue-100 hover:border-blue-300 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl relative"
          >
            <div
              class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full"
            >
              PHỔ BIẾN
            </div>
            <div class="mb-6">
              <h3
                class="font-['Be_Vietnam_Pro'] text-xl font-bold text-gray-900 mb-2"
              >
                Standard
              </h3>
              <p class="text-gray-400 text-sm">Cho môi giới cá nhân</p>
            </div>
            <div class="mb-6">
              <span class="text-5xl font-black text-[#0a0e27]">{{
                isYearly ? "2,390" : "299"
              }}</span>
              <span class="text-gray-400 text-sm"
                >k/{{ isYearly ? "năm" : "tháng" }}</span
              >
            </div>
            <ul class="space-y-3 mb-8">
              <li
                v-for="(feature, i) in standardFeatures"
                :key="i"
                class="flex items-start gap-3"
              >
                <span
                  class="material-symbols-outlined text-blue-600 text-[18px] flex-shrink-0"
                  >check_circle</span
                >
                <span class="text-gray-600 text-sm">{{ feature }}</span>
              </li>
            </ul>
            <button
              style="background-color: azure; color: dark; border-radius: 20px"
              class="w-full py-3.5 rounded-xl bg-blue-600 font-semibold hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5"
            >
              Đăng ký ngay
            </button>
          </div>

          <!-- PRO -->
          <div
            class="bg-gradient-to-br from-[#0a0e27] to-[#1a237e] rounded-[28px] p-8 text-white relative overflow-hidden transform scale-105 shadow-2xl"
          >
            <div
              class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl"
            ></div>
            <div
              class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-yellow-400 text-[#0a0e27] text-xs font-bold rounded-full"
            >
              TỐT NHẤT
            </div>
            <div class="mb-6 relative z-10">
              <h3 class="font-['Be_Vietnam_Pro'] text-xl font-bold mb-2">
                Professional
              </h3>
              <p class="text-gray-300 text-sm">Cho môi giới chuyên nghiệp</p>
            </div>
            <div class="mb-6 relative z-10">
              <span class="text-5xl font-black">{{
                isYearly ? "5,590" : "699"
              }}</span>
              <span class="text-gray-300 text-sm"
                >k/{{ isYearly ? "năm" : "tháng" }}</span
              >
            </div>
            <ul class="space-y-3 mb-8 relative z-10">
              <li
                v-for="(feature, i) in proFeatures"
                :key="i"
                class="flex items-start gap-3"
              >
                <span
                  class="material-symbols-outlined text-yellow-400 text-[18px] flex-shrink-0"
                  >check_circle</span
                >
                <span class="text-gray-200 text-sm">{{ feature }}</span>
              </li>
            </ul>
            <button
              style="border-radius: 20px"
              class="w-full py-3.5 rounded-xl bg-white text-[#0a0e27] font-bold hover:bg-gray-100 shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 relative z-10"
            >
              Nâng cấp Pro
            </button>
          </div>

          <!-- PREMIUM -->
          <div
            class="bg-white rounded-[28px] p-8 border-2 border-purple-200 hover:border-purple-400 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
          >
            <div class="mb-6">
              <h3
                class="font-['Be_Vietnam_Pro'] text-xl font-bold text-gray-900 mb-2"
              >
                Premium
              </h3>
              <p class="text-gray-400 text-sm">Cho công ty BĐS</p>
            </div>
            <div class="mb-6">
              <span class="text-5xl font-black text-[#0a0e27]">{{
                isYearly ? "12,490" : "1,499"
              }}</span>
              <span class="text-gray-400 text-sm"
                >k/{{ isYearly ? "năm" : "tháng" }}</span
              >
            </div>
            <ul class="space-y-3 mb-8">
              <li
                v-for="(feature, i) in premiumFeatures"
                :key="i"
                class="flex items-start gap-3"
              >
                <span
                  class="material-symbols-outlined text-purple-600 text-[18px] flex-shrink-0"
                  >check_circle</span
                >
                <span class="text-gray-600 text-sm">{{ feature }}</span>
              </li>
            </ul>
            <button
              class="w-full py-3.5 rounded-xl border-2 border-purple-300 text-purple-700 font-semibold hover:bg-purple-50 transition-all duration-300"
            >
              Liên hệ tư vấn
            </button>
          </div>
        </div>

        <!-- Features Comparison -->
        <!-- <div
          class="mt-20 bg-white rounded-[32px] p-8 md:p-12 shadow-lg border border-gray-100"
        >
          <h3
            class="font-['Be_Vietnam_Pro'] text-3xl font-bold text-center text-[#0a0e27] mb-12"
          >
            So sánh tính năng chi tiết
          </h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b-2 border-gray-100">
                  <th class="text-left py-4 px-4 font-semibold text-gray-700">
                    Tính năng
                  </th>
                  <th class="text-center py-4 px-4 font-semibold text-gray-700">
                    Free
                  </th>
                  <th class="text-center py-4 px-4 font-semibold text-blue-600">
                    Standard
                  </th>
                  <th
                    class="text-center py-4 px-4 font-semibold text-[#0a0e27] bg-blue-50 rounded-lg"
                  >
                    Pro
                  </th>
                  <th
                    class="text-center py-4 px-4 font-semibold text-purple-600"
                  >
                    Premium
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(row, i) in comparisonTable"
                  :key="i"
                  class="border-b border-gray-50 hover:bg-gray-50 transition-colors"
                >
                  <td class="py-4 px-4 text-gray-700 font-medium">
                    {{ row.feature }}
                  </td>
                  <td class="text-center py-4 px-4 text-gray-600">
                    {{ row.free }}
                  </td>
                  <td class="text-center py-4 px-4 text-gray-600">
                    {{ row.standard }}
                  </td>
                  <td class="text-center py-4 px-4 bg-blue-50/50 rounded-lg">
                    <span
                      class="material-symbols-outlined text-green-500 text-[20px]"
                      >check_circle</span
                    >
                  </td>
                  <td class="text-center py-4 px-4 text-gray-600">
                    <span
                      class="material-symbols-outlined text-green-500 text-[20px]"
                      >check_circle</span
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div> -->

        <!-- FAQ -->
        <div class="mt-16 text-center">
          <h3
            class="font-['Be_Vietnam_Pro'] text-2xl font-bold text-[#0a0e27] mb-4"
          >
            Câu hỏi thường gặp
          </h3>
          <p class="text-gray-500 mb-8">
            Chưa tìm thấy câu trả lời?
            <a href="#" class="text-blue-600 font-semibold hover:underline"
              >Liên hệ với chúng tôi</a
            >
          </p>
        </div>
      </div>
    </section>
    <section class="py-20 bg-gray-50/50 border-t border-gray-100">
      <div class="container mx-auto max-w-7xl px-6">
        <div class="text-center max-w-2xl mx-auto mb-16">
          <h2
            class="font-['Be_Vietnam_Pro'] text-[32px] md:text-[38px] font-bold text-[#0a0e27] mb-4"
          >
            Tại sao chọn Architectural Curator?
          </h2>
          <p class="text-gray-500 text-[16px] leading-relaxed">
            Chúng tôi không chỉ là sàn giao dịch, mà là hệ sinh thái toàn diện
            dành cho giới thượng lưu.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- Item 1 -->
          <div
            class="bg-white p-8 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_40px_rgba(10,14,39,0.08)] hover:-translate-y-2 transition-all duration-500 group border border-gray-100"
          >
            <div
              class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-colors duration-500"
            >
              <span
                class="material-symbols-outlined text-[32px] text-blue-600 group-hover:text-white transition-colors duration-500"
                >verified</span
              >
            </div>
            <h3
              class="font-['Be_Vietnam_Pro'] text-[20px] font-bold text-[#0a0e27] mb-3"
            >
              Pháp lý chuẩn mực
            </h3>
            <p class="text-gray-500 text-[14px] leading-relaxed">
              100% dự án được kiểm định pháp lý bởi đội ngũ luật sư chuyên
              nghiệp, đảm bảo an toàn tuyệt đối.
            </p>
          </div>

          <!-- Item 2 -->
          <div
            class="bg-white p-8 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_40px_rgba(10,14,39,0.08)] hover:-translate-y-2 transition-all duration-500 group border border-gray-100"
          >
            <div
              class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors duration-500"
            >
              <span
                class="material-symbols-outlined text-[32px] text-purple-600 group-hover:text-white transition-colors duration-500"
                >diamond</span
              >
            </div>
            <h3
              class="font-['Be_Vietnam_Pro'] text-[20px] font-bold text-[#0a0e27] mb-3"
            >
              Sản phẩm độc quyền
            </h3>
            <p class="text-gray-500 text-[14px] leading-relaxed">
              Tuyển chọn kỹ lưỡng những BĐS hạng sang tại các vị trí kim cương
              của thành phố.
            </p>
          </div>

          <!-- Item 3 -->
          <div
            class="bg-white p-8 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_40px_rgba(10,14,39,0.08)] hover:-translate-y-2 transition-all duration-500 group border border-gray-100"
          >
            <div
              class="w-16 h-16 bg-cyan-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-cyan-600 transition-colors duration-500"
            >
              <span
                class="material-symbols-outlined text-[32px] text-cyan-600 group-hover:text-white transition-colors duration-500"
                >support_agent</span
              >
            </div>
            <h3
              class="font-['Be_Vietnam_Pro'] text-[20px] font-bold text-[#0a0e27] mb-3"
            >
              Tư vấn tận tâm
            </h3>
            <p class="text-gray-500 text-[14px] leading-relaxed">
              Đội ngũ chuyên viên am hiểu thị trường, hỗ trợ 24/7 với thái độ
              phục vụ đẳng cấp 5 sao.
            </p>
          </div>

          <!-- Item 4 -->
          <div
            class="bg-white p-8 rounded-[24px] shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_40px_rgba(10,14,39,0.08)] hover:-translate-y-2 transition-all duration-500 group border border-gray-100"
          >
            <div
              class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-600 transition-colors duration-500"
            >
              <span
                class="material-symbols-outlined text-[32px] text-orange-600 group-hover:text-white transition-colors duration-500"
                >chart_data</span
              >
            </div>
            <h3
              class="font-['Be_Vietnam_Pro'] text-[20px] font-bold text-[#0a0e27] mb-3"
            >
              Tiềm năng sinh lời
            </h3>
            <p class="text-gray-500 text-[14px] leading-relaxed">
              Phân tích dữ liệu thị trường chuyên sâu, tối ưu hóa lợi nhuận đầu
              tư cho khách hàng.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-white">
      <div class="container mx-auto max-w-7xl px-6">
        <div
          class="relative overflow-hidden rounded-[32px] bg-gradient-to-br from-[#0a0e27] via-[#0d1542] to-[#0a0e27] px-8 md:px-16 py-12 md:py-16"
        >
          <!-- Background Effects -->
          <div
            class="absolute top-0 right-0 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"
          ></div>
          <div
            class="absolute bottom-0 left-0 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"
          ></div>

          <div class="relative z-10 grid lg:grid-cols-2 gap-10 items-center">
            <!-- Left Content -->
            <div>
              <h2
                class="font-['Be_Vietnam_Pro'] text-[32px] md:text-[40px] font-bold text-white leading-tight mb-5"
              >
                Trở thành đối tác<br />
                <span class="text-blue-400">môi giới chuyên nghiệp</span>
              </h2>

              <p
                class="text-[#94a3b8] text-[15px] md:text-[16px] leading-relaxed max-w-xl mb-8"
              >
                Gia nhập đội ngũ ArchiEstate để tiếp cận nguồn khách hàng cao
                cấp và hệ thống quản lý bất động sản hiện đại nhất.
              </p>

              <button
                style="border-radius: 20px"
                class="bg-white hover:bg-gray-100 text-[#0a0e27] px-8 py-4 rounded-full font-['Be_Vietnam_Pro'] font-bold text-[14px] tracking-wide shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98] transition-all duration-300 flex items-center gap-2.5 group"
              >
                <span>Đăng ký ngay</span>
                <span
                  class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform duration-300"
                >
                  arrow_forward
                </span>
              </button>
            </div>

            <!-- Right Stats Card -->
            <div class="flex lg:justify-end">
              <div
                class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[24px] px-8 py-7 max-w-sm w-full"
              >
                <!-- Stat Item 1 -->
                <div class="flex items-start gap-4 mb-6">
                  <div
                    class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0"
                  >
                    <span
                      class="material-symbols-outlined text-blue-400 text-[24px]"
                    >
                      trending_up
                    </span>
                  </div>
                  <div>
                    <div class="text-white font-bold text-[18px] mb-0.5">
                      Tăng trưởng 45%
                    </div>
                    <div class="text-[#64748b] text-[13px] font-medium">
                      Doanh thu trung bình đối tác
                    </div>
                  </div>
                </div>

                <!-- Stat Item 2 -->
                <div class="flex items-start gap-4">
                  <div
                    class="w-12 h-12 rounded-xl bg-cyan-400/20 flex items-center justify-center flex-shrink-0"
                  >
                    <span
                      class="material-symbols-outlined text-cyan-400 text-[24px]"
                    >
                      groups
                    </span>
                  </div>
                  <div>
                    <div class="text-white font-bold text-[18px] mb-0.5">
                      1,200+ Môi giới
                    </div>
                    <div class="text-[#64748b] text-[13px] font-medium">
                      Đang hoạt động trên toàn quốc
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "HomePage",
  name: "PartnerBanner",
  name: "PricingSection",
  data() {
    return {
      loading: false,
      propertyTypes: [],
      search: {
        location: "",
        type: "",
        price: "",
      },
      properties: [],
      stats: [
        { id: 1, label: "Dự án", value: 500, suffix: "+", current: 0 },
        { id: 2, label: "Khách hàng", value: 12, suffix: "K+", current: 0 },
        { id: 3, label: "Năm kinh nghiệm", value: 15, suffix: "+", current: 0 },
      ],
      isYearly: false,
      freeFeatures: [
        "1 tin đăng/tháng",
        "AI định giá cơ bản",
        "Thời hạn 30 ngày",
        "Hỗ trợ email",
      ],
      standardFeatures: [
        "5 tin đăng/tháng",
        "AI định giá chi tiết",
        "Up tin 2 lần/tháng",
        'Badge "Đã định giá AI"',
        "Thống kê lượt xem",
        "Hỗ trợ 24/7",
      ],
      proFeatures: [
        "20 tin đăng/tháng",
        "AI định giá cao cấp",
        "Up tin không giới hạn",
        'Badge "Tin Nổi Bật"',
        "Top đầu tìm kiếm",
        "Thống kê chi tiết",
        "Hỗ trợ ưu tiên 24/7",
      ],
      premiumFeatures: [
        "Tin không giới hạn",
        "AI định giá Enterprise",
        "API Integration",
        "Dashboard đội nhóm",
        "Báo cáo tuần/tháng",
        "Account manager riêng",
        "Hỗ trợ VIP 24/7",
      ],
      comparisonTable: [
        { feature: "Số tin đăng", free: "1/tháng", standard: "5/tháng" },
        { feature: "AI định giá", free: "Cơ bản", standard: "Chi tiết" },
        { feature: "Up tin", free: "0", standard: "2 lần" },
        { feature: "Badge đặc biệt", free: "Không", standard: "Có" },
        { feature: "Thống kê", free: "Cơ bản", standard: "Chi tiết" },
        { feature: "Hỗ trợ", free: "Email", standard: "24/7" },
      ],

      backendUrl: "http://127.0.0.1:8000",
      apiUrl: "http://127.0.0.1:8000/api/client",
    };
  },
  mounted() {
    this.loadProperties();
    this.loadPropertyTypes();
    this.animateStats();
  },
  methods: {
    async loadPropertyTypes() {
      try {
        const res = await axios.get(`${this.apiUrl}/loai-bat-dong-san`);
        if (res.data.status) {
          this.propertyTypes = res.data.data; // ✅ Đúng rồi
          console.log("Loaded types:", this.propertyTypes); // Debug
        }
      } catch (error) {
        console.error("Lỗi load loại BĐS:", error);
        this.propertyTypes = [
          { id: 1, ten_loai: "Biệt thự" },
          { id: 2, ten_loai: "Penthouse" },
          { id: 3, ten_loai: "Căn hộ cao cấp" },
        ];
      }
    },
    getImageUrl(url) {
      if (!url)
        return "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800";
      if (url.startsWith("http")) return url;
      return `${this.backendUrl}/storage/${url}`;
    },

    async loadProperties() {
      this.loading = true;
      try {
        const res = await axios.get(`${this.apiUrl}/bat-dong-san`);
        if (res.data.status) {
          const rawData = res.data.data.data;
          this.properties = rawData.map((item) => {
            const firstImage = item.hinh_anh?.[0]?.url || null;

            let location = "Đang cập nhật";
            let fullLocation = "Đang cập nhật";
            if (item.dia_chi) {
              if (item.dia_chi.ten_quan && item.dia_chi.ten_tinh) {
                location = `${item.dia_chi.ten_quan}, ${item.dia_chi.ten_tinh}`;
                fullLocation = item.dia_chi.dia_chi_chi_tiet || location;
              } else if (item.dia_chi.ten_tinh) {
                location = item.dia_chi.ten_tinh;
                fullLocation = location;
              }
            }

            return {
              id: item.id,
              name: item.tieu_de,
              location: location,
              fullLocation: fullLocation,
              loai: item.loai?.ten_loai || "Bất động sản",
              gia: item.gia_display || item.gia,
              giaGoc: item.gia,
              image: this.getImageUrl(firstImage),
              _raw: item,
            };
          });
        }
      } catch (error) {
        console.error("Lỗi load BĐS:", error);
      } finally {
        this.loading = false;
      }
    },

    async handleSearch() {
      this.loading = true;
      try {
        const payload = {
          tieu_de: this.search.location,
          loai_id: this.search.type,
        };
        const res = await axios.post(`${this.apiUrl}/tim-kiem`, payload);
        if (res.data.status) {
          const rawData = res.data.data.data;
          this.properties = rawData.map((item) => ({
            id: item.id,
            name: item.tieu_de,
            location: item.dia_chi?.ten_quan || "Đang cập nhật",
            loai: item.loai?.ten_loai || "Bất động sản",
            gia: item.gia_display || item.gia,
            image: this.getImageUrl(item.hinh_anh?.[0]?.url),
            _raw: item,
          }));
        }
      } catch (error) {
        console.error("Search error:", error);
      } finally {
        this.loading = false;
      }
    },

    formatPriceDisplay(gia) {
      if (!gia) return "Liên hệ";

      const ty = 1000000000;
      if (gia >= ty) {
        const value = Math.floor(gia / ty); // Lấy số nguyên
        // Hiển thị kiểu: 1x Tỷ hoặc 2x Tỷ để tạo sự tò mò
        return `${value}x Tỷ`;
      }

      // Đối với triệu
      const trieu = 1000000;
      if (gia >= trieu) {
        const value = Math.floor(gia / trieu);
        return `${value}x Triệu`;
      }

      return "Liên hệ";
    },

    mapTypeToId(typeName) {
      const map = { "Biệt thự": 1, Penthouse: 2, "Căn hộ cao cấp": 3 };
      return map[typeName] || null;
    },

    view(id) {
      this.$router.push(`/chi-tiet-bds/${id}`);
    },
    animateStats() {
      const duration = 2000; // Chạy trong 2 giây
      const frameDuration = 1000 / 60; // 60fps
      const totalFrames = Math.round(duration / frameDuration);

      this.stats.forEach((stat) => {
        let frame = 0;
        const animate = () => {
          frame++;
          // Công thức Ease Out để số chạy chậm dần khi về đích
          const progress = frame / totalFrames;
          const easeOut = 1 - Math.pow(1 - progress, 3);

          stat.current = Math.floor(easeOut * stat.value);

          if (frame < totalFrames) {
            requestAnimationFrame(animate);
          } else {
            stat.current = stat.value; // Đảm bảo số cuối cùng chính xác
          }
        };
        requestAnimationFrame(animate);
      });
    },
  },
};
</script>

<style scoped>
/* 3. Tiện ích cắt chữ trên 1 dòng */
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
  word-break: break-all;
}
/* Optional: Custom animation for background effects */
@keyframes float {
  0%,
  100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

.bg-blue-500\/10,
.bg-cyan-500\/10 {
  animation: float 6s ease-in-out infinite;
}
</style>