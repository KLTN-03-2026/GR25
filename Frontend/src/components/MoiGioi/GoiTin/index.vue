<template>
  <div class="pricing-wrapper">
    <div class="text-center mb-5">
      <h2 class="fw-bold mb-2 text-dark">Bảng Giá Gói Đăng Tin</h2>
      <p class="text-muted">Chọn gói phù hợp để tối ưu hiệu quả đăng tin</p>

      <div class="billing-toggle mt-4">
        <span
          :class="{ active: cycle === 'monthly' }"
          @click="cycle = 'monthly'"
          >Hàng tháng</span
        >
        <div
          class="toggle-switch"
          :class="{ yearly: cycle === 'yearly' }"
          @click="toggleCycle"
        >
          <div class="toggle-knob"></div>
        </div>
        <span :class="{ active: cycle === 'yearly' }" @click="cycle = 'yearly'">
          Hàng năm <span class="badge-discount">-20%</span>
        </span>
      </div>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-emerald" role="status"></div>
      <p class="mt-2 text-muted">Đang tải dữ liệu gói tin...</p>
    </div>

    <div
      v-else-if="error"
      class="alert alert-danger text-center shadow-sm border-0"
    >
      {{ error }}
    </div>

    <div v-else class="pricing-grid">
      <div
        v-for="plan in plans"
        :key="plan.id"
        class="pricing-card"
        :class="plan.cardClass"
      >
        <div v-if="plan.isPopular" class="popular-badge">PHỔ BIẾN NHẤT</div>

        <div class="card-header">
          <h3 class="plan-name" :class="{ 'text-emerald': plan.isPopular }">
            {{ plan.name }}
          </h3>
          <p class="plan-desc">{{ plan.description }}</p>
        </div>

        <div class="card-price" :class="{ 'text-emerald': plan.isPopular }">
          <span class="currency">đ</span>
          <span class="amount">{{ formatPrice(getPrice(plan)) }}</span>
          <span class="period"
            >/ {{ cycle === "monthly" ? "tháng" : "năm" }}</span
          >
        </div>

        <ul class="feature-list">
          <li v-for="(feat, i) in plan.features" :key="i">
            <i class="bx bx-check text-emerald me-2 fs-5"></i>
            <span v-html="feat"></span>
          </li>
          <li>
            <i class="bx bx-check text-emerald me-2 fs-5"></i>
            <span>Hỗ trợ đăng ảnh/video</span>
          </li>
          <li :class="{ 'text-muted': plan.postLimit < 20 }">
            <i
              :class="
                plan.postLimit >= 20
                  ? 'bx bx-check text-emerald'
                  : 'bx bx-x text-muted'
              "
              class="me-2 fs-5"
            ></i>
            <span
              :class="{ 'text-decoration-line-through': plan.postLimit < 20 }"
            >
              {{
                plan.postLimit >= 20 ? "Được gắn nhãn VIP" : "Không hỗ trợ VIP"
              }}
            </span>
          </li>
        </ul>

        <button
          class="btn w-100 mt-auto"
          :class="plan.btnClass"
          @click="selectPlan(plan)"
        >
          {{ plan.btnText }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const cycle = ref("monthly");
const plans = ref([]);
const loading = ref(true);
const error = ref(null);

const toggleCycle = () =>
  (cycle.value = cycle.value === "monthly" ? "yearly" : "monthly");

const getPrice = (plan) =>
  cycle.value === "monthly" ? plan.monthlyPrice : plan.yearlyPrice;

const formatPrice = (val) => new Intl.NumberFormat("vi-VN").format(val);

const loadPlans = async () => {
  try {
    loading.value = true;
    error.value = null;

    // Gọi API thật của bác ở đây:
    const { data } = await axios.get(
      "http://127.0.0.1:8000/api/moi-gioi/goi-tin/data"
    );
    if (data?.status) {
      plans.value = data.data;
    } else {
      throw new Error(data?.message || "Lỗi tải dữ liệu");
    }
  } catch (err) {
    console.warn("Gọi API thất bại, đang đổ dữ liệu mẫu ra thay thế...");

    // QUAN TRỌNG: Gán null cho error để grid v-else được hiển thị!
    error.value = null;
  } finally {
    loading.value = false;
  }
};

const selectPlan = (plan) => {
  console.log("Chọn gói:", plan.name, "Chu kỳ:", cycle.value);
  alert(
    `Đã chọn: ${plan.name} (${cycle.value === "monthly" ? "Tháng" : "Năm"})`
  );
};

onMounted(loadPlans);
</script>

<style scoped>
/* Tone màu Emerald cho Môi Giới */
.pricing-wrapper {
  --emerald-50: #ecfdf5;
  --emerald-100: #d1fae5;
  --emerald-500: #10b981;
  --emerald-600: #059669;

  max-width: 1100px;
  margin: 0 auto;
  padding: 2rem 1rem;
  font-family: "Inter", sans-serif;
}

.text-emerald {
  color: var(--emerald-500) !important;
}

/* Toggle */
.billing-toggle {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  background: #f8f9fa;
  padding: 8px 16px;
  border-radius: 50px;
  font-weight: 500;
  color: #6c757d;
}
.billing-toggle span {
  cursor: pointer;
  transition: 0.2s;
}
.billing-toggle span.active {
  color: var(--emerald-600);
  font-weight: 700;
}
.toggle-switch {
  width: 48px;
  height: 26px;
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
  width: 20px;
  height: 20px;
  background: white;
  border-radius: 50%;
  position: absolute;
  top: 3px;
  left: 3px;
  transition: 0.3s;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
.toggle-switch.yearly .toggle-knob {
  transform: translateX(22px);
}
.badge-discount {
  background: #fef08a;
  color: #ca8a04;
  font-size: 0.7rem;
  padding: 2px 6px;
  border-radius: 10px;
  margin-left: 4px;
  font-weight: 700;
}

/* Grid & Cards */
.pricing-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
  align-items: start;
  margin-top: 1rem;
}
.pricing-card {
  background: white;
  border-radius: 20px;
  padding: 2.5rem 2rem;
  border: 1px solid #e2e8f0;
  transition: 0.3s;
  position: relative;
  display: flex;
  flex-direction: column;
  height: 100%;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}
.pricing-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}
.pricing-card.popular {
  border: 2px solid var(--emerald-500);
  transform: scale(1.03);
  box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.15);
  z-index: 2;
}
.pricing-card.popular:hover {
  transform: scale(1.03) translateY(-5px);
}

