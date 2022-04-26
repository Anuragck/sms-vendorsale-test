<template>
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="main" v-else>
    <div class="col-lg-12">
      <div class="card mb-4 x">
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
          <h5 class="m-0 font-weight-bold text-dark">Item Report</h5>
        </div>
        <div class="input-group mb-3 justify-content-center">
          <div class="w-25">
            <model-list-select
              :list="this.item_list"
              name="product_id"
              v-model="product_id"
              option-value="id"
              :custom-text="productName"
              placeholder="select item"
            >
            </model-list-select>
          </div>
          <div class="input-group-append ml-4">
            <button class="btn btn-primary rounded-pill" @click="getItem()">
              Search <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>

        <div class="text-center mr-5 pr-5 mt-5 pt-5" v-if="product_name == ''">
          <h6>Select Product To Continue . . .</h6>
        </div>

        <!-------------------------->

        <div class="tableSection" v-if="product_name != ''">
          <!-- item detals  -->

          <!-- Earnings (Annual) Card Example -->
          <div class="col-6 mb-4 justify-content-center">
            <div class="card h-100">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="h5 mb-2 font-weight-bold">
                      <span class="p">Product Name: </span>
                      {{ product_name.toUpperCase() }}
                    </div>

                    <h4>
                      <span class="badge badge-info h3"
                        >Opening Quantity: {{ opening_quantity }}</span
                      >
                      <span class="badge badge-success ml-2 h3">
                        Current Quantity: {{ current_quantity }}</span
                      >
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="table-responsive p-3" v-if="this.items_list.length != 0">
            <table
              class="table table-sm align-items-center table-flush table-hover"
              id="itemTable"
              ref="itemTable"
            >
              <thead class="thead-light">
                <tr>
                  <th>Index</th>
                  <th>Date</th>
                  <th>Description</th>
                  <th>Quantity</th>
                  <th>Changes</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in items_list.slice().reverse()"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.date | myDate }}</td>
                  <td>{{ item.description }}</td>
                  <td>
                    <span
                      :class="item.type == '1' ? 'text-success' : 'text-danger'"
                    >
                      {{ item.quantity }}</span
                    >
                  </td>

                  <td v-if="item.type == '1'">
                    {{ item.change }}
                  </td>
                  <td v-if="item.type == '2'">
                    {{ item.change }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else>
            <h5>No Track Record Found !</h5>
          </div>
        </div>
      </div>
    </div>

    <!--<div v-else class="Height"></div>-->
  </div>
</template>

<script>
import { ModelListSelect } from "vue-search-select";
export default {
  components: {
    ModelListSelect,
  },
  data() {
    return {
      preLoader: false,
      item_list: [],
      product_list: [],
      items_list: [],
      product_id: "",
      netquantity: 0,

      product_name: "",
      opening_quantity: "",

      current_quantity: "",
      search: false,
    };
  },

  created() {
    this.getProduct();
  },
  methods: {
    getItem() {
      console.log(this.product_id);
      if (this.product_id == "") {
        return false;
      }
      this.items_list = [];

      axios
        .post("/admin/get-item-details", {
          product_id: this.product_id,
        })
        .then((response) => {
          var list = response.data;

          this.product_name = list["0"].product_name;

          this.opening_quantity = list["0"].opening_quantity;

          this.current_quantity = list["0"].current_quantity;
          this.netquantity = list["0"].opening_quantity;

          var date;
          var description;
          var quantity;
          var type;
          var change = parseFloat(list["0"].opening_quantity);
          for (let item in list["0"].transactions) {
            date = list["0"].transactions[item].date;
            description = list["0"].transactions[item].description;
            quantity = list["0"].transactions[item].quantity;
            type = list["0"].transactions[item].type;

            if (list["0"].transactions[item].type == 1) {
              change =
                change + parseFloat(list["0"].transactions[item].quantity);
            }
            if (list["0"].transactions[item].type == 2) {
              change =
                change - parseFloat(list["0"].transactions[item].quantity);
            }

            this.items_list.push({
              date: date,
              description: description,
              quantity: quantity,
              type: type,
              change: change.toFixed(2),
            });
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },

    getProduct() {
      this.preLoader = true;
      axios
        .get("/admin/get-products")
        .then((response) => {
          this.product_list = response.data;

          if (this.product_list.length != 0) {
            for (let item in this.product_list) {
              if (
                this.product_list[item].category_id == "1" ||
                this.product_list[item].category_id == "2"
              ) {
                this.item_list.push({
                  id: this.product_list[item].id,
                  name: this.product_list[item].name,
                });
              }
            }
          }
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

    productName(item) {
      return `${item.name}`;
    },
  },

  watch: {
    items_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.itemTable).DataTable({
          pageLength: 5,

          dom: "lBfrtip",

          buttons: [
            {
              extend: "copy",
              title: this.product_name,
              messageTop:
                "Opening Quantity  : " +
                this.opening_quantity +
                "   Current Quantity  :" +
                this.current_quantity,
            },
            {
              extend: "excel",
              title: this.product_name,
              messageTop:
                "Opening Quantity  : " +
                this.opening_quantity +
                "   Current Quantity  :" +
                this.current_quantity,
            },
            {
              extend: "pdf",
              title: this.product_name,
              messageTop:
                "Opening Quantity  : " +
                this.opening_quantity +
                "   Current Quantity  :" +
                this.current_quantity,
            },
            {
              extend: "print",
              title: this.product_name,
              messageTop:
                "Opening Quantity  : " +
                this.opening_quantity +
                "   Current Quantity  :" +
                this.current_quantity,
            },
          ],

          lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
          ],
          bSort: false,
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.itemTable).DataTable({
      pageLength: 5,

      dom: "lBfrtip",

      buttons: [
        {
          extend: "copy",
          title: this.product_name,
          messageTop:
            "Opening Quantity  : " +
            this.opening_quantity +
            "   Current Quantity  :" +
            this.current_quantity,
        },
        {
          extend: "excel",
          title: this.product_name,
          messageTop:
            "Opening Quantity  : " +
            this.opening_quantity +
            "   Current Quantity  :" +
            this.current_quantity,
        },
        {
          extend: "pdf",
          title: this.product_name,
          messageTop:
            "Opening Quantity  : " +
            this.opening_quantity +
            "   Current Quantity  :" +
            this.current_quantity,
        },
        {
          extend: "print",
          title: this.product_name,
          messageTop:
            "Opening Quantity  : " +
            this.opening_quantity +
            "   Current Quantity  :" +
            this.current_quantity,
        },
      ],

      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],

      bSort: false,
    });
  },
};
</script>

<style scoped>
.Height {
  min-height: 360px;
}

.x {
  min-height: 80vh;
}
</style>
