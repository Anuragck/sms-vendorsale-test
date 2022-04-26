<template>
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="main" v-else>
    <!-- DataTable with Hover -->
    <div class="col-lg-12">
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
          <h5 class="m-0 font-weight-bold text-dark">Products</h5>

          <button
            type="button"
            class="btn rounded-pill btn-primary"
            data-toggle="modal"
            data-target="#addProduct"
          >
            Add Product <i class="fas fa-plus-circle fa-fw"></i>
          </button>
        </div>
        <div class="table-responsive p-3">
          <table
            class="table align-items-center table-flush table-hover display"
            id="productsTable"
            ref="productsTable"
            data-order=""
          >
            <thead class="thead-light">
              <tr>
                <th>Index</th>
                <th>Category</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>MRP</th>
                <th v-if="user_gst">GST %</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Index</th>
                <th>Category</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>MRP</th>
                <th v-if="user_gst">GST %</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              <tr v-for="(product, index) in productsData" :key="product.id">
                <td>{{ index + 1 }}</td>
                <td>{{ product.category.name }}</td>
                <td>{{ product.name }}</td>
                <td>{{ product.quantity }}</td>
                <td>{{ product.mrp }}</td>
                <td v-if="user_gst">{{ product.gst }}</td>

                <td class="pl-3">
                  <button
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#editProductModal"
                    @click="editProduct(product)"
                  >
                    <i class="fas fa-edit fafw" title="Edit"></i>
                  </button>

                  <button
                    class="btn btn-danger btn-sm ml-1"
                    @click="deleteProduct(product)"
                  >
                    <i class="fas fa-trash fafw" title="Move to trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- add product modal -->

      <div
        id="addProduct"
        class="modal fade"
        tabindex="-1"
        data-backdrop="static"
        role="dialog"
        aria-labelledby="myLargeModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <add-product :usergst="this.user.gst"></add-product>
          </div>
        </div>
      </div>

      <!-- modal end -->

      <!-- add product modal end -->

      <div
        id="editProductModal"
        class="modal fade"
        tabindex="-1"
        data-backdrop="static"
        role="dialog"
        aria-labelledby="myLargeModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog  modal-lg">
          <div class="modal-content">
            <add-product
              :edit="true"
              :usergst="this.user.gst"
              :reward="this.user.reward"
            ></add-product>
          </div>
        </div>
      </div>

      <!-- modal end -->
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      preLoader: false,
      productsData: {},
      user_gst: this.user.gst,
    };
  },

  methods: {
    getProducts() {
      this.preLoader = true; //the loading begin

      axios
        .get("get-products")
        .then((response) => {
          this.productsData = response.data;
          this.preLoader = false; //the loading end
        })
        .catch((err) => {
          this.preLoader = false; //the loading end
          console.log(err);
        });
    },

    deleteProduct(product) {
      Swal.fire({
        title: "Are you sure?",
        // text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("delete-product", product).then((response) => {
            if (response.data == "success") {
              this.getProducts();
              Toast.fire({
                icon: "error",
                title: "Record Deleted successfully",
              });
            }

            if (response.data == "failed") {
              Toast.fire({
                icon: "error",
                title: "Some Error Occurred,Please Try Again Later",
              });

              this.getProducts();
            }
          });
        }
      });
    },

    editProduct(product) {
      bus.$emit("edit-product", product);
    },
  },

  created() {
    this.getProducts();

    var _this = this;
    bus.$on("product-added", function () {
      _this.getProducts();
    });
  },

  watch: {
    productsData(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.productsTable).DataTable({
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
    this.dt = $(this.$refs.productsTable).DataTable({
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
    });
  },
};
</script>
