<template>
<div>
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
        <!-- Nút Đăng tin -->
        <button @click="handlePostListing" class="btn-post">
          <span class="icon">✎</span><span class="label">Đăng tin</span>
        </button>

        <!-- ✅ Nút Tin đã lưu -->
        <div class="saved-trigger-wrapper" v-if="isLoggedIn">
          <button 
            @click.stop="toggleNotifPanel" 
            class="btn-saved"
            aria-label="Tin đã lưu"
          >
            <span class="icon">❤️</span>
            <span class="label">Tin đã lưu</span>
          </button>

          <!-- 🔔 NOTIFICATION PANEL - DESKTOP ONLY -->
          <transition name="notif-slide">
            <div v-if="showNotifPanel" class="notif-panel" @click.stop>
              <!-- Header -->
              <div class="notif-header">
                <h3 class="notif-title">Tin đã lưu</h3>
                <span class="notif-count">{{ notifications.length }} tin</span>
              </div>

              <!-- List -->
              <div class="notif-list">
                <!-- Danh sách tin đã lưu với Swipe to Delete (Desktop) -->
                <div 
                  v-for="item in notifications" 
                  :key="item.id" 
                  class="notif-item-wrapper"
                  :class="{ deleting: item.isDeleting }"
                >
                  <div class="notif-item-swipeable" :style="{ transform: `translateX(${item.swipeOffset || 0}px)` }">
                    
                    <!-- ✅ Nút XÓA (hiện khi vuốt trái) -->
                    <div class="notif-delete-btn" @click.stop="deleteSavedItem(item)">
                      <span class="material-symbols-outlined">delete</span>
                      <span>Xóa</span>
                    </div>
                    
                    <!-- Nội dung chính - có thể vuốt để hiện nút xóa -->
                    <div 
                      class="notif-item"
                      @click="handleNotifClick(item)"
                      @mousedown="handleMouseDown($event, item)"
                      @mousemove="handleMouseMove($event, item)"
                      @mouseup="handleMouseUp($event, item)"
                      @mouseleave="handleMouseUp($event, item)"
                      :class="{ 'dragging': draggedItem === item }"
                    >
                      <div class="notif-image-wrapper">
                        <img 
                          :src="getImageUrl(item.avatar)" 
                          :alt="item.text"
                          @error="handleImageError"
                          class="notif-image"
                        />
                      </div>
                      <div class="notif-content">
                        <p class="notif-text">{{ item.text }}</p>
                        <span class="notif-time">{{ item.time }}</span>
                      </div>
                      <span class="material-symbols-outlined notif-arrow">arrow_forward</span>
                    </div>
                  </div>
                </div>

                <!-- Empty State -->
                <div v-if="notifications.length === 0 && !loadingNotifs" class="notif-empty">
                  <div class="notif-empty-icon">📭</div>
                  <p class="notif-empty-title">Chưa có tin nào được lưu</p>
                  <p class="notif-empty-hint">Bấm ❤️ để lưu tin bạn quan tâm</p>
                </div>

                <!-- Loading State -->
                <div v-if="loadingNotifs" class="notif-empty">
                  <div class="notif-loading-spinner"></div>
                  <p>Đang tải...</p>
                </div>
              </div>

              <!-- Footer -->
              <div class="notif-footer">
                <button class="notif-btn-primary" @click="goToSavedPage">
                  Xem tất cả tin đã lưu
                </button>
              </div>
            </div>
          </transition>
        </div>

        <!-- CHƯA LOGIN -->
        <template v-else>
          <button @click="goToLogin" class="btn-login">Đăng nhập</button>
        </template>

        <!-- ĐÃ LOGIN - User Menu -->
        <div v-if="isLoggedIn" class="user-wrapper" @click="toggleMenu" style="cursor: pointer; transition: transform 0.15s ease;">
          <div class="avatar" style="transition: transform 0.15s ease;">{{ userInitial }}</div>
          <span class="user-name">{{ userName }}</span>
          <span class="arrow" :class="{ open: showMenu }" style="transition: transform 0.2s ease;">▼</span>

          <!-- ✅ Dropdown Menu - FIX: overflow visible + scrollable content -->
          <transition name="dropdown-smooth">
            <div 
              v-show="showMenu" 
              class="dropdown-menu" 
              @click.stop
              style="
                position: absolute; 
                top: 100%; 
                right: 0; 
                margin-top: 8px; 
                width: 260px; 
                background: white; 
                border: 1px solid #e2e8f0; 
                border-radius: 12px; 
                box-shadow: 0 20px 60px rgba(0,0,0,0.15); 
                z-index: 9999; 
                overflow: visible;
              "
            >
              
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

              <!-- ✅ Scrollable content area -->
              <div style="padding: 8px 0; max-height: 50vh; overflow-y: auto;">
                
                <router-link to="/khach-hang/da-luu" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #374151; text-decoration: none; font-size: 14px; transition: background 0.15s ease, padding-left 0.15s ease;" @click="showMenu = false">
                  <span>❤️</span><span>Tin đã lưu</span>
                </router-link>
                
                <div style="height: 1px; background: #e5e7eb; margin: 8px 0;"></div>
                
                <router-link to="/cai-dat" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #374151; text-decoration: none; font-size: 14px; transition: background 0.15s ease, padding-left 0.15s ease;" @click="showMenu = false">
                  <span>⚙️</span><span>Cài đặt</span>
                </router-link>
                
                <div style="height: 1px; background: #fee2e2; margin: 8px 0;"></div>
                
                <!-- ✅ Logout button - FIX: proper styling + hover effect -->
                <button 
                  @click="handleLogout" 
                  class="logout-button"
                  style="
                    width: 100%; 
                    display: flex; 
                    align-items: center; 
                    gap: 10px; 
                    padding: 12px 16px; 
                    background: none; 
                    border: none; 
                    color: #ef4444; 
                    font-weight: 600; 
                    cursor: pointer; 
                    font-size: 14px; 
                    text-align: left; 
                    transition: background 0.15s ease;
                    border-radius: 8px;
                    margin: 4px 8px;
                    box-sizing: border-box;
                  "
                >
                  <span>🚪</span><span>Đăng xuất</span>
                </button>
                
              </div>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </nav>      

  <!-- Modal Broker -->
  <transition name="modal-smooth">
    <div v-if="showBrokerModal" class="modal-overlay" @click.self="closeBrokerModal">
      <div class="modal-box">
        <div class="modal-header">
          <span class="modal-icon">🔐</span>
          <h3 class="modal-title">Yêu cầu tài khoản Môi giới</h3>
        </div>
        <div class="modal-body">
          <p class="modal-text">
            Chức năng đăng tin chỉ dành cho tài khoản
            <strong>Môi giới bất động sản</strong>.
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
</div>
</template>

