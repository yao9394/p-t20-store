<template>
<div class="row justify-content-center align-items-center">
  <div class="jumbotron">
    <p v-if="!Loggedin">Welcome to acg portal, please log in</p>
    <p v-if="Loggedin">Welcome to acg portal</p>
      <div class="small" v-if="Loggedin">
        <date-picker v-model="dateRange" value-type="format" format="YYYY-MM-DD" range placeholder="Select date range" :disabled-date="applyDataDateRange"></date-picker>
        <sales-count-line-chart :date-range="dateRange"></sales-count-line-chart>
      </div>
  </div>
</div>
</template>
<script>
  import DatePicker from 'vue2-datepicker';
  import common from './common.vue';
  import 'vue2-datepicker/index.css';
    export default {
      components: { DatePicker,common },
      created() {
        this.fetchDataDateRange = common.fetchDataDateRange
      },
      mounted () {
        this.fetchDataDateRange();
      },
      computed: {
        Loggedin: function () {
            return common.Loggedin
        }
      },
      data() {
        return {
          dateRange: ['2020-06-01', '2020-06-30'],//date range to be used for chart.
          datePickerDateRange: []
        }
      },
      methods: {
        applyDataDateRange: function(date) {
          const start = new Date(this.datePickerDateRange[0])
          start.setHours(0, 0, 0, 0);
           return date < start || date > new Date(this.datePickerDateRange[1])
        },
      }
    }
</script>