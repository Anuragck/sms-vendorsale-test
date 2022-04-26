<template>
  <!-- DataTable with Hover -->
  <pre-loader v-if="preLoader"></pre-loader>
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
        <h5 class="m-0 font-weight-bold text-dark">Closing Report</h5>
        <!--FilterButton-->

        <div class="col-md-2 ml-auto">
          <button
            class="btn btn-primary btn-sm float-right rounded-pill"
            @click="viewFilters()"
            v-if="search == false"
          >
            Add Filters<i class="fas fa-filter fa-fw"></i>
          </button>
          <button
            class="btn btn-outline-warning btn-sm float-right rounded-pill"
            @click="hideFilter()"
            v-if="search"
          >
            Clear Filter <i class="far fa-times-circle fa-fw"></i>
          </button>
        </div>
        <!--FilterButton end-->
      </div>
      <div class="" v-if="search">
        <div class="row justify-content-center">
          <div class="" style="">
            <div class="input-group">
              <span class="input-group-text">From</span>
              <input type="Date" class="form-control" v-model="from_date" />
              <span
                class="input-group-text ml-4 px-3"
                style="border-left: 0; border-right: 0"
              >
                To
              </span>
              <input type="Date" class="form-control" v-model="to_date" />
              <button class="btn btn-sm btn-primary ml-4" @click="addFilters()">
                Search <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-------------------------->

 <div class="table-responsive p-3">
        <table
          class="table table-sm align-items-center table-flush table-hover"
          id="closingTable"
          ref="closingTable"
        >
          <thead class="thead-light">
            <tr>
              <th>Index</th>
              <th>Closing Date</th>
              <th>Opening Balance</th>
              <th>Closing Balance</th>
 <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(close, index) in closing_list" :key="close.id">
              <td class="pt-3">{{ index + 1 }}</td>
              <td class="pt-3">{{ close.closing_date | myDate }}</td>
              <td><span>Cash: {{ close.opening_cash }}</span><br>
              <span>Bank: {{ close.opening_bank }}</span></td>
              <td><span>Cash: {{ close.closing_cash }}</span><br>
              <span>Bank: {{ close.closing_bank }}</span></td>


              <td class="" >
                <button
                  class="btn btn-primary btn-sm"
                  data-toggle="modal"
                  data-target="#viewClosing"
                 @click="viewClosing(close)"
                >
                  <i class="fas fa-eye fa-fw" title="view"></i>
                </button>
              </td>


            </tr>
          </tbody>
        </table>
      </div>
    </div>


  <!-- editIncomes modal -->
    <div
      class="modal fade"
      id="viewClosing"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
<view-closing></view-closing>
        </div>
      </div>
    </div>
    <!-- editIncomes modal -->

  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      preLoader: false,
      from_date: "",
      to_date: "",
      search: false,
closing_list:{},
    };
  },

  created() {
this.getClosings();

  },
  methods: {

 getClosings() {
      this.preLoader = true;
      axios
        .get("get-all-closings", {
          params: {
            from_date: this.from_date,
            to_date: this.to_date,
          },
        })
        .then((response) => {
          this.closing_list = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

 viewClosing(close) {
      bus.$emit("view-closings", close);
    },


    hideFilter() {
 this.getClosings();
      this.search = false;
      this.from_date = "";
      this.to_date = "";
    },

    viewFilters() {
      this.search = true;
    },
    addFilters() {
 this.getClosings();
      this.from_date = "";
      this.to_date = "";
    },


  },

  watch: {
    closing_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.closingTable).DataTable({
          pageLength: 5,

          dom: "Bfrtip",

          buttons: [
            {
              extend: "copy",
              title: "Closing Report",
              exportOptions: {
                columns: ":visible:not(:eq(4))",
              },
            },
            {
              extend: "excel",
              title: "Closing Report",
              exportOptions: {
                columns: ":visible:not(:eq(4))",
              },
            },
            {
              extend: "pdf",
              title: "Closing Report",
              exportOptions: {
                columns: ":visible:not(:eq(4))",
              },
            },
            {
              extend: "print",
              title: "Closing Report",
              exportOptions: {
                columns: ":visible:not(:eq(4))",
              },
            },
          ],
          lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
          ],
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.closingTable).DataTable({
      pageLength: 5,

      dom: "Bfrtip",

      buttons: [
        {
          extend: "copy",
          title: "Closing Report",
          exportOptions: {
            columns: ":visible:not(:eq(4))",
          },
        },
        {
          extend: "excel",
          title: "Closing Report",
          exportOptions: {
            columns: ":visible:not(:eq(4))",
          },
        },
        {
          extend: "pdf",
          title: "Closing Report",
          exportOptions: {
            columns: ":visible:not(:eq(4))",
          },
        },
        {
          extend: "print",
          title: "Closing Report",
          exportOptions: {
            columns: ":visible:not(:eq(4))",
          },
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

<style scoped>




.borderless td,
.borderless th {
    border: none;
    padding: 0px;
    margin: 0px;
    text-align: left;
    width: 60%;
}
</style>
