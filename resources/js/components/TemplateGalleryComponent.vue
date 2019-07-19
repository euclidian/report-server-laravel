<template>
  <v-app>
    <v-btn @click="addtemplate = true" fixed dark fab bottom right color="primary">
      <v-icon>add</v-icon>
    </v-btn>
    <v-snackbar v-model="snackbar" :bottom="true" :right="true" :timeout="4000">
      Data Template Diperbaharui
      <v-btn color="pink" flat @click="snackbar = false">Tutup</v-btn>
    </v-snackbar>
    <v-snackbar v-model="alertcopy" :bottom="true" :right="true" :timeout="4000">
      Data Template Berhasil di Copy
      <v-btn color="pink" flat @click="alertcopy = false">Tutup</v-btn>
    </v-snackbar>
    <v-snackbar v-model="alertcopygagal" :bottom="true" :right="true" :timeout="4000">
      Data Template Gagal di Copy
      <v-btn color="pink" flat @click="alertcopy = false">Tutup</v-btn>
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
                <label>Upload JRXML</label>
                <input
                  ref="inputUpload"
                  type="file"
                  accept=".jrxml, .jasper"
                  @change="handleFileUpload"
                >
              </v-flex>
              <v-flex xs12>
                <label>Upload Gambar</label>
                <input
                  ref="inputUpload"
                  type="file"
                  accept=".jpg, .png"
                  @change="galleryFileUpload"
                >
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
    <v-card v-if="preview==false">
      <v-card-title primary-title>
        <v-flex xs8>
          <div>
            <h3 class="headline mb-0">Template Gallery</h3>
          </div>
        </v-flex>
        <v-flex xs4>
        </v-flex>
        <v-flex xs12>
          <!-- SEARCH BAR -->
            <v-text-field
              v-model="pencarian"
              @keyup="updateData();"
              label="Pencarian Nama File"
              append-icon="search"
            ></v-text-field>
        </v-flex>
      </v-card-title>
      <v-container grid-list-md text-xs-center>
        <v-layout row wrap>
          <v-flex v-for="i in templates" v-bind:key="i.id" xs3>
              <v-img
                    :src="i.preview"
                    aspect-ratio="1.7"
                ></v-img>
              <v-card-text class="px-0">{{ i.filename }}</v-card-text>
              <v-btn depressed small @click="copyfile(i.id)">Copy</v-btn>
              <v-btn depressed small  @click="showDelete(i.id)" v-if="admin"><v-icon color="error">delete</v-icon>Hapus</v-btn>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
    <v-container grid-list-md v-if="preview==true">
      <v-layout row wrap>
        <v-flex xs8>
          <v-card>
            <v-card-title primary-title>
              <v-flex xs8>
                <div>
                  <h3 class="headline mb-0">Template Gallery</h3>
                </div>
              </v-flex>
              <v-flex xs4>
              </v-flex>
            </v-card-title>
            <v-container grid-list-md text-xs-center>
              <v-layout row wrap>
                <v-flex v-for="i in templates" v-bind:key="i.id" xs2>
                    <v-img
                    :src="detail.preview"
                    gradient="to top right, rgba(100,115,201,.33), rgba(25,32,72,.7)"
                    v-if = "gambar != null"
                    ></v-img>
                    <v-card-text class="px-0">{{ i.filename }}</v-card-text>
                    <v-btn depressed small @click="copyfile(i.id)">Copy</v-btn>
                    <v-btn fab dark small color="error" @click="showDelete(i.id)" v-on="on" v-if="admin">
                      <v-icon dark>delete</v-icon>
                    </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
        <v-flex xs4>
          <v-card>
            <v-card-title primary-title>
              <v-flex xs8>
                <div>
                  <h3 class="headline mb-0">{{ detail.filename }}</h3>
                </div>
              </v-flex>
              <v-flex xs4>
              </v-flex>
            </v-card-title>
              <br>
              <v-flex xs12>
                <v-img
                    :src="detail.preview"
                    gradient="to top right, rgba(100,115,201,.33), rgba(25,32,72,.7)"
                    v-if = "gambar != null"
                ></v-img>
                <v-card-text class="px-0">{{ detail.filename }}</v-card-text>
              </v-flex>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<script>
