<template>
  <header class="admin-topbar">
    <div class="admin-topbar__search">
      <span class="material-symbols-outlined">search</span>
      <input type="text" placeholder="Tìm kiếm hệ thống..." />
    </div>

    <div class="admin-topbar__actions">
      <button class="admin-topbar__icon" type="button" aria-label="Thông báo">
        <span class="material-symbols-outlined">notifications</span>
        <span class="admin-topbar__dot"></span>
      </button>

      <div class="admin-topbar__profile">
        <div class="admin-topbar__profile-copy">
          <strong>Admin Profile</strong>
          <p>Administrator</p>
        </div>
        <div class="admin-topbar__avatar">AC</div>
      </div>
    </div>
  </header>
</template>

<style scoped>
.admin-topbar {
  position: fixed;
  top: 0;
  left: 260px;
  right: 0;
  z-index: 20;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  height: 74px;
  padding: 12px 28px;
  background: rgba(247, 246, 243, 0.86);
  border-bottom: 1px solid rgba(26, 35, 126, 0.05);
  backdrop-filter: blur(18px);
}

.admin-topbar__search {
  position: relative;
  flex: 1;
  max-width: 480px;
  min-width: 240px;
}

.admin-topbar__search .material-symbols-outlined {
  position: absolute;
  top: 50%;
  left: 16px;
  transform: translateY(-50%);
  color: #a4a9b7;
  font-size: 1.08rem;
}

.admin-topbar__search input {
  width: 100%;
  height: 44px;
  padding: 0 16px 0 44px;
  border: 1px solid rgba(209, 213, 225, 0.88);
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.85);
  color: #283046;
  font-family: "Inter", sans-serif;
  font-size: 0.88rem;
  outline: none;
  transition:
    border-color 0.22s ease,
    box-shadow 0.22s ease,
    background-color 0.22s ease;
}

.admin-topbar__search input::placeholder {
  color: #b1b6c3;
}

.admin-topbar__search input:focus {
  border-color: rgba(26, 35, 126, 0.18);
  box-shadow: 0 0 0 4px rgba(26, 35, 126, 0.08);
  background: #fff;
}

.admin-topbar__actions {
  flex: 0 0 auto;
  display: flex;
  align-items: center;
  gap: 18px;
}

.admin-topbar__icon {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border: 0;
  border-radius: 50%;
  color: #737b8f;
  background: transparent;
}

.admin-topbar__icon .material-symbols-outlined {
  font-size: 1.15rem;
}

.admin-topbar__dot {
  position: absolute;
  top: 9px;
  right: 9px;
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #ff5d67;
  box-shadow: 0 0 0 2px rgba(247, 246, 243, 0.92);
}

.admin-topbar__profile {
  display: flex;
  align-items: center;
  gap: 12px;
}

.admin-topbar__profile-copy {
  text-align: right;
}

.admin-topbar__profile-copy strong {
  display: block;
  color: #1a237e;
  font-size: 0.84rem;
  font-weight: 700;
  line-height: 1.15;
}

.admin-topbar__profile-copy p {
  margin: 3px 0 0;
  color: #8d93a6;
  font-family: "Inter", sans-serif;
  font-size: 0.62rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
}

.admin-topbar__avatar {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #1d2a89 0%, #3342b6 100%);
  color: #fff;
  font-family: "Inter", sans-serif;
  font-size: 0.82rem;
  font-weight: 700;
  box-shadow: 0 10px 24px rgba(29, 42, 137, 0.24);
}

@media (max-width: 1024px) {
  .admin-topbar {
    position: sticky;
    top: 0;
    left: 0;
    gap: 12px;
    padding: 12px 16px;
  }
}

@media (max-width: 640px) {
  .admin-topbar {
    flex-direction: column;
    align-items: stretch;
    height: auto;
  }

  .admin-topbar__search {
    width: 100%;
  }

  .admin-topbar__actions {
    justify-content: space-between;
  }
}
</style>
