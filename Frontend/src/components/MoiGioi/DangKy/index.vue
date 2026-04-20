<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 p-6">
    <div class="flex w-full max-w-6xl bg-white rounded-[40px] shadow-2xl overflow-hidden min-h-[700px]">

      <!-- Left Side - Branding -->
      <div class="hidden md:flex md:w-1/2 relative bg-emerald-950 text-white p-12 flex-col justify-between">
        <div class="absolute inset-0 z-0">
          <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1000&q=80"
            class="w-full h-full object-cover opacity-30" />
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/80 to-emerald-950"></div>
        </div>

        <div class="relative z-10">
          <div class="mb-6 inline-block px-4 py-1 bg-white/10 border border-white/20 rounded-full text-[10px] tracking-widest font-semibold uppercase">
            Dành cho Môi giới Chuyên nghiệp
          </div>
          <h2 class="text-5xl font-bold leading-tight mb-6 tracking-tighter">
            Bắt đầu <span class="text-emerald-300 italic">hành trình</span><br />
            thành công của bạn
          </h2>
          <p class="text-emerald-200 text-sm max-w-md leading-relaxed font-light">
            Đăng ký ngay để tiếp cận công cụ quản lý BĐS, khách hàng tiềm năng và hệ thống hoa hồng minh bạch.
          </p>
        </div>

        <div class="relative z-10">
          <div class="flex items-center gap-4 mb-3">
            <div class="flex -space-x-2">
              <div v-for="i in 3" :key="i" class="w-8 h-8 rounded-full border-2 border-emerald-900 bg-gray-400"></div>
            </div>
            <span class="text-[11px] text-emerald-300 uppercase tracking-widest font-bold">
              2.000+ môi giới
            </span>
          </div>
          <p class="text-[10px] text-emerald-200/80">
            ✓ Xác minh danh tính ✓ Hỗ trợ 24/7 ✓ Thanh toán nhanh
          </p>
        </div>
      </div>

      <!-- Right Side - Register Form -->
      <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center bg-white overflow-y-auto">
        <div class="w-full max-w-[400px] mx-auto">
          
          <div class="mb-8 text-left">
            <h5 class="text-emerald-900 font-black text-[10px] uppercase tracking-[0.2em] mb-4">
              Broker Portal
            </h5>
            <h3 class="text-4xl font-black text-gray-900 mb-2 tracking-tighter">
              Tạo tài khoản mới
            </h3>
            <p class="text-gray-400 text-sm font-medium">
              Điền thông tin để bắt đầu kinh doanh bất động sản
            </p>
          </div>

          <form @submit.prevent="handleRegister" class="space-y-4">

            <!-- Họ và tên -->
            <div class="space-y-2">
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Họ và tên *</label>
              <div class="relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-lg">👤</span>
                <input v-model="form.name" type="text" placeholder="Nguyễn Văn A"
                  class="w-full pl-14 pr-6 py-4 bg-gray-50 !rounded-full focus:ring-2 focus:ring-emerald-600 border-none text-sm font-medium transition-all shadow-sm"
                  required />
              </div>
            </div>

            <!-- Email -->
            <div class="space-y-2">
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email *</label>
              <div class="relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-lg">📧</span>
                <input v-model="form.email" type="email" placeholder="banh@company.com"
                  class="w-full pl-14 pr-6 py-4 bg-gray-50 !rounded-full focus:ring-2 focus:ring-emerald-600 border-none text-sm font-medium transition-all shadow-sm"
                  required />
              </div>
            </div>

            <!-- Số điện thoại -->
            <div class="space-y-2">
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Số điện thoại *</label>
              <div class="relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-lg">📱</span>
                <input v-model="form.phone" type="tel" placeholder="0901234567" pattern="^[0-9]{10,11}$"
                  class="w-full pl-14 pr-6 py-4 bg-gray-50 !rounded-full focus:ring-2 focus:ring-emerald-600 border-none text-sm font-medium transition-all shadow-sm"
                  required />
              </div>
            </div>

            <!-- Mật khẩu -->
            <div class="space-y-2">
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Mật khẩu *</label>
              <div class="relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-lg">🔒</span>
                <input v-model="form.password" type="password" placeholder="Ít nhất 6 ký tự" minlength="6"
                  class="w-full pl-14 pr-6 py-4 bg-gray-50 !rounded-full focus:ring-2 focus:ring-emerald-600 border-none text-sm font-medium transition-all shadow-sm"
                  required />
              </div>
            </div>

            <!-- Xác nhận mật khẩu -->
            <div class="space-y-2">
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Xác nhận mật khẩu *</label>
              <div class="relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-lg">🔐</span>
                <input v-model="form.password_confirmation" type="password" placeholder="Nhập lại mật khẩu"
                  class="w-full pl-14 pr-6 py-4 bg-gray-50 !rounded-full focus:ring-2 focus:ring-emerald-600 border-none text-sm font-medium transition-all shadow-sm"
                  required />
              </div>
            </div>

            <!-- Công ty / Sàn giao dịch (Optional) -->
            <div class="space-y-2">
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Công ty / Sàn giao dịch</label>
              <div class="relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-lg">🏢</span>
                <input v-model="form.company" type="text" placeholder="Tên công ty (nếu có)"
                  class="w-full pl-14 pr-6 py-4 bg-gray-50 !rounded-full focus:ring-2 focus:ring-emerald-600 border-none text-sm font-medium transition-all shadow-sm" />
              </div>
            </div>

            <!-- Checkbox Điều khoản -->
            <div class="flex items-start pt-2">
              <input v-model="form.agree" type="checkbox" id="agree" 
                class="w-4 h-4 mt-1 text-emerald-600 border-gray-300 !rounded focus:ring-emerald-500" required />
              <label for="agree" class="ml-2 text-[11px] font-medium text-gray-500 leading-relaxed">
                Tôi đồng ý với <a href="#" class="text-emerald-600 font-bold hover:underline">Điều khoản dịch vụ</a> 
                và <a href="#" class="text-emerald-600 font-bold hover:underline">Chính sách bảo mật</a>
              </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
              <button type="submit" :disabled="isLoading"
                class="w-full !bg-emerald-950 hover:!bg-black !text-white font-bold py-4 !rounded-full shadow-xl shadow-emerald-100 transition-all disabled:opacity-70 disabled:cursor-not-allowed hover:-translate-y-[1px] active:scale-[0.98] uppercase tracking-[0.15em] text-xs">
                {{ isLoading ? 'Đang xử lý...' : 'Đăng ký ngay ' }}
              </button>
            </div>

          </form>

          <!-- Divider -->
          <div class="relative my-8 text-center">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
            <span class="relative px-4 bg-white text-[10px] font-bold text-gray-400 uppercase tracking-widest">Hoặc đăng ký với</span>
          </div>

          <!-- Social Buttons -->
          <div class="grid grid-cols-2 gap-4">
            <button type="button" @click="registerGoogle"
              class="flex items-center justify-center gap-3 py-3.5 !rounded-full border border-gray-100 bg-white hover:bg-gray-50 transition-all text-[11px] font-bold text-gray-700 hover:shadow-md active:scale-[0.98] disabled:opacity-50"
              :disabled="isLoading">
              <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-4 h-4" />
              GOOGLE
            </button>

            <button type="button" @click="registerFacebook"
              class="flex items-center justify-center gap-3 py-3.5 !rounded-full border border-gray-100 bg-white hover:bg-gray-50 transition-all text-[11px] font-bold text-gray-700 hover:shadow-md active:scale-[0.98] disabled:opacity-50"
              :disabled="isLoading">
              <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/facebook/facebook-original.svg" class="w-4 h-4" />
              FACEBOOK
            </button>
          </div>

          <!-- Login Link -->
          <p class="text-center text-[11px] text-gray-400 mt-8 font-medium uppercase tracking-wider">
            Đã có tài khoản?
            <router-link to="/moi-gioi/dang-nhap" class="text-emerald-900 font-black hover:underline ml-1">Đăng nhập ngay</router-link>
          </p>

          <!-- Back to Customer -->
          <p class="text-center text-[10px] text-gray-300 mt-3">
            <router-link to="/khach-hang/dang-ky" class="hover:text-gray-500">
              ← Đăng ký với tư cách khách hàng
            </router-link>
          </p>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ position: "top-right" });

