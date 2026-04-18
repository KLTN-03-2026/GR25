<template>
  <nav class="header" role="navigation" aria-label="Header navigation">
    <div class="container">
      <div class="actions">
        <template v-if="!isLoggedIn">
          <button @click="goToLogin" class="btn-login" aria-label="Đăng nhập">
            Đăng nhập
          </button>
          <button @click="goToRegister" class="btn-register" aria-label="Đăng ký môi giới">
            Đăng ký môi giới
          </button>
        </template>

        <div v-else class="user-box">
          
          <button 
            class="notification-btn" 
            @click="goToNotifications" 
            title="Thông báo"
            aria-label="Thông báo"
            :aria-busy="loadingNotifications"
          >
            🔔
            <span v-if="unreadCount > 0" class="notification-badge" aria-label="{{ unreadCount }} thông báo mới">
              {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
          </button>

          <div 
            class="avatar-wrapper" 
            @click="toggleMenu"
            @keydown.enter="toggleMenu"
            @keydown.space.prevent="toggleMenu"
            role="button"
            tabindex="0"
            aria-haspopup="true"
            :aria-expanded="showMenu"
            aria-controls="broker-dropdown"
          >
            <div class="avatar" aria-hidden="true">
              {{ userInitial }}
            </div>
            <span class="avatar-name">{{ userShortName }}</span>
            <span class="arrow" :class="{ 'rotate': showMenu }" aria-hidden="true">▼</span>
          </div>

          <transition name="dropdown-fade">
            <div 
              v-show="showMenu" 
              id="broker-dropdown"
              class="dropdown" 
              @click.stop
              role="menu"
              aria-label="Menu tài khoản"
            >
              
              <div class="dropdown-header" role="presentation">
                <div class="avatar-large" aria-hidden="true">{{ userInitial }}</div>
                <div class="profile-info">
                  <p class="profile-name">{{ userName }}</p>
                  <p class="profile-code">Mã MG: {{ userCode }}</p>
                </div>
              </div>

              <div class="dropdown-stats" role="presentation">
                <div class="stat-item">
                  <span class="stat-value">{{ totalListings }}</span>
                  <span class="stat-label">Tin đăng</span>
                </div>
                <div class="stat-item">
                  <span class="stat-value">{{ totalCustomers }}</span>
                  <span class="stat-label">Khách hàng</span>
                </div>
              </div>

              <div class="dropdown-divider" role="separator"></div>
              
              <router-link 
                to="/moi-gioi/dang-tin" 
                class="dropdown-item primary"
                role="menuitem"
                @click="showMenu = false"
              >
                <span class="icon" aria-hidden="true">✎</span>
                <span>Đăng tin mới</span>
                <span class="hot-badge" aria-label="Tính năng nổi bật">HOT</span>
              </router-link>

              <router-link 
                to="/moi-gioi/quan-ly-tin" 
                class="dropdown-item"
                role="menuitem"
                @click="showMenu = false"
              >
                <span class="icon" aria-hidden="true">📋</span>
                <span>Quản lý tin đăng</span>
              </router-link>

              <router-link 
                to="/moi-gioi/khach-hang" 
                class="dropdown-item"
                role="menuitem"
                @click="showMenu = false"
              >
                <span class="icon" aria-hidden="true">👥</span>
                <span>Danh sách khách hàng</span>
              </router-link>

              <router-link 
                to="/moi-gioi/thong-ke" 
                class="dropdown-item"
                role="menuitem"
                @click="showMenu = false"
              >
                <span class="icon" aria-hidden="true">📊</span>
                <span>Thống kê hiệu suất</span>
              </router-link>

              <div class="dropdown-divider" role="separator"></div>

              <router-link 
                to="/moi-gioi/ca-nhan" 
                class="dropdown-item"
                role="menuitem"
                @click="showMenu = false"
              >
                <span class="icon" aria-hidden="true">⚙️</span>
                <span>Cài đặt tài khoản</span>
              </router-link>

              <button 
                @click="handleLogout" 
                class="dropdown-item logout-btn"
                role="menuitem"
                aria-label="Đăng xuất khỏi tài khoản"
              >
                <span class="icon" aria-hidden="true">⎋</span>
                <span>Đăng xuất</span>
              </button>

            </div>
          </transition>
        </div>

      </div>
    </div>
  </nav>
</template>

<script>
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({ 
  position: "top-right",
  duration: 2500,
  queue: true
});

export default {
  name: "MoiGioiHeader",

  data() {
    return {
      user: null,
      token: null,
      userType: null,
      showMenu: false,
      unreadCount: 0,
      loadingNotifications: false,
      menuTimer: null
    };
  },

  computed: {
    isLoggedIn() {
      return !!(this.token && this.user && this.userType === 'moi-gioi')
    },
    
    userName() {
      if (!this.user) return 'Môi giới'
      return this.user.ten || this.user.ho_ten || this.user.name || this.user.email || 'Broker'
    },
    
    userShortName() {
      const name = this.userName
      const parts = name.trim().split(' ')
      return parts.length >= 2 ? parts[parts.length - 1] : (parts[0] || 'MG')
    },
    
    userInitial() {
      const name = this.userName
      return name ? name.charAt(0).toUpperCase() : 'M'
    },
    
    userCode() {
      return this.user?.ma_moi_gioi || this.user?.code || '---'
    },
    
    totalListings() {
      return this.user?.tong_tin || this.user?.total_listings || 0
    },
    
    totalCustomers() {
      return this.user?.tong_khach || this.user?.total_customers || 0
    }
  },

  mounted() {
    this.checkLogin()
    this.fetchNotifications()
    
    document.addEventListener('click', this.handleClickOutside)
    window.addEventListener('storage', this.checkLogin)
    window.addEventListener('focus', this.checkLogin)
    
    this.$router.afterEach(() => {
      this.showMenu = false
    })
  },

  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside)
    window.removeEventListener('storage', this.checkLogin)
    window.removeEventListener('focus', this.checkLogin)
    if (this.menuTimer) clearTimeout(this.menuTimer)
  },

  methods: {
    checkLogin() {
      const token = localStorage.getItem('auth_token')
      const userStr = localStorage.getItem('user_info')
      const role = localStorage.getItem('user_type')
      
      if (token && userStr && role === 'moi-gioi') {
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

    async fetchNotifications() {
      this.loadingNotifications = true
      try {
        this.unreadCount = Math.floor(Math.random() * 5)
      } catch (e) {
        console.error('Fetch notifications error:', e)
        this.unreadCount = 0
      } finally {
        this.loadingNotifications = false
      }
    },

    toggleMenu(e) {
      e?.stopPropagation()
      
      if (this.menuTimer) clearTimeout(this.menuTimer)
      
      this.menuTimer = setTimeout(() => {
        this.showMenu = !this.showMenu
      }, 100)
    },

    handleClickOutside(e) {
      if (!e.target.closest('.user-box')) {
        if (this.menuTimer) clearTimeout(this.menuTimer)
        this.menuTimer = setTimeout(() => {
          this.showMenu = false
        }, 150)
      }
    },

    goToLogin() {
      this.$router.push("/moi-gioi/dang-nhap")
    },

    goToRegister() {
      this.$router.push("/moi-gioi/dang-ky")
    },

    goToNotifications() {
      this.$router.push("/moi-gioi/thong-bao")
      this.unreadCount = 0 
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
        this.$router.push("/moi-gioi/dang-nhap")
      }, 400)
    }
  }
};
</script>

