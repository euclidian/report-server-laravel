<template>
  <v-app>
    <v-flex xs4 offset-xs4 style="padding:200px 0">
      <v-card>
        <v-card-title primary-title>
          <div>
            <h3 class="headline mb-0">Print Server</h3>
          </div>
        </v-card-title>
        <v-form>
          <v-container>
            <v-flex xs10 offset-xs1>
              <v-layout>
                <v-text-field v-model="email" :error-messages="erremail" label="Email" required></v-text-field>
              </v-layout>
              <v-layout>
                <v-text-field
                  type="password"
                  :error-messages="errpassword"
                  v-model="password"
                  label="Password"
                  required
                ></v-text-field>
              </v-layout>
              <v-btn color="info" @click="login">Login</v-btn>
            </v-flex>
          </v-container>
        </v-form>
      </v-card>
    </v-flex>
    <v-dialog v-model="error" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">Peringatan</v-card-title>
        <v-card-text>{{kesalahan}}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" class="white--text" @click="error = false">Oke</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>
<script>
export default {
  name: "LoginComponent",
  data() {
    return {
      error: false,
      email: null,
      password: null,
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      kesalahan: null,
      erremail: null,
      errpassword: null
    };
  },
  methods: {
    login() {
      var that = this;
      axios
        .post("login", {
          email: that.email,
          password: that.password,
          _token: that.csrf
        })
        .then(function(response) {
          console.log(response.data);
          window.location.replace("/home");
        })
        .catch(function(response) {
          that.kesalahan = "Username atau Password salah.";
          that.error = true;
        });
    }
  }
};
</script>