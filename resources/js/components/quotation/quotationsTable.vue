<template>
  <!-- DataTable with Hover -->
  <pre-loader v-if="preLoader"></pre-loader>
  <div class="salePage" v-else>
   
    <div class="col-lg-12" v-if="salePage == false">
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
          <h5 class="m-0 font-weight-bold text-dark">Quotations</h5>

          <button
            type="button"
            class="btn btn-primary rounded-pill"
            data-toggle="modal"
            data-target="#addquotation"
            id="#myBtn"
            @click="addQuotation = true"
          >
            Add Quotation<i class="fas fa-plus-circle fa-fw"></i>
          </button>
        </div>

        <div class="table-responsive p-3">
          <table
            class="table table-sm align-items-center table-flush table-hover"
            ref="salesTable"
            id="salesTable"
          >
            <thead class="thead-light">
              <tr>
                <th>Index</th>
                <th>Quotation No</th>
                <th>Name</th>
                <th>Total Amount</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(quotation, index) in quotation_list" :key="quotation.id">
                <td>{{ index + 1 }}</td>
                 <td>{{ quotation.quotation_no }}</td>
                <td>{{ quotation.customer["name"] }}</td>
                <td>{{ quotation.pay_amount }}</td>

                <td class="pl-3" style="white-space: nowrap">
                  <button
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#editsale"
                    @click="editQuotation(quotation)"
                    :disabled="quotation.gst_bill == 1 && authUser == false"
                  >
                    <i class="fas fa-edit fafw" title="Edit"></i>
                  </button>

                  <button
                    class="btn btn-danger btn-sm"
                    @click="deleteQuotation(quotation)"
                  >
                    <i class="fas fa-trash fafw" title="Move to trash"></i>
                  </button>

                  <button
                    class="btn btn-success btn-sm"
                    data-toggle="modal"
                    data-target="#printQuotation"
                    @click="quotationPrint(quotation)"
                  >
                    <i class="fas fa-file-invoice" title="Quotation"></i>
                  </button>
               
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- modal -->
      <div
        v-if="addQuotation"
        class="modal fade"
        id="addquotation"
        data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <add-quotation
              :usergst="this.user.gst"
              :vehicle="this.user.vehicle"
            ></add-quotation>
          </div>
        </div>
      </div>

      <!-- end modal -->

      <!-- edit modal  -->
      <div
        class="modal fade"
        id="editsale"
        data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <add-quotation
              :edit="true"
              :usergst="this.user.gst"
              :vehicle="this.user.vehicle"
            ></add-quotation>
          </div>
        </div>
      </div>
      <!-- end modal -->

      <!-- Print Quotation modal  -->
      <div
        class="modal fade"
        id="printQuotation"
        data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <quotation-print></quotation-print>
          </div>
        </div>
      </div>
      <!-- Print Quotation modal -->
    </div>
  </div>
</template>

<script>
import quotationPrint from './quotationPrint.vue';
export default {
  components: { quotationPrint },
  props: ["user"],
  data() {
    return {
      salePage: false,
      preLoader: false,
      quotation_list: {},
      vehiclecheck: false,
      rewardcheck: false,
      addQuotation: false,
      authUser: false,
    };
  },

  created() {
    if (this.user.gst == 1) {
      this.authUser = true;
    }
    if (this.user.vehicle == 1) {
      this.vehiclecheck = true;
    }
    if (this.user.reward == 1) {
      this.rewardcheck = true;
    }

    this.getQuotations();
    var _this = this;
    bus.$on("quotation-added", function () {
      _this.getQuotations();
    });
  },
  methods: {
    getQuotations() {
      this.preLoader = true; //the loading begin
      axios
        .get("/admin/get-quotations")

        .then((response) => {
          this.quotation_list = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

    editQuotation(quotation) {
      bus.$emit("edit-quotation", quotation);
    },

    quotationPrint(quotation) {
      bus.$emit("quotation-print", quotation);
    },

    deleteQuotation(quotation) {
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
            .post("/admin/delete-quotation", {
              id: quotation.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getQuotations();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getQuotations();
              }
            })
            .catch((err) => {
              console.log(err)
              Toast.fire({
                icon: "error",
                title: "Request failed ",
              });
            });
        }
      });
    },

    // dompdf

    pdf(quotation) {
      axios({
        url: "get-quotation",

        method: "GET",

        responseType: "blob",
        params: {
          id: quotation.id,
          gst_bill:quotation.gst_bill,

        },
      })
        .then((response) => {
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");

          fileLink.href = fileURL;
          fileLink.setAttribute("download", "quotation.pdf");
          document.body.appendChild(fileLink);

          fileLink.click();
        })
        .catch((err) => {});
    },
  },

  watch: {
    quotation_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.salesTable).DataTable({
          pageLength: 5,
          lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
          ],
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.salesTable).DataTable({
      pageLength: 5,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
    });
  },
};
</script>

<style>
</style>
