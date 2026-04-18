<template>
  <div class="page-content">
    <div class="broker-chat-container">
      
      <aside class="chat-sidebar">
        <div class="chat-sidebar__header">
          <div class="d-flex align-items-center mb-3">
            <h5 class="mb-0 fw-bold text-dark">Tin nhắn</h5>
            <span class="badge bg-emerald ms-2">{{ contacts.length }}</span>
            <div class="ms-auto dropdown">
              <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                <li><a class="dropdown-item" href="#">Đánh dấu tất cả đã đọc</a></li>
                <li><a class="dropdown-item" href="#">Cài đặt tin nhắn tự động</a></li>
              </ul>
            </div>
          </div>

          <div class="search-box">
            <i class="bx bx-search search-icon"></i>
            <input type="text" class="form-control" placeholder="Tìm kiếm khách hàng..." v-model="searchQuery" />
          </div>

          <div class="chat-filters mt-3">
            <button class="filter-btn active">Tất cả</button>
            <button class="filter-btn">Chưa đọc</button>
            <button class="filter-btn">Đang quan tâm</button>
          </div>
        </div>

        <div class="chat-sidebar__list">
          <div v-if="filteredContacts.length === 0" class="empty-state">
            <i class="bx bx-search-alt"></i>
            <p>Không tìm thấy khách hàng</p>
          </div>
          
          <a href="javascript:;" 
             v-for="contact in filteredContacts" 
             :key="contact.id" 
             class="chat-contact-item" 
             :class="{ 'is-active': activeChatId === contact.id }"
             @click="selectChat(contact.id)">
            
            <div class="contact-avatar">
              <img :src="contact.avatar || `https://ui-avatars.com/api/?name=${contact.name}&background=random&color=fff`" :alt="contact.name" />
              <span class="status-dot" :class="contact.online ? 'online' : 'offline'"></span>
            </div>
            
            <div class="contact-info">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <h6 class="contact-name mb-0">{{ contact.name }}</h6>
                <span class="contact-time">{{ contact.time }}</span>
              </div>
              <p class="contact-last-msg mb-0" :class="{ 'fw-bold text-dark': contact.unread }">
                {{ contact.lastMsg }}
              </p>
              <div v-if="contact.propertyInterest" class="property-tag mt-1">
                <i class="bx bx-building-house"></i> {{ contact.propertyInterest }}
              </div>
            </div>
            
            <div v-if="contact.unread" class="unread-badge"></div>
          </a>
        </div>
      </aside>

      <main class="chat-main">
        <template v-if="activeContact.id">
          <header class="chat-main__header">
            <div class="d-flex align-items-center">
              <img :src="activeContact.avatar || `https://ui-avatars.com/api/?name=${activeContact.name}&background=random&color=fff`" class="header-avatar" alt="" />
              <div class="ms-3">
                <h5 class="mb-0 fw-bold">{{ activeContact.name }}</h5>
                <span class="status-text" :class="activeContact.online ? 'text-emerald' : 'text-muted'">
                  <i class="bx bxs-circle fs-6 me-1"></i>
                  {{ activeContact.online ? 'Đang hoạt động' : 'Ngoại tuyến' }}
                </span>
              </div>
            </div>
            
            <div class="header-actions">
              <button class="action-btn" title="Gọi điện"><i class="bx bx-phone"></i></button>
              <button class="action-btn" title="Gửi hợp đồng/báo giá"><i class="bx bx-file"></i></button>
              <button class="action-btn" title="Thông tin khách hàng"><i class="bx bx-info-circle"></i></button>
            </div>
          </header>

          <div class="chat-main__content" ref="chatContent">
            <div class="security-notice">
              <i class="bx bx-lock-alt"></i> Cuộc trò chuyện được mã hóa đầu cuối. Không chia sẻ mật khẩu của bạn.
            </div>

            <div v-for="msg in currentMessages" :key="msg.id" class="message-wrapper" :class="msg.sender === 'me' ? 'is-me' : 'is-other'">
              <div class="message-bubble">
                <p class="message-text">{{ msg.text }}</p>
                <span class="message-time">{{ msg.time }}</span>
              </div>
            </div>
          </div>

          <footer class="chat-main__footer">
            <div class="chat-input-wrapper">
              <button class="input-action-btn"><i class="bx bx-plus-circle"></i></button>
              <button class="input-action-btn"><i class="bx bx-image"></i></button>
              
              <input 
                type="text" 
                class="chat-input" 
                placeholder="Nhập tin nhắn hỗ trợ khách hàng..." 
                v-model="newMessage" 
                @keyup.enter="sendMessage" 
              />
              
              <button class="send-btn" :class="{ 'active': newMessage.trim() }" @click="sendMessage">
                <i class="bx bx-send"></i>
              </button>
            </div>
          </footer>
        </template>
        
        <div v-else class="chat-empty-state">
          <div class="empty-icon">💬</div>
          <h4>Quản lý tin nhắn khách hàng</h4>
          <p>Chọn một cuộc trò chuyện từ danh sách bên trái để bắt đầu hỗ trợ tư vấn bất động sản.</p>
        </div>
      </main>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted } from 'vue';