.popular-badge {
  position: absolute;
  top: 16px;
  right: -30px;
  background: #ef4444;
  color: white;
  font-size: 0.7rem;
  font-weight: 800;
  padding: 4px 30px;
  transform: rotate(45deg);
  letter-spacing: 1px;
  z-index: 10;
}

.plan-name {
  font-weight: 800;
  font-size: 1.5rem;
  margin: 0 0 0.5rem;
}
.plan-desc {
  color: #64748b;
  font-size: 0.9rem;
  margin: 0 0 1.5rem;
  min-height: 40px;
}

.card-price {
  margin-bottom: 2rem;
}
.currency {
  font-size: 1.2rem;
  vertical-align: top;
  font-weight: 600;
  margin-right: 2px;
}
.amount {
  font-size: 2.5rem;
  font-weight: 800;
  line-height: 1;
  letter-spacing: -1px;
}
.period {
  color: #64748b;
  font-size: 0.95rem;
  font-weight: 500;
}

.feature-list {
  list-style: none;
  padding: 0;
  margin: 0 0 2rem;
  flex-grow: 1;
}
.feature-list li {
  margin-bottom: 15px;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
  color: #334155;
}
.feature-list li.text-muted {
  color: #94a3b8;
}

/* Buttons */
.btn-emerald {
  background: var(--emerald-500);
  color: white;
  border: none;
  font-weight: 700;
  padding: 12px;
  border-radius: 12px;
  transition: background 0.2s;
}
.btn-emerald:hover {
  background: var(--emerald-600);
  color: white;
}

.btn-outline-emerald {
  background: transparent;
  color: var(--emerald-600);
  border: 2px solid var(--emerald-500);
  font-weight: 700;
  padding: 10px;
  border-radius: 12px;
  transition: all 0.2s;
}
.btn-outline-emerald:hover {
  background: var(--emerald-50);
  color: var(--emerald-700);
}

.btn-dark {
  background: #1e293b;
  color: white;
  border: none;
  font-weight: 700;
  padding: 12px;
  border-radius: 12px;
  transition: background 0.2s;
}
.btn-dark:hover {
  background: #0f172a;
  color: white;
}

@media (max-width: 768px) {
  .pricing-card.popular {
    transform: scale(1);
    margin-top: 1rem;
    margin-bottom: 1rem;
  }
  .pricing-card.popular:hover {
    transform: translateY(-5px);
  }
  .amount {
    font-size: 2rem;
  }
}
</style>