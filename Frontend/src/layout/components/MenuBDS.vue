<template>
  <aside class="admin-sidebar">
    <div class="admin-sidebar__brand">
      <p class="admin-sidebar__brand-name">Architectural Curator</p>
      <p class="admin-sidebar__brand-subtitle">Real Estate Authority</p>
    </div>

    <nav class="admin-sidebar__nav" aria-label="Menu quản trị">
      <div class="admin-sidebar__group">
        <template v-for="item in primaryItems" :key="item.id">
          <RouterLink
            v-if="item.route"
            :to="item.route"
            class="admin-sidebar__link"
            :class="{ 'is-active': route.path === item.route }"
          >
            <span class="material-symbols-outlined">{{ item.icon }}</span>
            <span class="admin-sidebar__label">{{ item.label }}</span>
          </RouterLink>

          <button
            v-else
            type="button"
            class="admin-sidebar__link is-static"
          >
            <span class="material-symbols-outlined">{{ item.icon }}</span>
            <span class="admin-sidebar__label">{{ item.label }}</span>
          </button>
        </template>
      </div>

      <div class="admin-sidebar__divider"></div>

      <div class="admin-sidebar__group">
        <template v-for="item in secondaryItems" :key="item.id">
          <RouterLink
            v-if="item.route"
            :to="item.route"
            class="admin-sidebar__link"
            :class="{ 'is-active': route.path === item.route }"
          >
            <span class="material-symbols-outlined">{{ item.icon }}</span>
            <span class="admin-sidebar__label">{{ item.label }}</span>
          </RouterLink>

          <button
            v-else
            type="button"
            class="admin-sidebar__link is-static"
          >
            <span class="material-symbols-outlined">{{ item.icon }}</span>
            <span class="admin-sidebar__label">{{ item.label }}</span>
          </button>
        </template>
      </div>
    </nav>
  </aside>
</template>

<script setup>
import { RouterLink, useRoute } from "vue-router";

const route = useRoute();

const primaryItems = [
  { id: "dashboard", label: "Dashboard", icon: "dashboard", route: "/admin/dashboard" },
  { id: "customers", label: "Quản lý khách hàng", icon: "group" },
  { id: "brokers", label: "Quản lý môi giới", icon: "handshake" },
  { id: "properties", label: "Quản lý bất động sản", icon: "domain" },
  { id: "packages", label: "Quản lý gói tin", icon: "inventory_2" },
  { id: "history", label: "Lịch sử mua gói", icon: "history_edu" },
  { id: "transactions", label: "Quản lý giao dịch", icon: "payments" },
];

const secondaryItems = [
  { id: "roles", label: "Chức vụ", icon: "badge", route: "/admin/chuc-vu" },
  { id: "permissions", label: "Phân quyền", icon: "admin_panel_settings" },
  { id: "profile", label: "Hồ sơ admin", icon: "account_circle" },
];
</script>

<style scoped>
.admin-sidebar {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: 30;
  width: 260px;
  display: flex;
  flex-direction: column;
  padding: 24px 16px 22px;
  background: linear-gradient(180deg, #efefee 0%, #ececea 100%);
  border-right: 1px solid rgba(27, 35, 126, 0.04);
  overflow-y: auto;
}

.admin-sidebar__brand {
  padding: 6px 10px 24px;
}

.admin-sidebar__brand-name {
  display: block;
  color: #1a237e;
  font-size: 1.2rem;
  font-weight: 800;
  line-height: 1.15;
  text-transform: uppercase;
  letter-spacing: -0.03em;
  margin: 0;
}

.admin-sidebar__brand-subtitle {
  margin: 8px 0 0;
  color: #8c91a2;
  font-family: "Inter", sans-serif;
  font-size: 0.62rem;
  font-weight: 700;
  letter-spacing: 0.18em;
  text-transform: uppercase;
}

.admin-sidebar__nav {
  display: flex;
  flex: 1;
  flex-direction: column;
  gap: 16px;
}

.admin-sidebar__group {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.admin-sidebar__divider {
  height: 1px;
  margin: 0 10px;
  background: rgba(120, 126, 148, 0.18);
}

.admin-sidebar__link {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  min-height: 46px;
  padding: 12px 14px;
  border: 0;
  border-radius: 14px;
  appearance: none;
  color: #61687b;
  background: transparent;
  font-family: "Inter", sans-serif;
  font-size: 0.9rem;
  font-weight: 600;
  text-align: left;
  cursor: pointer;
  transition:
    color 0.22s ease,
    background-color 0.22s ease,
    transform 0.22s ease,
    box-shadow 0.22s ease;
}

.admin-sidebar__link .material-symbols-outlined {
  font-size: 1.15rem;
}

.admin-sidebar__link:hover {
  color: #1a237e;
  background: rgba(255, 255, 255, 0.62);
  transform: translateX(2px);
}

.admin-sidebar__link.is-active {
  color: #1a237e;
  background: rgba(255, 255, 255, 0.86);
  box-shadow:
    inset -3px 0 0 #1a237e,
    0 10px 20px rgba(22, 34, 94, 0.08);
}

.admin-sidebar__link.is-static {
  cursor: default;
}

.admin-sidebar__link.is-static:hover {
  transform: none;
}

.admin-sidebar__label {
  line-height: 1.2;
}

@media (max-width: 1024px) {
  .admin-sidebar {
    position: relative;
    width: 100%;
    padding-bottom: 16px;
  }

  .admin-sidebar__nav {
    gap: 12px;
  }
}
</style>