const searchQuery = ref('');
const activeChatId = ref(2);
const newMessage = ref('');
const chatContent = ref(null);

// Mock Data ngữ cảnh Bất động sản
const contacts = ref([
  { id: 1, name: 'Lê Minh Tuấn', avatar: '', lastMsg: 'Giá căn hộ 2PN bên Indochina là bao nhiêu em?', time: '09:51', online: true, unread: true, propertyInterest: 'Indochina Riverside' },
  { id: 2, name: 'Chị Hoàng Yến', avatar: '', lastMsg: 'Ok em, cuối tuần chị ghé xem thực tế nhé.', time: '14:32', online: true, unread: false, propertyInterest: 'Vinhomes Central' },
  { id: 3, name: 'Nguyễn Văn Đạt', avatar: '', lastMsg: 'Pháp lý dự án này đã có sổ hồng chưa?', time: 'T4', online: false, unread: false, propertyInterest: 'Đất nền Hòa Xuân' },
  { id: 4, name: 'Trần Thu Hà', avatar: '', lastMsg: 'Chị cần tìm mặt bằng kinh doanh quận Hải Châu', time: 'T3', online: true, unread: false, propertyInterest: 'Mặt bằng thương mại' },
  { id: 5, name: 'Phạm Đức Anh', avatar: '', lastMsg: 'Ngân hàng hỗ trợ vay tối đa bao nhiêu %?', time: '01/10', online: false, unread: false, propertyInterest: 'KĐT FPT City' }
]);

const messages = ref({
  1: [
    { id: 1, sender: 'other', text: 'Chào em, chị thấy tin đăng căn Penthouse.', time: '09:45' },
    { id: 2, sender: 'other', text: 'Giá căn hộ 2PN bên Indochina là bao nhiêu em?', time: '09:51' }
  ],
  2: [
    { id: 1, sender: 'other', text: 'Chào em, chị quan tâm dự án Vinhomes em đang đăng', time: '14:15' },
    { id: 2, sender: 'me', text: 'Dạ em chào chị Yến. Căn 3PN hiện tại giá đang cực tốt, chủ nhà đang ngộp bank ạ.', time: '14:17' },
    { id: 3, sender: 'other', text: 'Giá chốt là bao nhiêu em? Có bớt lộc không?', time: '14:20' },
    { id: 4, sender: 'me', text: 'Dạ giá chốt 4.2 tỷ bao gồm hết thuế phí rồi chị nhé. Để em gửi chị bảng tính dòng tiền ạ.', time: '14:22' },
    { id: 5, sender: 'other', text: 'Ok em, cuối tuần chị ghé xem thực tế nhé.', time: '14:32' }
  ],
  3: [
    { id: 1, sender: 'other', text: 'Pháp lý dự án này đã có sổ hồng chưa?', time: 'Thứ Tư' }
  ]
});

const filteredContacts = computed(() => {
  if (!searchQuery.value.trim()) return contacts.value;
  const q = searchQuery.value.toLowerCase();
  return contacts.value.filter(c => c.name.toLowerCase().includes(q) || c.propertyInterest?.toLowerCase().includes(q));
});

