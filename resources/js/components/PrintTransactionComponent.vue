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
                <v-textarea v-model="json" readonly></v-textarea>
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
    <h3 class="headline mb-10 primary--text">Daftar Print Transaction</h3>
    <v-card>
      <v-data-table
        :headers="headers"
        :items="print_transactions.data"
        :hide-actions="true"
        :loading="loading"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td>{{ props.item.url }}</td>
          <td>{{ props.item.print_time }}</td>
          <td>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="orange" @click="showJSON(props.item.json)" v-on="on">
                  <v-icon dark>code</v-icon>
                </v-btn>
              </template>
              <span>Tampilkan JSON</span>
            </v-tooltip>
          </td>
          <td>
            <v-tooltip top v-if="props.item.status==1">
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="success" v-on="on">
                  <v-icon dark>done</v-icon>
                </v-btn>
              </template>
              <span>Sukses</span>
            </v-tooltip>
            <v-tooltip top v-else>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="error" v-on="on">
                  <v-icon dark>close</v-icon>
                </v-btn>
              </template>
              <span>Gagal</span>
            </v-tooltip>
          </td>
        </template>
      </v-data-table>
      <div class="text-xs-center pt-2">
        <v-pagination
          v-model="print_transactions.current_page"
          @input="updatedPage"
          :length="length"
        ></v-pagination>
      </div>
    </v-card>
  </v-app>
</template>

<script>
export default {
  name: "PrintTransactionComponent",
  mounted() {
    this.list();
  },
  data() {
    return {
      totalPrints: 0,
      headers: [
        { text: "Url", value: "url" },
        { text: "Tanggal Print", value: "print_time" },
        { text: "Action", value: "id" },
        { text: "Status" }
      ],
      length: 0,
      print_transactions: [],
      json: null,
      jsonShowed: false,
      baseurl: null,
      loading: false,
      currentPage: 1
    };
  },
  watch: {
    pagination: {
      handler() {
        this.list().then(data => {
          console.log(data);
          this.print_transactions = data.items;
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
      this.jsonShowed = true;
    },
    list() {
      var that = this;
      this.loading = true;
      axios
        .get("api/allPrintTransaction?page=" + that.currentPage)
        .then(function(response) {
          that.print_transactions = response.data.data;
          that.loading = false;
          that.length = Math.ceil(
            response.data.data.total / response.data.data.per_page
          );
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    }
  }
};
</script>
