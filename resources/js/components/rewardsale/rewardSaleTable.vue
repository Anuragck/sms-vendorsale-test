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
        <h5 class="m-0 font-weight-bold text-dark">Reward Sales</h5>

        <button
          type="button"
          class="btn btn-primary rounded-pill"
          data-toggle="modal"
          data-target="#addRewardSale"
          id="#myBtn"
          @click="addReward = true"
        >
          Add Reward Sale<i class="fas fa-plus-circle fa-fw"></i>
        </button>
      </div>

      <div class="table-responsive p-3">
        <table
          class="table table-sm align-items-center table-flush table-hover"
          id="rewardSaleTable"
          ref="rewardSaleTable"
        >
          <thead class="thead-light">
            <tr>
              <th>Index</th>
              <th>Name</th>
              <th>Product</th>
              <th>Redeemed Point</th>

              <th class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(rewardSale, index) in reward_sale_list"
              :key="rewardSale.id"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ rewardSale.customer["name"] }}</td>
              <td>{{ rewardSale.product["name"] }}</td>
              <td>{{ rewardSale.redeemed_points }}</td>

              <td class="pl-3">
                <button
                  class="btn btn-primary btn-sm"
                  data-toggle="modal"
                  data-target="#editRewardSale"
                  @click="editRewardSale(rewardSale)"
                >
                  <i class="fas fa-edit fafw" title="Edit"></i>
                </button>

                <button
                  class="btn btn-danger btn-sm ml-1"
                  @click="deleteRewardSale(rewardSale)"
                >
                  <i class="fas fa-trash fafw" title="Move to trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- addRewardSale modal  -->
    <div
      class="modal fade"
      id="addRewardSale"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      v-if="addReward"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <add-reward-sale></add-reward-sale>
        </div>
      </div>
    </div>
    <!-- addRewardSale modal -->

    <!-- edit addRewardSale modal  -->
    <div
      class="modal fade"
      id="editRewardSale"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <add-reward-sale :edit="true"></add-reward-sale>
        </div>
      </div>
    </div>
    <!-- edit addRewardSale modal -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      preLoader: false,
      addReward: false,
      reward_sale_list: {},

      search: false,
    };
  },

  created() {
    this.getRewardSale();
    var _this = this;
    bus.$on("reward-sale-added", function () {
      _this.getRewardSale();
    });
  },
  methods: {
    getRewardSale() {
      this.preLoader = true; //the loading begin
      axios
        .get("/admin/get-rewardsales")
        .then((response) => {
          this.reward_sale_list = response.data;
          this.preLoader = false;
        })
        .catch((err) => {
          this.preLoader = false;
          console.log(err);
        });
    },

    editRewardSale(rewardSale) {
      bus.$emit("edit-reward-sale", rewardSale);
    },

    deleteRewardSale(reward) {
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
            .post("/admin/delete-rewardsale", {
              id: reward.id,
            })
            .then((response) => {
              if (response.data == "success") {
                Toast.fire({
                  icon: "success",
                  title: " Deleted successfully ",
                });

                this.getRewardSale();
                bus.$emit("reward-sale-deleted");
              }

              if (response.data == "failed") {
                Toast.fire({
                  icon: "error",
                  title: "Some Error Occurred,Please Try Again Later",
                });

                this.getRewardSale();
                bus.$emit("reward-sale-deleted");
              }
            })
            .catch((err) => {
              Toast.fire({
                icon: "error",
                title: "Request failed ",
              });

              console.log(err);
            });
        }
      });
    },
  },

  watch: {
    reward_sale_list(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.rewardSaleTable).DataTable({
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
    this.dt = $(this.$refs.rewardSaleTable).DataTable({
      pageLength: 5,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
    });
  },
};
</script>

<style scoped>
</style>