const activeContact = computed(() => {
  return contacts.value.find(c => c.id === activeChatId.value) || {};
});

const currentMessages = computed(() => {
  return messages.value[activeChatId.value] || [];
});

const selectChat = (id) => {
  activeChatId.value = id;
  // Xóa trạng thái unread khi click vào
  const contact = contacts.value.find(c => c.id === id);
  if (contact) contact.unread = false;
  
  nextTick(() => scrollToBottom());
};

const sendMessage = () => {
  if (!newMessage.value.trim()) return;
  
  const chatId = activeChatId.value;
  if (!messages.value[chatId]) messages.value[chatId] = [];
  
  const now = new Date();
  const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  
  messages.value[chatId].push({
    id: Date.now(),
    sender: 'me',
    text: newMessage.value.trim(),
    time: timeStr
  });
  
  const contact = contacts.value.find(c => c.id === chatId);
  if (contact) {
    contact.lastMsg = newMessage.value.trim();
    contact.time = timeStr;
  }
  
  newMessage.value = '';
  nextTick(() => scrollToBottom());
};

const scrollToBottom = () => {
  if (chatContent.value) {
    chatContent.value.scrollTop = chatContent.value.scrollHeight;
  }
};

onMounted(() => {
  scrollToBottom();
});
</script>

<style scoped>
/* Biến màu chủ đạo cho Broker */
.broker-chat-container {
  --emerald-50: #ecfdf5;
  --emerald-100: #d1fae5;
  --emerald-500: #10b981;
  --emerald-600: #059669;
  --emerald-700: #047857;
  --bg-gray: #f8fafc;
  --border-color: #e2e8f0;
  --text-dark: #1e293b;
  --text-muted: #64748b;
  
  display: flex;
  height: calc(100vh - 120px); /* Điều chỉnh tùy theo layout tổng của bạn */
  min-height: 600px;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  border: 1px solid var(--border-color);
  font-family: 'Inter', sans-serif;
}

.bg-emerald { background-color: var(--emerald-500) !important; color: white; }
.text-emerald { color: var(--emerald-500) !important; }

/* ================= SIDEBAR ================= */
.chat-sidebar {
  width: 340px;
  background: white;
  border-right: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
}

.chat-sidebar__header {
  padding: 20px;
  border-bottom: 1px solid var(--border-color);
}

.search-box {
  position: relative;
}
.search-box .search-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-muted);
  font-size: 1.2rem;
}
.search-box input {
  padding-left: 36px;
  border-radius: 99px;
  background: var(--bg-gray);
  border: 1px solid transparent;
  transition: all 0.2s;
}
.search-box input:focus {
  background: white;
  border-color: var(--emerald-500);
  box-shadow: 0 0 0 3px var(--emerald-100);
}

.chat-filters {
  display: flex;
  gap: 8px;
}
.filter-btn {
  background: var(--bg-gray);
  border: none;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  color: var(--text-muted);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}
.filter-btn.active, .filter-btn:hover {
  background: var(--emerald-50);
  color: var(--emerald-600);
}

