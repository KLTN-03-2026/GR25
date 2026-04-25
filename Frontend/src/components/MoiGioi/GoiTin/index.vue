<template>
  <div class="pricing-wrapper">
    <!-- ✅ PAYMENT RESULT MODAL (Hiển thị khi có kết quả thanh toán) -->
    <div v-if="paymentResult" class="payment-result-overlay">
      <div class="payment-result-card">
        <!-- Loading -->
        <div v-if="paymentLoading" class="text-center py-4">
          <div class="spinner-border text-emerald" role="status"></div>
          <p class="mt-3 text-muted">Đang xác nhận thanh toán...</p>
          <small class="text-muted">Mã đơn: {{ orderCode }}</small>
        </div>

        <!-- Success -->
        <div v-else-if="paymentResult === 'success'" class="text-center py-4">
          <i
            class="bx bx-check-circle"
            style="font-size: 4rem; color: #10b981"
          ></i>
          <h4 class="mt-3 fw-bold text-success">Thanh toán thành công!</h4>
          <p class="text-muted">Gói tin của bạn đã được kích hoạt.</p>
          <p class="small text-muted mb-3">Mã đơn: {{ orderCode }}</p>
          <button
            class="btn btn-emerald"
            @click="
              paymentResult = null;
              router.replace({ query: {} });
            "
          >
            Đóng
          </button>
        </div>

        <!-- Error -->
        <div v-else-if="paymentResult === 'error'" class="text-center py-4">
          <i class="bx bx-x-circle" style="font-size: 4rem; color: #dc3545"></i>
          <h4 class="mt-3 fw-bold text-danger">Thanh toán thất bại</h4>
          <p class="text-muted">Vui lòng thử lại hoặc liên hệ hỗ trợ.</p>
          <p class="small text-muted mb-3">Mã đơn: {{ orderCode }}</p>
          <div class="d-flex gap-2 justify-content-center">
            <button
              class="btn btn-outline-secondary"
              @click="
                paymentResult = null;
                router.replace({ query: {} });
              "
            >
              Đóng
            </button>
            <button
              class="btn btn-outline-emerald"
              @click="
                paymentResult = null;
                router.replace({ query: {} });
                loadPlans();
              "
            >
              Thử lại
            </button>
          </div>
        </div>

        <!-- Cancel -->
        <div v-else-if="paymentResult === 'cancel'" class="text-center py-4">
          <i
            class="bx bx-minus-circle"
            style="font-size: 4rem; color: #6c757d"
          ></i>
          <h4 class="mt-3 fw-bold">Đã hủy thanh toán</h4>
          <p class="text-muted">Bạn có thể thử lại bất cứ lúc nào.</p>
          <p class="small text-muted mb-3">Mã đơn: {{ orderCode }}</p>
          <button
            class="btn btn-outline-emerald"
            @click="
              paymentResult = null;
              router.replace({ query: {} });
            "
          >
            Quay lại
          </button>
        </div>

        <!-- Pending (timeout) -->
        <div v-else-if="paymentResult === 'pending'" class="text-center py-4">
          <i class="bx bx-time" style="font-size: 4rem; color: #ffc107"></i>
          <h4 class="mt-3 fw-bold text-warning">Đang xử lý</h4>
          <p class="text-muted">
            Thanh toán của bạn đang được xác nhận. Vui lòng kiểm tra lại sau.
          </p>
          <p class="small text-muted mb-3">Mã đơn: {{ orderCode }}</p>
          <button
            class="btn btn-outline-emerald"
            @click="
              loadPlans();
              paymentResult = null;
              router.replace({ query: {} });
            "
          >
            Kiểm tra lại
          </button>
        </div>
      </div>
    </div>

    <!-- Header -->
    <div class="text-center mb-5">
      <h2 class="fw-bold mb-2 text-dark">Bảng Giá Gói Đăng Tin</h2>
      <p class="text-muted">Chọn gói phù hợp để tối ưu hiệu quả đăng tin</p>

      <!-- Billing Toggle -->
      <div class="billing-toggle mt-4">
        <span
          :class="{ active: cycle === 'monthly' }"
          @click="cycle = 'monthly'"
        >
          Thanh toán hàng tháng
        </span>
        <div
          class="toggle-switch"
          :class="{ yearly: cycle === 'yearly' }"
          @click="toggleCycle"
        >
          <div class="toggle-knob"></div>
        </div>
        <span :class="{ active: cycle === 'yearly' }" @click="cycle = 'yearly'">
          Thanh toán hàng năm
          <span class="badge-discount">Tiết kiệm 20%</span>
        </span>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-emerald" role="status"></div>
      <p class="mt-2 text-muted">Đang tải gói tin...</p>
    </div>

    <!-- Error -->
    <div
      v-else-if="error"
      class="alert alert-danger text-center shadow-sm border-0"
    >
      {{ error }}
      <button class="btn btn-sm btn-outline-danger ms-2" @click="loadPlans">
        Thử lại
      </button>
    </div>

    <!-- Pricing Grid -->
    <div v-else class="pricing-grid">
      <div
        v-for="plan in sortedPlans"
        :key="plan.id"
        class="pricing-card"
        :class="{ popular: plan.isPopular, 'border-emerald': plan.isPopular }"
      >
        <div v-if="plan.label" class="vip-badge">{{ plan.label }}</div>
        <div v-if="plan.isPopular" class="popular-badge">
          <i class="bx bx-star me-1"></i> PHỔ BIẾN NHẤT
        </div>

        <div class="card-header">
          <h3 class="plan-name" :class="{ 'text-emerald': plan.isPopular }">
            {{ plan.name }}
          </h3>
          <p class="plan-desc">{{ plan.description }}</p>
        </div>

        <div class="card-price" :class="{ 'text-emerald': plan.isPopular }">
          <span class="amount">{{ formatPrice(getPrice(plan)) }}₫</span>
          <span class="period"
            >/{{ cycle === "monthly" ? "tháng" : "năm" }}</span
          >
        </div>

        <div class="post-allowance">
          <i class="bx bx-file me-2"></i>
          <strong>{{ plan.postLimit }}</strong> tin đăng /
          {{ cycle === "monthly" ? "tháng" : "năm" }}
          <small v-if="plan.postLimit < 999" class="d-block text-muted">{{
            getRemainingText(plan)
          }}</small>
        </div>

        <!-- ✅ DÙNG ICON ĐỘNG -->
        <ul class="feature-list">
          <li v-for="(feat, i) in plan.features" :key="i">
            <i :class="getFeatureIcon(feat)" class="me-2"></i>
            <span v-html="feat"></span>
          </li>
        </ul>

        <div v-if="plan.id === currentPlanId" class="current-plan-badge">
          <i class="bx bx-check me-1"></i> Bạn đang dùng gói này
        </div>

        <button
          class="btn w-100 mt-auto"
          :class="getButtonClass(plan)"
          @click="selectPlan(plan)"
          :disabled="plan.id === currentPlanId || paymentLoading"
        >
          {{ getButtonText(plan) }}
        </button>

        <div
          v-if="plan.id === currentPlanId && canBuyExtra"
          class="extra-credits mt-3"
        >
          <small class="text-muted d-block mb-2">Cần thêm tin đăng?</small>
          <div class="d-flex gap-2">
            <button
              v-for="extra in extraCreditOptions"
              :key="extra.count"
              class="btn btn-sm btn-outline-emerald flex-grow-1"
              @click="buyExtraCredits(extra)"
              :disabled="paymentLoading"
            >
              +{{ extra.count }} tin<br />
              <small>{{ formatPrice(extra.price) }}₫</small>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section mt-5 pt-4 border-top">
      <h5 class="mb-4 text-center fw-bold text-dark">❓ Câu hỏi thường gặp</h5>

      <div class="faq-list">
        <div
          v-for="(faq, index) in faqs"
          :key="index"
          class="faq-item"
          :class="{ active: activeIndex === index }"
          @click="toggleFaq(index)"
        >
          <div class="faq-header">
            <span class="faq-question-text">{{ faq.q }}</span>
            <i
              class="faq-icon"
              :class="
                activeIndex === index ? 'bx bx-up-arrow' : 'bx bx-down-arrow'
              "
            ></i>
          </div>

          <!-- Phần nội dung trả lời -->
          <div
            class="faq-body"
            :style="{ maxHeight: activeIndex === index ? '500px' : '0' }"
          >
            <div class="faq-content">
              {{ faq.a }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Payment Confirmation Modal -->
  <!-- Payment Confirmation Modal -->
  <div v-if="showModal && !paymentResult" class="payment-modal">
    <div class="modal-content">
      <h3>Thanh toán gói</h3>
      <p>
        <strong>{{ selectedPlan?.name }}</strong>
      </p>
      <p>
        Giá: {{ formatPrice(getPrice(selectedPlan)) }}đ /
        {{ cycle === "monthly" ? "tháng" : "năm" }}
      </p>

      <!-- ✅ Danh sách features với icon -->
      <ul class="modal-features">
        <li
          v-for="(f, i) in selectedPlan?.features"
          :key="i"
          v-html="formatFeatureWithIcon(f)"
        ></li>
      </ul>

      <div class="modal-actions">
        <button
          class="btn btn-secondary"
          @click="showModal = false"
          :disabled="paymentLoading"
        >
          Hủy
        </button>
        <button
          class="btn btn-emerald"
          @click="confirmPayment"
          :disabled="paymentLoading"
        >
          <span
            v-if="paymentLoading"
            class="spinner-border spinner-border-sm me-2"
          ></span>
          {{ paymentLoading ? "Đang xử lý..." : "Thanh toán ngay" }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";

const router = useRouter();
const route = useRoute(); // ✅ THÊM: Để đọc query params từ URL

const cycle = ref("monthly");
const plans = ref([]);
const loading = ref(true);
const error = ref(null);
const currentPlanId = ref(null);
const userPostUsage = ref({ used: 0, limit: 0 });
const selectedId = ref(null);
const selectedPlan = ref(null);
const showModal = ref(false);
const activeIndex = ref(null);

// ✅ THÊM: Trạng thái payment result
const paymentResult = ref(null); // 'success' | 'error' | 'cancel' | null
const paymentLoading = ref(false);
const orderCode = ref(null);

// Extra credit options
const extraCreditOptions = [
  { count: 5, price: 50000 },
  { count: 10, price: 90000 },
  { count: 20, price: 160000 },
];

// ✅ Method để xác định icon dựa trên nội dung feature
const getFeatureIcon = (featureText) => {
  const text = featureText.toLowerCase();

  // Số lượng tin đăng
  if (
    text.includes("tin đăng") ||
    text.includes("bài đăng") ||
    text.includes("tin")
  ) {
    return "bx bx-file text-emerald";
  }

  // Thời hạn/hiệu lực
  if (
    text.includes("hiệu lực") ||
    text.includes("ngày") ||
    text.includes("tháng") ||
    text.includes("thời hạn")
  ) {
    return "bx bx-calendar text-emerald";
  }

  // Hiển thị/ưu tiên/VIP
  if (
    text.includes("hiển thị") ||
    text.includes("ưu tiên") ||
    text.includes("vip") ||
    text.includes("nổi bật")
  ) {
    return "bx bx-star text-emerald";
  }

  // Hỗ trợ
  if (text.includes("hỗ trợ") || text.includes("support")) {
    return "bx bx-support text-emerald";
  }

  // Email/Zalo/lien lạc
  if (
    text.includes("email") ||
    text.includes("zalo") ||
    text.includes("liên hệ")
  ) {
    return "bx bx-chat text-emerald";
  }

  // Ảnh/hình ảnh
  if (text.includes("ảnh") || text.includes("hình") || text.includes("media")) {
    return "bx bx-image text-emerald";
  }

  // Thống kê/báo cáo
  if (
    text.includes("thống kê") ||
    text.includes("báo cáo") ||
    text.includes("analytics")
  ) {
    return "bx bx-bar-chart text-emerald";
  }

  // API/integration
  if (text.includes("api") || text.includes("integration")) {
    return "bx bx-code text-emerald";
  }

  // Quản lý tài khoản
  if (
    text.includes("tài khoản") ||
    text.includes("user") ||
    text.includes("quản lý")
  ) {
    return "bx bx-user text-emerald";
  }

  // Badge/nhãn
  if (text.includes("nhãn") || text.includes("badge") || text.includes("gắn")) {
    return "bx bx-badge text-emerald";
  }

  // Tìm kiếm
  if (text.includes("tìm kiếm") || text.includes("search")) {
    return "bx bx-search text-emerald";
  }

  // Mặc định
  return "bx bx-check-circle text-emerald";
};

// ✅ Method để format features với icon (dùng cho danh sách trong modal)
const formatFeatureWithIcon = (featureText) => {
  const icon = getFeatureIcon(featureText);
  return `<i class="${icon} me-2"></i><span>${featureText}</span>`;
};
// FAQs
const faqs = [
  {
    q: "Tin đăng có thời hạn bao lâu?",
    a: "Mỗi tin đăng có hiệu lực 15 ngày kể từ khi đăng. Sau đó tin sẽ tự động ẩn và bạn có thể đăng lại.",
  },
  {
    q: "Nếu dùng hết số tin trong gói thì sao?",
    a: "Bạn có thể mua thêm credit tin đăng bất cứ lúc nào, hoặc nâng cấp lên gói cao hơn. Credit mua thêm không có thời hạn.",
  },
  {
    q: "Có được hoàn tiền nếu hủy gói?",
    a: "Với gói tháng: không hoàn tiền cho phần đã sử dụng. Với gói năm: hoàn proportional cho số tháng còn lại.",
  },
  {
    q: "Credit mua thêm có hết hạn không?",
    a: "Credit mua thêm KHÔNG có thời hạn, bạn có thể dùng dần khi cần.",
  },
];

const toggleFaq = (index) => {
  // Nếu click vào câu đang mở thì đóng lại (null), nếu click câu khác thì mở câu đó
  activeIndex.value = activeIndex.value === index ? null : index;
};

const API = "http://127.0.0.1:8000/api";

// ===== COMPUTED =====
const sortedPlans = computed(() => {
  return [...plans.value].sort((a, b) => {
    if (a.isPopular && !b.isPopular) return -1;
    if (!a.isPopular && b.isPopular) return 1;
    return a.postLimit - b.postLimit;
  });
});

const canBuyExtra = computed(() => {
  return userPostUsage.value.used >= userPostUsage.value.limit * 0.8;
});

// ===== METHODS =====
const toggleCycle = () => {
  cycle.value = cycle.value === "monthly" ? "yearly" : "monthly";
};

const getPrice = (plan) => {
  return cycle.value === "monthly" ? plan.monthlyPrice : plan.yearlyPrice;
};

const formatPrice = (val) => {
  return new Intl.NumberFormat("vi-VN").format(val);
};

const getRemainingText = (plan) => {
  if (plan.id !== currentPlanId.value) return "";
  const remaining = plan.postLimit - userPostUsage.value.used;
  return `Còn ${remaining} tin chưa dùng trong kỳ này`;
};

const getButtonClass = (plan) => {
  if (plan.id === currentPlanId.value) return "btn-secondary";
  if (plan.isPopular) return "btn-emerald";
  return "btn-outline-emerald";
};

const getButtonText = (plan) => {
  if (plan.id === currentPlanId.value) return "Đang sử dụng";
  return plan.btnText;
};

// ✅ THÊM: Load plans với refresh token
const loadPlans = async () => {
  try {
    loading.value = true;
    error.value = null;

    const token = localStorage.getItem("auth_token");
    const { data } = await axios.get(
      "http://127.0.0.1:8000/api/moi-gioi/goi-tin/data",
      {
        headers: {
          Authorization: token ? `Bearer ${token}` : "",
        },
      }
    );

    if (data?.status && data?.data) {
      plans.value = data.data;
      if (data.current_plan) {
        currentPlanId.value = data.current_plan.id;
        userPostUsage.value = {
          used: data.current_plan.used_posts,
          limit: data.current_plan.postLimit,
        };
      }
    } else {
      throw new Error(data?.message || "Lỗi tải dữ liệu");
    }
  } catch (err) {
    console.error("Load plans error:", err);
    error.value = "Không thể tải bảng giá. Vui lòng thử lại sau.";

    if (import.meta.env?.DEV) {
      plans.value = [
        {
          id: 1,
          name: "Cơ Bản",
          description: "Phù hợp môi giới mới bắt đầu",
          monthly_price: 99000,
          yearly_price: 950000,
          post_limit: 10,
          features: [
            "Đăng tin cơ bản",
            "Hỗ trợ 3 ảnh/tin",
            "Thống kê đơn giản",
            "Hỗ trợ qua email",
          ],
          is_popular: false,
        },
        {
          id: 2,
          name: "Chuyên Nghiệp",
          description: "Tối ưu cho broker hoạt động thường xuyên",
          monthly_price: 299000,
          yearly_price: 2870000,
          post_limit: 50,
          features: [
            "Đăng tin không giới hạn ảnh",
            "Gắn nhãn VIP cho tin",
            "Thống kê chi tiết",
            "Ưu tiên hiển thị tìm kiếm",
            "Hỗ trợ priority 24/7",
          ],
          is_popular: true,
        },
        {
          id: 3,
          name: "Doanh Nghiệp",
          description: "Cho team/công ty bất động sản",
          monthly_price: 799000,
          yearly_price: 7670000,
          post_limit: 200,
          features: [
            "Tất cả tính năng Chuyên Nghiệp",
            "Quản lý nhiều tài khoản con",
            "API integration",
            "Báo cáo tùy chỉnh",
            "Account manager riêng",
          ],
          is_popular: false,
        },
      ];
      error.value = null;
    }
  } finally {
    loading.value = false;
  }
};

// ✅ THÊM: Check order status via API
const checkOrderStatus = async (code) => {
  if (!code) return null;

  try {
    const token = localStorage.getItem("auth_token");
    const { data } = await axios.get(`${API}/moi-gioi/giao-dich/${code}`, {
      headers: {
        Authorization: token ? `Bearer ${token}` : "",
      },
    });
    return data?.data?.trang_thai; // 'pending' | 'success' | 'failed' | 'cancelled'
  } catch (err) {
    console.error("Check order error:", err);
    return null;
  }
};

// ✅ THÊM: Polling check order status
const pollOrderStatus = async (code, maxAttempts = 15) => {
  paymentLoading.value = true;
  let attempts = 0;

  const interval = setInterval(async () => {
    attempts++;

    const status = await checkOrderStatus(code);

    if (status === "success") {
      clearInterval(interval);
      paymentResult.value = "success";
      paymentLoading.value = false;

      // Refresh user data
      await loadPlans();

      // Show success notification
      alert("🎉 Thanh toán thành công! Gói tin của bạn đã được kích hoạt.");

      // Clear query params
      router.replace({ query: {} });
    } else if (status === "failed" || status === "cancelled") {
      clearInterval(interval);
      paymentResult.value = status;
      paymentLoading.value = false;
      alert(
        status === "failed"
          ? "❌ Thanh toán thất bại. Vui lòng thử lại."
          : "⏹️ Bạn đã hủy thanh toán."
      );
    } else if (attempts >= maxAttempts) {
      // Timeout
      clearInterval(interval);
      paymentLoading.value = false;
      paymentResult.value = "pending";
      alert("⏳ Thanh toán đang được xử lý. Vui lòng kiểm tra lại sau.");
    }
  }, 2000); // Check every 2 seconds

  return () => clearInterval(interval); // Return cleanup function
};

// ✅ THÊM: Handle payment result from URL params
const handlePaymentRedirect = async () => {
  const status = route.query.status; // success | error | cancel
  const code = route.query.order_code;

  if (!status && !code) return; // No payment redirect

  orderCode.value = code;

  if (status === "success") {
    // Start polling to confirm webhook has updated DB
    await pollOrderStatus(code);
  } else if (status === "error") {
    paymentResult.value = "error";
    const message = route.query.message || "Thanh toán thất bại";
    alert(`❌ ${message}`);
  } else if (status === "cancel") {
    paymentResult.value = "cancel";
    alert("⏹️ Bạn đã hủy thanh toán.");
  }
};

const selectPlan = (plan) => {
  selectedPlan.value = plan;
  selectedId.value = plan.id;
  showModal.value = true;
};

const buyExtraCredits = async (extra) => {
  if (
    !confirm(
      `Mua thêm ${extra.count} tin đăng với giá ${formatPrice(extra.price)}₫?`
    )
  ) {
    return;
  }

  try {
    const token = localStorage.getItem("auth_token");
    const { data } = await axios.post(
      "http://127.0.0.1:8000/api/moi-gioi/credit/mua",
      { count: extra.count, price: extra.price },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      }
    );

    if (data?.status) {
      alert(`✅ Đã mua thành công ${extra.count} tin đăng!`);
      loadPlans();
    }
  } catch (err) {
    console.error("Buy credits error:", err);
    alert("❌ Lỗi khi mua credit. Vui lòng thử lại.");
  }
};

const confirmPayment = async () => {
  const token = localStorage.getItem("auth_token");

  if (!selectedId.value) {
    alert("Vui lòng chọn gói");
    return;
  }

  try {
    paymentLoading.value = true;

    const res = await axios.post(
      `${API}/moi-gioi/payment/create`,
      { goi_tin_id: selectedId.value },
      { headers: { Authorization: `Bearer ${token}` } }
    );

    if (res.data?.status && res.data?.payment_form) {
      // Submit SePay form
      const div = document.createElement("div");
      div.innerHTML = res.data.payment_form;
      const form = div.querySelector("form");

      if (form) {
        document.body.appendChild(div);
        form.submit();
        document.body.removeChild(div);
      }
    } else {
      alert("❌ Không tạo được link thanh toán");
    }
  } catch (err) {
    console.error("Payment error:", err);
    alert("❌ Lỗi thanh toán: " + (err.response?.data?.message || err.message));
  } finally {
    paymentLoading.value = false;
  }
};

// ✅ THÊM: Listen for WebSocket payment success event
const setupWebSocket = () => {
  const user = JSON.parse(localStorage.getItem("user"));
  if (!user?.id) return;

  if (window.Echo) {
    window.Echo.channel(`payment.${user.id}`).listen(
      "ThanhToanThanhCong",
      (data) => {
        console.log("🔔 WebSocket payment success:", data);

        if (orderCode.value) {
          paymentResult.value = "success";
          paymentLoading.value = false;
          alert("🎉 Thanh toán thành công!");
          loadPlans();
          router.replace({ query: {} });
        }
      }
    );
  }
};

// ✅ ON MOUNTED
onMounted(async () => {
  await loadPlans();
  setupWebSocket();

  // Check if user returned from SePay payment
  await handlePaymentRedirect();
});

// ✅ WATCH route query changes (in case user manually refreshes)
watch(
  () => route.query,
  async (newQuery) => {
    if (newQuery.status || newQuery.order_code) {
      await handlePaymentRedirect();
    }
  },
  { deep: true }
);
</script>
<style scoped>
.pricing-wrapper {
  --emerald-50: #ecfdf5;
  --emerald-100: #d1fae5;
  --emerald-500: #10b981;
  --emerald-600: #059669;
  --emerald-700: #047857;
  background: radial-gradient(circle at top, #ecfdf5, #ffffff);
  border-radius: 20px;
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
  font-family: "Inter", system-ui, sans-serif;
}

.text-emerald {
  color: var(--emerald-500) !important;
}
.border-emerald {
  border-color: var(--emerald-500) !important;
}

/* Toggle */
.billing-toggle {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  background: #f8f9fa;
  padding: 6px;
  border-radius: 50px;
  font-weight: 500;
  color: #6c757d;
  font-size: 0.9rem;
}
.billing-toggle span {
  cursor: pointer;
  padding: 8px 16px;
  border-radius: 40px;
  transition: 0.2s;
}
.billing-toggle span.active {
  background: white;
  color: var(--emerald-600);
  font-weight: 600;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.toggle-switch {
  width: 44px;
  height: 24px;
  background: #dee2e6;
  border-radius: 50px;
  position: relative;
  cursor: pointer;
  transition: 0.3s;
}
.toggle-switch.yearly {
  background: var(--emerald-500);
}
.toggle-knob {
  width: 18px;
  height: 18px;
  background: white;
  border-radius: 50%;
  position: absolute;
  top: 3px;
  left: 3px;
  transition: 0.3s;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}
.toggle-switch.yearly .toggle-knob {
  transform: translateX(20px);
}
.badge-discount {
  background: #fef08a;
  color: #854d0e;
  font-size: 0.7rem;
  padding: 2px 8px;
  border-radius: 10px;
  margin-left: 6px;
  font-weight: 600;
}

/* Grid */
.pricing-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  align-items: start;
}

/* Cards */
.pricing-card {
  background: linear-gradient(145deg, #ffffff, #f8fafc);
  border-radius: 20px;
  padding: 2rem 1.5rem;
  border: 1px solid #e2e8f0;
  position: relative;
  display: flex;
  flex-direction: column;
  min-height: 520px;

  /* 🔥 premium shadow */
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08),
    inset 0 1px 0 rgba(255, 255, 255, 0.6);

  transition: all 0.35s ease;
}

.pricing-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12),
    0 0 0 1px rgba(16, 185, 129, 0.15);
}

