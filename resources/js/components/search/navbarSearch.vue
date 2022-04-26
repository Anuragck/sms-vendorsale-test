<template>
  <div class="home-search-bar">
    <div class="input-group w-100 mt-2">
      <!-- <div class="search-wrapper panel-heading col-sm-12"> -->
      <input
        class="form-control py-2 rounded-pill mr-2"
        type="text"
        v-model="searchQuery"
        placeholder="Search Products"
        ref="searchBox"
        @keydown.enter="searchNow()"

      />
      <span class="input-group-append serach-icon-home">
        <button
          class="btn rounded-pill border-0 ml-n5 "
          type="button"
          @click="searchBox()"
        >
          <i class="fa fa-search"></i>
        </button>
      </span>
      <!-- </div> -->
    </div>

    <div class="dropdown" v-if="searchQuery != ''">
      <div id="myDropdown" class="dropdown-content max-height-search bg-light">
        <div v-for="item in resultQuery" :key="item.id" class="cursor-pointer">
          <a
            class="dropdown-item text-dark cursor-pointer;"
            data-toggle="modal"
            data-target="#productcard"
            @click="showProduct(item)"
            >{{ item.name }}</a
          >
        </div>
      </div>
    </div>

    <div class="dropdown" v-if="resultQuery.length == '0' && searchQuery != ''">
      <div id="myDropdown" class="dropdown-content max-height-search bg-light">
        <a class="dropdown-item text-dark">No Results Found</a>
        <!-- <span class="pl-3">No Results Found</span> -->
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      searchQuery: "",
      resources: [],
      product_data: [],
    };
  },
  methods: {

    getEnquiryProducts() {
      axios
        .get("/admin/get-sale-product")
        .then((response) => {
          this.resources = response.data;
          this.product_data = response.data;
        })
        .catch((err) => {
          console.log("enquiry form errs" + err);
        });
    },

    showProduct(item) {
      bus.$emit("product-details", item);
      this.searchQuery = "";
    },

    searchBox() {
      this.$refs.searchBox.focus();
    },

    searchNow() {
      if (this.searchQuery != "") {
        if (this.product_data != []) {
          var name;
          var product;
          this.product_data.forEach((e) => {
            name = e.name;
            product = e;

            if (name == this.searchQuery.toUpperCase()) {
              $("#productcard").modal("show");
              bus.$emit("product-details", product);
              this.searchQuery = "";
            }
          });
        }
      }
    },
  },
  created() {
    this.getEnquiryProducts();
    var _this = this;
    bus.$on("product-added", function () {
      _this.getEnquiryProducts();
    });
  },
  computed: {
    resultQuery() {
      if (this.searchQuery) {
        return this.resources.filter((item) => {
          return this.searchQuery
            .toLowerCase()
            .split(" ")
            .every((v) => item.name.toLowerCase().includes(v));
        });
      } else {
        return this.resources;
      }
    },
  },
};
</script>
<style scoped>
.max-height-search {
  max-height: 200px;
  overflow-y: scroll;
  position: absolute;
  z-index: 99;
  border-radius: 10px;
  top: 10px;
  width: 100%;
}

.cursor-pointer:hover {
  cursor: pointer;
}
</style>
