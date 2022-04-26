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
        <h5 class="m-0 font-weight-bold text-dark">Users</h5>

        <button
          type="button"
          class="btn btn-primary rounded-pill"
          data-toggle="modal"
          data-target="#addUser"
        >
          Add User<i class="fas fa-plus-circle fa-fw"></i>
        </button>
      </div>
      <div class="table-responsive p-3">
        <table
          class="table align-items-center table-flush table-hover"
          id="userTable"
          ref="userTable"
        >
          <thead class="thead-light">
            <tr>
              <th>Index</th>

              <th>Name</th>
              <th>username</th>
              <th >Mobile</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for=" (user , index ) in this.user_details" :key="user.id"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ user.name}}</td>
              <td>{{ user.username }}</td>
              <td >{{ user.mobile }}</td>

              <td>
                <button
                  type="button"
                  class="btn btn-sm btn-primary"
                  name=""
                  data-toggle="modal"
                  data-target="#editUser"
                  @click="editUser(user)"
                >
                  <i class="fas fa-edit fa-fw"></i>
                </button>

                <button
                  type="button"
                  class="btn btn-sm btn-danger"
                  name=""
                  @click="deleteUser(user)"
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
      id="addUser"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <add-user></add-user>
        </div>
      </div>
    </div>

    <!-- end modal -->

    <!-- edit modal  -->
    <div
      class="modal fade"
      id="editUser"
      data-backdrop="static"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <add-user :edit="true" ></add-user>
        </div>
      </div>
    </div>
    <!-- end modal -->
  </div>
</template>

<script>

export default {
  props: ["user"],

    data() {
    return {
      role_admin: 0,
      preLoader: false,
      user_details: {},
    };
  },
  created() {
this.getUsers();

 var _this = this;
    bus.$on("user-added", function () {
      _this.getUsers();
    });
  },

  methods: {


getUsers(){
this.preLoader=true;
axios.get('get-users').then((response) => {

  this.user_details=response.data;
  this.preLoader=false;
}).catch((err) => {
   console.log(err);
   this.preLoader=false;
});


},


editUser(user){
bus.$emit('edit-user',user)

},
deleteUser(user){
 Swal.fire({
        title: "Are you sure?",
        // text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Remove User!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("delete-user", {
id:user.id

          }).then((response) => {
            if (response.data == "success") {
              this.getUsers();
              Toast.fire({
                icon: "error",
                title: "user removed successfully",
              });
            }

            if (response.data == "failed") {
              Toast.fire({
                icon: "error",
                title: "Some Error Occurred,Please Try Again Later",
              });
              this.getUsers();
            }
          });
        }
      });

},
    },


  watch: {
    user_details(val) {
      this.dt.destroy();
      this.$nextTick(() => {
        this.dt = $(this.$refs.userTable).DataTable({
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
    this.dt = $(this.$refs.userTable).DataTable({
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
    });
  },
};
</script>