export default {
  name: "TemplateGalleryComponent",
  mounted() {
    this.list();
    this.cekAdmin();
  },
  data() {
    return {
      headers: [
        { text: "ID Template", value: "id" },
        { text: "Nama File", value: "filename" },
        { text: "Nama Asli File", value: "realfilename" },
        { text: "Tanggal Upload", value: "created_at" },
        { text: "Action", value: "id" }
      ],
      rowsPerPageItems: [25, 50, 75, 100],
      pagination: {
        rowsPerPage: 25
      },
      addtemplate: false,
      templates: [],
      IdTemplate:null,
      isDelete:false,
      loading: false,
      snackbar: false,
      alertcopy: false,
      alertcopygagal: false,
      TemplateFile: null,
      admin: false,
      preview: false,
      detail: [],
      gambar: null,
      filename:  '',
      id:null,
      pencarian: '',
      filtro: {
        menu: false,
        filtri: {
          categorie: [0, 1, 2],
          ddt: '',
          lunghezza: [0, 12000]
        }
      },
    };
  },
  methods: {
    showDelete(id){
      this.IdTemplate = id;
      this.isDelete = true;
    },
    handleFileUpload(e) {
      this.TemplateFile = e.target.files[0];
      console.log(this.ImageFile);
    },
    galleryFileUpload(e) {
      this.PreviewFile = e.target.files[0];
      console.log(this.ImageFile);
    },
    list() {
      var that = this;
      axios
        .get("api/allJRXMLGallery")
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
      formData.append("preview", this.PreviewFile);
      axios
        .post("api/addJRXMLGallery", formData, {
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
    deleted(){
      var that = this;
      axios
        .get("api/deleteJRXMLGallery/"+this.IdTemplate)
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
    },
    updateData(){
      var that = this;
      axios
        .post("api/searchJRXMLGallery",{
          'search' : that.pencarian
        })
        .then(function(response) {
          that.templates = response.data.data;
          console.log(response.data);
        })
        .catch(function(error) {
          console.log(error.response.data);
          that.addtemplate = false;
          that.list();
        });
    },
    cekAdmin() {
      var that = this;
      axios
        .get("api/getUser")
        .then(function(response) {
          that.admin = response.data.admin == 1;
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    preview1(id){
      var that = this;
      this.IdTemplate = id;
      that.preview = true;
      axios
        .get("api/detailJRXMLGallery/"+this.IdTemplate)
        .then(function(response) {
          that.detail = response.data.data;
          that.gambar = response.data.data.preview;
          console.log(response.data.data.id);
        })
        .catch(function(error) {
          console.log(error.response.data);
          that.addtemplate = false;
        });
    },
    copyfile(id){
      var that = this;
      this.IdTemplate = id;
      axios
        .get("api/detailJRXMLGallery/"+this.IdTemplate)
        .then(function(response) {
          that.filename = response.data.data.filename;
          that.realfilename = response.data.data.realfilename;
          console.log(response.data.data.filename);
          that.copy();
        })
        .catch(function(error) {
          console.log(error.response.data);
          that.addtemplate = false;
        });
    },
    copy(){
      var that = this;
      axios
        .post("api/copyJRXMLGallery",{
          'filename' : that.filename,
          'realfilename' : that.realfilename
        })
        .then(function(response) {
          that.alertcopy = true;
          console.log(response.data.data);
          that.list();
        })
        .catch(function(error) {
          that.alertcopygagal = true;
          console.log(error.response.data);
          that.addtemplate = false;
        });
    }
  }
};
</script>
