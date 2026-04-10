<template>
  <div class="row">
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header text-center"><h5>Thêm Mới Chức Vụ</h5></div>
        <div class="card-body">
          <label class="fw-bold"> Tên chức vụ </label>
          <input
            v-model="chuc_vu.ten_chuc_vu"
            type="text"
            class="form-control mb-2"
            name="ten_chuc_vu"
          />

          <label class="fw-bold"> Slug </label>
          <input
            v-model="chuc_vu.slug_chuc_vu"
            type="text"
            class="form-control mb-2"
            name="slug_chuc_vu"
          />
        </div>
        <div class="card-footer text-end">
          <button v-on:click="themChucVu()" class="btn btn-primary">
            Thêm chức vụ
          </button>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header text-center">
          <h5>Danh Sách Chức Vụ</h5>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center">STT</th>
                <th class="text-center">Tên chức vụ</th>
                <th class="text-center">Slug</th>
                
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(v, i) in chucVuList" :key="i">
                <tr>
                  <td class="text-center">{{ i + 1 }}</td>
                  <td>{{ v.ten_chuc_vu }}</td>
                  <td>{{ v.slug_chuc_vu }}</td>
                  <td class="text-center">
                  </td>
                  <td class="text-center">
                    <button
                      v-on:click="
                        () => {
                          Object.assign(edit, v);
                        }
                      "
                      class="btn btn-primary me-1"
                      data-bs-toggle="modal"
                      data-bs-target="#staticBackdrop"
                    >
                      Sửa
                    </button>
                    <button
                      v-on:click="
                        () => {
                          Object.assign(this.delete, v);
                        }
                      "
                      class="btn btn-danger"
                      data-bs-toggle="modal"
                      data-bs-target="#staticBackdropXoa"
                    >
                      Xóa
                    </button>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Sửa-->
  <div
    class="modal fade"
    id="staticBackdrop"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <label class="fw-bold"> Tên chức vụ </label>
          <input
            v-model="edit.ten_chuc_vu"
            type="text"
            class="form-control mb-2"
            name="ten_chuc_vu"
          />

          <label class="fw-bold"> Slug </label>
          <input
            v-model="edit.slug_chuc_vu"
            type="text"
            class="form-control mb-2"
            name="slug_chuc_vu"
          />

          <label class="fw-bold"> Tình trạng </label>
          <select
            v-model="edit.tinh_trang"
            class="form-select mb-2"
            name="tinh_trang"
          >
            <option value="1">Hoạt động</option>
            <option value="0">Không hoạt động</option>
          </select>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Hủy
          </button>
          <button
            data-bs-dismiss="modal"
            v-on:click="capNhatChucVu()"
            type="button"
            class="btn btn-primary"
          >
            Cập Nhật
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Model Xóa -->
  <div
    class="modal fade"
    id="staticBackdropXoa"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Xóa Chức Vụ</h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <p class="fw-bold">Xác nhận xóa chức vụ này không?</p>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Hủy
          </button>
          <button
            type="button"
            data-bs-dismiss="modal"
            class="btn btn-primary"
            @click="xoaChucVu()"
          >
            Xác nhận
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      chucVuList: [],
      chuc_vu: {
        ten_chuc_vu: "",
        slug_chuc_vu: ""
      },
      edit: {},
      delete: {},
    };
  },
  created() {
    this.loadDuLieu();
  },
  methods: {
    loadDuLieu() {
      axios
        .get("http://127.0.0.1:8000/api/admin/chuc-vu/data")
        .then((res) => {
          this.chucVuList = res.data.data;
        })
        .catch((err) => {
          console.log("Error:", err);
        });
    },
    themChucVu() {
      // Gọi API để thêm chức vụ
      axios
        .post("http://127.0.0.1:8000/api/admin/chuc-vu/create", this.chuc_vu)
        .then((response) => {
          // // Debug: xem response trả về như nào
          // console.log('API Response:', response.data);
          // Xử lý kết quả trả về
          if (
            response.data.success ||
            response.status === 200 ||
            response.status === 201
          ) {
            const message = response.data.message || "Thêm chức vụ thành công!";

            this.$toast.success(
              `<div style="text-align:left"><strong>✅ Thành công!</strong><p style="margin:4px 0 0 0">${message}</p></div>`
            );
            this.loadDuLieu(); // Tải lại danh sách sau khi thêm mới
            this.chuc_vu = {
              ten_chuc_vu: "",
              slug_chuc_vu: "",
            }; // Reset form
          } else {
            this.$toast.error(
              `<div style="text-align:left"><strong>❌ Thất bại!</strong><p style="margin:4px 0 0 0">${
                response.data.message || "Có lỗi xảy ra"
              }</p></div>`
            );
          }
        })
        .catch((error) => {
          // Xử lý lỗi
          console.error("Lỗi khi thêm mới Chức Vụ:", error);
          if (
            error.response &&
            error.response.data &&
            error.response.data.errors
          ) {
            const errors = error.response.data.errors;
            const items = Object.values(errors)
              .flat()
              .map((msg) => `<li>${msg}</li>`)
              .join("");
            const messages = `<div style="text-align:left"><strong>⚠️ Vui lòng kiểm tra lại:</strong><ul style="margin:6px 0 0 0;padding-left:18px">${items}</ul></div>`;
            this.$toast.error(messages);
          } else {
            this.$toast.error("Đã xảy ra lỗi khi thêm mới Chức Vụ.");
          }
        });
    },
    capNhatChucVu() {
      // Gọi API để cập nhật chức vụ
      axios
        .put(`http://127.0.0.1:8000/api/admin/chuc-vu/update`, this.edit)
        .then((response) => {
          // Xử lý kết quả trả về
          if (
            response.data.success ||
            response.status === 200 ||
            response.status === 201
          ) {
            const message =
              response.data.message || "Cập nhật chức vụ thành công!";

            this.$toast.success(
              `<div style="text-align:left"><strong>✅ Thành công!</strong><p style="margin:4px 0 0 0">${message}</p></div>`
            );
            this.loadDuLieu(); // Tải lại danh sách sau khi cập nhật
          } else {
            this.$toast.error(
              `<div style="text-align:left"><strong>❌ Thất bại!</strong><p style="margin:4px 0 0 0">${
                response.data.message || "Có lỗi xảy ra"
              }</p></div>`
            );
          }
        });
    },
    xoaChucVu() {
      // Gọi API để xóa chức vụ
      axios
        .delete(
          `http://127.0.0.1:8000/api/admin/chuc-vu/delete/${this.delete.id}`
        )
        .then((response) => {
          if (
            response.data.success ||
            response.status === 200 ||
            response.status === 201
          ) {
            const message = response.data.message || "Xóa chức vụ thành công!";

            this.$toast.success(
              `<div style="text-align:left"><strong>✅ Thành công!</strong><p style="margin:4px 0 0 0">${message}</p></div>`
            );
            this.loadDuLieu(); // Tải lại danh sách sau khi xóa
          } else {
            this.$toast.error(
              `<div style="text-align:left"><strong>❌ Thất bại!</strong><p style="margin:4px 0 0 0">${
                response.data.message || "Có lỗi xảy ra"
              }</p></div>`
            );
          }
        })
        .catch((error) => {
          console.error("Lỗi khi xóa Chức Vụ:", error);
          this.$toast.error("Đã xảy ra lỗi khi xóa Chức Vụ.");
        });
    },
    doiTrangThai(v) {
      axios
        .patch("http://127.0.0.1:8000/api/admin/chuc-vu/update-tinh-trang", {
          id: v.id,
          tinh_trang: v.tinh_trang == 1 ? 0 : 1,
        })
        .then((response) => {
          if (
            response.data.success ||
            response.status === 200 ||
            response.status === 201
          ) {
            const message =
              response.data.message || "Cập nhật trạng thái thành công!";

            this.$toast.success(
              `<div style="text-align:left"><strong>✅ Thành công!</strong><p style="margin:4px 0 0 0">${message}</p></div>`
            );
            this.loadDuLieu(); // Tải lại danh sách sau khi cập nhật
          } else {
            this.$toast.error(
              `<div style="text-align:left"><strong>❌ Thất bại!</strong><p style="margin:4px 0 0 0">${
                response.data.message || "Có lỗi xảy ra"
              }</p></div>`
            );
          }
        });
    },
  },
};
</script>

<style>
</style>