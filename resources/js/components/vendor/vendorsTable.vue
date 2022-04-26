<template>
  <pre-loader v-if="preLoader"></pre-loader>
  <!-- DataTable with Hover -->
  <div class="col-lg-12" v-else>
    <div class="card mb-4">
      <div
        class="
          card-header
          py-3
          d-flex
          flex-row
          align-items-center
          justify-content-between
        "
      >
        <h5 class="m-0 font-weight-bold text-dark">Vendors</h5>

        <button
          type="button"
          class="btn btn-primary rounded-pill"
          data-toggle="modal"
          data-target="#addvendor"
          id="#myBtna"
        >
          Add Vendor<i class="fas fa-plus-circle fa-fw"></i>
        </button>
      </div>
      <div class="table-responsive p-3">
        <table
          class="table align-items-center table-flush table-hover"
          id="vendorTable"
          ref="vendorTable"
        >
          <thead class="thead-light">
            <tr>
              <th>Index</th>

              <th>Name</th>
              <th>Mobile</th>

              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(vendor, index) in vendor_details" :key="vendor.id">
              <td>{{ index + 1 }}</td>
              <td>{{ vendor.name }}</td>
              <td>{{ vendor.mobile }}</td>
              <td>
                <button
                  type="button"
                  class="btn btn-sm btn-primary"
                  name=""
                  data-toggle="modal"
                  data-target="#editvendor"
                  @click="editVendor(vendor)"
                >
                  <i class="fas fa-edit fa-fw"></i>
                </button>

                <button
                  type="button"
                  class="btn btn-sm btn-danger"
                  name=""
                  @click="deleteVendors(vendor)"
                  v-if="role_admin == 1"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- modal -->
    <div
      class="modal fade"
      id="addvendor"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <add-vendor :usergst="this.user.gst"></add-vendor>
        </div>
      </div>
    </div>

    <!-- end modal -->
    <div
      class="modal fade"
      id="editvendor"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <add-vendor :edit="true" :usergst="this.user.gst"></add-vendor>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      role_admin: 0,
      preLoader: false,
      vendor_details: {},
    };
  },
  created() {
    this.role_admin = this.user.role;
    this.getVendors();
    var _this = this;
    bus.$on("vendor-added", function () {
      _this.getVendors();
    });
  },

  methods: {
    getVendors() {
      this.preLoader = true; //the loading begin
      axios
        .get("get-vendors")
        .then((res) => {
          this.vendor_details = res.data;
          this.preLoader = false; //the loading end
        })
        .catch((err) => {
          this.preLoader = false; //the loading end
          console.log(err);
        });
    },
    deleteVendors(vendor) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("delete-vendor", {
              id: vendor.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getVendors();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });
                this.getVendors();
              }
            })
            .catch((err) => {
              Toast.fire({
                icon: "error",
                title: "Request failed ",
              });
            });
        }
      });
    },

    editVendor(vendor) {
      bus.$emit("edit-vendor", vendor);
    },
  },

  watch: {
    vendor_details(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.vendorTable).DataTable({
          pageLength: 10,
          lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
          ],
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.vendorTable).DataTable({
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
    });
  },
};
</script>