<script>
import axios from 'axios';
import { createToaster } from "@meforma/vue-toaster";

// ✅ Khởi tạo toaster với error handling
let toaster;
try {
  toaster = createToaster({ 
    position: "top-right",
    duration: 2500,
    queue: true
  });
} catch (e) {
  console.warn('Toaster init failed, using fallback:', e);
  toaster = {
    success: (msg) => alert('✅ ' + msg),
    error: (msg) => alert('❌ ' + msg),
    warning: (msg) => alert('⚠️ ' + msg),
    info: (msg) => alert('ℹ️ ' + msg)
  };
}

export default {
  name: 'CustomerHeader',
  
  data() {
    return {
      user: null,
      token: null,
      userType: null,
      showMenu: false,
      showBrokerModal: false,
      showNotifPanel: false,
      loadingNotifs: false,
      notifications: [],
      defaultImage: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=200&q=80',
      
      // ✅ Desktop Swipe State
      dragStartX: 0,
      draggedItem: null,
      isDragging: false
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
      const labels = { 'moi-gioi': 'Môi giới', 'khach-hang': 'Khách hàng', 'admin': 'Admin' }
      return labels[this.userType] || 'Người dùng'
    }
  },

  async mounted() {
    const isLogged = this.checkLogin()
    if (isLogged) {
      await this.loadSavedNotifications()
    }
    document.addEventListener('click', this.handleClickOutside)
    window.addEventListener('storage', this.checkLogin)
    window.addEventListener('favorite-updated', this.loadSavedNotifications)
    document.addEventListener('selectstart', this.preventSelect)
  },

  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside)
    window.removeEventListener('storage', this.checkLogin)
    window.removeEventListener('favorite-updated', this.loadSavedNotifications)
    document.removeEventListener('selectstart', this.preventSelect)
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
          return true
        } catch (e) {
          console.error('Parse user error:', e)
          this.clearData()
        }
      } else {
        this.clearData()
      }
      return false
    },

    clearData() {
      this.token = null
      this.user = null
      this.userType = null
      this.showMenu = false
      this.showNotifPanel = false
    },

    toggleMenu(e) {
      e.stopPropagation() // 🔥 BẮT BUỘC
      this.showNotifPanel = false
      this.showMenu = !this.showMenu
    },

    handleClickOutside(e) {
      const isClickUser = e.target.closest('.user-wrapper')
      const isClickDropdown = e.target.closest('.dropdown-menu')

      if (!isClickUser && !isClickDropdown) {
        this.showMenu = false
      }

      if (!e.target.closest('.saved-trigger-wrapper')) {
        this.showNotifPanel = false
      }
    },

    preventSelect(e) {
      if (this.isDragging) e.preventDefault()
    },

    goToLogin() { 
      this.$router.push('/khach-hang/dang-nhap').catch(err => {
        console.warn('Router error:', err)
        window.location.href = '/khach-hang/dang-nhap'
      })
    },

    handlePostListing() {
      if (!this.isLoggedIn) { 
        this.$router.push('/moi-gioi/dang-nhap').catch(() => window.location.href = '/moi-gioi/dang-nhap')
        return 
      }
      if (this.userType === 'moi-gioi') { 
        this.$router.push('/moi-gioi/quan-ly-tin').catch(() => window.location.href = '/moi-gioi/quan-ly-tin')
        return 
      }
      toaster.warning('Chức năng này chỉ dành cho tài khoản Môi giới', { duration: 3000 })
      this.showBrokerModal = true
      this.showMenu = false
    },

    closeBrokerModal() { this.showBrokerModal = false },

    switchToBrokerLogin() {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_info')
      localStorage.removeItem('user_type')
      this.clearData()
      toaster.info('Vui lòng đăng nhập tài khoản Môi giới', { duration: 2000 })
      setTimeout(() => { 
        this.$router.push('/moi-gioi/dang-nhap').catch(() => window.location.href = '/moi-gioi/dang-nhap')
      }, 300)
      this.closeBrokerModal()
    },

    // ✅ FIX: Handle Logout với error handling đầy đủ
    handleLogout() {
      console.log('🔥 Logout clicked')
      
      // 1. Xóa token khỏi localStorage
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_info')
      localStorage.removeItem('user_type')
      console.log('🗑️ Tokens removed')
      
      // 2. Clear data component
      this.clearData()
      console.log('🧹 Data cleared')
      
      // 3. Đóng dropdown ngay
      this.showMenu = false
      
      // 4. Thông báo thành công (có fallback)
      try {
        if (toaster && typeof toaster.success === 'function') {
          toaster.success('Đăng xuất thành công!', { duration: 2000, variant: 'success' })
        } else {
          alert('✅ Đăng xuất thành công!')
        }
      } catch (e) {
        console.warn('Toaster error:', e)
        alert('✅ Đăng xuất thành công!')
      }
      
      // 5. Chuyển trang với error handling
      setTimeout(() => {
        console.log('🔄 Navigating to home...')
        if (this.$router && typeof this.$router.push === 'function') {
          this.$router.push('/').catch(err => {
            console.warn('Router push error:', err)
            window.location.href = '/'
          })
        } else {
          window.location.href = '/'
        }
      }, 300)
    },

    async loadSavedNotifications() {
      const token = localStorage.getItem('auth_token')
      if (!token) return

      this.loadingNotifs = true
      try {
        const res = await axios.get(
          "http://127.0.0.1:8000/api/khach-hang/bds/yeu-thich/data",
          { headers: { Authorization: `Bearer ${token}` } }
        )

        if (res.data?.status && Array.isArray(res.data.data)) {
          const isValidUrl = (u) => typeof u === "string" && u.trim() !== "" && u !== "null" && u !== "undefined";

          this.notifications = res.data.data.map(item => {
            const bds = item.batDongSan || item.bat_dong_san || item.property || {}
            let avatar = this.defaultImage;

            if (Array.isArray(bds.hinhAnh) && bds.hinhAnh.length > 0) {
              const img = bds.hinhAnh.find(i => i && isValidUrl(i.url));
              if (img) avatar = this.getImageUrl(img.url.trim());
            }
            if (avatar === this.defaultImage && bds.anh_dai_dien && isValidUrl(bds.anh_dai_dien.url)) {
              avatar = this.getImageUrl(bds.anh_dai_dien.url.trim());
            }

            return {
              id: item.id || bds.id,
              text: bds.tieu_de || 'Bất động sản',
              time: this.formatTime(item.created_at || item.createdAt),
              propertyId: bds.id || item.bat_dong_san_id,
              avatar: avatar,
              price: bds.gia,
              swipeOffset: 0,
              isDeleting: false
            }
          })
        } else {
          this.notifications = []
        }
      } catch (err) {
        console.error("Lỗi load saved notifications:", err)
        this.notifications = []
        if (err.response?.status === 401) {
          this.clearData()
          this.$router.push('/khach-hang/dang-nhap').catch(() => window.location.href = '/khach-hang/dang-nhap')
        }
      } finally {
        this.loadingNotifs = false
      }
    },

    formatTime(dateStr) {
      if (!dateStr) return 'Vừa xong'
      const now = new Date()
      const created = new Date(dateStr)
      const diff = Math.floor((now - created) / 1000)
      if (diff < 60) return 'Vừa xong'
      if (diff < 3600) return `${Math.floor(diff/60)} phút trước`
      if (diff < 86400) return `${Math.floor(diff/3600)} giờ trước`
      return created.toLocaleDateString('vi-VN')
    },

    getImageUrl(url) {
      if (!url) return this.defaultImage
      if (typeof url === 'string' && url.startsWith('http')) return url
      const cleanUrl = url.replace(/^\/+/, '')
      return `http://127.0.0.1:8000/storage/${cleanUrl}`
    },

    handleImageError(e) { e.target.src = this.defaultImage },

    toggleNotifPanel() {
      this.showNotifPanel = !this.showNotifPanel
      this.showMenu = false
      if (this.showNotifPanel && this.isLoggedIn) {
        this.loadSavedNotifications()
      }
    },

    handleNotifClick(item) {
      if (item.swipeOffset < -80) return
      this.showNotifPanel = false
      this.$router.push(`/khach-hang/chi-tiet-bat-dong-san/${item.propertyId}`).catch(err => {
        console.warn('Router error:', err)
        window.location.href = `/khach-hang/chi-tiet-bat-dong-san/${item.propertyId}`
      })
    },

    // ✅ DESKTOP MOUSE SWIPE FUNCTIONS
    handleMouseDown(e, item) {
      if (e.button !== 0) return
      this.isDragging = true
      this.draggedItem = item
      this.dragStartX = e.clientX
      item.swipeOffset = 0
      e.currentTarget.style.cursor = 'grabbing'
    },

    handleMouseMove(e, item) {
      if (!this.isDragging || this.draggedItem !== item) return
      const diff = e.clientX - this.dragStartX
      if (diff < 0) {
        item.swipeOffset = Math.max(diff, -120)
      }
    },

    handleMouseUp(e, item) {
      if (!this.isDragging || this.draggedItem !== item) return
      e.currentTarget.style.cursor = 'pointer'
      if (item.swipeOffset < -80) {
        item.swipeOffset = -120
      } else {
        item.swipeOffset = 0
      }
      this.isDragging = false
      this.draggedItem = null
    },

    // ✅ DELETE FUNCTION
    async deleteSavedItem(item) {
      const token = localStorage.getItem('auth_token')
      if (!token) return

      try {
        await axios.post(
          "http://127.0.0.1:8000/api/khach-hang/bds/yeu-thich",
          { bds_id: item.propertyId },
          { headers: { Authorization: `Bearer ${token}` } }
        )

        const index = this.notifications.findIndex(n => n.id === item.id)
        if (index === -1) return

        this.notifications[index].isDeleting = true
        this.notifications[index].swipeOffset = 0

        setTimeout(() => {
          this.notifications.splice(index, 1)
          toaster.success('Đã xóa tin khỏi danh sách lưu', { duration: 2000 })
          window.dispatchEvent(new Event('favorite-updated'))
        }, 300)

      } catch (err) {
        console.error("Lỗi xóa tin đã lưu:", err.response?.data || err)
        toaster.error('Có lỗi xảy ra khi xóa', { duration: 2000 })
        item.swipeOffset = 0
      }
    },

    goToSavedPage() {
      this.showNotifPanel = false
      this.$router.push('/khach-hang/da-luu').catch(err => {
        console.warn('Router error:', err)
        window.location.href = '/khach-hang/da-luu'
      })
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
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(0, 0, 0, 0.06);
  z-index: 1000;
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
.logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
.logo-icon { font-size: 24px; }
.logo-text { display: flex; flex-direction: column; line-height: 1.1; }
.brand { font-weight: 800; color: #1e293b; font-size: 17px; }
.sub { font-weight: 600; color: #3b82f6; font-size: 12px; }
.actions { display: flex; align-items: center; gap: 12px; }

/* ===== BUTTONS ===== */
.btn-post {
  display: flex; align-items: center; gap: 7px;
  padding: 10px 20px; border-radius: 999px;
  background: linear-gradient(135deg, #10b981, #059669);
  color: white; font-weight: 700; font-size: 13px;
  border: none; cursor: pointer;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
}
.btn-post:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(16, 185, 129, 0.45); }
.btn-post:active { transform: translateY(0) scale(0.98); transition: transform 0.1s ease; }
.btn-post .icon { font-size: 15px; }
.btn-post .label { display: none; }
@media (min-width: 640px) { .btn-post .label { display: inline; } }

.saved-trigger-wrapper { position: relative; }
.btn-saved {
  display: flex; align-items: center; gap: 7px;
  padding: 10px 20px; border-radius: 999px;
  background: white; color: #ef4444;
  font-weight: 600; font-size: 13px;
  border: 1px solid #fecaca; cursor: pointer;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.btn-saved:hover { background: #fef2f2; border-color: #ef4444; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(239, 68, 68, 0.25); }
.btn-saved:active { transform: translateY(0) scale(0.98); transition: transform 0.1s ease; }
.btn-saved .icon { font-size: 15px; }
.btn-saved .label { display: none; }
@media (min-width: 640px) { .btn-saved .label { display: inline; } }

.btn-login {
  padding: 10px 22px; border-radius: 999px;
  background: #3b82f6; color: white;
  border: none; font-weight: 600; font-size: 13px;
  cursor: pointer; transition: all 0.2s ease;
}
.btn-login:hover { background: #2563eb; transform: translateY(-1px); }
.btn-login:active { transform: scale(0.98); transition: transform 0.1s ease; }

/* ===== USER WRAPPER ===== */
.user-wrapper {
  position: relative;
  display: flex; align-items: center; gap: 10px;
  padding: 6px 12px; border-radius: 999px;
  cursor: pointer; background: #f8fafc;
  border: 1px solid #e2e8f0; transition: all 0.2s ease;
}
.user-wrapper:hover { background: #f1f5f9; border-color: #cbd5e1; transform: translateY(-1px); }
.user-wrapper:active { transform: scale(0.98); transition: transform 0.1s ease; }
.avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: white; display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 14px; flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3); transition: transform 0.15s ease;
}
.user-wrapper:active .avatar { transform: scale(0.95); }
.user-name { font-weight: 600; color: #1e293b; font-size: 14px; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.arrow { display: flex; align-items: center; color: #64748b; transition: transform 0.25s ease; margin-left: 4px; }
.arrow.open { transform: rotate(180deg); }

/* ===== DROPDOWN & MODAL ANIMATIONS ===== */
.dropdown-smooth-enter-active, .dropdown-smooth-leave-active { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); transition-delay: 0.05s; }
.dropdown-smooth-enter-from, .dropdown-smooth-leave-to { opacity: 0; transform: translateY(-5px) scale(0.99); }

.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 10000; padding: 20px; }
.modal-smooth-enter-active, .modal-smooth-leave-active { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.modal-smooth-enter-from, .modal-smooth-leave-to { opacity: 0; }
.modal-smooth-enter-from .modal-box, .modal-smooth-leave-to .modal-box { transform: scale(0.97) translateY(8px); transition: transform 0.25s ease 0.05s, opacity 0.2s ease; }
.modal-box { background: white; border-radius: 20px; width: 100%; max-width: 420px; box-shadow: 0 25px 80px rgba(0,0,0,0.25); overflow: hidden; }
.modal-header { padding: 20px 24px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; gap: 12px; }
.modal-icon { font-size: 24px; }
.modal-title { margin: 0; color: white; font-weight: 700; font-size: 16px; }
.modal-body { padding: 24px; }
.modal-text { margin: 0; color: #475569; font-size: 14px; line-height: 1.6; }
.modal-text strong { color: #1e293b; }
.modal-footer { padding: 16px 24px 24px; display: flex; gap: 12px; justify-content: flex-end; }
.btn-modal { padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 13px; cursor: pointer; transition: all 0.2s ease; border: none; }
.btn-modal.cancel { background: #f1f5f9; color: #475569; }
.btn-modal.cancel:hover { background: #e2e8f0; transform: translateY(-1px); }
.btn-modal.confirm { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3); }
.btn-modal.confirm:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(99, 102, 241, 0.45); }
.btn-modal:active { transform: scale(0.98); transition: transform 0.1s ease; }

/* 🔔 ===== NOTIFICATION PANEL - DESKTOP SWIPE ===== */
.notif-panel {
  position: absolute;
  top: calc(100% + 12px);
  right: 0;
  width: 420px;
  max-height: 85vh;
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.15);
  border: 1px solid #e5e7eb;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  z-index: 9999;
}
.notif-header {
  padding: 18px 20px;
  border-bottom: 1px solid #e5e7eb;
  background: #f9fafb;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-shrink: 0;
}
.notif-title { color: #111827; font-size: 18px; font-weight: 700; margin: 0; }
.notif-count {
  color: #6b7280; font-size: 13px; font-weight: 500;
  background: #f3f4f6; padding: 4px 12px; border-radius: 20px;
}
.notif-list {
  flex: 1; overflow-y: auto; padding: 8px 0; background: #ffffff;
  max-height: calc(85vh - 130px);
}
.notif-list::-webkit-scrollbar { width: 6px; }
.notif-list::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
.notif-list::-webkit-scrollbar-track { background: transparent; }

/* ===== SWIPE WRAPPER ===== */
.notif-item-wrapper {
  position: relative; overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.notif-item-wrapper.deleting {
  opacity: 0; transform: translateX(-100%);
}
.notif-item-swipeable {
  position: relative;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: transform;
}

/* ✅ DELETE BUTTON */
.notif-delete-btn {
  position: absolute; right: 0; top: 0; bottom: 0;
  width: 120px;
  background: linear-gradient(135deg, #ef4444, #dc2626);
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 4px; color: white; cursor: pointer;
  user-select: none; z-index: 0;
}
.notif-delete-btn:hover { background: linear-gradient(135deg, #dc2626, #b91c1c); }
.notif-delete-btn .material-symbols-outlined { font-size: 24px; }
.notif-delete-btn span:last-child { font-size: 12px; font-weight: 600; }

/* Main content */
.notif-item {
  display: flex; align-items: center; gap: 14px;
  padding: 14px 20px; cursor: pointer;
  transition: background 0.15s;
  background: white; border-bottom: 1px solid #f3f4f6;
  position: relative; z-index: 1; user-select: none;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: #f9fafb; }
.notif-item.dragging { cursor: grabbing; }

.notif-image-wrapper {
  flex-shrink: 0; width: 64px; height: 64px;
  border-radius: 12px; overflow: hidden;
  border: 1px solid #e5e7eb;
}
.notif-image { width: 100%; height: 100%; object-fit: cover; }
.notif-content { flex: 1; min-width: 0; }
.notif-text {
  color: #1f2937; font-size: 14px; line-height: 1.4;
  margin: 0 0 4px; font-weight: 600;
  overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.notif-time { color: #6b7280; font-size: 12px; display: block; }
.notif-arrow { color: #9ca3af; font-size: 18px; flex-shrink: 0; }

.notif-empty { text-align: center; padding: 60px 20px; color: #6b7280; }
.notif-empty-icon { font-size: 56px; margin-bottom: 16px; opacity: 0.4; display: block; }
.notif-empty-title { font-size: 15px; font-weight: 600; color: #374151; margin: 0 0 6px; }
.notif-empty-hint { font-size: 13px; color: #9ca3af; margin: 0; }

.notif-loading-spinner {
  width: 28px; height: 28px;
  border: 3px solid #e5e7eb; border-top-color: #3b82f6;
  border-radius: 50%; animation: spin 1s linear infinite;
  margin: 0 auto 16px;
}
@keyframes spin { to { transform: rotate(360deg); } }

.notif-footer {
  padding: 14px 20px; border-top: 1px solid #e5e7eb;
  background: #f9fafb; flex-shrink: 0;
}
.notif-btn-primary {
  background: #3b82f6; color: white; border: none;
  padding: 11px 16px; border-radius: 10px;
  font-size: 14px; font-weight: 600; cursor: pointer;
  width: 100%; transition: all 0.2s;
}
.notif-btn-primary:hover { background: #2563eb; transform: translateY(-1px); }

/* Animation */
.notif-slide-enter-active, .notif-slide-leave-active { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
.notif-slide-enter-from, .notif-slide-leave-to { opacity: 0; transform: translateY(-10px) scale(0.98); }

/* ✅ FIX: Logout button hover effect */
.logout-button:hover {
  background: #fef2f2 !important;
}

/* ✅ FIX: Dropdown scrollbar styling */
.dropdown-menu div[style*="overflow-y: auto"]::-webkit-scrollbar {
  width: 4px;
}
.dropdown-menu div[style*="overflow-y: auto"]::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 2px;
}
.dropdown-menu div[style*="overflow-y: auto"]::-webkit-scrollbar-track {
  background: transparent;
}
</style>