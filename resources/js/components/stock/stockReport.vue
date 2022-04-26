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
          <h5 class="m-0 font-weight-bold text-dark">Stock Report</h5>
          <div class="shadow p-3 rounded">
            <h6 class="m-0 font-weight-bold text-muted">
              Total Stock Amount :
              <span class="text-dark"> {{ totalStockAmount }}</span>
            </h6>
          </div>
        </div>
        <div class="table-responsive p-3">
          <table
            class="table align-items-center table-flush table-hover display"
            id="productsTable"
            ref="productsTable"
          >
            <thead class="thead-light">
              <tr>
                <th>Index</th>
                <th>Category</th>
                <th>Product Name</th>
                <th>Current Quantity</th>
                <th>MRP</th>
                <th v-if="user.gst">GST (%)</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Index</th>
                <th>Category</th>
                <th>Product Name</th>
                <th>Current Quantity</th>
                <th>MRP</th>
                <th v-if="user.gst">GST (%)</th>
              </tr>
            </tfoot>
            <tbody>
              <tr v-for="(product, index) in productsData" :key="product.id">
                <td>{{ index + 1 }}</td>
                <td>{{ product.category.name }}</td>
                <td>{{ product.name }}</td>
                <td>{{ product.quantity }}</td>
                <td>{{ product.mrp }}</td>
                <td v-if="user.gst">{{ product.gst }}</td>
              </tr>
            </tbody>
          </table>
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
      preLoader: false,
      productsData: [],
    };
  },
  created() {
    this.getProducts();
  },
  methods: {
    getProducts() {
      this.preLoader = true;
      axios
        .get("get-stock-report")
        .then((response) => {
          this.productsData = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },
  },

  watch: {
    productsData(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.productsTable).DataTable({
          pageLength: 7,

          dom: "lBfrtip",
          buttons: [
            {
              extend: "copy",
              title: "Stock Report",
            },
            {
              extend: "excel",
              title: "Stock Report",
            },
            {
              extend: "pdf",
              title: "Stock Report",
            },
            {
              extend: "print",
              title: "Stock Report",
            },
          ],

          lengthMenu: [
            [7, 15, 25, 50, -1],
            [7, 15, 25, 50, "All"],
          ],
        });
      });
    },
  },
  computed: {
    totalStockAmount: function () {
      if (this.productsData != []) {
        var sum = 0;
        this.productsData.forEach((e) => {
          sum += parseFloat(e.mrp) * parseFloat(e.quantity);
        });
        return parseFloat(sum).toFixed(2);
      }
    },
  },

  mounted() {
    this.dt = $(this.$refs.productsTable).DataTable({
      pageLength: 5,

      dom: "lBfrtip",
      buttons: [
        {
          extend: "copy",
          title: "Stock Report",
        },
        {
          extend: "excel",
          title: "Stock Report",
        },
        {
          extend: "pdf",
          title: "Stock Report",
        },
        {
          extend: "print",
          title: "Stock Report",
        },
      ],

      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
    });
  },
};
</script>
