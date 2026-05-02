<template>
  <component :is="layout">
    <router-view />
  </component>
</template>

<script>
import BlankLayout from "@/layout/wrapper/Blank/index.vue";
import AdminLayout from "@/layout/wrapper/Admin/index.vue";
import KhachHangLayout from "@/layout/wrapper/KhachHang/index.vue";
import MoiGioiLayout from "@/layout/wrapper/MoiGioi/index.vue";


export default {
  components: {
    "blank-layout": BlankLayout,
    "admin-layout": AdminLayout,
    "khach-hang-layout": KhachHangLayout,
    "moi-gioi-layout" : MoiGioiLayout,
  },

  computed: {
    layout() {
      return (this.$route.meta.layout || "khach-hang") + "-layout";
    },
  },
};
</script>