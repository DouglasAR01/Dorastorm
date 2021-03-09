<template>
  <div class="container rounded bg-white p-2">
    <div class="row justify-content-center">
      <h3>Users list</h3>
    </div>
    <div v-if="loading">Loading...</div>
    <div class="table-responsive" v-else>
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.role.name }}</td>
            <td>
              <router-link
                :to="{
                  name: 'users-read',
                  query: { user_id: user.id },
                }"
                data-toggle="tooltip"
                data-placement="top"
                title="View user data"
                class="btn btn-link btn-sm"
              >
                <i class="far fa-eye"></i>
              </router-link>
              <router-link
                :to="{
                  name: 'users-update',
                  query: { user_id: user.id },
                }"
                data-toggle="tooltip"
                data-placement="top"
                title="Update user data"
                class="btn btn-link btn-sm"
                v-if="userCanUpdate"
              >
                <i class="fas fa-pen"></i>
              </router-link>
              <button
                class="btn btn-link btn-sm"
                @click.prevent="deleteUser(user.id)"
                :disabled="deleting"
                v-if="userCanDelete"
              >
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <confirm-dialogue ref="confirmDialogue"></confirm-dialogue>
    </div>
  </div>
</template>
<script>
import ConfirmDialogue from "../../shared/components/ConfirmDialogue";
import { is404, is406 } from "../../shared/utils/responses";
import Permissions from "../../services/role-permissions";
export default {
  components: {
    ConfirmDialogue,
  },
  data() {
    return {
      loading: false,
      deleting: false,
      users: null,
    };
  },
  async created() {
    try {
      this.loading = true;
      this.users = (await axios.get("/api/users")).data.data;
      this.loading = false;
    } catch (error) {
      this.$toast.error("Something went wrong. Try again later.");
    }
  },
  methods: {
    async deleteUser(user_id) {
      const ok = await this.$refs.confirmDialogue.show({
        title: "Delete User",
        message:
          "Are you sure you want to delete this user? It cannot be undone.",
        okButton: "Delete Forever",
      });
      // If you throw an error, the method will terminate here unless you surround it wil try/catch
      if (ok) {
        this.deleting = true;
        try {
          await axios.delete("/api/users/" + user_id);
          this.loading = false;
          this.users = this.users.filter((user) => user.id != user_id);
          this.$toasts.success("The user has been deleted.");
        } catch (error) {
          if (is404(error)) {
            this.$toasts.error("We couldn't delete the user.");
          }
          if (is406(error)) {
            this.$toasts.error("You are the last admin left!");
          }
        }
        this.deleting = false;
      }
    },
  },
  computed: {
    userCanUpdate() {
      return Permissions.checkUserPermission(this.$store.state.user, Permissions.core.UPDATE_USERS);
    },
    userCanDelete() {
      return Permissions.checkUserPermission(this.$store.state.user, Permissions.core.DELETE_USERS);
    }
  }
};
</script>