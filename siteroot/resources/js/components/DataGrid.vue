<template>
<div>
<div class="row mb-2">
    <div class="col">
        <date-picker v-model="dateRange" value-type="format" format="YYYY-MM-DD" range placeholder="Select date range" :disabled-date="applyDataDateRange" :clearable="false"></date-picker>
    </div>
    <div class="col">
        <router-link :to="{ name: 'SalesForm' }" class="btn btn-danger">Add</router-link>
    </div>
</div>
<div class="row mb-2">
    <div class="col">
        <multiselect v-model="customers" :options="optionsCustomer" :multiple="true" placeholder="Select some customers" label="full_name" track-by="id" :disabled="loading"></multiselect>
    </div>
    <div class="col">
        <multiselect v-model="employees" :options="optionsEmployee" :multiple="true" placeholder="Select some sales person" label="name" track-by="id" :disabled="loading"></multiselect>
    </div>
</div>
<div class="row justify-content-center align-items-center">
    <table v-if="!loading" class="table table-striped">
        <thead>
          <tr>
            <th scope="col" v-for="key in columns" :key="key">
                {{key}}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sale in salesData" :key="sale.invoiceId">
            <td>
              {{sale.invoiceId}}
            </td>
            <td>
              {{sale.date}}
            </td>
            <td>
              {{sale.product.name}}
            </td>
            <td>
              {{sale.customer.full_name}}
            </td>
            <td>
              {{sale.employee.name}}
            </td>
          </tr>
        </tbody>
    </table>
    <div class="spinner-border mt-5" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
</div>
</div>  
</template>
<script>
import DatePicker from 'vue2-datepicker';
import common from './common.vue';
import 'vue2-datepicker/index.css';
import Multiselect from 'vue-multiselect'
export default {
    components: { DatePicker,common, Multiselect },
    created() {
        this.fetchDataDateRange = common.fetchDataDateRange
    },
    data() {
        return {
          dateRange: ['2020-06-01', '2020-06-30'],//date range to be used for chart.
          datePickerDateRange: [], //Date range for sales data in date picker element.
          columns: ['Invoice ID', 'date', 'product', 'customer', 'sales person'],
          salesData: [],
          optionsCustomer: [],
          optionsEmployee: [],
          customers: [],
          employees: [],
          formData: {
              start: '',
              end: '',
              customer: [],
              employee: [],
          },
          loading: false
        }
    },
    mounted() {
        this.fetchDataDateRange();
        this.fetchSalesData();
        this.filterOptions();
    },
    watch: {
      dateRange: function (val) {
          if (val[0] != null &&  val[1] != null) {
              this.fetchSalesData();
          }
      },
      customers: function (val) {
        this.fetchSalesData();
      },
      employees: function (val) {
        this.fetchSalesData();
      },
    },
    methods: {
        applyDataDateRange: function(date) {
          const start = new Date(this.datePickerDateRange[0])
          start.setHours(0, 0, 0, 0);
           return date < start || date > new Date(this.datePickerDateRange[1])
        },
        fetchSalesData: function() {
            this.loading = true;
            let c = this.customers.map(({id})=>id)
            let e = this.employees.map(({id})=>id)
            if (c.length > 0) {
                this.formData.customer = c
            } else {
                this.formData.customer = []
            }

            if (e.length > 0) {
                this.formData.employee = e
            } else {
                this.formData.employee = []
            }
            this.formData.start = this.dateRange[0]
            this.formData.end = this.dateRange[1]

            axios.post('api/sales/data', this.formData).then(response => {
                this.salesData = response.data
                this.loading = false;
            }).catch(error => console.log(error));
        },
        filterOptions: function() {
            axios.get('api/sales/filters').then(response => {
                this.optionsCustomer = response.data.clientOptions
                this.optionsEmployee = response.data.employeeOptions
            }).catch(error => console.log(error));
        }
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>