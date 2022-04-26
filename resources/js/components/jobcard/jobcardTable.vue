<template>
<pre-loader v-if="preLoader"></pre-loader>
<div class="main" v-else>
    <!-- DataTable with Hover -->
    <div class="col-lg-12">
        <div class="card mb-4">


            <div class="row px-1">
                <div class="col-lg-9 col-md-9 py-2 ">
                    <div class="card-header py-3 ">
                        <h5 class=" font-weight-bold text-dark " style="display:inline-block;
 ">Jobcards</h5>
    <button class="btn rounded btn-sm shadow-sm  ml-2" @click="pendingCards()" :class="pending_jobcards ? 'btn-primary' : ''">
                        Pending Jobcards - Completion
                        <i class="fas fa-hourglass-half fa-fw"></i>
                    </button>

  <button class="btn rounded btn-sm shadow-sm ml-1" @click="deliveryCards()" :class="pending_delivery ? 'btn-primary' : ''">
                        Pending Delivery <i class="fas fa-truck-loading fa-fw"></i>
                    </button>
                    </div>
                </div>




                <div class="col-lg-3 col-md-3  py-3  ">
                    <button type="button" class="btn rounded-pill btn-primary float-right mr-3" data-toggle="modal" data-target="#addJobcard">
                        Add Jobcard <i class="fas fa-plus-circle fa-fw"></i>
                    </button>
                </div>
            </div>

            <div class="table-responsive p-3">
                <table class="table table-flush  display table-sm table-font" id="jobcardTable" ref="jobcardTable" data-order="">
                    <!--------------JOBCARD-TABLE-NEW-DESIGN--------------------->
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 5%;">Index</th>
                            <th style="width: 60%;">Jobcard Details</th>
                            <th style="width: 35%;" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="">
                        <tr v-for="(jobcard, index) in jobcardsData" :key="jobcard.id">

                            <td>{{ index + 1 }}</td>

                            <td class="newspaper">

                                <table class='table borderless my-2'>

                                    <tbody>
                                        <tr>
                                            <td>
                                                Date:
                                            </td>
                                            <td align="left">
                                                <b>{{ jobcard.created_date | myDate }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Jobcard No:
                                            </td>
                                            <td align="left">
                                                <b>{{ jobcard.id  }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Customer Name:
                                            </td>
                                            <td align="left">
                                                <b>{{ jobcard.customer.name }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Vehicle:
                                            </td>
                                            <td align="left" v-if="jobcard.vehicle == null">
                                                <em>{{ 'Nil' }}</em>
                                            </td>
                                            <td align="left" v-else>
                                                <b>{{ jobcard.vehicle }}</b>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                Register No:
                                            </td>
                                            <td align="left">
                                                <b>{{ jobcard.register_no }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Job Advisor
                                            </td>
                                            <td align="left">
                                                <b>{{ jobcard.advisor.name }}</b>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            <td class="text-center v-center" v-if="pending_jobcards == true">
                                <button class="btn btn-warning btn-xs mt-1" data-toggle="modal" data-target="#editJobcard" @click="editJobcard(jobcard)" :disabled="jobcard.gst_bill == 1 && authUser == 0">
                                    <i class="fas fa-edit" title="Edit"></i>
                                </button>

                                <button class="btn btn-primary btn-xs mt-1" data-toggle="modal" data-target="#addjobcardpayment" @click="addJobcardPayment(jobcard)">
                                    <i class="fas fa-hand-holding-usd" title="add payment"></i>
                                </button>

                                <button class="btn btn-info btn-xs mt-1" data-toggle="modal" data-target="#printJobcard" @click="printJobcard(jobcard)">
                                    <i class="fas fa-print" title="print jobcard"></i>
                                </button>

                                <button class="btn btn-success btn-xs mt-1" @click="jobcardCompleted(jobcard)">
                                    <i class="fas fa-check" title="completed"></i>
                                </button>

                                <button class="btn btn-danger btn-xs mt-1" @click="deleteJobcard(jobcard)">
                                    <i class="fas fa-trash" title="Move to trash"></i>
                                </button>
                            </td>

                            <td v-if="pending_delivery == true" class="text-center">
                                <button class="btn btn-success btn-xs mt-1" @click="jobcardDelivered(jobcard)">
                                    <i class="fas fa-check" title="delevered"></i>
                                </button>

                                <button class="btn btn-warning btn-xs mt-1" data-toggle="modal" data-target="#editJobcard" @click="viewJobcard(jobcard)">
                                    <i class="fa fa-eye fa-fw" title="view"></i>
                                </button>
                                <button class="btn btn-info btn-xs mt-1" data-toggle="modal" data-target="#printJobcard" @click="printJobcard(jobcard)">
                                    <i class="fas fa-print" title="print jobcard"></i>
                                </button>

                                <button class="btn btn-primary btn-xs mt-1" data-toggle="modal" data-target="#addjobcardpayment" @click="addJobcardPayment(jobcard)">
                                    <i class="fas fa-hand-holding-usd" title="add payment"></i>
                                </button>

                                <button v-if="role_admin == 1" class="btn btn-danger btn-xs mt-1" @click="backToList(jobcard)">
                                    <i class="fas fa-history" title="back to list"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>

                    <!---------------JOBCARD-TABLE-NEW-DESIGN-END--------------->
                    <!--   <thead class="thead-light">
              <tr>
                <th style="width: px">#</th>
                <th style="width: 120px">Date</th>
                <th style="width: px">Jobcard No</th>
                <th style="width: px">Name</th>
                <th style="width: px">Vehicle</th>
                <th style="width: px">Reg No</th>
                <th style="width: 244px" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(jobcard, index) in jobcardsData" :key="jobcard.id">
                <td>{{ index + 1 }}</td>
                <td>{{ jobcard.created_date | myDate }}</td>
                <td>{{ jobcard.id }}</td>
                <td>{{ jobcard.customer.name }}</td>
                <td>{{ jobcard.vehicle }}</td>
                <td>{{ jobcard.register_no }}</td>

                <td class="text-center" v-if="pending_jobcards == true">
                  <button
                    class="btn btn-warning btn-xs"
                    data-toggle="modal"
                    data-target="#editJobcard"
                    @click="editJobcard(jobcard)"
                    :disabled="jobcard.gst_bill == 1 && authUser == 0"
                  >
                    <i class="fas fa-edit" title="Edit"></i>
                  </button>

                  <button
                    class="btn btn-primary btn-xs"
                    data-toggle="modal"
                    data-target="#addjobcardpayment"
                    @click="addJobcardPayment(jobcard)"
                  >
                    <i class="fas fa-hand-holding-usd" title="add payment"></i>
                  </button>

                  <button
                    class="btn btn-info btn-xs"
                    data-toggle="modal"
                    data-target="#printJobcard"
                    @click="printJobcard(jobcard)"
                  >
                    <i class="fas fa-print" title="print jobcard"></i>
                  </button>

                  <button
                    class="btn btn-success btn-xs"
                    @click="jobcardCompleted(jobcard)"
                  >
                    <i class="fas fa-check" title="completed"></i>
                  </button>

                  <button
                    class="btn btn-danger btn-xs"
                    @click="deleteJobcard(jobcard)"
                  >
                    <i class="fas fa-trash" title="Move to trash"></i>
                  </button>
                </td>

                <td v-if="pending_delivery == true" class="text-center">
                  <button
                    class="btn btn-success btn-xs"
                    @click="jobcardDelivered(jobcard)"
                  >
                    <i class="fas fa-check" title="delevered"></i>
                  </button>

                  <button
                    class="btn btn-warning btn-xs"
                    data-toggle="modal"
                    data-target="#editJobcard"
                    @click="viewJobcard(jobcard)"
                  >
                    <i class="fa fa-eye fa-fw" title="view"></i>
                  </button>
                  <button
                    class="btn btn-info btn-xs"
                    data-toggle="modal"
                    data-target="#printJobcard"
                    @click="printJobcard(jobcard)"
                  >
                    <i class="fas fa-print" title="print jobcard"></i>
                  </button>

                  <button
                    class="btn btn-primary btn-xs"
                    data-toggle="modal"
                    data-target="#addjobcardpayment"
                    @click="addJobcardPayment(jobcard)"
                  >
                    <i class="fas fa-hand-holding-usd" title="add payment"></i>
                  </button>

                  <button
                    v-if="role_admin == 1"
                    class="btn btn-danger btn-xs"
                    @click="backToList(jobcard)"
                  >
                    <i class="fas fa-history" title="back to list"></i>
                  </button>
                </td>
              </tr>
            </tbody>

-->
                </table>
            </div>
        </div>

        <div id="addJobcard" class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <add-jobcard :usergst="this.user.gst"></add-jobcard>
                </div>
            </div>
        </div>

        <div id="editJobcard" class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <add-jobcard :usergst="this.user.gst" :edit="true" :viewOnly="this.viewonly"></add-jobcard>
                </div>
            </div>
        </div>

        <!-- payment  -->

        <div id="addjobcardpayment" class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <add-jobcard-payment></add-jobcard-payment>
                </div>
            </div>
        </div>
        <!-- payment end -->

        <!-- print-jobcard  -->

        <div id="printJobcard" class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <jobcard-print></jobcard-print>
                </div>
            </div>
        </div>
        <!-- payment end -->
    </div>
</div>
</template>

<script>

export default {

  props: ["user"],
  data() {
    return {
      preLoader: false,
      pending_jobcards: true,
      pending_delivery: false,
      jobcard_status: "0",
      authUser: 0,
      role_admin: 0,
      viewonly: false,
      jobcardsData: {},
    };
  },

  methods: {
    getJobcards() {
      if (this.pending_jobcards == true) {
        this.jobcard_status = "0";
      } else {
        this.jobcard_status = "1";
      }
      this.preLoader = true; //the loading begin
      axios
        .get("/admin/get-jobcards", {
          params: {
            jobcard_status: this.jobcard_status,
          },
        })
        .then((response) => {
          this.jobcardsData = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

    pendingCards() {
      this.pending_jobcards = true;
      this.pending_delivery = false;
      this.getJobcards();
    },

    deliveryCards() {
      this.pending_jobcards = false;
      this.pending_delivery = true;
      this.getJobcards();
    },

    editJobcard(jobcard) {
      this.viewonly = false;
      bus.$emit("edit-jobcard", jobcard);
    },

    viewJobcard(jobcard) {
      this.viewonly = true;
      bus.$emit("edit-jobcard", jobcard);
    },
    addJobcardPayment(jobcard) {
      bus.$emit("add-jobcard-payment", jobcard);
    },

    printJobcard(jobcard) {
      bus.$emit("print-jobcard", jobcard);
    },

    jobcardCompleted(jobcard) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Completed!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("/admin/mark_jobcardcompleted", {
              id: jobcard.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Updated successfully ",
                });

                this.getJobcards();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getJobcards();
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

    jobcardDelivered(jobcard) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Delivered!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("/admin/mark_jobcarddelivered", {
              id: jobcard.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Updated successfully ",
                });

                this.getJobcards();
              }
              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getJobcards();
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

    deleteJobcard(jobcard) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Delete!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("/admin/delete-jobcard", {
              id: jobcard.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getJobcards();
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getJobcards();
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
    backToList(jobcard) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Confirm!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("/admin/back_to_list", {
              id: jobcard.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Moved successfully ",
                });

                this.getJobcards();
              }
              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getJobcards();
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
  },

  created() {
    this.getJobcards();
    this.authUser = this.user.gst;
    this.role_admin = this.user.role;
    var _this = this;
    bus.$on("jobcard-added", function () {
      _this.getJobcards();
    });

    bus.$on("jobcard-payment-added", function () {
      _this.getJobcards();
    });
  },

  watch: {
    jobcardsData(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.jobcardTable).DataTable({
          pageLength: 3,
          lengthMenu: [
            [3,5,10, 25, 50, -1],
            [3,5,10, 25, 50, "All"],
          ],
          bSort: false,
        });
      });
    },
  },

  mounted() {
    this.dt = $(this.$refs.jobcardTable).DataTable({
      pageLength: 3,
          lengthMenu: [
            [3,5,10, 25, 50, -1],
            [3,5,10, 25, 50, "All"],
          ],
      bSort: false,
    });
  },
};

</script>


<style scoped>
.btn-group-xs>.btn,
.btn-xs {
    padding: 0.25rem 0.4rem;
    font-size: 0.875rem;
    line-height: 0.5;
    border-radius: 0.2rem;
}

.table-font {
    font-size: 14px;
}

.newspaper {
    -webkit-column-gap: 40px;
    /* Chrome, Safari, Opera */
    -moz-column-gap: 40px;
    /* Firefox */
    column-gap: 40px;
}

.borderless td,
.borderless th {
    border: none;
    padding: 0px;
    margin: 0px;
    text-align: left;
    width: 60%;
}
</style>
