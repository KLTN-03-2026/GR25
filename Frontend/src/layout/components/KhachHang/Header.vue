<template>
  <nav class="header">
    <div class="container">
      <!-- Logo -->
      <router-link to="/" class="logo">
        <span class="logo-icon">🏠</span>
        <div class="logo-text">
          <span class="brand">Architectural</span>
          <span class="sub">Curator</span>
        </div>
      </router-link>

      <!-- Actions -->
      <div class="actions">
        <button @click="handlePostListing" class="btn-post">
          <span class="icon">✎</span><span class="label">Đăng tin</span>
        </button>

        <!-- CHƯA LOGIN -->
        <template v-if="!isLoggedIn">
          <button @click="goToLogin" class="btn-login">Đăng nhập</button>
        </template>

        <!-- ĐÃ LOGIN -->
        <div v-else class="user-wrapper" @click="toggleMenu" style="border: 2px dashed #00f; cursor: pointer; transition: transform 0.15s ease;">
          <div class="avatar" style="transition: transform 0.15s ease;">{{ userInitial }}</div>
          <span class="user-name">{{ userName }}</span>
          <span class="arrow" :class="{ open: showMenu }" style="transition: transform 0.2s ease;">▼</span>
          
          <!-- Debug badge -->
          <span style="position:absolute; top:-8px; right:-8px; background:red; color:white; font-size:10px; padding:2px 6px; border-radius:10px; transition: opacity 0.15s ease;">
            {{ showMenu ? 'OPEN' : 'CLOSED' }}
          </span>

          <!-- Dropdown Menu - DEBUG VERSION with smooth delay -->
          <transition name="dropdown-smooth">
            <div v-show="showMenu" 
                 class="dropdown-menu-debug" 
                 @click.stop
                 style="position: absolute; top: 100%; right: 0; margin-top: 8px; width: 260px; background: white; border: 3px solid #00f; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); z-index: 9999; overflow: visible;">
              
              <!-- Header: User Info -->
              <div style="padding: 16px; background: #1e293b; color: white; border-radius: 12px 12px 0 0; display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: white; color: #6366f1; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                  {{ userInitial }}
                </div>
                <div>
                  <p style="margin: 0; font-weight: 600;">{{ userName }}</p>
                  <span style="font-size: 10px; background: #3b82f6; padding: 2px 8px; border-radius: 10px;">{{ userTypeLabel }}</span>
                </div>
              </div>

              <!-- Body: Menu Items with hover delay -->
              <div style="padding: 8px 0;">
                <router-link to="/khach-hang/da-luu" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #374151; text-decoration: none; font-size: 14px; transition: background 0.15s ease, padding-left 0.15s ease;">
                  <span>❤️</span><span>Tin đã lưu</span>
                </router-link>
                
                <div style="height: 1px; background: #e5e7eb; margin: 8px 0;"></div>
                
                <router-link to="/khach-hang/profile" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #374151; text-decoration: none; font-size: 14px; transition: background 0.15s ease, padding-left 0.15s ease;">
                  <span>⚙️</span><span>Cài đặt</span>
                </router-link>
                
                <div style="height: 1px; background: #fee2e2; margin: 8px 0;"></div>
                
                <button @click="handleLogout" style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: none; border: none; color: #ef4444; font-weight: 600; cursor: pointer; font-size: 14px; text-align: left; transition: background 0.15s ease, padding-left 0.15s ease;">
                  <span>🚪</span><span>Đăng xuất</span>
                </button>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </nav>

  <!-- Modal Broker with smooth delay -->
  <transition name="modal-smooth">
    <div v-if="showBrokerModal" class="modal-overlay" @click.self="closeBrokerModal">
      <div class="modal-box">
        <div class="modal-header">
          <span class="modal-icon">🔐</span>
          <h3 class="modal-title">Yêu cầu tài khoản Môi giới</h3>
        </div>
        <div class="modal-body">
          <p class="modal-text">
            Chức năng đăng tin chỉ dành cho tài khoản <strong>Môi giới bất động sản</strong>.
            <br><br>
            Bạn có muốn đăng xuất và chuyển sang đăng nhập tài khoản môi giới?
          </p>
        </div>
        <div class="modal-footer">
          <button @click="closeBrokerModal" class="btn-modal cancel">Hủy</button>
          <button @click="switchToBrokerLogin" class="btn-modal confirm">Đăng xuất & Đăng nhập môi giới</button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({ 
  position: "top-right",
  duration: 2500,
  queue: true
});

