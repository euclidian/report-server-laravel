<template>
  <v-app>
    <v-btn @click="addtemplate = true" fixed dark fab bottom right color="primary">
      <v-icon>add</v-icon>
    </v-btn>
    <v-snackbar v-model="snackbar" :bottom="true" :right="true" :timeout="4000">
      Data Template Diperbaharui
      <v-btn color="pink" flat @click="snackbar = false">Tutup</v-btn>
    </v-snackbar>
    <v-dialog v-model="isDelete" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">Apakah anda yakin?</v-card-title>
        <v-card-text>Data yang terhapus tidak akan dikembalikan.</v-card-text>
        <v-card-text v-if="loading">
          <div class="text-xs-center">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </div>
        </v-card-text>
        <v-card-actions v-if="!loading">
          <v-spacer></v-spacer>
          <v-btn color="error" flat @click="isDelete = false">Batal</v-btn>
          <v-btn color="error" @click="deleted">Hapus</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="addtemplate" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Tambah Template</span>
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
                <input
                  ref="inputUpload"
                  type="file"
                  accept=".jrxml, .jasper"
                  @change="handleFileUpload"
                />
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions v-if="!loading">
          <v-spacer></v-spacer>
          <v-btn color="error" flat @click="addtemplate = false">Batal</v-btn>
          <v-btn color="primary" flat @click="save" v-if="TemplateFile != null ">Simpan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <h3 class="headline mb-10 primary--text">Daftar Template</h3>
    <v-card>
      <v-data-table
        :rows-per-page-items="rowsPerPageItems"
        :pagination.sync="pagination"
        :headers="headers"
        :items="templates"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td>{{ props.item.id }}</td>
          <td>{{ props.item.filename }}</td>
          <td>{{ props.item.realfilename }}</td>
          <td>{{ props.item.created_at }}</td>
          <td>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn
                  fab
                  dark
                  flat
                  small
                  color="error"
                  @click="showDelete(props.item.id)"
                  v-on="on"
                >
                  <v-icon dark>delete</v-icon>
                </v-btn>
              </template>
              <span>Hapus Template</span>
            </v-tooltip>
          </td>
        </template>
      </v-data-table>
    </v-card>
  </v-app>
</template>

<script>
export default {
  name: "TemplateComponent",
  mounted() {
    this.list();
  },
  data() {
    return {
      headers: [
        { text: "ID Template", value: "id" },
        { text: "Nama File", value: "filename" },
        { text: "Nama Asli File", value: "realfilename" },
        { text: "Tanggal Upload", value: "created_at" },
        { text: "Action",sortable:false, value: "id" }
      ],
      rowsPerPageItems: [25, 50, 75, 100],
      pagination: {
        rowsPerPage: 25
      },
      addtemplate: false,
      templates: [],
      IdTemplate: null,
      isDelete: false,
      loading: false,
      snackbar: false,
      TemplateFile: null
    };
  },
  methods: {
    showDelete(id) {
      this.IdTemplate = id;
      this.isDelete = true;
    },
    handleFileUpload(e) {
      this.TemplateFile = e.target.files[0];
      console.log(this.ImageFile);
    },
    list() {
      var that = this;
      axios
        .get("api/allJRXML")
        .then(function(response) {
          that.templates = response.data.data;
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    save() {
      this.loading = true;
      let formData = new FormData();
      var that = this;
      that.isLoading = true;
      formData.append("template", this.TemplateFile);
      axios
        .post("api/addJRXML", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(function() {
          that.addtemplate = false;
          that.snackbar = true;
          that.loading = false;
          that.list();
        })
        .catch(function(error) {
          console.log(error.response.data);
          that.addtemplate = false;
        });
    },
    deleted() {
      var that = this;
      axios
        .get("api/deleteJRXML/" + this.IdTemplate)
        .then(function() {
          that.isDelete = false;
          that.snackbar = true;
          that.loading = false;
          that.list();
        })
        .catch(function(error) {
          console.log(error.response.data);
          that.addtemplate = false;
        });
    }
  }
};
</script>