<style scoped>
/* ===== HEADER BASE ===== */
.header {
  position: fixed;
  top: 0; 
  /* ĐẨY HEADER SANG PHẢI 260px ĐỂ KHÔNG ĐÈ LÊN SIDEBAR */
  left: 260px; 
  right: 0;
  height: 70px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(6, 95, 70, 0.1);
  z-index: 20; /* Hạ z-index xuống một chút so với 1000 để an toàn với các popup khác */
  overflow: visible;
  transition: left 0.3s ease; /* Thêm hiệu ứng mượt khi resize */
}

.container {
  max-width: 100%;
  margin: auto;
  height: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 24px;
}

/* ===== LOGO ===== */
.logo {
  font-weight: 900;
  color: #065f46;
  font-size: 17px;
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  transition: opacity 0.2s ease;
}
.logo:hover { opacity: 0.9; }
.logo-icon { font-size: 20px; }

/* Ẩn chữ Logo trên máy tính vì Sidebar đã có */
.logo-text { display: none; }

.badge {
  font-size: 10px;
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
  padding: 3px 8px;
  border-radius: 20px;
  font-weight: 700;
  letter-spacing: 0.5px;
  display: none; /* Ẩn badge trên desktop luôn */
}

/* ===== ACTIONS ===== */
.actions {
  display: flex; align-items: center; gap: 12px;
  margin-left: auto; /* Đẩy cụm action về góc phải */
}

/* ===== BUTTONS ===== */
.btn-login, .btn-register {
  padding: 9px 18px; border-radius: 999px;
  font-weight: 600; font-size: 13px; cursor: pointer;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-login {
  border: 2px solid #10b981; color: #10b981; background: transparent;
}
.btn-login:hover {
  background: #10b981; color: white; transform: translateY(-1px);
}
.btn-login:active { transform: scale(0.98); transition: transform 0.1s ease; }

.btn-register {
  background: linear-gradient(135deg, #065f46, #10b981); color: white; border: none;
}
.btn-register:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}
.btn-register:active { transform: scale(0.98); transition: transform 0.1s ease; }

/* ===== NOTIFICATION ===== */
.notification-btn {
  position: relative; background: none; border: none;
  font-size: 18px; cursor: pointer; padding: 8px;
  border-radius: 50%; transition: all 0.2s ease;
}
.notification-btn:hover {
  background: #f0fdf4; transform: scale(1.05);
}
.notification-btn:active { transform: scale(0.95); transition: transform 0.1s ease; }

.notification-badge {
  position: absolute; top: 2px; right: 2px;
  background: #ef4444; color: white;
  font-size: 10px; font-weight: 700;
  min-width: 18px; height: 18px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  border: 2px solid white; animation: pulse 2s infinite;
}
@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.8; transform: scale(1.1); }
}

