<template>
  <header class="admin-topbar">
    <div class="admin-topbar__actions">
      <!-- 🔔 Notification -->
      <button class="admin-topbar__icon" type="button">
        <span class="material-symbols-outlined">notifications</span>
        <span class="admin-topbar__dot"></span>
      </button>

      <!-- 👤 PROFILE DROPDOWN -->
      <div
        class="admin-topbar__profile-wrapper"
        :class="{ active: showProfileDropdown }"
      >
        <!-- Avatar Button -->
        <div class="admin-topbar__avatar-btn" @click="toggleProfileDropdown">
          <div class="admin-topbar__avatar">
            {{ getInitials(adminName) }}
          </div>
          <span
            class="material-symbols-outlined admin-dropdown-arrow"
            :class="{ rotated: showProfileDropdown }"
          >
            expand_more
          </span>
        </div>

        <!-- Dropdown Menu -->
        <div class="admin-profile-dropdown" v-if="showProfileDropdown">
          <div class="admin-profile-dropdown__header">
            <div class="admin-profile-dropdown__avatar">
              {{ getInitials(adminName) }}
            </div>
            <div class="admin-profile-dropdown__info">
              <strong>{{ adminName }}</strong>
              <p>Administrator</p>
            </div>
          </div>
          <div class="admin-profile-dropdown__divider"></div>
            <button class="admin-profile-dropdown__item" @click="logout">
              <span class="material-symbols-outlined">logout</span>
              Đăng xuất
            </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import { getCurrentInstance } from "vue";

const router = useRouter();
const { appContext } = getCurrentInstance();
const toast = appContext.config.globalProperties.$toast;

const showProfileDropdown = ref(false);
const adminName = ref("Admin");



// Toggle dropdown
const toggleProfileDropdown = () => {
  showProfileDropdown.value = !showProfileDropdown.value;
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  const wrapper = document.querySelector(".admin-topbar__profile-wrapper");
  if (wrapper && !wrapper.contains(event.target)) {
    showProfileDropdown.value = false;
  }
};

// Get initials from name
const getInitials = (name) => {
  if (!name) return "AD";
  return name
    .split(" ")
    .map((part) => part[0])
    .slice(0, 2)
    .join("")
    .toUpperCase();
};

// Logout
const logout = () => {
  showProfileDropdown.value = false;
  localStorage.removeItem("auth_token");
  localStorage.removeItem("user_type");

  toast.success("Đăng xuất thành công 👋");

  setTimeout(() => {
    router.push("/admin/dang-nhap");
  }, 1000);
};

// Load admin info from token
onMounted(() => {
  const token = localStorage.getItem("auth_token");
  if (token) {
    // Decode token to get admin name (if available)
    try {
      const payload = JSON.parse(atob(token.split(".")[1]));
      if (payload.name) {
        adminName.value = payload.name;
      }
    } catch (e) {
      console.error("Error decoding token:", e);
    }
  }

  // Add click outside listener
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
.admin-topbar {
  position: fixed;
  top: 0;
  left: 260px;
  right: 0;
  z-index: 20;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  height: 74px;
  padding: 12px 28px;
  background: rgba(247, 246, 243, 0.86);
  border-bottom: 1px solid rgba(26, 35, 126, 0.05);
}

.admin-topbar__actions {
  display: flex;
  align-items: center;
  gap: 18px;
}

/* 🔔 Notification */
.admin-topbar__icon {
  position: relative;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: none;
  background: transparent;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
}

.admin-topbar__icon:hover {
  background: rgba(0, 0, 0, 0.05);
}

.admin-topbar__dot {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 6px;
  height: 6px;
  background: #ff4d4f;
  border-radius: 50%;
  border: 2px solid rgba(247, 246, 243, 0.86);
}

/* 👤 Profile Wrapper */
.admin-topbar__profile-wrapper {
  position: relative;
}

.admin-topbar__avatar-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  padding: 4px;
  border-radius: 8px;
  transition: background 0.2s;
}

.admin-topbar__avatar-btn:hover {
  background: rgba(0, 0, 0, 0.05);
}

.admin-topbar__avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #1d2a89, #3342b6);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 0.9rem;
}

.admin-dropdown-arrow {
  font-size: 20px;
  color: #1a237e;
  transition: transform 0.3s;
}

.admin-dropdown-arrow.rotated {
  transform: rotate(180deg);
}

/* 📋 Dropdown Menu */
.admin-profile-dropdown {
  position: absolute;
  top: calc(100% + 10px);
  right: 0;
  width: 280px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(26, 35, 126, 0.1);
  overflow: hidden;
  animation: slideDown 0.2s ease-out;
  z-index: 1000;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.admin-profile-dropdown__header {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.admin-profile-dropdown__avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: linear-gradient(135deg, #1d2a89, #3342b6);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.1rem;
}

.admin-profile-dropdown__info {
  flex: 1;
}

.admin-profile-dropdown__info strong {
  display: block;
  font-size: 0.95rem;
  font-weight: 700;
  color: #1a237e;
  margin-bottom: 2px;
}

.admin-profile-dropdown__info p {
  font-size: 0.75rem;
  color: #6c757d;
  margin: 0;
}

.admin-profile-dropdown__divider {
  height: 1px;
  background: #e9ecef;
  margin: 8px 16px;
}

.admin-profile-dropdown__menu {
  padding: 8px;
}

.admin-profile-dropdown__item {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border: none;
  background: transparent;
  border-radius: 8px;
  cursor: pointer;
  font-size: 0.9rem;
  color: #495057;
  transition: all 0.2s;
  text-align: left;
}

.admin-profile-dropdown__item:hover {
  background: #f8f9fa;
  color: #1a237e;
}

.admin-profile-dropdown__item .material-symbols-outlined {
  font-size: 18px;
  color: #6c757d;
}

.admin-profile-dropdown__item:hover .material-symbols-outlined {
  color: #1a237e;
}

.admin-profile-dropdown__item:last-child {
  color: #ff4d4f;
}

.admin-profile-dropdown__item:last-child:hover {
  background: #fff5f5;
}

.admin-profile-dropdown__item:last-child .material-symbols-outlined {
  color: #ff4d4f;
}
</style>