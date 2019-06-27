<template>
  <div>
    <v-snackbar
      v-model="snackbar"
      :bottom="true"
      :right="true"
      :timeout="4000"
    >
      Password Diperbaharui
      <v-btn color="pink" flat @click="snackbar = false">Tutup</v-btn>
    </v-snackbar>
    <v-dialog v-model="editpass" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Ubah Password</span>
        </v-card-title>
        <v-card-text v-if="loading">
          <div class="text-xs-center">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </div>
        </v-card-text>
        <v-card-text v-else>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-text-field label="Password Lama*" v-model="password" type="password" required></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field label="Password Baru*" v-model="password1" type="password" required></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  label="Konfirmasi Password Baru*"
                  v-model="password2"
                  type="password"
                  required
                ></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
          <small v-if="errortxt!=null" class="red--text">*{{errortxt}}</small>
        </v-card-text>
        <v-card-actions v-if="!loading">
          <v-spacer></v-spacer>
          <v-btn color="error" flat @click="editpass = false">Batal</v-btn>
          <v-btn color="primary" flat @click="save">Simpan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-card class="mx-auto" color="grey lighten-4" max-width="800">
      <v-img
        :aspect-ratio="16/9"
        src="https://siva.jsstatic.com/id/73598/images/banner/73598_banner_0_321168.jpg"
      ></v-img>
      <v-card-text class="pt-4" style="position: relative;">
        <v-tooltip top>
          <template v-slot:activator="{ on }">
            <v-btn
              absolute
              color="orange"
              class="white--text"
              @click="editpass = true"
              v-on="on"
              fab
              large
              right
              top
            >
              <v-icon>vpn_key</v-icon>
            </v-btn>
          </template>
          <span>Ubah Password</span>
        </v-tooltip>
        <div class="font-weight-light grey--text title mb-2">{{email}}</div>
        <h3 class="display-1 font-weight-light orange--text mb-2">{{name}}</h3>
        <div class="font-weight-light title mb-2">Akun dibuat pada : {{created}}</div>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  name: "ProfileComponent",
  mounted() {
    this.list();
  },
  data() {
    return {
      email: null,
      name: null,
      created: null,
      editpass: false,
      errortxt: null,
      password: null,
      password1: null,
      password2: null,
      loading: false,
      snackbar:false
    };
  },
  methods: {
    list() {
      var that = this;
      axios
        .get("api/getUser")
        .then(function(response) {
          that.email = response.data.email;
          that.name = response.data.name;
          that.created = response.data.created_at;
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    save() {
      var that = this;
      if (
        that.password1 == null ||
        that.password2 == null ||
        that.password == null
      ) {
        that.errortxt = "Tidak boleh ada yang kosong";
      } else if (that.password1 == that.password2) {
        that.errortxt = null;
        that.loading = true;
        axios
          .post("api/updatePassword", {
            password: that.password,
            new_password: that.password2
          })
          .then(function(response) {
            that.loading = false;
            that.editpass = false;
            that.snackbar = true;
            console.log(response.data);
          })
          .catch(function(response) {
            console.log(response.data);
          });
      } else {
        that.errortxt = "Konfirmasi password tidak sama";
      }
    }
  }
};
</script>
