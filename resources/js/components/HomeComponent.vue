<template>
  <v-app>
    <v-navigation-drawer app>
      <v-toolbar flat class="blue">
        <v-list>
          <v-list-tile>
            <v-list-tile-title class="title white--text">Print Server</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-toolbar>
      <v-divider></v-divider>

      <v-list dense class="pt-0">
        <v-list-tile to="/profile">
          <v-list-tile-action>
            <v-icon>face</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Profil</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile to="/template">
          <v-list-tile-action>
            <v-icon>format_paint</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Template</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile to="/print">
          <v-list-tile-action>
            <v-icon>print</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Print</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile to="/print_transaction">
          <v-list-tile-action>
            <v-icon>print</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Print Transaction</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile to="/users" v-if="admin">
          <v-list-tile-action>
            <v-icon>account_circle</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Daftar Pengguna</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile to="/template_gallery">
          <v-list-tile-action>
            <v-icon>account_circle</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Template Gallery</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="logout()">
          <v-list-tile-action>
            <v-icon>power_settings_new</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Log Out</v-list-tile-title>
            <form id="logout-form" action="/logout" method="POST" style="display: none;">
              <input type="hidden" name="_token" :value="csrf">
            </form>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <v-toolbar app></v-toolbar>
    <v-content>
      <v-container fluid>
        <v-fade-transition mode="out-in">
          <router-view></router-view>
        </v-fade-transition>
      </v-container>
    </v-content>
    <v-footer app>tiketux.com</v-footer>
  </v-app>
</template>
<script>
export default {
  name: "HomeComponent",
  mounted() {
    this.list();
  },
  data() {
    return {
      navigation: "profile",
      admin: false,
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content")
    };
  },
  methods: {
    logout() {
      document.getElementById("logout-form").submit();
    },
    list() {
      var that = this;
      axios
        .get("api/getUser")
        .then(function(response) {
          that.admin = response.data.admin == 1;
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    }
  }
};
</script>