.pricing-card.popular {
  border: 2px solid #10b981;
  transform: scale(1.06);
  background: linear-gradient(180deg, #ecfdf5, #ffffff);

  box-shadow: 0 20px 50px rgba(16, 185, 129, 0.25);
}

.popular-badge {
  position: absolute;
  top: 12px;
  right: -35px;
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 4px 40px;
  transform: rotate(45deg);
  letter-spacing: 0.5px;
}

.card-header {
  text-align: center;
  margin-bottom: 1.5rem;
}
.plan-name {
  font-weight: 800;
  font-size: 1.4rem;
  margin: 0 0 0.5rem;
}
.plan-desc {
  color: #64748b;
  font-size: 0.9rem;
  margin: 0;
  min-height: 40px;
}

.card-price {
  text-align: center;
  margin-bottom: 1rem;
}
.currency {
  font-size: 1rem;
  vertical-align: top;
  font-weight: 600;
  margin-right: 2px;
}
.amount {
  font-size: 2.4rem;
  font-weight: 800;
  background: linear-gradient(45deg, #10b981, #059669);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.period {
  color: #64748b;
  font-size: 0.9rem;
  font-weight: 500;
}

.post-allowance {
  background: var(--emerald-50);
  border-radius: 12px;
  padding: 12px 16px;
  margin-bottom: 1.5rem;
  text-align: center;
  font-size: 0.95rem;
  color: #065f46;
}
.post-allowance small {
  font-size: 0.8rem;
  margin-top: 4px;
}

.feature-list {
  list-style: none;
  padding: 0;
  margin: 0 0 1.5rem;
  flex-grow: 1;
}
.feature-list li {
  transition: 0.2s;
  margin-bottom: 12px;
  font-size: 0.9rem;
  display: flex;
  align-items: flex-start;
  color: #334155;
  line-height: 1.4;
}
.feature-list li:hover {
  transform: translateX(5px);
  color: #10b981;
}
.feature-list li i {
  margin-top: 3px;
  flex-shrink: 0;
}

.current-plan-badge {
  background: var(--emerald-100);
  color: var(--emerald-700);
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 500;
  text-align: center;
  margin-bottom: 12px;
}

/* Buttons */
.btn-emerald {
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
  border: none;
  font-weight: 600;
  padding: 12px;
  border-radius: 12px;
  transition: all 0.3s;
}

.btn-emerald:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
}

.btn-outline-emerald {
  background: transparent;
  color: var(--emerald-600);
  border: 2px solid var(--emerald-500);
  font-weight: 600;
  padding: 10px;
  border-radius: 12px;
  transition: all 0.2s;
}
.btn-outline-emerald:hover:not(:disabled) {
  background: var(--emerald-50);
  color: var(--emerald-700);
}
.btn-secondary {
  background: #e2e8f0;
  color: #64748b;
  border: none;
  font-weight: 600;
  padding: 12px;
  border-radius: 12px;
  cursor: not-allowed;
}

/* Extra Credits */
.extra-credits {
  border-top: 1px dashed #e2e8f0;
  padding-top: 1rem;
}
.extra-credits .btn-outline-emerald {
  padding: 8px 4px;
  font-size: 0.8rem;
}
.extra-credits .btn-outline-emerald small {
  font-size: 0.75rem;
  opacity: 0.9;
}

/* FAQ */
.faq-section {
  max-width: 800px;
  margin: 0 auto;
}
.accordion-button {
  font-weight: 500;
  font-size: 0.95rem;
  padding: 1rem 1.25rem;
}
.accordion-button:not(.collapsed) {
  background: var(--emerald-50);
  color: var(--emerald-700);
  box-shadow: none;
}
.accordion-button:focus {
  box-shadow: none;
  border-color: var(--emerald-200);
}

/* Responsive */
@media (max-width: 768px) {
  .pricing-card.popular {
    transform: none;
  }
  .pricing-card.popular:hover {
    transform: translateY(-4px);
  }
  .amount {
    font-size: 1.8rem;
  }
  .popular-badge {
    right: -25px;
    padding: 4px 25px;
    font-size: 0.65rem;
  }
}

.vip-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: linear-gradient(45deg, gold, orange);
  color: #000;
  font-size: 11px;
  padding: 4px 10px;
  border-radius: 8px;
  font-weight: bold;
  z-index: 10;

  /* 🔥 glow */
  box-shadow: 0 0 10px rgba(255, 200, 0, 0.6);

  animation: vipPulse 1.5s infinite;
}

