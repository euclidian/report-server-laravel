<template>
  <v-app>
    <app-drawer :user="user" :showDrawer="showDrawer"></app-drawer>
    <app-toolbar @side-icon-click="openNavDraw"></app-toolbar>
    <v-content>
      <!-- Page Header -->
      <div class="page-wrapper">
        <v-fade-transition mode="out-in">
          <router-view></router-view>
        </v-fade-transition>
      </div>
    </v-content>
  </v-app>
</template>

<script>
import AppDrawer from "./vue-admin/AppDrawer.vue";
import AppToolbar from "./vue-admin/AppToolbar.vue";

export default {
  components: {
    AppDrawer,
    AppToolbar
  },
  mounted() {
    var that = this;
    axios
      .get("api/user")
      .then(function(response) {
        that.user = response.data;
        console.log(response.data);
      })
      .catch(function(response) {
        console.log(response.data);
      });
  },
  data() {
    return {
      showDrawer: true,
      snackbar: {
        show: false,
        text: "Hay",
        color: ""
      },
      user: {
        admin: 0
      }
    };
  },
  created() {
    // add app events
  },
  methods: {
    openNavDraw() {
      this.showDrawer = !this.showDrawer;
    }
  }
};
</script>

<style scoped>
.page-wrapper {
  min-height: calc(100vh - 64px - 50px - 81px);
  padding: 50px;
}
</style>