.chat-sidebar__list {
  flex: 1;
  overflow-y: auto;
  padding: 10px 0;
}
.chat-sidebar__list::-webkit-scrollbar { width: 4px; }
.chat-sidebar__list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: var(--text-muted);
}
.empty-state i { font-size: 3rem; color: #cbd5e1; margin-bottom: 10px; }

.chat-contact-item {
  display: flex;
  padding: 12px 20px;
  text-decoration: none;
  color: inherit;
  transition: background 0.2s;
  position: relative;
}
.chat-contact-item:hover { background: var(--bg-gray); }
.chat-contact-item.is-active {
  background: var(--emerald-50);
  border-right: 3px solid var(--emerald-500);
}

.contact-avatar {
  position: relative;
  width: 48px;
  height: 48px;
  flex-shrink: 0;
}
.contact-avatar img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
}
.status-dot {
  position: absolute;
  bottom: 2px;
  right: 2px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  border: 2px solid white;
}
.status-dot.online { background: #10b981; }
.status-dot.offline { background: #94a3b8; }

.contact-info {
  flex: 1;
  min-width: 0;
  margin-left: 12px;
}
.contact-name { font-weight: 600; color: var(--text-dark); font-size: 0.95rem; }
.contact-time { font-size: 0.75rem; color: var(--text-muted); }
.contact-last-msg {
  font-size: 0.85rem;
  color: var(--text-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.property-tag {
  display: inline-block;
  font-size: 0.7rem;
  padding: 2px 8px;
  background: #f1f5f9;
  color: #475569;
  border-radius: 4px;
  font-weight: 500;
}

.unread-badge {
  position: absolute;
  top: 50%;
  right: 20px;
  transform: translateY(-50%);
  width: 10px;
  height: 10px;
  background: var(--emerald-500);
  border-radius: 50%;
}

/* ================= MAIN CHAT AREA ================= */
.chat-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: var(--bg-gray);
  min-width: 0;
}

.chat-main__header {
  padding: 16px 24px;
  background: white;
  border-bottom: 1px solid var(--border-color);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-avatar { width: 44px; height: 44px; border-radius: 50%; }
.status-text { font-size: 0.8rem; font-weight: 500; display: flex; align-items: center; }

.header-actions { display: flex; gap: 12px; }
.action-btn {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 1px solid var(--border-color);
  background: white;
  color: var(--text-dark);
  font-size: 1.1rem;
  cursor: pointer;
  transition: all 0.2s;
}
.action-btn:hover { background: var(--emerald-50); color: var(--emerald-600); border-color: var(--emerald-200); }

.chat-main__content {
  flex: 1;
  padding: 24px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.chat-main__content::-webkit-scrollbar { width: 6px; }
.chat-main__content::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }

.security-notice {
  text-align: center;
  font-size: 0.75rem;
  color: #94a3b8;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
}

.message-wrapper {
  display: flex;
  flex-direction: column;
  max-width: 70%;
}
.message-wrapper.is-other { align-self: flex-start; }
.message-wrapper.is-me { align-self: flex-end; }

.message-bubble {
  padding: 12px 16px;
  border-radius: 18px;
  position: relative;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.is-other .message-bubble {
  background: white;
  color: var(--text-dark);
  border-bottom-left-radius: 4px;
}
.is-me .message-bubble {
  background: var(--emerald-600);
  color: white;
  border-bottom-right-radius: 4px;
}

.message-text { margin: 0; font-size: 0.95rem; line-height: 1.4; }
.message-time {
  font-size: 0.7rem;
  display: block;
  margin-top: 4px;
  text-align: right;
  opacity: 0.7;
}

.chat-main__footer {
  padding: 20px 24px;
  background: white;
  border-top: 1px solid var(--border-color);
}

.chat-input-wrapper {
  display: flex;
  align-items: center;
  background: var(--bg-gray);
  border-radius: 99px;
  padding: 6px 12px;
  border: 1px solid var(--border-color);
}

.input-action-btn {
  background: transparent;
  border: none;
  color: var(--text-muted);
  font-size: 1.4rem;
  padding: 4px 8px;
  cursor: pointer;
  transition: color 0.2s;
}
.input-action-btn:hover { color: var(--emerald-500); }

.chat-input {
  flex: 1;
  background: transparent;
  border: none;
  padding: 8px 12px;
  outline: none;
  color: var(--text-dark);
}

.send-btn {
  background: var(--emerald-500);
  color: white;
  border: none;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  opacity: 0.5;
  pointer-events: none;
}
.send-btn.active { opacity: 1; pointer-events: auto; }
.send-btn.active:hover { background: var(--emerald-600); transform: scale(1.05); }

/* Khi chưa chọn đoạn chat */
.chat-empty-state {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: white;
  text-align: center;
  color: var(--text-muted);
}
.chat-empty-state .empty-icon { font-size: 4rem; margin-bottom: 16px; animation: bounce 2s infinite ease-in-out; }
@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

/* Responsive cơ bản */
@media (max-width: 768px) {
  .chat-sidebar { width: 100%; display: flex; }
  .chat-main { display: none; /* Ở bản mobile thực tế, bạn cần làm logic ẩn hiện bằng v-if khi activeChatId thay đổi */ }
}
</style>