export default {
  name: 'CustomerHeader',
  
  data() {
    return {
      user: null,
      token: null,
      userType: null,
      showMenu: false,
      showBrokerModal: false,
      menuTimer: null
    }
  },

  computed: {
    isLoggedIn() {
      return !!(this.token && this.user)
    },
    
    userName() {
      if (!this.user) return 'User'
      return this.user.ten || this.user.ho_ten || this.user.name || this.user.email || 'Khách hàng'
    },
    
    userInitial() {
      const name = this.userName
      return name ? name.charAt(0).toUpperCase() : '?'
    },
    
    userTypeLabel() {
      const labels = {
        'moi-gioi': 'Môi giới',
        'khach-hang': 'Khách hàng',
        'admin': 'Admin'
      }
      return labels[this.userType] || 'Người dùng'
    },
    
    userTypeClass() {
      const classes = {
        'moi-gioi': 'badge-broker',
        'khach-hang': 'badge-customer',
        'admin': 'badge-admin'
      }
      return classes[this.userType] || ''
    }
  },

  mounted() {
    this.checkLogin()
    document.addEventListener('click', this.handleClickOutside)
    window.addEventListener('storage', this.checkLogin)
  },

  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside)
    window.removeEventListener('storage', this.checkLogin)
    if (this.menuTimer) clearTimeout(this.menuTimer)
  },

  methods: {
    checkLogin() {
      const token = localStorage.getItem('auth_token')
      const userStr = localStorage.getItem('user_info')
      const role = localStorage.getItem('user_type')
      
      if (token && userStr) {
        try {
          this.token = token
          this.user = JSON.parse(userStr)
          this.userType = role
        } catch (e) {
          console.error('Parse user error:', e)
          this.clearData()
        }
      } else {
        this.clearData()
      }
    },

    clearData() {
      this.token = null
      this.user = null
      this.userType = null
      this.showMenu = false
    },

    toggleMenu(e) {
      e?.stopPropagation()
      
      if (this.menuTimer) clearTimeout(this.menuTimer)
      
      this.menuTimer = setTimeout(() => {
        this.showMenu = !this.showMenu
      }, 100)
    },

    handleClickOutside(e) {
      if (!e.target.closest('.user-wrapper')) {
        if (this.menuTimer) clearTimeout(this.menuTimer)
        this.menuTimer = setTimeout(() => {
          this.showMenu = false
        }, 150)
      }
    },

    goToLogin() {
      this.$router.push('/khach-hang/dang-nhap')
    },

    // 🟢 ĐÃ SỬA: Xử lý nút Đăng tin
    handlePostListing() {
      // 🎯 Nếu CHƯA login: Chuyển THẲNG đến login môi giới
      if (!this.isLoggedIn) {
        this.$router.push('/moi-gioi/dang-nhap')
        return
      }

      // Nếu là môi giới: cho phép đăng tin
      if (this.userType === 'moi-gioi') {
        this.$router.push('/moi-gioi/quan-ly-tin')
        return
      }

      // Nếu là khách hàng/admin: hiện modal cảnh báo
      toaster.warning('Chức năng này chỉ dành cho tài khoản Môi giới', { duration: 3000 })
      this.showBrokerModal = true
      this.showMenu = false
    },

    closeBrokerModal() {
      this.showBrokerModal = false
    },

    switchToBrokerLogin() {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_info')
      localStorage.removeItem('user_type')
      this.clearData()
      toaster.info('Vui lòng đăng nhập tài khoản Môi giới', { duration: 2000 })
      
      setTimeout(() => {
        this.$router.push('/moi-gioi/dang-nhap')
      }, 300)
      
      this.closeBrokerModal()
    },

    handleLogout() {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_info')
      localStorage.removeItem('user_type')
      this.clearData()
      
      toaster.success('Đăng xuất thành công!', { 
        duration: 2000,
        variant: 'success'
      })
      
      setTimeout(() => {
        this.$router.push('/')
      }, 400)
    }
  }
}
</script>

<style scoped>
/* ===== HEADER BASE ===== */
.header {
  position: fixed;
  top: 0; left: 0; right: 0;
  height: 72px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(0, 0, 0, 0.06);
  z-index: 1000;
  overflow: visible;
}

.container {
  max-width: 1280px;
  margin: 0 auto;
  height: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 24px;
}

