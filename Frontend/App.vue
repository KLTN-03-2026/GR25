<template>
  <div>
    <component :is="layout">
      <router-view />
    </component>

    <!-- 🔥 CHAT POPUP GLOBAL -->
    <ChatPopup />
<<<<<<< HEAD
    
    <!-- 🔥 CHAT FLOATING (BOT/SUPPORT) -->
    <ChatFloating />
=======
>>>>>>> qlkh-login
  </div>
</template>

<script>
import BlankLayout from "@/layout/wrapper/Blank/index.vue";
import AdminLayout from "@/layout/wrapper/Admin/index.vue";
import KhachHangLayout from "@/layout/wrapper/KhachHang/index.vue";
import MoiGioiLayout from "@/layout/wrapper/MoiGioi/index.vue";

<<<<<<< HEAD
// 🔥 IMPORT CHAT POPUP & FLOATING
import ChatPopup from "@/components/KhachHang/ChatPopup/ChatPopup.vue";
import ChatFloating from "@/components/Chat/ChatFloating.vue";
=======
// 🔥 IMPORT CHAT POPUP
import ChatPopup from "@/components/KhachHang/ChatPopup/ChatPopup.vue";
>>>>>>> qlkh-login

export default {
  components: {
    "blank-layout": BlankLayout,
    "admin-layout": AdminLayout,
    "khach-hang-layout": KhachHangLayout,
    "moi-gioi-layout": MoiGioiLayout,

    // 🔥 REGISTER
<<<<<<< HEAD
    ChatPopup,
    ChatFloating
=======
    ChatPopup
>>>>>>> qlkh-login
  },

  computed: {
    layout() {
      return (this.$route.meta.layout || "khach-hang") + "-layout";
    }
  }
};
</script>