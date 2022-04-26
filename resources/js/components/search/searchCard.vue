<template>
  <div class="container">
    <!-- Modal -->
    <div
      class="modal fade"
      id="productcard"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
              Product Details
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="clearModalData()"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">


    <div class="col-lg-12 px-4 pt-2 pb-5">
                        <h4>{{ item.name }}</h4>
                        <div class="button-list mt-1 mb-3 ">
                            <div  class="btn btn-primary-rgba font-weight-bold">Opening Quatity : {{ item.opening_quantity }}</div>
                            <div  class="btn btn-success-rgba font-weight-bold">Current Quantity : {{ item.quantity }}</div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row" class="p-1">Minimum Quantity :</th>
                                        <td class="p-1">{{ item.minimum_qty }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="p-1">Mrp :</th>
                                        <td class="p-1">{{ item.mrp }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="p-1">HSN Code :</th>
                                        <td class="p-1">{{ item.hsn_code }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="p-1">Part No :</th>
                                        <td class="p-1">{{ item.part_no }}</td>
                                    </tr>
  <tr v-if="item.gst != ''">
                                        <th scope="row" class="p-1">Gst :</th>
                                        <td class="p-1">{{ parseInt(item.gst) }} % </td>
                                    </tr>

<tr>
                                        <th scope="row" class="p-1">Location :</th>
                                        <td class="p-1">{{ (item.location) }}  </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>



          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      item: "",
    };
  },

  created() {
    var _this = this;
    bus.$on("product-details", function (item) {
      _this.item = item;
    });
  },

  methods: {
    clearModalData() {
      this.item = "";
    },
  },
};
</script>

<style scoped>
.card {
    width: 280px;
    background-color: #fff;
    border-radius: 35px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)
}

.m-b-30 {
    margin-bottom: 30px;
}
p{
color: #8A98AC;
}
.table-borderless td {
    border: 0 !important;
}
.table td {
    color: #8A98AC;
    vertical-align: middle;
    border-top: 1px solid rgba(0, 0, 0, 0.03);
    padding: 0.6rem;
}
.btn-primary-rgba {
    background-color: rgba(80, 111, 228, 0.1);
    border: none;
    color: #506fe4;
}
.btn-success-rgba {
    background-color: rgba(67, 209, 135, 0.1);
    border: none;
    color: #43d187;
}
.card {
    border: none;
    border-radius: 3px;
    background-color: #ffffff;
}

</style>>

