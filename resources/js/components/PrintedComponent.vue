<template>
  <v-app>
    <v-dialog v-model="jsonShowed" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Data JSON</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-treeview :items="items"></v-treeview>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" @click="jsonShowed = false">Kembali</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title primary-title>
        <div>
          <h3 class="headline mb-0">Daftar Print</h3>
        </div>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="prints.data"
        :hide-actions="true"
        :loading="loading"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td>{{ props.item.template.filename }}</td>
          <td>{{ props.item.created_at }}</td>
          <td>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="orange" @click="showJSON(props.item.json)" v-on="on">
                  <v-icon dark>code</v-icon>
                </v-btn>
              </template>
              <span>Tampilkan JSON</span>
            </v-tooltip>
            <a :href="baseurl+ props.item.url" target="_blank">
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn fab dark small color="success" v-on="on">
                    <v-icon dark>save_alt</v-icon>
                  </v-btn>
                </template>
                <span>Unduh Dokumen</span>
              </v-tooltip>
            </a>
          </td>
        </template>
      </v-data-table>
      <div class="text-xs-center pt-2">
        <v-pagination v-model="prints.current_page" @input="updatedPage" :length="length"></v-pagination>
      </div>
    </v-card>
  </v-app>
</template>

<script>
export default {
  name: "PrintedComponent",
  mounted() {
    this.list();
  },
  data() {
    return {
      totalPrints: 0,
      headers: [
        { text: "Nama Template", value: "template.filename" },
        { text: "Tanggal Print", value: "created_at" },
        { text: "Action", value: "id" }
      ],
      length: 0,
      prints: [],
      json: null,
      jsonShowed: false,
      baseurl: null,
      loading: false,
      currentPage: 1,
      items:[]
    };
  },
  watch: {
    pagination: {
      handler() {
        this.list().then(data => {
          console.log(data);
          this.prints = data.items;
          this.totalPrints = data.total;
        });
      },
      deep: true
    }
  },
  methods: {
    updatedPage(data) {
      this.currentPage = data;
      this.list();
    },
    showJSON(jsonn) {
      this.json = jsonn;
      var obj = JSON.parse(jsonn);
      var pretty = JSON.stringify(obj, undefined, 4);
      var sampleData = obj;
      var a = this.transformObject(sampleData, "root", true);
      //console.log(this.transformObject(sampleData, "root", true));
      this.items = [a];
      this.jsonShowed = true;
    },
    list() {
      var that = this;
      this.loading = true;
      axios
        .get("api/allPrinted?page=" + that.currentPage)
        .then(function(response) {
          that.prints = response.data.data.list;
          that.length = Math.ceil(
            response.data.data.list.total / response.data.data.list.per_page
          );
          that.baseurl = response.data.data.baseurl;
          that.loading = false;
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    // Transformer for the Object type
    transformObject: function(objectToTransform, keyForObject, isRootObject = false){
      return {
        name: keyForObject,
        type: "object",
        isRoot: isRootObject,
        children: this.generateChildrenFromCollection(objectToTransform)
      }
    },
    // Transformer for the non-Collection types, 
    // like String, Integer of Float
    transformValue: function(valueToTransform, keyForValue){
      return {
        name: keyForValue +':'+valueToTransform,
        key: keyForValue,
        type: "value",
        value: valueToTransform
      }
    },
    // Transformer for the Array type
    transformArray: function(arrayToTransform, keyForArray){
      return {
        name: keyForArray,
        type: "array",
        children: this.generateChildrenFromCollection(arrayToTransform)
      }
    },
    // Since we use lodash, the _.map method will work on
    // both Objects and Arrays, returning either the Key as
    // a string or the Index as an integer
    generateChildrenFromCollection: function(collection){
      return _.map(collection, (value, keyOrIndex)=>{
          if (this.isObject(value)) {
            return this.transformObject(value, keyOrIndex);
          }
          if (this.isArray(value)) {
            return this.transformArray(value, keyOrIndex);
          }
          if (this.isValue(value)) {
            return this.transformValue(value, keyOrIndex);
          }
        }) ;   
    },
    // Helper Methods for value type detection
    isObject: function(value){
      return _.isPlainObject(value);
    },
    
    isArray: function(value){
      return _.isArray(value);
    },
    
    isValue: function(value){
      return !this.isObject(value) && !this.isArray(value);
    }
  }
};
</script>
