<template>
  <v-app>
    <v-btn @click="adduser = true" fixed dark fab bottom right color="blue">
      <v-icon>add</v-icon>
    </v-btn>
    <v-snackbar v-model="snackbar" :bottom="true" :right="true" :timeout="4000">
      Data Pengguna Diperbaharui
      <v-btn color="pink" flat @click="snackbar = false">Tutup</v-btn>
    </v-snackbar>
    <v-dialog v-model="adduser" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Tambah Pengguna</span>
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
                <v-text-field label="Nama Pengguna*" v-model="name" type="text" required></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field label="Email Pengguna*" v-model="email" type="email" required></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field label="Password*" v-model="password1" type="password" required></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  label="Konfirmasi Password*"
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
          <v-btn color="error" flat @click="adduser = false">Batal</v-btn>
          <v-btn
            color="primary"
            flat
            @click="save"
            v-if="password1 != null &&
        password2 != null &&
        name != null &&
        email != null"
          >Simpan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title primary-title>
        <div>
          <h3 class="headline mb-0">Daftar Pengguna</h3>
        </div>
      </v-card-title>
      <v-data-table
        :rows-per-page-items="rowsPerPageItems"
        :pagination.sync="pagination"
        :headers="headers"
        :items="users"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td>{{ props.item.name }}</td>
          <td>{{ props.item.email }}</td>
          <td>{{ props.item.secretid }}</td>
          <td>{{ props.item.secret }}</td>
          <td v-if="props.item.id==saya.id">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="primary" v-on="on">
                  <v-icon dark>accessibility</v-icon>
                </v-btn>
              </template>
              <span>Saya</span>
            </v-tooltip>
          </td>
          <td v-else-if="props.item.admin==1">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="primary" @click="toAdmin(props.item.id)" v-on="on">
                  <v-icon dark>done</v-icon>
                </v-btn>
              </template>
              <span>Admin</span>
            </v-tooltip>
          </td>
          <td v-else>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="error" @click="toAdmin(props.item.id)" v-on="on">
                  <v-icon dark>close</v-icon>
                </v-btn>
              </template>
              <span>Bukan Admin</span>
            </v-tooltip>
          </td>
          <td>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn
                  fab
                  dark
                  small
                  color="orange"
                  @click="generateToken(props.item.id)"
                  v-on="on"
                >
                  <v-icon dark>vpn_key</v-icon>
                </v-btn>
              </template>
              <span>Generate Token</span>
            </v-tooltip>
          </td>
        </template>
      </v-data-table>
    </v-card>
  </v-app>
</template>

<script>
export default {
  name: "UserComponent",
  mounted() {
    this.list();
  },
  data() {
    return {
      headers: [
        { text: "Nama Pengguna", value: "name" },
        { text: "Email", value: "email" },
        { text: "Secret ID", value: "secretid" },
        { text: "Secret", value: "secret" },
        { value: "admin" },
        { value: "id" }
      ],
      rowsPerPageItems: [25, 50, 75, 100],
      pagination: {
        rowsPerPage: 25
      },
      saya: null,
      users: [],
      email: null,
      name: null,
      adduser: false,
      errortxt: null,
      password1: null,
      password2: null,
      loading: false,
      snackbar: false
    };
  },
  methods: {
    generateToken(id) {
      var that = this;
      axios
        .get("api/generateToken/" + id)
        .then(function(response) {
          that.list();
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    toAdmin(id) {
      var that = this;
      axios
        .get("api/toAdmin/" + id)
        .then(function(response) {
          that.list();
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    list() {
      var that = this;
      axios
        .get("api/allUser")
        .then(function(response) {
          that.users = response.data.data;
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
      axios
        .get("api/getUser")
        .then(function(response) {
          that.saya = response.data;
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    save() {
      var that = this;
      if (that.password1 == that.password2) {
        that.errortxt = null;
        that.loading = true;
        axios
          .post("api/addUser", {
            name: that.name,
            email: that.email,
            password: that.password2
          })
          .then(function(response) {
            that.loading = false;
            that.adduser = false;
            that.snackbar = true;
            that.email = null;
            that.name = null;
            that.errortxt = null;
            that.password1 = null;
            that.password2 = null;
            console.log(response.data);
          })
          .catch(function(response) {
            console.log(response.data);
            that.errortxt = "Terjadi konflik";
            that.loading = false;
          });
      } else {
        that.errortxt = "Konfirmasi password tidak sama";
      }
    }
  }
};
</script>