@keyframes vipPulse {
  0% {
    box-shadow: 0 0 5px rgba(255, 200, 0, 0.5);
  }
  50% {
    box-shadow: 0 0 20px rgba(255, 200, 0, 1);
  }
  100% {
    box-shadow: 0 0 5px rgba(255, 200, 0, 0.5);
  }
}

.payment-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);

  display: flex;
  justify-content: center;
  align-items: center;

  z-index: 999;
}

.modal-content {
  background: white;
  padding: 25px;
  border-radius: 15px;
  width: 350px;
  text-align: center;
}

.modal-actions {
  display: flex;
  gap: 10px;
  margin-top: 20px;
}
/* Fix Bootstrap Accordion Styling */
.accordion-item {
  border: 1px solid #e2e8f0 !important;
  border-radius: 8px !important;
  margin-bottom: 8px !important;
  overflow: hidden;
}

.accordion-button {
  background: #ecfdf5 !important; /* Light emerald */
  color: #065f46 !important;
  font-weight: 600 !important;
  padding: 1rem 1.25rem !important;
  border: none !important;
  box-shadow: none !important;
}

.accordion-button:not(.collapsed) {
  background: #d1fae5 !important; /* Darker emerald when open */
  color: #047857 !important;
}

.accordion-button:focus {
  box-shadow: none !important;
  border-color: #10b981 !important;
}