/* ===== USER BOX ===== */
.user-box { position: relative; display: flex; align-items: center; gap: 8px; }

.avatar-wrapper {
  display: flex; align-items: center; gap: 8px;
  cursor: pointer; padding: 4px 8px; border-radius: 999px;
  transition: all 0.2s ease; outline: none;
}
.avatar-wrapper:hover { background: #f0fdf4; }
.avatar-wrapper:focus { box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3); }
.avatar-wrapper:active { transform: scale(0.98); }

.avatar {
  width: 34px; height: 34px; border-radius: 50%;
  background: linear-gradient(135deg, #065f46, #10b981);
  color: white; display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 14px; flex-shrink: 0;
  transition: transform 0.15s ease;
}
.avatar-wrapper:active .avatar { transform: scale(0.95); }

.avatar-name {
  font-size: 13px; font-weight: 600; color: #1f2937;
  max-width: 100px; overflow: hidden;
  text-overflow: ellipsis; white-space: nowrap;
}

.arrow {
  font-size: 8px; color: #6b7280;
  transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.arrow.rotate { transform: rotate(180deg); }

/* ===== DROPDOWN - SMOOTH ANIMATION ===== */
.dropdown {
  position: absolute; right: 0; top: 52px;
  width: 280px; background: white;
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(6, 95, 70, 0.15);
  border: 1px solid rgba(229, 231, 235, 0.8);
  overflow: hidden; z-index: 1001;
}

.dropdown-fade-enter-active,
.dropdown-fade-leave-active {
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  transition-delay: 0.05s;
}
.dropdown-fade-enter-from,
.dropdown-fade-leave-to {
  opacity: 0;
  transform: translateY(-8px) scale(0.98);
}

/* Dropdown Header */
.dropdown-header {
  padding: 16px;
  background: linear-gradient(135deg, #065f46, #10b981);
  display: flex; align-items: center; gap: 12px;
}

.avatar-large {
  width: 48px; height: 48px; border-radius: 50%;
  background: white; color: #065f46;
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 18px;
  border: 3px solid rgba(255,255,255,0.3);
  flex-shrink: 0;
}

.profile-info { flex: 1; min-width: 0; }
.profile-name {
  color: white; font-weight: 700; font-size: 14px; margin: 0;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.profile-code {
  color: rgba(255,255,255,0.85); font-size: 11px;
  margin: 2px 0 0; font-weight: 500;
}

/* Stats */
.dropdown-stats {
  display: flex; padding: 12px 16px;
  background: #f0fdf4; gap: 16px;
  border-bottom: 1px solid #e5e7eb;
}
.stat-item { flex: 1; text-align: center; }
.stat-value {
  display: block; font-weight: 800; color: #065f46; font-size: 18px;
}
.stat-label {
  font-size: 10px; color: #6b7280; font-weight: 600;
  text-transform: uppercase; letter-spacing: 0.5px;
}

/* Divider */
.dropdown-divider {
  height: 1px; background: #e5e7eb; margin: 4px 0;
}

/* Menu Items */
.dropdown-item {
  display: flex; align-items: center; gap: 10px;
  width: 100%; padding: 12px 16px;
  border: none; background: none;
  cursor: pointer; text-decoration: none;
  color: #374151; font-size: 13px; font-weight: 500;
  transition: all 0.15s ease; text-align: left;
}
.dropdown-item:hover {
  background: #f9fafb; color: #065f46;
  padding-left: 20px;
}
.dropdown-item .icon {
  font-size: 16px; width: 20px; text-align: center; flex-shrink: 0;
}

/* Primary Item */
.dropdown-item.primary {
  background: linear-gradient(135deg, #10b981, #065f46);
  color: white; font-weight: 600;
}
.dropdown-item.primary:hover {
  background: linear-gradient(135deg, #059669, #047857);
  color: white; padding-left: 20px;
}

/* Hot Badge */
.hot-badge {
  margin-left: auto;
  background: #ef4444; color: white;
  font-size: 9px; font-weight: 800;
  padding: 2px 6px; border-radius: 4px;
  animation: pulse 2s infinite;
}

/* Logout */
.logout-btn {
  color: #ef4444 !important;
  border-top: 1px solid #f3f4f6;
  margin-top: 4px; padding-top: 14px !important;
}
.logout-btn:hover {
  background: #fef2f2 !important;
  color: #dc2626 !important;
  padding-left: 20px !important;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .header {
    left: 0; /* Trả về full width khi Sidebar gập lại trên màn hình nhỏ */
  }
  .logo-text, .badge {
    display: inline-block; /* Hiện lại Logo trên Mobile */
  }
}

@media (max-width: 768px) {
  .avatar-name { display: none; }
  .dropdown { width: 260px; right: -10px; }
  .logo { font-size: 15px; }
  .btn-login, .btn-register { padding: 8px 14px; font-size: 12px; }
}

@media (max-width: 480px) {
  .container { padding: 0 12px; }
  .dropdown { width: 240px; }
  .profile-code { display: none; }
}
</style>