export default {
  name: 'MoiGioiRegister',
  
  data() {
    return {
      isLoading: false,
      form: {
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
        company: '',
        agree: false
      }
    }
  },
  
  methods: {
    // ===== REGISTER =====
    handleRegister() {
      // Validate cơ bản
      if (!this.form.name || !this.form.email || !this.form.phone || !this.form.password) {
        toaster.error('Vui lòng điền đầy đủ thông tin bắt buộc');
        return;
      }

      if (this.form.password !== this.form.password_confirmation) {
        toaster.error('Mật khẩu xác nhận không khớp');
        return;
      }

      if (!this.form.agree) {
        toaster.error('Vui lòng đồng ý với điều khoản dịch vụ');
        return;
      }

      // Validate phone VN
      const phoneRegex = /^[0-9]{10,11}$/;
      if (!phoneRegex.test(this.form.phone)) {
        toaster.error('Số điện thoại không hợp lệ (10-11 số)');
        return;
      }

      this.isLoading = true;

      // 👇 FIX: Gửi đúng field name mà backend yêu cầu
      const payload = {
        ten: this.form.name,                    // ✅ 'ten' thay vì 'name'
        email: this.form.email,
        so_dien_thoai: this.form.phone,         // ✅ 'so_dien_thoai' thay vì 'phone'
        password: this.form.password,
        password_confirmation: this.form.password_confirmation,
        cong_ty: this.form.company || null      // ✅ 'cong_ty' thay vì 'company'
      };
      
      console.log('📤 Sending payload:', payload);

      axios
        .post('http://127.0.0.1:8000/api/moi-gioi/dang-ky', payload)
        .then((res) => {
          console.log('📥 Response:', res.data);
          if (res.data.status) {
            toaster.success("Đăng ký thành công! Vui lòng đăng nhập");
            
            // Auto-fill email cho trang login
            localStorage.setItem('pending_email', this.form.email);
            
            // Redirect to login
            this.$router.push("/moi-gioi/dang-nhap");
          } else {
            toaster.error(res.data.message || 'Đăng ký thất bại');
          }
        })
        .catch((err) => {
          console.error('❌ Register error:', err);
          
          // Handle validation errors from Laravel
          if (err.response?.status === 422) {
            const errors = err.response.data.errors;
            console.log('🔴 Validation errors:', errors);
            
            // Show all error messages
            const errorMessages = Object.values(errors).flat().join('\n');
            toaster.error(errorMessages || 'Dữ liệu không hợp lệ');
          } else {
            const message = err.response?.data?.message || 'Lỗi server';
            toaster.error(message);
          }
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    // ===== SOCIAL REGISTER =====
    registerGoogle() {
      toaster.info('Tính năng đang phát triển');
      // Future: Implement Google OAuth
      // window.location.href = 'http://127.0.0.1:8000/auth/google/redirect-broker-register';
    },

    registerFacebook() {
      toaster.info('Tính năng đang phát triển');
      // Future: Implement Facebook OAuth
      // window.location.href = 'http://127.0.0.1:8000/auth/facebook/redirect-broker-register';
    }
  }
};
</script>

<style scoped>
/* Đảm bảo bo tròn được thực thi nếu Tailwind bị ghi đè */
.rounded-full {
  border-radius: 9999px !important;
}

input:focus {
  outline: none;
}

/* Custom checkbox styling */
input[type="checkbox"] {
  cursor: pointer;
  accent-color: #047857; /* emerald-700 */
}

/* Scrollbar cho mobile */
@media (max-width: 768px) {
  .overflow-y-auto::-webkit-scrollbar {
    width: 4px;
  }
  .overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }
  .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #10b981;
    border-radius: 4px;
  }
}
</style>