/* ===== CUSTOM FAQ STYLING (THAY CHO BOOTSTRAP) ===== */
.faq-list {
  max-width: 800px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.faq-item {
  border: 1px solid #e2e8f0; /* Viền xám nhạt */
  border-radius: 12px;
  overflow: hidden;
  background: #ffffff;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.faq-item:hover {
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
  border-color: #10b981;
}

.faq-item.active {
  border-color: #10b981; /* Viền xanh khi mở */
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.faq-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  background-color: #f8fafc; /* Nền xám rất nhạt */
  transition: background 0.3s;
}

.faq-item.active .faq-header {
  background-color: #ecfdf5; /* Nền xanh nhạt khi mở */
}

.faq-question-text {
  font-weight: 600;
  font-size: 1rem;
  color: #1a202c !important; /* ✅ CHẮC CHẮN MÀU ĐEN ĐẬM */
}

.faq-icon {
  font-size: 1.2rem;
  color: #10b981;
  transition: transform 0.3s ease;
}

.faq-body {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease-out;
}

.faq-content {
  padding: 1rem 1.5rem;
  color: #4a5568 !important; /* ✅ CHỮ XÁM ĐEN DỄ ĐỌC */
  line-height: 1.6;
  background-color: #ffffff;
  border-top: 1px solid #f1f5f9;
}

/* ===== FIX MÀU CHỮ & GIAO DIỆN FAQ (GIỮ NGUYÊN HIỆU ỨNG BOOTSTRAP) ===== */
.accordion-item {
  border: 1px solid #d1fae5 !important;
  border-radius: 12px !important;
  margin-bottom: 12px !important;
  overflow: hidden;
  background: #ffffff !important;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
}

/* Nền & Chữ khi ĐÓNG */
.accordion-button {
  background-color: #ecfdf5 !important; /* Xanh nhạt */
  color: #111827 !important; /* ✅ ĐEN ĐẬM RÕ RÀNG */
  font-weight: 600 !important;
  font-size: 0.95rem !important;
  padding: 1rem 1.25rem !important;
  box-shadow: none !important; /* Bỏ viền xanh focus mặc định của Bootstrap */
}

/* Nền & Chữ khi MỞ */
.accordion-button:not(.collapsed) {
  background-color: #d1fae5 !important; /* Xanh đậm hơn chút */
  color: #065f46 !important; /* ✅ XANH ĐÁM DỄ ĐỌC */
  box-shadow: none !important;
}

/* Sửa màu mũi tên ↕️ */
.accordion-button::after {
  filter: brightness(0) saturate(100%) invert(25%) sepia(85%) saturate(400%)
    hue-rotate(120deg) !important;
}

/* Phần nội dung trả lời */
.accordion-body {
  background-color: #ffffff !important;
  color: #374151 !important; /* ✅ XÁM ĐEN CHUẨN */
  padding: 1rem 1.25rem !important;
  line-height: 1.6 !important;
  font-size: 0.9rem !important;
  border-top: 1px solid #e2e8f0 !important;
}

/* Bỏ hiệu ứng hover mặc định gây chói */
.accordion-button:hover {
  background-color: #d1fae5 !important;
}

/* Modal Features List */
.modal-features {
  list-style: none;
  padding: 0;
  margin: 1rem 0;
  text-align: left;
  background: #f8fafc;
  border-radius: 12px;
  padding: 1rem;
}

.modal-features li {
  padding: 8px 0;
  color: #374151;
  font-size: 0.9rem;
  display: flex;
  align-items: flex-start;
  border-bottom: 1px solid #e2e8f0;
}

.modal-features li:last-child {
  border-bottom: none;
}

.modal-features li i {
  margin-top: 2px;
  flex-shrink: 0;
}

.modal-features li span {
  flex-grow: 1;
}
</style>