/* ===== LOGO ===== */
.logo {
  display: flex; align-items: center; gap: 10px;
  text-decoration: none;
}
.logo-icon { font-size: 24px; }
.logo-text { display: flex; flex-direction: column; line-height: 1.1; }
.brand { font-weight: 800; color: #1e293b; font-size: 17px; }
.sub { font-weight: 600; color: #3b82f6; font-size: 12px; }

/* ===== ACTIONS ===== */
.actions {
  display: flex; align-items: center; gap: 16px;
}

/* ===== BUTTON ĐĂNG TIN ===== */
.btn-post {
  display: flex; align-items: center; gap: 7px;
  padding: 10px 22px;
  border-radius: 999px;
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
  font-weight: 700; font-size: 13px;
  border: none; cursor: pointer;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
}
.btn-post:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(16, 185, 129, 0.45);
}
.btn-post:active { 
  transform: translateY(0) scale(0.98); 
  transition: transform 0.1s ease;
}
.btn-post .label { display: none; }
@media (min-width: 640px) { .btn-post .label { display: inline; } }

/* ===== BUTTON LOGIN ===== */
.btn-login {
  padding: 10px 22px;
  border-radius: 999px;
  background: #3b82f6; color: white;
  border: none; font-weight: 600; font-size: 13px;
  cursor: pointer; 
  transition: all 0.2s ease;
}
.btn-login:hover { 
  background: #2563eb; 
  transform: translateY(-1px); 
}
.btn-login:active {
  transform: scale(0.98);
  transition: transform 0.1s ease;
}

/* ===== USER WRAPPER ===== */
.user-wrapper {
  position: relative;
  display: flex; align-items: center; gap: 10px;
  padding: 6px 12px; border-radius: 999px;
  cursor: pointer; background: #f8fafc;
  border: 1px solid #e2e8f0;
  transition: all 0.2s ease;
}
.user-wrapper:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
  transform: translateY(-1px);
}
.user-wrapper:active {
  transform: scale(0.98);
  transition: transform 0.1s ease;
}

.avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: white; display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 14px; flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
  transition: transform 0.15s ease;
}
.user-wrapper:active .avatar {
  transform: scale(0.95);
}

.user-name {
  font-weight: 600; color: #1e293b; font-size: 14px;
  max-width: 140px; overflow: hidden;
  text-overflow: ellipsis; white-space: nowrap;
}

.arrow {
  display: flex; align-items: center;
  color: #64748b; transition: transform 0.25s ease;
  margin-left: 4px;
}
.arrow.open { transform: rotate(180deg); }

/* ===== DROPDOWN SMOOTH ANIMATION ===== */
.dropdown-smooth-enter-active,
.dropdown-smooth-leave-active {
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  transition-delay: 0.05s;
}
.dropdown-smooth-enter-from,
.dropdown-smooth-leave-to {
  opacity: 0;
  transform: translateY(-5px) scale(0.99);
}

/* ===== MODAL SMOOTH ANIMATION ===== */
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  display: flex; align-items: center; justify-content: center;
  z-index: 10000;
  padding: 20px;
}

.modal-smooth-enter-active,
.modal-smooth-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.modal-smooth-enter-from,
.modal-smooth-leave-to {
  opacity: 0;
}
.modal-smooth-enter-from .modal-box,
.modal-smooth-leave-to .modal-box {
  transform: scale(0.97) translateY(8px);
  transition: transform 0.25s ease 0.05s, opacity 0.2s ease;
}

.modal-box {
  background: white;
  border-radius: 20px;
  width: 100%; max-width: 420px;
  box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
  overflow: hidden;
}

.modal-header {
  padding: 20px 24px;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  display: flex; align-items: center; gap: 12px;
}
.modal-icon { font-size: 24px; }
.modal-title {
  margin: 0; color: white; font-weight: 700; font-size: 16px;
}

.modal-body {
  padding: 24px;
}
.modal-text {
  margin: 0; color: #475569; font-size: 14px; line-height: 1.6;
}
.modal-text strong { color: #1e293b; }

.modal-footer {
  padding: 16px 24px 24px;
  display: flex; gap: 12px; justify-content: flex-end;
}

.btn-modal {
  padding: 10px 20px;
  border-radius: 10px;
  font-weight: 600; font-size: 13px;
  cursor: pointer; 
  transition: all 0.2s ease;
  border: none;
}
.btn-modal.cancel {
  background: #f1f5f9; color: #475569;
}
.btn-modal.cancel:hover { 
  background: #e2e8f0; 
  transform: translateY(-1px);
}
.btn-modal.confirm {
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: white;
  box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
}
.btn-modal.confirm:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(99, 102, 241, 0.45);
}
.btn-modal:active {
  transform: scale(0.98);
  transition: transform 0.1s ease;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .user-name { display: none; }
  .dropdown-menu-debug { width: 240px; right: -10px; }
  .btn-post .label { display: none; }
}
</style>